<?php

defined("ABSPATH") || exit("No script kiddies please!");
if (!class_exists("DevVN_Reviews_Class")) {
    class DevVN_Reviews_Class
    {
        public $_version = '1.4.1';
        public $_optionName = 'devvn_reviews_options';
        public $_optionGroup = "devvn_reviews-options-group";
        public $_defaultOptions = ["img_size" => "512000", "disable_upload" => "0", "number_img_upload" => 3, "cmt_length" => "10", "review_position" => "", "review_position_action" => "", "review_priority" => 12, "recaptcha" => "", "license_key" => "99999", "show_date" => "1", "show_tcmt" => "1", "show_sold" => "1", "rv_style" => "devvn-style2", "loop_rating" => 0, "loop_rating_zero" => 0, "quick_review" => "", "show_like" => "2", "label_review" => "", "tcmt_number" => 5, "show_avatar_review" => 2, "active_name_phone" => 2, "tcmt_phone" => 2, "active_field_email" => 1, "enable_qapage" => 2, "enable_cmt_avatar" => 2, "enable_postcmt" => 2, "enable_postcmt_phone" => 1, "hidden_phone_reviews" => 2, "include_script_shortcode" => 1, "disable_owl_slider" => 0, "enable_img_popup" => 0, "shortcode_pc_column" => 5, "shortcode_tablet_column" => 3, "shortcode_mobile_column" => 2, "shortcode_reviews_number" => 10, "show_img_reviews" => 1, "view_product_info_in_list" => 0];
        public $_schemaOptionName = "";
        public $_schemaOptionNamePrefix = "schema_";
        public $_autoReviewsOptionName = "";
        public $_autoReviewsOptionNamePrefix = "auto_reviews_";
        protected static $instance = NULL;
        public static function init()
        {
            is_null(self::$instance) && (self::$instance = new self());
            return self::$instance;
        }
        public function __construct()
        {
            $this->define_constants();
            global $devvn_review_settings;
            $devvn_review_settings = $this->get_options();
            $this->set_schemaOptionName();
            $this->_version = DEVVN_REVIEWS_VERSION_NUM;
            require_once DEVVN_REVIEWS_PLUGIN_DIR . "includes/functions.php";
            add_filter("plugin_action_links_" . DEVVN_REVIEWS_BASENAME, [$this, "add_action_links"], 10, 2);
            add_action("admin_menu", [$this, "admin_menu"]);
            add_action("admin_init", [$this, "register_mysettings"]);
            add_filter("comments_template", [$this, "devvn_comments_template_loader"], 99);
            add_action("comment_post", [$this, "count_agian_review_count"], 10, 3);
            add_action("preprocess_comment", [$this, "devvn_update_comment_type"], 1);
            add_filter("comments_template_query_args", [$this, "devvn_comments_template_query_args"], 10);
            add_action("delete_comment_meta", [$this, "devvn_delete_img_after_delete_cmt"], 10, 4);
            add_action("comment_post", [$this, "save_comment_meta_data"]);
            add_filter("get_comment_text", [$this, "modify_comment"], 20, 2);
            add_action("wp_update_comment_count", [$this, "devvn_clear_transients_count_review"], 20);
            add_filter("comment_post_redirect", [$this, "devvn_comment_post_redirect"], 10, 2);
            add_action("woocommerce_review_after_comment_text", [$this, "devvn_attach_view"]);
            add_filter("woocommerce_product_tabs", [$this, "woo_remove_product_tabs"], 98);
            if ($devvn_review_settings["review_position"]) {
                if ($devvn_review_settings["review_position"] == "custom" && $devvn_review_settings["review_position_action"]) {
                    add_action($devvn_review_settings["review_position_action"], "comments_template", $devvn_review_settings["review_priority"]);
                } else {
                    if ($devvn_review_settings["review_position"] != "custom") {
                        add_action($devvn_review_settings["review_position"], "comments_template", $devvn_review_settings["review_priority"]);
                    }
                }
            }
            if ($devvn_review_settings["show_tcmt"] == 1) {
                add_action("wp_ajax_devvn_cmt_submit", [$this, "devvn_cmt_submit_func"]);
                add_action("wp_ajax_nopriv_devvn_cmt_submit", [$this, "devvn_cmt_submit_func"]);
                add_action("comment_post", [$this, "devvn_delete_transient_tcomment"]);
                add_action("edit_comment", [$this, "devvn_delete_transient_tcomment"]);
                add_action("wp_set_comment_status", [$this, "devvn_delete_transient_tcomment"]);
                add_action("wp_ajax_devvn_cmt_search", [$this, "devvn_cmt_search_func"]);
                add_action("wp_ajax_nopriv_devvn_cmt_search", [$this, "devvn_cmt_search_func"]);
                add_action("wp_ajax_devvn_cmt_load_paged", [$this, "devvn_cmt_load_paged_func"]);
                add_action("wp_ajax_nopriv_devvn_cmt_load_paged", [$this, "devvn_cmt_load_paged_func"]);
            }
            add_action("wp_enqueue_scripts", [$this, "devvn_cmt_enqueue_style"]);
            include_once "includes/updates.php";
            add_action("admin_notices", [$this, "admin_notices"]);
            if (is_admin()) {
                add_action("in_plugin_update_message-" . DEVVN_REVIEWS_BASENAME, [$this, "devvn_modify_plugin_update_message"], 10, 2);
            }
            add_filter("body_class", [$this, "body_classs"]);
            add_action("admin_enqueue_scripts", [$this, "admin_enqueue_scripts"], 999);
            add_action("wp_ajax_devvn_reviews_sync_cmt", [$this, "devvn_reviews_sync_cmt_func"]);
            add_action("wp_ajax_fake_reviews_bought", [$this, "fake_reviews_bought_func"]);
            add_action("wp_ajax_admin_fake_label", [$this, "admin_fake_label_func"]);
            add_action("wp_ajax_open_comment_prod", [$this, "open_comment_prod_func"]);
            add_action("wp_ajax_reset_sold", [$this, "reset_sold_func"]);
            self::create_files();
            add_filter("woocommerce_comment_pagination_args", [$this, "devvn_woocommerce_comment_pagination_args"]);
            add_action("devvn_reviews_action", [$this, "get_like_review"]);
            add_action("wp_ajax_devvn_like_cmt", [$this, "devvn_like_cmt_func"]);
            add_action("wp_ajax_nopriv_devvn_like_cmt", [$this, "devvn_like_cmt_func"]);
            $this->load_textdomain();
            add_action("add_meta_boxes", [$this, "cmt_register_meta_boxes"]);
            add_filter("wp_update_comment_data", [$this, "cmt_register_meta_boxes_save"], 1);
            add_action("devvn_approved_reviews_schedule", [$this, "devvn_approved_reviews_schedule_func"]);
            add_action("wp_set_comment_status", [$this, "devvn_approved_reviews_changestatus_func"], 10, 2);
            add_shortcode("devvn_reviews", [$this, "devvn_reviews_func"]);
            add_action("plugins_loaded", [$this, "devvn_check_license_func"]);
            add_action("deactivated_plugin", [$this, "devvn_end_check_license_func"]);
            add_filter("cron_schedules", [$this, "weekly_cron_schedule"]);
            add_shortcode("devvn_sold", [$this, "devvn_sold_func"]);
            add_action("woocommerce_product_options_general_product_data", [$this, "devvn_sold_metabox"]);
            add_action("woocommerce_process_product_meta", [$this, "devvn_sold_metabox_save"]);
            add_filter("devvn_cmt_author", [$this, "devvn_custom_cmt_author"], 10, 2);
            add_action("wp_ajax_active_license_reviews", [$this, "active_license_func"]);
            add_action("wp_ajax_autoreviews_prod", [$this, "auto_reviews_func"]);
            add_action("wp_ajax_fake_sold_prod", [$this, "fake_sold_prod_func"]);
            if ($devvn_review_settings["enable_postcmt"] == 1) {
                add_filter("comments_template", [$this, "devvn_post_comments_template_loader"], 99);
                add_filter("wp_get_current_commenter", [$this, "wp_get_current_commenter"], 99);
                add_action("set_comment_cookies", [$this, "wp_set_comment_cookies"], 10, 3);
                add_action("preprocess_comment", [$this, "devvn_post_comment_preprocess"], 1);
                require_once DEVVN_REVIEWS_PLUGIN_DIR . "includes/class-devvn-walker-comment.php";
            }
            add_action("wp_enqueue_scripts", [$this, "devvn_comment_scripts"], 100);
            include "includes/shortcode-list-reviews.php";
            if ($this->get_schema_option("active")) {
                include "includes/schema-support.php";
                if (function_exists("Reviews_Schema_Support")) {
                    Reviews_Schema_Support();
                }
            }
            add_filter("wc_get_template", [$this, "wc_get_template"], 10, 2);
            add_filter("woocommerce_product_get_rating_html", [$this, "woocommerce_product_get_rating_html"], 10, 3);
            add_filter("comment_moderation_text", [$this, "comment_moderation_text"], 99, 2);
            add_action("wp_footer", [$this, "wp_footer_devvn_reviews"]);
            add_action("update_option_devvn_reviews_options", [$this, "check_after_update_option"]);
            add_action("devvn_reviews_before_comment_field", [$this, "add_quick_reviews"], 10, 3);
            add_filter("allow_empty_comment", [$this, "allow_empty_comment"], 10);
            add_filter("duplicate_comment_id", [$this, "duplicate_comment_id"], 20);
        }
        public function set_schemaOptionName()
        {
            $this->_schemaOptionName = apply_filters("devvn_reviews_schema_option", ["active" => ["args" => "", "group" => "schema-group", "default" => "0"], "brand" => ["args" => "", "group" => "schema-group", "default" => ""], "brand_default" => ["args" => "", "group" => "schema-group", "default" => ""]]);
            $this->_autoReviewsOptionName = apply_filters("devvn_reviews_auto_reviews_option", ["user" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "comment" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "number_reviews" => ["args" => "", "group" => "auto-reviews-group", "default" => 1], "number_reviews_to" => ["args" => "", "group" => "auto-reviews-group", "default" => 5], "cat_reviews" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "prod_reviews" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "no_reviews" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "date_reviews_form" => ["args" => "", "group" => "auto-reviews-group", "default" => ""], "date_reviews_to" => ["args" => "", "group" => "auto-reviews-group", "default" => ""]]);
        }
        public function get_schema_option($name)
        {
            $default = isset($this->_schemaOptionName[$name]["default"]) && $this->_schemaOptionName[$name]["default"] ? $this->_schemaOptionName[$name]["default"] : "";
            return get_option($this->_schemaOptionNamePrefix . $name, $default);
        }
        public function get_autoreviews_option($name)
        {
            $default = isset($this->_autoReviewsOptionName[$name]["default"]) && $this->_autoReviewsOptionName[$name]["default"] ? $this->_autoReviewsOptionName[$name]["default"] : "";
            return get_option($this->_autoReviewsOptionNamePrefix . $name, $default);
        }
        public function cmt_register_meta_boxes()
        {
            add_meta_box("devvn-reviews-metabox-id", __("Review Options", "devvn-reviews"), [$this, "cmt_register_meta_boxes_display_callback"], "comment", "normal", "high");
        }
        public function cmt_register_meta_boxes_display_callback($comment)
        {
            wp_nonce_field("devvn_cmt_save_data", "devvn_cmt_save_data_nonce");
            $bydevvnimport = get_comment_meta($comment->comment_ID, "bydevvnimport", true);
            $phone = get_comment_meta($comment->comment_ID, "phone", true);
            $devvn_like_cmt = get_comment_meta($comment->comment_ID, "devvn_like_cmt", true);
            $attachment_img = (array) get_comment_meta($comment->comment_ID, "attachment_img", true);
            $quick_tags = (array) get_comment_meta($comment->comment_ID, "quick_tag", true);
            echo "            <table class=\"form-table editcomment\" role=\"presentation\">\r\n                <tbody>\r\n                <tr>\r\n                    <td class=\"first\"><label for=\"bydevvnimport\">";
            _e("Schedule", "devvn-reviews");
            echo "</label></td>\r\n                    <td><label><input type=\"checkbox\" name=\"bydevvnimport\" value=\"1\" id=\"bydevvnimport\" ";
            checked($bydevvnimport, 1, true);
            echo "> ";
            _e("Wait to approved", "devvn-reviews");
            echo "</label></td>\r\n                </tr>\r\n                <tr>\r\n                    <td class=\"first\"><label for=\"phone\">";
            _e("Phone", "devvn-reviews");
            echo "</label></td>\r\n                    <td><input type=\"text\" name=\"phone\" value=\"";
            echo $phone;
            echo "\"></td>\r\n                </tr>\r\n                <tr>\r\n                    <td class=\"first\"><label for=\"devvn_like_cmt\">";
            _e("Like count", "devvn-reviews");
            echo "</label></td>\r\n                    <td><input type=\"number\" name=\"devvn_like_cmt\" value=\"";
            echo $devvn_like_cmt;
            echo "\"></td>\r\n                </tr>\r\n                <tr>\r\n                    <td class=\"first\" style=\" vertical-align: text-bottom; \"><label for=\"attachment_img\">";
            _e("Review images", "devvn-reviews");
            echo "</label></td>\r\n                    <td>\r\n                        <div class=\"devvn_gm_wrap\">\r\n                            <a href=\"#\" class=\"button devvn_gm_addimages\" data-choose=\"";
            _e("Add Images", "devvn-reviews");
            echo "\" data-update=\"";
            _e("Add Images", "devvn-reviews");
            echo "\" data-edit=\"";
            _e("Edit image", "devvn-reviews");
            echo "\" data-delete=\"";
            _e("Remove", "devvn-reviews");
            echo "\" data-text=\"";
            _e("Delete", "devvn-reviews");
            echo "\">";
            _e("Add gallery images", "devvn-reviews");
            echo "</a>\r\n                            <div class=\"devvn_gm_box\">\r\n                                <ul class=\"devvn_gm_images\">\r\n                                    ";
            foreach ($attachment_img as $img) {
                echo "                                    <li class=\"image\" data-attachment_id=\"";
                echo $img;
                echo "\">\r\n                                        <div class=\"li_img_box\">\r\n                                            ";
                echo wp_get_attachment_image($img, "thumbnail");
                echo "                                            <ul class=\"actions\">\r\n                                                <li><a href=\"#\" class=\"gm_delete\" title=\"Remove\"><span class=\"dashicons dashicons-no-alt\"></span></a></li>\r\n                                            </ul>\r\n                                        </div>\r\n                                    </li>\r\n                                    ";
            }
            echo "                                </ul>\r\n                                <input type=\"hidden\" id=\"attachment_img\" name=\"attachment_img\" value=\"";
            echo implode(",", $attachment_img);
            echo "\">\r\n                            </div>\r\n                        </div>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td class=\"first\"><label for=\"devvn_like_cmt\">";
            _e("Quick review tags", "devvn-reviews");
            echo "</label></td>\r\n                    <td>\r\n                        <textarea name=\"quick_tag\">";
            echo implode(PHP_EOL, array_map("esc_attr", $quick_tags));
            echo "</textarea>\r\n                    </td>\r\n                </tr>\r\n                </tbody>\r\n            </table>\r\n            ";
        }
        public function cmt_register_meta_boxes_save($data)
        {
            if (!isset($_POST["devvn_cmt_save_data_nonce"]) || !wp_verify_nonce(wp_unslash($_POST["devvn_cmt_save_data_nonce"]), "devvn_cmt_save_data")) {
                return $data;
            }
            $comment_id = $data["comment_ID"];
            if (isset($_POST["bydevvnimport"]) && $_POST["bydevvnimport"] == 1) {
                $this->clear_review_schedule($comment_id);
                $time = strtotime(get_gmt_from_date($data["comment_date"]) . " GMT");
                if (time() < $time && isset($data["comment_status"]) && $data["comment_status"] == 0) {
                    wp_schedule_single_event($time, "devvn_approved_reviews_schedule", [(int) $comment_id]);
                }
                update_comment_meta($comment_id, "bydevvnimport", intval(wp_unslash($_POST["bydevvnimport"])));
            } else {
                $this->clear_review_schedule($comment_id);
                delete_comment_meta($comment_id, "bydevvnimport");
            }
            if (isset($_POST["phone"])) {
                update_comment_meta($comment_id, "phone", wp_unslash($_POST["phone"]));
            }
            if (isset($_POST["devvn_like_cmt"])) {
                update_comment_meta($comment_id, "devvn_like_cmt", intval($_POST["devvn_like_cmt"]));
            }
            if (isset($_POST["attachment_img"]) && $_POST["attachment_img"]) {
                update_comment_meta($comment_id, "attachment_img", explode(",", $_POST["attachment_img"]));
            } else {
                delete_comment_meta($comment_id, "attachment_img");
            }
            if (isset($_POST["quick_tag"]) && $_POST["quick_tag"]) {
                update_comment_meta($comment_id, "quick_tag", explode("\n", str_replace("\r", "", $_POST["quick_tag"])));
            } else {
                delete_comment_meta($comment_id, "quick_tag");
            }
            return $data;
        }
        public function devvn_approved_reviews_schedule_func($comment_id)
        {
            if (!$this->set_review_schedule($comment_id)) {
                wp_set_comment_status($comment_id, "approve");
            }
        }
        public function devvn_approved_reviews_changestatus_func($comment_id, $comment_status)
        {
            if (in_array($comment_status, ["approve", "1"])) {
                $this->clear_review_schedule($comment_id);
            } else {
                $this->set_review_schedule($comment_id);
            }
        }
        public function clear_review_schedule($comment_id)
        {
            wp_clear_scheduled_hook("devvn_approved_reviews_schedule", [(int) $comment_id]);
        }
        public function set_review_schedule($comment_id)
        {
            $comment = get_comment($comment_id);
            if ($comment && !is_wp_error($comment)) {
                $bydevvnimport = get_comment_meta($comment_id, "bydevvnimport", true);
                if ("0" == $comment->comment_approved && $bydevvnimport == 1) {
                    $time = strtotime(get_gmt_from_date($comment->comment_date) . " GMT");
                    if (time() < $time) {
                        wp_clear_scheduled_hook("devvn_approved_reviews_schedule", [(int) $comment_id]);
                        wp_schedule_single_event($time, "devvn_approved_reviews_schedule", [(int) $comment_id]);
                        return true;
                    }
                }
            }
            wp_clear_scheduled_hook("devvn_approved_reviews_schedule", [(int) $comment_id]);
            return false;
        }
        public function load_textdomain()
        {
            $locale = determine_locale();
            $locale = apply_filters("plugin_locale", $locale, "devvn-reviews");
            unload_textdomain("devvn-reviews");
            load_textdomain("devvn-reviews", WP_LANG_DIR . "/plugins/devvn-reviews-" . $locale . ".mo");
            load_plugin_textdomain("devvn-reviews", false, plugin_basename(dirname(__FILE__)) . "/languages");
        }
        public function body_classs($classes)
        {
            if (function_exists("flatsome_setup")) {
                return array_merge($classes, ["theme-flatsome"]);
            }
            return $classes;
        }
        public function get_options()
        {
            return wp_parse_args(get_option($this->_optionName), $this->_defaultOptions);
        }
        public function admin_menu()
        {
            if (class_exists("WooCommerce")) {
                add_submenu_page("woocommerce", __("DevVN Reviews", "devvn-reviews"), __("DevVN Reviews", "devvn-reviews"), "manage_woocommerce", "devvn-woocommerce-reviews", [$this, "devvn_reviews_setting"]);
            } else {
                add_options_page(__("DevVN Reviews", "devvn-reviews"), __("DevVN Reviews", "devvn-reviews"), "manage_options", "devvn-woocommerce-reviews", [$this, "devvn_reviews_setting"]);
            }
        }
        public function register_mysettings()
        {
            register_setting($this->_optionGroup, $this->_optionName);
            foreach ($this->_schemaOptionName as $name => $options) {
                $group = isset($options["group"]) ? $options["group"] : "";
                $args = isset($options["args"]) && is_array($options["args"]) ? $options["args"] : [];
                if ($group) {
                    register_setting($group, $this->_schemaOptionNamePrefix . $name, $args);
                }
            }
            foreach ($this->_autoReviewsOptionName as $name => $options) {
                $group = isset($options["group"]) ? $options["group"] : "";
                $args = isset($options["args"]) && is_array($options["args"]) ? $options["args"] : [];
                if ($group) {
                    register_setting($group, $this->_autoReviewsOptionNamePrefix . $name, $args);
                }
            }
        }
        public function define_constants()
        {
            if (!defined("DEVVN_REVIEWS_URL")) {
                define("DEVVN_REVIEWS_URL", plugin_dir_url(__FILE__));
            }
            if (!defined("DEVVN_REVIEWS_PLUGIN_DIR")) {
                define("DEVVN_REVIEWS_PLUGIN_DIR", plugin_dir_path(__FILE__));
            }
        }
        public function devvn_reviews_setting()
        {
            require_once DEVVN_REVIEWS_PLUGIN_DIR . "includes/reviews-setting.php";
        }
        public function add_action_links($links, $file)
        {
            if (strpos($file, "devvn-woocommerce-reviews.php") !== false) {
                $settings_link = "<a href=\"" . admin_url("admin.php?page=devvn-woocommerce-reviews") . "\" title=\"" . __("Cài đặt", "devvn-reviews") . "\">" . __("Cài đặt", "devvn-reviews") . "</a>";
                array_unshift($links, $settings_link);
            }
            return $links;
        }
        public function devvn_comments_template_loader($template)
        {
//             if (!reviews_check_license() || get_post_type() !== "product") {
//                 return $template;
//             }
            $check_dirs = [trailingslashit(get_stylesheet_directory()) . "/devvn-reviews/", trailingslashit(plugin_dir_path(__FILE__)) . "templates/"];
            foreach ($check_dirs as $dir) {
                if (file_exists(trailingslashit($dir) . "single-product-reviews.php")) {
                    return trailingslashit($dir) . "single-product-reviews.php";
                }
            }
        }
        public function devvn_get_product_reviews_by_rating($product_id, $rating = 0)
        {
            $args = ["post_id" => $product_id, "type" => "review", "status" => "approve", "parent" => 0];
            if (0 < $rating) {
                $args["meta_query"] = [["key" => "rating", "value" => $rating]];
            }
            $comment_query = new WP_Comment_Query();
            $comments = $comment_query->query($args);
            return $comments;
        }
        public function count_agian_review_count($comment_ID, $comment_approved, $commentdata)
        {
            global $devvn_review_settings;
            $post_id = $commentdata["comment_post_ID"];
            if ("product" === get_post_type($post_id)) {
                $product = wc_get_product($post_id);
                $product->set_rating_counts($this->devvn_get_rating_counts_for_product($product));
                $product->set_average_rating($this->devvn_get_average_rating_for_product($product));
                $product->set_review_count($this->devvn_get_review_count_for_product($product));
                $product->save();
                if (isset($_FILES["attach"]) && !$devvn_review_settings["disable_upload"]) {
                    $attachment_img = [];
                    $devvn_files = $_FILES["attach"];
                    $stt = 1;
                    foreach ($devvn_files["tmp_name"] as $key => $tmp_name) {
                        if (0 < $devvn_files["size"][$key] && $devvn_files["error"][$key] == 0 && $stt <= $devvn_review_settings["number_img_upload"]) {
                            $file = ["name" => $devvn_files["name"][$key], "type" => $devvn_files["type"][$key], "tmp_name" => $devvn_files["tmp_name"][$key], "error" => $devvn_files["error"][$key], "size" => $devvn_files["size"][$key]];
                            $_FILES = ["cmt_file_upload" => $file];
                            add_filter("upload_dir", [$this, "set_upload_dir"]);
                            add_filter("intermediate_image_sizes_advanced", [$this, "remove_default_image_sizes"]);
                            foreach ($_FILES as $fileHandler => $array) {
                                $attachId = $this->devvn_cmt_insertAttachment($fileHandler, $post_id);
                            }
                            remove_filter("upload_dir", [$this, "set_upload_dir"]);
                            remove_filter("intermediate_image_sizes_advanced", [$this, "remove_default_image_sizes"]);
                            if (is_numeric($attachId)) {
                                $attachment_img[] = $attachId;
                            }
                            $stt++;
                        }
                    }
                    unset($_FILES);
                    if ($attachment_img) {
                        add_comment_meta($comment_ID, "attachment_img", $attachment_img);
                    }
                }
                if (isset($_POST["quick_tag"])) {
                    $quick_tags = array_map("esc_attr", $_POST["quick_tag"]);
                    add_comment_meta($comment_ID, "quick_tag", $quick_tags);
                }
            }
        }
        public function devvn_cmt_insertAttachment($fileHandler, $postId)
        {
            require_once ABSPATH . "wp-admin" . "/includes/image.php";
            require_once ABSPATH . "wp-admin" . "/includes/file.php";
            require_once ABSPATH . "wp-admin" . "/includes/media.php";
            return media_handle_upload($fileHandler, $postId);
        }
        public function devvn_update_comment_type($comment_data)
        {
            global $devvn_review_settings;
            $hidden_phone_reviews = isset($devvn_review_settings["hidden_phone_reviews"]) ? intval($devvn_review_settings["hidden_phone_reviews"]) : 2;
//             if ("product" === get_post_type(absint($_POST["comment_post_ID"])) && !$this->check_license()) {
//                 wp_die(sprintf(__("<strong>Error:</strong> Add plugin license to review", "devvn-reviews")));
//             }
            if (isset($_POST["comment_post_ID"]) && isset($comment_data["comment_type"]) && "product" === get_post_type(absint($_POST["comment_post_ID"])) && isset($_POST["comment_parent"]) && $_POST["comment_parent"] != 0) {
                $_POST["rating"] = 0;
            }
            if (!is_admin() && !is_user_logged_in() && isset($_POST["comment_post_ID"]) && isset($comment_data["comment_type"]) && "product" === get_post_type(absint($_POST["comment_post_ID"]))) {
                if ($hidden_phone_reviews == 2) {
                    if (!isset($_POST["phone"])) {
                        wp_die(__("Error: Phone number is required!", "devvn-reviews"));
                    }
                    $phone = $_POST["phone"];
                    if (!preg_match("/^0([0-9]{9,10})+\$/D", $phone) && apply_filters("devvn_woo_reviews_phone_format_vn", true)) {
                        wp_die(__("Error: The phone number is not in the correct format!", "devvn-reviews"));
                    }
                }
                if ($comment_data["comment_author"] == "") {
                    wp_die(__("Error: Your name is required!", "devvn-reviews"));
                }
            }
            if (is_admin() && isset($_POST["comment_post_ID"]) && isset($comment_data["comment_type"]) && "product" === get_post_type(absint($_POST["comment_post_ID"])) && isset($comment_data["comment_parent"]) && $comment_data["comment_parent"]) {
                $comment = get_comment($comment_data["comment_parent"]);
                if ("review" == $comment->comment_type) {
                    $comment_data["comment_type"] = "review";
                }
                if ("tcomment" == $comment->comment_type) {
                    $comment_data["comment_type"] = "tcomment";
                }
            }
            $minimalCommentLength = $devvn_review_settings["cmt_length"];
            if (strlen(trim(remove_accents($comment_data["comment_content"]))) < $minimalCommentLength && $comment_data["comment_type"] == "review") {
                wp_die(sprintf(__("Nội dung đánh giá phải tối thiểu %s ký tự.", "devvn-reviews"), $minimalCommentLength));
            }
            if (isset($_FILES["attach"]) && !$devvn_review_settings["disable_upload"]) {
                foreach ($_FILES["attach"]["tmp_name"] as $key => $tmp_name) {
                    if (0 < $_FILES["attach"]["size"][$key] && $_FILES["attach"]["error"][$key] == 0) {
//                         if (!$this->check_license()) {
//                             wp_die(sprintf(__("<strong>Error:</strong> Add plugin license to review", "devvn-reviews")));
//                         }
                        $fileInfo = pathinfo($_FILES["attach"]["name"][$key]);
                        $fileExtension = strtolower($fileInfo["extension"]);
                        if (function_exists("finfo_file")) {
                            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["attach"]["tmp_name"][$key]);
                        } else {
                            if (function_exists("mime_content_type")) {
                                $fileType = mime_content_type($_FILES["attach"]["tmp_name"][$key]);
                            } else {
                                $fileType = $_FILES["attach"]["type"][$key];
                            }
                        }
                        if (!in_array($fileType, $this->devvn_getImageMimeTypes()) || !in_array($fileExtension, $this->devvn_getAllowedFileExtensions())) {
                            wp_die(sprintf(__("<strong>Error:</strong> The image is not in the correct format", "devvn-reviews")));
                        }
                        if ($devvn_review_settings["img_size"] < $_FILES["attach"]["size"][$key]) {
                            wp_die(sprintf(__("<strong>Error:</strong> The image is too big. Only images allowed to be loaded <= %s", "devvn-reviews"), $this->formatSizeUnits($devvn_review_settings["img_size"])));
                        }
                    } else {
                        if ($_FILES["attach"]["error"][$key] == 1) {
                            wp_die(__("<strong>ERROR:</strong> The uploaded file exceeds the upload_max_filesize directive in php.ini.", "devvn-reviews"));
                        } else {
                            if ($_FILES["attach"]["error"][$key] == 2) {
                                wp_die(__("<strong>ERROR:</strong> The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.", "devvn-reviews"));
                            } else {
                                if ($_FILES["attach"]["error"][$key] == 3) {
                                    wp_die(__("<strong>ERROR:</strong> The uploaded file was only partially uploaded. Please try again later.", "devvn-reviews"));
                                } else {
                                    if ($_FILES["attach"]["error"][$key] == 6) {
                                        wp_die(__("<strong>ERROR:</strong> Missing a temporary folder.", "devvn-reviews"));
                                    } else {
                                        if ($_FILES["attach"]["error"][$key] == 7) {
                                            wp_die(__("<strong>ERROR:</strong> Failed to write file to disk.", "devvn-reviews"));
                                        } else {
                                            if ($_FILES["attach"]["error"][$key] == 7) {
                                                wp_die(__("<strong>ERROR:</strong> A PHP extension stopped the file upload.", "devvn-reviews"));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            do_action("devvn_reviews_preprocess_comment", $comment_data, $devvn_review_settings);
            return $comment_data;
        }
        public function formatSizeUnits($bytes)
        {
            if (1073741824 <= $bytes) {
                $bytes = number_format($bytes / 1073741824, 0) . " GB";
            } else {
                if (1048576 <= $bytes) {
                    $bytes = number_format($bytes / 1048576, 0) . " MB";
                } else {
                    if (1024 <= $bytes) {
                        $bytes = number_format($bytes / 1024, 0) . " KB";
                    } else {
                        if (1 < $bytes) {
                            $bytes = $bytes . " bytes";
                        } else {
                            if ($bytes == 1) {
                                $bytes = $bytes . " byte";
                            } else {
                                $bytes = "0 bytes";
                            }
                        }
                    }
                }
            }
            return $bytes;
        }
        public function devvn_getImageMimeTypes()
        {
            return apply_filters("devvn_reviews_image_mime_types", ["image/jpeg", "image/jpg", "image/jp_", "application/jpg", "application/x-jpg", "image/pjpeg", "image/pipeg", "image/vnd.swiftview-jpeg", "image/x-xbitmap", "image/gif", "image/x-xbitmap", "image/gi_", "image/png", "application/png", "application/x-png"]);
        }
        public function devvn_getAllowedFileExtensions()
        {
            return apply_filters("devvn_reviews_file_extensions", ["jpg", "gif", "png", "jpeg"]);
        }
        public function devvn_comments_template_query_args($comment_args)
        {
            if ("product" === get_post_type(absint($comment_args["post_id"]))) {
                $comment_args["order"] = "DESC";
                $comment_args["type"] = "review";
            }
            if (in_array(get_post_type(absint($comment_args["post_id"])), $this->get_post_comment_template())) {
                $comment_args["order"] = "DESC";
            }
            return $comment_args;
        }
        public function devvn_delete_img_after_delete_cmt($meta_id, $object_id, $meta_key, $meta_value)
        {
            if ($meta_key == "attachment_img" && $meta_value && is_array($meta_value)) {
                foreach ($meta_value as $item) {
                    wp_delete_attachment($item, true);
                }
            }
        }
        public function save_comment_meta_data($comment_id)
        {
            if (isset($_POST["phone"]) && $_POST["phone"] != "") {
                $phone = wp_filter_nohtml_kses($_POST["phone"]);
                if ($phone) {
                    add_comment_meta($comment_id, "phone", $phone);
                }
            }
        }
        public function get_quick_tag($commentID)
        {
            $quick_tag = get_comment_meta($commentID, "quick_tag", true);
            if ($quick_tag && !empty($quick_tag)) {
                ob_start();
                echo "                <div class=\"review_quick_tag\">\r\n                    ";
                foreach ($quick_tag as $item) {
                    echo "<span>";
                    echo $item;
                    echo "</span>";
                }
                echo "                </div>\r\n                ";
                return ob_get_clean();
            } else {
                return false;
            }
        }
        public function modify_comment($text, $comment)
        {
            $commentphone = get_comment_meta(get_comment_ID(), "phone", true);
            if ($commentphone && is_admin()) {
                $commentphone = "<br/>SĐT: <strong>" . esc_attr($commentphone) . "</strong>";
                $text = $text . $commentphone;
            }
            if ($comment->comment_type != "review") {
                return $text;
            }
            $quick_tag = $this->get_quick_tag(get_comment_ID());
            if ($quick_tag) {
                $text = $text . $quick_tag;
            }
            $attachment_img = get_comment_meta(get_comment_ID(), "attachment_img", true);
            if ($attachment_img && is_admin()) {
                $img_text = "";
                foreach ($attachment_img as $img) {
                    $img_text .= "<a class=\"review_img\" href=\"" . get_edit_post_link($img) . "\" title=\"\" target=\"_blank\">" . wp_get_attachment_image($img, "thumbnail") . "</a>";
                }
                $text = $text . "<br/>" . $img_text;
            }
            $is_woo = false;
            $is_parent = $comment->comment_parent;
            $comment_type = $comment->comment_type;
            $comment_post_ID = $comment->comment_post_ID;
            if (get_post_type($comment_post_ID) == "product") {
                $is_woo = true;
            }
            $verified = get_comment_meta($comment->comment_ID, "verified", 1);
            if (is_admin() && $is_woo && !$is_parent && $comment_type == "review") {
                $nonce = wp_create_nonce("admin_fake_label");
                if (!$verified) {
                    $text = $text . "<br>" . "<a href=\"javascript:void(0)\" class=\"admin_fake_label\" data-id=\"" . $comment->comment_ID . "\" data-nonce=\"" . $nonce . "\">" . __("Fake đã mua hàng", "devvn-reviews") . "</a>";
                } else {
                    $text = $text . "<br>" . __("Purchased", "devvn-reviews");
                }
            }
            return $text;
        }
        public function devvn_clear_transients_count_review($post_id)
        {
            if ("product" === get_post_type($post_id)) {
                $product = wc_get_product($post_id);
                $product->set_rating_counts($this->devvn_get_rating_counts_for_product($product));
                $product->set_average_rating($this->devvn_get_average_rating_for_product($product));
                $product->set_review_count($this->devvn_get_review_count_for_product($product));
                $product->save();
            }
        }
        public function devvn_get_rating_counts_for_product(&$product)
        {
            global $wpdb;
            $counts = [];
            $raw_counts = $wpdb->get_results($wpdb->prepare("\r\n\t\t\tSELECT meta_value, COUNT( * ) as meta_value_count FROM " . $wpdb->commentmeta . "\r\n\t\t\tLEFT JOIN " . $wpdb->comments . " ON " . $wpdb->commentmeta . ".comment_id = " . $wpdb->comments . ".comment_ID\r\n\t\t\tWHERE meta_key = 'rating'\r\n\t\t\tAND comment_post_ID = %d\r\n\t\t\tAND comment_type = 'review'\r\n\t\t\tAND comment_approved = '1'\r\n\t\t\tAND meta_value > 0\r\n\t\t\tGROUP BY meta_value\r\n\t\t\t\t", $product->get_id()));
            foreach ($raw_counts as $count) {
                $counts[$count->meta_value] = absint($count->meta_value_count);
            }
            return $counts;
        }
        public function devvn_get_average_rating_for_product(&$product)
        {
            global $wpdb;
            $count = $product->get_rating_count();
            if ($count) {
                $ratings = $wpdb->get_var($wpdb->prepare("\r\n\t\t\t\tSELECT SUM(meta_value) FROM " . $wpdb->commentmeta . "\r\n\t\t\t\tLEFT JOIN " . $wpdb->comments . " ON " . $wpdb->commentmeta . ".comment_id = " . $wpdb->comments . ".comment_ID\r\n\t\t\t\tWHERE meta_key = 'rating'\r\n\t\t\t\tAND comment_post_ID = %d\r\n\t\t\t    AND comment_type = 'review'\r\n\t\t\t\tAND comment_approved = '1'\r\n\t\t\t\tAND meta_value > 0\r\n\t\t\t\t\t", $product->get_id()));
                $average = number_format($ratings / $count, 2, ".", "");
            } else {
                $average = 0;
            }
            return $average;
        }
        public function devvn_get_review_count_for_product(&$product)
        {
            global $wpdb;
            $count = $wpdb->get_var($wpdb->prepare("\r\n\t\t\tSELECT COUNT(*) FROM " . $wpdb->comments . "\r\n\t\t\tWHERE comment_parent = 0\r\n\t\t\tAND comment_post_ID = %d\r\n\t\t\tAND comment_type = 'review'\r\n\t\t\tAND comment_approved = '1'\r\n\t\t\t\t", $product->get_id()));
            return $count;
        }
        public function devvn_comment_post_redirect($location, $comment)
        {
            $location = get_permalink($comment->comment_post_ID) . "#reviews";
            return $location;
        }
        public function devvn_attach_view($comment)
        {

            $attachment_img = get_comment_meta($comment->comment_ID, "attachment_img", true);
            if ($attachment_img && is_array($attachment_img)) {
                echo "                <ul class=\"cmt_attachment_img\">\r\n                    ";
                foreach ($attachment_img as $item) {
                    echo "                        <li><a href=\"";
                    echo wp_get_attachment_image_url($item, "full");
                    echo "\">";
                    echo wp_get_attachment_image($item, "woocommerce_gallery_thumbnail");
                    echo "</a></li>\r\n                    ";
                }
                echo "                </ul>\r\n                ";
            }
        }
        public function devvn_cmt_submit_func()
        {
            global $devvn_review_settings;
            if (!isset($_POST["cmt_data"])) {
                wp_send_json_error("Lỗi dữ liệu");
            }
            parse_str($_POST["cmt_data"], $cmt_data);
            $devvn_cmt_name = isset($cmt_data["devvn_cmt_name"]) ? wc_clean($cmt_data["devvn_cmt_name"]) : "";
            if (!$devvn_cmt_name) {
                $devvn_cmt_name = isset($cmt_data["devvn_cmt_replyname"]) ? wc_clean($cmt_data["devvn_cmt_replyname"]) : "";
            }
            $content = isset($_POST["content"]) ? sanitize_textarea_field($_POST["content"]) : "";
            $gender = isset($_POST["gender"]) ? wc_clean($_POST["gender"]) : "";
            $name = isset($_POST["name"]) ? wc_clean($_POST["name"]) : "";
            $phone = isset($_POST["phone"]) && $_POST["phone"] ? sanitize_text_field($_POST["phone"]) : "";
            $email = isset($_POST["email"]) && $_POST["email"] ? sanitize_email($_POST["email"]) : "";
            $cmt_parent_id = isset($cmt_data["cmt_parent_id"]) && $cmt_data["cmt_parent_id"] ? intval($cmt_data["cmt_parent_id"]) : 0;
            $post_ID = isset($cmt_data["post_id"]) ? intval($cmt_data["post_id"]) : "";
            if ($email && !is_email($email)) {
                wp_send_json_error("Lỗi định dạng email");
            }
            if (!is_user_logged_in()) {
                if (!in_array($gender, ["male", "female"])) {
                    wp_send_json_error("Lỗi giới tính");
                }
                if ($devvn_cmt_name != $name) {
                    wp_send_json_error("Lỗi đầu vào");
                }
                if ($devvn_review_settings["tcmt_phone"] == 1 && !$phone) {
                    wp_send_json_error(__("Your phone is required!", "devvn-reviews"));
                }
            }
            $products = wc_get_product($post_ID);
            if (!$products || is_wp_error($products)) {
                wp_send_json_error("Sản phẩm không tồn tại");
            }
            $current_user = wp_get_current_user();
            $commentdata = ["comment_post_ID" => $products->get_id(), "comment_content" => $content, "comment_type" => "tcomment", "comment_parent" => $cmt_parent_id, "user_id" => $current_user->ID];
            if (!$current_user->ID) {
                $commentdata["comment_author"] = $name;
                $commentdata["comment_author_email"] = $email;
                $commentdata["comment_author_url"] = "";
            } else {
                $commentdata["comment_author"] = $current_user->display_name;
                $commentdata["comment_author_email"] = $current_user->user_email;
                $commentdata["comment_author_url"] = "";
            }
            $comment_id = wp_new_comment($commentdata);
            if ($comment_id) {
                add_comment_meta($comment_id, "gender", $gender);
                if ($phone) {
                    add_comment_meta($comment_id, "phone", $phone);
                }
                $this->devvn_delete_transient_tcomment($comment_id);
                if ("approved" == wp_get_comment_status($comment_id)) {
                    $devvn_cmt = $this->query_all_tcomment($products);
                    $output = ["result" => true, "messages" => __("Comment successfully!", "devvn-reviews")];
                    if (1 < count($devvn_cmt)) {
                        $devvn_cmt_count = $this->get_tcomment_count($products);
                        $devvn_cmt_list_box = $this->get_list_tcomment($devvn_cmt, $products);
                        $output["fragments"] = [".devvn_cmt_count" => $devvn_cmt_count, ".devvn_cmt_list_box" => $devvn_cmt_list_box, ".devvn_cmt_paged" => $this->get_paginate_tcomment(1, $products)];
                    } else {
                        $output["fragments"] = [".devvn_cmt_list" => $this->devvn_list_all_tcomment($products)];
                    }
                    wp_send_json_success($output);
                } else {
                    $output = ["result" => false, "messages" => __("Sent comment successfully. Pending approval!", "devvn-reviews")];
                    wp_send_json_success($output);
                }
            } else {
                wp_send_json_error(__("Comment failed to post", "devvn-reviews"));
            }
            exit;
        }
        public function devvn_delete_transient_tcomment($comment_id)
        {
            $comment = get_comment($comment_id);
            if (in_array($comment->comment_type, ["tcomment", "review"])) {
                global $wpdb;
                $menus = $wpdb->get_col("SELECT option_name FROM " . $wpdb->prefix . "options WHERE option_name LIKE \"_transient_devvn_add_cmt_%\" OR option_name LIKE \"_transient_devvn_shortcode_reviews_%\" ");
                foreach ($menus as $menu) {
                    $key = str_replace("_transient_", "", $menu);
                    delete_transient($key);
                }
                wp_cache_flush();
            }
        }
        public function devvn_first_letter($string)
        {
            $words = preg_split("/[\\s,_-]+/", remove_accents($string));
            $words = array_slice($words, -2);
            $acronym = "";
            foreach ($words as $w) {
                $acronym .= strtoupper($w[0]);
            }
            return $acronym;
        }
        public function query_count_all_tcomment($product, $args = [])
        {
            global $wpdb;
            $search = "";
            if (isset($args["search"])) {
                $search = wc_clean($args["search"]);
            }
            $user_count = $wpdb->get_var("\r\n                            SELECT COUNT(*) \r\n                            FROM " . $wpdb->comments . " \r\n                            WHERE comment_post_ID = " . $product->get_id() . "\r\n                            AND comment_type = 'tcomment'\r\n                            AND comment_parent = 0\r\n                            AND comment_approved = 1\r\n                            AND comment_content LIKE '%" . $search . "%'\r\n                        ");
            return $user_count;
        }
        public function query_count_all_tcomment_noparent($product, $args = [])
        {
            global $wpdb;
            $search = "";
            if (isset($args["search"])) {
                $search = wc_clean($args["search"]);
            }
            $user_count = $wpdb->get_var("\r\n                            SELECT COUNT(*) \r\n                            FROM " . $wpdb->comments . " \r\n                            WHERE comment_post_ID = " . $product->get_id() . "\r\n                            AND comment_type = 'tcomment'\r\n                            AND comment_parent = 0\r\n                            AND comment_approved = 1\r\n                            AND comment_content LIKE '%" . $search . "%'\r\n                        ");
            return $user_count;
        }
        public function query_all_tcomment($product, $paged = 1)
        {
            global $devvn_review_settings;
            $number = $devvn_review_settings["tcmt_number"];
            if (false === ($devvn_cmt = get_transient("devvn_add_cmt_list_tcomment_p" . $product->get_id() . "_page_" . $paged . "_number_" . $number))) {
                $args = ["type" => "tcomment", "status" => "approve", "post_id" => $product->get_id(), "parent" => 0, "number" => $number, "paged" => $paged];
                $comment_query = new WP_Comment_Query();
                $devvn_cmt = $comment_query->query($args);
                if ($devvn_cmt) {
                    set_transient("devvn_add_cmt_list_tcomment_p" . $product->get_id() . "_page_" . $paged . "_number_" . $number, $devvn_cmt);
                }
            }
            return $devvn_cmt;
        }
        public function get_tcomment_count($product, $args = [])
        {
            $total_comment = $this->query_count_all_tcomment($product, $args);
            return sprintf(_n("%s Comment", "%s Comments", $total_comment, "devvn-reviews"), esc_html($total_comment));
        }
        public function check_faq_has_child($devvn_cmt, $product)
        {
            $out = false;
            foreach ($devvn_cmt as $cmt) {
                $comment_ID = isset($cmt->comment_ID) ? $cmt->comment_ID : "";
                if (false === ($out = get_transient("devvn_add_cmt_listchild_haschild_tcomment_" . $product->get_id()))) {
                    $args = ["type" => "tcomment", "status" => "approve", "post_id" => $product->get_id(), "parent" => $comment_ID, "order" => "ASC"];
                    $comment_query = new WP_Comment_Query();
                    $devvn_cmt_child = $comment_query->query($args);
                    if ($devvn_cmt_child) {
                        $out = true;
                        set_transient("devvn_add_cmt_listchild_haschild_tcomment_" . $product->get_id(), $out);
                        return $out;
                    }
                }
            }
        }
        public function get_child_cmt($prod, $comment_parent)
        {
            $args = ["type" => "tcomment", "status" => "approve", "post_id" => $prod->get_id(), "parent" => $comment_parent, "order" => "ASC"];
            $comment_query = new WP_Comment_Query();
            $devvn_cmt_child = $comment_query->query($args);
            if ($devvn_cmt_child && !empty($devvn_cmt_child) && !is_wp_error($devvn_cmt_child)) {
                foreach ($devvn_cmt_child as $cmt) {
                    $devvn_cmt_child = array_merge($devvn_cmt_child, (array) $this->get_child_cmt($prod, $cmt->comment_ID));
                }
                return $devvn_cmt_child;
            }
        }
        public function sort_array_by_object_date_value($a, $b)
        {
            $t1 = strtotime($a->comment_date_gmt);
            $t2 = strtotime($b->comment_date_gmt);
            return $t1 - $t2;
        }
        public function get_list_tcomment($devvn_cmt, $product, $paged = 1)
        {
            global $devvn_review_settings;
            $per_page = $devvn_review_settings["tcmt_number"];
            if ($devvn_review_settings["enable_qapage"] == 1) {
                $enable_QAPage = true;
            } else {
                $enable_QAPage = false;
            }
            $enable_QAPage = apply_filters("devvn_reviews_enable_qapage", $enable_QAPage);
            $faq_has_child = $this->check_faq_has_child($devvn_cmt, $product);
            ob_start();
            echo "            <ul ";
            if ($enable_QAPage && $faq_has_child) {
                echo "itemscope=\"\" itemtype=\"https://schema.org/FAQPage\"";
            }
            echo ">\r\n                ";
            foreach ($devvn_cmt as $cmt) {
                $comment_ID = isset($cmt->comment_ID) ? $cmt->comment_ID : "";
                $comment_author = isset($cmt->comment_author) ? $cmt->comment_author : "";
                $comment_content = isset($cmt->comment_content) ? wpautop($cmt->comment_content) : "";
                $comment_date = isset($cmt->comment_date) ? $cmt->comment_date : "";
                $user_id = isset($cmt->user_id) ? $cmt->user_id : "";
                if (false === ($devvn_cmt_child = get_transient("devvn_add_cmt_listchild_tcomment_" . $comment_ID))) {
                    $devvn_cmt_child = (array) $this->get_child_cmt($product, $comment_ID);
                    usort($devvn_cmt_child, [$this, "sort_array_by_object_date_value"]);
                    if ($devvn_cmt_child) {
                        set_transient("devvn_add_cmt_listchild_tcomment_" . $comment_ID, $devvn_cmt_child);
                    }
                }
                echo "                    <li ";
                if ($enable_QAPage && $devvn_cmt_child) {
                    echo "itemprop=\"mainEntity\" itemscope=\"\" itemtype=\"https://schema.org/Question\"";
                }
                echo ">\r\n                        <div class=\"devvn_cmt_box\">\r\n                            ";
                if ($cmt->comment_author_email && $devvn_review_settings["enable_cmt_avatar"] == 1) {
                    echo "<span class=\"cmt_avatar\">" . get_avatar($cmt->comment_author_email, 50) . "</span>";
                } else {
                    echo "<span>" . $this->devvn_first_letter($comment_author) . "</span>";
                }
                echo "                            <strong>";
                echo apply_filters("devvn_cmt_author", $comment_author, $cmt->comment_ID);
                echo "</strong>\r\n                            <div class=\"devvn_cmt_box_content\" ";
                if ($enable_QAPage && $devvn_cmt_child) {
                    echo "itemprop=\"name\"";
                }
                echo ">";
                echo $comment_content;
                echo "</div>\r\n                            <div class=\"devvn_cmt_tool\">\r\n                                <span><a href=\"javascript:void(0)\" class=\"devvn_cmt_reply\" data-cmtid=\"";
                echo $cmt->comment_ID;
                echo "\" data-authorname=\"";
                echo esc_attr($comment_author);
                echo "\">";
                _e("Reply", "devvn-reviews");
                echo "</a></span>\r\n                                ";
                do_action("devvn_reviews_action", $cmt);
                echo "                                ";
                if ($devvn_review_settings["show_date"] == "1") {
                    echo "                                    <span> • </span>\r\n                                    <span>";
                    echo human_time_diff(strtotime($comment_date), current_time("timestamp")) . " " . __("ago", "devvn-reviews");
                    echo "</span>\r\n                                ";
                }
                echo "                                ";
                $edit_cmt_link = get_edit_comment_link($comment_ID);
                if ($edit_cmt_link) {
                    echo "                                    <span> • </span>\r\n                                    <a class=\"comment-edit-link\" href=\"";
                    echo $edit_cmt_link;
                    echo "\" data-wpel-link=\"internal\" target=\"_blank\"><span>";
                    _e("Edit", "devvn-reviews");
                    echo "</span> </a>\r\n                                ";
                }
                echo "                            </div>\r\n                        </div>\r\n                        ";
                if ($devvn_cmt_child) {
                    echo "                            <ul class=\"devvn_cmt_child\">\r\n                                ";
                    $parent_cmt_ID = $comment_ID;
                    $key = 0;
                    foreach ($devvn_cmt_child as $cmt) {
                        if (!empty($cmt)) {
                            $comment_ID = isset($cmt->comment_ID) ? $cmt->comment_ID : "";
                            $comment_author = isset($cmt->comment_author) ? $cmt->comment_author : "";
                            $comment_content = isset($cmt->comment_content) ? wpautop($cmt->comment_content) : "";
                            $comment_date = isset($cmt->comment_date) ? $cmt->comment_date : "";
                            $user_id = isset($cmt->user_id) ? $cmt->user_id : "";
                            $user_roles = [];
                            if ($user_id) {
                                $user = get_userdata($user_id);
                                if ($user && !is_wp_error($user)) {
                                    $user_roles = $user->roles;
                                }
                            }
                            $qtv = devv_check_reviews_admin($user_roles);
                            echo "                                    <li ";
                            if ($enable_QAPage) {
                                echo "itemprop=\"acceptedAnswer\" itemscope=\"\" itemtype=\"https://schema.org/Answer\"";
                            }
                            echo ">\r\n                                        <div class=\"devvn_cmt_box\">\r\n                                            <span>";
                            if ($cmt->comment_author_email && $devvn_review_settings["enable_cmt_avatar"] == 1) {
                                echo get_avatar($cmt->comment_author_email, 50);
                            } else {
                                echo $this->devvn_first_letter($comment_author);
                            }
                            echo "</span>\r\n                                            <strong>";
                            echo apply_filters("devvn_cmt_author", $comment_author, $cmt->comment_ID);
                            echo "</strong>\r\n                                            ";
                            if ($qtv && $user_roles) {
                                echo "                                                <b class=\"qtv\">";
                                _e("Administrator", "devvn-reviews");
                                echo "</b>\r\n                                            ";
                            }
                            echo "                                            <div class=\"devvn_cmt_box_content\" ";
                            if ($enable_QAPage) {
                                echo "itemprop=\"text\"";
                            }
                            echo ">";
                            echo $comment_content;
                            echo "</div>\r\n                                            <div class=\"devvn_cmt_tool\">\r\n                                                <span><a href=\"javascript:void(0)\" class=\"devvn_cmt_reply\" data-cmtid=\"";
                            echo $cmt->comment_ID;
                            echo "\" data-authorname=\"";
                            echo esc_attr($comment_author);
                            echo "\">";
                            _e("Reply", "devvn-reviews");
                            echo "</a></span>\r\n                                                ";
                            do_action("devvn_reviews_action", $cmt);
                            echo "                                                ";
                            if ($devvn_review_settings["show_date"] == "1") {
                                echo "                                                    <span> • </span>\r\n                                                    <span>";
                                echo human_time_diff(strtotime($comment_date), current_time("timestamp")) . " " . __("ago", "devvn-reviews");
                                echo "</span>\r\n                                                ";
                            }
                            echo "                                                ";
                            $edit_cmt_link = get_edit_comment_link($comment_ID);
                            if ($edit_cmt_link) {
                                echo "                                                    <span> • </span>\r\n                                                    <a class=\"comment-edit-link\" href=\"";
                                echo $edit_cmt_link;
                                echo "\" data-wpel-link=\"internal\" target=\"_blank\"><span>";
                                _e("Edit", "devvn-reviews");
                                echo "</span> </a>\r\n                                                ";
                            }
                            echo "                                            </div>\r\n                                        </div>\r\n                                    </li>\r\n                                    ";
                            $key++;
                        }
                    }
                    echo "                            </ul>\r\n                        ";
                }
                echo "                    </li>\r\n                ";
            }
            echo "            </ul>\r\n            ";
            return ob_get_clean();
        }
        public function get_paginate_tcomment($page = 1, $product = "", $args = [])
        {
            global $devvn_review_settings;
            $max_page = $this->query_count_all_tcomment_noparent($product, $args);
            $defaults = ["base" => "#tcomment-page=%#%", "format" => "", "total" => ceil($max_page / $devvn_review_settings["tcmt_number"]), "current" => $page, "echo" => true, "prev_text" => "&larr;", "next_text" => "&rarr;", "type" => "list"];
            $page_links = paginate_links($defaults);
            return $page_links;
        }
        public function devvn_list_all_tcomment($product)
        {
            global $devvn_review_settings;
            ob_start();
            $devvn_cmt = $this->query_all_tcomment($product);
            if ($devvn_cmt) {
                echo "                <div class=\"devvn_cmt_list_header\">\r\n                    <div class=\"devvn_cmt_lheader_left\">\r\n                        <span class=\"devvn_cmt_count\">";
                echo $this->get_tcomment_count($product);
                echo "</span>\r\n                    </div>\r\n                    <div class=\"devvn_cmt_lheader_right\">\r\n                        <div class=\"devvn_cmt_search_box\">\r\n                            <form action=\"\" method=\"post\" id=\"devvn_cmt_search_form\">\r\n                                <input type=\"text\" name=\"devvn_cmt_search\" id=\"devvn_cmt_search\" placeholder=\"";
                _e("Search by content", "devvn-reviews");
                echo "\"/>\r\n                                <input type=\"hidden\" value=\"";
                echo $product->get_id();
                echo "\" name=\"post_id\">\r\n                                <button type=\"submit devvn-icon-search\">";
                _e("Search", "devvn-reviews");
                echo "</button>\r\n                            </form>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <div class=\"devvn_cmt_list_box\">\r\n                    ";
                echo $this->get_list_tcomment($devvn_cmt, $product);
                echo "                </div>\r\n                <nav class=\"devvn_cmt_paged woocommerce-pagination\">\r\n                    ";
                echo $this->get_paginate_tcomment(1, $product);
                echo "                </nav>\r\n                <script type=\"text/template\" id=\"tmpl-reply-devvn-cmt\">\r\n                    <form action=\"\" method=\"post\" id=\"devvn_cmt_reply\">\r\n                        <div class=\"devvn_cmt_input\">\r\n                            <textarea placeholder=\"\" name=\"devvn_cmt_replycontent\" id=\"devvn_cmt_replycontent\" minlength=\"20\">{{{ data.authorname }}}</textarea>\r\n                        </div>\r\n                        <div class=\"devvn_cmt_form_bottom\">\r\n                            ";
                if (!is_user_logged_in()) {
                    echo "                                <div class=\"devvn_cmt_radio\">\r\n                                    <label>\r\n                                        <input name=\"devvn_cmt_replygender\" type=\"radio\" value=\"male\" checked/>\r\n                                        <span>";
                    _e("Male", "devvn-reviews");
                    echo "</span>\r\n                                    </label>\r\n                                    <label>\r\n                                        <input name=\"devvn_cmt_replygender\" type=\"radio\" value=\"female\"/>\r\n                                        <span>";
                    _e("Female", "devvn-reviews");
                    echo "</span>\r\n                                    </label>\r\n                                </div>\r\n                                <div class=\"devvn_cmt_input\">\r\n                                    <input name=\"devvn_cmt_replyname\" type=\"text\" id=\"devvn_cmt_replyname\" placeholder=\"";
                    _e("Your name (Required)", "devvn-reviews");
                    echo "\"/>\r\n                                </div>\r\n                                ";
                    if ($devvn_review_settings["tcmt_phone"] == 1) {
                        echo "                                    <div class=\"devvn_cmt_input\">\r\n                                        <input name=\"devvn_cmt_replyphone\" type=\"text\" id=\"devvn_cmt_replyphone\" placeholder=\"";
                        _e("Your phone (Required)", "devvn-reviews");
                        echo "\"/>\r\n                                    </div>\r\n                                ";
                    }
                    echo "                                ";
                    if ($devvn_review_settings["active_field_email"] == 1) {
                        echo "                                    <div class=\"devvn_cmt_input\">\r\n                                        <input name=\"devvn_cmt_replyemail\" type=\"text\" id=\"devvn_cmt_replyemail\" placeholder=\"";
                        _e("Email", "devvn-reviews");
                        echo "\"/>\r\n                                    </div>\r\n                                ";
                    }
                    echo "                            ";
                }
                echo "                            <div class=\"devvn_cmt_submit\">\r\n                                <button type=\"submit\" id=\"devvn_cmt_replysubmit\">";
                _e("Post comment", "devvn-reviews");
                echo "</button>\r\n                                <input type=\"hidden\" value=\"";
                echo $product->get_id();
                echo "\" name=\"post_id\">\r\n                                <input type=\"hidden\" value=\"{{{ data.parent_id }}}\" name=\"cmt_parent_id\">\r\n                            </div>\r\n                        </div>\r\n                        <a href=\"javascript:void(0)\" class=\"devvn_cancel_cmt\">×</a>\r\n                    </form>\r\n                </script>\r\n            ";
            } else {
                echo "                <p>";
                _e("No comments yet", "devvn-reviews");
                echo "</p>\r\n            ";
            }
            echo "            ";
            return ob_get_clean();
        }
        public function devvn_cmt_search_func()
        {
            global $devvn_review_settings;
            parse_str($_POST["formData"], $formData);
            $devvn_cmt_search = isset($formData["devvn_cmt_search"]) ? wc_clean($formData["devvn_cmt_search"]) : "";
            $post_ID = isset($formData["post_id"]) ? intval($formData["post_id"]) : "";
            $search = isset($_POST["search"]) ? wc_clean($_POST["search"]) : "";
            $paged = isset($_POST["paged"]) ? intval($_POST["paged"]) : 1;
            if ($devvn_cmt_search != $search) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            if ("product" != get_post_type($post_ID)) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            $products = wc_get_product($post_ID);
            $args = ["type" => "tcomment", "status" => "approve", "parent" => 0, "post_id" => $products->get_id(), "search" => $search, "number" => $devvn_review_settings["tcmt_number"], "paged" => $paged];
            $comment_query = new WP_Comment_Query();
            $devvn_cmt = $comment_query->query($args);
            if ($devvn_cmt) {
                $devvn_cmt_count = $this->get_tcomment_count($products, $args);
                $devvn_cmt_list_box = $this->get_list_tcomment($devvn_cmt, $products, $paged);
                $output = ["result" => true, "messages" => __("Comment successfully!", "devvn-reviews"), "fragments" => [".devvn_cmt_count" => $devvn_cmt_count, ".devvn_cmt_list_box" => $devvn_cmt_list_box, ".devvn_cmt_paged" => $this->get_paginate_tcomment($paged, $products, $args)]];
                wp_send_json_success($output);
            } else {
                $output = ["result" => true, "messages" => __("No comments yet", "devvn-reviews"), "fragments" => [".devvn_cmt_count" => $this->get_tcomment_count($products, $args), ".devvn_cmt_list_box" => "", ".devvn_cmt_paged" => ""]];
                wp_send_json_success($output);
            }
            exit;
        }
        public function devvn_cmt_load_paged_func()
        {
            global $devvn_review_settings;
            parse_str($_POST["formData"], $formData);
            $devvn_cmt_search = isset($formData["devvn_cmt_search"]) ? wc_clean($formData["devvn_cmt_search"]) : "";
            $post_ID = isset($formData["post_id"]) ? intval($formData["post_id"]) : "";
            $paged = isset($_POST["paged"]) ? intval($_POST["paged"]) : "1";
            $search = isset($_POST["search"]) ? wc_clean($_POST["search"]) : "";
            if ($devvn_cmt_search != $search) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            if ("product" != get_post_type($post_ID)) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            $products = wc_get_product($post_ID);
            $args = ["type" => "tcomment", "status" => "approve", "parent" => 0, "post_id" => $products->get_id(), "number" => $devvn_review_settings["tcmt_number"], "paged" => $paged];
            if ($search) {
                $args["search"] = $search;
            }
            $comment_query = new WP_Comment_Query();
            $devvn_cmt = $comment_query->query($args);
            if ($devvn_cmt) {
                $devvn_cmt_count = $this->get_tcomment_count($products, $args);
                $devvn_cmt_list_box = $this->get_list_tcomment($devvn_cmt, $products);
                $output = ["result" => true, "messages" => __("Comment successfully!", "devvn-reviews"), "fragments" => [".devvn_cmt_count" => $devvn_cmt_count, ".devvn_cmt_list_box" => $devvn_cmt_list_box, ".devvn_cmt_paged" => $this->get_paginate_tcomment($paged, $products, $args)]];
                wp_send_json_success($output);
            } else {
                $output = ["result" => true, "messages" => __("No comments yet", "devvn-reviews"), "fragments" => [".devvn_cmt_count" => $this->get_tcomment_count($products, $args), ".devvn_cmt_list_box" => "", ".devvn_cmt_paged" => ""]];
                wp_send_json_success($output);
            }
            exit;
        }
        public function devvn_cmt_enqueue_style()
        {
            global $devvn_review_settings;
            $array = ["ajax_url" => admin_url("admin-ajax.php"), "img_size" => $devvn_review_settings["img_size"], "img_size_text" => $this->formatSizeUnits($devvn_review_settings["img_size"]), "cmt_length" => $devvn_review_settings["cmt_length"], "number_img_upload" => $devvn_review_settings["number_img_upload"], "star_1" => esc_html__("Very poor", "devvn-reviews"), "star_2" => esc_html__("Not that bad", "devvn-reviews"), "star_3" => esc_html__("Average", "devvn-reviews"), "star_4" => esc_html__("Good", "devvn-reviews"), "star_5" => esc_html__("Perfect", "devvn-reviews"), "name_required_text" => esc_html__("Your name is required!", "devvn-reviews"), "email_required_text" => esc_html__("Email is required!", "devvn-reviews"), "phone_required_text" => esc_html__("Your phone is required!", "devvn-reviews"), "cmt_required_text" => esc_html__("Comments is required!", "devvn-reviews"), "minlength_text" => sprintf(__("Minimum of %s characters", "devvn-reviews"), $devvn_review_settings["cmt_length"]), "minlength_text2" => sprintf(__("character ( Minimum of %d)", "devvn-reviews"), $devvn_review_settings["cmt_length"]), "file_format_text" => __("The image is not in the correct format. Only accept jpg/png/gif", "devvn-reviews"), "file_size_text" => sprintf(__("The image is too big. Only images allowed to be loaded <= %s", "devvn-reviews"), $this->formatSizeUnits($devvn_review_settings["img_size"])), "file_length_text" => sprintf(__("Allow uploading only %d images", "devvn-reviews"), $devvn_review_settings["number_img_upload"]), "review_success" => sprintf(__("Review successful!", "devvn-reviews")), "vietnamphone" => apply_filters("devvn_woo_reviews_phone_format_vn", true), "disable_owl_slider" => (int) $devvn_review_settings["disable_owl_slider"], "enable_img_popup" => (int) $devvn_review_settings["enable_img_popup"]];
            if ($devvn_review_settings["include_script_shortcode"] || $devvn_review_settings["show_img_reviews"]) {
                wp_enqueue_style("magnific-popup", plugin_dir_url(__FILE__) . "library/magnific-popup/magnific-popup.css", [], $this->_version, "all");
                wp_enqueue_script("magnific-popup", plugin_dir_url(__FILE__) . "library/magnific-popup/magnific-popup.js", ["jquery"], $this->_version, true);
            }
            if (!is_front_page() || apply_filters("devvn_script_include_from_front_page", false)) {
                if (is_singular("product")) {
                    if (!$devvn_review_settings["include_script_shortcode"]) {
                        wp_enqueue_style("magnific-popup", plugin_dir_url(__FILE__) . "library/magnific-popup/magnific-popup.css", [], $this->_version, "all");
                        wp_enqueue_script("magnific-popup", plugin_dir_url(__FILE__) . "library/magnific-popup/magnific-popup.js", ["jquery"], $this->_version, true);
                    }
                    wp_enqueue_style("devvn-reviews-style", plugin_dir_url(__FILE__) . "css/devvn-woocommerce-reviews.css", [], $this->_version, "all");
                    wp_enqueue_script("jquery.validate", plugin_dir_url(__FILE__) . "library/jquery.validate.min.js", ["jquery"], $this->_version, true);
                    wp_enqueue_script("devvn-reviews-script", plugin_dir_url(__FILE__) . "js/devvn-woocommerce-reviews.js", ["jquery", "magnific-popup", "jquery.validate", "wp-util"], $this->_version, true);
                    wp_localize_script("devvn-reviews-script", "devvn_reviews", $array);
                } else {
                    wp_enqueue_style("devvn-comment-style", plugin_dir_url(__FILE__) . "css/devvn-post-comment.css", [], $this->_version, "all");
                    wp_enqueue_script("jquery.validate", plugin_dir_url(__FILE__) . "library/jquery.validate.min.js", ["jquery"], $this->_version, true);
                    wp_enqueue_script("devvn-post-comment", plugin_dir_url(__FILE__) . "js/devvn-post-comment.js", ["jquery", "jquery.validate"], $this->_version, true);
                    wp_localize_script("devvn-post-comment", "devvn_reviews", $array);
                }
            }
            if ($devvn_review_settings["include_script_shortcode"] || $devvn_review_settings["show_img_reviews"]) {
                if (!$devvn_review_settings["disable_owl_slider"]) {
                    wp_enqueue_style("owl.carousel", plugin_dir_url(__FILE__) . "library/owl/assets/owl.carousel.min.css", [], $this->_version, "all");
                    wp_enqueue_script("owl.carousel", plugin_dir_url(__FILE__) . "library/owl/owl.carousel.min.js", ["jquery"], $this->_version, true);
                }
                wp_enqueue_style("devvn-shortcode-reviews-style", plugin_dir_url(__FILE__) . "css/devvn-shortcode-reviews.css", [], $this->_version, "all");
                wp_enqueue_script("masonry.pkgd", plugin_dir_url(__FILE__) . "library/masonry/masonry.pkgd.min.js", ["jquery"], $this->_version, true);
                wp_enqueue_script("devvn-shortcode-reviews", plugin_dir_url(__FILE__) . "js/devvn-shortcode-reviews.js", ["jquery", "masonry.pkgd"], $this->_version, true);
                wp_localize_script("devvn-shortcode-reviews", "devvn_shortcode_reviews", $array);
            }
        }
        public function woo_remove_product_tabs($tabs)
        {
            global $devvn_review_settings;
            if ($devvn_review_settings["review_position"]) {
                unset($tabs["reviews"]);
            }
            return $tabs;
        }
        public function admin_notices()
        {
            $class = "notice notice-error";
            if (!reviews_check_license()) {
                printf("<div class=\"%1\$s\"><p><strong>Plugin DevVN Woocommerce Reviews:</strong> Hãy điền <strong>License Key</strong> để tự động cập nhật khi có phiên bản mới. <a href=\"%2\$s\">Thêm tại đây</a></p></div>", esc_attr($class), esc_url(admin_url("admin.php?page=devvn-woocommerce-reviews")));
            }
        }
        public function devvn_modify_plugin_update_message($plugin_data, $response)
        {
            $license_key = reviews_get_license();
            if ($license_key && isset($plugin_data["package"]) && $plugin_data["package"]) {
                return NULL;
            }
            $PluginURI = isset($plugin_data["PluginURI"]) ? $plugin_data["PluginURI"] : "";
            echo "<br />" . sprintf(__("<strong>Mua bản quyền để được tự động update. <a href=\"%s\" target=\"_blank\">Xem thêm thông tin mua bản quyền</a></strong> hoặc liên hệ mua trực tiếp qua <a href=\"%s\" target=\"_blank\">facebook</a>", "devvn-reviews"), $PluginURI, "https://m.me/levantoan.wp");
        }
        public function admin_enqueue_scripts()
        {
            $current_screen = get_current_screen();
            $tab = isset($_GET["tab"]) ? esc_attr($_GET["tab"]) : "";
            if (isset($current_screen->base) && in_array($current_screen->base, ["comment", "edit-comments", "woocommerce_page_devvn-woocommerce-reviews", "settings_page_devvn-woocommerce-reviews", "product_page_product-reviews"])) {
                if ($tab == "auto-reviews") {
                    wp_enqueue_style("jquery-ui", "//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css");
                    wp_enqueue_script("jquery-ui-datepicker");
                }
                wp_enqueue_media();
                wp_enqueue_style("select2", plugins_url("/css/select2.min.css", __FILE__), [], $this->_version, "all");
                wp_enqueue_style("devvn-reviews-styles", plugins_url("/css/admin-devvn-woocommerce-reviews.css", __FILE__), [], $this->_version, "all");
                wp_enqueue_script("select2", plugins_url("/js/select2.full.min.js", __FILE__), ["jquery"], $this->_version, true);
                wp_enqueue_script("devvn-reviews", plugins_url("/js/admin-devvn-woocommerce-reviews.js", __FILE__), ["jquery"], $this->_version, true);
                $array = ["ajax_url" => admin_url("admin-ajax.php"), "text_loading" => __("Đang thực hiện...", "devvn-reviews"), "text_done" => __("Đã xong", "devvn-reviews"), "text_error" => __("Có lỗi xảy ra. Fake lại ngay", "devvn-reviews"), "nonce" => wp_create_nonce("search-products")];
                wp_localize_script("devvn-reviews", "devvn_reviews", $array);
            }
        }
        public function devvn_reviews_sync_cmt_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            $comments = get_comments();
            $count = $count_error = 0;
            foreach ($comments as $comment) {
                $post_id = $comment->comment_post_ID;
                $comment_type = $comment->comment_type;
                if ("product" == get_post_type($post_id) && ($comment_type == "" || $comment_type == "comment")) {
                    $new_comment = [];
                    $new_comment["comment_ID"] = $comment->comment_ID;
                    $new_comment["comment_type"] = "review";
                    $new_cmt = wp_update_comment($new_comment);
                    if ($new_cmt) {
                        $count++;
                    } else {
                        $count_error++;
                    }
                }
            }
            wp_send_json_success(sprintf(__("Đã sync %1\$d đánh giá. Có %2\$d lỗi.", "devvn-reviews"), $count, $count_error));
            exit;
        }
        public function open_comment_prod_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error("Lỗi kiểm tra mã bảo mật");
            }
            global $wpdb;
            $wpdb->query("UPDATE " . $wpdb->posts . " SET comment_status = 'open' WHERE post_type = 'product'");
            wp_send_json_success(__("Done!", "devvn-reviews"));
            exit;
        }
        public function reset_sold_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error("Lỗi kiểm tra mã bảo mật");
            }
            global $wpdb;
            $wpdb->query("DELETE FROM " . $wpdb->postmeta . " WHERE `meta_key` = 'total_sales'");
            wp_send_json_success(__("Done!", "devvn-reviews"));
            exit;
        }
        public function fake_sold_prod_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error("Lỗi kiểm tra mã bảo mật");
            }
            $sold_type = isset($_POST["sold_type"]) ? $_POST["sold_type"] : "";
            $sold_form = isset($_POST["sold_form"]) ? $_POST["sold_form"] : "";
            $sold_to = isset($_POST["sold_to"]) ? $_POST["sold_to"] : "";
            $fake_per_page = isset($_POST["fake_per_page"]) ? $_POST["fake_per_page"] : 20;
            $fake_paged = isset($_POST["fake_paged"]) ? $_POST["fake_paged"] : 1;
            $products = wc_get_products(["limit" => $fake_per_page, "page" => $fake_paged]);
            if ($products && !is_wp_error($products)) {
                foreach ($products as $product) {
                    $current_sold = $product->get_total_sales();
                    if ($sold_type == "reviews") {
                        $review_count = $product->get_review_count();
                        $rand = rand(2, 4);
                        $sold = $current_sold + $review_count * $rand;
                    } else {
                        if ($sold_form < $sold_to) {
                            $rand = rand($sold_form, $sold_to);
                        } else {
                            if ($sold_form) {
                                $rand = rand(1, $sold_form);
                            } else {
                                if ($sold_to) {
                                    $rand = rand(1, $sold_to);
                                }
                            }
                        }
                        $sold = $current_sold + $rand;
                    }
                    $product->set_total_sales($sold);
                    $product->save();
                }
                wp_send_json_success(["complete" => false, "number" => count($products)]);
            } else {
                if (!is_wp_error($products)) {
                    wp_send_json_success(["complete" => true, "number" => 0]);
                }
            }
            wp_send_json_error("Có lỗi xảy ra!");
            exit;
        }
        public function fake_reviews_bought_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error("Lỗi kiểm tra mã bảo mật");
            }
            $comments = get_comments();
            $count = $count_error = 0;
            foreach ($comments as $comment) {
                $post_id = $comment->comment_post_ID;
                $comment_type = $comment->comment_type;
                $comment_parent = $comment->comment_parent;
                if ("product" == get_post_type($post_id) && $comment_type == "review" && $comment_parent == 0) {
                    $new_cmt = update_comment_meta($comment->comment_ID, "verified", 1);
                    if ($new_cmt) {
                        $count++;
                    } else {
                        $count_error++;
                    }
                }
            }
            wp_send_json_success(sprintf(__("Đã fake %1\$d đánh giá.", "devvn-reviews"), $count, $count_error));
            exit;
        }
        public function admin_fake_label_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_fake_label")) {
                wp_send_json_error("Lỗi kiểm tra mã bảo mật");
            }
            $id = isset($_POST["id"]) && $_POST["id"] ? intval($_POST["id"]) : "";
            if ($id) {
                $comment = get_comment($id);
                if ($comment && !is_wp_error($comment)) {
                    $post_id = $comment->comment_post_ID;
                    $comment_type = $comment->comment_type;
                    $comment_parent = $comment->comment_parent;
                    if ("product" == get_post_type($post_id) && $comment_type == "review" && $comment_parent == 0) {
                        update_comment_meta($comment->comment_ID, "verified", 1);
                    }
                    wp_send_json_success();
                }
            }
            wp_send_json_error();
            exit;
        }
        public function set_upload_dir($upload)
        {
            $old_dir = $upload;
            $upload["subdir"] = "/woocommerce-reviews";
            $upload["path"] = $upload["basedir"] . $upload["subdir"];
            $upload["url"] = $upload["baseurl"] . $upload["subdir"];
            return apply_filters("devvn_reviews_upload_dir", $upload, $old_dir);
        }
        public function remove_default_image_sizes($sizes)
        {
            $review_size = [];
            if (isset($sizes["thumbnail"])) {
                $review_size["thumbnail"] = $sizes["thumbnail"];
            }
            if (isset($sizes["shop_single"])) {
                $review_size["shop_single"] = $sizes["shop_single"];
            }
            return apply_filters("devvn_reviews_allow_image_sizes", $review_size, $sizes);
        }
        private static function create_files()
        {
            $upload_dir = wp_upload_dir();
            $files = [["base" => $upload_dir["basedir"] . "/woocommerce-reviews", "file" => "index.html", "content" => ""], ["base" => $upload_dir["basedir"] . "/woocommerce-reviews", "file" => "index.html", "content" => ""]];
            foreach ($files as $file) {
                if (wp_mkdir_p($file["base"]) && !file_exists(trailingslashit($file["base"]) . $file["file"])) {
                    $file_handle = fopen(trailingslashit($file["base"]) . $file["file"], "w");
                    if ($file_handle) {
                        fwrite($file_handle, $file["content"]);
                        fclose($file_handle);
                    }
                }
            }
        }
        public function devvn_woocommerce_comment_pagination_args($args)
        {
            if (is_singular("product")) {
                $args["add_fragment"] = "#reviews";
            }
            return $args;
        }
        public function get_like_review($comment)
        {
            global $devvn_review_settings;
            if ($devvn_review_settings["show_like"] == "2") {
                return false;
            }
            $like_count = get_comment_meta($comment->comment_ID, "devvn_like_cmt", true);
            echo "            <span> • </span>\r\n            <a href=\"javascript:void(0)\" class=\"cmtlike\" data-like=\"";
            echo $like_count;
            echo "\" data-id=\"";
            echo $comment->comment_ID;
            echo "\" title=\"\"><span class=\"cmt_count\">";
            echo $like_count ? $like_count : "";
            echo "</span> ";
            _e("like", "devvn-reviews");
            echo "</a>\r\n            ";
        }
        public function devvn_like_cmt_func()
        {
            global $devvn_review_settings;
            $id = isset($_POST["id"]) ? intval($_POST["id"]) : "";
            if (!$id || !get_comment($id) || $devvn_review_settings["show_like"] == "2") {
                wp_send_json_error();
            }
            $like_count = intval(get_comment_meta($id, "devvn_like_cmt", true));
            update_comment_meta($id, "devvn_like_cmt", $like_count + 1);
            wp_send_json_success();
            exit;
        }
        public function devvn_reviews_func()
        {
            ob_start();
            comments_template();
            return ob_get_clean();
        }
        public function check_license()
        {
            // if (function_exists("reviews_check_license")) {
            //     return reviews_check_license();
            // }
            global $devvn_review_settings;
            return true;
        }
        public function devvn_check_license_func()
        {
            if (!wp_next_scheduled("devvn_check_wooreviews_license_cron")) {
                $devvn_ve = 0 < get_option("gmt_offset") ? "-" : "+";
                wp_schedule_event(strtotime("00:00 tomorrow " . $devvn_ve . absint(get_option("gmt_offset")) . " HOURS"), "weekly", "devvn_check_wooreviews_license_cron");
            }
            add_action("devvn_check_wooreviews_license_cron", [$this, "devvn_check_license_cron_func"]);
        }
        public function devvn_end_check_license_func()
        {
            wp_clear_scheduled_hook("devvn_check_wooreviews_license_cron");
        }
        public function check_after_update_option()
        {
            $this->devvn_check_license_cron_func();
        }
        public function devvn_check_license_cron_func()
        {
            $license = reviews_get_license();
            if ($license) {
                $response = reviews_check_license_live($license);

                    reviews_set_license($license, $response);

            }
        }
        public function remove_license()
        {
            global $devvn_review_settings;
            $devvn_review_settings["license_key"] = "99999";
            update_site_option($this->_optionName, $devvn_review_settings);
        }
        public function weekly_cron_schedule($schedules)
        {
            $schedules["weekly"] = ["interval" => 604800, "display" => __("Weekly")];
            return $schedules;
        }
        public function devvn_sold_func($atts)
        {
            $atts = shortcode_atts(["id" => ""], $atts, "devvn_sold");
            $id = $atts["id"];
            if (!$id) {
                global $product;
                $this_product = $product;
            } else {
                $this_product = wc_get_product($id);
            }
            if ($this_product) {
                $units_sold = $this_product->get_total_sales();
                if (!$units_sold) {
                    $units_sold = 0;
                }
                return "<span class=\"devvn_sold\"><span class=\"count\">" . apply_filters("devvn_sold", $units_sold) . "</span> " . sprintf(__("sold", "devvn-reviews"), $units_sold) . "</span>";
            }
            return false;
        }
        public function devvn_sold_metabox()
        {
            echo "<div class=\"options_group show_if_simple show_if_external show_if_variable\">";
            woocommerce_wp_text_input(["id" => "total_sales", "label" => __("Sold", "devvn-reviews"), "description" => "Số lượng đã bán của sản phẩm. Số này tự động cộng lên khi khách ấn mua hàng. Bạn có thể nhập số tùy ý, sau này nó cũng sẽ cộng lên", "desc_tip" => "true"]);
            echo "</div>";
        }
        public function devvn_sold_metabox_save($post_id)
        {
            if (get_post_type($post_id) == "product" && isset($_POST["total_sales"])) {
                update_post_meta($post_id, "total_sales", intval($_POST["total_sales"]));
            }
        }
        public function devvn_custom_cmt_author($author, $cmt_id)
        {
            global $devvn_review_settings;
            $commentphone = get_comment_meta($cmt_id, "phone", true);
            if ($devvn_review_settings["active_name_phone"] == 1 && $commentphone) {
                $author = $author . " " . apply_filters("devvn_custom_phone_author_view", $this->format_view_phone($commentphone));
            }
            return $author;
        }
        public function format_view_phone($phone)
        {
            $kitu = strlen($phone);
            if (4 < $kitu) {
                $rest = substr($phone, 0, $kitu - 3);
                $textsao = "***";
            } else {
                $rest = substr($phone, 0, $kitu - 1);
                $textsao = "*";
            }
            $view_phone = $rest . $textsao;
            return apply_filters("devvn_cmt_phone_view", $view_phone, $phone);
        }
        public function get_random_reviews()
        {
            $user = $this->get_autoreviews_option("user");
            $comment = $this->get_autoreviews_option("comment");
            $number_reviews = $this->get_autoreviews_option("number_reviews");
            $number_reviews_to = $this->get_autoreviews_option("number_reviews_to");
            $date_reviews_form = $this->get_autoreviews_option("date_reviews_form");
            $date_reviews_to = $this->get_autoreviews_option("date_reviews_to");
            $rumber_rand = rand($number_reviews, $number_reviews_to);
            $reviews_args = [];
            if (!$user || !$comment) {
                return false;
            }
            $user = explode("\n", str_replace("\r", "", $user));
            $comment = explode("\n", str_replace("\r", "", $comment));
            if ($rumber_rand) {
                for ($i = 0; $i < $rumber_rand; $i++) {
                    $user_rand = array_rand($user);
                    $comment_rand = array_rand($comment);
                    $user_content = $user[$user_rand];
                    $comment_content = $comment[$comment_rand];
                    $user_content = explode("|", $user_content);
                    $reviews_args[$i]["name"] = isset($user_content[0]) ? trim($user_content[0]) : "";
                    $reviews_args[$i]["sdt"] = isset($user_content[1]) ? trim($user_content[1]) : "";
                    $reviews_args[$i]["email"] = isset($user_content[2]) ? trim($user_content[2]) : "";
                    $comment_content = explode("|", $comment_content);
                    $reviews_args[$i]["cmt"] = isset($comment_content[0]) ? $comment_content[0] : "";
                    $reviews_args[$i]["rating"] = isset($comment_content[1]) && $comment_content[1] ? $comment_content[1] : 5;
                    $reviews_args[$i]["img"] = isset($comment_content[2]) && $comment_content[2] ? explode(",", trim($comment_content[2])) : [];
                    if ($date_reviews_form && $date_reviews_to) {
                        $reviews_args[$i]["date"] = $this->randomDate($date_reviews_form, $date_reviews_to);
                    } else {
                        $reviews_args[$i]["date"] = $this->randomDate(date_i18n("Y-m-d", strtotime(" -2 day")), date_i18n("Y-m-d"));
                    }
                }
            }
            return $reviews_args;
        }
        public function randomDate($start_date, $end_date)
        {
            $min = strtotime($start_date);
            $max = strtotime($end_date);
            $val = rand($min, $max);
            return date("Y-m-d H:i:s", $val);
        }
        public function import_media_by_url($url = "", $post_id = 0)
        {
            $file = [];
            $file["name"] = basename($url);
            $file["tmp_name"] = download_url($url);
            if (is_wp_error($file["tmp_name"])) {
                unlink($file["tmp_name"]);
                return false;
            }
            $attachment_id = media_handle_sideload($file, $post_id);
            $attach_data = wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id));
            wp_update_attachment_metadata($attachment_id, $attach_data);
            return $attachment_id;
        }
        public function auto_reviews_func()
        {
            if (!wp_verify_nonce($_REQUEST["nonce"], "admin_devvn_reviews_nonce_action")) {
                wp_send_json_error(__("Error!", "devvn-reviews"));
            }
            $auto_per_page = isset($_POST["auto_per_page"]) ? intval($_POST["auto_per_page"]) : 20;
            $fake_paged = isset($_POST["fake_paged"]) ? $_POST["fake_paged"] : 1;
            $number_reviews = $this->get_autoreviews_option("number_reviews");
            $user = $this->get_autoreviews_option("user");
            $comment = $this->get_autoreviews_option("comment");
            $cat_reviews = $this->get_autoreviews_option("cat_reviews");
            $prod_reviews = $this->get_autoreviews_option("prod_reviews");
            $no_reviews = $this->get_autoreviews_option("no_reviews");
            if (!$user || !$comment || !$number_reviews || !$auto_per_page) {
                wp_send_json_error("Chưa đủ thông tin");
            }
            $args = ["limit" => $auto_per_page, "page" => $fake_paged];
            if ($cat_reviews) {
                $cat_slugs = [];
                foreach ($cat_reviews as $item) {
                    $term = get_term_by("term_id", $item, "product_cat");
                    if ($term && !is_wp_error($term)) {
                        $cat_slugs[] = $term->slug;
                    }
                }
                $args["category"] = $cat_slugs;
            }
            if ($prod_reviews && !empty($prod_reviews)) {
                $args["include"] = $prod_reviews;
            }
            if ($no_reviews) {
                $args["review_count"] = 0;
            }
            $products = wc_get_products(apply_filters("devvn_auto_reviews_args", $args));
            $rv_count = 0;
            if ($products && !is_wp_error($products)) {
                foreach ($products as $product) {
                    $reviews = $this->get_random_reviews();
                    $rv_count += count($reviews);
                    foreach ($reviews as $cmt) {
                        $name = isset($cmt["name"]) ? $cmt["name"] : "";
                        $sdt = isset($cmt["sdt"]) ? $cmt["sdt"] : "";
                        $email = isset($cmt["email"]) ? $cmt["email"] : "";
                        $content = isset($cmt["cmt"]) ? $cmt["cmt"] : "";
                        $rating = isset($cmt["rating"]) ? (int) $cmt["rating"] : 5;
                        $imgs = isset($cmt["img"]) ? $cmt["img"] : [];
                        $date = isset($cmt["date"]) ? $cmt["date"] : "";
                        $commentdata = ["comment_post_ID" => $product->get_id(), "comment_author_email" => $email, "comment_author" => $name, "comment_content" => $content, "comment_parent" => 0, "comment_type" => "review", "comment_approved" => 1, "comment_date" => $date];
                        if (date_i18n("Y-m-d H:i:s") < $date) {
                            $commentdata["comment_approved"] = 0;
                            $commentdata["comment_meta"] = ["bydevvnimport" => 1];
                        }
                        $comment_id = wp_insert_comment($commentdata);
                        if ($comment_id) {
                            if ($rating) {
                                update_comment_meta($comment_id, "rating", $rating);
                            }
                            add_comment_meta($comment_id, "verified", true);
                            if ($sdt) {
                                add_comment_meta($comment_id, "phone", wp_unslash($sdt));
                            }
                            if ($imgs) {
                                $imgs_args = [];
                                foreach ($imgs as $img) {
                                    $img_id = $this->import_media_by_url(esc_url(trim($img)));
                                    if ($img_id) {
                                        $imgs_args[] = $img_id;
                                    }
                                }
                                if ($imgs_args) {
                                    update_comment_meta($comment_id, "attachment_img", $imgs_args);
                                }
                            }
                            if (date_i18n("Y-m-d H:i:s") < $date) {
                                $this->set_review_schedule($comment_id);
                            }
                        }
                    }
                    $this->devvn_clear_transients_count_review($product->get_id());
                }
                wp_send_json_success(["complete" => false, "number" => count($products), "rv_count" => $rv_count]);
            } else {
                if (!is_wp_error($products)) {
                    wp_send_json_success(["complete" => true, "number" => 0, "rv_count" => 0]);
                }
            }
            wp_send_json_error("Có lỗi xảy ra!");
            exit;
        }
        public function active_license_func()
        {

            $license = isset($_POST["license"]) ? sanitize_text_field($_POST["license"]) : "";
            if ($license) {
                $response = reviews_check_license_live($license);
                if ($response["status"]) {
                    reviews_set_license($license, $response);
                    wp_send_json_success($response["mess"]);
                } else {
                    reviews_remove_license();
                    wp_send_json_error($response["mess"]);
                }
            }
            wp_send_json_error("Thiếu license");
            exit;
        }
        public function get_post_comment_template()
        {
            return apply_filters("devvn_post_comment_template", ["post", "page", "cho_thue_xe"]);
        }
        public function devvn_post_comments_template_loader($template)
        {
            if (in_array(get_post_type(), $this->get_post_comment_template()) && reviews_check_license() && false === get_transient("reviews_check")) {
                $check_dirs = [trailingslashit(get_stylesheet_directory()) . "/devvn-reviews/", trailingslashit(plugin_dir_path(__FILE__)) . "templates/"];
                foreach ($check_dirs as $dir) {
                    if (file_exists(trailingslashit($dir) . "single-post-comments.php")) {
                        return trailingslashit($dir) . "single-post-comments.php";
                    }
                }
            } else {
                return $template;
            }
        }
        public function wp_set_comment_cookies($comment, $user, $cookies_consent = true)
        {
            if ($user->exists()) {
                return NULL;
            }
            if (false === $cookies_consent) {
                $past = time() - YEAR_IN_SECONDS;
                setcookie("comment_author_phone_" . COOKIEHASH, " ", $past, COOKIEPATH, COOKIE_DOMAIN);
            } else {
                $comment_cookie_lifetime = time() + apply_filters("comment_cookie_lifetime", 30000000);
                $secure = "https" === parse_url(home_url(), PHP_URL_SCHEME);
                if (isset($_POST["phone"]) && $_POST["phone"]) {
                    setcookie("comment_author_phone_" . COOKIEHASH, wp_unslash($_POST["phone"]), $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN, $secure);
                }
            }
        }
        public function wp_get_current_commenter($data)
        {
            if (isset($_COOKIE["comment_author_phone_" . COOKIEHASH])) {
                $data["comment_author_phone"] = $_COOKIE["comment_author_phone_" . COOKIEHASH];
            }
            return $data;
        }
        public function devvn_post_comment_preprocess($comment_data)
        {
            global $devvn_review_settings;
            $enable_postcmt_phone = $devvn_review_settings["enable_postcmt_phone"];
            if (in_array(get_post_type(absint($_POST["comment_post_ID"])), $this->get_post_comment_template()) && !is_admin() && !is_user_logged_in() && isset($_POST["comment_post_ID"]) && isset($comment_data["comment_type"])) {
                if (!isset($_POST["phone"]) && $enable_postcmt_phone == 1) {
                    wp_die(__("Error: Phone number is required!", "devvn-reviews"));
                }
                $phone = isset($_POST["phone"]) ? wp_unslash($_POST["phone"]) : "";
                if ($phone && !preg_match("/^0([0-9]{9,10})+\$/D", $phone) && apply_filters("devvn_woo_reviews_phone_format_vn", true)) {
                    wp_die(__("Error: The phone number is not in the correct format!", "devvn-reviews"));
                }
            }
            return $comment_data;
        }
        public function devvn_comment_scripts()
        {
            if (is_singular() && comments_open() && get_option("thread_comments")) {
                wp_enqueue_script("comment-reply");
            }
        }
        public function get_taxonomy_labels($taxonomies = [])
        {
            if (empty($taxonomies)) {
                $taxonomies = $this->get_taxonomies();
            }
            $ref = [];
            $data = [];
            foreach ($taxonomies as $taxonomy) {
                $object = get_taxonomy($taxonomy);
                $label = $object->labels->singular_name;
                $data[$taxonomy] = $label;
                if (!isset($ref[$label])) {
                    $ref[$label] = 0;
                }
                $ref[$label]++;
            }
            foreach ($data as $taxonomy => $label) {
                if (1 < $ref[$label]) {
                    $data[$taxonomy] .= " (" . $taxonomy . ")";
                }
            }
            return $data;
        }
        public function get_taxonomies($args = [])
        {
            $taxonomies = [];
            $objects = get_taxonomies($args, "objects");
            foreach ($objects as $i => $object) {
                if (!$object->_builtin || $object->public) {
                    $taxonomies[] = $i;
                }
            }
            if (isset($args["post_type"])) {
                $taxonomies = $this->get_taxonomies_for_post_type($args["post_type"]);
            }
            $taxonomies = apply_filters("devvn_reviews_get_taxonomies", $taxonomies, $args);
            return $taxonomies;
        }
        public function get_taxonomies_for_post_type($post_types = "post")
        {
            $taxonomies = [];
            foreach ((array) $post_types as $post_type) {
                $object_taxonomies = get_object_taxonomies($post_type);
                foreach ((array) $object_taxonomies as $taxonomy) {
                    $taxonomies[] = $taxonomy;
                }
            }
            $taxonomies = array_unique($taxonomies);
            return $taxonomies;
        }
        public function wc_get_template($template, $template_name)
        {
            global $devvn_review_settings;
            if ($template_name == "loop/rating.php" && $devvn_review_settings["loop_rating"]) {
                $template = DEVVN_REVIEWS_PLUGIN_DIR . "templates/loop-rating.php";
            }
            return $template;
        }
        public function woocommerce_product_get_rating_html($html, $rating, $count)
        {
            global $devvn_review_settings;
            if ($rating == 0 && $devvn_review_settings["loop_rating_zero"]) {
                $label = sprintf(__("Rated %s out of 5", "woocommerce"), $rating);
                $html = "<div class=\"star-rating\" role=\"img\" aria-label=\"" . esc_attr($label) . "\">" . wc_get_star_rating_html($rating, $count) . "</div>";
            }
            return $html;
        }
        public function comment_moderation_text($notify_message, $comment_id)
        {
            $sdt = isset($_POST["phone"]) ? wp_unslash($_POST["phone"]) : "";
            if ($sdt) {
                $notify_message .= "\r\n\r\n" . sprintf(__("Phone number: %s", "devvn-reviews"), $sdt) . "\r\n";
            }
            return $notify_message;
        }
        public function wp_footer_devvn_reviews()
        {
            if (function_exists("basel_enqueue_styles")) {
                echo "                <style type=\"text/css\">\r\n                    /*Css for basel theme*/\r\n                    body.woocommerce #reviews .star-rating {\r\n                        font-size: 12px !important;\r\n                    }\r\n                    .devvn-star::before,\r\n                    #review_form .comment-form-rating p.stars a:before, .woocommerce #reviews #comments ol.commentlist #respond .comment-form-rating p.stars a:before {\r\n                        content: \"\\f905\";\r\n                        font-family: basel-font;\r\n                    }\r\n                    .commentlist .comment_container, .commentlist .review_comment_container {\r\n                        padding: 0;\r\n                    }\r\n                    body.woocommerce #reviews #comments ol.commentlist ul.children {\r\n                        margin: 0;\r\n                    }\r\n                    body.woocommerce #reviews #comments ol.commentlist ul.children li {\r\n                        border-top: 0;\r\n                    }\r\n                    .commentlist .comment_container, .commentlist .review_comment_container {\r\n                        min-height: inherit;\r\n                    }\r\n                    .commentlist .comment-text .star-rating {\r\n                        float: left;\r\n                        margin-bottom: 0;\r\n                    }\r\n                    body.woocommerce #reviews #comments ol.commentlist #respond {\r\n                        margin: 0;\r\n                        background: #fff;\r\n                        padding: 10px;\r\n                    }\r\n                    .single-product-content #comments {\r\n                        width: 100%;\r\n                        padding: 20px 0;\r\n                    }\r\n                    /*#Css for basel theme*/\r\n                </style>\r\n            ";
            }
            if (function_exists("woodmart_load_classes")) {
                echo "                <style type=\"text/css\">\r\n                    .woocommerce-Reviews #comments, .woocommerce-Reviews #review_form_wrapper {\r\n                        flex: 0 1 100%;\r\n                        max-width: 100%;\r\n                    }\r\n                    body .star-average .woocommerce-product-rating .star-rating {\r\n                        font-size: 14px;\r\n                    }\r\n                    .devvn-star:before,\r\n                    #review_form .comment-form-rating p.stars a:before, .woocommerce #reviews #comments ol.commentlist #respond .comment-form-rating p.stars a:before {\r\n                        content: \"\\f148\";\r\n                        font-family: \"woodmart-font\";\r\n                    }\r\n                    body.woocommerce #reviews #comments ol.commentlist li .comment-text {\r\n                        display: block;\r\n                    }\r\n                    body.woocommerce #reviews .star-rating {\r\n                        font-size: 14px;\r\n                        margin: 0 6px 4px 0;\r\n                    }\r\n                    body.woocommerce #reviews #comments ol.commentlist ul.children {\r\n                        border: 0;\r\n                    }\r\n                    .form-style-rounded .devvn_cmt_input textarea {\r\n                        border-radius: 0;\r\n                    }\r\n                    .wrap-attaddsend {\r\n                        width: 100%;\r\n                    }\r\n                    .woocommerce #review_form #respond textarea, .woocommerce #reviews #comments ol.commentlist #respond textarea {\r\n                        border-radius: 0;\r\n                        min-height: auto;\r\n                    }\r\n                    div#comments {\r\n                        padding-left: 0 !important;\r\n                        padding-right: 0 !important;\r\n                    }\r\n                    div#respond input#wp-comment-cookies-consent {\r\n                        display: inline-block;\r\n                        width: auto !important;\r\n                    }\r\n                    .devvn_cmt_input textarea {\r\n                        min-height: auto;\r\n                    }\r\n                </style>\r\n                ";
            }
            if (function_exists("porto_setup")) {
                echo "                <style type=\"text/css\">\r\n                    div#reviews.woocommerce-Reviews, .devvn_prod_cmt {\r\n                        max-width: 100%;\r\n                    }\r\n                    .devvn-star:before {\r\n                        content: \"\";\r\n                        font-style: normal;\r\n                        font-family: 'Font Awesome 5 Free';\r\n                        font-weight: 900;\r\n                    }\r\n                    form#commentform {\r\n                        padding: 0;\r\n                        background: #fff;\r\n                    }\r\n                    #review_form .comment-form-rating p.stars a:before, .woocommerce #reviews #comments ol.commentlist #respond .comment-form-rating p.stars a:before {\r\n                        content: \"\";\r\n                    }\r\n                    .single-product .star_box .woocommerce-product-rating:after {\r\n                        display: none;\r\n                    }\r\n                    #reviews .commentlist li {\r\n                        padding-left: 0;\r\n                    }\r\n                    .commentlist li .comment-text {\r\n                        background: #fff;\r\n                    }\r\n                    #reviews .commentlist li .comment-text:before {\r\n                        display: none;\r\n                    }\r\n                    .commentlist li .comment-text p {\r\n                        font-size: 14px;\r\n                    }\r\n                    #reviews .commentlist li .star-rating {\r\n                        float: none;\r\n                    }\r\n                    body.woocommerce #reviews.devvn-style2 #comments ol.commentlist > li > ul.children .comment-text {\r\n                        background: transparent;\r\n                    }\r\n                    .woocommerce-pagination {\r\n                        text-align: left;\r\n                    }\r\n                    nav.devvn_cmt_paged.woocommerce-pagination ul li a, nav.devvn_cmt_paged.woocommerce-pagination ul li span.page-numbers.current{\r\n                        padding: 0 9px;\r\n                    }\r\n                    .devvn_review_item_image .owl-carousel {\r\n                        margin-bottom: 0;\r\n                    }\r\n                </style>\r\n                ";
            }
        }
        public function get_quick_reviews_tag($product)
        {
            global $devvn_review_settings;
            $quick_reviews = isset($devvn_review_settings["quick_review"]) ? $devvn_review_settings["quick_review"] : "";
            if ($quick_reviews) {
                $quick_reviews = explode("\n", str_replace("\r", "", $quick_reviews));
            }
            return $quick_reviews;
        }
        public function add_quick_reviews($comment_form, $commenter, $product)
        {
            $quick_reviews = $this->get_quick_reviews_tag($product);
            if ($quick_reviews && !empty($quick_reviews)) {
                echo "                <div class=\"quick_reviews_tag\">\r\n                    ";
                foreach ($quick_reviews as $item) {
                    echo "                        <label>\r\n                            <input type=\"checkbox\" name=\"quick_tag[]\" value=\"";
                    echo esc_attr($item);
                    echo "\">\r\n                            <span>";
                    echo esc_attr($item);
                    echo "</span>\r\n                        </label>\r\n                    ";
                }
                echo "                </div>\r\n                ";
            }
        }
        public function allow_empty_comment($false)
        {
            $quick_tag = isset($_POST["quick_tag"]) ? array_map("esc_attr", $_POST["quick_tag"]) : "";
            if ($quick_tag && is_array($quick_tag)) {
                $false = true;
            }
            return $false;
        }
        public function duplicate_comment_id()
        {
            return false;
        }
    }
}
devvn_reviews();
if (!function_exists("woocommerce_comments")) {
    function woocommerce_comments($comment, $args, $depth)
    {
        $GLOBALS["comment"] = $comment;
        $check_dirs = [trailingslashit(get_stylesheet_directory()) . "/devvn-reviews/", trailingslashit(plugin_dir_path(__FILE__)) . "templates/"];
        foreach ($check_dirs as $dir) {
            if (file_exists(trailingslashit($dir) . "review.php")) {
                include trailingslashit($dir) . "review.php";
                return NULL;
            }
        }
    }
}
if (!function_exists("woocommerce_review_display_meta")) {
    function woocommerce_review_display_meta()
    {
        $check_dirs = [trailingslashit(get_stylesheet_directory()) . "/devvn-reviews/", trailingslashit(plugin_dir_path(__FILE__)) . "templates/"];
        foreach ($check_dirs as $dir) {
            if (file_exists(trailingslashit($dir) . "review-meta.php")) {
                include trailingslashit($dir) . "review-meta.php";
                return NULL;
            }
        }
    }
}
if (!function_exists("devv_check_reviews_admin")) {
    function devv_check_reviews_admin($roles)
    {
        $qtv = false;
        $allow_roles = apply_filters("roles_reviews_admin", ["administrator", "shop_manager", "author"]);
        if ($roles && is_array($roles)) {
            foreach ($roles as $role) {
                if (in_array($role, $allow_roles)) {
                    $qtv = true;
                }
            }
        }
        return $qtv;
    }
}
if (!function_exists("devvn_check_comment_mod")) {
    function devvn_check_comment_mod($comment)
    {
        $user_id = isset($comment->user_id) ? $comment->user_id : "";
        $qtv = false;
        if ($user_id) {
            $user = get_userdata($user_id);
            if ($user && !is_wp_error($user)) {
                $user_roles = $user->roles;
                $qtv = devv_check_reviews_admin($user_roles);
            }
        }
        if ($user_id && $qtv && $comment->comment_parent != 0) {
            return true;
        }
        return false;
    }
}
if (devvn_reviews()->get_options()["show_sold"] == 1 && !function_exists("woocommerce_template_single_rating")) {
    function woocommerce_template_single_rating()
    {
        if (post_type_supports("product", "comments")) {
            global $product;
            if (!wc_review_ratings_enabled()) {
                return NULL;
            }
            $rating_count = $product->get_rating_count();
            $review_count = $product->get_review_count();
            $average = $product->get_average_rating();
            echo "\r\n                <div class=\"woocommerce-product-rating devvn_single_rating\">\r\n                    ";
            if (0 < $rating_count) {
                echo "                        <span class=\"devvn_average_rate\">";
                echo $average;
                echo "</span>\r\n                        ";
                echo wc_get_rating_html($average, $rating_count);
                echo "                    ";
            } else {
                echo "                        ";
                $label = sprintf(__("Rated %s out of 5", "woocommerce"), $rating_count);
                $html = "<div class=\"star-rating\" role=\"img\" aria-label=\"" . esc_attr($label) . "\">" . wc_get_star_rating_html($average, $rating_count) . "</div>";
                echo $html;
                echo "                    ";
            }
            echo "                    ";
            if (comments_open()) {
                echo "                                                <a href=\"#reviews\" class=\"woocommerce-review-link\" rel=\"nofollow\">\r\n                            ";
                if (0 < $rating_count) {
                    echo "                                ";
                    printf(_n("(%s customer review)", "(%s customer reviews)", $review_count, "devvn-reviews"), "<span class=\"count\">" . esc_html($review_count) . "</span>");
                    echo "                            ";
                } else {
                    echo "                                ";
                    _e("(customer review)", "devvn-reviews");
                    echo "                            ";
                }
                echo "                        </a>\r\n                                            ";
            }
            echo "\r\n                    ";
            echo do_shortcode("[devvn_sold]");
            echo "\r\n                </div>\r\n\r\n            ";
        }
    }
}
function devvn_reviews()
{
    return DevVN_Reviews_Class::init();
}
function reviews_check_license()
{
    // if (false !== get_transient("reviews_check")) {
    //     return true;
    // }
    $key = get_option("reviews_license", "99999");
        $response = reviews_check_license_live($key);
        $resp = isset($response["response"]) && $response["response"] ? $response["response"] : [];
        $respon_code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);
            reviews_set_license($key, $response);
            $options = get_transient("rv_valid");

    return true;
}
function devvn_woocommerce_paginate_links_output($output, $args)
{
    $output = preg_replace("/<a (.*?)>/", "<a \$1 rel=\"nofollow\">", $output);
    return $output;
}

?>