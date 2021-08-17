<?php
/*
 * @Description: Accordion block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-accordion-' . $block['id'];
$collapse_other_items = zaxu_get_field('zaxu_accordion_collapse_other_items');

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-accordion-container<?php echo $align_class; ?>">
    <?php
        if ( have_rows('zaxu_accordion_repeater') ) {
            // Has item
            if ($collapse_other_items == 'enabled') {
                echo '<div class="zaxu-accordion-list collapse-other-items">';
            } else {
                echo '<div class="zaxu-accordion-list">';
            };
    
            while( have_rows('zaxu_accordion_repeater') ): the_row();
                if (zaxu_get_field('zaxu_accordion_expand_all_items') == "enabled") {
                    $expand_this_item = " active";
                    $show_item = " style='display: block;'";
                } else {
                    $expand_this_item = (zaxu_get_sub_field('zaxu_accordion_expand_this_item') == "enabled") ? " active" : "";
                    $show_item = (zaxu_get_sub_field('zaxu_accordion_expand_this_item') == "enabled") ? " style='display: block;'" : "";
                };
                echo '
                    <div class="zaxu-accordion-item' . $expand_this_item . '">
                        <header>
                            <h3>' . esc_attr( zaxu_get_sub_field('zaxu_accordion_title') ) . '</h3>
                            <span class="icon"></span>
                        </header>
                        <div class="zaxu-accordion-content"' . $show_item . '>
                            <div class="zaxu-accordion-body">' . zaxu_get_sub_field('zaxu_accordion_content') .'</div>
                        </div>
                    </div>
                ';
            endwhile;
    
            echo '</div>';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit accordion.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The accordion is not currently available.', 'zaxu') );
            };
        };
    ?>
</section>