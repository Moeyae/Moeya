<?php
/*
 * @Description: Header
 * @Version: 2.7.1
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;
?>

<!DOCTYPE html>
	<!--[if lt IE 10]> <html <?php language_attributes(); ?> dir="ltr" class="old-ie-browser<?php echo (get_theme_mod('zaxu_site_grayscale') == "enabled") ? ' grayscale' : ''; ?>" xmlns="//www.w3.org/1999/xhtml"> <![endif]-->
	<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> dir="ltr" xmlns="http://www.w3.org/1999/xhtml" <?php echo (get_theme_mod('zaxu_site_grayscale') == "enabled") ? 'class="grayscale"' : ''; ?>> <!--<![endif]-->
	<head id="head">
		<?php
			zaxu_set_head();
			zaxu_screen_client_support();
			wp_head();

			// JS-SDK start
				if ( is_front_page() && is_home() ) {
					// Default homepage
				} else if ( is_front_page() ) {
					// Static homepage
					$post_id = get_the_ID();
					zaxu_jssdk($post_id);
				} else if ( is_home() ) {
					// Blog page
					$post_id = get_option('page_for_posts');
					zaxu_jssdk($post_id);
				} else {
					// Other page
					$post_id = get_the_ID();
					if ($post_id) {
						zaxu_jssdk($post_id);
					}
				}
			// JS-SDK end
			
			// Set body class start
				// User select start
					if (get_theme_mod('zaxu_site_user_select') == 'disabled') {
						$no_select = 'no-select';
					} else {
						$no_select = null;
					}
				// User select end

				// Has product start
					$no_product = null;
					if ( function_exists('is_woocommerce') ) {
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => -1,
							'post_status' => 'publish',
							'suppress_filters' => false,
						);
						$all_posts = new WP_Query($args);
						if ( is_shop() && !$all_posts->have_posts() ) {
							$no_product = 'no-product';
						}
					}
				// Has product end

				// Set navigation with safe area start
					function set_nav_safe_area($post_id) {
						if ( is_singular("post") || is_singular("page") || is_singular("portfolio") ) {
							// Is Post, Page, Portfolio
							$nav_safe_area = get_post_meta($post_id, 'zaxu_nav_safe_area', true);
							if ($nav_safe_area == 'no') {
								return 'no-nav-safe-area';
							} else {
								return 'has-nav-safe-area';
							}
						} else {
							// Other page
							if ( is_front_page() && is_home() ) {
								// Default homepage
								return 'has-nav-safe-area';
							} else if ( is_front_page() ) {
								// Static homepage
								return 'has-nav-safe-area';
							} else if ( is_home() ) {
								// Blog page
								$nav_safe_area = get_post_meta($post_id, 'zaxu_nav_safe_area', true);
								if ($nav_safe_area == 'no') {
									return 'no-nav-safe-area';
								} else {
									return 'has-nav-safe-area';
								}
							} else {
								// Other page
								return 'has-nav-safe-area';
							}
						}
					}

					if ( is_front_page() && is_home() ) {
						// Default homepage
						$nav_safe_area = 'has-nav-safe-area';
					} else if ( is_front_page() ) {
						// Static homepage
						$post_id = get_the_ID();
						$nav_safe_area = set_nav_safe_area($post_id);
					} else if ( is_home() ) {
						// Blog page
						$post_id = get_option('page_for_posts');
						$nav_safe_area = set_nav_safe_area($post_id);
					} else {
						// Everything else
						$post_id = get_the_ID();
						if ($post_id) {
							$nav_safe_area = set_nav_safe_area($post_id);
						} else {
							$nav_safe_area = 'has-nav-safe-area';
						}
					}
				// Set navigation with safe area end
			// Set body class end
		?>
	</head>
	<body id="body" <?php body_class( array($no_select, $no_product, $nav_safe_area) ); ?>>
		<?php zaxu_navigation(); ?>
		<section class="site-main-container">
			<div class="site-carry">
				<?php
					// Slide start
						if ( is_front_page() && is_home() ) {
							// Default homepage
						} else if ( is_front_page() ) {
							// Static homepage
							$post_id = get_the_ID();
							if ( !post_password_required($post_id) ) {
								zaxu_hero_slide($post_id);
							}
						} else if ( is_home() ) {
							// Blog page
							$post_id = get_option('page_for_posts');
							if ( !post_password_required($post_id) ) {
								zaxu_hero_slide($post_id);
							}
						} else {
							// Other page
							$post_id = get_the_ID();
							if ( $post_id && !post_password_required($post_id) ) {
								zaxu_hero_slide($post_id);
							}
						}
					// Slide end
				?>
				<div id="content" class="site-content">