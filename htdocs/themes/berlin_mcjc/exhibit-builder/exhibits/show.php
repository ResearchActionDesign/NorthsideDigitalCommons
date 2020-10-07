<?php
echo head([
    'title' =>
        metadata('exhibit_page', 'title') .
        ' &middot; ' .
        metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show',
]);

$breadcrumbs = [
    'Topics' => '/topics',
    $exhibit->title => exhibit_builder_exhibit_uri($exhibit),
];

if ($exhibit_page->parent_id) {
    $parentPage = $exhibitPage->getParent();
    $breadcrumbs[$parentPage->title] = exhibit_builder_exhibit_uri(
        $exhibit,
        $parentPage
    );
}

$backLinkText = array_key_last($breadcrumbs);
$backLinkUrl = $breadcrumbs[$backLinkText];
$backLinkText = "Back to " . $backLinkText;

$breadcrumbs[] = $exhibit_page->title;
?>

<?php echo common('breadcrumbs', ['trail' => $breadcrumbs]); ?>

<h1 class='exhibit-page-title'><span class="exhibit-page"><?php echo metadata(
    'exhibit_page',
    'title'
); ?></span></h1>

<div id="exhibit-blocks">
  <?php exhibit_builder_render_exhibit_page(); ?>
</div>

<nav class="exhibit-page-navigation">
  <?php if (
      $prevLink = exhibit_builder_link_to_previous_page(null, [
          'class' => 'exhibit-nav-prev button',
      ])
  ): ?>
      <?php echo $prevLink; ?>
  <?php endif; ?>
  <?php if (
      $nextLink = exhibit_builder_link_to_next_page(null, [
          'class' => 'exhibit-nav-next button',
      ])
  ): ?>
      <?php echo $nextLink; ?>
  <?php endif; ?>
    <?php if ($backLinkUrl): ?>
    <a class="exhibit-nav-parent" href="<?php echo $backLinkUrl; ?>"><?php echo $backLinkText; ?></a>
    <?php endif; ?>
</nav>
<?php echo foot(); ?>
