<div class="ui segment arconfig-panel <?php echo (in_array($activeSubmit, array('ArContactUsConfigForms')))? '' : 'hidden' ?>" id="arcontactus-forms">
    <button type="button" onclick="arCU.form.reset('<?php echo __('This action will replace all current forms to default! Do you want to proceed?', 'ar-contactus') ?>')" class="arcu-reset-forms" title="<?php echo __('Reset forms to default', 'ar-contactus') ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="redo-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-redo-alt fa-w-16 fa-2x"><path fill="currentColor" d="M483.515 28.485L431.35 80.65C386.475 35.767 324.485 8 256.001 8 119.34 8 7.9 119.525 8 256.185 8.1 393.067 119.095 504 256 504c63.926 0 122.202-24.187 166.178-63.908 5.113-4.618 5.353-12.561.482-17.433l-19.738-19.738c-4.498-4.498-11.753-4.785-16.501-.552C351.787 433.246 306.105 452 256 452c-108.321 0-196-87.662-196-196 0-108.321 87.662-196 196-196 54.163 0 103.157 21.923 138.614 57.386l-54.128 54.129c-7.56 7.56-2.206 20.485 8.485 20.485H492c6.627 0 12-5.373 12-12V36.971c0-10.691-12.926-16.045-20.485-8.486z" class=""></path></svg>
    </button>
    <div class="ui grid">
        <?php foreach($formsConfig->getForms() as $form){?>
        <div class="six wide column">
            <div class="ui segment arcu-form" id="arcu-form-<?php echo $form->id ?>" data-id="<?php echo $form->id ?>">
                <div class="ui form">
                    <div class="arcu-form-header">
                        <?php echo $form->getLangValue('header', $defaultLang) ?>
                        <button type="button" class="circular ui icon button" onclick="arCU.form.edit('<?php echo $form->id ?>')">
                            <i class="icon settings"></i>
                        </button>
                    </div>
                    <div class="arcu-form-fields">
                        <?php foreach ($form->fields as $field){ ?>
                            <div data-id="<?php echo $field->id ?>"  class="field arcu-form-field-group <?php echo $field->type == 'checkbox'? 'arcu-form-field-group-checkbox' : '' ?> <?php echo $field->required? 'required field-required' : '' ?>">
                                <div class="arcu-move-handle">
                                    <i class="icon move"></i>
                                </div>
                                <?php if (!$form->isUnremovableField($field->id)) { ?>
                                    <button type="button" class="circular ui icon button delete" title="<?php echo __('Remove field', 'ar-contactus') ?>" onclick="arCU.form.field.remove('<?php echo $form->id ?>', '<?php echo $field->id ?>')">
                                        <i class="icon trash"></i>
                                    </button>
                                <?php } ?>
                                <button type="button" class="circular ui icon button edit" title="<?php echo __('Field settings', 'ar-contactus') ?>" onclick="arCU.form.field.edit('<?php echo $form->id ?>', '<?php echo $field->id ?>')">
                                    <i class="icon settings"></i>
                                </button>
                                <?php if (in_array($field->type, array('text', 'tel', 'email'))){ ?>
                                    <?php if ($field->label){ ?>
                                        <label for="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>"><?php echo $field->getLangValue('label', $defaultLang) ?></label>
                                    <?php } ?>
                                    <input 
                                        type="<?php echo $field->type ?>" 
                                        id="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>" 
                                        data-id="<?php echo $field->id ?>"
                                        value="<?php echo $field->getLangValue('value', $defaultLang) ?>"
                                        readonly=""
                                        placeholder="<?php echo $field->getLangValue('placeholder', $defaultLang) ?>" />
                                <?php } elseif($field->type == 'select') { ?>
                                    <?php if ($field->label){ ?>
                                        <label for="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>"><?php echo $field->getLangValue('label', $defaultLang) ?></label>
                                    <?php } ?>
                                    <select readonly="" id="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>" placeholder="<?php echo $field->getLangValue('placeholder', $defaultLang) ?>">
                                        <?php foreach ($field->getValues($defaultLang) as $value) { ?>
                                            <option value="<?php echo $value['value'] ?>"><?php echo $value['label'] ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } elseif($field->type == 'textarea') { ?>
                                    <?php if ($field->label){ ?>
                                        <label for="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>"><?php echo $field->getLangValue('label', $defaultLang) ?></label>
                                    <?php } ?>
                                    <textarea 
                                        readonly=""
                                        id="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>" 
                                        data-id="<?php echo $field->id ?>"
                                        placeholder="<?php echo $field->getLangValue('placeholder', $defaultLang) ?>"><?php echo $field->getLangValue('value', $defaultLang) ?></textarea>
                                <?php } elseif($field->type == 'checkbox') { ?>
                                    <div class="ui checkbox">
                                        <?php if ($field->label){ ?>
                                            <label for="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>">
                                                <input type="checkbox" readonly="" id="arcu-form-<?php echo $form->id ?>-<?php echo $field->id ?>" data-id="<?php echo $field->id ?>" />
                                                <?php echo $field->getLangValue('label', $defaultLang) ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="arcu-field-add">
                        <button class="circular ui icon button add" title="<?php echo __('Add field', 'ar-contactus') ?>" onclick="arCU.form.field.add('<?php echo $form->id ?>')">
                            <i class="icon plus"></i>
                        </button>
                    </div>
                    <div class="arcu-form-buttons">
                        <?php foreach ($form->buttons as $button){ ?>
                            <div class="arcu-form-button-group" data-id="<?php echo $button->id ?>">
                                <div class="arcu-move-handle">
                                    <i class="icon move"></i>
                                </div>
                                <button type="button" class="circular ui icon button delete" title="<?php echo __('Remove button', 'ar-contactus') ?>" onclick="arCU.form.button.remove('<?php echo $form->id ?>', '<?php echo $button->id ?>')">
                                    <i class="icon trash"></i>
                                </button>
                                <button type="button" class="circular ui icon button edit" title="<?php echo __('Button settings', 'ar-contactus') ?>" onclick="arCU.form.button.edit('<?php echo $form->id ?>', '<?php echo $button->id ?>')">
                                    <i class="icon settings"></i>
                                </button>
                                <?php if (in_array($button->type, array('submit', 'button'))) {?>
                                    <button class="ui button fluid" type="button"><?php echo $button->getLangValue('label', $defaultLang) ?></button>
                                <?php } elseif($button->type == 'link') { ?>
                                    <a class="ui button fluid" href="<?php echo $button->url ?>"><?php echo $button->getLangValue('label', $defaultLang) ?></a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="arcu-field-add">
                        <button class="circular ui icon button add" title="<?php echo __('Add button', 'ar-contactus') ?>" onclick="arCU.form.button.add('<?php echo $form->id ?>')">
                            <i class="icon plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>