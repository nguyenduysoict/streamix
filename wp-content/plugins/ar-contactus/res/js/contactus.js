(function (root, factory) {
    if ( typeof define === 'function' && define.amd ) {
        define([], factory(root));
    } else if ( typeof exports === 'object' ) {
        module.exports = factory(root);
    } else {
        root.contactUs = factory(root);
    }
})(typeof global !== "undefined" ? global : this.window || this.global, function (root) {

    'use strict';

    var contactUs = {};
    var rootElement = null;
    var initialized = false;
    var supports = !!document.querySelector && !!root.addEventListener;
    var settings, eventTimeout;
    
    var popups = [];
    var x = 0;
    var y = 0;
    var _interval;
    var _timeout;
    var _animation = false;
    var _menuOpened = false;
    var _popupOpened = false;
    var _callbackOpened = false;
    var _formOpened = false;
    var countdown = null;
    var svgPath = null;
    var svgSteps = [];
    var svgPathOpen = null;
    var svgInitialPath = null;
    var svgStepsTotal = null;
    var isAnimating = false;

    // Default settings
    var defaults = {
        rootElementId: 'contactus',
        activated: false,
        pluginVersion: '2.4.1',
        wordpressPluginVersion: false,
        align: 'right',
        mode: 'regular',
        visible: true,
        countdown: 0,
        drag: false,
        online: null,
        buttonText: 'Contact us',
        buttonSize: 'large',
        buttonIconSize: 24,
        menuSize: 'normal',
        buttonIcon: '<svg width="20" height="20" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(-825 -308)"><g><path transform="translate(825 308)" fill="#FFFFFF" d="M 19 4L 17 4L 17 13L 4 13L 4 15C 4 15.55 4.45 16 5 16L 16 16L 20 20L 20 5C 20 4.45 19.55 4 19 4ZM 15 10L 15 1C 15 0.45 14.55 0 14 0L 1 0C 0.45 0 0 0.45 0 1L 0 15L 4 11L 14 11C 14.55 11 15 10.55 15 10Z"/></g></g></svg>',
        reCaptcha: false,
        reCaptchaAction: 'callbackRequest',
        reCaptchaKey: '',
        errorMessage: 'Connection error. Please try again.',
        showMenuHeader: false,
        menuHeaderText: 'How would you like to contact us?',
        menuSubheaderText: '',
        menuHeaderLayout: 'icon-center',
        layout: 'default',
        itemsHeader: 'Start chat with:',
        menuHeaderIcon: null,
        menuHeaderTextAlign: 'center',
        menuHeaderOnline: true,
        showHeaderCloseBtn: true,
        menuInAnimationClass: 'arcu-show',
        menuOutAnimationClass: '',
        headerCloseBtnBgColor: '#787878',
        headerCloseBtnColor: '#FFFFFF',
        items: [],
        itemsIconType: 'rounded',
        iconsAnimationSpeed: 800,
        iconsAnimationPause: 2000,
        promptPosition: 'side',
        style: null,
        itemsAnimation: null,
        popupAnimation: 'scale',
        forms: {},
        theme: '#000000',
        subMenuHeaderBackground: '#FFFFFF',
        subMenuHeaderColor: '#FFFFFF',
        closeIcon: '<svg width="12" height="13" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(-4087 108)"><g><path transform="translate(4087 -108)" fill="currentColor" d="M 14 1.41L 12.59 0L 7 5.59L 1.41 0L 0 1.41L 5.59 7L 0 12.59L 1.41 14L 7 8.41L 12.59 14L 14 12.59L 8.41 7L 14 1.41Z"></path></g></g></svg>',
        backIcon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M231.293 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L70.393 256 251.092 74.87c4.686-4.686 4.686-12.284 0-16.971L231.293 38.1c-4.686-4.686-12.284-4.686-16.971 0L4.908 247.515c-4.686 4.686-4.686 12.284 0 16.971L214.322 473.9c4.687 4.686 12.285 4.686 16.971-.001z" class=""></path></svg>',
        credits: true,
        creditsUrl: 'https://anychat.one?utm_source=widget',
        clickAway: true,
        backdrop: false,
        menuDirection: 'vertical',
        unreadCount: 0,
        buttonTitle: null,
        buttonDescription: null,
        buttonLabel: null,
        menuStyle: 'regular'
    };

    //
    // Methods
    //

    /**
     * A simple forEach() implementation for Arrays, Objects and NodeLists
     * @private
     * @param {Array|Object|NodeList} collection Collection of items to iterate
     * @param {Function} callback Callback function for each iteration
     * @param {Array|Object|NodeList} scope Object/NodeList/Array that forEach is iterating over (aka `this`)
     */
    var forEach = function (collection, callback, scope) {
        if (Object.prototype.toString.call(collection) === '[object Object]') {
            for (var prop in collection) {
                if (Object.prototype.hasOwnProperty.call(collection, prop)) {
                    callback.call(scope, collection[prop], prop, collection);
                }
            }
        } else {
            for (var i = 0, len = collection.length; i < len; i++) {
                callback.call(scope, collection[i], i, collection);
            }
        }
    };

    /**
     * Merge defaults with user options
     * @private
     * @param {Object} defaults Default settings
     * @param {Object} options User options
     * @returns {Object} Merged values of defaults and options
     */
    var extend = function ( defaults, options ) {
        var extended = {};
        forEach(defaults, function (value, prop) {
            extended[prop] = defaults[prop];
        });
        forEach(options, function (value, prop) {
            extended[prop] = options[prop];
        });
        return extended;
    };

    /**
     * Convert data-options attribute into an object of key/value pairs
     * @private
     * @param {String} options Link-specific options as a data attribute string
     * @returns {Object}
     */
    var getDataOptions = function ( options ) {
        return !options || !(typeof JSON === 'object' && typeof JSON.parse === 'function') ? {} : JSON.parse( options );
    };

    /**
     * Get the closest matching element up the DOM tree
     * @param {Element} elem Starting element
     * @param {String} selector Selector to match against (class, ID, or data attribute)
     * @return {Boolean|Element} Returns false if not match found
     */
    var getClosest = function (elem, selector) {
        var firstChar = selector.charAt(0);
        for ( ; elem && elem !== document; elem = elem.parentNode ) {
            if ( firstChar === '.' ) {
                if ( elem.classList.contains( selector.substr(1) ) ) {
                    return elem;
                }
            } else if ( firstChar === '#' ) {
                if ( elem.id === selector.substr(1) ) {
                    return elem;
                }
            } else if ( firstChar === '[' ) {
                if ( elem.hasAttribute( selector.substr(1, selector.length - 2) ) ) {
                    return elem;
                }
            }
        }
        return false;
    };
    
    var initMessengersBlock = function() {
        var $container = createElement('div', {
            classes: ['messangers-block', 'arcuAnimated']
        });
        var $menuListContainer = createElement('div', {
            classes: ['messangers-list-container']
        });
        if (settings.layout == 'personal') {
            var $itemsHeader = createElement('div', {
                classes: ['arcu-items-header']
            }, settings.itemsHeader);
            
            var $wellcomeMessages = createElement('div', {
                classes: ['arcu-wellcome']
            });
            
            $menuListContainer.append($wellcomeMessages);
            $menuListContainer.append($itemsHeader);
        }
        var $menuContainer = createElement('ul', {
            classes: ['messangers-list']
        });
        if (settings.itemsAnimation){
            $menuContainer.classList.add('arcu-'+settings.itemsAnimation);
        }
        if (settings.menuSize === 'large'){
            $container.classList.add('lg');
        }
        if (settings.menuSize === 'small'){
            $container.classList.add('sm');
        }
        appendMenuItems($menuContainer, settings.items);
        if (settings.showMenuHeader){
            var $header = createElement('div', {
                classes: ['arcu-menu-header', 'arcu-' + settings.menuHeaderLayout],
                style: (settings.theme? ('background-color:' + settings.theme) : null)
            });
            var $headerContent = createElement('div', {
                classes: ['arcu-menu-header-content', 'arcu-text-' + settings.menuHeaderTextAlign]
            }, settings.menuHeaderText);
            
            if (settings.menuHeaderIcon) {
                var $headerIcon = createElement('div', {
                    classes: ['arcu-header-icon']
                });
                if (settings.menuHeaderIcon.match(/^https?:\/\//)){
                    $headerIcon.style.cssText = 'background-image: url(' + settings.menuHeaderIcon + ')';
                    $headerIcon.classList.add('arcu-bg-image');
                } else {
                    $headerIcon.append(DOMElementFromHTML(settings.menuHeaderIcon));
                }
                if (settings.menuHeaderOnline !== null) {
                    var $headerOnlineBadge = createElement('div', {
                        classes: ['arcu-online-badge', (settings.menuHeaderOnline? 'online' : 'offline')],
                        style: 'border-color: ' + settings.theme
                    });
                    $headerIcon.append($headerOnlineBadge);
                }
                $header.append($headerIcon);
            }
            $header.append($headerContent);
            if (settings.menuSubheaderText) {
                var $subheaderContent = createElement('div', {
                    classes: ['arcu-menu-subheader', 'arcu-text-' + settings.menuHeaderTextAlign]
                }, settings.menuSubheaderText);
                $header.append($subheaderContent);
            }
            if (settings.showHeaderCloseBtn){
                var $closeBtn = createElement('div', {
                    classes: ['arcu-header-close'],
                    style: 'color:' + settings.headerCloseBtnColor + '; background:' + settings.headerCloseBtnBgColor
                });
                
                $closeBtn.append(DOMElementFromHTML(settings.closeIcon));
                $header.append($closeBtn);
            }
            $container.append($header);
            $container.classList.add('has-header');
        }
        if (settings.itemsIconType == 'rounded'){
            $menuContainer.classList.add('rounded-items');
        }else{
            $menuContainer.classList.add('not-rounded-items');
        }
        $menuListContainer.append($menuContainer);
        $container.append($menuListContainer);
        if (settings.style == 'elastic') {
            var $svg = DOMElementFromHTML('<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none"><path d="M-1,0h101c0,0-97.833,153.603-97.833,396.167C2.167,627.579,100,800,100,800H-1V0z"/></svg>');
            var $svgContainer = createElement('div', {
                classes: ['arcu-morph-shape'],
                id: 'arcu-morph-shape',
                'data-morph-open': 'M-1,0h101c0,0,0-1,0,395c0,404,0,405,0,405H-1V0z'
            });
            $svgContainer.append($svg);
            $container.append($svgContainer);
        }else if (settings.style == 'bubble') {
            var $svg = DOMElementFromHTML('<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none"><path d="M-7.312,0H0c0,0,0,113.839,0,400c0,264.506,0,400,0,400h-7.312V0z"></path><defs></defs></svg>');
            var $svgContainer = createElement('div', {
                classes: ['arcu-morph-shape'],
                id: 'arcu-morph-shape',
                'data-morph-open': 'M-7.312,0H15c0,0,66,113.339,66,399.5C81,664.006,15,800,15,800H-7.312V0z;M-7.312,0H100c0,0,0,113.839,0,400c0,264.506,0,400,0,400H-7.312V0z'
            });
            $svgContainer.append($svg);
            $container.append($svgContainer);
        }
        if (settings.credits) {
            var $credits = createElement('div', {
                classes: ['arcu-creds']
            });
            $credits.innerHTML = 'powered by <a href="' + settings.creditsUrl + '" target="_blank">anychat.one</a>';
            $menuListContainer.append($credits);
        }
        rootElement.append($container);
    };
    
    var initPopups = function() {
        var $container = createElement('div', {
            classes: ['arcu-popups-block', 'arcuAnimated']
        });
        var $popupListContainer = createElement('div', {
            classes: ['arcu-popups-list-container']
        });
        for (var i in popups){
            var popup = popups[i];
            if (typeof popup === 'object') {
                var $popup = createElement('div', {
                    classes: ['arcu-popup'],
                    id: 'arcu-popup-' + popup.id
                });
                var $header = createElement('div', {
                    classes: ['arcu-popup-header'],
                    style: (settings.theme? ('background-color:' + settings.theme) : null)
                });
                var $close = createElement('div', {
                    classes: ['arcu-popup-close'],
                    style: (settings.theme? ('background-color:' + settings.theme) : null)
                });
                var $back = createElement('div', {
                    classes: ['arcu-popup-back'],
                    style: (settings.theme? ('background-color:' + settings.theme) : null)
                });
                $close.append(DOMElementFromHTML(settings.closeIcon));

                $back.append(DOMElementFromHTML(settings.backIcon));

                $header.innerHTML = popup.title;
                $header.append($close);
                $header.append($back);
                var $content = createElement('div', {
                    classes: ['arcu-popup-content']
                });
                $content.innerHTML = popup.popupContent;

                $popup.append($header);
                $popup.append($content);
                $popupListContainer.append($popup);
            }
        };
        $container.append($popupListContainer);
        rootElement.append($container);
    };
    
    var initMessageButton = function() {
        var $container = createElement('div', {
            classes: ['arcu-message-button'],
            style: backgroundStyle()
        });
        if (settings.buttonSize === 'large'){
            rootElement.classList.add('lg');
        }
        if (settings.buttonSize === 'huge'){
            rootElement.classList.add('hg');
        }
        if (settings.buttonSize === 'medium'){
            rootElement.classList.add('md');
        }
        if (settings.buttonSize === 'small'){
            rootElement.classList.add('sm');
        }
        if (settings.online !== null) {
            var onlineBadge = createElement('div', {
                classes: ['arcu-online-badge', settings.online === true? 'online' : 'offline']
            });
            $container.append(onlineBadge);
        }
        var unreadCounter = createElement('div', {
            classes: ['arcu-unread-badge']
        });
        $container.append(unreadCounter);
        var $static = createElement('div', {
            classes: ['static']
        });
        var $staticContainer = createElement('div', {
            classes: ['static-container']
        });
        $static.append($staticContainer);
        var $staticContent = createElement('div', {
            classes: ['img-' + settings.buttonIconSize]
        });
        $staticContent.append(DOMElementFromHTML(settings.buttonIcon));
        if (settings.buttonText !== false){
            $staticContent.append(DOMElementFromHTML('<p>' + settings.buttonText + '</p>'));
        }else{
            $container.classList.add('no-text');
        }
        $staticContainer.append($staticContent);
        
        var $icons = createElement('div', {
            classes: ['icons', 'arcu-hide']
        });
        
        var $iconsLine = createElement('div', {
            classes: ['icons-line']
        });
        
        for (var i in settings.items){
            var item = settings.items[i];
            if ((typeof item === 'object') && item.includeIconToSlider) {
                var $icon = createElement('span', {
                    style: colorStyle()
                });
                $icon.append(DOMElementFromHTML(item.icon));
                $iconsLine.append($icon);
            }
        };
        
        $icons.append($iconsLine);
        
        
        var $close = createElement('div', {
            classes: ['arcu-close']
        });
        
        $close.append(DOMElementFromHTML(settings.closeIcon));
        
        var $pulsation = createElement('div', {
            classes: ['pulsation'],
            style: backgroundStyle()
        });
        
        var $pulsation2 = createElement('div', {
            classes: ['pulsation'],
            style: backgroundStyle()
        });
        
        var $iconContainer = createElement('div', {
            classes: ['arcu-button-icon'],
        });
        
        
        $iconContainer.append($static);
        $iconContainer.append($icons);
        $iconContainer.append($close);
        $container.append($iconContainer);
        $container.append($pulsation);
        $container.append($pulsation2);
        
        if (!isEmptyValue(settings.buttonTitle) || !isEmptyValue(settings.buttonDescription) || !isEmptyValue(settings.buttonLabel)){
            var $content = createElement('div', {
                classes: ['arcu-button-content']
            });
            if (!isEmptyValue(settings.buttonTitle)) {
                var $buttonTitle = createElement('div', {
                    classes: ['arcu-button-title']
                });
                $buttonTitle.append(DOMElementFromHTML(settings.buttonTitle));
                $content.append($buttonTitle);
            }
            if (!isEmptyValue(settings.buttonDescription)) {
                var $buttonDescr = createElement('div', {
                    classes: ['arcu-button-descr']
                });
                $buttonDescr.append(DOMElementFromHTML(settings.buttonDescription));
                $content.append($buttonDescr);
            }
            if (!isEmptyValue(settings.buttonLabel)) {
                var $buttonLabel = createElement('div', {
                    classes: ['arcu-button-label']
                });
                $buttonLabel.append(DOMElementFromHTML(settings.buttonLabel));
                $content.append($buttonLabel);
            }

            $container.append($content);
        }
        
        rootElement.append($container);
        setUnreadCount(settings.unreadCount);
    };
    
    var appendMenuItems = function($container, items) {
        for(var i in items) {
            var item = items[i];
            item.id = item.id ? item.id : 'arcu-menu-item-' + i;
            var $li = createElement('li', {});
            insertMenuItem($container, $li, item);
            $container.append($li);
        }
    };
    
    var updateMenuItem = (id, item) => {
        if (rootElement.querySelector('#' + id)) {
            item.id = id;
            var $li = rootElement.querySelector('#' + id).parentElement;
            $li.innerHTML = '';
            var $container = rootElement.querySelector('messangers-list');
            insertMenuItem($container, $li, item);
        }
    };
    
    var setMenuItemDisabled = (id, disabled) => {
        if (rootElement.querySelector('#' + id)) {
            const item = rootElement.querySelector('#' + id);
            if (disabled === true) {
                rootElement.querySelector('#' + id).classList.add('arcu-disabled');
            } else {
                rootElement.querySelector('#' + id).classList.remove('arcu-disabled');
            }
        }
    };
    
    var setMenuItemLabels = (id, labels) => {
        if (rootElement.querySelector('#' + id)) {
            const item = rootElement.querySelector('#' + id);
            const label = item.querySelector('.arcu-item-label');
            if (label.querySelector('.arcu-item-labels')) {
                label.querySelector('.arcu-item-labels').remove();
            }
            if (labels && labels.length > 0) {
                const $labels = createElement('div', {
                    classes: ['arcu-item-labels']
                });
                labels.map((lbl) => {
                    var $lbl = createElement('span', {
                        classes: ['arcu-item-lbl'],
                        style: 'background: ' + lbl.background + '; color: ' + lbl.color
                    }, lbl.title);
                    $labels.append($lbl);
                });
                label.append($labels);
            }
        }
    };
    
    var updateMenuItemStatus = (id, status) => {
        if (rootElement.querySelector('#' + id)) {
            const item = rootElement.querySelector('#' + id);
            if (status === null) {
                if (item.querySelector('.arcu-online-badge')) {
                    item.querySelector('.arcu-online-badge').remove();
                }
            } else if (status === false || status === true) {
                if (item.querySelector('.arcu-online-badge')) {
                    item.querySelector('.arcu-online-badge').classList.remove('online');
                    item.querySelector('.arcu-online-badge').classList.remove('offline');
                    item.querySelector('.arcu-online-badge').classList.add(status === true ? 'online' : 'offline');
                } else {
                    const $badge = createElement('div', {
                        classes: ['arcu-online-badge', status === true ? 'online' : 'offline']
                    });
                    item.querySelector('.arcu-item-icon').append($badge);
                }
            }
        }
    };
    
    var removeMenuItem = (id) => {
        if (rootElement.querySelector('#' + id)) {
            rootElement.querySelector('#' + id).parentElement.remove();
        }
    };
    
    var insertMenuItem = ($container, $li, item) => {
        if (typeof item === 'object') {
            if(item.href == '_popup') {
                popups.push(item);
                var $item = createElement('div', {
                    classes: ['messanger', 'arcu-popup-link', (item.class? item.class : '')],
                    id: (item.id? item.id : null),
                    title: item.title,
                    'data-id': (item.id? item.id : null),
                });
            }else{
                var $item = createElement('a', {
                    classes: ['messanger', (item.class? item.class : ''), (item.addons? 'has-addon' : '')],
                    id: (item.id? item.id : null),
                    rel: 'nofollow noopener',
                    href: item.href,
                    title: item.title,
                    target: (item.target? item.target : '_blank')
                });
            }
            if (item.disabled && item.disabled === true) {
                $item.classList.add('arcu-disabled');
            }
            if (item.onClick){
                $item.addEventListener('click', item.onClick);
            }
            if (item.addons){
                for(var ii in item.addons){
                    var addon = item.addons[ii];
                    var $addon = createElement('a', {
                        href: addon.href,
                        title: (addon.title? addon.title : null),
                        target: (addon.target? addon.target : '_blank'),
                        classes: [(addon.class? addon.class : 'arcu-addon')],
                        style: (addon.color? ('color:' + addon.color) : null) + '; background-color: transparent'
                    });
                    if (addon.icon) {
                        if (addon.icon.indexOf('<') === 0){
                            $addon.append(DOMElementFromHTML(addon.icon));
                        }else if(addon.icon.indexOf('<') === -1){
                            var $icon = createElement('img', {
                                src: addon.icon
                            });
                            $addon.append($icon);
                        }
                    }
                    if (addon.onClick){
                        $addon.addEventListener('click', addon.onClick);
                    }
                    $item.append($addon);
                };
            }
            if (settings.itemsIconType == 'rounded'){
                if (item.noContainer){
                    var $icon = createElement('span', {
                        style: ((item.color)? ('color:' + item.color + '; fill: ' + item.color) : null),
                        classes: ['no-container', 'arcu-item-icon']
                    });
                }else{
                    var $icon = createElement('span', {
                        style: ((item.color && !item.noContainer)? ('background-color:' + item.color) : null),
                        classes: ['arcu-item-icon']
                    });
                }
            }else{
                if (item.noContainer){
                    var $icon = createElement('span', {
                        style: ((item.color)? ('color:' + item.color + '; fill: ' + item.color) : null),
                        classes: ['no-container', 'arcu-item-icon']
                    });
                }else{
                    var $icon = createElement('span', {
                        style: ((item.color && !item.noContainer)? ('color:' + item.color) : null) + '; background-color: transparent',
                        classes: ['arcu-item-icon']
                    });
                }
            }
            if (typeof item.online !== 'undefined' && item.online !== null) {
                var $onlineBadge = createElement('div', {
                    classes: ['arcu-online-badge', (item.online === true? 'online' : 'offline')]
                });
                $icon.append($onlineBadge);
            }
            $icon.append(DOMElementFromHTML(item.icon));
            $item.append($icon);
            var $label = createElement('div', {
                classes: ['arcu-item-label']
            });
            var $title = createElement('div', {
                classes: ['arcu-item-title']
            }, item.title);
            $label.append($title);
            if (typeof item.subTitle != 'undefined' && item.subTitle){
                var $subTitle = createElement('div', {
                    classes: ['arcu-item-subtitle']
                }, item.subTitle);
                $label.append($subTitle);
            }
            if (item.labels && item.labels.length > 0) {
                var $labels = createElement('div', {
                    classes: ['arcu-item-labels']
                });
                item.labels.map((lbl) => {
                    var $lbl = createElement('span', {
                        classes: ['arcu-item-lbl'],
                        style: 'background: ' + lbl.background + '; color: ' + lbl.color
                    }, lbl.title);
                    $labels.append($lbl);
                });
                $label.append($labels);
            }
            $item.append($label);
            $li.append($item);
            if (item.items) {
                var itemId = item.id;
                var $subMenuHeader = createElement('div', {
                    classes: ['arcu-submenu-header'],
                    style: 'background-color:' + settings.subMenuHeaderBackground + '; color: ' + item.subMenuHeaderIconColor
                });
                var $subMenuTitle = createElement('div', {
                    classes: ['arcu-submenu-title', 'arcu-text-' + item.subMenuHeaderTextAlign],
                    style: 'color:' + settings.subMenuHeaderColor
                });
                if (item.subMenuHeader) {
                    $subMenuTitle.innerHTML = item.subMenuHeader;
                } else {
                    $subMenuTitle.innerHTML = item.title;
                }
                var $subMenuBack = createElement('div', {
                    classes: ['arcu-submenu-back'],
                    style: 'color:' + settings.subMenuHeaderColor + '; fill: ' + settings.subMenuHeaderColor,
                    'data-erl': itemId
                }, settings.backIcon);

                $subMenuBack.addEventListener('click', function() {
                    hideSubmenu({id: '#' + itemId});
                });

                $subMenuHeader.append($subMenuBack);
                if (item.subMenuHeaderIcon) {
                    $subMenuHeader.append(DOMElementFromHTML(item.subMenuHeaderIcon));
                }
                $subMenuHeader.append($subMenuTitle);

                var $div = createElement('div', {
                    classes: ['arcu-submenu-container']
                });
                var $ul = createElement('ul', {
                    classes: ['arcu-submenu']
                });
                $div.append($subMenuHeader);
                $div.append($ul);
                appendMenuItems($ul, item.items);

                $li.append($div);
            }
        }
    };
    
    var getParents = function (elem, selector) {

            // Element.matches() polyfill
            if (!Element.prototype.matches) {
                    Element.prototype.matches =
                            Element.prototype.matchesSelector ||
                            Element.prototype.mozMatchesSelector ||
                            Element.prototype.msMatchesSelector ||
                            Element.prototype.oMatchesSelector ||
                            Element.prototype.webkitMatchesSelector ||
                            function(s) {
                                    var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                                            i = matches.length;
                                    while (--i >= 0 && matches.item(i) !== this) {}
                                    return i > -1;
                            };
            }

            // Set up a parent array
            var parents = [];

            // Push each parent element to the array
            for ( ; elem && elem !== document; elem = elem.parentNode ) {
                    if (selector) {
                            if (elem.matches(selector)) {
                                    parents.push(elem);
                            }
                            continue;
                    }
                    parents.push(elem);
            }

            // Return our parent array
            return parents;

    };


    // !ToDO
    var hideSubmenu = function(data){
        rootElement.querySelector('.arcu-submenu-header').classList.add('active');
        var $el = rootElement.querySelector(data.id);
        $el.parentElement.classList.remove('active');
        $el.parentElement.querySelector('.arcu-submenu-container').classList.remove('active');
        $el.parentElement.querySelector('.arcu-submenu-header').classList.add('active');
        rootElement.querySelectorAll('.arcu-submenu-header').forEach(function(sh){
            sh.classList.remove('active');
        });
        rootElement.querySelectorAll('.arcu-submenu').forEach(function(sm){
            sm.classList.remove('active');
        });
        if (!rootElement.querySelector('.arcu-submenu-container.active')) {
            rootElement.querySelector('.messangers-list').classList.remove('arcu-submenu-active');
        } else {
            rootElement.querySelector('.arcu-submenu-container.active > .arcu-submenu-header').classList.add('active');
            rootElement.querySelector('.arcu-submenu-container.active > .arcu-submenu').classList.add('active');
        }
    };

    
    var showSubmenu = function(data){
        rootElement.querySelectorAll('.arcu-submenu-container').forEach(function(smc){
            smc.classList.remove('active');
        });
        rootElement.querySelectorAll('.arcu-submenu-container .arcu-submenu').forEach(function(sm){
            sm.classList.remove('active');
        });
        
        rootElement.querySelectorAll('.messangers-list li').forEach(function(li){
            li.classList.remove('active');
        });
        
        rootElement.querySelector('.messangers-list').classList.add('arcu-submenu-active');
        rootElement.querySelectorAll('.arcu-submenu-header').forEach(function(sh){
            sh.classList.remove('active');
        });
        
        var $el = rootElement.querySelector(data.id);
        $el.parentElement.querySelector('.arcu-submenu-container').classList.add('active');
        $el.parentElement.querySelector('.arcu-submenu-container').classList.add('animated');
        
        $el.parentElement.querySelector('.arcu-submenu-container > .arcu-submenu').classList.add('active');
        
        setTimeout(function(){
            $el.parentElement.querySelector('.arcu-submenu-container').classList.remove('animated');
        }, 300);
        
        var parents = getParents($el, '.arcu-submenu-container');
        
        if (parents) {
            for (var i in parents){
                parents[i].classList.add('active');
            }
        }
        
        var parents = getParents($el.parentElement, 'li');
        
        if (parents) {
            for (var i in parents){
                parents[i].classList.add('active');
            }
        }
        $el.parentElement.classList.add('active');
        $el.parentElement.querySelector('.arcu-submenu-container > .arcu-submenu-header').classList.add('active');
    };
    
    var showForm = function(id) {
        if (!rootElement.querySelector('#arcu-form-' + id)) {
            console.error('Form not found: ' + id);
            return false;
        }
        _formOpened = true;
        stopAnimation(false);
        rootElement.classList.add('open');
        rootElement.querySelector('.arcu-forms-container').classList.add('active');
        if (rootElement.querySelector('.arcu-form-container.active')) {
            rootElement.querySelector('.arcu-form-container.active').classList.remove('active');
        }
        rootElement.querySelector('#arcu-form-' + id).classList.add('active');
        if (rootElement.querySelector('#form-icon-' + id)) {
            rootElement.querySelector('#form-icon-' + id).classList.add('active');
            rootElement.querySelector('.arcu-message-button .static').classList.add('arcu-hide');
        }
        if (settings.visible === false) {
            contactUs.show();
        }
        var e = new CustomEvent('arcontactus.showForm', {
            detail: {
                id: id
            }
        });
        rootElement.dispatchEvent(e);
    };
    
    var hideForm = function() {
        rootElement.querySelector('.arcu-forms-container').classList.remove('active');
        if (rootElement.querySelectorAll('.form-icon')) {
            rootElement.querySelectorAll('.form-icon').forEach(function(fi){
                fi.classList.remove('active');
            });
        }
        rootElement.querySelector('.arcu-message-button .static').classList.remove('arcu-hide');
        _formOpened = false;
        setTimeout(function(){
            if (!_menuOpened){
                rootElement.classList.remove('open');
            }
            if (rootElement.querySelector('.arcu-form-success.active')) {
                rootElement.querySelector('.arcu-form-success.active').classList.remove('active');
            }
            if (rootElement.querySelector('.arcu-form-error.active')) {
                rootElement.querySelector('.arcu-form-error.active').classList.remove('active');
            }
            startAnimation();
        }, 150);
        if (settings.visible === false) {
            contactUs.hide();
        }
        var e = new Event('arcontactus.hideForm');
        rootElement.dispatchEvent(e);
    };
    
    var isEmptyValue = (v) => {
        return v === null || v === false || v === '' || v === '0' || v === 0;
    };
    
    var DOMElementFromHTML = function(htmlString) {
        if (typeof htmlString === 'string') {
            var template = document.createElement('template');
            htmlString = htmlString.trim();
            template.innerHTML = htmlString;
            return template.content.firstChild;
        }
    };
    
    var backgroundStyle = function(color) {
        if (typeof(color) !== 'undefined') {
            return 'background-color: ' + color;
        }
        return 'background-color: ' + settings.theme;
    };
    
    var colorStyle = function(color) {
        if (typeof(color) !== 'undefined') {
            return 'color: ' + color;
        }
        return 'color: ' + settings.theme;
    };
    
    var createElement = function(tag, options, content) {
        var el = document.createElement(tag);
        if (options) {
            if (options.classes) {
                if (typeof(options.classes) === 'object') {
                    for(var i in options.classes) {
                        if (options.classes[i]) {
                            if (typeof options.classes[i] === 'string') {
                                el.classList.add(options.classes[i]);
                            }
                        }
                    }
                }
            }
            for (var i in options){
                if (i !== 'classes' && options[i] && (typeof options[i] === 'string')) {
                    el.setAttribute(i, options[i]);
                }
            }
        }
        if (typeof(content) !== 'undefined') {
            el.innerHTML = content;
        }
        return el;
    };
    
    var initForms = function() {
        var $container = createElement('div', {
            classes: ['arcu-forms-container']
        });
        var $close = createElement('div', {
            classes: ['arcu-form-close'],
            style: 'background-color:' + settings.theme + '; color: #FFFFFF'
        }, settings.closeIcon);
        $container.append($close);
        for (var i in settings.forms){
            var form = settings.forms[i];
            if (typeof form === 'object') {
                if (form.icon) {
                    var $formIcon = createElement('div', {
                        id: 'form-icon-' + i,
                        classes: ['form-icon']
                    });
                    $formIcon.append(DOMElementFromHTML(form.icon));
                    var $button = rootElement.querySelector('.arcu-button-icon');
                    if ($button) {
                        $button.append($formIcon);
                    }
                }

                var $formContainer = createElement('div', {
                    classes: ['arcu-form-container'],
                    id: 'arcu-form-' + i
                });
                if (typeof form.action !== 'undefined'){
                    var $form = createElement('form', {
                        action: form.action,
                        method: 'POST',
                        classese: ['arcu-form'],
                        'data-id': i
                    });
                } else {
                    var $form = createElement('div', {
                        classes: ['arcu-form']
                    });
                }
                if (typeof form.header == 'string') {
                    var $header = createElement('div', {
                        classes: ['arcu-form-header'],
                        style: backgroundStyle()
                    }, form.header);
                    $form.append($header);
                }else if (typeof form.header == 'object'){
                    var $header = createElement('div', {
                        classes: ['arcu-form-header', form.header.layout],
                        style: backgroundStyle()
                    });
                    var $headerContent = createElement('div', {
                        classes: ['arcu-form-header-content']
                    }, form.header.content);
                    var $headerIcon = createElement('div', {
                        classes: ['arcu-form-header-icon']
                    }, form.header.icon);
                    $header.append($headerIcon);
                    $header.append($headerContent);
                    $form.append($header);
                }
                initFormFields(form, $form);
                initFormButtons(form, $form);

                $formContainer.append($form);
                if (typeof form.success == 'string') {
                    var $formSuccess = createElement('div', {
                        classes: ['arcu-form-success']
                    });
                    var $formSuccessContent = createElement('div', {}, form.success);
                    $formSuccess.append($formSuccessContent);
                    $formContainer.append($formSuccess);
                }
                if (typeof form.error == 'string') {
                    var $formError = createElement('div', {
                        classes: ['arcu-form-error']
                    });
                    var $formErrorContent = createElement('div', {}, form.error);
                    $formError.append($formErrorContent);
                    $formContainer.append($formError);
                }
                $container.append($formContainer);
            }
        };
        
        rootElement.append($container);
    };
    
    var initFormFields = function(form, $form) {
        for (var i in form.fields){
            var field = form.fields[i];
            if (typeof field === 'object') {
                var $inputContainer = createElement('div', {
                    classes: ['arcu-form-group', 'arcu-form-group-type-' + field.type, 'arcu-form-group-' + field.name, (field.required? 'arcu-form-group-required' : '')],
                });
                var input = 'input';
                switch(field.type){
                    case 'textarea':
                        input = 'textarea';
                        break;
                    case 'dropdown':
                        input = 'select';
                        break;
                }
                if (field.label){
                    var $inputLabel = createElement('div', {
                        classes: ['arcu-form-label']
                    });
                    var $label = createElement('label', {
                        for: 'arcu-field-' + form.id + '-' + field.name
                    }, field.label);
                    $inputLabel.append($label);
                    $inputContainer.append($inputLabel);
                }
                var $input = createElement(input, {
                    name: field.name,
                    classes: ['arcu-form-field', 'arcu-field-' + field.name],
                    required: field.required,
                    type: field.type == 'dropdown'? null : field.type,
                    id: 'arcu-field-' + form.id + '-' + field.name,
                    value: field.value? field.value : '',
                });
                if (field.type == 'textarea' && field.value) {
                    $input.innerHTML = field.value;
                }
                if (field.placeholder){
                    $input.setAttribute('placeholder', field.placeholder);
                }
                if (typeof field.maxlength != 'undefined') {
                    $input.setAttribute('maxlength', field.maxlength);
                }
                if (field.type == 'dropdown'){
                    for (var ii in field.values){
                        var val = field.values[ii];
                        var lbl = field.values[ii];
                        if (typeof field.values[ii] == 'object'){
                            var val = field.values[ii].value;
                            var lbl = field.values[ii].label;
                        }
                        var $option = createElement('option', {
                            value: val
                        }, lbl);
                        $input.append($option);
                    };
                }
                $inputContainer.append($input);
                $inputContainer.append(createElement('div', {
                    classes: ['arcu-form-field-errors']
                }));
                $form.append($inputContainer);
            }
        };
    };
    
    var initFormButtons = function(form, $form) {
        for (var i in form.buttons){
            var button = form.buttons[i];
            if (typeof button === 'object') {
                var $buttonContainer = createElement('div', {
                    classes: ['arcu-form-group', 'arcu-form-button'],
                });
                var buttonClass = '';
                if (typeof button.class != 'undefined') {
                    buttonClass = button.class;
                }
                if (['button', 'submit'].indexOf(button.type) !== -1){
                    var $button = createElement('button', {
                        id: 'arcu-button-' + button.id,
                        classes: ['arcu-button', buttonClass],
                        type: button.type,
                        style: backgroundStyle(button.background) + ';' + (button.color? colorStyle(button.color) : '')
                    });
                } else if(button.type == 'a') {
                    var $button = createElement('a', {
                        id: 'arcu-button-' + button.id,
                        classes: ['arcu-button', buttonClass],
                        href: button.href,
                        type: button.type,
                        style: backgroundStyle(button.background) + ';' + (button.color? colorStyle(button.color) : '')
                    });
                }
                if (button.onClick) {
                    $button.addEventListener('click', button.onClick);
                }
                $button.innerHTML = button.label;

                $buttonContainer.append($button);

                $form.append($buttonContainer);
            }
        };
    };
    
    var initPrompt = function() {
        var $container = createElement('div', {
            classes: ['arcu-prompt', 'arcu-prompt-' + settings.promptPosition]
        });
        var $close = createElement('div', {
            classes: ['arcu-prompt-close'],
            style: backgroundStyle() + '; color: #FFFFFF'
        });
        $close.append(DOMElementFromHTML(settings.closeIcon));
        
        var $inner = createElement('div', {
            classes: ['arcu-prompt-inner'],
        });
        
        $container.append($close);
        $container.append($inner);
        
        rootElement.append($container);
    };
    
    const documentClickHandler = () => {
        closeMenu();
        closePopup();
    };
    
    var initEvents = function() {
        rootElement.querySelector('.arcu-message-button').addEventListener('click', function(e){
            if (settings.mode == 'regular'){
                if (!_menuOpened && !_popupOpened && !_callbackOpened && !_formOpened) {
                    openMenu();
                }else{
                    if (_menuOpened){
                        closeMenu();
                    }
                    if (_popupOpened){
                        closePopup();
                    }
                }
            }else if(settings.mode == 'single'){
                var $a = rootElement.querySelector('.messangers-list li:first-child a');
                if ($a.getAttribute('href')) {
                    // do nothing
                } else {
                    $a.click();
                }
            }else{
                showForm('callback');
            }
            e.preventDefault();
        });
        if (settings.clickAway) {
            document.addEventListener('click', documentClickHandler);
        }
        rootElement.addEventListener('click', function(e) {
            e.stopPropagation();
            if (e.target.classList.contains('arcu-popup-link') || e.target.closest('.arcu-popup-link')) {
                var target = e.target.classList.contains('arcu-popup-link')? e.target : e.target.closest('.arcu-popup-link');
                var id = target.getAttribute('data-id');
                openPopup(id);
            }
            if (e.target.classList.contains('arcu-header-close') || e.target.closest('.arcu-header-close')) {
                closeMenu();
            }
            if (e.target.classList.contains('arcu-popup-close') || e.target.closest('.arcu-popup-close')) {
                closePopup();
            }
            if (e.target.classList.contains('arcu-popup-back') || e.target.closest('.arcu-popup-back')) {
                closePopup();
                openMenu();
            }
        });
        if (rootElement.querySelector('.call-back')) {
            rootElement.querySelector('.call-back').addEventListener('click', function(e) {
                openCallbackPopup();
            });
        }
        if (rootElement.querySelector('.arcu-form-close')) {
            rootElement.querySelector('.arcu-form-close').addEventListener('click', function() {
                if (countdown != null) {
                    clearInterval(countdown);
                    countdown = null;
                }
                hideForm();
            });
        }
        if (rootElement.querySelector('.arcu-prompt-close')) {
            rootElement.querySelector('.arcu-prompt-close').addEventListener('click', function() {
                hidePrompt();
            });
        }
        var forms = rootElement.querySelectorAll('.arcu-form-container form');
        if (forms) {
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    form.parentElement.classList.add('ar-loading');
                    if (settings.reCaptcha) {
                        grecaptcha.execute(settings.reCaptchaKey, {
                            action: settings.reCaptchaAction
                        }).then(function(token) {
                            form.querySelector('.ar-g-token').value = token;
                            sendFormData(form);
                        });
                    }else{
                        sendFormData(form);
                    }
                });
            });
        }
        setTimeout(function(){
            processHash();
        },500);
        window.addEventListener('hashchange', function(e){
            processHash();
        });
    };
    
    var sendFormData = function($form){
        var e = new CustomEvent('arcontactus.beforeSendFormData', {
            detail: {
                form: $form
            }
        });
        rootElement.dispatchEvent(e);
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    $form.parentElement.classList.remove('ar-loading');
                    clearFormErrors($form);
                    data = JSON.parse(xmlhttp.responseText);
                    if (data.success) {
                        $form.parentElement.querySelector('.arcu-form-success').classList.add('active');
                        $form.parentElement.querySelector('.arcu-form-error').classList.remove('active');
                        var e = new CustomEvent('arcontactus.successSendFormData', {
                            detail: {
                                form: $form, 
                                data: data
                            }
                        });
                        rootElement.dispatchEvent(e);
                    } else {
                        if (data.errors){
                            processFormErrors($form, data);
                        }
                        var e = new CustomEvent('arcontactus.errorSendFormData', {
                            detail: {
                                form: $form,
                                data: data
                            }
                        });
                        rootElement.dispatchEvent(e);
                    }
                } else {
                    clearFormErrors($form);
                    if ($form.parentElement.querySelector('.arcu-form-success')) {
                        $form.parentElement.querySelector('.arcu-form-success').classList.remove('active');
                    }
                    if ($form.parentElement.querySelector('.arcu-form-error')) {
                        $form.parentElement.querySelector('.arcu-form-error').classList.add('active');
                    }
                    $form.parentElement.classList.remove('ar-loading');
                    alert(settings.errorMessage);
                    var e = new CustomEvent('arcontactus.errorSendFormData', {
                        detail: {
                            form: $form,
                            data: null
                        }
                    });
                    rootElement.dispatchEvent(e);
                }
            }
        };
        var url = $form.getAttribute('action');
        var method = $form.getAttribute('method');
        var data = new FormData ($form);
        
        xmlhttp.open(method, url, true);
        xmlhttp.send(data);
    };
    
    var processFormErrors = function($form, data){
        if (data.success == 0) {
            for (var i in data.errors) {
                if ($form.querySelector('.arcu-form-group-' + i)) {
                    $form.querySelector('.arcu-form-group-' + i).classList.add('has-error');
                    $form.querySelector('.arcu-form-group-' + i).querySelector('.arcu-form-field-errors').innerHTML = data.errors[i].join('<br/>');
                }
            };
        }
    };
    
    var clearFormErrors = function($form){
        var items = $form.querySelectorAll('.arcu-form-group.has-error');
        items.forEach(function(item) {
            item.classList.remove('has-error');
        });
    };
    
    var processHash = function() {
        var hash =  window.location.hash;
        switch(hash){
            case '#callback-form':
            case 'callback-form':
                contactUs.showForm('callback');
                break;
            case '#callback-form-close':
            case 'callback-form-close':
                contactUs.hideForm();
                break;
            case '#contactus-menu':
            case 'contactus-menu':
                contactUs.openMenu();
                break;
            case '#contactus-menu-close':
            case 'contactus-menu-close':
                contactUs.closeMenu();
                break;
            case '#contactus-hide':
            case 'contactus-hide':
                contactUs.hide();
                break;
            case '#contactus-show':
            case 'contactus-show':
                contactUs.show();
                break;
        }
    };
    
    var openPopup = function(id){
        closeMenu();
        
        rootElement.querySelector('#arcu-popup-' + id).classList.add('arcu-show');
        if (!rootElement.querySelector('#arcu-popup-' + id).classList.contains('popup-opened')) {
            stopAnimation(false);
            rootElement.classList.add('popup-opened');
            rootElement.querySelector('#arcu-popup-' + id).classList.add(settings.menuInAnimationClass);
            rootElement.querySelector('.arcu-close').classList.add('arcu-show');
            rootElement.querySelector('.static').classList.add('arcu-hide');
            rootElement.querySelector('.icons').classList.add('arcu-hide');
            rootElement.querySelectorAll('.pulsation').forEach((puls) => {
                puls.classList.add('stop');
            });
            _popupOpened = true;
            if (settings.visible === false) {
                contactUs.show();
            }
            var e = new Event('arcontactus.openPopup');
            rootElement.dispatchEvent(e);
        }
    };
    
    var closePopup = function(){
        if (rootElement.querySelector('.arcu-popup.arcu-show')) {
            setTimeout(function(){
                rootElement.classList.remove('popup-opened');
            }, 150);
            rootElement.querySelector('.arcu-popup.arcu-show').classList.remove(settings.menuInAnimationClass);
            if (settings.menuOutAnimationClass) {
                rootElement.querySelector('.arcu-popup.arcu-show').classList.add(settings.menuOutAnimationClass);
            }
            setTimeout(function(){
                rootElement.classList.remove('popup-opened');
                startAnimation();
            }, 150);
            rootElement.querySelector('.arcu-close').classList.remove('arcu-show');
            rootElement.querySelector('.static').classList.remove('arcu-hide');
            
            _popupOpened = false;
            
            if (settings.visible === false) {
                contactUs.hide();
            }
            var e = new Event('arcontactus.closePopup');
            rootElement.dispatchEvent(e);
        }
    };
    
    var openMenu = function() {
        if (settings.mode == 'callback'){
            console.log('Widget in callback mode');
            return false;
        }
        if (_formOpened){
            hideForm();
        }
        if (settings.style == 'elastic' || settings.style == 'bubble'){
            document.querySelector('body').classList.add('arcu-show-menu');
            document.querySelector('body').classList.add('arcu-menu-' + settings.align);
            document.querySelector('body').classList.add('arcu-pushed');
        }
        
        if (!rootElement.querySelector('.messangers-block').classList.contains(settings.menuInAnimationClass)) {
            stopAnimation(false);
            rootElement.classList.add('open');
            rootElement.querySelector('.messangers-block').classList.add(settings.menuInAnimationClass);
            rootElement.querySelector('.arcu-close').classList.add('arcu-show');
            rootElement.querySelector('.icons, .static').classList.add('arcu-hide');
            rootElement.querySelectorAll('.pulsation').forEach((puls) => {
                puls.classList.add('stop');
            });
            _menuOpened = true;
            if (settings.visible === false) {
                contactUs.show();
            }
            var e = new Event('arcontactus.openMenu');
            rootElement.dispatchEvent(e);
        }
        if (settings.style == 'elastic') {
            svgPath.animate({
                path: svgPathOpen
            }, 400, mina.easeinout, function() {
                isAnimating = false;
            });
        }else if(settings.style == 'bubble') {
            var pos = 0,
            nextStep = function( pos ) {
                if( pos > svgStepsTotal - 1 ) {
                    //isAnimating = false; 
                    return;
                }
                svgPath.animate({ 
                    path: svgSteps[pos]
                }, pos === 0 ? 400 : 500, pos === 0 ? mina.easein : mina.elastic, function() {
                    nextStep(pos);
                });
                pos++;
            };

            nextStep(pos);
        }
    };
    
    var closeMenu = function() {
        if (settings.mode == 'callback'){
            console.log('Widget in callback mode');
            return false;
        }
        if (settings.style == 'elastic' || settings.style == 'bubble'){
            document.querySelector('body').classList.remove('arcu-show-menu');
            document.querySelector('body').classList.remove('arcu-menu-' + settings.align);
            setTimeout(function(){
                document.querySelector('body').classList.remove('arcu-pushed');
            }, 500);
        }
        if (rootElement.querySelector('.messangers-block').classList.contains(settings.menuInAnimationClass)) {
            setTimeout(function(){
                if (!_formOpened){
                    rootElement.classList.remove('open');
                }
            }, 150);
            rootElement.querySelector('.messangers-block').classList.remove(settings.menuInAnimationClass);
            if (settings.menuOutAnimationClass) {
                rootElement.querySelector('.messangers-block').classList.add(settings.menuOutAnimationClass);
                setTimeout(function(){
                    rootElement.querySelector('.messangers-block').classList.remove(settings.menuOutAnimationClass);
                }, 1000);
            }
            rootElement.querySelector('.arcu-close').classList.remove('arcu-show');
            rootElement.querySelector('.static').classList.remove('arcu-hide');
            rootElement.querySelectorAll('.pulsation').forEach((puls) => {
                puls.classList.remove('stop');
            });
            _menuOpened = false;
            if (settings.iconsAnimationPause){
                _timeout = setTimeout(function(){
                    if (_callbackOpened || _menuOpened || _popupOpened || _formOpened){
                        return false;
                    }
                    startAnimation();
                }, settings.iconsAnimationPause);
            } else {
                startAnimation();
            }
            if (settings.visible === false) {
                contactUs.hide();
            }
            var e = new Event('arcontactus.closeMenu');
            rootElement.dispatchEvent(e);
        }
        if (settings.style == 'elastic' || settings.style == 'bubble') {
            setTimeout(function() {
                // reset path
                svgPath.attr('d', svgInitialPath);
                isAnimating = false; 
            }, 300);
        }
    };
    
    var toggleMenu = function() {
        hidePrompt();
        if (!rootElement.querySelector('.messangers-block').classList.contains(settings.menuInAnimationClass)) {
            openMenu();
        }else{
            closeMenu();
        }
        var e = new Event('arcontactus.toggleMenu');
        rootElement.dispatchEvent(e);
    };
    
    var showPrompt = function(data){
        var $promptContainer = rootElement.querySelector('.arcu-prompt');
        if (data && data.content){
            $promptContainer.querySelector('.arcu-prompt-inner').innerHTML = data.content;
        }
        $promptContainer.classList.add('active');
        var e = new Event('arcontactus.showPrompt');
        rootElement.dispatchEvent(e);
    };
    
    var hidePrompt = function() {
        var $promptContainer = rootElement.querySelector('.arcu-prompt');
        $promptContainer.classList.remove('active');
        var e = new Event('arcontactus.hidePrompt');
        rootElement.dispatchEvent(e);
    };
    
    var startAnimation = function(afterPause) {
        if (_menuOpened || _formOpened || (_animation && !afterPause)) {
            return false;
        }

        var $container = rootElement.querySelector('.icons-line');
        var $static = rootElement.querySelector('.static');
        if (rootElement.querySelector('.icons-line>span:first-child') === null) {
            return false;
        }
        var width = rootElement.querySelector('.icons-line>span:first-child').clientWidth;
        var offset = width + 40;
        if (settings.buttonSize === 'huge'){
            var xOffset = 2;
            var yOffset = 0;
        }
        if (settings.buttonSize === 'large'){
            var xOffset = 2;
            var yOffset = 0;
        }
        if (settings.buttonSize === 'medium'){
            var xOffset = 4;
            var yOffset = -2;
        }
        if (settings.buttonSize === 'small'){
            var xOffset = 4;
            var yOffset = -2;
        }
        var iconsCount = rootElement.querySelector('.icons-line').children.length;
        var step = 0;
        if (settings.iconsAnimationSpeed === 0){
            return false;
        }
        _animation = true;
        _interval = setInterval(function(){
            if (step === 0){
                $container.parentElement.classList.remove('arcu-hide');
                $static.classList.add('arcu-hide');
            }
            var x = offset * step;
            var translate = 'translate(' + (-(x+xOffset)) + 'px, ' + yOffset + 'px)';
            $container.style.cssText = "-webkit-transform:" + translate + "; " + "-ms-transform: " + translate + "transform: " + translate;
            step++;
            if (step > iconsCount){
                if (step > iconsCount + 1){
                    if (settings.iconsAnimationPause){
                        stopAnimation(true);
                        if (_animation) {
                            _timeout = setTimeout(function(){
                                if (_callbackOpened || _menuOpened || _popupOpened || _formOpened){
                                    return false;
                                }
                                startAnimation(true);
                            }, settings.iconsAnimationPause);
                        }
                    }
                    step = 0;
                }
                $container.parentElement.classList.add('arcu-hide');
                $static.classList.remove('arcu-hide');
                var translate = 'translate(' + (-xOffset) + 'px, ' + yOffset + 'px)';
                $container.style.cssText = "-webkit-transform:" + translate + "; " + "-ms-transform: " + translate + "transform: " + translate;
            }
        }, settings.iconsAnimationSpeed);
    };
    
    var stopAnimation = function(pause) {
        clearInterval(_interval);
        if (!pause) {
            _animation = false;
            clearTimeout(_timeout);
        }
        var $container = rootElement.querySelector('.icons-line');
        var $static = rootElement.querySelector('.static');
        $container.parentElement.classList.add('arcu-hide');
        $static.classList.remove('arcu-hide');
        var translate = 'translate(' + (-2) + 'px, 0px)';
        $container.style.cssText = "-webkit-transform:" + translate + "; " + "-ms-transform: " + translate + "transform: " + translate;
    };
    
    var removeEvents = function() {
        document.removeEventListener('click', documentClickHandler);
    };
    
    var isMenuOpened = () => {
        return _menuOpened;
    };
    
    var setUnreadCount = (c) => {
        if (!isEmptyValue(c)){
            rootElement.querySelector('.arcu-unread-badge').innerHTML = c;
            rootElement.querySelector('.arcu-unread-badge').classList.add('active');
        } else {
            rootElement.querySelector('.arcu-unread-badge').innerHTML = '';
            rootElement.querySelector('.arcu-unread-badge').classList.remove('active');
        }
    };

    /**
     * Destroy the current initialization.
     * @public
     */
    contactUs.destroy = function () {

        // If plugin isn't already initialized, stop
        if ( !initialized ) return;

        stopAnimation(false);
        removeEvents();
        rootElement.innerHTML = '';
        rootElement.className = '';
        
        var e = new Event('arcontactus.destroy');
        rootElement.dispatchEvent(e);

        // Reset variables
        settings = null;
        eventTimeout = null;
        
        clearInterval(_interval);
        clearTimeout(_timeout);
        _animation = false;
        _menuOpened = false;
        _popupOpened = false;
        _callbackOpened = false;
        _formOpened = false;
        countdown = null;
        svgPath = null;
        svgSteps = [];
        svgPathOpen = null;
        svgInitialPath = null;
        svgStepsTotal = null;
        
        initialized = false;
    };
    
    var insertPromptTyping = function(){
        var $promptContainer = rootElement.querySelector('.arcu-prompt-inner');
        var $typing = createElement('div', {
            classes: ['arcu-prompt-typing']
        });
        var $item = createElement('div');
        $typing.append($item);
        $typing.append($item.cloneNode());
        $typing.append($item.cloneNode());
        $promptContainer.append($typing);
    };
    
    var showPromptTyping = function(){
        var $promptContainer = rootElement.querySelector('.arcu-prompt');
        $promptContainer.querySelector('.arcu-prompt-inner').innerHTML = '';
        insertPromptTyping();
        showPrompt({});
        var e = new Event('arcontactus.showPromptTyping');
        rootElement.dispatchEvent(e);
    };
    
    var hidePromptTyping = function(){
        var $promptContainer = rootElement.querySelector('.arcu-prompt');
        $promptContainer.classList.remove('active');
        var e = new Event('arcontactus.hidePromptTyping');
        rootElement.dispatchEvent(e);
    };
    
    var showWellcomeTyping = function(){
        var $wellcomeContainer = rootElement.querySelector('.arcu-wellcome');
        if (!$wellcomeContainer) {
            return false;
        }
        var $icon = rootElement.querySelector('.arcu-menu-header > .arcu-header-icon');
        if (!$wellcomeContainer.querySelector('.arcu-wellcome-msg.typing')) {
            var $wellcomeLine = createElement('div', {
                classes: ['arcu-wellcome-msg', 'typing']
            });
            var $wellcomeIcon = createElement('div', {
                classes: ['arcu-wellcome-icon']
            });
            
            $wellcomeIcon.append($icon.cloneNode(true));
            
            var $wellcomeTime = createElement('div', {
                classes: ['arcu-wellcome-time']
            });
            var msgDate = new Date();
            
            $wellcomeTime.innerHTML = (('0' + (msgDate.getHours())).slice(-2) + ':' + ('0' + (msgDate.getMinutes())).slice(-2));
            
            var $wellcomeContent = createElement('div', {
                classes: ['arcu-wellcome-content']
            });
            
            var $typing = createElement('div', {
                classes: ['arcu-prompt-typing']
            });
            var $item = createElement('div');
            $typing.append($item);
            $typing.append($item.cloneNode());
            $typing.append($item.cloneNode());
            
            $wellcomeContent.append($typing);
            
            $wellcomeLine.append($wellcomeTime);
            $wellcomeLine.append($wellcomeIcon);
            $wellcomeLine.append($wellcomeContent);
            $wellcomeContainer.append($wellcomeLine);
        }
    };
    
    var showWellcomeMessage = function(data){
        var $wellcomeContainer = rootElement.querySelector('.arcu-wellcome');
        if (!$wellcomeContainer) {
            return false;
        }
        if ($wellcomeContainer.querySelector('.arcu-wellcome-msg.typing')) {
            $wellcomeContainer.querySelector('.arcu-wellcome-msg.typing .arcu-wellcome-content').innerHTML = data.content;
            $wellcomeContainer.querySelector('.arcu-wellcome-msg.typing').classList.remove('typing');
        }
    };

    /**
     * Initialize Plugin
     * @public
     * @param {Object} options User settings
     */
    contactUs.init = function ( options ) {
        // feature test
        if ( !supports ) return;

        // Destroy any existing initializations
        contactUs.destroy();

        // Merge user options with defaults
        settings = extend( defaults, options || {} );
        
        settings.forms.dynamic_form = {
            header: ''
        };
        
        rootElement = document.getElementById(settings.rootElementId);
        
        if (!rootElement) {
            console.log('Root element not found');
        }
        
        rootElement.classList.add('arcu-widget');
        rootElement.classList.add('arcu-message');
        rootElement.classList.add('layout-' + settings.layout);
        
        if (settings.style !== '' && settings.style !== null) {
            rootElement.classList.add('arcu-' + settings.style);
        }
        if ((settings.style == null || settings.style == 'popup' || settings.style == '' || settings.style == 'no-background') && settings.popupAnimation) {
            rootElement.classList.add('arcu-'+settings.popupAnimation);
            if (settings.style == 'no-background') {
                rootElement.classList.add('arcu-menu-'+settings.menuDirection);
            }
        }
        if (settings.menuStyle && settings.menuStyle !== null) {
            rootElement.classList.add('arcu-menu-'+settings.menuStyle);
        }
        if (settings.align === 'left'){
            rootElement.classList.add('left');
        }else{
            rootElement.classList.add('right');
        }
        
        if (settings.items.length){
            if (settings.mode == 'regular' || settings.mode == 'single'){
                initMessengersBlock();
                if (settings.mode == 'single') {
                    var $a = rootElement.querySelector('.messangers-list li:first-child a');
                    if ($a.getAttribute('href')) {
                        rootElement.append(createElement('a', {
                            href: $a.getAttribute('href'),
                            target: $a.getAttribute('target'),
                            classes: ['arcu-single-mode-link']
                        }));
                    }
                }
            }
            if (popups.length) {
                initPopups();
            }
            initMessageButton();
            initForms();
            initPrompt();
            initEvents();
            setTimeout(function(){
                startAnimation();
            }, settings.iconsAnimationPause? settings.iconsAnimationPause : 2000);
            if (settings.backdrop) {
                var backdrop = createElement('div', {
                    classes: ['arcu-backdrop']
                });
                rootElement.append(backdrop);
            }
            if (settings.visible === true) { 
                rootElement.classList.add('active');
            }
        }else{
            console.info('jquery.contactus:no items');
        }
        if (settings.style == 'elastic' || settings.style == 'bubble') {
            var morphEl = document.getElementById('arcu-morph-shape');
            var s = Snap(morphEl.querySelector('svg'));
            svgPath = s.select('path');
            svgPathOpen = morphEl.getAttribute('data-morph-open');
            svgInitialPath = svgPath.attr('d');
            svgSteps = svgPathOpen.split(';');
            svgStepsTotal = svgSteps.length;
        }
        initialized = true;
        var e = new Event('arcontactus.init');
        rootElement.dispatchEvent(e);
    };

    contactUs.isInitialized = function() {
        return initialized;
    };
    
    contactUs.getSettings = function() {
        return settings;
    };
    
    contactUs.getRootElement = function() {
        return rootElement;
    };

    contactUs.openMenu = function() {
        return openMenu();
    };
    
    contactUs.closeMenu = function() {
        return closeMenu();
    };
    
    contactUs.toggleMenu = function() {
        return toggleMenu();
    };
    
    contactUs.showForm = function(id) {
        return showForm(id);
    };
    
    contactUs.hideForm = function() {
        return hideForm();
    };
    
    contactUs.showPromptTyping = function() {
        return showPromptTyping();
    };
    
    contactUs.hidePromptTyping = function() {
        return hidePromptTyping();
    };
    
    contactUs.showPrompt = function(data) {
        return showPrompt(data);
    };
    
    contactUs.hidePrompt = function() {
        return hidePrompt();
    };
    
    contactUs.showWellcomeTyping = function() {
        return showWellcomeTyping();
    };
    
    contactUs.showWellcomeMessage = function(data) {
        return showWellcomeMessage(data);
    };
    
    contactUs.openPopup = function(id) {
        return openPopup(id);
    };
    
    contactUs.closePopup = function() {
        return closePopup();
    };
    
    contactUs.showSubmenu = function(data) {
        return showSubmenu(data);
    };
    
    contactUs.hideSubmenu = function(data) {
        return hideSubmenu(data);
    };
    
    contactUs.show = function(){
        rootElement.classList.add('active');
        var e = new Event('arcontactus.show');
        startAnimation();
        rootElement.dispatchEvent(e);
    };
    contactUs.hide = function(){
        rootElement.classList.remove('active');
        var e = new Event('arcontactus.hide');
        stopAnimation(false);
        rootElement.dispatchEvent(e);
    };
    
    contactUs.startAnimation = function(){
        return startAnimation();
    };
    
    contactUs.stopAnimation = function(pause){
        return stopAnimation(pause);
    };
    
    contactUs.triggerItem = function(event, id, params) {
        if (rootElement.querySelector('#msg-item-' + id)) {
            var e = new CustomEvent(event, {
                detail: params
            });
            rootElement.querySelector('#msg-item-' + id).dispatchEvent(e);
        }
    };
    
    contactUs.updateMenuItem = (id, item) => {
        return updateMenuItem(id, item);
    };
    
    contactUs.updateMenuItemStatus = (id, status) => {
        return updateMenuItemStatus(id, status);
    };
    
    contactUs.setMenuItemDisabled = (id, disabled) => {
        return setMenuItemDisabled(id, disabled);
    };
    
    contactUs.setMenuItemLabels = (id, labels) => {
        return setMenuItemLabels(id, labels);
    };
    
    contactUs.isMenuOpened = () => isMenuOpened();
    
    contactUs.setUnreadCount = (c) => setUnreadCount(c);
    
    contactUs.utils = {};
    
    contactUs.utils.createElement = function(tag, options, content){
        return createElement(tag, options, content);
    };
    
    contactUs.utils.DOMElementFromHTML = function(htmlString) {
        return DOMElementFromHTML(htmlString);
    };

    return contactUs;
});