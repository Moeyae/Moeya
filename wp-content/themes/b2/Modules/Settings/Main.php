<?php
namespace B2\Modules\Settings;

use B2\Modules\Common\CircleRelate;
use B2\Modules\Common\Cache;
class Main{
    public function init(){
        //创建设置页面
        add_action('cmb2_admin_init',array($this,'main_options_page'));
        if(!apply_filters('b2_check_role',0)) return;

        //加载css和js
        add_action( 'admin_enqueue_scripts', array( $this, 'setup_admin_scripts' ),99999 );

        
        add_action( 'enqueue_block_editor_assets', array( $this, 'setup_gd_scripts' ));
        ob_start();

        //加载设置项
        $this->load_settings();

        add_action('cmb2_admin_init',array($this,'vip_count'),99999);

        //微信菜单
        add_action('cmb2_admin_init',array($this,'weixin_menu'),99999);

        add_action( 'wp_ajax_b2_insert_settings', array($this,'wp_ajax_b2_insert_settings' ));
        

        //后台上传支持 SVG格式
        add_filter('upload_mimes', array($this,'mimes_support'));

        add_action( 'cmb2_render_radio_image', array( $this, 'callback' ), 10, 5 );
        add_filter( 'cmb2_list_input_attributes', array( $this, 'attributes' ), 10, 4 );
        
        add_action( 'cmb2_render_text_two', array($this,'cmb2_render_callback_for_text_two'), 10, 5 );

        //允许搜索用户名
        add_filter( 'user_search_columns',  array($this,'allow_search_disply_name'));

        if((int)b2_get_option('template_main','prettify_load')){
            add_action('after_wp_tiny_mce', array($this,'prettify_bottom'));
        }

        add_filter( 'manage_posts_columns', array($this,'filter_posts_columns'));
        add_action( 'manage_posts_custom_column', array($this,'realestate_column'), 10, 2);

        add_filter( 'manage_document_posts_columns', array($this,'filter_document_columns'));
        add_action( 'manage_document_posts_custom_column', array($this,'document_column'), 10, 2);
        add_action('admin_notices', array($this,'cg_note'),0);

        foreach ( array('category','circle_tags','collection','shoptype','document_cat','newsflashes_tags') as $taxonomy ) {
            add_filter( "manage_edit-${taxonomy}_columns",          array($this,'t5_add_col' ),10);
            add_action( "manage_${taxonomy}_custom_column",         array($this,'t5_show_id'),10, 3 );
        }

        add_filter( "manage_edit-collection_columns",          array($this,'t5_add_col_c' ),10);
        add_action( "manage_collection_custom_column",         array($this,'t5_show_id_c'),10, 3 );

        add_action( 'admin_print_styles-edit-tags.php', array($this,'t5_tax_id_style' ));

    }

    public function t5_add_col( $columns ){
        return array('tax_id'=>'ID') + $columns;
    }
    public function t5_show_id( $v, $name, $id ){  
        return 'tax_id' === $name ? $id : $v;
    }
    public function t5_add_col_c( $columns ){
        return array('b2_tax_index'=>__('专题期数','b2')) + $columns;
    }
    public function t5_show_id_c( $v, $name, $id ){  
        if($name == 'b2_tax_index'){
            $b2_tax_index = get_term_meta($id, 'b2_tax_index', true);
            $b2_tax_index = $b2_tax_index ? sprintf(__('第%s期','b2'),$b2_tax_index) : __('请设置一个专题期数','b2');
            return $b2_tax_index;
        }
        return $v;
    }
    public function t5_tax_id_style(){
        print '<style>#tax_id{width:4em}</style>';
    }

    public function cg_note(){
        $text = $this->check_cg();
        if($text){
            echo '<div class="" style="padding:11px 15px;margin: 5px 15px 2px 2px;box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);background:#fff;border-left:4px solid red">扩展未能正确安装。请前往 <a href="'.home_url('/wp-admin/admin.php?page=b2_main_options').'">激活页面</a> 根据提示进行操作</div>';
        }
    }

