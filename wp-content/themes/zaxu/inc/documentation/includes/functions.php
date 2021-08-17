<?php
/*
 * @Description: Documentation functions
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

function zaxudocs_get_template_part($slug, $name = '') {
    $zaxudocs = zaxu_docs::init();
    $templates = array();
    $name = (string) $name;
    // lookup at theme/slug-name.php or documentation/slug-name.php
    if ('' !== $name) {
        $templates[] = "{$slug}-{$name}.php";
        $templates[] = $zaxudocs->theme_dir_path . "{$slug}-{$name}.php";
    }
    $template = locate_template($templates);

    // fallback to plugin default template
    if ( !$template && $name && file_exists(get_template_directory() . '/inc/documentation/templates/' . "{$slug}-{$name}.php") ) {
        $template = get_template_directory() . '/inc/documentation/templates/' . "{$slug}-{$name}.php";
    }
    // if not yet found, lookup in slug.php only
    if (!$template) {
        $templates = array(
            "{$slug}.php",
            $zaxudocs->theme_dir_path . "{$slug}.php"
        );
        $template = locate_template($templates);
    }
    if ($template) {
        load_template($template, false);
    }
}

/**
 * Include a template by precedance
 *
 * Looks at the theme directory first
 *
 * @param  string  $template_name
 * @param  array   $args
 *
 * @return void
 */
function zaxudocs_get_template( $template_name, $args = array() ) {
    $zaxudocs = zaxu_docs::init();
    if ( $args && is_array($args) ) {
        extract($args);
    }
    $template = locate_template( array(
        $zaxudocs->theme_dir_path . $template_name,
        $template_name
    ) );
    if (!$template) {
        $template = get_template_directory() . '/inc/documentation/templates/' . $template_name;
    }
    if ( file_exists($template) ) {
        include $template;
    }
}

if ( !function_exists('zaxudocs_breadcrumbs') ) :

/**
 * Docs breadcrumb
 *
 * @return void
 */
function zaxudocs_breadcrumbs() {
    global $post;
    $html = '';
    $args = apply_filters( 'zaxudocs_breadcrumbs', array(
        'delimiter' => '<li class="delimiter"><i class="zaxudocs-icon zaxudocs-icon-angle-right"></i></li>',
        'home' => __( 'Home', 'zaxu' ),
        'before' => '<li><span class="current">',
        'after' => '</span></li>'
    ) );
    $breadcrumb_position = 1;
    $html .= '<ol class="zaxudocs-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $html .= '<li><i class="zaxudocs-icon zaxudocs-icon-home"></i></li>';
    $html .= zaxudocs_get_breadcrumb_item( $args['home'], home_url( '/' ), $breadcrumb_position );
    $html .= $args['delimiter'];
    $documentation_pages = get_pages(
        array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'templates/template-documentation.php',
        )
    );
    $docs_home = null;
    foreach($documentation_pages as $documentation_page) {
        $docs_home = get_page_link($documentation_page->ID);
    };
    if ($docs_home) {
        $breadcrumb_position++;
        $html .= zaxudocs_get_breadcrumb_item( __('Docs', 'zaxu'), $docs_home, $breadcrumb_position );
        $html .= $args['delimiter'];
    }
    if ($post->post_type == 'docs' && $post->post_parent) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $breadcrumb_position++;
            $page  = get_post($parent_id);
            $breadcrumbs[] = zaxudocs_get_breadcrumb_item(get_the_title($page->ID), get_permalink($page->ID), $breadcrumb_position);
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
            $html .= $breadcrumbs[$i];
            $html .= ' ' . $args['delimiter'] . ' ';
        }
    }
    $html .= ' ' . $args['before'] . get_the_title() . $args['after'];
    $html .= '</ol>';
    echo apply_filters('zaxudocs_breadcrumbs_html', $html, $args);
}

endif;

if ( ! function_exists('zaxudocs_get_breadcrumb_item') ) :
/**
 * Schema.org breadcrumb item wrapper for a link
 *
 * @param  string  $label
 * @param  string  $permalink
 * @param  integer $position
 *
 * @return string
 */
function zaxudocs_get_breadcrumb_item($label, $permalink, $position = 1) {
    return '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="' . esc_attr($permalink) . '" class="ajax-link">
            <span itemprop="name">' . esc_html($label) . '</span>
        </a>
        <meta itemprop="position" content="' . $position . '" />
    </li>';
}

endif;

/**
 * Next, previous post navigation for a single doc
 *
 * @return void
 */
