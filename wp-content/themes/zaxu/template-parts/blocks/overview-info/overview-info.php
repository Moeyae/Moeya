<?php
/*
 * @Description: Overview info block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-overview-info-' . $block['id'];

// Align
$align = $block['align'];
if ($align == "wide") {
    $align_class = " alignwide";
} else if ($align == "full") {
    $align_class = " alignfull";
} else {
    $align_class = null;
}
?>

<section id="<?php echo esc_attr($id); ?>" class="zaxu-overview-info-container<?php echo $align_class; ?>">
    <?php
        if ( have_rows('zaxu_overview_info_repeater') ) {
            // Has item
            echo '<ul role="rowgroup" class="zaxu-overview-info-list">';
            while( have_rows('zaxu_overview_info_repeater') ): the_row();
                $title = esc_attr( zaxu_get_sub_field('zaxu_overview_info_title') );
                $content = esc_attr( zaxu_get_sub_field('zaxu_overview_info_content') );
                if ($title && $content) {
                    // Content is link
                    if ( filter_var($content, FILTER_VALIDATE_URL) ) {
                        $content_element = '<a href="' . $content . '" rel="nofollow" target="_blank" class="button button-primary button-small zaxu-overview-info-link">' . __('Visit the Website', 'zaxu') . '</a>';
                    } else {
                        $content_element = '<p>' . $content . '</p>';
                    }
                    echo '
                        <li role="role" class="zaxu-overview-info-item">
                            <div role="rowheader" class="zaxu-overview-info-title">
                                <h3>' . $title . '</h3>
                            </div>
                            <div role="cell gridcell" class="zaxu-overview-info-content">
                               ' . $content_element . ' 
                            </div>
                        </li>
                    ';
                };
            endwhile;
            echo '</ul>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit overview info.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The overview info is not currently available.', 'zaxu') );
            }
        }
    ?>
</section>