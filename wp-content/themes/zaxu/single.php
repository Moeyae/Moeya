<?php
/*
 * @Description: All single post
 * @Version: 2.7.2
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

get_header();
?>

<?php zaxu_wrapper_start(); ?>
	<?php
		if ( have_posts() ) {
			the_post();

			if ( is_singular('post') ) {
				// Default post type start
					if (get_theme_mod('zaxu_blog_details_page_style', 'journal') == "journal") {
						// Journal details page start
							$tagElement = null;
							$rating = null;
							$placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
		
							// Get author avatar start
								if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
									$avatar = '<img width="100" height="100" alt="' . get_the_author() . '" src="' . $placeholder_img . '" data-src="' . esc_url( get_avatar_url( get_the_author_meta('ID'), ['size' => '50'] ) ) . '" data-srcset="' . esc_url( get_avatar_url( get_the_author_meta('ID'), ['size' => '100'] ) ) . ' 2x" class="author-image zaxu-lazy" />';
								} else {
									$avatar = '<img src="' . esc_url( get_avatar_url( get_the_author_meta('ID'), ['size' => '50'] ) ) . '" srcset="' . esc_url( get_avatar_url( get_the_author_meta('ID'), ['size' => '100'] ) ) . ' 2x" width="100" height="100" alt="' . get_the_author() . '" class="author-image" />';
								}
							// Get author avatar end
		
							if ( !post_password_required() ) {
								// Get post tag start
									if (get_theme_mod('zaxu_blog_tag', "enabled") == "enabled") {
										$tags = get_the_tags();
										$output = '';
										if ($tags) {
											$output .= '
												<section class="post-tag-container">
													<ul class="post-tag-list">
											';
											foreach($tags as $tag) {
												$output .= '
													<li class="post-tag-item">
														<a href="' . get_term_link($tag) . '" rel="tag" class="post-tag-link ajax-link">' . $tag->name . '</a>
													</li>
												';
											}
											$output .= '
													</ul>
												</section>
											';
										}
										$tagElement = $output;
									}
								// Get post tag end
		
								// Get rating start
									if ( !is_attachment() ) {
										if ( get_theme_mod('zaxu_post_rating') != 'disabled') {
											$rating = thumbs_rating_getlink();
										}
									}
								// Get rating end
							}
		
							echo '
								<article id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article">
									<header class="entry-header">
										<h1 class="entry-title">' . esc_attr( get_the_title() ) . '</h1>
							';
		
							if (get_theme_mod('zaxu_blog_details_page_attr_info', 'enabled') == "enabled") {
								echo '
									<div class="entry-meta">
										<a href="' . get_author_posts_url( get_the_author_meta('ID') ) . '" class="author-link ajax-link">
											<picture class="author-avatar">' . $avatar . '</picture>
											<div class="publish-info">
												<span class="author-name">' . get_the_author() . '</span>
												<time class="publish-date" datetime="' . get_the_time('c') . '" itemprop="datePublished">' . get_the_time("Y-m-d") . '</time>
											</div>
										</a>
										<ul class="analytics-list">
											<li class="analytics-item pageview">' . zaxu_get_pageview( get_the_ID() ) . '</li>
										</ul>
									</div>
								';
							} else {
								echo '
									<time class="entry-date" datetime="' . get_the_time('c') . '" itemprop="datePublished">' . get_the_time("Y-m-d") . '</time>
								';
							}
		
							echo '
								</header>
								<div class="entry-content" itemprop="articleBody">
							';
		
							the_content();
							zaxu_page_break_pagination();
							echo $tagElement . $rating;
		
							echo '
									</div>
									<footer class="entry-footer">' . zaxu_post_navigation("blog") . '</footer>
								</article>
							';
						// Journal details page end
					} else {
						// Feature details page start
							$tagElement = null;
							$rating = null;
		
							if ( !post_password_required() ) {
								// Get post tag start
									if (get_theme_mod('zaxu_blog_tag', "enabled") == "enabled") {
										$tags = get_the_tags();
										$output = '';
										if ($tags) {
											$output .= '
												<section class="post-tag-container">
													<ul class="post-tag-list">
											';
											foreach($tags as $tag) {
												$output .= '
													<li class="post-tag-item">
														<a href="' . get_term_link($tag) . '" rel="tag" class="post-tag-link ajax-link">' . $tag->name . '</a>
													</li>
												';
											}
											$output .= '
													</ul>
												</section>
											';
										}
										$tagElement = $output;
									}
								// Get post tag end
		
								// Get rating start
									if ( !is_attachment() ) {
										if ( get_theme_mod('zaxu_post_rating') != 'disabled') {
											$rating = thumbs_rating_getlink();
										}
									}
								// Get rating end
							}
		
							echo '
								<article id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article">
									<div class="entry-content" itemprop="articleBody">
							';
		
							the_content();
							zaxu_page_break_pagination();
							echo $tagElement . $rating;
		
							echo '
									</div>
									<footer class="entry-footer">' . zaxu_post_navigation("blog") . '</footer>
								</article>
							';
						// Feature details page start
					}
				// Default post type end
			} else {
				// Other custom post type start
					echo '
						<article id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article">
							<div class="entry-content" itemprop="articleBody">
					';
						the_content();
						zaxu_page_break_pagination();

					echo '
							</div>
						</article>
					';
				// Other custom post type end
			}
		}
	?>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();