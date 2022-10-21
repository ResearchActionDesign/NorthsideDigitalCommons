<?php
$pageTitle = __("405 Method Not Allowed");
echo head(["title" => $pageTitle, "bodyclass" => "error"]);
?>
<h1><?php echo $pageTitle; ?></h1>
<div class="#primary">
<p><?php echo __(
    "The method used to access this URL (%s) is not valid.",
    html_escape($this->method)
); ?></p>
</div>
<?php echo foot();
?>
