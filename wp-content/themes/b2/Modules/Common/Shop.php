<?php namespace B2\Modules\Common;
use B2\Modules\Common\Orders;
use B2\Modules\Common\Message;
use B2\Modules\Common\Credit;
class Shop{

    public function init(){
        add_action( 'init', array(__CLASS__,'create_shop_type'));

    }

    //添加商品的文章类型
    public static function create_shop_type() {
        $labels = array(
            'name'              => __( '商品分类', 'b2' ),
            'singular_name'     => __( '商品分类', 'b2' ),
            'search_items'      => __( '搜索商品分类', 'b2' ),
            'all_items'         => __( '所有商品分类', 'b2' ),
            'parent_item'       => __( '父级商品分类', 'b2' ),
            'parent_item_colon' => __( '父级商品分类：', 'b2' ),
            'edit_item'         => __( '编辑商品分类', 'b2' ),
            'update_item'       => __( '更新商品分类', 'b2' ),
            'add_new_item'      => __( '添加商品分类', 'b2' ),
            'new_item_name'     => __( '商品分类名称', 'b2' ),
            'menu_name'         => __( '商品分类', 'b2' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'public'=>true,
            'update_count_callback'=>'_update_post_term_count',
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest' => true,
            'rewrite'           => array( 'slug' => 'shop', 'with_front' => false ),
        );

        register_taxonomy( 'shoptype', array( 'shoptype' ), $args );
        
        $labels = array(
            'name' => __('商品','b2'),
            'singular_name' => __('商品','b2'),
            'add_new' => __('添加一个商品','b2'),
            'add_new_item' => __('添加一个商品','b2'),
            'edit_item' => __('编辑商品','b2'),
            'new_item' => __('新的商品','b2'),
            'all_items' => __('所有商品','b2'),
            'view_item' => __('查看商品','b2'),
            'search_items' => __('搜索商品','b2'),
            'not_found' =>  __('没有商品','b2'),
            'not_found_in_trash' =>__('回收站为空','b2'),
            'parent_item_colon' => '',
            'menu_name' => __('商品','b2'),
        );
        register_post_type( 'shop', array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'menu_icon'=>'dashicons-products',
            'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail','comments'),
            'taxonomies' => array('shoptype','post_tag'),
            'exclude_from_search' => false,
            'capability_type' => 'page',
            'show_in_rest'       => true,
            'rewrite' => array( 'slug' => 'shop', 'with_front' => false ),
            'yarpp_support' => true
            )
        );
    }

    //获取商铺幻灯
    public static function shop_home_slider(){
        $slider = b2_get_option('shop_main','shop_slider');
        if(!$slider) return array();
        $list = \B2\Modules\Templates\Modules\Sliders::slider_data(array('slider_list'=>$slider,'show_widget'=>true));
        return $list;
    }

    //获取商品分类信息
    public static function get_shop_cat_data($cats){
        if(empty($cats)) return;

        $page_width = (int)b2_get_option('template_main','wrapper_width');
        $ratio = b2_get_option('shop_main','shop_cat_img_ratio');

        $ratio = explode('/',$ratio);
        $w_ratio = $ratio[0];
        $h_ratio = $ratio[1];

        $count = (int)b2_get_option('shop_main','shop_cat_row_count');
        $page_width = $page_width - ($count-1)*16;
        $width = round($page_width/$count,4);
        $height = round($width/$w_ratio*$h_ratio,4);

        $data = array();
        foreach ($cats as $k => $v) {
            $tax = get_term_by('slug', $v, 'shoptype');

            $thumb = b2_get_thumb(array('thumb'=>get_term_meta($tax->term_id,'b2_tax_img',true),'width'=>round($width,0),'height'=>round($height,0)));
            $thumb_webp = apply_filters('b2_thumb_webp',$thumb);
            if(isset($tax->term_id)){
                $data[] = array(
                    'title'=>$tax->name,
                    'link'=>get_term_link($tax->term_id),
                    'thumb'=>$thumb,
                    'thumb_webp'=>$thumb_webp,
                    'width'=>$width,
                    'height'=>$height
                );
            }
        }
        unset($tax);
        return $data;
    }

    //通过一组ID获取商铺信息
    public static function get_shop_items_data($ids,$return){

        if(!is_array($ids) || empty($ids)) return array('error'=>__('参数错误','b2'));
        if(count($ids) > 50) array('error'=>__('传入参数过多','b2'));

        $data = array();

        $user_id = get_current_user_id();

        foreach ($ids as $id) {
            $data[$id] = self::get_shop_item_data($id,$user_id,$return);
        }

        return $data;
    }

    //获取某个商品信息
    public static function get_shop_item_data($post_id,$user_id,$return = array()){

        $type = get_post_meta($post_id,'zrz_shop_type',true);

        $status = get_post_status($post_id);
        if($status !== 'publish') return array('error'=>__('不存在此商品','b2'));
 
        $thumb_id = get_post_thumbnail_id($post_id);
        $thumb_url = wp_get_attachment_image_src($thumb_id,'full');

        if(!isset($thumb_url[0]) || !$thumb_url[0]){
            $thumb_url = array(
                Post::get_post_thumb($post_id),
                400,
                300,
            );
        }

        //当前用户是不是VIP
        $views = get_post_meta($post_id,'views',true);
        $vip = get_user_meta($user_id,'zrz_vip',true);

        $commodity = (int)get_post_meta($post_id,'zrz_shop_commodity',true);

        $thumb = b2_get_thumb(array('thumb'=>$thumb_url[0],'width'=>300,'height'=>300,'ratio'=>2));

        $data = array(
            'id'=>$post_id,
            'thumb_full'=>Post::get_post_thumb($post_id),
            'thumb'=>$thumb,
            'thumb_webp'=>apply_filters('b2_thumb_webp',$thumb),
            'title'=>get_the_title($post_id),
            'link'=>get_permalink($post_id),
            'type'=>$type,
            'is_vip'=>$vip,
            'price'=>self::get_shop_price($post_id,$user_id,$type),
            'stock'=>self::product_stock($post_id),
            'views'=>b2_number_format($views),
            'can_buy'=>self::user_can_buy($post_id,$user_id,$type),
            'images'=>isset($return['images']) && $return['images'] ? self::get_product_images($post_id) : array(),
            'commodity'=>$commodity,
            'attrs'=>isset($return['attrs']) && $return['attrs'] ? self::shop_single_attrs($post_id) : array(),
            'out_link'=>get_post_meta($post_id,'shop_link',true),
            'is_full'=>false
        );

        return $data;
    }

    //获取库存信息
    public static function product_stock($post_id){
        $total = (int)get_post_meta($post_id,'shop_count',true);
        $sell = (int)get_post_meta($post_id,'shop_count_sell',true);

        $hidden_sell = (int)get_post_meta($post_id,'shop_count_hidden',true);

        $scale = $total === 0 ? 100 : bcmul(bcdiv($sell,$total,4),100);

        $data = array(
            'total'=>b2_number_format($total),
            'sell'=>$hidden_sell ? '' : b2_number_format($sell),
            'scale'=>$scale,
            'allow'=>false
        );

        if($total > 0){
            $data['allow'] = true;
        }

        return $data;
    }

    //商品图片
    public static function get_product_images($post_id){
        $images = get_post_meta($post_id,'shop_images',true);

        $data = array();

        if(is_array($images) && !empty($images)){
            foreach ($images as $k => $v) {
                $thumb = b2_get_thumb(array('thumb'=>$v,'width'=>300,'height'=>300,'ratio'=>2));
                $data[] = array(
                    'thumb'=>$thumb,
                    'thumb_webp'=>apply_filters('b2_thumb_webp',$thumb)
                );
            }
        }

        return $data;
    }

    //用户的购买权限
    public static function user_can_buy($post_id,$user_id,$type){

        $stock = self::product_stock($post_id);

        $vip = get_user_meta($user_id,'zrz_vip',true);
        $lv = get_user_meta($user_id,'zrz_lv',true);

        switch ($type) {
            case 'normal':
                if($stock['allow'] === false){
                    return array(
                        'allow'=>false,
                        'text'=>__('售罄','b2')
                    );
                }else{
                    return array(
                        'allow'=>true,
                        'text'=>__('购买','b2')
                    );
                }
                break;
            case 'lottery':
                $roles = get_post_meta($post_id,'shop_lottery_roles',true);
                $roles = is_array($roles) ? $roles : array();

                $role_name = array();
                foreach ($roles as $k => $v) {
                    $role_name[] = User::get_lv_icon($v);
                }

                if($stock['allow'] === false){
                    return array(
                        'roles'=>$role_name,
                        'allow'=>false,
                        'text'=>__('结束','b2')
                    );
                }

                if(!empty($roles)){
                    if(!in_array($lv,$roles) && !in_array($vip,$roles)){
                        return array(
                            'roles'=>$role_name,
                            'allow'=>false,
                            'text'=>__('权限不足','b2')
                        );
                    }
                }

                return array(
                    'roles'=>$role_name,
                    'allow'=>true,
                    'text'=>__('抽奖','b2')
                );
                
                break;
            case 'exchange':
                if($stock['allow'] === false){
                    return array(
                        'allow'=>false,
                        'text'=>__('完毕','b2')
                    );
                }else{
                    return array(
                        'allow'=>true,
                        'text'=>__('兑换','b2')
                    );
                }
                break;
            default:
                break;
        }
    }

    //获取商品价格
    public static function get_shop_price($post_id,$user_id,$type){

        $data = array(
            'price'=>'',
            'd_price'=>'',
            'u_price'=>'',
            'credit'=>'',
            'current_price'=>'',
            'price_text'=>''
        );

        $vip = get_user_meta($user_id,'zrz_vip',true);

        switch ($type) {
            case 'normal':
                $price = get_post_meta($post_id,'shop_price',true);
                $shop_d_price = get_post_meta($post_id,'shop_d_price',true);
                $shop_u_price =  get_post_meta($post_id,'shop_u_price',true);

                $data = array(
                    'price'=>$price,
                    'd_price'=>$shop_d_price,
                    'u_price'=>$shop_u_price,
                    'credit'=>(int)get_post_meta($post_id,'shop_price_credit',true),
                    'current_price'=>$vip && $shop_u_price !== '' ? $shop_u_price : ($shop_d_price && $shop_d_price !== '' ? $shop_d_price : $price),
                    'price_text'=>$vip && $shop_u_price ? __('会员价','b2') : ($shop_d_price && $price !== 0 ? sprintf(__('%s折','b2'),ceil($shop_d_price / $price * 100)/10) : '')
                );

                break;
            case 'lottery':
                $data['credit'] = get_post_meta($post_id,'shop_lottery_credit',true);
                $data['price'] = get_post_meta($post_id,'shop_lottery_price',true);
                $data['current_price'] = $data['credit'];
                break;
            case 'exchange':
                $data['credit'] = get_post_meta($post_id,'shop_exchange_credit',true);
                $data['price'] = get_post_meta($post_id,'shop_exchange_price',true);
                $data['current_price'] = $data['credit'];
                break;
            default:
                # code...
                break;
        }

        return $data;
    }

    //商品面包屑
    public static function shop_single_breadcrumb($post_id){
        
        $home = B2_HOME_URI;
        $shop = get_post_type_archive_link('shop');
        $tax = '';

        $tax = get_the_terms($post_id, 'shoptype');
        $tax_links = '';

        if($tax && $post_id){
            $tax = get_term($tax[0]->term_id, 'shoptype' );

            $term_id = $tax->term_id;
        }else{
            $term = get_queried_object();
            $term_id = isset($term->term_id) ? $term->term_id : 0;
        }

        if($tax){
            $tax_links = get_term_parents_list($term_id,'shoptype');
            $tax_links = str_replace('>/<','><span>></span><',$tax_links);
            $tax_links = rtrim($tax_links,'/');
            // foreach ($tax as $k => $v) {
            //     $tax_links .= '<a href="'.get_term_link($v->term_id).'">'.$v->name.'</a> ';
            // }
        }

        return '<a href="'.B2_HOME_URI.'">'.__('首页','b2').'</a><span>></span>'.'<a href="'.$shop.'">'.b2_get_option('shop_main','shop_name').'</a><span>></span>'.$tax_links;
    }

    //商品分类面包屑
    public static function shop_category_breadcrumb(){
        
        $home = B2_HOME_URI;
        $shop = get_post_type_archive_link('shop');

        $term = get_queried_object();
        $tax_links = '<a href="'.get_term_link($term->term_id).'">'.$term->name.'</a> ';

        return '<a href="'.B2_HOME_URI.'">'.__('首页','b2').'</a><span>></span>'.'<a href="'.$shop.'">'.b2_get_option('shop_main','shop_name').'</a><span>></span>'.$tax_links;
    }

     //商品类型面包屑
    public static function shop_type_breadcrumb(){
        
        $home = B2_HOME_URI;
        $shop = get_post_type_archive_link('shop');
        
        $type = get_query_var('b2_shop_type');
        $tax_links = '<a href="'.get_post_type_archive_link('shop').'/'.$type.'">'.($type === 'buy' ? __('购买','b2') : ($type === 'lottery' ? __('抽奖','b2') : __('兑换','b2'))).'</a> ';
        

        return '<a href="'.B2_HOME_URI.'">'.__('首页','b2').'</a><span>></span>'.'<a href="'.$shop.'">'.b2_get_option('shop_main','shop_name').'</a><span>></span>'.$tax_links;
    }

    public static function shop_single_attrs($post_id){
        $attrs = get_post_meta($post_id,'shop_attr',true);

        $str = trim($attrs, " \t\n\r\0\x0B\xC2\xA0");
        $str = explode(PHP_EOL, $attrs);
        $data = array();

        foreach ($str as $v) {
            $_v = explode('|', $v);
            if(!empty($_v) && isset($_v[1])){
                $data[] = array(
                    'k'=>$_v[0],
                    'v'=>$_v[1]
                );
            }
        }

        return $data;
    }

    public static function shop_stock_change($post_id,$count){
        $total = get_post_meta($post_id,'shop_count',true);
        $sell = get_post_meta($post_id,'shop_count_sell',true);

        $sell = $sell + $count;
        update_post_meta($post_id,'shop_count_sell',$sell);
        update_post_meta($post_id,'shop_count',$total - $count);

        return;
    }

    public static function decimalToFraction($decimal){
        if ($decimal < 0 || !is_numeric($decimal)) {
            return false;
        }
        if ($decimal == 0) {
            return [0, 0];
        }
    
        $tolerance = 1.e-4;
    
        $numerator = 1;
        $h2 = 0;
        $denominator = 0;
        $k2 = 1;
        $b = 1 / $decimal;
        do {
            $b = 1 / $b;
            $a = floor($b);
            $aux = $numerator;
            $numerator = $a * $numerator + $h2;
            $h2 = $aux;
            $aux = $denominator;
            $denominator = $a * $denominator + $k2;
            $k2 = $aux;
            $b = $b - $a;
        } while (abs($decimal - $numerator / $denominator) > $decimal * $tolerance);
    
        return [
            $numerator,
            $denominator
        ];
    }

    public static function shop_lottery_action($post_id,$address){
        $user_id = get_current_user_id();

        if(!$user_id) return array('error'=>__('请先登录','b2'));

        //检查抽奖权限
        $check = self::user_can_buy($post_id,$user_id,'lottery');

        //检查是否是抽奖类型
        if(get_post_meta($post_id,'zrz_shop_type',true) !== 'lottery') return array('error'=>__('商品类型错误','b2'));

        if($check['allow'] === false) return array('error'=>__('抽奖','b2').$check['text']);

        //检查积分
        $my_credit = (int)get_user_meta($user_id,'zrz_credit_total',true);
        $need_credit = (int)get_post_meta($post_id,'shop_lottery_credit',true);

        if($my_credit < $need_credit){
            return array('error'=>__('您的积分余额不足','b2'));
        }

        //中奖概率
        $probability = get_post_meta($post_id,'shop_lottery_probability',true);

        $nums = self::decimalToFraction($probability);

        $res = self::get_rand(array(
            1=>$nums[0],
            2=>$nums[1] - $nums[0]
        ));

        if($probability == 0){
            $res = 0;
        }

        $first = rand(1000,9999);

        if($res === 1){
            $sec = $first;
        }else{
            $sec = rand(1000,9999);

            while ($sec === $first) {
                $sec = rand(1000,9999);
            }
        }

        $order_id = '';

        if($sec === $first){
            $res = true;
            //创建订单
            $order_res = Orders::build_order(array(
                'title'=>get_the_title($post_id),
                'order_type'=>'c',
                'post_id'=>$post_id,
                'pay_type'=>'credit',
                'order_address'=>$address
            ));

            if(isset($order_res['error'])) return $order_res;
            // Orders::order_confirm($order_res);
        }else{
            $res = false;

            $credit = Credit::credit_change($user_id,-$need_credit);
            if(!$credit) return array('error'=>__('积分不足','b2'));

            Message::add_message(array(
                'user_id'=>$user_id,
                'msg_type'=>29,
                'msg_read'=>1,
                'msg_date'=>current_time('mysql'),
                'msg_users'=>0,
                'msg_credit'=>-$need_credit,
                'msg_credit_total'=>$credit,
                'msg_key'=>$post_id,
                'msg_value'=>''
            ));

        }

        return array(
            'fir'=>$first,
            'sec'=>$sec,
            'res'=>$res
        );
    }

    public static function get_rand($proArr) {

        $result = '';
    
        //概率数组的总概率精度
    
        $proSum = array_sum($proArr);
    
        //概率数组循环
    
        foreach ($proArr as $key => $proCur) {
    
            $randNum = mt_rand(1, $proSum);
    
            if ($randNum <= $proCur) {
    
                $result = $key;
    
                break;
    
            } else {
    
                $proSum -= $proCur;
    
            }
    
        }
    
        return $result;
    
    }

    public static function send_cards($post_id,$user_id,$count){
        $data = get_post_meta($post_id,'shop_xuni_cards_resout',true);

        $list = array();
        $list_str = '';
        //字符串转数组
        if($data){
            $_data = explode(PHP_EOL,$data);
            if($_data){
                $i = 1;
                foreach ($_data as $k => $v) {
                    if(strpos($v,'|sold-') === false){
                        if($i > $count) break;
                        $list[] = trim($v, " \t\n\r\0\x0B\xC2\xA0");
                        $i++;
                    }
                }

                if(!empty($list)){
                    foreach ($list as $_k => $_v) {
                        $data = str_replace($_v,$_v.'|sold-'.$user_id,$data);
                        $list_str .= $_v.PHP_EOL;
                    }
    
                    update_post_meta($post_id,'shop_xuni_cards_resout',$data);

                }
            }
        }

        return $list_str;
    }


    public static function send_email($email,$post_id,$count,$content,$order_id){

        $site_name = get_bloginfo('name');
        $subject = '['.$site_name.']'.__('：请查收您的商品信息','b2');

        $xuni = get_post_meta($post_id,'shop_xuni_type',true);

        $message = '<div style="width:700px;background-color:#fff;margin:0 auto;border: 1px solid #ccc;">
            <div style="height:64px;margin:0;padding:0;width:100%;">
                <a href="'.home_url().'" style="display:block;padding: 12px 30px;text-decoration: none;font-size: 24px;letter-spacing: 3px;border-bottom: 1px solid #ccc;" rel="noopener" target="_blank">
                    '.$site_name.'
                </a>
            </div>
            <div style="padding: 30px;margin:0;">
                <p style="font-size:14px;color:#333;">
                    '.__('您在本站购买的商品信息如下：','b2').'
                </p>
                <p style="font-size:14px;color: green;">'.__('商品：','b2').get_the_title($post_id).'</p>
                <p style="font-size:14px;color: green;">'.__('订单号：','b2').$order_id.'</p>
                <p style="font-size:14px;color: green;">'.__('数量：','b2').$count.'</p>
                <div style="font-size:16px;color: green;"><pre>'.$content.'</pre></div>
                <p style="font-size:14px;color: #999;">— '.$site_name.'</p>
                <p style="font-size:12px;color:#999;border-top:1px dotted #E3E3E3;margin-top:30px;padding-top:30px;">
                    '.__('本邮件为系统邮件不能回复，请勿回复。','b2').'
                </p>
            </div>
        </div>';

        $send = wp_mail( $email, $subject, $message );

        if(!$send){
            return false;
        }

        return true;
    }

    public static function get_user_buy_resout($post_id){
        $user_id = get_current_user_id();

        if(!$user_id) return array('error'=>__('请先登录','b2'));

        $data = array(
            'commodity'=>'',
            'type'=>'',
            'data'=>''
        );

        $info = get_post_meta($post_id,'b2_buy_users',true);

        if(isset($info[$user_id])){
            $commodity = (int)get_post_meta($post_id,'zrz_shop_commodity',true);
            
            $data['commodity'] = $commodity;

            if($commodity == 1){
                $data['data'] = __('您已成功购买过此商品，请注意查收！','b2');
            }else{
                $xuni = get_post_meta($post_id,'shop_xuni_type',true);
                if($xuni == 'html'){
                    $data['data'] = get_post_meta($post_id,'shop_xuni_html_resout',true);
                }else{
                    $data['data'] = $info[$user_id];
                }
                $data['type'] = $xuni;
            }
        }

        return $data;
    }

    public static function shop_get_express_data($order_id,$address = ''){

        $current_user_id = get_current_user_id();
        if(!$current_user_id) return array('error'=>__('权限不足','b2'));

        $phone = self::findThePhoneNumbers($address);
        $appcode = b2_get_option('orders_express','express_appcode');

        $args = array(
            'method'      => 'GET',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                'Authorization' => 'APPCODE '.$appcode,
                'Content-Type'  => 'application/json',
            ),
            'body'        => array(
                'com'=>'auto',
                'nu'=>$order_id
            ),
        );

        if($phone){
            $args['body']['receiverPhone'] = substr($phone,-4);
        }

        $request = wp_remote_post( 'https://allexp.market.alicloudapi.com/expQuery', $args );

        if(is_wp_error($request)){
            return array('error'=>$request->get_error_message());
        }

        return json_decode($request['body'],true);
    }

    public static function get_my_carts(){
        $user_id = get_current_user_id();
        if(!$user_id) return array('error'=>__('请先登陆','b2'));

        $carts = get_user_meta($user_id,'b2_carts',false);

        $data = array();
        if(!empty($carts)){
            foreach ($carts as $k => $v) {

                $data[$v['id']] = self::get_shop_item_data($v['id'],$user_id);
                $data[$v['id']]['count'] = $v['count'];
            }
        }

        return $data;
    }

    public static function delete_my_carts($id){
        $user_id = get_current_user_id();
        if(!$user_id) return array('error'=>__('请先登陆','b2'));

        $carts = get_user_meta($user_id,'b2_carts',false);

        if(!empty($carts)){

            if($id == 'all'){
                delete_user_meta($user_id, 'b2_carts');
            }else{
                foreach ($carts as $k => $v) {
                    if((int)$v['id'] == (int)$id){
                        delete_user_meta($user_id, 'b2_carts',$v);
                    }
                }
            }
            
        }

        return self::get_my_carts();
    }
    
    public static function set_my_carts($data){
        $user_id = get_current_user_id();
        if(!$user_id) return array('error'=>__('请先登陆','b2'));

        $post_id = (int)$data['id'];

        $post_type = get_post_type($post_id);
        if($post_type != 'shop') return array('error'=>__('这个不是商品','b2'));

        $total = (int)get_post_meta($post_id,'shop_count',true);
        $sell = (int)get_post_meta($post_id,'shop_count_sell',true);

        if((int)$data['count'] > ($total - $sell)) return array('error'=>__('库存不足','b2'));

        $carts = get_user_meta($user_id,'b2_carts',false);

        if(!empty($carts)){
            foreach ($carts as $k => $v) {
                if((int)$v['id'] == $post_id){
                    delete_user_meta($user_id, 'b2_carts',$v);
                }
            }
        }

        add_user_meta($user_id,'b2_carts',$data);

        return self::get_my_carts();
    }

    //提取手机号码
    public static function findThePhoneNumbers($oldStr = ""){
        preg_match_all("/(1\d{10})/",$oldStr,$a);

        if(isset($a[1]) && !empty($a[1])){
            return $a[1][0];
        }
        return '';
    }
}
