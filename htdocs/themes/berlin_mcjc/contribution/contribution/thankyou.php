<?php echo head(array('bodyclass' => 'contribution thank-you')); ?>
<div class="title">
    <h1><?php echo __("Add Your Story"); ?></h1>
</div>
<div id="primary">
    <h2><?php echo __("Thank you for contributing!"); ?></h2>
    <p><?php echo __("Your contribution will show up in the archive once an administrator approves it."); ?>
	</p>
    <a class="button" href="<?php echo contribution_contribute_url(); ?>"><?php echo __('Add another story'); ?></a>
    <a class='button' href="<?php echo url(array(), 'peopleDefault'); ?>"><?php echo __('View more stories'); ?></a>
	<?php if(get_option('contribution_open') && !current_user()): ?>
	<p><?php echo __("If you would like to interact with the site further, you can use an account that is ready for you. Visit %s, and request a new password for the email you used", "<a href='" . url('users/forgot-password') . "'>" . __('this page') . "</a>"); ?>
	<?php endif; ?>
</div>
<?php echo foot(); ?>
