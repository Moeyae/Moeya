<?php namespace B2\Modules\Templates;

class Widgets{
    public function init(){
        add_action( 'widgets_init', array($this,'register_widgets'));
    }

    public function register_widgets(){
        //文章聚合小工具
        register_widget( '\B2\Modules\Templates\Widgets\Post');

        //关于我们小工具
        register_widget( '\B2\Modules\Templates\Widgets\About');

        //连接组小工具
        register_widget( '\B2\Modules\Templates\Widgets\Links');

        //团队小工具
        register_widget( '\B2\Modules\Templates\Widgets\Team');

        //广告小工具
        register_widget( '\B2\Modules\Templates\Widgets\Html');

        if(!is_audit_mode()){
            //用户面板
            register_widget( '\B2\Modules\Templates\Widgets\User');

            //签到小工具
            register_widget( '\B2\Modules\Templates\Widgets\Mission');

            //最新评论小工具
            register_widget( '\B2\Modules\Templates\Widgets\Comment');

            //优惠劵小工具
            register_widget( '\B2\Modules\Templates\Widgets\Coupon');

            //商品聚合
            register_widget( '\B2\Modules\Templates\Widgets\Products');

            //快讯小工具
            register_widget( '\B2\Modules\Templates\Widgets\Newsflashes');

            //财富排行
            register_widget( '\B2\Modules\Templates\Widgets\CreditTop');

            //作者
            register_widget( '\B2\Modules\Templates\Widgets\Author');

            //下载小工具
            register_widget( '\B2\Modules\Templates\Widgets\Download');

            if(b2_get_option('circle_main','circle_open')){
                register_widget( '\B2\Modules\Templates\Widgets\CircleInfo');
                register_widget( '\B2\Modules\Templates\Widgets\HotCircle');
                register_widget( '\B2\Modules\Templates\Widgets\RecommendedCircle');
            }
        }

    }
}