<?php
$pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<h1><?php echo $pageTitle; ?></h1>
<?php echo search_filters(); ?>
<?php if ($total_results): ?>
  <?php echo pagination_links(); ?>
  <table id="search-results">
    <thead>
    <tr>
      <th class="table-header--1"><?php echo __('Record');?></th>
      <th class="table-header--2"><?php echo __('Media');?></th>
      <th class="table-header--3"><?php echo __('Type');?></th>
    </tr>
    </thead>
    <tbody>
    <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
    <?php foreach (loop('search_texts') as $searchText): ?>
      <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
      <?php $recordType = $searchText['record_type']; ?>
      <?php set_current_record($recordType, $record); ?>
      <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
        <td>
          <h2 class="search-item__title"><a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></h2>
        </td>
        <td>
          <?php if ($recordImage = record_image($recordType)): ?>
            <?php if ($recordType == 'Item'): echo mcjc_files_for_item(); ?>
            <?php else: echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
            <?php endif; ?>
          <?php endif; ?>
        </td>
        <td>
          <?php echo $searchRecordTypes[$recordType]; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php echo pagination_links(); ?>
<?php else: ?>
  <div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
  </div>
<?php endif; ?>
<?php echo foot(); ?>
