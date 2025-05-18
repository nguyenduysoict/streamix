<div class="ui segment arconfig-panel <?php echo (in_array($activeSubmit, array('ArContactUsConfigMenu', 'ArContactUsConfigMobileMenu')))? '' : 'hidden' ?>" id="arcontactus-menu">
    <div class="ui top attached tabular menu">
        <a class="item <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobileMenu')))? 'active' : '' ?>" data-tab="desktop-menu"><?php echo __('Desktop', 'ar-contactus') ?></a>
        <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobileMenu')))? 'active' : '' ?>" data-tab="mobile-menu"><?php echo __('Mobile', 'ar-contactus') ?></a> 
    </div>
    <div class="ui bottom attached tab segment <?php echo (!in_array($activeSubmit, array('ArContactUsConfigMobileMenu')))? 'active' : '' ?>" data-tab="desktop-menu">
        <?php echo $menuConfig->getFormHelper()->render() ?>
    </div>
    <div class="ui bottom attached tab segment <?php echo (in_array($activeSubmit, array('ArContactUsConfigMobileMenu')))? 'active' : '' ?>" data-tab="mobile-menu">
        <?php echo $mobileMenuConfig->getFormHelper()->render() ?>
    </div>
</div>