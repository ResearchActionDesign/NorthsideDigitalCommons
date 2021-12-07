<?php
$pageTitle = __('403 Page Forbidden');
echo head(['title' => $pageTitle, 'bodyclass' => 'error']);
?>

<h1><?php echo $pageTitle; ?></h1>
<div id="#primary">
  <?php echo flash(); ?>
    <p><?php echo __(
        'Hmmm. It seems that you do not have permission to access this page. Here are some other things you can try:'
    ); ?></p>
<a href="/how-to-use" class="button"><?php echo __(
    "Read about how to use the site"
); ?></a>
<a href="/people" class="button"><?php echo __("Browse all people"); ?></a>
<a href="/topics" class="button"><?php echo __("Browse all topics"); ?></a>
<a href="/search" class="button"><?php echo __("Search for an item"); ?></a>
    <p>Please contact us at <a href="mailto:rockwall@jacksoncenter.info">rockwall@jacksoncenter.info</a> if you need more help!</p>

</div>
<?php echo foot();
?>
