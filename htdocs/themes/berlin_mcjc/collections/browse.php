<?php
$pageTitle = __('Browse Collections');
echo head(['title' => $pageTitle, 'bodyclass' => 'collections browse']);
?>

<h1><?php echo $pageTitle; ?> <?php echo __(
     '(%s total)',
     $total_results
 ); ?></h1>
<?php echo pagination_links(); ?>

<?php
$sortLinks[__('Title')] = 'Dublin Core,Title';
$sortLinks[__('Date Added')] = 'added';
?>
<div id="sort-links">
    <span class="sort-label"><?php echo __(
        'Sort by: '
    ); ?></span><?php echo browse_sort_links($sortLinks); ?>
</div>

<?php foreach (loop('collections') as $collection): ?>

<div class="collection record">

    <h2><?php echo link_to_collection(); ?></h2>

    <div class="collection-meta item-meta">
    <?php if ($collectionImage = record_image('collection')): ?>
        <div class="item-img">
            <div class="item-images"><?php echo mcjc_files_for_item(
                'collection',
                ['browse_collections' => true]
            ); ?></div>
        </div>
    <?php endif; ?>

    <?php if (metadata('collection', ['Dublin Core', 'Description'])): ?>
    <div class="collection-description item-description">
        <?php echo text_to_paragraphs(
            metadata(
                'collection',
                ['Dublin Core', 'Description'],
                ['snippet' => 150]
            )
        ); ?>
    </div>
    <?php endif; ?>

    <?php if ($collection->hasContributor()): ?>
    <div class="collection-contributors">
        <p>
        <strong><?php echo __('Contributors'); ?>:</strong>
        <?php echo metadata(
            'collection',
            ['Dublin Core', 'Contributor'],
            ['all' => true, 'delimiter' => ', ']
        ); ?>
        </p>
    </div>
    <?php endif; ?>

    <p class="view-items-link"><?php echo link_to_items_browse(
        __('View the items in %s', metadata('collection', 'display_title')),
        ['collection' => metadata('collection', 'id')]
    ); ?></p>

    <?php fire_plugin_hook('public_collections_browse_each', [
        'view' => $this,
        'collection' => $collection,
    ]); ?>

    </div>

</div><!-- end class="collection" -->

<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_collections_browse', [
    'collections' => $collections,
    'view' => $this,
]); ?>

<?php echo foot(); ?>
