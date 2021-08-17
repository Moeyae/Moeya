<?php
namespace B2\Modules\Settings;

use B2\Modules\Common\User;
use B2\Modules\Common\CircleRelate;

class Circle{

    public static $default_settings = array(
        'circle_open'=>1,
        'circle_keywords'=>'',
        'circle_keywords'=>'',
        'circle_tags'=>'',
        'topic_ask_allow'=>[],
        'topic_vote_allow'=>[],
        'topic_guess_allow'=>[],
        'topic_public_allow'=>[],
        'topic_image_allow'=>1,
        'topic_video_allow'=>1,
        'topic_file_allow'=>1,
        'topic_card_allow'=>[],
        'topic_word_count'=>'5-250',
        'topic_image_count'=>5,
        'topic_video_count'=>1,
        'topic_file_count'=>5,
        'topic_card_count'=>3,
        'topic_per_count'=>20,
        'topic_role_comment'=>[],
        'topic_role_login'=>[],
        'topic_role_money'=>[],
        'topic_role_credit'=>[],
        'topic_role_lv'=>[],
        'topic_delete_time'=>30,
        'topic_pending_count'=>3
    );

    public function init(){
        add_action('cmb2_admin_init',array($this,'circle_settings'));
        add_action( 'cmb2_override_option_save_b2_circle_data', array($this,'save_action'), 10, 3 );
        add_action( 'circle_tags_row_actions', array($this,'edit_delete_in_category'), 10, 2 );
        add_action( "delete_circle_tags", array($this,'delete_circle_tags'), 10, 4);
    }

    public function delete_circle_tags($term, $tt_id, $deleted_term, $object_ids){

       CircleRelate::delete_data(array('circle_id'=>$term));

    }

    public function edit_delete_in_category($actions, $tag) {
        if($tag->term_id == get_option('b2_circle_default')){
            unset($actions['delete']); // Delete link
            unset( $actions['clone'] );
            unset( $actions['trash'] );
        }

        return $actions;
    }

    public function save_action( $cmb2_no_override_option_save, $this_options, $instance){
        if(isset($this_options['circle_rebuild_id'])){
            $circle_id = $this_options['circle_rebuild_id'];

            $role = get_term_meta($circle_id,'b2_circle_read',true);
            if(!$role) return;
            $args = array(
                'post_type' => 'circle',
                'posts_per_page'=>-1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'circle_tags',
                        'field' => 'term_id',
                        'terms' => $circle_id
                    )
                ),
                'suppress_filters' => false,
                'no_found_rows'=>true
            );

            $topic_query = new \WP_Query( $args );

            if ( $topic_query->have_posts()) {
                while ( $topic_query->have_posts() ) {
                    $topic_query->the_post();

                    if($role === 'public'){
                        delete_post_meta($topic_query->post->ID,'b2_currentCircle');
                    }else{
                        update_post_meta($topic_query->post->ID,'b2_currentCircle',true);
                    }
                }
            }

