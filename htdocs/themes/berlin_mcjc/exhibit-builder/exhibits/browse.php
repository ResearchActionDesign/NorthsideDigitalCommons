<?php
$pageTitle = __("Exhibits");
echo head(["title" => $pageTitle, "bodyclass" => "exhibits browse"]);
?>
<?php echo common("breadcrumbs", [
    "trail" => ["Topics" => "/topics", "Exhibits"],
]); ?>

<h1><?php echo $pageTitle; ?></h1>
<div class="exhibits-content">
  <div class="background-container">
      <div class="grid-container topics-grid masonry-grid">
          <div class="grid-items">
          <?php foreach (loop("exhibits") as $exhibit): ?>
            <?php
            $title = $exhibit->title;
            $exhibit->topicType = "exhibit";
            $description = metadata($exhibit, "description", [
                "no_escape" => true,
            ]);
            echo common("grid-item", [
                "item" => $exhibit,
                "title" => $title,
                "description" => $description,
                "class" => "topic exhibit",
                "masonry" => true,
            ]);
            ?>
          <?php endforeach; ?>
        </div>
      </div>
  </div>
  <?php echo pagination_links(); ?>
</div>
<?php echo foot(); ?>
