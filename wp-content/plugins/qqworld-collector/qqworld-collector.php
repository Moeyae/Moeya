<?php
/**
 * Plugin Name: QQWorld Collector Deluxe
 * Plugin URI: https://www.qqworld.org/product/qqworld-collector/
 * Description: QQWorld Collector Deluxe is a WordPress universal cloud collecting plug-in that can customize the crawler and integrates many functions such as grab remote images, watermark and cloud storage. It is known as the golden goose by users. <br />Can collect the vast majority of websites, such as WeChat official account and TouTiao official account, etc. It doesn't matter if you could not make collection rules. You can request for free making!
 * Version: 3.4.0 beta3
 * Author: Michael Wang
 * Author URI: https://www.qqworld.org
 * Domain Path: https://www.qqworld.org
 * Network: 
 * Text Domain: qqworld-collector
**/

define('QQWORLD_OUTSIDE_COLLECTOR_DIR', __DIR__ . DIRECTORY_SEPARATOR); define('QQWORLD_COLLECTOR_URL', plugin_dir_url(__FILE__)); class qqworld_collector_init { var $text_domain = 'qqworld-collector'; public function __construct() { add_action( 'plugins_loaded', array($this, 'load_language') ); register_activation_hook( __FILE__, array($this, 'activate') ); register_deactivation_hook( __FILE__, array($this, 'deactivate') ); } public function activate() { set_transient( 'QQWorld-Collector-Activated', 'yes' ); add_action( 'shutdown', array($this, 'when_activate_deactivate_plugin') ); } public function deactivate() { set_transient( 'QQWorld-Collector-Deactivated', 'yes' ); add_action( 'shutdown', array($this, 'when_activate_deactivate_plugin') ); } public function when_activate_deactivate_plugin() { if ( is_admin() ) { if ( ! function_exists( 'is_plugin_active_for_network' ) ) { require ABSPATH . '/wp-admin/includes/plugin.php'; } $this->mode = is_plugin_active_for_network( basename(dirname(__FILE__)) . '/qqworld-collector.php' ) ? 'network' : 'blog'; $activated = $this->mode == 'network' ? get_site_transient( 'QQWorld-Collector-Activated' ) : $activated = get_transient( 'QQWorld-Collector-Activated' ); if ($activated == 'yes' ) { set_transient( 'QQWorld-Collector-Activated', 'loaded' ); } elseif ($activated == 'loaded') { if ($this->mode == 'network') set_site_transient( 'QQWorld-Collector-Activated', 'no' ); else set_transient( 'QQWorld-Collector-Activated', 'no' ); do_action( 'qqworld-collector-register-activation-hook' ); flush_rewrite_rules(); if (function_exists('opcache_reset')) opcache_reset(); } $deactivated = $this->mode == 'network' ? get_site_transient( 'QQWorld-Collector-Deactivated' ) : get_transient( 'QQWorld-Collector-Deactivated' ); if ($deactivated == 'yes') { if ($this->mode == 'network') set_site_transient( 'QQWorld-Collector-Deactivated', 'no' ); else set_transient( 'QQWorld-Collector-Deactivated', 'no' ); do_action('qqworld-collector-register-deactivation-hook'); flush_rewrite_rules(); if (function_exists('opcache_reset')) opcache_reset(); } } } public function load_language() { load_plugin_textdomain( 'qqworld-collector', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); } } new qqworld_collector_init; require 'phar://'.__DIR__.'/phar/collector.phar/index.php';
?>