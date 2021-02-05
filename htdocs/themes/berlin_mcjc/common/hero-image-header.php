<?php
if (!is_array($className)) {
    $className = [$className];
}

$heroImageClasses = join(
    " ",
    array_map(function ($c) {
        return $c . '-image';
    }, $className)
);
$className = $className[0];

$audioGreetingFile = file_exists(
    getcwd() . "/themes/berlin_mcjc/assets/audio/greetings/{$className}.m4a"
)
    ? "/themes/berlin_mcjc/assets/audio/greetings/{$className}.m4a"
    : false;
$audioGreetingFileOgg = "/themes/berlin_mcjc/assets/audio/greetings/{$className}.ogg";
$audioGreetingImage = file_exists(
    getcwd() .
        "/themes/berlin_mcjc/assets/images/audio-greeting-buttons/{$className}.png"
)
    ? "/themes/berlin_mcjc/assets/images/audio-greeting-buttons/{$className}.png"
    : false;
?>
<div class="hero-image <?php echo $heroImageClasses; ?>">
  <?php if ($audioGreetingFile && $audioGreetingImage): ?>
      <div class="audio-greeting">
          <audio id="audio-greeting-element">
              <source src="<?php echo $audioGreetingFileOgg; ?>" type="audio/ogg"/>
              <source src="<?php echo $audioGreetingFile; ?>" type="audio/mp4"/>
              <p>Your browser does not support this audio. <a href="<?php echo $audioGreetingFile; ?>">Click here to access the file directly</a>.</p>
          </audio>
          <button id="audio-greeting-button">
              <?php echo common('picture-tag', [
                  'base_filename' => $audioGreetingImage,
                  'options' => [
                      'alt' => 'Play audio greeting',
                      'width' => 150,
                      'height' => 150,
                  ],
              ]); ?>
          </button>
      </div>
  <?php endif; ?>
  <div class="hero-image-content">
    <h1 class="image-title"><?php echo $title; ?></h1>
    <?php if ($headerText): ?>
      <div class="image-text"><?php echo $headerText; ?></div>
    <?php endif; ?>
  </div>
</div>
