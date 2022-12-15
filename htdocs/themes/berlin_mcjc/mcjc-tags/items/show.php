<?php

echo head(["title" => $tag_name, "bodyclass" => "items browse"]); ?>
<?php echo common("breadcrumbs", [
    "trail" => [
        "Tags" => "/tags",
        $tag_name,
    ],
]); ?>

<h1><?php echo $tag_name; ?></h1>
<div class="background-container">
<div class="grid-container">
  <div class="grid-items">
    <?php foreach (loop("items") as $item): ?>
      <?php echo common("grid-item", ["item" => $item]); ?>
    <?php endforeach; ?>
  </div>
</div>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook("public_items_browse", [
    "items" => $items,
    "view" => $this,
]); ?>
</div>
<?php echo foot(); ?>
