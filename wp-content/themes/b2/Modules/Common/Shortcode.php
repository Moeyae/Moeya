<?php namespace B2\Modules\Common;

use B2\Modules\Templates\Modules\Sliders;
use B2\Modules\Common\Coupon;
use B2\Modules\Common\Post;
use B2\Modules\Common\Circle;

class Shortcode{

    public function init(){
        //文件下载
        add_shortcode( 'zrz_file', array(__CLASS__,'file_down'));
        add_shortcode( 'b2_file', array(__CLASS__,'file_down'));

        add_shortcode( 'zrz_file_mp', array(__CLASS__,'file_down_mp'));
        add_shortcode( 'b2_file_mp', array(__CLASS__,'file_down_mp'));

        //隐藏内容
        add_shortcode('content_hide',array(__CLASS__,'content_hide'));
        add_shortcode('b2_content_hide',array(__CLASS__,'b2_content_hide'));

        //插入文章
        add_shortcode('zrz_insert_post',array(__CLASS__,'insert_post'));
        add_shortcode('b2_insert_post',array(__CLASS__,'insert_post'));

        add_shortcode('zrz_insert_post_mp',array(__CLASS__,'insert_post_mp'));
        add_shortcode('b2_insert_post_mp',array(__CLASS__,'insert_post_mp'));

        //邀请短代码
        add_shortcode( 'zrz_inv', array(__CLASS__,'invitation_list'));
        add_shortcode( 'b2_inv', array(__CLASS__,'invitation_list'));

        //优惠劵短代码
        add_shortcode( 'b2_coupon', array(__CLASS__,'coupon'));
    }

    //获取优惠劵
    public static function coupon($atts,$content = null){
       
        $a = shortcode_atts( array(
            'id'=>''
        ), $atts );

        $coupons = Coupon::get_coupons(array($a['id']),1);
        
        if(!empty($coupons)){
            foreach ($coupons as $k => $v) {

                $products = $v['products'];

                $desc = '';
                if(!empty($products)){
                    $title = __('限制商品','b2');
                    $type = 'stamp01';
                    foreach ($products as $_k => $_v) {
                        $thumb = b2_get_thumb(array('thumb'=>$_v['image'],'height'=>80,'width'=>80));
                        $desc .= '<a href="'.$_v['link'].'" target="_blank">
                            '.b2_get_img(array('src'=>$thumb,'class'=>array('b2-radius'))).'
                        </a> ';
                    }
                }elseif(!empty($v['cats'])){
                    $title = __('限制商品分类','b2');
                    $type = 'stamp02';
                    foreach ($v['cats'] as $c_k => $c_v) {
                        $desc .= '[<a href="'.$c_v['link'].'" target="_blank">'.$c_v['name'].'</a>] ';
                    }
                }else{
                    $title = __('不限制使用','b2');
                    $type = 'stamp03';
                    $desc .= __('所有商品和商品类型均可使用','b2');
                }

                $roles = '';
                if(!empty($v['roles']['lvs'])){
                    foreach ($v['roles']['lvs'] as $r_k => $r_v) {
                        $roles .= $r_v.' ';
                    }
                }else{
                    $roles = __('任何人都可以使用','b2'); 
                }

                $date = '';
                if($v['receive_date']['expired']){
                    $date = '<div class="coupon-desc">'.__('领取时间','b2').'</div>'.__('无法领取','b2');
                    $type = 'stamp04';
                }else{
                    if((int)$v['receive_date']['date'] === 0){
                        $date = '<div class="coupon-desc">'.__('领取时间','b2').'</div>'.__('随时领取','b2');
                    }else{
                        $date = '<div class="coupon-desc">'.__('领取时间截止到','b2').'</div>'.$v['receive_date']['date'];
                    }
                }

                $shixiao = '';
                if((int)$v['expiration_date']['date'] !== 0){
                    $shixiao = '<div class="coupon-desc">'.__('使用时效：','b2').'</div>'.$v['expiration_date']['date'].__('天内使用有效','b2');
                }else{
                    $shixiao = '<div class="coupon-desc">'.__('使用时效：','b2').'</div>'.__('永久有效','b2');
                }

                return '
                <div class="shop-coupon-item shortcode-coupon">
                    <div class="stamp '.$type.' b2-radius">
                        <div class="par">
                            <p>'.$title.'</p>
                            <sub class="sign">'.B2_MONEY_SYMBOL.'</sub><span>'.$v['money'].'</span><sub>'.__('优惠劵','b2').'</sub>
                            <div class="coupon-date">
                                <div>'.$shixiao.'</div>
                            </div>
                        </div>
                        <div class="copy">
                        <div class="copy-date">'.$date.'</div>
                            <p><button '.($type === 'stamp04' ? 'disabled="true"' : false).' class="coupon-receive" data-id="'.$v['id'].'">'.($type === 'stamp04' ? __('已经过期','b2') : __('立刻领取','b2')).'</button></p>
                            <div class="coupon-info-box">
                                <button class="text more-coupon-info">'.b2_get_icon('b2-information-line').__('查看详情','b2').'</button>
                                <div class="coupon-info b2-radius">
                                    <div class="shop-coupon-title"><div class="coupon-title"><span>'.__('优惠劵ID：','b2').'</span><span class="coupon-id">'.$v['id'].'</span></div><span class="close-coupon-info">×</span></div>
                                    <div class="">
                                        <span class="coupon-title">'.$title.'：</span>
                                        <div class="">'.$desc.'</div>
                                    </div>
                                    <div class="coupon-roles">
                                        <span class="coupon-title">'.__('限制用户组','b2').'：</span>
                                        <div class="coupon-roles-desc">'.$roles.'</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <i class="coupon-bg"></i>
                    </div>
                </div>
                ';
            }
        }
        return;
    }

