<?php
/*
 * @Description: Pending functions
 * @Version: 2.7.0
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

global $pending_status;
if ($pending_status == "maintenance") {
    $body_class = "maintenance-page";
} else if ($pending_status == "license") {
    $body_class = "license-page";
}

$jquery = includes_url(). 'js/jquery/jquery.min.js';
$pending_style = get_template_directory_uri() . '/inc/pending/assets/css/pending.min.css';
$fancybox_js = get_template_directory_uri() . '/assets/js/vendor/jquery.fancybox.min.js';
$pending_js = get_template_directory_uri() . '/inc/pending/assets/js/pending.min.js';

$javascript_file = '
    <script type="text/javascript" src="' . zaxu_pending_set_assets_ver($fancybox_js) . '"></script>
    <script type="text/javascript" src="' . zaxu_pending_set_assets_ver($pending_js) . '"></script>
';
?>

<!DOCTYPE html>
<!--[if lt IE 10]> <html <?php language_attributes(); ?> dir="ltr" class="old-ie-browser" xmlns="//www.w3.org/1999/xhtml"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> dir="ltr" xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
    <head id="head">
        <?php zaxu_set_head(); ?>
        <meta name="keywords" content="<?php bloginfo('name'); ?>">
        <?php
            if ($pending_status == "maintenance") {
                // Maintenance mode
                echo '<meta name="description" content="' . __('The website is under maintenance, please try again later.', 'zaxu') . '">';
                echo '<title>' . esc_attr( get_bloginfo('name') ) . '</title>';
            } else if ($pending_status == "license") {
                // License mode
                zaxu_set_license_description();
                echo '<title>' . esc_attr( get_bloginfo('name') ) . ' &#8212; ' . zaxu_set_license_title() . '</title>';
            }
            zaxu_head_icon();
            echo '
                <link rel="stylesheet" type="text/css" href="' . zaxu_pending_set_assets_ver($pending_style) . '">
                <script type="text/javascript" src="' . zaxu_pending_set_assets_ver($jquery) . '"></script>
            ';

            if ($pending_status == "maintenance") {
                // Set maintenance color scheme start
                    $txt_color = get_theme_mod('zaxu_maintenance_text_color', '#333333');
                    $bg_color = get_theme_mod('zaxu_maintenance_background_color', '#f2f2f2');
                    $color_scheme = '
                        <style type="text/css">
                            /* Framework start */
                                body {
                                    color: ' . $txt_color . ';
                                    background-color: ' . $bg_color . ';
                                }
                            /* Framework end */

                            /* Button start */
                                .button,
                                input[type="button"],
                                input[type="reset"],
                                input[type="submit"],
                                button[type="submit"] {
                                    color: ' . $txt_color . ' !important;
                                    border-color: ' . $txt_color . ';
                                }

                                .button.button-primary,
                                input[type="button"].button-primary,
                                input[type="reset"].button-primary,
                                input[type="submit"].button-primary,
                                button[type="submit"].button-primary {
                                    color: ' . $bg_color . ' !important;
                                    background-color: ' . $txt_color . ';
                                }

                                .button:hover,
                                input[type="button"]:hover,
                                input[type="reset"]:hover,
                                input[type="submit"]:hover,
                                button[type="submit"]:hover {
                                    color: ' . $bg_color . ' !important;
                                    background-color: ' . $txt_color . ';
                                }

                                .button:hover.button-primary,
                                input[type="button"]:hover.button-primary,
                                input[type="reset"]:hover.button-primary,
                                input[type="submit"]:hover.button-primary,
                                button[type="submit"]:hover.button-primary {
                                    color: ' . $bg_color . ';
                                    background-color: rgba(' . zaxu_hex2RGB($txt_color, true) . ', .8);
                                }
                            /* Button end */

                            /* Footer start */
                                .site-footer .footer-social-container ul li a {
                                    color: ' . $txt_color . ';
                                }
                                .site-footer .footer-info-container .copyright a {
                                    color: ' . $txt_color . ';
                                }
                                .site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box {
                                    color: ' . $txt_color . ';
                                    border-color: rgba(' . zaxu_hex2RGB($txt_color, true) . ', .1);
                                    background-color: ' . $bg_color . ';
                                }
                                .site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box:after {
                                    border-color: rgba(' . zaxu_hex2RGB($txt_color, true) . ', .1);
                                    background-color: ' . $bg_color . ';
                                }
                                .site-footer .footer-statement-container .statement-item {
                                    color: ' . $txt_color . ';
                                }
                            /* Footer end */

                            /* Old IE Browser start */
                                .old-ie-browser body .site-compatible-container {
                                    background-color: ' . $bg_color . ';
                                }
                            /* Old IE Browser end */
                        </style>
                    ';
                    if (get_theme_mod('zaxu_minify_engine', 'enabled') == 'enabled') {
                        $color_scheme = str_replace(
                            array(
                                "\rn",
                                "\r",
                                "\n",
                                "\t",
                                '  ',
                                '    ',
                                '    '
                            ),
                            '',
                            $color_scheme
                        );
                        $color_scheme = preg_replace('/\/\*.*?\*\//s', '', $color_scheme);
                    }
                    echo $color_scheme;
                // Set maintenance color scheme end
            }
        ?>
    </head>
    <body id="body" class="<?php echo $body_class; ?>">
        <?php
            if ( $pending_status == "maintenance" && get_theme_mod('zaxu_maintenance_background_image') ) {
                // Maintenance mode
                echo '
                    <picture class="pending-bg-img">
                        <img src="' . get_theme_mod('zaxu_maintenance_background_image') . '" alt="' . esc_attr( get_bloginfo('name') ) . '" />
                    </picture>
                ';
            };
        ?>
        <section class="site-main-container all-aboard">
            <div class="site-carry">
                <div id="content" class="site-content alignfull">
                    <?php zaxu_wrapper_start(); ?>
                        <article itemscope itemtype="http://schema.org/Article">
                            <div class="entry-content" itemprop="articleBody">
                                <?php
                                    if ($pending_status == "maintenance") {
                                        // Maintenance mode

                                        // Get title & description start
                                            $title_str = get_theme_mod('zaxu_maintenance_title');
                                            $desc_str = get_theme_mod('zaxu_maintenance_description');
                                            if ($title_str || $desc_str) {
                                                echo '<section class="text-center">';
                                                    if ($title_str) {
                                                        echo '<h1>' . $title_str . '</h1>';
                                                    }
                                                    if ($desc_str) {
                                                        echo '<h4>' . $desc_str . '</h4>';
                                                    }
                                                echo '</section>';
                                            }
                                        // Get title & description end

                                        // Get countdown description start
                                            $countdown_desc_str = get_theme_mod('zaxu_maintenance_countdown_description');
                                            if ($countdown_desc_str) {
                                                $countdown_desc_str = '<h5 class="countdown-desc text-center">' . $countdown_desc_str . '</h5>';
                                            } else {
                                                $countdown_desc_str = null;
                                            }
                                        // Get countdown description end

                                        if (get_theme_mod('zaxu_maintenance_countdown_switch') == "enabled") {
                                            echo '
                                                <section class="countdown-container" data-deadline="' . date( "Y/m/d H:i:s", strtotime( get_theme_mod('zaxu_maintenance_countdown_launch_date') ) ) . '" data-current="' . date( "Y/m/d H:i:s", strtotime( current_time("mysql") ) ) . '">
                                                    ' . $countdown_desc_str . '
                                                    <ul class="countdown-content">
                                                        <li>
                                                            <span class="digits days">00</span>
                                                            <i>:</i>
                                                            <span class="label">' . __('Days', 'zaxu') . '</span>
                                                        </li>
                                                        <li>
                                                            <span class="digits hours">00</span>
                                                            <i>:</i>
                                                            <span class="label">' . __('Hours', 'zaxu') . '</span>
                                                        </li>
                                                        <li>
                                                            <span class="digits minutes">00</span>
                                                            <i>:</i>
                                                            <span class="label">' . __('Minutes', 'zaxu') . '</span>
                                                        </li>
                                                        <li>
                                                            <span class="digits seconds">00</span>
                                                            <span class="label">' . __('Seconds', 'zaxu') . '</span>
                                                        </li>
                                                    </ul>
                                                </section>
                                            ';
                                        }
                                    } else if ($pending_status == "license") {
                                        // License mode
                                        echo '
                                            <section class="text-center">
                                                <h1>' . __('Oops!', 'zaxu') . '</h1>
                                                <h4>' . zaxu_set_license_tips() . '</h4>
                                            </section>
                                        ';
                                    }
                                ?>
                            </div>
                        </article>
                    <?php zaxu_wrapper_end(); ?>
                </div>
            </div>
            <?php zaxu_footer_info(); ?>
        </section>
        <?php
            zaxu_image_popup();
            zaxu_compatible();
            echo $javascript_file;
            zaxu_no_script();
        ?>
    </body>
</html>