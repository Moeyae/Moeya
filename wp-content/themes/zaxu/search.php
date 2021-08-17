<?php
/*
 * @Description: Search result page
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
		<?php zaxu_entry_header(); ?>
		<div class="entry-content" itemprop="articleBody">
			<?php
				// Get post content start
					// Search style
					$search_style = get_theme_mod('zaxu_search_style', 'list');
					// Search columns
					$search_cols = get_theme_mod('zaxu_search_cols', 'auto');
					
					if ( have_posts() ) {
						echo '
							<div class="alignfull">
								<div class="section-inner">
						';
							if ($search_style == 'list') {
								echo '<section class="search post-article-container list-mode">';
							} else if ($search_style == 'grid') {
								echo '<section class="search post-article-container grid-mode" data-columns="' . $search_cols . '">';
							}
							// Get post data start
								while ( have_posts() ) {
									the_post();
									zaxu_post_article($search_style, 'search', $post->ID, "normal");
								}
								wp_reset_postdata();
							// Get post data end

							echo '</section>';
							
							// Post pagination
							zaxu_post_pagination();

						echo '
								</div>
							</div>
						';
					} else {
						get_template_part('template-parts/content/content', 'none');
					}
				// Get post content end
			?>
		</div>
	</article>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();