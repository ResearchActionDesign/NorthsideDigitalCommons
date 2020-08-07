<?php

$pageTitle = __('Browse Items');
echo head(['title' => $pageTitle, 'bodyclass' => 'items browse']);
?>

<h1><?php echo $pageTitle; ?> <?php echo __(
     '(%s total)',
     $total_results
 ); ?></h1>

<nav class="items-nav navigation secondary-nav">
  <?php echo public_nav_items(); ?>
</nav>

<?php echo item_search_filters(); ?>

<?php if ($total_results > 0):

    $sortLinks[__('Title')] = 'Dublin Core,Title';
    $sortLinks[__('Creator')] = 'Dublin Core,Creator';
    $sortLinks[__('Date Added')] = 'added';
    ?>
<div id="sort-links">
  <span class="sort-label"><?php echo __(
      'Sort by: '
  ); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php
endif; ?>
<?php echo pagination_links(); ?>

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
