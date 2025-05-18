<?php

class ArContactUsUpdater
{
    const ACTIVATION_URL = 'https://api.areama.net/plugins/activate';
    const UPDATE_CHECK_URL = 'https://api.areama.net/plugins/update';
    const DEACTIVATE_URL = 'https://api.areama.net/plugins/deactivate';
    
    private $f = null;
    private $owner = null;


    public function __construct($owner = null)
    {
        $this->owner = $owner;
        add_filter('http_request_args', array($this, 'updatesExclude'), 5, 2);
    }
    
    public function updatesExclude($r, $url)
    {
        if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check')){
            return $r; // Not a plugin update request.
        }
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(AR_CONTACTUS_PLUGIN_FILE)]);
	unset($plugins->active[array_search(plugin_basename(AR_CONTACTUS_PLUGIN_FILE), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
    }
    
    //Returns current plugin info.
    function getPluginInfo($i) {
        $this_file = AR_CONTACTUS_PLUGIN_FILE;
        if (!function_exists('get_plugins')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $plugin_folder = get_plugins( '/' . plugin_basename(dirname($this_file)));
        $plugin_file = basename(($this_file));
        
        return $plugin_folder[$plugin_file][$i];
    }
    
    public function getActivationUrl($activate = true)
    {
        $params = http_build_query(array(
            'plugin' => AR_CONTACTUS_PLUGIN_NAME,
            'version' => AR_CONTACTUS_VERSION,
            'domain' => parse_url(get_bloginfo('url'), PHP_URL_HOST),
            'purchaseCode' => get_option('AR_CONTACTUS_PURCHASE_CODE', ''),
            'activate' => (int)$activate,
            'channel' => get_option('ARCU_CHANNEL', 'prod')
        ));
        return self::ACTIVATION_URL . '?' . $params;
    }
    
    public function getUpdateUrl()
    {
        $params = http_build_query(array(
            'plugin' => AR_CONTACTUS_PLUGIN_NAME,
            'version' => AR_CONTACTUS_VERSION,
            'domain' => parse_url(get_bloginfo('url'), PHP_URL_HOST),
            'purchaseCode' => get_option('AR_CONTACTUS_PURCHASE_CODE', ''),
            'channel' => get_option('ARCU_CHANNEL', 'prod')
        ));
        return self::UPDATE_CHECK_URL . '?' . $params;
    }
    
    public function getDeactivateUrl()
    {
        $params = http_build_query(array(
            'plugin' => AR_CONTACTUS_PLUGIN_NAME,
            'version' => AR_CONTACTUS_VERSION,
            'domain' => parse_url(get_bloginfo('url'), PHP_URL_HOST),
            'purchaseCode' => get_option('AR_CONTACTUS_PURCHASE_CODE', '')
        ));
        return self::DEACTIVATE_URL . '?' . $params;
    }
    
    public function deactivate()
    {
        $url = $this->getDeactivateUrl();
        $response = wp_remote_get($url);
        
        if (($response instanceof WP_Error) || (is_array($response) && isset($response['response']) && isset($response['response']['code']) && $response['response']['code'] != 200)) {
            return array(
                'success' => 0,
                'error' => __('Error deactivating plugin.', 'ar-contactus'),
                'res' => $response
            );
        }
        if ((is_array($response) && isset($response['response']) && isset($response['response']['code']) && $response['response']['code'] == 200)) {
            $data = json_decode($response['body']);
            if (isset($data->error) && $data->error) {
                return array(
                    'success' => 0,
                    'error' => $data->message? $data->message : __('Error deactivating plugin. Please check your purchase code.', 'ar-contactus'),
                    'res' => $response
                );
            } elseif(isset($data->success) && $data->success == 1) {
                return array(
                    'success' => 1,
                );
            }
        }
        return array(
            'success' => 0,
            'error' => __('Error deactivating plugin.', 'ar-contactus'),
            'res' => $response
        );
    }
    
    public function isActivated()
    {
        return $this->activate(false);
    }
    
    public function activate($activate = true)
    {
                return array(
                    'success' => 1,
                );
    }
    
    public function autoCheckUpdate($get_plugins)
    {
        $this->checkUpdate();
        return $get_plugins;
    }
    
    public function checkUpdate($cleanUp = false) {
        $this_file = AR_CONTACTUS_PLUGIN_FILE;
	$updateUrl = $this->getUpdateUrl();
        $plugin_folder = plugin_basename(dirname($this_file));
	$plugin_file = basename(($this_file));
        if (defined('WP_INSTALLING')) {
            return false;
        }
        $data = null;
        if ($response = $this->sendRequest($updateUrl)) {
            $data = json_decode($response);
            if (is_null($data)) {
                return false;
            }
        } else {
            return false;
        }
        
        if (isset($data->error) && $data->error){
            return false;
        }
        if (!isset($data->version) || !isset($data->url)){
            return false;
        }
        $currentVersion = $this->getPluginInfo('Version');
        $newVersion = $data->version;
        $url = $data->url;
        if (version_compare($newVersion, $currentVersion, '<=') && !$cleanUp){
            return false;
        }
	$plugin_transient = get_site_transient('update_plugins');
	$a = array(
            'slug' => $plugin_folder,
            'new_version' => $newVersion,
            'url' => $this->getPluginInfo("AuthorURI"),
            'package' => $url
	);
	$o = (object) $a;
        if (!empty($plugin_transient)) {
            if (version_compare($newVersion, $currentVersion, '<=') && isset($plugin_transient->response[$plugin_folder.'/'.$plugin_file])) {
                unset($plugin_transient->response[$plugin_folder.'/'.$plugin_file]);
                set_site_transient('update_plugins', $plugin_transient);
                return true;
            }
            $plugin_transient->response[$plugin_folder.'/'.$plugin_file] = $o;
            set_site_transient('update_plugins', $plugin_transient);
        }
    }
    
    public function sendRequest($url)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'ignore_errors' => true
            )
        ));
        if ($res = @file_get_contents($url, false, $context)) {
            if ($status_line = $http_response_header[0]) {
                if (preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match)) {
                    $status = $match[1];

                    if ($status !== '200') {
                        return null;
                    }
                    return $res;
                }
            }
        }
        return null;
    }
    
    public function migrate()
    {
        $oldVersion = $this->getOldVersion();
        $this->log('----- BEGIN -----' . PHP_EOL);
        $this->log('Old version ' . $oldVersion . PHP_EOL);
        if ($oldVersion === null){
            $this->updateVersion(AR_CONTACTUS_VERSION);
            return false;
        }
        
        $d = opendir(AR_CONTACTUS_PLUGIN_DIR . 'upgrade');
        $files = array();
        while ($file = readdir($d)) {
            if (is_file(AR_CONTACTUS_PLUGIN_DIR . 'upgrade/' . $file) && is_readable(AR_CONTACTUS_PLUGIN_DIR . 'upgrade/' . $file)) {
                $files[] = $file;
            }
        }
        
        if ($files) {
            sort($files);
            $this->log('Migrations found: ' . PHP_EOL);
            foreach ($files as $file){
                $this->log($file . PHP_EOL);
                $className = pathinfo($file, PATHINFO_FILENAME);
                require_once AR_CONTACTUS_PLUGIN_DIR . 'upgrade/' . $file;
                if (class_exists($className)){
                    $migration = new $className();
                    if (version_compare($migration->getVersion(), $oldVersion, '>')){
                        $this->log('Applying migration ' . $file);
                        if ($migration->upgrade()){
                            $this->log(' :: OK' . PHP_EOL);
                            $this->updateVersion($migration->getVersion());
                            $this->log('Store migration version ' . $migration->getVersion() . PHP_EOL);
                        }
                    }
                }
            }
        }
        if ($this->owner) {
            $this->owner->compileCSS();
            $this->owner->compileCSS(true);
        }
        $this->log('Store plugin version ' . $this->getPluginInfo('Version') . PHP_EOL);
        $this->updateVersion($this->getPluginInfo('Version'));
        $this->log('----- END -----' . PHP_EOL . PHP_EOL);
        $this->closeFile();
    }
    
    protected function getFileHandle()
    {
        if ($this->f === null) {
            $this->f = @fopen(AR_CONTACTUS_PLUGIN_DIR . 'upgrade.log', 'a+');
        }
        return $this->f;
    }

    protected function closeFile()
    {
        if ($this->f !== null && $this->f !== false) {
            fclose($this->f);
            $this->f = null;
        }
    }

    public function log($message)
    {
        $f = $this->getFileHandle();
        if ($f) {
            $line = '[' . date('Y-m-d H:i:s') . ']  ' . $message;
            fwrite($f, $line);
        }
    }
    
    public function getOldVersion()
    {
        return get_option('arcu_version', null);
    }
    
    public function updateVersion($version)
    {
        update_option('arcu_version', $version);
    }
}
