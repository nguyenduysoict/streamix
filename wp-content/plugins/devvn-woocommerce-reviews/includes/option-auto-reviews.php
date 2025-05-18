<?php

defined("ABSPATH") || exit("No script kiddies please!");
reviews_goto_license();
echo "\r\n<div class=\"wrap_row\">\r\n<div class=\"wrap_left\">\r\n<form method=\"post\" action=\"options.php\" novalidate=\"novalidate\">\r\n    ";
settings_fields("auto-reviews-group");
wp_nonce_field("admin_devvn_reviews_nonce_action", "admin_devvn_reviews_nonce");
echo "    <table class=\"form-table\">\r\n        <tbody>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Danh sách user", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                <textarea class=\"textarea\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "user\">";
echo esc_textarea($this->get_autoreviews_option("user"));
echo "</textarea>\r\n                <br>\r\n                <small>Định dạng: Tên | SĐT | Email<br> SĐT và email không bắt buộc. Mỗi user một dòng</small>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Nội dung", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                <textarea class=\"textarea\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "comment\">";
echo esc_textarea($this->get_autoreviews_option("comment"));
echo "</textarea>\r\n                <br>\r\n                <small>Định dạng: Nội dung reviews | Số sao đánh giá | Danh sách hình ảnh<br> Hình ảnh bằng link ảnh, cách nhau dấu phẩy (,). Mỗi nội dung 1 dòng</small>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Số reviews / 1 sản phẩm", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                Từ <input type=\"number\" min=\"1\" step=\"1\" style=\"width: 58px;\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "number_reviews\" value=\"";
echo $this->get_autoreviews_option("number_reviews");
echo "\" id=\"auto_reviews_number_reviews\"> đến <input type=\"number\" min=\"1\" step=\"1\" style=\"width: 58px;\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "number_reviews_to\" value=\"";
echo $this->get_autoreviews_option("number_reviews_to");
echo "\" id=\"auto_reviews_number_reviews_to\"> reviews/sản phẩm\r\n                <br>\r\n                <small>Số reviews ngẫu nhiên cho 1 sản phẩm</small>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Danh mục sản phẩm", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                ";
add_filter("wp_dropdown_cats", "devvn_dropdown_filter", 10, 2);
$cat_review = $this->get_autoreviews_option("cat_reviews");
$cat_review = $cat_review ? implode(",", $cat_review) : "";
wp_dropdown_categories(["taxonomy" => "product_cat", "depth" => 3, "hierarchical" => 1, "name" => $this->_autoReviewsOptionNamePrefix . "cat_reviews[]", "id" => "cat_reviews", "selected" => $cat_review]);
remove_filter("wp_dropdown_cats", "devvn_dropdown_filter", 10, 2);
echo "                <br>\r\n                <small>Chọn danh mục cụ thể bạn muốn đánh giá tự động. Nếu không chọn danh mục mặc định hệ thống sẽ đánh giá cho toàn bộ sản phẩm</small>\r\n            </td>\r\n        </tr>\r\n\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Chọn sản phẩm", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                ";
$prod_reviews = $this->get_autoreviews_option("prod_reviews");
echo "                <select name=\"";
echo $this->_autoReviewsOptionNamePrefix . "prod_reviews[]";
echo "\" id=\"prod_reviews\" multiple>\r\n                    ";
if ($prod_reviews) {
    echo "                        ";
    foreach ($prod_reviews as $item) {
        $product = wc_get_product($item);
        if ($product && !is_wp_error($product)) {
            $ten = $product->get_name();
            $masp = $product->get_id();
            echo "                                <option value=\"";
            echo $masp;
            echo "\" selected=\"selected\">";
            echo "#" . $masp . " - " . $ten;
            echo "</option>\r\n                            ";
        }
        echo "                        ";
    }
    echo "                    ";
}
echo "                </select>\r\n                <br>\r\n                <small>Chọn sản phẩm cụ thể bạn muốn đánh giá tự động. Nếu không chọn sản phẩm mặc định hệ thống sẽ đánh giá cho toàn bộ sản phẩm</small>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("SP chưa có reviews", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                ";
$no_reviews = $this->get_autoreviews_option("no_reviews");
echo "                <label><input type=\"checkbox\" name=\"";
echo $this->_autoReviewsOptionNamePrefix . "no_reviews";
echo "\" value=\"1\" id=\"no_reviews\" ";
checked($no_reviews, 1, true);
echo ">Chỉ đánh giá sản phẩm chưa có reviews</label>\r\n            </td>\r\n        </tr>\r\n\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Ngày đánh giá", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                Từ <input type=\"text\" style=\"width: 100px;\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "date_reviews_form\" value=\"";
echo $this->get_autoreviews_option("date_reviews_form");
echo "\" id=\"date_reviews_form\"> đến <input type=\"text\" style=\"width: 100px;\" name=\"";
echo $this->_autoReviewsOptionNamePrefix;
echo "date_reviews_to\" value=\"";
echo $this->get_autoreviews_option("date_reviews_to");
echo "\" id=\"date_reviews_to\">\r\n                <br>\r\n                <small>Nếu bỏ trống thì sẽ lấy ngẫu nhiên trong 2 ngày gần nhất. Từ ";
echo date_i18n("d/m/Y", strtotime(" -2 day"));
echo " đến hết ngày ";
echo date_i18n("d/m/Y");
echo "<br>\r\n                Nếu ngày của reviews lớn hơn ngày hiện tại sẽ được lên lịch và tự động đăng reviews trong tương lai\r\n                </small>\r\n\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label>";
_e("Hành động", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                Chạy <input type=\"number\" value=\"20\" min=\"0\" step=\"1\" id=\"auto_per_page\" style=\"width: 58px;\"> sản phẩm 1 lần cho tới hết. <button type=\"button\" class=\"button button-link-delete go_auto_reviews\">Chạy đánh giá tự động</button>\r\n                <span class=\"mess\"></span><br>\r\n                <small>Lưu thay đổi trước khi ấn nút này. Khi ấn nút này quá trình đánh giá tự động sẽ được chạy tự động cho tới khi thêm đánh giá vào hết sản phẩm</small>\r\n            </td>\r\n        </tr>\r\n        </tbody>\r\n    </table>\r\n    ";
do_settings_sections("auto-reviews-group", "default");
echo "    ";
submit_button();
echo "</form>\r\n</div>\r\n<div class=\"wrap_right\">\r\n    <h2>Hướng dẫn sử dụng</h2>\r\n    <div class=\"videoWrapper\"><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/hM_E4pQZ_bU\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe></div>\r\n</div>\r\n</div>";
function devvn_dropdown_filter($output, $r)
{
    $output = preg_replace("/<select (.*?)>/", "<select \$1 size=\"5\" multiple>", $output);
    foreach (array_map("trim", explode(",", $r["selected"])) as $value) {
        $output = str_replace("value=\"" . $value . "\"", "value=\"" . $value . "\" selected", $output);
    }
    return $output;
}

?>