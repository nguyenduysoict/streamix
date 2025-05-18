<?php

defined("ABSPATH") || exit("No script kiddies please!");
$reviews_license = reviews_get_license();
echo "<table class=\"form-table\">\r\n    <tbody>\r\n    <tr>\r\n        <th scope=\"row\"><label for=\"license\">License Key</label></th>\r\n        <td>\r\n            <input type=\"text\" value=\"";
echo $reviews_license;
echo "\" id=\"reviews_license\" name=\"reviews_license[license]\"><br>\r\n            ";
reviews_get_license_infor();
echo "<br>\r\n            <button type=\"button\" class=\"button button-primary reviews_active_license\" data-nonce=\"";
echo wp_create_nonce("active_license_reviews");
echo "\">Active License</button>\r\n            <span class=\"spinner\"></span>\r\n        </td>\r\n    </tr>\r\n    </tbody>\r\n</table>\r\n\r\n";

?>