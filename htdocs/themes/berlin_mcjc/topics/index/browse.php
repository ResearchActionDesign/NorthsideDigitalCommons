<?php
$pageTitle = __('Our Community, Our Stories');
echo head(array('title'=>$pageTitle,'bodyclass' => 'topics browse'));
?>

<h1><?php echo $pageTitle; ?></h1>

<?php
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Date Added')] = 'added';
?>
<div class="filter" id="grid__filter">
  <span class="grid__filter__title">Show:</span>
  <span class="grid__filter__option"><input type="checkbox" data-filter="exhibit">Exhibits</input></span>
  <span class="grid__filter__option"><input type="checkbox" data-filter="collection">Collections</input></span>
  <span class="grid__filter__option"><input type="checkbox" data-filter="theme">Themes</input></span>
</div>
<div id="sort-links">
    <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php if ($topics): ?>
<div class="topics grid">
<?php  foreach (loop('topics') as $topic): ?>
<?php
$topicClass = strtolower($topic->topicType);
if ($topicClass == 'exhibit') {
  $title = "Exhibit: " . html_escape($topic->title);
  $titleLink = exhibit_builder_link_to_exhibit($topic, $title); // TODO: fix this path.
  $description = metadata($topic, 'description', array('no_escape' => true));
} else {
  $title = "{$topic->topicType}: " . metadata($topic, 'display_title');
  $topic->item_type_id = 'collection';
  $titleLink = mcjc_link_to_item($title, $topic);
  $description = metadata($topic, array('Dublin Core','Description'));
}
?>
<div class="record topic grid__item <?php echo $topicClass ?>">
    <div class="item-label tile__label">
        <div class="item-title tile__title"><?php echo $titleLink; ?></div>
        <div class="item-description tile__description">
          <?php echo $description; ?>
        </div>
    </div>
          <div class="item-img tile__image">
            <?php echo record_image($topic,'square_thumbnail', array('alt' => $title)) ?>
          </div>
</div>
<?php endforeach;  ?>
</div>
<?php endif; ?>

<?php echo pagination_links(); ?>

<?php echo foot(); ?>
