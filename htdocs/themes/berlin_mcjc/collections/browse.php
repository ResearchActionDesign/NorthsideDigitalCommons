<?php
$pageTitle = __('Collections');
echo head(['title' => $pageTitle, 'bodyclass' => 'collections browse']);
?>
<?php echo common('breadcrumbs', [
    'trail' => ['Topics' => '/topics', 'Collections'],
]); ?>
<h1><?php echo $pageTitle; ?></h1>

<div class="collections-content">
    <div class="background-container">
          <div class="grid-container topics-grid masonry-grid">
              <div class="grid-items">
                <?php foreach (loop('collections') as $collection): ?>
                  <?php
                  $title = $collection->title;
                  $description = metadata(
                      $collection,
                      ['Dublin Core', 'Description'],
                      ['snippet' => 250]
                  );
                  echo common('grid-item', [
                      'item' => $collection,
                      'title' => $title,
                      'description' => $description,
                      'class' => "topic collection",
                      'masonry' => true,
                  ]);
                  ?>
                <?php endforeach; ?>
              </div>
          </div>
    </div>
  <?php echo pagination_links(); ?>
</div>
<?php echo foot(); ?>
