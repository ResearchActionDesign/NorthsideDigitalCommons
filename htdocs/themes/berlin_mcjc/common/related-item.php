<?php
if (!isset($title)) {
    $title = metadata($item, 'display_title');
}

$hasFiles = $isCollection || metadata($item, 'has files');
?>
<div class="related-item tile<?php echo $class && " {$class}"; ?>">
    <div class="item-label tile__label">
      <div class="item-title tile__title"><?php echo mcjc_link_to_item(
          $title,
          $item
      ); ?></div>
      <div class="item-description tile__description">
        <?php echo metadata($item, ['Dublin Core', 'Description']); ?>
      </div>
    </div>
    <?php if ($hasFiles): ?>
      <div class="item-img tile__image">
        <?php echo item_image(
            'square_thumbnail',
            ['alt' => $title],
            0,
            $item
        ); ?>
      </div>
    <?php endif; ?>
</div>
