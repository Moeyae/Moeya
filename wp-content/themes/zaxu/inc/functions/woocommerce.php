<?php
/*
 * @Description: WooCommerce functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

/** Prep theme
 *
 * @since 2.3.0
*/

// WooCommerce support
function woocommerce_support() {
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'woocommerce_support');

// Remove WooCommerce sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Remove WooCommerce breadcrumb
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

// Remove WooCommerce catalog ordering
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// Remove WooCommerce result count
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

// Disable WooCommerce page title
add_filter('woocommerce_show_page_title', '__return_false');

// Shop display only show product
update_option("woocommerce_shop_page_display", null);

// Remove WooCommerce customize section
function zaxu_woocommerce_customize_register() {     
	global $wp_customize;
	$wp_customize -> remove_section('woocommerce_product_catalog');
	$wp_customize -> remove_section('woocommerce_product_images');
}
add_action('customize_register', 'zaxu_woocommerce_customize_register');

// Remove single product title
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

// Remove single product summary
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

// Remove attribute 'p' tag
add_filter('woocommerce_attribute', 'woocommerce_attribute_filter_callback', 10, 3);
function woocommerce_attribute_filter_callback($formatted_values, $attribute, $values) {
    return str_ireplace( array('<p>', '</p>'), '', wpautop( wptexturize( implode(', ', $values) ) ) );
}

// Remove single product short description
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

// Remove single product category
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Reorder things on single product page
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 13);

add_action('woocommerce_before_add_to_cart_quantity', 'zaxu_echo_qty_front_add_cart');
function zaxu_echo_qty_front_add_cart() {
	echo '<div class="quantity-label">' . esc_html__('Quantity', 'zaxu') . '</div>'; 
}

// Remove WooCommerce inline style
add_action('wp_print_styles', function() {
	wp_style_add_data('woocommerce-inline', 'after', '');
});

// Remove WooCommerce no js
add_filter('body_class', function($classes) {
	remove_action('wp_footer', 'wc_no_js');
	$classes = array_diff( $classes, array('woocommerce-no-js') );
	return array_values($classes);
}, 10, 1);

// Change WooCommerce cart placeholder image
function zaxu_cart_item_thumbnail($thumbnail, $cart_item, $cart_item_key) {
	$product_image_id = $cart_item['data']->get_image_id();
	$product_name = $cart_item['data']->get_name();
	$product_image_url = wp_get_attachment_image_src($product_image_id, 'medium');
	if ( empty($product_image_id) ) {
		$thumbnail = "<img width='300' height='300' src='" . get_template_directory_uri() . "/assets/img/file-light-600x600.jpg' class='woocommerce-placeholder wp-post-image' alt='" . $product_name . "' />";
	} else {
		$thumbnail = "<img width='300' height='300' src='" . $product_image_url[0] . "' class='attachment-woocommerce_thumbnail size-woocommerce_thumbnail' alt='" . $product_name . "' />";
	}
	return $thumbnail;
}
add_filter('woocommerce_cart_item_thumbnail', 'zaxu_cart_item_thumbnail', 10, 3);

/** Remove WooCommerce styles and scripts
 *
 * @since 2.3.0
*/

function zaxu_woocommerce_script_cleaner() {
	remove_action( 'wp_head', array($GLOBALS['woocommerce'], 'generator') );
	wp_dequeue_style('woocommerce_frontend_styles');
	wp_dequeue_style('woocommerce-general');
	wp_dequeue_style('woocommerce-layout');
	wp_dequeue_style('woocommerce-smallscreen');
	wp_dequeue_style('woocommerce_fancybox_styles');
	wp_dequeue_style('woocommerce_chosen_styles');
	wp_dequeue_style('woocommerce_prettyPhoto_css');
	wp_dequeue_style('wc-block-style');
	wp_dequeue_style('select2');
    wp_dequeue_script('select2');
	wp_dequeue_script('selectWoo');
	wp_deregister_script('selectWoo');
	wp_dequeue_script('wc-add-payment-method');
	wp_dequeue_script('wc-lost-password');
	wp_dequeue_script('wc_price_slider');
	wp_dequeue_script('wc-single-product');
	wp_dequeue_script('wc-add-to-cart');
	wp_dequeue_script('wc-cart-fragments');
	wp_dequeue_script('wc-credit-card-form');
	wp_dequeue_script('wc-checkout');
	wp_dequeue_script('wc-add-to-cart-variation');
	wp_deregister_script('wc-add-to-cart-variation');
	wp_dequeue_script('wc-single-product');
	wp_dequeue_script('wc-cart');
	wp_dequeue_script('wc-chosen');
	wp_dequeue_script('woocommerce');
	wp_dequeue_script('prettyPhoto');
	wp_dequeue_script('prettyPhoto-init');
	wp_dequeue_script('jquery-blockui');
	wp_dequeue_script('jquery-placeholder');
	wp_dequeue_script('jquery-payment');
	wp_dequeue_script('fancybox');
	wp_dequeue_script('jqueryui');
}
add_action('wp_enqueue_scripts', 'zaxu_woocommerce_script_cleaner', 99);

