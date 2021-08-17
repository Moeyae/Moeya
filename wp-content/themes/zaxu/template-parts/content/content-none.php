<?php
/*
 * @Description: No content
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

?>

<div class="alignfull">
	<div class="section-inner">
		<?php if ( is_home() && current_user_can('publish_posts') ) : ?>
			<section class="no-post-article-container">
				<h4><?php echo __('You have not published any articles.', 'zaxu'); ?></h4>
				<p class="publish-post"><?php echo __('Ready to publish your first post?', 'zaxu') . ' <a href="' . esc_url( admin_url('post-new.php') ) . '" class="button button-primary button-mini">' . __('Get started here', 'zaxu') . '</a>'; ?></p>
			</section>
		<?php elseif ( is_search() ) : ?>
			<section class="no-post-article-container">
				<p><?php echo __('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zaxu'); ?></p>
				<?php get_search_form(); ?>
			</section>
		<?php else : ?>
			<section class="no-post-article-container">
				<h4><?php echo __('Oops! No articles have been published here.', 'zaxu'); ?></h4>
				<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zaxu'); ?></p>
				<?php get_search_form(); ?>
			</section>
		<?php endif; ?>
	</div>
</div>