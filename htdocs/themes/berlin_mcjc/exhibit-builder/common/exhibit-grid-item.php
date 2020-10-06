<?php
set_current_record('item', $item);
$attachment = $item->getAllAttachments()[0] ?? false;

if (!isset($masonry)) {
    $masonry = false;
}
if (!isset($title)) {
    $title = metadata($item, 'title');
}
if (!isset($description)) {
    $description = $attachment['caption'] ?? false;
}
if (!isset($url)) {
    $url = record_url($item);
}

if (!isset($image)) {
    $baseImageWidth = '300px'; // Set to Derivative Images width.

    $image_attrs = [
        'width' => $baseImageWidth,
        'alt' => '',
        'loading' => 'lazy',
        'height' => $baseImageWidth,
    ];
    if (!$masonry) {
        $image_attrs['height'] = $baseImageWidth;
        $image_attrs['loading'] = 'lazy';
    }

    if ($attachment) {
        $item = $attachment->getItem();
        $file = $attachment->getFile();
        if ($file) {
            $image = file_image(
                $masonry ? 'thumbnail' : 'square_thumbnail',
                [
                    'alt' => metadata(
                        $item,
                        ['Dublin Core', 'Title'],
                        ['no_escape' => true]
                    ),
                ],
                $file
            );
        }
    }
}
if (
    !isset($headingLevel) ||
    array_search($headingLevel, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) === false
) {
    $headingLevel = 'h3';
}
?>
<a href="<?php echo $url; ?>" class="item record related-item tile<?php
echo $image ? " has-picture" : "";
echo $description ? " has-description" : "";
echo $class ?? false ? " {$class}" : "";
?>">
    <?php if ($image): ?>
      <div class="item-img">
        <?php echo $image; ?>
      </div>
    <?php endif; ?>
      <div class="item-meta">
          <<?php echo $headingLevel; ?>><?php echo $title; ?></<?php echo $headingLevel; ?>>
          <?php if ($description): ?>
            <div class="item-description">
              <?php echo $description; ?>
            </div>
        <?php endif; ?>
      </div>
    <div class="item-title" aria-hidden="true"><?php echo $title; ?></div>
</a>
