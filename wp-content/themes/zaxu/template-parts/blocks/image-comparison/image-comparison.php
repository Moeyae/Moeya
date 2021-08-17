<?php
/*
 * @Description: Image comparison block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-image-comparison-' . $block['id'];

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-image-comparison-container<?php echo $align_class; ?>">
    <?php
        $before_img = zaxu_get_field('zaxu_image_comparison_before_image');
        $after_img = zaxu_get_field('zaxu_image_comparison_after_image');
        if ($before_img && $after_img) {
            // Has item
            $orientation = zaxu_get_field('zaxu_image_comparison_orientation');
            $move_slider_on_hover = zaxu_get_field('zaxu_image_comparison_move_slider_on_hover');
            $before_label = esc_attr( zaxu_get_field('zaxu_image_comparison_before_label') );
            $after_label = esc_attr( zaxu_get_field('zaxu_image_comparison_after_label') );
            $before_placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . $before_img['width'] . " " . $before_img['height'] . "'%3E%3C/svg%3E";
            $after_placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . $after_img['width'] . " " . $after_img['height'] . "'%3E%3C/svg%3E";

            // Set before & after image start
                if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                    $comparison_img = '
                        <img src="' . $before_placeholder_img . '" data-src="' . $before_img['sizes']['large'] . '" data-srcset="' . $before_img['url'] . ' 2x" width="' . $before_img['width'] . '" height="' . $before_img['height'] . '" alt="' . $before_label . '" class="zaxu-image-comparison-before zaxu-lazy" />
                        <img src="' . $after_placeholder_img . '" data-src="' . $after_img['sizes']['large'] . '" data-srcset="' . $after_img['url'] . ' 2x" width="' . $after_img['width'] . '" height="' . $after_img['height'] . '" alt="' . $after_label . '" class="zaxu-image-comparison-after zaxu-lazy" />
                    ';
                } else {
                    $comparison_img = '
                        <img src="' . $before_img['sizes']['large'] . '" srcset="' . $before_img['url'] . ' 2x" width="' . $before_img['width'] . '" height="' . $before_img['height'] . '" alt="' . $before_label . '" class="zaxu-image-comparison-before loaded" />
                        <img src="' . $after_img['sizes']['large'] . '" srcset="' . $after_img['url'] . ' 2x" width="' . $after_img['width'] . '" height="' . $after_img['height'] . '" alt="' . $after_label . '" class="zaxu-image-comparison-after loaded" />
                    ';
                }
            // Set before & after image end

            if ( is_admin() ) {
                $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . $before_img['width'] . " " . $before_img['height'] . "'%3E%3C/svg%3E";
                $backend_placeholder = '
                    <img src="' . $placeholder_img . '" width="' . $before_img['width'] . '" height="' . $before_img['height'] . '" alt="' . $before_label . '" class="zaxu-image-comparison-placeholder" />
                ';
                // Set handle for backend start
                    if ($orientation == 'horizontal') {
                        $backend_orientation = ' twentytwenty-horizontal';
                        $backend_action = '
                            <div class="twentytwenty-handle">
                                <span class="twentytwenty-left-arrow"></span>
                                <span class="twentytwenty-right-arrow"></span>
                            </div>
                        ';
                    } else {
                        $backend_orientation = ' twentytwenty-vertical';
                        $backend_action = '
                            <div class="twentytwenty-handle">
                                <span class="twentytwenty-down-arrow"></span>
                                <span class="twentytwenty-up-arrow"></span>
                            </div>
                        ';
                    }
                // Set handle for backend end
            } else {
                $backend_placeholder = null;
                $backend_orientation = null;
                $backend_action = null;
            }

            echo '
                <div class="zaxu-image-comparison-slider' . $backend_orientation . '" data-orientation="' . $orientation . '" data-before_label="' . $before_label . '" data-after_label="' . $after_label . '" data-mover_slider_on_hover="' . $move_slider_on_hover . '">
                    ' . $backend_placeholder . $comparison_img . $backend_action . '
                </div>
            ';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit image comparison.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The image comparison is not currently available.', 'zaxu') );
            };
        }
    ?>
</section>