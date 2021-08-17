<?php namespace B2\Modules\Common;

use B2\Modules\Common\Pay;
use B2\Modules\Common\Post;
use B2\Modules\Common\Shortcode;
use B2\Modules\Common\Shop;
use B2\Modules\Common\Message;
use B2\Modules\Common\User;
use B2\Modules\Common\Circle;
use B2\Modules\Common\PostRelationships;
use B2\Modules\Common\CircleRelate;

/*
* 商城订单项
* $order_type //订单类型
* c : 抽奖 ，d : 兑换 ，g : 购买 ，w : 文章内购 ，ds : 打赏 ，x : 资源下载 ，cz : 充值 ，vip : VIP购买 ,cg : 积分购买,
* v : 视频购买,verify : 认证付费,mission : 签到填坑 , coupon : 优惠劵订单,circle_join : 支付入圈 , circle_read_answer_pay : 付费查看提问答案,
* circle_hidden_content_pay : 付费查看隐藏内容
*
* $order_commodity //商品类型
* 0 : 虚拟物品 ，1 : 实物
*
* $order_state //订单状态
* w : 等待付款 ，f : 已付款未发货 ，c : 已发货 ，s : 已删除 ，q : 已签收 ，t : 已退款
*/
class Orders{

    public function init(){
        add_filter( 'b2_order_notify_return', array(__CLASS__,'order_notify_return'),5,1);
    }

    //生成订单号
    public static function build_order_no() {
        $year_code = array('A','B','C','D','E','F','G','H','I','J');
        $order_sn = $year_code[intval(date('Y'))-2020].
        strtoupper(dechex(date('m'))).date('d').
        substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $order_sn;
    }

    //创建临时订单
    public static function build_order($data){

        $user_id = get_current_user_id();

        $can_guset_pay = apply_filters('b2_can_guest_buy', $data);

        if(!$user_id && !(int)$can_guset_pay) return array('error'=>__('请先登录','b2'));

        if(!isset($data['order_type']) || $data['order_type'] === 'coupon' || $data['order_type'] === 'gx') return array('error'=>__('订单类型错误','b2'));

        $data['order_type'] = trim($data['order_type'], " \t\n\r\0\x0B\xC2\xA0");
        $data['pay_type'] = trim($data['pay_type'], " \t\n\r\0\x0B\xC2\xA0");

        if($data['pay_type'] === 'coupon') return array('error'=>__('嗯？','b2'));
        if(isset($data['_pay_type'])) return array('error'=>__('嗯？','b2'));

        $data['order_count'] = isset($data['order_count']) ? (int)$data['order_count'] : 1;

        if($data['order_count'] < 1) return array('error'=>__('嗯？','b2'));

        if(isset($data['order_price']) && $data['order_price'] < 0) return array('error'=>__('嗯？','b2'));
  
        $data = self::build_order_action($data);

        if(is_array($data)){
            return Pay::pay($data);
        }else{
            return $data;
        }
    }

