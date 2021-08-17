<?php
/*
 * @Description: Helper functions
 * @Version: 2.7.2
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

/** String to HEX
 *
 * @since 2.3.0
*/

if ( !function_exists('zaxu_strToHex') ) :
	function zaxu_strToHex($str) {
		$hex = "";
		for ($i = 0; $i < strlen($str); $i++)
		$hex .= dechex( ord( $str[$i] ) );
		$hex = strtoupper($hex);
		return $hex;
	}
endif;

/** RGB to HEX
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_hex2RGB') ) :
	function zaxu_hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); 
		$rgbArray = array();
		if (strlen($hexStr) == 6) { 
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { 
			$rgbArray['red'] = hexdec( str_repeat(substr($hexStr, 0, 1), 2) );
			$rgbArray['green'] = hexdec( str_repeat(substr($hexStr, 1, 1), 2) );
			$rgbArray['blue'] = hexdec( str_repeat(substr($hexStr, 2, 1), 2) );
		} else {
			return false;
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; 
	}
endif;

/** Palette colors
 *
 * @since 2.4.0
*/

if ( !function_exists('zaxu_get_palette_color') ) :
	function zaxu_get_palette_color($image, $num, $level = 5) {
		$level = (int)$level;
		$palette = array();
		$size = getimagesize($image);
		if (!$size) {
			return false;
		}
		switch( $size['mime'] ) {
			case 'image/jpg':
				$img = imagecreatefromjpeg($image);
				break;
			case 'image/jpeg':
				$img = imagecreatefromjpeg($image);
				break;
			case 'image/pjpeg':
				$img = imagecreatefromjpeg($image);
				break;
			case 'image/bmp':
				$img = imagecreatefromjpeg($image);
				break;
			case 'image/png':
				$img = imagecreatefrompng($image);
				break;
			case 'image/x-png':
				$img = imagecreatefrompng($image);
				break;
			case 'image/gif':
				$img = imagecreatefromgif($image);
				break;
			default:
			return false;
		}
		if (!$img) {
			return false;
		}
		for ($i = 0; $i < $size[0]; $i += $level) {
			for ($j = 0; $j < $size[1]; $j += $level) {
				$thisColor = imagecolorat($img, $i, $j);
				$rgb = imagecolorsforindex($img, $thisColor); 
				$color = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x33)) * 0x33)), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
				$palette[$color] = isset($palette[$color]) ? ++$palette[$color] : 1;  
			}
		}
		arsort($palette);
		return array_slice(array_keys($palette), 0, $num);
	}
endif;

/** Number format
 *
 * @since 2.4.0
*/

if ( !function_exists('zaxu_number_format_short') ) :
	function zaxu_number_format_short($n, $precision = 1) {
		if ($n < 900) {
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 900000) {
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}
		if ($precision > 0) {
			$dotzero = '.' . str_repeat('0', $precision);
			$n_format = str_replace($dotzero, '', $n_format);
		}
		return $n_format . $suffix;
	}
endif;

/** Set head
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_set_head') ) :
	function zaxu_set_head() {
		echo '
			<meta charset="' . get_bloginfo('charset') . '">
			<meta name="format-detection" content="telephone=no">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
			<meta http-equiv="Access-Control-Allow-Origin" content="*">
			<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
			<meta name="applicable-device" content="pc, mobile">
			<meta name="renderer" content="webkit">
			<meta name="MobileOptimized" content="320">
			<meta name="msapplication-tap-highlight" content="no">
		';

		// Dynamic color start
			if (get_theme_mod('zaxu_dynamic_color', 'disabled') == 'enabled') {
				echo '<meta name="supported-color-schemes" content="light dark">';
			}
		// Dynamic color end

		// Theme info start
			if ( get_template_directory() == get_stylesheet_directory() ) {
				echo '
					<meta name="version" content="' . wp_get_theme()->get('Version') . '">
					<meta name="theme-parent" content="' . wp_get_theme()->get('Name') . '">
					<meta name="theme-child" content="N/A">
				';
			} else {
				echo '
					<meta name="version" content="' . wp_get_theme()->parent()->get('Version') . '">
					<meta name="theme-parent" content="' . wp_get_theme()->parent()->get('Name') . '">
					<meta name="theme-child" content="' . wp_get_theme()->get('Name') . '">
				';
			};
		// Theme info end
		
		echo '
			<meta name="author" content="ZAXU, mail@muiteer.com">
			<meta name="designer" content="ZAXU">
			<meta name="copyright" content="© ZAXU">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="pingback" href="' . get_bloginfo('pingback_url') . '">
		';
	}
endif;

/** Social meta
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_social_meta') ) :
	function zaxu_social_meta() {
			// Get meta data start
				$site_name = esc_attr( get_bloginfo('name') );
				$seo_keyword = esc_attr( get_bloginfo('name') );
				$seo_desc = esc_attr( get_bloginfo('name') );
				$article_title = esc_attr( get_bloginfo('name') );
				$excerpt = esc_attr( get_bloginfo('name') );
				
				if ( is_front_page() && is_home() ) {
					// Default homepage
				} else if ( is_front_page() ) {
					// Static homepage
					$post_id = get_the_ID();
					$seo_keyword = esc_attr( get_post_meta($post_id, 'zaxu_seo_keywords', true) );
					$seo_desc = esc_attr( get_post_meta($post_id, 'zaxu_seo_description', true) );
					$article_title = esc_attr( get_the_title($post_id) );
					$excerpt = esc_attr( wp_strip_all_tags( zaxu_excerpt($post_id, "has-desc") ) );
				} else if ( is_home() ) {
					// Blog page
					$post_id = get_option('page_for_posts');
					$seo_keyword = esc_attr( get_post_meta($post_id, 'zaxu_seo_keywords', true) );
					$seo_desc = esc_attr( get_post_meta($post_id, 'zaxu_seo_description', true) );
					$article_title = esc_attr( get_the_title($post_id) );
					$excerpt = esc_attr( wp_strip_all_tags( zaxu_excerpt($post_id, "has-desc") ) );
				} else {
					// Other page
					$post_id = get_the_ID();
					if ($post_id) {
						$seo_keyword = esc_attr( get_post_meta($post_id, 'zaxu_seo_keywords', true) );
						$seo_desc = esc_attr( get_post_meta($post_id, 'zaxu_seo_description', true) );
						$article_title = esc_attr( get_the_title($post_id) );
						$excerpt = esc_attr( wp_strip_all_tags( zaxu_excerpt($post_id, "has-desc") ) );
					}
				}
			// Get meta data end

			// Set meta data start
				if ($seo_keyword) {
					echo '<meta name="keywords" content="' . $seo_keyword . '" />';
				} else {
					echo '<meta name="keywords" content="' . $site_name . '" />';
				}
				if ($seo_desc) {
					echo '<meta name="description" content="' . $seo_desc . '" />';
				} else if ($excerpt) {
					echo '<meta name="description" content="' . $excerpt . '" />';
				}
				echo '<meta property="og:site_name" content="' . $site_name . '" />';
				if ($seo_desc) {
					echo '<meta property="og:description" content="' . $seo_desc . '" />';
				} else if ($excerpt) {
					echo '<meta property="og:description" content="' . $excerpt . '" />';
				}
				echo '
					<meta property="og:title" content="' . $article_title . '" />
					<meta property="og:type" content="article" />
					<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />
					<meta name="twitter:card" value="summary" />
					<meta name="twitter:title" content="' . $article_title . '" />
				';
				if ($seo_desc) {
					echo '<meta property="twitter:description" content="' . $seo_desc . '" />';
				} else if ($excerpt) {
					echo '<meta property="twitter:description" content="' . $excerpt . '" />';
				}
			// Set meta data end
	}
endif;

/** JS-SDK functions
 *
 * @since 2.2.0
*/

if ( !function_exists('zaxu_jssdk') ) :
	function zaxu_jssdk($post_id) {
		if (get_theme_mod('zaxu_wechat_js_sdk') == 'enabled') {
			function getaccess_token() {
				$appid = esc_attr( get_theme_mod('zaxu_wechat_app_id') );
				$appsecret = esc_attr( get_theme_mod('zaxu_wechat_app_secret') );
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($data, true);
				if ( isset( $data['access_token'] ) ) {
					return $data['access_token'];
				}
			}
			function getjsapi_ticket() {
				$access_token = getaccess_token();
				$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($data, true);
				if ( isset( $data['ticket'] ) ) {
					return $data['ticket'];
				}
			}
			if (getaccess_token() != null && getjsapi_ticket() != null) {
				function createNonceStr($length = 16) {
					$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$str = "";
					for ($i = 0; $i < $length; $i++) {
						$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
					}
					return $str;
				}

				function getSignPackage() {
					$jsapiTicket = getjsapi_ticket();
					$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
					$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$timestamp = time();
					$nonceStr = createNonceStr();
					$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
					$signature = sha1($string);
					$signPackage = array(
						"appId" => esc_attr( get_theme_mod('zaxu_wechat_app_id') ),
						"nonceStr" => $nonceStr,
						"timestamp" => $timestamp,
						"url" => $url,
						"signature" => $signature
					);
					return $signPackage; 
				}

				$signPackage = getSignPackage();

				echo '
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							function WeChat(title, desc, link, imgUrl) {
								wx.config({
									debug: false,
									appId: "' . $signPackage["appId"] . '",
									timestamp: ' . $signPackage["timestamp"] . ',
									nonceStr: "' . $signPackage["nonceStr"] . '",
									signature: "' . $signPackage["signature"] . '",
									jsApiList: [
										"checkJsApi",
										"onMenuShareTimeline",
										"onMenuShareAppMessage",
										"onMenuShareQQ",
										"onMenuShareWeibo",
										"hideMenuItems",
										"showMenuItems",
										"hideAllNonBaseMenuItem",
										"showAllNonBaseMenuItem",
										"translateVoice",
										"startRecord",
										"stopRecord",
										"onRecordEnd",
										"playVoice",
										"pauseVoice",
										"stopVoice",
										"uploadVoice",
										"downloadVoice",
										"chooseImage",
										"previewImage",
										"uploadImage",
										"downloadImage",
										"getNetworkType",
										"openLocation",
										"getLocation",
										"hideOptionMenu",
										"showOptionMenu",
										"closeWindow",
										"scanQRCode",
										"chooseWXPay",
										"openProductSpecificView",
										"addCard",
										"chooseCard",
										"openCard"
									]
								});
								wx.ready(function() {
									wx.onMenuShareTimeline({
										title: title,
										link: link,
										imgUrl: imgUrl,
										success: function () {
										},
										cancel: function () {
										}
									});
									wx.onMenuShareAppMessage({
										title: title,
										desc: desc,
										link: link,
										imgUrl: imgUrl,
										success: function () {
										},
										cancel: function () {
										}
									});
									wx.onMenuShareQQ({
										title: title,
										desc: desc,
										link: link,
										imgUrl: imgUrl,
										success: function () {
										},
										cancel: function () {
										}
									});
									wx.onMenuShareQZone({
										title: title,
										desc: desc,
										link: link,
										imgUrl: imgUrl,
										success: function () {
										},
										cancel: function () {
										}
									});
									wx.hideMenuItems({
										menuList: ["menuItem:readMode"]
									});
								});
							};
							var title = $("title").text(),
								link = "' . get_permalink() . '",
				';

				// Check global sharing description start
					$wechat_global_sharing_desc = esc_attr( get_theme_mod("zaxu_wechat_global_sharing_description") );
					if ($wechat_global_sharing_desc !== "") {
						echo '
							desc = "' . $wechat_global_sharing_desc . '",
						';
					} else {
						echo '
							desc = $("meta[name=description]").attr("content"),
						';
					}
				// Check global sharing description end

				// Check global sharing thumbnail start
					if ( !empty( get_theme_mod("zaxu_wechat_global_sharing_thumbnail") ) ) {
						echo '
							imgUrl = "' . wp_get_attachment_image_src(get_theme_mod("zaxu_wechat_global_sharing_thumbnail"), 'thumbnail')[0] . '";
						';
					} else {
						if ( has_post_thumbnail($post_id) ) {
							echo '
								imgUrl = "' . wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail')[0] . '";
							';
						} else {
							echo '
								imgUrl = "' . get_template_directory_uri() . "/assets/img/wechat-light-600x600.jpg" . '";
							';
						}
					}
				// Check global sharing thumbnail end

				echo '
							WeChat(title, desc, link, imgUrl);
						});
					</script>
				';
			}
		}
	}
endif;

/** Hero slide
 *
 * @since 2.2.0
*/

