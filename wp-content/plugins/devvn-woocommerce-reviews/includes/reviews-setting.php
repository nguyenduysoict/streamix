<?php

defined("ABSPATH") || exit("No script kiddies please!");
global $devvn_review_settings;
$current_tab = isset($_REQUEST["tab"]) ? esc_html($_REQUEST["tab"]) : "general";
echo "<div class=\"wrap devvn_reviews_wrap\">\r\n    <h1>";
_e("Cài đặt bình luận và đánh giá cho Woocommerce", "devvn-reviews");
echo "</h1>\r\n    <p><span style=\"color: red;\">Chú ý:</span> Đọc thêm về phần chú ý cài đặt để plugin hoạt động chính xác hơn. <a href=\"https://levantoan.com/san-pham/devvn-woocommerce-reviews/#chu-y\" rel=\"nofollow\" target=\"_blank\">Đọc tại đây</a> </p>\r\n\r\n    <h2 class=\"nav-tab-wrapper devvn-nav-tab-wrapper\">\r\n        <a href=\"?page=devvn-woocommerce-reviews&tab=general\" class=\"nav-tab ";
echo $current_tab == "general" ? "nav-tab-active" : "";
echo "\"> ";
_e("Cài đặt chung", "devvn-reviews");
echo "</a>\r\n        <a href=\"?page=devvn-woocommerce-reviews&tab=auto-reviews\" class=\"nav-tab ";
echo $current_tab == "auto-reviews" ? "nav-tab-active" : "";
echo "\"> ";
_e("Tự động đánh giá", "devvn-reviews");
echo "</a>\r\n        <a href=\"?page=devvn-woocommerce-reviews&tab=schema\" class=\"nav-tab ";
echo $current_tab == "schema" ? "nav-tab-active" : "";
echo "\"> ";
_e("Schema Product", "devvn-reviews");
echo "</a>\r\n        <a href=\"?page=devvn-woocommerce-reviews&tab=license\" class=\"nav-tab ";
echo $current_tab == "license" ? "nav-tab-active" : "";
echo "\"> ";
_e("License", "devvn-reviews");
echo "</a>\r\n        <a href=\"?page=devvn-woocommerce-reviews&tab=how-to-use\" class=\"nav-tab ";
echo $current_tab == "how-to-use" ? "nav-tab-active" : "";
echo "\"> ";
_e("Hướng dẫn sử dụng", "devvn-reviews");
echo "</a>\r\n        <a href=\"";
echo esc_url(admin_url("update-core.php?force-check=1"));
echo "\" style=\" font-weight: 400; text-decoration: none; padding: 5px 10px; font-size: 14px; line-height: 1.71428571; display: inline-block; \" class=\"";
echo $current_tab == "check_update" ? "nav-tab-active" : "";
echo "\"> ";
_e("Check update", "devvn-reviews");
echo "</a>\r\n    </h2>\r\n\r\n    ";
switch ($current_tab) {
    case "general":
        include "setting-general.php";
        break;
    case "license":
        include "reviews-license.php";
        break;
    case "schema":
        include "schema-options.php";
        break;
    case "auto-reviews":
        include "option-auto-reviews.php";
        break;
    case "how-to-use":
        include "option-how-to-use.php";
        break;
    default:
        echo "\r\n    <p>Plugin được phát triển bởi <a href=\"https://levantoan.com\" rel=\"nofollow\" target=\"_blank\">Lê Văn Toản</a></p>\r\n</div>";
}

?>