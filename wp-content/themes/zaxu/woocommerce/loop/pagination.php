<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( !defined('ABSPATH') ) {
	exit;
}
?>

<section class="post-pagination-container">
	<div class="post-pagination-box">
		<?php
			// Previous button
			$prev_link = get_previous_posts_link("");
			if (!$prev_link) {
				echo '<a class="prev ajax-link"></a>';
			} else {
				echo str_replace('<a','<a class="prev ajax-link"', $prev_link);
			};

			// Next button
			$next_link = get_next_posts_link("");
			if (!$next_link) {
				echo '<a class="next ajax-link"></a>';
			} else {
				echo str_replace('<a','<a class="next ajax-link"', $next_link);
			};
		?>
	</div>
</section>
