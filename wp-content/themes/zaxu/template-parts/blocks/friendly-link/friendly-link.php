<?php
/*
 * @Description: Friendly link block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-friendly-link-' . $block['id'];

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-friendly-link-container<?php echo $align_class; ?>">
    <?php
        if ( have_rows('zaxu_friendly_link_repeater') ) {
            // Has item
            echo '<ul class="zaxu-friendly-link-list">';
            while( have_rows('zaxu_friendly_link_repeater') ): the_row();
                $avatar = wp_get_attachment_image_src(zaxu_get_sub_field('zaxu_friendly_link_avatar'), 'thumbnail');
                $default_avatar = get_template_directory_uri() . '/assets/img/user-light-300x300.jpg';
                $default_avatar_dark = get_template_directory_uri() . '/assets/img/user-dark-300x300.jpg';
                $name = esc_attr( zaxu_get_sub_field('zaxu_friendly_link_name') );
                $description = esc_attr( zaxu_get_sub_field('zaxu_friendly_link_description') );
                $url = esc_url( zaxu_get_sub_field('zaxu_friendly_link_url') );
                $dynamic_color = get_theme_mod('zaxu_dynamic_color', 'disabled');
                $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";

                // Avatar
                if ( empty($avatar) ) {
                    // No avatar
                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                        // Has lazyload
                        if ($dynamic_color == "enabled") {
                            $avatar_element = '
                                <picture>
                                    <source data-srcset="' . $default_avatar_dark . '" media="(prefers-color-scheme: dark)" />
                                    <img src="' . $placeholder_img . '" data-src=' . $default_avatar . ' alt="' . $name . '" class="zaxu-lazy" />
                                </picture>
                            ';
                        } else {
                            $avatar_element = '
                                <picture>
                                    <img src="' . $placeholder_img . '" data-src=' . $default_avatar . ' alt="' . $name . '" class="zaxu-lazy" />
                                </picture>
                            ';
                        }
                    } else {
                        // No lazyload
                        if ( $dynamic_color == "enabled" && !is_admin() ) {
                            $avatar_element = '
                                <picture>
                                    <source srcset="' . $default_avatar_dark . '" media="(prefers-color-scheme: dark)" />
                                    <img src=' . $default_avatar . ' alt="' . $name . '" />
                                </picture>
                            ';
                        } else {
                            $avatar_element = '
                                <picture>
                                    <img src=' . $default_avatar . ' alt="' . $name . '" />
                                </picture>
                            ';
                        }
                    }
                } else {
                    // Has avatar
                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                        // Has lazyload
                        $avatar_element = '
                            <picture>
                                <img src="' . $placeholder_img . '" data-src=' . $avatar[0] . ' alt="' . $name . '" class="zaxu-lazy" />
                            </picture>
                        ';
                    } else {
                        // No lazyload
                        $avatar_element = '
                            <picture>
                                <img src=' . $avatar[0] . ' alt="' . $name . '" />
                            </picture>
                        ';
                    }
                }

                // Name
                if ( empty($name) ) {
                    $name = '...';
                }

                // Description
                if ( empty($description) ) {
                    $description = '...';
                }

                // url
                if ( empty($url) ) {
                    $url_element = '<a href target="_blank" disabled class="button button-round button-mini">' . __('Visit', 'zaxu') . '</a>';
                } else {
                    $url_element = '<a href="' . $url . '" rel="nofollow" target="_blank" class="button button-round button-mini">' . __('Visit', 'zaxu') . '</a>';
                }

                echo '
                    <li class="zaxu-friendly-link-item">
                        <div class="zaxu-friendly-link-content">
                            ' . $avatar_element . '
                            <div class="zaxu-friendly-link-summary">
                                <h3 class="zaxu-friendly-link-name">' . $name . '</h3>
                                <span class="zaxu-friendly-link-description">' . $description . '</span>
                            </div>
                            ' . $url_element . '
                        </div>
                    </li>
                ';
            endwhile;
            echo '</ul>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit friendly link.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The friendly link is not currently available.', 'zaxu') );
            }
        }
    ?>
</section>