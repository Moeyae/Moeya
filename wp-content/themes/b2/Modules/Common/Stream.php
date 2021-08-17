<?php namespace B2\Modules\Common;

use B2\Modules\Common\Post;
use B2\Modules\Common\user;

class Stream{
    public static function get_list($data){

        $current_user_id = get_current_user_id();

        $count = get_option('posts_per_page');

        $offset = ((int)$data['paged'] -1)*$count;

        if((int)$data['author']){
            $methods = 'b2_stream_author_post_type';
        }else{
            $methods = 'b2_stream_post_type';
        }

        $types = apply_filters($methods, array(
            'post','circle','document','newsflashes'
        ));

        if(!isset($data['post_types']) || !is_array($data['post_types']) || empty($data['post_types'])){
            $data['post_types'] = $types;
        }else{
            foreach ((array)$data['post_types'] as $v) {
                if(!in_array($v,$types)){
                    return array('error'=>__('错误的文章类型','b2'));
                }
            }
        }
        
        $args = array(
            'post_type'=>$data['post_types'],
            'posts_per_page' => $count,
            'orderby' => 'date',
            'offset'=>$offset,
            'post_status'=>'publish',
            'include_children' => true,
            'paged'=>(int)$data['paged']
        );

        if((int)$data['author']){
            $args['author'] = (int)$data['author'];
        }

        $the_query = new \WP_Query( $args );

        $post_data = array();
        $_pages = 1;
        $_count = 0;
        if ( $the_query->have_posts() ) {

            $_pages = $the_query->max_num_pages;
            $_count = $the_query->found_posts;

            while ( $the_query->have_posts() ) {

                $the_query->the_post();

                $post_data[] = self::get_item($the_query,$data,$current_user_id);
            }
            wp_reset_postdata();
        }

        if(isset($data['pages']) || $data['count']){
            return array(
                'data'=>$post_data,
                'pages'=>$_pages,
                'count'=>$_count
            );
        }

        return $post_data;

    }

    public static function get_item($the_query,$data,$current_user_id){
        
        $post_id = $the_query->post->ID;
        $post_author = $the_query->post->post_author;

        $post_type =  $the_query->post->post_type;

        $thumb_id = get_post_thumbnail_id($post_id);
        $thumb_url = wp_get_attachment_image_src($thumb_id,'full');

        $isset_up = PostRelationships::isset(array('type'=>'post_up','user_id'=>$current_user_id,'post_id'=>$post_id));
        $isset_down = PostRelationships::isset(array('type'=>'post_down','user_id'=>$current_user_id,'post_id'=>$post_id));

        $count_vote = Post::get_post_vote_up($post_id);

        $post_meta = Post::post_meta($post_id);

        $post_meta['lv'] = User::get_user_lv($post_author,true);

        $meta = array(
            'terms'=>self::get_term_data(array('post_id'=>$post_id,'post_type'=>$post_type)),
            'meta'=>$post_meta,
            'data'=>array(
                'up'=>$count_vote['up'],
                'down'=>$count_vote['down'],
                'up_isset'=>$isset_up,
                'down_isset'=>$isset_down
            )
        );

        if($post_type == 'newsflashes'){
            $meta['meta']['date'] = b2_newsflashes_date(get_the_date('Y-n-j G:i:s',$post_id));
        }

        $post_type = get_post_meta($post_id,'b2_single_post_style',true);

        $imgs = array();
        
        if($post_type === 'post-style-3'){
            $images = b2_get_images_from_content(get_the_content($post_id),'all');
            if(count($images) >= 4){
                foreach ($images as $k => $v) {
                    if($k <=3){
                        $t = b2_get_thumb(array('thumb'=>$v,'width'=>180,'height'=>105,'ratio'=>2));
                        $imgs[] = array(
                            'thumb'=>$t,
                            'thumb_webp'=>apply_filters('b2_thumb_webp',$t)
                        );
                    }
                }
            }
        }

        $thumb ='';

        if(isset($thumb_url[0]) && $thumb_url[0]){
            if($post_type != 'shop'){
                $thumb = b2_get_thumb(array('thumb'=>$thumb_url[0],'width'=>180,'height'=>105,'ratio'=>2));
            }else{
                $thumb = b2_get_thumb(array('thumb'=>$thumb_url[0],'width'=>180,'height'=>180,'ratio'=>2));
            }
        }

        return array(
            'id'=>$post_id,
            'title'=>array(
                'name'=>get_the_title($post_id),
                'link'=>get_permalink($post_id)
            ),
            'thumb'=>$thumb,
            'thumb_webp'=>apply_filters('b2_thumb_webp',$thumb),
            'images'=>$imgs,
            'desc'=>$data['post_type'] !== 'circle' ? b2_get_des($post_id,120) : '',
            'data'=>$meta
        );
    }

    public static function get_term_data($data){

        switch ($data['post_type']) {
            case 'post':
                $type = 'category';
                $name = __('文章','b2');
                break;
            case 'circle':
                $type = 'circle_tags';
                $name = __('圈子','b2');
                break;
            case 'document':
                $type = 'document_cat';
                $name = __('文档','b2');
                break;
            case 'newsflashes':
                $type = 'newsflashes_tags';
                $name = __('快讯','b2');
                break;
            case 'shop':
                $type = 'shoptype';
                $name = __('商品','b2');
                break;
            default:
                return array();
                break;
        }

        $terms = wp_get_object_terms( $data['post_id'], $type);

        $list = array();

        if ( ! empty( $terms ) ) {
            if ( ! is_wp_error( $terms ) ) {
  
                foreach( $terms as $k=>$term ) {
                    if($k <= 3){
                        $list[] = array(
                            'id'=>$term->term_id,
                            'name'=>esc_html( $term->name ),
                            'link'=>esc_url( get_term_link( $term->slug, $type ) )
                        );
                    }
                }

            }
        }

        return array(
            'post_type'=>array('type'=>$data['post_type'],'name'=>$name),
            'terms'=> $list
        );
    }
}