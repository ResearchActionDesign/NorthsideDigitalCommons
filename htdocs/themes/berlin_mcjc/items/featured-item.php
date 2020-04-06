<div class="item record featured-item">
  <?php
  $title = metadata($item, 'display_title');
  $description = metadata(
      $item,
      ['Dublin Core', 'Description'],
      ['snippet' => 300]
  );
  $url = record_url($item, null, false, []);
  $imageUri = '';
  if (metadata($item, 'has files')) {
      $imageFile = $item->getFile(0);
      if ($imageFile->hasThumbnail()) {
          $imageUri = $imageFile->getWebPath('fullsize');
      } else {
          $itemMarkup = $this->fileMarkup($imageFile);
      }
  }
  ?>
  <a href="<?php echo $url; ?>">
    <?php if ($imageUri != ''): ?>
    <div class="featured-item__background-image" style="background-image: url(<?php echo $imageUri; ?>);"></div>
    <?php else:echo $itemMarkup;endif; ?>
  </a>
  <div class="featured-item__text">
    <h3><?php echo link_to($item, 'show', $title); ?></h3>
    <?php if ($description): ?>
      <p class="item-description"><?php echo $description; ?></p>
      <?php echo link_to($item, 'show', 'Learn more'); ?>
    <?php endif; ?>
  </div>
</div>
