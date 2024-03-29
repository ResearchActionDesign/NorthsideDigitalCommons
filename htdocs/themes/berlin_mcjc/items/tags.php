<?php
$pageTitle = __("All Tags");
$pageDescription = get_theme_option("tags_page_text");

// TODO: Is this the right way to set theme defaults?
if (!$pageDescription) {
    $pageDescription =
        "View our full list of tags here. You may prefer to <a href='/people'>browse all people</a>, <a href='/topics'>explore all topics</a>, or <a href='/search'>search for content</a>.";
}
echo head(["title" => $pageTitle, "bodyclass" => "tags list"]);
echo flash();
?>


<?php echo common("breadcrumbs", [
    "trail" => ["Tags"],
]); ?>
<?php echo common("hero-image-header", [
    "title" => $pageTitle,
    "className" => "tags",
    "headerText" => $pageDescription,
]); ?>

<?php if (count($tags)): ?>
<?php
usort($tags, "strnatcasecmp");
$tagsByLetter = mcjc_sort_tags_by_first_letter($tags);
$validLetters = array_keys($tagsByLetter);
?>
<div class="filter-by-letter">
  <span><?php echo __("SCROLL TO LETTER"); ?></span>
  <ul>
    <li class="active"><a href="<?php echo $this->url(
        [],
        "peopleDefault"
    ); ?>">All</a></li>
    <?php foreach (array_merge(["Digits"], range("A", "Z")) as $letter): ?>
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
<div class="container">
<?php foreach ($validLetters as $letter): ?>
<div class="tags-content">
  <div class=" tags__letter" id="<?php echo $letter; ?>">
    <h2><?php echo $letter; ?></h2>
    <?php echo mcjc_tags_list($tagsByLetter[$letter]); ?>
  </div>
</div>
<?php endforeach; ?>

<?php else: ?>
<p><?php echo __(
    "There are no tags to display. You must first tag some items."
); ?></p>

<?php endif; ?>
</div>

<?php echo foot(); ?>
