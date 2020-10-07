<?php
$title = metadata($item, ['Dublin Core', 'Title']);

// Workaround to situationsw here interview title matches person name
if ($title === $person) {
    $title = oral_history_item_subtitle($item);
}
?>
<div class="oral-history">
  <h2><?php echo $title; ?></h2>

  <!-- Item files -->
  <?php if (metadata($item, 'has files')): ?>
      <?php echo mcjc_render_oral_history_players(
          null,
          $item,
          ['class' => 'item-file'],
          ['title' => $title, 'limit' => 1]
      ); ?>
  <?php endif; ?>

  <div class='item-description'>
    <?php echo metadata($item, ['Dublin Core', 'Description']); ?>
  </div>

  <?php if ($sohp_url = mcjc_get_linked_sohp_interview($item)): ?>
    <div class="item-metadata sohp">
      <a target="_blank" href="<?php echo html_escape(
          $sohp_url
      ); ?>"><?php echo __(
    'View Details at Southern Oral History Program website'
); ?></a>
    </div>
  <?php endif; ?>

  <?php echo link_to_item(
      __('View oral history details'),
      [
          'class' => 'button item-link',
      ],
      'show',
      $item
  ); ?>
</div>
