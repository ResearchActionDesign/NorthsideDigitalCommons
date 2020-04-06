<?php if ($subjectRelations || $objectRelations): ?>

<?php
$isPerson = metadata('item', ['Dublin Core', 'Type']) === 'Person';
$currentRecord = get_current_record('item');
// Load related items into array.
$relatedItemIds = [];
foreach ($subjectRelations as $subjectRelation) {
    $relatedItemIds[] = $subjectRelation['object_item_id'];
    // Pull second degree relations as well.
    $secondDegreeRelations = get_db()
        ->getTable('ItemRelationsRelation')
        ->findByObjectItemId($subjectRelation['object_item_id']);
    foreach ($secondDegreeRelations as $relation) {
        // Don't add an item to its own related items view.
        if ($relation['subject_item_id'] != $currentRecord->id) {
            $relatedItemIds[] = $relation['subject_item_id'];
        }
    }
}

foreach ($objectRelations as $objectRelation) {
    $relatedItemIds[] = $objectRelation['subject_item_id'];
}

$relatedItemIds = array_unique($relatedItemIds);
?>
<div id="item-relations-display-item-relations">
  <!-- TODO: stub H2 tag here for people is just a space filler until CSS can be properly updated for the person case -->
  <h2><?php echo __($isPerson ? '' : 'Related Content'); ?></h2>
    <table>
      <?php foreach ($relatedItemIds as $itemId): ?>
        <?php
        $item = get_record_by_id('item', $itemId);
        if (is_null($item)) {
            continue;
        }
        ?>
        <div class="item entry">
          <div class="item-meta">
            <div class="citation">
              <?php
              $item_type = metadata($item, ['Dublin Core', 'Type']);
              if ($item_type != '') {
                  $item_type = ' (' . $item_type . ')';
              }
              echo mcjc_link_to_item(
                  '<h3>' .
                      metadata($item, 'display_title') .
                      $item_type .
                      '</h3>',
                  $item
              );
              ?>
            </div>
            <?php if (
                $description = metadata(
                    $item,
                    ['Dublin Core', 'Description'],
                    ['snippet' => 325]
                )
            ): ?>
              <div class="item-description">
                <?php echo $description; ?>
              </div>
            <?php endif; ?>
            <?php if (metadata($item, 'has thumbnail')): ?>
              <div class="item-img">
                <div class="item-images"><?php echo mcjc_files_for_item(
                    'item',
                    [],
                    ['class' => 'item-file'],
                    $item
                ); ?></div>
              </div>
            <?php endif; ?>
            <?php if (metadata($item, 'has tags')): ?>
              <div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
                  <?php echo tag_string($item); ?></p>
              </div>
            <?php endif; ?>

            <?php fire_plugin_hook('public_items_browse_each', [
                'view' => $this,
                'item' => $item,
            ]); ?>

          </div><!-- end class="item-meta" -->
        </div><!-- end class="item entry" -->
      <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>
