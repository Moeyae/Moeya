<?php
/*
 * @Description: Documentation functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

/**
 * zaxu_docs class
 *
 * @class zaxu_docs The class that holds the entire zaxu_docs plugin
 */
class zaxu_docs {
    public $theme_dir_path;
    private $post_type = 'docs';

    /**
     * Initializes the zaxu_docs() class
     *
     * Checks for an existing zaxu_docs() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;
        if (!$instance) {
            $instance = new zaxu_docs();
            add_action( 'after_setup_theme', array($instance, 'plugin_init') );
            register_activation_hook( __FILE__, array($instance, 'activate') );
        }
        return $instance;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    function plugin_init() {
        $this->theme_dir_path = apply_filters('zaxudocs_theme_dir_path', 'documentation/');
        $this->file_includes();
        $this->init_classes();
        // custom post types and taxonomies
        add_action( 'init', array($this, 'register_post_type') );
        // override the theme template
        add_filter('template_include', array($this, 'template_loader'), 20);
    }

    /**
     * Load the required files
     *
     * @return void
     */
    function file_includes() {
        include_once dirname( __FILE__ ) . '/includes/functions.php';
        include_once dirname( __FILE__ ) . '/includes/class-walker-docs.php';
        if ( is_admin() ) {
            include_once dirname( __FILE__ ) . '/includes/admin/class-admin.php';
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            include_once dirname( __FILE__ ) . '/includes/class-ajax.php';
        }
    }

    /**
     * Initialize the classes
     *
     * @since 1.4
     *
     * @return void
     */
    public function init_classes() {
        if ( is_admin() ) {
            new zaxu_docs_Admin();
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            new zaxu_docs_ajax();
        }
    }

    /**
     * Register the post type
     *
     * @return void
     */
    function register_post_type() {
        // Set documentation rewrite slug start
            // Get documentation homepage id start
                $page_id = null;
                $portfolio_pages = get_pages(
                    array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'templates/template-documentation.php',
                    )
                );
                foreach($portfolio_pages as $portfolio_page) {
                    $page_id = $portfolio_page->ID;
                }
            // Get documentation homepage id end
            if ( preg_match( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', get_permalink($page_id) ) ) {
                $has_special_str = true;
            } else {
                $has_special_str = false;
            }
            if ($page_id && $has_special_str == false) {
                $slug = basename( get_permalink($page_id) );
                $slug_tag = $slug . '-tag';
            } else {
                $slug = 'docs';
                $slug_tag = 'docs-tag';
            }
            flush_rewrite_rules();
        // Set documentation rewrite slug end
        
        $labels = array(
            'name' => __('Documentation', 'zaxu'),
            'singular_name' => __('Documentation', 'zaxu'),
            'all_items' => __('All Documentations', 'zaxu'),
        );
        $args = array(
            'labels' => $labels,
            'supports' => array(
                'title',
                'editor',
                'thumbnail', 
                'revisions',
                'page-attributes',
                'comments'
            ),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-document',
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => $slug,
                'with_front' => true,
                'pages' => true,
                'feeds' => true,
            ),
            'capability_type' => 'post',
            'taxonomies' => array('doc_tag')
        );
        register_post_type( $this->post_type, apply_filters('zaxudocs_post_type', $args) );

        $args = array(
            'labels' => false,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => $slug_tag,
                'with_front' => true,
                'hierarchical' => false,
            )
        );
        register_taxonomy('doc_tag', array('docs'), $args);
    }

    /**
     * If the theme doesn't have any single doc handler, load that from
     * the plugin
     *
     * @param  string  $template
     *
     * @return string
     */
    function template_loader($template) {
        $find = array($this->post_type . '.php');
        $file = '';
        if (is_single() && get_post_type() == $this->post_type) {
            $file = 'single-' . $this->post_type . '.php';
            $find[] = $file;
            $find[] = $this->theme_dir_path. $file;
        }
        if ($file) {
            $template = locate_template($find);
            if (!$template) {
                $template = get_template_directory() . '/inc/documentation/templates/' . $file;
            }
        }
        return $template;
    }
} // zaxu_docs

/**
 * Initialize the plugin
 *
 * @return \zaxu_docs
 */
function zaxudocs() {
    return zaxu_docs::init();
}

// kick it off
zaxudocs();
?>