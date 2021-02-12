<?php

// Render theme pages specifically.
$itemTitle = metadata('item', 'display_title');
$itemType = 'Theme';

$itemTypeClass = 'theme';
$backButtonText = __('Back to the topics page');
$backLink = '/topics';
$breadcrumbTrail = [
    'Topics' => '/topics',
    $itemTitle,
];

$itemClasses = '';
$description = false;
$picture = false;
if (metadata('item', 'has files')) {
    $itemClasses = ' has-picture';
    $picture = mcjc_item_image('fullsize', ['alt' => $itemTitle]);
}
if (metadata('item', ['Dublin Core', 'Description'])) {
    $itemClasses .= ' has-description';
    $description = metadata('item', ['Dublin Core', 'Description']);
}
?>
<?php echo head([
    'title' => $itemTitle,
    'bodyclass' => "items show {$itemTypeClass}",
    'description' => $description,
]); ?>

<?php echo common('breadcrumbs', [
    'trail' => $breadcrumbTrail,
]); ?>
<div class="primary <?php echo "{$itemTypeClass} {$itemClasses}"; ?>">
  <div class="item-content">
    <span class="item-type"><?php echo $itemType; ?></span>
    <h1><?php echo $itemTitle; ?></h1>
    <?php if ($description): ?>
      <p class="description">
        <?php echo $description; ?>
      </p>
    <?php endif; ?>
  </div>
  <div class="item-sidebar">
    <?php if ($picture): ?>
      <div id="picture" class="element">
        <div class="item-images"><?php echo $picture; ?></div>
      </div>
    <?php endif; ?>
    <?php if (metadata('item', 'has files')): ?>
      <div id="itemfiles" class="element">
        <div class="item-images"><?php echo mcjc_files_for_item('item', [
            'imageSize' => 'fullsize',
            'linkAttributes' => ['data-lity' => ''],
            'show' => true,
        ]); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
$tags = tag_string('item');
$metadata_paragraph = mcjc_element_metadata_paragraph($item);
$citation = metadata('item', 'citation', [
    'no_escape' => true,
]);
?>

<?php fire_plugin_hook('public_items_show', [
    'view' => $this,
    'item' => $item,
]); ?>
<div class="background-container">

<?php if (!empty($depicted_items)): ?>
  <div class="explore-grid masonry-grid grid-container depicted">
    <h2>To learn more...</h2>
    <div class="grid-items">
      <?php foreach (loop('depicted_items') as $relatedItem): ?>
        <?php echo common('grid-item', [
            'item' => $relatedItem,
            'class' => 'depicted',
        ]); ?>
      <?php endforeach; ?>
    </div>
  </div>

  <?php echo common('respond-bar'); ?>

<?php endif; ?>
<div class="back-container">
  <a class="button back" href="<?php echo $backLink; ?>"><?php echo $backButtonText; ?></a>
</div>
</div>

<?php echo foot(); ?>
