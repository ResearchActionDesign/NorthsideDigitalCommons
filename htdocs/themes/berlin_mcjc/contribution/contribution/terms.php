<?php
$head = ['title' => __('Add Your Story'), 'bodyclass' => 'contribution terms'];
echo head($head);
?>
<div class="title">
    <h1><?php echo $head['title']; ?></h1>
</div>
<div id="primary">
    <h2><?php echo __('Terms of Service'); ?></h2>
<?php echo get_option('contribution_consent_text'); ?>
</div>
<?php echo foot(); ?>
