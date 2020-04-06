<?php

$browseByPerson = true;
$pageTitle = __('Meet our Neighbors');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(['title' => $pageTitle, 'bodyclass' => 'people browse']);
?>

<h1><?php echo $pageTitle; ?></h1>

<div class="search-by-lastname">
    <span><?php echo __('SEARCH BY LAST NAME'); ?></span>
    <ul>
        <li<?php echo !$curLetter
            ? ' class="active"'
            : ''; ?>><a href="<?php echo $this->url(
    [],
    'peopleDefault'
); ?>">All</a></li>
        <?php foreach (range('A', 'Z') as $letter): ?>
        <?php if (in_array($letter, $validLetters)): ?>
                <li<?php echo $curLetter == $letter
                    ? ' class="active"'
                    : ''; ?>>
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

<?php foreach (loop('items') as $item): ?>
  <?php
  $itemTitle = metadata('item', 'display_title');
  $itemClasses = "";
  if (metadata('item', 'has files')) {
      $itemClasses = " has-picture";
  }
  if (metadata('item', ['Dublin Core', 'Description'])) {
      $itemClasses .= " has-bio";
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
