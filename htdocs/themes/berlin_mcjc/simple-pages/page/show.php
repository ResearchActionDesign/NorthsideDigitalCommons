<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

$submenu = mcjc_get_submenu();
$div_class = "page-about";

echo head([
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_pages_page', 'slug'),
]);
?>
<div id="primary" class="<?php echo $div_class; ?>">
    <?php if (!$is_home_page): ?>
        <p id="simple-pages-breadcrumbs" class="breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <?php endif; ?>
    <div class="about-content-wrapper">
        <div class="simple-pages-text-wrapper">
            <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
            <?php
            $text = metadata('simple_pages_page', 'text', [
                'no_escape' => true,
            ]);
            echo $this->shortcodes($text);
            ?>
        </div>
        <div class="simple-pages-submenu" id="secondary simple-pages-submenu"><?php echo $submenu; ?></div>
    </div>
</div>


<?php echo foot(); ?>
