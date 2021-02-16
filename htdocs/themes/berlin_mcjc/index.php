<?php echo head(['bodyid' => 'home']); ?>
<div class="homepage-title">
    <h1>From the Rock Wall
        <span class="subtitle">Living Histories of Black Chapel Hill/Carrboro</span>
    </h1>
</div>
<div class="video-container">
<?php if (get_theme_option('homepage_video')): ?>
    <div id="homepage-video">
        <video id="homepage-video-element" autoplay width="100%" preload="auto" loop poster="/themes/berlin_mcjc/assets/images/homepage_video.jpg" muted playsinline>
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig--vp9.webm" type="video/webm;codecs=vp9,vorbis">
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.webm" type="video/webm;codecs=vp8,vorbis">
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.mp4" type="video/mp4">
        </video>
        <audio id="homepage-background-audio" preload="metadata">
            <source src="/themes/berlin_mcjc/assets/audio/homepage.ogg" type="audio/ogg"/>
            <source src="/themes/berlin_mcjc/assets/audio/homepage.mp3" type="audio/mp3"/>
            <p>Your browser does not support audio, <a href="/themes/berlin_mcjc/assets/audio/homepage.m4a">click here to access the file directly</a></p>
        </audio>
        <div id="homepage-video-controls-prompt">
            <em>hover or tap for audio controls</em>
        </div>
        <div id="homepage-video-controls">
            <button class="button" id="unmute-button"><i class="fa fa-volume-off"></i><span class="button-text">Listen</span></button>
            <button class="button" id="play-pause-button"><i class="fa fa-pause"></i><span class="button-text">Pause</span></button>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="background-container">
<div id="primary">
    <?php if ($epigraph = get_theme_option('homepage_text')): ?>
    <div class="homepage-epigraph">
    <?php echo $epigraph; ?>
    </div>
    <?php endif; ?>

    <ul id="homepage-menu">
        <li class="homepage-menu__item">
            <div class='homepage-menu-image'>
              <?php echo common('picture-tag', [
                  'base_filename' =>
                      "/themes/berlin_mcjc/assets/images/home-page-buttons/welcome.jpg",
                  'options' => [
                      'alt' => "",
                      'width' => '250',
                      'height' => '250',
                  ],
              ]); ?>
            </div>
            <div class="homepage-menu-content">
                <h2><?php echo __('Welcome'); ?></h2>
              <?php if (
                  $link_text = get_theme_option('homepage_welcome_text')
              ): ?>
                  <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
              <?php endif; ?>
                <a href="/welcome" class="homepage-menu__item__button button"><?php echo get_theme_option(
                    'homepage_welcome_link_text'
                ) ?? "Click here to get started"; ?></a>
            </div>
        </li>
      <li class="homepage-menu__item">
        <div class='homepage-menu-image'>
          <?php echo common('picture-tag', [
              'base_filename' =>
                  "/themes/berlin_mcjc/assets/images/home-page-buttons/meet_our_neighbors.jpg",
              'options' => ['alt' => "", 'width' => '250', 'height' => '250'],
          ]); ?>
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __('Meet our neighbors'); ?></h2>
          <?php if (
              $link_text = get_theme_option('homepage_meet_our_neighbors_text')
          ): ?>
          <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/people" class="homepage-menu__item__button button"><?php echo get_theme_option(
              'homepage_meet_our_neighbors_link_text'
          ) ?? "View all people"; ?></a>
        </div>
      </li>
      <li class="homepage-menu__item">
        <div class="homepage-menu-image">
            <?php echo common('picture-tag', [
                'base_filename' =>
                    "/themes/berlin_mcjc/assets/images/home-page-buttons/explore_themes.jpg",
                'options' => ['alt' => "", 'width' => '250', 'height' => '250'],
            ]); ?>
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Explore our history"); ?></h2>
          <?php if (
              $link_text = get_theme_option('homepage_explore_themes_text')
          ): ?>
            <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/topics" class="homepage-menu__item__button button"><?php echo get_theme_option(
              'homepage_explore_themes_link_text'
          ) ?? "Browse all topics"; ?></a>
        </div>
      </li>
      <li class="homepage-menu__item">
        <div class="homepage-menu-image">
          <?php echo common('picture-tag', [
              'base_filename' =>
                  "/themes/berlin_mcjc/assets/images/home-page-buttons/add_your_story.jpg",
              'options' => ['alt' => "", 'width' => '250', 'height' => '250'],
          ]); ?>
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Say your piece"); ?></h2>
          <?php if ($link_text = get_theme_option('homepage_respond_text')): ?>
            <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/respond" class="homepage-menu__item__button button"><?php echo get_theme_option(
              'homepage_respond_link_text'
          ) ?? "Respond"; ?></a>
       </div>
      </li>
    </ul>
      <?php if ($have_you_heard = mcjc_get_have_you_heard()): ?>
          <div class="have-you-heard__container">
            <?php foreach ($have_you_heard as $item): ?>
                <div>
                  <div class="have-you-heard__item">
                    <?php echo common('picture-tag', [
                        'base_filename' =>
                            "/themes/berlin_mcjc/assets/images/question-mark.png",
                        'options' => [
                            'alt' => "",
                            'width' => '176',
                            'height' => '176',
                            'class' => 'question-mark',
                        ],
                    ]); ?>
                    <div class="have-you-heard__item__body">
                  <?php echo $item; ?>
                    </div>
                  </div>
                </div>
            <?php endforeach; ?>
          </div>
        <?php echo common('slider-markup', [
            'class' => 'have-you-heard__container',
        ]); ?>
      <?php endif; ?>
  </div>
</div>
<?php echo foot(['bodyid' => 'home']); ?>
