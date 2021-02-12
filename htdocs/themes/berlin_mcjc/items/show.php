<?php
/**
 * Note -- This file renders oral history & image items. "Person" items are rendered by people/items/show.php.
 */
$itemTitle = metadata('item', 'display_title');
$item_type = get_view()->getRecordTypeIdentifier($item);
switch ($item_type) {
    case 'Still Image':
        $itemTypeRaw = 'Image';
        break;
    case 'Project Interviews':
    case 'Oral History':
    case 'Oral History Clip':
        $itemTypeRaw = 'Oral History';
        break;
    default:
        $itemTypeRaw = 'Document';
        break;
}

$itemTypeDict = [
    'Still Image' => 'Image',
];

$itemType = array_key_exists($itemTypeRaw, $itemTypeDict)
    ? $itemTypeDict[$itemTypeRaw]
    : $itemTypeRaw;

$itemTypeParentDict = [
    'Image' => ['Images' => '/images'],
    'Oral History' => ['Oral Histories' => '/oral-histories'],
    'Document' => ['Documents' => '/documents'],
];
$itemTypePlural =
    array_key_first($itemTypeParentDict[$itemType] ?? []) ?? 'Documents';

$itemTypeClass = str_replace(' ', '-', strtolower($itemType));
$backButtonText = __('Back to all ') . strtolower($itemTypePlural);

$backLink =
    array_values($itemTypeParentDict[$itemType] ?? [])[0] ?? "/documents";
$breadcrumbTrail = array_merge(
    $itemTypeParentDict[$itemType] ?? ['Documents' => '/documents'],
    [$itemTitle]
);

$sohpUrl = false;
// If this is an oral history about a single person, show it as a subpage of their person page instead.
if ($itemType === 'Oral History' && count($depicted_items ?? []) === 1) {
    $sohpUrl = mcjc_get_linked_sohp_interview();
    $depictedPerson = reset($depicted_items);
    $personName = metadata($depictedPerson, 'display_title');
    $personUrl = record_url($depictedPerson);
    $backButtonText = __('Back to ') . $personName;
    $backLink = $personUrl;
    $breadcrumbTrail = [
        'People' => '/people',
        $personName => $personUrl,
        $itemTitle,
    ];
}

$itemClasses = '';
$description = false;
$picture = false;
$isPdf = '';
if (metadata('item', 'has files')) {
    $itemClasses = ' has-picture';
    $picture = mcjc_item_image('fullsize', ['alt' => $itemTitle]);

    if (!$picture && $itemTypeRaw === 'Document') {
        // Check if this is a pdf document and set classes accordingly.
        $imageFile = $item->getFile(0);
        if ($imageFile->mime_type === 'application/pdf') {
            $isPdf = 'is-pdf';
        }
    }
}
if (metadata('item', ['Dublin Core', 'Description'])) {
    $itemClasses .= ' has-description';
    $description = metadata('item', ['Dublin Core', 'Description']);
}
?>
<?php echo head([
    'title' => $itemTitle,
    'bodyclass' => "items show {$itemTypeClass}",
    'description' => $description,
]); ?>

<?php echo common('breadcrumbs', [
    'trail' => $breadcrumbTrail,
]); ?>
<div class="primary <?php echo "{$itemTypeClass} {$itemClasses} {$isPdf}"; ?>">
        <div class="item-content">
            <span class="item-type"><?php echo $itemType; ?></span>
            <h1><?php echo $itemTitle; ?></h1>
            <?php if ($itemType === 'Oral History'): ?>
            <span class="subtitle">
            <?php echo oral_history_item_subtitle(); ?>
            </span>
            <?php endif; ?>
            <?php if ($description): ?>
              <p class="description">
                <?php echo $description; ?>
              </p>
            <?php elseif (
                ($missing_caption = get_theme_option('missing_caption_text')) &&
                $itemTypeRaw !== 'Oral History'
            ): ?>
            <p class="description">
                <?php echo $missing_caption; ?>
            </p>
          <?php endif; ?>
          <?php echo common('share_icons', [
              'url' => absolute_url(current_url()),
              'title' => $itemTitle,
          ]); ?>
        </div>
        <div class="item-sidebar<?php echo $isPdf ? ' is-pdf' : ''; ?>">
        <?php if ($picture): ?>
        <div id="picture" class="element">
            <div class="item-images"><?php echo $picture; ?></div>
        </div>
        <?php endif; ?>
  <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">
        <div class="item-images">
          <?php echo mcjc_files_for_item('item', [
              'imageSize' => 'fullsize',
              'linkAttributes' => ['data-lity' => ''],
              'show' => true,
          ]); ?>
        </div>
    </div>
  <?php elseif ($sohpUrl): ?>
    <div class="item-metadata sohp element">
        <a href="<?php echo html_escape(
            $sohpUrl
        ); ?>">View More Details at Southern Oral History Program
            website</a>
    </div>
    <?php endif; ?>
