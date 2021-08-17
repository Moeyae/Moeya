<?php
/*
 * @Description: Theme customizer functions
 * @Version: 2.7.1
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

function zaxu_customize_register($wp_customize) {
	require_once get_template_directory() . '/inc/customizer/customizer-classes.php';
	// ********General start********
		$wp_customize->add_section( 'general_section', array(
			'title' => esc_html__('General', 'zaxu'),
			'priority' => 1,
		) );
		// Sub sections start
			// Site maximum width
			$wp_customize->add_setting(
				'zaxu_site_max_width', array(
					'default' => '120rem',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_site_max_width',
				array(
					'label' => esc_html__('Site Maximum Width', 'zaxu'),
					'section' => 'general_section',
					'settings' => 'zaxu_site_max_width',
					'type' => 'select',
					'choices' => array(
						'68rem' => esc_html__('Thin', 'zaxu'),
						'80rem' => esc_html__('Small', 'zaxu'),
						'100rem' => esc_html__('Medium', 'zaxu'),
						'120rem' => esc_html__('Large (Recommended)', 'zaxu'),
						'140rem' => esc_html__('Wide', 'zaxu'),
						'100%' => esc_html__('Full', 'zaxu'),
					)
				)
			);
			// Typography
			$wp_customize->add_setting(
				'zaxu_typography', array(
					'default' => 'default',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_typography',
				array(
					'label' => esc_html__('Typography', 'zaxu'),
					'section' => 'general_section',
					'settings' => 'zaxu_typography',
					'type' => 'select',
					'choices' => array(
						'default' => __('Default', 'zaxu'),
						'aleo' => __('Aleo', 'zaxu'),
						'playfair_display' => __('Playfair Display', 'zaxu'),
						'poppins' => __('Poppins', 'zaxu'),
						'roboto' => __('Roboto', 'zaxu'),
						'noto_serif_sc' => __('Noto Serif SC', 'zaxu'),
					)
				)
			);
			// Page loading
			$wp_customize->add_setting(
				'zaxu_page_loading', array(
					'default' => 'linear',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_page_loading',
				array(
					'label' => esc_html__('Page Loading', 'zaxu'),
					'description' => esc_html__('Page loading animation.', 'zaxu'),
					'section' => 'general_section',
					'settings' => 'zaxu_page_loading',
					'type' => 'select',
					'choices' => array(
						'spinner' => esc_html__('Spinner', 'zaxu'),
						'wipe' => esc_html__('Wipe', 'zaxu'),
						'linear' => esc_html__('Linear', 'zaxu'),
					)
				)
			);
			// Text selection
			$wp_customize->add_setting(
				'zaxu_site_user_select', array(
					'default' => 'enabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_site_user_select',
				array(
					'label' => esc_html__('Text Selection', 'zaxu'),
					'description' => esc_html__('The mouse select the web text.', 'zaxu'),
					'section' => 'general_section',
					'settings' => 'zaxu_site_user_select',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Reply notification via email
			$wp_customize->add_setting(
				'zaxu_comment_notify', array(
					'default' => 'disabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_comment_notify',
				array(
					'label' => esc_html__('Comment Notify via Email', 'zaxu'),
					'description' => esc_html__('Make sure you have set up the SMTP service.', 'zaxu'),
					'section' => 'general_section',
					'settings' => 'zaxu_comment_notify',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Baidu analytics
			$wp_customize->add_setting(
				'zaxu_baidu_analytics', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_textarea'
				)
			);
			$wp_customize->add_control('zaxu_baidu_analytics', 
				array(
					'label' => esc_html__('Baidu Analytics', 'zaxu'),
					'description' => '',
					'input_attrs' => array(
						'placeholder' => esc_html__('Please paste your Baidu analytics code...', 'zaxu')
					),
					'section' => 'general_section',
					'settings' => 'zaxu_baidu_analytics',
					'type' => 'textarea'
				)
			);
			// Google analytics
			$wp_customize->add_setting(
				'zaxu_google_analytics', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_textarea'
				)
			);
			$wp_customize->add_control('zaxu_google_analytics', 
				array(
					'label' => esc_html__('Google Analytics', 'zaxu'),
					'description' => '',
					'input_attrs' => array(
						'placeholder' => esc_html__('Please paste your Google analytics code...', 'zaxu')
					),
					'section' => 'general_section',
					'settings' => 'zaxu_google_analytics',
					'type' => 'textarea'
				)
			);
		// Sub sections end
	// ********General end********

	// ********Color scheme start********
		$wp_customize->add_section( 'color_scheme_section', array(
			'title' => esc_html__('Color Scheme', 'zaxu'),
			'priority' => 3,
		) );
		// Sub sections start
			// Grayscale
			$wp_customize->add_setting(
				'zaxu_site_grayscale', array(
					'default' => 'disabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_site_grayscale',
				array(
					'label' => esc_html__('Grayscale', 'zaxu'),
					'description' => esc_html__('Set the grayscale mode for your website.', 'zaxu'),
					'section' => 'color_scheme_section',
					'settings' => 'zaxu_site_grayscale',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Dynamic color
			$wp_customize->add_setting(
				'zaxu_dynamic_color', array(
					'default' => 'disabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_dynamic_color',
				array(
					'label' => esc_html__('Dynamic Color', 'zaxu'),
					'description' => esc_html__('Android 10, iOS 13.0 or macOS 10.14 and later support.', 'zaxu'),
					'section' => 'color_scheme_section',
					'settings' => 'zaxu_dynamic_color',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Background color
			$wp_customize->add_setting(
				'zaxu_bg_color', array(
					'default' => '#f2f2f2',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_control( 
				new WP_Customize_Color_Control( 
					$wp_customize, 
					'zaxu_bg_color', 
					array(
						'label'      => esc_html__('Background Color', 'zaxu'),
						'section'    => 'color_scheme_section',
						'settings'   => 'zaxu_bg_color'
					)
				)
			);
			// Text color
			$wp_customize->add_setting(
				'zaxu_txt_color', array(
					'default' => '#333333',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_control( 
				new WP_Customize_Color_Control( 
				$wp_customize, 
				'zaxu_txt_color', 
				array(
					'label' => esc_html__('Text Color', 'zaxu'),
					'section' => 'color_scheme_section',
					'settings' => 'zaxu_txt_color'
				) ) 
			);
			// Accent color
			$wp_customize->add_setting(
				'zaxu_acc_color', array(
					'default' => '#0088cc',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_control( 
				new WP_Customize_Color_Control( 
					$wp_customize, 
					'zaxu_acc_color', 
					array(
						'label' => esc_html__('Accent Color', 'zaxu'),
						'section' => 'color_scheme_section',
						'settings' => 'zaxu_acc_color'
					)
				) 
			);
		// Sub sections end
	// ********Color scheme end********

	// ********Navigation start********
		$wp_customize->add_section( 'navigation_section', array(
			'title' => esc_html__('Navigation', 'zaxu'),
			'priority' => 4,
		) );
		// Sub sections start
			// Logo
			$wp_customize->add_setting(
				'zaxu_logo', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'zaxu_logo',
					array(
						'label' => esc_html__('Logo', 'zaxu'),
						'section' => 'navigation_section',
						'settings' => 'zaxu_logo',
					)
				)
			);
			// Logo height
			$wp_customize->add_setting(
				'zaxu_logo_height', array(
					'default' => '30',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_sanitize_basic_number'
				)
			);
			$wp_customize->add_control('zaxu_logo_height', 
				array(
					'label' => esc_html__('Logo Height', 'zaxu'),
					'section' => 'navigation_section',
					'settings' => 'zaxu_logo_height',
					'type' => 'number',
					'input_attrs' => array(
						'min' => 5,
						'max' => 100,
						'step' => 1,
					),
				)
			);
			// Navigation status
			$wp_customize->add_setting(
				'zaxu_navigation_status', array(
					'default' => 'sticky',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_navigation_status',
				array(
					'label' => esc_html__('Navigation Status', 'zaxu'),
					'section' => 'navigation_section',
					'settings' => 'zaxu_navigation_status',
					'type' => 'select',
					'choices' => array(
						'sticky' => esc_html__('Scroll with the page', 'zaxu'),
						'normal' => esc_html__('Do not scroll with the page', 'zaxu'),
						'auto' => esc_html__('Scroll up to show, scroll down to hide', 'zaxu')
					)
				)
			);
			// Hamburger menu
			$wp_customize->add_setting(
				'zaxu_hamburger_menu', array(
					'default' => 'response',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_hamburger_menu',
				array(
					'label' => esc_html__('Hamburger Menu', 'zaxu'),
					'section' => 'navigation_section',
					'settings' => 'zaxu_hamburger_menu',
					'type' => 'select',
					'choices' => array(
						'always' => esc_html__('Always Display', 'zaxu'),
						'response' => esc_html__('Response', 'zaxu')
					)
				)
			);
			// Share
			$wp_customize->add_setting(
				'zaxu_site_share', array(
					'default' => 'enabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_site_share',
				array(
					'label' => esc_html__('Share', 'zaxu'),
					'section' => 'navigation_section',
					'settings' => 'zaxu_site_share',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Search
			$wp_customize->add_setting(
				'zaxu_site_search', array(
					'default' => 'enabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_site_search',
				array(
					'label' => esc_html__('Search', 'zaxu'),
					'section' => 'navigation_section',
					'settings' => 'zaxu_site_search',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Shopping bag
			if ( class_exists('WooCommerce') ) {
				$wp_customize->add_setting(
					'zaxu_shopping_bag', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_shopping_bag',
					array(
						'label' => esc_html__('Shopping Bag', 'zaxu'),
						'section' => 'navigation_section',
						'settings' => 'zaxu_shopping_bag',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			}
			// Background music
			$wp_customize->add_setting(
				'zaxu_background_music', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Upload_Control(
					$wp_customize, 'zaxu_background_music', array(
						'label' => esc_html__('Background Music', 'zaxu'),
						'section'  => 'navigation_section',
						'settings' => 'zaxu_background_music',
						'mime_type' => 'audio',
					)
				)
			);
		// Sub sections end
	// ********Navigation end********

	// ********Footer start********
		$wp_customize->add_panel( 'footer', array(
			'title' => esc_html__('Footer', 'zaxu'),
			'priority' => 5,
		) );
		// ========Sub Social section start========
			$wp_customize->add_section( 'footer_social_section', array(
				'title' => esc_html__('Social', 'zaxu'),
				'panel' => 'footer',
			) );
			// Sub sections start
				// Social icon
				$wp_customize->add_setting(
					'zaxu_social_icon', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_social_icon',
					array(
						'label' => esc_html__('Social Icon', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_icon',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// ZAXU link
				$wp_customize->add_setting(
					'zaxu_social_zaxu', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_zaxu', 
					array(
						'label' => esc_html__('ZAXU Link', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('https://www.zaxu.com', 'zaxu'),
						),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_zaxu',
						'type' => 'text'
					)
				);
				// Email address
				$wp_customize->add_setting(
					'zaxu_social_email', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_email', 
					array(
						'label' => esc_html__('Email Address', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_email',
						'type' => 'text'
					)
				);
				// WeChat QR Code
				$wp_customize->add_setting(
					'zaxu_social_wechat_qr_code', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'zaxu_social_wechat_qr_code',
						array(
							'label' => esc_html__('WeChat QR Code', 'zaxu'),
							'description' => esc_html__('Upload WeChat QR Code image.', 'zaxu'),
							'section' => 'footer_social_section',
							'settings' => 'zaxu_social_wechat_qr_code',
						)
					)
				);
				// WeChat Mini Program QR Code
				$wp_customize->add_setting(
					'zaxu_social_wechat_mini_program_qr_code', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'zaxu_social_wechat_mini_program_qr_code',
						array(
							'label' => esc_html__('WeChat Mini Program QR Code', 'zaxu'),
							'description' => esc_html__('Upload WeChat Mini Program QR Code image.', 'zaxu'),
							'section' => 'footer_social_section',
							'settings' => 'zaxu_social_wechat_mini_program_qr_code',
						)
					)
				);
				// TikTok QR Code
				$wp_customize->add_setting(
					'zaxu_social_tiktok_qr_code', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'zaxu_social_tiktok_qr_code',
						array(
							'label' => esc_html__('TikTok QR Code', 'zaxu'),
							'description' => esc_html__('Upload TikTok QR Code image.', 'zaxu'),
							'section' => 'footer_social_section',
							'settings' => 'zaxu_social_tiktok_qr_code',
						)
					)
				);
				// Kwai QR Code
				$wp_customize->add_setting(
					'zaxu_social_kwai_qr_code', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'zaxu_social_kwai_qr_code',
						array(
							'label' => esc_html__('Kwai QR Code', 'zaxu'),
							'description' => esc_html__('Upload Kwai QR Code image.', 'zaxu'),
							'section' => 'footer_social_section',
							'settings' => 'zaxu_social_kwai_qr_code',
						)
					)
				);
				// Weibo link
				$wp_customize->add_setting(
					'zaxu_social_weibo', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_weibo', 
					array(
						'label' => esc_html__('Weibo Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_weibo',
						'type' => 'text'
					)
				);
				// QQ link
				$wp_customize->add_setting(
					'zaxu_social_qq', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_qq', 
					array(
						'label' => esc_html__('QQ Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_qq',
						'type' => 'text'
					)
				);
				// QZone link
				$wp_customize->add_setting(
					'zaxu_social_qzone', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_qzone', 
					array(
						'label' => esc_html__('QZone Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_qzone',
						'type' => 'text'
					)
				);
				// Zhihu link
				$wp_customize->add_setting(
					'zaxu_social_zhihu', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_zhihu', 
					array(
						'label' => esc_html__('Zhihu Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_zhihu',
						'type' => 'text'
					)
				);
				// ZCOOL link
				$wp_customize->add_setting(
					'zaxu_social_zcool', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_zcool', 
					array(
						'label' => esc_html__('ZCOOL Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_zcool',
						'type' => 'text'
					)
				);
				// Huaban link
				$wp_customize->add_setting(
					'zaxu_social_huaban', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_huaban', 
					array(
						'label' => esc_html__('Huaban Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_huaban',
						'type' => 'text'
					)
				);
				// Lofter link
				$wp_customize->add_setting(
					'zaxu_social_lofter', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_lofter', 
					array(
						'label' => esc_html__('Lofter Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_lofter',
						'type' => 'text'
					)
				);
				// Tieba link
				$wp_customize->add_setting(
					'zaxu_social_tieba', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_tieba', 
					array(
						'label' => esc_html__('Tieba Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_tieba',
						'type' => 'text'
					)
				);
				// Xiongzhang link
				$wp_customize->add_setting(
					'zaxu_social_xiongzhang', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_xiongzhang', 
					array(
						'label' => esc_html__('Xiongzhang Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_xiongzhang',
						'type' => 'text'
					)
				);
				// Jianshu link
				$wp_customize->add_setting(
					'zaxu_social_jianshu', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_jianshu', 
					array(
						'label' => esc_html__('Jianshu Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_jianshu',
						'type' => 'text'
					)
				);
				// Xiaohongshu link
				$wp_customize->add_setting(
					'zaxu_social_xiaohongshu', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_xiaohongshu', 
					array(
						'label' => esc_html__('Xiaohongshu Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_xiaohongshu',
						'type' => 'text'
					)
				);
				// Douban link
				$wp_customize->add_setting(
					'zaxu_social_douban', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_douban', 
					array(
						'label' => esc_html__('Douban Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_douban',
						'type' => 'text'
					)
				);
				// NetEase Music link
				$wp_customize->add_setting(
					'zaxu_social_netease_music', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_netease_music', 
					array(
						'label' => esc_html__('NetEase Music Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_netease_music',
						'type' => 'text'
					)
				);
				// Taobao link
				$wp_customize->add_setting(
					'zaxu_social_taobao', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_taobao', 
					array(
						'label' => esc_html__('Taobao Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_taobao',
						'type' => 'text'
					)
				);
				// Youku link
				$wp_customize->add_setting(
					'zaxu_social_youku', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_youku', 
					array(
						'label' => esc_html__('Youku Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_youku',
						'type' => 'text'
					)
				);
				// Bilibili link
				$wp_customize->add_setting(
					'zaxu_social_bilibili', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_bilibili', 
					array(
						'label' => esc_html__('Bilibili Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_bilibili',
						'type' => 'text'
					)
				);
				// YouTube link
				$wp_customize->add_setting(
					'zaxu_social_youtube', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_youtube', 
					array(
						'label' => esc_html__('YouTube Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_youtube',
						'type' => 'text'
					)
				);
				// Google Plus link
				$wp_customize->add_setting(
					'zaxu_social_google_plus', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_google_plus', 
					array(
						'label' => esc_html__('Google Plus Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_google_plus',
						'type' => 'text'
					)
				);
				// Github link
				$wp_customize->add_setting(
					'zaxu_social_github', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_github', 
					array(
						'label' => esc_html__('Github Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_github',
						'type' => 'text'
					)
				);
				// Gitee link
				$wp_customize->add_setting(
					'zaxu_social_gitee', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_gitee', 
					array(
						'label' => esc_html__('Gitee Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_gitee',
						'type' => 'text'
					)
				);
				// CodePen link
				$wp_customize->add_setting(
					'zaxu_social_codepen', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_codepen', 
					array(
						'label' => esc_html__('CodePen Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_codepen',
						'type' => 'text'
					)
				);
				// 500px link
				$wp_customize->add_setting(
					'zaxu_social_500px', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_500px', 
					array(
						'label' => esc_html__('500px Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_500px',
						'type' => 'text'
					)
				);
				// Bēhance link
				$wp_customize->add_setting(
					'zaxu_social_behance', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_behance', 
					array(
						'label' => esc_html__('Bēhance Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_behance',
						'type' => 'text'
					)
				);
				// Dribbble link
				$wp_customize->add_setting(
					'zaxu_social_dribbble', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_dribbble', 
					array(
						'label' => esc_html__('Dribbble Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_dribbble',
						'type' => 'text'
					)
				);
				// Facebook link
				$wp_customize->add_setting(
					'zaxu_social_facebook', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_facebook', 
					array(
						'label' => esc_html__('Facebook Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_facebook',
						'type' => 'text'
					)
				);
				// Instagram link
				$wp_customize->add_setting(
					'zaxu_social_instagram', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_instagram', 
					array(
						'label' => esc_html__('Instagram Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_instagram',
						'type' => 'text'
					)
				);
				// Line Username
				$wp_customize->add_setting(
					'zaxu_social_line', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_line', 
					array(
						'label' => esc_html__('Line Username', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_line',
						'type' => 'text'
					)
				);
				// LinkedIn link
				$wp_customize->add_setting(
					'zaxu_social_linkedin', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_linkedin', 
					array(
						'label' => esc_html__('LinkedIn Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_linkedin',
						'type' => 'text'
					)
				);
				// Pinterest link
				$wp_customize->add_setting(
					'zaxu_social_pinterest', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_pinterest', 
					array(
						'label' => esc_html__('Pinterest Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_pinterest',
						'type' => 'text'
					)
				);
				// Skype Username
				$wp_customize->add_setting(
					'zaxu_social_skype', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_skype', 
					array(
						'label' => esc_html__('Skype Username', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_skype',
						'type' => 'text'
					)
				);
				// Snapchat Username
				$wp_customize->add_setting(
					'zaxu_social_snapchat', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_snapchat', 
					array(
						'label' => esc_html__('Snapchat Username', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_snapchat',
						'type' => 'text'
					)
				);
				// Soundcloud link
				$wp_customize->add_setting(
					'zaxu_social_soundcloud', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_soundcloud', 
					array(
						'label' => esc_html__('Soundcloud Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_soundcloud',
						'type' => 'text'
					)
				);
				// Twitter link
				$wp_customize->add_setting(
					'zaxu_social_twitter', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_twitter', 
					array(
						'label' => esc_html__('Twitter Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_twitter',
						'type' => 'text'
					)
				);
				// Medium link
				$wp_customize->add_setting(
					'zaxu_social_medium', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_medium', 
					array(
						'label' => esc_html__('Medium Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_medium',
						'type' => 'text'
					)
				);
				// RSS link
				$wp_customize->add_setting(
					'zaxu_social_rss', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_rss', 
					array(
						'label' => esc_html__('RSS Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_rss',
						'type' => 'text'
					)
				);
				// Flickr link
				$wp_customize->add_setting(
					'zaxu_social_flickr', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_flickr', 
					array(
						'label' => esc_html__('Flickr Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_flickr',
						'type' => 'text'
					)
				);
				// Vimeo link
				$wp_customize->add_setting(
					'zaxu_social_vimeo', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_vimeo', 
					array(
						'label' => esc_html__('Vimeo Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_vimeo',
						'type' => 'text'
					)
				);
				// WhatsApp Phone Number
				$wp_customize->add_setting(
					'zaxu_social_whatsapp', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_whatsapp', 
					array(
						'label' => esc_html__('WhatsApp Phone Number', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_whatsapp',
						'type' => 'text'
					)
				);
				// WordPress link
				$wp_customize->add_setting(
					'zaxu_social_wordpress', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_wordpress', 
					array(
						'label' => esc_html__('WordPress Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_wordpress',
						'type' => 'text'
					)
				);
				// Slack link
				$wp_customize->add_setting(
					'zaxu_social_slack', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_social_slack', 
					array(
						'label' => esc_html__('Slack Link', 'zaxu'),
						'section' => 'footer_social_section',
						'settings' => 'zaxu_social_slack',
						'type' => 'text'
					)
				);
			// Sub sections end
		// ========Sub Social section end========

		// ========Sub Copyright section start========
			$wp_customize->add_section( 'footer_copyright_section', array(
				'title' => esc_html__('Copyright', 'zaxu'),
				'panel' => 'footer',
			) );
			// Sub sections start
				// Website created time
				$wp_customize->add_setting(
					'zaxu_web_created_time', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_web_created_time', 
					array(
						'label' => esc_html__('Website Created On', 'zaxu'),
						'description' => '',
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter your website created time...', 'zaxu'),
						),
						'section' => 'footer_copyright_section',
						'settings' => 'zaxu_web_created_time',
						'type' => 'number'
					)
				);
				// Thanks for ZAXU
				$wp_customize->add_setting(
					'zaxu_thanks_for_zaxu', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_thanks_for_zaxu',
					array(
						'label' => esc_html__('Thanks for ZAXU', 'zaxu'),
						'description' => esc_html__('Show theme developer information for visitors.', 'zaxu'),
						'section' => 'footer_copyright_section',
						'settings' => 'zaxu_thanks_for_zaxu',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Thanks for WordPress
				$wp_customize->add_setting(
					'zaxu_thanks_for_wordpress', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_thanks_for_wordpress',
					array(
						'label' => esc_html__('Thanks for WordPress', 'zaxu'),
						'description' => esc_html__('Show CMS system information for visitors.', 'zaxu'),
						'section' => 'footer_copyright_section',
						'settings' => 'zaxu_thanks_for_wordpress',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Copyright section end========

		// ========Sub Statement section start========
			$wp_customize->add_section( 'footer_statement_section', array(
				'title' => esc_html__('Statement', 'zaxu'),
				'panel' => 'footer',
			) );
			// Sub sections start
				// ICP number (Only apply to China Mainland)
				$wp_customize->add_setting(
					'zaxu_icp_num', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_icp_num', 
					array(
						'label' => esc_html__('ICP Number (Only apply to China Mainland)', 'zaxu'),
						'description' => esc_html__('Please DO NOT USE link.', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('沪ICP备12345678号-1', 'zaxu'),
						),
						'section' => 'footer_statement_section',
						'settings' => 'zaxu_icp_num',
						'type' => 'text'
					)
				);
				// ICP certificate number (Only apply to China Mainland)
				$wp_customize->add_setting(
					'zaxu_icp_cert_num', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_icp_cert_num', 
					array(
						'label' => esc_html__('ICP Certificate Number (Only apply to China Mainland)', 'zaxu'),
						'description' => esc_html__('Please DO NOT USE link.', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('沪ICP证123456号', 'zaxu'),
						),
						'section' => 'footer_statement_section',
						'settings' => 'zaxu_icp_cert_num',
						'type' => 'text'
					)
				);
				// Public network security number (Only apply to China Mainland)
				$wp_customize->add_setting(
					'zaxu_public_network_security_num', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_public_network_security_num', 
					array(
						'label' => esc_html__('Public Network Security Number (Only apply to China Mainland)', 'zaxu'),
						'description' => esc_html__('Please DO NOT USE link.', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('沪公网安备 12345678901234号', 'zaxu'),
						),
						'section' => 'footer_statement_section',
						'settings' => 'zaxu_public_network_security_num',
						'type' => 'text'
					)
				);
				// Business license
				$wp_customize->add_setting(
					'zaxu_business_license', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'zaxu_business_license',
						array(
							'label' => esc_html__('Business License', 'zaxu'),
							'description' => esc_html__('Upload Business license image.', 'zaxu'),
							'section' => 'footer_statement_section',
							'settings' => 'zaxu_business_license',
						)
					)
				);
				// Extra content
				$wp_customize->add_setting(
					'zaxu_extra_content', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_textarea'
					)
				);
				$wp_customize->add_control('zaxu_extra_content', 
					array(
						'label' => esc_html__('Extra Content', 'zaxu'),
						'description' => esc_html__('Custom your extra content (Support HTML Code)', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter your extra content...', 'zaxu')
						),
						'section' => 'footer_statement_section',
						'settings' => 'zaxu_extra_content',
						'type' => 'textarea'
					)
				);
			// Sub sections end
		// ========Sub Statement section end========

		// ========Sub Language Switcher section start========
			if ( function_exists('pll_the_languages') || function_exists('icl_object_id') ) {
				$wp_customize->add_section( 'footer_lang_switcher_section', array(
					'title' => esc_html__('Language Switcher', 'zaxu'),
					'panel' => 'footer',
				) );
				// Sub sections start
					$wp_customize->add_setting(
						'zaxu_lang_switcher', array(
							'default' => 'enabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_lang_switcher',
						array(
							'label' => esc_html__('Language Switcher', 'zaxu'),
							'description' => '',
							'section' => 'footer_lang_switcher_section',
							'settings' => 'zaxu_lang_switcher',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);
				// Sub sections end
			}
		// ========Sub Language Switcher section end========
	// ********Footer end********

	// ********Blog start********
		$wp_customize->add_panel( 'blog', array(
			'title' => esc_html__('Blog', 'zaxu'),
			'priority' => 6,
		) );
		// ========Sub Recommended post section start========
			$wp_customize->add_section( 'blog_recommended_post_section', array(
				'title' => esc_html__('Recommended Post', 'zaxu'),
				'panel' => 'blog',
			) );
			// Sub sections start
				// Recommended post
				$wp_customize->add_setting(
					'zaxu_recommended_post', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_recommended_post',
					array(
						'label' => esc_html__('Recommended Post', 'zaxu'),
						'section' => 'blog_recommended_post_section',
						'settings' => 'zaxu_recommended_post',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'random' => esc_html__('Random', 'zaxu'),
							'specified' => esc_html__('Specified', 'zaxu')
						)
					)
				);
				// Specified content
				$wp_customize->add_setting( 'zaxu_specified_content', array(
					'default' => 'none',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_specified_content'
				) );
				$wp_customize->add_control(
					new WP_Customize_Post_Select_Multiple(
						$wp_customize,
						'zaxu_blog_carousel',
						array(
							'label' => esc_html__('Specified Content', 'zaxu'),
							'description' => esc_html__('You should select at least three posts by pressing the Ctrl (Cmd) key.', 'zaxu'),
							'section' => 'blog_recommended_post_section',
							'settings' => 'zaxu_specified_content',
						)
					)
				);
				// Cover ratio
				$wp_customize->add_setting(
					'zaxu_recommended_post_cover_ratio', array(
						'default' => '4_3',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_recommended_post_cover_ratio',
					array(
						'label' => esc_html__('Cover Ratio', 'zaxu'),
						'section' => 'blog_recommended_post_section',
						'settings' => 'zaxu_recommended_post_cover_ratio',
						'type' => 'select',
						'choices' => array(
							'1_1' => esc_html__('1:1', 'zaxu'),
							'4_3' => esc_html__('4:3', 'zaxu'),
							'16_9' => esc_html__('16:9', 'zaxu')
						)
					)
				);
				// Summary display
				$wp_customize->add_setting(
					'zaxu_recommend_summary_display', array(
						'default' => 'separate',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_summary_display',
					array(
						'label' => esc_html__('Summary Display', 'zaxu'),
						'section' => 'blog_recommended_post_section',
						'settings' => 'zaxu_recommend_summary_display',
						'type' => 'select',
						'choices' => array(
							'disabled' => __('Disabled', 'zaxu'),
							'overlay' => __('Overlay', 'zaxu'),
							'separate' => __('Separate', 'zaxu'),
						)
					)
				);
				// Attribute information
				$wp_customize->add_setting(
					'zaxu_recommend_attr_info', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_attr_info',
					array(
						'label' => esc_html__('Attribute Information', 'zaxu'),
						'section' => 'blog_recommended_post_section',
						'settings' => 'zaxu_recommend_attr_info',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Recommended post section end========

		// ========Sub Blog page section start========
			$wp_customize->add_section( 'blog_page_section', array(
				'title' => esc_html__('Blog Page', 'zaxu'),
				'panel' => 'blog',
			) );
			// Sub sections start
				// Style
				$wp_customize->add_setting(
					'zaxu_blog_style', array(
						'default' => 'grid',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_style',
					array(
						'label' => esc_html__('Style', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_style',
						'type' => 'select',
						'choices' => array(
							'list' => esc_html__('List', 'zaxu'),
							'grid' => esc_html__('Grid', 'zaxu'),
							'showcase' => esc_html__('Showcase', 'zaxu'),
						)
					)
				);
				// Per page
				$wp_customize->add_setting(
					'zaxu_blog_per_page', array(
						'default' => get_option('posts_per_page'),
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input_blog_per_page'
					)
				);
				$wp_customize->add_control('zaxu_blog_per_page', 
					array(
						'label' => esc_html__('Per Page', 'zaxu'),
						'input_attrs' => array(
							'min' => 1,
							'step'  => 1,
							'placeholder' => esc_html__('Please enter quantity...', 'zaxu'),
						),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_per_page',
						'type' => 'number',
					)
				);
				// Filter
				$wp_customize->add_setting(
					'zaxu_blog_filter', array(
						'default' => 'text',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_filter',
					array(
						'label' => esc_html__('Filter', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_filter',
						'type' => 'select',
						'choices' => array(
							'thumbnail' => esc_html__('Thumbnail & Text', 'zaxu'),
							'text' => esc_html__('Plain Text', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Cover ratio
				$wp_customize->add_setting(
					'zaxu_post_cover_ratio', array(
						'default' => 'responsive',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_post_cover_ratio',
					array(
						'label' => esc_html__('Cover Ratio', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_post_cover_ratio',
						'type' => 'select',
						'choices' => array(
							'responsive' => esc_html__('Responsive', 'zaxu'),
							'1_1' => esc_html__('1:1', 'zaxu'),
							'4_3' => esc_html__('4:3', 'zaxu'),
							'16_9' => esc_html__('16:9', 'zaxu')
						)
					)
				);
				// Grid column
				$wp_customize->add_setting(
					'zaxu_blog_cols', array(
						'default' => 'auto',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_cols',
					array(
						'label' => esc_html__('Grid Column', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_cols',
						'type' => 'select',
						'choices' => array(
							'auto' => esc_html__('Auto', 'zaxu'),
							'2' => esc_html__('2', 'zaxu'),
							'3' => esc_html__('3', 'zaxu')
						)
					)
				);
				// Summary display
				$wp_customize->add_setting(
					'zaxu_blog_summary_display', array(
						'default' => 'separate',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_summary_display',
					array(
						'label' => esc_html__('Summary Display', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_summary_display',
						'type' => 'select',
						'choices' => array(
							'disabled' => __('Disabled', 'zaxu'),
							'overlay' => __('Overlay', 'zaxu'),
							'separate' => __('Separate', 'zaxu'),
						)
					)
				);
				// Attribute information
				$wp_customize->add_setting(
					'zaxu_blog_page_attr_info', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_page_attr_info',
					array(
						'label' => esc_html__('Attribute Information', 'zaxu'),
						'section' => 'blog_page_section',
						'settings' => 'zaxu_blog_page_attr_info',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Blog page section end========

		// ========Sub Blog details page section start========
			$wp_customize->add_section( 'blog_details_page_section', array(
				'title' => esc_html__('Blog Details Page', 'zaxu'),
				'panel' => 'blog',
			) );
			// Sub sections start
				// Style
				$wp_customize->add_setting(
					'zaxu_blog_details_page_style', array(
						'default' => 'journal',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_details_page_style',
					array(
						'label' => esc_html__('Style', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_blog_details_page_style',
						'type' => 'select',
						'choices' => array(
							'journal' => esc_html__('Journal', 'zaxu'),
							'feature' => esc_html__('Feature', 'zaxu')
						)
					)
				);
				// Attribute information
				$wp_customize->add_setting(
					'zaxu_blog_details_page_attr_info', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_details_page_attr_info',
					array(
						'label' => esc_html__('Attribute Information', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_blog_details_page_attr_info',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Tag
				$wp_customize->add_setting(
					'zaxu_blog_tag', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_tag',
					array(
						'label' => esc_html__('Tag', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_blog_tag',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Rating button
				$wp_customize->add_setting(
					'zaxu_post_rating', array(
						'default' => 'all',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_post_rating',
					array(
						'label' => esc_html__('Rating Button', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_post_rating',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'like' => esc_html__('Like Button Only', 'zaxu'),
							'dislike' => esc_html__('Dislike Button Only', 'zaxu'),
							'all' => esc_html__('Like & Dislike Button', 'zaxu'),
						)
					)
				);
				// Post navigation
				$wp_customize->add_setting(
					'zaxu_recommend_blog_navigation', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_blog_navigation',
					array(
						'label' => esc_html__('Post Navigation', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_recommend_blog_navigation',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Post navigation ratio
				$wp_customize->add_setting(
					'zaxu_recommend_blog_navigation_ratio', array(
						'default' => '4_3',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_blog_navigation_ratio',
					array(
						'label' => esc_html__('Post Navigation Ratio', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_recommend_blog_navigation_ratio',
						'type' => 'select',
						'choices' => array(
							'4_3' => esc_html__('4:3', 'zaxu'),
							'16_9' => esc_html__('16:9', 'zaxu'),
						)
					)
				);
				// Widget sidebar
				$wp_customize->add_setting(
					'zaxu_post_widget_sidebar', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_post_widget_sidebar',
					array(
						'label' => esc_html__('Widget Sidebar', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_post_widget_sidebar',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Scroll top button
				$wp_customize->add_setting(
					'zaxu_blog_scroll_top_button', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_blog_scroll_top_button',
					array(
						'label' => esc_html__('Scroll Top Button', 'zaxu'),
						'section' => 'blog_details_page_section',
						'settings' => 'zaxu_blog_scroll_top_button',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Blog details page section end========
	// ********Blog end********

	// ********Portfolio start********
		$wp_customize->add_panel( 'portfolio', array(
			'title' => esc_html__('Portfolio', 'zaxu'),
			'priority' => 7,
		) );
		// ========Sub Portfolio page section start========
			$wp_customize->add_section( 'portfolio_page_section', array(
				'title' => esc_html__('Portfolio Page', 'zaxu'),
				'panel' => 'portfolio',
			) );
			// Sub sections start
				// Style
				$wp_customize->add_setting(
					'zaxu_portfolio_style', array(
						'default' => 'grid',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_style',
					array(
						'label' => esc_html__('Style', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_style',
						'type' => 'select',
						'choices' => array(
							'list' => esc_html__('List', 'zaxu'),
							'grid' => esc_html__('Grid', 'zaxu'),
							'showcase' => esc_html__('Showcase', 'zaxu'),
						)
					)
				);
				// Per page
				$wp_customize->add_setting(
					'zaxu_portfolio_per_page', array(
						'default' => get_option('posts_per_page'),
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_per_page', 
					array(
						'label' => esc_html__('Per Page', 'zaxu'),
						'input_attrs' => array(
							'min' => 1,
							'step'  => 1,
							'placeholder' => esc_html__('Please enter quantity...', 'zaxu'),
						),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_per_page',
						'type' => 'number',
					)
				);
				// Filter
				$wp_customize->add_setting(
					'zaxu_portfolio_filter', array(
						'default' => 'text',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_filter',
					array(
						'label' => esc_html__('Filter', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_filter',
						'type' => 'select',
						'choices' => array(
							'thumbnail' => esc_html__('Thumbnail & Text', 'zaxu'),
							'text' => esc_html__('Plain Text', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Cover ratio
				$wp_customize->add_setting(
					'zaxu_portfolio_cover_ratio', array(
						'default' => 'responsive',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_cover_ratio',
					array(
						'label' => esc_html__('Cover Ratio', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_cover_ratio',
						'type' => 'select',
						'choices' => array(
							'responsive' => esc_html__('Responsive', 'zaxu'),
							'1_1' => esc_html__('1:1', 'zaxu'),
							'4_3' => esc_html__('4:3', 'zaxu'),
							'16_9' => esc_html__('16:9', 'zaxu')
						)
					)
				);
				// Grid column
				$wp_customize->add_setting(
					'zaxu_portfolio_cols', array(
						'default' => 'auto',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_cols',
					array(
						'label' => esc_html__('Grid Column', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_cols',
						'type' => 'select',
						'choices' => array(
							'auto' => esc_html__('Auto', 'zaxu'),
							'2' => esc_html__('2', 'zaxu'),
							'3' => esc_html__('3', 'zaxu')
						)
					)
				);
				// Summary display
				$wp_customize->add_setting(
					'zaxu_portfolio_summary_display', array(
						'default' => 'separate',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_summary_display',
					array(
						'label' => esc_html__('Summary Display', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_summary_display',
						'type' => 'select',
						'choices' => array(
							'disabled' => __('Disabled', 'zaxu'),
							'overlay' => __('Overlay', 'zaxu'),
							'separate' => __('Separate', 'zaxu'),
						)
					)
				);
				// Attribute information
				$wp_customize->add_setting(
					'zaxu_portfolio_page_attr_info', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_page_attr_info',
					array(
						'label' => esc_html__('Attribute Information', 'zaxu'),
						'section' => 'portfolio_page_section',
						'settings' => 'zaxu_portfolio_page_attr_info',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Portfolio page section end========

		// ========Sub Portfolio details page section start========
			$wp_customize->add_section( 'portfolio_details_page_section', array(
				'title' => esc_html__('Portfolio Details Page', 'zaxu'),
				'panel' => 'portfolio',
			) );
			// Sub sections start
				// Style
				$wp_customize->add_setting(
					'zaxu_portfolio_details_page_style', array(
						'default' => 'journal',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_details_page_style',
					array(
						'label' => esc_html__('Style', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_details_page_style',
						'type' => 'select',
						'choices' => array(
							'journal' => esc_html__('Journal', 'zaxu'),
							'feature' => esc_html__('Feature', 'zaxu')
						)
					)
				);
				// Attribute information
				$wp_customize->add_setting(
					'zaxu_portfolio_details_page_attr_info', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_details_page_attr_info',
					array(
						'label' => esc_html__('Attribute Information', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_details_page_attr_info',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Tag
				$wp_customize->add_setting(
					'zaxu_portfolio_tag', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_tag',
					array(
						'label' => esc_html__('Tag', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_tag',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Rating button
				$wp_customize->add_setting(
					'zaxu_portfolio_rating', array(
						'default' => 'all',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_rating',
					array(
						'label' => esc_html__('Rating Button', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_rating',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'like' => esc_html__('Like Button Only', 'zaxu'),
							'dislike' => esc_html__('Dislike Button Only', 'zaxu'),
							'all' => esc_html__('Like & Dislike Button', 'zaxu'),
						)
					)
				);
				// Post navigation
				$wp_customize->add_setting(
					'zaxu_recommend_portfolio_navigation', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_portfolio_navigation',
					array(
						'label' => esc_html__('Post Navigation', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_recommend_portfolio_navigation',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Post navigation ratio
				$wp_customize->add_setting(
					'zaxu_recommend_portfolio_navigation_ratio', array(
						'default' => '4_3',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_recommend_portfolio_navigation_ratio',
					array(
						'label' => esc_html__('Post Navigation Ratio', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_recommend_portfolio_navigation_ratio',
						'type' => 'select',
						'choices' => array(
							'4_3' => esc_html__('4:3', 'zaxu'),
							'16_9' => esc_html__('16:9', 'zaxu'),
						)
					)
				);
				// Widget sidebar
				$wp_customize->add_setting(
					'zaxu_portfolio_widget_sidebar', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_widget_sidebar',
					array(
						'label' => esc_html__('Widget Sidebar', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_widget_sidebar',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Scroll top button
				$wp_customize->add_setting(
					'zaxu_portfolio_scroll_top_button', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_portfolio_scroll_top_button',
					array(
						'label' => esc_html__('Scroll Top Button', 'zaxu'),
						'section' => 'portfolio_details_page_section',
						'settings' => 'zaxu_portfolio_scroll_top_button',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Portfolio details page section end========
	// ********Portfolio end********

	// ********Documentation start********
		if (get_theme_mod('zaxu_dashboard_doc_type', 'disabled') == 'enabled') {
			$wp_customize->add_section( 'documentation_section', array(
				'title' => esc_html__('Documentation', 'zaxu'),
				'priority' => 8,
			) );
			// Sub sections start
				// Documentation navigation
				$wp_customize->add_setting(
					'zaxu_doc_navigation', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_doc_navigation',
					array(
						'label' => esc_html__('Documentation Navigation', 'zaxu'),
						'section' => 'documentation_section',
						'settings' => 'zaxu_doc_navigation',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Email feedback
				$wp_customize->add_setting(
					'zaxu_doc_email_feedback', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_doc_email_feedback',
					array(
						'label' => esc_html__('Email Feedback', 'zaxu'),
						'section' => 'documentation_section',
						'settings' => 'zaxu_doc_email_feedback',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Email address
				$wp_customize->add_setting(
					'zaxu_doc_email_address', array(
						'default' => get_option('admin_email'),
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_doc_email_address', 
					array(
						'label' => esc_html__('Email Address', 'zaxu'),
						'description' => esc_html__('The email address where the feedbacks should sent to.', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => '',
						),
						'section' => 'documentation_section',
						'settings' => 'zaxu_doc_email_address',
						'type' => 'text'
					)
				);
				// Helpful feedback
				$wp_customize->add_setting(
					'zaxu_doc_helpful_feedback', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_doc_helpful_feedback',
					array(
						'label' => esc_html__('Helpful Feedback', 'zaxu'),
						'section' => 'documentation_section',
						'settings' => 'zaxu_doc_helpful_feedback',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		}
	// ********Documentation end********

	// ********Search start********
		$wp_customize->add_section( 'search_section', array(
			'title' => esc_html__('Search', 'zaxu'),
			'priority' => 9,
		) );
		// Sub sections start
			// Style
			$wp_customize->add_setting(
				'zaxu_search_style', array(
					'default' => 'list',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_search_style',
				array(
					'label' => esc_html__('Style', 'zaxu'),
					'section' => 'search_section',
					'settings' => 'zaxu_search_style',
					'type' => 'select',
					'choices' => array(
						'list' => esc_html__('List', 'zaxu'),
						'grid' => esc_html__('Grid', 'zaxu'),
					)
				)
			);
			// Per page
			$wp_customize->add_setting(
				'zaxu_search_per_page', array(
					'default' => get_option('posts_per_page'),
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_input'
				)
			);
			$wp_customize->add_control('zaxu_search_per_page', 
				array(
					'label' => esc_html__('Per Page', 'zaxu'),
					'input_attrs' => array(
						'min' => 1,
						'step'  => 1,
						'placeholder' => esc_html__('Please enter quantity...', 'zaxu'),
					),
					'section' => 'search_section',
					'settings' => 'zaxu_search_per_page',
					'type' => 'number',
				)
			);
			// Cover ratio
			$wp_customize->add_setting(
				'zaxu_search_cover_ratio', array(
					'default' => 'responsive',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_search_cover_ratio',
				array(
					'label' => esc_html__('Cover Ratio', 'zaxu'),
					'section' => 'search_section',
					'settings' => 'zaxu_search_cover_ratio',
					'type' => 'select',
					'choices' => array(
						'responsive' => esc_html__('Responsive', 'zaxu'),
						'1_1' => esc_html__('1:1', 'zaxu'),
						'4_3' => esc_html__('4:3', 'zaxu'),
						'16_9' => esc_html__('16:9', 'zaxu')
					)
				)
			);
			// Grid column
			$wp_customize->add_setting(
				'zaxu_search_cols', array(
					'default' => 'auto',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_search_cols',
				array(
					'label' => esc_html__('Grid Column', 'zaxu'),
					'section' => 'search_section',
					'settings' => 'zaxu_search_cols',
					'type' => 'select',
					'choices' => array(
						'auto' => esc_html__('Auto', 'zaxu'),
						'2' => esc_html__('2', 'zaxu'),
						'3' => esc_html__('3', 'zaxu'),
						'4' => esc_html__('4', 'zaxu')
					)
				)
			);
			// Summary display
			$wp_customize->add_setting(
				'zaxu_search_summary_display', array(
					'default' => 'separate',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_search_summary_display',
				array(
					'label' => esc_html__('Summary Display', 'zaxu'),
					'section' => 'search_section',
					'settings' => 'zaxu_search_summary_display',
					'type' => 'select',
					'choices' => array(
						'disabled' => __('Disabled', 'zaxu'),
						'overlay' => __('Overlay', 'zaxu'),
						'separate' => __('Separate', 'zaxu'),
					)
				)
			);
			// Attribute information
			$wp_customize->add_setting(
				'zaxu_search_attr_info', array(
					'default' => 'enabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_search_attr_info',
				array(
					'label' => esc_html__('Attribute Information', 'zaxu'),
					'section' => 'search_section',
					'settings' => 'zaxu_search_attr_info',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
		// Sub sections end
	// ********Search end********

	// ********Archive start********
		$wp_customize->add_section( 'archive_section', array(
			'title' => esc_html__('Archive', 'zaxu'),
			'priority' => 9,
		) );
		// Sub sections start
			// Style
			$wp_customize->add_setting(
				'zaxu_archive_style', array(
					'default' => 'grid',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_archive_style',
				array(
					'label' => esc_html__('Style', 'zaxu'),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_style',
					'type' => 'select',
					'choices' => array(
						'list' => esc_html__('List', 'zaxu'),
						'grid' => esc_html__('Grid', 'zaxu'),
					)
				)
			);
			// Per page
			$wp_customize->add_setting(
				'zaxu_archive_per_page', array(
					'default' => get_option('posts_per_page'),
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_input'
				)
			);
			$wp_customize->add_control('zaxu_archive_per_page', 
				array(
					'label' => esc_html__('Per Page', 'zaxu'),
					'input_attrs' => array(
						'min' => 1,
						'step'  => 1,
						'placeholder' => esc_html__('Please enter quantity...', 'zaxu'),
					),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_per_page',
					'type' => 'number',
				)
			);
			// Cover ratio
			$wp_customize->add_setting(
				'zaxu_archive_cover_ratio', array(
					'default' => 'responsive',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_archive_cover_ratio',
				array(
					'label' => esc_html__('Cover Ratio', 'zaxu'),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_cover_ratio',
					'type' => 'select',
					'choices' => array(
						'responsive' => esc_html__('Responsive', 'zaxu'),
						'1_1' => esc_html__('1:1', 'zaxu'),
						'4_3' => esc_html__('4:3', 'zaxu'),
						'16_9' => esc_html__('16:9', 'zaxu')
					)
				)
			);
			// Grid column
			$wp_customize->add_setting(
				'zaxu_archive_cols', array(
					'default' => 'auto',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_archive_cols',
				array(
					'label' => esc_html__('Grid Column', 'zaxu'),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_cols',
					'type' => 'select',
					'choices' => array(
						'auto' => esc_html__('Auto', 'zaxu'),
						'2' => esc_html__('2', 'zaxu'),
						'3' => esc_html__('3', 'zaxu'),
						'4' => esc_html__('4', 'zaxu')
					)
				)
			);
			// Summary display
			$wp_customize->add_setting(
				'zaxu_archive_summary_display', array(
					'default' => 'separate',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_multiple_select'
				)
			);
			$wp_customize->add_control('zaxu_archive_summary_display',
				array(
					'label' => esc_html__('Summary Display', 'zaxu'),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_summary_display',
					'type' => 'select',
					'choices' => array(
						'disabled' => __('Disabled', 'zaxu'),
						'overlay' => __('Overlay', 'zaxu'),
						'separate' => __('Separate', 'zaxu'),
					)
				)
			);
			// Attribute information
			$wp_customize->add_setting(
				'zaxu_archive_attr_info', array(
					'default' => 'enabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_archive_attr_info',
				array(
					'label' => esc_html__('Attribute Information', 'zaxu'),
					'section' => 'archive_section',
					'settings' => 'zaxu_archive_attr_info',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
		// Sub sections end
	// ********Archive end********

	// ********404 start********
		$wp_customize->add_section( '404_section', array(
			'title' => esc_html__('404', 'zaxu'),
			'priority' => 10,
		) );
		// Sub sections start
			// 404 page background image
			$wp_customize->add_setting(
				'zaxu_404_bg', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'zaxu_404_bg',
					array(
						'label' => esc_html__('404 Page Background Image', 'zaxu'),
						'section' => '404_section',
						'settings' => 'zaxu_404_bg'
					)
				)
			);
			// 404 page slogan
			$wp_customize->add_setting(
				'zaxu_404_slogan', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_textarea'
				)
			);
			$wp_customize->add_control('zaxu_404_slogan', 
				array(
					'label' => esc_html__('404 Page Slogan', 'zaxu'),
					'description' => esc_html__('When page can&rsquo;t be found, show 404 slogan for visitors.', 'zaxu'),
					'input_attrs' => array(
						'placeholder' => esc_html__('Please enter 404 slogan...', 'zaxu')
					),
					'section' => '404_section',
					'settings' => 'zaxu_404_slogan',
					'type' => 'textarea'
				)
			);
		// Sub sections end
	// ********404 end********

	// ********Advanced start********
		$wp_customize->add_panel( 'advanced_panel', array(
			'title' => esc_html__('Advanced', 'zaxu'),
			'priority' => 11,
		) );
		// ========Sub Login page section start========
			$wp_customize->add_section( 'advanced_login_section', array(
				'title' => esc_html__('Login Page', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// WordPress logo
				$wp_customize->add_setting(
					'zaxu_login_wp_logo', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_login_wp_logo',
					array(
						'label' => esc_html__('WordPress Logo', 'zaxu'),
						'section' => 'advanced_login_section',
						'settings' => 'zaxu_login_wp_logo',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Back to homepage link
				$wp_customize->add_setting(
					'zaxu_login_back_to_homepage_link', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_login_back_to_homepage_link',
					array(
						'label' => esc_html__('Back to Homepage Link', 'zaxu'),
						'section' => 'advanced_login_section',
						'settings' => 'zaxu_login_back_to_homepage_link',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Account sharing
				$wp_customize->add_setting(
					'zaxu_login_account_sharing', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_login_account_sharing',
					array(
						'label' => esc_html__('Account Sharing', 'zaxu'),
						'section' => 'advanced_login_section',
						'settings' => 'zaxu_login_account_sharing',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Math captcha
				$wp_customize->add_setting(
					'zaxu_login_captcha', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_login_captcha',
					array(
						'label' => esc_html__('Math Captcha', 'zaxu'),
						'section' => 'advanced_login_section',
						'settings' => 'zaxu_login_captcha',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Login page section end========

		// ========Sub Dashboard section start========
			$wp_customize->add_section( 'advanced_dashboard_section', array(
				'title' => esc_html__('Dashboard', 'zaxu'),
				'description' => esc_html__('Warning: Those function only for developer.', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// ACF editor menu
				if ( class_exists('ACF') ) {
					$wp_customize->add_setting(
						'zaxu_dashboard_acf_editor_menu', array(
							'default' => 'disabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_dashboard_acf_editor_menu',
						array(
							'label' => esc_html__('ACF Editor Menu', 'zaxu'),
							'section' => 'advanced_dashboard_section',
							'settings' => 'zaxu_dashboard_acf_editor_menu',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);
				}
				// Admin bar WordPress logo
				$wp_customize->add_setting(
					'zaxu_dashboard_admin_bar_wp_logo', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_admin_bar_wp_logo',
					array(
						'label' => esc_html__('Admin Bar WordPress Logo', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_admin_bar_wp_logo',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				global $wp_version;
				if ($wp_version >= 5) {
					// WordPress Block-based Editor
					$wp_customize->add_setting(
						'zaxu_dashboard_wp_block_based_editor', array(
							'default' => 'enabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_dashboard_wp_block_based_editor',
						array(
							'label' => esc_html__('WordPress Block-based Editor', 'zaxu'),
							'section' => 'advanced_dashboard_section',
							'settings' => 'zaxu_dashboard_wp_block_based_editor',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);

					// Reusable Blocks
					$wp_customize->add_setting(
						'zaxu_dashboard_reusable_blocks', array(
							'default' => 'disabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_dashboard_reusable_blocks',
						array(
							'label' => esc_html__('Reusable Blocks', 'zaxu'),
							'section' => 'advanced_dashboard_section',
							'settings' => 'zaxu_dashboard_reusable_blocks',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);
				}
				// Welcome panel
				$wp_customize->add_setting(
					'zaxu_dashboard_welcome_panel', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_welcome_panel',
					array(
						'label' => esc_html__('Welcome Panel', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_welcome_panel',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// At a glance
				$wp_customize->add_setting(
					'zaxu_dashboard_right_now', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_right_now',
					array(
						'label' => esc_html__('At a Glance', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_right_now',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Activity
				$wp_customize->add_setting(
					'zaxu_dashboard_activity', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_activity',
					array(
						'label' => esc_html__('Activity', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_activity',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Quick press
				$wp_customize->add_setting(
					'zaxu_dashboard_quick_press', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_quick_press',
					array(
						'label' => esc_html__('Quick Press', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_quick_press',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// WordPress events and news
				$wp_customize->add_setting(
					'zaxu_dashboard_primary', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_primary',
					array(
						'label' => esc_html__('WordPress Events and News', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_primary',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Site health
				$wp_customize->add_setting(
					'zaxu_dashboard_site_health', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_site_health',
					array(
						'label' => esc_html__('Site Health', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_site_health',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Screen options tab
				$wp_customize->add_setting(
					'zaxu_dashboard_screen_options_tab', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_screen_options_tab',
					array(
						'label' => esc_html__('Screen Options Tab', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_screen_options_tab',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Help tab
				$wp_customize->add_setting(
					'zaxu_dashboard_help_tab', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_help_tab',
					array(
						'label' => esc_html__('Help Tab', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_help_tab',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Post type
				$wp_customize->add_setting(
					'zaxu_dashboard_post_type', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_post_type',
					array(
						'label' => esc_html__('Post Type', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_post_type',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'administrator' => esc_html__('Administrator Only', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Portfolio type
				$wp_customize->add_setting(
					'zaxu_dashboard_portfolio_type', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_portfolio_type',
					array(
						'label' => esc_html__('Portfolio Type', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_portfolio_type',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'administrator' => esc_html__('Administrator Only', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Page type
				$wp_customize->add_setting(
					'zaxu_dashboard_page_type', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_page_type',
					array(
						'label' => esc_html__('Page Type', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_page_type',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'administrator' => esc_html__('Administrator Only', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Documentation type
				$wp_customize->add_setting(
					'zaxu_dashboard_doc_type', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_doc_type',
					array(
						'label' => esc_html__('Documentation Type', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_doc_type',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Tools
				$wp_customize->add_setting(
					'zaxu_dashboard_tools', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_tools',
					array(
						'label' => esc_html__('Tools', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_tools',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'administrator' => esc_html__('Administrator Only', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// WordPress copyright information
				$wp_customize->add_setting(
					'zaxu_dashboard_copyright', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_copyright',
					array(
						'label' => esc_html__('WordPress Copyright Information', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_copyright',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Theme version information
				$wp_customize->add_setting(
					'zaxu_dashboard_theme_version', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_theme_version',
					array(
						'label' => esc_html__('Theme Version Information', 'zaxu'),
						'section' => 'advanced_dashboard_section',
						'settings' => 'zaxu_dashboard_theme_version',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Dashboard section end========

		// ========Sub Maintenance section start========
			$wp_customize->add_section( 'advanced_maintenance_section', array(
				'title' => esc_html__('Maintenance', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// Maintenance status
				$wp_customize->add_setting(
					'zaxu_maintenance_switch', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_switch',
					array(
						'label' => esc_html__('Maintenance Status', 'zaxu'),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_switch',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Allow access to your website
				$wp_customize->add_setting(
					'zaxu_maintenance_user_role', array(
						'default' => 'administrator',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_user_role',
					array(
						'label' => esc_html__('Allow Access to Your Website', 'zaxu'),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_user_role',
						'type' => 'select',
						'choices' => array(
							'administrator' => esc_html__('Administrator Only', 'zaxu'),
							'logged' => esc_html__('Logged in user', 'zaxu')
						)
					)
				);
				// Title
				$wp_customize->add_setting(
					'zaxu_maintenance_title', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_title', 
					array(
						'label' => esc_html__('Title', 'zaxu'),
						'description' => '',
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter custom title...', 'zaxu'),
						),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_title',
						'type' => 'text'
					)
				);
				// Description
				$wp_customize->add_setting(
					'zaxu_maintenance_description', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_textarea'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_description', 
					array(
						'label' => esc_html__('Description', 'zaxu'),
						'description' => '',
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter custom description...', 'zaxu'),
						),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_description',
						'type' => 'textarea'
					)
				);
				// Countdown status
				$wp_customize->add_setting(
					'zaxu_maintenance_countdown_switch', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_countdown_switch',
					array(
						'label' => esc_html__('Countdown Status', 'zaxu'),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_countdown_switch',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Countdown description
				$wp_customize->add_setting(
					'zaxu_maintenance_countdown_description', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_textarea'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_countdown_description', 
					array(
						'label' => esc_html__('Countdown Description', 'zaxu'),
						'description' => '',
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter custom countdown description...', 'zaxu'),
						),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_countdown_description',
						'type' => 'textarea'
					)
				);
				// Launch date
				$wp_customize->add_setting(
					'zaxu_maintenance_countdown_launch_date', array(
						'default' => date("Y-m-d H:i:s"),
						'type' => 'theme_mod',
						'transport' => 'refresh',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_sanitize_date_time'
					)
				);
				$wp_customize->add_control('zaxu_maintenance_countdown_launch_date',
					array(
					'label' => esc_html__('Launch Date', 'zaxu'),
					'section' => 'advanced_maintenance_section',
					'settings' => 'zaxu_maintenance_countdown_launch_date',
					'twelve_hour_format' => false,
					'type' => 'date_time'
					)
				);
				// Text color
				$wp_customize->add_setting(
					'zaxu_maintenance_text_color', array(
						'default' => '#333333',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'transport' => 'postMessage',
						'sanitize_callback' => 'sanitize_hex_color'
					)
				);
				$wp_customize->add_control( 
					new WP_Customize_Color_Control( 
					$wp_customize, 
					'zaxu_maintenance_text_color', 
					array(
						'label' => esc_html__('Text Color', 'zaxu'),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_text_color'
					) ) 
				);
				// Background color
				$wp_customize->add_setting(
					'zaxu_maintenance_background_color', array(
						'default' => '#f2f2f2',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'transport' => 'postMessage',
						'sanitize_callback' => 'sanitize_hex_color'
					)
				);
				$wp_customize->add_control( 
					new WP_Customize_Color_Control( 
						$wp_customize, 
						'zaxu_maintenance_background_color', 
						array(
							'label' => esc_html__('Background Color', 'zaxu'),
							'section' => 'advanced_maintenance_section',
							'settings' => 'zaxu_maintenance_background_color'
						)
					) 
				);
				// Background image
				$wp_customize->add_setting(
					'zaxu_maintenance_background_image', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
					$wp_customize,
					'zaxu_maintenance_background_image',
					array(
						'label' => esc_html__('Background Image', 'zaxu'),
						'description' => esc_html__('Recommended to use 1920 × 1080 pixels image, the background image will be set to full screen.', 'zaxu'),
						'section' => 'advanced_maintenance_section',
						'settings' => 'zaxu_maintenance_background_image'
					)
					)
				);
			// Sub sections end
		// ========Sub Maintenance section end========

		// ========Sub Screen response section start========
			$wp_customize->add_section( 'advanced_screen_response_section', array(
				'title' => esc_html__('Screen Response', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// Screen support
				$wp_customize->add_setting(
					'zaxu_screen_support', array(
						'default' => 'all',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_screen_support',
					array(
						'label' => esc_html__('Screen Support', 'zaxu'),
						'section' => 'advanced_screen_response_section',
						'settings' => 'zaxu_screen_support',
						'type' => 'select',
						'choices' => array(
							'all' => esc_html__('All', 'zaxu'),
							'landscape' => esc_html__('Landscape Only', 'zaxu'),
							'portrait' => esc_html__('Portrait Only', 'zaxu')
						)
					)
				);
				// Device/Client support
				$wp_customize->add_setting(
					'zaxu_screen_client_support', array(
						'default' => 'all',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_screen_client_support',
					array(
						'label' => esc_html__('Device/Client Support', 'zaxu'),
						'section' => 'advanced_screen_response_section',
						'settings' => 'zaxu_screen_client_support',
						'type' => 'select',
						'choices' => array(
							'all' => esc_html__('All', 'zaxu'),
							'desktop' => esc_html__('Desktop Only', 'zaxu'),
							'mobile' => esc_html__('Mobile Only', 'zaxu'),
							'wechat' => esc_html__('WeChat Only', 'zaxu')
						)
					)
				);
			// Sub sections end
		// ========Sub Screen response section end========

		// ========Performance section start========
			$wp_customize->add_section( 'advanced_performance_section', array(
				'title' => esc_html__('Performance', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// Minify Engine
				$wp_customize->add_setting(
					'zaxu_minify_engine', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_minify_engine',
					array(
						'label' => esc_html__('Minify Engine', 'zaxu'),
						'description' => esc_html__('Minify frontend code.', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_minify_engine',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// PJAX powered
				$wp_customize->add_setting(
					'zaxu_site_ajax', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_site_ajax',
					array(
						'label' => esc_html__('PJAX Powered', 'zaxu'),
						'description' => esc_html__('If you set the background music, please enable it.', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_site_ajax',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Lazyload
				$wp_customize->add_setting(
					'zaxu_lazyload', array(
						'default' => 'enabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_lazyload',
					array(
						'label' => esc_html__('Lazyload', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_lazyload',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Media library attachment rename
				$wp_customize->add_setting(
					'zaxu_dashboard_attachment_rename', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_dashboard_attachment_rename',
					array(
						'label' => esc_html__('Media Library Attachment Rename', 'zaxu'),
						'description' => esc_html__('Attachments will be renamed automatically when upload.', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_dashboard_attachment_rename',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'timestamp' => esc_html__('According to the Timestamp', 'zaxu'),
							'md5' => esc_html__('According to the MD5 Hash', 'zaxu')
						)
					)
				);
				// Media library image compression quality
				$wp_customize->add_setting(
					'zaxu_image_compress', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_image_compress',
					array(
						'label' => esc_html__('Media Library Image Compression Quality', 'zaxu'),
						'description' => esc_html__('Images will be compressed automatically when upload.', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_image_compress',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'high' => esc_html__('High (Recommended)', 'zaxu'),
							'medium' => esc_html__('Medium', 'zaxu'),
							'low' => esc_html__('Low', 'zaxu')
						)
					)
				);
				// Media library image size limit
				$wp_customize->add_setting(
					'zaxu_image_size_limit', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_multiple_select'
					)
				);
				$wp_customize->add_control('zaxu_image_size_limit',
					array(
						'label' => esc_html__('Media Library Image Size Limit', 'zaxu'),
						'description' => esc_html__('Images will be resized automatically when upload.', 'zaxu'),
						'section' => 'advanced_performance_section',
						'settings' => 'zaxu_image_size_limit',
						'type' => 'select',
						'choices' => array(
							'disabled' => esc_html__('Disabled', 'zaxu'),
							'2560' => esc_html__('Max Width 2560 Pixels', 'zaxu'),
							'1920' => esc_html__('Max Width 1920 Pixels (Recommended)', 'zaxu'),
							'1280' => esc_html__('Max Width 1280 Pixels', 'zaxu'),
						)
					)
				);
			// Sub sections end
		// ========Performance section start========

		// ========WeChat section start========
			$wp_customize->add_section( 'advanced_wechat_section', array(
				'title' => esc_html__('WeChat', 'zaxu'),
				'panel' => 'advanced_panel',
			) );
			// Sub sections start
				// WeChat JS-SDK
				$wp_customize->add_setting(
					'zaxu_wechat_js_sdk', array(
						'default' => 'disabled',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_select'
					)
				);
				$wp_customize->add_control('zaxu_wechat_js_sdk',
					array(
						'label' => esc_html__('WeChat JS-SDK', 'zaxu'),
						'description' => esc_html__('Your domain name must be set to secured domain name on WeChat official accounts platform.', 'zaxu'),
						'section' => 'advanced_wechat_section',
						'settings' => 'zaxu_wechat_js_sdk',
						'type' => 'select',
						'choices' => array(
							'enabled' => esc_html__('Enabled', 'zaxu'),
							'disabled' => esc_html__('Disabled', 'zaxu')
						)
					)
				);
				// Developer ID (AppID)
				$wp_customize->add_setting(
					'zaxu_wechat_app_id', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_wechat_app_id', 
					array(
						'label' => esc_html__('Developer ID (AppID)', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter AppID...', 'zaxu'),
						),
						'section' => 'advanced_wechat_section',
						'settings' => 'zaxu_wechat_app_id',
						'type' => 'text'
					)
				);
				// Developer password (AppSecret)
				$wp_customize->add_setting(
					'zaxu_wechat_app_secret', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_input'
					)
				);
				$wp_customize->add_control('zaxu_wechat_app_secret', 
					array(
						'label' => esc_html__('Developer Password (AppSecret)', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter AppSecret...', 'zaxu'),
						),
						'section' => 'advanced_wechat_section',
						'settings' => 'zaxu_wechat_app_secret',
						'type' => 'text'
					)
				);
				// Global sharing thumbnail
				$wp_customize->add_setting(
					'zaxu_wechat_global_sharing_thumbnail', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'absint'
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Cropped_Image_Control(
						$wp_customize,
						'zaxu_wechat_global_sharing_thumbnail',
						array(
							'label' => esc_html__('Global Sharing Thumbnail', 'zaxu'),
							'description' => esc_html__('Recommended to use 600 × 600 pixels image (You can also set single post or page separately)', 'zaxu'),
							'section' => 'advanced_wechat_section',
							'settings' => 'zaxu_wechat_global_sharing_thumbnail',
							'width' => 600,
							'height' => 600,
							'flex_width' => false,
							'flex_height' => false,
						)
					)
				);
				// Global sharing description
				$wp_customize->add_setting(
					'zaxu_wechat_global_sharing_description', array(
						'default' => '',
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'zaxu_validate_textarea'
					)
				);
				$wp_customize->add_control('zaxu_wechat_global_sharing_description', 
					array(
						'label' => esc_html__('Global Sharing Description', 'zaxu'),
						'description' => esc_html__('You can also set single post or page separately.', 'zaxu'),
						'input_attrs' => array(
							'placeholder' => esc_html__('Please enter global sharing description...', 'zaxu')
						),
						'section' => 'advanced_wechat_section',
						'settings' => 'zaxu_wechat_global_sharing_description',
						'type' => 'textarea'
					)
				);
			// Sub sections end
		// ========WeChat section end========
	// ********Advanced end********

	// ********Site identity start********
		// Sub sections start
			// Favicon
			$wp_customize->add_setting(
				'zaxu_favicon_image', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_favicon_image_sanitize_image'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'zaxu_favicon_image',
					array(
						'label' => esc_html__('Favicon', 'zaxu'),
						'description' => esc_html__('Upload favicon image (JPG/PNG format) Suggested image dimensions at: 1000 × 1000 pixels.', 'zaxu'),
						'section' => 'title_tagline',
						'settings' => 'zaxu_favicon_image'
					)
				)
			);
			// Pinned tab icon
			$wp_customize->add_setting(
				'zaxu_pinned_tab_icon', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_pinned_tab_icon_sanitize_image'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'zaxu_pinned_tab_icon',
					array(
						'label' => esc_html__('Pinned Tab Icon', 'zaxu'),
						'description' => esc_html__('Upload pinned tab icon image (SVG format) Pinned tab icon use 100% black for all vectors with a transparent background and set to 16 × 16 pixels.', 'zaxu'),
						'section' => 'title_tagline',
						'settings' => 'zaxu_pinned_tab_icon',
					)
				)
			);
			// Pinned tab icon background color
			$wp_customize->add_setting(
				'zaxu_pinned_tab_icon_background_color', array(
					'default' => '#ff3b30',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_control( 
				new WP_Customize_Color_Control( 
					$wp_customize, 
					'zaxu_pinned_tab_icon_background_color', 
					array(
						'label' => esc_html__('Pinned Tab Icon Background Color', 'zaxu'),
						'section' => 'title_tagline',
						'settings' => 'zaxu_pinned_tab_icon_background_color'
					)
				) 
			);
			// Progressive web apps
			$wp_customize->add_setting(
				'zaxu_pwas', array(
					'default' => 'disabled',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'zaxu_validate_select'
				)
			);
			$wp_customize->add_control('zaxu_pwas',
				array(
					'label' => esc_html__('Progressive Web Apps (PWAs)', 'zaxu'),
					'section' => 'title_tagline',
					'settings' => 'zaxu_pwas',
					'type' => 'select',
					'choices' => array(
						'enabled' => esc_html__('Enabled', 'zaxu'),
						'disabled' => esc_html__('Disabled', 'zaxu')
					)
				)
			);
			// Apple touch icon
			$wp_customize->add_setting(
				'zaxu_apple_touch_icon', array(
					'default' => '',
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'absint'
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Cropped_Image_Control(
					$wp_customize,
					'zaxu_apple_touch_icon',
					array(
						'label' => esc_html__('Apple Touch Icon', 'zaxu'),
						'description' => esc_html__('Upload apple touch icon image (JPG/PNG format) Suggested image dimensions at most 1200 × 1200 pixels.', 'zaxu'),
						'section' => 'title_tagline',
						'settings' => 'zaxu_apple_touch_icon',
						'width' => 1200,
						'height' => 1200,
						'flex_width' => false,
						'flex_height' => false,
					)
				)
			);
		// Sub sections end
	// ********Site identity end********

	// ********WooCommerce start********
		if ( class_exists('WooCommerce') ) {
			// ========Store page section start========
				$wp_customize->add_section( 'woocommerce_store_page_section', array(
					'title' => esc_html__('Store Page', 'zaxu'),
					'panel' => 'woocommerce',
					'priority' => 12,
				) );
				// Sub sections start
					// Style
					$wp_customize->add_setting(
						'zaxu_product_style', array(
							'default' => 'grid',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_product_style',
						array(
							'label' => esc_html__('Style', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_style',
							'type' => 'select',
							'choices' => array(
								'list' => esc_html__('List', 'zaxu'),
								'grid' => esc_html__('Grid', 'zaxu')
							)
						)
					);
					// Product sorting
					$wp_customize->add_setting(
						'zaxu_default_product_sorting', array(
							'default' => 'date',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_default_product_sorting',
						array(
							'label' => esc_html__('Product Sorting', 'zaxu'),
							'description' => '',
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_default_product_sorting',
							'type' => 'select',
							'choices' => array(
								'menu_order' => esc_html__('Default Sorting (Custom Ordering + Name)', 'zaxu'),
								'popularity' => esc_html__('Popularity (Sales)', 'zaxu'),
								'rating' => esc_html__('Average Rating', 'zaxu'),
								'date' => esc_html__('Sort by Most Recent', 'zaxu'),
								'price' => esc_html__('Sort by Price (ASC)', 'zaxu'),
								'price_desc' => esc_html__('Sort by Price (DESC)', 'zaxu')
							)
						)
					);
					// Per page
					$wp_customize->add_setting(
						'zaxu_product_per_page', array(
							'default' => get_option('posts_per_page'),
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_input'
						)
					);
					$wp_customize->add_control('zaxu_product_per_page', 
						array(
							'label' => esc_html__('Per Page', 'zaxu'),
							'description' => '',
							'input_attrs' => array(
								'min' => 1,
								'step' => 1,
								'placeholder' => esc_html__('Please enter quantity...', 'zaxu'),
							),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_per_page',
							'type' => 'number',
						)
					);
					// Cover ratio
					$wp_customize->add_setting(
						'zaxu_product_cover_ratio', array(
							'default' => 'responsive',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_product_cover_ratio',
						array(
							'label' => esc_html__('Cover Ratio', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_cover_ratio',
							'type' => 'select',
							'choices' => array(
								'responsive' => esc_html__('Responsive', 'zaxu'),
								'1_1' => esc_html__('1:1', 'zaxu'),
								'4_3' => esc_html__('4:3', 'zaxu'),
								'16_9' => esc_html__('16:9', 'zaxu')
							)
						)
					);
					// Grid column
					$wp_customize->add_setting(
						'zaxu_product_cols', array(
							'default' => 'auto',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_product_cols',
						array(
							'label' => esc_html__('Grid Column', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_cols',
							'type' => 'select',
							'choices' => array(
								'auto' => esc_html__('Auto', 'zaxu'),
								'2' => esc_html__('2', 'zaxu'),
								'3' => esc_html__('3', 'zaxu')
							)
						)
					);
					// Summary display
					$wp_customize->add_setting(
						'zaxu_product_summary_display', array(
							'default' => 'separate',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_product_summary_display',
						array(
							'label' => esc_html__('Summary Display', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_summary_display',
							'type' => 'select',
							'choices' => array(
								'disabled' => __('Disabled', 'zaxu'),
								'overlay' => __('Overlay', 'zaxu'),
								'separate' => __('Separate', 'zaxu'),
							)
						)
					);
					// Attribute information
					$wp_customize->add_setting(
						'zaxu_product_attr_info', array(
							'default' => 'disabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_product_attr_info',
						array(
							'label' => esc_html__('Attribute Information', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_attr_info',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);
					// Product category
					$wp_customize->add_setting(
						'zaxu_product_category', array(
							'default' => 'enabled',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_select'
						)
					);
					$wp_customize->add_control('zaxu_product_category',
						array(
							'label' => esc_html__('Product Category', 'zaxu'),
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_product_category',
							'type' => 'select',
							'choices' => array(
								'enabled' => esc_html__('Enabled', 'zaxu'),
								'disabled' => esc_html__('Disabled', 'zaxu')
							)
						)
					);
					// Product category sorting
					$wp_customize->add_setting(
						'zaxu_default_product_category_sorting', array(
							'default' => 'name',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_default_product_category_sorting',
						array(
							'label' => esc_html__('Product Category Sorting', 'zaxu'),
							'description' => '',
							'section' => 'woocommerce_store_page_section',
							'settings' => 'zaxu_default_product_category_sorting',
							'type' => 'select',
							'choices' => array(
								'menu_order' => esc_html__('Default Sorting (Custom Ordering + Name)', 'zaxu'),
								'name' => esc_html__('Sort by Name (ASC)', 'zaxu'),
								'name_desc' => esc_html__('Sort by Name (DESC)', 'zaxu')
							)
						)
					);
				// Sub sections end
			// ========Store page section end========

			// ========Product details page section start========
				$wp_customize->add_section( 'woocommerce_product_details_page_zaxu', array(
					'title' => esc_html__('Product Details Page', 'zaxu'),
					'panel' => 'woocommerce',
					'priority' => 13,
				) );
				// Sub sections start
					// Product preview image ratio
					$wp_customize->add_setting(
						'zaxu_product_preview_image_ratio', array(
							'default' => '1_1',
							'type' => 'theme_mod',
							'capability' => 'edit_theme_options',
							'sanitize_callback' => 'zaxu_validate_multiple_select'
						)
					);
					$wp_customize->add_control('zaxu_product_preview_image_ratio',
						array(
							'label' => esc_html__('Product Preview Image Ratio', 'zaxu'),
							'section' => 'woocommerce_product_details_page_zaxu',
							'settings' => 'zaxu_product_preview_image_ratio',
							'type' => 'select',
							'choices' => array(
								'responsive' => esc_html__('Responsive', 'zaxu'),
								'1_1' => esc_html__('1:1', 'zaxu'),
								'4_3' => esc_html__('4:3', 'zaxu'),
								'16_9' => esc_html__('16:9', 'zaxu')
							)
						)
					);
				// Sub sections end
			// ========Product details page section end========
		}
	// ********WooCommerce end********
}
add_action('customize_register', 'zaxu_customize_register');

// Sanitize function start
	function zaxu_validate_select($select_box) {
		if ( in_array($select_box, array('enabled', 'disabled', 'always'), true) ) {
			return $select_box;
		}
	}

	function zaxu_validate_multiple_select($input, $setting) {
		return $input;
	}

	function zaxu_sanitize_basic_number($number) {
		return absint($number);
	}

	function zaxu_validate_specified_content($input) {
		return $input;
	}

	function zaxu_validate_input($input) {
		return esc_html($input);
	}

	function zaxu_validate_textarea($textarea) {
		return esc_html($textarea);
	}

	function zaxu_validate_input_blog_per_page($input) {
		update_option('posts_per_page', $input);
		return esc_html($input);
	}

	function zaxu_pinned_tab_icon_sanitize_image($image) {
		if ($image) {
			$mimes = array(
				'svg' => 'image/svg+xml'
			);
			$file = wp_check_filetype($image, $mimes);
			return ($file['ext'] ? $image : $image->default);
		} else {
			return ($image);
		}
	}

	function zaxu_favicon_image_sanitize_image($image) {
		if ( empty($image) ) {
			$file = wp_upload_dir()['basedir'] . '/' . 'favicon.ico';
			wp_delete_file($file);
			return $image;
		} else {
			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png' => 'image/png'
			);
			$file = wp_check_filetype($image, $mimes);

			$source = $_SERVER['DOCUMENT_ROOT'] . wp_make_link_relative($image);
			$destination = wp_upload_dir()['basedir'] . '/' . 'favicon.ico';
			$ico_lib = new PHP_ICO(
				$source,
				array(
					array(16, 16),
					array(32, 32),
					array(48, 48),
					array(64, 64),
					array(128, 128)
				)
			);
			$ico_lib->save_ico($destination);

			return ($file['ext'] ? $image : $image->default);
		}
	}

	function zaxu_sanitize_date_time($input) {
		$date = new DateTime($input);
		return $date->format('Y-m-d H:i:s');
	}
// Sanitize function end

function zaxu_customize_scripts() {
	?>
		<script type="text/javascript">
			jQuery(function($) {
				// ********Color scheme start********
					// Dynamic color start
						wp.customize('zaxu_dynamic_color', function(setting) {
							function init_zaxu_dynamic_color(arr) {
								for(var i = 0, len = arr.length; i < len; i++) {
									wp.customize.control(arr[i], function(control) {
										var visibility = function() {
											if (setting.get() == 'enabled') {
												control.container.hide();
											} else {
												control.container.show();
											};
										}
										visibility();
										setting.bind(visibility);
									});
								}
							}

							init_zaxu_dynamic_color([
								'zaxu_bg_color',
								'zaxu_txt_color',
								'zaxu_acc_color'
							])
						});
					// Dynamic color end
				// ********Color scheme end********

				// ********Navigation start********
					// Logo start
						wp.customize('zaxu_logo', function(setting) {
							wp.customize.control('zaxu_logo_height', function(control) {
								var visibility = function() {
									if (setting.get() == '') {
										control.container.hide();
									} else {
										control.container.show();
									};
								}
								visibility();
								setting.bind(visibility);
							});
						});
					// Logo end
				// ********Navigation end********

				// ********Footer start********
					// ========Sub Social section start========
						// Social icon start
							wp.customize('zaxu_social_icon', function(setting) {
								function init_zaxu_social_icon(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_social_icon([
									'zaxu_social_zaxu',
									'zaxu_social_email',
									'zaxu_social_wechat_qr_code',
									'zaxu_social_wechat_mini_program_qr_code',
									'zaxu_social_tiktok_qr_code',
									'zaxu_social_kwai_qr_code',
									'zaxu_social_weibo',
									'zaxu_social_qq',
									'zaxu_social_qzone',
									'zaxu_social_zhihu',
									'zaxu_social_zcool',
									'zaxu_social_huaban',
									'zaxu_social_lofter',
									'zaxu_social_tieba',
									'zaxu_social_xiongzhang',
									'zaxu_social_jianshu',
									'zaxu_social_xiaohongshu',
									'zaxu_social_douban',
									'zaxu_social_netease_music',
									'zaxu_social_taobao',
									'zaxu_social_youku',
									'zaxu_social_bilibili',
									'zaxu_social_youtube',
									'zaxu_social_google_plus',
									'zaxu_social_github',
									'zaxu_social_gitee',
									'zaxu_social_codepen',
									'zaxu_social_500px',
									'zaxu_social_behance',
									'zaxu_social_dribbble',
									'zaxu_social_facebook',
									'zaxu_social_instagram',
									'zaxu_social_line',
									'zaxu_social_linkedin',
									'zaxu_social_pinterest',
									'zaxu_social_skype',
									'zaxu_social_snapchat',
									'zaxu_social_soundcloud',
									'zaxu_social_twitter',
									'zaxu_social_medium',
									'zaxu_social_rss',
									'zaxu_social_flickr',
									'zaxu_social_vimeo',
									'zaxu_social_whatsapp',
									'zaxu_social_wordpress',
									'zaxu_social_slack'
								]);
							});
						// Social icon end
					// ========Sub Social section end========
				// ********Footer end********

				// ********Blog start********
					// ========Sub Recommended post section start========
						wp.customize.section('blog_recommended_post_section', function(section) {
							section.expanded.bind(function(isExpanded) {
								if (isExpanded) {
									wp.customize.previewer.previewUrl.set('<?php
										echo get_permalink( get_option('page_for_posts') );
									?>');
								}
							});
						});
						// Recommended post start
							wp.customize('zaxu_recommended_post', function(setting) {
								function init_zaxu_recommended_post(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'disabled') {
													control.container.hide();
												} else if (setting.get() == 'random') {
													control.container.show();
													$("#customize-control-zaxu_blog_carousel").hide();
												} else if (setting.get() == 'specified') {
													control.container.show();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_recommended_post([
									'zaxu_blog_carousel',
									'zaxu_recommended_post_cover_ratio',
									'zaxu_recommend_summary_display',
									'zaxu_recommend_attr_info'
								]);
							});
						// Recommended post end
					// ========Sub Recommended post section end========

					// ========Sub Blog page section start========
						wp.customize.section('blog_page_section', function(section) {
							section.expanded.bind(function(isExpanded) {
								if (isExpanded) {
									wp.customize.previewer.previewUrl.set('<?php
										echo get_permalink( get_option('page_for_posts') );
									?>');
								}
							});
						});
						// Style start
							wp.customize('zaxu_blog_style', function(setting) {
								function init_zaxu_blog_style(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'showcase') {
													control.container.hide();
												} else if (setting.get() == 'grid') {
													control.container.show();
												} else if (setting.get() == 'list') {
													$("#customize-control-zaxu_blog_per_page").show();
													$("#customize-control-zaxu_blog_filter").show();
													$("#customize-control-zaxu_post_cover_ratio").hide();
													$("#customize-control-zaxu_blog_cols").hide();
													$("#customize-control-zaxu_blog_summary_display").hide();
													$("#customize-control-zaxu_blog_page_attr_info").show();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_blog_style([
									'zaxu_blog_per_page',
									'zaxu_blog_filter',
									'zaxu_post_cover_ratio',
									'zaxu_blog_cols',
									'zaxu_blog_summary_display',
									'zaxu_blog_page_attr_info'
								]);
							});
						// Style end
					// ========Sub Blog page section end========

					// ========Sub Blog details page section start========
						// Style start
							wp.customize('zaxu_blog_details_page_style', function(setting) {
								function init_zaxu_blog_details_page_style(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'journal') {
													control.container.show();
												} else if (setting.get() == 'feature') {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_blog_details_page_style([
									'zaxu_blog_details_page_attr_info',
								]);
							});
						// Style end

						// Post navigation start
							wp.customize('zaxu_recommend_blog_navigation', function(setting) {
								function init_zaxu_recommend_blog_navigation(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_recommend_blog_navigation([
									'zaxu_recommend_blog_navigation_ratio',
								]);
							});
						// Post navigation end
					// ========Sub Blog details page section end========
				// ********Blog end********

				// ********Portfolio start********
					// ========Sub Portfolio page section start========
						wp.customize.section('portfolio_page_section', function(section) {
							section.expanded.bind(function(isExpanded) {
								if (isExpanded) {
									wp.customize.previewer.previewUrl.set('<?php
										$pages = get_pages(
											array(
												'meta_key' => '_wp_page_template',
												'meta_value' => 'templates/template-portfolio.php',
											)
										);
										$page_id = null;
										foreach($pages as $page) {
											$page_id = $page->ID;
										};
										if ($page_id) {
											echo get_permalink($page_id);
										}
									?>');
								}
							});
						});
						// Style start
							wp.customize('zaxu_portfolio_style', function(setting) {
								function init_zaxu_portfolio_style(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'showcase') {
													control.container.hide();
												} else if (setting.get() == 'grid') {
													control.container.show();
												} else if (setting.get() == 'list') {
													$("#customize-control-zaxu_portfolio_per_page").show();
													$("#customize-control-zaxu_portfolio_filter").show();
													$("#customize-control-zaxu_portfolio_cover_ratio").hide();
													$("#customize-control-zaxu_portfolio_cols").hide();
													$("#customize-control-zaxu_portfolio_summary_display").hide();
													$("#customize-control-zaxu_portfolio_page_attr_info").show();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_portfolio_style([
									'zaxu_portfolio_per_page',
									'zaxu_portfolio_filter',
									'zaxu_portfolio_cover_ratio',
									'zaxu_portfolio_cols',
									'zaxu_portfolio_summary_display',
									'zaxu_portfolio_page_attr_info'
								]);
							});
						// Style end
					// ========Sub Portfolio page section end========

					// ========Sub Portfolio details page section start========
						// Style start
							wp.customize('zaxu_portfolio_details_page_style', function(setting) {
								function init_zaxu_portfolio_details_page_style(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'journal') {
													control.container.show();
												} else if (setting.get() == 'feature') {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_portfolio_details_page_style([
									'zaxu_portfolio_details_page_attr_info',
								]);
							});
						// Style end

						// Post navigation start
							wp.customize('zaxu_recommend_portfolio_navigation', function(setting) {
								function init_zaxu_recommend_portfolio_navigation(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_recommend_portfolio_navigation([
									'zaxu_recommend_portfolio_navigation_ratio',
								]);
							});
						// Post navigation end
					// ========Sub Portfolio details page section end========
				// ********Portfolio end********

				// ********Documentation start********
					wp.customize.section('documentation_section', function(section) {
						section.expanded.bind(function(isExpanded) {
							if (isExpanded) {
								wp.customize.previewer.previewUrl.set('<?php
									$pages = get_pages(
										array(
											'meta_key' => '_wp_page_template',
											'meta_value' => 'templates/template-documentation.php',
										)
									);
									$page_id = null;
									foreach($pages as $page) {
										$page_id = $page->ID;
									};
									if ($page_id) {
										echo get_permalink($page_id);
									}
								?>');
							}
						});
					});
					// Email feedback start
						wp.customize('zaxu_doc_email_feedback', function(setting) {
							function init_zaxu_doc_email_feedback(targetControl) {
								wp.customize.control(targetControl, function(control) {
									var visibility = function() {
										if (setting.get() == 'enabled') {
											control.container.show();
										} else {
											control.container.hide();
										};
									}
									visibility();
									setting.bind(visibility);
								});
							}
							init_zaxu_doc_email_feedback('zaxu_doc_email_address');
						});
					// Email feedback end
				// ********Documentation end********

				// ********Search start********
					// Style start
						wp.customize('zaxu_search_style', function(setting) {
							function init_zaxu_search_style(arr) {
								for(var i = 0, len = arr.length; i < len; i++) {
									wp.customize.control(arr[i], function(control) {
										var visibility = function() {
											if (setting.get() == 'grid') {
												control.container.show();
											} else if (setting.get() == 'list') {
												control.container.hide();
												$("#customize-control-zaxu_search_attr_info").show();
											};
										}
										visibility();
										setting.bind(visibility);
									});
								}
							}

							init_zaxu_search_style([
								'zaxu_search_cover_ratio',
								'zaxu_search_cols',
								'zaxu_search_summary_display',
								'zaxu_search_attr_info'
							]);
						});
					// Style end
				// ********Search end********

				// ********Archive start********
					// Style start
						wp.customize('zaxu_archive_style', function(setting) {
							function init_zaxu_archive_style(arr) {
								for(var i = 0, len = arr.length; i < len; i++) {
									wp.customize.control(arr[i], function(control) {
										var visibility = function() {
											if (setting.get() == 'grid') {
												control.container.show();
											} else if (setting.get() == 'list') {
												control.container.hide();
												$("#customize-control-zaxu_archive_attr_info").show();
											};
										}
										visibility();
										setting.bind(visibility);
									});
								}
							}

							init_zaxu_archive_style([
								'zaxu_archive_cover_ratio',
								'zaxu_archive_cols',
								'zaxu_archive_summary_display',
								'zaxu_archive_attr_info'
							]);
						});
					// Style end
				// ********Archive end********

				// ********Advanced start********
					// ========Sub Maintenance section start========
						// Maintenance status start
							wp.customize('zaxu_maintenance_switch', function(setting) {
								function init_zaxu_maintenance_switch(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
													if ($("#_customize-input-zaxu_maintenance_countdown_switch").children("option:selected").val() == "disabled") {
														$("#customize-control-zaxu_maintenance_countdown_description").hide();
														$("#customize-control-zaxu_maintenance_countdown_launch_date").hide();
													};
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_maintenance_switch([
									'zaxu_maintenance_user_role',
									'zaxu_maintenance_title',
									'zaxu_maintenance_description',
									'zaxu_maintenance_countdown_switch',
									'zaxu_maintenance_countdown_description',
									'zaxu_maintenance_countdown_launch_date',
									'zaxu_maintenance_text_color',
									'zaxu_maintenance_background_color',
									'zaxu_maintenance_background_image',
								]);
							});
						// Maintenance status end

						// Countdown status start
							wp.customize('zaxu_maintenance_countdown_switch', function(setting) {
								function init_zaxu_maintenance_switch(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
													if ($("#_customize-input-zaxu_maintenance_switch").children("option:selected").val() == "disabled") {
														$("#customize-control-zaxu_maintenance_countdown_description").hide();
														$("#customize-control-zaxu_maintenance_countdown_launch_date").hide();
													};
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_maintenance_switch([
									'zaxu_maintenance_countdown_description',
									'zaxu_maintenance_countdown_launch_date',
								]);
							});
						// Countdown status end
					// ========Sub Maintenance section end========

					// ========WeChat section start========
						// WeChat JS-SDK start
							wp.customize('zaxu_wechat_js_sdk', function(setting) {
								function init_zaxu_wechat_js_sdk(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
												} else {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_wechat_js_sdk([
									'zaxu_wechat_app_id',
									'zaxu_wechat_app_secret',
									'zaxu_wechat_global_sharing_thumbnail',
									'zaxu_wechat_global_sharing_description',
								]);
							});
						// WeChat JS-SDK end
					// ========WeChat section end========
				// ********Advanced end********

				// ********Site identity start********
					// Pinned tab icon start
						wp.customize('zaxu_pinned_tab_icon', function(setting) {
							wp.customize.control('zaxu_pinned_tab_icon_background_color', function(control) {
								var visibility = function() {
									if (setting.get() == '') {
										control.container.hide();
									} else {
										control.container.show();
									};
								}
								visibility();
								setting.bind(visibility);
							});
						});
					// Pinned tab icon end
				// ********Site identity end********

				// ********WooCommerce start********
					// ========Store page section start========
						wp.customize.section('woocommerce_store_page_section', function(section) {
							section.expanded.bind(function(isExpanded) {
								if (isExpanded) {
									wp.customize.previewer.previewUrl.set('<?php
										if ( class_exists('WooCommerce') ) {
											echo get_permalink( woocommerce_get_page_id('shop') );
										}
									?>');
								}
							});
						});
						// Style start
							wp.customize('zaxu_product_style', function(setting) {
								function init_zaxu_product_style(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'grid') {
													control.container.show();
												} else if (setting.get() == 'list') {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_product_style([
									'zaxu_product_cover_ratio',
									'zaxu_product_cols',
									'zaxu_product_summary_display'
								]);
							});
						// Style end

						// Product category start
							wp.customize('zaxu_product_category', function(setting) {
								function init_zaxu_product_category(arr) {
									for(var i = 0, len = arr.length; i < len; i++) {
										wp.customize.control(arr[i], function(control) {
											var visibility = function() {
												if (setting.get() == 'enabled') {
													control.container.show();
												} else if (setting.get() == 'disabled') {
													control.container.hide();
												};
											}
											visibility();
											setting.bind(visibility);
										});
									}
								}

								init_zaxu_product_category([
									'zaxu_default_product_category_sorting',
								]);
							});
						// Product category end
					// ========Store page section end========
				// ********WooCommerce end********
			});
		</script>
	<?php
}

add_action('customize_controls_print_scripts', 'zaxu_customize_scripts');
?>