<?php
echo head([
    'title' =>
        metadata('exhibit_page', 'title') .
        ' &middot; ' .
        metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show',
]); ?>

<h1 class='exhibit-page-title'><span class="exhibit-page"><?php echo metadata(
    'exhibit_page',
    'title'
); ?></span></h1>

<div id="exhibit-blocks">
  <?php exhibit_builder_render_exhibit_page(); ?>
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

<div id="exhibit-page-navigation">
  <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
    <div id="exhibit-nav-prev">
      <?php echo $prevLink; ?>
    </div>
  <?php endif; ?>
  <div id="exhibit-nav-up">
    <?php echo exhibit_builder_page_trail(); ?>
  </div>
  <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
    <div id="exhibit-nav-next">
      <?php echo $nextLink; ?>
    </div>
  <?php endif; ?>

</div>

<!-- <nav id="exhibit-pages">
    <h4><php echo exhibit_builder_link_to_exhibit($exhibit); ?></h4>
    <php echo exhibit_builder_page_tree($exhibit, $exhibit_page); ?>
</nav> -->
<?php echo foot(); ?>
