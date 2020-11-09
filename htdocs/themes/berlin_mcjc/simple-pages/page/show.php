<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

$submenu = false;
if ($simple_pages_page->getChildren()) {
    $submenu = mcjc_get_submenu();
}
$div_class = "page-about";

echo head([
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_pages_page', 'slug'),
]);

$text = metadata('simple_pages_page', 'text', [
    'no_escape' => true,
]);
?>
<div id="primary" class="<?php echo $div_class; ?>">
    <?php if (!$is_home_page): ?>
        <p id="simple-pages-breadcrumbs" class="breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <?php endif; ?>
    <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
    <div class="about-content-wrapper">
        <?php if ($text): ?>
        <div class="simple-pages-text-wrapper">
            <?php echo $this->shortcodes($text); ?>
        </div>
        <?php endif; ?>
        <?php if ($submenu): ?>
        <div class="simple-pages-submenu" id="secondary simple-pages-submenu"><?php echo $submenu; ?></div>
        <?php endif; ?>
    </div>
</div>


<?php echo foot(); ?>
