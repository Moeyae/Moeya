<?php
/*
 * @Description: ACF block functions
 * @Version: 2.7.0
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

function zaxu_theme_block_categories($categories, $post) {
    return array_merge(
        $categories,
        array (
            array (
                'slug' => 'zaxu-category',
                'title' => __('ZAXU Blocks', 'zaxu'),
                'icon' => '',
            ),
        )
    );
}
add_filter('block_categories', 'zaxu_theme_block_categories', 10, 2);

function register_acf_block_types() {
    // ********Highlight code start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5e2dc17bbbb9s',
                'title' => __('Highlight Code', 'zaxu'),
                'fields' => array (
                    // Language
                    array (
                        'key' => 'field_5e2dc17bbbb9c',
                        'label' => __('Language', 'zaxu'),
                        'name' => 'zaxu_highlight_code_language',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'paintext' => 'plaintext',
                            'apache' => 'Apache',
                            'bash' => 'Bash',
                            'cs' => 'C#',
                            'cpp' => 'C++',
                            'css' => 'CSS',
                            'coffeescript' => 'CoffeeScript',
                            'diff' => 'Diff',
                            'go' => 'Go',
                            'diff' => 'Diff',
                            'xml' => 'HTML, XML',
                            'http' => 'HTTP',
                            'json' => 'JSON',
                            'java' => 'Java',
                            'javascript' => 'JavaScript',
                            'kotlin' => 'Kotlin',
                            'less' => 'Less',
                            'lua' => 'Lua',
                            'makefile' => 'Makefile',
                            'markdown' => 'Markdown',
                            'nginx' => 'Nginx',
                            'objectivec' => 'Objective-C',
                            'php' => 'PHP',
                            'perl' => 'Perl',
                            'properties' => 'Properties',
                            'python' => 'Python',
                            'ruby' => 'Ruby',
                            'rust' => 'Rust',
                            'scss' => 'SCSS',
                            'sql' => 'SQL',
                            'shell' => 'Shell Session',
                            'swift' => 'Swift',
                            'ini' => 'TOML, INI',
                            'typescript' => 'TypeScript',
                            'yaml' => 'YAML',
                        ),
                        'default_value' => 'plaintext',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Code
                    array (
                        'key' => 'field_5e2dbbd5f2c08',
                        'label' => __('Code', 'zaxu'),
                        'name' => 'zaxu_highlight_code_content',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => 'code',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => __('Please enter code...', 'zaxu'),
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => '',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-highlight-code',
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
        
        acf_register_block_type(
            array (
                'name' => 'zaxu-highlight-code',
                'title' => __('Highlight Code', 'zaxu'),
                'description' => __('Display syntax-highlighting code.', 'zaxu'),
                'render_template' => 'template-parts/blocks/highlight-code/highlight-code.php',
                'category' => 'zaxu-category',
                'icon' => 'editor-code',
                'keywords' => array ('code', 'highlight'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide'),
                    "mode" => false,
                ),
            )
        );
    // ********Highlight code end********

    // ********Accordion start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5e304a5c24982',
                'title' => __('Accordion', 'zaxu'),
                'fields' => array (
                    // Expand all items
                    array (
                        'key' => 'field_5e304a5c24982',
                        'label' => __('Expand All Items', 'zaxu'),
                        'name' => 'zaxu_accordion_expand_all_items',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    // Collapse other items
                    array (
                        'key' => 'field_5e304a5c24983',
                        'label' => __('Collapse Other Items', 'zaxu'),
                        'name' => 'zaxu_accordion_collapse_other_items',
                        'type' => 'true_false',
                        'instructions' => __('Collapse other items when one is expanded.', 'zaxu'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    // Content
                    array (
                        'key' => 'field_5e3046100ba9c',
                        'label' => __('Content', 'zaxu'),
                        'name' => 'zaxu_accordion_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5e3046910ba9d',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Item', 'zaxu'),
                        'sub_fields' => array (
                            // Expand this item
                            array (
                                'key' => 'field_5e304b955867f',
                                'label' => __('Expand This Item', 'zaxu'),
                                'name' => 'zaxu_accordion_expand_this_item',
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

                            // Title
                            array (
                                'key' => 'field_5e3046910ba9d',
                                'label' => __('Title', 'zaxu'),
                                'name' => 'zaxu_accordion_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __('Please enter title...', 'zaxu'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Content
                            array (
                                'key' => 'field_5e3046fa0ba9e',
                                'label' => __('Content', 'zaxu'),
                                'name' => 'zaxu_accordion_content',
                                'type' => 'wysiwyg',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 1,
                                'delay' => 0,
                            ),
                        ),
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-accordion',
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

        acf_register_block_type(
            array (
                'name' => 'zaxu-accordion',
                'title' => __('Accordion', 'zaxu'),
                'description' => __('Display accordion list.', 'zaxu'),
                'render_template' => 'template-parts/blocks/accordion/accordion.php',
                'category' => 'zaxu-category',
                'icon' => 'editor-insertmore',
                'keywords' => array ('accordion'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide'),
                    "mode" => false,
                ),
            )
        );
    // ********Accordion end********

    // ********Waterfall gallery start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5e32ad8b6e2e4',
                'title' => __('Waterfall Gallery', 'zaxu'),
                'fields' => array (
                    // Ratio
                    array (
                        'key' => 'field_5e32ad8b6e2e4',
                        'label' => __('Ratio', 'zaxu'),
                        'name' => 'zaxu_waterfall_gallery_ratio',
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
                            'responsive' => 'Responsive',
                            '1_1' => '1:1',
                            '4_3' => '4:3',
                            '16_9' => '16:9',
                        ),
                        'default_value' => 'responsive',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                    
                    // Lightbox
                    array (
                        'key' => 'field_5e32b191222b0',
                        'label' => __('Lightbox', 'zaxu'),
                        'name' => 'zaxu_waterfall_gallery_lightbox',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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

                    // Gallery
                    array (
                        'key' => 'field_5e32b0c30cc5d',
                        'label' => __('Gallery', 'zaxu'),
                        'name' => 'zaxu_waterfall_gallery_gallery',
                        'type' => 'gallery',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'min' => '',
                        'max' => '',
                        'insert' => 'append',
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
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-waterfall-gallery',
                        ),
                    ),
                ),
                'menu_order' => 2,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );
        
        acf_register_block_type(
            array (
                'name' => 'zaxu-waterfall-gallery',
                'title' => __('Waterfall Gallery', 'zaxu'),
                'description' => __('Display waterfall gallery.', 'zaxu'),
                'render_template' => 'template-parts/blocks/waterfall-gallery/waterfall-gallery.php',
                'category' => 'zaxu-category',
                'icon' => 'schedule',
                'keywords' => array ('waterfall', 'gallery'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Waterfall gallery end********

    // ********Slider gallery start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5e36c0cd1d56f',
                'title' => __('Slider Gallery', 'zaxu'),
                'fields' => array (
                    // Previous/Next buttons
                    array (
                        'key' => 'field_5e36c0cd1d56e',
                        'label' => __('Previous/Next Buttons', 'zaxu'),
                        'name' => 'zaxu_slider_gallery_previous_next_buttons',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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

                    // Navigation dots
                    array (
                        'key' => 'field_5e36c12c53c7a',
                        'label' => __('Navigation Dots', 'zaxu'),
                        'name' => 'zaxu_slider_gallery_navigation_dots',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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

                    // Autoplay
                    array (
                        'key' => 'field_5e36bffc60de0',
                        'label' => __('Autoplay (Second)', 'zaxu'),
                        'name' => 'zaxu_slider_gallery_autoplay',
                        'type' => 'range',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'min' => '0',
                        'max' => '10',
                        'step' => '1',
                        'prepend' => '',
                        'append' => '',
                    ),
                    
                    // Slider height
                    array (
                        'key' => 'field_5e36c194c6f2e',
                        'label' => __('Slider Height', 'zaxu'),
                        'name' => 'zaxu_slider_gallery_slider_height',
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
                            'responsive' => 'Responsive',
                            '1_1' => '1:1',
                            '4_3' => '4:3',
                            '16_9' => '16:9',
                        ),
                        'default_value' => 'responsive',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Content
                    array (
                        'key' => 'field_5e36c3160fe92',
                        'label' => __('Content', 'zaxu'),
                        'name' => 'zaxu_slider_gallery_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5e36c34a0fe93',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Slide', 'zaxu'),
                        'sub_fields' => array (
                            // Image
                            array (
                                'key' => 'field_5e36c34a0fe93',
                                'label' => __('Image', 'zaxu'),
                                'name' => 'zaxu_slider_gallery_item_image',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'array',
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

                            // Caption
                            array (
                                'key' => 'field_5e36c40fadbf1',
                                'label' => __('Caption', 'zaxu'),
                                'name' => 'zaxu_slider_gallery_item_caption',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __('Please enter slide caption...', 'zaxu'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            
                            // Link
                            array (
                                'key' => 'field_5e36c3dfadbf0',
                                'label' => __('Link', 'zaxu'),
                                'name' => 'zaxu_slider_gallery_item_link',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __('Please enter slide url...', 'zaxu'),
                            ),
                        ),
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-slider-gallery',
                        ),
                    ),
                ),
                'menu_order' => 3,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-slider-gallery',
                'title' => __('Slider Gallery', 'zaxu'),
                'description' => __('Display slider gallery.', 'zaxu'),
                'render_template' => 'template-parts/blocks/slider-gallery/slider-gallery.php',
                'category' => 'zaxu-category',
                'icon' => 'format-gallery',
                'keywords' => array ('slider', 'gallery'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Slider gallery end********

    // ********Alert tips start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5e9fd3c9dba22',
                'title' => __('Alert Tips', 'zaxu'),
                'fields' => array (
                    // Type
                    array (
                        'key' => 'field_5e9fd3c9dba22',
                        'label' => __('Type', 'zaxu'),
                        'name' => 'zaxu_alert_tips_type',
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
                            'information' => 'Information',
                            'success' => 'Success',
                            'warning' => 'Warning',
                            'error' => 'Error',
                        ),
                        'default_value' => 'information',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Icon
                    array (
                        'key' => 'field_5e9fd845d487f',
                        'label' => __('Icon', 'zaxu'),
                        'name' => 'zaxu_alert_tips_icon',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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

                    // Close button
                    array (
                        'key' => 'field_5e9fd84588b4f',
                        'label' => __('Close Button', 'zaxu'),
                        'name' => 'zaxu_alert_tips_close_button',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    // Dynamic color
                    array (
                        'key' => 'field_5e9fd593ed2ca',
                        'label' => __('Dynamic Color', 'zaxu'),
                        'name' => 'zaxu_alert_tips_dynamic_color',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    // Title
                    array (
                        'key' => 'field_5e9fd6cee601b',
                        'label' => __('Title', 'zaxu'),
                        'name' => 'zaxu_alert_tips_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => __('Please enter title...', 'zaxu'),
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    // Content
                    array (
                        'key' => 'field_5e9fd70f8c6bb',
                        'label' => __('Content', 'zaxu'),
                        'name' => 'zaxu_alert_tips_content',
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-alert-tips',
                        ),
                    ),
                ),
                'menu_order' => 4,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-alert-tips',
                'title' => __('Alert Tips', 'zaxu'),
                'description' => __('Display alert tips.', 'zaxu'),
                'render_template' => 'template-parts/blocks/alert-tips/alert-tips.php',
                'category' => 'zaxu-category',
                'icon' => 'warning',
                'keywords' => array ('alert', 'tips'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide'),
                    "mode" => false,
                ),
            )
        );
    // ********Alert tips end********

    // ********Friendly link start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5c8fb55d56f2f',
                'title' => __('Friendly Link', 'zaxu'),
                'fields' => array (
                    array (
                        'key' => 'field_5c8fb571191bf',
                        'label' => __('Member', 'zaxu'),
                        'name' => 'zaxu_friendly_link_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5c8fb76d9ddcf',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Member', 'zaxu'),
                        'sub_fields' => array (
                            // Avatar
                            array (
                                'key' => 'field_5c8fb622191c0',
                                'label' => __('Avatar', 'zaxu'),
                                'name' => 'zaxu_friendly_link_avatar',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'id',
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

                            // Name
                            array (
                                'key' => 'field_5c8fb76d9ddcf',
                                'label' => __('Name', 'zaxu'),
                                'name' => 'zaxu_friendly_link_name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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

                            // Description
                            array (
                                'key' => 'field_5c8fb7e0f9bab',
                                'label' => __('Description', 'zaxu'),
                                'name' => 'zaxu_friendly_link_description',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                                'key' => 'field_5c8fb809f9bac',
                                'label' => __('URL', 'zaxu'),
                                'name' => 'zaxu_friendly_link_url',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                            ),
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-friendly-link',
                        ),
                    ),
                ),
                'menu_order' => 5,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-friendly-link',
                'title' => __('Friendly Link', 'zaxu'),
                'description' => __('Display friendly link.', 'zaxu'),
                'render_template' => 'template-parts/blocks/friendly-link/friendly-link.php',
                'category' => 'zaxu-category',
                'icon' => 'groups',
                'keywords' => array ('link', 'friendly'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Friendly link end********

    // ********Overview info start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5cbea0a7bf1cc',
                'title' => __('Overview Info', 'zaxu'),
                'fields' => array (
                    // Overview info
                    array (
                        'key' => 'field_5cbea1b7326f5',
                        'label' => __('Overview Info', 'zaxu'),
                        'name' => 'zaxu_overview_info_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Overview Info', 'zaxu'),
                        'sub_fields' => array (
                            // Title
                            array (
                                'key' => 'field_5cbea28a326f6',
                                'label' => __('Title', 'zaxu'),
                                'name' => 'zaxu_overview_info_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Content
                            array (
                                'key' => 'field_5cbea2bc326f7',
                                'label' => __('Content', 'zaxu'),
                                'name' => 'zaxu_overview_info_content',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-overview-info',
                        ),
                    ),
                ),
                'menu_order' => 6,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-overview-info',
                'title' => __('Overview Info', 'zaxu'),
                'description' => __('Display overview info.', 'zaxu'),
                'render_template' => 'template-parts/blocks/overview-info/overview-info.php',
                'category' => 'zaxu-category',
                'icon' => 'info',
                'keywords' => array ('overview', 'info'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide'),
                    "mode" => false,
                ),
            )
        );
    // ********Overview info end********

    // ********Brand wall start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5d0f901bf24a2',
                'title' => __('Brand Wall', 'zaxu'),
                'fields' => array (
                    // Style
                    array (
                        'key' => 'field_5d0f92b0788e8',
                        'label' => __('Style', 'zaxu'),
                        'name' => 'zaxu_brand_wall_style',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => 50,
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'grid' => __('Grid Mode', 'zaxu'),
                            'river' => __('River Mode', 'zaxu'),
                        ),
                        'default_value' => 'river',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                    // Ratio
                    array (
                        'key' => 'field_5d0f92b0786738',
                        'label' => __('Ratio', 'zaxu'),
                        'name' => 'zaxu_brand_wall_ratio',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => 50,
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'square' => __('Square', 'zaxu'),
                            'rectangle' => __('Rectangle', 'zaxu'),
                        ),
                        'default_value' => 'square',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Brands
                    array (
                        'key' => 'field_5d0f905435d58',
                        'label' => __('Brands', 'zaxu'),
                        'name' => 'zaxu_brand_wall_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5d0f91dd35d5a',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Brand', 'zaxu'),
                        'sub_fields' => array (
                            // Brand logo
                            array (
                                'key' => 'field_5d0f911d35d59',
                                'label' => __('Brand Logo', 'zaxu'),
                                'name' => 'zaxu_brand_wall_logo',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'id',
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

                            // Name
                            array (
                                'key' => 'field_5d0f91dd35d5a',
                                'label' => __('Brand Name', 'zaxu'),
                                'name' => 'zaxu_brand_wall_name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-brand-wall',
                        ),
                    ),
                ),
                'menu_order' => 7,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-brand-wall',
                'title' => __('Brand Wall', 'zaxu'),
                'description' => __('Display brand wall.', 'zaxu'),
                'render_template' => 'template-parts/blocks/brand-wall/brand-wall.php',
                'category' => 'zaxu-category',
                'icon' => 'image-filter',
                'keywords' => array ('brand', 'wall'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Brand wall end********

    // ********Post start********
        if ( class_exists('WooCommerce') ) {
            $choices_arr = array (
                'post' => __('Post', 'zaxu'),
                'page' => __('Page', 'zaxu'),
                'portfolio' => __('Portfolio', 'zaxu'),
                'product' => __('Product', 'zaxu'),
                'specified' => __('Specified', 'zaxu'),
            );
        } else {
            $choices_arr = array (
                'post' => __('Post', 'zaxu'),
                'page' => __('Page', 'zaxu'),
                'portfolio' => __('Portfolio', 'zaxu'),
                'specified' => __('Specified', 'zaxu'),
            );
        }

        acf_add_local_field_group(
            array (
                'key' => 'group_5d0f901bf24a3',
                'title' => __('Post', 'zaxu'),
                'fields' => array (
                    // Block title
                    array (
                        'key' => 'field_5d0f92b0788e9',
                        'label' => __('Block Title', 'zaxu'),
                        'name' => 'zaxu_post_block_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => __('Please enter block title...', 'zaxu'),
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    // Block link
                    array (
                        'key' => 'field_5d0f92b0788e10',
                        'label' => __('Block Link', 'zaxu'),
                        'name' => 'zaxu_post_block_link',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),

                    // Block link title
                    array (
                        'key' => 'field_5d0f92b0788e11',
                        'label' => __('Block Link Title', 'zaxu'),
                        'name' => 'zaxu_post_block_link_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => __('Please enter block link title...', 'zaxu'),
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    // Source
                    array (
                        'key' => 'field_5d0f92b0788e13',
                        'label' => __('Source', 'zaxu'),
                        'name' => 'zaxu_post_source',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => $choices_arr,
                        'default_value' => 'post',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Quantity
                    array (
                        'key' => 'field_5d0f92b078814',
                        'label' => __('Quantity', 'zaxu'),
                        'name' => 'zaxu_post_quantity',
                        'type' => 'number',
                        'instructions' => __('Display all post, please enter "-1".', 'zaxu'),
                        'required' => 0,
                        'conditional_logic' => array (
                            array (
                                array (
                                    'field' => 'field_5d0f92b0788e13',
                                    'operator' => '!=',
                                    'value' => 'specified',
                                ),
                            ),
                        ),
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '6',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '-1',
                        'max' => '',
                        'step' => '1',
                    ),

                    // Specified content
                    array (
                        'key' => 'field_5d0f92b078815',
                        'label' => __('Specified Content', 'zaxu'),
                        'name' => 'zaxu_post_specified_content',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array (
                            array (
                                array (
                                    'field' => 'field_5d0f92b0788e13',
                                    'operator' => '==',
                                    'value' => 'specified',
                                ),
                            ),
                        ),
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array (
                            'post',
                            'page',
                            'portfolio'
                        ),
                        'taxonomy' => array (
                        ),
                        'allow_null' => 0,
                        'multiple' => 1,
                        'return_format' => 'object',
                        'ui' => 1,
                    ),

                    // Style
                    array (
                        'key' => 'field_5d0f92b078816',
                        'label' => __('Style', 'zaxu'),
                        'name' => 'zaxu_post_style',
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
                            'list' => __('List', 'zaxu'),
                            'grid' => __('Grid', 'zaxu'),
                            'carousel' => __('Carousel', 'zaxu'),
                        ),
                        'default_value' => 'grid',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Ratio for grid
                    array (
                        'key' => 'field_5d0f92b078817',
                        'label' => __('Cover Ratio', 'zaxu'),
                        'name' => 'zaxu_post_grid_ratio',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array (
                            array (
                                array (
                                    'field' => 'field_5d0f92b078816',
                                    'operator' => '==',
                                    'value' => 'grid',
                                ),
                            ),
                        ),
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'responsive' => __('Responsive', 'zaxu'),
                            '1_1' => __('1:1', 'zaxu'),
                            '4_3' => __('4:3', 'zaxu'),
                            '16_9' => __('16:9', 'zaxu'),
                        ),
                        'default_value' => 'responsive',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Ratio for carousel
                    array (
                        'key' => 'field_5d0f92b078818',
                        'label' => __('Cover Ratio', 'zaxu'),
                        'name' => 'zaxu_post_carousel_ratio',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array (
                            array (
                                array (
                                    'field' => 'field_5d0f92b078816',
                                    'operator' => '==',
                                    'value' => 'carousel',
                                ),
                            ),
                        ),
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            '1_1' => __('1:1', 'zaxu'),
                            '4_3' => __('4:3', 'zaxu'),
                            '16_9' => __('16:9', 'zaxu'),
                        ),
                        'default_value' => '4_3',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Summary display
                    array (
                        'key' => 'field_5d0f92b078819',
                        'label' => __('Summary Display', 'zaxu'),
                        'name' => 'zaxu_post_block_summary_display',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array (
                            array (
                                array (
                                    'field' => 'field_5d0f92b078816',
                                    'operator' => '!=',
                                    'value' => 'list',
                                ),
                            ),
                        ),
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'disabled' => __('Disabled', 'zaxu'),
                            'overlay' => __('Overlay', 'zaxu'),
                            'separate' => __('Separate', 'zaxu'),
                        ),
                        'default_value' => 'separate',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Attribute information
                    array (
                        'key' => 'field_5d0f92b0788e12',
                        'label' => __('Attribute Information', 'zaxu'),
                        'name' => 'zaxu_post_attr_info',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-post',
                        ),
                    ),
                ),
                'menu_order' => 8,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-post',
                'title' => __('Post', 'zaxu'),
                'description' => __('Display post.', 'zaxu'),
                'render_template' => 'template-parts/blocks/post/post.php',
                'category' => 'zaxu-category',
                'icon' => 'admin-post',
                'keywords' => array ('post', 'article'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'edit',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Post end********

    // ********Price table start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5cbea0a73948',
                'title' => __('Price Table', 'zaxu'),
                'fields' => array (
                    // Price table
                    array (
                        'key' => 'field_5d0f90543095',
                        'label' => __('Price Table', 'zaxu'),
                        'name' => 'zaxu_price_table_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5d0f91dd39875',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Price Table', 'zaxu'),
                        'sub_fields' => array (
                            // Highlight
                            array (
                                'key' => 'field_5d0f91dd39873',
                                'label' => __('Highlight', 'zaxu'),
                                'name' => 'zaxu_price_table_highlight',
                                'type' => 'true_false',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '50',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                            ),

                            // Highlight title
                            array (
                                'key' => 'field_5d0f91dd39874',
                                'label' => __('Highlight title', 'zaxu'),
                                'name' => 'zaxu_price_table_highlight_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => 50,
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Product name
                            array (
                                'key' => 'field_5d0f91dd39875',
                                'label' => __('Product name', 'zaxu'),
                                'name' => 'zaxu_price_table_product_name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Product description
                            array (
                                'key' => 'field_5d0f91dd39876',
                                'label' => __('Product description', 'zaxu'),
                                'name' => 'zaxu_price_table_product_description',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Currency symbol
                            array (
                                'key' => 'field_5d0f91dd39877',
                                'label' => __('Currency symbol', 'zaxu'),
                                'name' => 'zaxu_price_table_currency_symbol',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '33',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => __('¥', 'zaxu'),
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Product price
                            array (
                                'key' => 'field_5d0f91dd39878',
                                'label' => __('Product price', 'zaxu'),
                                'name' => 'zaxu_price_table_product_price',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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

                            // Period
                            array (
                                'key' => 'field_5d0f91dd39879',
                                'label' => __('Period', 'zaxu'),
                                'name' => 'zaxu_price_table_period',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '33',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => __('/Monthly', 'zaxu'),
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Link
                            array (
                                'key' => 'field_5d0f91dd39880',
                                'label' => __('Link', 'zaxu'),
                                'name' => 'zaxu_price_table_link',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                                'key' => 'field_5d0f91dd39881',
                                'label' => __('Link title', 'zaxu'),
                                'name' => 'zaxu_price_table_link_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                                'key' => 'field_5d0f91dd39882',
                                'label' => __('Open in new tab', 'zaxu'),
                                'name' => 'zaxu_price_table_new_tab',
                                'type' => 'true_false',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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

                            // Feature
                            array (
                                'key' => 'field_5d0f90543096',
                                'label' => __('Feature', 'zaxu'),
                                'name' => 'zaxu_price_table_feature_repeater',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'collapsed' => 'field_5d0f91dd39883',
                                'min' => 0,
                                'max' => 0,
                                'layout' => 'block',
                                'button_label' => __('Add Feature', 'zaxu'),
                                'sub_fields' => array (
                                    // Feature title
                                    array (
                                        'key' => 'field_5d0f91dd39883',
                                        'label' => __('Feature title', 'zaxu'),
                                        'name' => 'zaxu_price_table_feature_title',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array (
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                    ),

                                    // Feature description
                                    array (
                                        'key' => 'field_5d0f91dd39884',
                                        'label' => __('Feature description', 'zaxu'),
                                        'name' => 'zaxu_price_table_feature_description',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array (
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-price-table',
                        ),
                    ),
                ),
                'menu_order' => 9,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-price-table',
                'title' => __('Price Table', 'zaxu'),
                'description' => __('Display price table.', 'zaxu'),
                'render_template' => 'template-parts/blocks/price-table/price-table.php',
                'category' => 'zaxu-category',
                'icon' => 'format-aside',
                'keywords' => array ('price', 'table'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Price table end********

    // ********Image comparison start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5cbea0a7bcb48',
                'title' => __('Image Comparison', 'zaxu'),
                'fields' => array (
                    // Orientation
                    array (
                        'key' => 'field_5cbea1b738709',
                        'label' => __('Orientation', 'zaxu'),
                        'name' => 'zaxu_image_comparison_orientation',
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
                            'horizontal' => __('Horizontal', 'zaxu'),
                            'vertical' => __('Vertical', 'zaxu'),
                        ),
                        'default_value' => 'horizontal',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),

                    // Move slider on hover
                    array (
                        'key' => 'field_5cbea1b738710',
                        'label' => __('Move Slider On Hover', 'zaxu'),
                        'name' => 'zaxu_image_comparison_move_slider_on_hover',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    // Before image
                    array (
                        'key' => 'field_5cbea1b738711',
                        'label' => __('Before Image', 'zaxu'),
                        'name' => 'zaxu_image_comparison_before_image',
                        'type' => 'image',
                        'instructions' => __('Before & after image recommended use the same height.', 'zaxu'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
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

                    // Before label
                    array (
                        'key' => 'field_5cbea1b738712',
                        'label' => __('Before Label', 'zaxu'),
                        'name' => 'zaxu_image_comparison_before_label',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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

                    // After image
                    array (
                        'key' => 'field_5cbea1b738713',
                        'label' => __('After Image', 'zaxu'),
                        'name' => 'zaxu_image_comparison_after_image',
                        'type' => 'image',
                        'instructions' => __('Before & after image recommended use the same height.', 'zaxu'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
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

                    // After label
                    array (
                        'key' => 'field_5cbea1b738714',
                        'label' => __('After Label', 'zaxu'),
                        'name' => 'zaxu_image_comparison_after_label',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
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
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-image-comparison',
                        ),
                    ),
                ),
                'menu_order' => 10,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-image-comparison',
                'title' => __('Image Comparison', 'zaxu'),
                'description' => __('Display image comparison.', 'zaxu'),
                'render_template' => 'template-parts/blocks/image-comparison/image-comparison.php',
                'category' => 'zaxu-category',
                'icon' => 'image-flip-horizontal',
                'keywords' => array ('image ', 'comparison'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Image comparison end********

    // ********Timeline start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5cbea0a7b98bc',
                'title' => __('Timeline', 'zaxu'),
                'fields' => array (
                    // Content
                    array (
                        'key' => 'field_5e304a5c20983',
                        'label' => __('Content', 'zaxu'),
                        'name' => 'zaxu_timeline_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5e304a5c20986',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Item', 'zaxu'),
                        'sub_fields' => array (
                            // Key node
                            array (
                                'key' => 'field_5e304a5c20985',
                                'label' => __('Key Node', 'zaxu'),
                                'name' => 'zaxu_timeline_key_node',
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

                            // Title
                            array (
                                'key' => 'field_5e304a5c20986',
                                'label' => __('Title', 'zaxu'),
                                'name' => 'zaxu_timeline_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __('Please enter title...', 'zaxu'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Description
                            array (
                                'key' => 'field_5e304a5c20987',
                                'label' => __('Description', 'zaxu'),
                                'name' => 'zaxu_timeline_description',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __('Please enter description...', 'zaxu'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Content
                            array (
                                'key' => 'field_5e304a5c20988',
                                'label' => __('Content', 'zaxu'),
                                'name' => 'zaxu_timeline_content',
                                'type' => 'wysiwyg',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 1,
                                'delay' => 0,
                            ),
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-timeline',
                        ),
                    ),
                ),
                'menu_order' => 11,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-timeline',
                'title' => __('Timeline', 'zaxu'),
                'description' => __('Display timeline.', 'zaxu'),
                'render_template' => 'template-parts/blocks/timeline/timeline.php',
                'category' => 'zaxu-category',
                'icon' => 'excerpt-view',
                'keywords' => array ('timeline'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide'),
                    "mode" => false,
                ),
            )
        );
    // ********Timeline end********

    // ********Feature start********
        acf_add_local_field_group(
            array (
                'key' => 'group_5cbea0a7b9835',
                'title' => __('Feature', 'zaxu'),
                'fields' => array (
                    // Item width
                    array (
                        'key' => 'field_5e36bffc62839',
                        'label' => __('Item width', 'zaxu'),
                        'name' => 'zaxu_feature_item_width',
                        'type' => 'range',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '400',
                        'min' => '160',
                        'max' => '800',
                        'step' => '1',
                        'prepend' => '',
                        'append' => '',
                    ),

                    // Content
                    array (
                        'key' => 'field_5e36bffc62840',
                        'label' => __('Content', 'zaxu'),
                        'name' => 'zaxu_feature_repeater',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => '',
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => 'field_5e36bffc62844',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => __('Add Item', 'zaxu'),
                        'sub_fields' => array (
                            // Style
                            array (
                                'key' => 'field_5e36bffc62841',
                                'name' => 'zaxu_feature_style',
                                'label' => __('Style', 'zaxu'),
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
                                'ui_on_text' => __('Cover', 'zaxu'),
                                'ui_off_text' => __('Icon', 'zaxu'),
                            ),

                            // Image or video
                            array (
                                'key' => 'field_5e36bffc62842',
                                'label' => __('Image/Video', 'zaxu'),
                                'name' => 'zaxu_feature_image_or_video',
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
                                'key' => 'field_5e36bffc62843',
                                'label' => __('Video cover', 'zaxu'),
                                'name' => 'zaxu_feature_video_cover',
                                'type' => 'image',
                                'instructions' => __('If you selected a video file, please set a video cover.', 'zaxu'),
                                'required' => 0,
                                'conditional_logic' => array (
                                    array (
                                        array (
                                            'field' => 'field_5e36bffc62842',
                                            'operator' => '!=empty',
                                        ),
                                    ),
                                ),
                                'wrapper' => array (
                                    'width' => '',
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

                            // Title
                            array (
                                'key' => 'field_5e36bffc62844',
                                'label' => __('Title', 'zaxu'),
                                'name' => 'zaxu_feature_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),

                            // Description
                            array (
                                'key' => 'field_5e36bffc62845',
                                'label' => __('Description', 'zaxu'),
                                'name' => 'zaxu_feature_description',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'maxlength' => '',
                                'rows' => '',
                                'new_lines' => '',
                            ),

                            // Link
                            array (
                                'key' => 'field_5e36bffc62846',
                                'label' => __('Link', 'zaxu'),
                                'name' => 'zaxu_feature_link',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                                'key' => 'field_5e36bffc62847',
                                'label' => __('Link title', 'zaxu'),
                                'name' => 'zaxu_feature_link_title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                                'key' => 'field_5e36bffc62848',
                                'name' => 'zaxu_feature_link_new_tab',
                                'label' => __('Open in new tab', 'zaxu'),
                                'type' => 'true_false',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
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
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/zaxu-feature',
                        ),
                    ),
                ),
                'menu_order' => 12,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            )
        );

        acf_register_block_type(
            array (
                'name' => 'zaxu-feature',
                'title' => __('Feature', 'zaxu'),
                'description' => __('Display feature.', 'zaxu'),
                'render_template' => 'template-parts/blocks/feature/feature.php',
                'category' => 'zaxu-category',
                'icon' => 'lightbulb',
                'keywords' => array ('feature'),
                // 'post_types' => array ('post', 'page'),
                'mode' => 'auto',
                'supports' => array (
                    'align' => array ('wide', 'full'),
                    "mode" => false,
                ),
            )
        );
    // ********Feature end********
}

// Check if function exists and hook into setup.
if ( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_acf_block_types');
}
?>