<ul>
    <?php if (!empty($form)){
        foreach ($form->fields as $field) {
            if ($field->report) { ?>
            <li>
                <?php if ($field->id == 'phone') { ?>
                    <?php echo $field->getLangValue('report_label', $currentLang) ?>: <b><a href="tel:<?php echo ArContactUsTools::formatPhone($model->params[$field->id]) ?>"><?php echo $model->params[$field->id] ?></a></b>
                <?php } elseif($field->id == 'email') { ?>
                    <?php echo $field->getLangValue('report_label', $currentLang) ?>: <b><a href="mailto:<?php echo $model->params[$field->id] ?>"><?php echo $model->params[$field->id] ?></a></b>
                <?php } else { ?>
                    <?php echo $field->getLangValue('report_label', $currentLang) ?>: <b><?php echo $model->params[$field->id] ?></b>
                <?php } ?>
            </li>
            <?php }
        }
    } ?>
    <li>
        <?php echo __('User', 'ar-contactus') ?>: <b><?php echo $model->getUserName() ?></b>
    </li>
    <li>
        <?php echo __('Created at', 'ar-contactus') ?>: <b><?php echo $model->created_at ?></b>
    </li>
    <li>
        <?php echo __('Status', 'ar-contactus') ?>: <b><?php echo $model->getStatusLabel() ?></b>
    </li>
</ul>