<?php echo head(['bodyid' => 'home']); ?>
<div class="video-container">
<?php if (get_theme_option('homepage_video')): ?>
    <div id="homepage-video">
        <video width="100%" preload="auto" autoplay loop poster="/themes/berlin_mcjc/assets/images/homepage_video.jpg" muted>
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig--vp9.webm" type='video/webm;codecs="vp9, vorbis"'>
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.webm" type='video/webm;codecs="vp8, vorbis"'>
            <source src="/themes/berlin_mcjc/assets/video/MCJCOrig.mp4" type='video/mp4"'>
        </video>
    </div>
    <?php endif; ?>
</div>
<div id="primary">
    <div id="homepage-menu">
      <div class="homepage-menu__item">
        <div class='homepage-menu-image'>
         <img src="/themes/berlin_mcjc/assets/images/buttons/meet_our_neighbors.png" alt="" loading="lazy">
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __('Meet our neighbors'); ?></h2>
          <?php if (
              $link_text = get_theme_option('homepage_meet_our_neighbors_text')
          ): ?>
          <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/people" class="homepage-menu__item__button button"><?php echo __(
              "View all people"
          ); ?></a>
        </div>
      </div>
      <div class="homepage-menu__item">
        <div class="homepage-menu-image">
          <img src="/themes/berlin_mcjc/assets/images/buttons/explore_themes.png" alt="" loading="lazy">
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Explore our history"); ?></h2>
          <?php if (
              $link_text = get_theme_option('homepage_explore_themes_text')
          ): ?>
            <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/topics" class="homepage-menu__item__button button"><?php echo __(
              "Browse all topics"
          ); ?></a>
        </div>
      </div>
      <div class="homepage-menu__item">
        <div class="homepage-menu-image">
         <img src="/themes/berlin_mcjc/assets/images/buttons/add_your_story.png" alt="" loading="lazy">
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Join the conversation"); ?></h2>
          <?php if (
              $link_text = get_theme_option('homepage_tell_your_story_text')
          ): ?>
            <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/add-a-story" class="homepage-menu__item__button button"><?php echo __(
              "Add a story"
          ); ?></a>
       </div>
      </div>
    </div>
  <?php if ($map_html = get_theme_option('homepage_map')): ?>
  <div id="homepage-map">
    <h2><?php echo __("The Northside Neighborhoods"); ?></h2>
    <div id="homepage-map__map">
    <?php echo $map_html; ?>
    </div>
  </div>
  <?php endif; ?>

  </div>
</div>
<!-- end primary -->

<?php echo foot(); ?>