    public function cmb2_render_callback_for_text_two( $field, $value, $object_id, $object_type, $field_type ) {
        $value = wp_parse_args( $value, array(
            'top' => '',
            'bottom' => ''
        ) );
        ?>
        <div><p><label for="<?php echo $field_type->_id( '_address_1' ); ?>"><?php echo __('距离上一个模块高度','b2'); ?></label></p>
		<?php echo $field_type->input( array(
			'name'  => $field_type->_name( '[top]' ),
			'id'    => $field_type->_id( '_top' ),
			'value' => $value['top'],
			'desc'  => '',
		) ); ?>
        </div>
        <div><p><label for="<?php echo $field_type->_id( '_address_2' ); ?>'"><?php echo __('距离下一个模块高度','b2'); ?></label></p>
            <?php echo $field_type->input( array(
                'name'  => $field_type->_name( '[bottom]' ),
                'id'    => $field_type->_id( '_bottom' ),
                'value' => $value['bottom'],
                'desc'  => '',
            ) ); ?>
        </div>
        <?php
    }

    public function filter_posts_columns( $columns ) {
        
        $new['id'] = 'ID';
        $new['d_mp'] = __('微信关键词','b2');
        array_insert($columns,2,$new);
        return $columns;
    }

    public function realestate_column($column, $post_id){
        if ( 'id' === $column ) {
            echo $post_id;
            return;
        }
        if ( 'd_mp' === $column ) {
            echo get_post_meta($post_id,'single_post_mp_back_key',true);
            return;
        }
    }

    public function filter_document_columns( $columns){
        $new['d_order'] = __('文档排序','b2');
        array_insert($columns,3,$new);
        return $columns;
    }

    public function document_column($column, $post_id){
        if ( 'd_order' === $column ) {
            $id = get_post_meta($post_id,'b2_document_order',true);
            if(!$id){
                echo __('缺少排序，前台不显示','b2');
            }else{
                echo $id;
            }
        }
    }

    function wp_ajax_b2_insert_settings(){

        if(!current_user_can('administrator')) return;

        $status = apply_filters('b2_theme_check', 'check');

        $status = $status === true || $status === 'test' ? true : false;

        if(!$status){
            print json_encode(array('status'=>401,'data' =>__('请先激活主题再导入数据','b2')));
            exit;
        }

        if(strpos($_FILES['file']['type'],'text') === false){
            print json_encode(array('status'=>401,'data' =>$_FILES));
            exit;
        }
        
        $str = file_get_contents($_FILES['file']['tmp_name']);

        $arg = maybe_unserialize($str);
        foreach ($arg as $k => $v) {
                
            $arg[$k]['option_value'] = b2_strReplace('https://test.7b2.com',home_url(),maybe_unserialize($v['option_value']));
            
        }

        if(!empty($arg)){
            foreach ($arg as $k => $v) {
                if($v['option_name'] != 'b2_circle_default') {
                    update_option( $v['option_name'],$v['option_value']);
                }
            }
            
            wp_cache_flush();

        }
        print json_encode(array('status'=>401,'data' =>'success'));
        exit;

    }

    public function prettify_bottom($mce_settings) {
    ?>
        <script type="text/javascript">
        QTags.addButton( 'b2pre', '代码高亮', '<pre>\n\n</pre>', "" );//添加高亮代码
        function prettify_bottom() {
        }
        </script>
    <?php
    }
    
    public function allow_search_disply_name($search_columns){
        $search_columns[] = 'display_name';
        return $search_columns;
    }

    public function weixin_menu(){

        if(!current_user_can('administrator')) return;
        
        if(isset($_POST['weixin_menu'])){

            $settings = \B2\Modules\Common\Wecatmp::get_wecat_option();
            
            if($_POST['weixin_menu']){
                $data = stripslashes($_POST['weixin_menu']);

                $data = json_decode($data,true);
    
                try {
    
                    // 实例接口
                    $menu = new \WeChat\Menu($settings);
                
                    // 执行创建菜单
                    $menu->create($data);
                    
                } catch (Exception $e){
                    // 异常处理
                    wp_die($e->getMessage());
                }
            }else{
                try {

                    // 实例接口
                    $menu = new \WeChat\Menu($settings);
                
                    // 执行删除菜单
                    $data = $menu->delete();
                    
                } catch (Exception $e){
                    // 异常处理
                    wp_die($e->getMessage());
                }
            }
            
        }
    }

