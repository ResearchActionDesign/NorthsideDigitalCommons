<?php
const ORAL_HISTORY_ITEM_TYPE = 4;
const IMAGE_ITEM_TYPE = 6;

if (!isset($masonry)) {
    $masonry = false;
}
if (!isset($title)) {
    $title = metadata($item, 'display_title');
}
if (!isset($url)) {
    $url = mcjc_url_for_item($item);
}
if (!isset($description)) {
    $description = metadata(
        $item,
        ['Dublin Core', 'Description'],
        ['snippet' => 250]
    );
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
    $image = mcjc_record_image(
        $item,
        $masonry ? 'thumbnail' : 'square_thumbnail',
        $image_attrs
    );
}
if (
    !isset($headingLevel) ||
    array_search($headingLevel, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) === false
) {
    $headingLevel = 'h3';
}

$icon = '';
switch ($item->item_type_id) {
    case ORAL_HISTORY_ITEM_TYPE:
        $icon = '<i class="fa fa-microphone" aria-label="Oral History"></i>';
        break;
    case IMAGE_ITEM_TYPE:
        $icon = '<i class="fa fa-camera" aria-label="Photograph"></i>';
        break;
    default:
        break;
}

if ($icon) {
    $title = $icon . '&nbsp;' . $title;
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