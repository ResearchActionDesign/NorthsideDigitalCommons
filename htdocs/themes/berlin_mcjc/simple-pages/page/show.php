<?php
$bodyclass = "page simple-page";
if ($is_home_page):
    $bodyclass .= " simple-page-home";
endif;

$submenu = false;
if ($simple_pages_page->getChildren()) {
    $submenu = mcjc_get_submenu();
}
$div_class = "page-about";
$pageTitle = metadata("simple_pages_page", "title");
$pageClass = metadata("simple_pages_page", "slug");
$ancestorPageClass = simple_pages_earliest_ancestor_page(null)->slug ?? "";
echo head([
    "title" => $pageTitle,
    "bodyclass" => $bodyclass,
    "bodyid" => $pageClass,
]);

$text = metadata("simple_pages_page", "text", [
    "no_escape" => true,
]);
$headerText = null;
switch ($pageTitle) {
    case "Respond":
        $headerText = get_theme_option("respond_page_text");
        break;
    case "About":
        $headerText = get_theme_option("about_page_text");
        break;
    case "Welcome":
        $headerText = get_theme_option("welcome_page_text");
        break;
    default:
        break;
}
?>
<p id="simple-pages-breadcrumbs" class="breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>

<?php echo common("hero-image-header", [
    "title" => $pageTitle,
    "headerText" => $headerText,
    "className" => [$pageClass, $ancestorPageClass],
]); ?>
<div id="primary" class="<?php echo $div_class; ?>">
    <div class="about-content-wrapper">
        <?php if ($text): ?>
        <div class="simple-pages-text-wrapper">
            <?php echo $this->shortcodes($text); ?>
            <?php echo common("share_icons", [
                "url" => absolute_url(current_url()),
                "title" => $pageTitle,
            ]); ?>
        </div>
        <?php endif; ?>
        <?php if ($submenu): ?>
        <div class="simple-pages-submenu" id="secondary simple-pages-submenu"><?php echo $submenu; ?></div>
        <?php endif; ?>
    </div>
</div>

<?php echo foot();
?>
