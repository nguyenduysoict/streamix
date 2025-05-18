<div class="field <?php echo $required? 'required' : '' ?>">
    <label><?php echo $label ?></label>
    <?php if ($isWPML && $lang){?>
        <div class="ui grid arcu-lang-group" id="<?php echo $formId ?>_<?php echo $name ?>">
            <div class="sixteen column row">
                <div class="fourteen wide column arcu-lang-content">
                    <?php foreach($languages as $k => $lang) {?>
                    <div data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" class="arcu-lang-field <?php echo ($k == $defaultLang)? 'active' : 'hidden' ?>">
                        <textarea data-lang-field="true" data-serializable="true" data-lang-id="<?php echo $lang['id'] ?>" data-lang-code="<?php echo $lang['language_code'] ?>" placeholder="" id="<?php echo $formId ?>_<?php echo $name ?><?php echo ('_' . $k) ?>" data-default="" data-serializable="true" name="<?php echo $name ?>"></textarea>
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
        <?php if ($lang){ ?>
            <textarea placeholder="" id="<?php echo $formId ?>_<?php echo $name ?>_<?php echo $defaultLang ?>" data-default="" data-serializable="true" name="<?php echo $name ?>"></textarea>
        <?php } else { ?>
            <textarea placeholder="" id="<?php echo $formId ?>_<?php echo $name ?>" data-default="" data-serializable="true" name="<?php echo $name ?>"></textarea>
        <?php } ?>
    <?php } ?>
    <?php if ($hint){ ?>
        <div class="help-block">
            <?php echo $hint ?>
        </div>
    <?php } ?>
    <div class="errors"></div>
</div>