<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file('style');

    echo head_css();
    ?>
    <!-- JavaScripts -->
	<?php queue_js_file('vendor/jquery.flexslider-min', 'javascripts'); ?>
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('globals'); ?>
	<?php echo head_js(); ?>	
<script type="text/javascript">
  jQuery(window).load(function() {
	jQuery('.flexslider').flexslider({
	animation: "fade",
	controlNav: true,
  });
 }); 
</script>
    
</head>
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
        <header>
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>

            <div id="search-container">
                <h2>Search</h2>
                    <?php echo search_form(array('show_advanced'=>TRUE)); ?>
            </div>
			
			<div id="subtitle">
			<br />
			Without the past, we have no future
			</div>
        </header>

         <div id="primary-nav">
             <?php
                  echo public_nav_main();
             ?>
	
         </div>
  
         <div id="mobile-nav">
             <?php
                  echo public_nav_main();
             ?>
         </div>			
			<!-- FlexSlider Images -->
			<div id="theme_header_image" class="flexslider">
	    	   	<ul class="slides">
					<li>
					<img src="<?php echo img('image1_resized.jpg'); ?>" />					
					 <p class="flex-caption"><a href="/exhibits/show/oral-history-trust/chapel-hill-civil-rights"><em>"Chapel Hill Civil Rights" Oral Histories</em> View Here &#10142;</a></p>
					</li>
					<li>
					<img src="<?php echo img('image3_resized.jpg'); ?>" />	
					<p class="flex-caption"><a href="/exhibits/show/oral-history-trust"><em>Listen to audio interviews in the Oral History Trust</em> here &#10142;</a></p>
					</li>
					<li>
					<img src="<?php echo img('image4_resized.jpg'); ?>" />
					<p class="flex-caption"><a href="/exhibits/show/the-struggle-continues--weavin/mighty-mighty-tigers"><em>"Mighty Mighty Tigers!"</em> View Here &#10142;</a></p>
					</li>
					<li>
					<img src="<?php echo img('image2_resized.jpg'); ?>" />
					<p class="flex-caption"><a href="/exhibits/show/the-struggle-continues--weavin/we-shall-not-be-moved"><em>"We Shall Not Be Moved"</em> View Here &#10142;</a></p>
					</li>
					<li>
					<img src="<?php echo img('image5_resized.jpg'); ?>" />
					<p class="flex-caption"><a href="/exhibits/show/the-struggle-continues--weavin/like-going-home-to-heaven"><em>"Like Going Home to Heaven"</em> View here &#10142;</a></p>
					</li>
					<li>
					<img src="<?php echo img('image7_resized.jpg'); ?>" />
					<p class="flex-caption"><a href="/exhibits/show/oral-history-trust/community-routes"><em>"Community Routes" Oral Histories</em> View Here &#10142;</a></p>
					</li>
				</ul>
		</div>	    
			<!-- End FlexSlider Images --> 
		
    <div id="content">

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