if ( !function_exists('zaxu_hero_slide') ) :
	function zaxu_hero_slide($postid) {
		$output = null;

		function zaxu_hex_to_rgb($hex, $alpha) {
		   $hex = str_replace('#', '', $hex);
		   $length = strlen($hex);
		   $rgb['r'] = hexdec( $length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0) );
		   $rgb['g'] = hexdec( $length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0) );
		   $rgb['b'] = hexdec( $length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0) );
		   $rgb['a'] = $alpha;
		   return $rgb;
		}

		if (zaxu_get_field('slide_visibility', $postid) == true) {
			// Public parameters.

			// Get autoplay value.
			$autoplay = zaxu_get_field('slide_autoplay', $postid);
			if ($autoplay) {
				$autoplayValue = $autoplay * 1000;
			} else {
				$autoplayValue = 0;
			}

			// Get scroll with the content value.
			$scroll_with_content = (zaxu_get_field('slide_scroll_with_the_content', $postid) == 1) ? '' : ' sticky';

			// Get slide height value.
			while( have_rows('slide_height_group', $postid) ): the_row();
				$slide_height_desktop = zaxu_get_sub_field('slide_height_desktop', $postid);
				$slide_height_unit_desktop = zaxu_get_sub_field('slide_height_unit_desktop', $postid);
				$slide_height_visibility_pad = zaxu_get_sub_field('slide_height_visibility_pad', $postid);
				$slide_height_pad = zaxu_get_sub_field('slide_height_pad', $postid);
				$slide_height_unit_pad = zaxu_get_sub_field('slide_height_unit_pad', $postid);
				$slide_height_visibility_mobile = zaxu_get_sub_field('slide_height_visibility_mobile', $postid);
				$slide_height_mobile = zaxu_get_sub_field('slide_height_mobile', $postid);
				$slide_height_unit_mobile = zaxu_get_sub_field('slide_height_unit_mobile', $postid);
			endwhile;

			// Set slide height data.
			$slide_height_desktop_data = 'data-slide-height-desktop="' . $slide_height_desktop . '" data-slide-height-unit-desktop="' . $slide_height_unit_desktop . '"';
			$slide_height_pad_data = 'data-slide-height-visibility-pad="' . $slide_height_visibility_pad . '" data-slide-height-pad="' . $slide_height_pad . '" data-slide-height-unit-pad="' . $slide_height_unit_pad . '"';
			$slide_height_mobile_data = 'data-slide-height-visibility-mobile="' . $slide_height_visibility_mobile . '" data-slide-height-mobile="' . $slide_height_mobile . '" data-slide-height-unit-mobile="' . $slide_height_unit_mobile . '"';
			$slide_height = $slide_height_desktop_data . ' ' . $slide_height_pad_data . ' ' . $slide_height_mobile_data;

			// Get page content height value.
			while( have_rows('page_content_height_group', $postid) ): the_row();
				$page_content_height_desktop = zaxu_get_sub_field('page_content_height_desktop', $postid);
				$page_content_height_unit_desktop = zaxu_get_sub_field('page_content_height_unit_desktop', $postid);
				$page_content_height_visibility_pad = zaxu_get_sub_field('page_content_height_visibility_pad', $postid);
				$page_content_height_pad = zaxu_get_sub_field('page_content_height_pad', $postid);
				$page_content_height_unit_pad = zaxu_get_sub_field('page_content_height_unit_pad', $postid);
				$page_content_height_visibility_mobile = zaxu_get_sub_field('page_content_height_visibility_mobile', $postid);
				$page_content_height_mobile = zaxu_get_sub_field('page_content_height_mobile', $postid);
				$page_content_height_unit_mobile = zaxu_get_sub_field('page_content_height_unit_mobile', $postid);
			endwhile;

			// Set page content height data.
			$page_content_height_desktop_data = 'data-page-content-height-desktop="' . $page_content_height_desktop . '" data-page-content-height-unit-desktop="' . $page_content_height_unit_desktop . '"';
			$page_content_height_pad_data = 'data-page-content-height-visibility-pad="' . $page_content_height_visibility_pad . '" data-page-content-height-pad="' . $page_content_height_pad . '" data-page-content-height-unit-pad="' . $page_content_height_unit_pad . '"';
			$page_content_height_mobile_data = 'data-page-content-height-visibility-mobile="' . $page_content_height_visibility_mobile . '" data-page-content-height-mobile="' . $page_content_height_mobile . '" data-page-content-height-unit-mobile="' . $page_content_height_unit_mobile . '"';
			$page_content_height = $page_content_height_desktop_data . ' ' . $page_content_height_pad_data . ' ' . $page_content_height_mobile_data;
			
			// Slide enabled.
			$output .= '<section class="hero-slide-container' . $scroll_with_content . '" ' . $slide_height . ' ' . $page_content_height . ' data-prev-next-buttons="' . zaxu_get_field('slide_previous_and_next_buttons', $postid) . '" data-navi-dots="' . zaxu_get_field('slide_navigation_dots', $postid) . '" data-autoplay="' . $autoplayValue . '">';
				// Page background opacity
				$page_bg_opacity_start = 1 - zaxu_get_field('page-bg-opacity-s', $postid) / 100;
				$page_bg_opacity_end = 1 - zaxu_get_field('page-bg-opacity', $postid) / 100;
				$output .= '
					<style type="text/css">
						.hero-slide-container {
							opacity: ' . $page_bg_opacity_start . ';
						}
						.hero-slide-container.active {
							opacity: ' . $page_bg_opacity_end . ';
						}
					</style>
				';

				// Slide count
				$slide_counter = 0;
				while( have_rows('slide_content', $postid) ): the_row();
					$slide_counter++;
				endwhile;

				if ($slide_counter <= 1) {
					$slide_count = ' data-slide-count="single"';
				} else {
					$slide_count = ' data-slide-count="multiple"';
				};

				$output .= '
					<gallery>
						<ul class="swiper-wrapper"' . $slide_count . '>
				';

				$counter = 0;
				while( have_rows('slide_content', $postid) ): the_row();
					$counter++;
					// Desktop parameters.
					$desktop_file = zaxu_get_sub_field('slide_image_or_video_desktop', $postid);
					$desktop_video_cover = zaxu_get_sub_field('slide_video_cover_desktop', $postid);
					$desktop_lazyload_cover = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $desktop_video_cover . '"' : ' style="background-image: url(' . $desktop_video_cover . ');"';
					$desktop_overlay_opacity = zaxu_get_sub_field('slide_overlay_opacity_desktop', $postid) / 100;
					$desktop_overlay_color = zaxu_get_sub_field('slide_overlay_color_desktop', $postid);
					$desktop_overlay_rgba = zaxu_hex_to_rgb($desktop_overlay_color, $desktop_overlay_opacity);
					$desktop_rgba = $desktop_overlay_rgba['r'] . ', ' . $desktop_overlay_rgba['g'] . ', ' . $desktop_overlay_rgba['b'] . ', ' . $desktop_overlay_rgba['a'];
					$desktop_caption_color = zaxu_get_sub_field('slide_caption_color_desktop', $postid);
					$desktop_animation = (zaxu_get_sub_field('slide_animation_desktop', $postid) == 1) ? ' animation' : '';
					$desktop_link = esc_url( zaxu_get_sub_field('slide_link_desktop', $postid) );
					$desktop_link_title =  esc_attr( zaxu_get_sub_field('slide_link_title_desktop', $postid) );
					$desktop_link_new_tab = zaxu_get_sub_field('slide_link_new_tab_desktop', $postid);
					$desktop_ajax = ( !empty($desktop_link) ) ? 'ajax-link ' : '';
					$desktop_title = esc_attr( zaxu_get_sub_field('slide_title_desktop', $postid) );
					$desktop_subtitle = esc_attr( zaxu_get_sub_field('slide_subtitle_desktop', $postid) );
					$desktop_lazyload_file = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $desktop_file . '"' : ' style="background-image: url(' . $desktop_file . ');"'; 
					
					// Pad parameters.
					$pad_file = zaxu_get_sub_field('slide_image_or_video_pad', $postid);
					$pad_video_cover = zaxu_get_sub_field('slide_video_cover_pad', $postid);
					$pad_lazyload_cover = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $pad_video_cover . '"' : ' style="background-image: url(' . $pad_video_cover . ');"';
					$pad_overlay_opacity = zaxu_get_sub_field('slide_overlay_opacity_pad', $postid) / 100;
					$pad_overlay_color = zaxu_get_sub_field('slide_overlay_color_pad', $postid);
					$pad_overlay_rgba = zaxu_hex_to_rgb($pad_overlay_color, $pad_overlay_opacity);
					$pad_rgba = $pad_overlay_rgba['r'] . ', ' . $pad_overlay_rgba['g'] . ', ' . $pad_overlay_rgba['b'] . ', ' . $pad_overlay_rgba['a'];
					$pad_caption_color = zaxu_get_sub_field('slide_caption_color_pad', $postid);
					$pad_animation = (zaxu_get_sub_field('slide_animation_pad', $postid) == 1) ? ' animation' : '';
					$pad_link = esc_url( zaxu_get_sub_field('slide_link_pad', $postid) );
					$pad_link_title = esc_attr( zaxu_get_sub_field('slide_link_title_pad', $postid) );
					$pad_link_new_tab = zaxu_get_sub_field('slide_link_new_tab_pad', $postid);
					$pad_ajax = ( !empty($pad_link) ) ? 'ajax-link ' : '';
					$pad_title = esc_attr( zaxu_get_sub_field('slide_title_pad', $postid) );
					$pad_subtitle = esc_attr( zaxu_get_sub_field('slide_subtitle_pad', $postid) );
					$pad_lazyload_file = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $pad_file . '"' : ' style="background-image: url(' . $pad_file . ');"'; 
					
					// Mobile parameters.
					$mobile_file = zaxu_get_sub_field('slide_image_or_video_mobile', $postid);
					$mobile_video_cover = zaxu_get_sub_field('slide_video_cover_mobile', $postid);
					$mobile_lazyload_cover = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $mobile_video_cover . '"' : ' style="background-image: url(' . $mobile_video_cover . ');"';
					$mobile_overlay_opacity = zaxu_get_sub_field('slide_overlay_opacity_mobile', $postid) / 100;
					$mobile_overlay_color = zaxu_get_sub_field('slide_overlay_color_mobile', $postid);
					$mobile_overlay_rgba = zaxu_hex_to_rgb($mobile_overlay_color, $mobile_overlay_opacity);
					$mobile_rgba = $mobile_overlay_rgba['r'] . ', ' . $mobile_overlay_rgba['g'] . ', ' . $mobile_overlay_rgba['b'] . ', ' . $mobile_overlay_rgba['a'];
					$mobile_caption_color = zaxu_get_sub_field('slide_caption_color_mobile', $postid);
					$mobile_animation = (zaxu_get_sub_field('slide_animation_mobile', $postid) == 1) ? ' animation' : '';
					$mobile_link = esc_url( zaxu_get_sub_field('slide_link_mobile', $postid) );
					$mobile_link_title = esc_attr( zaxu_get_sub_field('slide_link_title_mobile', $postid) );
					$mobile_link_new_tab = zaxu_get_sub_field('slide_link_new_tab_mobile', $postid);
					$mobile_ajax = ( !empty($mobile_link) ) ? 'ajax-link ' : '';
					$mobile_title = esc_attr( zaxu_get_sub_field('slide_title_mobile', $postid) );
					$mobile_subtitle = esc_attr( zaxu_get_sub_field('slide_subtitle_mobile', $postid) );
					$mobile_lazyload_file = (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") ? ' data-background="' . $mobile_file . '"' : ' style="background-image: url(' . $mobile_file . ');"';

					$desktop_path_info = pathinfo($desktop_file);
					if ($desktop_file) {
						$desktop_extension = $desktop_path_info['extension'];
					} else {
						$desktop_extension = null;
					}

					$pad_path_info = pathinfo($pad_file);
					if ($pad_file) {
						$pad_extension = $pad_path_info['extension'];
					} else {
						$pad_extension = null;
					}

					$mobile_path_info = pathinfo($mobile_file);
					if ($mobile_file) {
						$mobile_extension = $mobile_path_info['extension'];
					} else {
						$mobile_extension = null;
					}

					// ********Set Slide Start********
						$output .= '<li class="swiper-slide" data-index="' . $counter . '">';
							// ********For Desktop Start********
								if ($desktop_extension == 'mp4') {
									// File is video.
									$output .= '
										<div class="slide-media-box has-video desktop' . $desktop_animation . '" data-overlay-color="' . $desktop_overlay_color . '" data-text-color="' . $desktop_caption_color . '">
											<div class="slide-video" data-video="' . $desktop_file . '">
									';
										if ( !empty($desktop_video_cover) ) {
											$output .= '
												<div class="slide-video-cover-box">
													<div class="slide-video-cover swiper-lazy"' . $desktop_lazyload_cover . '></div>
												</div>
											';
										}
									$output .= '</div>';

										// Output overlay
										$output .= '
											<div class="slide-overlay-wrapper" style="background-color: rgba(' . $desktop_rgba . ');"></div>
										';

										// Output slide content.
										if ( !empty($desktop_title) || !empty($desktop_subtitle) || !empty($desktop_link) ) {
											$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $desktop_caption_color . '">';
												if ( !empty($desktop_title) ) {
													$output .= '<h2 class="headline">' . $desktop_title . '</h2>';
												};
												if ( !empty($desktop_subtitle) ) {
													$output .= '<h3 class="subhead">' . $desktop_subtitle . '</h3>';
												};
												if ( !empty($desktop_link) ) {
													if ( empty($desktop_link_title) ) {
														$desktop_link_title = esc_html__('Learn More', 'zaxu');
													}
													if ($desktop_link_new_tab == true) {
														$desktop_link_new_tab = 'target="_blank" ';
													} else {
														$desktop_link_new_tab = null;
													}
													$output .= '<a href="' . $desktop_link . '" ' . $desktop_link_new_tab . 'class="' . $desktop_ajax . 'slide-link button button-primary" style="color: ' . $desktop_overlay_color . ' !important; background-color: ' . $desktop_caption_color . ';">' . $desktop_link_title . '</a>';
												};
											$output .= '</div>';
										};
									$output .= '</div>';
								} else {
									// File is image.
									$output .= '
										<div class="slide-media-box desktop' . $desktop_animation . '" data-overlay-color="' . $desktop_overlay_color . '" data-text-color="' . $desktop_caption_color . '">
											<div class="slide-media swiper-lazy"' . $desktop_lazyload_file . '></div>
									';

										// Output overlay
										$output .= '
											<div class="slide-overlay-wrapper" style="background-color: rgba(' . $desktop_rgba . ');"></div>
										';

										// Output slide content.
										if ( !empty($desktop_title) || !empty($desktop_subtitle) || !empty($desktop_link) ) {
											$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $desktop_caption_color . '">';
												if ( !empty($desktop_title) ) {
													$output .= '<h2 class="headline">' . $desktop_title . '</h2>';
												};
												if ( !empty($desktop_subtitle) ) {
													$output .= '<h3 class="subhead">' . $desktop_subtitle . '</h3>';
												};
												if ( !empty($desktop_link) ) {
													if ( empty($desktop_link_title) ) {
														$desktop_link_title = esc_html__('Learn More', 'zaxu');
													}
													if ($desktop_link_new_tab == true) {
														$desktop_link_new_tab = 'target="_blank" ';
													} else {
														$desktop_link_new_tab = null;
													}
													$output .= '<a href="' . $desktop_link . '" ' . $desktop_link_new_tab . 'class="' . $desktop_ajax . 'slide-link button button-primary" style="color: ' . $desktop_overlay_color . ' !important; background-color: ' . $desktop_caption_color . ';">' . $desktop_link_title . '</a>';
												};
											$output .= '</div>';
										};
									$output .= '</div>';
								};
							// ********For Desktop End********

							// ********For Pad Start********
								if ( !empty($pad_file) ) {
									if ($pad_extension == 'mp4') {
										// File is video.
										$output .= '
											<div class="slide-media-box has-video pad' . $pad_animation . '" data-overlay-color="' . $pad_overlay_color . '" data-text-color="' . $pad_caption_color . '">
												<div class="slide-video" data-video="' . $pad_file . '">
										';
											if (!empty($pad_video_cover)) {
												$output .= '
													<div class="slide-video-cover-box">
														<div class="slide-video-cover swiper-lazy"' . $pad_lazyload_cover . '></div>
													</div>
												';
											}
										$output .= '</div>';

											// Output overlay
											$output .= '
												<div class="slide-overlay-wrapper" style="background-color: rgba(' . $pad_rgba . ');"></div>
											';

											// Output slide content.
											if ( !empty($pad_title) || !empty($pad_subtitle) || !empty($pad_link) ) {
												$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $pad_caption_color . '">';
													if ( !empty($pad_title) ) {
														$output .= '<h2 class="headline">' . $pad_title . '</h2>';
													};
													if ( !empty($pad_subtitle) ) {
														$output .= '<h3 class="subhead">' . $pad_subtitle . '</h3>';
													};
													if ( !empty($pad_link) ) {
														if ( empty($pad_link_title) ) {
															$pad_link_title = esc_html__('Learn More', 'zaxu');
														}
														if ($pad_link_new_tab == true) {
															$pad_link_new_tab = 'target="_blank" ';
														} else {
															$pad_link_new_tab = null;
														}
														$output .= '<a href="' . $pad_link . '" ' . $pad_link_new_tab . 'class="' . $pad_ajax . 'slide-link button button-primary" style="color: ' . $pad_overlay_color . ' !important; background-color: ' . $pad_caption_color . ';">' . $pad_link_title . '</a>';
													};
												$output .= '</div>';
											};
										$output .= '</div>';
									} else {
										// File is image.
										$output .= '
											<div class="slide-media-box pad' . $pad_animation . '" data-overlay-color="' . $pad_overlay_color . '" data-text-color="' . $pad_caption_color . '">
												<div class="slide-media swiper-lazy"' . $pad_lazyload_file . '></div>
										';

											// Output overlay
											$output .= '
												<div class="slide-overlay-wrapper" style="background-color: rgba(' . $pad_rgba . ');"></div>
											';

											// Output slide content.
											if ( !empty($pad_title) || !empty($pad_subtitle) || !empty($pad_link) ) {
												$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $pad_caption_color . '">';
													if ( !empty($pad_title) ) {
														$output .= '<h2 class="headline">' . $pad_title . '</h2>';
													};
													if ( !empty($pad_subtitle) ) {
														$output .= '<h3 class="subhead">' . $pad_subtitle . '</h3>';
													};
													if ( !empty($pad_link) ) {
														if ( empty($pad_link_title) ) {
															$pad_link_title = esc_html__('Learn More', 'zaxu');
														}
														if ($pad_link_new_tab == true) {
															$pad_link_new_tab = 'target="_blank" ';
														} else {
															$pad_link_new_tab = null;
														}
														$output .= '<a href="' . $pad_link . '" ' . $pad_link_new_tab . 'class="' . $pad_ajax . 'slide-link button button-primary" style="color: ' . $pad_overlay_color . ' !important; background-color: ' . $pad_caption_color . ';">' . $pad_link_title . '</a>';
													};
												$output .= '</div>';
											};
										$output .= '</div>';
									}
								};
							// ********For Pad End********

							// ********For Mobile Start********
								if ( !empty($mobile_file) ) {
									if ($mobile_extension == 'mp4') {
										// File is video.
										$output .= '
											<div class="slide-media-box has-video mobile' . $mobile_animation . '" data-overlay-color="' . $mobile_overlay_color . '" data-text-color="' . $mobile_caption_color . '">
												<div class="slide-video" data-video="' . $mobile_file . '">
										';
											if (!empty($mobile_video_cover)) {
												$output .= '
													<div class="slide-video-cover-box">
														<div class="slide-video-cover swiper-lazy"' . $mobile_lazyload_cover . '></div>
													</div>
												';
											}
										$output .= '</div>';

											// Output overlay
											$output .= '
												<div class="slide-overlay-wrapper" style="background-color: rgba(' . $mobile_rgba . ');"></div>
											';

											// Output slide content.
											if ( !empty($mobile_title) || !empty($mobile_subtitle) || !empty($mobile_link) ) {
												$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $mobile_caption_color . '">';
													if ( !empty($mobile_title) ) {
														$output .= '<h2 class="headline">' . $mobile_title . '</h2>';
													};
													if ( !empty($mobile_subtitle) ) {
														$output .= '<h3 class="subhead">' . $mobile_subtitle . '</h3>';
													};
													if ( !empty($mobile_link) ) {
														if ( empty($mobile_link_title) ) {
															$mobile_link_title = esc_html__('Learn More', 'zaxu');
														}
														if ($mobile_link_new_tab == true) {
															$mobile_link_new_tab = 'target="_blank" ';
														} else {
															$mobile_link_new_tab = null;
														}
														$output .= '<a href="' . $mobile_link . '" ' . $mobile_link_new_tab . 'class="' . $mobile_ajax . 'slide-link button button-primary" style="color: ' . $mobile_overlay_color . ' !important; background-color: ' . $mobile_caption_color . ';">' . $mobile_link_title . '</a>';
													};
												$output .= '</div>';
											};
										$output .= '</div>';
									} else {
										// File is image.
										$output .= '
											<div class="slide-media-box mobile' . $mobile_animation . '" data-overlay-color="' . $mobile_overlay_color . '" data-text-color="' . $mobile_caption_color . '">
												<div class="slide-media swiper-lazy"' . $mobile_lazyload_file . '></div>
										';
											// Output overlay
											$output .= '
												<div class="slide-overlay-wrapper" style="background-color: rgba(' . $mobile_rgba . ');"></div>
											';

											// Output slide content.
											if ( !empty($mobile_title) || !empty($mobile_subtitle) || !empty($mobile_link) ) {
												$output .= '<div class="slide-caption-wrapper section-inner" style="color: ' . $mobile_caption_color . '">';
													if ( !empty($mobile_title) ) {
														$output .= '<h2 class="headline">' . $mobile_title . '</h2>';
													};
													if ( !empty($mobile_subtitle) ) {
														$output .= '<h3 class="subhead">' . $mobile_subtitle . '</h3>';
													};
													if ( !empty($mobile_link) ) {
														if ( empty($mobile_link_title) ) {
															$mobile_link_title = esc_html__('Learn More', 'zaxu');
														}
														if ($mobile_link_new_tab == true) {
															$mobile_link_new_tab = 'target="_blank" ';
														} else {
															$mobile_link_new_tab = null;
														}
														$output .= '<a href="' . $mobile_link . '" ' . $mobile_link_new_tab . 'class="' . $mobile_ajax . 'slide-link button button-primary" style="color: ' . $mobile_overlay_color . ' !important; background-color: ' . $mobile_caption_color . ';">' . $mobile_link_title . '</a>';
													};
												$output .= '</div>';
											};
										$output .= '</div>';
									}
								};
							// ********For Mobile End********
						$output .= '</li>';
					// ********Set Slide End********
				endwhile;

				$output .= '
						</gallery>
					</ul>
				';

				if (zaxu_get_field('slide_previous_and_next_buttons', $postid) == 1 && $slide_counter > 1) {
					$output .= '
						<div class="zaxu-swiper-button-prev background-blur"></div>
						<div class="zaxu-swiper-button-next background-blur"></div>
					';
				};
				if (zaxu_get_field('slide_navigation_dots', $postid) == 1 && $slide_counter > 1) {
					$output .= '<div class="swiper-pagination"></div>';
				};
			$output .= '</section>';
		} else {
			// Slide disabled.
			$output .= null;
		};

		echo $output;
	}
