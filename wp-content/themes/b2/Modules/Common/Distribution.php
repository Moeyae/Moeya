<?php namespace B2\Modules\Common;

use B2\Modules\Common\User;
use B2\Modules\Common\Message;
use B2\Modules\Common\IntCode;

class Distribution{
    
    public function init(){
        add_filter('b2_order_notify_return', array(__CLASS__,'distribution_action'),0,1);
    }

    public static function distribution_action($data){

        if(empty($data)) return $data;

        if($data['pay_type'] === 'credit') return $data;

        $allow = (int)b2_get_option('distribution_main','distribution_open');

        //如果分销是关闭状态
        if(!$allow) return $data;

        //如果没有分销对象
        if(!$data['user_id']) return $data;

        $distribution_user = get_user_meta($data['user_id'],'b2_distribution_related',true);
        if(!$distribution_user) return $data;

        //允许分销的商品类型
        //c : 抽奖 ，d : 兑换 ，g : 购买 ，w : 文章内购 ，ds : 打赏 ，x : 资源下载 ，cz : 充值 ，vip : VIP购买 ,cg : 积分购买,v : 视频购买,verify : 认证付费,mission : 签到填坑 , coupon : 优惠劵订单
        $arg = array('g');
        if($data['order_type'] === 'w' || $data['order_type'] === 'x' || $data['order_type'] === 'v'){

            $allow = get_post_meta($data['post_id'],'b2_allow_distribution',true);
            
            if($allow === ''){
                $allow = -1; 
            }

            $allow = (int)$allow;

            if($allow === 0) return $data;

            if($allow === -1){
                if((int)b2_get_option('distribution_main','distribution_post')){
                    $arg[] = 'w';
                    $arg[] = 'x';
                    $arg[] = 'v';
                }
            }else{
                $arg[] = 'w';
                $arg[] = 'x';
                $arg[] = 'v';
            }
        }

        if(b2_get_option('distribution_main','distribution_vip')){
            $arg[] = 'vip';
        }

        if(b2_get_option('distribution_main','distribution_cg')){
            $arg[] = 'cg';
        }

        if(b2_get_option('distribution_main','distribution_verify')){
            $arg[] = 'verify';
        }

        $allow = apply_filters('b2_distribution_allow',$arg);

        if(!in_array($data['order_type'],$allow)) return $data;

        $action = 'action_'.$data['order_type'];

        return self::$action($data);

    }

    public static function action_x($data){
        self::action_w($data);
        return $data;
    }

    public static function action_vip($data){
        self::update_distribution_order($data);
        return $data;
    }

    public static function action_cg($data){
        self::update_distribution_order($data);
        return $data;
    }

    public static function action_verify($data){
        self::update_distribution_order($data);
        return $data;
    }

    public static function action_v($data){
        self::action_w($data);
        return $data;
    }

    public static function action_w($data){
        //检查是否允许分销
        $allow = get_post_meta($data['post_id'],'b2_allow_distribution',true);
        if($allow === '') $allow = 1;
        if((int)$allow){
            self::update_distribution_order($data);
        }

        return $data;
    }

