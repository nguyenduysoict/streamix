<?php

function reviews_get_license_infor()
{
    $license_infor = get_option("reviews_license_infor");
    $html = "";
    if ($license_infor) {
        $date_expire = isset($license_infor->date_expire) ? $license_infor->date_expire : "";
        $html = "<p>" . sprintf(__("Ngày hết hạn: %s", "devvn-reviews"), $date_expire) . "</p>";
    }
    echo $html;
}
function reviews_goto_license()
{
    // if (!reviews_check_license()) {
    //     $url = admin_url("admin.php?page=devvn-woocommerce-reviews&tab=license");
    //     echo "        <script type=\"text/javascript\">\r\n            window.location.href = '";
    //     echo $url;
    //     echo "';\r\n        </script>\r\n        ";
    //     exit;
    // }
}
function reviews_get_license()
{
    global $devvn_review_settings;
    $old_license = isset($devvn_review_settings["license_key"]) ? $devvn_review_settings["license_key"] : "";
    if ($old_license) {
        $license = $old_license;
        $response = reviews_check_license_live($license);
        reviews_set_license($license, $response);
        $devvn_review_settings["license_key"] = "99999";
        unset($devvn_review_settings["license_key"]);
        update_site_option("devvn_reviews_options", $devvn_review_settings);
    } else {
        $license = get_option("reviews_license", "99999");
    }
    return $license;
}
function reviews_check_license_live($license)
{
$output = ["status" => true, "mess" => "Đăng ký license thành công!", "infor" => isset($body["data"]) ? $body["data"] : ""];
    return $output;
}
function reviews_set_license($license, $response = [])
{
    update_option("reviews_license", $license);
    $license_valid = maybe_serialize(["site" => wp_parse_url(home_url(), PHP_URL_HOST), "key" => $license]);
    $response_valid = isset($response["response"]) && $response["response"] ? $response["response"] : [];
    $response_valid["valid"] = md5($license_valid);
    unset($response_valid["http_response"]);
    set_transient("rv_valid", $response_valid, 2 * DAY_IN_SECONDS);
    delete_transient("reviews_check");
    $infor = isset($response["infor"]) && $response["infor"] ? $response["infor"] : "";
    if ($infor) {
        update_option("reviews_license_infor", $infor);
    }
    return true;
}
function reviews_remove_license()
{
    delete_transient("rv_valid");
    delete_option("reviews_license_infor");
    set_transient("reviews_check", true);
}

?>