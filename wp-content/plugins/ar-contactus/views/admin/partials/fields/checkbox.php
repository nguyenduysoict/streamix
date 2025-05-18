<div class="field <?php echo $required? 'required' : '' ?>">
    <div class="ui toggle checkbox">
        <input id="<?php echo $formId ?>_<?php echo $name ?>_OFF" name="<?php echo $name ?>" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
        <input id="<?php echo $formId ?>_<?php echo $name ?>" name="<?php echo $name ?>" data-serializable="true" data-default="<?php echo $default ?>" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
        <label for="<?php echo $formId ?>_<?php echo $name ?>"><?php echo $label ?></label>
    </div>
    <div class="errors"></div>
</div>