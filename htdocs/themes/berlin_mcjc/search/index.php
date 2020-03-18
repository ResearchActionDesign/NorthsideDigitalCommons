<?php
$pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
echo head(['title' => $pageTitle, 'bodyclass' => 'search']);
$searchRecordTypes = get_search_record_types();
?>
<h1><?php echo $pageTitle; ?></h1>
<?php echo search_filters(); ?>
<?php if ($total_results): ?>

<div id="search-results">
  <!-- <div class="search-results-headers">
    
      <span class="table-header--1"><?php echo __('Record');?></span>
      <span class="table-header--2"><?php echo __('Media');?></span>
      <span class="table-header--3"><?php echo __('Type');?></span>
    
    </div> -->
  <div class="search-results-body">
    <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
    <?php foreach (loop('search_texts') as $searchText): ?>
    <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
    <?php $recordType = $searchText['record_type']; ?>
    <?php set_current_record($recordType, $record); ?>

    <div class="search-results-rows" class="<?php echo strtolower($filter->filter($recordType)); ?>">
      <span class="results-row-img">
        <?php if ($recordImage = record_image($recordType)): ?>
        <?php echo mcjc_files_for_item(strtolower($recordType)); ?>
        <?php endif; ?>
      </span>
      <div class="results-row-info">
        <span>
          <?php echo $searchRecordTypes[$recordType]; ?>
        </span>
        <span>
          <h2 class="search-item__title"><a
              href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
          </h2>
        </span>

      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
  <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>