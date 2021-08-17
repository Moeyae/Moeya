<?php
/*
 * @Description: TGM plugin config
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

require_once get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'zaxu_register_required_plugins');
function zaxu_register_required_plugins() {
	$plugins = array(
		// Advanced Custom Fields Pro
		array(
			'name'               => __('Advanced Custom Fields Pro', 'zaxu'),
			'slug'               => 'advanced-custom-fields-pro',
			'source'             => get_template_directory() . '/inc/plugins/advanced-custom-fields-pro.zip', 
			'required'           => true, 
			'version'            => '', 
			'force_activation'   => true, 
			'force_deactivation' => false, 
			'external_url'       => '',
			'is_callable'        => '',
		),
		// WooCommerce
		// array(
		// 	'name'               => __('WooCommerce', 'zaxu'),
		// 	'slug'               => 'woocommerce',
		// ),
	);
	$config = array(
		'id'           => 'zaxu',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => false,
		'dismiss_msg'  => '', 
		'is_automatic' => false,
		'message'      => '',
	);
	tgmpa($plugins, $config);
};
?>