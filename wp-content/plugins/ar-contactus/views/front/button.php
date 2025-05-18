<?php if ($apple) { ?>
    <script src="https://static.cdn-apple.com/businesschat/start-chat-button/2/index.js"></script>
<?php } ?>
<?php if ($generalConfig->ga_account && $generalConfig->ga_script){ ?>
    <script async src="<?php echo esc_url('https://www.googletagmanager.com/gtag/js?id=' . $generalConfig->ga_account) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_html($generalConfig->ga_account) ?>');
    </script>
<?php } ?>
<?php if ($phplive){ ?>
    <span style="color: #0000FF; text-decoration: underline; line-height: 0px !important; cursor: pointer; position: fixed; bottom: 0px; right: 20px; z-index: 20000000;" id="phplive_btn_1576807307"></span>
<?php } ?>
<div id="arcontactus"></div>
<?php foreach ($items as $item){?>
    <?php if ($item['type'] == 4){?>
        <div id="arcu-popup-content-<?php echo (int)$item['id'] ?>" class="arcu-popup-html">
            <?php echo $item['content'] ?>
        </div>
    <?php } ?>
<?php } ?>

<?php if ($popupConfig->maskedinput){?>
    <script src="<?php echo AR_CONTACTUS_PLUGIN_URL ?>res/js/maskedinput.min.js?version=<?php echo AR_CONTACTUS_VERSION ?>"></script>
<?php } ?>
<?php if ($menuConfig->menu_popup_style == 'sidebar'){?>
    <script src="<?php echo AR_CONTACTUS_PLUGIN_URL ?>res/js/snap.svg-min.js?version=<?php echo AR_CONTACTUS_VERSION ?>"></script>
<?php } ?>
<?php if ($vkChat){ ?>
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?157"></script>
    <div id="vk_community_messages"></div>
<?php } ?>
<?php if ($zalo) { ?>
    <div id="ar-zalo-chat-widget">
        <div class="zalo-chat-widget" data-oaid="<?php echo esc_html($liveChatsConfig->zalo_id) ?>" data-welcome-message="<?php echo esc_html($liveChatsConfig->zalo_welcome) ?>" data-autopopup="0" data-width="<?php echo (int)$liveChatsConfig->zalo_width ?>" data-height="<?php echo (int)$liveChatsConfig->zalo_height ?>"></div>
    </div>
    <script src="https://sp.zalo.me/plugins/sdk.js"></script>
<?php } ?>
<?php if ($tidio){ ?>
    <?php if ($liveChatsConfig->tidio_userinfo){ ?>
        <script>
            document.tidioIdentify = {
                email: '<?php echo esc_html($user->user_email) ?>',
                name: "<?php echo esc_html($user->user_firstname) . ' ' . esc_html($user->user_lastname) ?>"
            };
        </script>
    <?php } ?>
    <script src="//code.tidio.co/<?php echo esc_html($liveChatsConfig->tidio_key) ?>.js"></script>
<?php } ?>
<?php if ($jivosite){?>
    <script src="//code.jivosite.com/widget.js" data-jv-id="<?php echo esc_html($liveChatsConfig->jivosite_id) ?>" async></script>
    <style type="text/css">
        #jivo-iframe-container + jdiv{
            display: none;
        }
        #jivo-iframe-container + jdiv.active{
            display: block;
        }
    </style>
<?php } ?>
<script type="text/javascript" id="arcu-main-js">
    <?php echo $mainJs ?>
</script>
<?php if ($liveZilla){ ?>
    <script type="text/javascript" rel="livezilla" id="<?php echo ArContactUsTools::escJsString($liveChatsConfig->lz_id) ?>" src="<?php echo ArContactUsTools::escJsString($liveChatsConfig->lz_id) ?>"></script>
<?php } ?>
<?php if ($zopim) {?>
    <?php if ($liveChatsConfig->isZendeskChat()) {?>
        <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=<?php echo ArContactUsTools::escJsString($liveChatsConfig->zopim_id) ?>"> </script>
        <script type="text/javascript">
            zE('webWidget:on', 'chat:connected', function(){
                zE('webWidget', 'hide');
            });
            zE('webWidget:on', 'open', function(){
                contactUs.hide();
            });
            zE('webWidget:on', 'close', function(){
                zE('webWidget', 'hide');
                contactUs.show();
            });
            zE('webWidget:on', 'chat:unreadMessages', function(msgs){
                zE('webWidget', 'show');
                zE('webWidget', 'open');
            });
            <?php if ($liveChatsConfig->zopim_userinfo && $user->ID){ ?>
                zE('webWidget', 'identify', {
                    name: "<?php echo $user->user_firstname . ' ' . $user->user_lastname ?>",
                    email: '<?php echo $user->user_email ?>'
                });
            <?php } ?>
        </script>
    <?php }else { ?>
        <script type="text/javascript">
            window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
            _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
            $.src="https://v2.zopim.com/?<?php echo ArContactUsTools::escJsString($liveChatsConfig->zopim_id) ?>";z.t=+new Date;$.
            type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
            $zopim(function(){
                $zopim.livechat.hideAll();
                <?php if ($buttonConfig->position == 'left') {?>
                    $zopim.livechat.window.setPosition('bl');
                <?php }else { ?>
                    $zopim.livechat.window.setPosition('br');
                <?php } ?>
                $zopim.livechat.window.onHide(function(){
                    $zopim.livechat.hideAll();
                    contactUs.show();
                });
            });
        </script>
    <?php } ?>
<?php } ?>
<?php if ($liveChatsConfig->isFacebookChatIntegrated() && $facebook) {?>
    <div id="ar-fb-chat">
        <div id="fb-root"></div>
        <?php if ($liveChatsConfig->fb_init){ ?>
            <script>
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    <?php if ($wpml){ ?>
                        js.src = "//connect.facebook.net/<?php echo $liveChatsConfig->fb_lang[$currentLang]? ArContactUsTools::escJsString($liveChatsConfig->fb_lang[$currentLang]) : 'en_US' ?>/sdk/xfbml.customerchat.js#xfbml=1&version=v10.0&autoLogAppEvents=1";
                    <?php } else { ?>
                        js.src = "//connect.facebook.net/<?php echo $liveChatsConfig->fb_lang? ArContactUsTools::escJsString($liveChatsConfig->fb_lang) : 'en_US' ?>/sdk/xfbml.customerchat.js#xfbml=1&version=v10.0&autoLogAppEvents=1";
                    <?php } ?>
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk-chat'));
            </script>
        <?php } ?>
        <div class="fb-customerchat"
            attribution="setup_tool"
            page_id="<?php echo ArContactUsTools::escJsString($liveChatsConfig->fb_page_id) ?>"
            greeting_dialog_display="hide"
            <?php if ($liveChatsConfig->fb_color){ ?>
                theme_color="#<?php echo ArContactUsTools::escJsString($liveChatsConfig->fb_color) ?>"
            <?php } ?>
        ></div>
    </div>
<?php }