<?php
/*
 * @Description: Color scheme
 * @Version: 2.7.1
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

function zaxu_color_scheme_value() {
	// Get post id start
		$post_id = get_the_ID();
		if ( is_front_page() && is_home() ) {
			// Default homepage
		} else if ( is_front_page() ) {
			// Static homepage
		} else if ( is_home() ) {
			// Blog page
			$post_id = get_option('page_for_posts');
		} else {
			// Other page
		}
	// Get post id end

	if ($post_id && zaxu_get_field('page-scheme', $post_id) == 1) {
		// Page scheme enable
		if ( is_singular("post") || is_singular("page") || is_singular("portfolio") || is_singular("docs") || is_singular("product") ) {
			// Is Post, Page, Portfolio, Documentation, Product
			$bg_color = zaxu_get_field('page-bg-color');
			$txt_color = zaxu_get_field('page-txt-color');
			$acc_color = zaxu_get_field('page-acc-color');
		} else {
			// Other page
			$bg_color = get_theme_mod('zaxu_bg_color', '#f2f2f2');
			$txt_color = get_theme_mod('zaxu_txt_color', '#333333');
			$acc_color = get_theme_mod('zaxu_acc_color', '#0088cc');
			
			if ( is_front_page() && is_home() ) {
				// Default homepage
			} else if ( is_front_page() ) {
				// Static homepage
			} else if ( is_home() ) {
				// Blog page
				$bg_color = zaxu_get_field('page-bg-color', $post_id);
				$txt_color = zaxu_get_field('page-txt-color', $post_id);
				$acc_color = zaxu_get_field('page-acc-color', $post_id);
			} else {
				// Other page
			}
		}
	} else {
		// Page scheme disable
		$bg_color = get_theme_mod('zaxu_bg_color', '#f2f2f2');
		$txt_color = get_theme_mod('zaxu_txt_color', '#333333');
		$acc_color = get_theme_mod('zaxu_acc_color', '#0088cc');
	}

	$colors = $bg_color . ', ' . $txt_color . ', ' . $acc_color;
	$color_arr = explode(', ', $colors);
	return $color_arr;
}

function zaxu_color_scheme() {
	// Logo start
		$logo = esc_attr( get_theme_mod('zaxu_logo_height', 30) );
	// Logo end

	// Content maximum width start
		$zaxu_site_max_width = get_theme_mod('zaxu_site_max_width', '120rem');
	// Content maximum width end

	$bg_color = zaxu_color_scheme_value()[0];
	$txt_color = zaxu_color_scheme_value()[1];
	$acc_color = zaxu_color_scheme_value()[2];
	
	if (get_theme_mod('zaxu_dynamic_color', 'disabled') == 'disabled') {
		// General color start
			$custom_css = "
				:root {
					--light: {$bg_color};
					--dark: {$bg_color};
				}
				/* Framework start */
					body {
						color: {$txt_color};
						background-color: {$bg_color};
					}
				
					.section-inner,
					.woocommerce-page .site-content {
						max-width: {$zaxu_site_max_width};
					}
				
					/* Smooth Scrollbar start */
						.scrollbar-track .scrollbar-thumb {
							background: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .5) !important;
						}
					/* Smooth Scrollbar end */
				
					/* Loading content start */
						.zaxu-loading-content-container:not(.zaxu-no-more-content):before {
							border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
							border-top-color: {$txt_color};
						}
					/* Loading content start */
				
					/* ZAXU Spinner start */
						.zaxu-spinner-container {
							background-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .5);
						}
						.zaxu-spinner-container:before {
							border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
							border-top-color: {$txt_color};
						}
					/* ZAXU Spinner end */
				
					/* Blocks start */
						/* WP block file start */
							.wp-block-file a:not(.wp-block-file__button) {
								color: {$txt_color} !important;
							}
							.wp-block-file a:not(.wp-block-file__button):after {
								border-top-color: {$bg_color};
								border-bottom-color: {$bg_color};
							}
							.wp-block-file a.wp-block-file__button {
								color: {$bg_color} !important;
								background: {$txt_color};
							}
						/* WP block file end */
				
						/* WP block table start */
							.wp-block-table.is-style-stripes tbody tr:nth-child(odd) {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* WP block table end */
				
						/* WP block page break start */
							.page-links .page-links-box .post-page-numbers {
								color: {$txt_color} !important;
							}
							.page-links .page-links-box .post-page-numbers.current {
								color: {$bg_color} !important;
								background-color: {$txt_color};
							}
							.page-links .page-links-box .post-page-numbers:not(.current):hover {
								color:  {$bg_color} !important;
								background-color: {$txt_color};
							}
						/* WP block page break end */
				
						/* WP block search start */
							.wp-block-search .wp-block-search__button {
								border-color: {$txt_color};
							}
							.wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* WP block search end */
				
						/* ZAXU alert tips start */
							.zaxu-alert-tips-container .zaxu-alert-tips-box {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* ZAXU alert tips end */
				
						/* ZAXU friendly link start */
							.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content {
								background-color: {$bg_color};
							}
							.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content picture {
								background-color: {$bg_color};
							}
							.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content .zaxu-friendly-link-summary {
								color: {$txt_color};
							}
						/* ZAXU friendly link end */
				
						/* ZAXU brand wall start */
							.zaxu-brand-wall-container.river .zaxu-brand-wall-list .zaxu-brand-wall-item picture {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxu-brand-wall-container.river .zaxu-brand-wall-list .zaxu-brand-wall-item .zaxu-brand-wall-name {
								color: {$bg_color};
								background-color: {$txt_color};
							}
						/* ZAXU brand wall end */
				
						/* ZAXU post start */
							.zaxu-post-container .zaxu-post-wrapper .zaxu-post-headline .zaxu-post-link {
								color: {$txt_color};
							}
						/* ZAXU post end */
				
						/* ZAXU price table start */
							.zaxu-price-table-container .zaxu-price-tables .zaxu-price-table .zaxu-price-table-wrap {
								background-color: {$bg_color};
							}
							.zaxu-price-table-container .zaxu-price-tables .zaxu-price-table .zaxu-price-table-wrap .zaxu-price-table-highlight-tag {
								color: {$bg_color};
								background-color: {$txt_color};
							}
						/* ZAXU price table end */
				
						/* ZAXU timeline start */
							.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box {
								background-color: {$bg_color};
							}
							.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head .zaxu-timeline-arrow {
								background-color: {$bg_color};
							}
							.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head:before {
								background-color: {$bg_color};
							}
						/* ZAXU timeline end */
					/* Blocks end */
				/* Framework end */
				
				/* Media element start */
					.wp-playlist {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.wp-playlist.wp-playlist-light,
					.wp-playlist.wp-playlist-dark {
						color: {$txt_color};
						background: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item {
						background: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item .wp-playlist-caption,
					.wp-playlist.wp-playlist-dark .wp-playlist-tracks .wp-playlist-item .wp-playlist-caption {
						color: {$txt_color};
					}
					.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item.wp-playlist-playing,
					.wp-playlist.wp-playlist-dark .wp-playlist-tracks .wp-playlist-item.wp-playlist-playing {
						background: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
					}
				/* Media element end */
				
				/* Link start */
					a[href] {
						color: {$acc_color};
					}
					a[href]:active,
					a[href]:visited {
						color: {$acc_color};
					}
				/* Link end */
				
				/* Input & textarea start */
					input[type='text'],
					input[type='email'],
					input[type='password'],
					input[type='search'],
					input[type='number'],
					input[type='url'],
					input[type='tel'],
					input[type='date'],
					input[type='week'],
					input[type='month'],
					input[type='datetime-local'],
					input[type='time'],
					textarea {
						color: {$txt_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					input[type='text']::-webkit-input-placeholder,
					input[type='email']::-webkit-input-placeholder,
					input[type='password']::-webkit-input-placeholder,
					input[type='search']::-webkit-input-placeholder,
					input[type='number']::-webkit-input-placeholder,
					input[type='url']::-webkit-input-placeholder,
					input[type='tel']::-webkit-input-placeholder,
					input[type='date']::-webkit-input-placeholder,
					input[type='week']::-webkit-input-placeholder,
					input[type='month']::-webkit-input-placeholder,
					input[type='datetime-local']::-webkit-input-placeholder,
					input[type='time']::-webkit-input-placeholder,
					textarea::-webkit-input-placeholder {
						color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
					}
					input[type='text']::-webkit-input-placeholder,
					input[type='text']::-moz-input-placeholder,
					input[type='text']::-ms-input-placeholder,
					input[type='text']:-ms-input-placeholder,
					input[type='text']::placeholder,
					input[type='email']::-webkit-input-placeholder,
					input[type='email']::-moz-input-placeholder,
					input[type='email']::-ms-input-placeholder,
					input[type='email']:-ms-input-placeholder,
					input[type='email']::placeholder,
					input[type='password']::-webkit-input-placeholder,
					input[type='password']::-moz-input-placeholder,
					input[type='password']::-ms-input-placeholder,
					input[type='password']:-ms-input-placeholder,
					input[type='password']::placeholder,
					input[type='search']::-webkit-input-placeholder,
					input[type='search']::-moz-input-placeholder,
					input[type='search']::-ms-input-placeholder,
					input[type='search']:-ms-input-placeholder,
					input[type='search']::placeholder,
					input[type='number']::-webkit-input-placeholder,
					input[type='number']::-moz-input-placeholder,
					input[type='number']::-ms-input-placeholder,
					input[type='number']:-ms-input-placeholder,
					input[type='number']::placeholder,
					input[type='url']::-webkit-input-placeholder,
					input[type='url']::-moz-input-placeholder,
					input[type='url']::-ms-input-placeholder,
					input[type='url']:-ms-input-placeholder,
					input[type='url']::placeholder,
					input[type='tel']::-webkit-input-placeholder,
					input[type='tel']::-moz-input-placeholder,
					input[type='tel']::-ms-input-placeholder,
					input[type='tel']:-ms-input-placeholder,
					input[type='tel']::placeholder,
					input[type='date']::-webkit-input-placeholder,
					input[type='date']::-moz-input-placeholder,
					input[type='date']::-ms-input-placeholder,
					input[type='date']:-ms-input-placeholder,
					input[type='date']::placeholder,
					input[type='week']::-webkit-input-placeholder,
					input[type='week']::-moz-input-placeholder,
					input[type='week']::-ms-input-placeholder,
					input[type='week']:-ms-input-placeholder,
					input[type='week']::placeholder,
					input[type='month']::-webkit-input-placeholder,
					input[type='month']::-moz-input-placeholder,
					input[type='month']::-ms-input-placeholder,
					input[type='month']:-ms-input-placeholder,
					input[type='month']::placeholder,
					input[type='datetime-local']::-webkit-input-placeholder,
					input[type='datetime-local']::-moz-input-placeholder,
					input[type='datetime-local']::-ms-input-placeholder,
					input[type='datetime-local']:-ms-input-placeholder,
					input[type='datetime-local']::placeholder,
					input[type='time']::-webkit-input-placeholder,
					input[type='time']::-moz-input-placeholder,
					input[type='time']::-ms-input-placeholder,
					input[type='time']:-ms-input-placeholder,
					input[type='time']::placeholder,
					textarea::-webkit-input-placeholder,
					textarea::-moz-input-placeholder,
					textarea::-ms-input-placeholder,
					textarea:-ms-input-placeholder,
					textarea::placeholder {
						color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
					}
					input[type='date']::-webkit-datetime-edit-month-field,
					input[type='date']::-webkit-datetime-edit-day-field,
					input[type='date']::-webkit-datetime-edit-year-field {
						color: {$txt_color};
					}
					input[type='text']:focus:not([readonly]),
					input[type='email']:focus:not([readonly]),
					input[type='password']:focus:not([readonly]),
					input[type='search']:focus:not([readonly]),
					input[type='number']:focus:not([readonly]),
					input[type='url']:focus:not([readonly]),
					input[type='tel']:focus:not([readonly]),
					input[type='date']:focus:not([readonly]),
					input[type='week']:focus:not([readonly]),
					input[type='month']:focus:not([readonly]),
					input[type='datetime-local']:focus:not([readonly]),
					input[type='time']:focus:not([readonly]),
					textarea:focus:not([readonly]) {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
					}
				/* Input & textarea end */
				
				/* Range start */
					input[type='range']::-webkit-slider-runnable-track {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					input[type='range']::-moz-range-track {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					input[type='range']::-ms-track {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Range end */
				
				/* File start */
					.input-file-box {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.input-file-box .input-file-current {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* File end */
				
				/* Color start */
					input[type='color'] {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Color end */
				
				/* Progress start */
					progress {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					progress::-webkit-progress-value {
						background-color: {$acc_color};
					}
					progress::-moz-progress-bar {
						background-color: {$acc_color};
					}
					progress:indeterminate {
						background-image: linear-gradient(45deg, {$acc_color}, {$acc_color} 25%, transparent 25%, transparent 50%, {$acc_color} 50%, {$acc_color} 75%, transparent 75%, transparent);
					}
				/* Progress end */
				
				/* Select start */
					select {
						color: {$txt_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					select[size][multiple] option:checked,
					select[size][multiple] option[selected] {
						color: {$bg_color} !important;
						background: {$acc_color} linear-gradient(0deg, {$acc_color} 0%, {$acc_color} 100%) !important;
					}
					select:not([multiple]):not([size]) {
						background-image: url('data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20version%3D%221.1%22%20style%3D%22fill%3A%20rgb(" . zaxu_hex2RGB($txt_color, true) . ")%3B%22%3E%3Cpath%20d%3D%22M8.5%2C9l3.469%2C5L15.5%2C9H8.5z%22%2F%3E%3C%2Fsvg%3E');
					}
					select option {
						background-color: {$bg_color};
					}
				/* Select end */
				
				/* Button start */
					.button,
					input[type='button'],
					input[type='reset'],
					input[type='submit'],
					button[type='submit'] {
						color: {$txt_color} !important;
						border-color: {$txt_color};
					}
				
					.button.button-primary,
					input[type='button'].button-primary,
					input[type='reset'].button-primary,
					input[type='submit'].button-primary,
					button[type='submit'].button-primary {
						color: {$bg_color} !important;
						background-color: {$txt_color};
					}
				
					.button:hover,
					input[type='button']:hover,
					input[type='reset']:hover,
					input[type='submit']:hover,
					button[type='submit']:hover {
						color: {$bg_color} !important;
						background-color: {$txt_color};
					}
				
					.button:hover.button-primary,
					input[type='button']:hover.button-primary,
					input[type='reset']:hover.button-primary,
					input[type='submit']:hover.button-primary,
					button[type='submit']:hover.button-primary {
						color: {$bg_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .8);
					}
				/* Button end */
				
				/* Checkbox start */
					input[type='checkbox'] {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					input[type='checkbox']:checked {
						border-color: {$acc_color};
						background-color: {$acc_color};
					}
					input[type='checkbox']:checked:before,
					input[type='checkbox']:checked:after {
						background-color: {$bg_color};
					}
				/* Checkbox end */
				
				/* Radio start */
					input[type='radio'] {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					input[type='radio']:checked {
						border-color: {$acc_color};
					}
					input[type='radio']:checked:before {
						background-color: {$acc_color};
					}
				/* Radio end */
				
				/* Table start */
					table,
					th,
					td {
						border-color: {$txt_color};
					}
					table thead {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
					}
				/* Table end */
				
				/* Keyboard input start */
					kbd {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Keyboard input end */
				
				/* Fieldset start */
					fieldset {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Fieldset end */
				
				/* Mark start */
					mark {
						color: {$bg_color};
						background-color: {$txt_color};
					}
				/* Mark end */
				
				/* Page loading start */
					.site-loading-container.spinner {
						background-color: {$bg_color};
					}
					.site-loading-container.spinner:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
					.site-loading-container.wipe {
						background-color: {$bg_color};
					}
					.site-loading-container.wipe:before,
					.site-loading-container.wipe:after {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
					}
					.site-loading-container.linear {
						background-color: {$txt_color};
					}
				/* Page loading end */
				
				/* Navigation start */
					.site-navigation-container.hamburger-menu-opened .navigation-holder,
					.site-navigation-container.hamburger-menu-opened .navigation-holder .navigation-logo,
					.site-navigation-container.hamburger-menu-opened .navigation-holder .menu-item a,
					.site-navigation-container:not(.transparent-mode) .navigation-holder,
					.site-navigation-container:not(.transparent-mode) .navigation-holder .navigation-logo,
					.site-navigation-container:not(.transparent-mode) .navigation-holder .menu-item a {
						color: {$txt_color} !important;
					}
					.site-navigation-container.image-logo-enabled .navigation-holder .navigation-logo svg {
						height: {$logo}px !important;
					}
					.site-navigation-container.sticky:after,
					.site-navigation-container.auto:after {
						background: -webkit-gradient(linear, left top, left bottom, from( rgba(" . zaxu_hex2RGB($bg_color, true) . ", .5) ), to( rgba(" . zaxu_hex2RGB($bg_color, true) . ", 0) ));
						background: linear-gradient(to bottom, rgba(" . zaxu_hex2RGB($bg_color, true) . ", .5) 0%, rgba(" . zaxu_hex2RGB($bg_color, true) . ", 0) 100%);
					}
					.site-hamburger-menu-container {
						background-color: {$bg_color};
					}
					.site-hamburger-menu-container .hamburger-menu-content nav .main-menu > .menu-item a {
						color: {$txt_color};
					}
					.site-navigation-container .navigation-holder .content-list .normal-menu-container nav .main-menu .menu-item .sub-menu {
						color: {$bg_color} !important;
						background-color: {$txt_color};
						border-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .1);
					}
					.site-navigation-container .navigation-holder .content-list .normal-menu-container nav .main-menu .menu-item .sub-menu:before {
						background-color: {$txt_color};
						border-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .1);
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content {
						color: {$txt_color} !important;
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: {$bg_color};
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: {$bg_color};
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-loading:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .remove_from_cart_button {
						color: {$txt_color} !important;
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .remove_from_cart_button:after {
						background-color: {$bg_color};
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item a:not(.remove_from_cart_button) {
						color: {$txt_color} !important;
					}
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .attachment-woocommerce_thumbnail,
					.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .woocommerce-placeholder {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Navigation end */
				
				/* Post article start */
					/* No post article start */
						.no-post-article-container {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
					/* No post article end */
				
					/* List mode start */
						.post-article-container.list-mode .list-article .list-content .list-link {
							color: {$txt_color};
						}
						.post-article-container.list-mode .list-article .list-content .list-link .list-info .list-header .sticky {
							color: {$bg_color};
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .7);
						}
					/* List mode end */
				
					/* Grid mode start */
						.post-article-container.grid-mode .tile-article.tile-head-on-bottom .tile-content .tile-featured {
							background-color: {$bg_color};
						}
						.post-article-container.grid-mode .tile-article.tile-head-on-bottom .tile-content .tile-featured .tile-head .sticky {
							color: {$bg_color};
							background-color: {$txt_color};
						}
						.post-article-container.grid-mode .tile-article .tile-content .tile-featured .tile-link {
							color: {$txt_color};
						}
						.post-article-container.grid-mode .tile-article .tile-content .tile-attribution .tile-author .tile-author-link {
							color: {$txt_color};
						}
						.post-article-container.grid-mode .tile-article .tile-content .tile-context {
							background-color: {$bg_color};
						}
						.post-article-container.grid-mode .tile-article .tile-content .tile-context .tile-link {
							color: {$txt_color};
						}
						.post-article-container.grid-mode .tile-article .tile-content .tile-context .tile-link .sticky {
							color: {$bg_color};
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .7);
						}
					/* Grid mode end */
				
					/* Carousel mode start */
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article.carousel-head-on-bottom .carousel-content .carousel-featured {
							background-color: {$bg_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article.carousel-head-on-bottom .carousel-content .carousel-featured .carousel-head .sticky {
							color: {$bg_color};
							background-color: {$txt_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-featured .carousel-link {
							color: {$txt_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-attribution .carousel-author .carousel-author-link {
							color: {$txt_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context {
							background-color: {$bg_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context .carousel-link {
							color: {$txt_color};
						}
						.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context .carousel-link .sticky {
							color: {$bg_color};
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .7);
						}
					/* Carousel mode end */
				
					/* Showcase mode start */
						.post-article-container.showcase-mode gallery .swiper-wrapper .swiper-slide .showcase-article .showcase-link {
							color: {$txt_color};
						}
					/* Showcase mode end */
				/* Post article end */
				
				/* Single start */
					body.single-post .site-main article .entry-header .entry-meta .author-link,
					body.single-portfolio .site-main article .entry-header .entry-meta .author-link,
					body.attachment .site-main article .entry-header .entry-meta .author-link,
					body.single-post .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link,
					body.single-portfolio .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link,
					body.attachment .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link {
						color: {$txt_color};
					}
				/* Single end */
				
				/* Post pagination start */
					.post-pagination-container .post-pagination-box a {
						color: {$bg_color} !important;
						background-color: {$txt_color} !important;
					}
				/* Post pagination end */
				
				/* Sidebar start */
					.site-sidebar-container .site-sidebar-wrap {
						color: {$txt_color};
						background-color: {$bg_color};
					}
					.site-sidebar-container .site-sidebar-wrap .site-sidebar-header {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Sidebar end */
				
				/* Widget start */
					.widget {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.widget .tagcloud a {
						color: {$txt_color} !important;
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					/* WPML start */
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown>ul>.wpml-ls-item .wpml-ls-item-toggle,
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown-click>ul>.wpml-ls-item .wpml-ls-item-toggle {
							color: {$txt_color};
						}
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown>ul>.wpml-ls-item .wpml-ls-sub-menu .wpml-ls-item .wpml-ls-link,
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown-click>ul>.wpml-ls-item .wpml-ls-sub-menu .wpml-ls-item .wpml-ls-link {
							color: {$txt_color};
						}
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-list-vertical .wpml-ls-item .wpml-ls-link {
							color: {$txt_color};
						}
						.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-list-horizontal ul .wpml-ls-item .wpml-ls-link {
							color: {$txt_color};
						}
					/* WPML end */
				/* Widget end */
				
				/* Comments start */
					.site-comments-container .comments-wrap {
						color: {$txt_color};
						background-color: {$bg_color};
					}
					.site-comments-container .comments-wrap .comments-header {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.site-comments-container .comments-wrap .comments-content .comment-respond {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.site-comments-container .comments-wrap .comments-content #comments-list .comment {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.site-comments-container .comments-wrap .comments-content #comments-list .comment article .comment-avatar .by-author .icon svg * {
						fill: {$bg_color};
					}
					.site-comments-container .comments-wrap .comments-content .comment-reply-link,
					.site-comments-container .comments-wrap .comments-content .comment-reply-login,
					.site-comments-container .comments-wrap .comments-content #cancel-comment-reply-link {
						color: {$txt_color} !important;
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
					}
					.site-comments-container .comments-wrap .comments-content .comment-respond:before {
						border-bottom-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.site-comments-container .comments-wrap .comments-content .comment-navigation .nav-links .nav-previous a,
					.site-comments-container .comments-wrap .comments-content .comment-navigation .nav-links .nav-next a {
						color: {$txt_color} !important;
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
					}
					.site-comments-container .comments-wrap .comments-content .main-comments .loading:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
					.site-comments-container .comments-wrap .comments-content #comments-list .comment article .comment-content .comment-text p .comment-author-tag {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
				/* Comments end */
				
				/* Tabbar start */
					.site-tabbar-container .site-tabbar-content {
						background-color: {$txt_color};
						box-shadow: inset 0 0 0 .1rem rgba(" . zaxu_hex2RGB($bg_color, true) . ", .1);
					}
					@supports ((-webkit-backdrop-filter: initial) or (backdrop-filter: initial)) {
						.site-tabbar-container .site-tabbar-content {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .7);
						}
					}
					.site-tabbar-container .site-tabbar-content .tabbar .icon svg * {
						fill: {$bg_color};
					}
					.site-tabbar-container .site-tabbar-content .tabbar-desc-box .icon svg * {
						fill: {$bg_color};
					}
					.site-tabbar-container .site-tabbar-content .tabbar-desc-box .context {
						color: {$bg_color};
					}
				/* Tabbar end */
				
				/* Action start */
					.site-action-container .site-action-wrap .action-button-box ul .action-button {
						background-color: {$bg_color};
					}
					.site-action-container .site-action-wrap .action-button-box ul .action-button:before {
						box-shadow: inset 0 0 0 .1rem rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.site-action-container .site-action-wrap .action-button-box ul .action-button .icon svg * {
						fill: {$txt_color};
					}
				/* Action end */
				
				/* Post password start */
					.post-password-form {
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.post-password-form.loading .action:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
				/* Post password end */
				
				/* Filter carousel start */
					.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide .filter-list-item .name {
						color: {$txt_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
					}
					.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide.current .filter-list-item .name {
						color: {$bg_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .8);
					}
					.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide .filter-list-item .name .badge {
						color: {$bg_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .5);
					}
					.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide.current .filter-list-item .name .badge {
						color: {$txt_color};
						background-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .5);
					}
				/* Filter carousel end */
				
				/* Swiper custom start */
					.swiper-lazy-preloader {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
					.zaxu-swiper-button-prev, .zaxu-swiper-button-next {
						color: {$bg_color};
						background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .7);
					}
					.zaxu-swiper-button-prev:hover, .zaxu-swiper-button-next:hover {
						background-color: {$txt_color};
					}
				/* Swiper custom end */
				
				/* Screen response start */
					.site-response-container {
						color: {$bg_color};
						background-color: {$txt_color};
					}
				/* Screen response end */
				
				/* 404 start */
					.error404 .site-carry .not-found-bg-img:after {
						background-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .8);
					}
				/* 404 end */
				
				/* Footer start */
					.site-footer .footer-social-container ul li a {
						color: {$txt_color};
					}
					.site-footer .footer-info-container .copyright a {
						color: {$txt_color};
					}
					.site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box {
						color: {$txt_color};
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: {$bg_color};
					}
					.site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box:after {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						background-color: {$bg_color};
					}
					.site-footer .footer-statement-container .statement-item {
						color: {$txt_color};
					}
				/* Footer end */

				/* Post navigation start */
					.post-navigation-container .post-navigation-box .post-navigation-head .post-navigation-headline .post-navigation-tagline {
						color: {$bg_color};
						background-color: {$txt_color};
					}
				/* Post navigation end */
				
				/* Old IE Browser start */
					.site-compatible-container {
						background-color: {$bg_color};
					}
				/* Old IE Browser end */
				
				/* WooCommerce start */
					/* Global start */
						.woocommerce-page.page .woocommerce {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-message,
						.woocommerce-info,
						.woocommerce-error {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-message a,
						.woocommerce-info a,
						.woocommerce-error a {
							color: {$txt_color};
						}
					/* Global end */
				
					/* Single product start */
						.single-product .single-product-content-container .entry-summary-container form.cart .single_add_to_cart_button .add-loading {
							background-color: {$txt_color};
						}
						.single-product .single-product-content-container .entry-summary-container form.cart .single_add_to_cart_button .add-loading:before {
							border-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .3);
							border-top-color: {$bg_color};
						}
						.single-product .single-product-content-container .entry-summary-container form.cart.variations_form table tbody tr .value .reset_variations {
							color: {$txt_color};
							border-color: {$txt_color};
						}
						.single-product .single-product-content-container .entry-summary-container form.cart.variations_form table tbody tr .value .reset_variations:hover {
							color: {$bg_color};
							background-color: {$txt_color};
						}
					/* Single product end */
				
					/* Cart start */
						.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-remove .remove {
							color: {$txt_color} !important;
						}
						.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-remove .remove:after {
							background-color: {$bg_color};
						}
						.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-thumbnail .attachment-woocommerce_thumbnail,
						.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-thumbnail .woocommerce-placeholder {
							border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-name a {
							color: {$txt_color} !important;
						}
						.woocommerce-checkout form.woocommerce-checkout .woocommerce-checkout-review-order .woocommerce-checkout-payment .place-order .woocommerce-terms-and-conditions-wrapper .woocommerce-terms-and-conditions {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-cart .cart-collaterals .cart_totals table.shop_table tbody tr.shipping td {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-cart .cart-collaterals .cross-sells {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
					/* Cart end */
				
					/* Checkout start */
						/* Login start */
							.woocommerce-checkout .woocommerce-form-login {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* Login end */
				
						/* Coupon start */
							.woocommerce-checkout .woocommerce-form-coupon {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* Coupon end */
				
						.woocommerce-checkout form.woocommerce-checkout .woocommerce-checkout-review-order table {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
					/* Checkout end */
				
					/* Order received start */
						.woocommerce-order-received .woocommerce-order .woocommerce-bacs-bank-details {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-order-received .woocommerce-order .woocommerce-order-details table {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.woocommerce-order-received .woocommerce-order .woocommerce-order-details table tbody tr td a {
							color: {$txt_color};
						}
						.woocommerce-order-received .woocommerce-order .woocommerce-customer-details address {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
					/* Order received end */
				
					/* My account start */
						/* Login / Register start */
							.woocommerce-account:not(.logged-in) .woocommerce .u-columns#customer_login .col-1,
							.woocommerce-account:not(.logged-in) .woocommerce .u-columns#customer_login .col-2 {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.woocommerce-account:not(.logged-in) .woocommerce .woocommerce-form.login,
							.woocommerce-account:not(.logged-in) .woocommerce .woocommerce-form.register {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* Login / Register end */
				
						/* Logged start */
							/* Account nav start */
								.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li a {
									color: {$txt_color};
								}
								@media only screen and (max-width: 767px) {
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li a {
										background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
									}
								}
								.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
								@media only screen and (max-width: 767px) {
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a {
										color: {$bg_color};
										background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .8);
									}
								}
							/* Account nav end */
				
							/* Orders start */
								.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-content table.woocommerce-orders-table {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
							/* Orders end */
				
							/* Addresses start */
								.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses .woocommerce-Address address {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
							/* Addresses end */
				
							/* Edit address start */
								.woocommerce-account.logged-in.woocommerce-edit-address .woocommerce form[method='post'] .woocommerce-address-fields {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
							/* Edit address end */
				
							/* View order start */
								.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-order-details table {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
								.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-order-details table tbody tr td a {
									color: {$txt_color};
								}
							/* View order end */
				
							/* Customer details start */
								.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-customer-details address {
									background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
								}
							/* Customer details end */
						/* Logged end */
					/* My account end */
				
					/* Store notice start */
						.woocommerce-store-notice {
							color: {$bg_color};
							background-color: {$txt_color};
						}
					/* Store notice end */
				/* WooCommerce end */
				
				/* zaxuDocs start */
					/* Contents start */
						.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .inside .zaxudocs-doc-sections li a {
							color: {$txt_color};
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
						}
						.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .inside .zaxudocs-doc-sections li a:hover {
							background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
						}
						.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single > h3 a {
							color: {$txt_color};
						}
						.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .zaxudocs-doc-link a {
							color: {$bg_color};
							background-color: {$txt_color};
						}
					/* Contents end */
				
					/* Single page start */
						/* Sidebar for desktop start */
							.zaxudocs-single-wrap .zaxudocs-sidebar {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item a {
								color: {$txt_color};
							}
							.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.current_page_item {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.opened {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.page_item_has_children .children > .page_item.current_page_item {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
							}
							.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.page_item_has_children.opened > a {
								border-bottom-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* Sidebar for desktop end */
				
						/* Sidebar for mobile start */
							.zaxudocs-sidebar.mobile {
								color: {$txt_color};
								background-color: {$bg_color};
							}
							.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item a {
								color: {$txt_color};
							}
							.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.current_page_item {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.opened {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.page_item_has_children .children > .page_item.current_page_item {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
							}
							.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.page_item_has_children.opened > a {
								border-bottom-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
						/* Sidebar for mobile end */
				
						/* Content start */
							.zaxudocs-single-wrap .zaxudocs-single-content .zaxudocs-breadcrumb {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content .zaxudocs-breadcrumb li a {
								color: {$txt_color};
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .article-child ul li a {
								color: {$txt_color};
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .article-child ul li a:hover {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .tags-links a {
								color: {$txt_color};
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-prev a,
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-next a {
								color: {$txt_color};
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .1);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-prev a:hover,
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-next a:hover {
								background-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .2);
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-feedback-wrap .vote-link-wrap a {
								color: {$bg_color};
								background-color: {$txt_color};
							}
							.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-contact-modal .zaxudocs-modal-header .zaxudocs-modal-close {
								color: {$txt_color};
							}
						/* Content end */
					/* Single page end */
				/* zaxuDocs end */
				
				/* Contact form 7 start */
					.wpcf7 form.wpcf7-form .wpcf7-form-control-wrap .wpcf7-not-valid-tip {
						color: {$bg_color};
						background-color: {$txt_color};
					}
					.wpcf7 form.wpcf7-form .wpcf7-form-control-wrap .wpcf7-not-valid-tip:before {
						background-color: {$txt_color};
					}
					.wpcf7 form.wpcf7-form .wpcf7-response-output {
						color: {$bg_color};
						background-color: {$txt_color};
					}
					.wpcf7 form.wpcf7-form .ajax-loader {
						background-color: rgba(" . zaxu_hex2RGB($bg_color, true) . ", .2);
					}
					.wpcf7 form.wpcf7-form .ajax-loader:before {
						border-color: rgba(" . zaxu_hex2RGB($txt_color, true) . ", .3);
						border-top-color: {$txt_color};
					}
				/* Contact form 7 end */
			";
		// General color end
	} else {
		$userAgent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
		if ( preg_match('~MSIE|Internet Explorer~i', $userAgent) || (strpos($userAgent, 'Trident/7.0') !== false && strpos($userAgent, 'rv:11.0') !== false) ) {
			// IE browser start
				$custom_css = "
					/* Framework start */
						.section-inner,
						.woocommerce-page .site-content {
							max-width: {$zaxu_site_max_width};
						}

						/* Smooth Scrollbar start */
							.scrollbar-track .scrollbar-thumb {
								background: rgba(0, 0, 0, .5) !important;
							}
						/* Smooth Scrollbar end */
					/* Framework end */

					/* Navigation start */
						.site-navigation-container.image-logo-enabled .navigation-holder .navigation-logo svg {
							height: {$logo}px !important;
						}
					/* Navigation end */
				";
			// IE browser end
		} else {
			// Dynamic color start
				$custom_css = "
					/* Dark mode support start */
						:root {
							--light: #f2f2f2;
							--dark: #000;
							color-scheme: light dark;
						}
					/* Dark mode support end */

					/* Framework start */
						body {
							color: var(--txt-color-100);
							background-color: var(--background-color-100);
						}

						.section-inner,
						.woocommerce-page .site-content {
							max-width: {$zaxu_site_max_width};
						}

						/* Smooth Scrollbar start */
							.scrollbar-track .scrollbar-thumb {
								background: var(--txt-color-50) !important;
							}
						/* Smooth Scrollbar end */

						/* Loading content start */
							.zaxu-loading-content-container:not(.zaxu-no-more-content):before {
								border-color: var(--txt-color-30);
								border-top-color: var(--txt-color-100);
							}
						/* Loading content start */
						
						/* ZAXU Spinner start */
							.zaxu-spinner-container {
								background-color: var(--background-color-50);
							}
							.zaxu-spinner-container:before {
								border-color: var(--txt-color-30);
								border-top-color: var(--txt-color-100);
							}
						/* ZAXU Spinner end */

						/* ZAXU Message Notice start */
							.zaxu-message-notice-container .zaxu-message-notice-item {
								color: var(--txt-color-100);
								background-color: var(--content-background-color-1);
							}
							.zaxu-message-notice-container .zaxu-message-notice-item .icon:before,
							.zaxu-message-notice-container .zaxu-message-notice-item .icon:after {
								background-color: var(--content-background-color-1) !important;
							}
							.zaxu-message-notice-container .zaxu-message-notice-item .close {
								background-color: var(--txt-color-10);
							}
						/* ZAXU Message Notice end */

						/* Blocks start */
							/* WP block file start */
								.wp-block-file a:not(.wp-block-file__button) {
									color: var(--txt-color-100) !important;
								}
								.wp-block-file a:not(.wp-block-file__button):after {
									border-top-color: var(--background-color-100);
									border-bottom-color: var(--background-color-100);
								}
								.wp-block-file a.wp-block-file__button {
									color: var(--background-color-100) !important;
									background: var(--txt-color-100);
								}
							/* WP block file end */

							/* WP block table start */
								.wp-block-table.is-style-stripes tbody tr:nth-child(odd) {
									background-color: var(--txt-color-10);
								}
							/* WP block table end */

							/* WP block page break start */
								.page-links .page-links-box .post-page-numbers {
									color: var(--txt-color-100) !important;
								}
								.page-links .page-links-box .post-page-numbers.current {
									color: var(--background-color-100) !important;
									background-color: var(--txt-color-100);
								}
								.page-links .page-links-box .post-page-numbers:not(.current):hover {
									color: var(--background-color-100) !important;
									background-color: var(--txt-color-100);
								}
							/* WP block page break end */

							/* WP block search start */
								.wp-block-search .wp-block-search__button {
									border-color: var(--txt-color-100);
								}
								.wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper {
									background-color: var(--txt-color-10);
								}
							/* WP block search end */

							/* ZAXU alert tips start */
								.zaxu-alert-tips-container .zaxu-alert-tips-box {
									background-color: var(--txt-color-10);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color) {
									color: var(--alert-tips-title-color);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-error {
									background-color: var(--alert-tips-background-color-error);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-error:after {
									box-shadow: inset 0 0 0 .1rem var(--alert-tips-border-color-error);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-error .icon svg * {
									fill: var(--alert-tips-icon-color-error);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-warning {
									background-color: var(--alert-tips-background-color-warning);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-warning:after {
									box-shadow: inset 0 0 0 .1rem var(--alert-tips-border-color-warning);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-warning .icon svg * {
									fill: var(--alert-tips-icon-color-warning);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-information {
									background-color: var(--alert-tips-background-color-info);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-information:after {
									box-shadow: inset 0 0 0 .1rem var(--alert-tips-border-color-info);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-information .icon svg * {
									fill: var(--alert-tips-icon-color-info);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-success {
									background-color: var(--alert-tips-background-color-success);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-success:after {
									box-shadow: inset 0 0 0 .1rem var(--alert-tips-border-color-success);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color).zaxu-alert-tips-success .icon svg * {
									fill: var(--alert-tips-icon-color-success);
								}

								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color) .zaxu-alert-tips-description .zaxu-alert-tips-title {
									color: var(--alert-tips-title-color);
								}
								.zaxu-alert-tips-container .zaxu-alert-tips-box:not(.dynamic-color) .zaxu-alert-tips-description .zaxu-alert-tips-content {
									color: var(--alert-tips-content-color);
								}
							/* ZAXU alert tips end */

							/* ZAXU friendly link start */
								.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content {
									background-color: var(--background-color-100);
								}
								.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content picture {
									background-color: var(--background-color-100);
								}
								.zaxu-friendly-link-container .zaxu-friendly-link-list .zaxu-friendly-link-item .zaxu-friendly-link-content .zaxu-friendly-link-summary {
									color: var(--txt-color-100);
								}
							/* ZAXU friendly link end */

							/* ZAXU brand wall start */
								.zaxu-brand-wall-container.river .zaxu-brand-wall-list .zaxu-brand-wall-item picture {
									background-color: var(--txt-color-10);
								}
								.zaxu-brand-wall-container.river .zaxu-brand-wall-list .zaxu-brand-wall-item .zaxu-brand-wall-name {
									color: var(--background-color-100);
									background-color: var(--txt-color-100);
								}
							/* ZAXU brand wall end */

							/* ZAXU post start */
								.zaxu-post-container .zaxu-post-wrapper .zaxu-post-headline .zaxu-post-link {
									color: var(--txt-color-100);
								}
							/* ZAXU post end */

							/* ZAXU price table start */
								.zaxu-price-table-container .zaxu-price-tables .zaxu-price-table .zaxu-price-table-wrap {
									background-color: var(--content-background-color-1);
								}
								.zaxu-price-table-container .zaxu-price-tables .zaxu-price-table .zaxu-price-table-wrap:after {
									opacity: .1;
								}
								.zaxu-price-table-container .zaxu-price-tables .zaxu-price-table .zaxu-price-table-wrap .zaxu-price-table-highlight-tag {
									color: var(--background-color-100);
									background-color: var(--txt-color-100);
								}
							/* ZAXU price table end */

							/* ZAXU timeline start */
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box {
									background-color: var(--content-background-color-1);
								}
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box:after {
									opacity: .1;
								}
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head:after {
									opacity: .1;
								}
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head .zaxu-timeline-arrow {
									background-color: var(--content-background-color-1);
								}
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head .zaxu-timeline-arrow:after {
									opacity: .1;
								}
								.zaxu-timeline-container .zaxu-timeline-list .zaxu-timeline-item .zaxu-timeline-box .zaxu-timeline-head:before {
									background-color: var(--background-color-100);
								}
							/* ZAXU timeline end */
						/* Blocks end */
					/* Framework end */

					/* Media element start */
						.wp-playlist {
							border-color: var(--txt-color-10);
						}
						.wp-playlist.wp-playlist-light,
						.wp-playlist.wp-playlist-dark {
							color: var(--txt-color-100);
							background: var(--txt-color-10);
						}
						.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item {
							background: var(--txt-color-10);
						}
						.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item .wp-playlist-caption,
						.wp-playlist.wp-playlist-dark .wp-playlist-tracks .wp-playlist-item .wp-playlist-caption {
							color: var(--txt-color-100);
						}
						.wp-playlist.wp-playlist-light .wp-playlist-tracks .wp-playlist-item.wp-playlist-playing,
						.wp-playlist.wp-playlist-dark .wp-playlist-tracks .wp-playlist-item.wp-playlist-playing {
							background: var(--txt-color-20);
						}
					/* Media element end */

					/* Link start */
						a[href] {
							color: var(--accent-color);
						}
						a[href]:active,
						a[href]:visited {
							color: var(--accent-color);
						}
					/* Link end */

					/* Input & textarea start */
						input[type='text'],
						input[type='email'],
						input[type='password'],
						input[type='search'],
						input[type='number'],
						input[type='url'],
						input[type='tel'],
						input[type='date'],
						input[type='week'],
						input[type='month'],
						input[type='datetime-local'],
						input[type='time'],
						textarea {
							color: var(--txt-color-100);
							background-color: var(--txt-color-10);
						}
						input[type='text']::-webkit-input-placeholder,
						input[type='email']::-webkit-input-placeholder,
						input[type='password']::-webkit-input-placeholder,
						input[type='search']::-webkit-input-placeholder,
						input[type='number']::-webkit-input-placeholder,
						input[type='url']::-webkit-input-placeholder,
						input[type='tel']::-webkit-input-placeholder,
						input[type='date']::-webkit-input-placeholder,
						input[type='week']::-webkit-input-placeholder,
						input[type='month']::-webkit-input-placeholder,
						input[type='datetime-local']::-webkit-input-placeholder,
						input[type='time']::-webkit-input-placeholder,
						textarea::-webkit-input-placeholder {
							color: var(--txt-color-30);
						}
						input[type='text']::-webkit-input-placeholder,
						input[type='text']::-moz-input-placeholder,
						input[type='text']::-ms-input-placeholder,
						input[type='text']:-ms-input-placeholder,
						input[type='text']::placeholder,
						input[type='email']::-webkit-input-placeholder,
						input[type='email']::-moz-input-placeholder,
						input[type='email']::-ms-input-placeholder,
						input[type='email']:-ms-input-placeholder,
						input[type='email']::placeholder,
						input[type='password']::-webkit-input-placeholder,
						input[type='password']::-moz-input-placeholder,
						input[type='password']::-ms-input-placeholder,
						input[type='password']:-ms-input-placeholder,
						input[type='password']::placeholder,
						input[type='search']::-webkit-input-placeholder,
						input[type='search']::-moz-input-placeholder,
						input[type='search']::-ms-input-placeholder,
						input[type='search']:-ms-input-placeholder,
						input[type='search']::placeholder,
						input[type='number']::-webkit-input-placeholder,
						input[type='number']::-moz-input-placeholder,
						input[type='number']::-ms-input-placeholder,
						input[type='number']:-ms-input-placeholder,
						input[type='number']::placeholder,
						input[type='url']::-webkit-input-placeholder,
						input[type='url']::-moz-input-placeholder,
						input[type='url']::-ms-input-placeholder,
						input[type='url']:-ms-input-placeholder,
						input[type='url']::placeholder,
						input[type='tel']::-webkit-input-placeholder,
						input[type='tel']::-moz-input-placeholder,
						input[type='tel']::-ms-input-placeholder,
						input[type='tel']:-ms-input-placeholder,
						input[type='tel']::placeholder,
						input[type='date']::-webkit-input-placeholder,
						input[type='date']::-moz-input-placeholder,
						input[type='date']::-ms-input-placeholder,
						input[type='date']:-ms-input-placeholder,
						input[type='date']::placeholder,
						input[type='week']::-webkit-input-placeholder,
						input[type='week']::-moz-input-placeholder,
						input[type='week']::-ms-input-placeholder,
						input[type='week']:-ms-input-placeholder,
						input[type='week']::placeholder,
						input[type='month']::-webkit-input-placeholder,
						input[type='month']::-moz-input-placeholder,
						input[type='month']::-ms-input-placeholder,
						input[type='month']:-ms-input-placeholder,
						input[type='month']::placeholder,
						input[type='datetime-local']::-webkit-input-placeholder,
						input[type='datetime-local']::-moz-input-placeholder,
						input[type='datetime-local']::-ms-input-placeholder,
						input[type='datetime-local']:-ms-input-placeholder,
						input[type='datetime-local']::placeholder,
						input[type='time']::-webkit-input-placeholder,
						input[type='time']::-moz-input-placeholder,
						input[type='time']::-ms-input-placeholder,
						input[type='time']:-ms-input-placeholder,
						input[type='time']::placeholder,
						textarea::-webkit-input-placeholder,
						textarea::-moz-input-placeholder,
						textarea::-ms-input-placeholder,
						textarea:-ms-input-placeholder,
						textarea::placeholder {
							color: var(--txt-color-30);
						}
						input[type='date']::-webkit-datetime-edit-month-field,
						input[type='date']::-webkit-datetime-edit-day-field,
						input[type='date']::-webkit-datetime-edit-year-field {
							color: var(--txt-color-100);
						}
						input[type='text']:focus:not([readonly]),
						input[type='email']:focus:not([readonly]),
						input[type='password']:focus:not([readonly]),
						input[type='search']:focus:not([readonly]),
						input[type='number']:focus:not([readonly]),
						input[type='url']:focus:not([readonly]),
						input[type='tel']:focus:not([readonly]),
						input[type='date']:focus:not([readonly]),
						input[type='week']:focus:not([readonly]),
						input[type='month']:focus:not([readonly]),
						input[type='datetime-local']:focus:not([readonly]),
						input[type='time']:focus:not([readonly]),
						textarea:focus:not([readonly]) {
							background-color: var(--txt-color-20);
						}
					/* Input & textarea end */

					/* Range start */
						input[type='range']::-webkit-slider-runnable-track {
							background-color: var(--txt-color-10);
						}
						input[type='range']::-moz-range-track {
							background-color: var(--txt-color-10);
						}
						input[type='range']::-ms-track {
							background-color: var(--txt-color-10);
						}
					/* Range end */

					/* File start */
						.input-file-box {
							border-color: var(--txt-color-20);
							background-color: var(--txt-color-10);
						}
						.input-file-box .input-file-current {
							background-color: var(--txt-color-10);
						}
					/* File end */

					/* Color start */
						input[type='color'] {
							background-color: var(--txt-color-10);
						}
					/* Color end */

					/* Progress start */
						progress {
							background-color: var(--txt-color-10);
						}
						progress::-webkit-progress-value {
							background-color: var(--accent-color);
						}
						progress::-moz-progress-bar {
							background-color: var(--accent-color);
						}
						progress:indeterminate {
							background-image: linear-gradient(45deg, var(--accent-color), var(--accent-color) 25%, transparent 25%, transparent 50%, var(--accent-color) 50%, var(--accent-color) 75%, transparent 75%, transparent);
						}
					/* Progress end */

					/* Select start */
						select {
							color: var(--txt-color-100);
							background-color: var(--txt-color-10);
						}
						select[size][multiple] option:checked,
						select[size][multiple] option[selected] {
							color: var(--background-color-100) !important;
							background: var(--accent-color) linear-gradient(0deg, var(--accent-color) 0%, var(--accent-color) 100%) !important;
						}
						select:not([multiple]):not([size]) {
							background-image: url('data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20version%3D%221.1%22%20style%3D%22fill%3A%20%23333333%3B%22%3E%3Cpath%20d%3D%22M8.5%2C9l3.469%2C5L15.5%2C9H8.5z%22%2F%3E%3C%2Fsvg%3E');
						}
						@media (prefers-color-scheme: dark) {
							select:not([multiple]):not([size]) {
								background-image: url('data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20version%3D%221.1%22%20style%3D%22fill%3A%20%23ffffff%3B%22%3E%3Cpath%20d%3D%22M8.5%2C9l3.469%2C5L15.5%2C9H8.5z%22%2F%3E%3C%2Fsvg%3E');
							}
						}
						select option {
							background-color: var(--background-color-100);
						}
					/* Select end */

					/* Button start */
						.button,
						input[type='button'],
						input[type='reset'],
						input[type='submit'],
						button[type='submit'] {
							color: var(--txt-color-100) !important;
							border-color: var(--txt-color-100);
						}

						.button.button-primary,
						input[type='button'].button-primary,
						input[type='reset'].button-primary,
						input[type='submit'].button-primary,
						button[type='submit'].button-primary {
							color: var(--background-color-100) !important;
							background-color: var(--txt-color-100);
						}

						.button:hover,
						input[type='button']:hover,
						input[type='reset']:hover,
						input[type='submit']:hover,
						button[type='submit']:hover {
							color: var(--background-color-100) !important;
							background-color: var(--txt-color-100);
						}

						.button:hover.button-primary,
						input[type='button']:hover.button-primary,
						input[type='reset']:hover.button-primary,
						input[type='submit']:hover.button-primary,
						button[type='submit']:hover.button-primary {
							color: var(--background-color-100);
							background-color: var(--txt-color-80);
						}
					/* Button end */

					/* Checkbox start */
						input[type='checkbox'] {
							border-color: var(--txt-color-10);
							background-color: var(--txt-color-10);
						}
						input[type='checkbox']:checked {
							border-color: var(--accent-color);
							background-color: var(--accent-color);
						}
						input[type='checkbox']:checked:before,
						input[type='checkbox']:checked:after {
							background-color: var(--background-color-100);
						}
					/* Checkbox end */

					/* Radio start */
						input[type='radio'] {
							border-color: var(--txt-color-10);
							background-color: var(--txt-color-10);
						}
						input[type='radio']:checked {
							border-color: var(--accent-color);
						}
						input[type='radio']:checked:before {
							background-color: var(--accent-color);
						}
					/* Radio end */

					/* Table start */
						table,
						th,
						td {
							border-color: var(--txt-color-100);
						}
						table thead {
							background-color: var(--txt-color-20);
						}
					/* Table end */

					/* Keyboard input start */
						kbd {
							border-color: var(--txt-color-10);
							background-color: var(--txt-color-10);
						}
					/* Keyboard input end */

					/* Fieldset start */
						fieldset {
							border-color: var(--txt-color-10);
						}
					/* Fieldset end */

					/* Mark start */
						mark {
							color: var(--background-color-100);
							background-color: var(--txt-color-100);
						}
					/* Mark end */

					/* Page loading start */
						.site-loading-container.spinner {
							background-color: var(--background-color-100);
						}
						.site-loading-container.spinner:before {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
						.site-loading-container.wipe {
							background-color: var(--background-color-100);
						}
						.site-loading-container.wipe:before,
						.site-loading-container.wipe:after {
							background-color: var(--txt-color-30);
						}
						.site-loading-container.linear {
							background-color: var(--txt-color-100);
						}
					/* Page loading end */

					/* Navigation start */
						.site-navigation-container.hamburger-menu-opened .navigation-holder,
						.site-navigation-container.hamburger-menu-opened .navigation-holder .navigation-logo,
						.site-navigation-container.hamburger-menu-opened .navigation-holder .menu-item a,
						.site-navigation-container:not(.transparent-mode) .navigation-holder,
						.site-navigation-container:not(.transparent-mode) .navigation-holder .navigation-logo,
						.site-navigation-container:not(.transparent-mode) .navigation-holder .menu-item a {
							color: var(--txt-color-100) !important;
						}
						.site-navigation-container.image-logo-enabled .navigation-holder .navigation-logo svg {
							height: {$logo}px !important;
						}
						.site-navigation-container.sticky:after,
						.site-navigation-container.auto:after {
							background: -webkit-gradient(linear, left top, left bottom, from( var(--background-color-50) ), to( var(--background-color-0) ));
							background: linear-gradient(to bottom, var(--background-color-50) 0%, var(--background-color-0) 100%);
						}
						.site-hamburger-menu-container {
							background-color: var(--background-color-100);
						}
						.site-hamburger-menu-container .hamburger-menu-content nav .main-menu > .menu-item a {
							color: var(--txt-color-100);
						}
						.site-navigation-container .navigation-holder .content-list .normal-menu-container nav .main-menu .menu-item .sub-menu {
							color: var(--background-color-100) !important;
							background-color: var(--txt-color-100);
							border-color: var(--background-color-10);
						}
						.site-navigation-container .navigation-holder .content-list .normal-menu-container nav .main-menu .menu-item .sub-menu:before {
							background-color: var(--txt-color-100);
							border-color: var(--background-color-10);
						}
						.site-search-container.desktop .searchform input {
							color: var(--txt-color-100) !important;
							background-color: var(--content-background-color-1) !important;
							border-color: var(--txt-color-10);
						}
						.site-search-container.desktop .searchform input::-webkit-input-placeholder {
							color: var(--txt-color-50) !important;
						}
						.site-search-container.desktop .searchform input::-webkit-input-placeholder,
						.site-search-container.desktop .searchform input::-moz-input-placeholder,
						.site-search-container.desktop .searchform input::-ms-input-placeholder,
						.site-search-container.desktop .searchform input:-ms-input-placeholder,
						.site-search-container.desktop .searchform input::placeholder { 
							color: var(--txt-color-50) !important;
						}
						.site-search-container.desktop .searchform .search-icon:before {
							border-color: var(--txt-color-100);
						}
						.site-search-container.desktop .searchform .search-icon:after {
							background-color: var(--txt-color-100);
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content {
							color: var(--txt-color-100) !important;
							border-color: var(--txt-color-10);
							background-color: var(--content-background-color-1);
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content:before {
							border-color: var(--txt-color-10);
							background-color: var(--content-background-color-1);
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-loading:before {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .remove_from_cart_button {
							color: var(--txt-color-100) !important;
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .remove_from_cart_button:after {
							background-color: var(--background-color-100);
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item a:not(.remove_from_cart_button) {
							color: var(--txt-color-100) !important;
						}
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .attachment-woocommerce_thumbnail,
						.site-navigation-container .navigation-holder .content-list .content-item.shopping-bag-toggle .shopping-bag-content .shopping-bag-list .woocommerce-mini-cart .woocommerce-mini-cart-item .woocommerce-placeholder {
							border-color: var(--txt-color-10);
						}
					/* Navigation end */

					/* Post article start */
						/* No post article start */
							.no-post-article-container {
								background-color: var(--txt-color-10);
							}
						/* No post article end */

						/* List mode start */
							.post-article-container.list-mode .list-article .list-content .list-link {
								color: var(--txt-color-100);
							}
							.post-article-container.list-mode .list-article .list-content .list-link .list-info .list-header .sticky {
								color: var(--background-color-100);
								background-color: var(--txt-color-70);
							}
						/* List mode end */

						/* Grid mode start */
							.post-article-container.grid-mode .tile-article.tile-head-on-bottom .tile-content .tile-featured {
								background-color: var(--content-background-color-1);
							}
							.post-article-container.grid-mode .tile-article.tile-head-on-bottom .tile-content .tile-featured .tile-head .sticky {
								color: var(--background-color-100);
								background-color: var(--txt-color-100);
							}
							.post-article-container.grid-mode .tile-article .tile-content .tile-featured .tile-link {
								color: var(--txt-color-100);
							}
							.post-article-container.grid-mode .tile-article .tile-content .tile-attribution .tile-author .tile-author-link {
								color: var(--txt-color-100);
							}
							.post-article-container.grid-mode .tile-article .tile-content .tile-context {
								background-color: var(--content-background-color-1);
							}
							.post-article-container.grid-mode .tile-article .tile-content .tile-context .tile-link {
								color: var(--txt-color-100);
							}
							.post-article-container.grid-mode .tile-article .tile-content .tile-context .tile-link .sticky {
								color: var(--background-color-100);
								background-color: var(--txt-color-70);
							}
						/* Grid mode end */

						/* Showcase mode start */
							.post-article-container.showcase-mode gallery .swiper-wrapper .swiper-slide .showcase-article .showcase-link {
								color: var(--txt-color-100);
							}
						/* Showcase mode end */

						/* Carousel mode start */
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article.carousel-head-on-bottom .carousel-content .carousel-featured {
								background-color: var(--content-background-color-1);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article.carousel-head-on-bottom .carousel-content .carousel-featured .carousel-head .sticky {
								color: var(--background-color-100);
								background-color: var(--txt-color-100);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-featured .carousel-link {
								color: var(--txt-color-100);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-attribution .carousel-author .carousel-author-link {
								color: var(--txt-color-100);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context {
								background-color: var(--background-color-100);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context .carousel-link {
								color: var(--txt-color-100);
							}
							.post-article-container.carousel-mode .swiper-wrapper .swiper-slide .carousel-article .carousel-content .carousel-context .carousel-link .sticky {
								color: var(--background-color-100);
								background-color: var(--txt-color-70);
							}
						/* Carousel mode end */
					/* Post article end */

					/* Single start */
						body.single-post .site-main article .entry-header .entry-meta .author-link,
						body.single-portfolio .site-main article .entry-header .entry-meta .author-link,
						body.attachment .site-main article .entry-header .entry-meta .author-link,
						body.single-post .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link,
						body.single-portfolio .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link,
						body.attachment .site-main article .entry-content .post-tag-container .post-tag-list .post-tag-item .post-tag-link {
							color: var(--txt-color-100);
						}
					/* Single end */

					/* Sidebar start */
						.site-sidebar-container .site-sidebar-wrap {
							color: var(--txt-color-100);
							background-color: var(--content-background-color-1);
						}
						.site-sidebar-container .site-sidebar-wrap .site-sidebar-header {
							background-color: var(--txt-color-10);
						}
					/* Sidebar end */

					/* Widget start */
						.widget {
							background-color: var(--txt-color-10);
						}
						.widget .tagcloud a {
							color: var(--txt-color-100) !important;
							background-color: var(--txt-color-10);
						}
						/* WPML start */
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown>ul>.wpml-ls-item .wpml-ls-item-toggle,
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown-click>ul>.wpml-ls-item .wpml-ls-item-toggle {
								color: var(--txt-color-100);
							}
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown>ul>.wpml-ls-item .wpml-ls-sub-menu .wpml-ls-item .wpml-ls-link,
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-dropdown-click>ul>.wpml-ls-item .wpml-ls-sub-menu .wpml-ls-item .wpml-ls-link {
								color: var(--txt-color-100);
							}
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-list-vertical .wpml-ls-item .wpml-ls-link {
								color: var(--txt-color-100);
							}
							.widget.widget_icl_lang_sel_widget .wpml-ls-legacy-list-horizontal ul .wpml-ls-item .wpml-ls-link {
								color: var(--txt-color-100);
							}
						/* WPML end */
					/* Widget end */

					/* Comments start */
						.site-comments-container .comments-wrap {
							color: var(--txt-color-100);
							background-color: var(--content-background-color-1);
						}
						.site-comments-container .comments-wrap .comments-header {
							background-color: var(--txt-color-10);
						}
						.site-comments-container .comments-wrap .comments-content .comment-respond {
							background-color: var(--txt-color-10);
						}
						.site-comments-container .comments-wrap .comments-content #comments-list .comment {
							background-color: var(--txt-color-10);
						}
						.site-comments-container .comments-wrap .comments-content #comments-list .comment article .comment-avatar .by-author .icon svg * {
							fill: var(--background-color-100);
						}
						.site-comments-container .comments-wrap .comments-content .comment-reply-link,
						.site-comments-container .comments-wrap .comments-content .comment-reply-login,
						.site-comments-container .comments-wrap .comments-content #cancel-comment-reply-link {
							color: var(--txt-color-100) !important;
							background-color: var(--txt-color-20);
						}
						.site-comments-container .comments-wrap .comments-content .comment-respond:before {
							border-bottom-color: var(--txt-color-10);
						}
						.site-comments-container .comments-wrap .comments-content .comment-navigation .nav-links .nav-previous a,
						.site-comments-container .comments-wrap .comments-content .comment-navigation .nav-links .nav-next a {
							color: var(--txt-color-100) !important;
							background-color: var(--txt-color-20);
						}
						.site-comments-container .comments-wrap .comments-content .main-comments .loading:before {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
						.site-comments-container .comments-wrap .comments-content #comments-list .comment article .comment-content .comment-text p .comment-author-tag {
							background-color: var(--txt-color-10);
						}
					/* Comments end */

					/* Tabbar start */
						.site-tabbar-container .site-tabbar-content {
							background-color: var(--txt-color-100);
							box-shadow: inset 0 0 0 .1rem var(--background-color-10);
						}
						@supports ((-webkit-backdrop-filter: initial) or (backdrop-filter: initial)) {
							.site-tabbar-container .site-tabbar-content {
								background-color: var(--txt-color-70);
							}
						}
						.site-tabbar-container .site-tabbar-content .tabbar .icon svg * {
							fill: var(--background-color-100);
						}
						.site-tabbar-container .site-tabbar-content .tabbar-desc-box .icon svg * {
							fill: var(--background-color-100);
						}
						.site-tabbar-container .site-tabbar-content .tabbar-desc-box .context {
							color: var(--background-color-100);
						}
					/* Tabbar end */

					/* Action start */
						.site-action-container .site-action-wrap .action-button-box ul .action-button {
							background-color: var(--content-background-color-1);
						}
						.site-action-container .site-action-wrap .action-button-box ul .action-button:before {
							box-shadow: inset 0 0 0 .1rem var(--txt-color-10);
						}
						.site-action-container .site-action-wrap .action-button-box ul .action-button .icon svg * {
							fill: var(--txt-color-100);
						}
					/* Action end */

					/* Post password start */
						.post-password-form {
							background-color: var(--txt-color-10);
						}
						.post-password-form.loading .action:before {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
					/* Post password end */

					/* Post pagination start */
						.post-pagination-container .post-pagination-box a {
							color: var(--background-color-100) !important;
							background-color: var(--txt-color-100) !important;
						}
					/* Post pagination end */

					/* Filter carousel start */
						.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide .filter-list-item .name {
							color: var(--txt-color-100);
							background-color: var(--txt-color-10);
						}
						.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide.current .filter-list-item .name {
							color: var(--background-color-100);
							background-color: var(--txt-color-80);
						}
						.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide .filter-list-item .name .badge {
							color: var(--background-color-100);
							background-color: var(--txt-color-50);
						}
						.filter-carousel-container.text .filter-carousel .swiper-wrapper .swiper-slide.current .filter-list-item .name .badge {
							color: var(--txt-color-100);
							background-color: var(--background-color-50);
						}
					/* Filter carousel end */

					/* Swiper custom start */
						.swiper-lazy-preloader {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
						.zaxu-swiper-button-prev, .zaxu-swiper-button-next {
							color: var(--background-color-100);
							background-color: var(--txt-color-70);
						}
						.zaxu-swiper-button-prev:hover, .zaxu-swiper-button-next:hover {
							background-color: var(--txt-color-100);
						}
					/* Swiper custom end */

					/* Screen response start */
						.site-response-container {
							color: var(--txt-color-100);
							background-color: var(--background-color-100);
						}
					/* Screen response end */

					/* 404 start */
						.error404 .site-carry .not-found-bg-img:after {
							background-color: var(--background-color-80);
						}
					/* 404 end */

					/* Site sharing start */
						.site-sharing-container .site-sharing-content {
							color: var(--txt-color-100);
							background-color: var(--content-background-color-1);
						}
						.site-poster-container .site-poster-content .card {
							color: var(--txt-color-100);
							background-color: var(--content-background-color-1);
						}
						.site-poster-container .site-poster-content .card .loading {
							border: solid .3rem var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
					/* Site sharing end */

					/* Footer start */
						.site-footer .footer-social-container ul li a {
							color: var(--txt-color-100);
						}
						.site-footer .footer-info-container .copyright a {
							color: var(--txt-color-100);
						}
						.site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box {
							color: var(--txt-color-100);
							border-color: var(--txt-color-10);
							background-color: var(--content-background-color-1);
						}
						.site-footer .footer-info-container .lang-switcher-container .lang-switcher-list-box:after {
							border-color: var(--txt-color-10);
							background-color: var(--content-background-color-1);
						}
						.site-footer .footer-statement-container .statement-item {
							color: var(--txt-color-100);
						}
					/* Footer end */

					/* Post navigation start */
						.post-navigation-container .post-navigation-box .post-navigation-head .post-navigation-headline .post-navigation-tagline {
							color: var(--background-color-100);
							background-color: var(--txt-color-100);
						}
					/* Post navigation end */

					/* Old IE Browser start */
						.site-compatible-container {
							background-color: var(--background-color-100);
						}
					/* Old IE Browser end */

					/* Highlight code start */
						code,
						pre {
							color: var(--highlight-code-txt-color);
							background-color: var(--highlight-code-background-color);
						}
						code:not(.hljs) {
							border-color: var(--highlight-code-border-color);
						}
						pre {
							border-color: var(--highlight-code-border-color);
						}
						.hljs-ln-numbers {
							color: var(--highlight-code-number-txt-color);
							border-right-color: var(--highlight-code-border-color) !important;
						}
						
						.hljs {
							background-color: var(--highlight-code-background-color);
						}
						.hljs,
						.hljs-tag,
						.hljs-subst {
							color: var(--highlight-code-txt-color);
						}
						.hljs-strong,
						.hljs-emphasis {
							color: var(--highlight-code-txt-color-1);
						}
						.hljs-bullet,
						.hljs-quote,
						.hljs-number,
						.hljs-regexp,
						.hljs-literal,
						.hljs-link {
							color: var(--highlight-code-txt-color-2);
						}
						.hljs-code,
						.hljs-title,
						.hljs-section,
						.hljs-selector-class {
							color: var(--highlight-code-txt-color-3);
						}
						.hljs-keyword,
						.hljs-selector-tag,
						.hljs-name,
						.hljs-attr {
							color: var(--highlight-code-txt-color-4);
						}
						.hljs-symbol,
						.hljs-attribute {
							color: var(--highlight-code-txt-color-5);
						}
						.hljs-params,
						.hljs-class .hljs-title {
							color: var(--highlight-code-txt-color);
						}
						.hljs-string,
						.hljs-type,
						.hljs-built_in,
						.hljs-builtin-name,
						.hljs-selector-id,
						.hljs-selector-attr,
						.hljs-selector-pseudo,
						.hljs-addition,
						.hljs-variable,
						.hljs-template-variable {
							color: var(--highlight-code-txt-color-6);
						}
						.hljs-comment,
						.hljs-deletion,
						.hljs-meta {
							color: var(--highlight-code-txt-color-7);
						}
					/* Highlight code end */

					/* WooCommerce start */
						/* Global start */
							.woocommerce-page.page .woocommerce {
								background-color: var(--txt-color-10);
							}
							.woocommerce-message,
							.woocommerce-info,
							.woocommerce-error {
								background-color: var(--txt-color-10);
							}
							.woocommerce-message a,
							.woocommerce-info a,
							.woocommerce-error a {
								color: var(--txt-color-100);
							}
						/* Global end */

						/* Single product start */
							.single-product .single-product-content-container .entry-summary-container form.cart .single_add_to_cart_button .add-loading {
								background-color: var(--txt-color-100);
							}
							.single-product .single-product-content-container .entry-summary-container form.cart .single_add_to_cart_button .add-loading:before {
								border-color: var(--background-color-30);
								border-top-color: var(--background-color-100);
							}
							.single-product .single-product-content-container .entry-summary-container form.cart.variations_form table tbody tr .value .reset_variations {
								color: var(--txt-color-100);
								border-color: var(--txt-color-100);
							}
							.single-product .single-product-content-container .entry-summary-container form.cart.variations_form table tbody tr .value .reset_variations:hover {
								color: var(--background-color-100);
								background-color: var(--txt-color-100);
							}
						/* Single product end */

						/* Cart start */
							.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-remove .remove {
								color: var(--txt-color-100) !important;
							}
							.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-remove .remove:after {
								background-color: var(--background-color-100);
							}
							.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-thumbnail .attachment-woocommerce_thumbnail,
							.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-thumbnail .woocommerce-placeholder {
								border-color: var(--txt-color-10);
							}
							.woocommerce-cart .woocommerce-cart-form table tbody tr.cart_item .product-name a {
								color: var(--txt-color-100) !important;
							}
							.woocommerce-checkout form.woocommerce-checkout .woocommerce-checkout-review-order .woocommerce-checkout-payment .place-order .woocommerce-terms-and-conditions-wrapper .woocommerce-terms-and-conditions {
								background-color: var(--txt-color-10);
							}
							.woocommerce-cart .cart-collaterals .cart_totals table.shop_table tbody tr.shipping td {
								background-color: var(--txt-color-10);
							}
							.woocommerce-cart .cart-collaterals .cross-sells {
								background-color: var(--txt-color-10);
							}
						/* Cart end */

						/* Checkout start */
							/* Login start */
								.woocommerce-checkout .woocommerce-form-login {
									background-color: var(--txt-color-10);
								}
							/* Login end */

							/* Coupon start */
								.woocommerce-checkout .woocommerce-form-coupon {
									background-color: var(--txt-color-10);
								}
							/* Coupon end */

							.woocommerce-checkout form.woocommerce-checkout .woocommerce-checkout-review-order table {
								background-color: var(--txt-color-10);
							}
						/* Checkout end */

						/* Order received start */
							.woocommerce-order-received .woocommerce-order .woocommerce-bacs-bank-details {
								background-color: var(--txt-color-10);
							}
							.woocommerce-order-received .woocommerce-order .woocommerce-order-details table {
								background-color: var(--txt-color-10);
							}
							.woocommerce-order-received .woocommerce-order .woocommerce-order-details table tbody tr td a {
								color: var(--txt-color-100);
							}
							.woocommerce-order-received .woocommerce-order .woocommerce-customer-details address {
								background-color: var(--txt-color-10);
							}
						/* Order received end */

						/* My account start */
							/* Login / Register start */
								.woocommerce-account:not(.logged-in) .woocommerce .u-columns#customer_login .col-1,
								.woocommerce-account:not(.logged-in) .woocommerce .u-columns#customer_login .col-2 {
									background-color: var(--txt-color-10);
								}
								.woocommerce-account:not(.logged-in) .woocommerce .woocommerce-form.login,
								.woocommerce-account:not(.logged-in) .woocommerce .woocommerce-form.register {
									background-color: var(--txt-color-10);
								}
							/* Login / Register end */

							/* Logged start */
								/* Account nav start */
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li a {
										color: var(--txt-color-100);
									}
									@media only screen and (max-width: 767px) {
										.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li a {
											background-color: var(--txt-color-10);
										}
									}
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a {
										background-color: var(--txt-color-10);
									}
									@media only screen and (max-width: 767px) {
										.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a {
											color: var(--background-color-100);
											background-color: var(--txt-color-80);
										}
									}
								/* Account nav end */

								/* Orders start */
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-content table.woocommerce-orders-table {
										background-color: var(--txt-color-10);
									}
								/* Orders end */

								/* Addresses start */
									.woocommerce-account.logged-in .woocommerce .woocommerce-MyAccount-content .woocommerce-Addresses .woocommerce-Address address {
										background-color: var(--txt-color-10);
									}
								/* Addresses end */

								/* Edit address start */
									.woocommerce-account.logged-in.woocommerce-edit-address .woocommerce form[method='post'] .woocommerce-address-fields {
										background-color: var(--txt-color-10);
									}
								/* Edit address end */

								/* View order start */
									.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-order-details table {
										background-color: var(--txt-color-10);
									}
									.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-order-details table tbody tr td a {
										color: var(--txt-color-100);
									}
								/* View order end */

								/* Customer details start */
									.woocommerce-account.logged-in.woocommerce-view-order .woocommerce .woocommerce-customer-details address {
										background-color: var(--txt-color-10);
									}
								/* Customer details end */
							/* Logged end */
						/* My account end */

						/* Store notice start */
							.woocommerce-store-notice {
								color: var(--background-color-100);
								background-color: var(--txt-color-100);
							}
						/* Store notice end */

						/* Mobile category start */
							.product-category-container.mobile .product-category-box {
								color: var(--txt-color-100);
								background-color: var(--content-background-color-1);
							}
						/* Mobile category end */
					/* WooCommerce end */

					/* zaxuDocs start */
						/* Contents start */
							.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single {
								background-color: var(--txt-color-10);
							}
							.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .inside .zaxudocs-doc-sections li a {
								color: var(--txt-color-100);
								background-color: var(--txt-color-10);
							}
							.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .inside .zaxudocs-doc-sections li a:hover {
								background-color: var(--txt-color-20);
							}
							.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single > h3 a {
								color: var(--txt-color-100);
							}
							.zaxudocs-shortcode-container .zaxudocs-docs-list .zaxudocs-docs-single .zaxudocs-doc-link a {
								color: var(--background-color-100);
								background-color: var(--txt-color-100);
							}
						/* Contents end */

						/* Single page start */
							/* Sidebar for desktop start */
								.zaxudocs-single-wrap .zaxudocs-sidebar {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item a {
									color: var(--txt-color-100);
								}
								.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.current_page_item {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.opened {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.page_item_has_children .children > .page_item.current_page_item {
									background-color: var(--txt-color-20);
								}
								.zaxudocs-single-wrap .zaxudocs-sidebar nav .doc-nav-list .page_item.page_item_has_children.opened > a {
									border-bottom-color: var(--txt-color-10);
								}
							/* Sidebar for desktop end */

							/* Sidebar for mobile start */
								.zaxudocs-sidebar.mobile {
									color: var(--txt-color-100);
									background-color: var(--background-color-100);
								}
								.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item a {
									color: var(--txt-color-100);
								}
								.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.current_page_item {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.opened {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.page_item_has_children .children > .page_item.current_page_item {
									background-color: var(--txt-color-20);
								}
								.zaxudocs-sidebar.mobile nav .doc-nav-list .page_item.page_item_has_children.opened > a {
									border-bottom-color: var(--txt-color-10);
								}
							/* Sidebar for mobile end */

							/* Content start */
								.zaxudocs-single-wrap .zaxudocs-single-content .zaxudocs-breadcrumb {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content .zaxudocs-breadcrumb li a {
									color: var(--txt-color-100);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs {
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .article-child ul li a {
									color: var(--txt-color-100);
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .article-child ul li a:hover {
									background-color: var(--txt-color-20);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-entry-content .tags-links a {
									color: var(--txt-color-100);
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-prev a,
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-next a {
									color: var(--txt-color-100);
									background-color: var(--txt-color-10);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-prev a:hover,
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-doc-nav .nav-next a:hover {
									background-color: var(--txt-color-20);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-feedback-wrap .vote-link-wrap a {
									color: var(--background-color-100);
									background-color: var(--txt-color-100);
								}
								.zaxudocs-single-wrap .zaxudocs-single-content article.type-docs .zaxudocs-contact-modal .zaxudocs-modal-header .zaxudocs-modal-close {
									color: var(--txt-color-100);
								}
							/* Content end */
						/* Single page end */
					/* zaxuDocs end */

					/* Contact form 7 start */
						.wpcf7 form.wpcf7-form .wpcf7-form-control-wrap .wpcf7-not-valid-tip {
							color: var(--background-color-100);
							background-color: var(--txt-color-100);
						}
						.wpcf7 form.wpcf7-form .wpcf7-form-control-wrap .wpcf7-not-valid-tip:before {
							background-color: var(--txt-color-100);
						}
						.wpcf7 form.wpcf7-form .wpcf7-response-output {
							color: var(--background-color-100);
							background-color: var(--txt-color-100);
						}
						.wpcf7 form.wpcf7-form .ajax-loader {
							background-color: var(--background-color-30);
						}
						.wpcf7 form.wpcf7-form .ajax-loader:before {
							border-color: var(--txt-color-30);
							border-top-color: var(--txt-color-100);
						}
					/* Contact form 7 end */
				";
			// Dynamic color end
		}
	}
	
	if (get_theme_mod('zaxu_minify_engine', 'enabled') == 'enabled') {
		$custom_css = str_replace(
			array(
				"\rn",
				"\r",
				"\n",
				"\t",
				'  ',
				'    ',
				'    '
			),
			'',
			$custom_css
		);
		$custom_css = preg_replace('/\/\*.*?\*\//s', '', $custom_css);
	}

	wp_register_style('zaxu-color-scheme', false);
    wp_enqueue_style('zaxu-color-scheme');
	wp_add_inline_style('zaxu-color-scheme', $custom_css);
}
add_action('wp_enqueue_scripts', 'zaxu_color_scheme', 10);
?>