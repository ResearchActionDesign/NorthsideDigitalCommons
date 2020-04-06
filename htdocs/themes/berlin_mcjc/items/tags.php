<?php
$pageTitle = __('All Tags');
$pageDescription = get_theme_option('tags_page_text');

// TODO: Is this the right way to set theme defaults?
if (!$pageDescription) {
    $pageDescription =
        "View our full list of tags here. You may prefer to <a href='/people'>browse all people</a>, <a href='/topics'>explore all topics</a>, or <a href='/search'>search for content</a>.";
}
echo head(['title' => $pageTitle, 'bodyclass' => 'tags list']);
echo flash();
?>
<?php if (count($tags)): ?>
  <h1><?php echo $pageTitle; ?></h1>
  <p><?php echo $pageDescription; ?></p>
  <?php
  sort($tags, SORT_STRING | SORT_FLAG_CASE);
  $tagsByLetter = mcjc_sort_tags_by_first_letter($tags);
  $validLetters = array_keys($tagsByLetter);
  ?>

  <?php
    /* This chunk of code similar to people/index/browse.php, potentially abstract into a function */
    ?>
  <div class="tags__alphabet-tabs">
    <span><?php echo __('SCROLL TO LETTER'); ?></span>
    <ul>
      <li class="active"><a href="<?php echo $this->url(
          [],
          'peopleDefault'
      ); ?>">All</a></li>
      <?php foreach (array_merge(['123'], range('A', 'Z')) as $letter): ?>
        <?php if (in_array($letter, $validLetters)): ?>
          <li>
            <a href="#<?php echo $letter; ?>"><?php echo $letter; ?></a>
          </li>
        <?php else: ?>
          <li class="disabled">
            <?php echo $letter; ?>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>

  <?php foreach ($validLetters as $letter): ?>
    <div class="tags__letter" id="<?php echo $letter; ?>">
      <h2><?php echo $letter; ?></h2>
      <?php echo mcjc_tags_list($tagsByLetter[$letter]); ?>
    </div>
  <?php endforeach; ?>

<?php else: ?>
  <p><?php echo __(
      'There are no tags to display. You must first tag some items.'
  ); ?></p>
<?php endif; ?>
<?php echo foot(); ?>