    /**
     * 加载后台使用的CSS和JS文件
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function setup_admin_scripts(){

        //wp_enqueue_script('admin-widgets');
        wp_enqueue_script( 'jike_admin_js',B2_THEME_URI.'/Assets/admin/admin.js?v='.B2_VERSION, array(
            'jquery',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
            ), B2_VERSION, true );

        wp_enqueue_style( 'jike_admin_css', B2_THEME_URI.'/Assets/admin/admin.css?v='.B2_VERSION, B2_VERSION, null);
    }

    public function setup_gd_scripts(){
        // 古腾堡编辑器扩展
        if (function_exists('register_block_type')) { //判断是否使用古腾堡编辑器
            wp_register_script( //引入核心js文件
                'b2_block_js',
                B2_THEME_URI.'/Assets/admin/gd_block.js?v='.B2_VERSION,
                array( 'wp-blocks', 'wp-element', 'wp-editor','wp-i18n', 'wp-components' )
            );

            wp_register_style(  //引入css外观样式文件
                'b2_block_css',
                B2_THEME_URI.'/Assets/admin/gd_block.css?v='.B2_VERSION,
                array( 'wp-edit-blocks' )
            );

            register_block_type( 'b2/block', array(
                'editor_script' => 'b2_block_js',
                'editor_style'  => 'b2_block_css',
            ) );
        }
    }

    /**
     * 后台文件上传支持SVG格式
     *
     * @param array $file_types
     *
     * @return array
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function mimes_support($file_types){
        $new_filetypes = array();
        $new_filetypes['svg'] = 'image/svg+xml';
        $file_types = array_merge($file_types, $new_filetypes );
    
        return $file_types;
    }

    /**
     * 加载后台的设置页面及设置项
     *
     * @return bool
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function load_settings(){

        do_action('b2_setting_action');

        //自定义菜单功能
        $menu = new Menu();
        $menu->init();

        //Tax页面设置项
        $tax = new Taxonomies();
        $tax->init();

        //加载邀请码设置
        $menu = new Invitation();
        $menu->init();

        //文章页面设置
        $post = new Post();
        $post->init();

        //自定义编辑器按钮
        $post = new Editor();
        $post->init();

        //加载SEO
        $seo = new Seo();
        $seo->init();

        $users = new Users();
        $users->init();

        //商铺管理
        $shop = new Shop();
        $shop->init();

        //订单管理
        $orders = new Orders();
        $orders->init();

        //文档
        $document = new Document();
        $document->init();

        //快讯
        $newsflashes = new Newsflashes();
        $newsflashes->init();

        //圈子
        $circle = new Circle();
        $circle->init();

        //卡密管理
        $orders = new Card();
        $orders->init();

        //认证管理
        $orders = new Verify();
        $orders->init();

        //多级分销
        $distribution = new Distribution();
        $distribution->init();

        //提现
        $cash_out = new CashOut();
        $cash_out->init();
    }

    /**
    * 获取设置项
    *
    * @param string $where 设置项的组别，默认是某个组别设置项的类名
    * @param string $key 设置项的KEY
    *
    * @return string
    * @return int
    * @return array
    * @author Li Ruchun <lemolee@163.com>
    * @version 1.0.0
    * @since 2018
    */
    public static function get_option($where,$key,$circle_id = 0){

        if($circle_id){
            $setting = apply_filters('b2_get_circle_setting_by_id', array('circle_id'=>$circle_id,'key'=>$key));
            if($setting !== false){
                return $setting;
            }
            
        }

        $settings = get_option('b2_'.$where);
        if(isset($settings[$key])){
            return $settings[$key];
        }else{
            $class = 'B2\Modules\Settings\\'.ucfirst(substr($where,0,strpos($where, '_')));
            if(file_exists(B2_THEME_DIR.str_replace('\\', B2_DS, str_replace('B2','',$class)).'.php')){
                $default = $class::get_default_settings($key);

                if($default){
                    return $default;
                }else{
                    $default = $class::$default_settings;
                    if(isset($default[$key])){
                        return $default[$key];
                    }else{
                        return '';
                    }
                    return '';
                }
            }
        }

        return '';
    }

    public function callback($field, $escaped_value, $object_id, $object_type, $field_type_object) {
        echo $field_type_object->radio();
    }

    public function attributes($args, $defaults, $field, $cmb) {
        if ($field->args['type'] == 'radio_image' && isset($field->args['images'])) {
            foreach ($field->args['images'] as $field_id => $image) {
                if ($field_id == $args['value']) {
                    $image = trailingslashit($field->args['images_path']) . $image;
                    $args['label'] = '<img src="' . $image . '" alt="' . $args['value'] . '" title="' . $args['label'] . '" /><br><span>'.$args['label'].'</span>';
                }
            }
        }
        return $args;
    }

