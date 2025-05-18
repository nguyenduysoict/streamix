<?php if ($menuConfig->menu_bg){?>
    .arcu-widget .messangers-block{
        background-color: #<?php echo $menuConfig->menu_bg ?>;
    }
    .arcu-widget .messangers-block:before{
        border-top-color: #<?php echo $menuConfig->menu_bg ?>;
    }
<?php } ?>
<?php if ($menuConfig->menu_color){?>
    .messangers-block .messanger p, .messangers-block .messanger .arcu-item-label{
        color:  #<?php echo $menuConfig->menu_color ?>;
    }
<?php } ?>
<?php if ($menuConfig->menu_hcolor){?>
    .messangers-block .messanger:hover p, .messangers-block .messanger:hover .arcu-item-label{
        color:  #<?php echo $menuConfig->menu_hcolor ?>;
    }
<?php } ?>
<?php if ($menuConfig->menu_hbg){?>
    .messangers-block .messanger:hover{
        background-color:  #<?php echo $menuConfig->menu_hbg ?>;
    }
<?php } ?>
<?php if ($menuConfig->menu_subtitle_color) { ?>
    .arcu-widget .messanger p .arcu-item-subtitle, .arcu-widget .messanger .arcu-item-label .arcu-item-subtitle{
        color:  #<?php echo $menuConfig->menu_subtitle_color ?>;
    }
<?php } ?>
<?php if ($menuConfig->menu_subtitle_hcolor) { ?>
    .arcu-widget .messanger:hover p .arcu-item-subtitle, .arcu-widget .messanger:hover .arcu-item-label .arcu-item-subtitle{
        color:  #<?php echo $menuConfig->menu_subtitle_hcolor ?>;
    }
<?php } ?>
#arcu-message-callback-phone-submit{
    font-weight: normal;
}
<?php if ($popupConfig->hide_recaptcha){?>
    .grecaptcha-badge{
        display: none;
    }
<?php } ?>
<?php if ($buttonConfig->x_offset){?>
    .arcu-widget.<?php echo $buttonConfig->position ?>.arcu-message{
        <?php if ($buttonConfig->position == 'left'){?>
            left: <?php echo (int)$buttonConfig->x_offset ?>px;
        <?php } ?>
        <?php if ($buttonConfig->position == 'right'){?>
            right: <?php echo (int)$buttonConfig->x_offset ?>px;
        <?php } ?>
    }
<?php } ?>
    
.arcu-widget .arcu-message-button .static div svg, 
.arcu-widget .arcu-message-button .static div i, 
.arcu-widget .arcu-message-button .static div img{
    width: <?php echo $buttonConfig->button_icon_size ?>px;
    height: <?php echo $buttonConfig->button_icon_size ?>px;
}
.arcu-widget .arcu-message-button .static div img{
    border-radius: 50%;
    display: block;
}
    
