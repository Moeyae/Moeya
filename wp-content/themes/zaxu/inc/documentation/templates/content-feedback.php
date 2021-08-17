<?php
/*
 * @Description: Content feedback
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

global $post;
?>

<div class="zaxudocs-feedback-wrap">
    <?php
        $positive = (int) get_post_meta($post->ID, 'positive', true);
        $negative = (int) get_post_meta($post->ID, 'negative', true);
        $positive_title = $positive ? sprintf( _n('%d person found this useful', '%d persons found this useful', $positive, 'zaxu'), number_format_i18n($positive) ) : __('No votes yet', 'zaxu');
        $negative_title = $negative ? sprintf( _n('%d person found this not useful', '%d persons found this not useful', $negative, 'zaxu'), number_format_i18n($negative) ) : __('No votes yet', 'zaxu');
        _e('Was this article helpful to you?', 'zaxu');
    ?>
    <span class="vote-link-wrap">
        <a href="#" class="zaxudocs-tip positive" data-id="<?php the_ID(); ?>" data-type="positive" title="<?php echo esc_attr($positive_title); ?>">
            <?php _e('Yes', 'zaxu'); ?>
            <?php if ($positive) { ?>
                <span class="count"><?php echo number_format_i18n($positive); ?></span>
            <?php } ?>
        </a>
        <a href="#" class="zaxudocs-tip negative" data-id="<?php the_ID(); ?>" data-type="negative" title="<?php echo esc_attr($negative_title); ?>">
            <?php _e( 'No', 'zaxu' ); ?>
            <?php if ($negative) { ?>
                <span class="count"><?php echo number_format_i18n($negative); ?></span>
            <?php } ?>
        </a>
    </span>
</div>