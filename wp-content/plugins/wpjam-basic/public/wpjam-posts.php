<?php
class WPJAM_Posts_Admin{
	public static function builtin_page_load($screen_base, $current_screen){
		if($screen_base == 'post'){
			if(wpjam_basic_get_setting('disable_block_editor')){
				add_filter('use_block_editor_for_post_type', '__return_false');
			}else{
				if(wpjam_basic_get_setting('disable_google_fonts_4_block_editor')){	// 古腾堡编辑器不加载 Google 字体
					wp_deregister_style('wp-editor-font');
					wp_register_style('wp-editor-font', '');
				}
			}

			// if(wpjam_basic_get_setting('disable_revision')){
			//	wp_deregister_script('autosave');
			// }

			if(wpjam_basic_get_setting('disable_trackbacks')){
				wp_add_inline_style('wpjam-style', "\n".'label[for="ping_status"]{display:none !important;}'."\n");
			}
		}elseif($screen_base == 'edit'){
			$post_type	= $current_screen->post_type;
			$pt_obj		= get_post_type_object($post_type);

			if($post_type == 'page'){
				wpjam_register_list_table_column('template', ['title'=>'模板', 'column_callback'=>'get_page_template_slug']);
			}

			if(wpjam_basic_get_setting('post_list_set_thumbnail', 1) && post_type_supports($post_type, 'thumbnail')){
				wpjam_register_list_table_action('set_thumbnail', [
					'title'			=> '设置',
					'page_title'	=> '设置特色图片',
					'fields'		=> ['_thumbnail_id'	=> ['title'=>'缩略图', 'type'=>'img', 'size'=>'600x0']],
					'callback'		=> ['WPJAM_Post', 'update_metas'],
					'row_action'	=> false,
					'width'			=> 500
				]);
			}

			if((is_post_type_viewable($post_type) && wpjam_basic_get_setting('post_list_update_views', 1)) || !empty($pt_obj->viewable)){
				wpjam_register_list_table_action('update_views', [
					'title'			=> '修改',
					'page_title'	=> '修改浏览数',
					'fields'		=> ['views'	=> ['title'=>'浏览数', 'type'=>'number']],
					'capability'	=> $pt_obj->cap->edit_others_posts,
					'callback'		=> ['WPJAM_Post', 'update_metas'],
					'row_action'	=> false,
					'width'			=> 500
				]);

				wpjam_register_list_table_column('views', ['title'=>'浏览', 'sortable_column'=>'views', 'column_callback'=>[self::class, 'get_views']]);
			}

			if(wpjam_basic_get_setting('post_list_author_filter', 1) && post_type_supports($post_type, 'author')){
				add_action('restrict_manage_posts', [self::class, 'add_users_dropdown'], 99);
			}

			if(wpjam_basic_get_setting('post_list_sort_selector', 1)){
				add_action('restrict_manage_posts', [self::class, 'add_orders_dropdown'], 99);
			}

			if(is_object_in_taxonomy($post_type, 'category')){
				add_filter('disable_categories_dropdown', '__return_true');
			}

			add_action('restrict_manage_posts',		[self::class, 'add_taxonomy_dropdown'], 1);

			add_filter('request',	[self::class, 'filter_request']);

			wp_add_inline_style('list-tables', "\n".implode("\n", [
				'td.column-title img.wp-post-image{float:left; margin:0px 10px 10px 0;}',
				'th.manage-column.column-views{width:72px;}',
				'th.manage-column.column-template{width:15%;}',
				'.fixed .column-date{width:98px;}',
				'.fixed .column-categories, .fixed .column-tags{width:12%;}'
			])."\n");
		}elseif($screen_base == 'upload'){
			add_action('restrict_manage_posts',	[self::class, 'add_users_dropdown'], 99);
			add_action('restrict_manage_posts',	[self::class, 'add_taxonomy_dropdown'], 1);

			add_filter('request',	[self::class, 'filter_request']);
		}
	}

	public static function get_views($post_id){
		$post_views	= wpjam_get_post_views($post_id, false) ?: 0;
		$pt_obj		= get_post_type_object(get_current_screen()->post_type);

		if(current_user_can($pt_obj->cap->edit_others_posts)){
			$post_views	= wpjam_get_list_table_row_action('update_views',	['id'=>$post_id,	'title'=>$post_views,]);
		}

		return $post_views;
	}

