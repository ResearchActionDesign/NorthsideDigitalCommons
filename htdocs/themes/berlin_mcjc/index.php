<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>
<div id="primary">
    <?php if (get_theme_option('homepage_video')): ?>
    <div id="homepage-video">
        <video width="100%" preload="auto" autoplay loop poster="/themes/berlin_mcjc/images/homepage_video.jpg" muted>
            <source src="/themes/berlin_mcjc/video/MCJCOrig--vp9.webm" type='video/webm;codecs="vp9, vorbis"'>
            <source src="/themes/berlin_mcjc/video/MCJCOrig.webm" type='video/webm;codecs="vp8, vorbis"'>
            <source src="/themes/berlin_mcjc/video/MCJCOrig.mp4" type='video/mp4"'>
        </video>
    </div>
    <?php endif; ?>

  <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <div id="homepage-text"><?php echo $homepageText; ?></div>
  <?php endif; ?>
    <div id="homepage-menu">
      <div class="homepage-menu__item">
        <img src="https://via.placeholder.com/250">
        <h2>Meet our neighbors</h2>
        <?php if ($link_text = get_theme_option('homepage_meet_our_neighbors_text')): ?>
        <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
        <?php endif; ?>
        <a href="/people" class="homepage-menu__item__button">View all people</a>
      </div>
      <div class="homepage-menu__item">
        <img src="https://via.placeholder.com/250">
        <h2>Explore themes</h2>
        <?php if ($link_text = get_theme_option('homepage_explore_themes_text')): ?>
          <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
        <?php endif; ?>
        <a href="/topics" class="homepage-menu__item__button">Explore themes</a>
      </div>
      <div class="homepage-menu__item">
        <img src="https://via.placeholder.com/250">
        <h2>Explore themes</h2>
        <?php if ($link_text = get_theme_option('homepage_tell_your_story_text')): ?>
          <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
        <?php endif; ?>
        <a href="/add-your-story" class="homepage-menu__item__button">Add your story</a>
      </div>
    </div>
  <?php if ($map_html = get_theme_option('homepage_map')): ?>
  <div id="homepage-map">
    <h2>The Northside Neighborhoods</h2>
    <div id="homepage-map__map">
    <?php echo $map_html; ?>
    </div>
  </div>
  <?php endif; ?>

  </div>
</div>
<!-- end primary -->

<?php echo foot(); ?>
