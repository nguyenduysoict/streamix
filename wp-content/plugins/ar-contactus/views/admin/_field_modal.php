<div class="ui modal small" id="arcontactus-field-modal">
    <i class="close icon"></i>
    <div class="header" id="arcontactus-modal-title">
        <?php echo __('Field options', 'ar-contactus') ?>
    </div>
    <form id="arcontactus_field-form" method="POST" onsubmit="arCU.form.field.save(); return false;">
        <div class="ui form" style="padding: 20px;">
            <input placeholder="" id="arcontactus_field_form_id" type="hidden" />
            <div class="ui grid">
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <div class="field required">
                            <label><?php echo __('ID', 'ar-contactus') ?></label>
                            <input placeholder="" id="arcontactus_field_id" data-default="" data-serializable="true" name="id" type="text">
                            <div class="errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_field', 'required', array(
                            'label' => __('Required', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_field', 'type', array(
                            'label' => __('Field type', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang,
                            'default' => 'text',
                            'values' => array(
                                'text' => __('Text field', 'ar-contactus'),
                                'tel' => __('Phone field', 'ar-contactus'),
                                'email' => __('Email field', 'ar-contactus'),
                                'textarea' => __('Textarea', 'ar-contactus'),
                                'checkbox' => __('Checkbox', 'ar-contactus'),
                                'select' => __('Select', 'ar-contactus')
                            )
                        )) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'label', array(
                            'label' => __('Label', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row" id="arcontactus_field_placeholder-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'placeholder', array(
                            'label' => __('Placeholder', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row" id="arcontactus_field_values-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('textarea', 'arcontactus_field', 'values', array(
                            'label' => __('Values', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row" id="arcontactus_field_value-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'value', array(
                            'label' => __('Value', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div id="arcontactus_field_mask-group" class="sixteen wide column">
                    <div class="ui grid">
                        <div class="row">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_field', 'mask_on', array(
                                    'label' => __('Enable mask', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang
                                )) ?>
                            </div>
                        </div>
                        <div class="row" id="arcontactus_field_mask_on-group">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <div class="ui orange message">
                                    <?php echo __('Mask functionality temporarily does not work. We are moving to non-jquery plugin, so <b>jquery.maskedinput</b> plugin does not meet our requirements anymore. We are working on new maskedinput plugin now.', 'ar-contactus') ?>
                                </div>
                                <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'mask', array(
                                    'label' => __('Mask', 'ar-contactus'),
                                    'hint' => __('<b>X</b> means any number', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="arcontactus_field_validation-group" class="sixteen wide column">
                    <div class="ui grid">
                        <div class="row">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('select', 'arcontactus_field', 'validation', array(
                                    'label' => __('Validation', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang,
                                    'default' => '',
                                    'values' => array(
                                        '' => __('-- none --', 'ar-contactus'),
                                        'email' => __('Email', 'ar-contactus'),
                                        'letters' => __('Enable only letters and spaces', 'ar-contactus'),
                                        'numbers' => __('Enable only numbers and spaces', 'ar-contactus'),
                                        'letters_numbers' => __('Enable only letters, numbers and spaces', 'ar-contactus'),
                                        'advanced' => __('Advanced (regular expression)', 'ar-contactus'),
                                    )
                                )) ?>
                            </div>
                        </div>
                        <div class="row" id="arcontactus_field_preg-group">
                            <div class="two wide column"></div>
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'preg', array(
                                    'label' => __('Regular expression', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang,
                                    'lang' => false,
                                    'required' => true
                                )) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('checkbox', 'arcontactus_field', 'report', array(
                            'label' => __('Include to report', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
                        )) ?>
                    </div>
                </div>
                <div class="row" id="arcontactus_field_report_label-group">
                    <div class="two wide column"></div>
                    <div class="twelve wide column">
                        <?php echo ArContactUsFormField::renderConfigField('text', 'arcontactus_field', 'report_label', array(
                            'label' => __('Report column label', 'ar-contactus'),
                            'isWPML' => $isWPML,
                            'languages' => $languages,
                            'defaultLang' => $defaultLang
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