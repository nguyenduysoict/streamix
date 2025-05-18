<?php

/**
 * Class FSLM_APIv3_Responses
 */
class FSLM_APIv3_Responses
{

    const INVALID_LICENSE_KEY = array(
        "result" => "error",
        "code" => "100",
        "message" => "Invalid license key"
    );

    const LICENSE_KEY_ACTIVATED = array(
        "result" => "success",
        "code" => "300",
        "message" => "License key activated"
    );

    const ACTIVATION_MAX_REACHED = array(
        "result" => "error",
        "code" => "350",
        "message" => "Maximum number of activation reached"
    );

    const LICENSE_KEY_DEACTIVATED = array(
        "result" => "success",
        "code" => "400",
        "message" => "License key deactivated"
    );

    const LICENSE_ALREADY_INACTIVE = array(
        "result" => "success",
        "code" => "450",
        "message" => "License key already inactive"
    );

    const VALID = array(
        "result" => "success",
        "code" => "500",
        "message" => "Valid license key"
    );

    const EXPIRED = array(
        "result" => "error",
        "code" => "550",
        "message" => "Expired license key"
    );

    const ERROR = array(
        "result" => "error",
        "code" => "000",
        "message" => "An error has occurred please retry"
    );

    const INVALID_DEVICE_ID = array(
        "result" => "error",
        "code" => "650",
        "message" => "Invalid device ID"
    );


    const DEVICE_ID_REQUIRED_DEACTIVATION = array(
        "result" => "error",
        "code" => "700",
        "message" => "Device ID required, this license keys was activated with a device ID, a device ID is required to deactivate it"
    );

    const DEVICE_ID_REQUIRED_ACTIVATION = array(
        "result" => "error",
        "code" => "750",
        "message" => "Device ID required, this license keys was activated with a device ID, a device ID is required to activate it again"
    );

    const LICENSE_STATUS_UPDATED = array(
        "result" => "success",
        "code" => "850",
        "message" => "License status updated"
    );

    const NOT_OWNER = array(
        "result" => "error",
        "code" => "900",
        "message" => "The authenticated user doesn't own this license key"
    );

    const UNREGISTERED_LICENSE_KEY_NOT_FOUND = array(
        "result" => "error",
        "code" => "910",
        "message" => "Unregistered license key not found"
    );

    const UNREGISTERED_LICENSE_KEY_ASSIGNED = array(
        "result" => "success",
        "code" => "915",
        "message" => "Unregistered license key assigned"
    );

    const LICENSE_UPDATED = array(
        "result" => "success",
        "code" => "950",
        "message" => "License key updated"
    );

    const LICENSE_DELETED = array(
        "result" => "success",
        "code" => "851",
        "message" => "License key deleted"
    );

    const INVALID_PRODUCT_ID = array(
        "result" => "error",
        "code" => "960",
        "message" => "Invalid product ID"
    );

    const INVALID_VARIATION_ID = array(
        "result" => "error",
        "code" => "961",
        "message" => "Invalid variation ID"
    );

    const LICENSE_CREATED = array(
        "result" => "success",
        "code" => "962",
        "message" => "License key(s) created"
    );

    const META_KEY_ADDED = array(
        "result" => "success",
        "code" => "970",
        "message" => "License key meta added"
    );

    const META_KEY_ALREADY_EXISTS = array(
        "result" => "error",
        "code" => "971",
        "message" => "License key meta already exists"
    );

    const META_KEY_UPDATED = array(
        "result" => "success",
        "code" => "972",
        "message" => "License key meta updated"
    );

    const META_KEY_DOESNT_EXIST = array(
        "result" => "error",
        "code" => "973",
        "message" => "License key meta doesn't exist"
    );

    const META_KEY_DELETED = array(
        "result" => "success",
        "code" => "974",
        "message" => "License key meta deleted"
    );

    const META_KEY_ADMIN_ONLY = array(
        "result" => "error",
        "code" => "975",
        "message" => "The authenticated user can't update/delete this key, admin only meta key"
    );

    const INVALID_ORDER_ID = array(
        "result" => "error",
        "code" => "980",
        "message" => "Invalid order ID"
    );

    const ORDER_INVALID_PRODUCT = array(
        "result" => "error",
        "code" => "981",
        "message" => "The order doesn't have an item with the given product ID"
    );

    const ORDER_INVALID_VARIATION = array(
        "result" => "error",
        "code" => "982",
        "message" => "The order doesn't have an item with the given variation ID"
    );

}
