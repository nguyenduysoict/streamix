<div class="ui segment arconfig-panel <?php echo (in_array($activeSubmit, array('ArContactUsConfigPrompt', 'ArContactUsConfigMobilePrompt')))? '' : 'hidden' ?>" id="arcontactus-prompt">
    <div class="ui top attached tabular menu">
        <a class="item <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobilePrompt')))? 'active' : '' ?>" data-tab="desktop-prompts"><?php echo __('Desktop', 'ar-contactus') ?></a>
        <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobilePrompt')))? 'active' : '' ?>" data-tab="mobile-prompts"><?php echo __('Mobile', 'ar-contactus') ?></a> 
    </div>
    <div class="ui bottom attached tab segment <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobilePrompt')))? 'active' : '' ?>" data-tab="desktop-prompts">
        <?php echo $promptConfig->getFormHelper()->render() ?>
    </div>
    <div class="ui bottom attached tab segment <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobilePrompt')))? 'active' : '' ?>" data-tab="mobile-prompts">
        <?php echo $mobilePromptConfig->getFormHelper()->render() ?>
    </div>
</div>