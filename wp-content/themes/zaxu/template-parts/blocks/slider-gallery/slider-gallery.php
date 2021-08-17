<?php
/*
 * @Description: Slider gallery block
 * @Version: 2.7.1
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-slider-gallery-' . $block['id'];
$autoplay = esc_attr( zaxu_get_field("zaxu_slider_gallery_autoplay") );
$navigation = zaxu_get_field("zaxu_slider_gallery_previous_next_buttons");
$pagination = zaxu_get_field("zaxu_slider_gallery_navigation_dots");
$slide_height = zaxu_get_field("zaxu_slider_gallery_slider_height");

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
<section id="<?php echo esc_attr($id); ?>" class="zaxu-slider-gallery-container<?php echo $align_class; ?>">
    <?php
        if ( have_rows('zaxu_slider_gallery_repeater') ) {
            // Has item
            echo '
                <gallery data-autoplay="' . $autoplay . '" data-height="' . $slide_height . '">
                    <ul class="swiper-wrapper">
            ';
            while( have_rows('zaxu_slider_gallery_repeater') ): the_row();
                $link = esc_url( zaxu_get_sub_field('zaxu_slider_gallery_item_link') );
                $caption = esc_attr( zaxu_get_sub_field('zaxu_slider_gallery_item_caption') );
                $default_image = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
                $default_image_dark = get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg';
                $image = zaxu_get_sub_field('zaxu_slider_gallery_item_image');

                $dynamic_color = get_theme_mod('zaxu_dynamic_color', 'disabled');

                if ($image) {
                    // Has image
                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                        $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 " . $image['width'] . " " . $image['height'] . "'%3E%3C/svg%3E";
                        // Has lazyload
                        $image_html = '
                            <picture>
                                <img src="' . $placeholder_img . '" data-src="' . $image["url"] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $caption . '" class="swiper-lazy" />
                                <div class="swiper-lazy-preloader"></div>
                            </picture>
                        ';
                    } else {
                        // No lazyload
                        $image_html = '
                            <picture>
                                <img src="' . $image["url"] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $caption . '" />
                            </picture>
                        ';
                    };
                } else {
                    // No image
                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                        $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1920 1280'%3E%3C/svg%3E";
                        // Has lazyload
                        if ($dynamic_color == "enabled") {
                            $image_html = '
                                <picture>
                                    <source data-srcset="' . $default_image_dark . '" media="(prefers-color-scheme: dark)" class="swiper-lazy" />
                                    <img src="' . $placeholder_img . '" data-src="' . $default_image . '" width="1920" height="1280" alt="' . $caption . '" class="swiper-lazy" />
                                    <div class="swiper-lazy-preloader"></div>
                                </picture>
                            ';
                        } else {
                            $image_html = '
                                <picture>
                                    <img src="' . $placeholder_img . '" data-src="' . $default_image . '" width="1920" height="1280" alt="' . $caption . '" class="swiper-lazy" />
                                    <div class="swiper-lazy-preloader"></div>
                                </picture>
                            ';
                        }
                    } else {
                        // No lazyload
                        if ( is_admin() ) {
                            $image_html = '
                                <picture>
                                    <img src="' . $default_image . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $caption . '" />
                                </picture>
                            ';
                        } else {
                            if ($dynamic_color == "enabled") {
                                $image_html = '
                                    <picture>
                                        <source srcset="' . $default_image_dark . '" media="(prefers-color-scheme: dark)" />
                                        <img src="' . $default_image . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $caption . '" />
                                    </picture>
                                ';
                            } else {
                                $image_html = '
                                    <picture>
                                        <img src="' . $default_image . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $caption . '" />
                                    </picture>
                                ';
                            }
                        }
                    };
                };

                echo '<li class="swiper-slide">';
                if ($caption) {
                    if ($pagination == 1) {
                        echo '<figure class="has-caption has-pagination">'; 
                    } else {
                        echo '<figure class="has-caption">';
                    };
                } else {
                    echo '<figure>';
                };
                if ($link) {
                    echo '
                        <a href="' . esc_url($link) . '" target="_blank">' . $image_html . '</a>
                    ';
                } else {
                    echo $image_html;
                };
                if ($caption) {
                    echo '<figcaption>' . $caption . '</figcaption>';
                };
                echo '</figure></li>';
            endwhile;

            echo '</ul>';
                if ($navigation == 1) {
                    echo '
                        <div class="zaxu-swiper-button-next background-blur"></div>
                        <div class="zaxu-swiper-button-prev background-blur"></div>
                    ';
                };
                if ($pagination == 1) {
                    echo '<div class="swiper-pagination"></div>';
                };
            echo '</gallery>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit slider gallery.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The slider gallery is not currently available.', 'zaxu') );
            };
        };
    ?>
</section>