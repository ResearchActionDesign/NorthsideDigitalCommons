<?php echo head([
    'title' => metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits summary',
]); ?>
<div class=exhibit-content>
<div class='exhibit-header-container'>
<div class='exhibit-title-container'>
<h2 class='exhibit-title'><?php echo metadata('exhibit', 'title'); ?></h2>
<?php echo exhibit_builder_page_nav(); ?>

<div id="primary exhibit-description-wrapper">
<?php if (
    $exhibitDescription = metadata('exhibit', 'description', [
        'no_escape' => true,
    ])
): ?>
<div class="exhibit-description">
    <?php echo $exhibitDescription; ?>
</div>
</div>

<?php endif; ?>

<?php if ($exhibitCredits = metadata('exhibit', 'credits')): ?>
<div class="exhibit-credits">
    <h3><?php echo __('Credits'); ?></h3>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
</div>

<?php
$pageTree = exhibit_builder_page_tree();
if ($pageTree): ?>
<div class='exhibit-img-wrapper'>
    <div class='exhibit-img-placeholder'>Placeholder</div>
</div>
</div>
<div class='placeholder-wrapper'>
    <div class='PlaceholderParagraph'>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
        Voluptatibus hic omnis recusandae repudiandae molestiae 
        laudantium esse facilis asperiores! Dolores fuga aspernatur 
        in mollitia ratione dolorem consequatur deleniti quaerat 
        adipisci earum.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
        Voluptatibus hic omnis recusandae repudiandae molestiae 
        laudantium esse facilis asperiores! Dolores fuga aspernatur 
        in mollitia ratione dolorem consequatur deleniti quaerat 
        adipisci earum.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
        Voluptatibus hic omnis recusandae repudiandae molestiae 
        laudantium esse facilis asperiores! Dolores fuga aspernatur 
        in mollitia ratione dolorem consequatur deleniti quaerat 
        adipisci earum.</p>
    </div>
    <div class='Related-People-list'>
        <ul>
            <h3>Related people</h3>
            <li>placeholder</li>
            <li>placeholder2</li>
            <li>placeholder3</li>
            <li>placeholder4</li>
        </ul>
    </div>
</div>
<h2>In this Exhibit</h2>
<nav id="exhibit-pages">
    <div class='exhibit-items'><?php echo $pageTree; ?> </div>
</nav>
</div>
<?php endif;
?>

<?php echo foot(); ?>
