<?php

$browseByPerson = true;
$pageTitle = __('Meet our Neighbors');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(['title' => $pageTitle, 'bodyclass' => 'people browse']);
?>
<div class="top-header">

  <h1><?php echo $pageTitle; ?></h1>
  <?php if (
      $link_text = get_theme_option('homepage_meet_our_neighbors_text')
  ): ?>
      <p><?php echo $link_text; ?></p>
  <?php endif; ?>

</div>
<div class="filter-by-letter">
  <span><?php echo __('SEARCH BY LAST NAME'); ?></span>
  <ul>
    <li<?php echo !$curLetter
        ? ' class="active"'
        : ''; ?>><a href="<?php echo $this->url(
    [],
    'peopleDefault'
); ?>" class="search-by-lastname__all">All</a></li>
      <?php foreach (range('A', 'Z') as $letter): ?>
      <?php if (in_array($letter, $validLetters)): ?>
      <li<?php echo $curLetter == $letter ? ' class="active"' : ''; ?>>
        <a href="<?php echo $this->url([], 'peopleDefault', [
            'firstLetter' => $letter,
        ]); ?>"><?php echo $letter; ?></a>
        </li>
        <?php else: ?>
        <li class="disabled">
          <?php echo $letter; ?>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
  </ul>
</div>

<div class="grid-container">
  <div class="grid-items">
  <?php foreach (loop('items') as $item): ?>
  <?php
  // TODO: Replace this with the related-item common file potentially.
  $itemTitle = metadata('item', 'display_title');
  $itemClasses = '';
  $hasImg = false;
  if (metadata('item', 'has files')) {
      $itemClasses = ' has-picture';
      $hasImg = true;
  }
  $description = metadata(
      'item',
      ['Dublin Core', 'Description'],
      ['snippet' => 250]
  );
  if ($description) {
      $itemClasses .= ' has-description';
  }
  ?>
  <a href="<?php echo mcjc_url_for_item(
      $item
  ); ?>" class="item record<?php echo $itemClasses; ?>">
    <?php if ($hasImg): ?>
    <div class="item-img">
      <?php echo item_image('square_thumbnail', ['alt' => $itemTitle]); ?>
    </div>
    <?php endif; ?>



    <div class="item-meta">
      <h2><?php echo $itemTitle; ?></h2>

      <?php if ($description): ?>
      <div class="item-description">
        <?php echo $description; ?>
      </div>
      <?php endif; ?>

      <?php fire_plugin_hook('public_items_browse_each', [
          'view' => $this,
          'item' => $item,
      ]); ?>

    </div>
    <!-- end class="item-meta" -->
    <div class="item-title"><?php echo $itemTitle; ?></div>
    <!-- end class="item entry" -->
  </a>

  <?php endforeach; ?>
</div>
</div>
<div <?php echo pagination_links(); ?> <?php fire_plugin_hook(
     'public_items_browse',
     [
         'items' => $items,
         'view' => $this,
     ]
 ); ?> <?php echo foot(); ?>
