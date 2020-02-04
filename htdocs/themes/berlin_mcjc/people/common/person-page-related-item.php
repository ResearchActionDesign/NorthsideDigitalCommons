<div class="related-item">
    <div class="item-label">
      <div class="item-title"><?php echo link_to_item(metadata($item, array('Dublin Core', 'Title')), array('class'=>'permalink')); ?></div>
      <div class="item-description">
        <?php echo metadata($item, array('Dublin Core','Description')); ?>
      </div>
    </div>
    <?php if (metadata($item, 'has files')): ?>
      <div class="item-img">
        <div class="item-images"><?php echo mcjc_files_for_item('item', array(), array('class' => 'item-file'), $item); ?></div>
      </div>
    <?php endif; ?>
</div>
