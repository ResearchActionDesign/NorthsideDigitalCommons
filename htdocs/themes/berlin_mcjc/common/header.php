<!DOCTYPE html>
<html class="<?php echo get_theme_option(
    'Style Sheet'
); ?>" lang="<?php echo get_html_lang(); ?>">
<?php
$commit_hash = "1";
@include 'commit-hash.php';

// Generate social media metadata
if (isset($description)) {
    $description = htmlentities(strip_formatting($description));
}

if (!isset($description) || empty($description)) {
    $description = option('description');
}

if (isset($title)) {
    $titleParts[] = htmlentities(trim(strip_formatting($title)));
}
$titleParts[] = option('site_title');
$title = implode(' | ', $titleParts);
?>
<head>
  <?php if ($google_id = get_theme_option('google_analytics_id')): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo $google_id; ?>');</script>
    <!-- End Google Tag Manager -->
  <?php endif; ?>

    <?php
/** Load google fonts. @see https://csswizardry.com/2020/05/the-fastest-google-fonts/ */
?>
    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin />
    <link rel="preload"
          as="style"
          href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Montserrat:400,600,700&display=swap" />
    <link rel="preload"
          as="style"
          href="/themes/berlin_mcjc/css/style.css?v=<?php echo $commit_hash; ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

    <meta name="title" content="<?php echo $title; ?>" />
    <?php if (!empty($description)): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta property="twitter:description" content="<?php echo $description; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <?php endif; ?>

    <meta property="og:url" content="<?php echo absolute_url(); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?php echo $title; ?>">

    <?php if ($image = mcjc_item_image_url()): ?>
    <meta property="twitter:image" content="<?php echo $image; ?>">
    <meta property="og:image" content="<?php echo $image; ?>">
    <?php endif; ?>

    <title><?php echo $title; ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head', ['view' => $this]); ?>
    <?php
/* Include modernizr script first so classes are applied before CSS loaded */
?>
    <script src="/themes/berlin_mcjc/javascripts/modernizr-webp.js" async></script>
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
  <?php queue_css_file(['iconfonts', 'media-player']); ?>
  <?php if ($bodyid !== 'home') {
      queue_js_file('masonry.min');
  }
// JS and CSS actually rendered in footer for the most part.
?>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Montserrat:400,600,700&display=swap" />
    <link rel="stylesheet"
          href="/themes/berlin_mcjc/css/style.css?v=<?php echo $commit_hash; ?>" />
    <noscript>
        <link rel="stylesheet"
              href="/themes/berlin_mcjc/css/noscript.css?v=<?php echo $commit_hash; ?>" />
    </noscript>
</head>
<?php echo body_tag(['id' => $bodyid ?? '', 'class' => $bodyclass ?? '']); ?>
<?php if ($google_id): ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $google_id; ?>"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
<div class="page-header-background-container">
    <div class="page-header-container">
<a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
<?php fire_plugin_hook('public_body', ['view' => $this]); ?>

 <?php echo common('header-nav', ['bodyid' => $bodyid ?? '']); ?>
    </div>
</div>
<article id="content" role="main">
<?php fire_plugin_hook('public_content_top', ['view' => $this]); ?>
