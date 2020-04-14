<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

$is_about_page = mcjc_is_about_page($simple_pages_page);
$div_class = "";
if ($is_about_page) {
    $submenu = mcjc_get_submenu();
    $div_class = "page-about";
}

echo head([
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_pages_page', 'slug'),
]);
?>
<div id="primary <?php echo $div_class; ?>">
    <?php if (!$is_home_page): ?>
        <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
        <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
    <?php endif; ?>
    <div class="about-content-wrapper">
        <div class="simple-pages-text-wrapper">
            <?php
            $text = metadata('simple_pages_page', 'text', [
                'no_escape' => true,
            ]);
            echo $this->shortcodes($text);
            ?>
        </div>
        <?php if ($is_about_page): ?>
        <div class="simple-pages-submenu" id="secondary simple-pages-submenu"><?php echo $submenu; ?></div>
        <?php endif; ?>
    </div>
</div>

<?php echo foot(); ?>
