<?php

$browseByPerson = true;
$pageTitle = __('Meet our Neighbors');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(['title' => $pageTitle, 'bodyclass' => 'people browse']);
?>
    <div class="header-background-container">
        <div class="header-background-container-content">
            <h1 class="image-title"><?php echo $pageTitle; ?></h1>
  <?php if (
      $link_text = get_theme_option('homepage_meet_our_neighbors_text')
  ): ?>
      <p class="image-text"><?php echo $link_text; ?></p>
  <?php endif; ?>
        </div>
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
        <?php echo common('grid-item', [
            'item' => $item,
            'headingLevel' => 'h2',
        ]); ?>
      <?php endforeach; ?>
    </div>
</div>

<?php echo pagination_links(); ?> <?php fire_plugin_hook(
     'public_items_browse',
     [
         'items' => $items,
         'view' => $this,
     ]
 ); ?> <?php echo foot(); ?>