            wp_reset_postdata();
        }
    }

    public static function get_default_settings($key){
        $arr = array(
            'circle_name'=>__('圈子','b2')
        );

        if(isset($arr[$key])){
            return $arr[$key];
        }
    }

    public function circle_settings(){

        //常规设置
        $circle = new_cmb2_box( array(
            'id'           => 'b2_circle_main_options_page',
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_circle_main',
            'tab_group'    => 'b2_circle_options',
            'parent_slug'     => 'b2_main_options',
            'tab_title'    => __('圈子基本设置','b2'),
            'menu_title'   => __('圈子设置','b2'),
            'save_button'     => __( '保存设置', 'b2' )
        ));

        $circle->add_field(array(
            'name'    => __( '是否启用圈子', 'b2' ),
            'id'=>'circle_open',
            'type'=>'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'before_row'=>'<p>'.sprintf(__('创建圈子的权限，请前往%s用户设置%s进行设置','b2'),'<a href="'.admin_url().'/admin.php?page=b2_normal_user" target="_blank">','</a>').'</p>',
            'default'=>self::$default_settings['circle_open']
        ));

        $circle->add_field(array(
            'name'    => __( '圈子名称', 'b2' ),
            'desc'    => __( '给圈子起一个你觉得合适的其他的名字，比如群组等', 'b2' ),
            'id'=>'circle_name',
            'type'=>'text',
            'default'=>self::get_default_settings('circle_name')
        ));

        $circle->add_field(array(
            'name'    => __( '圈子首页SEO关键词', 'b2' ),
            'desc'    => __( '圈子首页SEO关键词，多个关键词请用英文的逗号隔开，一般显示在页面标签内，做SEO用', 'b2' ),
            'id'=>'circle_keywords',
            'type'=>'text'
        ));

        $circle->add_field(array(
            'name'    => __( '圈子首页SEO描述', 'b2' ),
            'desc'    => __( '圈子首页SEO描述，一般显示在页面标签内，做SEO用', 'b2' ),
            'id'=>'circle_desc',
            'type'=>'text'
        ));

        $circle->add_field(array(
            'name'    => __( '每页显示多少个话题', 'b2' ),
            'desc'    => __( '圈子中每页显示多少个话题', 'b2' ),
            'id'=>'topic_per_count',
            'type'=>'text',
            'default'=>self::$default_settings['topic_per_count']
        ));

        $circle->add_field(array(
            'name'    => __( '圈子标签', 'b2' ),
            'desc'    => sprintf(__( '用户在创建圈子的时候需要选择该圈子属于哪个标签，方便圈子的分类管理。每个标签占一行，比如:%s%s', 'b2' ),'<br>',__('经济','b2').'<br>'.__('生活','b2').'<br>'.__('投资','b2').'<br>'.__('人文','b2').'<br>'.__('情感','b2').'<br>'.__('娱乐','b2').'<br>'.__('科技','b2')),
            'id'=>'circle_tags',
            'type'=>'textarea',
            'default'=>self::$default_settings['circle_tags']
        ));

        self::topic_settings();
        self::reset_circle_data();
    }

    public static function topic_settings(){
        $topic = new_cmb2_box(array(
            'id'           => 'b2_circle_topic_options_page',
            'tab_title'    => __('话题权限全局设置','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_circle_topic',
            'parent_slug'     => 'admin.php?page=b2_circle_main',
            'tab_group'    => 'b2_circle_options',
        ));

        self::topic_settings_items($topic);

    }

    public static function topic_settings_items($topic){
        $topic->add_field(array(
            'before_row'=>'<div id="circle-topic-custom-settings" class="cmb2-options-page"><p>'.sprintf(__('如果需要单独给某个圈子设置权限，请前往中设置%s','b2'),'<a href="'.admin_url('/edit-tags.php?taxonomy=circle_tags&post_type=circle').'" target="_blank">'.__('圈子设置->点击编辑某个圈子->帖子权限设置','b2').'</a>').'</p><div class="cmb2-metabox cmb-field-list">',
            'name' => __('话题发布之后多长时间内允许删除或编辑','b2'),
            'id'      => 'topic_delete_time',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_delete_time'],
            'desc'=>__('话题发布之后多长时间内允许用户自己删除或编辑，此处同时对编辑文章、编辑圈子问答答案、删除评论有效。请直接填写数字，单位是分钟（如果话题处于待审状态，则没有时间限制）','b2'),
        ));

        $topic->add_field(array(
            'name' => __('超过多少个待审话题之后不允许再发布话题','b2'),
            'id'      => 'topic_pending_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_pending_count'],
            'desc'=>__('圈子中发布了超过多少个待审话题后，不允许再发布话题。','b2'),
        ));

        $lvs = User::get_user_roles();

        $setting_lvs = array();
        foreach($lvs as $k => $v){
            $setting_lvs[$k] = $v['name'];
        }

        if(b2_get_option('verify_main','verify_allow')){
            $setting_lvs['verify'] = __('认证用户','b2');
        }

        $topic->add_field(array(
            'name' => __('哪些用户组允许发布提问','b2'),
            'id'   => 'topic_ask_allow',
            'type' => 'multicheck_inline',
            'before_row'=>'<p>'.sprintf(__('创建话题和圈子的权限，请前往%s用户设置%s进行设置','b2'),'<a href="'.admin_url().'/admin.php?page=b2_normal_user" target="_blank">','</a>').'</p>',
            'options'=>$setting_lvs,
            'desc'=> __('全部取消选择，则关闭提问功能','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许发起投票','b2'),
            'id'   => 'topic_vote_allow',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('全部取消选择，则关闭投票功能','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许发起你猜','b2'),
            'id'   => 'topic_guess_allow',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('全部取消选择，则关闭你猜功能','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许插入卡片','b2'),
            'id'   => 'topic_card_allow',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('全部取消选择，则关闭插入卡片功能','b2')
        ));

        $topic->add_field(array(
            'name' => __('允许哪些用户组直接发帖，不用审核','b2'),
            'id'   => 'topic_public_allow',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('如果这些用户组有发帖权限，选中后将不用审核帖子内容，直接发布状态，未选中的用户组发帖之后需要群主审核方可显示','b2')
        ));

        $topic->add_field(array(
            'name' => __('是否开启图片上传功能','b2'),
            'id'      => 'topic_image_allow',
            'type'    => 'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['topic_image_allow'],
            'desc'=>sprintf(__('开启的同时需要给当前用户允许上传图片的权限%s'),'<a target="_blank" href="'.admin_url('/admin.php?page=b2_normal_write').'">媒体设置</a>')
        ));

        $topic->add_field(array(
            'name' => __('是否开启视频上传功能','b2'),
            'id'      => 'topic_video_allow',
            'type'    => 'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['topic_video_allow'],
            'desc'=>sprintf(__('开启的同时需要给当前用户允许上传视频的权限%s'),'<a target="_blank" href="'.admin_url('/admin.php?page=b2_normal_write').'">媒体设置</a>')
        ));

        $topic->add_field(array(
            'name' => __('是否开启文件上传功能','b2'),
            'id'      => 'topic_file_allow',
            'type'    => 'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['topic_file_allow'],
            'desc'=>sprintf(__('开启的同时需要给当前用户允许上传文件的权限%s'),'<a target="_blank" href="'.admin_url('/admin.php?page=b2_normal_write').'">媒体设置</a>')
        ));

        $topic->add_field(array(
            'name' => __('话题文字限制多少个字','b2'),
            'id'      => 'topic_word_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_word_count'],
            'desc'=>__('请使用5-250这种形式，5是最小字数限制，250是最大字数限制，用户输入的话题文字不能少于5个文字，不能大于250个文字','b2'),
        ));

        $topic->add_field(array(
            'name' => __('最多上传图片多少张','b2'),
            'id'      => 'topic_image_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_image_count'],
            'desc'=>__('一个话题最多上传多少张图片，建议5张以内','b2'),
        ));

        $topic->add_field(array(
            'name' => __('最多上传视频多少个','b2'),
            'id'      => 'topic_video_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_video_count'],
            'desc'=>__('一个话题最多上传多少个视频，建议3个以内','b2'),
        ));

        $topic->add_field(array(
            'name' => __('最多上传文件多少个','b2'),
            'id'      => 'topic_file_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_file_count'],
            'desc'=>__('一个话题最多上传多少个附件，建议5个以内','b2'),
        ));

        $topic->add_field(array(
            'name' => __('最多插入卡片多少个','b2'),
            'id'      => 'topic_card_count',
            'type'    => 'input',
            'default'=>self::$default_settings['topic_card_count'],
            'desc'=>__('一个话题最多插入多少个卡片，建议3个以内','b2'),
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许设置登录可见','b2'),
            'id'   => 'topic_role_login',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选中的用户组发布话题的时候可以设置登录可见','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许设置评论可见','b2'),
            'id'   => 'topic_role_comment',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选中的用户组发布话题的时候可以设置评论可见','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许设置积分可见','b2'),
            'id'   => 'topic_role_credit',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选中的用户组发布话题的时候可以设置支付积分可见','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许设置付费可见','b2'),
            'id'   => 'topic_role_money',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选中的用户组发布话题的时候可以设置支付费用可见','b2')
        ));

        $topic->add_field(array(
            'name' => __('哪些用户组允许设置分组可见','b2'),
            'id'   => 'topic_role_lv',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选中的用户组发布话题的时候可以设置只允许某些用户组可见','b2'),
            'after_row'=>'</div></div>'
        ));
    }

    public static function reset_circle_data(){
        $bulid = new_cmb2_box(array(
            'id'           => 'b2_circle_data_options_page',
            'tab_title'    => __('重建圈子数据','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_circle_data',
            'parent_slug'     => '/admin.php?page=b2_invitation_main',
            'tab_group'    => 'b2_circle_options',
            'save_button'     => __( '重建', 'b2' ),
            'message_cb'=>array(__CLASS__,'bulid_data_cb'),
        ));

        $bulid->add_field(array(
            'name' => __('请输入需要重建的圈子ID','b2'),
            'id'      => 'circle_rebuild_id',
            'type'    => 'input',
            'desc'=>sprintf(__('如果您编辑了某个圈子的入圈权限，请在这里重建以下圈子数据，注意，如果这个圈子里面的帖子比较多（超过1万条），请将%sphp的超时时间与内存改大一些%s','b2'),'<a href="" target="_blank">','</a>'),
        ));
    }

    public static function bulid_data_cb($cmb, $args){
        if ( ! empty( $args['should_notify'] )) {
            add_settings_error( $args['setting'], $args['code'],sprintf(__( '数据重建成功。', 'b2' ),'<a href="'.admin_url('/admin.php?page=b2_invitation_list').'">邀请码列表</a>') , 'updated' );
        }
    }
}