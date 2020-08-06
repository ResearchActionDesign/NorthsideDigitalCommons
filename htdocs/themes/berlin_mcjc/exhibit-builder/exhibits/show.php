<?php
echo head([
    'title' =>
        metadata('exhibit_page', 'title') .
        ' &middot; ' .
        metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show',
]); ?>

<nav class="exhibit-page-navigation">
  <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
      <div class="exhibit-nav-prev">
        <?php echo $prevLink; ?>
      </div>
  <?php endif; ?>
  <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
      <div class="exhibit-nav-next">
        <?php echo $nextLink; ?>
      </div>
  <?php endif; ?>
</nav>

<h1 class='exhibit-page-title'><span class="exhibit-page"><?php echo metadata(
    'exhibit_page',
    'title'
); ?></span></h1>

<div id="exhibit-blocks">
  <?php exhibit_builder_render_exhibit_page(); ?>
</div>

<nav class="exhibit-page-navigation">
  <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
    <div class="exhibit-nav-prev">
      <?php echo $prevLink; ?>
    </div>
  <?php endif; ?>
  <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
    <div class="exhibit-nav-next">
      <?php echo $nextLink; ?>
    </div>
  <?php endif; ?>
    <?php if (
        $parentLink = exhibit_builder_link_to_parent_page(null, [
            'class' => 'exhibit-nav-parent button',
        ])
    ): ?>
     <?php echo $parentLink; ?>
    <?php else: ?>
      <?php echo exhibit_builder_link_to_exhibit(null, null, [
          'class' => 'exhibit-nav-parent button',
      ]); ?>
    <?php endif; ?>
</nav>
<?php echo foot(); ?>
