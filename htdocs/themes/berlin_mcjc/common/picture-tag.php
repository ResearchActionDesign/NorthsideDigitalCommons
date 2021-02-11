<?php

$commit_hash = "1";
@include 'commit-hash.php';

$path_parts = pathinfo($base_filename);
$webp_filename =
    $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
$options_string = "";
foreach ($options as $key => $value) {
    $options_string .= "{$key}='{$value}'";
}
?>

<picture>
  <source srcset="<?php echo $webp_filename; ?>?v=<?php echo $commit_hash; ?>" type="image/webp">
  <img src="<?php echo $base_filename; ?>?v=<?php echo $commit_hash; ?>" <?php echo $options_string; ?>>
</picture>
