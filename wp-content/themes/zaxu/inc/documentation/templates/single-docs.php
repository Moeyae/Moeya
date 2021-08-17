<?php
/*
 * @Description: Single doc
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;
$skip_sidebar = (get_post_meta($post->ID, 'skip_sidebar', true) == 'yes') ? true : false;
get_header();
?>

<?php zaxu_wrapper_start(); ?>
<?php while ( have_posts() ) : the_post(); ?>
    <div class="zaxudocs-single-wrap section-inner">
        <?php if (!$skip_sidebar) { ?>
            <?php zaxudocs_get_template_part('docs', 'sidebar'); ?>
        <?php } ?>

        <div class="zaxudocs-single-content">
            <?php zaxudocs_breadcrumbs(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
                <header class="entry-header">
                    <?php the_title('<h2 class="entry-title" itemprop="headline">', '</h2>'); ?>
                </header>
                <div class="zaxudocs-entry-content zaxu-fancybox" itemprop="articleBody">
                    <?php
                        the_content( sprintf(
                            /* translators: %s: Name of current post. */
                            wp_kses( __('Continue reading %s <span class="meta-nav">&rarr;</span>', 'zaxu'), array( 'span' => array( 'class' => array() ) ) ),
                            the_title('<span class="screen-reader-text">"', '"</span>', false)
                        ) );

                        zaxu_page_break_pagination();

                        $children = wp_list_pages("title_li=&order=menu_order&child_of=" . $post->ID . "&echo=0&post_type=" . $post->post_type);

                        if ($children) {
                            echo '<div class="article-child well">';
                                echo '<h3>' . __('Articles', 'zaxu') . '</h3>';
                                echo '<ul>';
                                    echo $children;
                                echo '</ul>';
                            echo '</div>';
                        }

                        $tags = wp_get_post_terms($post->ID, 'doc_tag');
                        if ($tags) {
                            echo '<span class="tags-links">';
                                foreach($tags as $tag) {
                                    echo '<a href="' . get_term_link($tag) . '" rel="tag" class="ajax-link">' . $tag->name . '</a>';
                                }
                            echo '</span>';
                        }
                    ?>
                </div>
                <footer class="zaxudocs-entry-footer">
                    <?php if (get_theme_mod('zaxu_doc_email_feedback', 'enabled') == 'enabled'): ?>
                        <span class="zaxudocs-help-link">
                            <i class="zaxudocs-icon zaxudocs-icon-envelope"></i>
                            <?php printf( '%s <a id="zaxudocs-stuck-modal" href="%s">%s</a>', __('Still stuck?', 'zaxu'), '#', __('How can we help?', 'zaxu') ); ?>
                        </span>
                    <?php endif; ?>

                    <div class="zaxudocs-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <meta itemprop="name" content="<?php echo get_the_author(); ?>" />
                        <meta itemprop="url" content="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>" />
                    </div>

                    <meta itemprop="datePublished" content="<?php echo get_the_time('c'); ?>"/>
                    <time itemprop="dateModified" datetime="<?php echo esc_attr( get_the_modified_date('c') ); ?>"><?php printf( __('Updated on %s', 'zaxu'), get_the_modified_date() ); ?></time>
                </footer>

                <?php if (get_theme_mod('zaxu_doc_navigation', 'enabled') == 'enabled'): ?>
                    <?php zaxudocs_doc_nav(); ?>
                <?php endif; ?>

                <?php if (get_theme_mod('zaxu_doc_helpful_feedback', 'enabled') == 'enabled'): ?>
                    <?php zaxudocs_get_template_part('content', 'feedback'); ?>
                <?php endif; ?>

                <?php if (get_theme_mod('zaxu_doc_email_feedback', 'enabled') == 'enabled'): ?>
                    <?php zaxudocs_get_template_part('content', 'modal'); ?>
                <?php endif; ?>
            </article>
        </div>
    </div>
<?php endwhile; ?>
<?php zaxu_wrapper_end(); ?>

<?php
    get_footer();
