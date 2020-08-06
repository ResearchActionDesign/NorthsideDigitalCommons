<?php
$query = $_GET['query'] ?? '';
$pageTitle = trim(__('%s results for "%s"', $total_results, $query));
echo head(['title' => $pageTitle, 'bodyclass' => 'search']);
$searchRecordTypes = get_search_record_types();
?>
<div id="search-results">
    <div class="search-results--header">
        <h1 class="search-results--title"><?php echo $pageTitle; ?></h1>
        <div class="search-results--form">
          <?php echo search_form(['expanded' => true]); ?>
        </div>
    </div>
    <?php if ($total_results): ?>
    <div class="search-results-body">
      <?php $filter = new Zend_Filter_Word_CamelCaseToDash(); ?>
      <?php foreach (loop('search_texts') as $searchText): ?>
        <?php $record = get_record_by_id(
            $searchText['record_type'],
            $searchText['record_id']
        ); ?>
        <?php
        $recordType = $searchText['record_type'];
        $detailed_record_type =
            $recordType === 'Item'
                ? metadata($record, ['Dublin Core', 'Type'])
                : $recordType;
        ?>
        <?php set_current_record($recordType, $record); ?>
          <div class="search-result <?php echo strtolower(
              $filter->filter($recordType)
          ); ?>">
                  <div class="item-img">
                    <?php if ($recordImage = record_image($recordType)): ?>
                    <a href="<?php echo mcjc_url_for_item($record); ?>">
                      <?php echo $recordImage; ?>
                    </a>
                    <?php endif; ?>
                  </div>
                  <div class="item-info">
                  <span class="item-type">
                    <?php echo $detailed_record_type; ?>
                  </span>
                      <h2 class="item-title"><?php echo mcjc_link_to_item(
                          $searchText['title']
                              ? $searchText['title']
                              : '[Unknown]',
                          $record
                      ); ?>
                      </h2>
                <?php if (
                    $description = metadata(
                        $record,
                        ['Dublin Core', 'Description'],
                        ['snippet' => 250]
                    )
                ): ?>
                    <span class="item-description">
                      <?php echo $description; ?>
                    </span>
                <?php endif; ?>
          </div>
          </div>
      <?php endforeach; ?>
    </div>
<?php echo pagination_links(); ?>
<?php else: ?>
    <div id="no-results">
        <p><?php echo __(
            'No results were found. Please check your spelling or try searching for a different term'
        ); ?></p>
        <a href="/people" class="homepage-menu__item__button"><?php echo __(
            "Browse all people"
        ); ?></a>
        <a href="/topics" class="homepage-menu__item__button"><?php echo __(
            "Browse all topics"
        ); ?></a>
    </div>
<?php endif; ?>
</div>
<?php echo foot(); ?>
