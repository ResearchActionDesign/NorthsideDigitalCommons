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
<div class="search-options">
    <label for="query"><?php echo __('Your search term:'); ?></label>
    <div class="field">
  <?php echo $this->formText('query', $filters['query'], [
      'title' => __('Search'),
  ]); ?>
  <?php echo $this->formButton('submit_search', $options['submit_value'], [
      'type' => 'submit',
      'content' => 'Search Again',
      'class' => 'button',
  ]); ?>
</div>
</div>
<?php else: ?>
<?php echo $this->formText('query', $filters['query'], [
    'title' => __('Search'),
    'placeholder' => 'Enter a search term',
]); ?>
<button id="submit_search" class="button" name="submit_search" type="submit" value="Search">
    <span class="sr-only">Search</span><i class="fa fa-search"></i></button>
</button>
<?php endif; ?>
</form>
