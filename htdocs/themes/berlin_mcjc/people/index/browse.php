<?php

$browseByPerson = true;
$pageTitle = __('Meet our Neighbors');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(['title' => $pageTitle, 'bodyclass' => 'people browse']);
?>

<?php echo common('breadcrumbs', [
    'trail' => ['People'],
]); ?>
    <div class="header-background-container people-image">
        <div class="header-background-container-content">
            <h1 class="image-title"><?php echo $pageTitle; ?></h1>
  <?php if ($header_text = get_theme_option('people_page_text')): ?>
      <p class="image-text"><?php echo $header_text; ?></p>
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
<div class="background-container">

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
 ); ?>
</div>
<?php echo foot(); ?>
