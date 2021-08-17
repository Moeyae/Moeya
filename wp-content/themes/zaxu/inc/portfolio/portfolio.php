<?php
/*
 * @Description: Portfolio functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

add_action('init', 'zaxu_add_portfolio_post');
add_action('restrict_manage_posts', 'zaxu_add_portfolio_taxonomy_filters');

function zaxu_add_portfolio_post() {
	// Add custom post type
	$labels = array(
		'name' => __('Portfolio', 'zaxu'),
		'singular_name' => __('Portfolio', 'zaxu'),
		'all_items' => __('All Portfolio', 'zaxu'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'has_archive' => false,
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'author',
			'custom-fields',
			'revisions'
		),
		'capability_type' => 'post',
		'rewrite' => array(
			"slug" => 'portfolio',
			'with_front' => true,
            'pages' => true,
            'feeds' => true,
		),
		'menu_position' => 5,
		'menu_icon' => 'dashicons-portfolio',
		'show_in_rest' => true,
	);
	register_post_type('portfolio', $args);
	
	// Add category
	$args = array(
		'labels' => false,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_tagcloud' => true,
		'hierarchical' => true,
		'rewrite' => array(
			'slug' => 'portfolio-category'
		),
		'query_var' => true,
		'show_in_rest' => true,
	);
	register_taxonomy('portfolio_category', array('portfolio'), $args);

	// Add tag
	$args = array(
		'labels' => false,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => 'portfolio-tag'
		),
		'show_admin_column' => true,
		'query_var' => true,
		'show_in_rest' => true,
	);
	register_taxonomy('portfolio_tag', array('portfolio'), $args);

	flush_rewrite_rules();
}

// Add taxonomy filter
function zaxu_add_portfolio_taxonomy_filters() {
	global $typenow;
	$taxonomies = array('portfolio_category');
	if ($typenow == 'portfolio') {
		foreach ($taxonomies as $tax_slug) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if (count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>$tax_name</option>";
				foreach ($terms as $term) {
					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count . ')</option>';
				}
				echo "</select>";
			}
		}
	}
}
?>