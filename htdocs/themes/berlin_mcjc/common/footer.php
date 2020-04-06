</div><!-- end content -->

<footer role="contentinfo">

    <div id="footer-content" class="center-div">
        <nav><?php echo public_nav_main()->setMaxDepth(0); ?></nav>
        <p><a href="https://jacksoncenter.info"><img src="/themes/berlin_mcjc/images/mcjc_logo.jpg" alt="Marian Cheek Jackson Center"/></a></p>
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
      <p><?php echo __(
          'Proudly powered by <a href="http://omeka.org">Omeka</a>.'
      ); ?></p>
    </div>
    <!-- end footer-content -->

     <?php fire_plugin_hook('public_footer', ['view' => $this]); ?>

</footer>

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
