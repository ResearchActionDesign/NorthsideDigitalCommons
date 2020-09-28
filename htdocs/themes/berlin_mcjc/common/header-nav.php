  <header id="home_page_header_container" role="banner">
    <?php fire_plugin_hook('public_header', ['view' => $this]); ?>
    <div class="header-content-wrapper">
      <?php if ($bodyid !== 'home'): ?>
      <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>

      <div class="nav-search-wrapper">
        <div id="search-container" role="search">
          <div class="search-form-container">
            <?php echo search_form(); ?>
          </div>
        </div>
        <nav id="primary-nav" role="navigation">
          <?php echo public_nav_main(); ?>
        </nav>
      </div>
      <?php endif; ?>

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
