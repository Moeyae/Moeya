<?php
/*
 * @Description: Rating functions
 * @Version: 2.7.0
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */ 

if ( !defined('ABSPATH') ) exit;

function thumbs_rating_getlink($post_ID = '', $type_of_vote = '') {
	$thumb_up_icon = zaxu_icon('thumb_up', 'icon');
	$thumb_down_icon = zaxu_icon('thumb_down', 'icon');
	$post_ID = intval( sanitize_text_field($post_ID) );
	$type_of_vote = intval( sanitize_text_field($type_of_vote) );
	$thumbs_rating_link = "";
	if ($post_ID == 0 || $post_ID == '') {
		$post_ID = get_the_ID();
	}
	$thumbs_rating_up_count = get_post_meta($post_ID, '_thumbs_rating_up', true) != '' ? get_post_meta($post_ID, '_thumbs_rating_up', true) : '0';
	$thumbs_rating_down_count = get_post_meta($post_ID, '_thumbs_rating_down', true) != '' ? get_post_meta($post_ID, '_thumbs_rating_down', true) : '0';
	$link_up = '
		<div class="rating-button rating-like'. ( (isset($thumbs_rating_up_count) && intval($thumbs_rating_up_count) > 0) ? ' rating-voted' : '' ) .'" data-post-id="' . $post_ID . '" data-type="1">
			' . $thumb_up_icon . '
			<span class="badge">' . $thumbs_rating_up_count . '</span>
		</div>
	';
	$link_down = '
		<div class="rating-button rating-dislike'. ( (isset($thumbs_rating_down_count) && intval($thumbs_rating_down_count) > 0) ? ' rating-voted' : '' ) .'" data-post-id="' . $post_ID . '" data-type="2">
			' . $thumb_down_icon . '
			<span class="badge">' . $thumbs_rating_down_count . '</span>
		</div>
	';
	$thumbs_rating_link = '
		<section class="rating-container" id="rating-' . $post_ID . '" data-content-id="' . $post_ID . '">
			<div class="rating-buttons">
	';
		global $post;
		if ($post->post_type == 'portfolio') {
			if (get_theme_mod('zaxu_portfolio_rating') == 'all') {
				$thumbs_rating_link .= $link_up;
				$thumbs_rating_link .= $link_down;
			} elseif (get_theme_mod('zaxu_portfolio_rating') =='like') {
				$thumbs_rating_link .= $link_up;
			} elseif (get_theme_mod('zaxu_portfolio_rating') =='dislike') {
				$thumbs_rating_link .= $link_down;
			} elseif (get_theme_mod('zaxu_portfolio_rating') == '') {
				$thumbs_rating_link .= $link_up;
				$thumbs_rating_link .= $link_down;
			}
		} elseif ($post->post_type == 'post') {
			if (get_theme_mod('zaxu_post_rating') == 'all') {
				$thumbs_rating_link .= $link_up;
				$thumbs_rating_link .= $link_down;
			} elseif (get_theme_mod('zaxu_post_rating') == 'like') {
				$thumbs_rating_link .= $link_up;
			} elseif (get_theme_mod('zaxu_post_rating') == 'dislike') {
				$thumbs_rating_link .= $link_down;
			} elseif (get_theme_mod('zaxu_post_rating') == '') {
				$thumbs_rating_link .= $link_up;
				$thumbs_rating_link .= $link_down;
			}
		}
	$thumbs_rating_link .= '
			</div>
		</section>
	';
	return $thumbs_rating_link;
}

function thumbs_rating_add_vote_callback() {
	$post_ID = intval( $_POST['postid'] );
	$type_of_vote = intval( $_POST['type'] );
	if ($type_of_vote == 1) {
		$meta_name = "_thumbs_rating_up";
	} elseif ($type_of_vote == 2) {
		$meta_name = "_thumbs_rating_down";
	}
	$thumbs_rating_count = get_post_meta($post_ID, $meta_name, true) != '' ? get_post_meta($post_ID, $meta_name, true) : '0';
	$thumbs_rating_count = $thumbs_rating_count + 1;
	update_post_meta($post_ID, $meta_name, $thumbs_rating_count);
	$results = thumbs_rating_getlink($post_ID, $type_of_vote);
	die($results);
}
add_action('wp_ajax_thumbs_rating_add_vote', 'thumbs_rating_add_vote_callback');
add_action('wp_ajax_nopriv_thumbs_rating_add_vote', 'thumbs_rating_add_vote_callback');

//Set rating columns start
	function zaxu_rating_columns($columns) {
		return array_merge($columns,
			array(
				'zaxu_rating' => esc_html__('Rating', 'zaxu')
			)
		);
	}
	// Post
	add_filter('manage_post_posts_columns', 'zaxu_rating_columns');
	// Portfolio
	add_filter('manage_portfolio_posts_columns', 'zaxu_rating_columns');
//Set rating columns end

// Get rating value for columns start
	function zaxu_get_rating_value_for_columns($column, $post_id) {
		// Get value
		$like_value = get_post_meta($post_id, '_thumbs_rating_up', true);
		$dislike_value = get_post_meta($post_id, '_thumbs_rating_down', true);

		switch ($column) {
			case 'zaxu_rating' :
				if ($like_value != '') {
					echo '<span class="dashicons-before dashicons-thumbs-up"></span> ' . $like_value . '<br />';
				} else {
					echo '<span class="dashicons-before dashicons-thumbs-up"></span> 0' . '<br />';
				}
				if ($dislike_value != '') {
					echo '<span class="dashicons-before dashicons-thumbs-down"></span> ' . $dislike_value;
				} else {
					echo '<span class="dashicons-before dashicons-thumbs-down"></span> 0';
				}
			break;
		}
	}
	// Post
	add_action('manage_posts_custom_column', 'zaxu_get_rating_value_for_columns', 10, 2);
	// Page
	// add_action('manage_pages_custom_column', 'zaxu_get_rating_value_for_columns', 10, 2);
// Get rating value for columns end

// Get rating value for meta box start
	function zaxu_rating_meta_box() {
		global $post;
		// Get value
		$like_value = get_post_meta($post->ID, '_thumbs_rating_up', true);
		$dislike_value = get_post_meta($post->ID, '_thumbs_rating_down', true);
		?>
		<div class="acf-field">
			<table style="width: 100%;">
				<tr>
					<td class="zaxu-rating-positive">
						<span class="dashicons dashicons-thumbs-up"></span>
						<?php
							if ($like_value != '') {
								echo $like_value;
							} else {
								echo '0';
							}
						?>
					</td>

					<td class="zaxu-rating-negative" style="text-align: right;">
						<span class="dashicons dashicons-thumbs-down"></span>
						<?php
							if ($dislike_value != '') {
								echo $dislike_value;
							} else {
								echo '0';
							}
						?>
					</td>
				</tr>
			</table>
		</div>
        <?php
	}
	function zaxu_rating_op_menu_meta_box() {
		$post_type = array(
			'post',
			'portfolio'
		);
		add_meta_box(
			'zaxu-rating-meta-box',
			__('Rating', 'zaxu'),
			'zaxu_rating_meta_box',
			$post_type,
			'side',
			'default'
		);
	}
	add_action('add_meta_boxes', 'zaxu_rating_op_menu_meta_box');
// Get rating value for meta box end
?>
