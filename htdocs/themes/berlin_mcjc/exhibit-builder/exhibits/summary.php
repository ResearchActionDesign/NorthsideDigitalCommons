<?php
$pageTree = exhibit_builder_page_tree();
$firstPage = $exhibit->getFirstTopPage();
$firstPageUri = exhibit_builder_exhibit_uri($exhibit, $firstPage);
?>

<?php echo head([
    'title' => metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits summary',
]); ?>
<div class=exhibit-content>
<div class='exhibit-header-container'>
<div class='exhibit-title-container'>
<h1 class='exhibit-title'><?php echo metadata('exhibit', 'title'); ?></h1>
<?php echo exhibit_builder_page_nav(); ?>

<?php if (
    $exhibitDescription = metadata('exhibit', 'description', [
        'no_escape' => true,
    ])
): ?>
<div class="exhibit-description">
  <?php echo record_image('exhibit', 'fullsize', [
      'alt' => '',
      'class' => 'exhibit-image',
  ]); ?>
  <?php echo $exhibitDescription; ?>
  <a href="<?php echo $firstPageUri; ?>" class="button">Start the tour &rarr;</a>
</div>
<?php endif; ?>

<?php if ($exhibitCredits = metadata('exhibit', 'credits')): ?>
<div class="exhibit-credits">
    <h3><?php echo __('Credits'); ?></h3>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
</div>
<?php if ($exhibitTags = tag_string('exhibit')): ?>
    <div id="item-tags" class="element">
        <span class="element-title"><?php echo __('Tags: '); ?></span>
        <span class="element-text"><?php echo $exhibitTags; ?></span>
    </div>
    <?php endif; ?>
</div>


<?php if ($pageTree): ?>
<nav id="exhibit-pages">
    <h2>In this Exhibit</h2>
    <div class='exhibit-items'><?php echo $pageTree; ?> </div>
</nav>
</div>
<?php endif; ?>

<?php echo foot(); ?>
