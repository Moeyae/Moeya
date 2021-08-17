<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( !defined('ABSPATH') ) {
	exit;
}
?>

<section class="product-content-container">
	<?php
		zaxu_product_category("desktop");
		zaxu_product_category("mobile");
	?>
	<section class="product-list-container">
		<?php if ( is_shop() || is_product_category() || is_product_tag() ): ?>
			<header class="product-list-header">
				<?php if ( is_product_category() ): ?>
					<!-- Category page -->
					<h2 class="title"><?php echo esc_html__('Category', 'zaxu'); ?>: <?php single_cat_title(); ?></h2>
				<?php elseif ( is_product_tag() ) : ?>
					<!-- Tag page -->
					<h2 class="title"><?php echo esc_html__('Tag', 'zaxu'); ?>: <?php single_tag_title(); ?></h2>
				<?php elseif ( is_shop() ) : ?>
					<!-- Shop page -->
					<h2 class="title" data-original-title="<?php echo esc_html__('All Products', 'zaxu'); ?>" data-category-title="<?php echo esc_html__('Category', 'zaxu'); ?>: "><?php echo esc_html__('All Products', 'zaxu'); ?></h2>
				<?php endif; ?>
			</header>
		<?php endif; ?>
		<?php
			// Product style
			$product_style = get_theme_mod('zaxu_product_style', 'grid'); 
			// Product columns
			$product_cols = get_theme_mod('zaxu_product_cols', 'auto'); 

			if ($product_style == "list") {
				echo '<section class="product post-article-container list-mode">';
			} else if ($product_style == "grid") {
				echo '<section class="product post-article-container grid-mode" data-columns="' . $product_cols . '">';
			}
		?>