<div class="field">
    <label for="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>"><?php echo $label ?></label>
    <div id="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>-preview" class="arcu-img-preview">

    </div>
    <button type="button" class="arcu-media-button button button-primary button-large icon" 
            data-preview="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>-preview" 
            data-config="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>" 
            id="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>-button">
        <i class="image icon"></i> <?php echo __('Select image', 'ar-contacus') ?>
    </button>

    <input id="<?php echo $formId ?>_<?php echo str_replace('.', '_', $name) ?>" value="" data-default="" data-serializable="true" name="<?php echo $name ?>" type="hidden">
    <div class="errors"></div>
</div>