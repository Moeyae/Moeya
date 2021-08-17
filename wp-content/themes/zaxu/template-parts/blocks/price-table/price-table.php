<?php
/*
 * @Description: Price table block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-price-table-' . $block['id'];

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-price-table-container<?php echo $align_class; ?>">
    <?php
        $empty_name = true;
        $empty_item = true;
        if ( have_rows('zaxu_price_table_repeater') ) {
            // Has item
            $empty_item = false;
            $price_table_item = null;
            while( have_rows('zaxu_price_table_repeater') ): the_row();
                // Get parameter
                $product_name = esc_attr( zaxu_get_sub_field('zaxu_price_table_product_name') );
                
                if ($product_name) {
                    $empty_name = false;

                    // Get parameter
                    $highlight = zaxu_get_sub_field('zaxu_price_table_highlight');
                    $highlight_title = esc_attr( zaxu_get_sub_field('zaxu_price_table_highlight_title') );
                    $product_desc = esc_attr( zaxu_get_sub_field('zaxu_price_table_product_description') );
                    $currency = esc_attr( zaxu_get_sub_field('zaxu_price_table_currency_symbol') );
                    $product_price = esc_attr( zaxu_get_sub_field('zaxu_price_table_product_price') );
                    $period = esc_attr( zaxu_get_sub_field('zaxu_price_table_period') );
                    $link = esc_url( zaxu_get_sub_field('zaxu_price_table_link') );
                    $link_title = esc_attr( zaxu_get_sub_field('zaxu_price_table_link_title') );
                    $new_tab = zaxu_get_sub_field('zaxu_price_table_new_tab');

                    // Highlight
                    if ($highlight == 1) {
                        $highlight = ' active';
                    }
                    // Highlight title
                    if ($highlight_title) {
                        $highlight_title =  '<div class="zaxu-price-table-highlight-tag">' . $highlight_title . '</div>';
                    }
                    // Product description
                    if ($product_desc) {
                        $product_desc = '<p class="zaxu-price-table-product-desc">' . $product_desc . '</p>';
                    }
                    // Price content
                    $price_content = null;
                    if ($currency || $product_price || $period) {
                        // Currency symbol
                        if ($currency) {
                            $currency = '<span class="zaxu-price-table-currency">' . $currency . '</span>';
                        }
                        // Product price
                        if ($product_price) {
                            $product_price = '<span class="zaxu-price-table-price">' . $product_price . '</span>';
                        }
                        if ($period) {
                            $period = '<span class="zaxu-price-table-period">' . $period . '</span>';
                        }
                        $price_content = '
                            <section class="zaxu-price-table-body">
                                ' . $currency . $product_price . $period . '
                            </section>
                        ';
                    }
                    // Link
                    if ($link) {
                        if (!$link_title) {
                            $link_title = __('Buy Now', 'zaxu');
                        }
                        if ($new_tab) {
                            $new_tab = ' target="_blank"';
                        }
                        $link = '<a href="' . $link . '"' . $new_tab . ' class="zaxu-price-table-link button button-primary">' . $link_title . '</a>';
                    }
                    // Feature
                    $feature_item = null;
                    $feature_content = null;
                    if ( have_rows('zaxu_price_table_feature_repeater') ) {
                        $empty_feature_title = true;
                        while( have_rows('zaxu_price_table_feature_repeater') ): the_row();
                            // Get parameter
                            $feature_title = esc_attr( zaxu_get_sub_field('zaxu_price_table_feature_title') );
                            $feature_desc = esc_attr( zaxu_get_sub_field('zaxu_price_table_feature_description') );

                            if ($feature_title) {
                                $empty_feature_title = false;
                                if ($feature_desc) {
                                    $feature_desc = '<dd class="zaxu-price-table-feature-desc">' . $feature_desc . '</dd>';
                                }
                                $feature_icon = zaxu_icon('success', 'icon');
                                $feature_item .= '
                                    <li class="zaxu-price-table-feature">
                                        ' . $feature_icon . '
                                        <dl>
                                            <dt class="zaxu-price-table-feature-heading">' . $feature_title . '</dt>
                                            ' . $feature_desc . '
                                        </dl>
                                    </li>
                                ';
                            }
                        endwhile;

                        if ($empty_feature_title == false) {
                            $feature_content = '
                                <ul class="zaxu-price-table-features">
                                    ' . $feature_item . '
                                </ul>
                            ';
                        }
                    }

                    // Output price table item
                    $price_table_item .= '
                        <li class="zaxu-price-table' . $highlight . '">
                            <div class="zaxu-price-table-wrap">
                                ' . $highlight_title . '
                                <div class="zaxu-price-table-box">
                                <header class="zaxu-price-table-head">
                                    <h3 class="zaxu-price-table-product-name">' . $product_name . '</h3>
                                    ' . $product_desc . '
                                </header>
                                ' . $price_content . $link . $feature_content . '
                                </div>
                            </div>
                        </li>
                    ';
                };
            endwhile;

            // Output price table
            if ($empty_name == false) {
                echo '
                    <ul class="zaxu-price-tables">' . $price_table_item . '</ul>
                ';
            }
        }
        
        if ($empty_name == true || $empty_item == true) {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit price table.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The price table is not currently available.', 'zaxu') );
            };
        }
    ?>
</section>