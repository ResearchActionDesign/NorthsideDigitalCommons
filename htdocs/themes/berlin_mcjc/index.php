<?php echo head(['bodyid' => 'home']); ?>
<div class="homepage-title">
    <h1>From the Rock Wall
        <span class="subtitle">Living Histories of Black Chapel Hill/Carrboro</span>
    </h1>
    <blockquote>Without the past, you have no future.</blockquote>
    <cite>Mrs. Marian Cheek Jackson</cite>
</div>
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

  <?php if ($have_you_heard = mcjc_get_have_you_heard()): ?>
      <div class="have-you-heard">
          <p class="have-you-heard__content">
            <?php echo $have_you_heard; ?>
          </p>
      </div>
  <?php endif; ?>

    <ul id="homepage-menu">
      <li class="homepage-menu__item">
        <div class='homepage-menu-image'>
         <img src="/themes/berlin_mcjc/assets/images/buttons/meet_our_neighbors.png" alt="">
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
      </li>
      <li class="homepage-menu__item">
        <div class="homepage-menu-image">
          <img src="/themes/berlin_mcjc/assets/images/buttons/explore_themes.png" alt="">
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
      </li>
      <li class="homepage-menu__item">
        <div class="homepage-menu-image">
         <img src="/themes/berlin_mcjc/assets/images/buttons/add_your_story.png" alt="">
        </div>
        <div class="homepage-menu-content">
          <h2><?php echo __("Join the conversation"); ?></h2>
          <?php if ($link_text = get_theme_option('homepage_respond_text')): ?>
            <p class="homepage-menu__item__text"><?php echo $link_text; ?></p>
          <?php endif; ?>
          <a href="/respond" class="homepage-menu__item__button button"><?php echo __(
              "Respond"
          ); ?></a>
       </div>
      </li>
    </ul>
  </div>
<?php echo foot(); ?>