    public static function action_g($data){

        $post_data = json_decode(stripslashes($data['order_value']),true);

        if($post_data){
            $ids = $post_data['products'];

            $money = 0;

            global $wpdb;
            $table_name = $wpdb->prefix . 'zrz_order';

            $gx_order_id = $data['order_id'];

            //检查是否允许分销
            foreach ($ids as $k => $v) {

                $res = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE `order_id`=%s",$gx_order_id.'-'.$k),
                    ARRAY_A
                );
                
                if(!empty($res)){
                    //检查是否允许分销
                    $allow = get_post_meta($res['post_id'],'b2_allow_distribution',true);

                    if($allow === '') $allow = -1;
                    
                    $allow = (int)$allow;

                    if($allow === 1){
                        self::update_distribution_order($res);
                    }else if($allow === -1){
                        if((int)b2_get_option('distribution_main','distribution_shop')){
                            self::update_distribution_order($res);
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 当前用户是否有分销的权限
     *
     * @param [type] $user_id 推广人
     * @param integer $money
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function user_can_distribution($user_id){

        if((int)b2_get_option('distribution_main','distribution_open') === 0) return false;

        $user_distribution = (int)get_user_meta($user_id,'b2_distribution',true);
        if($user_distribution === 1) return true;

        //获取分销权限
        $role = (int)b2_get_option('distribution_main','distribution_conditions');

        if($role === 0){
            update_user_meta($user_id,'b2_distribution',1);
            return true;
        }

        if($role === 1){
            if(get_user_meta($user_id,'b2_title',true)){
                update_user_meta($user_id,'b2_distribution',1);
                return true;
            }
        }

        if($role === 2){
            $lvs = b2_get_option('distribution_main','distribution_user_lv');
            if(empty($lvs)) return false;
            $user_lv = get_user_meta($user_id,'zrz_lv',true);
            $user_vip = get_user_meta($user_id,'zrz_vip',true);

            if(in_array($user_lv,(array)$lvs) || in_array($user_vip,(array)$lvs)){
                update_user_meta($user_id,'b2_distribution',1);
                return true;
            }
        }

        return false;
    }

    public static function check_distribution($current_user,$user_id){

        $current_user_distribution_related = get_user_meta($current_user_id,'b2_distribution_related',true);
        if(!$current_user_distribution_related) {
            $intCode = new IntCode();
            $user_id = $intCode->encode($ref);
            if(self::user_can_distribution($user_id)){
                update_user_meta($current_user_id,'b2_distribution_related',$user_id);
                $distribution = true;
            }
        }
    }

    /**
     * 记录分销订单
     *
     * @param [int] $current_user 当前用户
     * @param [int] $user_id 推广员
     * @param string $lv
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function update_distribution_order($data){

        //一级分销用户
        $lv1 = get_user_meta($data['user_id'],'b2_distribution_related',true);
        if($lv1){
            $allow = self::user_can_distribution($lv1);
            if($allow){
                $ratio = (float)b2_get_option('distribution_main','distribution_lv1');
                if($ratio){
                    //计算提成金额
                    $money = bcmul($data['order_total'],$ratio,2);
                    self::insert_distribution_data($lv1,$data['user_id'],'lv1',$money,$data['post_id'],$data['order_total'],$ratio,$data['order_type']);
                }
            }
        }

        //二级分销用户
        $lv2 = get_user_meta($data['user_id'],'b2_distribution_related_lv2',true);
        if($lv2){
            $allow = self::user_can_distribution($lv2);
            if($allow){
                $ratio = (float)b2_get_option('distribution_main','distribution_lv2');
                if($ratio){
                    //计算提成金额
                    $money = bcmul($data['order_total'],$ratio,2);
                    self::insert_distribution_data($lv2,$data['user_id'],'lv2',$money,$data['post_id'],$data['order_total'],$ratio,$data['order_type']);
                }
            }
        }

        //三级分销用户
        $lv3 = get_user_meta($data['user_id'],'b2_distribution_related_lv3',true);
        if($lv3){
            $allow = self::user_can_distribution($lv3);
            if($allow){
                $ratio = (float)b2_get_option('distribution_main','distribution_lv3');
                if($ratio){
                    //计算提成金额
                    $money = bcmul($data['order_total'],$ratio,2);
                    self::insert_distribution_data($lv3,$data['user_id'],'lv3',$money,$data['post_id'],$data['order_total'],$ratio,$data['order_type']);
                }
            }
        }
    }

    public static function insert_distribution_data($user_id,$current_user_id,$lv,$money,$post_id,$p_total,$ratio,$order_type){

        $author_allow = false;

        if($order_type === 'w' || $order_type === 'x' || $order_type === 'v'){

            $post_author = (int)get_post_field('post_author',$post_id);

            if($post_author){
                $total = User::money_change($post_author,-$money);
                if($total){
                    Message::add_message(array(
                        'user_id'=>$post_author,
                        'msg_type'=>69,
                        'msg_read'=>1,
                        'msg_date'=>current_time('mysql'),
                        'msg_users'=>$current_user_id,
                        'msg_credit'=>-$money,
                        'msg_credit_total'=>$total,
                        'msg_key'=>$post_id,
                        'msg_value'=>$order_type.'/'.$p_total.'/'.$ratio
                    ));

                    $author_allow = true;
                }
            }
        }

        if($author_allow === false && ($order_type === 'w' || $order_type === 'x' || $order_type === 'v')){
            return;
        }

        $total = User::money_change($user_id,$money);

        $add = get_user_meta($user_id,'b2_distribution_money',true);

        update_user_meta($user_id,'b2_distribution_money',bcadd($add,$money,2));

        $n = $lv === 'lv1' ? 66 : ($lv === 'lv2' ? 67 : 68);

        Message::add_message(array(
            'user_id'=>$user_id,
            'msg_type'=>$n,
            'msg_read'=>1,
            'msg_date'=>current_time('mysql'),
            'msg_users'=>$current_user_id,
            'msg_credit'=>$money,
            'msg_credit_total'=>$total,
            'msg_key'=>$post_id,
            'msg_value'=>$order_type.'/'.$p_total.'/'.$ratio
        ));

    }

    public static function get_my_distribution_data($user_id = 0){
        $current_user_id = get_current_user_id();
        
        if(!$current_user_id) return array('error'=>__('请先登录','b2'));

        if((int)$user_id !== 0 && user_can($current_user_id, 'administrator' )){
            $current_user_id = $user_id;
        }

        if(!self::user_can_distribution($current_user_id)) return array(
            'total_money'=>'****',
            'ref'=>'*********'
        );

        $intCode = new IntCode();
        $ref = $intCode->encode($current_user_id);

        $money = get_user_meta($current_user_id,'b2_distribution_money',true);
        $money = $money ? $money : 0;

        $data = array(
            'total_money'=>$money,
            'ref'=>$ref
        );

        return $data;
    }

    public static function get_my_distribution_orders($user_id = 0,$paged = 1){

        $current_user_id = get_current_user_id();

        if(!$current_user_id) return array('error'=>__('请先登录','b2'));

        $types = array(66,67,68);
        $types = implode("','",$types);

        if((int)$user_id !== 0 && user_can($current_user_id, 'administrator' )){
            $current_user_id = $user_id;
        }

        $number = 20;
        $offset = ($paged-1)*$number;

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_message';

        $data = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM $table_name
                WHERE user_id = %d AND msg_type IN ('".$types."') ORDER BY msg_id DESC LIMIT %d,%d
                ",
                $current_user_id,$offset,$number
            )
        ,ARRAY_A);

        $total = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(msg_id) FROM $table_name
            WHERE user_id = %d AND msg_type IN ('".$types."')
            ",
            $current_user_id
        ));

        return array(
            'list'=>Message::order_data_map($data),
            'pages'=>ceil($total/$number),
            'total'=>$total
        );
    }

    public static function get_my_partner($user_id,$paged){

        $current_user_id = get_current_user_id();

        if((int)$user_id !== 0 && user_can($current_user_id, 'administrator' )){
            $current_user_id = $user_id;
        }

        $count = 20;

        $offset = ($paged -1)*$count;

        $args = array(
            'number' => $count,
            'offset'=>$offset,
            'order' => 'ASC',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'b2_distribution_related',
                    'value' => $current_user_id,
                    'compare' => '='
                ),
                array(
                    'key' => 'b2_distribution_related_lv2',
                    'value' => $current_user_id,
                    'compare' => '='
                ),
                array(
                    'key' => 'b2_distribution_related_lv3',
                    'value' => $current_user_id,
                    'compare' => '='
                )
            )
        );

        $user_query = new \WP_User_Query( $args );
        $authors = $user_query->get_results();

        $data = array();

        if(!empty($authors)){

            foreach ($authors as $k => $v) {
                $public_data = User::get_user_public_data($v->ID,true);

                $lv1 = (int)get_user_meta($v->ID,'b2_distribution_related',true);
                $lv2 = (int)get_user_meta($v->ID,'b2_distribution_related_lv2',true);
                $lv3 = (int)get_user_meta($v->ID,'b2_distribution_related_lv3',true);

                if($lv1 === $current_user_id){
                    $public_data['partner_lv'] = 'lv1';
                }

                if($lv2 === $current_user_id){
                    $public_data['partner_lv'] = 'lv2';
                }

                if($lv3 === $current_user_id){
                    $public_data['partner_lv'] = 'lv3';
                }

                $data[] = $public_data;
            }
        }

        return $data;
    }
}