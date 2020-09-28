<?php

$pageTitle = __('Browse Items');
echo head(['title' => $pageTitle, 'bodyclass' => 'items browse']);
?>
<?php echo common('breadcrumbs', [
    'trail' => ['Tags'],
]); ?>

<h1><?php echo $tag_name; ?></h1>

<div class="grid-container">
  <div class="grid-items">
    <?php foreach (loop('items') as $item): ?>
      <?php echo common('grid-item', ['item' => $item]); ?>
    <?php endforeach; ?>
  </div>
</div>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_items_browse', [
    'items' => $items,
    'view' => $this,
]); ?>

<?php echo foot(); ?>
