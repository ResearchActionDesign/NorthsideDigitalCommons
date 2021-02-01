<?php
$pageTitle = __('Our Community. Our Stories.');
echo head(['title' => $pageTitle, 'bodyclass' => 'topics browse']);
?>
<?php echo common('breadcrumbs', [
    'trail' => ['Topics'],
]); ?>

<?php echo common('hero-image-header', [
    'title' => $pageTitle,
    'headerText' => get_theme_option('topics_page_text'),
    'className' => 'topics',
]); ?>

<div class="topics-content">
    <div class="filter_container">
    </div>

    <div class="background-container">
  <?php if ($topics): ?>
  <div class="grid-container topics-grid masonry-grid">
    <div class="grid-items">
    <?php foreach (loop('topics') as $topic): ?>
    <?php
    $topicClass = strtolower($topic->topicType); //    $title = "{$topic->topicType}: " . html_escape($topic->title);
    $title = $topic->title;
    if ($topicClass == 'exhibit') {
        $description = metadata($topic, 'description', ['no_escape' => true]);
    } else {
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
</div>
<?php echo foot(); ?>
