<?php

defined("ABSPATH") || exit("No script kiddies please!");
reviews_goto_license();
echo "<form method=\"post\" action=\"options.php\" novalidate=\"novalidate\">\r\n    ";
settings_fields("schema-group");
wp_nonce_field("admin_devvn_reviews_nonce_action", "admin_devvn_reviews_nonce");
echo "    <table class=\"form-table\">\r\n        <tbody>\r\n        <tr>\r\n            <th scope=\"row\"><label for=\"img_size\">";
_e("Kích hoạt tính năng hỗ trợ Schema", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                <p>\r\n                    <label style=\"margin-right: 10px\"><input type=\"radio\" name=\"";
echo $this->_schemaOptionNamePrefix;
echo "active\" value=\"1\" id=\"";
echo $this->_schemaOptionNamePrefix;
echo "active\" ";
checked(intval($this->get_schema_option("active")), 1, true);
echo "> Có</label>\r\n                    <label><input type=\"radio\" name=\"";
echo $this->_schemaOptionNamePrefix;
echo "active\" value=\"0\" id=\"";
echo $this->_schemaOptionNamePrefix;
echo "active\" ";
checked(intval($this->get_schema_option("active")), 0, true);
echo "> Không</label><br>\r\n                    <small style=\"color: red\">Nếu bạn dùng Rankmath hoặc Yoast Seo: Woocommerce addon thì không nên bật tính năng này</small>\r\n                </p>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <th scope=\"row\"><label for=\"img_size\">";
_e("Brand", "devvn-reviews");
echo "</label></th>\r\n            <td>\r\n                Theo danh mục hoặc biến thể <select name=\"";
echo $this->_schemaOptionNamePrefix;
echo "brand\">\r\n                    <option value=\"null\">Không xác định</option>\r\n                    ";
foreach ($this->get_taxonomy_labels() as $k => $v) {
    echo "                        <option value=\"";
    echo $k;
    echo "\" ";
    selected($this->get_schema_option("brand"), $k, true);
    echo ">";
    echo $v;
    echo "</option>\r\n                    ";
}
echo "                </select>\r\n                Hoặc brand cố định <input style=\" width: 130px; \" type=\"text\" name=\"";
echo $this->_schemaOptionNamePrefix;
echo "brand_default\" value=\"";
echo $this->get_schema_option("brand_default");
echo "\">\r\n                <small>Nếu KHÔNG chọn brand theo danh mục hoặc biến thể thì nhập vào ô brand cố định. Như vậy toàn bộ sản phẩm sẽ theo brand đã nhập</small>\r\n            </td>\r\n        </tr>\r\n        </tbody>\r\n    </table>\r\n    ";
do_settings_sections("schema-group", "default");
echo "    ";
submit_button();
echo "</form>\r\n";

?>