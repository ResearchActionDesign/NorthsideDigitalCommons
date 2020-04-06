<?php
$pageTitle = __('Images');

echo head(['title' => $pageTitle, 'bodyclass' => 'images browse']);
?>

<h1><?php echo $pageTitle; ?></h1>

<?php foreach (loop('items') as $item): ?>
  <?php
  $itemTitle = metadata('item', 'display_title');
  $itemClasses = "";
  if (metadata('item', 'has files')) {
      $itemClasses = " has-picture";
  }
  if (metadata('item', ['Dublin Core', 'Description'])) {
      $itemClasses .= " has-description";
  }
  ?>
    <div class="item record<?php echo $itemClasses; ?>">
        <h2><?php echo mcjc_link_to_item($itemTitle, $item); ?></h2>
        <div class="item-meta">
          <?php if (metadata('item', 'has files')): ?>
              <div class="item-img">
                  <?php echo item_image('square_thumbnail', [
                      'alt' => $itemTitle,
                  ]); ?>
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

          <?php fire_plugin_hook('public_items_browse_each', [
              'view' => $this,
              'item' => $item,
          ]); ?>

        </div><!-- end class="item-meta" -->
    </div><!-- end class="item entry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_items_browse', [
    'items' => $items,
    'view' => $this,
]); ?>

<?php echo foot(); ?>
