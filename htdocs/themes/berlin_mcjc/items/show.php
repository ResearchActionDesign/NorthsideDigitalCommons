<?php queue_css_file('lity', 'all', false, 'lity'); ?>
<?php $itemTitle = metadata('item', 'display_title'); ?>
<?php echo head(array('title' => $itemTitle,'bodyclass' => 'items show')); ?>
<?php $isPerson = (metadata('item', array('Dublin Core', 'Type')) === 'Person'); ?>
<div id="primary">
    <h1><?php echo $itemTitle; ?></h1>
  <?php
  if (metadata('item', array('Dublin Core', 'Type')) == 'Oral History'):?>
    <div id="item-__oral-history-subtitle">
      <?php echo oral_history_item_subtitle(); ?>
    </div>
  <?php endif; ?>

  <!-- Item files -->
  <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">
      <div class="item-images"><?php echo mcjc_files_for_item('item', array('imageSize' => 'fullsize', 'linkAttributes' => array('data-lity' => ""), 'show' => TRUE)); ?>
      </div>
    </div>
  <?php endif; ?>

  <div id="item-description">
    <?php echo metadata('item', array('Dublin Core','Description')); ?>
  </div>

  <?php if ($sohp_url = mcjc_get_linked_sohp_interview()): ?>
  <div class="item-metadata sohp">
    <a target="_blank" href="<?php echo html_escape($sohp_url) ?>">View Details at Southern Oral History Program website</a>
  </div>
    <?php else: ?>

    <!-- Only display "View Details" for non-person records -->
    <?php if (!$isPerson): ?>
          <!-- Other metadata -->
    <details id="item-metadata">
        <summary><h2><?php echo __('View Details') ?></h2></summary>
    <!-- Items metadata -->
    <div id="item-metadata">
        <?php echo all_element_texts('item'); ?>
    </div>

    <?php if(metadata('item','Collection Name')): ?>
      <div id="collection" class="element">
        <h3><?php echo __('Collection'); ?></h3>
        <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
      </div>
   <?php endif; ?>

     <!-- The following prints a list of all tags associated with the item -->
    <?php if (metadata('item','has tags')): ?>
    <div id="item-tags" class="element">
        <h3><?php echo __('Tags'); ?></h3>
        <div class="element-text"><?php echo tag_string('item'); ?></div>
    </div>
    <?php endif;?>

    <!-- The following prints a citation for this item. -->
    <div id="item-citation" class="element">
        <h3><?php echo __('Citation'); ?></h3>
        <div class="element-text"><?php echo metadata('item','citation',array('no_escape'=>true)); ?></div>
    </div>
    </details>
    <?php endif; ?>
  <?php endif; ?>
       <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
    </ul>

</div> <!-- End of Primary. -->

<?php echo js_tag('lity', 'lity'); ?>
<?php echo foot(); ?>
