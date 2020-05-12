<?php echo head(['bodyclass' => 'contribution thank-you']); ?>

<div class="title">
    <h1><?php echo __("Add Your Story"); ?></h1>
</div>
<div class='header-background-container add-story-image'>
    <div class="header-background-container-content">
        <!-- <h2 class='image-title'>  </h2> -->
        <h2 class='image-title'><?php echo __("Tell your Story"); ?></h2>
        <div class='image-text'>
            <p>Loerum Ipsoms dolor sit amet, consectetur</p>
            <p>adipiscing elit. Integer suscitpit diam a nulla</p>
            <p>tempus rhoncus. Aliquam erat volutpat</p>
        </div>
        
    </div>
</div>
<div id="primary" class='contribute-content'>
    <h2 class='contribute-title'><?php echo __(
        "Thank you for contributing!"
    ); ?></h2>
    <p class='contribute-paragraph'><?php echo __(
        "Your contribution will show up in the archive once an administrator approves it. Meanwhile, feel free to make another contribution or browse the archive"
    ); ?>
    </p>
    <div class='contribute-button-wrapper'>
            <a class="button contribute" href="<?php echo contribution_contribute_url(); ?>"><?php echo __(
    'Make another contribution'
); ?></a>
            <a class='button browseTopics' href="<?php echo url(
                [],
                'peopleDefault'
            ); ?>"><?php echo __('Browse all Topics'); ?></a>
    </div>
	<?php if (get_option('contribution_open') && !current_user()): ?>
	<p class='contribute-paragraph'><?php echo __(
     "If you would like to interact with the site further, you can use an account that is ready for you. Visit %s, and request a new password for the email you used",
     "<a href='" .
         url('users/forgot-password') .
         "'>" .
         __('this page') .
         "</a>"
 ); ?>
	<?php endif; ?>
</div>
<?php echo foot(); ?>
