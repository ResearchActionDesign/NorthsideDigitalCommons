<?php echo head(['bodyid' => 'home']); ?>
<div class="homepage-title">
    <h1>From the Rock Wall
        <span class="subtitle">Living Histories of Black Chapel Hill/Carrboro</span>
    </h1>
</div>
<div class="video-container">
<?php if (get_theme_option('homepage_video')): ?>
    <div id="homepage-video">
        <video width="100%" preload="auto" autoplay loop poster="/themes/berlin_mcjc/assets/images/homepage_video.jpg" muted playsinline>
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig--vp9.webm" type="video/webm;codecs=vp9, vorbis">
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.webm" type="video/webm;codecs=vp8, vorbis">
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.mp4" type="video/mp4">
        </video>
    </div>
    <?php endif; ?>
</div>
<div class="background-container">
<div id="primary">
    <div class="homepage-epigraph">
    <blockquote>Without the past, you have no future.</blockquote>
    <cite>Mrs. Marian Cheek Jackson</cite>
    </div>

    <ul id="homepage-menu">
      <li class="homepage-menu__item">
        <div class='homepage-menu-image'>
          <?php echo common('picture-tag', [
              'base_filename' =>
                  "/themes/berlin_mcjc/assets/images/buttons/meet_our_neighbors.jpg",
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
                    "/themes/berlin_mcjc/assets/images/buttons/explore_themes.jpg",
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
                  "/themes/berlin_mcjc/assets/images/buttons/add_your_story.jpg",
              'options' => ['alt' => "", 'width' => '250', 'height' => '250'],
          ]); ?>
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Join the conversation"); ?></h2>
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
                  <?php echo $item; ?>
                  </div>
                </div>
            <?php endforeach; ?>
          </div>
          <ul class="slider-controls" id="have-you-heard__controls" aria-label="Carousel Navigation" tabindex="0">
              <li class="prev" aria-controls="customize" tabindex="-1" data-controls="prev" aria-label="Previous slide">
                  <i class="fa fa-arrow-circle-left"></i>
              </li>
              <li class="next" aria-controls="customize" tabindex="-1" data-controls="next" aria-label="Next slide">
                  <i class="fa fa-arrow-circle-right"></i>
              </li>
          </ul>
      <?php endif; ?>
  </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>
<script type="module">
    var slider = tns({
        container: '.have-you-heard__container',
        items: 1,
        gutter: 16,
        controlsContainer: '#have-you-heard__controls',
        nav: false,
        responsive: {
            "768": {
                edgePadding: 140,
            }
        }
    });
</script>
<?php echo foot(['bodyid' => 'home']); ?>
