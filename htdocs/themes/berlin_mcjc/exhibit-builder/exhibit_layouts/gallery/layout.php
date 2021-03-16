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
    <?php
    $item = $attachment->getItem();
    $file = $attachment->getFile();
    ?>
      <?php if ($item['public']): ?>
      <div class="exhibit-gallery-item">
        <?php
        if ($file) {
            echo mcjc_file_markup($file, $item);
        }
        echo get_view()->exhibitAttachmentCaption($attachment);
        ?>
      </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
