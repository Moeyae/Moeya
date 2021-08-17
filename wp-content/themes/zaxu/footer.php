<?php
/*
 * @Description: Footer
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */
if ( !defined('ABSPATH') ) exit;
?>
				</div>
			</div>
			<?php zaxu_footer_info(); ?>
		</section>

		<?php
			zaxu_tabbar();
			zaxu_action();
			zaxu_sharing();

			// Site poster start
				if ( is_front_page() && is_home() ) {
					// Default homepage
					zaxu_site_poster('no_id');
				} else if ( is_front_page() ) {
					// Static homepage
					$post_id = get_the_ID();
					zaxu_site_poster($post_id);
				} else if ( is_home() ) {
					// Blog page
					$post_id = get_option('page_for_posts');
					zaxu_site_poster($post_id);
				} else {
					// Other page
					$post_id = get_the_ID();
					if ($post_id) {
						zaxu_site_poster($post_id);
					} else if ( class_exists('WooCommerce') ) {
						$shop_page_id = intval( get_option('woocommerce_shop_page_id') );
						if ( is_shop() ) {
							zaxu_site_poster($shop_page_id);
						} else {
							zaxu_site_poster('no_id');
						}
					} else {
						zaxu_site_poster('no_id');
					}
				}
			// Site poster end

			zaxu_image_popup();
			zaxu_search("desktop");
			// Sidebar start
				if (get_theme_mod('zaxu_post_widget_sidebar', 'disabled') == 'enabled' && is_singular('post') || get_theme_mod('zaxu_portfolio_widget_sidebar', 'disabled') == 'enabled' && is_singular("portfolio") ) {
					echo '
						<aside class="site-sidebar-container site-overlay-element">
							<div class="site-sidebar-wrap">
								<header class="site-sidebar-header">
									<div class="site-sidebar-title">
										<span class="close"></span>
										<h3>' . __('Sidebar', 'zaxu') . '</h3>
									</div>
								</header>
								<div class="site-sidebar-content">
					';

					$sidebar = dynamic_sidebar('sidebar-main');
					if ($sidebar) {
						$sidebar;
					} else {
						zaxu_no_item_tips( __('Sorry! The widget is not currently available.', 'zaxu') );
					}

					echo '
								</div>
							</div>
						</aside>
					';
				}
			// Sidebar end
			// Comments start
				if ( is_singular('post') || is_singular("portfolio") ) {
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				}
			// Comments end
			// Page loading start
				$page_loading = get_theme_mod('zaxu_page_loading', 'linear');
				echo '<section class="site-loading-container ' . $page_loading . '"></section>';
			// Page loading end
			zaxu_response();
			zaxu_compatible();
			wp_footer();
			zaxu_no_script();
		?>
	</body>
</html>