/** Default product sorting
 *
 * @since 2.3.0
*/

add_filter('woocommerce_default_catalog_orderby', 'woocommerce_default_product_sorting');
function woocommerce_default_product_sorting() {
	$product_sort = get_theme_mod("zaxu_default_product_sorting", "date");
	if ($product_sort == "date") {
		return 'date';
	} else if ($product_sort == "menu_order") {
		return 'menu_order';
	} else if ($product_sort == "popularity") {
		return 'popularity';
	} else if ($product_sort == "rating") {
		return 'rating';
	} else if ($product_sort == "price") {
		return 'price';
	} else if ($product_sort == "price_desc") {
		return 'price-desc';
	};
}

/** Product per page
 *
 * @since 2.3.0
*/

add_filter('loop_shop_per_page', 'zaxu_product_per_page', 20);

function zaxu_product_per_page($cols) {
	$cols = get_theme_mod("zaxu_product_per_page", 20);
	return $cols;
}

/** New tab to visit store
 *
 * @since 2.3.0
*/

add_action('admin_bar_menu', 'zaxu_new_tab_to_visit_store', 999);
function zaxu_new_tab_to_visit_store($wp_admin_bar) {
    $all_toolbar_nodes = $wp_admin_bar->get_nodes();
	foreach ($all_toolbar_nodes as $node) {
        if ($node->id == 'site-name' || $node->id == 'view-store') {
        	$args = $node;
            $args->meta = array('target' => '_blank');
			$wp_admin_bar->add_node($args);
        }
	}
}

/** Product category
 *
 * @since 2.3.0
*/

if ( !function_exists('zaxu_product_category') ) :
	function zaxu_product_category($device) {
		if (get_theme_mod('zaxu_product_category', 'enabled') == 'enabled') {
			if ( is_shop() ) {
				$product_category_sorting = get_theme_mod('zaxu_default_product_category_sorting', 'name');
				if ($product_category_sorting == 'name') {
					// Sort by name (asc)
					$cat_args = array(
					    'order' => 'ASC',
					    'orderby' => 'name',
					    'posts_per_page' => -1,
					    'hide_empty' => true,
					    'post_status' => 'publish',
					    'suppress_filters' => false,
					);
				} else if ($product_category_sorting == 'name_desc') {
					// Sort by name (desc)
					$cat_args = array(
					    'order' => 'DESC',
					    'orderby' => 'name',
					    'posts_per_page' => -1,
					    'hide_empty' => true,
					    'post_status' => 'publish',
					    'suppress_filters' => false,
					);
				} else if ($product_category_sorting == 'menu_order') {
					// Default sorting (custom ordering + name)
					$cat_args = array(
					    'orderby' => 'menu_order',
					    'posts_per_page' => -1,
					    'hide_empty' => true,
					    'post_status' => 'publish',
					    'suppress_filters' => false,
					);
				};

				$product_categories = get_terms('product_cat', $cat_args);

				if ( !empty($product_categories) ) {
					if ($device == "desktop") {
						echo '
						    <section class="product-category-container ' . $device . '">
							    <h3 class="headline">' . esc_html__('Category', 'zaxu') . '</h3>
							    <div class="product-category-box">
								    <nav>
									    <ul class="product-category-list">
										    <li class="product-category-item all current" data-category="*">
											    <span class="name">' . esc_html__('All', 'zaxu') . '</span>
											    <span class="badge">' .  wp_count_posts("product") -> publish . '</span>
										    </li>
					    ';
									foreach ($product_categories as $key => $category) {
										echo '
											<li class="product-category-item" data-category=".' . zaxu_strToHex($category -> name) . '">
												<span class="name">' . $category -> name . '</span>
												<span class="badge">' . $category -> count . '</span>
											</li>
										';
									}
					    echo '
									    </ul>
								    </div>
							    </nav>
						    </section>
					    ';
					} else {
						echo '
							<section class="product-category-container site-overlay-element ' . $device . '">
								<div class="product-category-box">
									<header>
										<h3 class="headline">' . esc_html__('Category', 'zaxu') . '</h3>
									</header>
									<nav>
										<ul class="product-category-list">
											<li class="product-category-item all current" data-category="*">
												<span class="name">' . esc_html__('All', 'zaxu') . '</span>
												<span class="badge">' .  wp_count_posts("product") -> publish . '</span>
											</li>
						';
									foreach ($product_categories as $key => $category) {
										echo '
											<li class="product-category-item" data-category=".' . zaxu_strToHex($category -> name) . '">
												<span class="name">' . $category -> name . '</span>
												<span class="badge">' . $category -> count . '</span>
											</li>
										';
									}
						echo '
										</ul>
									</nav>
								</div>
						    </section>
					    ';
					}
				}
			};
		};
	};
endif;
?>