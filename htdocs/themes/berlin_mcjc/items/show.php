<?php
/**
 * Note -- This file renders oral history & image items. "Person" items are rendered by people/items/show.php.
 */

queue_css_file('lity', 'all', false, 'lity');
$itemTitle = metadata('item', 'display_title');
$itemType = metadata('item', array('Dublin Core', 'Type'));
$itemTypeClass = str_replace(' ', '-', strtolower($itemType));
$backButtonText = __('Back to all people');
$itemClasses = "";
$description = false;
$picture = false;
if (metadata('item', 'has files')) {
  $itemClasses = " has-picture";
  $picture = item_image('fullsize', array('alt' => $itemTitle));
}
if (metadata('item', array('Dublin Core', 'Description'))) {
  $itemClasses .= " has-description";
  $description = metadata('item', array('Dublin Core', 'Description'));
}
?>
<?php echo head(array('title' => $itemTitle, 'bodyclass' => "items show {$itemTypeClass}")); ?>
<div class="primary <?php echo "{$itemTypeClass} {$itemClasses}" ?>">
    <div class="title-block">
        <span class="item-type"><?php echo $itemType; ?></span>
        <h1><?php echo $itemTitle; ?></h1>
      <?php if ($itemType === 'Oral History'): ?>
          <span class="subtitle">
          <?php echo oral_history_item_subtitle(); ?>
        </span>
      <?php endif; ?>
    </div>
  <?php if ($picture): ?>
      <div id="picture" class="element">
          <div class="item-images"><?php echo $picture; ?>
          </div>
      </div>
  <?php endif; ?>
  <?php if ($description): ?>
      <div class="description">
        <?php echo $description ?>
      </div>
  <?php endif; ?>
  <?php if ($sohp_url = mcjc_get_linked_sohp_interview()): ?>
      <div class="item-metadata sohp">
          <a target="_blank" href="<?php echo html_escape($sohp_url) ?>">View Details at Southern Oral History Program
              website</a>
      </div>
  <?php elseif (metadata('item', 'has files')): ?>
      <div id="itemfiles" class="element">
          <div class="item-images"><?php echo mcjc_files_for_item('item', array('imageSize' => 'fullsize', 'linkAttributes' => array('data-lity' => ""), 'show' => TRUE)); ?>
          </div>
      </div>
  <?php endif; ?>
    <div id="item-tags" class="element">
        <span class="element-title"><?php echo __('Tags: '); ?></span>
        <span class="element-text"><?php echo tag_string('item'); ?></span>
    </div>
    <div class="details">
        <div id="item-detail" class="element">
            <span class="element-text"><?php echo mcjc_element_metadata_paragraph($item); ?></span>
        </div>
        <div id="item-citation" class="element">
            <span class="element-title"><?php echo __('Citation: '); ?></span>
            <span class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></span>
        </div>
    </div>
</div>
<div class="explore-grid depicted">
    <h2><?php echo __('In this ') . $itemType; ?></h2>
  <?php foreach (loop('depicted') as $relatedItem): ?>
    <?php echo common('related-item', array('item' => $relatedItem, 'class' => 'depicted')); ?>
  <?php endforeach; ?>
</div>
<div class="explore-grid related-items">
    <h2><?php echo __('More to explore'); ?></h2>
  <?php foreach (loop('related_items') as $relatedItem): ?>
    <?php echo common('related-item', array('item' => $relatedItem, 'class' => 'related-item')); ?>
  <?php endforeach; ?>
</div>

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
<a class="button back" href="<?php echo $this->url(array(), 'peopleDefault'); ?>"><?php echo $backButtonText; ?></a>

<?php echo js_tag('lity', 'lity'); ?>
<?php echo foot(); ?>
