<?php

$browseByTopic = true;
$pageTitle = __('Explore Our Neighborhoods');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(['title' => $pageTitle, 'bodyclass' => 'places browse']);
$missing_photo_text = strip_tags(get_theme_option('missing_photo_text'));
?>

<?php echo common('breadcrumbs', [
    'trail' => ['Places'],
]); ?>

<?php echo common('hero-image-header', [
    'title' => $pageTitle,
    'headerText' => get_theme_option('places_page_text'),
    'className' => 'places',
]); ?>

<div class="filter-by-letter">
  <span><?php echo __('SEARCH BY NAME'); ?></span>
  <ul>
    <li<?php echo !$curLetter
        ? ' class="active"'
        : ''; ?>><a href="<?php echo $this->url(
    [],
    'placesDefault'
); ?>" class="search-by-lastname__all">All</a></li>
      <?php foreach (range('A', 'Z') as $letter): ?>
      <?php if (in_array($letter, $validLetters)): ?>
      <li<?php echo $curLetter == $letter ? ' class="active"' : ''; ?>>
        <a href="<?php echo $this->url([], 'placesDefault', [
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
            'noImageText' => $missing_photo_text,
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
<?php echo foot();
?>