    /**
     * 创建设置页面
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function main_options_page(){

        $options = new_cmb2_box(array(
            'id'	=>	'b2_main_options_page',
            'title'	=>	__('B2主题设置','b2'),
            'icon_url'	=>	'dashicons-admin-generic',
            'option_key'      => 'b2_main_options',
            'show_on'	=>	array(
                'options-page'	=>'b2_main_options',
            ),
            'object_types' => array( 'options-page' ),
            'display_cb'      => array($this,'main_option_page_cb'),
            'menu_title'    => __('B2主题设置','b2'),
            'tab_title'   => __('B2主题设置','b2'),
        ));
    }

    public function check_cg(){
        preg_match("#^\d.\d#", PHP_VERSION, $p_v);

        $text = '';

        if($p_v[0] < '7.0'){
            $text = '<h2 class="red">请升级您的PHP，建议使用 PHP7.3</h2>';
        }

        if($p_v[0] >= '8.0'){
            $text = '<h2 class="red">当前版本暂未支持 php8.0，建议使用 php7.3 版本</h2> ';
        }

        $loader_name = PATH_SEPARATOR==':' ? 'loader'.str_replace('.','',$p_v[0]).'.so' : 'win_loader'.str_replace('.','',$p_v[0]).'.dll';

        $path = B2_THEME_DIR;

        $path =  PATH_SEPARATOR!=':' ? str_replace('/',B2_DS,$path) : $path;

        if(!$text){
            if(extension_loaded('swoole_loader')){
                $ext = new \ReflectionExtension('swoole_loader');
                $ver = $ext->getVersion();

                if($ver >= '3.0'){
                    $text = '<h2 class="red">当前版本暂未支持 php8.0，建议使用 php7.3 版本 </h2>';
                }

                if($ver < '2.2' || $ver >= '3.0'){
                    $text = '<h2 class="red">升级扩展：请按照如下提示进行操作</h2>
                    <p>'.sprintf(__('1、打开您的php.ini文件（%s），删除类似%s的整行代码。一般在php.ini文件最下面几行','b2'),'<code>'.php_ini_loaded_file().'</code>','<code>='.$loader_name.'</code>').'</p>
                    <p>'.sprintf(__('2、将%s复制到php.ini文件的最后一行保存','b2'),'<code>extension='.$path.B2_DS.'Assets'.B2_DS.'admin'.B2_DS.'loader'.B2_DS.$loader_name.'</code>').'</p>
                    <p>'.__('3、重启php','b2').'</p>
                    <p>'.__('4、刷新本页后激活','b2').'</p>';
                }
    
            }else{
                $text = '<h2 class="red">请安装扩展</h2>
                <p>'.__('未安装扩展，请按照下面的方法进行安装','b2').'</p>
                <p>'.sprintf(__('1、打开您的php.ini文件（%s），然后将%s复制到php.ini文件的最后一行保存','b2'),'<code>'.php_ini_loaded_file().'</code>','<code>extension='.$path.B2_DS.'Assets'.B2_DS.'admin'.B2_DS.'loader'.B2_DS.$loader_name.'</code>').'</p>
                <p>'.__('2、重启php','b2').'</p>
                <p>'.__('3、刷新本页后激活','b2').'</p>';
            }
        }

        return $text;
    }

    /**
     * 设置页面首页，欢迎页面
     *
     * @return string
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public function main_option_page_cb(){
        $status = apply_filters('b2_theme_check', 'check');

        $status = $status === true || $status === 'test' ? true : false;
        
        $id = apply_filters('b2_get_theme_id',1);
        $id = isset($id['id']) ? (int)$id['id'] : '';

        $text = $this->check_cg();
        ?>
        <div id="b2-settings-opt"></div>
        <div class="wrap">
            <style>
                .jihuo p{
                    font-size:15px
                }
            </style>
            <h1><?php echo __('感谢您使用B2主题','b2');?></h1>
                <?php if($text){ echo '<div class="jihuo">'.$text.'</div>';}else{  ?>
                    <h2 class="title"><?php echo __('激活','b2');?></h2>
                    <form method="post">
                        <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
                
                        <?php
                            echo '<div style="margin-top:20px;margin-bottom:10px">主题当前状态：'.($status ? '<b style="color:green">已激活</b>' : '<b style="color:red">未激活</b>').'</div>';
                        ?>

                        <input type="text" value="<?php echo $id  ?>" name="zrz_theme_id">
                        <p><?php echo __('请在官网个人中心查看是第几号会员，然后把会员号填在此处激活','b2'); ?></p>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $status ? '手动更新授权' : '激活' ;?>"></p>
                    </form>
                    <?php if($status){ ?>
                    <h2 class="title"><?php echo __('设置项导出','b2');?></h2>
                    <a class="button empty" id="b2-settings-output" href="<?php echo admin_url('?page=b2_main_options&output_settings=1'); ?>"><?php echo __('导出B2主题设置项','b2'); ?></a>
                    <p class="desc"><?php echo sprintf(__('%s如果您的主题已经设置完毕，或者有其他重要的设置需要变更，建议及时导出.%s只限B2主题的设置项和小工具，不包括文章、分类设置、订单、卡密、邀请码等数据%s','b2'),'<p class="red">','<br>','</p>'); ?></p>

                    <h2 class="title"><?php echo __('设置项导入','b2');?></h2>
                    <label class="button empty"><?php echo __('导入B2主题设置项','b2'); ?><input type="file" accept="text/plain" style="display:none" onchange="b2getFilename(event)"></label>
                    <p class="desc"><?php echo __('1、导入的设置项将会替换原有的设置项，请谨慎操作。导入的文件格式为.txt','b2'); ?></p>
                    <p class="desc"><?php echo __('2、如果您已有设置好的项目，请先导出备份一下。导入的文件格式为.txt','b2'); ?></p>
                    <?php } ?>
                <?php } ?>

        <h2><?php echo __('说明','b2'); ?></h2>
        <p>感谢您选择B2主题，这是一个来自未来的主题，我们使用了众多新的技术，及其方便的扩展能力让您不必操心站点的问题，专心经营内容。<p>
        <p>我们已经为2000多个用户提供了优质的服务，您将也是其中之一。如果您还没有购买主题，请加QQ联系我们：110613846</p>
        <p>B2主题涉及到众多敏感信息，包括支付宝，微信的账户的私密信息，实名认证信息等，请不要使用来路不明或未经授权的主题，否则造成的一切后果我们概不负责</p>
        <p>未激活的主题不能正常使用，请第一时间激活主题</p>
        <p>如果您已经购买了我们的主题，请加入我们的售后群：424186042</p>
        <h2>主题安装方法：</h2>
        <p>请根据下面的提示进行安装：</p>
        <p>1、设置服务器伪静态和固定连接：<a href="https://7b2.com/document/36827.html" target="_blank">设置伪静态和固定连接</a></p>
        <p>2、然后安装jwt：<a href="https://7b2.com/document/38556.html" target="_blank">安装jwt</a></p>
        </div>
    <?php
    }

    public function vip_count(){


        if(isset($_REQUEST['index_group'])){
            Cache::clean_index_module_cache();
            wp_cache_incr( 'widget' );
        }

        if(isset($_REQUEST['user_slug'])){

            global $wpdb;
            $vip_info = b2_get_option('normal_user','user_vip_group');
            $count = array();
            foreach ($vip_info as $k => $v) {
                $row = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT COUNT(*)
                        FROM {$wpdb->usermeta}
                        WHERE meta_key = %s AND meta_value = %s
                    ",'zrz_vip','vip'.$k),
                    ARRAY_N
                );

                $count['vip'.$k] = $row[0];
            }

            update_option('b2_vip_count',$count);
        }

        if(isset($_GET['page']) && $_GET['page'] === 'b2_verify_list' && isset($_GET['status']) && isset($_GET['action']) && $_GET['action'] ==='edit' ){

            
            global $wpdb;
            $table_name = $wpdb->prefix . 'b2_verify';
            $res = $wpdb->get_row(
                $wpdb->prepare("
                    SELECT * FROM $table_name
                    WHERE user_id=%s
                    ",
                    $_GET['user_id']
            ),ARRAY_A);

            $data = array(
                'user_id'=>$_GET['user_id'],
                'verified'=>$_GET['verified'],
                'name'=>$_GET['name'],
                'identification'=>$_GET['identification'],
                'card'=>$_GET['card'],
                'title'=>$_GET['title'],
                'status'=>$_GET['status'],
            );
            \B2\Modules\Common\Verify::add_verify_data($data);

            if((int)$res['status'] === 4 && (int)$_GET['status'] === 2){

                $task_check = get_user_meta($_GET['user_id'],'b2_task_check',true);
               
                if($task_check === ''){
                    $credit = b2_get_option('normal_task','task_user_verify');
                    if((int)$credit !== 0){
                        $total = \B2\Modules\Common\Credit::credit_change($_GET['user_id'],$credit);
        
                        //积分记录
                        \B2\Modules\Common\Message::add_message(array(
                            'user_id'=>$_GET['user_id'],
                            'msg_type'=>60,
                            'msg_read'=>0,
                            'msg_date'=>current_time('mysql'),
                            'msg_users'=>'',
                            'msg_credit'=>$credit,
                            'msg_credit_total'=>$total,
                            'msg_key'=>'',
                            'msg_value'=>''
                        ));

                        update_user_meta($_GET['user_id'],'b2_task_check',1);
                    }
                }
            }

            do_action('b2_notify_verify_change',$_GET['user_id'],$res['status'],(int)$_GET['status']);

            if((int)$_GET['status'] === 1 || (int)$_GET['status'] === 3 || (int)$_GET['status'] === 4){
                delete_user_meta($_GET['user_id'], 'b2_title');
            }else{
                update_user_meta($_GET['user_id'],'b2_title',$_GET['title']);
            }
            wp_cache_delete('b2_user_'.$_GET['user_id'],'b2_user_data');
            wp_cache_delete('b2_user_'.$_GET['user_id'],'b2_user_custom_data');
        }

        if(isset($_GET['output_settings']) && (int)$_GET['output_settings'] === 1){
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM $wpdb->options WHERE (`option_name` LIKE '%b2\_%' OR `option_name` LIKE '%widget\_b2%' OR  `option_name`='sidebars_widgets' OR `option_name`='theme_mods_b2') AND `option_name` NOT LIKE '%\_transient\_%'",ARRAY_A);

            $date = current_time('mysql');
            $date = date_create($date);

            $y = date_format($date,'Y');
            $m = date_format($date,'m');
            $d = date_format($date,'d');
            $time = date_format($date,'H');

            header("Content-Type: application/octet-stream");    
            $center = serialize($results);   
            $filename = 'b2-settings-'.$y.'-'.$m.'-'.$d.'-'.$time.'.txt';//生成的文件名 
            if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) { 
                header('Content-Disposition:  attachment; filename="' . $encoded_filename . '"'); 
            } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) { 
                // header('Content-Disposition: attachment; filename*="utf8' .  $filename . '"');
                header('Content-Disposition: attachment; filename*="' .  $filename . '"'); 
            } else { 
                header('Content-Disposition: attachment; filename="' .  $filename . '"'); 
            }
            echo $center;
            exit;
        }

        if(isset($_POST['b2_circle_admin']) && !empty($_POST['b2_circle_admin'])){

            global $wpdb;
            $table_name = $wpdb->prefix . 'b2_circle_related';

            $old =  $wpdb->get_row(
                $wpdb->prepare("
                    SELECT * FROM $table_name
                    WHERE circle_id= %d
                    AND circle_role=%s
                    ",
                    (int)$_POST['tag_ID'],'admin'
            ),ARRAY_A);

            if(empty($old)){
                CircleRelate::update_data(array(
                    'user_id'=>(int)$_POST['b2_circle_admin'],
                    'circle_id'=>(int)$_POST['tag_ID'],
                    'circle_role'=>'admin',
                    'join_date'=>current_time('mysql')
                ));
            }else{
                CircleRelate::update_data(
                    array(
                        'circle_role' => 'member',
                    ),
                    array(
                        'user_id'=>$old['user_id'],
                        'circle_id'=>(int)$_POST['tag_ID'],
                        'circle_role'=>'admin',
                    )
                );

                $old_user =  $wpdb->get_row(
                    $wpdb->prepare("
                        SELECT * FROM $table_name
                        WHERE user_id=%d
                        AND circle_id= %d
                        ",
                        (int)$_POST['b2_circle_admin'],(int)$_POST['tag_ID']
                ),ARRAY_A);

                if($old_user){
                    CircleRelate::update_data(
                        array(
                            'circle_role' => 'admin',
                        ),
                        array(
                            'circle_id'=>(int)$_POST['tag_ID'],
                            'user_id'=>(int)$_POST['b2_circle_admin'],
                            'circle_role'=>$old_user['circle_role']
                        )
                    );
                }else{
                    CircleRelate::update_data(array(
                        'user_id'=>(int)$_POST['b2_circle_admin'],
                        'circle_id'=>(int)$_POST['tag_ID'],
                        'circle_role'=>'admin',
                        'join_date'=>current_time('mysql')
                    ));
                }
            }
        }
        
    }
}