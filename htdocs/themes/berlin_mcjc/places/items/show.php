<?php

// Render theme pages specifically.
$itemTitle = metadata("item", "display_title");
$itemType = "Place";

$itemTypeClass = "place";
$backButtonText = __("Back to the places page");
$backLink = "/places";
$breadcrumbTrail = [
    "Places" => "/places",
    $itemTitle,
];

$itemClasses = "";
$description = false;
$picture = false;
$showMap = false;

if (metadata("item", "has files")) {
    $itemClasses = " has-picture";
    $picture = mcjc_item_image("fullsize", ["alt" => $itemTitle]);
}
if (metadata("item", ["Dublin Core", "Description"])) {
    $itemClasses .= " has-description";
    $description = metadata("item", ["Dublin Core", "Description"]);
}

$latitude = (float) metadata("item", ["Item Type Metadata", "Latitude"]);
$longitude = (float) metadata("item", ["Item Type Metadata", "Longitude"]);

if ($latitude != 0 && $longitude != 0) {
    $showMap = true;
    $itemClasses .= " has-map";
}
?>
<?php echo head([
    "title" => $itemTitle,
    "bodyclass" => "items show {$itemTypeClass}",
    "description" => $description,
]); ?>


<?php echo common("breadcrumbs", [
    "trail" => $breadcrumbTrail,
]); ?>

<div class="place-badges">
<?php foreach (mcjc_get_place_badges("item") as $placeBadge): ?>
<a href="/tags/<?php echo $placeBadge["tag_name"]; ?>">
  <?php echo common("picture-tag", [
      "base_filename" => $placeBadge["image_path"],
      "options" => [
          "alt" => $placeBadge["tag_name"],
          "width" => 150,
          "height" => 150,
      ],
  ]); ?>
</a>
<?php endforeach; ?>
</div>

<div class="primary <?php echo "{$itemTypeClass} {$itemClasses}"; ?>">
  <div class="item-content">
    <span class="item-type"><?php echo $itemType; ?></span>
    <h1><?php echo $itemTitle; ?></h1>
    <?php if ($description): ?>
      <p class="description">
        <?php echo $description; ?>
      </p>
    <?php endif; ?>
  </div>
  <div class="item-sidebar">
    <?php if ($picture): ?>
      <div id="picture" class="element">
        <div class="item-images"><?php echo $picture; ?></div>
      </div>
    <?php endif; ?>
    <?php if (metadata("item", "has files")): ?>
      <div id="itemfiles" class="element">
        <div class="item-images"><?php echo mcjc_files_for_item("item", [
            "imageSize" => "fullsize",
            "linkAttributes" => ["data-lity" => ""],
            "show" => true,
        ]); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php
$tags = mcjc_tag_string("item");
$metadata_paragraph = mcjc_element_metadata_paragraph($item);
$rights = metadata($item, ["Dublin Core", "Rights"]);
$citation = metadata("item", "citation", [
    "no_escape" => true,
]);
?>
<div class="tags-container">
  <?php if ($tags): ?>
      <p id="item-tags" class="element">
          <span class="element-title"><?php echo __("Tags: "); ?></span>
          <span class="element-text"><?php echo $tags; ?></span>
      </p>
  <?php endif; ?>
    <div class="details">
      <?php if ($metadata_paragraph): ?>
          <p id="item-detail" class="element">
              <span class="element-text"><?php echo $metadata_paragraph; ?></span>
          </p>
      <?php endif; ?>
      <?php if ($citation): ?>
          <p id="item-citation" class="element">
              <span class="element-title"><?php echo __("Citation: "); ?></span>
              <span class="element-text"><?php echo $citation; ?></span>
          </p>
      <?php endif; ?>
      <?php if ($rights && $rights !== "Open for research."): ?>
          <p id="item-rights" class="element"><span class="element-title"><?php echo __(
              "Rights: "
          ); ?></span><span class="element-text"><?php echo $rights; ?></span></p>
      <?php endif; ?>
    </div>
</div>

<?php if ($showMap): ?>
    <section class="leaflet map">
        <link rel="stylesheet" href="/themes/berlin_mcjc/css/lib/leaflet.css" />
        <script type="text/javascript" src="/themes/berlin_mcjc/javascripts/leaflet.js"></script>
        <div id="leaflet-map" style="height: 200px;">
        </div>
        <script type="text/javascript">
            var mymap = L.map('leaflet-map',{
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
                touchZoom: false,
                doubleClickZoom: false,
            }).setView([<?php echo $latitude . "," . $longitude; ?>], 16);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
            }).addTo(mymap);
            var marker = L.marker([<?php echo $latitude .
                "," .
                $longitude; ?>]).addTo(mymap);
            marker.bindPopup("<?php echo $itemTitle; ?>").openPopup();
        </script>
    </section>
<?php endif; ?>

<?php fire_plugin_hook("public_items_show", [
    "view" => $this,
    "item" => $item,
]); ?>
<div class="background-container">

<?php if (!empty($depicted_items)): ?>
  <div class="explore-grid masonry-grid grid-container depicted">
    <h2>To learn more...</h2>
    <div class="grid-items">
      <?php foreach (loop("depicted_items") as $relatedItem): ?>
        <?php echo common("grid-item", [
            "item" => $relatedItem,
            "class" => "depicted",
        ]); ?>
      <?php endforeach; ?>
    </div>
  </div>

  <?php echo common("respond-bar"); ?>

<?php endif; ?>
<div class="back-container">
  <a class="button back" href="<?php echo $backLink; ?>"><?php echo $backButtonText; ?></a>
</div>
</div>

<?php echo foot(); ?>
