<?php
$pageTitle = __('Our Community. Our Stories.');
echo head(['title' => $pageTitle, 'bodyclass' => 'topics browse']);
?>

<div class="header-background-container topics-image">
  <div class="header-background-container-content">
    <h1 class="image-title"><?php echo $pageTitle; ?></h1>
    <?php if ($link_text = get_theme_option('homepage_explore_themes_text')): ?>
        <p class="image-text"><?php echo $link_text; ?></p>
    <?php endif; ?>
  </div>
</div>

<div class="topics-content">
  <?php
  $sortLinks[__('Title')] = 'Dublin Core,Title';
  $sortLinks[__('Date Added')] = 'added';
  ?>

  <div class="filter_container">
    <div class="filter" id="grid__filter">
      <span class="grid__filter__title">Show:</span>
      <span class="grid__filter__option"><input class="checkbox" type="checkbox" data-filter="exhibit"><span
          class="filter-titles">Exhibits</span></input></span>
      <span class="grid__filter__option"><input class="checkbox" type="checkbox" data-filter="collection"><span
          class="filter-titles">Collections</span></input></span>
      <span class="grid__filter__option"><input class="checkbox" type="checkbox" data-filter="theme"><span
          class="filter-titles">Themes</span></input></span>
    </div>
    <div id="sort-links">
      <span class="sort-label"><?php echo __(
          'Sort by: '
      ); ?></span><?php echo browse_sort_links($sortLinks); ?>
    </div>
  </div>
  <?php if ($topics): ?>
  <div class="grid-container topics-grid masonry-grid">
    <div class="grid-items">
    <?php foreach (loop('topics') as $topic): ?>
    <?php
    $topicClass = strtolower($topic->topicType);

    if ($topicClass == 'exhibit') {
        $title = 'Exhibit: ' . html_escape($topic->title);
        $description = metadata($topic, 'description', ['no_escape' => true]);
    } else {
        $title = "{$topic->topicType}: " . metadata($topic, 'display_title');
        $description = metadata(
            $topic,
            ['Dublin Core', 'Description'],
            ['snippet' => 250]
        );
    }
    ?>
      <?php echo common('grid-item', [
          'item' => $topic,
          'title' => $title,
          'description' => $description,
          'class' => "topic {$topicClass}",
          'masonry' => true,
      ]); ?>
    <?php endforeach; ?>
  </div>
  </div>
  <?php endif; ?>
</div>
<?php echo pagination_links(); ?>

<?php echo foot(); ?>