    public static function build_order_action($data,$_order_id = ''){
        $user_id = get_current_user_id();

        $data['order_count'] = isset($data['order_count']) ? (int)$data['order_count'] : 1;

        if($data['order_count'] < 1) return array('error'=>__('嗯？','b2'));

        $data['post_id'] = isset($data['post_id']) ? (int)$data['post_id'] : 0;
        
        if(isset($data['_pay_type'])) return array('error'=>__('嗯？','b2'));

        if(isset($data['order_price']) && $data['order_price'] < 0 && $data['order_type'] !== 'coupon') return array('error'=>__('嗯？','b2'));

        $data['user_id'] = $user_id;

        $order_type = b2_order_type();
        
        if(!isset($data['order_type']) || !isset($order_type[$data['order_type']])) return array('error'=>__('订单类型错误','b2'));

        $data['_pay_type'] = $data['pay_type'];
        
        //判断支付类型
        $pay_type = Pay::pay_type($data['pay_type']);
        if(isset($pay_type['error'])) return $pay_type;
        $data['pay_type'] = $pay_type['type'];
        
        //if($data['order_type'] === 'cz' && ($data['pay_type'] === 'balance' || $data['pay_type'] === 'credit' || $data['pay_type'] === 'card' || $data['pay_type'] === 'coupon')) return array('error'=>__('订单类型错误'));

        //扫码支付还是跳转支付
        $jump = Pay::check_pay_type($data['_pay_type']);
        $data['jump'] = $jump['pay_type'];
        
        //订单号
        if(($data['order_type'] === 'gx' || $data['order_type'] === 'coupon') && $_order_id){
            $data['order_id'] = $_order_id;
        }else{
            $order_id = self::build_order_no();
            $data['order_id'] = $order_id;
        }

        //检查支付金额
        $order_price = apply_filters('b2_order_price', $data);
        if(isset($order_price['error'])) return $order_price;
        
        $data['order_price'] = $order_price;
        
        if($data['order_price'] < 0 && $data['order_type'] !== 'coupon') return array('error'=>__('订单总金额错误','b2'));

        if($data['order_count'] < 1) return array('error'=>__('嗯？','b2'));

        //如果是合并支付
        if($data['order_type'] === 'g' || $data['order_type'] === 'coupon'){
            $total = $order_price;
        }else{
            $total = bcmul($data['order_price'],$data['order_count'],2);
        }
        
        //检查金额
        if(isset($data['order_total']) && (float)$data['order_total'] !== (float)$total){
            return array('error'=>__('订单总金额错误','b2'));
        }

        $data['order_total'] = $total;
        
        if($data['order_total'] < 0 && $data['order_type'] !== 'coupon') return array('error'=>__('订单总金额错误','b2'));

        //标题
        if(isset($data['title'])){
            $data['title'] = b2_get_des(0,30,urldecode($data['title']));
        }
        
        //金额类型
        $money_type = self::money_type($data);
        if(isset($money_type['error'])) return $money_type;
        $data['money_type'] = $money_type;
        
        //文章ID
        $data['post_id'] = isset($data['post_id']) ? (int)$data['post_id'] : 0;
        if($data['order_type'] === 'g'){
            $data['post_id'] = -1;
        }
        
        //检查是虚拟物品还是实物
        $commodity = self::check_commodity_type($data);
        $data['order_commodity'] = $commodity;
        
        //order_key
        $data['order_key'] = isset($data['order_key']) ? esc_sql(str_replace(array('{{','}}'),'',sanitize_text_field($data['order_key']))) : '';
        $data['order_value'] = isset($data['order_value']) ? urldecode($data['order_value']) : '';
        if($data['order_type'] === 'g' && self::isJson($data['order_value'])){
            //order_value
            $data['order_value'] = esc_sql(sanitize_text_field($data['order_value']));
        }else{
            //order_value
            $data['order_value'] = isset($data['order_value']) ? esc_sql(str_replace(array('{{','}}'),'',sanitize_text_field($data['order_value']))) : '';
        }
        
        //order_content
        $data['order_content'] = isset($data['order_content']) ? urldecode($data['order_content']) : '';
        $data['order_content'] = isset($data['order_content']) && $data['order_content'] != '' ? '[客户留言]：'.esc_sql(str_replace(array('{{','}}'),'',sanitize_text_field($data['order_content']))) : '';

        $data['order_address'] = isset($data['order_address']) ? esc_sql(str_replace(array('{{','}}'),'',sanitize_text_field($data['order_address']))) : '';

        $data = apply_filters('b2_order_build_before', $data);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';
        
        $wpdb->insert(
            $table_name, 
            array(
                'order_id' => $data['order_id'], 
                'user_id' => $data['user_id'],
                'post_id'=>$data['post_id'],
                'order_type'=>$data['order_type'],
                'order_commodity'=>$data['order_commodity'],
                'order_state'=>'w',
                'order_date'=>current_time('mysql'),
                'order_count'=>$data['order_count'],
                'order_price'=>$data['order_price'],
                'order_total'=>$data['order_total'],
                'money_type'=>$data['money_type'],
                'order_key'=>$data['order_key'],
                'order_value'=>$data['order_value'],
                'order_content'=>$data['order_content'],
                'pay_type'=>$data['pay_type'],
                'tracking_number'=>'',
                'order_address'=>self::get_address($data),
                'order_mark'=>b2_get_user_ip()
            ), 
            array(
                '%s',
                '%d',
                '%d',
                '%s',
                '%d',
                '%s',
                '%s',
                '%d',
                '%f',
                '%f',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        return apply_filters('b2_order_build_after',$data,$wpdb->insert_id);
    }

    //判断货币类型
    public static function money_type($data){
        //c : 抽奖 ，d : 兑换 ，g : 合并购买， gx : 合并购买后单品，w : 文章内购 ，ds : 打赏 ，x : 资源下载 ，cz : 充值 ，vip : VIP购买 ,cg : 积分购买,v视频查看
        //0 是货币，1是积分
        switch ($data['order_type']) {
            case 'g':
            case 'ds':
            case 'cz':
            case 'vip':
            case 'verify':
            case 'circle_join':
            case 'coupon':
            case 'gx':
                return 0;
                break;
            case 'd':
            case 'c':
                return 1;
                break;
            case 'x':
                if(!isset($data['order_key'])) return array('error'=>__('金额错误','b2'));
                if(!isset($data['post_id'])) return array('error'=>__('没有相关资源','b2'));

                $download = Post::get_download_page_data($data['post_id'],$data['order_key'],0);
                if(isset($download['error'])) return $download;

                $download = $download['current_user'];
                $download = $download['can'];
                if($download['type'] == 'money' && $download['value']){
                    return 0;
                }elseif($download['type'] == 'credit' && $download['value']){
                    return 1;
                }
                break;
            case 'v':
                $type = get_post_meta($data['post_id'],'b2_single_post_video_role',true);
                if($type === 'money'){
                    return 0;
                }
                if($type === 'credit'){
                    return 1;
                }
                break;
            case 'w':
                $cap = Shortcode::check_reading_cap($data['post_id'],$data['user_id']);
                if(isset($cap['cap']) && $cap['cap'] === 'money'){
                    return 0;
                }else{
                    return 1;
                }
                break;
            case 'circle_read_answer_pay':
                $type = get_post_meta($data['post_id'],'b2_circle_ask_reward',true);
                if($type === 'money') return 0;
                return 1;
                break;
            case 'circle_hidden_content_pay':
                $type = get_post_meta($data['post_id'],'b2_topic_read_role',true);
                if($type === 'money') return 0;
                return 1;
                break;
            case 'mission':
                return 1;
            break;
        }

        return 0;
    }

    //订单异步回调
    public static function order_confirm($order_id,$money){

        if(!$order_id) return array('error'=>__('数据不完整','b2'));
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';

        //获取订单数据
        $order = $wpdb->get_row(
            $wpdb->prepare("
                SELECT * FROM $table_name
                WHERE order_id = %s
                ",
                $order_id
            )
        ,ARRAY_A);

        //如果已经支付成功，直接返回
        if($order['order_state'] !== 'w') return 'success';

        if(empty($order)){
            return array('error'=>__('没有找到这个订单','b2'));
        }

        if($money && (float)$money != $order['order_total']){
            return array('error'=>__('金额错误','b2'));
        }

        //虚拟物品还是实物
        if((int)$order['order_commodity'] === 0){
            $state = 'q';
        }else{
            $state = 'f';
        }

        //更新订单
        if(apply_filters('b2_update_orders', array('order_state'=>$state,'order'=>$order))){

            $user_id = apply_filters('b2_order_user_id',$order);
            if(isset($user_id['error'])) return $user_id;

            //do_action('b2_order_notify_action',$order);
            return apply_filters('b2_order_notify_return', $order);
        }

        //do_action('b2_order_notify_action',array());
        return array('error'=>__('回调错误','b2'));
    }

    //判断商品是虚拟物品还是实物
    public static function check_commodity_type($data){

        if($data['order_type'] == 'g'){
            return -1;
        }

        //c : 抽奖 ，d : 兑换 ，g : 合并支付 ，gx : 合并支付的单个商品,w : 文章内购 ，ds : 打赏 ，x : 资源下载 ，cz : 充值 ，vip : VIP购买 ,cg : 积分购买
        $type = array('cg','vip','cz','x','verify','circle_join','circle_read_answer_pay','circle_hidden_content_pay','mission','coupon');
        if(in_array($data['order_type'],$type)) return 0;

        $post_type = get_post_type($data['post_id']);
        if($post_type !== 'shop'){
            return 0;
        }

        if($post_type === 'shop'){
            return (int)get_post_meta($data['post_id'],'zrz_shop_commodity',true);
        }

        return 1;
    }

    //获取用户的地址数据
    public static function get_address($data){
        
        if((int)$data['order_commodity'] === 0){
            return $data['order_address'];
        }else{
            $key = isset($data['order_address']) && $data['order_address'] ? $data['order_address'] : '';
            return User::get_default_address($data['user_id'],$key);
        }

        return '';
    }

    //支付成功回调
    public static function order_notify_return($data){

        if(empty($data)) return array('error'=>__('更新订单失败','b2'));

        $order_type = 'callback_'.$data['order_type'];
        return self::$order_type($data);
    }

    //合并支付回调
    public static function callback_g($data){
 
        $post_data = json_decode(stripslashes($data['order_value']),true);

        if($post_data){
            $ids = $post_data['products'];

            foreach ($ids as $k => $v) {
                self::order_confirm($data['order_id'].'-'.$k,0);
            }

            //优惠劵回调
            if(!empty($post_data['coupons'])){
                self::order_confirm($data['order_id'].'-coupon',0);
            }

            //删除临时合并订单
            global $wpdb;
            $table_name = $wpdb->prefix.'zrz_order';

            $wpdb->delete( $table_name, array( 'order_id' => $data['order_id']) );
        }

        return apply_filters('b2_order_callback_g', $post_data,$data);
    }

    public static function callback_coupon($data){
        if($data['pay_type'] === 'balance'){
            //$money = User::money_change($data['user_id'],-$data['order_total']);
            Message::add_message(array(
                'user_id'=>$data['user_id'],
                'msg_type'=>64,
                'msg_read'=>1,
                'msg_date'=>current_time('mysql'),
                'msg_users'=>'',
                'msg_credit'=>-$data['order_total'],
                'msg_credit_total'=>get_user_meta($data['user_id'],'zrz_rmb',true),
                'msg_key'=>-1,
                'msg_value'=>$data['order_value']
            ));
        }

        //删除已领的优惠劵
        $coupons = explode(',',$data['order_value']);
        $my_coupons = get_user_meta($data['user_id'],'b2_coupons',true);
        foreach ($coupons as $k => $v) {
            if(isset($my_coupons[$v])){
                unset($my_coupons[$v]);
            }
        }

        update_user_meta($data['user_id'],'b2_coupons',$my_coupons);

        return apply_filters('b2_order_callback_coupon',$data);
    }

    //合并支付单个商品回调
    public static function callback_gx($data){

        //检查商品是否有赠送积分
        $post_id = $data['post_id'];
        $type = get_post_meta($post_id,'zrz_shop_type',true);

        $money = Shop::get_shop_price($post_id,$data['user_id'],$type);

        if($money['credit'] && $money['credit'] > 0 && $type === 'normal'){
            $credit = bcmul($money['credit'],$data['order_count']);
            $total = Credit::credit_change($data['user_id'],$credit);

            Message::add_message(array(
                'user_id'=>$data['user_id'],
                'msg_type'=>62,
                'msg_read'=>1,
                'msg_date'=>current_time('mysql'),
                'msg_users'=>'',
                'msg_credit'=>$credit,
                'msg_credit_total'=>$total,
                'msg_key'=>$data['post_id'],
                'msg_value'=>$data['order_total']
            ));
        }

        if($data['pay_type'] === 'balance'){
            //订单回调
            $strlen = strlen($data['order_id']);  
            $tp = strpos($data['order_id'],'-');  
            $order_id = substr($data['order_id'],-$strlen,$tp);

            global $wpdb;
            $table_name = $wpdb->prefix . 'zrz_order';

            $res = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT * FROM $table_name WHERE `order_id` LIKE %s AND `order_state` = %s
                ",'%'.$order_id.'-%','w')
            ,ARRAY_A);

            $total = get_user_meta($data['user_id'],'zrz_rmb',true);
            $total = $total ? $total : 0;

            if($res){
                foreach ($res as $k => $v) {
                    $total = bcadd($total,$v['order_total'],2);
                }
            }
            
            $res = Message::add_message(array(
                'user_id'=>$data['user_id'],
                'msg_type'=>63,
                'msg_read'=>1,
                'msg_date'=>current_time('mysql'),
                'msg_users'=>0,
                'msg_credit'=>-$data['order_total'],
                'msg_credit_total'=>$total,
                'msg_key'=>$data['post_id'],
                'msg_value'=>''
            ));

            if(isset($res['error'])) return $res;
        }

        //库存变更
        Shop::shop_stock_change($data['post_id'],$data['order_count']);

        //记录购买信息
        self::buy_resout($data);

        return apply_filters('b2_order_callback_gx', $money, $data);
    }

    public static function callback_mission($data){
        return apply_filters('b2_order_callback_mission', $data);
    }

    //兑换回调
    public static function callback_d($data){
        //库存变更
        Shop::shop_stock_change($data['post_id'],$data['order_count']);

        //记录购买信息
        self::buy_resout($data);
        return apply_filters('b2_order_callback_d', $data);
    }

    //抽奖回调
    public static function callback_c($data){
        //库存变更
        Shop::shop_stock_change($data['post_id'],$data['order_count']);

        //记录购买信息
        self::buy_resout($data);
        return apply_filters('b2_order_callback_c', $data);
    }

    //积分充值
    public static function callback_cg($data){
        $dh = (int)b2_get_option('normal_gold','credit_dh');

        $credit = bcmul($data['order_total'],$dh,0);

        $total = Credit::credit_change($data['user_id'],$credit);

        Message::add_message(array(
            'user_id'=>$data['user_id'],
            'msg_type'=>56,
            'msg_read'=>1,
            'msg_date'=>current_time('mysql'),
            'msg_users'=>'',
            'msg_credit'=>$credit,
            'msg_credit_total'=>$total,
            'msg_key'=>$data['id'],
            'msg_value'=>$data['order_total']
        ));

        return apply_filters('b2_order_callback_cg', $dh, $data);
    }

    //vip回调
    public static function callback_vip($data){
        
        $vip = get_user_meta($data['user_id'],'zrz_vip',true);

        $vip_data = b2_get_option('normal_user','user_vip_group');
        $_vip = (string)preg_replace('/\D/s','',$data['order_key']);
        $day = $vip_data[$_vip];
        $day = $day['time'];

        if($vip && $vip === 'vip'.$_vip){
            $time = get_user_meta($data['user_id'],'zrz_vip_time',true);

            if((string)$day === '0'){
                $end = 0;
            }elseif(isset($time['end']) && (string)$time['end'] !== '0'){
                $end = $time['end'] + DAY_IN_SECONDS*$day;
            }else{
                $end = strtotime(date('Y-m-d H:i:s',strtotime('+'.$day.' day')));
            }

            if(isset($time['start'])){
                $start = $time['start'];
            }else{
                $start = strtotime(current_time('Y-m-d H:i:s'));
            }

            if($vip !== $data['order_key']){
                update_user_meta($data['user_id'],'zrz_vip',$data['order_key']);
            }
        }else{
            update_user_meta($data['user_id'],'zrz_vip',$data['order_key']);
            $start = strtotime(current_time('Y-m-d H:i:s'));
            if((string)$day === '0'){
                $end = 0;
            }else{
                $end = strtotime(date('Y-m-d H:i:s',strtotime('+'.$day.' day')));
            }
        }

        update_user_meta($data['user_id'],'zrz_vip_time',array(
            'start'=>$start,
            'end'=>$end
        ));


        return apply_filters('b2_order_callback_vip', $data['order_key'], $data);
    }

    //视频支付成功回调
    public static function callback_v($data){
        $video_payed = get_post_meta($data['post_id'],'b2_video_pay',true);
        $video_payed = is_array($video_payed) ? $video_payed : array();
        $video_payed[] = $data['user_id'];

        update_post_meta($data['post_id'],'b2_video_pay',$video_payed);

        return apply_filters('b2_order_callback_v', $video_payed, $data);
    }

    //支付成功以后，资源下载数据处理
    public static function callback_x($data){

        $buy_data = get_post_meta($data['post_id'],'b2_download_buy',true);
        $buy_data = is_array($buy_data) ? $buy_data : array();

        $buy_data[$data['order_key']] = isset($buy_data[$data['order_key']]) && is_array($buy_data[$data['order_key']]) ? $buy_data[$data['order_key']] : array();
        $buy_data[$data['order_key']][] = $data['user_id'];

        update_post_meta($data['post_id'],'b2_download_buy',$buy_data);

        return apply_filters('b2_order_callback_x', $buy_data, $data);
    }

    //支付成功以后，文章内容阅读
    public static function callback_w($data){

        $buy_data = get_post_meta($data['post_id'],'zrz_buy_user',true);
        $buy_data = is_array($buy_data) ? $buy_data : array();

        $buy_data[] = (int)$data['user_id'];

        update_post_meta($data['post_id'],'zrz_buy_user',$buy_data);

        return apply_filters('b2_order_callback_w', $buy_data, $data);
    }

    //支付成功以后，打赏数据处理
    public static function callback_ds($data){
        $ds = get_post_meta($data['post_id'],'zrz_shang',true);
        $ds = is_array($ds) ? $ds : array();

        $ds[] = array(
            'user'=>$data['user_id'],
            'rmb'=>number_format(round($data['order_price']), 2)
        );
        
        update_post_meta($data['post_id'],'zrz_shang',$ds);

        return apply_filters('b2_order_callback_ds',$ds, $data);
    }

    //充值成功回调
    public static function callback_cz($data){
        
        $total = User::money_change($data['user_id'],$data['order_total']);

        Message::add_message(array(
            'user_id'=>$data['user_id'],
            'msg_type'=>57,
            'msg_read'=>1,
            'msg_date'=>current_time('mysql'),
            'msg_users'=>'',
            'msg_credit'=>$data['order_total'],
            'msg_credit_total'=>$total,
            'msg_key'=>$data['id'],
            'msg_value'=>''
        ));

        return apply_filters('b2_order_callback_cz',$total, $data);
    }

    public static function callback_circle_join($data){

        // update_user_meta(1,'test_circle',$data);

        $type = $data['order_key'];

        $arg = array('permanent','year','halfYear','season','month');
        if(!in_array($type,$arg)) return array('error'=>__('有效期错误','b2'));

        $now = current_time('mysql');
        $end = '';
        
        switch($type){
            case 'year':
                $end = date("Y-m-d H:i:s",strtotime("+1years",strtotime($now)));
            break;
            case 'halfYear':
                $end = date("Y-m-d H:i:s",strtotime("+6months",strtotime($now)));
            break;
            case 'season':
                $end = date("Y-m-d H:i:s",strtotime("+3months",strtotime($now)));
            break;
            case 'month':
                $end = date("Y-m-d H:i:s",strtotime("+1months",strtotime($now)));
            break;
        }
        
        CircleRelate::update_data(array(
            'user_id'=>(int)$data['user_id'],
            'circle_id'=>(int)$data['post_id'],
            'circle_role'=>'member',
            'join_date'=>$now,
            'end_date'=>$end,
            'circle_key'=>$type
        ));

        return apply_filters('b2_order_callback_circle_join', $data);
    }

    public static function callback_circle_hidden_content_pay($data){
        
        PostRelationships::update_data(array(
            'type'=>'circle_buy_hidden_content',
            'user_id'=>$data['user_id'],
            'post_id'=>$data['post_id']
        ));
    
        return apply_filters('b2_order_callback_circle_hidden_content_pay', $data);
    }

    public static function callback_circle_read_answer_pay($data){

        if($data['pay_type'] !== 'credit' && $data['pay_type'] !== 'balance'){
            $author = get_post_field('post_author', $data['post_id']);

            PostRelationships::update_data(array(
                'type'=>'circle_buy_answer',
                'user_id'=>$data['user_id'],
                'post_id'=>$data['post_id']
            ));
    
            //给提问者和答主通知
            $answers = Circle::get_answer_authors($data['post_id']);
    
            $answers = array_merge($answers,array($author));
    
            $answers = array_flip($answers);
            $answers = array_flip($answers);
    
            if(!empty($answers)){
                $average = $data['order_total']/count($answers);
                if($average < 1) return $data;
    
                foreach ($answers as $v) {
                    $average = intval($average);
                    $total = User::money_change($v,$average);
                    
                    self::add_message(array(
                        'user_id'=>$v,
                        'msg_type'=>76,
                        'msg_read'=>0,
                        'msg_date'=>current_time('mysql'),
                        'msg_users'=>$data['user_id'],
                        'msg_credit'=>$average,
                        'msg_credit_total'=>$total,
                        'msg_key'=>$data['post_id'],
                        'msg_value'=>''
                    ));
                }
            }
        }

        return apply_filters('b2_order_callback_circle_read_answer_pay', $data);
    }

    //充值成功回调
    public static function callback_verify($data){
        
        $data = array(
            'user_id'=>$data['user_id'],
            'money'=>$data['order_total']
        );

        Verify::add_verify_data($data);

        return apply_filters('b2_order_callback_verify',$total, $data);
    }

    //获取我的订单
    public static function get_my_orders($_user_id,$paged){
        $_user_id = (int)$_user_id;
        $user_id = (int)get_current_user_id();

        if(!$_user_id || !$user_id){
            return array('error'=>__('请先登录','b2'));
        }

        if($user_id !== $_user_id && !user_can($user_id, 'administrator' )) return array('error'=>__('权限不足','b2'));

        $user_id = $_user_id;

        $number = 12;
        $offset = ($paged-1)*$number;

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';

        //获取订单数据
        $order = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM $table_name
                WHERE user_id = %d AND order_state != %s AND order_type != %s ORDER BY `order_date` DESC LIMIT %d,%d
                ",
                $user_id,'w','coupon',$offset,$number
            )
        ,ARRAY_A);
        
