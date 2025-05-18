<?php

if (!function_exists("Reviews_Schema_Support")) {
    function Reviews_Schema_Support()
    {
        return Reviews_Schema_Support_Class::instance();
    }
}
class Reviews_Schema_Support_Class
{
    protected static $_instance = NULL;
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct()
    {
        add_filter("woocommerce_structured_data_product", [$this, "devvn_reviews_custom_woocommerce_structured_data_product"]);
        add_filter("woocommerce_structured_data_product_offer", [$this, "woocommerce_structured_data_product_offer"]);
        add_filter("wpseo_schema_product", [$this, "wpseo_schema_product"]);
        add_filter("rank_math/snippet/rich_snippet_product_entity", [$this, "rich_snippet_product_entity"]);
    }
    public function devvn_reviews_custom_woocommerce_structured_data_product($data)
    {
        global $product;
        $sku = $product->get_sku();
        $id = $product->get_id();
        $brand = devvn_reviews()->get_schema_option("brand");
        $brand_default = devvn_reviews()->get_schema_option("brand_default");
        if (!isset($data["brand"]) || isset($data["brand"]) && $data["brand"] == "") {
            if ($brand != "null") {
                if ($product->get_attribute($brand)) {
                    $brand_value = $product->get_attribute($brand);
                } else {
                    $term_obj_list = get_the_terms($product->get_id(), $brand);
                    $brand_value = implode(", ", wp_list_pluck($term_obj_list, "name"));
                }
                $data["brand"] = ["@type" => "Brand", "name" => $brand_value];
            } else {
                if ($brand_default) {
                    $data["brand"] = ["@type" => "Brand", "name" => $brand_default];
                }
            }
        }
        if (!isset($data["mpn"]) || isset($data["mpn"]) && $data["mpn"] == "") {
            $data["mpn"] = $sku ? $sku : $id;
        }
        if (!isset($data["id"]) || isset($data["id"]) && $data["id"] == "") {
            $data["id"] = $id;
        }
        return $data;
    }
    public function woocommerce_structured_data_product_offer($offers)
    {
        if (!isset($offers["priceValidUntil"]) || isset($offers["priceValidUntil"]) && $offers["priceValidUntil"] == "") {
            $offers["priceValidUntil"] = date_i18n("Y-01-01", strtotime("+2 year"));
        }
        return $offers;
    }
    public function wpseo_schema_product($data)
    {
        if (isset($data["offers"])) {
            foreach ($data["offers"] as $key => $offer) {
                if (!isset($offer["priceValidUntil"]) || isset($offer["priceValidUntil"]) && $offer["priceValidUntil"] == "") {
                    $data["offers"][$key]["priceValidUntil"] = date_i18n("Y-01-01", strtotime("+2 year"));
                }
            }
        }
        return $data;
    }
    public function rich_snippet_product_entity($entity)
    {
        global $product;
        if (!is_singular("product") || !$product || is_wp_error($product)) {
            return $entity;
        }
        if (!isset($entity["offers"]["priceValidUntil"]) || isset($entity["offers"]["priceValidUntil"]) && $entity["offers"]["priceValidUntil"] == "") {
            $entity["offers"]["priceValidUntil"] = date_i18n("Y-01-01", strtotime("+2 year"));
        }
        if (!isset($entity["sku"]) || isset($entity["sku"]) && $entity["sku"] == "") {
            $entity["sku"] = $product->get_sku() ? $product->get_sku() : $product->get_id();
        }
        if (!isset($entity["mpn"]) || isset($entity["mpn"]) && $entity["mpn"] == "") {
            $entity["mpn"] = $product->get_sku() ? $product->get_sku() : $product->get_id();
        }
        if (!isset($entity["id"]) || isset($entity["id"]) && $entity["id"] == "") {
            $entity["id"] = $product->get_id() ? $product->get_id() : NULL;
        }
        return $entity;
    }
}

?>