<?php if ($buttonConfig->y_offset){?>
    .arcu-widget.<?php echo $buttonConfig->position ?>.arcu-message{
        bottom: <?php echo (int)$buttonConfig->y_offset ?>px;
    }
<?php } ?>
<?php if ($buttonConfig->position == 'storefront'){?>
    .arcu-widget .arcu-message-button{
        display: none;
    }
    .arcu-widget.arcu-message{
        bottom: -1000px;
    }
<?php } ?>
<?php if ($menuConfig->shadow_size){ ?>
    .arcu-widget .messangers-block, .arcu-widget .arcu-prompt, .arcu-widget .callback-countdown-block{
        box-shadow: 0 0 <?php echo (int)$menuConfig->shadow_size ?>px rgba(0, 0, 0, <?php echo $menuConfig->shadow_opacity ?>);
    }
<?php } ?>
.arcu-widget .arcu-message-button .pulsation{
    -webkit-animation-duration:<?php echo $buttonConfig->pulsate_speed / 1000 ?>s;
    animation-duration: <?php echo $buttonConfig->pulsate_speed / 1000 ?>s;
}
<?php if ($menuConfig->item_border_style != 'none' && $menuConfig->item_border_color){?>
.arcu-widget.arcu-message .messangers-block .messangers-list li{
    border-bottom: 1px <?php echo $menuConfig->item_border_style ?> #<?php echo $menuConfig->item_border_color ?>;
}
.arcu-widget.arcu-message .messangers-block .messangers-list li:last-child{
    border-bottom: 0 none;
}
<?php } ?>
#ar-zalo-chat-widget{
    display: none;
}
#ar-zalo-chat-widget.active{
    display: block;
}
<?php if (!$isMobile){ ?>
.arcu-widget .messangers-block,
.arcu-widget .arcu-popup{
    <?php if ($menuConfig->menu_width){ ?>
        width: <?php echo (int)$menuConfig->menu_width ?>px;
    <?php }else{ ?>
        width: auto;
    <?php } ?>
}
.messangers-block .messanger p, .messangers-block .messanger .arcu-item-label{
    <?php if (!$menuConfig->menu_width){ ?>
        white-space: nowrap;
    <?php } ?>
}
<?php if ($popupConfig->popup_width){?>
.arcu-widget .callback-countdown-block{
    width: <?php echo (int)$popupConfig->popup_width ?>px;
}
<?php } ?>
<?php } ?>

.arcu-widget.no-bg .messanger .arcu-item-label{
    background: #<?php echo $menuConfig->menu_bg?>;
}
.arcu-widget.no-bg .messanger:hover .arcu-item-label{
    background: #<?php echo $menuConfig->menu_hbg?>;
}
.arcu-widget.no-bg .messanger .arcu-item-label:before,
.arcu-widget.no-bg .messanger:hover .arcu-item-label:before{
    border-left-color: #<?php echo $menuConfig->menu_hbg?>;
}
.arcu-widget.left.no-bg .messanger:hover .arcu-item-label:before{
    border-right-color: #<?php echo $menuConfig->menu_hbg?>;
    border-left-color: transparent;
}

<?php if ($menuConfig->shadow_size){ ?>
    .arcu-widget.no-bg .messanger:hover .arcu-item-label{
        box-shadow: 0 0 <?php echo (int)$menuConfig->shadow_size ?>px rgba(0, 0, 0, <?php echo $menuConfig->shadow_opacity ?>);
    }
<?php } ?>
<?php if (!$isMobile){?>
    .arcu-widget .arcu-forms-container{
        width: auto;
    }
    <?php if (isset($formsConfig) && $formsConfig) { ?>
        <?php foreach ($formsConfig->getForms() as $form){ ?>
            <?php if ($form->desktopWidth){ ?>
                .arcu-widget .arcu-forms-container #arcu-form-<?php echo $form->id ?> {
                    width: <?php echo $form->desktopWidth ?>px;
                }
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>
@media(max-width: 428px){
    .arcu-widget.<?php echo $buttonConfig->position ?>.arcu-message.opened,
    .arcu-widget.<?php echo $buttonConfig->position ?>.arcu-message.open,
    .arcu-widget.<?php echo $buttonConfig->position ?>.arcu-message.popup-opened{
        left: 0;
        right: 0;
        bottom: 0;
    }
}
<?php if (isset($generalConfig) && $generalConfig && $generalConfig->font) {?>
    .arcu-widget .messanger p,
    .arcu-widget .messanger .arcu-item-label,
    .arcu-widget .arcu-message-button p,
    .arcu-widget .arcu-message-button .arcu-item-label,
    .arcu-widget .arcu-forms-container .arcu-form-container,
    .arcu-widget .arcu-forms-container .arcu-form-container .arcu-form-field,
    .arcu-widget .arcu-forms-container .arcu-form-container .arcu-button{
        font-family: <?php echo $generalConfig->font ?>;
    }
<?php } ?>

<?php if (isset($generalConfig) && $generalConfig && $generalConfig->custom_css){ ?>
    /* Custom CSS */
    <?php echo $generalConfig->custom_css; ?>
    /* Custom CSS end */
<?php }