    public static function insert_post_mp($atts,$content = null){
        $post_id = isset($atts['id']) ? $atts['id'] : false;
        if(!$post_id) return '';

        if(!is_numeric($post_id)){
            $url = $post_id;
            $post_id = url_to_postid($post_id);

            if(strpos($url,'/circle') !== false && $post_id === 0){
                if($url === home_url('/circle') || $url === home_url('/circle/')){
                    $circle_id = get_option('b2_circle_default');
                }else{
                    $slug = str_replace(home_url('/circle/'),'',$url);
                    $circle_id = get_term_by('slug', $slug, 'circle_tags');
                    if(isset($circle_id->term_id)){
                        $circle_id = $circle_id->term_id;
                    }else{
                        return '';
                    }
                }

                $circle_data = Circle::get_circle_data($circle_id);

                if(isset($circle_data['name'])){
                    return '<div class="insert-post circle_tags">
                    <div class="insert-post-content">
                        <div class="circle-type">'.__('圈子','b2').'</div>
                        <div class="insert-post-title"><a href="'.$circle_data['link'].'" target="_blank">'.$circle_data['name'].'</a></div>
                    </div>
                    </div>
                    ';
                }
            }
        }

        $post_type = get_post_type($post_id);

        if(!$post_type) return;

        $post_meta = Post::post_meta($post_id);

        $html = '<div class="insert-post '.$post_type.'">';

        if($post_type === 'post'){
            $html .= '<div class="insert-post-content">
                <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></div>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
                <div class="insert-post-meta">
                    <div class="insert-post-meta-avatar"><a href="'.$post_meta['user_link'].'">'.$post_meta['user_name'].'</a></div>
                    <div class="post-meta">
                        <div class="single-date">
                            '.$post_meta['date'].'
                        </div>
                        <div class="single-like">
                            '.__('喜欢：','b2').$post_meta['like'].'
                        </div>
                        <div class="single-eye">
                            '.__('访问：','b2').$post_meta['views'].'
                        </div>
                    </div>
                </div>
            </div>';
        }else if($post_type === 'page'){
            $html .= '<div class="insert-post-content">
                <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></div>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
            </div>';
        }else

        //插入快讯
        if($post_type === 'newsflashes'){
            $vote_up = b2_get_option('newsflashes_main','newsflashes_vote_up_text');
            $vote_down = b2_get_option('newsflashes_main','newsflashes_vote_down_text');

            $vote = Post::get_post_vote_up($post_id);
            
            $html .= '<div class="insert-post-content">
                <div><a href="'.$post_meta['user_link'].'">'.$post_meta['user_name'].'</a></div>
                <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></div>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
                <div class="insert-post-meta">
                    <div class="single-date">'.$post_meta['date'].'</div>
                    <div class="post-meta">
                        <div class="single-like">
                            '.$vote_up.$vote['up'].'
                        </div>
                        <div class="single-eye">
                            '.$vote_down.$vote['down'].'
                        </div>
                    </div>
                </div>
            </div>';
        }else

        //插入文档
        if($post_type === 'document'){
            $html .= '<div class="insert-post-content">
                <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></div>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
            </div>';
        }else

        if($post_type === 'shop'){

            $data = Shop::get_shop_item_data($post_id,0);
            $type = $data['type'];
            $type = $type === 'normal' ? __('出售','b2') : ($type === 'lottery' ? __('抽奖','b2') : __('兑换','b2'));
            $icon = $data['type'] === 'normal' ? B2_MONEY_SYMBOL : __('积分：','b2');

            if(isset($data['price']['price'])){
                $html .= '<div class="insert-post-content">
                    <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a><span class="insert-post-title-span">['.$type.']</span></div>
                    <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
                    <div class="insert-post-meta">
                        <div class="insert-shop-price">
                            <div class="price">'.$icon.$data['price']['current_price'].'</div>
                            <div class="delete">'.$icon.$data['price']['price'].'</div>
                        </div>
                        <div class="post-meta">
                            <div class="single-date">
                                '.__('库存：','b2').$data['stock']['total'].'
                            </div>
                            <div class="single-like">
                                '.__('已售：','b2').$data['stock']['sell'].'
                            </div>
                            <div class="single-eye">
                                '.__('人气：','b2').$data['views'].'
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }else

        if($post_type === 'circle'){
            $title = get_the_title($post_id);
            if(!$title){
                $title = get_post_meta($post_id,'b2_auto_title',true);
            }
            
            if(!$title){
                $title = __('无标题的话题','b2');
            }
            $html .= '<div class="insert-post-content">
                <div class="circle-topic">'.__('圈子话题','b2').'</div>
                <div class="insert-post-title"><a href="'.get_permalink($post_id).'" target="_blank">'.$title.'</a></div>
            </div>';
        }else{
            return '';
            global $wp;
            $current_url = home_url(add_query_arg(array(),$wp->request));
            $html .= '<div class="insert-post-content">
                <div class="insert-post-title"><a href="'.$current_url.'" target="_blank">'.wp_get_document_title().'</a></div>
            </div>';
        }

        $html .= '</div>';

        return $html;
    }

    public static function b2_content_hide($atts,$content = null){

        $post_id = get_the_id();

        $_role = get_post_meta($post_id,'b2_post_reading_role',true);
        if(!$_role || $_role == 'none') return $content;

        $role = self::get_content_hide_arg($post_id);
    
        if(is_array($role)){
            $str = $content;
        }else{
            $str = $role;
        }

        return '<div class="content-hidden">
            <div class="content-hidden-info">
                <div>
                    '.do_shortcode($str).'
                </div>
            </div>
        </div>';
    }

    public static function content_hide($atts,$content = null){

        $post_id = get_the_id();
        $_role = get_post_meta($post_id,'b2_post_reading_role',true);
        if(!$_role || $_role == 'none') return $content;

        return '<div class="content-hidden">
            <div class="content-hidden-info">
                <div class="content-show-roles b2-mark">
                </div>
            </div>
        </div>';
    }

    public static function login_button($tag){
        
        //是否允许注册
        $allow_sign = b2_get_option('normal_login','allow_register');

        $login = '<div class="content-user-lv-login">
            <'.$tag.' class="empty content-cap-login button empty" onclick="userTools.login(1)">'.__('登录','b2').'</'.$tag.'>
            '.($allow_sign ? '<'.$tag.' class="content-cap-signin button" onclick="userTools.login(2)">'.__('注册','b2').'</'.$tag.'>' : '').'</div>';

        return $login;
    }

    public static function get_content_hide_arg($post_id,$order_id = '',$json = false){
        $user_id = get_current_user_id();
        $can_guset_pay = (int)get_post_meta($post_id,'b2_hidden_guest_buy',true);

        //检查用户的权限
        $role = self::check_reading_cap($post_id,$user_id,$order_id);
        
        if(isset($role['error'])) return '';

        if($role['cap'] === 'dark_room'){

            if($json){
                return array(
                    'data'=>'dark_room',
                    'role'=>$role
                );
            }

            return '<div class="content-cap">
                    <div>
                        <div class="content-cap-title"><span>'.b2_get_icon('b2-git-repository-private-line').__('小黑屋禁闭','b2').'</span></div>
                        <div class="content-buy-count"><span>'.__('无法查看隐藏内容','b2').'</span></div>
                    </div>
                    <div class="content-cap-info">
                        '.__('小黑屋思过中...','b2').'
                    </div>
                </div>';
        }

        //登录可见
        if($role['cap'] === 'login' && !$user_id){

            if($json){
                return array(
                    'data'=>'login',
                    'role'=>$role
                );
            }

            return '<div class="content-cap">
                <div>
                    <div class="content-cap-title" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'><span>'.b2_get_icon('b2-git-repository-private-line').__('隐藏内容，登录后阅读','b2').'</span></div>
                    <div class="content-buy-count"><span>'.__('若无账户，请先注册','b2').'</span></div>
                </div>
                <div class="content-cap-info">
                    '.self::login_button('button').'
                </div>
                </div>';
        }

        //评论可见
        if($role['cap'] === 'comment' && $role['allow'] === false){

            if($json){
                return array(
                    'data'=>'comment',
                    'role'=>$role
                );
            }

            return '<div class="content-cap">
                <div>
                    <div class="content-cap-title" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'><span>'.b2_get_icon('b2-git-repository-private-line').__('隐藏内容，评论后阅读','b2').'</span></div>
                    <div class="content-buy-count"><span>'.__('评论后，请刷新页面','b2').'</span></div>
                </div>
                <div class="content-cap-info">
                    '.(!$user_id ? self::login_button('button') : '').'
                </div>
                </div>';
        }

        //限制等级可见
        if($role['cap'] === 'roles' && $role['allow'] === false){
            $lvs = '';
            foreach ($role['roles'] as $k => $v) {
                $lvs .= User::get_lv_icon($v);
            }

            if($json){

                $_lvs = array();

                foreach ($role['roles'] as $k => $v) {
                    $_lvs[] = User::get_lv_icon($v,true);
                }

                return array(
                    'data'=>$_lvs,
                    'role'=>$role
                );
            }

            return '<div class="content-cap content-see-lv">
                <div>
                    <div class="content-cap-title">
                        <span>'.b2_get_icon('b2-git-repository-private-line').__('隐藏内容，仅限以下用户组阅读','b2').'</span>
                    </div>
                    <div class="content-buy-count"><span>'.__('如果您未在其中，可以升级','b2').'</span></div>
                    <div class="content-cap-info content-user-lv">
                        '.$lvs.'
                    </div>
                </div>
                    '.(!$user_id ? 
                        self::login_button('a href="javascript:void(0)"')
                        : '<div class="content-cap-title"><a href="'.b2_get_custom_page_url('vips').'" target="_blank" class="button">'.__('立刻升级','b2').'</a></div>').'

            </div>';
        }

        if($role['cap'] === 'money' && $role['allow'] === false){

            $data = array(
                'order_price'=>$role['m_c'],
                'order_type'=>'w',
                'post_id'=>$post_id,
                'title'=>wptexturize(b2_get_des(0,200,get_the_title($post_id)))
            );

            $buy_count = get_post_meta($post_id,'zrz_buy_user',true);
            $buy_count = is_array($buy_count) ? count($buy_count) : 0;

            $default_count = get_post_meta($post_id,'b2_post_hidden_count',true);
            $buy_count = $buy_count+(int)$default_count;

            $pass_times = get_post_meta($post_id,'b2_post_hidden_times',true);

            if($json){
                return array(
                    'data'=>array(
                        'pay'=>$data,
                        'count'=>$buy_count,
                        'pass_time'=>$pass_times
                    ),
                    'role'=>$role
                );
            }

            return '<div class="content-cap">
                <div>
                    <div class="content-cap-title" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'>
                        <span>'.b2_get_icon('b2-git-repository-private-line').__('隐藏内容，支付费用后阅读','b2').'</span>'.($pass_times ? '<span class="hidden-tips">'.sprintf(__('购买完%s小时后过期','b2'),$pass_times).'</span>' : '').'
                    </div>
                    <div class="content-buy-count" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'><span>'.sprintf(__('已经有%s人购买查看了此内容','b2'),'<b>'.$buy_count.'</b>').'</span></div>
                    <div class="content-cap-info content-user-money">
                        <span class="user-money">'.B2_MONEY_SYMBOL.'<b>'.$role['m_c'].'</b></span>
                    </div>
                </div>
                '.(!$can_guset_pay && !$user_id ? self::login_button('a href="javascript:void(0)"') : '<div class="content-user-lv-login">
                <button data-pay=\''.json_encode($data,true).'\' class="empty content-cap-login" onClick="b2pay(this)">'.__('支付','b2').'</button>
            </div>' ).'
            </div>';
        }

        if($role['cap'] === 'credit' && $role['allow'] === false){
            $data = array(
                'order_price'=>$role['m_c'],
                'order_type'=>'w',
                'post_id'=>$post_id,
                'title'=>wptexturize(b2_get_des(0,200,get_the_title($post_id)))
            );

            $buy_count = get_post_meta($post_id,'zrz_buy_user',true);
            $buy_count = is_array($buy_count) ? count($buy_count) : 0;

            $default_count = get_post_meta($post_id,'b2_post_hidden_count',true);
            $buy_count = $buy_count+(int)$default_count;

            $pass_times = get_post_meta($post_id,'b2_post_hidden_times',true);

            if($json){
                return array(
                    'data'=>array(
                        'pay'=>$data,
                        'count'=>$buy_count,
                        'pass_time'=>$pass_times
                    ),
                    'role'=>$role
                );
            }

            return '<div class="content-cap">
                <div>
                    <div class="content-cap-title" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'><span>'.b2_get_icon('b2-git-repository-private-line').__('隐藏内容，支付积分后阅读','b2').'</span>'.($pass_times ? '<span class="hidden-tips">'.sprintf(__('购买完%s小时后过期','b2'),$pass_times).'</span>' : '').'</div>
                    <div class="content-buy-count" '.(!$user_id ? 'onclick="userTools.login(1)"' : '').'><span>'.sprintf(__('已经有%s人购买查看了此内容','b2'),'<b>'.$buy_count.'</b>').'</span></div>
                    <div class="content-cap-info content-user-money">
                        <span class="user-money">'.b2_get_icon('b2-coin-line').$role['m_c'].'</span>
                    </div>
                </div>
                '.(!$user_id ? 
                    self::login_button('a href="javascript:void(0)"')
                    : '<div class="content-user-lv-login">
                        <button class="empty content-cap-login" data-pay=\''.json_encode($data,true).'\' onclick="b2creditpay(this)">'.__('支付','b2').'</button>
                    </div>').'
            </div>';
        }

        if($json){
            return array(
                'data'=>'',
                'role'=>$role
            );
        }

        $arg = array();
        $pattern = get_shortcode_regex();

        if (   preg_match_all( '/'. $pattern .'/s',get_post_field('post_content',$post_id) , $matches )
            && array_key_exists( 2, $matches )
            && in_array( 'content_hide', $matches[2] )
            && !empty($matches[0]))
        {
            foreach ($matches[0] as $k => $v) {
                if(strpos($v,'content_hide') !== false && strpos($v,'_content_hide') === false){
                    $content = str_replace(array('[content_hide]','[/content_hide]'),'',$v);
                    $content = str_replace( ']]>', ']]&gt;', $content);
                    $arg[] = do_shortcode(wpautop($content));
                }
            }
        }

        return $arg;
    }

