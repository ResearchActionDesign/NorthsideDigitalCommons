<?php
/* end content div from header.php */
?>
</article>
<footer>
    <div id="footer-content" class="center-div">
        <div id="footer-nav">
            <nav><?php echo public_nav_main()->setMaxDepth(0); ?></nav>
        </div>
        <div id="footer-logo">
          <a href="https://jacksoncenter.info">
            <?php echo common('picture-tag', [
                'base_filename' =>
                    "/themes/berlin_mcjc/assets/images/icons/mcjc_logo.png",
                'options' => [
                    'alt' => "Marian Cheek Jackson Center",
                    'width' => '200',
                    'height' => '109',
                ],
            ]); ?>
          </a>
        </div>
        <div id="footer-text">
            <?php if ($footerText = get_theme_option('Footer Text')): ?>
                <div id="custom-footer-text">
                    <?php echo get_theme_option('Footer Text'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (
                get_theme_option('Display Footer Copyright') == 1 &&
                ($copyright = option('copyright'))
            ): ?>
                <div id="footer-copyright"><?php echo $copyright; ?></div>
            <?php endif; ?>
        </div>
    </div>
     <?php fire_plugin_hook('public_footer', ['view' => $this]); ?>
    <div id="footer-omeka"><?php echo __(
        'Designed and built by <a href="https://rad.cat">Research Action Design</a>, proudly powered by <a href="https://omeka.org">Omeka</a>.'
    ); ?></div>
    <!-- end footer-content -->
</footer>


<script type="text/javascript">
    jQuery(document).ready(function(){
        FromTheRockWall.filters();
        FromTheRockWall.grids();
        FromTheRockWall.downloads();
        FromTheRockWall.searchToggle();
        FromTheRockWall.didYouKnow();
        FromTheRockWall.audioGreeting();
        FromTheRockWall.readMore();
    });
</script>
<script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
<noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt=""/></noscript>

</body>

</html>
