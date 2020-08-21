<?php

if (
    $title == "Search Items" and
    @$_REQUEST['query'] and
    empty(@$_REQUEST['search'])
) {
    $_REQUEST['search'] = $_REQUEST['query'];
} ?>
<!DOCTYPE html>
<html class="<?php echo get_theme_option(
    'Style Sheet'
); ?>" lang="<?php echo get_html_lang(); ?>">
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

    <?php fire_plugin_hook('public_head', ['view' => $this]); ?>
    <!-- Stylesheets -->
    <?php queue_css_file(['iconfonts', 'style']); ?>
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
  <?php echo head_css(); ?>
    <!-- JavaScripts -->
  <?php queue_js_file('vendor/selectivizr', 'javascripts', [
      'conditional' => '(gte IE 6)&(lte IE 8)',
  ]); ?>
  <?php queue_js_file('vendor/respond'); ?>
  <?php queue_js_file('vendor/jquery-accessibleMegaMenu'); ?>
  <?php queue_js_file('masonry.min'); ?>
    <?php queue_js_file('berlin'); ?>
  <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
</head>
 <?php echo body_tag(['id' => $bodyid ?? '', 'class' => $bodyclass ?? '']); ?>
    <a href="#content" id="skipnav"><?php echo __(
        'Skip to main content'
    ); ?></a>
    <?php fire_plugin_hook('public_body', ['view' => $this]); ?>
        <header id="home_page_header_container" role="banner">
            <?php fire_plugin_hook('public_header', ['view' => $this]); ?>
            <div class="header-content-wrapper">
                <div id="site-title"><?php echo link_to_home_page(
                    theme_logo()
                ); ?></div>

                <div class="nav-search-wrapper">
                    <nav id="primary-nav" role="navigation">
                        <?php echo public_nav_main(); ?>
                    </nav>

                    <div id="search-container" role="search">
                      <?php echo $this->formButton(
                          'search-toggle',
                          $options['search_toggle'] ?? '',
                          [
                              'type' => 'button',
                              'aria-label' => 'Show search bar',
                          ]
                      ); ?>
                        <div class="search-form-container">
                            <button id="search-close" aria-label="Hide search bar">Close search bar</button>
                                <?php echo search_form(); ?>
                            </div>
                        </div>
                </div>

                <nav id="mobile-nav" role="navigation">
                  <button class="menu" aria-expanded="false" aria-label="<?php echo __(
                      'Menu'
                  ); ?>"><i class="fa fa-bars"></i></button>
                  <?php echo public_nav_main(); ?>
                    <div class="search-form-container">
                    <?php echo search_form(['id' => 'mobile-search-form']); ?>
                    </div>
                </nav>
            </div>
        </header>

        <?php echo theme_header_image(); ?>

    <div id="content" role="main" tabindex="-1">

<?php fire_plugin_hook('public_content_top', ['view' => $this]); ?>
