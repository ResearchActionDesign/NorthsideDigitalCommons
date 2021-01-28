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
    getcwd() . "/themes/berlin_mcjc/assets/audio/greetings/{$className}.mp3"
)
    ? "/themes/berlin_mcjc/assets/audio/greetings/{$className}.mp3"
    : false;
$audioGreetingOption = get_theme_option("audio_greeting_{$className}_page");
?>
<div class="hero-image <?php echo $heroImageClasses; ?>">
  <?php if ($audioGreetingOption && $audioGreetingFile): ?>
      <div class="audio-greeting">
          <audio id="audio-greeting-element">
              <source src="<?php echo $audioGreetingFile; ?>">
          </audio>
          <button id="audio-greeting-button">
              <?php echo common('picture-tag', [
                  'base_filename' =>
                      "/themes/berlin_mcjc/assets/images/audio-greeting-buttons/" .
                      $className .
                      ".png",
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
