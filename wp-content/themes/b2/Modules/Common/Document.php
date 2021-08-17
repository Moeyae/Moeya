<?php namespace B2\Modules\Common;

class Document{ 

    public function init(){
        add_action( 'init', array($this,'create_document' ),10,0);
    }

    public function create_document(){

        $arr = array(
            'name'              => __( '文档分类', 'b2' ),
            'singular_name'     => __( '文档分类', 'b2' ),
            'search_items'      => __( '搜索文档分类', 'b2' ),
            'all_items'         => __( '所有文档分类', 'b2' ),
            'parent_item'       => __( '父级文档分类', 'b2' ),
            'parent_item_colon' => __( '父级文档分类', 'b2' ),
            'edit_item'         => __( '编辑文档分类', 'b2' ),
            'update_item'       => __( '更新文档分类', 'b2' ),
            'add_new_item'      => __( '添加文档分类', 'b2' ),
            'new_item_name'     => __( '文档分类名称', 'b2' ),
            'menu_name'         => __( '文档分类', 'b2' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $arr,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'=>true,
            'show_in_rest' => true,
            'update_count_callback'=>'_update_post_term_count',
            'rewrite'           => array( 'slug' => 'document', 'with_front' => false ),
        );
    
        register_taxonomy( 'document_cat', array( 'document_cat' ), $args );

        $document = array(
            'name' => __('文档中心','b2'),
            'singular_name' => __('文档','b2'),
            'add_new' => __('添加一个文档','b2'),
            'add_new_item' => __('添加一个文档','b2'),
            'edit_item' => __('编辑文档','b2'),
            'new_item' => __('新的文档','b2'),
            'all_items' => __('所有文档','b2'),
            'view_item' => __('查看文档','b2'),
            'search_items' => __('搜索文档','b2'),
            'not_found' =>  __('没有文档','b2'),
            'not_found_in_trash' =>__('回收站为空','b2'),
            'menu_name' => __('文档','b2'),
        );
        register_post_type( 'document', 
            array(
                'labels' => $document,
                'has_archive' => true,
                'public' => true,
                'show_in_rest' => true,
                'menu_icon'=>'dashicons-editor-paste-word',
                'rewrite' => array('slug' => 'document', 'with_front' => false),
                'taxonomies' => array('document_cat','post_tag'),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt','comments' ),
                'capability_type' => 'page',
                'yarpp_support' => true
            )
        );
    }

    public static function document_breadcrumb($post_id = 0){
        $home = B2_HOME_URI;
        $shop = get_post_type_archive_link('document');
        $tax = '';

        $tax = get_the_terms($post_id, 'document_cat');
        $tax_links = '';
        $post_link = '';

        if($tax && $post_id){
            $tax = get_term($tax[0]->term_id, 'document_cat' );

            $term_id = $tax->term_id;

            unset($tax);
        }else{
            $term = get_queried_object();
            $term_id = isset($term->term_id) ? $term->term_id : 0;

            unset($term);
        }

        if($term_id){
            $tax_links = get_term_parents_list($term_id,'document_cat');
            $tax_links = str_replace('>/<','><span>></span><',$tax_links);
            $tax_links = rtrim($tax_links,'/');
        }else{
            if(isset($_GET['s'])){
                $tax_links = __('搜索','b2');
            }else{
                $tax_links = __('工单中心','b2');
            }
        }

        if($post_id){
            $post_link = '<span>></span>'.get_the_title($post_id);
        }

        return '<a href="'.B2_HOME_URI.'">'.__('首页','b2').'</a><span>></span>'.'<a href="'.$shop.'">'.b2_get_option('document_main','document_name').'</a><span>></span>'.$tax_links.$post_link;
    }

    public static function submit_request($data){

        $current_user_id = get_current_user_id();

        if(!$current_user_id) return array('error'=>__('请先登录','b2'));

        $public_count = apply_filters('b2_check_repo_before',$current_user_id);
        if(isset($public_count['error'])) return $public_count;

        $censor = apply_filters('b2_text_censor', $data['content'].$data['title']);
        if(isset($censor['error'])) return $censor;

        $data['content'] = str_replace(array('{{','}}'),'',$data['content']);

        $content = sanitize_textarea_field($data['content']);

        if(!$content) return array('error'=>__('请填写工单内容','b2'));

        $data['title'] = str_replace(array('{{','}}'),'',$data['title']);

        $title = sanitize_text_field($data['title']);
        
        if(!$title) return array('error'=>__('请填写标题','b2'));

        if(!is_email($data['email'])){
            return array('error'=>__('请填写正确的邮箱地址','b2'));
        }

        $image = '';
        if((int)$data['image']){
            $img_data = wp_get_attachment_url($data['image']);
            if($img_data){
                $image = '<a href="'.$img_data.'" target="_blank"><img src="'.$img_data.'" /></a>';
            }
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_directmessage';

        $mark = '0+'.$current_user_id;

        //检查是否有未回复的工单
        $res = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE `mark`=%s order by id desc limit 1",$mark),
            ARRAY_A
        );

        if($res && isset($res[0]['to']) && $res[0]['to'] === '0'){
            return array('error'=>__('您有未处理的工单，请处理完毕后再提交！','b2'));
        }

        $res = $wpdb->insert($table_name, array(
            'mark'=>$mark,
            'from'=> (int)$current_user_id,
            'to'=> 0,
            'date'=> current_time('mysql'),
            'status'=> 0,
            'content'=> $content.$image,
            'key'=>$data['email'],
            'value'=>$title
        ));

        do_action('b2_submit_request',$data);

        if($res){
            apply_filters('b2_check_repo_after',$current_user_id,$public_count);

            return true;
        }

        return false;
    }
}