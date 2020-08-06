<?php
const ORAL_HISTORY_ITEM_TYPE = 4;
const IMAGE_ITEM_TYPE = 6;

if (!isset($title)) {
    $title = metadata($item, 'display_title');
}
if (!isset($masonry)) {
    $masonry = false;
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

$description = metadata(
    $item,
    ['Dublin Core', 'Description'],
    ['snippet' => 250]
);

$url = mcjc_url_for_item($item);

$hasFiles = ($isCollection ?? false) || metadata($item, 'has files');
?>
<a href="<?php echo $url; ?>" class="item record related-item tile<?php
echo $hasFiles ? " has-picture" : "";
echo $description ? " has-description" : "";
echo $class ? " {$class}" : "";
?>">
      <div class="item-img">
        <?php echo item_image(
            $masonry ? 'thumbnail' : 'square_thumbnail',
            ['alt' => ''],
            0,
            $item
        ); ?>
      </div>
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
