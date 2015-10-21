<?php
$title = __('Browse Documentary Initiatives');
echo head(array('title' => $title, 'bodyclass' => 'exhibits browse'));
?>
<h1><?php echo $title; ?></h1>
<?php if (count($exhibits) > 0): ?>
<p class="introDescription"><em><img class="docInitiatives" src="/files/fullsize/80b014794dda61fa734880241693095c.jpg" width="300px" height="200px" /><?php echo __('We are honored to showcase the spoken word performances, interactive exhibits, audio tours, and other creative documentary initiatives that emerged from community oral histories and the desire of students and staff to graciously respond to the stories they are privileged to hear. The site currently features The Struggle Continues, an exhibit honoring those local leaders who fought during a critical part of the freedom struggle in the 1960s.  Spoken word performances and Neighborhood Audio Tour coming soon!
 ') ?></em></p><hr />
<!--<nav class="navigation secondary-nav">
    <?php echo nav(array(
        array(
            'label' => __('Browse All'),
            'uri' => url('exhibits')
        ),
        array(
            'label' => __('Browse by Tag'),
            'uri' => url('exhibits/tags')
        )
    )); ?>
</nav>-->

<?php echo pagination_links(); ?>

<?php $exhibitCount = 0; ?>
<?php foreach (loop('exhibit') as $exhibit): ?>
	<!-- the code directly below makes sure that Oral History Trust does not appear on the "Browse Documentary Initiatives page --> 
	<?php if ($exhibit->slug == 'oral-history-trust'):
		continue; ?>
	<?php endif; ?>
	
    <?php $exhibitCount++; ?>
    <div class="exhibit <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
        <h2><?php echo link_to_exhibit(); ?></h2>
        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
        <div class="description"><?php echo $exhibitDescription; ?></div>
        <?php endif; ?>
        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
        <p class="tags"><?php echo $exhibitTags; ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php else: ?>
<p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
