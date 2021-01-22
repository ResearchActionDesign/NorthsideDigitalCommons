  <header id="home_page_header_container" role="banner">
    <?php fire_plugin_hook('public_header', ['view' => $this]); ?>
    <div class="header-content-wrapper">
      <?php if ($bodyid !== 'home'): ?>
      <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>
      <?php endif; ?>

        <nav>
          <?php echo public_nav_main()->setMaxDepth(0); ?>
            <a id="search-toggle" href="/search">Search <i class="fa fa-search"></i></a>
            <div id="search-form-container">
              <button id="search-close"><i class="fa fa-close"></i><span class="sr-only">Close</span></button>
            <?php echo search_form(); ?>
            </div>
        </nav>
    </div>
  </header>

<?php echo theme_header_image(); ?>