function zaxudocs_doc_nav() {
    global $post, $wpdb;
    $next_query = "SELECT ID FROM $wpdb->posts
        WHERE post_parent = $post->post_parent and post_type = 'docs' and post_status = 'publish' and menu_order > $post->menu_order
        ORDER BY menu_order ASC
        LIMIT 0, 1";
    $prev_query = "SELECT ID FROM $wpdb->posts
        WHERE post_parent = $post->post_parent and post_type = 'docs' and post_status = 'publish' and menu_order < $post->menu_order
        ORDER BY menu_order DESC
        LIMIT 0, 1";
    $next_post_id = (int) $wpdb->get_var($next_query);
    $prev_post_id = (int) $wpdb->get_var($prev_query);
    if ($next_post_id || $prev_post_id) {
        echo '<nav class="zaxudocs-doc-nav">';
        echo '<h3 class="assistive-text screen-reader-text">'. __('Documentation Navigation', 'zaxu') . '</h3>';
        if ($prev_post_id) {
            echo '<span class="nav-prev"><a href="' . get_permalink($prev_post_id) . '" class="ajax-link">' . apply_filters('zaxudocs_translate_text', get_post($prev_post_id)->post_title) . '</a></span>';
        }
        if ($next_post_id) {
            echo '<span class="nav-next"><a href="' . get_permalink($next_post_id) . '" class="ajax-link">' . apply_filters('zaxudocs_translate_text', get_post($next_post_id)->post_title) . '</a></span>';
        }
        echo '</nav>';
    }
}

if ( !function_exists('zaxudocs_get_posts_children') ) :
/**
 * Recursively fetch child posts
 *
 * @param  integer  $parent_id
 * @param  string  $post_type
 *
 * @return array
 */
function zaxudocs_get_posts_children($parent_id, $post_type = 'page') {
    $children = array();
    // grab the posts children
    $posts = get_posts( array(
        'numberposts' => -1,
        'post_status' => 'publish',
        'post_type' => $post_type,
        'post_parent' => $parent_id
    ) );
    // now grab the grand children
    foreach ($posts as $child) {
        // recursion!! hurrah
        $gchildren = zaxudocs_get_posts_children($child->ID, $post_type);
        // merge the grand children into the children array
        if ( !empty($gchildren) ) {
            $children = array_merge($children, $gchildren);
        }
    }
    // merge in the direct descendants we found earlier
    $children = array_merge($children,$posts);
    return $children;
}
endif;

/**
 * Get a clients IP address
 *
 * @return string
 */
function zaxudocs_get_ip_address() {
    $ipaddress = '';
    if ( isset($_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if ( isset($_SERVER['HTTP_X_FORWARDED'] ) ) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if ( isset($_SERVER['HTTP_FORWARDED_FOR'] ) ) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if ( isset($_SERVER['HTTP_FORWARDED'] ) ) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

/**
 * Send email feedback on a document
 *
 * @param  integer $doc_id
 * @param  string $author
 * @param  string $email
 * @param  string $subject
 * @param  string $message
 *
 * @since 1.2
 *
 * @return void
 */
function zaxudocs_doc_feedback_email($doc_id, $author, $email, $subject, $message) {
    $wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( $_SERVER['SERVER_NAME'] ) );
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $document = get_post($doc_id);
    $email_to = get_theme_mod( 'zaxu_doc_email_feedback', get_option('admin_email') );
    $subject = sprintf(__('[%1$s] New Doc Feedback: "%2$s"', 'zaxu'), $blogname, $subject);
    $email_body = sprintf( __('New feedback on your doc "%s"', 'zaxu'), apply_filters('zaxudocs_translate_text', $document->post_title) ) . "\r\n";
    $email_body .= sprintf( __('Author: %1$s (IP: %2$s)', 'zaxu'), $author, zaxudocs_get_ip_address() ) . "\r\n";
    $email_body .= sprintf(__('Email: %s', 'zaxu'), $email) . "\r\n";
    $email_body .= sprintf(__('Feedback: %s', 'zaxu'), "\r\n" . $message) . "\r\n\r\n";
    $email_body .= sprintf(__('Doc Permalink: %s', 'zaxu'), get_permalink($document) ) . "\r\n";
    $email_body .= sprintf( __('Edit Doc: %s', 'zaxu'), admin_url('post.php?action=edit&post=' . $doc_id) ) . "\r\n";
    $from = "From: \"$author\" <$wp_email>";
    $reply_to = "Reply-To: \"$email\" <$email>";
    $message_headers = "$from\n"
            . "Content-Type: text/plain; charset =\"" . get_option('blog_charset') . "\"\n";
    $message_headers .= $reply_to . "\n";
    $email_to = apply_filters('zaxudocs_email_feedback_to', $email_to, $doc_id, $document);
    $subject = apply_filters( 'zaxudocs_email_feedback_subject', $subject, $doc_id, $document, $_POST);
    $email_body = apply_filters('zaxudocs_email_feedback_body', $email_body, $doc_id, $document, $_POST);
    $message_headers = apply_filters('zaxudocs_email_feedback_headers', $message_headers, $doc_id, $document, $_POST);
    @wp_mail($email_to, wp_specialchars_decode($subject), $email_body, $message_headers);
}

/**
 * Get the publishing capability for zaxuDocs admin
 *
 * @since 1.3
 *
 * @return string
 */
function zaxudocs_get_publish_cap() {
    return apply_filters('zaxudocs_publish_cap', 'publish_posts');
}

add_action('zaxudocs_before_main_content', 'zaxudocs_template_wrapper_start', 10);
add_action('zaxudocs_after_main_content', 'zaxudocs_template_wrapper_end', 10);
?>