    /**
     * 检查文章的阅读权限
     *
     * @param int $post_id 文章ID
     * @param int $user_id 当前用户的ID
     *
     * @return void
     * @author Li Ruchun <lemolee@163.com>
     * @version 1.0.0
     * @since 2018
     */
    public static function check_reading_cap($post_id,$user_id,$order_id = ''){

        $cap = apply_filters('check_reading_cap', array('post_id'=>$post_id,'user_id'=>$user_id));

        if(isset($cap['user_id'])) return array('error'=>__('没有数据','b2'));
        $allow = false;

        $m_c = 0;

        $roles = array();

        $dark_room = (int)get_user_meta($user_id,'b2_dark_room',true);
        if($dark_room) {
            $allow = false;
            $cap = 'dark_room';
        }else{
            //如果没有限制权限
            if(!$cap || $cap === 'none' || user_can( $user_id, 'manage_options' )){
                $allow = true;
            }

            //如果是允许查看所有隐藏内容的用户
            elseif(User::check_user_can_read_all($user_id)){
                $allow = true;
            }

            //如果是登录可见
            elseif($cap === 'login' && $user_id) {
                $allow = true;
            }

            //如果是管理员
            elseif(user_can($user_id,'delete_users')) {
                $allow = true;
            }

            //如果是文章作者
            elseif(get_post_field('post_author',$post_id) == $user_id) {
                $allow = true;
            }

            //如果是评论可见
            elseif($cap === 'comment'){
                $allow = self::check_user_commented($user_id,$post_id);
            }

            //如果是限制等级可见
            elseif($cap === 'roles'){
                $roles = get_post_meta($post_id,'b2_post_roles',true);
                $roles = is_array($roles) ? $roles : array();
                $user_role = User::get_user_lv($user_id);

                if(isset($user_role['vip']['lv']) && isset($user_role['lv']['lv'])){
                    if(in_array($user_role['vip']['lv'],$roles) || in_array($user_role['lv']['lv'],$roles)){
                        $allow = true;
                    }
                }
            }else{

                $allow = apply_filters('b2_get_content_hide_allow', array('post_id'=>$post_id,'user_id'=>$user_id,'order_id'=>$order_id,'order_type'=>'w','order_key'=>0));

                if(!$allow){
                    if($cap === 'money'){
                        $m_c = get_post_meta($post_id,'b2_post_money',true);
                    }elseif($cap === 'credit'){
                        $m_c = get_post_meta($post_id,'b2_post_credit',true);
                    }
                }
            }
        }
        
		
        return array(
            'allow'=>$allow,
            'cap'=>$cap,
            'm_c'=>$m_c,
            'roles'=>$roles
        );

    }

