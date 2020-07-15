<?php
if (!isset($title)) {
    $title = metadata($item, 'display_title');
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
            'square_thumbnail',
            ['alt' => $title],
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
    <div class="item-title"><?php echo $title; ?></div>
</a>
