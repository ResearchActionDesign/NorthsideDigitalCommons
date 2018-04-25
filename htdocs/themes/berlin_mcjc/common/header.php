<?php

if ($title == "Search Items" AND @$_REQUEST['query'] AND empty(@$_REQUEST['search'])) {
    $_REQUEST['search'] = $_REQUEST['query'];
}
?>
<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts', 'skeleton','style'));
    ?>
    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans|Open+Sans:400,400i,700" rel="stylesheet">
	<link rel="apple-touch-icon" sizes="57x57" href="/themes/berlin_mcjc/images/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/themes/berlin_mcjc/images/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/themes/berlin_mcjc/images/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/themes/berlin_mcjc/images/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/themes/berlin_mcjc/images/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/themes/berlin_mcjc/images/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/themes/berlin_mcjc/images/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/themes/berlin_mcjc/images/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/themes/berlin_mcjc/images/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/themes/berlin_mcjc/images/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/themes/berlin_mcjc/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/themes/berlin_mcjc/images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/themes/berlin_mcjc/images/favicon-16x16.png">
	<link rel="manifest" href="/themes/berlin_mcjc/images/manifest.json">
	<meta name="msapplication-TileImage" content="/themes/berlin_mcjc/images/ms-icon-144x144.png">
  <?php echo head_css(); ?>
    <!-- JavaScripts -->
  <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
  <?php queue_js_file('vendor/respond'); ?>
  <?php queue_js_file('vendor/jquery-accessibleMegaMenu'); ?>
  <?php queue_js_file('berlin'); ?>
  <?php queue_js_file('globals'); ?>
  <?php echo head_js(); ?>
</head>
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
        <header role="banner">
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>

            <div id="search-container" role="search">
                <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                <?php echo search_form(array('show_advanced' => true)); ?>
                <?php else: ?>
                <?php echo search_form(); ?>
                <?php endif; ?>
            </div>
        </header>

         <div id="primary-nav" role="navigation">
             <?php
                  echo public_nav_main();
             ?>
         </div>

         <div id="mobile-nav" role="navigation" aria-label="<?php echo __('Mobile Navigation'); ?>">
             <?php
                  echo public_nav_main();
             ?>
         </div>

        <?php echo theme_header_image(); ?>

    <div id="content" role="main" tabindex="-1">

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
