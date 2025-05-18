<div class="ui modal small" id="arcontactus-form-modal">
    <i class="close icon"></i>
    <div class="header" id="arcontactus-modal-title">
        <?php echo __('Form options', 'ar-contactus') ?>
    </div>
    <form id="arcontactus_form-form" method="POST" onsubmit="arCU.form.save(); return false;">
        <div class="ui form" style="padding: 20px;">
            <div class="ui grid">
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <div class="field required">
                            <label><?php echo __('ID', 'ar-contactus') ?></label>
                            <input placeholder="" id="arcontactus_form_id" data-default="" data-serializable="true" name="id" readonly="" type="text">
                            <div class="errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="sixteen wide column arcu-form-section">
                        <span><?php echo __('Form header', 'ar-contactus') ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_form', 'layout', array(
                            'label' => __('Header layout', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'default' => 'text',
                            'values' => array(
                                '1' => __('No header', 'ar-contactus'),
                                '2' => __('Text only', 'ar-contactus'),
                                '3' => __('Text with icon left', 'ar-contactus'),
                                '4' => __('Text with icon center', 'ar-contactus')
                            )
                        )) ?>
                    </div>
                </div>
                <div class="row" id="arcu-form-header-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'header', array(
                            'label' => __('Header label', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div id="arcu-form-icon-group" class="sixteen wide column">
                    <div class="ui grid">
                        <div class="row">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_form', 'icon_type', array(
                                    'label' => __('Header icon type', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang,
                                    'default' => 'svg',
                                    'values' => array(
                                        'svg' => __('Built-in SVG', 'ar-contactus'),
                                        'fa5' => __('FontAwesome 5', 'ar-contactus'),
                                        'image' => __('Uploaded image', 'ar-contactus')
                                    )
                                )) ?>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-header-svg">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Header icon', 'ar-contactus') ?></label>
                                    <div class="ui fluid selection search dropdown iconed" id="arcontactus_form_icon_svg-dropdown">
                                        <input name="icon_svg" id="arcontactus_form_icon_svg" data-default="" autocomplete="off" data-serializable="true" type="hidden">
                                        <i class="dropdown icon"></i>
                                        <div class="default text"><?php echo __('Select icon', 'ar-contactus') ?></div>
                                        <div class="menu">
                                            <?php foreach (ArContactUsConfigModel::getIcons() as $key => $svg){?>
                                                <div class="item" data-value="<?php echo $key ?>">
                                                    <?php echo $svg ?>
                                                    <?php echo $key ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-header-fa5">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('Header FontAwesome icon', 'ar-contactus') ?></label>
                                    <input placeholder="&#x3C;i class=&#x22;fab fa-font-awesome-flag&#x22;&#x3E;&#x3C;/i&#x3E;" id="arcontactus_form_icon_fa5" data-default="" data-serializable="true" name="icon_fa5" type="text">
                                    <div class="errors"></div>
                                    <div class="help-block">
                                        <?php echo sprintf(__('You can use FontAwesome5 icon. Please find needed icon here %s', 'ar-contactus'), '<a target="_blank" href="https://fontawesome.com/icons?d=gallery">https://fontawesome.com/icons</a>') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-header-image">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('img', 'arcontactus_form', 'icon_img', array(
                                    'label' => __('Header icon', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="sixteen wide column arcu-form-section">
                        <span><?php echo __('Button icon', 'ar-contactus') ?></span>
                    </div>
                </div>
                <div id="arcu-form-button-icon-group" class="sixteen wide column">
                    <div class="ui grid">
                        <div class="row">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_form', 'button_icon_type', array(
                                    'label' => __('Change button icon if form is opened', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang,
                                    'default' => '',
                                    'values' => array(
                                        '' => __('Do not change button icon', 'ar-contactus'),
                                        'svg' => __('Built-in SVG', 'ar-contactus'),
                                        'fa5' => __('FontAwesome 5', 'ar-contactus'),
                                        'image' => __('Uploaded image', 'ar-contactus')
                                    )
                                )) ?>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-button-svg">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Button icon', 'ar-contactus') ?></label>
                                    <div class="ui fluid selection search dropdown iconed" id="arcontactus_form_button_icon_svg-dropdown">
                                        <input name="button_icon_svg" id="arcontactus_form_button_icon_svg" data-default="" autocomplete="off" data-serializable="true" type="hidden">
                                        <i class="dropdown icon"></i>
                                        <div class="default text"><?php echo __('Select icon', 'ar-contactus') ?></div>
                                        <div class="menu">
                                            <?php foreach (ArContactUsConfigModel::getIcons() as $key => $svg){?>
                                                <div class="item" data-value="<?php echo $key ?>">
                                                    <?php echo $svg ?>
                                                    <?php echo $key ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-button-fa5">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('Button FontAwesome icon', 'ar-contactus') ?></label>
                                    <input placeholder="&#x3C;i class=&#x22;fab fa-font-awesome-flag&#x22;&#x3E;&#x3C;/i&#x3E;" id="arcontactus_form_button_icon_fa5" data-default="" data-serializable="true" name="button_icon_fa5" type="text">
                                    <div class="errors"></div>
                                    <div class="help-block">
                                        <?php echo sprintf(__('You can use FontAwesome5 icon. Please find needed icon here %s', 'ar-contactus'), '<a target="_blank" href="https://fontawesome.com/icons?d=gallery">https://fontawesome.com/icons</a>') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-button-image">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('img', 'arcontactus_form', 'button_icon_img', array(
                                    'label' => __('Button icon', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="sixteen wide column arcu-form-section">
                        <span><?php echo __('Form content', 'ar-contactus') ?></span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'successContent', array(
                            'label' => __('Success message', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'failContent', array(
                            'label' => __('Fail message', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="sixteen wide column arcu-form-section">
                        <span><?php echo __('actions', 'ar-contactus') ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'email_on', array(
                            'label' => __('Send email on success', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'value' => 0,
                            'lang' => false
                        )) ?>
                    </div>
                </div>
                <div class="row arcu-form-email-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'email_list', array(
                            'label' => __('Email list', 'ar-contactus'),
                            'hint' => __('One email per line', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'value' => 0,
                            'lang' => false
                        )) ?>
                    </div>
                </div>
                <?php if ($popupConfig->twilio){ ?>
                    <div class="row">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'twilio_on', array(
                                'label' => __('Send SMS on success', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-twilio-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'twilio_phone', array(
                                'label' => __('Send SMS to this phone', 'ar-contactus'),
                                'hint' => __('SMS message will be send to this phone number. Use international format', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-twilio-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'twilio_message', array(
                                'label' => __('SMS text', 'ar-contactus'),
                                'hint' => __('You can use ID of field as variable. For example: {phone} will be replaced to Phone field value. Also you can use built-in variables: {site} token will be replaced to site domain, {referer} token will be replaced to page url which used for callback request.', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => true
                            )) ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($popupConfig->tg){ ?>
                    <div class="row">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'tg_on', array(
                                'label' => __('Send telegram message', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-tg-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'tg_chat_id', array(
                                'label' => __('Telegram chat id', 'ar-contactus'),
                                'hint' => __('Messages will be received to this chatID. To know your chatID please write to bot t.me/userinfobot. You can set few chatID comma-separated. Please note that each chatID must be subscribed to your bot to receive messages from the bot. To subscribe please find your bot in telegram and write /start to the bot.', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-tg-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'tg_message', array(
                                'label' => __('Telegram message', 'ar-contactus'),
                                'hint' => __('You can use ID of field as variable. For example: {phone} will be replaced to Phone field value. Also you can use built-in variables: {site} token will be replaced to site domain, {referer} token will be replaced to page url which used for callback request.', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => true
                            )) ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($popupConfig->onesignal){ ?>
                    <div class="row">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'onesignal_on', array(
                                'label' => __('Send webpush message', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-onesignal-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'onesignal_title', array(
                                'label' => __('Webpush message title', 'ar-contactus'),
                                'hint' => __('You can use ID of field as variable. For example: {phone} will be replaced to Phone field value. Also you can use built-in variables: {site} token will be replaced to site domain, {referer} token will be replaced to page url which used for callback request.', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => true
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-onesignal-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_form', 'onesignal_message', array(
                                'label' => __('Webpush message message', 'ar-contactus'),
                                'hint' => __('You can use ID of field as variable. For example: {phone} will be replaced to Phone field value. Also you can use built-in variables: {site} token will be replaced to site domain, {referer} token will be replaced to page url which used for callback request.', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => true
                            )) ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($popupConfig->perfex){ ?>
                    <div class="row">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'perfex_on', array(
                                'label' => __('Create lead in Perfex CRM', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-perfex-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'perfex_source', array(
                                'label' => __('Source ID', 'ar-contactus'),
                                'hint' => __('You can get source ID from setup/leads/sources in your Perfex CRM', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'required' => true,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <div class="row arcu-form-perfex-group">
                        <div class="two wide column"></div>
                        <div class="twelve wide column">
                            <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'perfex_status', array(
                                'label' => __('Status ID', 'ar-contactus'),
                                'hint' => __('You can get status ID from setup/leads/statuses in your Perfex CRM', 'ar-contactus'),
                                'isWPML' => $isWPML,
                                'languages' => $languages,
                                'defaultLang' => $defaultLang,
                                'required' => true,
                                'value' => 0,
                                'lang' => false
                            )) ?>
                        </div>
                    </div>
                    <?php foreach (ArContactUsForm::perfexAssignmentFields() as $key => $label) {?>
                        <div class="row arcu-form-perfex-group">
                            <div class="two wide column"></div>
                            <div class="twelve wide column perfex-field-assign">
                                <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_form', $key, array(
                                    'label' => $label,
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang,
                                    'value' => 0,
                                    'lang' => false,
                                    'default' => '',
                                    'required' => in_array($key, array('perfex_name')),
                                    'values' => array(

                                    )
                                )) ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_form', 'webhook_on', array(
                            'label' => __('Webhook', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'value' => 0,
                            'lang' => false
                        )) ?>
                    </div>
                </div>
                <div class="row arcu-form-webhook-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'webhook_url', array(
                            'label' => __('Webhook URL', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'value' => 0,
                            'lang' => false
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="sixteen wide column arcu-form-section">
                        <span><?php echo __('Advanced options', 'ar-contactus') ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'desktopWidth', array(
                            'label' => __('Desktop width', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'lang' => false
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_form', 'autoClose', array(
                            'label' => __('Close form automatically after', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'value' => 0,
                            'lang' => false
                        )) ?>
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