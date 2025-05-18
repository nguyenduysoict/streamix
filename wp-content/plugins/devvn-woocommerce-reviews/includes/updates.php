<?php

defined("ABSPATH") || exit("No script kiddies please!");
if (!class_exists("devvn_reviews_auto_update")) {
    class devvn_reviews_auto_update
    {
        public $current_version = NULL;
        public $update_path = NULL;
        public $plugin_slug = NULL;
        public $slug = NULL;
        public $name_transient = NULL;
        public function __construct($current_version, $update_path, $plugin_slug)
        {
            $this->current_version = $current_version;
            $this->update_path = $update_path;
            $this->plugin_slug = $plugin_slug;
            list($t1, $t2) = explode("/", $plugin_slug);
            $this->slug = str_replace(".php", "", $t2);
            $this->name_transient = "devvn_update_information_" . $this->slug;
            add_filter("pre_set_site_transient_update_plugins", [$this, "check_update"]);
            add_filter("plugins_api", [$this, "check_info"], 10, 3);
            add_action("upgrader_process_complete", [$this, "upgrader_process_complete"], 10, 2);
        }
        public function check_update($transient)
        {
            $remote_infor = $this->getRemote_information();
            $remote_version = $this->getRemote_version();
            if (version_compare($this->current_version, $remote_version, "<")) {
                $obj = new stdClass();
                $obj->slug = $remote_infor->slug;
                $obj->plugin = $this->plugin_slug;
                $obj->new_version = $remote_version;
                $obj->url = $remote_infor->homepage;
                $obj->package = $remote_infor->download_link;
                $obj->icons = $remote_infor->icons;
                $obj->banners = $remote_infor->banners;
                $obj->requires = $remote_infor->requires;
                $obj->tested = $remote_infor->tested;
                $obj->requires_php = $remote_infor->requires_php;
                $transient->response[$this->plugin_slug] = $obj;
            }
            return $transient;
        }
        public function check_info($false, $action, $arg)
        {
            if ($action !== "plugin_information") {
                return false;
            }
            if ($arg->slug == $this->slug) {
                $information = $this->getRemote_information();
                return $information;
            }
            return $false;
        }
        public function getRemote_version()
        {
            $information = $this->getRemote_information();
            if ($information && isset($information->version) && $information->version) {
                return $information->version;
            }
            return false;
        }
        public function getRemote_information()
        {
            if (false === ($information = get_transient($this->name_transient))) {
                $request = wp_remote_post($this->update_path, ["body" => ["getremote" => "info", "slug" => $this->slug, "site" => wp_parse_url(home_url(), PHP_URL_HOST), "ip" => $_SERVER["REMOTE_ADDR"], "locale" => get_locale(), "phpv" => phpversion(), "current_version" => $this->current_version], "user-agent" => "WordPress/" . get_bloginfo("version") . "; " . get_bloginfo("url")]);
                if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                    $information = unserialize($request["body"]);
                    set_transient($this->name_transient, $information, 20 * MINUTE_IN_SECONDS);
                }
            }
            if ($information) {
                return $information;
            }
            return false;
        }
        public function upgrader_process_complete($upgrader, $update_data)
        {
            delete_transient($this->name_transient);
        }
    }
    add_action("init", "devvn_reviews_auto_update_init");
    function devvn_reviews_auto_update_init()
    {
        $license_key = reviews_get_license();
        $devvn_plugin_current_version = DEVVN_REVIEWS_VERSION_NUM;
        $devvn_plugin_slug = DEVVN_REVIEWS_BASENAME;
        $devvn_plugin_remote_path = "https://license.levantoan.com/wp-admin/admin-ajax.php?action=devvn_update&slug=devvn-woocommerce-reviews&getremote=update&license=" . $license_key;
        new devvn_reviews_auto_update($devvn_plugin_current_version, $devvn_plugin_remote_path, $devvn_plugin_slug);
    }
}

?>