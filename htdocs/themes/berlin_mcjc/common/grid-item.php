<?php
const ORAL_HISTORY_ITEM_TYPE = 4;
const ORAL_HISTORY_CLIP_ITEM_TYPE = 18;
const PROJECT_INTERVIEW_ITEM_TYPE = 19;
const IMAGE_ITEM_TYPE = 6;
const TOPIC_ITEM_TYPE = 20;

set_current_record('item', $item);

if (!isset($masonry)) {
    $masonry = false;
}
if (!isset($title)) {
    $title = metadata($item, 'display_title');
}
if (!isset($url)) {
    $url = record_url($item);
}
if (!isset($description)) {
    $description = metadata(
        $item,
        ['Dublin Core', 'Description'],
        ['snippet' => 300]
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

if (!$description && !$image && isset($noImageText)) {
    $description = $noImageText;
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
    case ORAL_HISTORY_CLIP_ITEM_TYPE:
    case PROJECT_INTERVIEW_ITEM_TYPE:
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

    <?php if (
        ($item->topicType ?? 'Item') === 'Item' &&
        !$isCollection &&
        !stristr($class, 'collection')
    ): ?>
    <?php fire_plugin_hook('public_items_browse_each', [
        'view' => $this,
        'item' => $item,
    ]); ?>
    <?php endif; ?>
</a>
