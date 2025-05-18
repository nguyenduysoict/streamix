<?php
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH.'wp-includes/l10n.php');

class ArContactUsTools 
{
    public static function escJsString($value, $nl2br = false)
    {
        $value = esc_js($value);
        //$value = nl2br($value);
        //$value = str_replace(array("\n", "\r"), '', $value);
        if ($nl2br) {
            $value = str_replace(array('\n'), '<br/>', $value);
        }
        
        return $value;
    }
    
    public static function minifyStyles($styles)
    {
        $styles = preg_replace('/\s+/is', ' ', $styles);
        $styles = str_replace(array('; }'), '}', $styles);
        $styles = str_replace(array('{ '), '{', $styles);
        $styles = str_replace(array('{ {'), '{{', $styles);
        $styles = str_replace(array('} }'), '}}', $styles);
        $styles = str_replace(array(': '), ':', $styles);
        return $styles;
    }
    
    public static function isMultilang()
    {
        return self::isWPML() || self::isPolylang();
    }
    
    public static function isWPML()
    {
        return is_plugin_active('sitepress-multilingual-cms/sitepress.php');
    }
    
    public static function isPolylang()
    {
        return is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php');
    }
    
    public static function getLanguages()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
        } elseif (self::isPolylang()) {
            return icl_get_languages();
        } else {
            return array(
                self::getDefaultLanguage() => array(
                    'code' => self::getDefaultLanguage(),
                    'language_code' => self::getDefaultLanguage()
                )
            );
        }
    }
    
    public static function getDefaultLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_default_language', null);
        } elseif(self::isPolylang()) {
            return icl_get_default_language();
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
    
    public static function getCurrentLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_current_language', null);
        } elseif (self::isPolylang()) {
            return icl_get_current_language();
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
    
    public static function formatPhone($phone)
    {
        return '+' . preg_replace('{\W+}is', '', $phone);
    }
    
    public static function isMobile()
    {
        if (function_exists('wp_is_mobile')){
            return wp_is_mobile();
        }
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}
        
	return apply_filters('wp_is_mobile', $is_mobile);
    }
    
    public static function getCurrentUrl()
    {
        $host = parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_HOST);
        $requestUri = $_SERVER['REQUEST_URI'];
        $scheme = 'http';
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')) {
            $scheme = 'https';
        }
        return $scheme . '://' . $host . $requestUri;
    }
}
