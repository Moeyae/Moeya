<?php
/*
 * @Description: Highlight code block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-highlight-code-' . $block['id'];
$language = zaxu_get_field('zaxu_highlight_code_language');
$code = zaxu_get_field('zaxu_highlight_code_content');

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-highlight-code-container<?php echo $align_class; ?>">
    <?php
        if ( !empty($code) ) {
            // Has item
            echo '<pre class="zaxu-highlightjs"><code class="' . esc_attr($language) . '">' . htmlentities($code) . '</code></pre>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit highlight code.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The highlight code is not currently available.', 'zaxu') );
            };
        };
    ?>
</section>