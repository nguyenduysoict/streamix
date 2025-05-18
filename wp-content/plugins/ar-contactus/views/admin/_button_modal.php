<div class="ui modal small" id="arcontactus-button-modal">
    <i class="close icon"></i>
    <div class="header" id="arcontactus-modal-title">
        <?php echo __('Button options', 'ar-contactus') ?>
    </div>
    <form id="arcontactus_button-form" method="POST" onsubmit="arCU.form.button.save(); return false;">
        <div class="ui form" style="padding: 20px;">
            <input placeholder="" id="arcontactus_button_form_id" type="hidden" />
            <div class="ui grid">
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <div class="field required">
                            <label><?php echo __('ID', 'ar-contactus') ?></label>
                            <input placeholder="" id="arcontactus_button_id" data-default="" data-serializable="true" name="id" type="text">
                            <div class="errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_button', 'type', array(
                            'label' => __('Button type', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'default' => 'submit',
                            'values' => array(
                                'submit' => __('Submit', 'ar-contactus'),
                                'link' => __('Link', 'ar-contactus')
                            )
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_button', 'label', array(
                            'label' => __('Label', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'required' => true
                        )) ?>
                    </div>
                </div>
                
                <div class="row" id="arcontactus_form_url-group">
                    <div class="sixteen wide column">
                        <div class="ui grid">
                            <div class="row">
                                <div class="two wide column"></div>
                                <div class="twelve wide column">
                                    <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_button', 'url', array(
                                        'label' => __('URL', 'ar-contactus'),
                                        'isWPML' => $isWPML,
                                        'languages' => $languages,
                                        'defaultLang' => $defaultLang,
                                        'lang' => false,
                                        'required' => true
                                    )) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="two wide column"></div>
                                <div class="twelve wide column">
                                    <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_button', 'new_window', array(
                                        'label' => __('Open link in new window', 'ar-contactus'),
                                        'isWPML' => $isWPML,
                                        'languages' => $languages,
                                        'defaultLang' => $defaultLang,
                                        'default' => 1,
                                        'lang' => false
                                    )) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_button', 'class_name', array(
                            'label' => __('CSS class', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'required' => false,
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