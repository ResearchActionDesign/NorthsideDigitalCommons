<?php
/* end content div from header.php */
?>
</article>
<footer role="contentinfo">
    <div id="footer-content" class="center-div">
        <div id="footer-nav">
            <nav><?php echo public_nav_main()->setMaxDepth(0); ?></nav>
        </div>
        <div id="footer-logo">
          <a href="https://jacksoncenter.info"><img src="/themes/berlin_mcjc/assets/images/icons/mcjc_logo.png" alt="Marian Cheek Jackson Center" loading="lazy"/></a>
        </div>
        <div id="footer-text">
            <?php if ($footerText = get_theme_option('Footer Text')): ?>
                <div id="custom-footer-text">
                    <p><?php echo get_theme_option('Footer Text'); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (
                get_theme_option('Display Footer Copyright') == 1 &&
                ($copyright = option('copyright'))
            ): ?>
                <div id="footer-copyright"><?php echo $copyright; ?></div>
            <?php endif; ?>
     <?php fire_plugin_hook('public_footer', ['view' => $this]); ?>
</footer>
      <div id="footer-omeka"><?php echo __(
          'Designed and built by <a href="https://rad.cat">Research Action Design</a>, proudly powered by <a href="https://omeka.org">Omeka</a>.'
      ); ?></div>
        <!-- end footer-content -->


<script type="text/javascript">
    jQuery(document).ready(function(){
        Omeka.showAdvancedForm();
        Omeka.skipNav();
        Omeka.megaMenu();
        Berlin.dropDown();
    });
</script>

</body>

</html>
