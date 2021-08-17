<?php
/*
 * @Description: All pages
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
