<?php
$collectionTitle = metadata('collection', 'display_title'); ?>

<?php echo head([
    'title' => $collectionTitle,
    'bodyclass' => 'collections show',
]); ?>


<?php echo common('breadcrumbs', [
    'trail' => [
        'Topics' => '/topics',
        $collectionTitle,
    ],
]); ?>

<div class="primary">
    <div class="item-content">
    <span class="item-type">Collection</span>
    <h1><?php echo $collectionTitle; ?></h1>
  <p class="description">
    <?php echo metadata('collection', ['Dublin Core', 'Description']); ?>
  </p>
    </div>
      <?php if ($picture = record_image('collection')): ?>
    <div class="item-sidebar">
    <div id="picture" class="element">
              <div class="item-images"><?php echo $picture; ?></div>
          </div>
    </div>
      <?php endif; ?>
</div>
<div class="background-container">
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
  <?php echo pagination_links(); ?>
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


<div class="back-container">
    <a class="button back" href="/topics">Back to all Topics</a>
</div>
</div>

<?php echo foot(); ?>
