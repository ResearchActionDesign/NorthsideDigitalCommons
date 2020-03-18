<div class="oral-history">
  <h2><?php echo metadata($item, array('Dublin Core','Title')); ?>: <?php echo oral_history_item_subtitle($item); ?></h2>

  <!-- Item files -->
  <?php if (metadata($item, 'has files')): ?>
    <div id="player" class="element">
      <?php echo mcjc_render_oral_history_players($item); ?>
    </div>
  <?php endif; ?>

  <div id="item-description">
    <?php echo metadata($item, array('Dublin Core','Description')); ?>
  </div>

  <?php if ($sohp_url = mcjc_get_linked_sohp_interview($item)): ?>
    <div class="item-metadata sohp">
      <a target="_blank" href="<?php echo html_escape($sohp_url) ?>"><?php echo __('View Details at Southern Oral History Program website'); ?></a>
    </div>
  <?php endif; ?>

  <?php echo link_to_item(__('View oral history details'), array('class' => 'button item-link'), 'show', $item); ?>
</div>