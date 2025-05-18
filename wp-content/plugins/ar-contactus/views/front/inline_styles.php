<?php if (!empty($generatedCss)){?>
    <?php echo $generatedCss ?>
<?php } ?>
<?php if ($jivosite) {?>
    .globalClass_ET{display: none}
    .globalClass_ET.active{display: block}
<?php } ?>
<?php if ($phplive){ ?>
    #phplive_btn_1576807307_clone{
        display: none !important;
    }
<?php } ?>
<?php if ($paldesk){?>
    #paldesk-widget-btnframe{
        display: none;
    }
<?php } ?>
<?php if ($vkChat){ ?>
    <?php if (!$isMobile) {?>
        #vk_community_messages{
            <?php if ($buttonConfig->position == 'right') { ?>
                right: -10px !important;
            <?php }else{ ?>
                left: -10px !important;
            <?php } ?>
        }
    <?php } ?>
<?php } ?>
<?php if ($liveChatsConfig->isFacebookChatIntegrated() && $facebook) {?>
    <?php if ($buttonConfig->position == 'left'){ ?>
        .fb-customerchat > span > iframe{
            left: 10px !important;
            right: auto !important;
        }
        .fb-customerchat > span > iframe.fb_customer_chat_bounce_in_v2_mobile_chat_started{
            left: 0 !important;
        }
    <?php }else{ ?>
        .fb-customerchat > span > iframe{
            right: 10px !important;
            left: auto !important;
        }
        .fb-customerchat > span > iframe.fb_customer_chat_bounce_in_v2_mobile_chat_started{
            right: 0 !important;
        }
    <?php } ?>
    #ar-fb-chat{
        display: block;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
    }
    #ar-fb-chat.active{
        display: block;
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }
    @media (max-width: 480px){
        .fb-customerchat > span > iframe{
            left: 0 !important;
            right: 0 !important;
        }
    }
<?php } ?>