        $data = array();

        $arr = array(
            'w'=>__('等待付款','b2'),
            'f'=>__('已付款未发货','b2'),
            'c'=>__('已发货','b2'),
            's'=>__('已删除','b2'),
            'q'=>__('已签收','b2'),
            't'=>__('已退款','b2')
        );

        $order_type = b2_order_type();

        foreach ($order as $k => $v) {

            $type = !isset($order_type[$v['order_type']]) ? $order_type[$v['order_type']] : __('未知订单类型','b2');

            $title = self::get_order_name($v['order_type'],$v['post_id']);

            $track = maybe_unserialize($v['tracking_number']);
            $tk = b2_express_types();
            $tk = isset($track['type']) ? $tk[$track['type']] : '';
            $nb = isset($track['number']) ? $track['number'] : '';

            $data[] = array(
                'id'=>$v['id'],
                'order_id'=>$v['order_id'],
                'order_name'=>$title['title'],
                'order_price'=>$v['order_price'],
                'order_total'=>$v['order_total'],
                'order_count'=>$v['order_count'],
                'order_date'=>$v['order_date'],
                'order_state'=>isset($arr[$v['order_state']]) ? $arr[$v['order_state']] : 'w',
                'tracking_number'=>isset($v['tracking_number']) ? array('type'=>$tk,'number'=>$nb) : '',
                'money_type'=>$v['money_type'],
                'order_type'=>$type,
                'thumb'=>$title['img'],
                'address'=>$v['order_address'],
                'order_content'=>$v['order_content']
            );
        }

