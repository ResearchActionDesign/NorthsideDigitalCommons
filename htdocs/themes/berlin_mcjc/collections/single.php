<div class="collection record">
    <?php
    $title = metadata($collection, 'display_title');
    $description = metadata(
        $collection,
        ['Dublin Core', 'Description'],
        ['snippet' => 150]
    );
    ?>
    <h3><?php echo link_to($this->collection, 'show', $title); ?></h3>
    <?php if ($collectionImage = record_image($collection)): ?>
        <?php echo link_to($this->collection, 'show', $collectionImage, [
            'class' => 'image',
        ]); ?>
    <?php endif; ?>
    <?php if ($description): ?>
        <p class="collection-description"><?php echo $description; ?></p>
        <?php echo link_to($this->collection, 'show', 'Keep reading'); ?>
    <?php endif; ?>
</div>
