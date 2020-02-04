<?php queue_css_file('lity', 'all', false, 'lity'); ?>
<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'people show person')); ?>
<?php
    $backButtonText = __('Back to all people');
    $itemClasses = "";
    $bio = false;
    $picture = false;
    if (metadata('item', 'has files')) {
      $itemClasses = " has-picture";
      $picture = mcjc_files_for_item('item', array('imageSize' => 'fullsize', 'linkAttributes' => array('data-lity' => ""), 'show' => TRUE));
    }
    if (metadata('item', array('Dublin Core', 'Description'))) {
      $itemClasses .= " has-bio";
      $bio = metadata('item', array('Dublin Core', 'Description'));
    }
?>
<div class="primary person<?php echo $itemClasses?>">
  <h1><?php echo metadata('item', array('Dublin Core','Title')); ?></h1>

  <!-- Item files -->
  <?php if ($picture): ?>
    <div id="picture" class="element">
      <div class="item-images"><?php echo $picture; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($bio): ?>
  <div class="bio">
    <?php echo $bio ?>
  </div>
  <?php endif; ?>

</div> <!-- End of Primary. -->

<?php if (count($oral_history_items)): ?>
<div class="oral-histories">
  <?php foreach (loop('oral_history_items') as $item): ?>
    <?php echo common('person-page-oral-history-item', array('item' => $item)); ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (count($related_items)): ?>
<div class="related-items">
  <?php foreach (loop('related_items') as $item): ?>
    <?php echo common('person-page-related-item', array('item' => $item)); ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (count($in_the_community_items)): ?>
<div class="in-the-community">
  <h3><?php echo __('In the community'); ?></h3>
  <?php foreach (loop('in_the_community_items') as $item): ?>
    <?php echo common('person-page-in-the-community-item', array('item' => $item)); ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<a class="button back" href="<?php echo $this->url(array(), 'peopleDefault'); ?>"><?php echo $backButtonText; ?></a>

<?php echo js_tag('lity', 'lity'); ?>
<?php echo foot(); ?>
