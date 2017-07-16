<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>
<div id="primary">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <p><?php echo $homepageText; ?></p>
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item -->
    <div id="featured-item" class="featured">
        <h2 class="hidden"><?php echo __('Featured Item'); ?></h2>
        <?php echo random_featured_items(1); ?>
    </div><!--end featured-item-->
    <?php endif; ?>
</div><!-- end primary -->

<div id="secondary">
    <?php if (get_theme_option('Display Featured Collection')): ?>
        <!-- Featured Collection -->
        <div id="featured-collection" class="featured">
            <h2 class="hidden"><?php echo __('Featured Collection'); ?></h2>
            <?php echo random_featured_collection(); ?>
            <div class="featured__button">
                <a href="/collections/browse">Explore Collections</a>
            </div>
        </div><!-- end featured collection -->
    <?php endif; ?>
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
        <!-- Featured Exhibit -->
        <div id="featured-exhibit" class="featured">
            <h2 class="hidden"><?php echo __('Featured Collection'); ?></h2>
            <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
            <div class="featured__button">
                <a href="/exhibits/browse">Explore Exhibits</a>
            </div>
        </div><!-- end featured exhibit -->
    <?php endif; ?>
</div><!-- end secondary -->
<?php echo foot(); ?>
