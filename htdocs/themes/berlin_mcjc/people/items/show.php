<?php queue_css_file('lity', 'all', false, 'lity'); ?>
<?php $itemTitle = metadata('item', 'display_title'); ?>
<?php echo head([
    'title' => $itemTitle,
    'bodyclass' => 'people show person',
]); ?>
<?php
$backButtonText = __('Back to all people');
$itemClasses = "";
$bio = false;
$picture = false;
if (metadata('item', 'has files')) {
    $itemClasses = " has-picture";
    $picture = item_image('fullsize', ['alt' => $itemTitle]);
}
if (metadata('item', ['Dublin Core', 'Description'])) {
    $itemClasses .= " has-bio";
    $bio = metadata('item', ['Dublin Core', 'Description']);
}
?>
<div class="primary person<?php echo $itemClasses; ?>">

  <h1 class='person title'><?php echo $itemTitle; ?></h1>

  <!-- Item files -->
  <?php if ($picture): ?>
    <div id="picture" class="element">
      <div class="item-images"><?php echo $picture; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($bio): ?>
  <div class="bio">
    <?php echo $bio; ?>
  </div>
  <?php endif; ?>

</div> <!-- End of Primary. -->

<?php if (count($oral_history_items)): ?>
<div class="oral-histories">
  <?php foreach (loop('oral_history_items') as $oralHistoryItem): ?>
    <?php echo common('person-page-oral-history-item', [
        'item' => $oralHistoryItem,
        'person' => $itemTitle,
    ]); ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php echo common('respond-bar'); ?>

<?php if (count($related_items)): ?>
  <div class="browse masonry-grid grid-container related-items">
      <h2><?php echo $itemTitle; ?> also appears in...</h2>
    <div class='grid-items'>
    <?php foreach (loop('related_items') as $relatedItem): ?>
      <?php echo common('grid-item', [
          'item' => $relatedItem,
          'masonry' => true,
      ]); ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (count($in_the_community_items)): ?>
<div class="browse in-the-community">
  <div class="community-content grid-container">
    <h2><?php echo __('More to explore'); ?></h2>
    <div class='grid-items'>
            <?php foreach (
                loop('in_the_community_items')
                as $inTheCommunityItem
            ): ?>
              <?php $loopItemTitle = metadata($inTheCommunityItem, [
                  'Dublin Core',
                  'Title',
              ]); ?>
              <?php echo common('grid-item', [
                  'item' => $inTheCommunityItem,
                  'class' => 'in-the-community',
                  'title' => "{$inTheCommunityItem->inTheCommunity}: {$loopItemTitle}",
                  'isCollection' =>
                      $inTheCommunityItem->inTheCommunity === 'Collection',
              ]); ?>
            <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="back-container">
  <a class="button back" href="<?php echo $this->url(
      [],
      'peopleDefault'
  ); ?>"><?php echo $backButtonText; ?></a>
</div>
<?php echo js_tag('lity', 'lity'); ?>
<?php echo foot(); ?>
