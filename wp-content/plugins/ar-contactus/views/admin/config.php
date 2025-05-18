<?php 
if (empty($activeSubmit) && (isset($_GET['paged']) || isset($_GET['orderby']) || isset($_GET['arcontactus_requests']))){
    $activeSubmit = 'arcontactus-requests';
}?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<div id="arcontactus-plugin-container">
    <div class="arcontactus-masthead">
        <div class="arcontactus-masthead__inside-container">
            <div class="arcontactus-masthead__logo-container">
                <?php echo sprintf(__('All-in-one contact button %swith call-back request feature%s', 'ar-contactus'), '<small>', '</small>') ?>
            </div>
        </div>
    </div>
    <div class="arcontactus-body">
        <?php if (!isset($activated['success']) || !$activated['success']){?>
            <div class="ui red message">
                <b><?php echo __('Plugin is not activated.', 'ar-contactus') ?></b> <?php echo __('You will not receive updates automaticaly.', 'ar-contactus') ?>
                <a href="#" onclick="jQuery('#arcu-about-tab').click(); return false;"><?php echo __('Please activate plugin.', 'ar-contactus') ?></a>
            </div>
        <?php } ?>
        <?php if ($success){?>
            <div class="ui success message">
                <?php echo $success ?>
                <?php if ($importSuccess) {?>
                    <?php echo __('Page will be reloaded after 3 seconds', 'ar-contactus') ?>
                    <script>
                        setTimeout(function(){
                            location.assign('<?php echo admin_url('options-general.php?page=ar-contactus-key-config') ?>');
                        }, 3000)
                    </script>
                <?php } ?>
            </div>
            
        <?php } ?>
        <?php if ($errors){?>
            <?php foreach ($errors as $fieldErrors){?>
                <?php foreach ($fieldErrors as $error){?>
                    <div class="ui negative message">
                        <?php echo $error ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical fluid pointing menu" id="acrontactus-menu">
                    <a class="item <?php echo ($activeSubmit == 'ArContactUsConfigGeneral' || empty($activeSubmit))? 'active' : '' ?>" data-target="#arcontactus-general">
                        <?php echo __('General configuration', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigButton', 'ArContactUsConfigMobileButton')))? 'active' : '' ?>" data-target="#arcontactus-button">
                        <?php echo __('Button settings', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigMenu', 'ArContactUsConfigMobileMenu')))? 'active' : '' ?>" data-target="#arcontactus-menu">
                        <?php echo __('Menu settings', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'ArContactUsConfigPopup')? 'active' : '' ?>" data-target="#arcontactus-callback">
                        <?php echo __('Integration settings', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'ArContactUsConfigForms')? 'active' : '' ?>" data-target="#arcontactus-forms">
                        <?php echo __('Forms', 'ar-contactus') ?>
                        <div class="ui red left mini label">new</div>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'ArContactUsConfigEmails')? 'active' : '' ?>" data-target="#arcontactus-emails">
                        <?php echo __('Email templates', 'ar-contactus') ?>
                        <div class="ui red left mini label">new</div>
                    </a>
                    <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigPrompt', 'ArContactUsConfigMobilePrompt')))? 'active' : '' ?>" data-target="#arcontactus-prompt">
                        <?php echo __('Prompt settings', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo (in_array($activeSubmit, array('ArContactUsConfigWelcome')))? 'active' : '' ?> <?php echo ($menuConfig->menu_layout == 'personal' || $mobileMenuConfig->menu_layout == 'personal')? '' : 'hidden' ?>" data-target="#arcontactus-welcome">
                        <?php echo __('Welcome messages', 'ar-contactus') ?>
                        <div class="ui red left mini label">new</div>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'ArContactUsConfigLiveChat')? 'active' : '' ?>" data-target="#arcontactus-livechat">
                        <?php echo __('Live chat integrations', 'ar-contactus') ?>
                    </a>
                    <a class="item" data-target="#arcontactus-prompt-items">
                        <?php echo __('Prompt messages', 'ar-contactus') ?>
                    </a>
                    <a class="item" data-target="#arcontactus-items">
                        <?php echo __('Menu items', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'arcontactus-requests')? 'active' : '' ?>" href="<?php echo admin_url('admin.php?page=ar-contactus-key-requests') ?>">
                        <?php echo __('Callback requests', 'ar-contactus') ?>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'arcontactus-requests')? 'active' : '' ?>" href="<?php echo admin_url('admin.php?page=ar-contactus-email-requests') ?>">
                        <?php echo __('Email requests', 'ar-contactus') ?>
                        <div class="ui red left mini label">new</div>
                    </a>
                    <a class="item <?php echo ($activeSubmit == 'importDataSubmit' || $activeSubmit == 'migrateSettingsSubmit')? 'active' : '' ?>" data-target="#arcontactus-data">
                        <?php echo __('Export/import', 'ar-contactus') ?>
                    </a>
                    <a class="item" href="https://plugins.areama.net/ar-contactus/docs/" target="_blank">
                        <?php echo __('Help', 'ar-contactus') ?>
                        <span class="arcu-icon-svg">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-external-link fa-w-16 fa-2x"><path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM474.67,0H316a28,28,0,0,0-28,28V46.71A28,28,0,0,0,316.79,73.9L384,72,135.06,319.09l-.06.06a24,24,0,0,0,0,33.94l23.94,23.85.06.06a24,24,0,0,0,33.91-.09L440,128l-1.88,67.22V196a28,28,0,0,0,28,28H484a28,28,0,0,0,28-28V37.33h0A37.33,37.33,0,0,0,474.67,0Z" class=""></path></svg>
                        </span>
                    </a>
                    <a class="item" id="arcu-about-tab" data-target="#arcontactus-about">
                        <?php echo __('About', 'ar-contactus') ?>
                    </a>
                </div>
            </div>
            <div class="twelve wide stretched column" id="arcontactus-tabs">
                <span class="hidden"></span>
                <?php echo ArContactUsAdmin::render('/admin/_general.php', array(
                    'generalConfig' => $generalConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_button.php', array(
                    'buttonConfig' => $buttonConfig,
                    'activeSubmit' => $activeSubmit,
                    'mobileButtonConfig' => $mobileButtonConfig
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_menu.php', array(
                    'menuConfig' => $menuConfig,
                    'buttonConfig' => $buttonConfig,
                    'mobileMenuConfig' => $mobileMenuConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_callback.php', array(
                    'popupConfig' => $popupConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_forms.php', array(
                    'formsConfig' => $formsConfig,
                    'activeSubmit' => $activeSubmit,
                    'defaultLang' => $defaultLang
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_emails.php', array(
                    'emailsConfig' => $emailsConfig,
                    'activeSubmit' => $activeSubmit,
                    'defaultLang' => $defaultLang
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_live_chats.php', array(
                    'liveChatsConfig' => $liveChatsConfig,
                    'buttonConfig' => $buttonConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_prompt.php', array(
                    'promptConfig' => $promptConfig,
                    'mobilePromptConfig' => $mobilePromptConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_welcome.php', array(
                    'menuConfig' => $menuConfig,
                    'mobileMenuConfig' => $mobileMenuConfig,
                    'welcomeConfig' => $welcomeConfig,
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_items.php', array(
                    'items' => $items,
                    'buttonConfig' => $buttonConfig,
                    'isWPML' => $isWPML
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_prompt_items.php', array(
                    'items' => $promptItems
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_data.php', array(
                    'activeSubmit' => $activeSubmit
                )) ?>
                <?php echo ArContactUsAdmin::render('/admin/_about.php', array(
                    'activated' => $activated,
                    'arcu_channel' => $arcu_channel
                )) ?>
                <span class="hidden"></span>
            </div>
        </div>
    </div>
</div>

<div class="ui modal small" id="arcontactus-prompt-modal">
    <i class="close icon"></i>
    <div class="header" id="arcontactus-prompt-modal-title">
        <?php echo __('Add item', 'ar-contactus') ?>
    </div>
    <form id="arcontactus-prompt-form" method="POST" onsubmit="arCU.prompt.save(); return false;">
        <input type="hidden" id="arcontactus_prompt_id" name="id" data-serializable="true" autocomplete="off" data-default=""/>
        <div class="ui form" style="padding: 20px;">
            <div class="ui grid">
                <div class="row">
                    <div class="two wide column">
                    </div>
                    <div class="twelve wide column">
                        <?php if ($isWPML){?>
                            <div class="field required">
                                <label><?php echo __('Message', 'ar-contactus') ?></label>
                                <div class="ui grid arcu-lang-group" id="arcontactus_prompt_message">
                                    <div class="sixteen column row">
                                        <div class="fourteen wide column arcu-lang-content">
                                            <?php foreach($languages as $k => $lang) {?>
                                            <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                                <textarea data-lang-field="true" data-serializable="true" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" rows="3" id="arcontactus_prompt_message<?php echo ('_' . $k) ?>" name="message"></textarea>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="two wide column arcu-lang">
                                            <div class="ui inline dropdown button">
                                                <div class="text">
                                                    <img class="ui image" src="<?php echo $languages[$defaultLang]['country_flag_url'] ?>">
                                                    <?php echo $languages[$defaultLang]['language_code'] ?>
                                                </div>
                                                <i class="dropdown icon"></i>
                                                <div class="menu">
                                                <?php foreach($languages as $k => $lang) {?>
                                                    <div class="item <?php echo ($k == $defaultLang)? 'active selected' : '' ?>" data-lang-code="<?php echo $lang['language_code'] ?>" onclick="arCU.switchLang('<?php echo $lang['language_code'] ?>');">
                                                        <img class="ui image" src="<?php echo $lang['country_flag_url'] ?>">
                                                        <?php echo $lang['language_code'] ?>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="errors"></div>
                            </div>
                        <?php }else{ ?>
                            <div class="field required">
                                <label><?php echo __('Message', 'ar-contactus') ?></label>
                                <textarea placeholder="" rows="3" id="arcontactus_prompt_message" data-default="" autocomplete="off" data-serializable="true" name="message" type="text"></textarea>
                                <div class="errors"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="button-large black deny button">
                <?php echo __('Cancel', 'ar-contactus') ?>
            </button>
            <button type="submit" class="button button-primary button-large icon">
                <?php echo __('Save', 'ar-contactus') ?>
                <i class="checkmark icon"></i>
            </button>
        </div>
    </form>
</div>
<?php echo ArContactUsAdmin::render('/admin/_item_modal.php', array(
    'isWPML' => $isWPML,
    'languages' => $languages,
    'defaultLang' => $defaultLang,
    'integrations' => $integrations,
    'brandColors' => $brandColors,
    'scheduleDays' => $scheduleDays,
    'formsConfig' => $formsConfig
)) ?>
<?php echo ArContactUsAdmin::render('/admin/_form_modal.php', array(
    'isWPML' => $isWPML,
    'languages' => $languages,
    'defaultLang' => $defaultLang,
    'popupConfig' => $popupConfig
)) ?>
<?php echo ArContactUsAdmin::render('/admin/_field_modal.php', array(
    'isWPML' => $isWPML,
    'languages' => $languages,
    'defaultLang' => $defaultLang
)) ?>
<?php echo ArContactUsAdmin::render('/admin/_button_modal.php', array(
    'isWPML' => $isWPML,
    'languages' => $languages,
    'defaultLang' => $defaultLang
)) ?>
<script>
    window.addEventListener('load', function(){
        if (jQuery.fn.dropdown && jQuery.fn.dropdown.noConflict){
            jQuery.fn.bsDropdown = jQuery.fn.dropdown.noConflict();
        }
        var currentTime = '<?php echo $currentTime ?>';
        var currentMoment = moment(currentTime, 'kk:mm:ss');
        
        setInterval(function(){
            jQuery('#arcu-server-time').text(currentMoment.add(1, 'seconds').format('kk:mm:ss'));
        }, 1000);
        
        jQuery('#arcontactus-plugin-container .menu .item').uiTab();
        jQuery('#arcontactus-modal .menu .item').uiTab();
        jQuery('#arcontactus-plugin-container .ui.dropdown').dropdown();
        jQuery('#arcontactus-prompt-modal .ui.dropdown').dropdown();
        jQuery('#arcontactus-modal .ui.dropdown').dropdown();
        jQuery('#arcontactus-form-modal .ui.dropdown').dropdown();
        jQuery('#arcontactus-field-modal .ui.dropdown').dropdown();
        jQuery('#arcontactus-button-modal .ui.dropdown').dropdown();
        jQuery('.ui.dropdown.iconed').dropdown();
        
        setTimeout(function(){
            jQuery('.field_custom_css').append('<div id="ace-css-editor"></div>');
            var editor  = new ace.edit('ace-css-editor');
            editor.session.setMode("ace/mode/css");
            editor.setTheme("ace/theme/chrome");
            editor.setValue(jQuery('#ARCUG_CUSTOM_CSS').val());
            editor.on('change', function(e){
                jQuery('#ARCUG_CUSTOM_CSS').val(editor.getValue());
            });
        }, 100);
        
        arCU.ajaxUrl = ajaxurl;
        arCU.nonce = '<?php echo wp_create_nonce('arcu_config') ?>';
        arCU.editTitle = '<?php echo __('Edit item', 'ar-contactus') ?>';
        arCU.addTitle = '<?php echo __('Add item', 'ar-contactus') ?>';
        
        jQuery('.arcu-media-button').click(function(e) {
            e.preventDefault();
            var previewEl = jQuery(this).attr('data-preview');
            var configEl = jQuery(this).attr('data-config');
            var image_frame;
            if(image_frame){
                image_frame.open();
            }
            // Define image_frame as wp.media object
            image_frame = wp.media({
                title: 'Select Media',
                multiple : false,
                library : {
                     type : 'image',
                 }
            });

            image_frame.on('close',function() {
               // On close, get selections and save to the hidden input
               // plus other AJAX stuff to refresh the image preview
               var selection =  image_frame.state().get('selection');
               var gallery_ids = new Array();
               var my_index = 0;
               selection.each(function(attachment) {
                  gallery_ids[my_index] = attachment['id'];
                  my_index++;
               });
               var ids = gallery_ids.join(",");
               jQuery('#' + configEl).val(ids);
               arCU.refreshImage(ids, previewEl);
            });

            image_frame.on('open',function() {
              // On open, get the id from the hidden input
              // and select the appropiate images in the media manager
              var selection =  image_frame.state().get('selection');
              var ids = jQuery('#' + configEl).val().split(',');
              ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
              });

            });
            image_frame.open();
        });
        
        arContactUsSwitchFields();
        
        jQuery('#arcontactus_params_always').change(function(){
            arcontactusChangeVisibilityType();
        });
        
        jQuery('.arcu-schedule-checkbox').click(function(){
            var $input = jQuery(this).find('input');
            if ($input.val() == 0) {
                $input.val(1);
            } else {
                $input.val(0);
            }
            scheduleChanged();
        });
        
        jQuery('#arcontactus_field_type').change(function(){
            fieldTypeChanged();
        });
        fieldTypeChanged();
        
        jQuery('#arcontactus_form_twilio_on,#arcontactus_form_tg_on,#arcontactus_form_onesignal_on,#arcontactus_form_email_on,#arcontactus_form_perfex_on, #arcontactus_form_webhook_on').change(function(){
            changeFormIntegrations();
        });
        
        jQuery('#arcontactus_field_validation').change(function(){
            changeValidationType();
        });
        changeValidationType();
        
        jQuery('#arcontactus_field_mask_on').change(function(){
            changeMask();
        });
        changeMask();
        
        jQuery('#arcontactus_form_layout').change(function(){
            headerLayoutChanged();
        });
        headerLayoutChanged();
        
        jQuery('#arcontactus_form_icon_type').change(function(){
            headerIconTypeChanged();
        });
        headerIconTypeChanged();
        
        jQuery('#arcontactus_form_button_icon_type').change(function(){
            buttonIconTypeChanged();
        });
        buttonIconTypeChanged();
        
        jQuery('#ARCUM_MENU_HEADER_LAYOUT').change(function(){
            desktopHeaderLayoutChanged();
        });
        
        jQuery('#ARCUM_MENU_HEADER_ICON_TYPE').change(function(){
            desktopHeaderIconTypeChanged();
        });
        
        
        jQuery('#ARCUMM_MENU_HEADER_LAYOUT').change(function(){
            mobileHeaderLayoutChanged();
        });
        
        
        jQuery('#ARCUMM_MENU_HEADER_ICON_TYPE').change(function(){
            mobileHeaderIconTypeChanged();
        });
        
        jQuery('#arcontactus_button_type').change(function(){
            buttonTypeChanged();
        });
        buttonTypeChanged();
        
        jQuery('#ARCUM_MENU_LAYOUT').change(function(){
            desktopMenuLayoutChanged();
        });
        desktopMenuLayoutChanged();
        
        jQuery('#ARCUMM_MENU_LAYOUT').change(function(){
            mobileMenuLayoutChanged();
        });
        mobileMenuLayoutChanged();
        
        arCU.init();
    });
</script>