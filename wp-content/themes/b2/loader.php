<?php
namespace B2;

use B2\Modules\Settings\Main as SettingsLoader;
use B2\Modules\Templates\Main as TemplatesLoader;
use B2\Modules\Common\Main as CommonLoader;

if ( ! class_exists( 'B2', false ) ) {
    class B2{
        public function __construct(){

            spl_autoload_register('self::autoload');

            $this->load_library();

            $this->load_modules();
            
        }

        /**
         * 加载依赖
         *
         * @return void
         * @author Li Ruchun <lemolee@163.com>
         * @version 1.0.0
         * @since 2018
         */
        public function load_library(){

            $is_admin = is_admin() || $GLOBALS['pagenow'] === 'wp-login.php';

            try {
                $ext = new \ReflectionExtension('swoole_loader');
                $ver = $ext->getVersion();

                if($ver <= '2.1' || $ver >= '3.0'){
                    if(!$is_admin){
                        wp_die('<h2>'.__('系统维护中.....','b2').'</h2><p>'.__('如果您是管理员，请登陆后台操作','b2').'</p>');
                    }
                }else{
                    try {
                    preg_match("#^\d.\d#", PHP_VERSION, $p_v);

                    require B2_THEME_DIR . '/Modules/Common/Private/private'.$p_v[0].'.php';
                    }catch (\Throwable $th) {
                        
                        wp_die('<h2>'.__('请重启一下php','b2').'</h2><p>'.__('显示这个页面说明扩展未能正确加载，请重启一下您的PHP','b2').'</p>');
                        
                    }
                }
            } catch (\Throwable $th) {
                if(!$is_admin){
                    wp_die('<h2>'.__('系统维护中.....','b2').'</h2><p>'.__('如果您是管理员，请登陆后台操作','b2').'</p>');
                }
            }

            if($is_admin){
                //加载cmb2
                require B2_THEME_DIR . '/Library/Cmb2/init.php';
                require B2_THEME_DIR . '/Library/cmb2-nav-menu/cmb2-nav-menus.php';
                require B2_THEME_DIR . '/Library/cmb-field-select2/cmb-field-select2.php';
                add_action('admin_enqueue_scripts', function() {
                    wp_register_style('cmb2_widgets', B2_THEME_URI.'/Library/cmb2-widget/assets/cmb2-widgets.css', false, '1.0.0');
                    wp_register_script('cmb2_widgets', B2_THEME_URI.'/Library/cmb2-widget/assets/cmb2-widgets.js', ['jquery'], '1.0.0');
        
                    wp_enqueue_style('cmb2_widgets');
                    wp_enqueue_script('cmb2_widgets');
                });
            }
            
            require B2_THEME_DIR.'/Library/WeChatDeveloper/include.php';

            //加载图片裁剪库
            require B2_THEME_DIR.'/Library/Grafika/Grafika.php';

            //微信官方
            require B2_THEME_DIR .'/Library/Wxjs/jssdk.php';

            //加载 jwt
            // require_once B2_THEME_DIR . '/Library/jwt/includes/class-jwt-auth.php';
            // $jwt = new \Jwt_Auth();
            // $jwt->run();

        }

        /**
         * 加载模块
         *
         * @return void
         * @author Li Ruchun <lemolee@163.com>
         * @version 1.0.0
         * @since 2018
         */
        public function load_modules(){

            //加载设置项
            if(is_admin()){
                $settings = new SettingsLoader();
                $settings->init();
            }

            //加载公共类
            $common = new CommonLoader();
            $common->init();

            //加载模板
            $templates = new TemplatesLoader();
            $templates->init();
        }

        /**
         * 自动加载命名空间
         *
         * @return void
         * @author Li Ruchun <lemolee@163.com>
         * @version 1.0.0
         * @since 2018
         */
        public static function autoload($class){

            //主题模块
            if (strpos($class, 'B2\\') !== false) {
                $class = str_replace('B2\\','',$class);
                require B2_THEME_DIR.B2_DS.str_replace('\\', B2_DS, $class).'.php';
            }

            //图片裁剪库
            if(preg_match("/^Grafika\\\/i", $class)){
                $filename = B2_THEME_DIR.B2_DS.'Library'.B2_DS.str_replace('\\', B2_DS, $class).'.php';
                require $filename;
            }
        }
    }

    new B2();
}