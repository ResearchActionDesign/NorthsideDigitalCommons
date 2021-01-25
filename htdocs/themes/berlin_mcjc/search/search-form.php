<?php
$name = 'search-form';
if ($options['expanded'] ?? false) {
    $name = 'search-form-expanded';
    $options['form_attributes']['id'] = $name;
}
if ($options['id'] ?? false) {
    $name = $options['id'];
    $options['form_attributes']['id'] = $name;
}
$options['form_attributes']['class'] = 'search-form';
echo $this->form($name, $options['form_attributes']);

// If the form has been submitted, retain the number of search
// fields used and rebuild the form
if (!empty($_GET['advanced'])) {
    $search = $_GET['advanced'];
} else {
    $search = [['field' => '', 'type' => '', 'value' => '']];
}
?>
<?php if ($options['expanded'] ?? false): ?>
  <?php $item_type_options = ['All', 'Person', 'Image', 'Story', 'Topic']; ?>
<div class="search-options">
<div class="field">
    <label for="query"><?php echo __('Search Term'); ?></label>
  <?php echo $this->formText('query', $filters['query'], [
      'title' => __('Search'),
  ]); ?>
</div>
</div>
<?php else: ?>
<?php echo $this->formText('query', $filters['query'], [
    'title' => __('Search'),
]); ?>
<?php endif; ?>
<?php echo $this->formButton('submit_search', $options['submit_value'], [
    'type' => 'submit',
    'content' => $options['expanded'] ?? false ? 'Search Again' : 'Search',
    'class' => 'button',
]); ?>
</form>
