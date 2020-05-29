<?php
$head = ['title' => __('Add Your Story'), 'bodyclass' => 'contribution terms'];
echo head($head);
$imageTitle = __("Tell Your Story");
?>
<div class="title">
    <div class='header-background-container add-story-image'>
        <div class="header-background-container-content">
            <h1 class='image-title'> <?php echo $imageTitle; ?> </h1>
            <div class='image-text'>
                <p>Loerum Ipsoms dolor sit amet, consectetur</p>
                <p>adipiscing elit. Integer suscitpit diam a nulla</p>
                <p>tempus rhoncus. Aliquam erat volutpat</p>
            </div>
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
