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

$audioGreetingFile = false;
$audioGreetingButton = get_theme_option(
    "audio_greeting_button_{$className}_page"
);

switch ($className) {
    case 'topics':
        $audioGreetingFile =
            "/themes/berlin_mcjc/assets/audio/greetings/topics.mp3";
        break;
    case 'people':
        $audioGreetingFile =
            "/themes/berlin_mcjc/assets/audio/greetings/people.mp3";
        break;
    case 'respond':
        $audioGreetingFile =
            "/themes/berlin_mcjc/assets/audio/greetings/respond.mp3";
        break;
    default:
        break;
}
?>
<div class="hero-image <?php echo $heroImageClasses; ?>">
  <?php if ($audioGreetingButton && $audioGreetingFile): ?>
      <div class="audio-greeting">
          <audio id="audio-greeting-element">
              <source src="<?php echo $audioGreetingFile; ?>">
          </audio>
          <button id="audio-greeting-button">
              <img src="/files/theme_uploads/<?php echo $audioGreetingButton; ?>" alt="Play audio greeting" width="150px" height="150px">
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