endif;

/** Wrapper start
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_wrapper_start') ) :
	function zaxu_wrapper_start() {
		echo '
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
        ';
	}
endif;

/** Recommended post
 *
 * @since 2.2.0
*/

if ( !function_exists('zaxu_recommended_post') ) :
	function zaxu_recommended_post() {
		$recommend_mode = get_theme_mod('zaxu_recommended_post', 'disabled');
		if ($recommend_mode != "disabled") {
			echo '
				<section class="zaxu-post-container carousel-mode alignfull">
					<div class="zaxu-post-wrapper section-inner">
						<header class="zaxu-post-headline">
							<h3 class="zaxu-post-title">' . __('Recommended for You', 'zaxu') . '</h3>
						</header>
			';
				$args = array(
					'numberposts' => 1,
					'post_type' => 'post',
					'post_status' => 'publish',
					'suppress_filters' => false
				);

				$post_query = new WP_Query($args);

				if ( $post_query -> have_posts() ) {
					// Has post
					if ($recommend_mode == "specified") {
						// Specified content
						$recommend_post = get_theme_mod('zaxu_specified_content', 'none');

						if ($recommend_post !== 'none' && ! empty($recommend_post) && $recommend_post[0] !== 'none') {
							echo '
								<section class="post-article-container carousel-mode">
									<ul class="swiper-wrapper">
							';

							// Get post item start
								$all_posts = new WP_Query(
									array(
										'post__in' => $recommend_post,
										'ignore_sticky_posts' => 1
									)
								);
								while ( $all_posts -> have_posts() ) : $all_posts -> the_post();
									zaxu_post_article('carousel', 'post', $all_posts->id, "recommend");
								endwhile;
								wp_reset_postdata();
							// Get post item end

							echo '
									</ul>
									<div class="zaxu-swiper-button-next background-blur"></div>
									<div class="zaxu-swiper-button-prev background-blur"></div>
									<div class="swiper-pagination"></div>
								</section>
							';
						} else {
							// No post
							zaxu_no_item_tips( __('Sorry! The widget is not currently available.', 'zaxu') );
						};
					} else if ($recommend_mode == "random") {
						// Random post
						echo '
							<section class="post-article-container carousel-mode">
								<ul class="swiper-wrapper">
						';

						// Get post item start
							$args = array(
								'numberposts' => 6,
								'orderby'   => 'rand',
								'post_type' => 'post',
								'post_status' => 'publish',
								'suppress_filters' => false
							);
							$post_query = new WP_Query($args);
							$random_posts = wp_get_recent_posts($args);
							foreach($random_posts as $random) {
								zaxu_post_article('carousel', 'post', $random['ID'], "recommend");
							};
						// Get post item end

						echo '
								</ul>
								<div class="zaxu-swiper-button-next background-blur"></div>
								<div class="zaxu-swiper-button-prev background-blur"></div>
								<div class="swiper-pagination"></div>
							</section>
						';
					};
					wp_reset_postdata();
				} else {
					// No post
					zaxu_no_item_tips( __('Sorry! The post is not currently available.', 'zaxu') );
				};
			echo '
					</div>
				</section>
			';
		}
	}
endif;

/** Post filter
 *
 * @since 2.2.0
*/

if ( !function_exists('zaxu_post_filter') ) : 
	function zaxu_post_filter($post_type, $style) {
		global $post;
		$output = null;

		if ($post_type == 'post') {
			// Category
			$categories = get_categories(
				array(
					'hide_empty' => 1,
					'taxonomy' => 'category',
				)
			);

			// Get post homepage link
			$post_homepage_link = get_the_permalink( get_option('page_for_posts') );
		} else {
			// Category
			$categories = get_categories(
				array(
					'hide_empty' => 1,
					'taxonomy' => $post_type . '_category',
				)
			);

			// Get portfolio homepage link
			$portfolio_pages = get_pages(
				array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'templates/template-portfolio.php',
				)
			);
			foreach($portfolio_pages as $portfolio_page) {
				$post_homepage_link = get_page_link($portfolio_page->ID);
			};
		};
		$output .= '
			<section class="filter-carousel-container alignfull '. $style .'">
				<div class="section-inner">
		';
			$count_posts = wp_count_posts($post_type);
			$published_posts = $count_posts -> publish;
			
			if ($style == 'thumbnail') {
				$output .= '
					<div class="filter-headline">
						<h3>' . __('Category', 'zaxu') . '</h3>
					</div>
						<div class="filter-carousel">
							<ul class="swiper-wrapper">
				';
				$output .= '
					<li class="swiper-slide all current">
						<a href="' . $post_homepage_link . '" data-filter="*" class="filter-list-item swiper-lazy">
							<div class="name">' . __('All', 'zaxu') . '<span class="badge background-blur">' . $published_posts . '</span></div>
						</a>
					</li>
				';
				foreach ($categories as $category) {
					// Get category link
					$category_link = get_category_link($category->cat_ID);

					// Get first featured image from category start
						if ($post_type == 'post') {
							// Blog
							$args = array(
								'post_type' => $post_type,
								'category_name' => $category->slug,
								'posts_per_page' => -1,
								'orderby' => 'date',
								'order' => 'ASC',
								'hide_empty' => true,
								'post_status' => 'publish',
								'suppress_filters' => false,
							);
						} else {
							// Portfolio
							$args = array(
								'post_type' => $post_type,
								'portfolio_category' => $category->slug,
								'posts_per_page' => -1,
								'orderby' => 'date',
								'order' => 'ASC',
								'hide_empty' => true,
								'post_status' => 'publish',
								'suppress_filters' => false,
							);
						}
						$all_posts = new WP_Query($args); 
						while ( $all_posts->have_posts() ) : $all_posts->the_post();
							$post_id = $post->ID;
						endwhile; 
						wp_reset_query();

						$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium');
						if ( empty($featured_image) ) {
							$featured_image = get_template_directory_uri() . '/assets/img/file-light-480x320.jpg';
						} else {
							$featured_image = $featured_image[0];
						}
					// Get first featured image from category end

					$output .= '
						<li class="swiper-slide">
					';
					if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
						$output .= '
							<a href="' . $category_link .'" data-filter=".' . zaxu_strToHex($category->name) . '" data-background="' . $featured_image . '" class="filter-list-item swiper-lazy">
						';
					} else {
						$output .= '
							<a href="' . $category_link .'" data-filter=".' . zaxu_strToHex($category->name) . '" style="background-image: url(' . $featured_image . ');" class="filter-list-item">
						';
					};
					$output .= '
								<div class="name">' . $category->name . '<span class="badge background-blur">' . $category->count . '</span></div>
							</a>
						</li>
					';
				}
			} elseif ($style == 'text') {
				$output .= '
					<div class="filter-headline">
						<h3>' . __('Category', 'zaxu') . '</h3>
					</div>
						<div class="filter-carousel">
							<ul class="swiper-wrapper">
				';
				$output .= '
					<li class="swiper-slide all current">
						<a href="' . $post_homepage_link . '" data-filter="*" class="filter-list-item">
							<div class="name">' . __('All', 'zaxu') . '<span class="badge">' . $published_posts . '</span></div>
						</a>
					</li>
				';
				foreach ($categories as $category) {
					// Get category link
					$category_link = get_category_link($category->cat_ID);
					$output .= '
						<li class="swiper-slide">
							<a href="' . $category_link . '" data-filter=".' . zaxu_strToHex($category->name) . '" class="filter-list-item">
								<div class="name">' . $category->name . '<span class="badge">' . $category->count . '</span></div>
							</a>
						</li>
					';
				}
			};

		$output .= '
						</ul>
					</div>
				</div>
			</section>
		';
		echo $output;
	}
endif;

/** Post article
 *
 * @since 2.2.0
*/

