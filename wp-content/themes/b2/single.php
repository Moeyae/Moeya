<?php
    get_header();
    $post_type = get_post_type();
    $b2_custom_post_type = b2_get_search_type();
    $post_id = get_the_id();
?>
    
    <?php do_action('b2_single_wrapper_before'); ?>

    <div class="b2-single-content wrapper">

        <?php do_action('b2_single_before'); ?>

        <div id="primary-home" class="content-area">

            <?php  while ( have_posts() ) : the_post();

                do_action('b2_single_content_before');

                get_template_part( 'TempParts/single',isset($b2_custom_post_type[$post_type]) ? $post_type : 'normal');

                do_action('b2_single_content_after');

                if ( (comments_open() || get_comments_number()) && (int)b2_get_option('template_comment','comment_close') === 1 && $post_type !== 'circle') :
                    comments_template();
                endif;

                endwhile; ?>

                <?php do_action('b2_comments_after'); ?>
        </div>

        <?php do_action('b2_single_after'); ?>

    <?php 
        if($post_type !== 'announcement' && $post_type !== 'circle')
        get_sidebar(); 
    ?>
    
    </div>
    <!-- 公告弹窗 -->
    <div id="post-gg">
        <post-gg :show="show" :title="title" :content="content" @close="close" v-cloak></post-gg>
    </div>
    <?php do_action('b2_single_wrapper_after'); ?>
<?php
get_footer();