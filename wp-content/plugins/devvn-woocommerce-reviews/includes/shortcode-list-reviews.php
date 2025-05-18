<?php

defined("ABSPATH") || exit("No script kiddies please!");
if (!function_exists("devvn_list_reviews")) {
    function devvn_shortcode_get_reviews($args = [])
    {
        global $devvn_review_settings;
        $args = wp_parse_args($args, ["number" => apply_filters("devvn_shortcode_reviews_number", $devvn_review_settings["shortcode_reviews_number"]), "status" => "approve", "post_status" => "publish", "post_type" => "product", "type" => "review", "paged" => 1, "parent" => 0]);
        if (isset($args["has_img"]) && $args["has_img"] == 1) {
            $args["meta_query"] = [["key" => "attachment_img", "compare" => "EXISTS"]];
        }
        $args = apply_filters("devvn_shortcode_reviews_args", $args);
        $key = "devvn_shortcode_reviews_" . md5(json_encode($args));
        if (false === ($out = get_transient($key))) {
            $args2 = $args;
            $args2["paged"] = 0;
            $args2["number"] = "";
            $paging_allReviews = get_comments($args2);
            $paging_count = count($paging_allReviews);
            $paging_perpage = max((int) $args["number"], 1);
            $paging_maxpage = ceil($paging_count / $paging_perpage);
            $out = ["all_comment" => $paging_count, "perpage" => $paging_perpage, "maxpage" => $paging_maxpage, "current_page" => isset($args["paged"]) ? (int) $args["paged"] : 1];
            set_transient($key, $out, DAY_IN_SECONDS);
        }
        $allReviews = get_comments($args);
        $out["comment"] = $allReviews;
        return $out;
    }
    if (!function_exists("check_img_exits")) {
        function check_img_exits($attachment_img)
        {
            if ($attachment_img && is_array($attachment_img)) {
                foreach ($attachment_img as $k => $img) {
                    if (!wp_attachment_is_image($img)) {
                        unset($attachment_img[$k]);
                    }
                }
            }
            return $attachment_img;
        }
    }
    function devvn_shortcode_get_reviews_list($args = [])
    {
        global $devvn_review_settings;
        $label_review = $devvn_review_settings["label_review"];
        $view_product_info_in_list = $devvn_review_settings["view_product_info_in_list"];
        $view_style = isset($args["view_style"]) && $args["view_style"] ? $args["view_style"] : 0;
        $reviews = devvn_shortcode_get_reviews($args);
        if (!isset($reviews["comment"]) || !$reviews["comment"]) {
            return false;
        }
        $review_item = [];
        $stt = 1;
        foreach ($reviews["comment"] as $item) {
            $attachment_img = get_comment_meta($item->comment_ID, "attachment_img", true);
            $attachment_img = check_img_exits($attachment_img);
            $comment_content = wpautop($item->comment_content);
            $user_id = $item->user_id;
            $qtv = "";
            if ($user_id) {
                $user = get_userdata($user_id);
                $user_roles = $user->roles;
                $qtv = devv_check_reviews_admin($user_roles);
            }
            $verified = wc_review_is_from_verified_owner($item->comment_ID);
            $product = wc_get_product($item->comment_post_ID);
            $count_img = $attachment_img ? count($attachment_img) : 0;
            $count_all_comment = $reviews["all_comment"];
            $number_reviews = isset($args["number"]) ? (int) $args["number"] : 0;
            if (!$count_img && isset($args["has_img"]) && $args["has_img"] == 1) {
                $stt++;
            } else {
                ob_start();
                echo "            <div class=\"devvn_list_item\">\r\n                <div class=\"devvn_list_item_box\">\r\n                    <div class=\"devvn_list_item_box_content\">\r\n                        ";
                if ($attachment_img && is_array($attachment_img)) {
                    echo "                        <div class=\"devvn_review_item_image\">\r\n                            <div class=\"devvn_review_image_noslider\">\r\n                                <div class=\"img_item\">\r\n                                    ";
                    echo wp_get_attachment_image($attachment_img[0], "shop_single");
                    echo "                                    ";
                    if (1 < $count_img) {
                        echo "<span>";
                        echo sprintf(_n("%s image", "%s images", $count_img, "devvn-reviews"), number_format_i18n($count_img));
                        echo "</span>";
                    }
                    echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n                        ";
                }
                echo "                        ";
                if ($view_style != 2) {
                    echo "                        <div class=\"devvn_review_item_infor\">\r\n                            <div class=\"meta\">\r\n                                <strong class=\"woocommerce-review__author\">";
                    echo apply_filters("devvn_cmt_author", get_comment_author($item->comment_ID), $item->comment_ID);
                    echo "</strong>\r\n                                <div class=\"devvn_shortcode_review_rating\">\r\n                                ";
                    $rating = intval(get_comment_meta($item->comment_ID, "rating", true));
                    if ($rating && wc_review_ratings_enabled()) {
                        echo wc_get_rating_html($rating);
                    }
                    echo "                                </div>\r\n                                ";
                    if ("yes" === get_option("woocommerce_review_rating_verification_label") && $verified && !$qtv) {
                        if ($label_review) {
                            echo "<em class=\"woocommerce-review__verified verified\">" . $label_review . "</em> ";
                        } else {
                            echo "<em class=\"woocommerce-review__verified verified\">" . sprintf(esc_attr__("Bought at %s", "devvn-reviews"), $_SERVER["SERVER_NAME"]) . "</em> ";
                        }
                    }
                    echo "                            </div>\r\n                            <div class=\"devvn_review_item_fullcontent\">";
                    echo $comment_content;
                    echo "</div>\r\n                        </div>\r\n                        ";
                }
                echo "                        <a href=\"#devvn_reviews_popup_";
                echo $item->comment_ID;
                echo "\" class=\"devvn-reviews-img-popup\"></a>\r\n                    </div>\r\n                    ";
                if ($product && !is_wp_error($product) && $view_style != 2 && $view_product_info_in_list) {
                    echo "                        <div class=\"devvn_review_item_product\">\r\n                            <a href=\"";
                    echo $product->get_permalink();
                    echo "\" title=\"";
                    echo $product->get_name();
                    echo "\">\r\n                                ";
                    echo $product->get_image();
                    echo "                                <div class=\"devvn_review_item_product_info\">\r\n                                    <strong>";
                    echo $product->get_name();
                    echo "</strong>\r\n                                    <span class=\"price\">";
                    echo $product->get_price_html();
                    echo "</span>\r\n                                    <span class=\"devvn-button\">";
                    _e("View product", "devvn-reviews");
                    echo "</span>\r\n                                </div>\r\n                            </a>\r\n                        </div>\r\n                    ";
                }
                echo "                </div>\r\n                ";
                if ($view_style == 2 && $number_reviews < $count_all_comment && $stt == $number_reviews) {
                    echo "                    <div class=\"view_more_img\" data-prod_id=\"";
                    echo $product->get_id();
                    echo "\">";
                    echo sprintf(__("View %s images from customers", "devvn-reviews"), number_format_i18n($count_all_comment));
                    echo "</div>\r\n                ";
                }
                echo "                <div id=\"devvn_reviews_popup_";
                echo $item->comment_ID;
                echo "\" class=\"devvn_list_item_popup view_style_";
                echo $view_style;
                echo " mfp-hide ";
                echo $attachment_img ? "" : "devvn_list_item_no_hasimg";
                echo "\">\r\n                    ";
                if ($attachment_img && is_array($attachment_img)) {
                    echo "                    <div class=\"devvn_list_item_popup_left\">\r\n                        <div class=\"devvn_review_item_image\">\r\n                            <div class=\"devvn_review_image_slider\">\r\n                                ";
                    foreach ($attachment_img as $img) {
                        echo "                                    <div class=\"img_item\"><a href=\"";
                        echo wp_get_attachment_image_url($img, "full");
                        echo "\">";
                        echo wp_get_attachment_image($img, "shop_single");
                        echo "</a></div>\r\n                                ";
                    }
                    echo "                            </div>\r\n                        </div>\r\n                    </div>\r\n                    ";
                }
                echo "                    <div class=\"devvn_list_item_popup_right\">\r\n                        <div class=\"devvn_review_item_infor\">\r\n                            <p class=\"meta\">\r\n                                <strong class=\"woocommerce-review__author\">";
                echo apply_filters("devvn_cmt_author", get_comment_author($item->comment_ID), $item->comment_ID);
                echo "</strong>\r\n                                <div class=\"devvn_shortcode_review_rating\">\r\n                                ";
                $rating = intval(get_comment_meta($item->comment_ID, "rating", true));
                if ($rating && wc_review_ratings_enabled()) {
                    echo wc_get_rating_html($rating);
                }
                echo "                                </div>\r\n                                ";
                if ("yes" === get_option("woocommerce_review_rating_verification_label") && $verified && !$qtv) {
                    if ($label_review) {
                        echo "<em class=\"woocommerce-review__verified verified\">" . $label_review . "</em> ";
                    } else {
                        echo "<em class=\"woocommerce-review__verified verified\">" . sprintf(esc_attr__("Bought at %s", "devvn-reviews"), $_SERVER["SERVER_NAME"]) . "</em> ";
                    }
                }
                if ($user_id && $qtv && $item->comment_parent != 0) {
                    echo "                                    <span class=\"review_qtv\">";
                    _e("Administrator", "devvn-reviews");
                    echo "</span>\r\n                                ";
                }
                echo "                            </p>\r\n                            <div class=\"devvn_review_item_fullcontent\">";
                echo $comment_content;
                echo "</div>\r\n                        </div>\r\n                        ";
                if ($product && !is_wp_error($product) && $view_style != 2) {
                    echo "                        <div class=\"devvn_review_item_product\">\r\n                            <a href=\"";
                    echo $product->get_permalink();
                    echo "\" title=\"";
                    echo $product->get_name();
                    echo "\">\r\n                                ";
                    echo $product->get_image();
                    echo "                                <div class=\"devvn_review_item_product_info\">\r\n                                    <strong>";
                    echo $product->get_name();
                    echo "</strong>\r\n                                    <span class=\"price\">";
                    echo $product->get_price_html();
                    echo "</span>\r\n                                    <span class=\"devvn-button\">";
                    _e("View product", "devvn-reviews");
                    echo "</span>\r\n                                </div>\r\n                            </a>\r\n                        </div>\r\n                        ";
                }
                echo "                    </div>\r\n                </div>\r\n            </div>\r\n            ";
                $review_item[] = ob_get_clean();
                $stt++;
            }
        }
        unset($reviews["comment"]);
        $reviews["review_item"] = $review_item;
        return $reviews;
    }
    add_shortcode("devvn_list_reviews", "devvn_list_reviews");
    function devvn_list_reviews($atts)
    {
        global $devvn_review_settings;
        $pc_column = isset($atts["pc_column"]) && $atts["pc_column"] ? $atts["pc_column"] : $devvn_review_settings["shortcode_pc_column"];
        $tablet_column = isset($atts["tablet_column"]) && $atts["tablet_column"] ? $atts["tablet_column"] : $devvn_review_settings["shortcode_tablet_column"];
        $mobile_column = isset($atts["mobile_column"]) && $atts["mobile_column"] ? $atts["mobile_column"] : $devvn_review_settings["shortcode_mobile_column"];
        $view_style = isset($atts["view_style"]) && $atts["view_style"] ? $atts["view_style"] : 0;
        if ($view_style == 2) {
            $atts["number"] = apply_filters("number_img_reviews_show", 10);
            $atts["has_img"] = 1;
        }
        ob_start();
        $reviews = devvn_shortcode_get_reviews_list($atts);
        if (isset($reviews["review_item"]) && $reviews["review_item"]) {
            if ($view_style == 2) {
                echo "                <div class=\"devvn_shortcode_list_reviews img_reviews_wrap\">\r\n                    <strong>";
                _e("Customer images", "devvn-reviews");
                echo "</strong>\r\n                    <div class=\"img_reviews_box\">\r\n                        ";
                foreach ($reviews["review_item"] as $item) {
                    echo "                            ";
                    echo $item;
                    echo "                        ";
                }
                echo "                    </div>\r\n                    <div class=\"img_reviews_box_full\">\r\n                        <div class=\"devvn_list_popup_content\">\r\n                            <div class=\"devvn_list_popup_title\"></div>\r\n                            <button class=\"devvn_list_popup_close\"><svg class=\"icon-close icon\" viewBox=\"0 0 32 32\"><g id=\"icon-close\"><path class=\"path1\" d=\"M31.708 25.708c-0-0-0-0-0-0l-9.708-9.708 9.708-9.708c0-0 0-0 0-0 0.105-0.105 0.18-0.227 0.229-0.357 0.133-0.356 0.057-0.771-0.229-1.057l-4.586-4.586c-0.286-0.286-0.702-0.361-1.057-0.229-0.13 0.048-0.252 0.124-0.357 0.228 0 0-0 0-0 0l-9.708 9.708-9.708-9.708c-0-0-0-0-0-0-0.105-0.104-0.227-0.18-0.357-0.228-0.356-0.133-0.771-0.057-1.057 0.229l-4.586 4.586c-0.286 0.286-0.361 0.702-0.229 1.057 0.049 0.13 0.124 0.252 0.229 0.357 0 0 0 0 0 0l9.708 9.708-9.708 9.708c-0 0-0 0-0 0-0.104 0.105-0.18 0.227-0.229 0.357-0.133 0.355-0.057 0.771 0.229 1.057l4.586 4.586c0.286 0.286 0.702 0.361 1.057 0.229 0.13-0.049 0.252-0.124 0.357-0.229 0-0 0-0 0-0l9.708-9.708 9.708 9.708c0 0 0 0 0 0 0.105 0.105 0.227 0.18 0.357 0.229 0.356 0.133 0.771 0.057 1.057-0.229l4.586-4.586c0.286-0.286 0.362-0.702 0.229-1.057-0.049-0.13-0.124-0.252-0.229-0.357z\"></path></g></svg></button>\r\n                            <div class=\"devvn_list_popup_box\">\r\n                                <div class=\"devvn_list_box\"></div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                ";
            } else {
                echo "                <div class=\"devvn_shortcode_list_reviews\">\r\n                    <div class=\"devvn_list_box pc_column_";
                echo $pc_column;
                echo " tablet_column_";
                echo $tablet_column;
                echo " mobile_column_";
                echo $mobile_column;
                echo "\">\r\n                        ";
                foreach ($reviews["review_item"] as $item) {
                    echo "                            ";
                    echo $item;
                    echo "                        ";
                }
                echo "                    </div>\r\n                    ";
                if (1 < $reviews["maxpage"] && $reviews["current_page"] < $reviews["maxpage"]) {
                    echo "                        <div class=\"devvn_shortcode_reviews_paging\">\r\n                            <button type=\"button\" class=\"devvn_shortcode_review_loadmore\" data-page=\"";
                    echo $reviews["current_page"] + 1;
                    echo "\" data-options=\"";
                    echo esc_attr(json_encode($atts));
                    echo "\">";
                    _e("View more", "devvn-reviews");
                    echo "</button>\r\n                        </div>\r\n                    ";
                }
                echo "                </div>\r\n                ";
            }
        }
        return ob_get_clean();
    }
}
add_action("wp_ajax_get_shortcode_reviews", "get_shortcode_reviews_func");
add_action("wp_ajax_nopriv_get_shortcode_reviews", "get_shortcode_reviews_func");
add_action("wp_ajax_get_reviews_has_img", "get_reviews_has_img_func");
add_action("wp_ajax_nopriv_get_reviews_has_img", "get_reviews_has_img_func");
function get_shortcode_reviews_func()
{
    $paged = isset($_POST["paged"]) ? intval($_POST["paged"]) : 1;
    $options = isset($_POST["options"]) ? array_map("wp_unslash", (array) $_POST["options"]) : 1;
    if ($paged <= 1) {
        wp_send_json_error("Dữ liệu đầu vào không đúng!");
    }
    $options["paged"] = $paged;
    $reviews = devvn_shortcode_get_reviews_list($options);
    if (isset($reviews["review_item"]) && $reviews["review_item"]) {
        wp_send_json_success($reviews);
    }
    wp_send_json_error("No data");
    exit;
}
function get_reviews_has_img_func()
{
    $prod_id = isset($_POST["prod_id"]) ? intval($_POST["prod_id"]) : "";
    if (!$prod_id) {
        wp_send_json_error(__("Product information is incorrect", "devvn-reviews"));
    }
    $options["post_id"] = $prod_id;
    $options["number"] = "";
    $options["has_img"] = 1;
    $options["view_style"] = 2;
    $reviews = devvn_shortcode_get_reviews_list($options);
    if (isset($reviews["review_item"]) && $reviews["review_item"]) {
        $reviews["title_popup"] = sprintf(__("%s images from customer", "devvn-reviews"), count($reviews["review_item"]));
        wp_send_json_success($reviews);
    }
    wp_send_json_error("No data");
    exit;
}

?>