if ( !function_exists('zaxu_post_article') ) : 
	function zaxu_post_article($post_style, $post_type, $post_id, $post_block) {
		$category_name = null;

		// Get post block
		if ($post_block == "block") {
			$post_block_style = zaxu_get_field('zaxu_post_style');
			$post_block_attr_info = zaxu_get_field('zaxu_post_attr_info');
			$post_block_grid_ratio = zaxu_get_field('zaxu_post_grid_ratio');
			$post_block_carousel_ratio = zaxu_get_field('zaxu_post_carousel_ratio');
		} else {
			$post_block_style = null;
			$post_block_attr_info = null;
			$post_block_grid_ratio = null;
			$post_block_carousel_ratio = null;
		}

		// Get attribute information
		$recommend_attr_info = get_theme_mod('zaxu_recommend_attr_info', 'enabled');
		$blog_page_attr_info = get_theme_mod('zaxu_blog_page_attr_info', 'enabled');
		$portfolio_page_attr_info = get_theme_mod('zaxu_portfolio_page_attr_info', 'enabled');
		$search_attr_info = get_theme_mod('zaxu_search_attr_info', 'enabled');
		$archive_attr_info = get_theme_mod('zaxu_archive_attr_info', 'enabled');
		$product_attr_info = get_theme_mod('zaxu_product_attr_info', 'disabled');
		$placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";

		// Get featured image
		$source_image_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');

		// Get featured video
		$source_video_id = zaxu_get_field("zaxu_featured_video_file", $post_id);
		$source_video_file = zaxu_get_field("zaxu_featured_video_file", $post_id);
		$source_video_cover = zaxu_get_field("zaxu_featured_video_cover", $post_id);

		$source_type = "image";

		$default_dominant_color = ['#469CFF', '#FF934E', '#FF71BA', '#A053FF', '#FF7B7B'];

		// Get sticky tag start
			if ( $post_type == "post" && is_sticky($post_id) ) {
				$sticky_tag = '<span class="sticky">' . __('Sticky', 'zaxu') . '</span>';
			} else {
				$sticky_tag = null;
			}
		// Get sticky tag end

		// Get media data start
			if ( empty($source_image_thumbnail) && empty($source_video_id) ) {
				// No featured image & featured video
				$source_image_original = get_template_directory_uri() . '/assets/img/file-light-960x640.jpg';
				$source_image_retina = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
				$source_image_original_dark = get_template_directory_uri() . '/assets/img/file-dark-960x640.jpg';
				$source_image_retina_dark = get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg';

				// Get size
				list($width, $height) = getimagesize(get_template_directory() . '/assets/img/file-light-1920x1280.jpg');

				if ($post_style == "list") {
					$source_image_original = get_template_directory_uri() . '/assets/img/file-light-300x300.jpg';
					$source_image_retina = get_template_directory_uri() . '/assets/img/file-light-600x600.jpg';
					$source_image_original_dark = get_template_directory_uri() . '/assets/img/file-dark-300x300.jpg';
					$source_image_retina_dark = get_template_directory_uri() . '/assets/img/file-dark-600x600.jpg';

					// Get size
					list($width, $height) = getimagesize(get_template_directory() . '/assets/img/file-light-600x600.jpg');
				}

				// Set featured media start
					if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
						if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled") {
							$featured_media = '
								<picture class="featured-image">
									<source data-srcset="' . $source_image_original_dark . ' 1x, ' . $source_image_retina_dark . ' 2x" media="(prefers-color-scheme: dark)" />
									<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
								</picture>
							';
						} else {
							$featured_media = '
								<picture class="featured-image">
									<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
								</picture>
							';
						}
					} else {
						if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled") {
							$featured_media = '
								<picture class="featured-image">
									<source srcset="' . $source_image_original_dark . ' 1x, ' . $source_image_retina_dark . ' 2x" media="(prefers-color-scheme: dark)" />
									<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
								</picture>
							';
						} else {
							$featured_media = '
								<picture class="featured-image">
									<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
								</picture>
							';
						}
					}
				// Set featured media end

				// Set dominant color start
					$dominant_color = "#fff";
				// Set dominant color end
			} elseif ( empty($source_video_id) ) {
				// Has featured image, no featured video
				$source_image_original = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium_large')[0];
				$source_image_retina = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large')[0];

				if ($post_style == "list") {
					$source_image_original = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium')[0];
					$source_image_retina = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium_large')[0];
				} elseif ($post_style == "showcase") {
					$source_image_original = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large')[0];
					$source_image_retina = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full')[0];
				}
				
				// Get size
				$source = wp_get_attachment_metadata( get_post_thumbnail_id($post_id) );
				$width = $source['width'];
				$height = $source['height'];

				// Set featured media start
					if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
						$featured_media = '
							<picture class="featured-image">
								<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
							</picture>
						';
					} else {
						$featured_media = '
							<picture class="featured-image">
								<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
							</picture>
						';
					}
				// Set featured media end

				// Get dominant color start
					$dominant_color = get_post_meta(get_post_thumbnail_id($post_id), 'dominant_color', true);
					if ( empty($dominant_color) ) {
						$dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
					}
				// Get dominant color end
			} elseif ($source_video_id) {
				// Has featured video, no featured image
				$source_type = "video";

				// Get size
				$source = wp_get_attachment_metadata( $source_video_id['id'] );
				$width = $source['width'];
				$height = $source['height'];
				if ($source_video_cover) {
					// Has video cover
					$source_image_original = $source_video_cover['sizes']['medium_large'];
					$source_image_retina = $source_video_cover['sizes']['large'];

					if ($post_style == "list") {
						$source_image_original = $source_video_cover['sizes']['medium'];
						$source_image_retina = $source_video_cover['sizes']['medium_large'];
					} elseif ($post_style == "showcase") {
						$source_image_original = $source_video_cover['sizes']['large'];
						$source_image_retina = $source_video_cover['url'];
					}

					// Set featured media start
						if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
							$featured_media = '
								<picture class="featured-video-cover">
									<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
								</picture>
								<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $source_video_file['url'] . '" class="featured-video no-mejs zaxu-lazy"></video>
							';
						} else {
							$featured_media = '
								<picture class="featured-video-cover">
									<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
								</picture>
								<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $source_video_file['url'] . '" class="featured-video no-mejs"></video>
							';
						}
					// Set featured media end

					// Get dominant color start
						$dominant_color = get_post_meta($source_video_cover['id'], 'dominant_color', true);
						if ( empty($dominant_color) ) {
							$dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
						}
					// Get dominant color end
				} else {
					// No video cover
					$source_image_original = get_template_directory_uri() . '/assets/img/file-light-960x640.jpg';
					$source_image_retina = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
					$source_image_original_dark = get_template_directory_uri() . '/assets/img/file-dark-960x640.jpg';
					$source_image_retina_dark = get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg';

					if ($post_style == "list") {
						$source_image_original = get_template_directory_uri() . '/assets/img/file-light-300x300.jpg';
						$source_image_retina = get_template_directory_uri() . '/assets/img/file-light-600x600.jpg';
						$source_image_original_dark = get_template_directory_uri() . '/assets/img/file-dark-300x300.jpg';
						$source_image_retina_dark = get_template_directory_uri() . '/assets/img/file-dark-600x600.jpg';
					}

					// Set featured media start
						if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
							if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled") {
								$featured_media = '
									<picture class="featured-video-cover">
										<source data-srcset="' . $source_image_original_dark . ' 1x, ' . $source_image_retina_dark . ' 2x" media="(prefers-color-scheme: dark)" />
										<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
									</picture>
									<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $source_video_file['url'] . '" class="featured-video no-mejs zaxu-lazy"></video>
								';
							} else {
								$featured_media = '
									<picture class="featured-video-cover">
										<img width="' . $width . '" height="' . $height . '" src="' . $placeholder_img . '" data-src="' . $source_image_original . '" data-srcset="' . $source_image_retina . ' 2x" alt="' . get_the_title($post_id) . '" class="zaxu-lazy" />
									</picture>
									<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $source_video_file['url'] . '" class="featured-video no-mejs zaxu-lazy"></video>
								';
							}
						} else {
							if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled") {
								$featured_media = '
									<picture class="featured-video-cover">
										<source srcset="' . $source_image_original_dark . ' 1x, ' . $source_image_retina_dark . ' 2x" media="(prefers-color-scheme: dark)" />
										<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
									</picture>
									<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $source_video_file['url'] . '" class="featured-video no-mejs"></video>
								';
							} else {
								$featured_media = '
									<picture class="featured-video-cover">
										<img src="' . $source_image_original . '" srcset="' . $source_image_retina . ' 2x" width="' . $width . '" height="' . $height . '" alt="' . get_the_title($post_id) . '" />
									</picture>
									<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $source_video_file['url'] . '" class="featured-video no-mejs"></video>
								';
							}
						}
					// Set featured media end

					// Set dominant color start
						$dominant_color = "#fff";
					// Set dominant color end
				}
			}
		// Get media data end

		// Get author id start
			$author_id = get_post_field('post_author', $post_id);
		// Get author id end

		// Get avatar data start
			if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
				$avatar = '<img width="100" height="100" alt="' . get_the_author_meta('display_name', $author_id) . '" src="' . $placeholder_img . '" data-src="' . esc_url( get_avatar_url( get_the_author_meta('ID', $author_id), ['size' => '50'] ) ) . '" data-srcset="' . esc_url( get_avatar_url( get_the_author_meta('ID', $author_id), ['size' => '100'] ) ) . ' 2x" class="author-image zaxu-lazy" />';
			} else {
				$avatar = '<img src="' . esc_url( get_avatar_url( get_the_author_meta('ID', $author_id), ['size' => '50'] ) ) . '" srcset="' . esc_url( get_avatar_url( get_the_author_meta('ID', $author_id), ['size' => '100'] ) ) . ' 2x" width="100" height="100" alt="' . get_the_author_meta('display_name', $author_id) . '" class="author-image" />';
			}
		// Get avatar data end

		// Get cover ratio start
			if ($post_block == "block") {
				// Block mode
				if ($post_block_style == "grid") {
					$cover_ratio = $post_block_grid_ratio;
				} else {
					$cover_ratio = $post_block_carousel_ratio;
				}
			} else if ($post_block == "recommend") {
				// Recommend mode
				$cover_ratio = get_theme_mod('zaxu_recommended_post_cover_ratio', '4_3');
			} else {
				// Normal mode
				$cover_ratio = get_theme_mod('zaxu_' . $post_type . '_cover_ratio', 'responsive');
			}

			if ($cover_ratio == "responsive") {
				// Response
				$ratio = $width / $height;
				$targetWidth = 100 / $ratio * $ratio;
				$targetHeight = $targetWidth / $ratio;
			} else if ($cover_ratio == "1_1") {
				// 1:1
				$targetHeight = 100;
			} else if ($cover_ratio == "4_3") {
				// 4:3
				$targetHeight = 66.667;
			} else if ($cover_ratio == "16_9") {
				// 16:9
				$targetHeight = 56.215;
			}
		// Get cover ratio end
		
		// Get category name start
			$category_name = null;
			if ($post_block == "normal" && $post_type == 'post' || $post_block == "normal" && $post_type == 'portfolio') {
				// Get blog or portfolio category start
					$category_post_type = ($post_type == "post") ? 'category' : $post_type . '_category';
					$categories = wp_get_post_terms($post_id, $category_post_type);
					foreach($categories as $category) {
						$category_name .= zaxu_strToHex($category -> name) . " ";
					}
				// Get blog or portfolio category end
			} else if ( class_exists('WooCommerce') ) {
				// Get WooCommerce category start
					$categories = wp_get_post_terms($post_id, 'product_cat');
					foreach($categories as $category) {
						$category_name = zaxu_strToHex($category -> name) . " ";
					}
				// Get WooCommerce category end
			}
		// Get category name end

		// Get description start
			$description = esc_attr( get_post_meta($post_id, 'zaxu_seo_description', true) );
			if ( empty($description) && zaxu_excerpt($post_id, "has-desc") ) {
				// Excerpt
				if (
					$post_block == "block" && $post_style == "carousel" ||
					$post_block == "block" && $post_style == "grid" ||
					$post_block == "normal" && $post_style == "grid" ||
					$post_block == "recommend" && $post_style == "carousel"
				) {
					$excerpt = zaxu_excerpt($post_id, "no-desc");
				} else {
					$excerpt = zaxu_excerpt($post_id, "has-desc");
				}
				$description = '<p class="description">' . esc_attr( wp_strip_all_tags($excerpt) ) . '</p>';
			} else {
				// SEO description
				$description = '<p class="description">' . $description . '</p>';
			}
		// Get description end

		if ($post_style == "list") {
			// List mode
			echo '
				<article data-id="' . $post_id . '" class="' . $category_name . join(' ', get_post_class("list-article", $post_id) ) . '" itemscope itemtype="http://schema.org/Article">
					<div class="list-content">
						<a href="' . get_permalink($post_id) . '" class="list-link ajax-link">
							<div class="list-featured">
								<figure class="list-media" style="background-color: ' . $dominant_color . '">' . $featured_media . '</figure>
							</div>
							<div class="list-info">
								<header class="list-header">
									
			';

			if ($post_type == "product" || get_post_type($post_id) == "product") {
				echo '
						<h3 class="list-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						<div class="list-price">' . wc_get_product($post_id)->get_price_html() . '</div>
					</header>
					<ul class="list-attribution">
				';
			} else {
				echo '
						' . $sticky_tag . '
						<h3 class="list-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						' . $description . '
					</header>
					<ul class="list-attribution">
				';
			}

			// Attribution information start
				if (
					$recommend_attr_info == "enabled" && $post_type == "post" && $post_block == "recommend" ||
					$blog_page_attr_info == "enabled" && $post_type == "post" && $post_block == "normal" ||
					$portfolio_page_attr_info == "enabled" && $post_type == "portfolio" && $post_block == "normal" ||
					$search_attr_info == "enabled" && $post_type == "search" && $post_block == "normal" ||
					$archive_attr_info == "enabled" && $post_type == "archive" && $post_block == "normal" ||
					$product_attr_info == "enabled" && $post_type == "product" && $post_block == "normal" ||
					$post_block_attr_info == 1 && $post_type == "post" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "page" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "portfolio" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "product" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "specified" && $post_block == "block"
				) {
					echo '
						<li class="list-attribution-item author">' . get_the_author_meta('display_name', $author_id) . '</li>
						<li class="list-attribution-item pageview">' . zaxu_get_pageview($post_id) . '</li>
					';
				}
			// Attribution information end

			echo '
									<li class="list-attribution-item date">' . get_the_time("Y-m-d", $post_id) . '</li>
								</ul>
							</div>
						</a>
					</div>
				</article>
			';
		} else if ($post_style == "grid") {
			// Grid mode
			if ($post_type == "product" || get_post_type($post_id) == "product") {
				$tile_head = '
					<header class="tile-head">
						' . $sticky_tag . '
						<h3 class="tile-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						<div class="tile-price">' . wc_get_product($post_id)->get_price_html() . '</div>
					</header>
					' . (wc_get_product($post_id)->is_on_sale() ? '<span class="tile-badge sale background-blur">' . __('Sale', 'zaxu') . '</span>' : null) .
					(wc_get_product($post_id)->get_stock_status() == "outofstock" ? '<span class="tile-badge stock background-blur">' . __('Out of Stock', 'zaxu') . '</span>' : null) .
					(wc_get_product($post_id)->get_stock_status() == "onbackorder" ? '<span class="tile-badge stock background-blur">' . __('On Backorder', 'zaxu') . '</span>' : null) . '
				';
			} else {
				$tile_head = '
					<header class="tile-head">
						' . $sticky_tag . '
						<h3 class="tile-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						' . $description . '
						<time class="tile-date" datetime="' . get_the_time("c", $post_id) . '" itemprop="datePublished">' . get_the_time("Y-m-d", $post_id) . '</time>
					</header>
				';
			}

			// Summary display start
				if ($post_block == "normal" && $post_type == "post") {
					// Blog
					$sum_display_value = get_theme_mod('zaxu_blog_summary_display', 'separate');
				} else if ($post_block == "normal" && $post_type == "portfolio") {
					// Portfolio
					$sum_display_value = get_theme_mod('zaxu_portfolio_summary_display', 'separate');
				} else if ($post_block == "normal" && $post_type == "search") {
					// Search
					$sum_display_value = get_theme_mod('zaxu_search_summary_display', 'separate');
				} else if ($post_block == "normal" && $post_type == "archive") {
					// Archive
					$sum_display_value = get_theme_mod('zaxu_archive_summary_display', 'separate');
				} else if ($post_block == "normal" && $post_type == "product" || get_post_type($post_id) == "product") {
					// Product
					$sum_display_value = get_theme_mod('zaxu_product_summary_display', 'separate');
				} else if ($post_block == "block") {
					// Post block
					$sum_display_value = zaxu_get_field("zaxu_post_block_summary_display");
				}

				$sum_display_class = null;
				if ($sum_display_value == 'separate') {
					$sum_display_class = "tile-head-on-bottom ";
				} else if ($sum_display_value == 'disabled') {
					$sum_display_class = "tile-head-disabled ";
				}
			// Summary display end

			if (empty($source_image_thumbnail) && empty($source_video_id) && $post_type != "product" && get_post_type($post_id) != "product") {
				// No featured image & featured video
				$tile_content = '
					<div class="tile-context">
						<a href="' . get_permalink($post_id) . '" class="tile-link ajax-link">' . $tile_head . '</a>
					</div>
				';
			} else {
				// Has featured image or featured video
				if ($sum_display_value == 'disabled') {
					$tile_head = null;
				}
				$tile_content = '
					<div class="tile-featured">
						<a href="' . get_permalink($post_id) . '" class="tile-link ajax-link">
							<figure class="tile-media" style="padding-bottom: ' . $targetHeight . '%; background-color: ' . $dominant_color . '">' . $featured_media . '</figure>
						</a>
						' . $tile_head . '
					</div>
				';
			}

			echo '
				<article data-id="' . $post_id . '" class="' . $sum_display_class . $category_name . join(' ', get_post_class("tile-article", $post_id) ) . '" itemscope itemtype="http://schema.org/Article">
					<div class="tile-content">
						' . $tile_content . '
			';
			
			// Attribution information start
				if (
					$recommend_attr_info == "enabled" && $post_type == "post" && $post_block == "recommend" ||
					$blog_page_attr_info == "enabled" && $post_type == "post" && $post_block == "normal" ||
					$portfolio_page_attr_info == "enabled" && $post_type == "portfolio" && $post_block == "normal" ||
					$search_attr_info == "enabled" && $post_type == "search" && $post_block == "normal" ||
					$archive_attr_info == "enabled" && $post_type == "archive" && $post_block == "normal" ||
					$product_attr_info == "enabled" && $post_type == "product" && $post_block == "normal" ||
					$post_block_attr_info == 1 && $post_type == "post" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "page" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "portfolio" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "product" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "specified" && $post_block == "block"
				) {
					echo '
						<div class="tile-attribution">
							<div class="tile-author">
								<a href="' . get_author_posts_url( get_the_author_meta('ID', $author_id) ) . '" class="tile-author-link ajax-link">
									<picture class="tile-author-avatar">' . $avatar . '</picture>
									<span class="tile-author-name">' . get_the_author_meta('display_name', $author_id) . '</span>
								</a>
							</div>
							<ul class="tile-analytics-list">
								<li class="tile-action-item pageview">' . zaxu_get_pageview($post_id) . '</li>
							</ul>
						</div>
					';
				}
			// Attribution information end

			echo '	
					</div>
				</article>
			';
		} else if ($post_style == "showcase") {
			echo '
				<li class="swiper-slide">
					<article data-id="' . $post_id . '" class="' . $category_name . join(' ', get_post_class("showcase-article", $post_id) ) . '" itemscope itemtype="http://schema.org/Article">
						<a href="' . get_permalink($post_id) . '" class="showcase-link ajax-link">
							<div class="showcase-featured">
								<figure class="showcase-media" style="background-color: ' . $dominant_color . '">' . $featured_media . '</figure>
							</div>
						</a>
						<header class="showcase-head">
							<h3 class="showcase-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						</header>
					</article>
				</li>
			';
		} else if ($post_style == "carousel") {
			// Carousel mode
			if ($post_type == "product" || get_post_type($post_id) == "product") {
				$carousel_head = '
					<header class="carousel-head">
						' . $sticky_tag . '
						<h3 class="carousel-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						<div class="carousel-price">' . wc_get_product($post_id)->get_price_html() . '</div>
					</header>
					' . (wc_get_product($post_id)->is_on_sale() ? '<span class="carousel-badge sale background-blur">' . __('Sale', 'zaxu') . '</span>' : null) .
					(wc_get_product($post_id)->get_stock_status() == "outofstock" ? '<span class="carousel-badge stock background-blur">' . __('Out of Stock', 'zaxu') . '</span>' : null) .
					(wc_get_product($post_id)->get_stock_status() == "onbackorder" ? '<span class="carousel-badge stock background-blur">' . __('On Backorder', 'zaxu') . '</span>' : null) . '
				';
			} else {
				$carousel_head = '
					<header class="carousel-head">
						' . $sticky_tag . '
						<h3 class="carousel-headline">' . esc_attr( get_the_title($post_id) ) . '</h3>
						' . $description . '
						<time class="carousel-date" datetime="' . get_the_time("c", $post_id) . '" itemprop="datePublished">' . get_the_time("Y-m-d", $post_id) . '</time>
					</header>
				';
			}

			// Summary display start
				if ($post_block == "recommend") {
					// Recommended post
					$sum_display_value = get_theme_mod('zaxu_recommend_summary_display', 'separate');
				} else if ($post_block == "block") {
					// Post block
					$sum_display_value = zaxu_get_field("zaxu_post_block_summary_display");
				}

				$sum_display_class = null;
				if ($sum_display_value == 'separate') {
					$sum_display_class = "carousel-head-on-bottom ";
				} else if ($sum_display_value == 'disabled') {
					$sum_display_class = "carousel-head-disabled ";
					$carousel_head = null;
				}
			// Summary display end

			if ( empty($source_image_thumbnail) && empty($source_video_id) ) {
				// No featured image & featured video
				$carousel_content = '
					<div class="carousel-featured">
						<a href="' . get_permalink($post_id) . '" class="carousel-link ajax-link">
							<figure class="carousel-media" style="padding-bottom: ' . $targetHeight . '%; background-color: ' . $dominant_color . '">' . $featured_media . '</figure>
						</a>
						' . $carousel_head . '
					</div>
				';
			} else {
				// Has featured image or featured video
				$carousel_content = '
					<div class="carousel-featured">
						<a href="' . get_permalink($post_id) . '" class="carousel-link ajax-link">
							<figure class="carousel-media" style="padding-bottom: ' . $targetHeight . '%; background-color: ' . $dominant_color . '">' . $featured_media . '</figure>
						</a>
						' . $carousel_head . '
					</div>
				';
			}

			echo '
				<li class="swiper-slide">
					<article data-id="' . $post_id . '" class="' . $sum_display_class . $category_name . join(' ', get_post_class("carousel-article", $post_id) ) . '" itemscope itemtype="http://schema.org/Article">
						<div class="carousel-content">
							' . $carousel_content . '
			';

			// Attribution information start
				if (
					$recommend_attr_info == "enabled" && $post_type == "post" && $post_block == "recommend" ||
					$blog_page_attr_info == "enabled" && $post_type == "post" && $post_block == "normal" ||
					$portfolio_page_attr_info == "enabled" && $post_type == "portfolio" && $post_block == "normal" ||
					$search_attr_info == "enabled" && $post_type == "search" && $post_block == "normal" ||
					$archive_attr_info == "enabled" && $post_type == "archive" && $post_block == "normal" ||
					$product_attr_info == "enabled" && $post_type == "product" && $post_block == "normal" ||
					$post_block_attr_info == 1 && $post_type == "post" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "page" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "portfolio" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "product" && $post_block == "block" ||
					$post_block_attr_info == 1 && $post_type == "specified" && $post_block == "block"
				) {
					echo '
						<div class="carousel-attribution">
							<div class="carousel-author">
								<a href="' . get_author_posts_url( get_the_author_meta('ID', $author_id) ) . '" class="carousel-author-link ajax-link">
									<picture class="carousel-author-avatar">' . $avatar . '</picture>
									<span class="carousel-author-name">' . get_the_author_meta('display_name', $author_id) . '</span>
								</a>
							</div>
							<ul class="carousel-analytics-list">
								<li class="carousel-action-item pageview">' . zaxu_get_pageview($post_id) . '</li>
							</ul>
						</div>
					';
				}
			// Attribution information end

			echo '	
						</div>
					</article>
				</li>
			';
		}
	}
