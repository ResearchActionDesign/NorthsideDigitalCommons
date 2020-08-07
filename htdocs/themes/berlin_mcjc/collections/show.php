<?php
$collectionTitle = metadata('collection', 'display_title'); ?>

<?php echo head([
    'title' => $collectionTitle,
    'bodyclass' => 'collections show',
]); ?>

<div class="collection-top-header">
  <h1><?php echo $collectionTitle; ?></h1>
  <div class="header-description">
    <?php echo metadata('collection', ['Dublin Core', 'Description']); ?>
  </div>
</div>

<div class="grid-container masonry-grid collection-grid">
  <?php if (metadata('collection', 'total_items') > 0): ?>
    <div class="grid-items">
  <?php foreach (loop('items') as $item): ?>
  <?php echo common('grid-item', [
      'item' => $item,
      'class' => 'collection',
      'masonry' => true,
  ]); ?>
    <?php endforeach; ?>
    </div>
  <?php else: ?>
  <p><?php echo __(
      'There are currently no items within this collection.'
  ); ?></p>
  <?php endif; ?>
</div>
<!-- end collection-grid -->

<?php fire_plugin_hook('public_collections_show', [
    'view' => $this,
    'collection' => $collection,
]); ?>

<?php echo foot(); ?>