</div>
</div>

  <?php
  $tags = tag_string('item');
  $metadata_paragraph = mcjc_element_metadata_paragraph($item);
  $rights = metadata($item, ['Dublin Core', 'Rights']);
  $citation = metadata('item', 'citation', [
      'no_escape' => true,
  ]);
  ?>
  <div class="tags-container">
      <?php if ($tags): ?>
    <p id="item-tags" class="element">
      <span class="element-title"><?php echo __('Tags: '); ?></span>
      <span class="element-text"><?php echo $tags; ?></span>
    </p>
      <?php endif; ?>
    <div class="details">
        <?php if ($metadata_paragraph): ?>
      <p id="item-detail" class="element">
        <span class="element-text"><?php echo $metadata_paragraph; ?></span>
      </p>
        <?php endif; ?>
        <?php if ($citation): ?>
      <p id="item-citation" class="element">
        <span class="element-title"><?php echo __('Citation: '); ?></span>
        <span class="element-text"><?php echo $citation; ?></span>
      </p>
        <?php endif; ?>
      <?php if ($rights && $rights !== 'Open for research.'): ?>
          <p id="item-rights" class="element"><span class="element-title"><?php echo __(
              'Rights: '
          ); ?></span><span class="element-text"><?php echo $rights; ?></span></p>
      <?php endif; ?>
      <?php if ($sohpUrl): ?>
        <p class="element"><a href="<?php echo $sohpUrl; ?>"><i class="fa fa-external-link"></i>View this interview on the Southern Oral History Program
                website</a></p>
        <?php endif; ?>
    </div>
  </div>
<div class="background-container">

<?php fire_plugin_hook('public_items_show', [
    'view' => $this,
    'item' => $item,
]); ?>

<?php echo common('respond-bar'); ?>

<?php if (!empty($depicted_items)): ?>
<div class="explore-grid grid-container depicted">
  <h2><?php echo __('In this ') . $itemType; ?></h2>
  <div class="grid-items <?php echo count($depicted_items) < 3
      ? 'grid-count-' . count($depicted_items)
      : ''; ?>">
    <?php foreach (loop('depicted_items') as $relatedItem): ?>
    <?php echo common('grid-item', [
        'item' => $relatedItem,
        'class' => 'depicted',
    ]); ?>
    <?php endforeach; ?>
  </div>
</div>

<?php endif; ?>
<?php if (!empty($related_items) || !empty($collection)): ?>
<div class="explore-grid masonry-grid grid-container related-items ">
  <h2><?php echo __('More to explore'); ?></h2>
  <div class="grid-items">
    <?php if (!empty($collection)): ?>
    <?php
    $collectionTitle =
        __('Collection') . ': ' . metadata($collection, 'display_title');
    echo common('grid-item', [
        'item' => $collection,
        'class' => 'related-item',
        'title' => $collectionTitle,
        'isCollection' => true,
    ]);
    ?>
    <?php endif; ?>
    <?php foreach (loop('related_items') as $relatedItem): ?>
    <?php echo common('grid-item', [
        'item' => $relatedItem,
        'class' => 'related-item',
        'masonry' => true,
    ]); ?>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
<div class="back-container">
  <a class="button back" href="<?php echo $backLink; ?>"><?php echo $backButtonText; ?></a>
</div>
</div>

<?php echo foot(); ?>
