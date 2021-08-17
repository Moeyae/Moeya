<?php namespace B2\Modules\Common;

use B2\Modules\Common\Post;
use B2\Modules\Templates\Single;

class Newsflashes{ 

    public function init(){
        add_action( 'init', array($this,'create_news' ));
    }

    public function create_news(){

        $arr = array(
            'name'              => __( '快讯标签', 'b2' ),
            'singular_name'     => __( '快讯标签', 'b2' ),
            'search_items'      => __( '搜索快讯标签', 'b2' ),
            'all_items'         => __( '所有快讯标签', 'b2' ),
            'parent_item'       => __( '父级快讯标签', 'b2' ),
            'parent_item_colon' => __( '父级快讯标签', 'b2' ),
            'edit_item'         => __( '编辑快讯标签', 'b2' ),
            'update_item'       => __( '更新快讯标签', 'b2' ),
            'add_new_item'      => __( '添加快讯标签', 'b2' ),
            'new_item_name'     => __( '快讯标签名称', 'b2' ),
            'menu_name'         => __( '快讯标签', 'b2' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $arr,
            'public'=>true,
            'update_count_callback'=>'_update_post_term_count',
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest' => true,
            'rewrite'           => array( 'slug' => 'newsflashes', 'with_front' => false ),
        );
    
        register_taxonomy( 'newsflashes_tags', array( 'newsflashes_tags' ), $args );

        $newsflashes = array(
            'name' => __('快讯','b2'),
            'singular_name' => __('快讯','b2'),
            'add_new' => __('添加一个快讯','b2'),
            'add_new_item' => __('添加一个快讯','b2'),
            'edit_item' => __('编辑快讯','b2'),
            'new_item' => __('新的快讯','b2'),
            'all_items' => __('所有快讯','b2'),
            'view_item' => __('查看快讯','b2'),
            'search_items' => __('搜索快讯','b2'),
            'not_found' =>  __('没有快讯','b2'),
            'not_found_in_trash' =>__('回收站为空','b2'),
            'menu_name' => __('快讯','b2'),
        );
        register_post_type( 'newsflashes', 
            array(
                'labels' => $newsflashes,
                'has_archive' => true,
                'public'=>true,
                'menu_position'=>28,
                'menu_icon'=>'dashicons-media-spreadsheet',
                'taxonomies' => array('newsflashes_tags'),
                'exclude_from_search' => false,
                'capability_type' => 'page',
                'capabilities' => array(
                    'create_posts' => false,
                ),
                'supports' => array(
                    'title',
                    'comments',
                    'editor',
                    'thumbnail'
                ),
                'map_meta_cap' => true,
                'yarpp_support' => true,
                'rewrite' => array( 'slug' => 'newsflashes' ,'with_front' => false),
            )
        );
    }

    public static function submit_newsflashes($data){
        $user_id = get_current_user_id();

        if(!$user_id) return array('error'=>__('请先登录','b2'));

        //检查是否有权限
        $role = User::check_user_role($user_id,'newsflashes');

        if(!$role && !user_can( $user_id, 'manage_options' ) && !user_can( $user_id, 'editor' )) return array('error'=>__('您没有权限发布快讯','b2'));

        if(!user_can( $user_id, 'manage_options' ) && !user_can( $user_id, 'editor' ) && $data['type'] === 'publish'){
            //检查是否有草稿
            $args=array(
                'post_type' => 'newsflashes',
                'post_status' => 'pending',
                'posts_per_page' => 3,
                'author' => $user_id
            );

            $posts = get_posts($args);
            if(count($posts) > $post_count){
                return array('error'=>__('您还有未审核的快讯，请审核完后再提交','b2'));
            }
        }

        //检查文章内容
        if(!isset($data['content']) || !$data['content']){
            return array('error'=>__('内容不可为空','b2'));
        }

        $censor = apply_filters('b2_text_censor', $data['content']);
        if(isset($censor['error'])) return $censor;

        //检查文章内容
        if(!isset($data['title']) || !$data['title']){
            return array('error'=>__('标题不可为空','b2'));
        }
        
        if(user_can( $user_id, 'manage_options' ) || user_can( $user_id, 'editor' )){
            $data['type'] = 'publish';
        }else{
            $data['type'] = 'pending';

            $allow = b2_get_option('newsflashes_main','newsflashes_can_post');

            //是否直接发布
            if(!empty($allow) && (in_array(get_user_meta($user_id,'zrz_lv',true),$allow) || in_array(get_user_meta($user_id,'zrz_vip',true),$allow))){
                $data['type'] = 'publish';
            }
        }

        //检查标签是否存在
        $tags = b2_get_option('newsflashes_main','newsflashes_tags');
        if($tags){
            $tags = explode(',',$tags);
            if(!in_array($data['tag'],$tags)){
                return array('error'=>__('标签不存在','b2'));
            }
        }

        $data['tag'] = str_replace(array('{{','}}'),'',$data['tag']);

        $term = get_term_by('name', $data['tag'], 'newsflashes_tags');

        if(!$term){
            $resout = wp_insert_term(
            $data['tag'],
                'newsflashes_tags',
                array(
                    'slug' => $data['tag'],
                )
            );

            if(is_wp_error( $resout )){
                return array('error'=>$resout->error_data);
            }
            $topic = $resout['term_id'];
        }else{
            $topic = $term->term_id;
        }

        $content = str_replace(array('{{','}}'),'',wp_strip_all_tags($data['content']));

        $data['title'] = str_replace(array('{{','}}'),'',$data['title']);

        //提交
        $arg = array(
            'post_type'=>'newsflashes',
            'post_title' => $data['title'],
            'post_content' => $content,
            'post_status' => $data['type'],
            'post_author' => $user_id,
        );

        $post_id = wp_insert_post( $arg );
        
        if($post_id){

            if(isset($data['from']) && $data['from']){
                $data['from'] = esc_url(wp_strip_all_tags($data['from']));

                update_post_meta($post_id,'b2_newsflashes_from',$data['from']);
            }

            //设置话题
            wp_set_post_terms($post_id,array($topic),'newsflashes_tags');

            //设置特色图
            if(isset($data['img']['id']) && $data['img']['id']){
                set_post_thumbnail($post_id,$data['img']['id']);
            }

            do_action('b2_submit_newsflashes',$data,$post_id);
            
            //设置自定义字段
            if(!empty($data['custom'])){
                $custom_arr = array();
                foreach($data['custom'] as $k => $v){
                    $k = esc_attr(strip_tags($k));
                    if($v){
                        if(is_array($v)){
                            $i = 0;
                            foreach ($v as $_k => $_v) {
                                $v[$i] = esc_attr(strip_tags($_v));
                                $i++;
                            }
                        }else{
                            $v = esc_attr(strip_tags($v));
                        }
                        $custom_arr[] = $k;
                        update_post_meta($post_id,$k,$v);
                    }
                }

                update_post_meta($post_id,'b2_custom_key',$custom_arr);
            }

            //图片挂载到当前文章
            wp_update_post(
                array(
                    'ID' => $data['img']['id'], 
                    'post_parent' => $post_id
                )
            );
    
            return get_author_posts_url($user_id).'/post';
        }

        unset($data);

        return array('error'=>__('发布失败','b2'));
    }

    public static function get_newsflashes_item_data($post_id){

        $user_id = get_current_user_id();

        $from = get_post_meta($post_id,'b2_newsflashes_from',true);

        $author = get_post_field('post_author',$post_id);

        $img = Post::get_post_thumb($post_id);

        $tax = get_the_terms($post_id, 'newsflashes_tags');

        $tag = array();

        if($tax){
            $tax = $tax[0];
            $link = get_term_link($tax->term_id);
            $tag = array(
                'id'=>$tax->term_id,
                'name'=>$tax->name,
                'link'=>esc_url($link)
            );
        }

        unset($tax);

        $vote = Post::get_post_vote_up($post_id);

        $vote['up_isset'] = PostRelationships::isset(array('type'=>'post_up','user_id'=>$user_id,'post_id'=>$post_id));
        $vote['down_isset'] = PostRelationships::isset(array('type'=>'post_down','user_id'=>$user_id,'post_id'=>$post_id));
        $vote['up_text'] = b2_get_option('newsflashes_main','newsflashes_vote_up_text');
        $vote['down_text'] = b2_get_option('newsflashes_main','newsflashes_vote_down_text');

        $thumb = $img ? b2_get_thumb(array('thumb'=>$img,'width'=>720,'height'=>480)) : '';

        $date = get_the_date('Y-n-j G:i:s',$post_id);
        return array(
            'id'=>$post_id,
            'title'=>get_the_title($post_id),
            'link'=>esc_url(get_permalink($post_id)),
            '_date'=>Post::time_ago($date),
            'date'=>b2_newsflashes_date($date),
            'content'=>sanitize_textarea_field(get_post_field('post_content',$post_id)),
            'author'=>User::get_user_public_data($author,true),
            'from'=>$from,
            'img'=>$thumb,
            'img_webp'=>$thumb ? apply_filters('b2_thumb_webp',$thumb) : '',
            'tag'=>$tag,
            'vote'=>$vote,
            'comment_count'=>b2_number_format(get_comments_number($post_id)),
            'share'=>Single::get_share_links(false,$post_id)
        );
    }

    public static function get_newsflashes_data($paged,$term_id = 0,$user_id = 0,$s = '',$count = 0){

        if(!$count){
            $count = b2_get_option('newsflashes_main','newsflashes_show_count');
        }
        
        $offset = ($paged -1)*$count;
        
        $_pages = 0;

        $args = array(
            'post_type' => 'newsflashes',
            'orderby'  => 'date',
            'order'=>'DESC',
            'post_status'=>'publish',
            'posts_per_page'=>$count,
            'offset'=>$offset,
            'paged'=>$paged,
        );

        if($s){
            $args['search_tax_query'] = true;
            $args['s'] = esc_attr($s);
        }

        if($term_id){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'newsflashes_tags',
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                )
            );
        }
        if($user_id){
            $args['author__in'] = $user_id;
        }
        