	public static function filter_request($query_vars){
		$tax_query	= [];

		foreach(get_object_taxonomies(get_current_screen()->post_type, 'objects') as $taxonomy=>$tax_obj){
			if(!$tax_obj->show_ui){
				continue;
			}

			$tax	= $taxonomy == 'post_tag' ? 'tag' : $taxonomy;

			if($tax != 'category'){
				if(!empty($_REQUEST[$tax.'_id'])){
					$query_vars[$tax.'_id']	= (int)$_REQUEST[$tax.'_id'];
				}
			}

			if(!empty($_REQUEST[$tax.'__and'])){
				$tax__and	= wp_parse_id_list($_REQUEST[$tax.'__and']);

				if(count($tax__and) == 1){
					if (!isset($_REQUEST[$tax.'__in'])){
						$_REQUEST[$tax.'__in']	= [];
					}

					$_REQUEST[$tax.'__in'][]	= absint(reset($tax__and));
				}else{
					$tax__and		= array_map('absint', array_unique($tax__and));
					$tax_query[]	= [
						'taxonomy'	=> $taxonomy,
						'terms'		=> $tax__and,
						'field'		=> 'term_id',
						'operator'	=> 'AND',
						// 'include_children'	=> false,
					];
				}
			}

			if(!empty($_REQUEST[$tax.'__in'])){
				$tax__in		= wp_parse_id_list($_REQUEST[$tax.'__in']);
				$tax__in		= array_map('absint', array_unique($tax__in));

				$tax_query[]	= [
					'taxonomy'	=> $taxonomy,
					'terms'		=> $tax__in,
					'field'		=> 'term_id'
				];
			}

			if(!empty($_REQUEST[$tax.'__not_in'])){
				$tax__not_in	= wp_parse_id_list($_REQUEST[$tax.'__not_in']);
				$tax__not_in	= array_map('absint', array_unique($tax__not_in));

				$tax_query[]	= [
					'taxonomy'	=> $taxonomy,
					'terms'		=> $tax__not_in,
					'field'		=> 'term_id',
					'operator'	=> 'NOT IN'
				];
			}
		}

		if($tax_query){
			$tax_query['relation']		= $_REQUEST['tax_query_relation'] ?? 'and'; 
			$query_vars['tax_query']	= $tax_query;
		}

		return $query_vars;
	}

	public static function add_taxonomy_dropdown($post_type){
		foreach(get_object_taxonomies($post_type, 'objects') as $taxonomy => $tax_obj){
			if(empty($tax_obj->show_admin_column)){
				continue;
			}

			$filterable	= $tax_obj->filterable;

			if($taxonomy == 'category' && is_null($filterable)){
				$filterable	= true;
			}

			if(empty($filterable)){
				return;
			}

			$query_var	= $tax_obj->query_var;
			$query_key	= wpjam_get_taxonomy_query_key($taxonomy);
			$selected	= '';

			if(!empty($_REQUEST[$query_key])){
				$selected	= $_REQUEST[$query_key];
			}elseif(!empty($query_var) && !empty($_REQUEST[$query_var])){
				if($term	= get_term_by('slug', $_REQUEST[$query_var], $taxonomy)){
					$selected	= $term->term_id;
				}
			}elseif(!empty($_REQUEST['taxonomy']) && ($_REQUEST['taxonomy'] == $taxonomy) && !empty($_REQUEST['term'])){
				if($term	= get_term_by('slug', $_REQUEST['term'], $taxonomy)){
					$selected	= $term->term_id;
				}
			}

			if($tax_obj->hierarchical){
				wp_dropdown_categories([
					'taxonomy'			=> $taxonomy,
					'show_option_all'	=> $tax_obj->labels->all_items,
					'show_option_none'	=> '没有设置',
					'name'				=> $query_key,
					'selected'			=> (int)$selected,
					'hierarchical'		=> true
				]);
			}else{
				echo wpjam_get_field_html([
					'key'			=> $query_key,
					'value'			=> $selected,
					'type'			=> 'text',
					'data_type'		=> 'taxonomy',
					'taxonomy'		=> $taxonomy,
					'placeholder'	=> '请输入'.$tax_obj->label,
					'title'			=> '',
					'class'			=> ''
				]);
			}
		}
	}

	public static function add_users_dropdown($post_type){
		$author		= (int)wpjam_get_data_parameter('author');
		$option_all	= $post_type == 'attachment' ? '所有上传者' : '所有作者';

		wp_dropdown_users(['name'=>'author',	'who'=>'authors',	'hide_if_only_one_author'=>true,	'show_option_all'=> $option_all,	'selected'=>$author]);
	}

	public static function add_orders_dropdown($post_type){
		$wp_list_table		= $GLOBALS['wp_list_table'] ?: _get_list_table('WP_Posts_List_Table', ['screen'=>get_current_screen()->id]);

		list($columns, $hidden, $sortable_columns, $primary)	= $wp_list_table->get_column_info();

		$options	= [''=>'排序','ID'=>'ID'];

		foreach($sortable_columns as $sortable_column => $data){
			if(isset($columns[$sortable_column])){
				$options[$data[0]]	= $columns[$sortable_column];
			}
		}

		$options	= array_merge($options, ['modified'=>'修改时间']);
		$orderby	= wpjam_get_data_parameter('orderby',	['sanitize_callback'=>'sanitize_key']);
		$order		= wpjam_get_data_parameter('order',		['sanitize_callback'=>'sanitize_key', 'default'=>'DESC']);

		echo wpjam_get_field_html(['key'=>'orderby',	'type'=>'select',	'value'=>$orderby,	'options'=> $options]);
		echo wpjam_get_field_html(['key'=>'order',		'type'=>'select',	'value'=>$order,	'options'=> ['desc'=>'降序','asc'=>'升序']]);
	}
}