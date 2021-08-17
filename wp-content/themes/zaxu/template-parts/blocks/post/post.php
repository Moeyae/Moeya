<?php
/*
 * @Description: Post block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-post-' . $block['id'];

$block_title = esc_attr( zaxu_get_field('zaxu_post_block_title') );
$block_link = esc_url( zaxu_get_field('zaxu_post_block_link') );
$block_link_title = esc_attr( zaxu_get_field('zaxu_post_block_link_title') );
$style = zaxu_get_field('zaxu_post_style');
$source = zaxu_get_field('zaxu_post_source');
$quantity = esc_attr( zaxu_get_field('zaxu_post_quantity') );
$specified_contents = zaxu_get_field('zaxu_post_specified_content');

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

<?php
    if ($style == "carousel") {
        $align_class = " alignfull";
    }

    echo '<section id="' . esc_attr($id) . '" class="zaxu-post-container ' . $style . '-mode' . $align_class . '">';

    if ($style == "carousel" && $align == "full") {
        echo '<div class="zaxu-post-wrapper alignfull">';
    } else if ($style == "carousel" && $align == "wide") {
        echo '<div class="zaxu-post-wrapper alignwide">';
    } else {
        echo '<div class="zaxu-post-wrapper">';
    }

    // Get header start
        $block_link_html = null;
        if ($block_link) {
            if (!$block_link_title) {
                $block_link_title = $block_link;
            }
            $block_link_html = '<a href="' . $block_link . '" class="zaxu-post-link ajax-link">' . $block_link_title . '<span class="icon"></span></a>';
        };

        if ($block_title) {
            echo '
                <header class="zaxu-post-headline">
                    <h3 class="zaxu-post-title">' . $block_title . '</h3>
                    '. $block_link_html . '
                </header>
            ';
        };
    // Get header end

    if ($style == "grid") {
        $grid_cols = ' data-columns="auto"';
    } else {
        $grid_cols = '';
    };

    // Get post item start
        if ( !function_exists('get_post_item') ) {
            function get_post_item($quantity, $source, $style, $grid_cols) {
                $args = array(
                    'numberposts' => $quantity,
                    'post_type' => $source,
                    'post_status' => 'publish',
                    'suppress_filters' => false
                );
                $post_query = new WP_Query($args);
                if ($post_query -> have_posts() && $quantity != 0 && $quantity) {
                    // Has item
                    $recent_posts = wp_get_recent_posts($args);
                    echo '<section class="post-article-container ' . $style . '-mode"' . $grid_cols . '>';
                        if ($style == "carousel") {
                            echo '<ul class="swiper-wrapper">';
                        }
                            foreach($recent_posts as $recent_post) {
                                zaxu_post_article($style, $source, $recent_post['ID'], "block");
                            }
                            wp_reset_postdata();
                        if ($style == "carousel") {
                            echo '
                                </ul>
                                <div class="zaxu-swiper-button-next background-blur"></div>
                                <div class="zaxu-swiper-button-prev background-blur"></div>
                                <div class="swiper-pagination"></div>
                            ';
                        }
                    echo '</section>';
                } else {
                    // No item
                    zaxu_no_item_tips( __('Sorry! The post is not currently available.', 'zaxu') );
                }
            }
        }
    // Get post item end

    // Get specified content start
        if ( !function_exists('get_specified_content') ) {
            function get_specified_content($specified_contents, $source, $style) {
                if ($specified_contents) {
                    // Has item
                    echo '<section class="post-article-container ' . $style . '-mode">';
                        if ($style == "carousel") {
                            echo '<ul class="swiper-wrapper">';
                        }
                            foreach($specified_contents as $specified_content) {
                                if (get_post_status($specified_content->ID) == 'publish') {
                                    zaxu_post_article($style, $source, $specified_content->ID, "block");
                                }
                            }
                            wp_reset_postdata();
                        if ($style == "carousel") {
                            echo '
                                </ul>
                                <div class="zaxu-swiper-button-next"></div>
                                <div class="zaxu-swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                            ';
                        }
                    echo '</section>';
                } else {
                    // No item
                    zaxu_no_item_tips( __('Sorry! The post is not currently available.', 'zaxu') );
                }
            }
        }
    // Get specified content end

    if ($source == "post") {
        // Get blog post
        get_post_item($quantity, $source, $style, $grid_cols);
    } else if ($source == "page") {
        // Get page post
        get_post_item($quantity, $source, $style, $grid_cols);
    } else if ($source == "portfolio") {
        // Get portfolio post
        get_post_item($quantity, $source, $style, $grid_cols);
    } else if ($source == "product") {
        // Get product post
        if ( class_exists('WooCommerce') ) {
            get_post_item($quantity, $source, $style, $grid_cols);
        } else {
            zaxu_no_item_tips( __('Sorry! WooCommerce plugin is not activated.', 'zaxu') );
        }
    } else {
        // Get specified content
        get_specified_content($specified_contents, $source, $style);
    }

    echo '
            </div>
        </section>
    ';
?>