<?php
$pageList = get_view()->exhibitPageList($exhibit);
$firstPage = $exhibit->getFirstTopPage();
$firstPageUri = exhibit_builder_exhibit_uri($exhibit, $firstPage);
?>

<?php
echo head([
    "title" => metadata("exhibit", "title"),
    "bodyclass" => "exhibits summary",
]);

$breadcrumbs = [
    "Topics" => "/topics",
    $exhibit->title,
];

echo common("breadcrumbs", ["trail" => $breadcrumbs]);
?>

<div class=exhibit-content>
<div class='exhibit-header-container'>
<div class='exhibit-title-container'>
<h1 class='exhibit-title'><?php echo metadata("exhibit", "title"); ?></h1>
<?php echo exhibit_builder_page_nav(); ?>

<?php if (
    $exhibitDescription = metadata("exhibit", "description", [
        "no_escape" => true,
    ])
): ?>
<div class="exhibit-description">
  <?php echo record_image("exhibit", "fullsize", [
      "alt" => "",
      "class" => "exhibit-image",
  ]); ?>
  <?php echo $exhibitDescription; ?>
  <a href="<?php echo $firstPageUri; ?>" class="button">Start the tour &rarr;</a>
</div>
<?php endif; ?>

<?php if ($exhibitCredits = metadata("exhibit", "credits")): ?>
<div class="exhibit-credits">
    <h3><?php echo __("Credits"); ?></h3>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
</div>
<?php if ($exhibitTags = mcjc_tag_string("exhibit")): ?>
    <div id="item-tags" class="element">
        <span class="element-title"><?php echo __("Tags: "); ?></span>
        <span class="element-text"><?php echo $exhibitTags; ?></span>
    </div>
    <?php endif; ?>
</div>

    <div class="background-container">
<?php if ($pageList): ?>
<div class="explore-grid grid-container exhibit-pages">
    <h2>In this Exhibit</h2>
    <div class="grid-items <?php echo count($pageList) < 3
        ? "grid-count-" . count($pageList)
        : ""; ?>">
        <?php foreach ($pageList as $page): ?>
          <?php echo common("exhibit-grid-item", [
              "item" => $page,
              "class" => "exhibit-page",
          ]); ?>
          <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
        <div class="back-container">
            <a class="button back" href="/topics">Back to all Topics</a>
        </div>
    </div>

<?php echo foot();
?>
