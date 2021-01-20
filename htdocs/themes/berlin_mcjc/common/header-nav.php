  <header id="home_page_header_container" role="banner">
    <?php fire_plugin_hook('public_header', ['view' => $this]); ?>
    <div class="header-content-wrapper">
      <?php if ($bodyid !== 'home'): ?>
      <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>
      <?php endif; ?>

        <nav>
          <?php echo public_nav_main()->setMaxDepth(0); ?>
            <button id="search-toggle" aria-controls="search-form-container" aria-expanded="false"><i class="fa fa-search"></i><span class="sr-only">Search</span></button>
            <div id="search-form-container">
              <button id="search-close"><i class="fa fa-close"></i><span class="sr-only">Close</span></button>
            <?php echo search_form(); ?>
            </div>
        </nav>
    </div>
  </header>

<?php echo theme_header_image(); ?>
