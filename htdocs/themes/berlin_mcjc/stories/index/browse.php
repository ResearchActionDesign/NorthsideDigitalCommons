<?php
$pageTitle = __('Oral Histories');

echo head(['title' => $pageTitle, 'bodyclass' => 'stories browse']);
?>
<?php echo common('breadcrumbs', [
    'trail' => ['Oral Histories'],
]); ?>

<h1><?php echo $pageTitle; ?></h1>

<div class="background-container">
<div class="grid-container">
  <div class="grid-items">
<?php foreach (loop('items') as $item): ?>
  <?php echo common('grid-item', [
      'item' => $item,
  ]); ?>
<?php endforeach; ?>
  </div>
</div>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_items_browse', [
    'items' => $items,
    'view' => $this,
]); ?>
</div>

<?php echo foot(); ?>
