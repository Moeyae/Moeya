<?php
/*
 * @Description: Feature block
 * @Version: 2.7.1
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-feature-' . $block['id'];

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-feature-container<?php echo $align_class; ?>">
    <?php
        $empty_item = true;
        $feature_item = null;
        $item_width = esc_attr( zaxu_get_field('zaxu_feature_item_width') );

        if ( have_rows('zaxu_feature_repeater') ) {
            // Has item
            $empty_item = false;
            while( have_rows('zaxu_feature_repeater') ): the_row();
                $style = zaxu_get_sub_field('zaxu_feature_style');
                $style_class = $style ? ' zaxu-feature-cover' : ' zaxu-feature-icon';
                $image_or_video = zaxu_get_sub_field('zaxu_feature_image_or_video');
                $video_cover = zaxu_get_sub_field('zaxu_feature_video_cover');
                $title = esc_attr( zaxu_get_sub_field('zaxu_feature_title') );
                $description = esc_attr( zaxu_get_sub_field('zaxu_feature_description') );
                $link = esc_attr( zaxu_get_sub_field('zaxu_feature_link') );
                $link_title = esc_attr( zaxu_get_sub_field('zaxu_feature_link_title') ) ? esc_attr( zaxu_get_sub_field('zaxu_feature_link_title') ) : __('Learn More', 'zaxu');
                $link_new_tab = zaxu_get_sub_field('zaxu_feature_link_new_tab') ? ' target="_blank"' : null;
                $placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
                
				$source_image_original = get_template_directory_uri() . '/assets/img/file-light-960x640.jpg';
				$source_image_retina = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
				$source_image_original_dark = get_template_directory_uri() . '/assets/img/file-dark-960x640.jpg';
				$source_image_retina_dark = get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg';

                if (!$image_or_video && !$title && !$description && !$link) {
                    $feature_item .= null;
                } else {
                    // Image or video html start
                        if ($image_or_video) {
                            $extension = pathinfo($image_or_video)['extension'];
                            if ($extension == 'mp4') {
                                // Video start
                                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                                        // Frontend start
                                            if ($video_cover) {
                                                $video_cover_html = '<img src="' . $placeholder_img . '" data-src="' . $video_cover . '" alt="' . $title . '" class="zaxu-lazy" />';
                                            } else {
                                                if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled") {
                                                    $video_cover_html = '
                                                        <source data-srcset="' . $source_image_original_dark . ' 1x, ' . $source_image_retina_dark . ' 2x" media="(prefers-color-scheme: dark)" />
                                                        <img src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . $title . '" class="zaxu-lazy" />
                                                    ';
                                                } else {
                                                    $video_cover_html = '
                                                        <img src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . $title . '" class="zaxu-lazy" />
                                                    ';
                                                }
                                            }

                                            $media_html = '
                                                <figure class="zaxu-feature-item-media">
                                                    <picture class="zaxu-feature-item-cover">' . $video_cover_html . '</picture>
                                                    <video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $image_or_video . '" class="zaxu-feature-item-video no-mejs zaxu-lazy"></video>
                                                </figure>
                                            ';
                                        // Frontend end
                                    } else {
                                        // Backend start
                                            if ($video_cover) {
                                                $video_cover_html = '<img src="' . $video_cover . '" alt="' . $title . '" />';
                                            } else {
                                                $video_cover_html = ' <img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" alt="' . $title . '" />';
                                            }

                                            $media_html = '
                                                <figure class="zaxu-feature-item-media">
                                                    <picture class="zaxu-feature-item-cover">' . $video_cover_html . '</picture>
                                                    <video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $image_or_video . '" class="zaxu-feature-item-video no-mejs"></video>
                                                </figure>
                                            ';
                                        // Backend end
                                    }
                                // Video end
                            } else {
                                // Image start
                                    if ( get_theme_mod('zaxu_lazyload', 'enabled') == "enabled" && !is_admin() ) {
                                        // Frontend start
                                            $media_html = '
                                                <figure class="zaxu-feature-item-media">
                                                    <picture class="zaxu-feature-item-image">
                                                        <img src="' . $placeholder_img . '" data-src="' . $image_or_video . '" alt="' . $title . '" class="zaxu-lazy" />
                                                    </picture>
                                                </figure>
                                            ';
                                        // Frontend end
                                    } else {
                                        // Backend start
                                            $media_html = '
                                                <figure class="zaxu-feature-item-media">
                                                    <picture class="zaxu-feature-item-image">
                                                        <img src="' . $image_or_video . '" alt="' . $title . '" />
                                                    </picture>
                                                </figure>
                                            ';
                                        // Backend end
                                    }
                                // Image end
                            }
                        } else if ($style == 'cover') {
                            $media_html = '<figure class="zaxu-feature-item-media"></figure>';
                        } else {
                            $media_html = null;
                        }
                    // Image or video html end

                    // Title html start
                        if ($title) {
                            $title_html = '<h4 class="zaxu-feature-item-title">' . $title . '</h4>';
                        } else {
                            $title_html = null;
                        }
                    // Title html end

                    // Description html start
                        if ($description) {
                            $description_html = '<p class="zaxu-feature-item-description">' . $description . '</p>';
                        } else {
                            $description_html = null;
                        }
                    // Description html end

                    // Link html start
                        if ($link) {
                            $link_html = '<a href="' . $link . '"' . $link_new_tab . ' class="button button-primary button-mini zaxu-feature-item-link">' . $link_title . '</a>';
                        } else {
                            $link_html = null;
                        }
                    // Link html end

                    if ($title_html || $description_html || $link_html) {
                        $info_class = null;
                        $info_html = '<div class="zaxu-feature-item-info">' . $title_html . $description_html . $link_html . '</div>';
                    } else {
                        $info_class = ' zaxu-feature-no-info';
                        $info_html = null;
                    }

                    $feature_item .= '
                        <li class="zaxu-feature-item' . $style_class . $info_class . '">
                            <div class="zaxu-feature-item-box">
                                ' . $media_html . $info_html . '
                            </div>
                        </li>
                    ';
                }
            endwhile;
        }

        if ($empty_item == false && $feature_item) {
            // Has item
            echo '<ul class="zaxu-feature-list" data-item_with="' . $item_width . '">' . $feature_item . '</ul>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit feature.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The feature is not currently available.', 'zaxu') );
            }
        }
    ?>
</section>