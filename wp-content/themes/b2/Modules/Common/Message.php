<?php namespace B2\Modules\Common;
use B2\Modules\Common\User;
use B2\Modules\Common\Credit;

/*
* 积分余额与通知
* $type 是积分类型，用数字表示，对应 zrz_message 表中的 type ,目前最大88项。含义如下 ：
* 
* 后面标注1的是已经实装的通知
* 
* 4 新注册用户增加积分和通知 1 a
* 46 邀请注册奖励积分（邀请） 1
*
* 1 如果当前评论有父级，给父级评论作者通知（评论）1 a
* 2 给评论者增加积分（评论）1 a
* 3 文章被回复，给文章作者通知（评论）1 a
* 8 不喜欢某个评论，给评论作者通知（评论）1 a
* 10 喜欢某个评论，给评论作者通知（评论）1 a
*
* 11 关注了某人，给某人通知（关注）1 a
* 15 取消关注某人，给某人通知（关注）1 a
* 42 关注了某人，给自己增加积分（关注）1 a
* 43 取消关注了某人，给自己减掉的积分（关注）1 a 
* 
* 12 私信通知（私信）1
* 13 私信内容（私信）
*
* 14 管理员给某人变更了积分，通知某人（用户）1 a
* 37 管理员给某人变更了余额，通知某人（用户）1 a
* 16 签到的通知（用户）1 a
* 61 签到填坑（用户）1 a
*
* 17 发表帖子通知（bbpress）
* 18 帖子回复通知（bbpress）
* 19 给帖子的作者通知（bbpress）
* 20 帖子回复时提到某人，给这个人通知(bbpress)
* 
* 47 视频出售后给文章作者通知（余额）1 a
* 48 视频购买后给购买者通知（余额）1 a
* 49 视频出售后给文章作者通知（积分）1 a
* 50 视频购买后给购买者通知（积分）1 a
* 51 资源下载后给文章作者通知（余额）1 a
* 52 资源下载后给购买这通知（余额）1 a
* 53 资源下载后给文章作者通知（积分）1 a
* 54 资源下载后给购买这通知（积分）1 a
* 55 卡密充值（余额）1 a
* 57 余额充值（除卡密）1 a
* 58 vip购买（余额）1
* 
* 59 认证付款（余额）
*
* 60 认证积分奖励（积分）
* 
* 5 发表文章（文章）
* 6 文章被点赞，给文章作者通知（文章）1 a
* 7 文章被取消点赞，给文章作者通知（文章）1 a
* 21 打赏人减掉金额时通知（文章）(余额) a
* 22 被打赏人增加金额时通知（文章）(余额) a
* 25 文章被删除时发出通知（文章）
* 31 付费文章购买通知（文章）（余额）1 a
* 32 付费文章出售通知（文章）（余额）1 a
* 33 积分文章购买通知（文章）(积分) 1 a
* 34 积分文章出售通知（文章）（积分）1 a
*
* 36 发表研究（研究）
*
* 23 有人申请了有情链接，给管理员通知（友情链接）
* 24 发表了冒泡，给冒泡作者通知（冒泡）
* 26 冒泡被点赞，给冒泡作者通知（冒泡）
* 27 冒泡被取消点赞，给冒泡作者通知（冒泡）
*
* 28 积分购买（商城）
* 29 积分抽奖（商城）
* 30 购买（商城）
* 64 优惠劵消息（商城）
* 63 余额购买（余额，购买者）
* 62 购买赠送积分（商城）
* 38 使用余额购买积分（余额）1 a
* 56 购买积分通知（积分）1 a
*
* 39 邀请别人成功，给自己增加积分
* 40 被邀请人增加积分
*
* 41 提现申请
*
* 44 报名通知（活动）
* 45 给报名的人减掉积分（活动）
*
* 65 发布快讯（积分）
*
* 66 一级分销奖励（余额）
* 67 二级分销奖励（余额）
* 68 三级分销奖励（余额）
* 69 分销减掉余额（余额）
*
* 70 入圈付费，购买者（余额）
* 71 入圈付费，圈主（余额）
*
* 72 圈子提问扣除积分（积分）
* 73 圈子提问扣除余额（余额）
* 
* 74 圈子邀请回答问题（圈子消息）
*
* 75 偷瞄答案,答主（积分）
* 76 偷瞄答案，答主（余额）
*
* 77 偷瞄答案，偷瞄者（积分）
* 78 偷瞄答案，偷瞄者（余额）
* 
* 79 问题被采纳（积分）
* 80 问题被采纳（余额）
* 
* 81 过期分红（积分）
* 82 过期分红（余额）
* 
* 83 没有回答，返还积分
* 84 没有回答，返还到余额
* 
* 85 付费查看帖子，作者（积分）
* 86 付费查看帖子，作者（余额）
* 
* 87 付费查看帖子，购买者（积分）
* 88 付费查看帖子，购买者（余额）

* 89 关小黑屋（消息）
*/

