<?php 
/* @var $model ArContactUsModel */
?>
<a id="arcontactus-menu-item-<?php echo $model->id ?>" 
   style="border-left: 2px solid #<?php echo $model->color ?>; 
   <?php if (isset($params['bg']) && $params['bg']){ echo 'background-color: ' . $params['bg'] . ';'; } ?>"
   class="arcu-menu-item"
    <?php if ($model->type == ArContactUsModel::TYPE_LINK){ ?>
        href="<?php echo $model->getLink() ?>" 
        target="<?php echo $model->target == ArContactUsModel::TARGET_NEW_WINDOW? '_blank' : '_self' ?>"
    <?php }elseif($model->type == ArContactUsModel::TYPE_INTEGRATION || $model->type == ArContactUsModel::TYPE_JS){ ?>
        href="#" 
        onclick="contactUs.triggerItem('click', '<?php echo $model->id ?>'); return false"
    <?php }elseif($model->type == ArContactUsModel::TYPE_FORM){ ?>
        href="#"
        onclick="contactUs.showForm('<?php echo ArContactUsTools::escJsString($model->params->form) ?>'); return false;"
    <?php } ?>>
    <span class="arcu-menu-item-icon" style="color: #<?php echo $model->color ?>">
        <?php if (isset($model->params->icon_type) && $model->params->icon_type == 2 && isset($model->params->icon_img) && $model->params->icon_img){ ?>
            <?php echo wp_get_attachment_image($model->params->icon_img, 'full', false); ?>
        <?php } else { ?>
            <?php echo $model->getIcon() ?>
        <?php } ?>
    </span>
    <span class="arcu-menu-item-title" style="<?php if (isset($params['color']) && $params['color']){ echo 'color: ' . $params['color'] . ';'; } ?>">
        <?php echo $model->title ?>
    </span>
</a>