    public static function check_user_commented($user_id,$post_id){
        $allow = false;

        //如果是游客
        if(!$user_id){
            $commenter = wp_get_current_commenter();

            if(!$commenter['comment_author_email']){
                $allow = false;
            }else{
                $args = array( 
                    'post_id' => $post_id,
                    'author_email'=>$commenter['comment_author_email'],
                    'status'=>'approve'
                );
    
                $comment = get_comments($args);
    
                if(!empty($comment)){
                    $allow = true;
                }
            }

           

        //如果不是游客，检查是否在文章中评论过
        }else{
            $args = array( 
                'user_id' => $user_id, 
                'post_id' => $post_id,
                'status'=>'approve'
            );

            $comment = get_comments($args);

            if(!empty($comment)){
                $allow = true;
            }

        }

        return $allow;
    }

    public static function file_down($atts,$content = null){

        $a = shortcode_atts( array(
            'link'=>'',
            'name'=>'',
            'pass'=>'',
            'code'=>'',
        ), $atts );

        $html = '<div class="file-down b2-radius">';
        
        $html .= '<div class="file-down-icon">'.b2_get_icon('b2-download-cloud-line').'</div>';

        $html .= '<div class="file-down-box">
            <div class="file-down-code">
                <h2>'.$a['name'].'</h2>
                <div class="file-down-pass">
                    '.__('提取码：','b2').($a['pass'] ? '<code>'.esc_attr($a['pass']).'</code><span>'.__('复制','b2').'<input value="'.esc_attr($a['pass']).'" type="text" class="b2-hidden"></span>' : __('无','b2')).'
                </div>
                <div class="file-down-pass">
                    '.__('解压码：','b2').($a['code'] ? '<code>'.esc_attr($a['code']).'</code><span>'.__('复制','b2').'<input value="'.esc_attr($a['code']).'" type="text" class="b2-hidden"></span>' : __('无','b2')).'
                </div>
            </div>
            <div class="file-down-code-button"><a class="button empty" target="_blank" href="'.$a['link'].'">'.__('下载','b2').'</a></div>
        </div>';

        $html .= '</div>';

        return $html;
    }

