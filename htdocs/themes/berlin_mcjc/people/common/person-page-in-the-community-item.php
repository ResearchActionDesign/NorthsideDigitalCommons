<?php
$inTheCommunity = '';
if (isset($item->inTheCommunity)) {
    $inTheCommunity = $item->inTheCommunity;
    $itemTitle = $inTheCommunity . ": ";
}
$itemTitle .= metadata($item,'display_title');
$itemHasFiles = ($inTheCommunity === 'Collection') || metadata($item, 'has files');
$itemLink = $inTheCommunity === 'Family' ? people_get_link_to_item($itemTitle, $item) : link_to_item($itemTitle, array('class'=>'permalink'), 'show', $item);
?>
<div class="in-the-community-item tile <?php echo $inTheCommunity; ?>">
  <div class="item-label tile__label">
    <div class="item-title tile__title"><?php echo $itemLink; ?></div>
    <div class="item-description tile__description">
      <?php echo metadata($item, array('Dublin Core','Description')); ?>
    </div>
  </div>
  <?php if ($itemHasFiles): ?>
    <div class="item-img tile__image">
      <?php echo item_image('square_thumbnail', array('alt' => $itemTitle), 0, $item) ?>
    </div>
  <?php endif; ?>
</div>
