<?php
/*
 * @Description: ACF config functions
 * @Version: 2.7.2
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

if ( function_exists('acf_add_local_field_group') ) {
	// ********Page style start********
		if (get_theme_mod('zaxu_dynamic_color', 'disabled') == 'disabled') {
			acf_add_local_field_group(
				array (
					'key' => 'group_5728563d5fa70',
					'title' => __('Page Style', 'zaxu'),
					'fields' => array (
						// Visibility
						array (
							'key' => 'field_573d6a96b8bb1',
							'label' => __('Use unique different color scheme for this page', 'zaxu'),
							'name' => 'page-scheme',
							'type' => 'true_false',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'message' => '',
							'default_value' => 0,
							'ui' => 1,
							'ui_on_text' => '',
							'ui_off_text' => '',
						),

						// Background color
						array (
							'key' => 'field_5728566adc859',
							'label' => __('Background Color', 'zaxu'),
							'name' => 'page-bg-color',
							'type' => 'color_picker',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array (
								array (
									array (
										'field' => 'field_573d6a96b8bb1',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array (
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'default_value' => '#f2f2f2',
						),

						// Text color
						array (
							'key' => 'field_5728568adc860',
							'label' => __('Text Color', 'zaxu'),
							'name' => 'page-txt-color',
							'type' => 'color_picker',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array (
								array (
									array (
										'field' => 'field_573d6a96b8bb1',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array (
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'default_value' => '#333333',
						),

						// Accent color
						array (
							'key' => 'field_573d6acab8ba2',
							'label' => __('Accent Color', 'zaxu'),
							'name' => 'page-acc-color',
							'type' => 'color_picker',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array (
								array (
									array (
										'field' => 'field_573d6a96b8bb1',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array (
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'default_value' => '#0088cc',
						),
					),
					'location' => array (
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'post',
							),
						),
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'page',
							),
						),
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'portfolio',
							),
						),
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'docs',
							),
						),
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'product',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => 1,
					'description' => '',
				)
			);
		}
	// ********Page style end********

	// ********Slide start********
		acf_add_local_field_group(
			array (
				'key' => 'group_5bffc06672016',
				'title' => __('Slide', 'zaxu'),
				'fields' => array (
					// Visibility
					array (
						'key' => 'field_5bffc548859de',
						'label' => __('Use the slide for this page', 'zaxu'),
						'name' => 'slide_visibility',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),

					// Slide opacity (start)
					array (
						'key' => 'field_5728de3dc85b',
						'label' => __('Slide Opacity (Start)', 'zaxu'),
						'name' => 'page-bg-opacity-s',
						'type' => 'range',
						'instructions' => __('The slide is opacity before scrolling.', 'zaxu'),
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'min' => '',
						'max' => '',
						'step' => '',
						'prepend' => '',
						'append' => '',
					),

					// Slide opacity (end)
					array (
						'key' => 'field_572856c3dc85b',
						'label' => __('Slide Opacity (End)', 'zaxu'),
						'name' => 'page-bg-opacity',
						'type' => 'range',
						'instructions' => __('The slide is opacity after scrolling.', 'zaxu'),
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'min' => '',
						'max' => '',
						'step' => '',
						'prepend' => '',
						'append' => '',
					),

					// Previous/next buttons
					array (
						'key' => 'field_5bffd144512ae',
						'label' => __('Previous/Next Buttons', 'zaxu'),
						'name' => 'slide_previous_and_next_buttons',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),

					// Navigation dots
					array (
						'key' => 'field_5bffd2292fd70',
						'label' => __('Navigation Dots', 'zaxu'),
						'name' => 'slide_navigation_dots',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),

					// Scroll with the content
					array (
						'key' => 'field_5bffcb1ae5716',
						'label' => __('Scroll with the Content', 'zaxu'),
						'name' => 'slide_scroll_with_the_content',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),

					// Autoplay
					array (
						'key' => 'field_5bffc70e69366',
						'label' => __('Autoplay (Second)', 'zaxu'),
						'name' => 'slide_autoplay',
						'type' => 'number',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'default_value' => '0',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '0',
						'max' => '10',
						'step' => '1',
					),

					// Slide height
					array (
						'key' => 'field_5bffc32a09836',
						'name' => 'slide_height_group',
						'label' => __('Slide Height', 'zaxu'),
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array (
							// Desktop tab start
								array (
									'key' => 'field_5bffc32a08965',
									'label' => __('Desktop', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Height
								array (
									'key' => 'field_5bffca56f1ad5',
									'name' => 'slide_height_desktop',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '1',
									'max' => '',
									'step' => '1',
								),

								// Unit
								array (
									'key' => 'field_5bffca56f1ad6',
									'name' => 'slide_height_unit_desktop',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Desktop tab end

							// Pad tab start
								array (
									'key' => 'field_5bffc32a08966',
									'label' => __('Pad (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Visibility
								array (
									'key' => 'field_5bffca56f4833',
									'name' => 'slide_height_visibility_pad',
									'label' => '',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Height
								array (
									'key' => 'field_5bffca56f1ad7',
									'name' => 'slide_height_pad',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4833',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '1',
									'max' => '',
									'step' => '1',
								),

								// Unit
								array (
									'key' => 'field_5bffca56f1ad8',
									'name' => 'slide_height_unit_pad',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4833',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Pad tab end

							// Mobile tab start
								array (
									'key' => 'field_5bffc32a08967',
									'label' => __('Mobile (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Visibility
								array (
									'key' => 'field_5bffca56f4834',
									'name' => 'slide_height_visibility_mobile',
									'label' => '',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Height
								array (
									'key' => 'field_5bffca56f1ad9',
									'name' => 'slide_height_mobile',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4834',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '1',
									'max' => '',
									'step' => '1',
								),

								// Unit
								array (
									'key' => 'field_5bffca56f1a10',
									'name' => 'slide_height_unit_mobile',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4834',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Mobile tab end
						),
					),

					// Page content height
					array (
						'key' => 'field_5bffc32a09837',
						'name' => 'page_content_height_group',
						'label' => __('Page Content Height', 'zaxu'),
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array (
							// Desktop tab start
								array (
									'key' => 'field_5bffc32a08968',
									'label' => __('Desktop', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Height
								array (
									'key' => 'field_5bffc082efc89',
									'name' => 'page_content_height_desktop',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '0',
									'max' => '',
									'step' => '1',
								),

								// Unit
								array (
									'key' => 'field_5bffc082efc90',
									'name' => 'page_content_height_unit_desktop',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Desktop tab end

							// Pad tab start
								array (
									'key' => 'field_5bffc32a08969',
									'label' => __('Pad (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Visibility
								array (
									'key' => 'field_5bffca56f4835',
									'name' => 'page_content_height_visibility_pad',
									'label' => '',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Height
								array (
									'key' => 'field_5bffc082efc91',
									'name' => 'page_content_height_pad',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4835',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '0',
									'max' => '',
									'step' => '1',
								),

								// Unit
								array (
									'key' => 'field_5bffc082efc92',
									'name' => 'page_content_height_unit_pad',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4835',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Pad tab end

							// Mobile tab start
								array (
									'key' => 'field_5bffc32a08970',
									'label' => __('Mobile (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Visibility
								array (
									'key' => 'field_5bffca56f4836',
									'name' => 'page_content_height_visibility_mobile',
									'label' => '',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Height
								array (
									'key' => 'field_5bffc082efc93',
									'name' => 'page_content_height_mobile',
									'label' => __('Height', 'zaxu'),
									'type' => 'number',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4836',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '100',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => '0',
									'max' => '',
									'step' => '1',
								),
								
								// Unit
								array (
									'key' => 'field_5bffc082efc94',
									'name' => 'page_content_height_unit_mobile',
									'label' => __('Unit', 'zaxu'),
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffca56f4836',
												'operator' => '==',
												'value' => '1',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'choices' => array (
										'%' => '%',
										'px' => 'PX',
										'vh' => 'VH',
									),
									'default_value' => '%',
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
							// Mobile tab end
						),
					),

					// Content
					array (
						'key' => 'field_5bffc082efc88',
						'label' => __('Content', 'zaxu'),
						'name' => 'slide_content',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5bffc548859de',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => 'field_5bffc0aeefc89',
						'min' => 0,
						'max' => 0,
						'layout' => 'block',
						'button_label' => __('Add Slide', 'zaxu'),
						'sub_fields' => array (
							// Desktop tab start
								array (
									'key' => 'field_5bffc32a08848',
									'label' => __('Desktop', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Image/Video
								array (
									'key' => 'field_5bffc0aeefc89',
									'label' => __('Image/Video', 'zaxu'),
									'name' => 'slide_image_or_video_desktop',
									'type' => 'file',
									'instructions' => __('You can select/upload image or video file.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'library' => 'all',
									'min_size' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp, webm, mp4',
								),

								// Video cover
								array (
									'key' => 'field_5bffc0aeefc91',
									'label' => __('Video cover', 'zaxu'),
									'name' => 'slide_video_cover_desktop',
									'type' => 'image',
									'instructions' => __('If you selected a video file, please set a video cover.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp',
								),

								// Animation
								array (
									'key' => 'field_5bffc0aeefc90',
									'label' => __('Animation', 'zaxu'),
									'name' => 'slide_animation_desktop',
									'type' => 'true_false',
									'instructions' => __('Slowly transform scale.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 1,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Title
								array (
									'key' => 'field_5bffc1c6efc8a',
									'label' => __('Title', 'zaxu'),
									'name' => 'slide_title_desktop',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Subtitle
								array (
									'key' => 'field_5bffc1fbefc8b',
									'label' => __('Subtitle', 'zaxu'),
									'name' => 'slide_subtitle_desktop',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Link
								array (
									'key' => 'field_5bffce3f716d8',
									'label' => __('Link', 'zaxu'),
									'name' => 'slide_link_desktop',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),

								// Link title
								array (
									'key' => 'field_5bffc1fbe3739',
									'label' => __('Link title', 'zaxu'),
									'name' => 'slide_link_title_desktop',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Open in new tab
								array (
									'key' => 'field_5bffca56f3954',
									'name' => 'slide_link_new_tab_desktop',
									'label' => __('Open in new tab', 'zaxu'),
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Overlay opacity
								array (
									'key' => 'field_5bffcf200e0c1',
									'label' => __('Overlay opacity', 'zaxu'),
									'name' => 'slide_overlay_opacity_desktop',
									'type' => 'range',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '50',
									'min' => '',
									'max' => '',
									'step' => '',
									'prepend' => '',
									'append' => '',
								),

								// Overlay color
								array (
									'key' => 'field_5bffd020ec13a',
									'label' => __('Overlay color', 'zaxu'),
									'name' => 'slide_overlay_color_desktop',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#000000',
								),

								// Caption color
								array (
									'key' => 'field_5bffd064ec13b',
									'label' => __('Caption color', 'zaxu'),
									'name' => 'slide_caption_color_desktop',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc0aeefc89',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#ffffff',
								),
							// Desktop tab end

							// Pad tab start
								array (
									'key' => 'field_5bffc34508849',
									'label' => __('Pad (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Image/Video
								array (
									'key' => 'field_5bffc3996cb4d',
									'label' => __('Image/Video', 'zaxu'),
									'name' => 'slide_image_or_video_pad',
									'type' => 'file',
									'instructions' => __('You can select/upload image or video file.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'library' => 'all',
									'min_size' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp, webm, mp4',
								),

								// Video cover
								array (
									'key' => 'field_5bffc3996cb4h',
									'label' => __('Video cover', 'zaxu'),
									'name' => 'slide_video_cover_pad',
									'type' => 'image',
									'instructions' => __('If you selected a video file, please set a video cover.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp',
								),

								// Animation
								array (
									'key' => 'field_5bffc3996cb4g',
									'label' => __('Animation', 'zaxu'),
									'name' => 'slide_animation_pad',
									'type' => 'true_false',
									'instructions' => __('Slowly transform scale.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 1,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Title
								array (
									'key' => 'field_5bffc3b66cb4e',
									'label' => __('Title', 'zaxu'),
									'name' => 'slide_title_pad',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Subtitle
								array (
									'key' => 'field_5bffc3c36cb4f',
									'label' => __('Subtitle', 'zaxu'),
									'name' => 'slide_subtitle_pad',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Link
								array (
									'key' => 'field_5bffce32716d7',
									'label' => __('Link', 'zaxu'),
									'name' => 'slide_link_pad',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),

								// Link title
								array (
									'key' => 'field_5bffc1fbe3740',
									'label' => __('Link title', 'zaxu'),
									'name' => 'slide_link_title_pad',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Open in new tab
								array (
									'key' => 'field_5bffca56f3955',
									'name' => 'slide_link_new_tab_pad',
									'label' => __('Open in new tab', 'zaxu'),
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Overlay opacity
								array (
									'key' => 'field_5bffd737c0e1c',
									'label' => __('Overlay opacity', 'zaxu'),
									'name' => 'slide_overlay_opacity_pad',
									'type' => 'range',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '50',
									'min' => '',
									'max' => '',
									'step' => '',
									'prepend' => '',
									'append' => '',
								),

								// Overlay color
								array (
									'key' => 'field_5bffd742c0e1d',
									'label' => __('Overlay color', 'zaxu'),
									'name' => 'slide_overlay_color_pad',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#000000',
								),

								// Caption color
								array (
									'key' => 'field_5bffd74bc0e1e',
									'label' => __('Caption color', 'zaxu'),
									'name' => 'slide_caption_color_pad',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc3996cb4d',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#ffffff',
								),
							// Pad tab end

							// Mobile tab start
								array (
									'key' => 'field_5bffc3650884a',
									'label' => __('Mobile (Optional)', 'zaxu'),
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),

								// Image/Video
								array (
									'key' => 'field_5bffc406ac788',
									'label' => __('Image/Video', 'zaxu'),
									'name' => 'slide_image_or_video_mobile',
									'type' => 'file',
									'instructions' => __('You can select/upload image or video file.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'library' => 'all',
									'min_size' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp, webm, mp4',
								),

								// Video cover
								array (
									'key' => 'field_5bffc406ac791',
									'label' => __('Video cover', 'zaxu'),
									'name' => 'slide_video_cover_mobile',
									'type' => 'image',
									'instructions' => __('If you selected a video file, please set a video cover.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => 'jpg, jpeg, png, gif, webp',
								),

								// Animation
								array (
									'key' => 'field_5bffc406ac790',
									'label' => __('Animation', 'zaxu'),
									'name' => 'slide_animation_mobile',
									'type' => 'true_false',
									'instructions' => __('Slowly transform scale.', 'zaxu'),
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 1,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Title
								array (
									'key' => 'field_5bffc410ac789',
									'label' => __('Title', 'zaxu'),
									'name' => 'slide_title_mobile',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Subtitle
								array (
									'key' => 'field_5bffc41bac78a',
									'label' => __('Subtitle', 'zaxu'),
									'name' => 'slide_subtitle_mobile',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Link
								array (
									'key' => 'field_5bffcdf5716d6',
									'label' => __('Link', 'zaxu'),
									'name' => 'slide_link_mobile',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),

								// Link title
								array (
									'key' => 'field_5bffc1fbe3741',
									'label' => __('Link title', 'zaxu'),
									'name' => 'slide_link_title_mobile',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),

								// Open in new tab
								array (
									'key' => 'field_5bffca56f3956',
									'name' => 'slide_link_new_tab_mobile',
									'label' => __('Open in new tab', 'zaxu'),
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'message' => '',
									'default_value' => 0,
									'ui' => 1,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),

								// Overlay opacity
								array (
									'key' => 'field_5bffd79bd81d1',
									'label' => __('Overlay opacity', 'zaxu'),
									'name' => 'slide_overlay_opacity_mobile',
									'type' => 'range',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => array (
										'width' => '33',
										'class' => '',
										'id' => '',
									),
									'default_value' => '50',
									'min' => '',
									'max' => '',
									'step' => '',
									'prepend' => '',
									'append' => '',
								),

								// Overlay color
								array (
									'key' => 'field_5bffd792d81d0',
									'label' => __('Overlay color', 'zaxu'),
									'name' => 'slide_overlay_color_mobile',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#000000',
								),

								// Caption color
								array (
									'key' => 'field_5bffd77dd81ce',
									'label' => __('Caption color', 'zaxu'),
									'name' => 'slide_caption_color_mobile',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array (
										array (
											array (
												'field' => 'field_5bffc406ac788',
												'operator' => '!=empty',
											),
										),
									),
									'wrapper' => [
										'width' => '33',
										'class' => '',
										'id' => '',
									],
									'default_value' => '#ffffff',
								),
							// Mobile tab end
						),
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'post',
						),
					),
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'page',
						),
					),
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'portfolio',
						),
					),
				),
				'menu_order' => 1,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			)
		);
	// ********Slide end********

	// ********Featured video start********
		acf_add_local_field_group(
			array (
				'key' => 'group_5ede1ce1b5b06',
				'title' => __('Featured video', 'zaxu'),
				'fields' => array (
					// Video file
					array (
						'key' => 'field_5ede239ea66f0',
						'label' => __('Video file', 'zaxu'),
						'name' => 'zaxu_featured_video_file',
						'type' => 'file',
						'instructions' => __('You can select/upload a video file.', 'zaxu'),
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'library' => 'all',
						'min_size' => '',
						'max_size' => '',
						'mime_types' => 'mp4',
					),

					// Video cover
					array (
						'key' => 'field_5ede248a30de3',
						'label' => __('Video cover', 'zaxu'),
						'name' => 'zaxu_featured_video_cover',
						'type' => 'image',
						'instructions' => __('If you selected a video file, please set a video cover.', 'zaxu'),
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5ede239ea66f0',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id' => '',
						],
						'return_format' => 'array',
						'preview_size' => 'medium',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => 'jpg, jpeg, png, gif, webp',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'post',
						),
					),
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'portfolio',
						),
					),
				),
				'menu_order' => 2,
				'position' => 'side',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			)
		);
	// ********Featured video end********
}
?>