    public static function file_down_mp($atts,$content = null){

        $a = shortcode_atts( array(
            'link'=>'',
            'name'=>'',
            'pass'=>'',
            'code'=>'',
        ), $atts );

        $html = '<div class="file-down">';

        $html .= '
            <div class="insert-post-title">'.$a['name'].'</div>
            <div class="file-down-pass">
                '.__('提取码：','b2').($a['pass'] ? esc_attr($a['pass']) : __('无','b2')).'
            </div>
            <div class="file-down-pass">
                '.__('解压码：','b2').($a['code'] ? esc_attr($a['code']) : __('无','b2')).'
            </div>
            
            <div class="file-down-code-button"><a class="button empty" target="_blank" href="'.$a['link'].'">'.__('下载','b2').'</a></div>
        ';

        $html .= '</div>';

        return $html;
    }

    public static function insert_post($atts,$content = null){

        $post_id = isset($atts['id']) ? $atts['id'] : false;
        if(!$post_id) return '';

        if(!is_numeric($post_id)){
            $url = $post_id;
            $post_id = url_to_postid($post_id);

            if(strpos($url,'/circle') !== false && $post_id === 0){

                if($url === home_url('/circle') || $url === home_url('/circle/')){
                    $circle_id = get_option('b2_circle_default');
                }else{
                    $slug = str_replace(home_url('/circle/'),'',$url);
                    $circle_id = get_term_by('slug', $slug, 'circle_tags');
                    if(isset($circle_id->term_id)){
                        $circle_id = $circle_id->term_id;
                    }else{
                        return '';
                    }
                }

                $circle_data = Circle::get_circle_data($circle_id);

                if(isset($circle_data['name'])){
                    return '<div class="insert-post b2-radius circle_tags">
                    <span class="insert-post-bg"></span>
                    <div class="insert-post-thumb"><img src="'.$circle_data['icon'].'" class="b2-radius"/></div>
                    <div class="file-down-icon">'.b2_get_icon('b2-donut-chart-fill').'</div><div class="insert-post-content">
                        <p>'.__('圈子','b2').'</p>
                        <h2><a href="'.$circle_data['link'].'" target="_blank">'.$circle_data['name'].'</a></h2>
                    </div>
                    </div>
                    ';
                }
            }
        }

        $post_type = get_post_type($post_id);

        if(!$post_type) return;

        $thumb_url = \B2\Modules\Common\Post::get_post_thumb($post_id);

        $thumb_url = b2_get_thumb(array(
            'thumb'=>$thumb_url,
            'width'=>140,
            'height'=>100
        ));

        $thumb = b2_get_img(array('src'=>$thumb_url,'class'=>array('b2-radius')));

        $post_meta = Post::post_meta($post_id);

        $html = '<div class="insert-post b2-radius '.$post_type.'"><span class="insert-post-bg">
        '.$thumb.'
        </span>';

        $html .= '<div class="insert-post-thumb">
            <a href="'.get_permalink($post_id).'" target="_blank">
            '.$thumb.'
            </a>
        </div>';

        if($post_type === 'post'){
            $html .= '<div class="insert-post-content">
                <h2><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></h2>
                <div class="insert-post-meta">
                    <div class="insert-post-meta-avatar"><img class="avatar" src="'.$post_meta['user_avatar'].'" /><a href="'.$post_meta['user_link'].'">'.$post_meta['user_name'].'</a></div>
                    <ul class="post-meta">
                        <li class="single-date">
                            '.$post_meta['date'].'
                        </li>
                        <li class="single-like">
                            '.b2_get_icon('b2-heart-fill').$post_meta['like'].'
                        </li>
                        <li class="single-eye">
                            '.b2_get_icon('b2-eye-fill').$post_meta['views'].'
                        </li>
                    </ul>
                </div>
            </div>';
        }else if($post_type === 'page'){
            $html .= '<div class="file-down-icon">'.b2_get_icon('b2-pages-line').'</div><div class="insert-post-content">
                <h2><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></h2>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
            </div>';
        }else

        //插入快讯
        if($post_type === 'newsflashes'){
            $vote_up = b2_get_option('newsflashes_main','newsflashes_vote_up_text');
            $vote_down = b2_get_option('newsflashes_main','newsflashes_vote_down_text');

            $vote = Post::get_post_vote_up($post_id);
            
            $html .= '<div class="insert-post-meta-avatar"><a href="'.$post_meta['user_link'].'"><img class="avatar" src="'.$post_meta['user_avatar'].'" /></a></div><div class="insert-post-content mg-l">
                <div><a href="'.$post_meta['user_link'].'">'.$post_meta['user_name'].'</a></div>
                <h2><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></h2>
                <div class="insert-post-meta">
                    <div>'.$post_meta['date'].'</div>
                    <ul class="post-meta">
                        <li class="single-like">
                            '.b2_get_icon('b2-funds-box-line').$vote_up.$vote['up'].'
                        </li>
                        <li class="single-eye">
                            '.b2_get_icon('b2-funds-box-line1').$vote_down.$vote['down'].'
                        </li>
                    </ul>
                </div>
            </div>';
        }else

        //插入文档
        if($post_type === 'document'){
            $html .= '<div class="document-icon">'.b2_get_icon('b2-questionnaire-line').'</div><div class="insert-post-content">
                <h2><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a></h2>
                <div class="insert-post-desc">'.Sliders::get_des($post_id,150).'</div>
            </div>';
        }else

        if($post_type === 'shop'){

            $data = Shop::get_shop_item_data($post_id,0);
            $type = $data['type'];
            $type = $type === 'normal' ? __('出售','b2') : ($type === 'lottery' ? __('抽奖','b2') : __('兑换','b2'));
            $icon = $data['type'] === 'normal' ? B2_MONEY_SYMBOL : b2_get_icon('b2-coin-line');

            if(isset($data['price']['price'])){
                $html .= '<div class="insert-post-content">
                    <h2><a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a><span>['.$type.']</span></h2>
                    <div class="insert-post-meta">
                        <div class="insert-shop-price">
                            <div class="price">'.$icon.$data['price']['current_price'].'</div>
                            <div class="delete">'.$icon.$data['price']['price'].'</div>
                        </div>
                        <ul class="post-meta">
                            <li class="single-date">
                                '.__('库存：','b2').$data['stock']['total'].'
                            </li>
                            <li class="single-like">
                                '.__('已售：','b2').$data['stock']['sell'].'
                            </li>
                            <li class="single-eye">
                                '.__('人气：','b2').$data['views'].'
                            </li>
                        </ul>
                    </div>
                </div>';
            }
        }else

        if($post_type === 'circle'){
            $title = get_the_title($post_id);
            if(!$title){
                $title = get_post_meta($post_id,'b2_auto_title',true);
            }
            
            if(!$title){
                $title = __('无标题的话题','b2');
            }
            $html .= '<div class="file-down-icon">'.b2_get_icon('b2-chat-smile-3-line').'</div><div class="insert-post-content">
                <p>'.__('圈子话题','b2').'</p>
                <h2><a href="'.get_permalink($post_id).'" target="_blank">'.$title.'</a></h2>
            </div>';
        }else{
            return '';
            global $wp;
            $current_url = home_url(add_query_arg(array(),$wp->request));
            $html .= '<div class="document-icon">'.b2_get_icon('b2-pages-line').'</div><div class="insert-post-content">
                <h2><a href="'.$current_url.'" target="_blank">'.wp_get_document_title().'</a></h2>
            </div>';
        }

        $html .= '</div>';

        return $html;
    }

