<?php
$pageTitle = __('Our Community. Our Stories.');
echo head(['title' => $pageTitle, 'bodyclass' => 'topics browse']);
?>

<div class="header-background-container topics-image">
  <div class="header-background-container-content">
    <h1 class="image-title"><?php echo $pageTitle; ?></h1>
    <p class="image-text"> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, quo saepe non magnam cum, molestiae
      incidunt voluptatum, hic nisi dolor fuga? Mollitia magnam velit aliquid voluptatem saepe sit alias laboriosam.</p>
  </div>
</div>

<div class="topics-content">
  <?php
  $sortLinks[__('Title')] = 'Dublin Core,Title';
  $sortLinks[__('Date Added')] = 'added';
  ?>

  <div class=" filter_container">
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
  <div class="topics-grid">
    <?php foreach (loop('topics') as $topic): ?>
    <?php
    $topicClass = strtolower($topic->topicType);
    if ($topicClass == 'exhibit') {
        $title = 'Exhibit: ' . html_escape($topic->title);
        $titleLink = exhibit_builder_link_to_exhibit($topic, $title); // TODO: fix this path.
        $description = metadata($topic, 'description', ['no_escape' => true]);
    } else {
        $title = "{$topic->topicType}: " . metadata($topic, 'display_title');
        $topic->item_type_id = 'collection';
        $titleLink = mcjc_link_to_item($title, $topic);
        $description = metadata(
            $topic,
            ['Dublin Core', 'Description'],
            ['snippet' => 250]
        );
    }
    ?>

    <div class="topic item ">
      <div class=" item-img "><?php echo record_image(
          $topic,
          'square_thumbnail',
          [
              'alt' => $title,
          ]
      ); ?>
      </div>

      <div class=" item-meta topics-card">
        <h2><?php echo $titleLink; ?></h2>

        <div class="item-description">
          <?php echo $description; ?>

        </div>
      </div><!-- end class="item-meta" -->
      <div class="item-title"><?php echo $titleLink; ?></div>

      <!-- end class="item entry" -->
    </div>

    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php echo pagination_links(); ?>

<?php echo foot(); ?>
