<?php 
queue_js_file('lightbox.min', 'javascripts/vendor');
queue_css_file('lightbox');
?>
<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'item show')); ?>

<div id="primary">
    <h1><?php echo metadata('item', array('Dublin Core','Title')); ?></h1>
  <?php
  if (metadata('item', array('Dublin Core', 'Type')) == 'Oral History'):?>
    <div id="item-__oral-history-subtitle">
      <?php echo oral_history_item_subtitle(); ?>
    </div>
  <?php endif; ?>
  <div id="item-description">
    <?php echo metadata('item', array('Dublin Core','Description')); ?>
  </div>

  <!-- Item files -->
  <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">
      <div class="element-text"><?php echo item_image_gallery(); ?></div>
    </div>
  <?php endif; ?>

  <!-- Other metadata -->
  <details id="item-metadata">
    <summary><h2><?php echo __('Details') ?></h2></summary>
    <?php echo all_element_texts('item'); ?>

    <?php if (metadata('item', 'Collection Name')): ?>
      <div id="collection" class="element">
        <h3><?php echo __('Collection'); ?></h3>
        <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
      </div>
    <?php endif; ?>

    <!-- The following prints a citation for this item. -->
    <div id="item-citation" class="element">
      <h3><?php echo __('Citation'); ?></h3>
      <div class="element-text"><?php echo metadata('item','citation',array('no_escape'=>true)); ?></div>
      </div>
  </details>


  <!-- The following prints a list of all tags associated with the item -->
  <?php if (metadata('item', 'has tags')): ?>
    <div id="item-tags" class="element">
      <h3><?php echo __('Tags'); ?></h3>
      <div class="element-text"><?php echo tag_string('item'); ?></div>
    </div>
  <?php endif;?>

  <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
    </ul>

</div> <!-- End of Primary. -->

 <?php echo foot(); ?>