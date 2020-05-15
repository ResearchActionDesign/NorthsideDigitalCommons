<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

queue_js_file('contribution-public-form');
$contributionPath = get_option('contribution_page_path');
if (!$contributionPath) {
    $contributionPath = 'contribution';
}
queue_css_file('form');

//load user profiles js and css if needed
if (
    get_option('contribution_user_profile_type') &&
    plugin_is_active('UserProfiles')
) {
    queue_js_file('admin-globals');
    queue_js_file('tinymce.min', 'javascripts/vendor/tinymce');
    queue_js_file('elements');
    queue_css_string("input.add-element {display: block}");
}

$head = ['title' => 'Add Your Story', 'bodyclass' => 'contribution form'];
$imageTitle = __("Tell Your Story");
echo head($head);
?>
<script type="text/javascript">
// <![CDATA[
enableContributionAjaxForm(<?php echo js_escape(
    url($contributionPath . '/type-form')
); ?>);
// ]]>
</script>
<div class="title">
    <div class='header-background-container add-story-image'>
        <div class="header-background-container-content">
            <h2 class='image-title'> <?php echo $imageTitle; ?> </h2>
            <div class='image-text'>
                <p>Loerum Ipsoms dolor sit amet, consectetur</p>
                <p>adipiscing elit. Integer suscitpit diam a nulla</p>
                <p>tempus rhoncus. Aliquam erat volutpat</p>
            </div>
        </div>
    </div>
</div>
<div id="primary">
<?php echo flash(); ?>
    <div class='contribution-content-wrapper'>
        <div class='descriptionParagraph'>
            <p>Description or instructions on how to add your own story, things people might want to add or
                consider, etc. Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos, quis autem.
                Enim debitis sapiente cupiditate deleniti omnis dolorem cumque officiis temporibus voluptatibus a
                explicabo ut inventore, rerum sint sit nam! </p>
        </div>

    <?php if (!($user = current_user()) && !get_option('contribution_open')): ?>
        <?php
        $session = new Zend_Session_Namespace();
        $session->redirect = absolute_url();
        ?>
        <p>You must <a href='<?php echo url(
            'guest-user/user/register'
        ); ?>'>create an account</a> or <a href='<?php echo url(
    'guest-user/user/login'
); ?>'>log in</a> before contributing. You can still leave your identity to site visitors anonymous.</p>        
    <?php else: ?>
        <form method="post" action="" enctype="multipart/form-data">
            <fieldset id="contribution-item-metadata">
                    <div class="inputs contribution">
                        <label for="contribution-type"><?php echo __(
                            "What type of item do you want to contribute?"
                        ); ?></label>
                        <?php $options = get_table_options(
                            'ContributionType'
                        ); ?>
                        <?php $typeId = isset($type) ? $type->id : ''; ?>
                        <?php echo $this->formSelect(
                            'contribution_type',
                            $typeId,
                            ['multiple' => false, 'id' => 'contribution-type'],
                            $options
                        ); ?>
                        <input type="submit" name="submit-type" id="submit-type" value="Select" />
                    </div>
                    <div id="contribution-type-form">
                    <?php if (isset($type)) {
                        include 'type-form.php';
                    } ?>
                    </div>
            </fieldset>

            <fieldset id="contribution-confirm-submit" <?php if (
                !isset($type)
            ) {
                echo 'style="display: none;"';
            } ?>>
                <?php if (isset($captchaScript)): ?>
                    <div id="captcha" class="inputs"><?php echo $captchaScript; ?></div>
                <?php endif; ?>
                <div class="inputs">
                    <?php $public = isset($_POST['contribution-public'])
                        ? $_POST['contribution-public']
                        : 0; ?>
                    <?php echo $this->formCheckbox(
                        'contribution-public',
                        $public,
                        null,
                        ['1', '0']
                    ); ?>
                    <?php echo $this->formLabel(
                        'contribution-public',
                        __('Publish my contribution on the web.')
                    ); ?>
                </div>
                <div class="inputs">
                    <?php $anonymous = isset($_POST['contribution-anonymous'])
                        ? $_POST['contribution-anonymous']
                        : 0; ?>
                    <?php echo $this->formCheckbox(
                        'contribution-anonymous',
                        $anonymous,
                        null,
                        [1, 0]
                    ); ?>
                    <?php echo $this->formLabel(
                        'contribution-anonymous',
                        __("Keep identity private.")
                    ); ?>
                </div>
                <p class='contribute-terms'><?php echo __(
                    "In order to contribute, you must read and agree to the %s",
                    "<a href='" .
                        contribution_contribute_url('terms') .
                        "' target='_blank'>" .
                        __('Terms and Conditions') .
                        ".</a>"
                ); ?></p>
                <div class="inputs">
                    <?php $agree = isset($_POST['terms-agree'])
                        ? $_POST['terms-agree']
                        : 0; ?>
                    <?php echo $this->formCheckbox(
                        'terms-agree',
                        $agree,
                        null,
                        ['1', '0']
                    ); ?>
                    <?php echo $this->formLabel(
                        'terms-agree',
                        __('I agree to the Terms and Conditions.')
                    ); ?>
                </div>
                <?php echo $this->formSubmit(
                    'form-submit',
                    __('Contribute your Story'),
                    [
                        'class' => 'submitinput',
                    ]
                ); ?>
            </fieldset>
            <?php echo $csrf; ?>
        </form>
    <?php endif; ?>
    </div>
</div>
<?php echo foot();
