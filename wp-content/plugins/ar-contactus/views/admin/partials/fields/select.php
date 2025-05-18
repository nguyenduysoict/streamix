<div class="field <?php echo $required? 'required' : '' ?>">
    <label><?php echo $label ?></label>
    <select id="<?php echo $formId ?>_<?php echo $name ?>" name="<?php echo $name ?>" class="form-control arcontactus-control" data-serializable="true" data-default="<?php echo $default ?>">
        <?php foreach ($values as $k => $v) {?>
            <option value="<?php echo $k ?>"><?php echo $v ?></option>
        <?php } ?> 
    </select>
    <div class="errors"></div>
</div>