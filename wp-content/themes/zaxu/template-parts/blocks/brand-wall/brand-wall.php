<?php
/*
 * @Description: Brand wall block
 * @Version: 2.7.0
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-brand-wall-' . $block['id'];
// Style
$style_class = zaxu_get_field('zaxu_brand_wall_style');

// Ratio
$ratio_class = zaxu_get_field('zaxu_brand_wall_ratio');

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-brand-wall-container <?php echo $ratio_class . ' ' . $style_class . $align_class; ?>">
    <?php
        if ( have_rows('zaxu_brand_wall_repeater') ) {
            // Has item
            echo '<ul class="zaxu-brand-wall-list">';
                while ( have_rows('zaxu_brand_wall_repeater') ): the_row();
                    // Logo
                    if ($ratio_class == "rectangle") {
                        $default_logo = get_template_directory_uri() . '/assets/img/user-light-300x120.jpg';
                        $default_logo_retina = get_template_directory_uri() . '/assets/img/user-light-600x240.jpg';
                        $default_logo_dark = get_template_directory_uri() . '/assets/img/user-dark-300x120.jpg';
                        $default_logo_dark_retina = get_template_directory_uri() . '/assets/img/user-dark-600x240.jpg';
                    } else {
                        $default_logo = get_template_directory_uri() . '/assets/img/user-light-300x300.jpg';
                        $default_logo_retina = get_template_directory_uri() . '/assets/img/user-light-600x600.jpg';
                        $default_logo_dark = get_template_directory_uri() . '/assets/img/user-dark-300x300.jpg';
                        $default_logo_dark_retina = get_template_directory_uri() . '/assets/img/user-dark-600x600.jpg';
                    }
                    $logo = wp_get_attachment_image_src(zaxu_get_sub_field('zaxu_brand_wall_logo'), 'medium');
                    $logo_retina = wp_get_attachment_image_src(zaxu_get_sub_field('zaxu_brand_wall_logo'), 'large');
                    $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
                    // Name
                    $name_string = esc_attr( zaxu_get_sub_field('zaxu_brand_wall_name') );
                    $name_element = $name_string ? '<span class="zaxu-brand-wall-name">' . $name_string . '</span>' : '';
                    $dynamic_color = get_theme_mod('zaxu_dynamic_color', 'disabled');

                    if ($style_class == "river") {
                        if ( empty($logo) ) {
                            if ( $dynamic_color == "enabled" && !is_admin() ) {
                                // No logo
                                $content = '
                                    <picture>
                                        <source srcset="' . $default_logo_dark . ' 1x, ' . $default_logo_dark_retina . ' 2x" media="(prefers-color-scheme: dark)" />
                                        <img src="' . $default_logo . '" srcset="' . $default_logo_retina . ' 2x" />
                                    </picture>
                                    '. $name_element .'
                                ';
                            } else {
                                // No logo
                                $content = '
                                    <picture>
                                        <img src="' . $default_logo . '" srcset="' . $default_logo_retina . ' 2x" />
                                    </picture>
                                    '. $name_element .'
                                ';
                            }
                        } else {
                            // Has logo
                            $content = '
                                <picture>
                                    <img src="' . $logo[0] . '" srcset="' . $logo_retina[0] . ' 2x" />
                                </picture>
                                '. $name_element .'
                            ';
                        }
                    } else if ($style_class == "grid") {
                        if ( empty($logo) ) {
                            // No logo
                            if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                                // Lazy load
                                if ($dynamic_color == "enabled") {
                                    $content = '
                                        <picture>
                                            <source srcset="' . $default_logo_dark . ' 1x, ' . $default_logo_dark_retina . ' 2x" media="(prefers-color-scheme: dark)" />
                                            <img src="' . $placeholder_img . '" data-src="' . $default_logo . '" data-srcset="' . $default_logo_retina . ' 2x" class="zaxu-lazy" />
                                        </picture>
                                        '. $name_element .'
                                    ';
                                } else {
                                    $content = '
                                        <picture>
                                            <img src="' . $placeholder_img . '" data-src="' . $default_logo . '" data-srcset="' . $default_logo_retina . ' 2x" class="zaxu-lazy" />
                                        </picture>
                                        '. $name_element .'
                                    ';
                                }
                            } else {
                                // No lazy load
                                if ( $dynamic_color == "enabled" && !is_admin() ) {
                                    $content = '
                                        <picture>
                                            <source srcset="' . $default_logo_dark . ' 1x, ' . $default_logo_dark_retina . ' 2x" media="(prefers-color-scheme: dark)" />
                                            <img src="' . $default_logo . '" srcset="' . $default_logo_retina . ' 2x" />
                                        </picture>
                                        '. $name_element .'
                                    ';
                                } else {
                                    $content = '
                                        <picture>
                                            <img src="' . $default_logo . '" srcset="' . $default_logo_retina . ' 2x" />
                                        </picture>
                                        '. $name_element .'
                                    ';
                                }
                            }
                        } else {
                            // Has logo
                            if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                                // Lazy load
                                $content = '
                                    <picture>
                                        <img src="' . $placeholder_img . '" data-src="' . $logo[0] . '" data-srcset="' . $logo_retina[0] . ' 2x" class="zaxu-lazy" />
                                    </picture>
                                    '. $name_element .'
                                ';
                            } else {
                                // No lazy load
                                $content = '
                                    <picture>
                                        <img src="' . $logo[0] . '" srcset="' . $logo_retina[0] . ' 2x" />
                                    </picture>
                                    '. $name_element .'
                                ';
                            }
                        }
                    }

                    echo '<li class="zaxu-brand-wall-item">' . $content . '</li>';
                endwhile;
            echo '</ul>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit brand wall.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The brand wall is not currently available.', 'zaxu') );
            }
        }
    ?>
</section>