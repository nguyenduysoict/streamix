<div class="ui segment arconfig-panel <?php echo (in_array($activeSubmit, array('ArContactUsConfigButton', 'ArContactUsConfigMobileButton')))? '' : 'hidden' ?>" id="arcontactus-button">
    <div class="ui top attached tabular menu">
        <a class="item <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobileButton')))? 'active' : '' ?>" data-tab="desktop-button"><?php echo __('Desktop', 'ar-contactus') ?></a>
        <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobileButton')))? 'active' : '' ?>" data-tab="mobile-button"><?php echo __('Mobile', 'ar-contactus') ?></a> 
    </div>
    <div class="ui bottom attached tab segment <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobileButton')))? 'active' : '' ?>" data-tab="desktop-button">
        <?php echo $buttonConfig->getFormHelper()->render() ?>
    </div>
    <div class="ui bottom attached tab segment <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobileButton')))? 'active' : '' ?>" data-tab="mobile-button">
        <?php echo $mobileButtonConfig->getFormHelper()->render() ?>
    </div>
</div>