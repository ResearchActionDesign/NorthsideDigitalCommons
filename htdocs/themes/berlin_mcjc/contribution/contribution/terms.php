<?php
$head = ['title' => __('Add Your Story'), 'bodyclass' => 'contribution terms'];
echo head($head);
$imageTitle = __('Tell Your Story');
?>
<div class="title">
  <div class='header-background-container add-story-image'>
    <div class="header-background-container-content">
      <h1 class='image-title'> <?php echo $imageTitle; ?> </h1>
      <p class="image-text"> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, quo saepe non magnam
        cum, molestiae
        incidunt voluptatum, hic nisi dolor fuga? Mollitia magnam velit aliquid voluptatem saepe sit alias laboriosam.
      </p>
    </div>
  </div>
</div>
<div id="primary">
  <div class='contribution-content-wrapper'>
    <h2><?php echo __('Terms of Service'); ?></h2>
    <?php echo get_option('contribution_consent_text'); ?>
  </div>
</div>
<?php echo foot(); ?>
