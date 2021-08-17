<?php
/*
 * Template Name: Portfolio
 * @Description: Portfolio page
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

get_header();
?>

<?php zaxu_wrapper_start(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Article">
			<div class="entry-content" itemprop="articleBody">
				<?php
					if ( !post_password_required() ) {
						the_content();
						zaxu_page_break_pagination();
						
						// Portfolio style
						$portfolio_style = get_theme_mod('zaxu_portfolio_style', 'grid');
						// Portfolio columns
						$portfolio_cols = get_theme_mod('zaxu_portfolio_cols', 'auto');

						if ($portfolio_style == 'showcase') {
							// Portfolio quantity
							$posts_per_page = -1;
						} else {
							// Portfolio quantity
							$posts_per_page = get_theme_mod( 'zaxu_portfolio_per_page', get_option('posts_per_page') );

							// Portfolio filter start
								$args = array(
									'post_type' => 'portfolio',
									'post_status' => 'publish',
									'suppress_filters' => false,
								);
								$all_posts = new WP_Query($args);

								if ( $all_posts->have_posts() ) {
									if (get_theme_mod('zaxu_portfolio_filter', 'text') === 'text') {
										zaxu_post_filter('portfolio', 'text');
									}
									if ( get_theme_mod('zaxu_portfolio_filter', 'text' ) === 'thumbnail') {
										zaxu_post_filter('portfolio', 'thumbnail');
									}
									wp_reset_postdata();
								}
							// Portfolio filter end
						}

						// Get post content start
							$args = array(
								'post_type' => 'portfolio',
								'paged' => get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1),
								'posts_per_page' => $posts_per_page,
								'post_status' => 'publish',
								'suppress_filters' => false,
							);
							
							$all_posts = new WP_Query($args);

							if ( $all_posts->have_posts() ) {
								if ($portfolio_style == 'list') {
									echo '
										<div class="alignfull">
											<div class="section-inner">
												<section class="portfolio post-article-container list-mode">
									';
								} else if ($portfolio_style == 'grid') {
									echo '
										<div class="alignfull">
											<div class="section-inner">
												<section class="portfolio post-article-container grid-mode" data-columns="' . $portfolio_cols . '">
									';
								} else if ($portfolio_style == 'showcase') {
									echo '
										<section class="portfolio post-article-container showcase-mode alignfull">
											<gallery>
												<ul class="swiper-wrapper">
									';
								}
								
								// Get post data start
									while ( $all_posts->have_posts() ) {
										$all_posts->the_post();
										zaxu_post_article($portfolio_style, "portfolio", $post->ID, "normal");
									}
									wp_reset_query();
								// Get post data end
								
								if ($portfolio_style == 'list' || $portfolio_style == 'grid') {
									echo '</section>';
									// Post pagination
									zaxu_post_pagination($all_posts);
									echo '
											</div>
										</div>
									';
								} else if ($portfolio_style == 'showcase') {
									echo '
												</ul>
											</gallery>
										</section>
									';
								}
							} else {
								get_template_part('template-parts/content/content', 'none');
							}
						// Get post content end
					} else {
						get_the_password_form();
					}
				?>
			</div>
		</article>
	<?php endwhile; ?>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();