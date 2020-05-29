<?php
$collectionTitle = metadata('collection', 'display_title'); ?>

<?php echo head([
    'title' => $collectionTitle,
    'bodyclass' => 'collections show',
]); ?>

<div class="collection-top-header">
  <h1><?php echo $collectionTitle; ?></h1>
  <div class="header-description">
    <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis quod libero nulla quia autem? Sapiente
      aliquid voluptatibus veritatis. Consequatur, voluptatem! Facilis, accusantium doloremque. Eaque, ipsum corporis?
      Animi dolorem numquam ullam!</p>
    <div class="collection-header-img">PLACEHOLDER</div>
  </div>
</div>

<div class="collection-main-content">
  <div class="collection-description">
    <?php echo metadata('collection', ['Dublin Core', 'Description']); ?>
  </div>
  <div class=" related-items">Related-item PlaceHolder</div>
</div>


<div class="collection-grid">

  <?php if (metadata('collection', 'total_items') > 0): ?>
  <?php foreach (loop('items') as $item): ?>
  <?php $itemTitle = metadata('item', 'display_title'); ?>

  <div class="collection item">
    <div class="collection-item-img">
      <?php if ($collectionImage = record_image('collection')): ?>
      <?php echo mcjc_files_for_item(
          'item',
          [],
          ['class' => 'item-file'],
          $item
      ); ?>
    </div>
    <div class="collection item-meta">
      <div class="item-description">
        <?php echo $description; ?>
      </div>
    </div><!-- end class="item-meta" -->
    <div class="item-title"><?php echo mcjc_link_to_item(
        $itemTitle,
        $item
    ); ?></div>
    <?php endif; ?>

    <?php if (
        $description = metadata(
            'item',
            ['Dublin Core', 'Description'],
            ['snippet' => 250]
        )
    ): ?>

    <?php endif; ?>
  </div>
  <?php endforeach; ?>
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
