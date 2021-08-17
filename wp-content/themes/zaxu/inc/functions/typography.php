<?php
/*
 * @Description: Typography
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

function zaxu_typography_style() {
    $zaxu_typography = get_theme_mod('zaxu_typography', 'default');
    if ($zaxu_typography == "aleo") {
        $zaxu_typography = "'Aleo', serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=Aleo:wght@400;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    } else if ($zaxu_typography == "playfair_display") {
        $zaxu_typography = "'Playfair Display', serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    } else if ($zaxu_typography == "poppins") {
        $zaxu_typography = "'Poppins', sans-serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    } else if ($zaxu_typography == "roboto") {
        $zaxu_typography = "'Roboto', sans-serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    } else if ($zaxu_typography == "noto_serif_sc") {
        $zaxu_typography = "'Noto Serif SC', serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    } else {
        $zaxu_typography = "-apple-system, 'SF Pro Text', 'Helvetica Neue', Helvetica, 'PingFang SC', 'Microsoft YaHei', Arial, sans-serif";
        $google_font_param = "@import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Playfair+Display:wght@400;700&display=swap');";
    }

    $typography_css = "
        {$google_font_param}
        /* Framework start */
            body {
                font-family: {$zaxu_typography};
            }
        /* Framework end */

        /* Media element start */
            .mejs-container * {
                font-family: {$zaxu_typography};
            }
        /* Media element end */
    ";

    if (get_theme_mod('zaxu_minify_engine', 'enabled') == 'enabled') {
        $typography_css = str_replace(
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
            $typography_css
        );
        $typography_css = preg_replace('/\/\*.*?\*\//s', '', $typography_css);
    }

    wp_register_style('zaxu-typography', false);
    wp_enqueue_style('zaxu-typography');
    wp_add_inline_style('zaxu-typography', $typography_css);
}
add_action('wp_enqueue_scripts', 'zaxu_typography_style', 10);
?>