<?php
/*
 * Template Name: Documentation
 * @Description: Documentation page
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

						if (get_theme_mod('zaxu_dashboard_doc_type', 'disabled') == 'enabled') {
							$defaults = array(
								'include' => 'any',
								'exclude' => '',
								'items' => 10
							);
							
							$args = array();
							
							$args = wp_parse_args($args, $defaults);
							$arranged = array();
					
							$parent_args = array(
								'post_type' => 'docs',
								'parent' => 0,
								'sort_column' => 'menu_order'
							);
					
							if ( 'any' != $args['include'] ) {
								$parent_args['include'] = $args['include'];
							}
					
							if ( !empty( $args['exclude'] ) ) {
								$parent_args['exclude'] = $args['exclude'];
							}
					
							$parent_docs = get_pages($parent_args);
							foreach ($parent_docs as $root) {
								$sections = get_children( array(
									'post_parent' => $root->ID,
									'post_type' => 'docs',
									'post_status' => 'publish',
									'orderby' => 'menu_order',
									'order' => 'ASC',
									'posts_per_page' => (int) $args['items'],
									'suppress_filters' => false,
								) );
								$arranged[] = array(
									'doc' => $root,
									'sections' => $sections
								);
							}

							if ($arranged) {
								echo '
									<div class="alignfull">
										<div class="section-inner">
											<section class="zaxudocs-shortcode-container">
												<ul class="zaxudocs-docs-list">
								';
									foreach ($arranged as $main_doc) {
										$main_doc_link = get_permalink($main_doc['doc']->ID);
										echo '
											<li class="zaxudocs-docs-single">
												<h3>
													<a href="' . $main_doc_link . '" class="ajax-link">' . $main_doc['doc']->post_title . '</a>
												</h3>
										';
										if ( $main_doc['sections'] ) {
											echo '
												<div class="inside">
													<ul class="zaxudocs-doc-sections">
											';
											foreach ($main_doc['sections'] as $section) {
												echo '
													<li>
														<a href="' . get_permalink($section->ID) . '" class="ajax-link">' . $section->post_title . '</a>
													</li>
												';
											}
											echo '
													</ul>
												</div>
											';
										}
										echo '
												<div class="zaxudocs-doc-link">
													<a href="' . $main_doc_link . '" class="ajax-link">' . __("View Details", "zaxu") . '</a>
												</div>
											</li>
										';
									}
								echo '
												</ul>
											</section>
										</div>
									</div>
								';
							}
						} else if ( current_user_can('administrator') ) {
							$query['autofocus[control]'] = 'zaxu_dashboard_doc_type';
							$control_link = add_query_arg( $query, admin_url('customize.php') );
							zaxu_no_item_tips( __('Sorry! Your documentation is disabled.', 'zaxu') .' <a href="' . $control_link . '">' . __('Click here', 'zaxu') . '</a> ' . __('to enable this function.', 'zaxu') );
						}
					} else {
						echo get_the_password_form();
					}
				?>
			</div>
		</article>
	<?php endwhile; ?>
<?php zaxu_wrapper_end(); ?>

<?php
	get_footer();