<?php
/*
 * @Description: Main
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
				// Get post id start
					if ( is_front_page() && is_home() ) {
						// Default homepage
						$post_id = null;
					} else if ( is_front_page() ) {
						// Static homepage
						$post_id = get_the_ID();
					} else if ( is_home() ) {
						// Blog page
						$post_id = get_option('page_for_posts');
					} else {
						// Other page
						$post_id = get_the_ID();
					}
				// Get post id end

				if ( !post_password_required($post_id) ) {
					if ( is_front_page() && is_home() ) {
						// Default homepage
					} else if ( is_front_page() ) {
						// Static homepage
					} else if ( is_home() ) {
						// Blog page
						$post_id = get_option('page_for_posts');
						echo apply_filters( 'the_content', get_post_field('post_content', $post_id) );
						zaxu_page_break_pagination();
					} else {
						// Other page
					}
					
					// Blog style
					$blog_style = get_theme_mod('zaxu_blog_style', 'grid');
					// Blog columns
					$blog_cols = get_theme_mod('zaxu_blog_cols', 'auto');

					if ($blog_style == 'showcase') {
						// Blog quantity
						$posts_per_page = -1;

						// Recommended post
						zaxu_recommended_post();
					} else {
						// Blog quantity
						$posts_per_page = get_theme_mod( 'zaxu_blog_per_page', get_option('posts_per_page') );

						// Recommended post
						zaxu_recommended_post();
						
						// Blog filter start
							if ( have_posts() ) {
								if (get_theme_mod('zaxu_blog_filter', 'text') === 'text') {
									zaxu_post_filter('post', 'text');
								}
								if (get_theme_mod('zaxu_blog_filter', 'text') === 'thumbnail') {
									zaxu_post_filter('post', 'thumbnail');
								}
								wp_reset_postdata();
							}
						// Blog filter end
					}

					// Get post content start
						$args = array(
							'post_type' => 'post',
							'paged' => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
							'posts_per_page' => $posts_per_page,
							'post_status' => 'publish',
							'suppress_filters' => false,
						);

						$all_posts = new WP_Query($args);

						if ( $all_posts->have_posts() ) {
							if ($blog_style == 'list') {
								echo '
									<div class="alignfull">
										<div class="section-inner">
											<section class="post post-article-container list-mode">
								';
							} else if ($blog_style == 'grid') {
								echo '
									<div class="alignfull">
										<div class="section-inner">
											<section class="post post-article-container grid-mode" data-columns="' . $blog_cols . '">
								';
							} else if ($blog_style == 'showcase') {
								echo '
									<section class="post post-article-container showcase-mode alignfull">
										<gallery>
											<ul class="swiper-wrapper">
								';
							}

							// Get post data start
								while ( $all_posts->have_posts() ) {
									$all_posts->the_post();
									zaxu_post_article($blog_style, "post", $post->ID, "normal");
								}
								wp_reset_query();
							// Get post data end

							if ($blog_style == 'list' || $blog_style == 'grid') {
								echo '</section>';
								// Post pagination
								zaxu_post_pagination($all_posts);
								echo '
										</div>
									</div>
								';
							} else if ($blog_style == 'showcase') {
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
					get_the_password_form($post_id);
				}
			?>
		</div>
	</article>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();