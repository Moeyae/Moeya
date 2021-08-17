<?php
/*
 * @Description: Doc sidebar
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;
?>
<div class="zaxudocs-sidebar">
    <?php
        $ancestors = array();
        $root = $parent = false;

        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $root = count($ancestors) - 1;
            $parent = $ancestors[$root];
        } else {
            $parent = $post->ID;
        }

        // var_dump( $parent, $ancestors, $root );
        $walker = new zaxu_docs_Walker_Docs();
        $children = wp_list_pages(
            array (
                'title_li' => '',
                'order' => 'menu_order',
                'child_of' => $parent,
                'echo' => false,
                'post_type' => 'docs',
                'walker' => $walker
            )
        );
    ?>

    <header class="documentation-header">
        <div class="documentation-title">
            <span class="close"></span>
            <h3><?php echo get_post_field('post_title', $parent, 'display'); ?></h3>
        </div>
    </header>

    <?php
        if ($children) {
            echo '
                <nav>
                    <ul class="doc-nav-list">' . $children . '</ul>
                </nav>
            ';
        }
    ?>
</div>