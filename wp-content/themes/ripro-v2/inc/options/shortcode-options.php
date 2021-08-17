<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

$prefix = '_ripro_v2_shortcodes';

if (true && !is_close_site_shop()) {
    CSF::createShortcoder($prefix, array(
        'button_title'   => '添加付费隐藏内容',
        'select_title'   => '选择添加的内容块',
        'insert_title'   => '插入到文章',
        'show_in_editor' => true,
        'gutenberg'      => array(
            'title'       => 'Ri简码组件',
            'description' => 'Ri简码组件',
            'icon'        => 'screenoptions',
            'category'    => 'widgets',
            'keywords'    => array('shortcode', 'csf', 'insert'),
            'placeholder' => '在此处编写Ri简码...',
        ),
    ));

    CSF::createSection($prefix, array(
        'title'     => '隐藏部分付费内容[rihide]',
        'view'      => 'normal',
        'shortcode' => 'rihide',
        'fields'    => array(

            array(
                'id'    => 'content',
                'type'  => 'wp_editor',
                'title' => '',
                'desc'  => '[rihide]隐藏部分付费内容[/rihide] <br/> 注意：添加隐藏内容后，因为公用价格和折扣字段，所有资源类型优先为付费查看内容模式，侧边栏下载资源小工具将不显示',
            ),

        ),
    ));

}

/**
 * 付费查看部分内容
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:14:41+0800
 * @param    [type]                   $atts    [description]
 * @param    string                   $content [description]
 * @return   [type]                            [description]
 */
function rizhuti_v2_hide_shortcode($atts, $content = '') {
    // 付费资源信息
    if (is_close_site_shop()) {
        return '';
    }
    global $post, $current_user;
    $user_id     = $current_user->ID; //用户ID
    $post_id     = $post->ID; //文章ID
    $click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);
    // 付费资源信息 //是否购买
    $RiClass        = new RiClass($post_id, $user_id);
    $IS_PAID        = $RiClass->is_pay_post();
    $the_user_type  = _get_user_vip_type($user_id);
    $the_post_price = get_post_price($post_id, $the_user_type);

    //业务逻辑
    // 显示原始价格
  

    $_content = '<div class="ripay-content card mb-4">';
    $_content .= '<div class="card-body">';
    $_content .= '<span class="badge badge-info-lighten"><i class="fas fa-lock mr-1"></i> ' . esc_html__('隐藏内容', 'ripro-v2') . '</span>';

    if ($IS_PAID == 0) {
        $_content .= '<div class="d-flex justify-content-center">';
        $_content .= '<div class="text-center mb-4">';
        # 未购买..
        ob_start();
        the_post_shop_priceo_options($post_id);
        $_content .= ob_get_clean();
        if ($the_user_type == 'nov' && $the_post_price == -1) {
            $_content .= '<button type="button" class="btn btn-danger btn-sm" disabled>' . esc_html__('暂无购买权限', 'ripro-v2') . '</button>';
        } elseif (empty($user_id) && !is_site_nologin_pay()) {
            $_content .= '<button type="button" class="btn btn-dark btn-sm login-btn">' . esc_html__('登录后购买', 'ripro-v2') . '</button>';
        } else {
            $_content .= '<button type="button" class="btn btn-dark btn-sm click-pay-post" data-postid="' . $post_id . '" data-nonce="' . $click_nonce . '" data-price="' . $the_post_price . '">' . esc_html__('购买本内容', 'ripro-v2') . '</button>';
        }

        $_content .= '</div>';
        $_content .= '</div>';
    } elseif ($IS_PAID == 3) {
        # 免费资源...
        if (empty($user_id) && !is_site_nologin_pay()) {
            $_content .= '<button type="button" class="btn btn-light btn-sm login-btn">' . esc_html__('登录后免费查看', 'ripro-v2') . '</button>';
        } else {
            $_content .= '<div>' . do_shortcode($content) . '</div>';
        }
    } elseif ($IS_PAID > 0) {
        # 已购买...
        $_content .= '<div>' . do_shortcode($content) . '</div>';
    }

    $_content .= '</div>';
    $_content .= '</div>';

    // END

    return do_shortcode($_content);

}
add_shortcode('rihide', 'rizhuti_v2_hide_shortcode');