class Message{

    public function init(){

        //出售者通知
        add_filter('b2_order_notify_return',array($this,'order_notify_return'),2, 1);

        //购买者通知（余额）
        add_filter('b2_balance_pay_after',array($this,'balance_pay_after_message'),5, 2);

        //购买者通知（积分）
        add_filter('b2_credit_pay_after',array($this,'credit_pay_after_message'),5, 2);

    }

    //获取消息
    public static function get_user_message($user_id,$type,$paged){
        $user_id = (int)$user_id;

        $_user_id = (int)get_current_user_id();

        if(!$_user_id || !$user_id){
            return array('error'=>__('请先登录','b2'));
        }

        if($user_id !== $_user_id && !user_can($_user_id, 'administrator' )) return array('error'=>__('权限不足','b2'));

        $_user_id = $user_id;

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_message';

        $number = 20;
        $offset = ($paged-1)*$number;

        $credit = array(4,46,1,2,3,5,8,10,11,15,42,43,14,16,49,50,53,54,6,7,33,34,56,60,61,62,28,29,65,72,75,79,81,83,85,87);

        $money = array(37,47,48,51,52,21,22,31,32,55,38,57,58,59,63,64,66,67,68,69,41,70,71,73,76,78,80,82,84,86,88);

        if($type === 'credit'){
            $a = $credit;
            $orderby = 'ORDER BY `msg_date` DESC';
        }

        if($type === 'money'){
            $a = $money;
            $orderby = 'ORDER BY `msg_date` DESC';
        }

        if($type === 'all'){
            $a = array_merge($money,$credit);
            $a = array_diff($a, array(2,42,43,16,50,54,31,33,41,48,52,32,34,21,55,56,38,57,58,59,60,62,63,64,28,29,65,66,67,68,69,70,72,73,75,76,78,81,82,83,84,85,86,87,88));

            $a = array_merge($a,array(74,89));
            $orderby = 'ORDER BY `msg_read`,`msg_date` DESC';
        }

        $a = implode("','",$a);

        // $a = array_map(function($v) {
        //     return "'" . esc_sql($v) . "'";
        // }, $a);
        // $a = implode(',', $a);

        $and = '';
        
        if($type !== 'all'){
            $and = "AND msg_credit != 0";
        }

        //获取消息数据
        $data = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM $table_name
                WHERE user_id = %d AND msg_type IN ('".$a."') $and $orderby LIMIT %d,%d
                ",
                $_user_id,$offset,$number
            )
        ,ARRAY_A);

        $total = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(msg_id) FROM $table_name
            WHERE user_id = %d AND msg_type IN ('".$a."') $and
            ",
            $_user_id
        ));

        $read_count = 0;
        if($type === 'all'){
            $read_count = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(msg_id) FROM $table_name
                WHERE user_id = %d AND msg_type IN ('".$a."') AND msg_read=%d
                ",
                $_user_id,0
            ));

            $wpdb->update(
                $table_name,
                array( 
                    'msg_read' => 1,
                ), 
                array( 'msg_read' => 0,'user_id'=> $_user_id),
                array( 
                    '%d'
                ), 
                array( '%d','%d' ) 
            );
        }

        return array(
            'pages'=>ceil($total/$number),
            'data'=>self::order_data_map($data),
            'read_count'=>$read_count
        );
    }

    public static function order_data_map($data){
        if(empty($data)) return array();

        $_data = array();
        foreach ($data as $k => $v) {
            $title = array();

            switch ($v['msg_type']) {
                case 59:
                    $title = array(
                        'name'=>__('认证费用','b2'),
                        'link'=>b2_get_custom_page_url('verify')
                    );
                    break;
                case 61:
                    $title = array(
                        'name'=>__('签到填坑','b2'),
                        'link'=>b2_get_custom_page_url('verify')
                    );
                    break;
                case 64:
                    $title = array(
                        'name'=>__('优惠劵消费','b2'),
                        'link'=>''
                    );
                    break;
                case 89:
                    $days = (int)get_user_meta($v['user_id'],'b2_dark_room_days',true);
                    $title = array(
                        'name'=>$days === 0 ? __('永久关进小黑屋','b2') : sprintf(__('关进小黑屋%s天','b2'),$days),
                        'link'=>b2_get_custom_page_url('dark-room')
                    );
                    break;
                case 70:
                case 71:
                    $title = array(
                        'name'=>get_term( (int)$v['msg_key'] )->name,
                        'link'=>get_term_link((int)$v['msg_key'])
                    );
                    break;
                default:
                    $link = is_numeric($v['msg_key']) ? get_permalink($v['msg_key']) : 'javascript:void(0)';
                    $_title = get_the_title($v['msg_key']);
                    if(!$_title){
                        $_title = b2_get_des($v['msg_key'],50);
                    }
                    $title = array(
                        'name'=>$_title,
                        'link'=>$link
                    );
                    break;
            }

            $_data[] = array(
                'type'=>$v['msg_type'],
                'users'=>self::get_users($v['msg_users']),
                'title'=>$title,
                'number'=>$v['msg_credit'],
                'total'=>$v['msg_credit_total'],
                'date'=>b2_timeago($v['msg_date']),
                'content'=>self::msg_value($v['msg_value'],$v['msg_type']),
                'read'=>$v['msg_read'],
                'value'=>$v['msg_value']
            );
        }

        unset($data);

        return $_data;
    }

    public static function msg_value($value,$type){
        switch($type){
            case '1':
            case '2':
            case '3':
            case '8':
            case '10':
                return Comment::get_comment_content($value);
            case '66':
            case '67':
            case '68':
            case '69':
            case '70':
            case '71':
                $arg = explode('/',$value);
                $text = '';
                switch($arg[0]){
                    case 'gx':
                        $text = __('商品购买','b2');
                    break;
                    case 'w':
                        $text = __('购买隐藏内容','b2');
                    break;
                    case 'x':
                        $text = __('购买下载内容','b2');
                    break;
                    case 'v':
                        $text = __('视频购买','b2');
                    break;
                    case 'vip':
                        $text = __('会员购买','b2');
                    break;
                    case 'cg':
                        $text = __('积分购买','b2');
                    break;
                    case 'verify':
                        $text = __('购买认证服务','b2');
                    case 'circle_join':
                        $text = __('付费入圈','b2');
                    case 'circle_join':
                        $text = __('付费入圈','b2');
                    break;
                }
                
                return array(
                    'type'=>$text,
                    'money'=>isset($arg[1]) ? $arg[1] : '',
                    'ratio'=>isset($arg[2]) ? $arg[2] : ''
                );
                
            default :
                return $value;
                break;
        }
    }

    public static function get_users($users){
        $users = json_decode($users);

        $_users = array();
        if(is_array($users)){
            $users = array_reverse($users);
            $i = 1;
            foreach ($users as $k => $v) {

                if($i >= 5) break;
                if(is_numeric($v)){
                    $u = User::get_user_public_data($v,true);
                    if(!isset($u['error'])){
                        $_users[] = $u;
                    }else{
                        $_users[] = __('游客','b2');
                    }
                    $i++;
                }else{
                    $i++;
                    $_users[] = $v;
                }
                
            }
        }else{
            $_users[] = User::get_user_public_data($users,true);
        }
        unset($users);
        return $_users;
    }

    public static function add_message($data){

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_message';

        //检查之前是否有相同的数据
        $res = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM $table_name
                WHERE msg_read = %d AND user_id = %d AND msg_key = %s AND msg_type = %d AND msg_value = %s
                ",
                0,$data['user_id'],$data['msg_key'],$data['msg_type'],$data['msg_value']
            )
        ,ARRAY_A);

        if($res){
            $old = $res[0];
            $msg_users = $old['msg_users'];
            $msg_users = json_decode($msg_users,true);
            $msg_users = is_array($msg_users) ? $msg_users : array();

            if(!in_array($data['msg_users'],$msg_users)){
                $msg_users[] = $data['msg_users'];

                return $wpdb->update(
                    $table_name, 
                    array( 
                        'msg_users' => json_encode($msg_users),
                        'msg_date'=>current_time('mysql'),
                        'msg_credit'=>bcadd($old['msg_credit'],$data['msg_credit'],2),
                        'msg_credit_total'=>$data['msg_credit_total']
                    ), 
                    array( 'msg_id' => $old['msg_id']), 
                    array( 
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    ),
                    array( '%d' ) 
                );
            }else{
                return $wpdb->update(
                    $table_name, 
                    array( 
                        'msg_date'=>current_time('mysql'),
                        'msg_credit'=>bcadd($old['msg_credit'],$data['msg_credit'],2),
                        'msg_credit_total'=>$data['msg_credit_total']
                    ), 
                    array( 'msg_id' => $old['msg_id']), 
                    array( 
                        '%s',
                        '%s',
                        '%s'
                    ),
                    array( '%d' ) 
                );
            }
        }else{
            $users = array($data['msg_users']);
            $users = json_encode($users);
            $data['msg_users'] = $users;

            return $wpdb->insert(
                $table_name, 
                $data,
                array( 
                    '%d',//user_id
                    '%d',//msg_type
                    '%d',//msg_read
                    '%s',//msg_date
                    '%s',//msg_users
                    '%s',//msg_credit
                    '%s',//msg_credit_total
                    '%s',//msg_key
                    '%s'//msg_value
                )
            );
        }
    }

    //给出售者通知
    public function order_notify_return($data){

        $author_id = get_post_field('post_author', $data['post_id']);

        if($data['order_type'] === 'circle_join'){
            global $wpdb;

            $table_name = $wpdb->prefix . 'b2_circle_related';

            $res = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT * FROM $table_name
                    WHERE circle_id=%d
                    AND circle_role=%s
                    ",
                    $data['post_id'],'admin'
            ),ARRAY_A);

            if(empty($res)) return $data;
            $res = $res[0];

            $author_id = $res['user_id'];
        }

        $array = array(
            'v'=>array(
                'credit'=>49,
                'balance'=>47
            ),
            'x'=>array(
                'credit'=>53,
                'balance'=>51
            ),
            'w'=>array(
                'credit'=>34,
                'balance'=>32
            ),
            'ds'=>array(
                'balance'=>22
            ),
            'circle_join'=>array(
                'balance'=>71
            ),
            'circle_hidden_content_pay'=>array(
                'credit'=>85,
                'balance'=>86
            )
        );

        if(!isset($array[$data['order_type']])) return $data;
        
        if($data['order_total'] <= 0) return array('error'=>__('金额错误','b2'));

        if($data['pay_type'] === 'credit'){
            
            $total = Credit::credit_change($author_id,$data['order_total']);

        }else{
            
            $total = User::money_change($author_id,$data['order_total']);

        }

        $pay_type = $data['pay_type'];
        if($pay_type !== 'credit'){
            $pay_type = 'balance';
        }

        $type = $array[$data['order_type']];
        $type = $type[$pay_type];

        self::add_message(array(
            'user_id'=>$author_id,
            'msg_type'=>$type,
            'msg_read'=>0,
            'msg_date'=>current_time('mysql'),
            'msg_users'=>$data['user_id'],
            'msg_credit'=>$data['order_total'],
            'msg_credit_total'=>$total,
            'msg_key'=>$data['post_id'],
            'msg_value'=>$data['order_key']
        ));

        return $data;
    }

    /**
     * 给购买者通知
     * $data 订单数据
     * $balance 支付用户的总余额
     */
    public function balance_pay_after_message($data,$balance){

        if($data['order_type'] === 'circle_read_answer_pay') 
        return $this->circle_answer_read_after($data,$balance);

        $author_id = get_post_field('post_author', $data['post_id']);

        $array = array(
            'v'=>48,
            'x'=>52,
            'w'=>31,
            'ds'=>21,
            'cg'=>38,
            'vip'=>58,
            'verify'=>59,
            'circle_join'=>70,
            'circle_hidden_content_pay'=>88
        );

        if(!isset($array[$data['order_type']])) return $data;

        if($data['order_type'] === 'cg'){
            $data['post_id'] = $data['id'];
        }

        if($data['order_type'] === 'circle_join'){
            $author_id = '';
        }

        $res = self::add_message(array(
            'user_id'=>$data['user_id'],
            'msg_type'=>$array[$data['order_type']],
            'msg_read'=>self::no_read($data['order_type']),
            'msg_date'=>current_time('mysql'),
            'msg_users'=>(int)$array[$data['order_type']] === 58 ? '' : $author_id,
            'msg_credit'=>-$data['order_total'],
            'msg_credit_total'=>$balance,
            'msg_key'=>(int)$array[$data['order_type']] === 58 ? $data['id'] : $data['post_id'],
            'msg_value'=>(int)$array[$data['order_type']] === 58 && isset($data['title']) ? $data['title'] : ($data['order_type'] === 'circle_join' ? $data['order_key'] : '')
        ));
        
        if(isset($res['error'])) return $res;

        return $data;
    }

    public static function no_read($type){
        switch ($type) {
            case 'cg':
            case 'mission':
            case 'gx':
            case 'd':
            case 'c':
            case 'circle_join':
            case 'circle_read_answer_pay':
            case 'circle_hidden_content_pay':
                return 1;
            
            default:
                return 0;
        }
        return 0;
    }

    public function credit_pay_after_message($data,$credit){

        if($data['order_type'] === 'circle_read_answer_pay') 
        return $this->circle_answer_read_after($data,$credit);

        $author_id = '';

        if($data['post_id']){
            $author_id = get_post_field('post_author', $data['post_id']);
        }
        
        $array = array(
            'v'=>50,
            'x'=>54,
            'w'=>33,
            'mission'=>61,
            'd'=>28,
            'c'=>29,
            'circle_hidden_content_pay'=>87
        );

        if(!isset($array[$data['order_type']])) return $data;

        self::add_message(array(
            'user_id'=>$data['user_id'],
            'msg_type'=>$array[$data['order_type']],
            'msg_read'=>self::no_read($data['order_type']),
            'msg_date'=>current_time('mysql'),
            'msg_users'=>$author_id,
            'msg_credit'=>-$data['order_total'],
            'msg_credit_total'=>$credit,
            'msg_key'=>$data['post_id'],
            'msg_value'=>''
        ));

        return $data;

    }

    public function circle_answer_read_after($data,$credit){

        $author = get_post_field('post_author', $data['post_id']);

        //给偷瞄者通知
        self::add_message(array(
            'user_id'=>$data['user_id'],
            'msg_type'=>$data['pay_type'] === 'credit' ? 77 : 78,
            'msg_read'=>1,
            'msg_date'=>current_time('mysql'),
            'msg_users'=>$author,
            'msg_credit'=>-$data['order_total'],
            'msg_credit_total'=>$credit,
            'msg_key'=>$data['post_id'],
            'msg_value'=>''
        ));

        //给查看答案权限
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

                if($data['pay_type'] === 'credit'){
                    $total = Credit::credit_change($v,$average);
                }else{
                    $total = User::money_change($v,$average);
                }
                
                self::add_message(array(
                    'user_id'=>$v,
                    'msg_type'=>$data['pay_type'] === 'credit' ? 75 : 76,
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

        return $data;

    }
}