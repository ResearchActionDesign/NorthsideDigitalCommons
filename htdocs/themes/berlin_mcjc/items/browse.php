<?php
// Display separate theming if this is specifically a browse people page.
$filters = item_search_filters();
if (strpos($filters, 'Item Type: Person') !== FALSE) {
    $browseByPerson = TRUE;
    $pageTitle = __('People');
}
else {
    $browseByPerson = FALSE;
    $pageTitle = __('Browse Items');
}
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>

<h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

<?php if (!$browseByPerson): ?>
<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>

<?php
endif;
echo $browseByPerson ? '' : item_search_filters();

if (($total_results > 0) && (!$browseByPerson)):
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Creator')] = 'Dublin Core,Creator';
$sortLinks[__('Date Added')] = 'added';
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php endif; ?>
<?php echo pagination_links(); ?>

<?php foreach (loop('items') as $item): ?>
<div class="item record">
    <h2><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title')), array('class'=>'permalink')); ?></h2>
    <div class="item-meta">
    <?php if (metadata('item', 'has files')): ?>
    <div class="item-img">
        <div class="item-images"><?php echo mcjc_files_for_item(); ?></div>
    </div>
    <?php elseif (metadata('item', array('Dublin Core', 'Type')) == 'Person'): ?>
        <div class="item-img">
            <div class="item-images">
                <div class="image-jpeg">
                    <?php echo link_to_item("<img class=\"thumb head-icon\" width=\"200\" height=\"200\" src=\"/themes/berlin_mcjc/images/head_icon.png\" alt=\"\"/>", array('class' => 'permalink'));?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
    <div class="item-description">
        <?php echo $description; ?>
    </div>
    <?php endif; ?>

    <?php if (metadata('item', 'has tags')): ?>
    <div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
        <?php echo tag_string('items'); ?></p>
    </div>
    <?php endif; ?>

    <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

    </div><!-- end class="item-meta" -->
</div><!-- end class="item entry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

<?php echo foot(); ?>
