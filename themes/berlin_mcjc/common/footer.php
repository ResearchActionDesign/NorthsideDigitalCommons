</div><!-- end content -->

<footer>

    <div id="footer-content" class="center-div">
        <?php if($footerText = get_theme_option('Footer Text')): ?>
        <div id="custom-footer-text">
            <p><?php echo get_theme_option('Footer Text'); ?></p>
        </div>
        <?php endif; ?>
        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>		<p><h3>A project created by The Marian Cheek Jackson Center for Saving and Making History</h3>        		<a href="https://www.facebook.com/mcjcenter" target="_blank"><img src="<?php echo img('facebook.png'); ?>" /></a>&nbsp;<a href="https://twitter.com/MCJCenter" target="_blank"><img src="<?php echo img('twitter.png'); ?>" /></a>&nbsp;		<a href="http://jacksoncenter.info/" target="_blank"><img src="<?php echo img('jacksonCenterLogo.PNG'); ?>" /></a>		</p>
        <?php endif; ?>
       <div id="footer-copyright">&copy; 2014 <a href="http://www.jacksoncenter.info/" target="_blank">Jackson Center</a>. <?php echo $copyright; ?>&nbsp;Powered by <a href="http://omeka.org">Omeka</a></div>
    </div><!-- end footer-content -->

     <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>

</footer>

<script type="text/javascript">
    jQuery(document).ready(function(){
        Omeka.showAdvancedForm();
               Omeka.dropDown();
    });
</script>
<br />
</body>

</html>
