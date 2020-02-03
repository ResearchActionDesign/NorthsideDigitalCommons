<?php queue_css_file('lity', 'all', false, 'lity'); ?>
<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'people show')); ?>

<div id="primary">
  <h1><?php echo metadata('item', array('Dublin Core','Title')); ?></h1>

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

  <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


  <ul class="item-pagination navigation">
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
  </ul>

</div> <!-- End of Primary. -->

<?php echo js_tag('lity', 'lity'); ?>
<?php echo foot(); ?>