endif;

/** Pagination
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_post_pagination') ) :
	function zaxu_post_pagination($query = null) {
		if ($query === null) {
			global $wp_query;
			$query = $wp_query;
		}
		$page = $query->query_vars['paged'] === 0 ? 1 : $query->query_vars['paged'];
		$pages = $query->max_num_pages;
		$output = '';
		if ($pages > 1) {
			$output .= '
				<section class="post-pagination-container">
					<div class="post-pagination-box">
						<a class="prev ajax-link"' . ($page - 1 >= 1 ? ' href="' . esc_url( get_pagenum_link($page - 1) ) . '"' : '') . '></a>
						<a class="next ajax-link"' . ($page + 1 <= $pages ? ' href="' . esc_url( get_pagenum_link($page + 1) ) . '"' : '') . '></a>
					</div>
				</section>
			';
		}
		echo $output;
	}
endif;

/** No item tips
 *
 * @since 2.3.0
*/

if ( !function_exists('zaxu_no_item_tips') ) :
	function zaxu_no_item_tips($context) {
		echo '
			<section class="no-item-tips-container">
				' . zaxu_icon('information', 'icon') . '
				<span class="context">' . $context . '</span>
			</section>
		';
	}
endif;

/** Entry header 
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_entry_header') ) :
	function zaxu_entry_header() {
		if ( is_category() ) {
			$title = __('Category', 'zaxu');
			$keyword = single_cat_title('', false);
		} else if ( is_tag() ) {
			$title = __('Tag', 'zaxu');
			$keyword = single_tag_title('', false);
		} else if ( is_author() ) {
			$title = __('Author', 'zaxu');
			$keyword = get_the_author();
		} else if ( is_year() ) {
			$title = __('Year', 'zaxu');
			$keyword = get_the_time("Y");
		} else if ( is_month() ) {
			$title = __('Month', 'zaxu');
			$keyword = get_the_time("Y-m");
		} else if ( is_day() ) {
			$title = __('Day', 'zaxu');
			$keyword = get_the_time("Y-m-d");
		} else if ( is_post_type_archive() ) {
			$title =  __('Archives', 'zaxu');
			$keyword = post_type_archive_title('', false);
		} else if ( is_tax() ) {
			$tax = get_taxonomy(get_queried_object()->taxonomy);
			$title = $tax->labels->singular_name;
			$keyword = single_term_title('', false);
		} else if ( is_search() ) {
			$title = __('Search', 'zaxu');
			$keyword = get_search_query();
		} else {
			$title = __('Archives', 'zaxu');
			$keyword = '';
		}
		echo '<header class="entry-header section-inner">';
			if ($keyword == '') {
				echo '<h2 class="entry-title" itemprop="headline">' . $title . '</h2>';
			} else {
				echo '<h2 class="entry-title" itemprop="headline">' . $title . '<span class="entry-keyword">' . $keyword . '</span></h2>';
			}
		echo '</header>';
	}
endif;

/** Page break pagination
 *
 * @since 2.5.0
*/

if ( !function_exists('zaxu_page_break_pagination') ) :
	function zaxu_page_break_pagination() {
		$pagination = wp_link_pages(
			array(
				'before' => '
					<nav class="page-links">
						<span class="page-links-headline">' . __('Pagination', 'zaxu') . '</span>
						<div class="page-links-box">
				',
				'after' => '
						</div>
					</nav>
				',
				'pagelink' => '%',
				'echo' => 0,
			)
		);
		
		echo str_replace("post-page-numbers", "post-page-numbers ajax-link", $pagination);
	}
endif;

/** Post navigation
 *
 * @since 2.1.0
*/

if ( !function_exists('zaxu_post_navigation') ) :
	function zaxu_post_navigation($post_type) {
		if (get_theme_mod('zaxu_recommend_' . $post_type . '_navigation', 'enabled') == 'enabled') {
			$prev_post = get_adjacent_post(false, '', false, 'category');
			$next_post = get_adjacent_post(false, '', true, 'category');
			$default_img = get_template_directory_uri() . '/assets/img/file-light-960x640.jpg';
			$default_img_retina = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
			$default_img_dark = get_template_directory_uri() . '/assets/img/file-dark-960x640.jpg';
			$default_img_dark_retina = get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg';
			$placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
			$default_dominant_color = ['#469CFF', '#FF934E', '#FF71BA', '#A053FF', '#FF7B7B'];
			
			// Detect dynamic color picture source start
				if (get_theme_mod('zaxu_dynamic_color', 'disabled') == "enabled" && $prev_post || $next_post) {
					$dynamic_color_source = '<source srcset="' . $default_img_dark . ' 1x, ' . $default_img_dark_retina . ' 2x" media="(prefers-color-scheme: dark)" />';
				} else {
					$dynamic_color_source = null;
				}
			// Detect dynamic color picture source end

			// Prev post media data start
				if ($prev_post) {
					$prev_post_id = $prev_post->ID;
					$prev_post_link = get_the_permalink($prev_post_id);
					$prev_post_title = esc_attr( get_the_title($prev_post) );
					$featured_video_file = zaxu_get_field("zaxu_featured_video_file", $prev_post_id);
					$featured_video_cover = zaxu_get_field("zaxu_featured_video_cover", $prev_post_id);

					// Detect featured media start
						if ($featured_video_file) {
							// Detect lazyload start
								if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
									$feature_video_html = '
										<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $featured_video_file['url'] . '" class="post-navigation-featured-video no-mejs swiper-lazy"></video>
									';
								} else {
									$feature_video_html = '
										<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $featured_video_file['url'] . '" class="post-navigation-featured-video no-mejs"></video>
									';
								}
							// Detect lazyload end
							
							if ($featured_video_cover) {
								// Has featured video & featured cover start
									$featured_video_cover_img = $featured_video_cover['sizes']['medium_large'];
									$featured_video_cover_img_retina = $featured_video_cover['sizes']['large'];

									// Detect lazyload start
										if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
											$prev_post_media = '
												<picture class="post-navigation-featured-video-cover">
													<img data-src="' . $featured_video_cover_img . '" data-srcset="' . $featured_video_cover_img_retina . ' 2x" alt="' . $prev_post_title . '" class="swiper-lazy" />
												</picture>
												' . $feature_video_html . '
											';
										} else {
											$prev_post_media = '
												<picture class="post-navigation-featured-video-cover">
													<img src="' . $featured_video_cover_img . '" srcset="' . $featured_video_cover_img_retina . ' 2x" alt="' . $prev_post_title . '" />
												</picture>
												' . $feature_video_html . '
											';
										}
									// Detect lazyload end
								// Has featured video & featured cover end

								// Get dominant color start
									$prev_post_dominant_color = get_post_meta($featured_video_cover['id'], 'dominant_color', true);
									if ( empty($prev_post_dominant_color) ) {
										$prev_post_dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
									}
								// Get dominant color end
							} else {
								// Has featured video & no featured cover start
									// Detect lazyload start
										if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
											$prev_post_media = '
												<picture class="post-navigation-featured-video-cover">
													' . $dynamic_color_source . '
													<img data-src="' . $default_img . '" data-srcset="' . $default_img_retina . ' 2x" alt="' . $prev_post_title . '" class="swiper-lazy" />
												</picture>
												' . $feature_video_html . '
											';
										} else {
											$prev_post_media = '
												<picture class="post-navigation-featured-video-cover">
													' . $dynamic_color_source . '
													<img src="' . $default_img . '" srcset="' . $default_img_retina . ' 2x" alt="' . $prev_post_title . '" />
												</picture>
												' . $feature_video_html . '
											';
										}
									// Detect lazyload end
								// Has featured video & no featured cover end

								// Set dominant color start
									$prev_post_dominant_color = "#fff";
								// Set dominant color end
							}
						} else if ( has_post_thumbnail($prev_post) ) {
							// Has featured image no featured video start
								$prev_post_img = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post), 'medium')[0];
								$prev_post_img_retina = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post), 'large')[0];

								// Detect lazyload start
									if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
										$prev_post_media = '
											<picture class="post-navigation-featured-image">
												<img src="' . $placeholder_img . '" data-src="' . $prev_post_img . '" data-srcset="' . $prev_post_img_retina . ' 2x" alt="' . $prev_post_title . '" class="swiper-lazy" />
											</picture>
										';
									} else {
										$prev_post_media = '
											<picture class="post-navigation-featured-image">
												<img src="' . $prev_post_img . '" srcset="' . $prev_post_img_retina . ' 2x" alt="' . $prev_post_title . '" />
											</picture>
										';
									}
								// Detect lazyload end
							// Has featured image no featured video end

							// Get dominant color start
								$prev_post_dominant_color = get_post_meta(get_post_thumbnail_id($prev_post_id), 'dominant_color', true);
								if ( empty($prev_post_dominant_color) ) {
									$prev_post_dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
								}
							// Get dominant color end
						} else {
							// No featured image & featured video start
								$prev_post_img = $default_img;
								$prev_post_img_retina = $default_img_retina;
								
								// Detect lazyload start
									if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
										$prev_post_media = '
											<picture class="post-navigation-featured-image">
												' . $dynamic_color_source . '
												<img src="' . $placeholder_img . '" data-src="' . $prev_post_img . '" data-srcset="' . $prev_post_img_retina . ' 2x" alt="' . $prev_post_title . '" class="swiper-lazy" />
											</picture>
										';
									} else {
										$prev_post_media = '
											<picture class="post-navigation-featured-image">
												' . $dynamic_color_source . '
												<img src="' . $prev_post_img . '" srcset="' . $prev_post_img_retina . ' 2x" alt="' . $prev_post_title . '" />
											</picture>
										';
									}
								// Detect lazyload end
							// No featured image & featured video end

							// Set dominant color start
								$prev_post_dominant_color = "#fff";
							// Set dominant color end
						}
					// Detect featured media end

					// Headline
					$prev_post_headline = '
						<li class="post-navigation-headline">
							<span class="post-navigation-tagline">' . __("Prev Post", 'zaxu') . '</span>
							<span class="post-navigation-title">' . $prev_post_title . '</span>
						</li>
					';
				}
			// Prev post media data end
			
			// Next post media data start
				if ($next_post) {
					$next_post_id = $next_post->ID;
					$next_post_link = get_the_permalink($next_post_id);
					$next_post_title = esc_attr( get_the_title($next_post) );
					$featured_video_file = zaxu_get_field("zaxu_featured_video_file", $next_post_id);
					$featured_video_cover = zaxu_get_field("zaxu_featured_video_cover", $next_post_id);

					// Detect featured media start
						if ($featured_video_file) {
							// Detect lazyload start
								if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
									$feature_video_html = '
										<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" data-src="' . $featured_video_file['url'] . '" class="post-navigation-featured-video no-mejs swiper-lazy"></video>
									';
								} else {
									$feature_video_html = '
										<video muted loop webkit-playsinline playsinline x5-video-player-type="h5-page" src="' . $featured_video_file['url'] . '" class="post-navigation-featured-video no-mejs"></video>
									';
								}
							// Detect lazyload end

							if ($featured_video_cover) {
								// Has featured video & featured cover start
									$featured_video_cover_img = $featured_video_cover['sizes']['medium_large'];
									$featured_video_cover_img_retina = $featured_video_cover['sizes']['large'];

									// Detect lazyload start
										if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
											$next_post_media = '
												<picture class="post-navigation-featured-video-cover">
													<img data-src="' . $featured_video_cover_img . '" data-srcset="' . $featured_video_cover_img_retina . ' 2x" alt="' . $next_post_title . '" class="swiper-lazy" />
												</picture>
												' . $feature_video_html . '
											';
										} else {
											$next_post_media = '
												<picture class="post-navigation-featured-video-cover">
													<img src="' . $featured_video_cover_img . '" srcset="' . $featured_video_cover_img_retina . ' 2x" alt="' . $next_post_title . '" />
												</picture>
												' . $feature_video_html . '
											';
										}
									// Detect lazyload end
								// Has featured video & featured cover end

								// Get dominant color start
									$next_post_dominant_color = get_post_meta($featured_video_cover['id'], 'dominant_color', true);
									if ( empty($next_post_dominant_color) ) {
										$next_post_dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
									}
								// Get dominant color end
							} else {
								// Has featured video & no featured cover start
									// Detect lazyload start
										if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
											$next_post_media = '
												<picture class="post-navigation-featured-video-cover">
													' . $dynamic_color_source . '
													<img data-src="' . $default_img . '" data-srcset="' . $default_img_retina . ' 2x" alt="' . $next_post_title . '" class="swiper-lazy" />
												</picture>
												' . $feature_video_html . '
											';
										} else {
											$next_post_media = '
												<picture class="post-navigation-featured-video-cover">
													' . $dynamic_color_source . '
													<img src="' . $default_img . '" srcset="' . $default_img_retina . ' 2x" alt="' . $next_post_title . '" />
												</picture>
												' . $feature_video_html . '
											';
										}
									// Detect lazyload end
								// Has featured video & no featured cover end

								// Set dominant color start
									$next_post_dominant_color = "#fff";
								// Set dominant color end
							}
						} else if ( has_post_thumbnail($next_post) ) {
							// Has featured image no featured video start
								$next_post_img = wp_get_attachment_image_src(get_post_thumbnail_id($next_post), 'medium')[0];
								$next_post_img_retina = wp_get_attachment_image_src(get_post_thumbnail_id($next_post), 'large')[0];

								// Detect lazyload start
									if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
										$next_post_media = '
											<picture class="post-navigation-featured-image">
												<img src="' . $placeholder_img . '" data-src="' . $next_post_img . '" data-srcset="' . $next_post_img_retina . ' 2x" alt="' . $next_post_title . '" class="swiper-lazy" />
											</picture>
										';
									} else {
										$next_post_media = '
											<picture class="post-navigation-featured-image">
												<img src="' . $next_post_img . '" srcset="' . $next_post_img_retina . ' 2x" alt="' . $next_post_title . '" />
											</picture>
										';
									}
								// Detect lazyload end
							// Has featured image no featured video end

							// Get dominant color start
								$next_post_dominant_color = get_post_meta(get_post_thumbnail_id($next_post_id), 'dominant_color', true);
								if ( empty($next_post_dominant_color) ) {
									$next_post_dominant_color = $default_dominant_color[ mt_rand(0, count($default_dominant_color) - 1) ];
								}
							// Get dominant color end
						} else {
							// No featured image & featured video start
								$next_post_img = $default_img;
								$next_post_img_retina = $default_img_retina;

								// Detect lazyload start
									if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
										$next_post_media = '
											<picture class="post-navigation-featured-image">
												' . $dynamic_color_source . '
												<img src="' . $placeholder_img . '" data-src="' . $next_post_img . '" data-srcset="' . $next_post_img_retina . ' 2x" alt="' . $next_post_title . '" class="swiper-lazy" />
											</picture>
										';
									} else {
										$next_post_media = '
											<picture class="post-navigation-featured-image">
												' . $dynamic_color_source . '
												<img src="' . $next_post_img . '" srcset="' . $next_post_img_retina . ' 2x" alt="' . $next_post_title . '" />
											</picture>
										';
									}
								// Detect lazyload end
							// No featured image & featured video end

							// Set dominant color start
								$next_post_dominant_color = "#fff";
							// Set dominant color end
						}
					// Detect featured media end

					// Headline
					$next_post_headline = '
						<li class="post-navigation-headline">
							<span class="post-navigation-tagline">' . __("Next Post", 'zaxu') . '</span>
							<span class="post-navigation-title">' . $next_post_title . '</span>
						</li>
					';
				}
			// Next post media data end

			if ($prev_post || $next_post) {
				// Has prev or next post start
					if ($prev_post && $next_post) {
						// Has prev & next post
						$post_nav_item = '
							<li class="swiper-slide">
								<a href="' . $prev_post_link . '" class="post-navigation-link ajax-link">
									<figure class="post-navigation-featured-media" style="background-color: ' . $prev_post_dominant_color . '">' . $prev_post_media . '</figure>
								</a>
							</li>
							<li class="swiper-slide">
								<a href="' . $next_post_link . '" class="post-navigation-link ajax-link">
									<figure class="post-navigation-featured-media" style="background-color: ' . $next_post_dominant_color . '">' . $next_post_media . '</figure>
								</a>
							</li>
						';

						// Has prev & next post head
						$post_nav_head = $prev_post_headline . $next_post_headline;
					} else {
						if ($prev_post) {
							// Has prev post
							$post_nav_item = '
								<li class="swiper-slide">
									<a href="' . $prev_post_link . '" class="post-navigation-link ajax-link">
										<figure class="post-navigation-featured-media" style="background-color: ' . $prev_post_dominant_color . '">' . $prev_post_media . '</figure>
									</a>
								</li>
							';

							// Has prev post head
							$post_nav_head = $prev_post_headline;
						}
		
						if ($next_post) {
							// Has next post
							$post_nav_item = '
								<li class="swiper-slide">
									<a href="' . $next_post_link . '" class="post-navigation-link ajax-link">
										<figure class="post-navigation-featured-media" style="background-color: ' . $next_post_dominant_color . '">' . $next_post_media . '</figure>
									</a>
								</li>
							';

							// Has next post head
							$post_nav_head = $next_post_headline;
						}
					}
				// Has prev or next post end

				// Get post navigation ratio start
					$post_nav_ratio = get_theme_mod('zaxu_recommend_' . $post_type . '_navigation_ratio', '4_3');
				// Get post navigation ratio end

				$output = '
					<section class="post-navigation-container">
						<div class="post-navigation-box">
							<div class="post-navigation-content" data-ratio="' . $post_nav_ratio . '">
								<gallery>
									<div class="swiper-container">
										<ul class="swiper-wrapper">' . $post_nav_item . '</ul>
										<div class="zaxu-swiper-button-next"></div>
										<div class="zaxu-swiper-button-prev"></div>
										<div class="swiper-pagination"></div>
									</div>
								</gallery>
							</div>
							<ul class="post-navigation-head">' . $post_nav_head . '</ul>
						</div>
					</section>
				';
			} else {
				// No prev & next post start
					$output = null;
				// No prev & next post end
			}

			return $output;
		}
	}
