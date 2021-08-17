<!doctype html>
<html <?php language_attributes(); ?> class="avgrund-ready">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
	<meta http-equiv="Cache-Control" content="no-transform" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="renderer" content="webkit"/>
	<meta name="force-rendering" content="webkit"/>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta name="theme-color" content="<?php echo b2_get_option('template_top','gg_bg_color'); ?>">
	<?php wp_head();?>

</head>

<body <?php body_class(b2_get_option('template_top','top_type')); ?>>

	<div id="page" class="site">

		<?php do_action('b2_header'); ?>
		
	<div id="content" class="site-content">
	
		<?php do_action('b2_content_before'); ?>
