<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>
<div id="primary">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <p><?php echo $homepageText; ?></p>
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item -->
    <div id="featured-item">
        <h2><?php echo __('Featured Item'); ?></h2>
        <table>
			<tr>
			<td><?php echo random_featured_items(0); ?></td>
			</tr>
		</table>
    </div><!--end featured-item-->
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Collection')): ?>
    <!-- Featured Collection 
    <div id="featured-collection">
        <h2><?php echo __('On Display At The Center'); ?></h2>
        <?php echo random_featured_collection(); ?><br />
    </div> end featured collection -->
    <?php endif; ?>
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
    <!-- Featured Exhibit -->
    <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
    <?php endif; ?>
</div><!-- end primary -->

<div id="secondary">
    <!-- Recent Items -->
    <div id="recent-items">
		<h2><?php echo __('The Northside Memory Project'); ?></h2>
		<p><iframe width="435" height="275" src="//www.youtube.com/embed/83grc87RnNw" frameborder="0" allowfullscreen></iframe><br><br></p>
        <h2><?php echo __('Words From Our Oral Histories'); ?></h2>
<p>
So when the Civil Rights movement came around he told us we couldn’t go do anything with civil rights because he didn’t know how that was going to affect his business. Because he’s dealing with people who are going to, you know, maybe be his customers. So we wanted to be a part of it, we knew we couldn’t go to jail. But we would sneak and we would go be a part of the movement.<br> 
-- <a href="http://archives.jacksoncenter.info/linda-carver" target="_blank"><em>Linda Carver</em></a> in <a href="http://archives.jacksoncenter.info/linda-carver" target="_blank">Linda</a> and <a href="http://archives.jacksoncenter.info/terry-carver" target="_blank">Terry Carver</a> Interview
</p>

<p>
My mother did domestic work, and you know sometimes I look back now and I can remember when she was making maybe 25 bucks a week. She had six boys and, you know, some way or another she knew how to, you know, make ends do. Because we never went hungry, you know. A lot of times, we had to cook for ourselves, because, you know, tight work schedule she’d be doing. It used to bother me sometimes, you know, here she is cooking for this white family and, you know, but she [pause] she really took care of us.<br>
-- <a href="http://archives.jacksoncenter.info/paul-caldwell" target="_blank"><em>Paul Caldwell</em></a> in <a href="http://archives.jacksoncenter.info/paul-caldwell" target="_blank">Paul Caldwell</a> 2013 Interview
</p>

    </div><!-- end recent-items -->
    
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>

</div><!-- end secondary -->
<?php echo foot(); ?>
