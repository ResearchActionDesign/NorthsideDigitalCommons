<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>
<div id="primary">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <p><?php echo $homepageText; ?></p>
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item -->
    <div id="featured-item" class="featured">
        <h2 class="hidden"><?php echo __('Featured Item'); ?></h2>
        <?php echo mcjc_random_featured_items(1); ?>
    </div><!--end featured-item-->
    <?php endif; ?>
</div><!-- end primary -->

<div id="secondary">
    <?php if (get_theme_option('Display Featured Collection')): ?>
        <!-- Featured Collection -->
      <div class="featured-wrapper featured-wrapper--collection">
        <div id="featured-collection">
          <h2 class="hidden"><?php echo __('Featured Collection'); ?></h2>
          <?php echo random_featured_collection(); ?>
          <div class="featured__button">
            <a href="/collections/browse">Explore Collections</a>
          </div>
        </div><!-- end featured collection -->
      </div>
    <?php endif; ?>
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
        <div class="featured-wrapper featured-wrapper--exhibit">
            <!-- Featured Exhibit -->
            <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
            <div class="featured__button">
                <a href="/exhibits/browse">Explore Exhibits</a>
            </div>
        </div>
    <?php endif; ?>
</div><!-- end secondary -->
<?php echo foot(); ?>
