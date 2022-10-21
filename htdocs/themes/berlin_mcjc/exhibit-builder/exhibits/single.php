<div class="exhibit record">
    <h3><?php echo exhibit_builder_link_to_exhibit($exhibit); ?></h3>
    <?php if ($exhibitImage = record_image($exhibit)): ?>
        <?php echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage, [
            "class" => "image",
        ]); ?>
    <?php endif; ?>
    <p class="exhibit-description"><?php echo snippet_by_word_count(
        metadata($exhibit, "description", ["no_escape" => true])
    ); ?></p>
    <?php echo exhibit_builder_link_to_exhibit($exhibit, "Keep reading"); ?>
</div>
