<?php
$id = 'search-form';
if ($options['expanded'] ?? false) {
    $id = 'search-form-expanded';
    $options['form_attributes']['id'] = $id;
}
echo $this->form($id, $options['form_attributes']);
?>
<?php if ($options['expanded'] ?? false): ?>
<div class="field">
    <label for="query"><?php echo __('Search Term'); ?></label>
  <?php echo $this->formText('query', $filters['query'], [
      'title' => __('Search'),
  ]); ?>
</div>
<div class="field">
    <label for="item-type"><?php echo __(
        'Item Type'
    ); ?> (TODO, currently has no effect)</label>
    <select id="item-type">
        <option>All</option>
        <option>Person</option>
        <option>Image</option>
        <option>Story</option>
        <option>Topic</option>
    </select>
</div>
<details>
    <summary>More search options</summary>
    <div class="search-form--advanced">
        <i>TODO: These currently don't work</i>
        <div id="search-narrow-by-fields" class="field">
            <div class="label"><?php echo __(
                'Narrow by Specific Fields'
            ); ?></div>
            <div class="inputs">
              <?php // If the form has been submitted, retain the number of search // fields used and rebuild the form // If the form has been submitted, retain the number of search // fields used and rebuild the form
    // If the form has been submitted, retain the number of search // fields used and rebuild the form
    // If the form has been submitted, retain the number of search
    // fields used and rebuild the form
    ?>
              if (!empty($_GET['advanced'])) {
                  $search = $_GET['advanced'];
              } else {
                  $search = [['field' => '', 'type' => '', 'value' => '']];
              } //Here is where we actually build the search form
              foreach ($search as $i => $rows): ?>
                  <div class="search-entry">
                    <?php //etc
                  //etc
                  ?>
                    //etc
                    //The POST looks like =>
                    // advanced[0] =>
                    //[field] = 'description'
                    //[type] = 'contains'
                    //[terms] = 'foobar'
                    echo $this->formSelect(
                        "advanced[$i][joiner]",
                        @$rows['joiner'],
                        [
                            'title' => __("Search Joiner"),
                            'id' => null,
                            'class' => 'advanced-search-joiner',
                        ],
                        [
                            'and' => __('AND'),
                            'or' => __('OR'),
                        ]
                    );
                    echo $this->formSelect(
                        "advanced[$i][element_id]",
                        @$rows['element_id'],
                        [
                            'title' => __("Search Field"),
                            'id' => null,
                            'class' => 'advanced-search-element',
                        ],
                        get_table_options('Element', null, [
                            'record_types' => ['Item', 'All'],
                            'sort' => 'orderBySet',
                        ])
                    );
                    echo $this->formSelect(
                        "advanced[$i][type]",
                        @$rows['type'],
                        [
                            'title' => __("Search Type"),
                            'id' => null,
                            'class' => 'advanced-search-type',
                        ],
                        label_table_options([
                            'contains' => __('contains'),
                            'does not contain' => __('does not contain'),
                            'is exactly' => __('is exactly'),
                            'is empty' => __('is empty'),
                            'is not empty' => __('is not empty'),
                            'starts with' => __('starts with'),
                            'ends with' => __('ends with'),
                        ])
                    );
                    echo $this->formText(
                        "advanced[$i][terms]",
                        @$rows['terms'],
                        [
                            'size' => '20',
                            'title' => __("Search Terms"),
                            'id' => null,
                            'class' => 'advanced-search-terms',
                        ]
                    );
                    ?>
                      <button type="button" class="remove_search" disabled="disabled" style="display: none;"><?php echo __(
                          'Remove field'
                      ); ?></button>
                  </div>
              <?php endforeach;
              ?>
            </div>
            <button type="button" class="add_search"><?php echo __(
                'Add a Field'
            ); ?></button>
        </div>
        <div class="field">
          <?php echo $this->formLabel('tag-search', __('Search By Tags')); ?>
            <div class="inputs">
              <?php echo $this->formText('tags', @$_REQUEST['tags'], [
                  'size' => '40',
                  'id' => 'tag-search',
              ]); ?>
            </div>
        </div>
        <div class="field">
          <?php echo $this->formLabel(
              'collection-search',
              __('Search By Collection')
          ); ?>
            <div class="inputs">
              <?php echo $this->formSelect(
                  'collection',
                  @$_REQUEST['collection'],
                  [
                      'id' => 'collection-search',
                  ],
                  get_table_options('Collection', null, [
                      'include_no_collection' => true,
                  ])
              ); ?>
            </div>
        </div>

    </div>
</details>
<?php else: ?>
<?php echo $this->formText('query', $filters['query'], [
    'title' => __('Search'),
]); ?>
<?php endif; ?>
<?php echo $this->formButton('submit_search', $options['submit_value'], [
    'type' => 'submit',
]); ?>
</form>
