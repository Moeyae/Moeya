<?php
/*
 * @Description: Alert tips block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-alert-tips-' . $block['id'];

$type = zaxu_get_field('zaxu_alert_tips_type');
$type_class = 'zaxu-alert-tips-' . $type;
$icon = zaxu_get_field('zaxu_alert_tips_icon');
$close_button = zaxu_get_field('zaxu_alert_tips_close_button');
$dynamic_color = zaxu_get_field('zaxu_alert_tips_dynamic_color');
if ($dynamic_color == true) {
    $dynamic_color_class = " dynamic-color";
} else {
    $dynamic_color_class = null;
}
if ($close_button == true) {
    $close_button_class = " has-close-button";
} else {
    $close_button_class = null;
}
$title = esc_attr( zaxu_get_field('zaxu_alert_tips_title') );
$content = zaxu_get_field('zaxu_alert_tips_content');

// Align
$align = $block['align'];
if ($align == "wide") {
    $align_class = " alignwide";
} else if ($align == "full") {
    $align_class = " alignfull";
} else {
    $align_class = null;
}
?>

<section id="<?php echo esc_attr($id); ?>" class="zaxu-alert-tips-container<?php echo $align_class; ?>">
    <?php
        if ( !empty($title) || !empty($content) ) {
            // Has item
            echo '<div class="zaxu-alert-tips-box ' . $type_class . $dynamic_color_class .  $close_button_class . '" role="alert">';
                if ($icon == true) {
                    echo zaxu_icon($type, 'icon');
                };
                echo '<div class="zaxu-alert-tips-description">';
                    if ($title) {
                        echo '<span class="zaxu-alert-tips-title">' . $title . '</span>';
                    };
                    if ($content) {
                        echo '<span class="zaxu-alert-tips-content">' . $content . '</span>';
                    };
                echo '</div>';
                if ($close_button == true) {
                    echo '<span class="close"></span>';
                };
            echo '</div>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit alert tips.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The alert tips is not currently available.', 'zaxu') );
            };
        };
    ?>
</section>