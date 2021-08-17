<?php
/*
 * @Description: Waterfall gallery block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-waterfall-gallery-' . $block['id'];

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
<section id="<?php echo esc_attr($id); ?>" class="zaxu-waterfall-gallery-container<?php echo $align_class; ?>">
    <?php
        $images = zaxu_get_field("zaxu_waterfall_gallery_gallery");
        $ratio = zaxu_get_field("zaxu_waterfall_gallery_ratio");
        if ($ratio == "1_1") {
            $ratio = "view-1";
        } else if ($ratio == "4_3") {
            $ratio = "view-2";
        } else if ($ratio == "16_9") {
            $ratio = "view-3";
        } else {
            $ratio = "responsive";
        };
        $lightbox = zaxu_get_field("zaxu_waterfall_gallery_lightbox");
        if ($images) {
            // Has item
            echo '<ul class="zaxu-waterfall-gallery-list ' . $ratio . '">';
                foreach ($images as $image) {
                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                        $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . $image['width'] . " " . $image['height'] . "'%3E%3C/svg%3E";
                        $img_html = '<img src="' . $placeholder_img . '" data-src="' . esc_url( $image['sizes']['medium_large'] ) . '" data-srcset="' . esc_url( $image['sizes']['large'] ) . ' 2x" alt="' . esc_attr( $image['alt'] ) . '" width="' . $image['width'] . '" height="' . $image['height'] . '" class="zaxu-lazy" />';
                    } else {
                        $img_html = '<img src="' . esc_url( $image['sizes']['medium_large'] ) . '" srcset="' . esc_url( $image['sizes']['large'] ) . ' 2x" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . esc_attr( $image['alt'] ) . '" />';
                    };
                    echo '<li class="zaxu-waterfall-gallery-item">';
                        $caption = esc_attr( $image['caption'] );
                        if ($lightbox == 'enabled') {
                            echo '
                                <figure>
                                    <a href="' . esc_url( $image['url'] ) . '">' . $img_html . '</a>
                            ';
                                if ($caption) {
                                    echo '
                                        <figcaption>' . $caption . '</figcaption>
                                    ';
                                };
                            echo '</figure>';
                        } else {
                            echo '
                                <figure>' . $img_html . '
                            ';
                                if ($caption) {
                                    echo '
                                        <figcaption>' . $caption . '</figcaption>
                                    ';
                                };
                            echo '</figure>';
                        };
                    echo '</li>';
                }
            echo '</ul>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit waterfall gallery.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The waterfall gallery is not currently available.', 'zaxu') );
            };
        };
    ?>
</section>