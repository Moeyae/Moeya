<?php
namespace B2\Modules\Settings;

use B2\Modules\Common\User;

class Shop{

    public static $default_settings = array(
        'shop_open'=>1,
        'shop_name'=>'商铺',
        'shop_slider'=>'',
        'shop_slider_height'=>400,
        'shop_slider_title'=>0,
        'shop_cat_row_count'=>12,
        'shop_cat_img_ratio'=>'4/3',
        'shop_type'=>array('normal','exchange','lottery'),
        'shop_type_count'=>6,
    );

    public function init(){
        add_action('cmb2_admin_init',array($this,'shop_settings'));

        add_filter( 'manage_shop_posts_columns', array(__CLASS__,'set_edit_shop_columns' ));
        add_action( 'manage_shop_posts_custom_column' , array(__CLASS__,'shop_column'), 10, 4 );

        add_action( 'cmb2_override_option_save_b2_coupon_bulid', array($this,'save_action'), 10, 3 );
    }

    public static function get_default_settings($key){
        $arr = array(
            'shop_cat'=>array()
        );

        if(isset($arr[$key])){
            return $arr[$key];
        }
    }

    //商品管理页面添加栏目
    public static function set_edit_shop_columns($columns) {

        $columns['zrz_shop_type'] = __( '商品类型', 'b2' );
        $columns['zrz_shop_commodity'] = __( '是虚拟物品吗', 'b2' );

        return $columns;
    }

    public static function shop_column($column, $post_id){
        switch ( $column ) {

            case 'zrz_shop_type' :
                $terms = get_post_meta($post_id, 'zrz_shop_type', true);
                if($terms == 'normal'){
                    echo '出售';
                }elseif($terms == 'lottery'){
                    echo '积分抽奖';
                }else{
                    echo '积分兑换';
                }
                break;
            case 'zrz_shop_commodity' :
                $terms = get_post_meta($post_id, 'zrz_shop_commodity', true);
                if($terms == 1){
                    echo '实物';
                }else{
                    echo '虚拟物品';
                }
                break;
        }
    }

