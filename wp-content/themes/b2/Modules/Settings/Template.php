<?php
namespace B2\Modules\Settings;

class Template{

    //默认设置项
    public static $default_settings = array(
        'wrapper_width'=>'1100',
        'sidebar_width'=>'300',
        'web_color'=>'#0066ff',
        'bg_color'=>'#f7f9fa',
        'button_radius'=>'4px',
        'top_type'=>'social-top',
        'top_width'=>'',
        'top_scroll_pc'=>1,
        'top_scroll_mobile'=>1,
        'count_link'=>0,
        'close_theme_menu_custom'=>1,
        'show_sidebar'=>1,
        'sidebar_width'=>300,
        'prettify_load'=>1,
        'bg_image_repeat'=>0,
        //是否显示顶部公告
        'gg_bg_show'=>0,
        'gg_bg_color'=>'#ffffff',
        'gg_text_color'=>'#121212',
        'default_video_poster'=>'',
        'default_video_logo'=>'',
        //底部设置
        'footer_color'=>'#ffffff',
        'footer_text_color'=>'#121212',
        'footer_nav_color'=>'#ffffff',
        'footer_nav_text_color'=>'#121212',
        'footer_beian'=>'',
        'footer_gongan'=>'',
        'footer_mobile_show_links'=>0,
        'footer_menu_open'=>1,
        'footer_menu_left'=>'',
        'footer_menu_right'=>'',
        //打赏
        'single_post_ds_open'=>1,
        'single_post_ds_text'=>'',
        'single_post_ds_none_text'=>'',
        'single_post_ds_money'=>'2|5|10|20|50',
        //海报
        'single_poster_default_img'=>'',
        'single_poster_default_logo'=>'',
        'single_poster_default_text'=>'',
        //评论
        'comment_close'=>1,
        'comment_use_image'=>1,
        'comment_use_smiles'=>1,
        'nav_type'=>'p',
        'comment_tips'=>'提示标题|http://#',
        //专题页面
        'collection_open_cover'=>0,
        'collection_title'=>'专题',
        'collection_desc'=>'实时热点，深锐观察',
        'collection_image'=>'',
        'collection_order'=>'DESC',
        'collection_orderby'=>'number',
        'collection_number'=>18,
        //右侧工具条
        'aside_show'=>1,
        'aside_user'=>1,
        'aside_mission'=>1,
        'aside_message'=>1,
        'aside_dmsg'=>1,
        'aside_vip'=>1
    );

    public function init(){

        add_action('cmb2_admin_init',array($this,'template_options_page'));

        //add_action('cmb2_admin_init',array($this,'bulid_index_widget'),99999);
    }

    public function bulid_index_widget(){
        if(isset($_POST['index_group']) && isset($_GET['page']) &&  $_GET['page'] == 'b2_template_index'){
            if(is_array($_POST['index_group'])){

                $list = array();

                foreach ($_POST['index_group'] as $k => $v) {
                    if((int)$v['show_widget'] === 1){
                        $list[$k] = array(
                            'key'=>$v['key'],
                            'name'=>$v['title']
                        );
                        
                    }
                }

                update_option('b2_index_widget',$list);
            }
        }
    }

