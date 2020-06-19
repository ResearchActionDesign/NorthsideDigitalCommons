<?php
$showcasePosition = isset($options['showcase-position'])
    ? html_escape($options['showcase-position'])
    : 'none';
$showcaseFile = $showcasePosition !== 'none' && !empty($attachments);
$galleryPosition = isset($options['gallery-position'])
    ? html_escape($options['gallery-position'])
    : 'left';
$galleryFileSize = isset($options['gallery-file-size'])
    ? html_escape($options['gallery-file-size'])
    : null;
$captionPosition = isset($options['captions-position'])
    ? html_escape($options['captions-position'])
    : 'center';
?>
<H2>TEST</H2>
<?php if ($showcaseFile): ?>
<div class="gallery-showcase <?php echo $showcasePosition; ?> with-<?php echo $galleryPosition; ?> captions-<?php echo $captionPosition; ?>">
    <?php
    $attachment = array_shift($attachments);
    echo $this->exhibitAttachment($attachment, ['imageSize' => 'fullsize']);
    ?>
</div>
<?php endif; ?>
<div class='grid-container explore-grid related-items'>
    <div class="gallery grid-items <?php if ($showcaseFile || !empty($text)) {
        echo "with-showcase $galleryPosition";
    } ?> captions-<?php echo $captionPosition; ?>">
        <?php echo $this->exhibitAttachmentGallery($attachments, [
            'imageSize item' => $galleryFileSize,
        ]); ?>
</div>
</div>
<?php echo $text; ?>