        $pages = self::get_user_orders_count($user_id);

        return array(
            'pages'=>ceil($pages/$number),
            'data'=>$data
        );
    }

    public static function get_user_orders_count($user_id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_order';

        return $wpdb->get_var(
            $wpdb->prepare("
                SELECT COUNT(*) FROM $table_name
                WHERE user_id = %d AND order_state != %s
                ",
                $user_id,'w'
            ));
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function get_order_name($type,$id){
        if($type === 'cz'){
            $name = array(
                'name'=>__('充值','b2'),
                'link'=>b2_get_custom_page_url('gold')
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }elseif($type === 'cg'){
            $name = array(
                'name'=>__('积分购买','b2'),
                'link'=>b2_get_custom_page_url('gold')
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cg.png';
        }elseif($type === 'vip'){
            $name = array(
                'name'=>__('VIP购买','b2'),
                'link'=>b2_get_custom_page_url('vips')
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-vip.png';
        }elseif($type === 'verify'){
            $name = array(
                'name'=>__('认证付费','b2'),
                'link'=>b2_get_custom_page_url('verify')
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/rz-icon.png';
        }elseif($type === 'circle_join'){
            $name = array(
                'name'=>__('付费入圈','b2'),
                'link'=>get_term_link($id)
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }elseif($type === 'circle_read_answer_pay'){
            $name = array(
                'name'=>__('付费查看圈子问答','b2'),
                'link'=>get_post_permalink($id)
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }elseif($type === 'circle_hidden_content_pay'){
            $name = array(
                'name'=>__('付费查看帖子','b2'),
                'link'=>get_post_permalink($id)
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }elseif($type === 'mission'){
            $name = array(
                'name'=>__('签到填坑','b2'),
                'link'=>b2_get_custom_page_url('mission')
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }elseif($type === 'coupon'){
            $name = array(
                'name'=>__('优惠劵','b2'),
                'link'=>'javascript:void(0)'
            );
            $img = B2_THEME_URI.'/Assets/fontend/images/order-cz.png';
        }else{
            $name = array(
                'name'=>get_the_title($id),
                'link'=>get_permalink($id)
            );
            $img = b2_get_thumb(array('thumb'=>Post::get_post_thumb($id),'width'=>100,'height'=>100));
        }

        return array(
            'title'=>$name,
            'img'=>$img
        );
    }

    //记录购买结果
    public static function buy_resout($data){

        $post_id = $data['post_id'];
        $user_id = $data['user_id'];

        //虚拟物品还是实物
        $commodity = (int)get_post_meta($post_id,'zrz_shop_commodity',true);

        $res = '';

        if($commodity === 0){
            $xuni = get_post_meta($post_id,'shop_xuni_type',true);
            
            if($xuni === 'cards'){
                //如果是卡密，记录卡密
                $html = Shop::send_cards($data['post_id'],$data['user_id'],$data['order_count']);
                Shop::send_email($data['order_address'],$data['post_id'],$data['order_count'],$html,$data['order_id']);
            }else{
                $html = get_post_meta($post_id,'shop_xuni_html_resout',true);
                Shop::send_email($data['order_address'],$data['post_id'],$data['order_count'],$html,$data['order_id']);
            }

            global $wpdb;
            $table_name = $wpdb->prefix.'zrz_order';
            
            $wpdb->update( 
                $table_name,
                array(
                    'order_content'=>$data['order_content'] ? $data['order_content'].PHP_EOL.PHP_EOL.'[购买结果]：'.PHP_EOL.$html : '[购买结果]：'.PHP_EOL.$html
                ),
                array(
                    'order_id'=>$data['order_id']
                ),
                '%s',
                '%s'
            );
    
        }

        //记录购买结果
        $buys = get_post_meta($post_id,'b2_buy_users',true);
        $buys = is_array($buys) ? $buys : array();

        $buys[$user_id] = $res;
        update_post_meta($post_id,'b2_buy_users',$buys);

        return apply_filters('b2_buy_resout', $data);

    }
}