        $news_the_query = new \WP_Query( $args );

        $group = array();
        $index = 0;
        $key = '';
        if ( $news_the_query->have_posts()) {
            $_pages = $news_the_query->max_num_pages;
            while ( $news_the_query->have_posts() ) {
                $news_the_query->the_post();
                $data = self::get_newsflashes_item_data($news_the_query->post->ID);
                $data['paged'] = $paged;
                if(!$key){
                    $key = $data['date']['key'];
                }elseif($key !== $data['date']['key']){
                    $index++;
                }

                $group[$paged.$index][] = $data;

                $key = $data['date']['key'];
            }
        }
        wp_reset_postdata();
        unset($data);

        return array(
            'data'=>$group,
            'pages'=>$_pages
        );
    }

    //快讯小工具数据
    public static function get_widget_Newsflashes($options){
        $options['post_type'] = 'newsflashes';
        $options['no_found_rows'] = true;
        $options['posts_per_page'] = $options['posts_per_page'] > 30 ? 6 : $options['posts_per_page'];
        $news_the_query = new \WP_Query( $options );

        $group = array();

        if ( $news_the_query->have_posts()) {
            while ( $news_the_query->have_posts() ) {
                $news_the_query->the_post();
                $data = self::get_newsflashes_item_data($news_the_query->post->ID);
 
                $group[] = $data;

            }
            
            unset($data);
        }

        wp_reset_postdata();
        unset($news_the_query);

        return $group;
    }

}