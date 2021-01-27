<!DOCTYPE html>
<html class="<?php echo get_theme_option(
    'Style Sheet'
); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <?php
/** Load google fonts. @see https://csswizardry.com/2020/05/the-fastest-google-fonts/ */
?>
    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin />
    <link rel="preload"
          as="style"
          href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Montserrat:400,600,700&display=swap" />
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Montserrat:400,600,700&display=swap"
          media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Montserrat:400,600,700&display=swap" />
    </noscript>
    <link rel="preload"
          as="style"
          href="/themes/berlin_mcjc/css/style.css?v=2.7.1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
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

    <?php fire_plugin_hook('public_head', ['view' => $this]); ?>
    <?php
/* Include modernizr script first so classes are applied before CSS loaded */
?>
    <script src="/themes/berlin_mcjc/javascripts/modernizr-webp.js"></script>
    <!-- Stylesheets -->
	<link rel="apple-touch-icon" sizes="57x57" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/themes/berlin_mcjc/assets/images/icons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/themes/berlin_mcjc/assets/images/icons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/themes/berlin_mcjc/assets/images/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/themes/berlin_mcjc/assets/images/icons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/themes/berlin_mcjc/assets/images/icons/favicon-16x16.png">
	<link rel="manifest" href="/themes/berlin_mcjc/assets/manifest.json">
	<meta name="msapplication-TileImage" content="/themes/berlin_mcjc/assets/images/icons/ms-icon-144x144.png">
  <?php queue_css_file(['style', 'iconfonts']); ?>
    <?php echo head_css(); ?>
    <!-- JavaScripts -->
    <script
            src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
            crossorigin="anonymous"></script>
  <?php queue_js_file('masonry.min'); ?>
  <?php queue_js_file('frw'); ?>
  <?php
  if (stripos($bodyclass, 'show') !== false) {
      queue_js_file('sharer.min', 'javascripts', ['defer' => true]);
  }
  echo head_js(false);
  ?>
</head>
<?php echo body_tag(['id' => $bodyid ?? '', 'class' => $bodyclass ?? '']); ?>
<div class="page-header-background-container">
    <div class="page-header-container">
<a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
<?php fire_plugin_hook('public_body', ['view' => $this]); ?>

 <?php echo common('header-nav', ['bodyid' => $bodyid ?? '']); ?>
    </div>
</div>
<article id="content" role="main">
<?php fire_plugin_hook('public_content_top', ['view' => $this]); ?>
