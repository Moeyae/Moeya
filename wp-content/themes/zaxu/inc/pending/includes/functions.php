<?php
/*
 * @Description: Pending functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

// Set assets version start
    if ( !function_exists('zaxu_pending_set_assets_ver') ) :
        function zaxu_pending_set_assets_ver($src) {
            $file = $_SERVER['DOCUMENT_ROOT'] . parse_url($src, PHP_URL_PATH);
            if ( file_exists($file) ) {
                $version = filemtime($file);
                return $src . '?ver=' . $version;
            }
        }
    endif;
// Set assets version end

// Set 503 start
    if ( !function_exists('zaxu_pending_set_503') ) :
        function zaxu_pending_set_503() {
            $protocol = $_SERVER["SERVER_PROTOCOL"];
            if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol)
            $protocol = 'HTTP/1.0';
            header("$protocol 503 Service Unavailable", true, 503);
            header('Content-Type: text/html; charset=utf-8');
            require get_template_directory() . '/inc/pending/index.php';
            die();
        }
    endif;
// Set 503 end

// Pending mode start
    if ( !function_exists('zaxu_pending_mode') ) :
        function zaxu_pending_mode() {
            global $pending_status;
            if ($pending_status == "maintenance") {
                // Maintenance mode
                if (get_theme_mod('zaxu_maintenance_user_role') == 'administrator' || get_theme_mod('zaxu_maintenance_user_role') == '') {
                    if ( !current_user_can('edit_themes') || !is_user_logged_in() ) {
                        zaxu_pending_set_503();
                    }
                } else if (get_theme_mod('zaxu_maintenance_user_role') == 'logged') {
                    if ( !is_user_logged_in() ) {
                        zaxu_pending_set_503();
                    }
                }
            } else if ($pending_status == "license") {
                // License mode
                zaxu_pending_set_503();
            }
        }
        add_action('get_header', 'zaxu_pending_mode');
    endif;
// Pending mode end
?>