endif;

/** Wrapper end
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_wrapper_end') ) :
	function zaxu_wrapper_end() {
		echo '
                </main>
            </div>
        ';
	}
endif;

/** Redefine the_excerpt
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_excerpt') ) :
	function zaxu_excerpt($post_id, $status) {
		$output = null;
	    if ( has_excerpt($post_id) ) {
	    	// Has excerpt content
	    	$output = get_the_excerpt($post_id);
		    $output = apply_filters('wptexturize', $output);
		    $output = apply_filters('convert_chars', $output);
		    $str_length = mb_strlen($output, 'utf-8');
		    if ($str_length > 140) {
		    	$output = mb_substr($output, 0, 140, 'utf-8') . "...";
		    } else {
		    	$output = mb_substr($output, 0, 140, 'utf-8');
			}
	    } elseif ($status == "has-desc") {
			// No excerpt content
			$page_object = get_page($post_id);
			$content = $page_object->post_content;
			if ( $content && !post_password_required() ) {
				$output = strip_tags( preg_replace("~(?:\[/?)[^\]]+/?\]~s", '', $content) );
				$output = str_replace(array("\r", "\n"), "", $output);
				$str_length = mb_strlen($output, 'utf-8');
				if ($str_length > 140) {
					$output = mb_substr($output, 0, 140, 'utf-8') . "...";
				}
			}
		}
	    return $output;
	}
endif;

/** Screen client support
 *
 * @since 2.3.0
*/

if ( !function_exists('zaxu_screen_client_support') ) :
	function zaxu_screen_client_support() {
		$screen_support = get_theme_mod('zaxu_screen_client_support');
		if ($screen_support == 'wechat') {
			if ( !is_customize_preview() ) {
				echo '
					<script type="text/javascript">
						var useragent = navigator.userAgent;
						if (useragent.match(/MicroMessenger/i) != "MicroMessenger") {
							alert("' . __('Please visit this page on WeChat.', 'zaxu') . '");
							var opened = window.open("about:blank", "_self");
							opened.opener = null;
						    opened.close();
						}
					</script>
				';
			}
		} else if ($screen_support == 'desktop') {
			if ( !is_customize_preview() ) {
				echo '
					<script type="text/javascript">
						if (navigator.userAgent.match(/mobile/i)) {
							alert("' . __('Please visit this page on desktop.', 'zaxu') . '");
							var opened = window.open("about:blank", "_self");
							opened.opener = null;
						    opened.close();
						}
					</script>
				';
			}
		} else if ($screen_support == 'mobile') {
			if ( !is_customize_preview() ) {
				echo '
					<script type="text/javascript">
						if (!navigator.userAgent.match(/mobile/i)) {
							alert("' . __('Please visit this page on mobile.', 'zaxu') . '");
							var opened = window.open("about:blank", "_self");
							opened.opener = null;
						    opened.close();
						}
					</script>
				';
			}
		}
	}
endif;

/** Navigation
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_navigation') ) :
	function zaxu_navigation() {
		// No primary menu start
			function no_primary_menu() {
				if ( is_user_logged_in() ) {
					$menu_item = '
						<li class="menu-item">
							<a href="' . esc_url( admin_url('nav-menus.php') ) . '">' . __('Edit Menu', 'zaxu') . '</a>
						</li>
					';
				} else {
					$menu_item = null;
				}
				
				echo '
					<nav class="menu-navigation-container" role="navigation">
						<ul class="main-menu">
							<li class="menu-item">
								<a href="' . esc_url( home_url('/') ) . '" class="ajax-link">' . __('Home', 'zaxu') . '</a>
							</li>
							' . $menu_item . '
						</ul>
					</nav>
				';
			}
		// No primary menu end

		// Get WP navigation start
			function zaxu_get_wp_navigation() {
				if ( has_nav_menu('primary') ) {
					$primary_menu = wp_nav_menu(
						array (
							'menu' => '',
							'container' => 'nav',
							'container_class' => '',
							'container_id' => '',
							'menu_class' => 'main-menu',
							'menu_id' => '',
							'echo' => false,
							'fallback_cb' => '__return_false',
							'before' => '',
							'after' => '',
							'link_before' => '',
							'link_after' => '',
							'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'item_spacing' => 'preserve',
							'depth' => 0,
							'walker' => '',
							'theme_location' => 'primary',
						)
					);

					if ( empty($primary_menu) ) {
						no_primary_menu();
					} else {
						echo $primary_menu;
					}
				} else {
					no_primary_menu();
				}
			}
		// Get WP navigation end

		// Add sub menu toggle start
			function zaxu_add_sub_menu_toggle($item_output, $item, $depth, $args) {
				if ($args->theme_location == 'primary') {
					if ( in_array('menu-item-has-children', $item->classes) ) {
						$item_output = str_replace(
							$args->link_after . '</a>',
							$args->link_after . '</a><div class="sub-menu-toggle"></div>',
							$item_output
						);
					}
				}
				return $item_output;
			}
			add_filter('walker_nav_menu_start_el', 'zaxu_add_sub_menu_toggle', 10, 4);
		// Add sub menu toggle end

		// Get navigation logo
		function zaxu_navigation_logo() {
			$logo = get_theme_mod('zaxu_logo');
			$is_logo = is_file( $_SERVER['DOCUMENT_ROOT'] . '/' . parse_url($logo, PHP_URL_PATH) );
			$logo_height = esc_attr( get_theme_mod('zaxu_logo_height', 30) );
			$placeholder_img = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E";
			if ($logo && $is_logo) {
				$filetype = wp_check_filetype($logo)['ext'];
				if ($filetype == "svg") {
					$svg_inline_content = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . '/' . parse_url($logo, PHP_URL_PATH) );
					$svg_inline_content_new = str_replace("<svg", "<svg preserveAspectRatio='xMinYMid'", $svg_inline_content);
					echo '
						<span class="link-title">' . esc_attr( get_bloginfo('name') ) . '</span>
					';
					echo $svg_inline_content_new;
				} else {
					if (get_theme_mod('zaxu_lazyload', 'enabled') == "enabled") {
						echo '
							<img src="' . $placeholder_img . '" data-src="' . $logo . '" alt="' . esc_attr( get_bloginfo('name') ) . '" itemprop="logo" style="height: ' . $logo_height . 'px" class="zaxu-lazy" />
						';
					} else {
						echo '
							<img src="' . $logo . '" alt="' . esc_attr( get_bloginfo('name') ) . '" itemprop="logo" style="height: ' . $logo_height . 'px" />
						';
					}
				}
			} else {
				echo '
					<h2>
						<span itemprop="name">' . esc_attr( get_bloginfo('name') ) . '</span>
					</h2>
				';
			}
		};

		// Get navigation status
		if (get_theme_mod('zaxu_navigation_status', 'sticky') === 'sticky') {
			$navigation_status = "sticky";
		} else if (get_theme_mod('zaxu_navigation_status', 'sticky') === 'auto') {
			$navigation_status = "auto";
		} else if (get_theme_mod('zaxu_navigation_status', 'sticky') === 'normal') {
			$navigation_status = "normal";
		};

		// Get navigation logo status
		if ( get_theme_mod('zaxu_logo') ) {
			$image_logo_status = 'image-logo-enabled';
		} else {
			$image_logo_status = 'image-logo-disabled';
		}

		// Get hamburger menu status
		if (get_theme_mod('zaxu_hamburger_menu', 'response') === 'always') {
			$hamburger_menu_status = "hamburger-menu-always-display";
		} else if (get_theme_mod('zaxu_hamburger_menu', 'response') === 'response') {
			$hamburger_menu_status = "hamburger-menu-response-display";
		}

		// Get shopping bag toggle status
		function zaxu_shopping_bag_toggle() {
			if ( class_exists('WooCommerce') ) {
				if (get_theme_mod('zaxu_shopping_bag', 'enabled') === 'enabled') {
					$woocommerce_cart_count = WC()->cart->get_cart_contents_count();
					if ($woocommerce_cart_count != 0) {
						$badge =  '<span class="badge">' . $woocommerce_cart_count . '</span>';
					} else {
						$badge = null;
					}
					echo '
						<li class="content-item shopping-bag-toggle">
							' . $badge . '
							<div class="shopping-bag-content">
								<div class="shopping-bag-list">
									<h3 class="shopping-bag-title">' . __('My Cart', 'zaxu') . '</h3>
					';
					woocommerce_mini_cart();
					echo '
								</div">
							</div>
						</li>
					';
				}
			}
		}

		// Get search status
		if (get_theme_mod('zaxu_site_search', 'enabled') == 'enabled') {
			$search_status = 'search-enabled';
		} else {
			$search_status = 'search-disabled';
		}

		// Get search toggle status
		function zaxu_search_toggle() {
			if (get_theme_mod('zaxu_site_search', 'enabled') == 'enabled') {
				echo '<li class="content-item search-toggle"></li>';
			}
		}

		// Get search bar status
		function zaxu_search($device) {
			if (get_theme_mod('zaxu_site_search', 'enabled') == 'enabled') {
				if ($device == "desktop") {
					echo '<section class="site-search-container site-overlay-element desktop">';
						echo '<div class="search-bar-box section-inner">';
							get_search_form();
						echo '</div>';
					echo '</section>';
				} else {
					echo '<div class="site-search-container section-inner">';
						get_search_form();
					echo '</div>';
				}
			}
		}

		// Get background music toggle status
		function zaxu_background_music_toggle() {
			if ( get_theme_mod('zaxu_background_music') ) {
				echo '
					<li class="content-item background-music-toggle">
						<div class="buffering-icon"></div>
						<div class="equalizer-icon">
							<span class="line"></span>
							<span class="line"></span>
							<span class="line"></span>
						</div>
					</li>
				';
			}
		}

		// Get share toggle status
		function zaxu_share_toggle() {
			if (get_theme_mod('zaxu_site_share', 'enabled') === 'enabled') {
				echo '
					<li class="content-item share-toggle">
						<div class="share-icon"></div>
					</li>
				';
			}
		}

		echo '<header class="site-navigation-container ' . $navigation_status . ' ' . $hamburger_menu_status . ' ' . $image_logo_status . ' ' . $search_status . '" role="banner">';
			echo '<div class="navigation-holder section-inner">';
				
				echo '<a href="' . esc_url( home_url('/') ) . '" class="navigation-logo ajax-link">';
					zaxu_navigation_logo();
				echo '</a>';
				
				echo '<ul class="content-list">';
					echo '<li class="content-item normal-menu-container">';
						zaxu_get_wp_navigation();
					echo '</li>';
					zaxu_background_music_toggle();
					zaxu_share_toggle();
					zaxu_search_toggle();
					zaxu_shopping_bag_toggle();
					echo '<li class="content-item hamburger-menu-toggle"></li>';
				echo '</ul>';

			echo '</div>';
		echo '</header>';
		
		echo '<section class="site-hamburger-menu-container">';
			zaxu_search("mobile");
			echo '<div class="hamburger-menu-content section-inner">';
				zaxu_get_wp_navigation();
			echo '</div>';
		echo '</section>';
	}
endif;

/** Search
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_search_form') ) :
	function zaxu_search_form($form) {
	    $form = '
			<form role="search" method="get" class="searchform" action="' . esc_url( home_url('/') ) . '" >
				<span class="search-icon"></span>
				<input type="search" autocomplete="off" placeholder="' . esc_html__('Search', 'zaxu') . '" name="s" />
		    </form>
	   ';
	    return $form;
	}
endif;
add_filter('get_search_form', 'zaxu_search_form');

/** Social icon
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_social_icon') ) :
	function zaxu_social_icon() {
		$social_item = null;
		if (get_theme_mod('zaxu_social_icon', 'disabled') === 'enabled') {
			$zaxu = zaxu_icon('zaxu', 'icon');
			$email = zaxu_icon('email', 'icon');
			$wechat = zaxu_icon('wechat', 'icon');
			$wechat_mini_program = zaxu_icon('wechat_mini_program', 'icon');
			$tiktok = zaxu_icon('tiktok', 'icon');
			$kwai = zaxu_icon('kwai', 'icon');
			$weibo = zaxu_icon('weibo', 'icon');
			$qq = zaxu_icon('qq', 'icon');
			$qzone = zaxu_icon('qzone', 'icon');
			$zhihu = zaxu_icon('zhihu', 'icon');
			$zcool = zaxu_icon('zcool', 'icon');
			$huaban = zaxu_icon('huaban', 'icon');
			$lofter = zaxu_icon('lofter', 'icon');
			$tieba = zaxu_icon('tieba', 'icon');
			$xiongzhang = zaxu_icon('xiongzhang', 'icon');
			$jianshu = zaxu_icon('jianshu', 'icon');
			$xiaohongshu = zaxu_icon('xiaohongshu', 'icon');
			$douban = zaxu_icon('douban', 'icon');
			$netease_music = zaxu_icon('netease_music', 'icon');
			$taobao = zaxu_icon('taobao', 'icon');
			$youku = zaxu_icon('youku', 'icon');
			$bilibili = zaxu_icon('bilibili', 'icon');
			$youtube = zaxu_icon('youtube', 'icon');
			$google_plus = zaxu_icon('google_plus', 'icon');
			$github = zaxu_icon('github', 'icon');
			$gitee = zaxu_icon('gitee', 'icon');
			$codepen = zaxu_icon('codepen', 'icon');
			$five_hundred_px = zaxu_icon('500px', 'icon');
			$behance = zaxu_icon('behance', 'icon');
			$dribbble = zaxu_icon('dribbble', 'icon');
			$facebook = zaxu_icon('facebook', 'icon');
			$instagram = zaxu_icon('instagram', 'icon');
			$line = zaxu_icon('line', 'icon');
			$linkedin = zaxu_icon('linkedin', 'icon');
			$pinterest = zaxu_icon('pinterest', 'icon');
			$skype = zaxu_icon('skype', 'icon');
			$snapchat = zaxu_icon('snapchat', 'icon');
			$soundcloud = zaxu_icon('soundcloud', 'icon');
			$twitter = zaxu_icon('twitter', 'icon');
			$medium = zaxu_icon('medium', 'icon');
			$rss = zaxu_icon('rss', 'icon');
			$flickr = zaxu_icon('flickr', 'icon');
			$vimeo = zaxu_icon('vimeo', 'icon');
			$whatsapp = zaxu_icon('whatsapp', 'icon');
			$wordpress = zaxu_icon('wordpress', 'icon');
			$slack = zaxu_icon('slack', 'icon');
		}

		// Social item start
			if (get_theme_mod('zaxu_social_zaxu') != '') {
				$social_item .= '
					<li>
						<a class="zaxu" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_zaxu') ) . '" title="' . esc_html__('Learn more about this theme...', 'zaxu') . '">' . $zaxu . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_email') != '') {
				$social_item .= '
					<li>
						<a class="email"  href="mailto:' . esc_attr( get_theme_mod('zaxu_social_email') ) . '">' . $email . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_wechat_qr_code') != '') {
				$social_item .= '
					<li>
						<a class="wechat" href="javascript:;">' . $wechat . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_wechat_mini_program_qr_code') != '') {
				$social_item .= '
					<li>
						<a class="wechat-mini-program" href="javascript:;">' . $wechat_mini_program . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_tiktok_qr_code') != '') {
				$social_item .= '
					<li>
						<a class="tiktok" href="javascript:;">' . $tiktok . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_kwai_qr_code') != '') {
				$social_item .= '
					<li>
						<a class="kwai" href="javascript:;">' . $kwai . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_weibo') != '') {
				$social_item .= '
					<li>
						<a class="weibo" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_weibo') ) . '" rel="nofollow">' . $weibo . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_qq') != '') {
				$social_item .= '
					<li>
						<a class="qq" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_qq') ) . '">' . $qq . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_qzone') != '') {
				$social_item .= '
					<li>
						<a class="qzone" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_qzone') ) . '" rel="nofollow">' . $qzone . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_zhihu') != '') {
				$social_item .= '
					<li>
						<a class="zhihu" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_zhihu') ) . '" rel="nofollow">' . $zhihu . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_zcool') != '') {
				$social_item .= '
					<li>
						<a class="zcool" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_zcool') ) . '" rel="nofollow">' . $zcool . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_huaban') != '') {
				$social_item .= '
					<li>
						<a class="huaban" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_huaban') ) . '" rel="nofollow">' . $huaban . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_lofter') != '') {
				$social_item .= '
					<li>
						<a class="lofter" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_lofter') ) . '" rel="nofollow">' . $lofter . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_tieba') != '') {
				$social_item .= '
					<li>
						<a class="tieba" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_tieba') ) . '" rel="nofollow">' . $tieba . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_xiongzhang') != '') {
				$social_item .= '
					<li>
						<a class="xiongzhang" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_xiongzhang') ) . '" rel="nofollow">' . $xiongzhang . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_jianshu') != '') {
				$social_item .= '
					<li>
						<a class="jianshu" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_jianshu') ) . '" rel="nofollow">' . $jianshu . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_xiaohongshu') != '') {
				$social_item .= '
					<li>
						<a class="xiaohongshu" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_xiaohongshu') ) . '" rel="nofollow">' . $xiaohongshu . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_douban') != '') {
				$social_item .= '
					<li>
						<a class="douban" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_douban') ) . '" rel="nofollow">' . $douban . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_netease_music') != '' ) {
				$social_item .= '
					<li>
						<a class="netease-music" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_netease_music') ) . '" rel="nofollow">' . $netease_music . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_taobao') != '') {
				$social_item .= '
					<li>
						<a class="taobao" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_taobao') ) . '" rel="nofollow">' . $taobao . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_youku') != '') {
				$social_item .= '
					<li>
						<a class="youku" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_youku') ) . '" rel="nofollow">' . $youku . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_bilibili') != '') {
				$social_item .= '
					<li>
						<a class="bilibili" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_bilibili') ) . '" rel="nofollow">' . $bilibili . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_youtube') != '') {
				$social_item .= '
					<li>
						<a class="youtube" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_youtube') ) . '" rel="nofollow">' . $youtube . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_google_plus') != '') {
				$social_item .= '
					<li>
						<a class="google-plus" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_google_plus') ) . '" rel="nofollow">' . $google_plus . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_github') != '') {
				$social_item .= '
					<li>
						<a class="github" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_github') ) . '" rel="nofollow">' . $github . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_gitee') != '') {
				$social_item .= '
					<li>
						<a class="gitee" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_gitee') ) . '" rel="nofollow">' . $gitee . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_codepen') != '') {
				$social_item .= '
					<li>
						<a class="codepen" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_codepen') ) . '" rel="nofollow">' . $codepen . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_500px') != '') {
				$social_item .= '
					<li>
						<a class="500px" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_500px') ) . '" rel="nofollow">' . $five_hundred_px . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_behance') != '') {
				$social_item .= '
					<li>
						<a class="behance" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_behance') ) . '" rel="nofollow">' . $behance . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_dribbble') != '') {
				$social_item .= '
					<li>
						<a class="dribbble" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_dribbble') ) . '" rel="nofollow">' . $dribbble . '</a>
					</li>
				';
			}
			if ( get_theme_mod('zaxu_social_facebook') != '') {
				$social_item .= '
					<li>
						<a class="facebook" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_facebook') ) . '" rel="nofollow">' . $facebook . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_instagram') != '') {
				$social_item .= '
					<li>
						<a class="instagram" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_instagram') ) . '" rel="nofollow">' . $instagram . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_line') != '') {
				$social_item .= '
					<li>
						<a class="line" target="_blank" href="https://line.me/R/ti/p/' . esc_attr( get_theme_mod('zaxu_social_line') ) . '" rel="nofollow">' . $line . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_linkedin') != '') {
				$social_item .= '
					<li>
						<a class="linkedin" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_linkedin') ) . '" rel="nofollow">' . $linkedin . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_pinterest') != '') {
				$social_item .= '
					<li>
						<a class="pinterest" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_pinterest') ) . '" rel="nofollow">' . $pinterest . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_skype') != '') {
				$social_item .= '
					<li>
						<a class="skype" target="_blank" href="skype:' . esc_attr( get_theme_mod('zaxu_social_skype') ) . '?call" rel="nofollow">' . $skype . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_snapchat') != '') {
				$social_item .= '
					<li>
						<a class="snapchat" target="_blank" href="https://www.snapchat.com/add/' . esc_attr( get_theme_mod('zaxu_social_snapchat') ) . '" rel="nofollow">' . $snapchat . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_soundcloud') != '') {
				$social_item .= '
					<li>
						<a class="soundcloud" target="_blank" href="' . esc_url( get_theme_mod( 'zaxu_social_soundcloud') ) . '" rel="nofollow">' . $soundcloud . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_twitter') != '' ) {
				$social_item .= '
					<li>
						<a class="twitter" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_twitter') ) . '" rel="nofollow">' . $twitter . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_medium') != '') {
				$social_item .= '
					<li>
						<a class="medium" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_medium') ) . '" rel="nofollow">' . $medium . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_rss') != '') {
				$social_item .= '
					<li>
						<a class="rss" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_rss') ) . '" rel="nofollow">' . $rss . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_flickr') != '') {
				$social_item .= '
					<li>
						<a class="flickr" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_flickr') ) . '" rel="nofollow">' . $flickr . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_vimeo') != '') {
				$social_item .= '
					<li>
						<a class="vimeo" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_vimeo') ) . '" rel="nofollow">' . $vimeo . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_whatsapp') != '') {
				$social_item .= '
					<li>
						<a class="whatsapp" target="_blank" href="https://api.whatsapp.com/send/?phone=' . esc_attr( get_theme_mod('zaxu_social_whatsapp') ) . '" rel="nofollow">' . $whatsapp . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_wordpress') != '') {
				$social_item .= '
					<li>
						<a class="wordpress" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_wordpress') ) . '" rel="nofollow">' . $wordpress . '</a>
					</li>
				';
			}
			if (get_theme_mod('zaxu_social_slack') != '') {
				$social_item .= '
					<li>
						<a class="slack" target="_blank" href="' . esc_url( get_theme_mod('zaxu_social_slack') ) . '" rel="nofollow">' . $slack . '</a>
					</li>
				';
			}
		// Social item end

		if ($social_item != null) {
			echo '
				<section class="footer-social-container">
					<ul>
						' . $social_item . '
					</ul>
				</section>
			';
		}
	}
endif;

/** Site sharing
 *
 * @since 2.2.5
*/