    public function shop_settings(){
        //常规设置
        $shop = new_cmb2_box( array(
            'id'           => 'b2_shop_main_options_page',
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_shop_main',
            'tab_group'    => 'b2_shop_options',
            'parent_slug'     => 'b2_main_options',
            'tab_title'    => __('商品首页','b2'),
            'menu_title'   => __('商铺设置','b2'),
            'save_button'     => __( '保存设置', 'b2' )
        ));

        $shop->add_field(array(
            'name'    => __( '是否启用商铺', 'b2' ),
            'id'=>'shop_open',
            'type'=>'select',
            'options'=>array(
                1=>__('开启','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['shop_open']
        ));

        $shop->add_field(array(
            'name'    => __( '商铺名称', 'b2' ),
            'desc'    => __( '给商铺取一个名字，比如小店、商城等。', 'b2' ),
            'id'=>'shop_name',
            'type'=>'text',
            'default'=>self::$default_settings['shop_name']
        ));

        $shop->add_field(array(
            'name'    => __( '商铺SEO关键词', 'b2' ),
            'desc'    => __( '商铺SEO关键词，多个关键词请用英文的逗号隔开，一般显示在页面标签内，做SEO用', 'b2' ),
            'id'=>'shop_keywords',
            'type'=>'text'
        ));

        $shop->add_field(array(
            'name'    => __( '商铺SEO描述', 'b2' ),
            'desc'    => __( '商铺SEO描述，一般显示在页面标签内，做SEO用', 'b2' ),
            'id'=>'shop_desc',
            'type'=>'text'
        ));

        $shop->add_field(
            array(
                'name' => __('商铺首页幻灯内容','b2'),
                'id'   => 'shop_slider',
                'type' => 'textarea_code',
                'description'=>sprintf(__('支持所有文章类型（文章，商品等），每组占一行，排序与此设置相同（不设置请留空）。图片可以在%s上传或选择。
                %s
                支持的格式如下：
                %s','b2'),
                '<a target="__blank" href="'.admin_url('/upload.php').'">媒体中心</a>','<br>','
                <br>文章ID+幻灯图片地址：<code>123<span class="red">|</span>https://xxx.com/wp-content/uploads/xxx.jpg</code><br>
                文章ID+文章默认的缩略图：<code>3434<span class="red">|</span>0</code><br>
                网址连接+幻灯图片地址+标题（适合外链到其他网站）：<code>https://www.xxx.com/123.html<span class="red">|</span>https://xxx.com/wp-content/uploads/xxx.jpg<span class="red">|</span>标题</code><br>
                '),
                'options' => array( 'disable_codemirror' => true ),
                'before_row'=>'<h2>商铺幻灯设置</h2>'
            )
        );

        $shop->add_field(array(
            'name'    => __( '商铺首页幻灯高度', 'b2' ),
            'id'=>'shop_slider_height',
            'type'=>'text',
            'default'=>self::$default_settings['shop_slider_height']
        ));

        $shop->add_field(array(
            'name'    => __( '商铺首页幻灯是否显示标题', 'b2' ),
            'id'=>'shop_slider_title',
            'type'=>'select',
            'options'=>array(
                1=>__('显示','b2'),
                0=>__('关闭','b2')
            ),
            'default'=>self::$default_settings['shop_slider_title']
        ));

        $shop->add_field(array(
            'name' => __('商铺首页允许显示的商品分类','b2'),
            'id'   => 'shop_cat',
            'type' => 'taxonomy_multicheck_hierarchical',
            'taxonomy'=>'shoptype',
            'text'           => array(
                'no_terms_text' => sprintf(__('没有分类，请前往%s添加','b2'),'<a target="__blank" href="'.admin_url('//edit-tags.php?taxonomy=shoptype&post_type=shop').'"></a>')
            ),
            'remove_default' => 'true',
            'query_args' => array(
                'orderby' => 'count',
                'hide_empty' => false,
            ),
            'select_all_button' => true,
            'desc'=>__('请确保您的分类别名不是中文，否则无法选中，全部取消将不显示','b2'),
            'before_row'=>'<h2>商铺分类导航设置</h2>'
        ));

        $shop->add_field(array(
            'name'    => __( '商铺首页分类每行显示的数量', 'b2' ),
            'id'=>'shop_cat_row_count',
            'type'=>'text',
            'default'=>self::$default_settings['shop_cat_row_count']
        ));

        $shop->add_field(array(
            'name'    => __( '商铺首页分类背景图比例', 'b2' ),
            'id'=>'shop_cat_img_ratio',
            'type'=>'text',
            'default'=>self::$default_settings['shop_cat_img_ratio']
        ));

        $shop->add_field(array(
            'name'    => __( '要显示的产品类型', 'b2' ),
            'id'=>'shop_type',
            'type'=>'multicheck_inline',
            'options'=>array(
                'normal'=>__('购买','b2'),
                'exchange'=>__('积分兑换','b2'),
                'lottery'=>__('积分抽奖','b2')
            ),
            'default'=>self::$default_settings['shop_type'],
            'before_row'=>'<h2>商品设置</h2>'
        ));

        $shop->add_field(array(
            'name'    => __( '每种类型显示多少个', 'b2' ),
            'id'=>'shop_type_count',
            'type'=>'text',
            'default'=>self::$default_settings['shop_type_count'],
        ));

        $this->shop_coupon_settings();
        $this->coupon_list();
        $this->shop_post_settings();
    }

    public function shop_coupon_settings(){
        //常规设置
        $coupon = new_cmb2_box(array(
            'id'           => 'b2_coupon_bulid_options_page',
            'tab_title'    => __('优惠劵创建','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_coupon_bulid',
            'parent_slug'     => '/admin.php?page=b2_shop_main',
            'tab_group'    => 'b2_shop_options',
            'message_cb'=>array($this,'bulid_message_cb'),
            'save_button'     => __( '创建', 'b2' ),
            'before_row'=>'<p>下面为优惠劵的使用条件，请按照说明进行创建，创建之后优惠劵列表中会显示一条优惠劵</p>'
        ));

        $coupon->add_field( array(
            'name'    => __( '优惠劵面值', 'b2' ),
            'id'      => 'coupon_money',
            'type' => 'text_money',
            'sanitization_cb' => 'b2_sanitize_number',
            'before_field' => B2_MONEY_SYMBOL,
            'default'=>30
        ) );

        $coupon->add_field( array(
            'name'    => __( '优惠劵领取有效期', 'b2' ),
            'id'      => 'coupon_days',
            'type'    => 'text',
            'default'=>0,
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'desc'=>__('请直接填入天数，永久有效请填写0，有效期从创建成功的那一刻开始计算，过期以后用户将无法领取，已经领取的用户可以在优惠劵使用有效期内进行使用。')
        ) );

        $coupon->add_field( array(
            'name'    => __( '优惠劵使用有效期', 'b2' ),
            'id'      => 'coupon_use_days',
            'type'    => 'text',
            'default'=>0,
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'desc'=>__('请直接填入天数，永久有效请填写0，有效期从用户领取优惠劵的时刻开始计算，超过时效作废，并且不能再次领取。')
        ) );

        $lvs = User::get_user_roles();

        $setting_lvs = array();
        foreach($lvs as $k => $v){
            $setting_lvs[$k] = $v['name'];
        }

        $coupon->add_field(array(
            'name' => __('允许领取的用户组','b2'),
            'id'   => 'coupon_allow_roles',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('请选择允许领取优惠劵的用户组，如果不选择，则所有人都可以领取','b2'),
        ));

        $coupon->add_field(array(
            'name' => __('允许使用的商品分类','b2'),
            'id'   => 'coupon_cats',
            'type' => 'taxonomy_multicheck_hierarchical',
            'taxonomy'=>'shoptype',
            // Optional :
            'text'           => array(
                'no_terms_text' => sprintf(__('没有分类，请前往%s添加','b2'),'<a target="__blank" href="'.admin_url('/edit-tags.php?taxonomy=collection').'">分类管理</a>') // Change default text. Default: "No terms"
            ),
            'remove_default' => 'true', // Removes the default metabox provided by WP core.
            // Optionally override the args sent to the WordPress get_terms function.
            'query_args' => array(
                'orderby' => 'count',
                'hide_empty' => false,
            ),
            'select_all_button' => true,
            'desc'=>__('请确保您的分类别名不是中文，否则无法选中。不选择则不限制使用，选中则只允许这些分类的产品使用优惠劵','b2'),
        ));

        $coupon->add_field(array(
            'name' => __('允许使用的单个商品','b2'),
            'id'   => 'coupon_products',
            'type' => 'textarea',
            'desc'=>__('请直接输入产品的ID，每个ID占一行。如果设置了此项，优惠劵将只允许此商品使用，否则此项不做为限制条件','b2'),
        ));

    }

    public function coupon_list(){
        //优惠劵列表
        $coupon = new_cmb2_box(array(
            'id'           => 'b2_coupon_list_options_page',
            'tab_title'    => __('优惠劵列表','b2'), 
            'object_types' => array( 'options-page' ),
            'option_key'      => 'b2_coupon_list',
            'parent_slug'     => '/admin.php?page=b2_shop_main',
            'tab_group'    => 'b2_shop_options',
            'display_cb'=>array($this,'coupon_list_table'),
            'save_button'=>false
        ));
    }

    static function cb_options_page_tabs( $cmb_options ) {
        $tab_group = $cmb_options->cmb->prop( 'tab_group' );
        $tabs      = array();
        foreach ( \CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
            if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
                $tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
                    ? $cmb->prop( 'tab_title' )
                    : $cmb->prop( 'title' );
            }
        }
        return $tabs;
    }

    public function coupon_list_table($cmb_options){
        $tabs = $this->cb_options_page_tabs( $cmb_options );
        $coupon = new CouponListTable();
        $coupon->prepare_items();
        $status = isset($_GET["invitation_status"]) ? esc_sql($_GET["invitation_status"]) : 'all';

        if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'delete')){
                
            $order_ids = isset($_REQUEST['id']) ? (array)$_REQUEST['id'] : '';

            if($order_ids){
                $coupon->delete_coupons($order_ids);

                $ref_url = wp_get_referer();
                $ref_url = remove_query_arg(array('id', 'action','action2','s'), $ref_url);
                exit(header("Location: ".$ref_url));
                //echo '<script> location.replace("'.$ref_url.'"); </script>';
            }
        }
        ?>
        <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
            <?php if ( get_admin_page_title() ) : ?>
                <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
            <?php endif; ?>
            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                    <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
                <?php endforeach; ?>
            </h2>
            <div class="wrap">
                <form id="coupon-filter" method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

                    <?php $coupon->display() ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function bulid_message_cb($cmb, $args){
        if ( ! empty( $args['should_notify'] )) {
            add_settings_error( $args['setting'], $args['code'],sprintf(__( '优惠劵生成成功， 请前往%s查看。', 'b2' ),'<a href="'.admin_url('/admin.php?page=b2_coupon_list').'">优惠劵列表</a>') , 'updated' );
        }
    }

    public function save_action($cmb2_no_override_option_save, $this_options, $instance ){
        if(isset($this_options['coupon_days']) && isset($this_options['coupon_use_days'])){

            $ids = array();
            if(isset($this_options['coupon_products']) && !empty($this_options['coupon_products'])){
                $str = explode(PHP_EOL, $this_options['coupon_products']);
                if(!empty($str)){
                    foreach ($str as $v) {
                        if($v){
                            $ids[] = trim($v, " \t\n\r\0\x0B\xC2\xA0");
                        }
                    }
                }
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'b2_coupon';
            $res = $wpdb->insert($table_name, array(
                'money'=> $this_options['coupon_money'],
                'receive_date'=> $this_options['coupon_days'] === '0' ? 0 : date("Y-m-d H:i:s",strtotime("+".$this_options['coupon_days']." day")),
                'expiration_date'=> $this_options['coupon_use_days'],
                'roles'=> maybe_serialize($this_options['coupon_allow_roles']),
                'cats'=>maybe_serialize($this_options['coupon_cats']),
                'products'=>maybe_serialize(!empty($ids) ? $ids : '')
            ));
            
        }
    }

    public function shop_post_settings(){
        $shop = new_cmb2_box(array( 
            'id'            => 'shop_metabox',
            'title'         => __( '商品属性', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $shop->add_field(array( 
            'name'  =>__('购买方式','b2'),
            'id'=>'zrz_shop_type',
            'type'=>'select',
            'options'=>array(
                'normal'=>__('购买','b2'),
                'lottery'=>__('积分抽奖','b2'),
                'exchange'=>__('积分兑换','b2')
            ),
        ));

        $shop->add_field(array( 
            'name'  =>__('商品类型','b2'),
            'id'=>'zrz_shop_commodity',
            'type'=>'select',
            'options'=>array(
                1=>__('实物','b2'),
                0=>__('虚拟物品','b2')
            ),
            'desc'=>__('如果选择虚拟物品，请在左下设置虚拟物品的相关参数','b2')
        ));

        $price = new_cmb2_box(array( 
            'id'            => 'shop_price_metabox',
            'title'         => __( '商品价格', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $price->add_field(array( 
            'name'  =>__('正常价格','b2'),
            'id'=>'shop_price',
            'type'=>'text_money',
            'sanitization_cb' => 'b2_sanitize_number',
            'desc'=>__('正常价格必填','b2')
        ));

        $price->add_field(array( 
            'name'  =>__('折后价格','b2'),
            'id'=>'shop_d_price',
            'type'=>'text_money',
            'sanitization_cb' => 'b2_sanitize_number',
            'desc'=>__('如果不设置此价格，请直接留空','b2')
        ));

        $price->add_field(array( 
            'name'  =>__('会员价格','b2'),
            'id'=>'shop_u_price',
            'type'=>'text_money',
            'sanitization_cb' => 'b2_sanitize_number',
            'desc'=>__('如果不设置此价格，请直接留空','b2')
        ));

        $price->add_field(array( 
            'name'  =>__('奖励积分','b2'),
            'id'=>'shop_price_credit',
            'type'=>'text',
            'desc'=>__('如果是会员，优先使用会员价格，其次使用折后价格，如果会员价格和折后价格都没有设置，则默认使用正常价格。奖励积分是用户购买之后获得的积分奖励值。','b2')
        ));

        $price->add_field(array( 
            'name'  =>__('外链商品','b2'),
            'id'=>'shop_link',
            'type'=>'text',
            'desc'=>__('如果此商品为外链商品，请直接在此处输入外链商品的连接，用户点击之后会进行跳转','b2')
        ));

        // $coupon = new_cmb2_box(array( 
        //     'id'            => 'shop_coupon_metabox',
        //     'title'         => __('优惠劵', 'b2' ),
        //     'object_types'  => array( 'shop'),
        //     'context'       => 'side',
        //     'priority'      => 'high',
        //     'show_names'    => true,
        // ));

        // $coupon->add_field(array( 
        //     'name'  =>__('是否允许使用优惠劵','b2'),
        //     'id'=>'shop_coupon',
        //     'type'=>'select',
        //     'options'=>array(
        //         1=>__('允许','b2'),
        //         0=>__('不允许','b2')
        //     )
        // ));

        $lottery = new_cmb2_box(array( 
            'id'            => 'shop_lottery',
            'title'         => __( '抽奖设置', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $lottery->add_field(array( 
            'name'  =>__('命中率','b2'),
            'id'=>'shop_lottery_probability',
            'type'=>'text',
            'default'=>'0.01',
            'desc'=>__('默认0.01 即1%的命中率，请直接填写小数','b2')
        ));

        $lottery->add_field(array( 
            'name'  =>__('抽奖所需积分','b2'),
            'id'=>'shop_lottery_credit',
            'type'=>'text',
            'default'=>'1000',
            'desc'=>__('抽奖完成会扣除这些积分','b2')
        ));

        $lottery->add_field(array( 
            'name'  =>__('商品原价','b2'),
            'id'=>'shop_lottery_price',
            'type'=>'text',
            'default'=>'',
            'desc'=>__('展示给用户，当前抽奖商品的原价是多少','b2')
        ));

        $lvs = User::get_user_roles();

        $setting_lvs = array();
        foreach($lvs as $k => $v){
            $setting_lvs[$k] = $v['name'];
        }

        $lottery->add_field(array(
            'name' => __('允许抽奖的用户组','b2'),
            'id'   => 'shop_lottery_roles',
            'type' => 'multicheck_inline',
            'options'=>$setting_lvs,
            'desc'=> __('选择允许抽奖的用户组后将只允许这些用户抽奖，如果不选择所有人都可以抽奖','b2'),
        ));

        $exchange = new_cmb2_box(array( 
            'id'            => 'shop_exchange',
            'title'         => __( '积分兑换', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $exchange->add_field(array( 
            'name'  =>__('兑换所需积分','b2'),
            'id'=>'shop_exchange_credit',
            'type'=>'text',
            'default'=>'1000',
            'desc'=>__('兑换完成会扣除用户的相应积分','b2')
        ));

        $exchange->add_field(array( 
            'name'  =>__('商品原价','b2'),
            'id'=>'shop_exchange_price',
            'type'=>'text',
            'default'=>'',
            'desc'=>__('展示给用户，当前积分兑换的商品的原价是多少','b2')
        ));

        $count = new_cmb2_box(array( 
            'id'            => 'shop_count_metabox',
            'title'         => __( '商品数量', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $count->add_field(array( 
            'name'  =>__('商品总数','b2'),
            'id'=>'shop_count',
            'type'=>'text',
            'default'=>9999
        ));

        $count->add_field(array( 
            'name'  =>__('已经出售或兑换数量：','b2'),
            'id'=>'shop_count_sell',
            'type'=>'text',
            'default'=>0
        ));

        $count->add_field(array( 
            'name'  =>__('是否隐藏销售数量','b2'),
            'id'=>'shop_count_hidden',
            'type'=>'select',
            'options'=>array(
                1=>__('隐藏销售数量','b2'),
                0=>__('显示销售数量','b2')
            ),
            'default'=>0
        ));

        $xuni = new_cmb2_box(array( 
            'id'            => 'shop_xuni',
            'title'         => __( '虚拟物品购买结果', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $xuni->add_field(array( 
            'name'  =>__('虚拟物品类型','b2'),
            'id'=>'shop_xuni_type',
            'type'=>'select',
            'options'=>array(
                'html'=>__('文本','b2'),
                'cards'=>__('卡密','b2')
            ),
            'default'=>'html',
            'before_row'=>'<p>用户购买成功以后，购买结果会展示在商品页，如果网站服务器具有邮寄发送功能，并且用户绑定了自己的邮箱，购买结果页将会以邮件的形式发送到用户的邮箱。</p>'
        ));

        $xuni->add_field(array( 
            'name'  =>__('直接展示的内容','b2'),
            'id'=>'shop_xuni_html_resout',
            'type'=>'textarea_code',
            'options' => array( 'disable_codemirror' => true ),
            'desc'=>__('购买结果支持html，如果是复杂的HTML可能用户的邮件中无法显示，或被邮件服务器过滤，不过商品页面会正常显示购买结果。','b2')
        ));

        $xuni->add_field(array( 
            'name'  =>__('发送到邮箱的内容','b2'),
            'id'=>'shop_xuni_cards_resout',
            'type'=>'textarea_code',
            'options' => array( 'disable_codemirror' => true ),
            'desc'=>sprintf(__('此项设置可以填写多条内容，用户购买以后按照从上到下的顺序发送，如果已经购买过，后面会变成%s的形式。请确保商品数量和此处卡密数量一致，否则可能出现超卖而又收不到卡密的情况。每一条内容占一行。比如：%s已经购买过会变成%s','b2'),'<code>xxx|sold-购买者的用户ID</code>','<br><br><code>卡号：12323  密码：1231233</code><br><code>卡号：23534346  密码：346346</code><br><br>','<br><code>卡号：12323  密码：1231233<span class="red">|sold-5690</span></code>')
        ));

        $attr = new_cmb2_box(array( 
            'id'            => 'shop_attr',
            'title'         => __( '商品属性', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true
        ));

        $attr->add_field(array( 
            'name'  =>__('商品属性值','b2'),
            'id'=>'shop_attr',
            'type'=>'textarea',
            'desc'=>sprintf(__('请按照%s属性名|属性值%s的格式设置商品属性，每个属性占一行，不填则不显示此项。比如%s%s颜色|红色%s尺码|30%s'),'<code>','</code>','<br>','<code>','</code><br><code>','</code>')
        ));

        $image = new_cmb2_box(array( 
            'id'            => 'shop_image',
            'title'         => __( '商品图片', 'b2' ),
            'object_types'  => array( 'shop'),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true
        ));

        $image->add_field( array(
            'name'    => __( '更多展示图片', 'b2' ),
            'desc'    => __( '这些图片会在商品页面顶部显示，可以设置多个', 'b2' ),
            'id'      => 'shop_images',
            'type'    => 'file_list',
            'options' => array(
                'url' => true, 
            ),
            'text'    => array(
                'add_upload_file_text' => __( '选择图片', 'b2' ),
            ),
            'query_args' => array(
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            )
        ));

    }
}