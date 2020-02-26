</div><!-- end content -->

<footer role="contentinfo">

    <div id="footer-content" class="center-div">
        <div id="footer-nav">
            <nav><?php echo public_nav_main()->setMaxDepth(0); ?></nav>
        </div>
        <div id="footer-logo">
          <a href="https://jacksoncenter.info"><img src="/themes/berlin_mcjc/images/mcjc_logo.jpg" alt="Marian Cheek Jackson Center"/></a>
        </div>
        <div id="footer-text">
            <?php if($footerText = get_theme_option('Footer Text')): ?>
                <div id="custom-footer-text">
                    <p><?php echo get_theme_option('Footer Text'); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
                <div id="footer-copyright"><?php echo $copyright; ?></div>
            <?php endif; ?>
     <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>
</footer>
    </div>
      <div id="footer-omeka"><?php echo __('Proudly powered by <a href="http://omeka.org">Omeka</a>.'); ?></div>
    </div>
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
