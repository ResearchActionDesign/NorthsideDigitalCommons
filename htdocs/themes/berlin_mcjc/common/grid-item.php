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
    $baseImageWidth = '200px'; // Set to Derivative Images width.

    $image_attrs = ['width' => $baseImageWidth, 'alt' => ''];
    if (!$masonry) {
        $image_attrs['height'] = $baseImageWidth;
    }
    $image = record_image(
        $item,
        $masonry ? 'thumbnail' : 'square_thumbnail',
        $image_attrs
    );
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
          <h2><?php echo $title; ?></h2>
          <?php if ($description): ?>
            <div class="item-description">
              <?php echo $description; ?>
            </div>
        <?php endif; ?>
      </div>
    <div class="item-title" aria-hidden="true"><?php echo $title; ?></div>
</a>
