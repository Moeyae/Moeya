<?php
/**侧边栏
 */

// if(!B2\Modules\Templates\Main::show_sidebar()) return;
$sidebars_widgets = wp_get_sidebars_widgets();

// $arg = array(
//     'post_single'=>'sidebar-3',
//     'shop_single'=>'sidebar-6',
//     'shop_archive'=>'sidebar-7',
//     'shop_home'=>'sidebar-5',
//     'circle_single'=>'sidebar-12',
//     'circle_archive'=>'sidebar-10',
//     'newsflashes_single'=>'sidebar-8',
//     'newsflashes_archive'=>'sidebar-11',
//     'page_single'=>'sidebar-4',
//     'default'=>'sidebar-1',
// );



// ob_start();
// dynamic_sidebar( 'sidebar-8' );
// $output = ob_get_contents();

// ob_end_clean();

$page = is_page();

$is_circle = apply_filters('b2_is_page', 'circle');

$is_newsflashes = is_singular('newsflashes');
$is_archive_newsflashes = is_post_type_archive('newsflashes') || is_tax('newsflashes_tags');
$is_shop = is_singular('shop');
$is_post = is_singular('post');
$is_circle_single = is_singular('circle');

$is_stream = apply_filters('b2_is_page', 'stream');

if($is_post && empty($sidebars_widgets['sidebar-3'])) return;
if($is_circle_single && empty($sidebars_widgets['sidebar-12'])){
    echo '<style>.single-circle .b2-single-content, .circle-topic-edit.b2-single-content {
        width: 620px;
        max-width: 100%;
    }</style>';
    return;
}

if($is_post){
    $style = B2\Modules\Templates\Single::get_single_post_settings(get_the_id(),'single_post_style');
    $style = $style ? $style : 'post-style-1';

    if($style === 'post-style-2') return;

    $show_widget = B2\Modules\Templates\Single::get_single_post_settings(get_the_id(),'single_post_sidebar_show');
    if((int)$show_widget == 0) return;
}

if($is_shop && empty($sidebars_widgets['sidebar-6'])) return;

if($is_newsflashes && empty($sidebars_widgets['sidebar-8'])) return;

if($is_archive_newsflashes && empty($sidebars_widgets['sidebar-11'])) return;

if($page && empty($sidebars_widgets['sidebar-4']) && !is_front_page() && !$is_stream) return;

if($is_circle && empty($sidebars_widgets['sidebar-10'])){
    return;
}

//分类
$tax = get_queried_object();
$taxonomy = isset($tax->taxonomy) ? $tax->taxonomy : '';

?>
<aside id="secondary" class="widget-area">
    <div class="sidebar">
        <div class="sidebar-innter widget-ffixed">
            <?php 
                if($is_newsflashes){
                    dynamic_sidebar( 'sidebar-8' );
                }elseif($is_circle_single){
                    dynamic_sidebar( 'sidebar-12' );
                }elseif($is_circle){
                    dynamic_sidebar( 'sidebar-10' );
                }elseif($is_shop){
                    dynamic_sidebar( 'sidebar-6' );
                }elseif($is_post){
                    dynamic_sidebar( 'sidebar-3' );
                }elseif(is_post_type_archive('shop') || apply_filters('b2_is_page', 'shop')){
                    dynamic_sidebar( 'sidebar-5' );
                }elseif($taxonomy === 'shoptype'){
                    dynamic_sidebar( 'sidebar-7' );
                }elseif($is_stream){
                    dynamic_sidebar( 'sidebar-13' );
                }elseif($is_archive_newsflashes){
                    dynamic_sidebar( 'sidebar-11' );
                }elseif($page){
                    dynamic_sidebar( 'sidebar-4' );
                }else{
                    dynamic_sidebar( 'sidebar-1' );
                }
            ?>
        </div>
    </div>
</aside>