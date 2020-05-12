<?php
$collectionTitle = metadata('collection', 'display_title'); ?>

<?php echo head([
    'title' => $collectionTitle,
    'bodyclass' => 'collections show',
]); ?>

<h1><?php echo $collectionTitle; ?></h1>

<div id="item-description">
  <?php echo metadata('collection', ['Dublin Core', 'Description']); ?>
</div>

<div id="collection-items">
  <h2><?php echo link_to_items_browse(
      __('Items in the %s Collection', $collectionTitle),
      ['collection' => metadata('collection', 'id')]
  ); ?></h2>
  <?php if (metadata('collection', 'total_items') > 0): ?>
  <?php foreach (loop('items') as $item): ?>
  <?php $itemTitle = metadata('item', 'display_title'); ?>
  <div class="item entry">
    <h3><?php echo mcjc_link_to_item($itemTitle, $item); ?></h3>

    <?php if ($collectionImage = record_image('collection')): ?>
    <div class="item-img">
      <div class="item-images"><?php echo mcjc_files_for_item(
          'item',
          [],
          ['class' => 'item-file'],
          $item
      ); ?></div>
    </div>
    <?php endif; ?>

    <?php if (
        $description = metadata(
            'item',
            ['Dublin Core', 'Description'],
            ['snippet' => 250]
        )
    ): ?>
    <div class="item-description">
      <?php echo $description; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <p><?php echo __(
      'There are currently no items within this collection.'
  ); ?></p>
  <?php endif; ?>
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', [
    'view' => $this,
    'collection' => $collection,
]); ?>

<?php echo foot(); ?>
