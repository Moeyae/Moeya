<?php namespace B2\Modules\Templates\PostType;
use B2\Modules\Templates\Modules\Sliders;
class Announcement{
    public function init(){
        //创建公告文章形式
        add_action( 'init', array($this,'create_announcement' ),10,0);

        //重写公告连接样式
        add_filter('post_type_link', array($this,'announcement_permalink'), 1, 2);

        //如果是文章内页，添加查看全部公告按钮
        add_action('b2_single_content_before',array($this,'announcement_show_more'),3);

        //如果发布公告，清除顶部html缓存
    }

    /**
     * 创建公告文章形式
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function create_announcement(){
        $announcement = array(
            'name' => __('公告','b2'),
            'singular_name' => __('公告','b2'),
            'add_new' => __('添加一个公告','b2'),
            'add_new_item' => __('添加一个公告','b2'),
            'edit_item' => __('编辑公告','b2'),
            'new_item' => __('新的公告','b2'),
            'all_items' => __('所有公告','b2'),
            'view_item' => __('查看公告','b2'),
            'search_items' => __('搜索公告','b2'),
            'not_found' =>  __('没有公告','b2'),
            'not_found_in_trash' =>__('回收站为空','b2'),
            'menu_name' => __('公告','b2'),
        );
        register_post_type( 'announcement', 
            array(
                'labels' => $announcement,
                'has_archive' => true,
                'public' => true,
                'rewrite' => array(
                    'slug' => 'announcement',
                    'with_front' => true
                ),
                'menu_icon'=>'dashicons-controls-volumeon',
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt','comments' ),
                'capability_type' => 'page',
                'rewrite' => array( 'slug' => 'announcement' ,'with_front' => false),
            )
        );

        add_rewrite_rule(
            'announcement/([0-9]+).html',
            'index.php?post_type=announcement&p=$matches[1]',
            'top' 
        );

    }

    /**
     * 重写公告连接
     *
     * @param string $link
     * @param object $post
     *
     * @return string
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function announcement_permalink($link, $post){
        if (isset($post->post_type) && $post->post_type == 'announcement' ){
            return home_url( 'announcement/' . $post->ID .'.html');
        } else {
            return $link;
        }
    }  

    /**
     * 获取公告数据
     *
     * @param array $arg
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function get_announcements($arg){

        $the_query = new \WP_Query($arg);

        $html = '';
        $_pages = 0;
        $titles = array();

        if ( $the_query->have_posts() ) {

            $_pages = $the_query->max_num_pages;

            ob_start();

            while ( $the_query->have_posts() ) {
                $the_query->the_post();

                //只获取标题
                if(isset($arg['announcement_type']) && $arg['announcement_type'] == 'title'){

                    $titles[] = array(
                        'title' => get_the_title(),
                        'link' => get_permalink(),
                        'date' => b2_timeago(get_the_date('Y-m-d G:i:s'))
                    );
        
                }else{
                    get_template_part( 'TempParts/Announcement/content');
                }
                
            }
            
            $html = ob_get_clean();
            
        }
        wp_reset_postdata();
        return array(
            'html'=>$html,
            'pages'=>$_pages,
            'titles'=>$titles
        );
    }

    /**
     * 文章内页公告导航
     *
     * @return string
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function announcement_show_more(){
        if(get_post_type() ==  'announcement')
        echo '
            <div class="announcement-show-more b2-pd box b2-radius">
                <span>'.__('公告','b2').'</span>
                <span><a href="'.b2_get_custom_page_url('announcements').'">'.__('所有公告','b2').'</a></span>
            </div>
        ';
    }
}