    public static function invitation_list($atts,$content = null){

        global $post;
        if(!isset($post->ID)) return;
        $user_id = get_post_field('post_author', $post->ID);

        $user = get_userdata($user_id);

        if(!empty($user->roles) && !in_array('administrator', $user->roles)){
            return '';
        }
        
        $start = isset($atts['start']) ? (int)$atts['start'] : 1;
        $end = isset($atts['end']) ? (int)$atts['end'] : 20;
        $owner = isset($atts['owner']) ? (int)$atts['owner'] : $user_id;

        global $wpdb;
        $table_name = $wpdb->prefix . 'zrz_invitation';
        $codes = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM $table_name WHERE
                invitation_owner=%d AND (id>=%d AND id<=%d)
                ",
                $owner,$start, $end
            ),ARRAY_A );

        $html = '';
        if(count($codes) > 0){
            $html = '<table class="wp-list-table widefat fixed striped shop_page_order_option">
            <thead>
                <tr><td>'.__('编号','b2').'</td><td>'.__('邀请码','b2').'</td><td>'.__('奖励','b2').'</td><td>'.__('使用状态','b2').'</td><td>'.__('使用者','b2').'</td></tr>
            </thead>
            <tbody>';
            $i = 0;
            foreach ($codes as $code) {
                $i++;
                if($code['invitation_user']){
                    $user = '<a target="__blank" href="'.get_author_posts_url($code['invitation_user']).'">'.get_the_author_meta('display_name',$code['invitation_user']).'</a>';
                }else{
                    $user = __('无','b2');
                }
                $html .= '<tr>
                <td>'.$i.'</td>
                <td>'.$code['invitation_nub'].'</td>
                <td>'.$code['invitation_credit'].'</td>
                <td>'.($code['invitation_status'] ? '<span style="color:green">已使用</span>' : '<span style="color:red">未使用</span>').'</td>
                <td>'.$user.'</td>
                </tr>';
            }
            $html .= '</tbody>
            </table>';
        }
        return $html;
    }
}