<?php
/*
 * @Description: 404 page
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

get_header();
?>

<?php zaxu_wrapper_start(); ?>
	<article itemscope itemtype="http://schema.org/Article">
			<div class="entry-content" itemprop="articleBody">
				<?php
					// Get background image start
						$placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
						$bg_img_src = get_theme_mod('zaxu_404_bg');
						
						if ($bg_img_src) {
							if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
								$bg_img = '
									<picture class="not-found-bg-img alignfull">
										<img src="' . $placeholder_img . '" data-src="' . $bg_img_src . '" class="zaxu-lazy" />
									</picture>
								';
							} else {
								$bg_img = '
									<picture class="not-found-bg-img alignfull">
										<img src="' . $bg_img_src . '" />
									</picture>
								';
							}
						} else {
							$bg_img = null;
						}
					// Get background image end

					// Get slogan start
						$slogan = esc_attr( get_theme_mod('zaxu_404_slogan') );
						if (!$slogan) {
							$slogan = esc_html__('The page you&rsquo;re looking for can&rsquo;t be found. Please try again later.', 'zaxu');
						}
					// Get slogan end

					echo $bg_img . '
						<section class="not-found-container text-center">
							<h1>' . __('Oops!', 'zaxu') . '</h1>
							<h4>' . $slogan . '</h4>
							<a class="button button-primary ajax-link" href="' . esc_url( home_url() ) . '">' . __('Back to Home', 'zaxu') . '</a>
						</section>
					';
				?>
			</div>
	</article>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();
