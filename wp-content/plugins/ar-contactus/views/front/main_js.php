var $arcuWidget;
var zaloWidgetInterval;
var tawkToInterval;
var tawkToHideInterval;
var skypeWidgetInterval;
var lcpWidgetInterval;
var closePopupTimeout;
var lzWidgetInterval;
var paldeskInterval;
var arcuOptions;
var hideCustomerChatInterval;
var _arCuTimeOut = null;
var arCuPromptClosed = false;
var _arCuWelcomeTimeOut = null;
var arCuMenuOpenedOnce = false;
var arcuAppleItem = null;

<?php if ($promptConfig->enable_prompt && $messages){?>
    var arCuMessages = <?php echo json_encode($messages) ?>;
    var arCuLoop = <?php echo $promptConfig->loop? 'true' : 'false' ?>;;
    var arCuCloseLastMessage = <?php echo $promptConfig->close_last? 'true' : 'false' ?>;
    var arCuDelayFirst = <?php echo (int)$promptConfig->first_delay ?>;
    var arCuTypingTime = <?php echo (int)$promptConfig->typing_time ?>;
    var arCuMessageTime = <?php echo (int)$promptConfig->message_time ?>;
    var arCuClosedCookie = 0;
<?php } ?>
<?php if ($menuConfig->menu_layout == 'personal' && $welcomeConfig) { ?>
    var arWelcomeMessages = <?php echo json_encode($welcomeConfig->getMessages($currentLang)) ?>;
    var arWelcomeDelayFirst = <?php echo (int)$welcomeConfig->getFirstDelay() ?>;
    var arWelcomeTypingTime = <?php echo (int)$welcomeConfig->getTypingTime() ?>;
    var arWelcomeMessageTime = <?php echo (int)$welcomeConfig->getMessageTime() ?>;    
<?php } ?>
var arcItems = [];
<?php if ($liveChatsConfig->isTawkToIntegrated() && $tawkTo) {?>
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
<?php } ?>
window.addEventListener('load', function(){
    $arcuWidget = document.createElement('div');
    var body = document.getElementsByTagName('body')[0];
    $arcuWidget.id = 'arcontactus';
    
    if (document.getElementById('arcontactus')) {
        document.getElementById('arcontactus').parentElement.removeChild(document.getElementById('arcontactus'));
    }
    
    body.appendChild($arcuWidget);
    
    <?php if ($promptConfig->show_after_close != '-1'){?>
        arCuClosedCookie = arCuGetCookie('arcu-closed');
    <?php } ?>
    $arcuWidget.addEventListener('arcontactus.init', function(){
        $arcuWidget.classList.add('arcuAnimated');
        $arcuWidget.classList.add('<?php echo ArContactUsTools::escJsString($buttonConfig->animation) ?>');

        setTimeout(function(){
            $arcuWidget.classList.remove('<?php echo ArContactUsTools::escJsString($buttonConfig->animation) ?>');
        }, 1000);
        
        <?php if ($apple) {?>
            if(!window.appleBusinessChat.isSupported() && arcuAppleItem) {
                if (document.getElementById(arcuAppleItem)) {
                    const appleElement = document.getElementById(arcuAppleItem).parentNode;
                    $arcuWidget.querySelector('.messangers-list').removeChild(appleElement);
                    console.log('Apple business chat is not supported on this device');
                }
            }
        <?php } ?>
            
        <?php foreach($formsConfig->getForms() as $form) { ?>
            if (document.querySelector('#arcu-form-<?php echo $form->id ?> form')) {
                document.querySelector('#arcu-form-<?php echo $form->id ?> form').append(contactUs.utils.DOMElementFromHTML(arCUVars._wpnonce));
            }
            <?php foreach ($form->fields as $field) { ?>
                <?php if ($field->mask_on && $field->mask) { ?>
                    console.log('Mask is not supported yet.')
                <?php } ?> 
            <?php } ?>
            <?php if ($popupConfig->recaptcha) { ?>
                var $gToken = contactUs.utils.createElement('input', {
                    type: 'hidden',
                    name: 'gtoken',
                    classes: ['ar-g-token']
                });
                $arcuWidget.querySelector('#arcu-form-<?php echo $form->id ?> form').append($gToken);
            <?php } ?>
        <?php } ?>
        <?php foreach($formsConfig->getForms() as $form) { ?>
            $arcuWidget.addEventListener('arcontactus.successSendFormData', function(event){
                <?php if ($form->autoClose) {?>
                    if (event.detail.form.getAttribute('data-id') == '<?php echo $form->id ?>') {
                        closePopupTimeout = setTimeout(function(){
                            contactUs.hideForm();
                        }, <?php echo (int)$form->autoClose * 1000 ?>);
                    }
                <?php } ?>
            });
        <?php } ?>
        $arcuWidget.addEventListener('arcontactus.errorSendFormData', function(event){
            if (event.detail.data && event.detail.data.message) {
                alert(event.detail.data.message);
            }
        });
        $arcuWidget.addEventListener('arcontactus.hideFrom', function(){
            clearTimeout(closePopupTimeout);
        });
        <?php if ($promptConfig->enable_prompt && $messages){ ?>
            if (arCuClosedCookie){
                return false;
            }
            arCuShowMessages();
        <?php } ?>
        <?php if ($menuConfig->menu_layout == 'personal' && $welcomeConfig && $welcomeConfig->show_type == 'page_load') { ?>
            arCuShowWellcomeMessages();
        <?php } ?>
        <?php if ($menuConfig->auto_open){ ?>
            setTimeout(function(){
                if (arCuGetCookie('arcumenu-closed') == 0){
                    contactUs.openMenu();
                }
            }, <?php echo (int)$menuConfig->auto_open ?>);
        <?php } ?>
    });
    $arcuWidget.addEventListener('arcontactus.closeMenu', function(){
        arCuCreateCookie('arcumenu-closed', 1, 1);
    });
    <?php if (($promptConfig->enable_prompt && $messages) || ($menuConfig->menu_layout == 'personal' && $welcomeConfig && $welcomeConfig->show_type == 'menu_open')){ ?>
        $arcuWidget.addEventListener('arcontactus.openMenu', function(){
            clearTimeout(_arCuTimeOut);
            if (!arCuPromptClosed){
                arCuPromptClosed = true;
                contactUs.hidePrompt();
            }
            <?php if ($menuConfig->menu_layout == 'personal' && $welcomeConfig && $welcomeConfig->show_type == 'menu_open') { ?>
                if (!arCuMenuOpenedOnce) {
                    arCuMenuOpenedOnce = true;
                    arCuShowWellcomeMessages();
                }
            <?php } ?> 
        });
        $arcuWidget.addEventListener('arcontactus.showFrom', function(){
            clearTimeout(_arCuTimeOut);
            if (!arCuPromptClosed){
                arCuPromptClosed = true;
                contactUs.hidePrompt();
            }
            <?php if ($menuConfig->menu_layout == 'personal' && $welcomeConfig && $welcomeConfig->show_type == 'menu_open') { ?>
                if (!arCuMenuOpenedOnce) {
                    arCuMenuOpenedOnce = true;
                    arCuShowWellcomeMessages();
                }
            <?php } ?> 
        });
        $arcuWidget.addEventListener('arcontactus.showForm', function(){
            clearTimeout(_arCuTimeOut);
            if (!arCuPromptClosed){
                arCuPromptClosed = true;
                contactUs.hidePrompt();
            }
        });

        $arcuWidget.addEventListener('arcontactus.hidePrompt', function(){
            clearTimeout(_arCuTimeOut);
            if (arCuClosedCookie != "1"){
                arCuClosedCookie = "1";
                <?php if ($promptConfig->show_after_close != '-1'){?>
                    arCuPromptClosed = true;
                    <?php if ($promptConfig->show_after_close == '0'){?>
                        arCuCreateCookie('arcu-closed', 1, 0);
                    <?php }else{ ?>
                        arCuCreateCookie('arcu-closed', 1, <?php echo ((int)$promptConfig->show_after_close) / 1440 ?>);
                    <?php } ?>
                <?php } ?>
            }
        });
    <?php } ?>
    <?php foreach ($items as $item){
        $params = $item['params'];
        ?>
        <?php if ($item['js'] && $item['type'] == ArContactUsModel::TYPE_FORM){ ?>
            $arcuWidget.addEventListener('arcontactus.successSendFormData', function(event){
                if (event.detail.form.getAttribute('data-id') == '<?php echo $params->form ?>'){
                    <?php echo $item['js'] ?>
                }
            });
        <?php } ?>
        var arcItem = {};
        <?php if ($item['id']){?>
            arcItem.id = '<?php echo ArContactUsTools::escJsString($item['id']) ?>';
        <?php } ?>
        <?php if (isset($params->nocontainer) && $params->nocontainer){?>
            arcItem.noContainer = 1;
        <?php } ?>
        <?php if ($item['type'] == ArContactUsModel::TYPE_INTEGRATION){ ?>
            arcItem.onClick = function(e){
                e.preventDefault();
                e.stopPropagation();
                contactUs.closeMenu();
            <?php if ($item['integration'] == 'tawkto'){ ?>
                if (typeof Tawk_API == 'undefined'){
                    console.error('Tawk.to integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                Tawk_API.showWidget();
                Tawk_API.maximize();
            <?php }elseif($item['integration'] == 'crisp'){ ?>
                if (typeof $crisp == 'undefined'){
                    console.error('Crisp integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                $crisp.push(["do", "chat:show"]);
                $crisp.push(["do", "chat:open"]);
            <?php }elseif ($item['integration'] == 'intercom'){ ?>
                if (typeof Intercom == 'undefined'){
                    console.error('Intercom integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                Intercom('show');
            <?php }elseif ($item['integration'] == 'facebook'){ ?>
                if (typeof FB == 'undefined' || typeof FB.CustomerChat == 'undefined'){
                    console.error('Facebook customer chat integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                document.getElementById('ar-fb-chat').classList.add('active');
                clearInterval(hideCustomerChatInterval);
                setTimeout(function(){
                    FB.CustomerChat.show(true);
                    FB.CustomerChat.showDialog();
                }, 500);
            <?php }elseif ($item['integration'] == 'vk'){ ?>
                if (typeof vkMessagesWidget == 'undefined'){
                    console.error('VK chat integration is disabled in module configuration');
                    return false;
                }
                vkMessagesWidget.expand();
            <?php }elseif ($item['integration'] == 'zopim'){ ?>
                <?php if ($liveChatsConfig->isZendeskChat()) {?>
                    if (typeof zE == 'undefined'){
                        console.error('Zendesk integration is disabled in module configuration');
                        return false;
                    }
                    zE('webWidget', 'show');
                    zE('webWidget', 'open');
                <?php }else{ ?>
                    if (typeof $zopim == 'undefined'){
                        console.error('Zendesk integration is disabled in module configuration');
                        return false;
                    }
                    $zopim.livechat.window.show();
                <?php } ?>
                contactUs.hide();
            <?php }elseif ($item['integration'] == 'zalo'){ ?>
                if (typeof ZaloSocialSDK == 'undefined'){
                    console.error('Zalo integration is disabled in module configuration');
                    return false;
                }
                document.getElementById('ar-zalo-chat-widget').classList.add('active');
                ZaloSocialSDK.openChatWidget();
                zaloWidgetInterval = setInterval(function(){
                    checkZaloIsOpened();
                }, 100);
                contactUs.hide();
            <?php }elseif ($item['integration'] == 'lhc'){ ?>
                if (typeof lh_inst == 'undefined'){
                    console.error('Live Helper Chat integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                lh_inst.lh_openchatWindow();
            <?php }elseif ($item['integration'] == 'smartsupp'){ ?>
                if (typeof smartsupp == 'undefined'){
                    console.error('Smartsupp chat integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                document.getElementById('chat-application').classList.add('active');
                smartsupp('chat:open');
                ssInterval = setInterval(function(){
                    checkSSIsOpened();
                }, 100);
            <?php }elseif ($item['integration'] == 'livechat'){?>
                if (typeof LC_API == 'undefined'){
                    console.error('Live Chat integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                LC_API.open_chat_window();
            <?php }elseif ($item['integration'] == 'livechatpro'){?>
                if (typeof phpLiveChat == 'undefined'){
                    console.error('Live Chat Pro integration is disabled in module configuration');
                    return false;
                }
                <?php if (!$isMobile) {?>
                    contactUs.hide();
                <?php } ?>
                document.getElementById('customer-chat-iframe').classList.add('active');
                setTimeout(function(){
                    lcpWidgetInterval = setInterval(function(){
                        checkLCPIsOpened();
                    }, 100);
                }, 500);
                phpLiveChat.show();
            <?php }elseif ($item['integration'] == 'livezilla'){?>
                if (typeof OverlayChatWidgetV2 == 'undefined'){
                    console.error('Live Zilla integration is disabled in module configuration');
                    return false;
                }
                document.getElementById('lz_overlay_wm').classList.add('active');
                OverlayChatWidgetV2.Show();
                lzWidgetInterval = setInterval(function(){
                    checkLZIsOpened();
                }, 100);
            <?php }elseif ($item['integration'] == 'tidio'){?>
                if (typeof tidioChatApi == 'undefined'){
                    console.error('Tidio integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                tidioChatApi.show();
                tidioChatApi.open();
            <?php }elseif ($item['integration'] == 'jivosite'){?>
                if (typeof jivo_api == 'undefined'){
                    console.error('Jivosite integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                jivo_api.open();
            <?php }elseif ($item['integration'] == 'zoho'){?>
                if (typeof $zoho == 'undefined'){
                    console.error('Zoho SalesIQ integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                $zoho.salesiq.floatwindow.visible('show');
            <?php }elseif ($item['integration'] == 'fc'){?>
                if (typeof fcWidget == 'undefined'){
                    console.error('FreshChat integration is disabled in module configuration');
                    return false;
                }
                contactUs.hide();
                window.fcWidget.show();
                window.fcWidget.open();
            <?php }elseif ($item['integration'] == 'phplive'){?>
                phplive_launch_chat_1();
                contactUs.hide();
            <?php }elseif ($item['integration'] == 'paldesk'){?>
                window.BeeBeeate.widget.openChatWindow();
                contactUs.hide();
                paldeskInterval = setInterval(function(){
                    checkPaldeskIsOpened();
                }, 100);
            <?php } ?>


            <?php if ($item['js']){ ?>
                <?php echo $item['js'] ?>
            <?php } ?>
            }
        <?php }elseif ($item['type'] == ArContactUsModel::TYPE_FORM){ ?>
            arcItem.onClick = function(e){
                e.preventDefault();
                contactUs.closeMenu();
                contactUs.showForm('<?php echo ArContactUsTools::escJsString($params->form) ?>');
                return false;
            }
        <?php }elseif ($item['type'] == ArContactUsModel::TYPE_CONTENT){ ?>
            arcItem.href = '_popup';
            arcItem.popupContent = document.getElementById('arcu-popup-content-<?php echo (int)$item['id'] ?>').innerHTML;
            document.getElementById('arcu-popup-content-<?php echo (int)$item['id'] ?>').remove();
        <?php }elseif ($item['js']){ ?>
            arcItem.onClick = function(e){
                <?php if ($item['type'] == ArContactUsModel::TYPE_JS){ ?>
                    e.preventDefault();                        
                <?php } ?>
                <?php echo $item['js'] ?>
            }                
        <?php } ?>
        <?php if ($item['online'] !== null) {?>
            arcItem.online = <?php echo $item['online']? 'true' : 'false' ?>;
        <?php } ?>
        arcItem.class = '<?php echo ArContactUsTools::escJsString($item['class']) ?>';
        arcItem.title = "<?php echo ArContactUsTools::escJsString($item['title']) ?>";
        <?php if ($item['subtitle']){?>
            arcItem.subTitle = "<?php echo ArContactUsTools::escJsString($item['subtitle']) ?>";
        <?php } ?>
        <?php if (isset($params->icon_type) && $params->icon_type == 2 && isset($params->icon_img) && $params->icon_img){ ?>
            arcItem.icon = '<?php echo wp_get_attachment_image($params->icon_img, 'full', false); ?>';
        <?php } else { ?>
            arcItem.icon = '<?php echo $item['icon'] ?>';
        <?php } ?>
        <?php if (isset($params->include_to_slider) && $params->include_to_slider){ ?>
            arcItem.includeIconToSlider = true;
        <?php } ?> 
        <?php if ($item['type'] == ArContactUsModel::TYPE_LINK){ ?>
            arcItem.href = '<?php echo $item['href'] ?>';
            <?php if (strpos($item['href'], '//bcrw.apple.com/')) { ?>
                arcuAppleItem = '<?php echo $item['id'] ?>';
            <?php } ?> 
        <?php }elseif($item['type'] == ArContactUsModel::TYPE_FORM){ ?>
            arcItem.href = null;
        <?php } ?>
        <?php if ($item['type'] == ArContactUsModel::TYPE_LINK && $item['target'] == ArContactUsModel::TARGET_SAME_WINDOW){ ?>
            arcItem.target = '_self';
        <?php } ?>
        arcItem.color = '<?php echo ArContactUsTools::escJsString($item['color']) ?>';
        arcItems.push(arcItem);
    <?php } ?>
    arcuOptions = {
        rootElementId: 'arcontactus',
        credits: false,
        visible: <?php echo $generalConfig->hide_on_load? 'false' : 'true' ?>,
        wordpressPluginVersion: '<?php echo ArContactUsTools::escJsString(AR_CONTACTUS_VERSION) ?>',
        online: <?php echo $buttonConfig->online_badge? 'true' : 'null' ?>,
        <?php if ($buttonConfig->button_icon_type == 'built-in' || empty($buttonConfig->button_icon_type)){ ?>
            <?php if ($buttonIcon){ ?>
                buttonIcon: '<?php echo $buttonIcon ?>',
            <?php } ?>
        <?php } elseif ($buttonConfig->button_icon_type == 'uploaded' && $buttonConfig->button_icon_img){?>
            buttonIcon: '<?php echo wp_get_attachment_image($buttonConfig->button_icon_img, 'full', false); ?>',
        <?php } ?>
        layout: '<?php echo $menuConfig->menu_layout ?>',
        <?php if ($menuConfig->menu_layout == 'personal'){ ?>
            <?php if ($wpml){ ?>
                itemsHeader: '<?php echo ArContactUsTools::escJsString($menuConfig->getLangValue('icons_title', $currentLang)) ?>',
            <?php } else { ?>
                itemsHeader: '<?php echo ArContactUsTools::escJsString($menuConfig->icons_title) ?>',        
            <?php } ?>
            style: 'popup',
        <?php } ?>
        drag: <?php echo $buttonConfig->drag? 'true' : 'false' ?>,
        mode: '<?php echo $buttonConfig->mode? ArContactUsTools::escJsString($buttonConfig->mode) : 'regular' ?>',
        buttonIconUrl: '<?php echo ArContactUsTools::escJsString(AR_CONTACTUS_PLUGIN_URL) . 'res/img/msg.svg' ?>',
        showMenuHeader: <?php echo $menuConfig->menu_header_on? 'true' : 'false' ?>,
        <?php if ($wpml){ ?>
            menuHeaderText: "<?php echo ArContactUsTools::escJsString($menuConfig->getLangValue('menu_header', $currentLang)) ?>",
            menuSubheaderText: "<?php echo ArContactUsTools::escJsString($menuConfig->getLangValue('menu_subheader', $currentLang)) ?>",
        <?php }else{ ?>
            menuHeaderText: "<?php echo ArContactUsTools::escJsString($menuConfig->menu_header) ?>",
            menuSubheaderText: "<?php echo ArContactUsTools::escJsString($menuConfig->menu_subheader) ?>",
        <?php } ?>

        <?php if ($menuConfig->menu_header_layout != 'noicon'){ ?>
            menuHeaderLayout: '<?php echo $menuConfig->menu_header_layout ?>',
            menuHeaderIcon: '<?php echo $menuConfig->getHeaderIcon() ?>',
            <?php if ($menuConfig->menu_header_layout == 'icon-left'){ ?>
                menuHeaderTextAlign: 'left',
            <?php } ?>
        <?php } ?>

        showHeaderCloseBtn: <?php echo $menuConfig->header_close? 'true' : 'false' ?>,
        <?php if (isset($menuConfig->menu_header_bg) && $menuConfig->menu_header_bg){ ?>
            headerCloseBtnBgColor: '#<?php echo ArContactUsTools::escJsString($menuConfig->header_close_bg) ?>',
        <?php } ?>
        <?php if ($menuConfig->header_close_bg){ ?>
            headerCloseBtnBgColor: '#<?php echo ArContactUsTools::escJsString($menuConfig->header_close_bg) ?>',
        <?php } ?>
        <?php if ($menuConfig->header_close_color){ ?>
            headerCloseBtnColor: '#<?php echo ArContactUsTools::escJsString($menuConfig->header_close_color) ?>',
        <?php } ?>
        itemsIconType: '<?php echo ArContactUsTools::escJsString($menuConfig->item_style) ?>',
        align: '<?php echo ArContactUsTools::escJsString($buttonConfig->position) ?>',
        reCaptcha: <?php echo $popupConfig->recaptcha? 'true' : 'false' ?>,
        reCaptchaKey: '<?php echo ArContactUsTools::escJsString($popupConfig->key) ?>',
        countdown: <?php echo (int)$popupConfig->timeout ?>,
        theme: '#<?php echo ArContactUsTools::escJsString($buttonConfig->button_color) ?>',
        <?php if ($buttonConfig->text){ ?>
            <?php if ($wpml){ ?>
                buttonText: "<?php echo ArContactUsTools::escJsString($buttonConfig->getLangValue('text', $currentLang)) ?>",
            <?php }else{ ?>
                buttonText: "<?php echo ArContactUsTools::escJsString($buttonConfig->text) ?>",
            <?php } ?>
        <?php }else{ ?>
            buttonText: false,
        <?php } ?>
        <?php if ($buttonConfig->title){ ?>
            <?php if ($wpml){ ?>
                buttonTitle: '<?php echo ArContactUsTools::escJsString($buttonConfig->getLangValue('title', $currentLang)); ?>',
            <?php } else { ?>
                buttonTitle: '<?php echo ArContactUsTools::escJsString($buttonConfig->title); ?>',
            <?php } ?>
        <?php } ?>
        <?php if ($buttonConfig->description){ ?>
            <?php if ($wpml){ ?>
                buttonDescription: '<?php echo ArContactUsTools::escJsString($buttonConfig->getLangValue('description', $currentLang)); ?>',
            <?php } else { ?>
                buttonDescription: '<?php echo ArContactUsTools::escJsString($buttonConfig->description); ?>',
            <?php } ?>
        <?php } ?>
        <?php if ($buttonConfig->label){ ?>
            <?php if ($wpml){ ?>
                buttonLabel: '<?php echo ArContactUsTools::escJsString($buttonConfig->getLangValue('label', $currentLang)); ?>',
            <?php } else { ?>
                buttonLabel: '<?php echo ArContactUsTools::escJsString($buttonConfig->label); ?>',
            <?php } ?>
        <?php } ?>
        buttonSize: '<?php echo ArContactUsTools::escJsString($buttonConfig->button_size) ?>',
        <?php if ((int)$buttonConfig->button_icon_size){?>
            buttonIconSize: <?php echo (int)$buttonConfig->button_icon_size ?>,
        <?php } ?>
        menuSize: '<?php echo ArContactUsTools::escJsString($menuConfig->menu_size) ?>',
        <?php if ($wpml){ ?>
            phonePlaceholder: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('phone_placeholder', $currentLang)) ?>',
            callbackSubmitText: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('btn_title', $currentLang)) ?>',
            errorMessage: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('fail_message', $currentLang), true) ?>',
            callProcessText: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('proccess_message', $currentLang), true) ?>',
            callSuccessText: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('success_message', $currentLang), true) ?>',
            callbackFormText: '<?php echo ArContactUsTools::escJsString($popupConfig->getLangValue('message', $currentLang), true) ?>',
        <?php }else{ ?>
            phonePlaceholder: '<?php echo ArContactUsTools::escJsString($popupConfig->phone_placeholder) ?>',
            callbackSubmitText: '<?php echo ArContactUsTools::escJsString($popupConfig->btn_title) ?>',
            errorMessage: '<?php echo ArContactUsTools::escJsString($popupConfig->fail_message, true) ?>',
            callProcessText: '<?php echo ArContactUsTools::escJsString($popupConfig->proccess_message, true) ?>',
            callSuccessText: '<?php echo ArContactUsTools::escJsString($popupConfig->success_message, true) ?>',
            callbackFormText: '<?php echo ArContactUsTools::escJsString($popupConfig->message, true) ?>',
        <?php } ?>
        iconsAnimationSpeed: <?php echo (int)$buttonConfig->icon_speed ?>,
        iconsAnimationPause: <?php echo (int)$buttonConfig->icon_animation_pause ?>,
        items: arcItems,
        ajaxUrl: '<?php echo admin_url('admin-ajax.php') ?>',
        <?php if ($promptConfig->prompt_position){?>
            promptPosition: '<?php echo ArContactUsTools::escJsString($promptConfig->prompt_position) ?>',
        <?php } ?>
        <?php if ($menuConfig->menu_popup_style == 'sidebar'){?>
            style: '<?php echo ArContactUsTools::escJsString($menuConfig->sidebar_animation) ?>',
        <?php } elseif ($menuConfig->menu_popup_style == 'no-background' && $menuConfig->menu_layout != 'personal') {?>
            style: 'no-background',
            <?php if ($menuConfig->popup_animation){?>
                popupAnimation: '<?php echo ArContactUsTools::escJsString($menuConfig->popup_animation) ?>',
            <?php } ?>
        <?php }else{ ?>
            <?php if ($menuConfig->popup_animation){?>
                popupAnimation: '<?php echo ArContactUsTools::escJsString($menuConfig->popup_animation) ?>',
            <?php } ?>
            style: '',
        <?php } ?>
        <?php if ($menuConfig->items_animation && ($menuConfig->items_animation != '-')){?>
            itemsAnimation: '<?php echo ArContactUsTools::escJsString($menuConfig->items_animation) ?>',
        <?php } ?>
        <?php if ($menuConfig->menu_style && $menuConfig->menu_popup_style != 'no-background') { ?>
            menuStyle: '<?php echo ArContactUsTools::escJsString($menuConfig->menu_style) ?>',
        <?php } ?>
        backdrop: <?php echo $menuConfig->backdrop? 'true' : 'false' ?>,
        forms: {
            <?php foreach ($formsConfig->getForms() as $form) { ?>
                <?php echo $form->id ?>: {
                    id: '<?php echo $form->id ?>',
                    <?php if ($form->layout != '1'){ ?>
                        header: {
                            content: "<?php echo ArContactUsTools::escJsString($form->getLangValue('header', $currentLang)) ?>",
                            layout: "<?php echo ArContactUsTools::escJsString($form->getLayout()) ?>",
                            <?php if (in_array($form->layout, array(3, 4))) { ?>
                                icon: '<?php echo $form->getIcon() ?>'
                            <?php } ?>
                        },
                    <?php } ?>
                    <?php if ($form->button_icon_type){ ?>
                        icon: '<?php echo $form->getButtonIcon() ?>',
                    <?php } ?>
                    success: "<?php echo ArContactUsTools::escJsString($form->getLangValue('successContent', $currentLang)) ?>",
                    error: "<?php echo ArContactUsTools::escJsString($form->getLangValue('failContent', $currentLang)) ?>",
                    action: '<?php echo admin_url('admin-ajax.php') ?>',
                    buttons: [
                        <?php foreach ($form->buttons as $button){ ?>
                            {
                                name: "<?php echo ArContactUsTools::escJsString($button->id) ?>",
                                label: "<?php echo ArContactUsTools::escJsString($button->getLangValue('label', $currentLang)) ?>",
                                type: "<?php echo $button->type == 'link'? 'a' : $button->type ?>",
                                <?php if (!empty($button->class_name)){?>
                                    class: '<?php echo ArContactUsTools::escJsString($button->class_name) ?>',
                                <?php } ?>
                                <?php if ($button->type == 'link'){?>
                                    href: '<?php echo ArContactUsTools::escJsString($button->url) ?>',
                                    target: '<?php echo $button->new_window? '_blank' : '' ?>'
                                <?php } ?>
                            },
                        <?php } ?>
                    ],
                    fields: {
                        formId: {
                            name: 'formId',
                            value: '<?php echo ArContactUsTools::escJsString($form->id) ?>',
                            type: 'hidden'
                        },
                        action: {
                            name: 'action',
                            value: 'arcontactus_request_<?php echo ArContactUsTools::escJsString($form->id) ?>',
                            type: 'hidden'
                        },
                        <?php foreach ($form->fields as $field){ ?>
                            <?php echo ArContactUsTools::escJsString($field->id) ?>: {
                                name: "<?php echo ArContactUsTools::escJsString($field->id) ?>",
                                enabled: true,
                                required: <?php echo $field->required? 'true' : 'false' ?>,
                                type: "<?php echo $field->type == 'select'? 'dropdown' : ArContactUsTools::escJsString($field->type) ?>",
                                label: "<?php echo $field->getLangValue('label', $currentLang) ?>",
                                placeholder: "<?php echo ArContactUsTools::escJsString($field->getLangValue('placeholder', $currentLang)) ?>",
                                <?php if ($field->values) { ?>
                                    values: <?php echo json_encode($field->getValues($currentLang)) ?>,
                                <?php } ?>
                                <?php if ($field->value) { ?>
                                    value: "<?php echo ArContactUsTools::escJsString($field->getValue($currentLang)) ?>",
                                <?php } ?>
                            },
                        <?php } ?>
                    }
                },
            <?php } ?>   
        }
    };
    <?php if (!$generalConfig->disable_init){?>
        <?php if ($generalConfig->delay_init){?>
            setTimeout(function(){
                contactUs.init(arcuOptions);
            }, <?php echo (int)$generalConfig->delay_init ?>);
        <?php } else { ?>
            contactUs.init(arcuOptions);
        <?php } ?>
    <?php } ?>
    <?php if ($liveChatsConfig->isTawkToIntegrated() && $tawkTo) {?>
        Tawk_API.onLoad = function(){
            if(!Tawk_API.isChatOngoing()){
                Tawk_API.hideWidget();
            }else{
                contactUs.hide();
                clearInterval(tawkToHideInterval);
                tawkToInterval = setInterval(function(){
                    checkTawkIsOpened();
                }, 100);
            }
        };
        Tawk_API.onChatMinimized = function(){
            Tawk_API.hideWidget();
            setTimeout(function(){
                Tawk_API.hideWidget();
            }, 100);
            contactUs.show();
        };
        Tawk_API.onChatEnded = function(){
            Tawk_API.hideWidget();
            setTimeout(function(){
                Tawk_API.hideWidget();
            }, 100);
            contactUs.show();
        };
        Tawk_API.onChatStarted = function(){
            contactUs.hide();
            Tawk_API.showWidget();
            Tawk_API.maximize();
        };
        window.addEventListener('tawkChatMessageAgent', (e) => {
            contactUs.hide();
            Tawk_API.showWidget();
            Tawk_API.maximize();
        });
        window.addEventListener('tawkChatMessageSystem', (e) => {
            console.log('tawkChatMessageSystem');
        });
        <?php if ($liveChatsConfig->tawk_to_userinfo && $user->ID) { ?>
            Tawk_API.visitor = {
                name : "<?php echo ArContactUsTools::escJsString($user->user_firstname) . ' ' . ArContactUsTools::escJsString($user->user_lastname) ?>",
                email : '<?php echo ArContactUsTools::escJsString($user->user_email) ?>'
            };
        <?php } ?>
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/<?php echo ArContactUsTools::escJsString($liveChatsConfig->tawk_to_site_id) ?>/<?php echo ArContactUsTools::escJsString($liveChatsConfig->tawk_to_widget) ?>';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    <?php } ?>
    <?php if ($liveChatsConfig->isFacebookChatIntegrated() && $facebook) {?>
        if (localStorage.getItem('__fb_chat_plugin') === null){
            localStorage.setItem('__fb_chat_plugin', '{"v":0,"chatState":1,"visibility":"hidden"}');
        }
        FB.Event.subscribe('customerchat.dialogHide', function(){
            document.getElementById('ar-fb-chat').classList.remove('active');
            contactUs.show();
            FB.CustomerChat.hide();
        });
    <?php } ?>
    <?php if ($liveChatsConfig->isLhcIntegrated() && $lhc){?>
        lh_inst.chatClosedCallback = function(){
            contactUs.show();
            clearInterval(LHCInterval);
        };
        lh_inst.chatOpenedCallback = function(){
            contactUs.hide();
            LHCInterval = setInterval(function(){
                checkLHCisOpened();
            }, 100);
        };
    <?php } ?>
    <?php if ($tidio){?>
        function onTidioChatApiReady(){
            window.tidioChatApi.hide();
        }
        function onTidioChatClose(){
            window.tidioChatApi.hide();
            contactUs.show();
        }
        if (window.tidioChatApi) {
            window.tidioChatApi.on("ready", onTidioChatApiReady);
            window.tidioChatApi.on("close", onTidioChatClose);
        }else{
            document.addEventListener("tidioChat-ready", onTidioChatApiReady);
            document.addEventListener("tidioChat-close", onTidioChatClose);
        }
    <?php } ?>
    <?php if ($generalConfig->ga_account && $generalConfig->ga_tracker){ ?>
        ga('create', '<?php echo ArContactUsTools::escJsString($generalConfig->ga_account) ?>', 'auto');
    <?php } ?>
    <?php if ($paldesk) {?>
        window.BeeBeeate.widget.closeChatWindow(function(){
            contactUs.show();
        }, function(error) {

        });
    <?php } ?>
});
<?php if ($liveChatsConfig->isCrispIntegrated() && $crisp) {?>
    window.$crisp=[];window.CRISP_WEBSITE_ID="<?php echo ArContactUsTools::escJsString($liveChatsConfig->crisp_site_id) ?>";(function(){
        d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);
    })();
    $crisp.push(["on", "session:loaded", function(){
        $crisp.push(["do", "chat:hide"]);
    }]);
    $crisp.push(["on", "chat:closed", function(){
        $crisp.push(["do", "chat:hide"]);
        contactUs.show();
    }]);
    $crisp.push(["on", "message:received", function(){
        $crisp.push(["do", "chat:show"]);
        contactUs.hide();
    }]);
<?php } ?>
<?php if ($liveChatsConfig->isIntercomIntegrated() && $intercom) {?>
    window.intercomSettings = {
        app_id: "<?php echo ArContactUsTools::escJsString($liveChatsConfig->intercom_app_id) ?>",
        alignment: 'right',     
        horizontal_padding: 20, 
        vertical_padding: 20
    };
    (function() {
        var w = window;
        var ic = w.Intercom;
        if (typeof ic === "function") {
            ic('reattach_activator');
            ic('update', intercomSettings);
        } else {
            var d = document;
            var i = function() {
                i.c(arguments)
            };
            i.q = [];
            i.c = function(args) {
                i.q.push(args)
            };
            w.Intercom = i;

            function l() {
                var s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://widget.intercom.io/widget/<?php echo ArContactUsTools::escJsString($liveChatsConfig->intercom_app_id) ?>';
                var x = d.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            }
            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();
    Intercom('onHide', function(){
        contactUs.show();
    });
<?php } ?>
<?php if ($vkChat) {?>
    var vkMessagesWidget = VK.Widgets.CommunityMessages("vk_community_messages", <?php echo ArContactUsTools::escJsString($liveChatsConfig->vk_page_id) ?>, {
        disableButtonTooltip: 1,
        welcomeScreen: 0,
        expanded: 0,
        buttonType: 'no_button',
        widgetPosition: '<?php echo ArContactUsTools::escJsString($buttonConfig->position) ?>'
    });
<?php } ?>
<?php if ($lcp) {?>
    function checkLCPIsOpened(){
        if (parseInt(document.getElementById('customer-chat-iframe').style.bottom) < -300){ 
            contactUs.show();
            document.getElementById('customer-chat-iframe').classList.remove('active');
            clearInterval(lcpWidgetInterval);
        }
    }
<?php } ?>
<?php if ($zalo) {?>
    function checkZaloIsOpened(){
        if (document.querySelector('#ar-zalo-chat-widget > div').offsetHeight < 100){ 
            document.getElementById('ar-zalo-chat-widget').classList.remove('active');
            contactUs.show();
            clearInterval(zaloWidgetInterval);
        }
    }
<?php } ?>
<?php if ($lhc){ ?>
    var LHCChatOptions = {};
    var LHCInterval = null;

    LHCChatOptions.opt = {
        widget_height: <?php echo (int)$liveChatsConfig->lhc_height ?>,
        widget_width: <?php echo (int)$liveChatsConfig->lhc_width ?>,
        popup_height: <?php echo (int)$liveChatsConfig->lhc_popup_width ?>,
        popup_width: <?php echo (int)$liveChatsConfig->lhc_popup_width ?>
    };
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        var refferer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
        var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
        po.src = '<?php echo ArContactUsTools::escJsString($liveChatsConfig->lhc_uri) ?>/chat/getstatus/(click)/internal/(ma)/br/(position)/bottom_right/(check_operator_messages)/true/(top)/350/(units)/pixels?r='+refferer+'&l='+location;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    function checkLHCisOpened(){
        if (lh_inst.isMinimized){ 
            contactUs.show();
            lh_inst.isMinimized = false;
            clearInterval(LHCInterval);
        }
    }
<?php } ?>
<?php if ($ss){?>
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '<?php echo ArContactUsTools::escJsString($liveChatsConfig->ss_key) ?>';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    <?php if ($liveChatsConfig->ss_userinfo and $user->ID){?>
        smartsupp('name', "<?php echo ArContactUsTools::escJsString($user->user_firstname) . ' ' . ArContactUsTools::escJsString($user->user_lastname) ?>");
        smartsupp('email', '<?php echo ArContactUsTools::escJsString($user->user_email) ?>');
        smartsupp('variables', {
            accountId: {
                label: 'User ID',
                value: <?php echo (int)$user->ID ?>
            }
        });
    <?php } ?>
    var ssInterval;

    function checkSSIsOpened(){
        if (document.getElementById('chat-application').offsetHeight < 300){ 
            smartsupp('chat:close');
            contactUs.show();
            clearInterval(ssInterval);
            document.getElementById('chat-application').classList.remove('active');
        }
    }
    smartsupp('on', 'message', function(model, message) {
        if (message.type == 'agent') {
            document.getElementById('chat-application').classList.add('active');
            smartsupp('chat:open');
            contactUs.hide();
            setTimeout(function(){
                ssInterval = setInterval(function(){
                    checkSSIsOpened();
                }, 100);
            }, 500);

        }
    });
<?php } ?>
<?php if ($lc){?>
    window.__lc = window.__lc || {};
    window.__lc.license = <?php echo ArContactUsTools::escJsString($liveChatsConfig->lc_key) ?>;
    (function() {
      var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
      lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
    })();
    var LC_API = LC_API || {};
    var livechat_chat_started = false;
    LC_API.on_before_load = function() {
        LC_API.hide_chat_window();
    };
    LC_API.on_after_load = function() {
        LC_API.hide_chat_window();
        <?php if ($liveChatsConfig->lc_userinfo && $user->ID){?>
            LC_API.set_visitor_name('<?php echo ArContactUsTools::escJsString($user->user_firstname) . ' ' . ArContactUsTools::escJsString($user->user_lastname) ?>');
            LC_API.set_visitor_email('<?php echo ArContactUsTools::escJsString($user->user_email) ?>');
        <?php } ?>
    };
    LC_API.on_chat_window_minimized = function(){
        LC_API.hide_chat_window();
        contactUs.show();
    };
    LC_API.on_message = function(data) {
        LC_API.open_chat_window();
        contactUs.hide();
    };
    LC_API.on_chat_started = function() {
        livechat_chat_started = true;
    };
<?php } ?>
<?php if ($liveZilla) {?>
    function checkLZIsOpened(){
        if (!document.getElementsById('lz_overlay_chat').is(':visible')){ 
            contactUs.show();
            document.getElementsById('#lz_overlay_wm').classList.remove('active');
            clearInterval(lzWidgetInterval);
        }
    }
<?php } ?>
<?php if ($lcp) { ?>
(function(d,t,u,s,e){e=d.getElementsByTagName(t)[0];s=d.createElement(t);s.src=u;s.async=1;e.parentNode.insertBefore(s,e);})(document,'script','<?php echo $liveChatsConfig->lcp_uri ?>');
<?php } ?>
<?php if ($jivosite){?>
    <?php if ($liveChatsConfig->jivosite_userinfo){?>
        function jivo_onLoadCallback(state) {
            jivo_api.setContactInfo({
                "name": "<?php echo ArContactUsTools::escJsString($user->user_firstname) . ' ' . ArContactUsTools::escJsString($user->user_lastname) ?>",
                "email": "<?php echo ArContactUsTools::escJsString($user->user_email) ?>"
            }); 
        }
    <?php } ?>
    function jivo_onChangeState(state) {
        if (state == 'chat' || state == 'offline' || state == 'introduce') {
            document.querySelector('#jivo-iframe-container + jdiv').classList.add('active');
            contactUs.hide();
        }
        if (state == 'call' || state == 'chat/call') {
            document.querySelector('#jivo-iframe-container + jdiv').classList.add('active');
            contactUs.hide();
        }
        if (state == 'label' || state == 'chat/min'){
            document.querySelector('#jivo-iframe-container + jdiv').classList.remove('active');
            contactUs.show();
        }
    } 
<?php } ?>
<?php if ($zoho) { ?>
    var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"<?php echo ArContactUsTools::escJsString($liveChatsConfig->zoho_id) ?>", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="<?php echo ArContactUsTools::escJsString($liveChatsConfig->zoho_host) ?>/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
    $zoho.salesiq.ready=function(){
        $zoho.salesiq.floatbutton.visible("hide");
        $zoho.salesiq.floatwindow.minimize(function(){
            contactUs.show();
        });
        $zoho.salesiq.floatwindow.close(function(){
            contactUs.show();
        });
    }
<?php } ?>
<?php if ($freshChat){ ?>
    function initFreshChat() {
        setTimeout(function(){
            window.fcWidget.on("widget:closed", function(resp) {
                contactUs.show();
            });
        }, 500);
        window.fcWidget.init({
            token: "<?php echo ArContactUsTools::escJsString($liveChatsConfig->fc_token) ?>",
            host: "<?php echo ArContactUsTools::escJsString($liveChatsConfig->fc_host) ?>"
        });
        <?php if ($liveChatsConfig->fc_userinfo && $user->ID){ ?>
            window.fcWidget.user.setProperties({
                firstName: "<?php echo ArContactUsTools::escJsString($user->user_firstname) ?>",
                lastName: "<?php echo ArContactUsTools::escJsString($user->user_lastname) ?>",
                email: "<?php echo ArContactUsTools::escJsString($user->user_email) ?>"
            });
        <?php } ?>
    }

    function initialize(i,t){var e;i.getElementById(t)?initFreshChat():((e=i.createElement("script")).id=t,e.async=!0,e.src="<?php echo ArContactUsTools::escJsString($liveChatsConfig->fc_host) ?>/js/widget.js",e.onload=initFreshChat,i.head.appendChild(e))}function initiateCall(){initialize(document,"freshchat-js-sdk")}window.addEventListener?window.addEventListener("load",initiateCall,!1):window.attachEvent("load",initiateCall,!1);
<?php } ?>
<?php if ($phplive) {?>
    <?php if ($liveChatsConfig->phplive_userinfo) {?>
        var phplive_v = new Object ;
        phplive_v["name"] = "<?php echo ArContactUsTools::escJsString($user->user_firstname) ?> <?php echo ArContactUsTools::escJsString($user->user_lastname) ?>" ;
        phplive_v["email"] = "<?php echo ArContactUsTools::escJsString($user->user_email) ?>" ;
    <?php } ?>
    (function() {
        var phplive_href = encodeURIComponent( location.href ) ;
        var phplive_e_1576807307 = document.createElement("script") ;
        phplive_e_1576807307.type = "text/javascript" ;
        phplive_e_1576807307.async = true ;
        phplive_e_1576807307.src = "<?php echo ArContactUsTools::escJsString($liveChatsConfig->phplive_src) ?>?v=1%7C1576807307%7C2%7C&r="+phplive_href;
        document.getElementById("phplive_btn_1576807307").appendChild( phplive_e_1576807307 ) ;
        if ( [].filter ) { document.getElementById("phplive_btn_1576807307").addEventListener( "click", function(){ phplive_launch_chat_1() } ) ; } else { document.getElementById("phplive_btn_1576807307").attachEvent( "onclick", function(){ phplive_launch_chat_1() } ) ; }
    })() ;
    function phplive_callback_minimize() {
        contactUs.show();
        phplive_embed_window_close(1);
    }
    function phplive_callback_close() {
        contactUs.show();
    }
<?php } ?>
<?php if ($paldesk) {?>
    <?php if ($liveChatsConfig->paldesk_userinfo) {?>
        custom_user_data = {
            externalId: "<?php echo ArContactUsTools::escJsString($user->ID) ?>",
            email: "<?php echo ArContactUsTools::escJsString($user->user_email) ?>",
            firstname: "<?php echo ArContactUsTools::escJsString($user->user_firstname) ?>",
            lastname: "<?php echo ArContactUsTools::escJsString($user->user_lastname) ?>"
        };
    <?php } ?>
    if("undefined"!==typeof requirejs){
        window.onload=function(e){requirejs(["https://paldesk.io/api/widget-client?apiKey=<?php echo ArContactUsTools::escJsString($liveChatsConfig->paldesk_key) ?>"],function(e){"undefined"!==typeof custom_user_data&&(beebeeate_config.user_data=custom_user_data),BeeBeeate.widget.new(beebeeate_config)})};
    }else{var s=document.createElement("script");s.async=!0,s.src="https://paldesk.io/api/widget-client?apiKey=<?php echo ArContactUsTools::escJsString($liveChatsConfig->paldesk_key) ?>",s.onload=function(){"undefined"!==typeof custom_user_data&&(beebeeate_config.user_data=custom_user_data),BeeBeeate.widget.new(beebeeate_config)};
        if(document.body){
            document.body.appendChild(s)
        }else if(document.head){
            document.head.appendChild(s)
        }
    }

    function checkPaldeskIsOpened() {
        if (document.getElementById('paldesk-widget-mainframe').offsetHeight < 100){ 
            contactUs.show();
            clearInterval(paldeskInterval);
        }
    }
<?php } ?>