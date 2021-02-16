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
        'Designed and built by <a href="https://rad.cat">Research Action Design</a>, powered by <a href="https://omeka.org">Omeka</a>.'
    ); ?></div>
    <!-- end footer-content -->
</footer>
<?php echo head_css(); ?>
<script
        src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
        crossorigin="anonymous"></script>
<?php echo head_js(false); ?>
<?php
$commit_hash = "1";
@include 'commit-hash.php';
?>
<script type="text/javascript" src="/themes/berlin_mcjc/javascripts/lity.min.js" async defer></script>
<script type="text/javascript" src="/themes/berlin_mcjc/javascripts/sharer.min.js" async defer></script>
<script type="text/javascript" src="/themes/berlin_mcjc/javascripts/frw.js?v=<?php echo $commit_hash; ?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        FromTheRockWall.searchToggle();
        FromTheRockWall.homepageAudio();
        FromTheRockWall.grids();
        FromTheRockWall.filters();
        FromTheRockWall.downloads();
        FromTheRockWall.audioGreeting();
        FromTheRockWall.readMore();
    });
</script>
<script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
<noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt=""/></noscript>
</body>
</html>