if ( !function_exists('zaxu_sharing') ) :
	function zaxu_sharing() {
		if (get_theme_mod('zaxu_site_share', 'enabled') === 'enabled') {
			// Site sharing start
				echo '
					<section class="site-sharing-container site-overlay-element">
						<div class="site-sharing-content" data-subject="' . __("Recommended a great website for you:", "zaxu") . '" data-body="' . __("Visit this link to learn more details:", "zaxu") . '">
							<span class="title">' . __("Share This Page", "zaxu") . '</span>
							<ul>
								<li class="poster">
									' . zaxu_icon('poster', 'icon') . '
									<span class="title">' . __("Build Poster", "zaxu") . '</span>
								</li>
								<li class="link">
									' . zaxu_icon('link', 'icon') . '
									<span class="title" data-default="' . __("Copy Link", "zaxu") . '" data-success="' . __("Copied", "zaxu") . '" data-error="' . __("Error", "zaxu") . '">' . __("Copy Link", "zaxu") . '</span>
								</li>
								<li class="wechat">
									' . zaxu_icon('wechat', 'icon') . '
									<span class="title">' . __("WeChat", "zaxu") . '</span>
								</li>
								<li class="email">
									' . zaxu_icon('email', 'icon') . '
									<span class="title">' . __("Email", "zaxu") . '</span>
								</li>
								<li class="weibo">
									' . zaxu_icon('weibo', 'icon') . '
									<span class="title">' . __("Weibo", "zaxu") . '</span>
								</li>
								<li class="qzone">
									' . zaxu_icon('qzone', 'icon') . '
									<span class="title">' . __("QZone", "zaxu") . '</span>
								</li>
								<li class="facebook">
									' . zaxu_icon('facebook', 'icon') . '
									<span class="title">' . __("Facebook", "zaxu") . '</span>
								</li>
								<li class="twitter">
									' . zaxu_icon('twitter', 'icon') . '
									<span class="title">' . __("Twitter", "zaxu") . '</span>
								</li>
							</ul>
						</div>
					</section>
				';
			// Site sharing end
		}
	}
endif;

/** Footer information
 *
 * @since 2.2.6
*/

if ( !function_exists('zaxu_footer_info') ) :
	function zaxu_footer_info() {
		$lang_switcher = get_theme_mod('zaxu_lang_switcher', 'enabled');
		$has_lang = '<footer class="site-footer section-inner" role="contentinfo">';
		$no_lang = '<footer class="site-footer section-inner no-lang-switcher" role="contentinfo">';

		if ($lang_switcher == 'enabled') {
			if ( function_exists('icl_object_id') ) {
				// WPML
				$langs = icl_get_languages('skip_missing=1');
				if ( empty($langs) ) {
					// Not set language
					echo $no_lang;
				} else {
					// Set language
					$lang_status = false;
					foreach ($langs as $lang) {
						$current_lang = $lang['active'];
						if ($current_lang == 1) {
							$lang_status = true;
						}
					}
					if ($lang_status == true) {
						echo $has_lang;
					} else {
						echo $no_lang;
					}
				}
			} else if ( function_exists('pll_the_languages') ) {
				// Polylang
				$langs = pll_the_languages(
					array(
						'display_names_as' => 1,
						'hide_if_no_translation' => 1,
						'raw' => 1,
					)
				);
				
				if ( empty($langs) ) {
					// Not set language
					echo $no_lang;
				} else {
					// Set language
					$lang_status = false;
					foreach ($langs as $lang) {
						$current_lang = $lang['current_lang'];
						if ($current_lang == 1) {
							$lang_status = true;
						}
					}
					if ($lang_status == true) {
						echo $has_lang;
					} else {
						echo $no_lang;
					}
				}
			} else {
				// Not install language plugin
				echo $no_lang;
			}
		} else {
			// Language switcher is disabled
			echo $no_lang;
		}

		// Social content start
			if (get_theme_mod('zaxu_social_icon', 'disabled') === 'enabled') {
				zaxu_social_icon();
			};
		// Social content end

		// Information content start
			$web_created_time_str = esc_attr( get_theme_mod('zaxu_web_created_time') );
			echo '<section class="footer-info-container"><div class="copyright">';
			if ($web_created_time_str) {
				$web_created_time = $web_created_time_str . '-';
			} else {
				$web_created_time = null;
			}
			echo __('Copyright &copy;', 'zaxu') . ' ' . $web_created_time . date("Y") . ' ' . get_bloginfo("name") . '. ' . __('All rights reserved.', 'zaxu');
			if (get_theme_mod('zaxu_thanks_for_zaxu', 'enabled') == 'enabled') {
				echo ' ' . __('Theme created by', 'zaxu') . ' <a href="https://www.zaxu.com/" target="_blank">ZAXU</a>.';
			}
			if (get_theme_mod('zaxu_thanks_for_wordpress', 'enabled') == 'enabled') {
				echo ' ' . __('Powered by', 'zaxu') . ' <a href="https://wordpress.org/" rel="nofollow" target="_blank">WordPress</a>.';
			}
			echo '</div>';

			if ($lang_switcher == 'enabled') {
				zaxu_lang_switcher();
			}

			echo '</section>';
		// Information content end

		// Statement content start
			// ICP number
			$icp_num_str = esc_attr( get_theme_mod('zaxu_icp_num') );

			// ICP certificate number
			$icp_cert_num_str = esc_attr( get_theme_mod('zaxu_icp_cert_num') );

			// Public network security number
			$pub_num_str = esc_attr( get_theme_mod('zaxu_public_network_security_num') );
			if ( preg_match('/\d+/', $pub_num_str, $arr) ) {
				$pub_num = $arr[0];
			};

			// Business license
			$business_license = get_theme_mod('zaxu_business_license');

			// Extra content
			$extra_content_str = get_theme_mod('zaxu_extra_content');

			if ($icp_num_str || $icp_cert_num_str || $pub_num_str || $extra_content_str || $business_license) {
				echo '<section class="footer-statement-container">';
					if ($icp_num_str) {
						echo '<a class="statement-item" href="http://beian.miit.gov.cn/" rel="nofollow" target="_blank">' . $icp_num_str . '</a>';
					};
					if ($icp_cert_num_str) {
						echo '<a class="statement-item" href="https://tsm.miit.gov.cn/dxxzsp/" rel="nofollow" target="_blank">' . $icp_cert_num_str . '</a>';
					};
					if ($pub_num_str) {
						echo '<a class="statement-item" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=' . $pub_num . '" rel="nofollow" target="_blank">' . $pub_num_str . '</a>';
					};
					if ($business_license) {
						echo '<a class="statement-item fancybox" href="' . $business_license . '">' . __('Business License', 'zaxu') . '</a>';
					}
					if ($extra_content_str) {
						echo '<div class="statement-item">' . html_entity_decode($extra_content_str) . '</div>';
					}
				echo '</section>';
			};
		// Statement content end

		// Analytics content start
			zaxu_analytics();
		// Analytics content end
		echo '</footer>';
	}
endif;

/** Analytics
 *
 * @since 2.4.0
*/

if ( !function_exists('zaxu_analytics') ) :
	function zaxu_analytics() {
		// Baidu Analytics
		$baidu_analytics = get_theme_mod('zaxu_baidu_analytics');
		if ($baidu_analytics != '') {
			echo html_entity_decode($baidu_analytics);
		};

		// Google Analytics
		$google_analytics = get_theme_mod('zaxu_google_analytics');
		if (get_theme_mod('zaxu_google_analytics') != '') {
			echo html_entity_decode($google_analytics);
		};
	}
endif;

/** Language switcher
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_lang_switcher') ) :
	function zaxu_lang_switcher() {
		if ( function_exists('icl_object_id') ) {
			// WPML
			$current_lang = null;
			$lang_item = null;
			$langs = icl_get_languages('skip_missing=1');
			if( !empty($langs) ) {
				foreach ($langs as $lang) {
					if ( $lang['active'] ) {
						$current_lang = '
							<div class="lang-switcher-trigger">
								<div class="lang-switcher-opt">
									' . zaxu_icon('language', 'icon') . '
									<span class="lang-switcher-name">' . $lang['native_name'] .'</span>
								</div>
								<span class="arrow"></span>
							</div>
						';
						$lang_item .= '
							<li class="lang-switcher-item current">
								<a href="' . $lang['url'] . '" class="lang-switcher-link">
									<span class="icon"></span>
									<span class="lang-switcher-name">' . $lang['native_name'] .'</span>
								</a>
							</li>
						';
					} else {
						$lang_item .= '
							<li class="lang-switcher-item">
								<a href="' . $lang['url'] . '" class="lang-switcher-link">
									<span class="icon"></span>
									<span class="lang-switcher-name">' . $lang['native_name'] .'</span>
								</a>
							</li>
						';
					}
				}

				echo '
					<section class="lang-switcher-container">
						' . $current_lang . '
						<div class="lang-switcher-list-box">
							<ul class="lang-switcher-list">
								' . $lang_item . '
							</ul>
						</div>
					</section>
				';
			}
		} else if ( function_exists('pll_the_languages') ) {
			// Polylang
			$current_lang = null;
			$lang_item = null;
			$langs = pll_the_languages(
				array(
					'display_names_as' => 1,
					'hide_if_no_translation' => 1,
					'raw' => 1,
				)
			);
	
			if ( !empty($langs) ) {
				foreach ($langs as $lang) {
					if ( $lang['current_lang'] ) {
						$current_lang = '
							<div class="lang-switcher-trigger">
								<div class="lang-switcher-opt">
									' . zaxu_icon('language', 'icon') . '
									<span class="lang-switcher-name">' . $lang['name'] .'</span>
								</div>
								<span class="arrow"></span>
							</div>
						';
						$lang_item .= '
							<li class="lang-switcher-item current">
								<a href="' . $lang['url'] . '" class="lang-switcher-link">
									<span class="icon"></span>
									<span class="lang-switcher-name">' . $lang['name'] .'</span>
								</a>
							</li>
						';
					} else {
						$lang_item .= '
							<li class="lang-switcher-item">
								<a href="' . $lang['url'] . '" class="lang-switcher-link">
									<span class="icon"></span>
									<span class="lang-switcher-name">' . $lang['name'] .'</span>
								</a>
							</li>
						';
					}
				}

				echo '
					<section class="lang-switcher-container">
						' . $current_lang . '
						<div class="lang-switcher-list-box">
							<ul class="lang-switcher-list">
								' . $lang_item . '
							</ul>
						</div>
					</section>
				';
			}
		}
	}
endif;

/** Tabbar
 *
 * @since 2.0.0
*/

