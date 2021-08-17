<?php
/*
 * @Description: Ajax Class
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

class zaxu_docs_ajax {
    /**
     * Bind actions
     */
    function __construct() {
        add_action( 'wp_ajax_zaxudocs_create_doc', array($this, 'create_doc') );
        add_action( 'wp_ajax_zaxudocs_remove_doc', array($this, 'remove_doc') );
        add_action( 'wp_ajax_zaxudocs_admin_get_docs', array($this, 'get_docs') );
        add_action( 'wp_ajax_zaxudocs_sortable_docs', array($this, 'sort_docs') );
        // feedback
        add_action( 'wp_ajax_zaxudocs_ajax_feedback', array($this, 'handle_feedback') );
        add_action( 'wp_ajax_nopriv_zaxudocs_ajax_feedback', array($this, 'handle_feedback') );
        // contact
        add_action( 'wp_ajax_zaxudocs_contact_feedback', array($this, 'handle_contact') );
        add_action( 'wp_ajax_nopriv_zaxudocs_contact_feedback', array($this, 'handle_contact') );
    }

    /**
     * Create a new doc
     *
     * @return void
     */
    public function create_doc() {
        check_ajax_referer('zaxudocs-admin-nonce');
        $title  = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'draft';
        $parent = isset( $_POST['parent'] ) ? absint( $_POST['parent'] ) : 0;
        $order  = isset( $_POST['order'] ) ? absint( $_POST['order'] ) : 0;
        $status = 'publish';
        $post_type_object = get_post_type_object('docs');
        if ( ! current_user_can($post_type_object->cap->publish_posts) ) {
            $status = 'pending';
        }

        $post_id = wp_insert_post(
            array(
                'post_title' => $title,
                'post_type' => 'docs',
                'post_status' => $status,
                'post_parent' => $parent,
                'post_author' => get_current_user_id(),
                'menu_order' => $order,
            )
        );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error();
        }
        wp_send_json_success( array(
            'post' => array(
                'id' => $post_id,
                'title' => stripslashes($title),
                'status' => $status,
                'url' => get_permalink($post_id),
                'caps' => array(
                    'edit' => current_user_can($post_type_object->cap->edit_post, $post_id),
                    'delete' => current_user_can($post_type_object->cap->delete_post, $post_id)
                )
            ),
            'child' => array()
        ) );
    }

    /**
     * Delete a doc
     *
     * @return void
     */
    public function remove_doc() {
        check_ajax_referer('zaxudocs-admin-nonce');
        $force_delete = false;
        $post_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        if ( !current_user_can('delete_post', $post_id) ) {
            wp_send_json_error( __('You are not allowed to delete this item.', 'zaxu') );
        }
        if ($post_id) {
            // delete childrens first if found
            $this->remove_child_docs($post_id, $force_delete);
            // delete main doc
            wp_delete_post($post_id, $force_delete);
        }
        wp_send_json_success();
    }

    /**
     * Remove child docs
     *
     * @param  integer  $parent_id
     *
     * @return void
     */
    public function remove_child_docs($parent_id = 0, $force_delete) {
        $childrens = get_children( array('post_parent' => $parent_id) );
        if ($childrens) {
            foreach ($childrens as $child_post) {
                // recursively delete
                $this->remove_child_docs($child_post->ID, $force_delete);
                wp_delete_post($child_post->ID, $force_delete);
            }
        }
    }

    /**
     * Get all docs
     *
     * @return void
     */
    public function get_docs() {
        check_ajax_referer('zaxudocs-admin-nonce');
        $docs = get_pages(
            array(
                'post_type' => 'docs',
                'post_status' => array(
                    'publish',
                    'draft',
                    'pending'
                ),
                'posts_per_page' => '-1',
                'orderby' => 'menu_order',
                'order' => 'ASC',
            )
        );
        $arranged = $this->build_tree($docs);
        usort( $arranged, array($this, 'sort_callback') );
        wp_send_json_success($arranged);
    }

    /**
     * Store feedback for an article
     *
     * @return void
     */
    function handle_feedback() {
        check_ajax_referer('zaxudocs-ajax');
        $previous = isset( $_COOKIE['zaxudocs_response'] ) ? explode( ',', $_COOKIE['zaxudocs_response'] ) : array();
        $post_id = intval( $_POST['post_id'] );
        $type = in_array( $_POST['type'], array('positive', 'negative') ) ? $_POST['type'] : false;
        // check previous response
        if ( in_array($post_id, $previous) ) {
            $message = __('Sorry, you\'ve already recorded your feedback.', 'zaxu');
            wp_send_json_error($message);
        }
        // seems new
        if ($type) {
            $count = (int) get_post_meta($post_id, $type, true);
            update_post_meta($post_id, $type, $count + 1);
            array_push($previous, $post_id);
            $cookie_val = implode(',',  $previous);
            $val = setcookie('zaxudocs_response', $cookie_val, time() + WEEK_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }
        $message = __('Thanks for your feedback.', 'zaxu');
        wp_send_json_success($message);
    }

    /**
     * Send email feedback
     *
     * @return void
     */
    public function handle_contact() {
        check_ajax_referer('zaxudocs-ajax');
        $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
        $message = isset( $_POST['message'] ) ? strip_tags( $_POST['message'] ) : '';
        $doc_id = isset( $_POST['doc_id'] ) ? intval( $_POST['doc_id'] ) : 0;
        if ( !is_user_logged_in() ) {
            $email = isset( $_POST['email'] ) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : false;
            if (!$email) {
                wp_send_json_error( __('Please enter a valid email address.', 'zaxu') );
            }
        } else {
            $email = wp_get_current_user()->user_email;
        }
        if ( empty($subject) ) {
            wp_send_json_error( __('Please provide a subject line.', 'zaxu') );
        }
        if ( empty($message) ) {
            wp_send_json_error( __('Please provide the message details.', 'zaxu') );
        }
        zaxudocs_doc_feedback_email($doc_id, $name, $email, $subject, $message);
        wp_send_json_success( __('Thanks for your feedback.', 'zaxu') );
    }

    /**
     * Sort docs
     *
     * @return void
     */
    public function sort_docs() {
        check_ajax_referer('zaxudocs-admin-nonce');
        $doc_ids = isset( $_POST['ids'] ) ? array_map( 'absint', $_POST['ids'] ) : array();
        if ($doc_ids) {
            foreach ($doc_ids as $order => $id) {
                wp_update_post( array(
                    'ID' => $id,
                    'menu_order' => $order
                ) );
            }
        }
        exit;
    }

    /**
     * Build a tree of docs with parent-child relation
     *
     * @param  array   $docs
     * @param  integer  $parent
     *
     * @return array
     */
    public function build_tree($docs, $parent = 0) {
        $result = array();
        if (!$docs) {
            return $result;
        }
        $post_type_object = get_post_type_object('docs');
        foreach ($docs as $key => $doc) {
            if ($doc->post_parent == $parent) {
                unset( $docs[$key] );
                // build tree and sort
                $child = $this->build_tree($docs, $doc->ID);
                usort( $child, array($this, 'sort_callback') );
                $result[] = array(
                    'post' => array(
                        'id' => $doc->ID,
                        'title' => $doc->post_title,
                        'status' => $doc->post_status,
                        'order' => $doc->menu_order,
                        'url' => get_permalink($doc->ID),
                        'caps' => array(
                            'edit' => current_user_can($post_type_object->cap->edit_post, $doc->ID),
                            'delete' => current_user_can($post_type_object->cap->delete_post, $doc->ID)
                        )
                    ),
                    'child' => $child
                );
            }
        }
        return $result;
    }

    /**
     * Sort callback for sorting posts with their menu order
     *
     * @param  array  $a
     * @param  array  $b
     *
     * @return int
     */
    public function sort_callback($a, $b) {
        return $a['post']['order'] - $b['post']['order'];
    }
}
?>
