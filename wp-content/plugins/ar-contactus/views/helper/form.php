<form id="<?php echo $form['id'] ?>" method="POST" class="ui form">
    <?php wp_nonce_field(ArContactUsAdmin::NONCE) ?>
    <?php foreach ($fields as $attr => $params){?>
        <div class="field <?php echo $params['form_group_class'] ?>">
            <?php if ($params['type'] == 'switch'){ ?>
                <div class="ui toggle checkbox">
                    <input id="<?php echo $params['id'] ?>_OFF" name="<?php echo $params['name'] ?>" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                    <input id="<?php echo $params['id'] ?>" name="<?php echo $params['name'] ?>" value="1" tabindex="0" autocomplete="off" <?php echo $params['value']? 'checked="true"' : '' ?> class="hidden" type="checkbox">
                    <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                </div>
            <?php } ?>
            <?php if ($params['type'] == 'text'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <?php if ($params['suffix']){?>
                    <div class="ui right labeled input">
                        <input id="<?php echo $params['id'] ?>" name="<?php echo $params['name'] ?>" value="<?php echo $params['value'] ?>" placeholder="<?php echo $params['placeholder'] ?>" type="text">
                        <div class="ui basic label"><?php echo $params['suffix'] ?></div>
                    </div>
                <?php }else{ ?>
                    <?php if ($params['lang'] && $wpml) { ?>
                        <div class="ui grid arcu-lang-group">
                            <div class="sixteen column row">
                                <div class="fourteen wide column arcu-lang-content">
                                    <?php foreach($languages as $k => $lang) {?>
                                    <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                        <input id="<?php echo $params['id'] ?><?php echo ($k == $defaultLang)? '' : ('_' . $k) ?>" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" name="<?php echo $params['name'] ?><?php echo ('[' . $k . ']') ?>" 
                                                <?php if (is_array($params['value'])){?>
                                                    value="<?php echo $params['value'][$k] ?>" 
                                                <?php }elseif(is_object($params['value'])){ ?>
                                                    value="<?php echo $params['value']->$k ?>" 
                                                <?php }else{ ?>
                                                    value="<?php echo $params['value'] ?>" 
                                                <?php } ?>
                                               placeholder="<?php echo $params['placeholder'] ?>" type="text">
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
                    <?php }else{ ?>
                        <?php if (is_array($params['value'])){
                            $fieldValue = reset($params['value']);
                        }else{
                            $fieldValue = $params['value'];
                        } ?>
                        <input id="<?php echo $params['id'] ?>" name="<?php echo $params['name'] ?>" value="<?php echo $fieldValue ?>" placeholder="<?php echo $params['placeholder'] ?>" type="text">
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <?php if ($params['type'] == 'textarea'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <?php if ($params['lang'] && $wpml) { ?>
                    <div class="ui grid arcu-lang-group">
                        <div class="sixteen column row">
                            <div class="fourteen wide column arcu-lang-content">
                                <?php foreach($languages as $k => $lang) {?>
                                <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                    <textarea data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" rows="3" id="<?php echo $params['id'] ?><?php echo ($k == $defaultLang)? '' : ('_' . $k) ?>" name="<?php echo $params['name'] ?><?php echo ('[' . $k . ']') ?>" 
                                        placeholder="<?php echo $params['placeholder'] ?>"><?php if (is_array($params['value'])){?><?php echo $params['value'][$k] ?><?php }elseif(is_object($params['value'])){ ?><?php echo $params['value']->$k ?><?php }else{ ?><?php echo $params['value'] ?><?php } ?></textarea>
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
                <?php }else{ ?>
                    <?php if (is_array($params['value'])){
                        $fieldValue = reset($params['value']);
                    }else{
                        $fieldValue = $params['value'];
                    } ?>
                    <textarea rows="3" id="<?php echo $params['id'] ?>" name="<?php echo $params['name'] ?>" placeholder="<?php echo $params['placeholder'] ?>"><?php echo $fieldValue ?></textarea>
                <?php } ?>
            <?php } ?>
            <?php if ($params['type'] == 'editor'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <?php if ($params['lang'] && $wpml) { ?>
                    <div class="ui grid arcu-lang-group">
                        <div class="sixteen column row">
                            <div class="fourteen wide column arcu-lang-content">
                                <?php foreach($languages as $k => $lang) {?>
                                <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                                    <?php if (is_array($params['value'])) {
                                        $fieldValue = $params['value'][$k];
                                    } elseif(is_object($params['value'])) {
                                        $fieldValue = $params['value']->$k;
                                    } else {
                                        $fieldValue = $params['value'];
                                    }
                                    $fieldId = $params['id'] . (($k == $defaultLang)? '' : ('_' . $k));
                                    $fieldName = $params['id'] . '[' . $k . ']';
                                    wp_editor($fieldValue, $fieldId, array(
                                        'textarea_name' => $fieldName,
                                        'media_buttons' => false,
                                        'textarea_rows' => 10
                                    )) ?>
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
                <?php }else{ ?>
                    <?php if (is_array($params['value'])){
                        $fieldValue = reset($params['value']);
                    }else{
                        $fieldValue = $params['value'];
                    } 
                    wp_editor($fieldValue, $params['id'], array(
                        'media_buttons' => false,
                        'textarea_rows' => 10
                    )); ?>
                <?php } ?>
            <?php } ?>
            <?php if ($params['type'] == 'color'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <input id="<?php echo $params['id'] ?>" name="<?php echo $params['name'] ?>" class="jscolor" value="<?php echo $params['value'] ?>" placeholder="<?php echo $params['placeholder'] ?>" type="text">
            <?php } ?>
            <?php if ($params['type'] == 'mediaImage'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <div id="<?php echo $params['id'] ?>-preview" class="arcu-img-preview">
                    <?php if ($params['value']){
                        echo wp_get_attachment_image($params['value'], 'full', false);
                    } ?>
                </div>
                <button type="button" class="arcu-media-button button button-primary button-large icon" data-preview="<?php echo $params['id'] ?>-preview" data-config="<?php echo $params['id'] ?>" id="<?php echo $params['id'] ?>-button">
                    <i class="image icon"></i> <?php echo __('Select image', 'ar-contacus') ?>
                </button>
                
                <input id="<?php echo $params['id'] ?>" value="<?php echo $params['value'] ?>" name="<?php echo $params['name'] ?>" type="hidden">
            <?php } ?>
            <?php if ($params['type'] == 'select'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <?php if ($params['multiple']){?>
                    <select autocomplete="off" id="<?php echo $params['id'] ?>" class="ui <?php echo (!$params['grouped'])? 'dropdown' : '' ?>" name="<?php echo $params['name'] ?>" id="" multiple="">
                        <?php foreach ($params['options']['values'] as $key => $title){?>
                            <?php if (is_array($title)){?>
                                <optgroup label="<?php echo $title['name'] ?>">
                                    <?php foreach ($title['items'] as $optionId => $optionTitle){?>
                                        <option <?php echo in_array($optionId, $params['value'])? 'selected="selected"' : '' ?> value="<?php echo $optionId ?>"><?php echo $optionTitle ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php }else{ ?>
                                <option <?php echo in_array($key, $params['value'])? 'selected="selected"' : '' ?> value="<?php echo $key ?>"><?php echo $title ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php }else{ ?>
                    <select autocomplete="off" id="<?php echo $params['id'] ?>" class="ui <?php echo (!$params['grouped'])? 'dropdown' : '' ?>" name="<?php echo $params['name'] ?>">
                        <?php foreach ($params['options']['values'] as $key => $title){?>
                            <?php if (is_array($title)){?>
                                <optgroup label="<?php echo $title['name'] ?>">
                                    <?php foreach ($title['items'] as $optionId => $optionTitle){?>
                                        <option <?php echo $optionId == $params['value']? 'selected="selected"' : '' ?> value="<?php echo $optionId ?>"><?php echo $optionTitle ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php }else{ ?>
                                <option <?php echo $key == $params['value']? 'selected="selected"' : '' ?> value="<?php echo $key ?>"><?php echo $title ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } ?>
            <?php } ?>
            <?php if ($params['type'] == 'iconDropdown'){ ?>
                <label for="<?php echo $params['id'] ?>"><?php echo $params['label'] ?></label>
                <div class="ui fluid selection search dropdown iconed" id="<?php echo $params['id'] ?>-dropdown">
                    <input value="<?php echo $params['value'] ?>" name="<?php echo $params['name'] ?>" id="<?php echo $params['id'] ?>" data-default="" autocomplete="off" data-serializable="true" type="hidden">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php echo __('Select icon', 'ar-contactus') ?></div>
                    <div class="menu">
                        <?php foreach ($params['options']['values'] as $key => $svg){?>
                            <div class="item" data-value="<?php echo $key ?>">
                                <?php echo $svg ?>
                                <?php echo $key ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($params['type'] == 'html'){ ?>
                <?php echo $params['html_content'] ?>
            <?php } ?>
            <?php if ($params['desc']){?>
                <div class="help-block">
                    <?php echo $params['desc'] ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="text-right">
        <input name="<?php echo $form['id'] ?>" class="button button-primary button-large" value="<?php echo __('Save', 'ar-contactus') ?>" type="submit" />
    </div>
</form>
<script>
    window.addEventListener('load', function(){
        jQuery('#<?php echo $form['id'] ?> .ui.checkbox').checkbox();
        jQuery('#<?php echo $form['id'] ?> .ui.dropdown').dropdown({
            allowAdditions: true
        });
    });
</script>