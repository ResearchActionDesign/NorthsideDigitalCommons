<?php $itemTitle = metadata($item,'display_title'); ?>
<div class="related-item tile">
    <div class="item-label tile__label">
      <div class="item-title tile__title"><?php echo link_to_item($itemTitle, array('class'=>'permalink')); ?></div>
      <div class="item-description tile__description">
        <?php echo metadata($item, array('Dublin Core','Description')); ?>
      </div>
    </div>
    <?php if (metadata($item, 'has files')): ?>
      <div class="item-img tile__image">
        <?php echo item_image('square_thumbnail', array('alt' => $itemTitle), 0, $item) ?>
      </div>
    <?php endif; ?>
</div>
