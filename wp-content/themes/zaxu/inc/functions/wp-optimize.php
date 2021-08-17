<?php
/*
 * @Description: WordPress Optimize functions
 * @Version: 2.7.2
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

// Disable site icon start
    function zaxu_disable_site_icon() {
        global $wp_customize;
        $wp_customize->remove_control('site_icon');
    }
    add_action('customize_register', 'zaxu_disable_site_icon', 20);

    add_filter('site_icon_meta_tags', 'zaxu_filter_site_icon_meta_tags');
    function zaxu_filter_site_icon_meta_tags($meta_tags) {
        array_splice($meta_tags, 2);
        return $meta_tags;
    }
// Disable site icon end

// Head icon start
    function zaxu_head_icon() {
        if (get_theme_mod('zaxu_pwas', 'disabled') == 'enabled') {
            if ( get_theme_mod('zaxu_pinned_tab_icon_background_color') ) {
                $m_theme_color = get_theme_mod('zaxu_pinned_tab_icon_background_color');
            } else {
                $m_theme_color = get_theme_mod('zaxu_pinned_tab_icon_background_color', '#ff3b30');
            }
            echo '
                <meta name="mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="default">
                <meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo('name') ) . '">
            ';
            if ( get_theme_mod('zaxu_apple_touch_icon') ) {
                $icon = wp_get_attachment_image_url(get_theme_mod('zaxu_apple_touch_icon'), 'full');
                $blah = parse_url($icon);
                $icon_size = getimagesize( ltrim($blah['path'], '/') );
                $width = $icon_size[0];
                $height = $icon_size[1];
                if ($width > '194' || $height > '194') {
                    $icon16 = aq_resize($icon, 16, 16, true);
                    $icon32 = aq_resize($icon, 32, 32, true);
                    $icon57 = aq_resize($icon, 57, 57, true);
                    $icon60 = aq_resize($icon, 60, 60, true);
                    $icon72 = aq_resize($icon, 72, 72, true);
                    $icon76 = aq_resize($icon, 76, 76, true);
                    $icon96 = aq_resize($icon, 96, 96, true);
                    $icon114 = aq_resize($icon, 114, 114, true);
                    $icon120 = aq_resize($icon, 120, 120, true);
                    $icon144 = aq_resize($icon, 144, 144, true);
                    $icon152 = aq_resize($icon, 152, 152, true);
                    $icon180 = aq_resize($icon, 180, 180, true);
                    $icon192 = aq_resize($icon, 192, 192, true);
                    $icon194 = aq_resize($icon, 194, 194, true);

                    $ms_application_title_color = get_theme_mod('zaxu_pinned_tab_icon_background_color', '#ff3b30');

                    echo '
                        <link rel="apple-touch-icon" href="' . $icon .'">
                        <link rel="apple-touch-icon" sizes="57x57" href="' . $icon57 . '">
                        <link rel="apple-touch-icon" sizes="60x60" href="' . $icon60 . '">
                        <link rel="apple-touch-icon" sizes="72x72" href="' . $icon72 . '">
                        <link rel="apple-touch-icon" sizes="76x76" href="' . $icon76 . '">
                        <link rel="apple-touch-icon" sizes="114x114" href="' . $icon114 . '">
                        <link rel="apple-touch-icon" sizes="120x120" href="' . $icon120 . '">
                        <link rel="apple-touch-icon" sizes="144x144" href="' . $icon144 . '">
                        <link rel="apple-touch-icon" sizes="152x152" href="' . $icon152 . '">
                        <link rel="apple-touch-icon" sizes="180x180" href="' . $icon180 . '">
                        <meta name="msapplication-TileColor" content="' . $ms_application_title_color . '">
                        <meta name="msapplication-TileImage" content="' . $icon144 . '">
                    ';
                }
            }
        }

        if ( get_theme_mod('zaxu_favicon_image') ) {
            $favicon = get_theme_mod('zaxu_favicon_image');
            $blah = parse_url($favicon);
            $favicon_size = getimagesize( ltrim($blah['path'], '/') );
            $width = $favicon_size[0];
            $height = $favicon_size[1];
            if ($width > '194' || $height > '194') {
                $favicon16 = aq_resize($favicon, 16, 16, true);
                $favicon32 = aq_resize($favicon, 32, 32, true);
                $favicon96 = aq_resize($favicon, 96, 96, true);
                $favicon194 = aq_resize($favicon, 194, 194, true);
                $favicon192 = aq_resize($favicon, 192, 192, true); 
                echo '
                    <link rel="icon" href="' . $favicon16 . '" sizes="16x16">
                    <link rel="icon" href="' . $favicon32 . '" sizes="32x32">
                    <link rel="icon" href="' . $favicon96 . '" sizes="96x96">
                    <link rel="icon" href="' . $favicon194 . '" sizes="194x194">
                    <link rel="icon" href="' . $favicon192 . '" sizes="192x192">
                ';
            }
            $favicon_uri = wp_upload_dir()['baseurl'] . '/' . 'favicon.ico';
            $favicon_dir = wp_upload_dir()['basedir'] . '/' . 'favicon.ico';
            if ( file_exists($favicon_dir) ) {
                echo '
                    <link rel="shortcut icon" href="' . $favicon_uri . "?ver=" . filemtime($favicon_dir) . '">
                ';
            }
        }
        if ( get_theme_mod('zaxu_pinned_tab_icon_background_color') ) {
            $m_mask_icon_bg_color = get_theme_mod('zaxu_pinned_tab_icon_background_color');
        } else {
            $m_mask_icon_bg_color = get_theme_mod('zaxu_pinned_tab_icon_background_color', '#ff3b30');
        }
        if ( get_theme_mod('zaxu_pinned_tab_icon') ) {
            echo '<link rel="mask-icon" href="' . get_theme_mod('zaxu_pinned_tab_icon') . '" color="' . $m_mask_icon_bg_color . '" />';
        }
    }
    add_action('wp_head', 'zaxu_head_icon', 1);
// Head icon end

// Media library attachment rename start
    // According to the Timestamp
    if (get_theme_mod('zaxu_dashboard_attachment_rename') == 'timestamp') {
        function zaxu_attachment_rename($file) {
            $time = date("YmdHis");
            $file['name'] = $time . "" . mt_rand(1, 100) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
            return $file;
        }
        add_filter('wp_handle_upload_prefilter', 'zaxu_attachment_rename');
    }
    // According to the MD5 hash
    if (get_theme_mod('zaxu_dashboard_attachment_rename') == 'md5') {
        function zaxu_attachment_rename($filename) {
            $info = pathinfo( $filename );
            $ext  = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
            $name = basename($filename, $ext);
            return md5($name) . $ext;
        }
        add_filter('sanitize_file_name', 'zaxu_attachment_rename', 10);
    }
// Media library attachment rename end

// Compress image start
    function zaxu_upload_media($media_data) {
        $type = $media_data['type'];
        $file = $media_data['file'];

        if (get_theme_mod('zaxu_image_size_limit') == '2560') {
            $preset_width = 2560;
            $preset_height = 2560;
        } elseif (get_theme_mod('zaxu_image_size_limit') == '1920') {
            $preset_width = 1920;
            $preset_height = 1920;
        } elseif (get_theme_mod('zaxu_image_size_limit') == '1280') {
            $preset_width = 1280;
            $preset_height = 1280;
        }

        if (get_theme_mod('zaxu_image_compress') == 'high') {
            $compression_level = 80;
        } elseif (get_theme_mod('zaxu_image_compress') == 'medium') {
            $compression_level = 60;
        } elseif (get_theme_mod('zaxu_image_compress') == 'low') {
            $compression_level = 40;
        }

        $resize_image = get_theme_mod('zaxu_image_size_limit', 'disabled') !== 'disabled';
        $compress_image = get_theme_mod('zaxu_image_compress', 'disabled') !== 'disabled';
        
        if ($type == 'image/jpeg' || $type == 'image/pjpeg' || $type == 'image/jpg' || $type == 'image/png' || $type == 'image/x-png' || $type == 'image/bmp') {
            $image_editor = wp_get_image_editor($file);
            $sizes = $image_editor->get_size();
            $width = $sizes['width'];
            $height = $sizes['height'];
            $ratio = $width / $height;
            if ($resize_image) {
                // Resize image
                if ($width >= $preset_width && $width >= $height) {
                    // Horizontal image
                    $preset_height = $preset_width / $ratio;
                } elseif ($width > $preset_width && $width < $height) {
                    // Vertical image
                    $preset_width = $preset_height * $ratio;
                }
                $image_editor->resize($preset_width, $preset_height, true);
            }
            if ($compress_image) {
                // Compress image
                $image_editor->set_quality($compression_level);
            }
            if ($resize_image || $compress_image) {
                $saved_image = $image_editor->save($file);
            }
        }
        return $media_data;
    }
    add_action('wp_handle_upload', 'zaxu_upload_media');
// Compress image end

// Set image dominant color start
    function zaxu_dominant_color_metadata($meta_data, $attachment_id) {
        $attachment = get_post($attachment_id);
        $type = get_post_mime_type($attachment);
        if ($type == 'image/jpeg' || $type == 'image/pjpeg' || $type == 'image/jpg' || $type == 'image/png' || $type == 'image/x-png' || $type == 'image/bmp') {
            $image_file = wp_get_attachment_image_src( $attachment_id, array(100, 100) );
            $image_file_path = str_replace( get_site_url(), $_SERVER['DOCUMENT_ROOT'], $image_file[0] );
            $dominant_color = "#" . zaxu_get_palette_color($image_file_path, 6, 1)[0];
            update_post_meta($attachment_id, 'dominant_color', $dominant_color);
        }
        return $meta_data;
    }
    
    function zaxu_dominant_color_column($columns) {
        $columns['dominant_color'] = __('Dominant color', 'zaxu');    
        return $columns;
    }
    
    function zaxu_dominant_color_display($column_name, $post_id) {
        if ( 'dominant_color' != $column_name || !wp_attachment_is_image($post_id) ) {
            return;
        } else {
            $dominant_color = get_post_meta($post_id, 'dominant_color', true);
            echo '<span class="zaxu-dominant-color-display" style="background-color: ' . $dominant_color . '"></span>';
        }
    }

    add_filter('wp_generate_attachment_metadata', 'zaxu_dominant_color_metadata', 10, 2);
    add_filter('manage_upload_columns', 'zaxu_dominant_color_column');
    add_action('manage_media_custom_column', 'zaxu_dominant_color_display', 10, 2);
// Set image dominant color end

// Check website visibility start
    if ( current_user_can('administrator') ):
        if ( 0 == get_option('blog_public') ) {
            function zaxu_blog_public_tips() {
                global $wp_admin_bar;
                // Go to reading settings
                $wp_admin_bar->add_menu(
                    array (
                        'parent' => false,
                        'id' => 'zaxu_blog_public_tips_discouraged',
                        'title' => '<span class="ab-icon"></span><span class="ab-label">' . __('Search Engines Discouraged', 'zaxu') . '</span>',
                        'href' => admin_url('options-reading.php'),
                        'meta' => false
                    )
                );
            };
        } else {
            function zaxu_blog_public_tips() {
                global $wp_admin_bar;
                // Go to reading settings
                $wp_admin_bar->add_menu(
                    array (
                        'parent' => false,
                        'id' => 'zaxu_blog_public_tips_public',
                        'title' => '<span class="ab-icon"></span><span class="ab-label">' . __('Search Engines Public', 'zaxu') . '</span>',
                        'href' => admin_url('options-reading.php'),
                        'meta' => false
                    )
                );
            };
        };
        add_action('wp_before_admin_bar_render', 'zaxu_blog_public_tips', 1002);
    endif;
// Check website visibility end

// Account sharing start
    if (get_theme_mod('zaxu_login_account_sharing') == 'disabled') {
        function zaxu_user_has_concurrent_sessions() {
            return (is_user_logged_in() && count( wp_get_all_sessions() ) > 1);
        }

        function zaxu_get_current_session() {
            $sessions = WP_Session_Tokens::get_instance( get_current_user_id() );
            return $sessions->get( wp_get_session_token() );
        }

        function zaxu_disallow_account_sharing() {
            if ( !zaxu_user_has_concurrent_sessions() ) {
                return;
            }
            $newest = max( wp_list_pluck(wp_get_all_sessions(), 'login') );
            $session = zaxu_get_current_session();
            if ($session['login'] === $newest) {
                wp_destroy_other_sessions();
            } else {
                wp_destroy_current_session();
            }
        }
        add_action('init', 'zaxu_disallow_account_sharing');
    }
// Account sharing end

// Login captcha start
    if (get_theme_mod('zaxu_login_captcha', 'enabled') === 'enabled') {
        function zaxu_login_captcha() {
            $num1 = rand(1, 20);
            $num2 = rand(1, 20);
            echo "
                <p>
                    <label for='math' class='small'>" . __('Math Captcha', 'zaxu') . " ($num1 + $num2 = ?)</label>
                    <input type='number' name='sum' class='input' value='' tabindex='4' autocomplete='off' style='height: auto;'>
                    " . "
                    <input type='hidden' name='num1' value='$num1'>
                    " . "
                    <input type='hidden' name='num2' value='$num2'>
                </p>
            ";
        }
        add_action('login_form','zaxu_login_captcha');

        function zaxu_login_val() {
            if ( isset( $_POST['sum'] ) ) {
                $sum = $_POST['sum'];
                $result = $_POST['num1'] + $_POST['num2'];
                if ($sum != $result && $sum == null) {
                    // No captcha value
                    function zaxu_empty_captcha() {
                        return new WP_Error( "empty_captcha", __('<strong>Error</strong>: Please enter captcha value.', 'zaxu') );
                    }
                    add_filter("wp_authenticate_user", "zaxu_empty_captcha", 10, 2);
                    add_action('login_footer', 'wp_shake_js', 12);
                } else if ($sum != $result && $sum != null) {
                    // Captcha is incorrect
                    function zaxu_incorrect_captcha() {
                        return new WP_Error( "incorrect_captcha", __('<strong>Error</strong>: The captcha is incorrect.', 'zaxu') );
                    }
                    add_filter("wp_authenticate_user", "zaxu_incorrect_captcha", 10, 2);
                    add_action('login_footer', 'wp_shake_js', 12);
                };
            }
        }
        add_action('login_form_login', 'zaxu_login_val');
    }
// Login captcha end

// Remove WordPress version start
    function zaxu_remove_wp_version() {
        return '';
    }
    add_filter('the_generator', 'zaxu_remove_wp_version');
// Remove WordPress version end

// Add featured image start
    // Add post
    add_filter('manage_post_posts_columns', 'zaxu_add_thumbnail_column', 10, 1);
    add_action('manage_post_posts_custom_column', 'zaxu_display_thumbnail', 10, 1);

    // Add portfolio
    add_filter('manage_portfolio_posts_columns', 'zaxu_add_thumbnail_column', 10, 1);
    add_action('manage_portfolio_posts_custom_column', 'zaxu_display_thumbnail', 10, 1);

    // Add page
    add_filter('manage_pages_columns', 'zaxu_add_thumbnail_column', 10, 1);
    add_action('manage_pages_custom_column', 'zaxu_display_thumbnail', 10, 1);

    function zaxu_add_thumbnail_column($columns) {
        $column_thumbnail = array(
            'thumbnail' => __('Thumbnail', 'zaxu'),
        );
        $columns = array_slice($columns, 0, 1, true) + $column_thumbnail + array_slice($columns, 1, NULL, true);
        return $columns;
    }
    
    function zaxu_display_thumbnail($column) {
        global $post;
        $featured_image = get_the_post_thumbnail( $post->ID, array(60, 60) );
        switch ($column) {
            case 'thumbnail':
                if ($featured_image) {
                    echo $featured_image;
                } else {
                    echo '<img width="300" height="300" src="' . get_template_directory_uri() . '/assets/img/file-light-300x300.jpg' . '" />';
                }
            break;
        }
    }
// Add featured image end

// Set assets version start
    function zaxu_set_assets_ver($src) {
        $file = $_SERVER['DOCUMENT_ROOT'] . parse_url($src, PHP_URL_PATH);
        if ( file_exists($file) ) {
            $version = filemtime($file);
            if ( strpos($src, 'ver=') ) {
                $src = add_query_arg('ver', $version, $src);
                return esc_url($src);
            }
        }
    }
    function zaxu_add_assets_version() {
        add_filter('style_loader_src', 'zaxu_set_assets_ver', 9999);
        add_filter('script_loader_src', 'zaxu_set_assets_ver', 9999);
    }
    add_action('init', 'zaxu_add_assets_version');
// Set assets version end

// Remove WordPress caption width start
    add_filter('img_caption_shortcode_width', '__return_false');
// Remove WordPress caption width end

// Make "Visit Site" Link open in new tab start
    function zaxu_new_tab_to_visit_site($wp_admin_bar) {
        $all_toolbar_nodes = $wp_admin_bar->get_nodes();
        foreach ($all_toolbar_nodes as $node) {
            if ($node->id == 'site-name' || $node->id == 'view-site') {
                $args = $node;
                $args->meta = array('target' => '_blank');
                $wp_admin_bar->add_node( $args );
            }
        }
    }
    add_action('admin_bar_menu', 'zaxu_new_tab_to_visit_site', 999);
// Make "Visit Site" Link open in new tab end

// Allow SVG format start
    function zaxu_svg_support($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'zaxu_svg_support');
// Allow SVG format end

// Set default settings of attachment media box start
    function attachment_default_settings() {
        update_option('image_default_link_type', 'file');
        update_option('image_default_size', 'full');
    }
    add_action('after_setup_theme', 'attachment_default_settings');
// Set default settings of attachment media box end

// Disable admin bar start
    add_filter('show_admin_bar', '__return_false');
// Disable admin bar end

// Remove edit post link start
    function remove_edit_post_link($link) {
        return '';
    }
    add_filter('edit_post_link', 'remove_edit_post_link');
// Remove edit post link end

// Change login page title start
    function zaxu_login_title($zaxu_login_title) {
            return str_replace (
                array (
                    ' &lsaquo;',
                    ' &#8212; WordPress'
                ),
                array (
                    ' &#8212;'
                ),
                $zaxu_login_title
            );
        }
    add_filter('login_title', 'zaxu_login_title');
// Change login page title end

// Fix WordPress title "-" bug start
    add_filter('run_wptexturize', '__return_false');
// Fix WordPress title "-" bug end

// Remove JQMIGRATE start
    add_action('wp_default_scripts', function ($scripts) {
        if ( !empty( $scripts->registered['jquery'] ) ) {
            $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, ['jquery-migrate'] );
        }
    });
// Remove JQMIGRATE end

// Remove default favicon start
    add_action( 'do_faviconico', function() {
        // Check for icon with no default value
        if ( $icon = get_site_icon_url(32) ) {
            // Show the icon
            wp_redirect($icon);
        } else {
            // Show nothing
            header('Content-Type: image/vnd.microsoft.icon');
        }
        exit;
    } );
// Remove default favicon end

// Change comment date format start
    function zaxu_change_comment_date_format($date, $date_format, $comment) {
        return date('Y-m-d G:i:s', strtotime($comment->comment_date) );
    }
    add_filter('get_comment_date', 'zaxu_change_comment_date_format', 10, 3);
// Change comment date format end

// Add @author for comment start
    function zaxu_comment_add_at( $comment_text, $comment = '') {
        if ( $comment->comment_parent > 0) {
        $comment_text = '<span class="comment-author-tag">@'.get_comment_author( $comment->comment_parent ) . '</span> ' . $comment_text;
        }
        return $comment_text;
    }
    add_filter( 'comment_text' , 'zaxu_comment_add_at', 20, 2);
// Add @author for comment end

// Code escape for comment start
    function zaxu_comment_code_escape($incoming_comment) {
        $incoming_comment = htmlspecialchars($incoming_comment);
        return $incoming_comment;
    }
    add_filter('comment_text', 'zaxu_comment_code_escape');
    add_filter('comment_text_rss', 'zaxu_comment_code_escape');
// Code escape for comment end

// Enabled/Disabled login page logo start
    if (get_theme_mod('zaxu_login_wp_logo') == 'disabled') {
        function zaxu_disable_login_logo() {
            echo '
                <style type="text/css">
                    #login h1 {
                        display: none;
                    }
                </style>
            ';
        }
        add_action('login_head', 'zaxu_disable_login_logo');
    }
// Enabled/Disabled login page logo end

// Enabled/Disabled login back to homepage start
    if (get_theme_mod('zaxu_login_back_to_homepage_link') == 'disabled') {
        function zaxu_login_back_to_homepage_link() {
            echo '
                <style type="text/css">
                    #backtoblog {
                        display: none;
                    }
                </style>
            ';
        }
        add_action('login_head', 'zaxu_login_back_to_homepage_link');
    }
// Enabled/Disabled login back to homepage end

// Enabled/Disabled dashboard admin bar wp logo start
    if (get_theme_mod('zaxu_dashboard_admin_bar_wp_logo') == 'disabled') {
        function zaxu_dashboard_admin_bar_wp_logo() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
        }
        add_action('wp_before_admin_bar_render', 'zaxu_dashboard_admin_bar_wp_logo');
    }
// Enabled/Disabled dashboard admin bar wp logo end

global $wp_version;
if ($wp_version >= 5) {
    // Enabled/Disabled WordPress Block-based editor start
        if (get_theme_mod('zaxu_dashboard_wp_block_based_editor', 'enabled') == 'disabled') {
            function zaxu_disable_wp_block_based_editor($use_block_editor) {
                return false;
            }
            add_filter('use_block_editor_for_post_type', 'zaxu_disable_wp_block_based_editor');
        }
    // Enabled/Disabled WordPress Block-based editor end

    // Enabled/Disabled Reusable Blocks start
        if (get_theme_mod('zaxu_dashboard_reusable_blocks', 'disabled') == 'enabled' && get_theme_mod('zaxu_dashboard_wp_block_based_editor', 'enabled') == 'enabled') {
            function zaxu_reusable_blocks_admin_menu() {
                add_menu_page(
                    __('Reusable Blocks', 'zaxu'),
                    __('Reusable Blocks', 'zaxu'),
                    'edit_posts',
                    'edit.php?post_type=wp_block',
                    '',
                    'dashicons-block-default',
                    21
                );
                add_submenu_page(
                    'edit.php?post_type=wp_block',
                    __('Add New', 'zaxu'),
                    __('Add New', 'zaxu'),
                    'edit_posts',
                    'post-new.php?post_type=wp_block',
                    '',
                    21
                );
            }
            add_action('admin_menu', 'zaxu_reusable_blocks_admin_menu');
        }
    // Enabled/Disabled Reusable Blocks end
}

// Enabled/Disabled dashboard welcome panel start
    if (get_theme_mod('zaxu_dashboard_welcome_panel') == 'disabled') {
        remove_action('welcome_panel', 'wp_welcome_panel');
    }
// Enabled/Disabled dashboard welcome panel end

// Enabled/Disabled dashboard right now start
    if (get_theme_mod('zaxu_dashboard_right_now') == 'disabled') {
        function zaxu_dashboard_right_now() {
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        }
        add_action('wp_dashboard_setup', 'zaxu_dashboard_right_now');
    }
// Enabled/Disabled dashboard right now end

// Enabled/Disabled dashboard activity start
    if (get_theme_mod('zaxu_dashboard_activity') == 'disabled') {
        function zaxu_dashboard_activity() {
            remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        }
        add_action('wp_dashboard_setup', 'zaxu_dashboard_activity');
    }
// Enabled/Disabled dashboard activity end

// Enabled/Disabled dashboard quick press start
    if (get_theme_mod('zaxu_dashboard_quick_press') == 'disabled') {
        function zaxu_dashboard_quick_press() {
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        }
        add_action('wp_dashboard_setup', 'zaxu_dashboard_quick_press');
    }
// Enabled/Disabled dashboard quick press end

// Enabled/Disabled dashboard primary start
    if (get_theme_mod('zaxu_dashboard_primary') == 'disabled') {
        function zaxu_dashboard_primary() {
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
        }
        add_action('wp_dashboard_setup', 'zaxu_dashboard_primary');
    }
// Enabled/Disabled dashboard primary end

// Enabled/Disabled dashboard site health start
    if (get_theme_mod('zaxu_dashboard_site_health') == 'disabled') {
        function zaxu_dashboard_site_health() {
            remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        }
        add_action('wp_dashboard_setup', 'zaxu_dashboard_site_health');
    }
// Enabled/Disabled dashboard site health end

// Post type enabled/disabled start
    if (get_theme_mod('zaxu_dashboard_post_type') == 'disabled') {
        // Remove menu
        function zaxu_remove_post_type() {
            remove_menu_page('edit.php');
        }
        add_action('admin_menu', 'zaxu_remove_post_type');

        // Remove admin bar new submenu
        function zaxu_remove_new_post_item() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_node('new-post');
        }
        add_action('admin_bar_menu', 'zaxu_remove_new_post_item', 999);
    } elseif (get_theme_mod('zaxu_dashboard_post_type') == 'administrator') {
        if ( !current_user_can('administrator') ) {
            // Remove menu
            function zaxu_remove_post_type() {
                remove_menu_page('edit.php');
            }
            add_action('admin_menu', 'zaxu_remove_post_type');

            // Remove admin bar new submenu
            function zaxu_remove_new_post_item() {
                global $wp_admin_bar;   
                $wp_admin_bar->remove_node('new-post');
            }
            add_action('admin_bar_menu', 'zaxu_remove_new_post_item', 999);
        }
    }

    if (get_theme_mod('zaxu_dashboard_portfolio_type') == 'disabled') {
        // Remove menu
        function zaxu_dashboard_portfolio_type() {
            remove_menu_page('edit.php?post_type=portfolio');
        }
        add_action('admin_menu', 'zaxu_dashboard_portfolio_type');

        // Remove admin bar new submenu
        function zaxu_remove_new_portfolio_item() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_node('new-portfolio');
        }
        add_action('admin_bar_menu', 'zaxu_remove_new_portfolio_item', 999);
    } elseif (get_theme_mod('zaxu_dashboard_post_type') == 'administrator') {
        if ( !current_user_can('administrator') ) {
            // Remove menu
            function zaxu_dashboard_portfolio_type() {
                remove_menu_page('edit.php?post_type=portfolio');
            }
            add_action('admin_menu', 'zaxu_dashboard_portfolio_type');

            // Remove admin bar new submenu
            function zaxu_remove_new_portfolio_item() {
                global $wp_admin_bar;   
                $wp_admin_bar->remove_node('new-portfolio');
            }
            add_action('admin_bar_menu', 'zaxu_remove_new_portfolio_item', 999);
        }
    }

    if (get_theme_mod('zaxu_dashboard_page_type') == 'disabled') {
        // Remove menu
        function zaxu_dashboard_page_type() {
            remove_menu_page('edit.php?post_type=page');
        }
        add_action('admin_menu', 'zaxu_dashboard_page_type');

        // Remove admin bar new submenu
        function zaxu_remove_new_page_item() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_node('new-page');
        }
        add_action('admin_bar_menu', 'zaxu_remove_new_page_item', 999);
    } elseif (get_theme_mod('zaxu_dashboard_post_type') == 'administrator') {
        if ( !current_user_can('administrator') ) {
            // Remove menu
            function zaxu_dashboard_page_type() {
                remove_menu_page('edit.php?post_type=page');
            }
            add_action('admin_menu', 'zaxu_dashboard_page_type');

            // Remove admin bar new submenu
            function zaxu_remove_new_page_item() {
                global $wp_admin_bar;   
                $wp_admin_bar->remove_node('new-page');
            }
            add_action('admin_bar_menu', 'zaxu_remove_new_page_item', 999);
        }
    }

    if (get_theme_mod('zaxu_dashboard_link_manager') == 'disabled') {
        // Remove menu
        function zaxu_dashboard_link_manager() {
            remove_menu_page('link-manager.php');
        }
        add_action('admin_menu', 'zaxu_dashboard_link_manager');

        // Remove admin bar new submenu
        function zaxu_remove_new_link_item() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_node('new-link');
        }
        add_action('admin_bar_menu', 'zaxu_remove_new_link_item', 999);
    } elseif (get_theme_mod('zaxu_dashboard_post_type') == 'administrator') {
        if ( !current_user_can('administrator') ) {
            // Remove menu
            function zaxu_dashboard_link_manager() {
                remove_menu_page('link-manager.php');
            }
            add_action('admin_menu', 'zaxu_dashboard_link_manager');

            // Remove admin bar new submenu
            function zaxu_remove_new_link_item() {
                global $wp_admin_bar;   
                $wp_admin_bar->remove_node('new-link');
            }
            add_action('admin_bar_menu', 'zaxu_remove_new_link_item', 999);
        }
    }

    if (get_theme_mod('zaxu_dashboard_tools') == 'disabled') {
        // Remove menu
        function zaxu_dashboard_tools() {
            remove_menu_page('tools.php');
        }
        add_action('admin_menu', 'zaxu_dashboard_tools');
    } elseif (get_theme_mod('zaxu_dashboard_post_type') == 'administrator') {
        if ( !current_user_can('administrator') ) {
            // Remove menu
            function zaxu_dashboard_tools() {
                remove_menu_page('tools.php');
            }
            add_action('admin_menu', 'zaxu_dashboard_tools');
        }
    }
// Post type enabled/disabled end

// Dashboard title start
    function zaxu_dashboard_title($admin_title, $title) {
        return $title . ' &lsaquo; ' . esc_attr( get_bloginfo('name') ) . __(' Website Console', 'zaxu');
    }
    add_filter('admin_title', 'zaxu_dashboard_title', 10, 2);
// Dashboard title end

// Dashboard screen options tab start
    if ( get_theme_mod('zaxu_dashboard_screen_options_tab') == 'disabled') {
        function zaxu_dashboard_screen_options_tab() {
            return false;
        }
        add_filter('screen_options_show_screen', 'zaxu_dashboard_screen_options_tab');
    }
// Dashboard screen options tab end

// Dashboard help tab start
    if (get_theme_mod('zaxu_dashboard_help_tab') == 'disabled') {
        function zaxu_dashboard_help_tab($old_help, $screen_id, $screen) {
            $screen->remove_help_tabs();
            return $old_help;
        }
        add_filter('contextual_help', 'zaxu_dashboard_help_tab', 999, 3);
    }
// Dashboard help tab end

// Dashboard copyright start
    if (get_theme_mod('zaxu_dashboard_copyright') == 'enabled') {
        function zaxu_dashboard_copyright () {
            $web_created_time_str = get_theme_mod('zaxu_web_created_time');
            if ($web_created_time_str) {
                $web_created_time = $web_created_time_str . '-';
            } else {
                $web_created_time = null;
            }
            echo __('Copyright &copy;', 'zaxu') . ' ' .  $web_created_time . date("Y") . ' ' . get_bloginfo("name") . '. ' . __('All rights reserved.', 'zaxu'); 
        } 
        add_filter('admin_footer_text', 'zaxu_dashboard_copyright');
    }
// Dashboard copyright end

// Dashboard theme version start
    if (get_theme_mod('zaxu_dashboard_theme_version') == 'enabled') {
        function zaxu_dashboard_theme_version() {
            $wp_ver = __('WordPress Version', 'zaxu') . ' ' . get_bloginfo('version');
            if ( get_template_directory() === get_stylesheet_directory() ) {
                $theme_no_child_ver = __('Theme Version', 'zaxu') . ' ' . wp_get_theme()->get('Version');
                echo $wp_ver . ' | ' . $theme_no_child_ver;
            } else {
                $theme_has_child_ver = __('Theme Version', 'zaxu') . ' ' . wp_get_theme()->parent()->get('Version');
                echo $wp_ver . ' | ' . $theme_has_child_ver;
            }
        } 
        add_filter('update_footer', 'zaxu_dashboard_theme_version', 9999);
    }
// Dashboard theme version end

// Remove widget tab for customizer start
    function zaxu_remove_widget_customizer($wp_customize) {
        $wp_customize->remove_panel('widgets');
    }
    add_action('customize_register', 'zaxu_remove_widget_customizer');
// Remove widget tab for customizer end

// Replace Gravatar start
    function zaxu_replace_gravatar($avatar) {
        $avatar = str_replace(
            array(
                "//gravatar.com/",
                "//www.gravatar.com/",
                "//secure.gravatar.com/",
                "//0.gravatar.com/",
                "//1.gravatar.com/",
                "//2.gravatar.com/",
                "//cn.gravatar.com/"
            ),
            "//gravatar.loli.net/",
            $avatar
        );
        $avatar = str_replace("http://", "https://", $avatar);
        return $avatar;
    }
    add_filter('get_avatar', 'zaxu_replace_gravatar');
    add_filter('get_avatar_url', 'zaxu_replace_gravatar');
// Replace Gravatar end

// Password protected form start
    add_filter('the_password_form', 'zaxu_the_password_form');
    function zaxu_the_password_form() {
        global $post;
        $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
        echo '
            <form action="' . esc_url( site_url('wp-login.php?action=postpass', 'login_post') ) . '" class="post-password-form" method="post">
                ' . zaxu_icon('lock', 'icon') . '
                <p class="tips">' . __('This content is password protected. To view it please enter your password below.', 'zaxu') . '</p>
                <div class="action">
                    <input name="post_password" id="' . $label . '" type="password" autocomplete="new-password" placeholder="' . __('Please enter password...', 'zaxu') . '" />
                    <input type="submit" name="Submit" value="' . __('Submit', 'zaxu') . '" class="button-primary" />
                </div>
            </form>
        ';
    }
// Password protected form end

// Disable emoji start
    function zaxu_disable_emoji() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');	
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');	
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'zaxu_disable_emoji_tinymce');
    }
    add_action('init', 'zaxu_disable_emoji');

    function zaxu_disable_emoji_tinymce($plugins) {
        if ( is_array($plugins) ) {
            return array_diff( $plugins, array('wpemoji') );
        } else {
            return array();
        }
    }
// Disable emoji end

// Reply notification via email start
    function zaxu_comment_notification($comment_id) {
        $comment = get_comment($comment_id);   
        $parent_id = $comment->comment_parent ? $comment->comment_parent : '';   
        $spam_confirmed = $comment->comment_approved; 

        if ($parent_id != '' && $spam_confirmed != 'spam') {
            $to = trim(get_comment($parent_id)->comment_author_email); 
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $subject = get_option('blogname') . ' - ' . __('New reply to your comment, please check it!', 'zaxu');
            
            $web_created_time_str = get_theme_mod('zaxu_web_created_time');
            if ($web_created_time_str) {
                $web_created_time = $web_created_time_str . '-';
            };

            $body = '
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #fff;">
                    <tbody>
                        <tr>
                            <td width="20">&nbsp;</td>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 620px; margin: 0 auto;">
                                    <tbody>
                                        <tr>
                                            <td style="padding:0 20px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <thead>
                                                        <tr>
                                                            <td height="40">&nbsp;</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <p style="color: #333; font-size: 20px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 40px;">
                                                                    <strong>'. __('Hi', 'zaxu') . ' ' . trim(get_comment($parent_id)->comment_author) . ',</strong>
                                                                </p>
                                                                <p style="color: #333; font-size: 16px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 40px;">
                                                                    <strong>' . __('Thank you for participating in the discussion, your comment has been replied!', 'zaxu') . '</strong>
                                                                </p>
                                                                <p style="color: #333; font-size: 16px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 40px;">
                                                                    <strong>' . __('The article you commented on:', 'zaxu') . ' <a href="' . esc_url( get_permalink($comment->comment_post_ID) ) . '" target="_blank">' . esc_attr( get_the_title($comment->comment_post_ID) ) . '</a></strong>
                                                                </p>
                                                                <p style="color: #333; font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 10px;">' . __('Your comments', 'zaxu') . '</p>
                                                                <p style="color: #333; font-size: 16px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 10px; padding: 10px; background: #f2f2f2; border-radius: 6px;">' . nl2br( strip_tags(get_comment($parent_id)->comment_content) ) . '</p>
                                                                <p style="color: #999; font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-decoration: none; margin-top: 0; margin-bottom: 20px;">' . get_comment($parent_id)->comment_date . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p style="color: #333; font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 10px;">' . trim($comment->comment_author) . ' ' . __('has replied to you', 'zaxu') . '</p>
                                                                <p style="color: #333; font-size: 16px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 10px; padding: 10px; background: #f2f2f2; border-radius: 6px;">' . nl2br( strip_tags($comment->comment_content) ) . '</p>
                                                                <p style="color: #999; font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-decoration: none; margin-top: 0; margin-bottom: 20px;">' . $comment->comment_date . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="' . htmlspecialchars( get_comment_link($parent_id) ) . '" target="_blank" style="display: table; color: #ffffff; font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; line-height: 30px; text-align: center; text-decoration: none; margin: 0 auto; padding: 5px 20px; background: #333; border-radius: 4px;">
                                                                    <strong>' . __('View comments', 'zaxu') . '</strong>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td height="60">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p style="color: #999; font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top: 0; margin-bottom: 20px;">' . esc_html__('Copyright &copy;', 'zaxu') . ' ' . $web_created_time . date("Y") . ' ' . get_bloginfo("name") . '. ' . esc_html__('All rights reserved.', 'zaxu') . ' ' . __('This message is automatically sent, please do not reply directly.', 'zaxu') . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="40">&nbsp;</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td width="20">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            ';

            wp_mail($to, $subject, $body, $headers);
        }
    }
    if (get_theme_mod('zaxu_comment_notify', 'disabled') == "enabled") {
        add_action('comment_post', 'zaxu_comment_notification');
    }
// Reply notification via email end

// Ajax password protected start
    $zaxu_ajax_password_protected = new zaxu_ajax_password_protected();
    class zaxu_ajax_password_protected {
        function __construct() {
            add_action( 'wp_ajax_do_post_password', array($this, 'zaxu_do_x_post_password_cb') );
            add_action( 'wp_ajax_nopriv_do_post_password', array($this, 'zaxu_do_x_post_password_cb') );
        }
        function zaxu_do_x_post_password_cb() {
            require_once(ABSPATH . 'wp-includes/class-phpass.php');
            $wp_hasher = new PasswordHash(8, true);
            setcookie('wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $_POST['pass'] ) ), time() + 864000, COOKIEPATH);
            $_COOKIE['wp-postpass_' . COOKIEHASH] = $wp_hasher->HashPassword( stripslashes( $_POST['pass'] ) );
        }
    }
// Ajax password protected end

// Disable search function start
    function zaxu_disable_search_function($obj) {
        if ( $obj->is_search && $obj->is_main_query() ) {
            unset( $_GET['s'] );
            unset( $_POST['s'] );
            unset( $_REQUEST['s'] );
            unset( $obj->query['s'] );
            $obj->set('s', '');
            $obj->is_search = false;
            $obj->set_404();
            status_header(404);
            nocache_headers();
        }
    }

    if ( !is_admin() && get_theme_mod('zaxu_site_search', 'enabled') == 'disabled') {
        add_action('parse_query', 'zaxu_disable_search_function');
    }
// Disable search function end

// Replace wp video shortcode start
    add_filter('wp_video_shortcode', function($output) {
        $str = preg_replace('/\<[\/]{0,1}div[^\>]*\>/i', '', $output);
        $wrap = "<div class='wp-media-wrapper wp-video'>" . $str . "</div>";
        $output = $wrap;
        return $output;
    });
// Replace wp video shortcode end

// Replace wp audio shortcode start
    add_filter('wp_audio_shortcode', function($output) {
        $output = "<div class='wp-media-wrapper wp-audio'>" . $output . "</div>";
        return $output;
    });
// Replace wp audio shortcode end

// Meta box start
    // Pageview start
        // Set pageview columns
        function zaxu_pageview_columns($columns) {
            return array_merge($columns,
                array(
                    'zaxu_pageview' => esc_html__('Pageview', 'zaxu')
                )
            );
        }
        add_filter('manage_post_posts_columns', 'zaxu_pageview_columns');
        add_filter('manage_portfolio_posts_columns', 'zaxu_pageview_columns');
        add_filter('manage_product_posts_columns', 'zaxu_pageview_columns');
        add_filter('manage_docs_posts_columns', 'zaxu_pageview_columns');
        add_filter('manage_page_posts_columns', 'zaxu_pageview_columns');

        // Get pageview value for columns start
            function zaxu_get_pageview_value_for_columns($column, $post_id) {
                // Get value
                $count = get_post_meta($post_id, 'zaxu_pageview_count', true);
                switch ($column) {
                    case 'zaxu_pageview' :
                        if ($count != '') {
                            echo $count;
                        } else {
                            echo '0';
                        }
                    break;
                }
            }
            // Post
            add_action('manage_posts_custom_column', 'zaxu_get_pageview_value_for_columns', 10, 2);
            // Page
            add_action('manage_pages_custom_column', 'zaxu_get_pageview_value_for_columns', 10, 2);
        // Get pageview value for columns end

        // Get pageview value for meta box start
            function zaxu_pageview_meta_box() {
                global $post;
                // Get value
                $count = get_post_meta($post->ID, 'zaxu_pageview_count', true);
                echo '<div class="acf-field">';
                    if ($count == '') {
                        echo '0' . __(' View', 'zaxu');
                    } elseif ($count == '1') {
                        echo $count . __(' View', 'zaxu');
                    } else {
                        echo $count . __(' Views', 'zaxu');
                    }
                echo '</div>';
            }
            function zaxu_pageview_op_menu_meta_box() {
                $post_type = array(
                    'post',
                    'page',
                    'portfolio',
                    'product',
                    'docs'
                );
                add_meta_box(
                    'zaxu-pageview-meta-box',
                    __('Pageview', 'zaxu'),
                    'zaxu_pageview_meta_box',
                    $post_type,
                    'side',
                    'default'
                );
            }
            add_action('add_meta_boxes', 'zaxu_pageview_op_menu_meta_box');
        // Get pageview value for meta box end

        // Set pageview count
        function zaxu_set_pageview($post_id) {
            $count_key = 'zaxu_pageview_count';
            $count = get_post_meta($post_id, $count_key, true);
            if ($count == '') {
                $count = 1;
                delete_post_meta($post_id, $count_key);
                add_post_meta($post_id, $count_key, '1');
            } else {
                $count++;
                update_post_meta($post_id, $count_key, $count);
            }
        }

        // Get pageview count
        function zaxu_get_pageview($post_id) {
            $count_key = 'zaxu_pageview_count';
            $count = get_post_meta($post_id, $count_key, true);
            if ($count == '') {
                $count = '0' . __(' View', 'zaxu');
            } elseif ($count == '1') {
                $count = $count . __(' View', 'zaxu');
            } else {
                $count = zaxu_number_format_short($count) . __(' Views', 'zaxu');
            }
            return $count;
        }
        
        // Record pageview value
        add_action('wp_footer', function() {
            global $template;

            if ( is_front_page() && is_home() ) {
                // Default homepage
                $page_id = get_option('page_on_front');
            } elseif ( !is_front_page() && is_home() ) {
                // Static post homepage
                $page_id = get_option('page_for_posts');
            } elseif (basename($template) == "template-portfolio.php") {
                // Portfolio homepage
                $pages = get_pages(
                    array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'templates/template-portfolio.php',
                    )
                );
                foreach($pages as $page) {
                    $page_id = $page->ID;
                };
            } else {
                // Other page
                $page_id = get_the_id();
            }
            
            if ( function_exists('is_woocommerce') ) {
                if ( is_shop() ) {
                    // Shop homepage
                    $page_id = woocommerce_get_page_id('shop');
                }
            }

            if (get_post_status($page_id) == "publish" && get_theme_mod('zaxu_maintenance_switch') != 'enabled') {
                zaxu_set_pageview($page_id);
            }
        });
    // Pageview end

    // SEO start
        function zaxu_register_seo_meta_boxes() {
            $post_types = array(
                'post',
                'page',
                'portfolio',
                'product',
                'docs'
            );
            add_meta_box(
                'zaxu_seo',
                __('SEO', 'zaxu'),
                'zaxu_seo_display_callback',
                $post_types,
                'normal',
                'default'
            );
        }
        add_action('add_meta_boxes', 'zaxu_register_seo_meta_boxes');

        function zaxu_seo_display_callback($post) {
            $seo_keywords = get_post_meta($post->ID, 'zaxu_seo_keywords', true);
            $seo_description = get_post_meta($post->ID, 'zaxu_seo_description', true);

            echo '
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="zaxu_seo_keywords">' . __("Meta Keywords (Comma Separated)", "zaxu") . '</label>
                            </th>
                            <td>
                                <input name="zaxu_seo_keywords" type="text" id="zaxu_seo_keywords" class="large-text" value="' . $seo_keywords . '">
                                <p class="description">' . __("Most search engines use a maximum of 64 chars for the keywords.", "zaxu") . '</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="zaxu_seo_description">' . __("Meta Description", "zaxu") . '</label>
                            </th>
                            <td>
                                <textarea name="zaxu_seo_description" rows="8" maxlength="320" id="zaxu_seo_description" class="large-text">' . $seo_description . '</textarea>
                                <p class="description">' . __("Most search engines use a maximum of 230-320 chars for the description.", "zaxu") . '</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            ';
        }
        
        function zaxu_seo_save_meta_box($post_id) {
            if ( isset( $_POST['zaxu_seo_keywords'] ) || isset( $_POST['zaxu_seo_description'] ) ) {
                update_post_meta(
                    $post_id,
                    'zaxu_seo_keywords',
                    sanitize_text_field( $_POST['zaxu_seo_keywords'] )
                );
                update_post_meta(
                    $post_id,
                    'zaxu_seo_description',
                    sanitize_text_field( $_POST['zaxu_seo_description'] )
                );
            }
        }
        add_action('save_post', 'zaxu_seo_save_meta_box');
    // SEO end

    // Set navigation with safe area start
        function zaxu_register_nav_safe_area_meta_boxes() {
            $post_types = array(
                'post',
                'page',
                'portfolio',
            );
            add_meta_box(
                'zaxu_nav_safe_area',
                __('Navigation', 'zaxu'),
                'zaxu_nav_safe_area_display_callback',
                $post_types,
                'side',
                'default'
            );
        }
        add_action('add_meta_boxes', 'zaxu_register_nav_safe_area_meta_boxes');

        function zaxu_nav_safe_area_display_callback($post) {
            $nav_safe_area = get_post_meta($post->ID, 'zaxu_nav_safe_area', true);
            wp_nonce_field('zaxu_update_nav_safe_area_settings', 'zaxu_update_nav_safe_area_nonce');
            
            if ($nav_safe_area == 'no') {
                $checked = null;
            } else {
                $checked = ' checked';
            }

            echo '
                <div class="acf-field">
                    <label for="zaxu_nav_safe_area_checkbox">
                        <input type="checkbox" name="zaxu_nav_safe_area_checkbox" id="zaxu_nav_safe_area_checkbox" value="yes"' . $checked . ' />
                        ' . __('Set navigation with safe area', 'zaxu') . '
                    </label>
                </div>
            ';
        }
        
        function zaxu_nav_safe_area_save_meta_box($post_id) {
            if ( !current_user_can('edit_post', $post_id) ) {
                return;
            }
            if ( !isset( $_POST['zaxu_update_nav_safe_area_nonce'] ) || !wp_verify_nonce( $_POST['zaxu_update_nav_safe_area_nonce'], 'zaxu_update_nav_safe_area_settings' ) ) {
                return;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if ( isset( $_POST['zaxu_nav_safe_area_checkbox'] ) ) {
                update_post_meta(
                    $post_id,
                    'zaxu_nav_safe_area',
                    'yes'
                );
            } else {
                update_post_meta(
                    $post_id,
                    'zaxu_nav_safe_area',
                    'no'
                );
            }
        }
        add_action('save_post', 'zaxu_nav_safe_area_save_meta_box');
    // Set navigation with safe area end
// Meta box end

// Set result page query start
    function zaxu_set_result_page_query($query) {
            if ( is_author() || is_year() || is_month() || is_day() || is_tax() ) {
                // Archive page start
                    $posts_per_page = get_theme_mod( 'zaxu_archive_per_page', get_option('posts_per_page') );
                    
                    if ($query->get('post_type') == 'nav_menu_item') {
                        return $query;
                    }
                    $query->set(
                        'post_type',
                        array(
                            'post',
                            'page',
                            'portfolio',
                            'docs',
                            'product'
                        )
                    );
                    $query->set('post_status', 'publish');
                    $query->set('suppress_filters', false);
                    $query->set('posts_per_page', $posts_per_page);
                // Archive page end
            } else if ( is_search() ) {
                // Search page start
                    $posts_per_page = get_theme_mod( 'zaxu_search_per_page', get_option('posts_per_page') );

                    if ($query->get('post_type') == 'nav_menu_item') {
                        return $query;
                    }
                    $query->set(
                        'post_type',
                        array(
                            'post',
                            'page',
                            'portfolio',
                            'docs',
                            'product'
                        )
                    );
                    $query->set('post_status', 'publish');
                    $query->set('suppress_filters', false);
                    $query->set('posts_per_page', $posts_per_page);
                // Search page end
            }
        return $query;
    }
    add_action('pre_get_posts', 'zaxu_set_result_page_query');
// Set result page query end

// Lazyload for WordPress image start
    if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
        function zaxu_lazyload_for_wp_img($content) {
            // Add zaxu-lazy class
            $pattern = '/(class=".*?)(wp-image-.*?")/';
            $replacement = '$1zaxu-lazy $2';
            $content = preg_replace($pattern, $replacement, $content);

            // Add zaxu-wp-lazy class
            $pattern = '/(<img)(.*?class=".*?wp-image-.*?")/';
            $replacement = '$1 zaxu-wp-lazy$2';
            $content = preg_replace($pattern, $replacement, $content);

            // Replace src to data-src
            $pattern = '/(zaxu-wp-lazy.*?)(src=")/';
            $replacement = '$1data-$2';
            $content = preg_replace($pattern, $replacement, $content);

            // Replace srcset to data-srcset
            $pattern = '/(zaxu-wp-lazy.*?)(srcset=")/';
            $replacement = '$1data-$2';
            $content = preg_replace($pattern, $replacement, $content);

            // Add placeholder image for src
            $pattern = '/(zaxu-wp-lazy.*?)(data-src=")/';
            $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
            $replacement = '$1src="' . $placeholder_img . '" $2';
            $content = preg_replace($pattern, $replacement, $content);

            // For cover block of parallax
            $pattern = '/(wp-block-cover.*?)(style="background-image:url\((.*?)\))/';
            $replacement = 'zaxu-lazy $1data-bg="$3"';
            $content = preg_replace($pattern, $replacement, $content);

            return $content;
        }
        add_filter('the_content','zaxu_lazyload_for_wp_img');
    }
// Lazyload for WordPress image end
?>