if ( !function_exists('zaxu_tabbar') ) :
	function zaxu_tabbar() {
		global $post;
		$sidebar_icon = zaxu_icon('tabbar_sidebar', 'icon');
		$close_icon = zaxu_icon('tabbar_close', 'icon');
		$comment_icon = zaxu_icon('tabbar_comment', 'icon');
		$scroll_top_icon = zaxu_icon('tabbar_scroll_top', 'icon');

		$current_page = "other-page";
		if ( function_exists('is_woocommerce') ) {
			global $product;
	        if ( is_shop() ) {
				$current_page = "shop-page";
	        }
	    }
		
		if ($current_page == "other-page") {
			if ( is_singular("post") ) {
				// Blog start
					if (get_theme_mod('zaxu_post_widget_sidebar', 'disabled') === 'enabled') {
						echo '
							<section class="site-tabbar-container">
								<div class="site-tabbar-content background-blur">
									<div class="sidebar tabbar">' . $sidebar_icon . '</div>
						';
					} else {
						echo '
							<section class="site-tabbar-container">
								<div class="site-tabbar-content background-blur">
						';
					}

					// Close button
					if ( esc_url( get_the_permalink( get_option('page_for_posts') ) ) == get_the_permalink($post->ID) ) {
						echo '
							<a class="close tabbar ajax-link" href="' . esc_url( home_url('/') ) . '">' . $close_icon . '</a>
						';
					} else {
						echo '
							<a class="close tabbar ajax-link" href="' . esc_url( get_the_permalink( get_option('page_for_posts') ) ) . '">' . $close_icon . '</a>
						';
					}
				// Blog end
			} else if ( is_singular("portfolio") ) {
				// Portfolio start
					if (get_theme_mod('zaxu_portfolio_widget_sidebar', 'disabled') === 'enabled') {
						echo '
							<section class="site-tabbar-container">
								<div class="site-tabbar-content background-blur">
									<div class="sidebar tabbar">' . $sidebar_icon . '</div>
						';
					} else {
						echo '
							<section class="site-tabbar-container">
								<div class="site-tabbar-content background-blur">
						';
					}

					// Close button
					$portfolio_pages = get_pages(
						array(
							'meta_key' => '_wp_page_template',
							'meta_value' => 'templates/template-portfolio.php',
						)
					);
					foreach ($portfolio_pages as $portfolio_page) {
						$post_homepage_link = get_page_link($portfolio_page->ID);
					};

					if ( empty($post_homepage_link) ) {
						echo '
							<a class="close tabbar ajax-link" href="' . esc_url( home_url('/') ) . '">' . $close_icon . '</a>
						';
					} else {
						echo '
							<a class="close tabbar ajax-link" href="' . $post_homepage_link . '">' . $close_icon . '</a>
						';
					}
				// Portfolio end
			} else if ( is_singular("docs") ) {
				// Documentation start
					echo '
						<section class="site-tabbar-container tabbar-desc documentation">
							<div class="site-tabbar-content background-blur">
								<div class="tabbar-desc-box">
									' . $sidebar_icon . '
									<span class="context">' . __("Documentation Contents", "zaxu") . '</span>
								</div>
							</div>
						</section>
					';
				// Documentation end
			}

			if ( is_singular("post") || is_singular("portfolio") ) {
				// Blog or portfolio start
					// Comments
					if ( comments_open() || get_comments_number() ) {
						if ( !post_password_required($post->ID) ) {
							if (get_comments_number('0', '1', '%') == "0") {
								echo '
									<div class="comments tabbar">' . $comment_icon . '</div>
								';
							} else {
								echo '
									<div class="comments tabbar">
										' . $comment_icon . '
										<span class="badge">' . get_comments_number('0', '1', '%') . '</span>
									</div>
								';
							};
						};
					};

					// Scroll top button start
						$scroll_top_button = '<div class="scroll-top tabbar invisible">' . $scroll_top_icon . '</div>';
						if ( is_singular("post") ) {
							if (get_theme_mod('zaxu_blog_scroll_top_button', 'disabled') == 'enabled') {
								echo $scroll_top_button;
							}
						} else if ( is_singular("portfolio") ) {
							if (get_theme_mod('zaxu_portfolio_scroll_top_button', 'disabled') == 'enabled') {
								echo $scroll_top_button;
							}
						}
					// Scroll top button end

					echo '
							</div>
						</section>
					';
				// Blog or portfolio end
			}
		} else if ($current_page == "shop-page" && !empty($product) ) {
			// WooCommerce start
				if (get_theme_mod('zaxu_product_category', 'enabled') == 'enabled') {
					echo '
						<section class="site-tabbar-container tabbar-desc woocommerce">
							<div class="site-tabbar-content background-blur">
								<div class="tabbar-desc-box">
									' . $sidebar_icon . '
									<span class="context">' . __("Category", "zaxu") . '</span>
								</div>
							</div>
						</section>
					';
				};
			// WooCommerce end
		}
	}
endif;

/** Action button
 *
 * @since 2.6.0
*/

if ( !function_exists('zaxu_action') ) :
	function zaxu_action() {
		global $post;
		$back_icon = zaxu_icon('tabbar_back', 'icon');
		$comment_icon = zaxu_icon('tabbar_comment', 'icon');
		$sidebar_icon = zaxu_icon('tabbar_sidebar', 'icon');
		$scroll_top_icon = zaxu_icon('tabbar_scroll_top', 'icon');
		$edit_icon = zaxu_icon('tabbar_edit', 'icon');

		$sidebar_button = null;
		$comment_button = null;
		$action_right_box = null;
		$scroll_top_button = null;
		$edit_button = null;
		if ( is_singular("post") ) {
			// Sidebar button
			if (get_theme_mod('zaxu_post_widget_sidebar', 'disabled') == 'enabled') {
				$sidebar_button = '<li class="action-button sidebar" title="' . __('Widget Sidebar', 'zaxu') . '">' . $sidebar_icon . '</li>';
			}

			// Scroll top
			if (get_theme_mod('zaxu_blog_scroll_top_button', 'disabled') == 'enabled') {
				$scroll_top_button = '<li class="action-button scroll-top invisible" title="' . __('Scroll Top', 'zaxu') . '">' . $scroll_top_icon . '</li>';
			}

			// Edit button
			if ( is_user_logged_in() ) {
				$edit_button = '
					<li class="action-button edit" title="' . __('Edit This Post', 'zaxu') . '">
						<a href="' . get_edit_post_link() . '" target="_blank">' . $edit_icon . '</a>
					</li>
				';
			}

			// Action right box
			if ( is_user_logged_in() || get_theme_mod('zaxu_blog_scroll_top_button', 'disabled') == 'enabled') {
				$action_right_box = '
					<div class="action-button-box right">
						<ul>
							' . $scroll_top_button . $edit_button . '
						</ul>
					</div>
				';
			}
		} else if ( is_singular("portfolio") ) {
			// Sidebar button
			if (get_theme_mod('zaxu_portfolio_widget_sidebar', 'disabled') == 'enabled') {
				$sidebar_button = '<li class="action-button sidebar" title="' . __('Widget Sidebar', 'zaxu') . '">' . $sidebar_icon . '</li>';
			}

			// Scroll top
			if (get_theme_mod('zaxu_portfolio_scroll_top_button', 'disabled') == 'enabled') {
				$scroll_top_button = '<li class="action-button scroll-top invisible" title="' . __('Scroll Top', 'zaxu') . '">' . $scroll_top_icon . '</li>';
			}

			// Edit button
			if ( is_user_logged_in() ) {
				$edit_button = '
					<li class="action-button edit" title="' . __('Edit This Post', 'zaxu') . '">
						<a href="' . get_edit_post_link() . '" target="_blank">' . $edit_icon . '</a>
					</li>
				';
			}

			// Action right box
			if ( current_user_can('editor') || get_theme_mod('zaxu_portfolio_scroll_top_button', 'disabled') == 'enabled') {
				$action_right_box = '
					<div class="action-button-box right">
						<ul>
							' . $scroll_top_button . $edit_button . '
						</ul>
					</div>
				';
			}
		}
		
		// Only for post or portfolio
		if ( is_singular("post") || is_singular("portfolio") ) {
			// Comment button
			if ( comments_open() || get_comments_number() ) {
				if ( !post_password_required($post->ID) ) {
					if (get_comments_number('0', '1', '%') == "0") {
						$comment_button = '
							<li class="action-button comments" title="' . __('Comment', 'zaxu') . '">' . $comment_icon . '</li>
						';
					} else {
						$comment_button = '
							<li class="action-button comments" title="' . __('Comment', 'zaxu') . '">
								' . $comment_icon . '
								<span class="badge">' . get_comments_number('0', '1', '%') . '</span>
							</li>
						';
					};
				};
			};

			echo '
				<section class="site-action-container">
					<div class="site-action-wrap section-inner">
						<div class="action-button-box left">
							<ul>
								<li class="action-button close" title="' . __('Back', 'zaxu') . '">' . $back_icon . '</li>
								' . $comment_button . $sidebar_button . '
							</ul>
						</div>
						' . $action_right_box . '
					</div>
				</section>
			';
		}
	}
endif;

/** Comment
 *
 * @since 1.0.0
*/

if ( !function_exists('zaxu_comment') ) :
	function zaxu_comment($comment, $args, $depth) {
			global $post;
			$GLOBALS['comment'] = $comment;
		?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article itemscope itemtype="http://schema.org/Comment">
				<div class="comment-avatar">
					<?php
						echo get_avatar($comment, 160, $default = '');
						if ($post && $post = get_post($post->ID) ) {
							if ($comment->user_id === $post->post_author) {
								echo '<span class="by-author">' . zaxu_icon('krown', 'icon') . '</span>';
							}
						}
					?>
					</div>
				<div class="comment-content">
					<div class="comment-meta">
						<h6 class="comment-title">
							<?php echo ( get_comment_author_url() != '' ? '<a itemprop="creator" href="' . get_comment_author_url() . '" target="blank">' . get_comment_author() . '</a>' : comment_author() ); ?>
						</h6>
						<span class="comment-date" itemprop="dateCreated">
							<?php echo comment_date( esc_html__('M j \a\t h:i', 'zaxu') ); ?>
						</span>
					</div>
					<div class="comment-text">
						<div itemprop="text">
							<?php if ($comment->comment_approved == '0') : ?>
								<p>
									<em class="await"><?php _e('Your comment is awaiting moderation.', 'zaxu'); ?></em>
								</p>
							<?php endif; ?>
							<?php comment_text(); ?>
						</div>
						<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('reply', 'zaxu') ) ) ); ?>
					</div>
				</div>
			</article>
		<?php
	}
endif;

/** Site Poster
 *
 * @since 2.2.5
*/

if ( !function_exists('zaxu_site_poster') ) :
	function zaxu_site_poster($post_id) {
		if (get_theme_mod('zaxu_site_share', 'enabled') == 'enabled') {
			if ($post_id == "no_id") {
				// 404 page
				// Get featured image
				$featured_image = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
				$featured_image_dark = 'data-cover-dark="' . get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg" ';
				$width = 1920;
				$height = 1280;

				// Get title
				$title = esc_attr( get_bloginfo('name') );

				// Get description
				$description = esc_attr( get_option('blogdescription') );
			} else {
				// Other page
				// Get featured image
				$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), "full");
				$featured_image_dark = null;

				if (empty($featured_image) || pathinfo($featured_image[0])['extension'] == "svg") {
					$featured_image = get_template_directory_uri() . '/assets/img/file-light-1920x1280.jpg';
					$featured_image_dark = 'data-cover-dark="' . get_template_directory_uri() . '/assets/img/file-dark-1920x1280.jpg" ';
					$width = 1920;
					$height = 1280;
				} else {
					$featured_image = $featured_image[0];
					// Get featured image metadata
					if ( get_post_thumbnail_id($post_id) ) {
						$source = wp_get_attachment_metadata( get_post_thumbnail_id($post_id) );
					} else {
						$source = wp_get_attachment_metadata($post_id);
					}
					$width = $source['width'];
					$height = $source['height'];
				}

				// Get title
				$title = esc_attr( get_the_title($post_id) );
				if ( is_front_page() ) {
					$title = esc_attr( get_bloginfo('name') );
				}

				// Get description
				$description = esc_attr( get_post_meta($post_id, 'zaxu_seo_description', true) );
				if ( empty($description) && zaxu_excerpt($post_id, "has-desc") ) {
					$description = esc_attr( wp_strip_all_tags( zaxu_excerpt($post_id, "has-desc") ) );
				}
			}

			// Calc size
			$ratio = $width / $height;
			$targetWidth = 768 / $ratio * $ratio;
			$targetHeight = $targetWidth / $ratio;

			// Site poster start
				echo '
					<section class="site-poster-container site-overlay-element">
						<div class="site-poster-content">
							<div class="card" data-cover="' . $featured_image . '" ' . $featured_image_dark . 'data-width="' . $targetWidth . '" data-height="' . $targetHeight . '" data-title="' . $title . '" data-description="' . $description . '" data-tips="' . __("Scan the QR code to learn more details", "zaxu") . '">
								<span class="loading"></span>
								<div class="close-button">
									<span class="icon background-blur"></span>
								</div>
							</div>
						</div>
					</section>
				';
			// Site poster end
		}
	}
endif;

/** Site Image Popup
 *
 * @since 2.2.5
*/

if ( !function_exists('zaxu_image_popup') ) :
	function zaxu_image_popup() {
		$sharing = get_theme_mod('zaxu_site_share', 'enabled');
		$social = get_theme_mod('zaxu_social_icon', 'disabled');

		if ($sharing == 'enabled' || $social == 'enabled') {
			$wechat_img = get_theme_mod('zaxu_social_wechat_qr_code');
			$mini_program_img = get_theme_mod('zaxu_social_wechat_mini_program_qr_code');
			$tiktok_img = get_theme_mod('zaxu_social_tiktok_qr_code');
			$kwai_img = get_theme_mod('zaxu_social_kwai_qr_code');
			if ($wechat_img || $mini_program_img || $tiktok_img || $kwai_img) {
				$img = '
					<img src="' . $wechat_img . '" />
					<img src="' . $mini_program_img . '" />
					<img src="' . $tiktok_img . '" />
					<img src="' . $kwai_img . '" />
				';
			} else {
				$img = null;
			}
			echo '
				<section class="site-image-popup-container site-overlay-element">
					<div class="qrcode-box" data-sharing_tips="' . __("Scan the QR code via WeChat", "zaxu") . '" data-wechat_img="' . $wechat_img . '" data-wechat_tips="' . __("Scan the QR code to add on WeChat", "zaxu") . '" data-mini_program_img="' . $mini_program_img . '" data-mini_program_tips="' . __('Scan the Mini Program QR code via WeChat', 'zaxu') . '" data-tiktok_img="' . $tiktok_img . '" data-kwai_img="' . $kwai_img . '">' . $img . '</div>
				</section>
			';
		}
	}
endif;

/** Site Response
 *
 * @since 2.3.0
*/

if ( !function_exists('zaxu_response') ) :
	function zaxu_response() {
		$screen_support = get_theme_mod('zaxu_screen_support');
		if ($screen_support == 'portrait') {
			echo '
	   	       <section class="site-response-container landscape-tips">
		           <div class="section-inner">
			           <h2>' . __("Sorry", "zaxu") . '</h2>
			           <h5>' . __("This page only supports portrait mode.", "zaxu") . '</h5>
		           </div>
	           </section>
	   	   ';
		} else if ($screen_support == 'landscape') {
			echo '
				<section class="site-response-container portrait-tips">
					<div class="section-inner">
						<h2>' . __("Sorry", "zaxu") . '</h2>
						<h5>' . __("This page only supports landscape mode.", "zaxu") . '</h5>
					</div>
				</section>
			';
		};
	}
endif;

/** Site compatible
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_compatible') ) :
	function zaxu_compatible() {
		echo '
			<div class="site-compatible-container">
				<div class="message">
					<p>' . __('We noticed that your browser version is too low. This site requires a more modern browser to fully display, we recommend that you can download Google Chrome browser to browse this site.', 'zaxu') . '</p>
					<a href="' . esc_url('https://www.google.com/chrome/') . '" rel="nofollow" target="_blank" class="button button-primary">' . __('Download Chrome', 'zaxu') . '</a>
				</div>
			</div>
		';
	}
endif;

/** No script
 *
 * @since 2.5.8
*/

if ( !function_exists('zaxu_no_script') ) :
	function zaxu_no_script() {
		echo '<noscript>' . __('Your browser does not support JavaScript!', 'zaxu') . '</noscript>';
	}
endif;
?>