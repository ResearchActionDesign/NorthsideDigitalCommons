<?php
$pageTitle = __('Error');
echo head(['title' => $pageTitle, 'bodyclass' => 'error']);
?>
<h1><?php echo $pageTitle; ?></h1>
<div id="primary">
  <p><?php echo __(
      'Sorry, the site encountered an error. You might try:'
  ); ?></p>
  <a href="/how-to-use" class="button"><?php echo __(
      "Read about how to use the site"
  ); ?></a>
  <a href="/people" class="button"><?php echo __("Browse all people"); ?></a>
  <a href="/topics" class="button"><?php echo __("Browse all topics"); ?></a>
  <a href="/search" class="button"><?php echo __("Search for an item"); ?></a>
  <p>Please contact us at <a href="mailto:rockwall@jacksoncenter.info">rockwall@jacksoncenter.info</a> if you need more help!</p>
</div>
<?php echo foot(); ?>
