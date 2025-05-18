<div class="ui modal small" id="arcontactus-modal">
    <i class="close icon"></i>
    <div class="header" id="arcontactus-modal-title">
        <?php echo __('Add item', 'ar-contactus') ?>
    </div>
    <form id="arcontactus-form" method="POST" onsubmit="arCU.save(); return false;">
        <input type="hidden" id="arcontactus_id" name="id" data-serializable="true" autocomplete="off" data-default=""/>
        <?php wp_nonce_field('arcu_config') ?>
        <div class="ui form" style="padding: 20px;">
            <div class="">
                <div class="ui grid">
                    <div class="ui top attached tabular menu">
                        <a class="item active" data-tab="arcu-item-general"><?php echo __('General', 'ar-contactus') ?></a>
                        <a class="item" data-tab="arcu-item-action"><?php echo __('Action', 'ar-contactus') ?></a>
                        <a class="item" data-tab="arcu-item-visibility"><?php echo __('Visibility', 'ar-contactus') ?></a> 
                    </div>
                    <div class="ui bottom attached tab segment active" data-tab="arcu-item-general">
                        <div class="row">
                            <div class="twelve wide column">
                                <?php if ($isWPML){?>
                                    <div class="field required">
                                        <label><?php echo __('Title', 'ar-contactus') ?></label>
                                        <div class="ui grid arcu-lang-group" id="arcontactus_title">
                                            <div class="sixteen column row">
                                                <div class="fourteen wide column arcu-lang-content">
                                                    <?php foreach($languages as $k => $lang) {?>
                                                    <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                                        <input data-lang-field="true" data-serializable="true" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" placeholder="" id="arcontactus_title<?php echo ('_' . $k) ?>" data-default="" data-serializable="true" name="title" type="text">
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
                                        <label><?php echo __('Title', 'ar-contactus') ?></label>
                                        <input placeholder="" id="arcontactus_title" data-default="" data-serializable="true" name="title" type="text">
                                        <div class="errors"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <?php if ($isWPML){?>
                                    <div class="field">
                                        <label><?php echo __('Subtitle', 'ar-contactus') ?></label>
                                        <div class="ui grid arcu-lang-group" id="arcontactus_subtitle">
                                            <div class="sixteen column row">
                                                <div class="fourteen wide column arcu-lang-content">
                                                    <?php foreach($languages as $k => $lang) {?>
                                                    <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                                        <input data-lang-field="true" data-serializable="true" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" placeholder="" id="arcontactus_subtitle<?php echo ('_' . $k) ?>" data-default="" data-serializable="true" name="subtitle" type="text">
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
                                    <div class="field">
                                        <label><?php echo __('Subtitle', 'ar-contactus') ?></label>
                                        <input placeholder="" id="arcontactus_subtitle" data-default="" data-serializable="true" name="subtitle" type="text">
                                        <div class="errors"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Icon type', 'ar-contactus') ?></label>
                                    <select id="arcontactus_params_icon_type" onchange="arContactUsIconChanged()" name="params.icon_type" class="form-control arcontactus-control" data-serializable="true" data-default="0">
                                        <option value="0"><?php echo __('Built-in SVG', 'ar-contactus') ?></option>
                                        <option value="1"><?php echo __('FontAwesome 5', 'ar-contactus') ?></option>
                                        <option value="2"><?php echo __('Uploaded image', 'ar-contactus') ?></option>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="arcu-item-svg">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Icon', 'ar-contactus') ?></label>
                                    <div class="ui fluid selection search dropdown iconed" id="arcontactus-icon-dropdown">
                                        <input name="icon" id="arcontactus_icon" data-default="" autocomplete="off" data-serializable="true" type="hidden">
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
                
                        <div class="row hidden" id="arcu-fa5">
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('FontAwesome icon', 'ar-contactus') ?></label>
                                    <input placeholder="&#x3C;i class=&#x22;fab fa-font-awesome-flag&#x22;&#x3E;&#x3C;/i&#x3E;" id="arcontactus_fa_icon" data-default="" data-serializable="true" name="fa_icon" type="text">
                                    <div class="errors"></div>
                                    <div class="help-block">
                                        <?php echo sprintf(__('You can use FontAwesome5 icon. Please find needed icon here %s', 'ar-contactus'), '<a target="_blank" href="https://fontawesome.com/icons?d=gallery">https://fontawesome.com/icons</a>') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-item-image">
                            <div class="twelve wide column">
                                <?php echo ArContactUsFormField::renderConfigField('img', 'arcontactus', 'params.icon_img', array(
                                    'label' => __('Icon', 'ar-contactus'),
                                    'isWPML' => $isWPML,
                                    'languages' => $languages,
                                    'defaultLang' => $defaultLang
                                )) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <div class="ui toggle checkbox">
                                        <input id="arcontactus_params_nocontainer_OFF" name="params.nocontainer" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                                        <input id="arcontactus_params_nocontainer" name="params.nocontainer" data-serializable="true" data-default="0" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                                        <label for="arcontactus_params_nocontainer"><?php echo __('Remove circle around icon', 'ar-contactus') ?></label>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field required" style="overflow: hidden">
                                    <label><?php echo __('Color', 'ar-contactus') ?></label>
                                    <div style="width: 60%; float: left; padding: 0 3px 0 0">
                                        <input class="jscolor" id="arcontactus_color" data-jscolor="{value:'000000'}" data-default="000000" autocomplete="off" data-serializable="true" name="color" type="text">
                                        <div class="errors"></div>
                                    </div>
                                    <div style="width: 40%; float: left;">
                                        <select style="margin: 0; height: 38px" id="arcontactus_brand_color" class="form-control arcontactus-control" data-default="0">
                                            <option value="0" disabled=""><?php echo __('Brand color', 'ar-contactus') ?></option>
                                            <?php foreach($brandColors as $brand => $color){?>
                                                <option value="<?php echo $color ?>"><?php echo $brand ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <div class="ui toggle checkbox">
                                        <input id="arcontactus_params_online_badge_OFF" name="params.online_badge" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                                        <input id="arcontactus_params_online_badge" name="params.online_badge" data-serializable="true" data-default="0" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                                        <label for="arcontactus_params_online_badge"><?php echo __('Show online badge', 'ar-contactus') ?></label>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <div class="ui toggle checkbox">
                                        <input id="arcontactus_params_include_to_slider_OFF" name="params.include_to_slider" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                                        <input id="arcontactus_params_include_to_slider" name="params.include_to_slider" data-serializable="true" data-default="1" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                                        <label for="arcontactus_params_include_to_slider"><?php echo __('Include icon to slider', 'ar-contactus') ?></label>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui bottom attached tab segment" data-tab="arcu-item-action">
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Action', 'ar-contactus') ?></label>
                                    <select id="arcontactus_type" name="type" class="form-control arcontactus-control" data-serializable="true" data-default="0">
                                        <option value="0"><?php echo __('Link', 'ar-contactus') ?></option>
                                        <option value="1"><?php echo __('Integration', 'ar-contactus') ?></option>
                                        <option value="2"><?php echo __('Custom JS code', 'ar-contactus') ?></option>
                                        <option value="3"><?php echo __('Form', 'ar-contactus') ?></option>
                                        <option value="4"><?php echo __('Custom popup', 'ar-contactus') ?></option>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="arcu-form-group">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Form', 'ar-contactus') ?></label>
                                    <select id="arcontactus_params_form" name="params.form" class="form-control arcontactus-control" data-serializable="true" data-default="">
                                        <?php foreach ($formsConfig->getForms() as $form){ ?>
                                            <option value="<?php echo $form->id ?>"><?php echo $form->id ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row arcu-link-group">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Link', 'ar-contactus') ?> 
                                        <a target="_blank" href="https://plugins.areama.net/ar-contactus/docs/deeplinks.php"><small style="font-weight: normal"><?php echo __('Deeplink examples', 'ar-contactus') ?></small></a>
                                    </label>
                                    <input placeholder="" id="arcontactus_link" data-default="" autocomplete="off" data-serializable="true" name="link" type="text">
                                    <div class="errors"></div>
                                    <div class="help-block">
                                        <?php echo sprintf(__('You can set absolute or relative URL. You can use %s{site}%s and %s{url}%s variables.', 'ar-contactus'), '<b>', '</b>', '<b>', '</b>') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row arcu-link-group">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Target', 'ar-contactus') ?></label>
                                    <select id="arcontactus_target" name="target" class="form-control arcontactus-control" data-serializable="true" data-default="0">
                                        <option value="0"><?php echo __('New window', 'ar-contactus') ?></option>
                                        <option value="1"><?php echo __('Same window', 'ar-contactus') ?></option>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row arcu-content-group" id="arcu-content-group">
                            <div class="twelve wide column">
                                <?php if ($isWPML){?>
                                    <div class="field required">
                                        <label><?php echo __('Content', 'ar-contactus') ?></label>
                                        <div class="ui grid arcu-lang-group" id="arcontactus_content">
                                            <div class="sixteen column row">
                                                <div class="fourteen wide column arcu-lang-content">
                                                    <?php foreach($languages as $k => $lang) {?>
                                                    <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                                        <textarea data-lang-field="true" data-serializable="true" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="wp-editor-area" rows="6" tabindex="2" autocomplete="off" data-serializable="true" data-default="" name="content" id="arcontactus_content<?php echo ('_' . $k) ?>" aria-hidden="false"></textarea>
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
                                    <label><?php echo __('Content', 'ar-contactus') ?></label>
                                    <textarea class="wp-editor-area" rows="6" tabindex="2" autocomplete="off" data-serializable="true" data-default="" name="content" id="arcontactus_content" aria-hidden="false"></textarea>
                                    <div class="errors"></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row" id="arcu-integration-group">
                            <div class="twelve wide column">
                                <div class="field required">
                                    <label><?php echo __('Integration', 'ar-contactus') ?></label>
                                    <select id="arcontactus_integration" name="integration" class="form-control arcontactus-control" data-serializable="true" data-default="0">
                                        <?php foreach ($integrations as $id => $integration) {?>
                                            <option value="<?php echo $id ?>"><?php echo $integration ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="arcu-js-group">
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('Custom JS code', 'ar-contactus') ?></label>
                                    <textarea placeholder="" rows="3" id="arcontactus_js" data-default="" autocomplete="off" data-serializable="true" name="js" type="text"></textarea>
                                    <div class="errors"></div>
                                    <div class="help-block">
                                        <?php echo __('JavaScript code to run onclick. Please type here JavaScript code without <b>&lt;script&gt;</b> tag.', 'ar-contactus') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui bottom attached tab segment" data-tab="arcu-item-visibility">
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('Device', 'ar-contactus') ?></label>
                                    <select id="arcontactus_display" name="display" class="form-control arcontactus-control" data-serializable="true" data-default="1">
                                        <option value="1"><?php echo __('Desktop and mobile', 'ar-contactus') ?></option>
                                        <option value="2"><?php echo __('Desktop only', 'ar-contactus') ?></option>
                                        <option value="3"><?php echo __('Mobile only', 'ar-contactus') ?></option>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <label><?php echo __('Users', 'ar-contactus') ?></label>
                                    <select id="arcontactus_registered_only" name="registered_only" class="form-control arcontactus-control" data-serializable="true" data-default="0">
                                        <option value="0"><?php echo __('All users', 'ar-contactus') ?></option>
                                        <option value="1"><?php echo __('Logged-in users only', 'ar-contactus') ?></option>
                                        <option value="2"><?php echo __('Not logged-in users only', 'ar-contactus') ?></option>
                                    </select>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <?php if ($isWPML){?>
                            <div class="row" id="arcu-language-group">
                                <div class="twelve wide column">
                                    <div class="field">
                                        <label><?php echo __('Show for language', 'ar-contactus') ?></label>
                                        <select id="arcontactus_language" data-serializable="true" name="language" data-default="">
                                            <option value=""> -- <?php echo __('all languages', 'ar-contactus') ?> --</option>
                                            <?php foreach($languages as $k => $lang) {?>
                                                <option value="<?php echo $lang['language_code'] ?>"><?php echo $lang['native_name'] . ' (' . $lang['language_code'] . ')' ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="twelve wide column">
                                <div class="field">
                                    <div class="ui toggle checkbox">
                                        <input id="arcontactus_params_always_OFF" name="params.always" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                                        <input id="arcontactus_params_always" name="params.always" data-serializable="true" data-default="1" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                                        <label for="arcontactus_params_always"><?php echo __('Always display this item', 'ar-contactus') ?></label>
                                    </div>
                                    <div class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div id="arcu-item-schedule" style="position: relative">
                            <div class="row">
                                <div class="twelve wide column">
                                    <div class="field">
                                        <label><?php echo __('Schedule', 'ar-contactus') ?></label>
                                        <span class="arcu-server-time">
                                            <?php echo __('Current server time:', 'ar-contactus') ?>
                                            <span id="arcu-server-time"></span>
                                        </span>
                                        
                                        <div class="arcu-schedule">
                                            <?php foreach ($scheduleDays as $day) {?>
                                                <div class="arcu-schedule-item checked">
                                                    <div class="arcu-schedule-checkbox checked">
                                                        <input type="hidden" class="arcu-schedule-input" id="arcontactus_params_<?php echo strtolower($day) ?>" name="params.<?php echo strtolower($day) ?>" data-serializable="true" data-default="1" value="1" />
                                                        <?php echo __($day, 'ar-contactus') ?>
                                                    </div>
                                                    <div class="arcu-schedule-time">
                                                        <input type="text" id="arcontactus_params_<?php echo strtolower($day) ?>_time_from" placeholder="00:00" data-serializable="true" data-default="00:00" name="params.<?php echo strtolower($day) ?>_time_from" />
                                                        <span>:</span>
                                                        <input type="text" id="arcontactus_params_<?php echo strtolower($day) ?>_time_to" placeholder="23:59" data-serializable="true" data-default="23:59" name="params.<?php echo strtolower($day) ?>_time_to" />
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="twelve wide column">
                                    <div class="field">
                                        <label><?php echo __('If item is not active', 'ar-contactus') ?></label>
                                        <select id="arcontactus_params_inactive" name="params.inactive" class="form-control arcontactus-control" data-serializable="true" data-default="1">
                                            <option value="1"><?php echo __('Hide this item', 'ar-contactus') ?></option>
                                            <option value="2"><?php echo __('Show offline badge', 'ar-contactus') ?></option>
                                        </select>
                                        <div class="errors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden" id="arcu-shortcode-group">
                        <div class="sixteen wide column">
                            <div class="field">
                                <label><?php echo __('Shortcode', 'ar-contactus') ?></label>
                                <input placeholder="" rows="3" readonly=""id="arcontactus_shortcode" class="disabled" data-default="" autocomplete="off" type="text" />
                            </div>
                        </div>
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