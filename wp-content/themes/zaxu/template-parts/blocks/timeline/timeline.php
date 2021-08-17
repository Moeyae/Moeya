<?php
/*
 * @Description: Timeline block
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$id = 'zaxu-timeline-' . $block['id'];

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

<section id="<?php echo esc_attr($id); ?>" class="zaxu-timeline-container<?php echo $align_class; ?>">
    <?php
        $empty_item = true;
        $timeline_item = null;

        if ( have_rows('zaxu_timeline_repeater') ) {
            // Has item
            $empty_item = false;
            while( have_rows('zaxu_timeline_repeater') ): the_row();
                $key_node = zaxu_get_sub_field('zaxu_timeline_key_node');
                $title = esc_attr( zaxu_get_sub_field('zaxu_timeline_title') );
                $description = esc_attr( zaxu_get_sub_field('zaxu_timeline_description') );
                $content = zaxu_get_sub_field('zaxu_timeline_content');

                // Key node start
                    if ($key_node == 1) {
                        $key_node = ' active';
                    } else {
                        $key_node = null;
                    }
                // Key node end

                // Header start
                    if ($title || $description) {
                        // Title
                        if ($title) {
                            $title = '<h3 class="zaxu-timeline-title">' . $title . '</h3>';
                        } else {
                            $title = null;
                        }

                        // Description
                        if ($description) {
                            $description = '<span class="zaxu-timeline-description">' . $description . '</span>';
                        } else {
                            $description = null;
                        }

                        $header = '
                            <header class="zaxu-timeline-head">
                                <span class="zaxu-timeline-arrow"></span>
                                ' . $title . $description . '
                            </header>
                        ';
                    } else {
                        $header = null;
                    }
                // Header end

                // Content start
                    if ($content) {
                        $content = '
                            <div class="zaxu-timeline-content">
                                <div class="zaxu-timeline-body">' . $content . '</div>
                            </div>
                        ';
                    } else {
                        $content = null;
                    }
                // Content end

                if (!$header && !$content) {
                    // No header & content
                    $timeline_item .= null;
                } else {
                    // Have header or content
                    if (!$content) {
                        $no_content = ' zaxu-timeline-no-content';
                    } else {
                        $no_content = null;
                    }

                    $timeline_item .= '
                        <li class="zaxu-timeline-item' . $key_node . $no_content . '">
                            <div class="zaxu-timeline-box">
                                ' . $header . $content . '
                            </div>
                        </li>
                    ';
                }
            endwhile;
        }
        
        if ($empty_item == false && $timeline_item) {
            echo '
                <ul class="zaxu-timeline-list">' . $timeline_item . '</ul>
            ';
        } else {
            // No item
            if ( is_admin() ) {
                echo '<p class="zaxu-block-placeholder-tips">' . __('Click here to edit timeline.', 'zaxu') . '</p>';
            } else {
                zaxu_no_item_tips( __('Sorry! The timeline is not currently available.', 'zaxu') );
            }
        }
    ?>
</section>