    /**
     * 默认变量
     *
     * @param string $key 数组键值
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function get_default_settings($key){
        $arr = apply_filters('b2_admin_index_modules_default_settings', array(
            'default_video_poster'=>B2_THEME_URI.'/Assets/fontend/images/xg-poster-default.jpg',
            'default_video_logo'=>b2_get_option('normal_main','img_logo_white'),
            'footer_beian'=>get_option('zh_cn_l10n_icp_num'),
            'single_post_ds_text'=>__('点点赞赏，手留余香','b2'),
            'single_post_ds_none_text'=>__('还没有人赞赏，快来当第一个赞赏的人吧！','b2'),
            'single_poster_default_img'=>B2_THEME_URI.'/Assets/fontend/images/xg-poster-default.jpg',
            'single_poster_default_logo'=>B2_THEME_URI.'/Assets/fontend/images/poster-logo.png',
            'single_poster_default_text'=>get_bloginfo('name'),
            'single_poster_default_desc'=>get_bloginfo('description'),
            'footer_menu_left'=>'
            <i class="b2font b2-home-heart-line"></i>|首页|'.home_url().PHP_EOL.'<i class="b2font b2-bookmark-3-line"></i>|专题|'.b2_get_custom_page_url('collection').PHP_EOL.'<i class="b2font b2-shield-user-line"></i>|'.__('认证','b2').'|'.b2_get_custom_page_url('verify'),
            'footer_menu_right'=>'search'.PHP_EOL.'menu'.PHP_EOL.'login',
            'bg_image'=>'',
            'footer_img'=>'',
            'gg_bg_image'=>'',
            'single_poster_dl'=>0,
            'user_menus'=>array('directmessage','gold','distribution','task','vip','certification','orders','settings','dark_room')
        ));

        if(isset($arr[$key])){
            return $arr[$key];
        }
    }

    /**
     * 常规设置
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function template_options_page(){

        //常规设置
        $this->normal_settings();
        
        //顶部设置
        $this->top_settings();

        //首页设置
        $this->index_settings();
    }

    public function normal_settings(){
        $template = new_cmb2_box( array(
            'id'           => 'b2_template_main_options_page',
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_main',
            'tab_group'    => 'b2_template_options',
            'parent_slug'     => 'b2_main_options',
            'tab_title'    => __('综合','b2'),
            'menu_title'   => __('模块管理','b2'),
        ) );

        $template->add_field(array(
            'name'    => __( '页面宽度', 'b2' ),
            'desc'    => sprintf(__( '请直接填写宽度数字，默认%s，不支持百分比。', 'b2' ),'<code>1200</code>'),
            'id'=>'wrapper_width',
            'type'=>'text',
            'default'=>self::$default_settings['wrapper_width']
        ));

        //侧边栏宽度
        $template->add_field(array(
            'name'    => __( '侧边栏宽度', 'b2' ),
            'desc'    => sprintf(__( '请填写具体的数值，默认%s', 'b2' ),'<code>300</code>'),
            'id'=>'sidebar_width',
            'type'             => 'text',
            'default'          => self::$default_settings['sidebar_width'],
        ));

        $template->add_field(array(
            'name'    => __( '网站主色调', 'b2' ),
            'desc'    => __( '显示在按钮、链接等需要突出显示的地方。', 'b2' ),
            'id'=>'web_color',
            'type'=>'colorpicker',
            'default'=>self::$default_settings['web_color']
        ));

        $template->add_field(array(
            'name'    => __( '网站背景颜色', 'b2' ),
            'id'=>'bg_color',
            'type'=>'colorpicker',
            'default'=>self::$default_settings['bg_color']
        ));

        $template->add_field(array(
            'name'    => __( '网站全局背景图片', 'b2' ),
            'id'=>'bg_image',
            'type'=>'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=> __('不显示请留空','b2'),
            'default'=>self::get_default_settings('bg_image')
        ));
        $template->add_field(array(
            'name'    => __( '网站全局背景图片是否重复', 'b2' ),
            'id'=>'bg_image_repeat',
            'type'=>'select',
            'options' => array(
                1 => __('背景图片重复显示','b2'),
                0=>__('背景图片不重复显示','b2')
            ),
            'default'=>self::$default_settings['bg_image_repeat']
        ));

        $template->add_field(array(
            'name'    => __( '圆角弧度', 'b2' ),
            'desc'    => sprintf(__( '全站生效的圆角弧度，默认%s，请带上单位%s', 'b2' ),'<code>2px</code>','<code>px</code>'),
            'id'=>'button_radius',
            'type'=>'text',
            'default'=>self::$default_settings['button_radius']
        ));

        $template->add_field(array(
            'name'    => __( '是否开启代码高亮功能', 'b2' ),
            'desc'    => __( '此设置全站生效，当代码被&lt;pre&gt;标签包裹起来之后，会自动启用代码高亮', 'b2' ),
            'id'=>'prettify_load',
            'type'=>'radio_inline',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['prettify_load']
        ));
    }

    public function top_settings(){
        //页面顶部
        $top = new_cmb2_box(array(
            'id'           => 'b2_template_top_options_page',
            'tab_title'    => __('顶部','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_top',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options'
        ));

        $top->add_field(array(
            'name'    => __( '顶部背景颜色', 'b2' ),
            'id'=>'gg_bg_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['gg_bg_color'],
        ));

        
        $top->add_field(array(
            'name'    => __( '顶部背景图片', 'b2' ),
            'id'=>'gg_bg_image',
            'type'=>'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=> __('不显示请留空','b2'),
            'default'=>self::get_default_settings('gg_bg_image')
        ));


        $top->add_field(array(
            'name'    => __( '顶部文字颜色', 'b2' ),
            'id'=>'gg_text_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['gg_text_color'],
        ));

        $top->add_field(array(
            'name'    => __( '顶部布局形式', 'b2' ),
            'id'=>'top_type',
            'type' => 'radio_image',
            'options'          => array(
                'logo-center'    => __('LOGO居中','b2'), 
                'logo-left'  => __('LOGO居左','b2'), 
                'menu-center' => __('菜单居中','b2'), 
                'logo-top' => __('LOGO居上','b2'),
                'social-top'=>__('社交类型菜单','b2')
            ),
            'images_path'      => B2_THEME_URI,
            'images'           => array(
                'logo-center'    => '/Assets/admin/images/top-1.svg',
                'logo-left'  => '/Assets/admin/images/top-2.svg',
                'menu-center' => '/Assets/admin/images/top-3.svg',
                'logo-top' => '/Assets/admin/images/top-4.svg',
                'social-top' => '/Assets/admin/images/top-5.svg'
            ),
            'default'=>self::$default_settings['top_type']
        ));
        
        $top->add_field(array(
            'name'    => __( '页面顶部模块宽度', 'b2' ),
            'desc'    => sprintf(__( '可以使用%s、%s等符号，使用默认值请留空。', 'b2' ),'<code>px</code>','<code>%</code>'),
            'id'=>'top_width',
            'type'=>'text',
            'default'=>self::$default_settings['top_width']
        ));

        // $top->add_field(array(
        //     'name'    => __( 'PC端顶部固定方式', 'b2' ),
        //     'id'=>'top_scroll_pc',
        //     'type'=>'radio',
        //     'options'=>array(
        //         1=>__('页面上滑显示','b2'),
        //         2=>__('始终显示','b2'),
        //         3=>__('始终关闭','b2'),
        //     ),
        //     'default'=>self::$default_settings['top_scroll_pc']
        // ));

        // $top->add_field(array(
        //     'name'    => __( '移动端端顶部固定方式', 'b2' ),
        //     'id'=>'top_scroll_mobile',
        //     'type'=>'radio',
        //     'options'=>array(
        //         1=>__('页面上滑显示','b2'),
        //         2=>__('始终显示','b2'),
        //         3=>__('始终关闭','b2'),
        //     ),
        //     'default'=>self::$default_settings['top_scroll_mobile']
        // ));

        $top->add_field(array(
            'name'    => __( '主题自带菜单功能', 'b2' ),
            'desc'    => __( '如果您使用了菜单插件或者需要自己DIY顶部菜单代码而出现了兼容问题，请选择关闭主题自带的菜单功能。', 'b2' ),
            'id'=>'close_theme_menu_custom',
            'type'             => 'select',
            'default'          => self::$default_settings['close_theme_menu_custom'],
            'options'          => array(
                1 => __( '开启', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ));

        $top->add_field(array(
            'name'    => __( '点击头像要显示的菜单', 'b2' ),
            'desc'    => __( '用户登陆状态下，点击头像要显示的菜单。', 'b2' ),
            'id'=>'user_menus',
            'type'             => 'multicheck',
            'default'          => self::get_default_settings('user_menus'),
            'options'          => array(
                'directmessage' => __( '私信列表', 'b2' ),
                'gold'   => __( '财富管理', 'b2' ),
                'distribution'=>__( '推广中心', 'b2' ),
                'task'=>__( '任务中心', 'b2' ),
                'vip'=>__( '成为会员', 'b2' ),
                'certification'=>__( '认证服务', 'b2' ),
                'dark_room'=>__( '小黑屋', 'b2' ),
                'orders'=>__( '我的订单', 'b2' ),
                'settings'=>__( '我的设置', 'b2' )
            ),
        ));
    }

    public function index_settings(){
 
        //首页布局
        $index = new_cmb2_box(array(
            'id'           => 'b2_template_index_options_page',
            'tab_title'    => __('首页','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_index',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        //模块设置
        $index_group = $index->add_field( array(
            'id'          => 'index_group',
            'type'        => 'group',
            'description' => __( '首页内容模块布局（点击小箭头展开设置）', 'b2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( '模块{#}', 'b2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( '添加新模块', 'b2' ),
                'remove_button'     => __( '删除模块', 'b2' ),
                'sortable'          => true,
                'closed'         => true, // true to have the groups closed by default
                'remove_confirm' => __( '确定要删除这个模块吗？', 'b2' ), // Performs confirmation before removing group.
            ),
        ));

        $index->add_group_field( $index_group, array(
            'name' => sprintf(__('模块标题%s','b2'),'<span class="red">（必填）</span>'),
            'id'   => 'title',
            'type' => 'text',
            'desc'=> __('给这个模块起个名字，某些模块下会显示这个标题','b2')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => sprintf(__('模块key%s','b2'),'<span class="red">（必填）</span>'),
            'id'   => 'key',
            'type' => 'text',
            'desc'=> sprintf(__('给这个模块起一个%s唯一的标识，纯小写字母，不要有空格或特殊字符%s，一般情况下不需要随意改动，这个key将和它对应的小工具挂钩，模块顺序变了以后不影响小工具的显示，如果改动这个值，它对应的小工具需要重新设置','b2'),'<b class="red">','</b>')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('模块描述','b2'),
            'id'   => 'desc',
            'type' => 'textarea_small',
            'desc'=> __('某些模块下会显示这个描述','b2')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('是否开启此模块小工具','b2'),
            'id'   => 'show_widget',
            'type' => 'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2'),
            ),
            'default'=>0,
            'desc'=> sprintf(__('如果设置开启，保存之后请去%s中对此模块小工具进行设置','b2'),'<a target="__blank" href="'.admin_url('/widgets.php').'">小工具设置</a>')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('此模块小工具是否跟随浏览器滚动','b2'),
            'id'   => 'widget_ffixed',
            'type' => 'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2'),
            ),
            'default'=>1,
            'desc'=> __('如果开启了小工具模块，此设置才会生效并只在PC端生效','b2')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('模块背景颜色','b2'),
            'id'   => 'module_bg_color',
            'type' => 'colorpicker',
            'default'=>'',
            'desc'=> __('某些模块设置背景颜色会生效，如果不设置，显示默认背景颜色，请点击清空','b2')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('模块背景图片','b2'),
            'id'   => 'module_bg_img',
            'type' => 'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=> __('一些配置低的电脑或手机可能造成卡顿，请尽量上传提交较小的图片','b2')
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('可见性','b2'),
            'id'   => 'module_mobile_show',
            'type' => 'select',
            'options' => array(
                1 => __('桌面和移动端都显示','b2'), 
                0=>__('仅桌面可见','b2'),
                2=>__('仅移动端可见','b2'),
                3=>__('不显示（仅用作短代码调用）','b2')
            ),
        ) );

        $temp_type = apply_filters('b2_temp_type', array(
            'sliders' => __( '幻灯', 'b2' ),
            'html'   => __( '自定义', 'b2' ),
            'posts'     => __( '文章', 'b2' ),
            'collection'     => __( '专题', 'b2' ),
            'products'     => __( '商品', 'b2' ),
            'search'=>__('搜索','b2')
            // 'user'     => __( '用户', 'b2' ),
            // 'topic'     => __( '帖子', 'b2' )
        ));

        $index->add_group_field( $index_group, array(
            'name' => __('调用内容','b2'),
            'id'      => 'module_type',
            'type'    => 'radio_inline',
            'options' => $temp_type,
            'default' => 'posts',
            'classes' => 'model-picked'
        ) );

        $cats = array();

        $categories = get_categories( array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'hide_empty'      => false,
        ) );
         
        foreach( $categories as $category ) {
            $cats[$category->term_id] = $category->name;
        } 

        //搜索框
        $index->add_group_field( $index_group, array(
            'before_row'=>'<div class="search-module cmb-row set-hidden">',
            'name'    => __( '搜索标题', 'b2' ),
            'id'=>'search_title',
            'type' => 'text',
            'default'=>''
        ));

        $index->add_group_field( $index_group, array(
            'name'    => __( '搜索描述', 'b2' ),
            'id'=>'search_desc',
            'type' => 'text',
            'default'=>''
        ));

        $index->add_group_field( $index_group, array(
            'name'    => __( '搜索框内描述文字', 'b2' ),
            'id'=>'search_input_desc',
            'type' => 'text',
            'default'=>''
        ));

        $index->add_group_field( $index_group, array(
            'name'    => __( '文字颜色', 'b2' ),
            'id'=>'search_color',
            'type' => 'colorpicker',
            'default'=>''
        ));

        $index->add_group_field( $index_group, array(
            'name'    => __( '允许搜索的分类', 'b2' ),
            'id'=>'search_cat',
            'type'    => 'pw_multiselect',
            'options' =>$cats,
        ));

        $index->add_group_field( $index_group, array(
            'name'    => __( '热门搜索关键词', 'b2' ),
            'id'=>'search_key',
            'type' => 'textarea_small',
            'desc'=>__('请输入关键词，每个关键词占一行，留空则不显示','b2'),
            'default'=>''
        ) );

        $index->add_group_field( $index_group, array(
            'after_row'=>'</div>',
            'name'    => __( '搜索模块的宽度', 'b2' ),
            'id'=>'search_width',
            'type' => 'select',
            'options'=>array(
                1=>__('与页面同宽','b2'),
                0=>__('铺满窗口','b2')
            ),
        ));

        $slider = apply_filters('b2_admin_index_mudules_slider_settings', array(
            'options'=>array(
                'slider-1' => __('大幻灯','b2'), 
                'slider-2' => __('小幻灯1','b2'), 
                'slider-3' => __('小幻灯2','b2'), 
                'slider-4' => __('小幻灯3','b2'),
                'slider-5' => __('小幻灯5','b2'), 
                'slider-6' => __('小幻灯6','b2'),
            ),
            'images'       => array(
                'slider-1' => '/Assets/admin/images/slider-1.svg',
                'slider-2' => '/Assets/admin/images/slider-2.svg',
                'slider-3' => '/Assets/admin/images/slider-3.svg',
                'slider-4' => '/Assets/admin/images/slider-4.svg',
                'slider-5' => '/Assets/admin/images/slider-5.svg',
                'slider-6' => '/Assets/admin/images/slider-6.svg'
            )
        )); 

        //幻灯形式
        $index->add_group_field( $index_group, array(
            'before_row'=>'<div class="sliders-module cmb-row set-hidden">',
            'name'    => __( '幻灯样式', 'b2' ),
            'id'=>'slider_type',
            'type' => 'radio_image',
            'options'          => $slider['options'],
            'images_path'  => B2_THEME_URI,
            'images'       => $slider['images'],
            'default'=>'slider-1'
        ) );
        
        do_action('b2_admin_index_mudules_slider_action',$index,$index_group);

        //幻灯内容
        $index->add_group_field( $index_group, array(
            'name' => __('幻灯内容','b2'),
            'id'   => 'slider_list',
            'type' => 'textarea_code',
            'description'=>sprintf(__('支持所有文章类型（文章，活动，商品等），每组占一行，排序与此设置相同。图片可以在%s上传或选择。
            %s
            支持的格式如下：
            %s','b2'),
            '<a target="__blank" href="'.admin_url('/upload.php').'">媒体中心</a>','<br>','
            <br>文章ID+幻灯图片地址：<code>123<span class="red">|</span>https://xxx.com/wp-content/uploads/xxx.jpg</code><br>
            文章ID+文章默认的缩略图：<code>3434<span class="red">|</span>0</code><br>
            网址连接+幻灯图片地址+标题（适合外链到其他网站）：<code>https://www.xxx.com/123.html<span class="red">|</span>https://xxx.com/wp-content/uploads/xxx.jpg<span class="red">|</span>标题</code><br>
            '),
            'options' => array( 'disable_codemirror' => true ),
        ) );

        //幻灯宽度
        $index->add_group_field( $index_group, array(
            'name' => __('幻灯宽度','b2'),
            'id'   => 'slider_width',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '与页面同宽', 'b2' ),
                0   => __( '铺满窗口', 'b2' ),
            ),
        ));

        $index->add_group_field( $index_group, array(
            'name' => __('幻灯的高度','b2'),
            'id'   => 'slider_height',
            'type' => 'text',
            'default'=>'565',
            'description'=>sprintf(__('只支持具体的数值，比如%s'),'<code>565</code>'),
        ));

        $index->add_group_field( $index_group, array(
            'name' => __('幻灯之间的间距','b2'),
            'id'   => 'slider_gap',
            'type' => 'text',
            'default'=>B2_GAP,
            'description'=>__('某些幻灯模式下不会生效，请直接填写数字（只在PC端生效）'),
        ));

        //幻灯切换方式
        // $index->add_group_field( $index_group, array(
        //     'name' => __('幻灯切换方式','b2'),
        //     'id'   => 'slider_fade',
        //     'type'             => 'select',
        //     'default'          => 1,
        //     'options'          => array(
        //         1 => __( '滚动切换', 'b2' ),
        //         0   => __( '渐变切换', 'b2' ),
        //     ),
        // ) );

        //幻灯切换方式
        $index->add_group_field( $index_group, array(
            'name' => __('是否显示幻灯标题','b2'),
            'id'   => 'slider_show_title',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' ),
            ),
        ) );

        $index->add_group_field( $index_group, array(
            'name' => __('是否新窗口打开','b2'),
            'id'   => 'slider_new_window',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1   => __( '原创窗口打开', 'b2' ),
                0 => __( '新窗口打开', 'b2' )
            ),
        ) );

        //幻灯切换速度
        $index->add_group_field( $index_group, array(
            'name' => __('幻灯切换速度','b2'),
            'id'   => 'slider_speed',
            'type' => 'text',
            'default'=>'4000',
            'description'=>sprintf(__('设为%s则禁止自动切换，设为具体的数值，比如%s，则4秒切换一次'),'<code>0</code>','<code>4000</code>'),
            'after_row'=>'</div>',
        ) );

        //广告位代码
        $index->add_group_field( $index_group, array(
            'before_row'=>'<div class="html-module set-hidden">',
            'name' => __('自定义','b2'),
            'id'   => 'html',
            'type' => 'textarea_code',
            'description'=>sprintf(__('自定义模块支持html和php，如果写php代码，请不要忘记 %s 和 %s 符号。','b2'),'<code>&lt;?php</code>','<code>?&gt;</code>'),
            'options' => array( 'disable_codemirror' => true ),
            'default'=>'<a href="#"><img src="'.B2_THEME_URI.'/Assets/fontend/images/ads-example.jpg" /></a>',
        ) );

        //幻灯宽度
        $index->add_group_field( $index_group, array(
            'name' => __('自定义HTML宽度','b2'),
            'id'   => 'html_width',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '与页面同宽', 'b2' ),
                0   => __( '铺满窗口', 'b2' ),
            ),
            'after_row'=>'</div>',
        ));

        $post_type = apply_filters('b2_temp_post_type', array(
            'post-1' => array(
                'name'=>__('网格模式','b2'),
                'img'=>'/Assets/admin/images/post-1.svg'
            ),
            'post-2' => array(
                'name'=>__('瀑布流','b2'),
                'img'=>'/Assets/admin/images/post-2.svg'
            ),
            'post-3' => array(
                'name'=>__('列表模式','b2'),
                'img'=>'/Assets/admin/images/post-3.svg'
            ),
            'post-4' => array(
                'name'=>__('组合模式','b2'),
                'img'=>'/Assets/admin/images/post-4.svg'
            ),
            'post-5' => array(
                'name'=>__('纯文字模式','b2'),
                'img'=>'/Assets/admin/images/post-5.svg'
            ),
            'post-6' => array(
                'name'=>__('纯文字带自定义字段','b2'),
                'img'=>'/Assets/admin/images/post-6.svg'
            )
        ));

        $options = array();
        $images = array();

        foreach ($post_type as $k => $v) {
            $options[$k] = $v['name'];
            $images[$k] = $v['img'];
        }

        $index->add_group_field($index_group,array(
            'before_row'=>'<div class="posts-module set-hidden">',
            'name' => __('列表样式','b2'),
            'id'   => 'post_type',
            'type' => 'radio_image',
            'options'          => $options,
            'images_path'  => B2_THEME_URI,
            'images'       => $images,
            'default'=>'post-1',
        ));

        do_action('b2_temp_post_type_action',$index,$index_group);

        $index->add_group_field($index_group,array(
            'name'=>__('排序方式','b2'),
            'id'=>'post_order',
            'type'=>'select',
            'options' => array(
                'new' => __('最新文章','b2'),
                'modified' => __('修改时间','b2'),
                'random' => __('随机文章','b2'),
                'sticky' => __('置顶文章','b2'),
                'views' => __('浏览最多文章','b2'),
                'comments' => __('评论最多文章','b2')
            ),
            'default' => 'new',
        ));

        // $index->add_group_field($index_group,array(
        //     'name' => __('文章分类','b2'),
        //     'id'   => 'post_cat',
        //     'type' => 'taxonomy_multicheck_hierarchical',
        //     'taxonomy'=>'category',
        //     // Optional :
        //     'text'           => array(
        //         'no_terms_text' => sprintf(__('没有分类，请前往%s添加','b2'),'<a target="__blank" href="'.admin_url('/edit-tags.php?taxonomy=category').'"></a>') // Change default text. Default: "No terms"
        //     ),
        //     'remove_default' => 'true', // Removes the default metabox provided by WP core.
        //     // Optionally override the args sent to the WordPress get_terms function.
        //     'query_args' => array(
        //         'orderby' => 'count',
        //         'hide_empty' => false,
        //     ),
        //     'select_all_button' => true,
        //     'desc'=>__('请确保您的分类别名不是中文，否则无法选中','b2'),
        // ));

        $index->add_group_field($index_group,array(
            'name'    => '文章分类',
            'id'      => 'post_cat',
            'desc'    => __('请选择要显示的文章分类，可以拖动排序','b2'),
            'type'    => 'pw_multiselect',
            'options' =>$cats,
        ) );

        // $index->add_group_field($index_group,array(
        //     'name'=>__('是否显示置顶文章','b2'),
        //     'id'=>'post_ignore_sticky_posts',
        //     'type'=>'select',
        //     'options'=>array(
        //         0=>__('显示置顶文章','b2'),
        //         1=>__('不显示置顶文章','b2')
        //     ),
        //     'default'=>1,
        // ));

        $index->add_group_field($index_group,array(
            'name'=>__('每行显示数量','b2'),
            'id'=>'post_row_count',
            'type'=>'text',
            'default'=>4,
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('每页显示数量','b2'),
            'id'=>'post_count',
            'type'=>'text',
            'default'=>8,
        ));

        $index->add_group_field($index_group,array(
            'before_row'=>'<div class="custom-key">',
            'name'=>'<span class="red">'.__('【纯文字带自定义字段】模式自定义字段','b2').'</span>',
            'id'=>'post_custom_key',
            'type'=>'textarea_small',
            'desc'=>sprintf(__('如果不需要请留空。请根据%s格式设置您要显示的自定义字段，比如：%s','b2'),'<code>自定义字段的中文名1|自定义字段key1</code>','<br>
                <code>国籍|move_country</code><br>
                <code>电影类别|move_type</code><br>
            '),
            'after_row'=>'</div>'
        ));

        $index->add_group_field($index_group,array(
            'before_row'=>'<div class="list-width">',
            'name'=>__('PC缩略图端宽度','b2'),
            'id'=>'post_thumb_width',
            'type'=>'text',
            'default'=>'190',
            'desc'=>'<span class="red">'.__('PC端该【列表模式】下缩略图的宽度，单位是px，请直接填写数字即可。').'</span>',
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('PC端缩略图比例','b2'),
            'id'=>'post_thumb_ratio_pc',
            'type'=>'text',
            'default'=>'1/0.74',
            'desc'=>'<span class="red">'.sprintf(__('PC端该【列表模式】下缩略图宽和高的比例，比如%s，%s。'),'<code>4/3</code>','<code>1/0.618</code>').'</span>',
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('移动端缩略图宽度','b2'),
            'id'=>'post_thumb_width_mobile',
            'type'=>'text',
            'default'=>'100',
            'desc'=>'<span class="red">'.__('移动端该【列表模式】下缩略图的宽度，单位是px，请直接填写数字即可。').'</span>',
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('移动端缩略图比例','b2'),
            'id'=>'post_thumb_ratio_mobile',
            'type'=>'text',
            'default'=>'1/0.6',
            'desc'=>'<span class="red">'.sprintf(__('移动端该【列表模式】下缩略图宽和高的比例，比如%s，%s。'),'<code>4/3</code>','<code>1/0.618</code>').'</span>',
            'after_row'=>'</div>'
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('PC端标题最多显示几行','b2'),
            'id'=>'post_title_row',
            'type'=>'text',
            'default'=>1,
            'desc'=>__('如果设置成1，标题超过1行会显示省略号，如果设置2，标题超过2行会显示省略号，以此类推。','b2')
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('移动端端标题最多显示几行','b2'),
            'id'=>'post_title_row_mobile',
            'type'=>'text',
            'default'=>2,
            'desc'=>__('如果设置成1，标题超过1行会显示省略号，如果设置2，标题超过2行会显示省略号，以此类推。','b2')
        ));

        $index->add_group_field($index_group,array(
            'before_row'=>'<div class="list-width-normal">',
            'name'=>__('缩略图比例','b2'),
            'id'=>'post_thumb_ratio',
            'type'=>'text',
            'default'=>'4/3',
            'desc'=>sprintf(__('缩略图高度自适应的情况下不生效，请填写宽和高的比例，比如%s，%s。'),'<code>4/3</code>','<code>1/0.618</code>'),
            'after_row'=>'</div>'
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('打开方式','b2'),
            'id'=>'post_open_type',
            'type'    => 'select',
            'options' => array(
                1 => __( '原窗口打开', 'b2' ),
                0   => __( '新窗口打开', 'b2' ),
            ),
            'default' => 1,
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('文章meta选择','b2'),
            'id'=>'post_meta',
            'type'    => 'multicheck_inline',
            'options' => array(
                'title'=>__( '模块标题', 'b2' ),
                'desc'=>__( '模块描述', 'b2' ),
                'links'=>__( '导航', 'b2' ),
                'user' => __( '作者', 'b2' ),
                'date' => __( '时间', 'b2' ),
                'like' => __( '喜欢数量', 'b2' ),
                'comment'=>__('评论数量','b2'),
                'views' => __( '浏览量', 'b2' ),
                'cats' => __( '分类', 'b2' ),
                'des' => __( '摘要', 'b2' ),
                'video'=>__('视频标签','b2'),
                'download'=>__('下载标签','b2'),
                'hide'=>__('隐藏内容标签','b2'),
            ),
            'default' => array(),
        ));

        $index->add_group_field( $index_group, array(
            'name' => __('是否显示加载更多按钮','b2'),
            'id'   => 'post_load_more',
            'type'             => 'select',
            'default'          => 0,
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' ),
            ),
            'after_row'=>'</div>'
        ) );

        // $index->add_field( array(
        //     'name'       => 'widget',
        //     'desc'       => 'field description (optional)',
        //     'id'         => 'wiki_text',
        //     'type'       => 'normal',
        //     'column'     => true,
        //     'classes'=> array('b2-widget-box'),
        //     'after' => array($this,'builder_widgets'), // Output the display of the column values through a callback.
        // ) );

        //商品样式
        $index->add_group_field($index_group,array(
            'before_row'=>'<div class="products-module set-hidden">',
            'name' => __('要显示的商品id','b2'),
            'id'   => 'products_ids',
            'type' => 'textarea',
            'desc'=>__('请输入商品的ID，每个ID占一行，留空则不显示优惠劵信息','b2')
        ));

        $index->add_group_field($index_group,array(
            'name' => __('要显示的优惠劵id','b2'),
            'id'   => 'products_coupons',
            'type' => 'textarea',
            'desc'=>__('请输入优惠劵的ID，每个ID占一行，留空则不显示优惠劵信息','b2')
        ));

        $index->add_group_field($index_group,array(
            'name' => __('每行显示数量','b2'),
            'id'   => 'products_count',
            'type' => 'text',
            'desc'=>__('请直接填写每行要显示的数量','b2')
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('商品缩略图比例','b2'),
            'id'=>'products_thumb_ratio',
            'type'=>'text',
            'default'=>'1/1',
            'desc'=>sprintf(__('请填写宽和高的比例，比如%s，%s'),'<code>4/3</code>','<code>1/0.618</code>'),
        ));
        
        $index->add_group_field($index_group,array(
            'name' => __('每行显示数量','b2'),
            'id'   => 'products_count',
            'type' => 'text',
            'desc'=>__('请直接填写每行要显示的数量','b2')
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('打开方式','b2'),
            'id'=>'products_open_type',
            'type'    => 'select',
            'options' => array(
                1 => __( '原窗口打开', 'b2' ),
                0   => __( '新窗口打开', 'b2' ),
            ),
            'default' => 1,
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('文章meta选择','b2'),
            'id'=>'products_meta',
            'type'    => 'multicheck_inline',
            'options' => array(
                'title'=>__( '模块标题', 'b2' ),
                'desc'=>__( '模块描述', 'b2' ),
                'links'=>__( '导航', 'b2' ),
                'coupons'=>__( '优惠卷', 'b2' )
            ),
            'default' => array(),
            'after_row'=>'</div>'
        ));

        //专题样式
        $index->add_group_field( $index_group, array(
            'before_row'=>'<div class="collection-module cmb-row set-hidden">',
            'name'    => __( '专题样式', 'b2' ),
            'id'=>'collection_type',
            'type' => 'radio_image',
            'options'          => array(
                'collection-1' => __('左图右文','b2'), 
                'collection-2' => __('上图下文','b2'), 
                'collection-3' => __('图片','b2'), 
            ),
            'images_path'  => B2_THEME_URI,
            'images'       => array(
                'collection-1' => 'Assets/admin/images/collection1.svg',
                'collection-2' => 'Assets/admin/images/collection2.svg',
                'collection-3' => 'Assets/admin/images/collection3.svg',
            ),
            'default'=>'collection-1'
        ) );
       
        $index->add_group_field($index_group,array(
            'name' => __('要显示的专题','b2'),
            'id'   => 'collections',
            'type' => 'taxonomy_multicheck_hierarchical',
            'taxonomy'=>'collection',
            // Optional :
            'text'           => array(
                'no_terms_text' => sprintf(__('没有专题，请前往%s添加','b2'),'<a target="__blank" href="'.admin_url('/edit-tags.php?taxonomy=collection').'">专题管理</a>') // Change default text. Default: "No terms"
            ),
            'remove_default' => 'true', // Removes the default metabox provided by WP core.
            // Optionally override the args sent to the WordPress get_terms function.
            'query_args' => array(
                'orderby' => 'count',
                'hide_empty' => false,
            ),
            'select_all_button' => true,
            'desc'=>__('请确保您的专题别名不是中文，否则无法选中','b2'),
        ));

        // $index->add_group_field($index_group,array(
        //     'name'=>__('首屏显示数量','b2'),
        //     'id'=>'collection_row_count',
        //     'type'=>'text',
        //     'default'=>6,
        //     'desc'=>__('首屏显示数量必须小于上面选中的专题总数','b2')
        // ));

        $index->add_group_field($index_group,array(
            'name'=>__('下面显示几篇此专题的文章','b2'),
            'id'=>'collection_count',
            'type'=>'text',
            'desc'=>__('如果设置为0，将不在这里显示此专题的文章','b2'),
            'default'=>3,
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('专题缩略图比例','b2'),
            'id'=>'collection_thumb_ratio',
            'type'=>'text',
            'default'=>'4/3',
            'desc'=>sprintf(__('请填写宽和高的比例，比如%s，%s'),'<code>4/3</code>','<code>1/0.618</code>'),
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('是否新窗口打开','b2'),
            'id'=>'collection_open',
            'type'    => 'select',
            'options' => array(
                1 => __( '原窗口打开', 'b2' ),
                0   => __( '新窗口打开', 'b2' ),
            ),
            'default' => 1
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('专题meta选择','b2'),
            'id'=>'collection_meta',
            'type'    => 'multicheck_inline',
            'options' => array(
                'title'=>__( '模块标题', 'b2' ),
                'desc'=>__( '模块描述', 'b2' ),
            ),
            'default' => array('title','des'),
            'after_row'=>'</div>'
        ));

        $index->add_group_field($index_group,array(
            'name'=>__('短代码','b2'),
            'id'=>'short_code',
            'type'    => 'text',
            'default' => '[b2_index_module key=#]',
            'attributes' => array(
                'readonly' => 'readonly'
            ),
            'desc'=>__('此模块的短代码，不可编辑，您可以在站点的任何地方，调用此短代码','b2')
        ));

        $this->single_settings();

        $this->comments_settings();

        $this->footer_settings();

        $this->collection();

        $this->download_page_ads();

        $this->aside_bar();
    }

    public function comments_settings(){
        //评论设置
        $single = new_cmb2_box(array(
            'id'           => 'b2_template_comment_options_page',
            'tab_title'    => __('评论设置','b2'),
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_comment',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        //是否关闭评论功能
        $single->add_field(array(
            'name' => __('是否关闭评论功能','b2'),
            'id'   => 'comment_close',
            'type'             => 'select',
            'default'          => 1,
            'desc'=>__('如果关闭，整个网站都讲不显示评论列表和评论框，一般站点在备案的时候用得上。','b2'),
            'options'          => array(
                1 => __( '开启', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ) );

        //是否允许发布表情
        $single->add_field(array(
            'name' => __('是否允许添加表情','b2'),
            'id'   => 'comment_use_smiles',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '开启', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ) );

        //是否允许添加图片
        $single->add_field(array(
            'name' => __('是否允许添加图片','b2'),
            'id'   => 'comment_use_image',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '开启', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ) );

        //评论加载方式
        $single->add_field(array(
            'name' => __('评论分页类型','b2'),
            'id'   => 'nav_type',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                'p' => __( 'ajax分页', 'b2' ),
                'm'  => __( '无限加载', 'b2' )
            ),
            'desc'=>sprintf(__('无限加载模式需要%s中设置为%s'),'<a href="'.admin_url('/options-discussion.php').'">评论设置</a>','<code>默认显示最前一页 </code>')
        ) );

        //评论顶部tips提示
        $single->add_field(array(
            'name' => __('评论顶部提示','b2'),
            'id'   => 'comment_tips',
            'type'             => 'textarea',
            'default'          => '',
            'desc'=>sprintf(__('这是一个小提示的功能，显示在评论列表顶部，不显示请直接留空，不显示链接，请直接填写标题。格式：%s或者%s，每条占一行'),'<code>标题|链接</code>','<code>直接写标题不要连接</code>')
        ) );
    }

    public function single_settings(){
        //文章内页布局
        $single = new_cmb2_box(array(
            'id'           => 'b2_template_single_options_page',
            'tab_title'    => __('文章内页','b2'),
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_single',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        $single_group = $single->add_field( array(
            'id'          => 'single_style_group',
            'type'        => 'group',
            'description' => __( '文章内页模块默认布局', 'b2' ),
            'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( '如果您没有对单篇文章的布局进行设置，则此处设置默认生效', 'b2' ), // since version 1.1.4, {#} gets replaced by row number
                'sortable'          => false,
                'closed'         => false, // true to have the groups closed by default
            ),
        ));

        $single->add_group_field($single_group,array(
            'name'    => __( '默认文章样式', 'b2' ),
            'id'=>'single_post_style',
            'type' => 'radio_image',
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'options'          => array(
                'post-style-1' => __('纯文字','b2'),
                'post-style-2' => __('简洁','b2'),
                'post-style-3' => __('大图片','b2'),
                'post-style-4' => __('小图片','b2')
            ),
            'classes'=>array('cmb-type-radio-image'),
            'images_path'  => B2_THEME_URI,
            'images'       => array(
                'post-style-1' => '/Assets/admin/images/post-style-1.svg',
                'post-style-2' => '/Assets/admin/images/post-style-2.svg',
                'post-style-3' => '/Assets/admin/images/post-style-3.svg',
                'post-style-4' => '/Assets/admin/images/post-style-4.svg',
            ),
            'default'=>'post-style-1'
        ));
        
        //是否显示侧边栏
        $single->add_group_field($single_group,array(
            'name' => __('是否显示侧边栏小工具','b2'),
            'id'   => 'single_post_sidebar_show',
            'type'             => 'select',
            'default'          => 1,
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' ),
            ),
        ) );

        //是否使用自带幻灯
        $single->add_group_field($single_group,array(
            'name' => __('是否使用主题自带文章内图片点击放大功能','b2'),
            'id'   => 'single_post_slider',
            'type'             => 'select',
            'default'          => 1,
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'options'          => array(
                1 => __( '开启', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ) );

        //是否显示侧边栏
        $single->add_group_field($single_group,array(
            'name' => __('是否显示语音朗读','b2'),
            'id'   => 'single_show_radio',
            'type'             => 'select',
            'default'          => 0,
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' ),
            ),
        ) );

        //是否显示标签
        $single->add_group_field($single_group,array(
            'name' => __('是否显示标签','b2'),
            'id'   => 'single_show_tags',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' ),
            ),
        ) );

        //文章顶部广告位代码
        $single->add_group_field($single_group,array(
            'name' => __('文章顶部广告位代码','b2'),
            'id'   => 'single_post_top_ads',
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'type' => 'textarea_code',
            'options' => array( 'disable_codemirror' => true ),
            'default'=>'',
        ));

        //文章低部广告位代码
        $single->add_group_field($single_group,array(
            'name' => __('文章低部广告位代码','b2'),
            'id'   => 'single_post_bottom_ads',
            'desc'=>__( '优先显示文章中的此项设置，如果文章中未设置，则此处设置生效', 'b2' ),
            'type' => 'textarea_code',
            'options' => array( 'disable_codemirror' => true ),
            'default'=>'',
        ));

        $ds_group = $single->add_field( array(
            'id'          => 'single_ds_group',
            'type'        => 'group',
            'description' => __( '打赏设置', 'b2' ),
            'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( '打赏的相关参数', 'b2' ), // since version 1.1.4, {#} gets replaced by row number
                'sortable'          => false,
                'closed'         => false, // true to have the groups closed by default
            ),
        ));

        //是否启用打赏功能
        $single->add_group_field($ds_group,array(
            'name' => __('是否启用打赏功能','b2'),
            'id'   => 'single_post_ds_open',
            'type'             => 'select',
            'default'          => 1,
            'desc'=>__( '文章内页的打赏功能', 'b2' ),
            'options'          => array(
                1 => __( '启用', 'b2' ),
                0   => __( '关闭', 'b2' ),
            ),
        ));

        $single->add_group_field($ds_group,array(
            'name' => __('打赏文字','b2'),
            'id'   => 'single_post_ds_text',
            'type'             => 'text',
            'desc'=>__( '打赏按钮前面的提示文字', 'b2' ),
            'default'          => self::get_default_settings('single_post_ds_text')
        ));

        $single->add_group_field($ds_group,array(
            'name' => __('没有打赏的提示文字','b2'),
            'id'   => 'single_post_ds_none_text',
            'type'             => 'text',
            'desc'=>__( '没有人打赏的时候，提示的文字', 'b2' ),
            'default'          => self::get_default_settings('single_post_ds_none_text')
        ));

        $single->add_group_field($ds_group,array(
            'name' => __('默认打赏金额','b2'),
            'id'   => 'single_post_ds_money',
            'type'             => 'text',
            'desc'=>sprintf(__( '请填写默认的金额，数量为5个，每个金额使用竖杠符号%s隔开，默认为%s', 'b2' ),'<code>|</code>','<code>'.self::$default_settings['single_post_ds_money'].'</code>'),
            'default'          => self::$default_settings['single_post_ds_money']
        ));

        $poster_group = $single->add_field( array(
            'id'          => 'single_poster_group',
            'type'        => 'group',
            'description' => __( '海报设置', 'b2' ),
            'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( '生成海报的相关参数', 'b2' ), // since version 1.1.4, {#} gets replaced by row number
                'sortable'          => false,
                'closed'         => false, // true to have the groups closed by default
            ),
        ));

        // $single->add_group_field($poster_group,array(
        //     'name' => __('是否使用代理生成海报','b2'),
        //     'id'   => 'single_poster_dl',
        //     'type'             => 'select',
        //     'options' => array(
        //         1 => __('开启','b2'), 
        //         0=>__('关闭','b2')
        //     ),
        //     'desc'=>__( '有些站点因为防盗链、跨域等问题无法生成海报，可以开启代理，通过代理生成，但是相应的速度也会降低。', 'b2' ),
        //     'default'          => self::get_default_settings('single_poster_dl')
        // ));

        //海报默认特色图
        $single->add_group_field($poster_group,array(
            'before_row'=>'<p class="red">如果图片不在本地，请务必保证图片服务器开启了跨域，具体开启方法，请咨询图片服务提供商。</p>',
            'name' => __('海报默认特色图','b2'),
            'id'   => 'single_poster_default_img',
            'type'             => 'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=>__( '当您的文章没有特色图，或者无法获取到您的特色图，海报将显示此默认特色图（请使用本地图片，不要使用远程图片）', 'b2' ),
            'default'          => self::get_default_settings('single_poster_default_img')
        ));

        //海报默认特色图
        $single->add_group_field($poster_group,array(
            'name' => __('海报中显示的网站LOGO','b2'),
            'id'   => 'single_poster_default_logo',
            'type'             => 'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=>__( '请使用本地图片，不要使用远程图片，不支持SVG格式', 'b2' ),
            'default'          => self::get_default_settings('single_poster_default_logo')
        ));

        //海报默认特色图
        $single->add_group_field($poster_group,array(
            'name' => __('海报中显示的网站名称','b2'),
            'id'   => 'single_poster_default_text',
            'type'             => 'text',
            'default'          => self::get_default_settings('single_poster_default_text')
        ));

        $single->add_group_field($poster_group,array(
            'name' => __('海报中显示的网站描述','b2'),
            'id'   => 'single_poster_default_desc',
            'type'             => 'text',
            'default'          => self::get_default_settings('single_poster_default_desc')
        ));
    }

    public function footer_settings(){
        $footer = new_cmb2_box(array(
            'id'           => 'b2_template_footer_options_page',
            'tab_title'    => __('底部','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_footer',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        $footer->add_field(array(
            'name'    => __( '底部第一层背景图片', 'b2' ),
            'id'=>'footer_img',
            'type' => 'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=>__('如果不设置，请填写none后保存','b2'),
            'default' => self::get_default_settings('footer_img'),
        ));

        $footer->add_field(array(
            'name'    => __( '底部第一层背景颜色', 'b2' ),
            'id'=>'footer_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['footer_color'],
        ));

        $footer->add_field(array(
            'name'    => __( '底部第一层文字颜色', 'b2' ),
            'id'=>'footer_text_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['footer_text_color'],
        ));

        $footer->add_field(array(
            'name'    => __( '底部第二层背景颜色', 'b2' ),
            'id'=>'footer_nav_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['footer_nav_color'],
        ));

        $footer->add_field(array(
            'name'    => __( '底部第二层文字颜色', 'b2' ),
            'id'=>'footer_nav_text_color',
            'type'             => 'colorpicker',
            'default'          => self::$default_settings['footer_nav_text_color'],
        ));

        $footer->add_field(array(
            'name' => __('底部显示的友情连接分类','b2'),
            'id'   => 'link_cat',
            'type' => 'taxonomy_multicheck_hierarchical',
            'taxonomy'=>'link_category',
            // Optional :
            'text'           => array(
                'no_terms_text' => sprintf(__('没有连接分类，请前往%s添加','b2'),'<a target="__blank" href="'.admin_url('/edit-tags.php?taxonomy=link_category').'">链接分类</a>') // Change default text. Default: "No terms"
            ),
            'remove_default' => 'true', // Removes the default metabox provided by WP core.
            // Optionally override the args sent to the WordPress get_terms function.
            'query_args' => array(
                'orderby' => 'count',
                'hide_empty' => false,
            ),
            'select_all_button' => true,
            'desc'=>__('请确保您的分类别名不是中文，否则无法选中','b2'),
        ));

        $footer->add_field(array(
            'name'    => __( '移动端是否显示底部友情连接', 'b2' ),
            'id'=>'footer_mobile_show_links',
            'type'             => 'select',
            'options'=>array(
                1=>__('显示','b2'),
                0=>__('隐藏','b2')
            ),
            'default'          => self::$default_settings['footer_mobile_show_links'],
        ));

        $footer->add_field(array(
            'name'    => __( '备案号', 'b2' ),
            'id'=>'footer_beian',
            'type'             => 'text',
            'default'          => b2_get_option('template_footer','footer_beian'),
        ));

        $footer->add_field(array(
            'name'    => __( '公安备案号', 'b2' ),
            'id'=>'footer_gongan',
            'type'             => 'text',
            'default'          => self::$default_settings['footer_gongan'],
        ));

        // $footer->add_field(array(
        //     
        //     'name'    => __( '移动端底部菜单固定方式', 'b2' ),
        //     'id'=>'footer_menu_open',
        //     'type'             => 'radio',
        //     'options'=>array(
        //         1=>__('页面下滑显示','b2'),
        //         2=>__('始终显示','b2'),
        //         0=>__('始终不显示','b2')
        //     ),
        //     'default'          => self::$default_settings['footer_menu_open'],
        // ));

        $footer->add_field(array(
            'before_row'=>'<h2>'.__('移动端底部菜单设置','b2').'</h2>
            <p>复制下面的单词到对应的位置占一行将会显示对应的菜单：</p>
            <p>登录：<code>login</code>，登录状态将会显示我的主页</p>
            <p>返回顶部：<code>top</code>，点击之后返回顶部</p>
            <p>搜索：<code>search</code>，点击之后打开搜索创口</p>
            <p>菜单：<code>menu</code>，点击之后打开菜单（如果只设置了顶部菜单，此项将不起作用）</p>
            ',
            'name'    => __( '底部左侧菜单', 'b2' ),
            'id'=>'footer_menu_left',
            'type'             => 'textarea_code',
            'default'          => self::get_default_settings('footer_menu_left'),
            'desc'=>sprintf(__('可以使用图标字体或者图片,每个连接占一行，最好不超过3个，否则放不下。比如：%s'),'<br><code>'.htmlspecialchars('<i class="b2font b2-home"></i>').'|首页|https://www.dachaoka.com</code><br><code>'.htmlspecialchars('<img src="https://www.xxx.com/123.png" />').'|专题|https://www.dachaoka.com/collection</code>'),
            'options' => array( 'disable_codemirror' => true )
        ));

        $footer->add_field(array(
            'name'    => __( '底部右侧菜单', 'b2' ),
            'id'=>'footer_menu_right',
            'type'             => 'textarea_code',
            'default'          => self::get_default_settings('footer_menu_right'),
            'options' => array( 'disable_codemirror' => true ),
        ));
    }

    //专题聚合页面
    public function collection(){
        $collection = new_cmb2_box(array(
            'id'           => 'b2_template_collection_options_page',
            'tab_title'    => __('专题聚合页面','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_collection',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        $collection->add_field(array(
            'name' => __('是否显示专题页顶部大背景图片','b2'),
            'id'   => 'collection_open_cover',
            'type'             => 'select',
            'options'=>array(
                0=>__('关闭','b2'),
                1=>__('显示','b2')
            ),
            'default'          => self::$default_settings['collection_open_cover'],
        ));

        $collection->add_field(array(
            'name' => __('专题聚合页面标题','b2'),
            'id'   => 'collection_title',
            'type'             => 'text',
            'default'          => self::$default_settings['collection_title'],
            'desc'=>__('将会显示在专题聚合页面','b2')
        ));

        $collection->add_field(array(
            'name' => __('专题聚合页面描述','b2'),
            'id'   => 'collection_desc',
            'type'             => 'text',
            'default'          => self::$default_settings['collection_desc'],
            'desc'=>__('将会显示在专题聚合页面','b2')
        ));

        $collection->add_field(array(
            'name' => __('模块背景图片','b2'),
            'id'   => 'collection_image',
            'type' => 'file',
            'options' => array(
                'url' => true, 
            ),
            'desc'=> __('将会显示在专题聚合页面','b2')
        ));

        $collection->add_field(array(
            'name' => __('专题聚合页面每页显示数量','b2'),
            'id'   => 'collection_number',
            'type'             => 'input',
            'default'          => self::$default_settings['collection_number']
        ));
    }

    public function download_page_ads(){
        $download = new_cmb2_box(array(
            'id'           => 'b2_template_download_options_page',
            'tab_title'    => __('下载弹窗设置','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_download',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        $download->add_field(array(
            'name' => __('顶部广告（长方形）','b2'),
            'id'   => 'download_ads_top',
            'type'             => 'textarea_code',
            'default'          => ''
        ));

        $download->add_field(array(
            'name' => __('标题下方广告（正方形）','b2'),
            'id'   => 'download_ads_middle',
            'type'             => 'textarea_code',
            'default'          => ''
        ));

        $download->add_field(array(
            'name' => __('底部广告（长方形）','b2'),
            'id'   => 'download_ads_bottom',
            'type'             => 'textarea_code',
            'default'          => ''
        ));
    }

    /**
     * 右侧跟随工具条
     */
    public function aside_bar(){
        $aside = new_cmb2_box(array(
            'id'           => 'b2_template_aside_options_page',
            'tab_title'    => __('右侧跟随工具条','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_template_aside',
            'parent_slug'     => '/admin.php?page=b2_template_main',
            'tab_group'    => 'b2_template_options',
        ));

        $aside->add_field(array(
            'name' => __('是否开启右侧工具条','b2'),
            'id'   => 'aside_show',
            'type'             => 'select',
            'default'          => 1,
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));

        $aside->add_field(array(
            'name' => __('是否显示个人中心','b2'),
            'id'   => 'aside_user',
            'type'             => 'select',
            'default'          => self::$default_settings['aside_user'],
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));

        $aside->add_field(array(
            'name' => __('是否显示签到','b2'),
            'id'   => 'aside_mission',
            'type'             => 'select',
            'default'          => self::$default_settings['aside_mission'],
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));

        $aside->add_field(array(
            'name' => __('是否显示消息','b2'),
            'id'   => 'aside_message',
            'type'             => 'select',
            'default'          => self::$default_settings['aside_message'],
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));

        $aside->add_field(array(
            'name' => __('是否显示私信','b2'),
            'id'   => 'aside_dmsg',
            'type'             => 'select',
            'default'          => self::$default_settings['aside_dmsg'],
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));
        $aside->add_field(array(
            'name' => __('是否显示vip按钮','b2'),
            'id'   => 'aside_vip',
            'type'             => 'select',
            'default'          => self::$default_settings['aside_vip'],
            'options'          => array(
                1 => __( '显示', 'b2' ),
                0   => __( '隐藏', 'b2' )
            )
        ));

        $qrcode = $aside->add_field( array(
            'id'          => 'aside_qrcode',
            'type'        => 'group',
            'description' => __('默认会显示当前页面的二维码，如果您还有其他二维码，请在此处进行设置','b2'),
            'repeatable'  => true, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( '二维码设置{#}', 'b2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( '添加新二维码', 'b2' ),
                'remove_button'     => __( '删除二维码', 'b2' ),
                'sortable'          => true,
                'closed'         => true, // true to have the groups closed by default
                'remove_confirm' => __( '确定要删除这个二维码吗？', 'b2' ), // Performs confirmation before removing group.
            ),
        ));

        $aside->add_group_field($qrcode, array(
            'name' => __('二维码图片','b2'),
            'id'   => 'qrcode_img',
            'type' => 'file',
            'options' => array(
                'url' => true, 
            ),
        ) );

        $aside->add_group_field($qrcode, array(
            'name' => __('二维码描述','b2'),
            'id'   => 'qrcode_desc',
            'type' => 'text'
        ) );
    }

    /**
     * 小工具注册
     *
     * @param array $sections
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function builder_widgets( $sections = array() ) {
        global $wp_registered_widgets, $sidebars_widgets;
        
        $sidebars_widgets = wp_get_sidebars_widgets();
        if ( empty( $sidebars_widgets ) ){
            $sidebars_widgets = wp_get_widget_defaults();
        }
        
        if ( ! function_exists('wp_list_widgets') ){
            require_once(ABSPATH . '/wp-admin/includes/widgets.php');
        }
        
        ?>
        
        <form style="display: none;" action="" method="post"></form>
        
        <div id="sidebars-customize" class="popup-block popup-window">

        <div class="customize-widgets-container">
        
            <input type="hidden" name="pw-sidebar-customize" value="0" />
        
            <div id="custom-widgtes-settings">
            <div class="widget-liquid-left">
                <div id="widgets-left">
                <div id="available-widgets" class="widgets-holder-wrap">
                    <div class="widget-holder">
                    <div id="widget-list">
                        <?php wp_list_widgets(); ?>
                    </div>
                    <br class='clear' />
                    </div>
                    <br class="clear" />
                </div>
                </div>
            </div>
        
            <div class="widget-liquid-right">
                <div id="widgets-right">
        
                <?php
                global $wp_registered_sidebars;

                $custom = array(
                    
                );
                if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ){
                    foreach ( $wp_registered_sidebars as $section ){
                $section_id = $section['id'];
        
                echo '<div id="wrap-'. $section_id .'" class="widgets-holder-wrap">';
                wp_list_widget_controls( $section_id, __('当前模块使用的小工具', 'b2' ) );
                echo '</div>';
                }
                }
                ?>
        
                </div>
            </div>
            </div>
        
        </div> <!-- .customize-widgets-container /-->
        
            <form action="" method="post">
            <?php wp_nonce_field('save-sidebar-widgets', '_wpnonce_widgets', false); ?>
            </form>
            <br class="clear" />
        
        </div><!-- End #pw-sidebars-customize -->
        
        <div class="widgets-chooser">
            <ul class="widgets-chooser-sidebars"></ul>
            <div class="widgets-chooser-actions">
            <button class="button-secondary"><?php __( '取消', 'b2'  ); ?></button>
            <button class="button-primary"><?php __( '添加小工具', 'b2'  ); ?></button>
            </div>
        </div>
        <?php
    }
}