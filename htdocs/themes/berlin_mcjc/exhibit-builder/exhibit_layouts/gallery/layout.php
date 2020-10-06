<?php
$showcasePosition = isset($options['showcase-position'])
    ? html_escape($options['showcase-position'])
    : 'none';
$showcaseFile = $showcasePosition !== 'none' && !empty($attachments);
?>
<?php if ($showcaseFile): ?>
<div class="gallery-showcase">
    <?php
    $attachment = array_shift($attachments);
    echo $this->exhibitAttachment($attachment, ['imageSize' => 'fullsize']);
    ?>
</div>
<?php else: ?>
  <?php echo $text; ?>
<?php endif; ?>

<div class='exhibit-gallery'>
      <?php foreach ($attachments as $attachment): ?>
      <div class="exhibit-gallery-item">
        <?php $item = $attachment->getItem(); ?>
        <?php if (metadata($item, 'has files')): ?>
          <?php echo mcjc_render_oral_history_players(
              $item,
              ['class' => 'item-file'],
              ['limit' => 1]
          ); ?>
        <?php echo get_view()->exhibitAttachmentCaption($attachment); ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
</div>
