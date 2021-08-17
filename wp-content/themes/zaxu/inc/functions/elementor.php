<?php
/*
 * @Description: Elementor functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

// Check PJAX status start
    if ( esc_attr( get_theme_mod('zaxu_site_ajax', 'enabled') ) == 'enabled' && current_user_can('administrator') ) {
        function zaxu_elementor_pjax_notice() {
            $class = 'notice notice-info';
            $message = __('We noticed that you have installed Elementor plugin and PJAX Powered is enabled. We recommend that to disable PJAX Powered for a better experience.', 'zaxu');
            $query['autofocus[control]'] = 'zaxu_site_ajax';
            $section_link = add_query_arg( $query, admin_url('customize.php') );
            printf(
                '
                    <div class="%1$s">
                        <p>%2$s</p>
                        <p>
                            <a href="' . $section_link . '" class="button button-primary">' . __('To Disable PJAX Powered', 'zaxu') . '</a>
                        </p>
                    </div>
                ',
                esc_attr($class),
                esc_html($message)
            ); 
        }
        add_action('admin_notices', 'zaxu_elementor_pjax_notice');
    }
// Check PJAX status end
?>