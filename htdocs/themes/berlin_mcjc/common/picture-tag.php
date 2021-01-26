<?php
$path_parts = pathinfo($base_filename);
$webp_filename =
    $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
$options_string = "";
foreach ($options as $key => $value) {
    $options_string .= "{$key}='{$value}'";
}
?>

<picture>
  <source srcset="<?php echo $webp_filename; ?>">
  <img src="<?php echo $base_filename; ?>" <?php echo $options_string; ?>>
</picture>
