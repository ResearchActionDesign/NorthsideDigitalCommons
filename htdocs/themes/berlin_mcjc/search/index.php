<?php
$query = $_GET['query'] ?? '';
$pageTitle = trim(__('%s results for "%s"', $total_results, $query));
echo head(['title' => $pageTitle, 'bodyclass' => 'search']);
$searchRecordTypes = get_search_record_types();

// Get tag results
$searchTagResults = mcjc_get_matching_tags($query);
usort($searchTagResults, function ($a, $b) {
    return strnatcasecmp($a['name'], $b['name']);
});
?>
<?php echo common('breadcrumbs', [
    'trail' => ['Search'],
]); ?>
<div id="search-results">
    <div class="search-results--header">
        <h1 class="search-results--title"><?php echo $pageTitle; ?></h1>
        <div class="search-results--form">
          <?php echo search_form(['expanded' => true]); ?>
        </div>
    </div>
    <?php if ($total_results): ?>
      <?php
      $filter = new Zend_Filter_Word_CamelCaseToDash();
      array_walk($search_texts, function (&$searchText) use ($query) {
          $record = get_record_by_id(
              $searchText['record_type'],
              $searchText['record_id']
          );
          $searchText['record'] = $record;
          $searchText['display_type'] = $searchText['record_type'];
          switch ($searchText['display_type']) {
              case 'Item':
                  $searchText['display_type'] = metadata($record, [
                      'Dublin Core',
                      'Type',
                  ]);
                  $searchText['description'] = metadata(
                      $searchText['record'],
                      ['Dublin Core', 'Description'],
                      ['snippet' => 250]
                  );
                  if (!$searchText['display_type']) {
                      $searchText['display_type'] = $record->Type->name;
                  }
                  break;
              case 'SimplePagesPage':
                  $searchText['display_type'] = 'Page';
                  $start = mb_stripos($searchText['record']['text'], $query);
                  if ($start) {
                      $searchText['description'] = snippet(
                          $searchText['record']['text'],
                          max($start - 25, 0),
                          $start + 225
                      );
                  }
                  $searchText['description'] = snippet(
                      $searchText['record']['text'],
                      0,
                      250
                  );
                  break;
              case 'ExhibitPage':
              case 'Exhibit':
                  $searchText['display_type'] = 'Exhibit';
                  break;
          }
      });
      $validFilterTypes = array_unique(
          array_map(function ($s) use ($filter) {
              return strtolower(
                  $filter->filter(str_replace(' ', '-', $s['display_type']))
              );
          }, $search_texts)
      );
      sort($validFilterTypes);
      $filterTypesDisplay = [
          'oral-history' => 'Oral Histories',
          'person' => 'People',
          'document' => 'Documents',
          'still-image' => 'Still Images',
          'collection' => 'Collections',
          'theme' => 'Themes',
          'page' => 'Pages',
          'exhibit' => 'Exhibits',
      ];
      ?>
    <div class="filter_container">
        <div class="filter" id="grid__filter">
            <span class="grid__filter__title">I want to see:</span>
            <?php foreach ($validFilterTypes as $filterType): ?>
            <span class="grid__filter__option"><label
                        class="filter-titles">
                    <input class="checkbox" type="checkbox" data-filter="<?php echo $filterType; ?>" id="grid-filter-<?php echo $filterType; ?>">
                  <?php echo $filterTypesDisplay[$filterType] ?? $filterType; ?>
                    </input>
                </label></span>
           <?php endforeach; ?>
          </div>
    </div>
    <?php endif; ?>
    <div class="search-results--body">
      <?php if (count($searchTagResults)): ?>
      <div class="search-results--tags">
          <p>Your search also matched the following tags:</p>
          <?php echo mcjc_tags_list($searchTagResults); ?>
      </div>
      <?php endif; ?>
      <?php if ($total_results): ?>
        <?php foreach (loop('search_texts') as $searchText): ?>
        <?php set_current_record(
            $searchText['record_type'],
            $searchText['record']
        ); ?>
          <div class="search-result item <?php echo strtolower(
              $filter->filter(
                  str_replace(' ', '-', $searchText['display_type'])
              )
          ); ?>">
                  <div class="item-img">
                    <?php if (
                        $recordImage = record_image(
                            $searchText['record_type'],
                            null,
                            ['loading' => 'lazy']
                        )
                    ): ?>
                    <a href="<?php echo record_url($searchText['record']); ?>">
                      <?php echo $recordImage; ?>
                    </a>
                    <?php endif; ?>
                  </div>
                  <div class="item-info">
                  <span class="item-type">
                    <?php echo $searchText['display_type']; ?>
                  </span>
                      <h2 class="item-title"><?php echo link_to(
                          $searchText['record'],
                          'show',
                          $searchText['title']
                              ? $searchText['title']
                              : '[Unknown]'
                      ); ?>
                      </h2>
                <?php if ($searchText['description'] ?? false): ?>
                    <span class="item-description">
                      <?php echo $searchText['description']; ?>
                    </span>
                <?php endif; ?>
          </div>
          </div>
      <?php endforeach; ?>
<?php echo pagination_links(); ?>
<?php else: ?>
        <p><?php echo get_theme_option('search_no_results_text') ??
            __(
                'No results were found. Please check your spelling or try searching for a different term'
            ); ?></p>
        <a href="/people" class="button"><?php echo __(
            "Browse all people"
        ); ?></a>
        <a href="/topics" class="button"><?php echo __(
            "Browse all topics"
        ); ?></a>
<?php endif; ?>
</div>
</div>
<?php echo foot(); ?>
