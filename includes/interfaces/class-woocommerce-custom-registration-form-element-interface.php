<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:42
 */

interface Woocommerce_Custom_Registration_iForm_Element
{
    public static function getType();

    public static function getLabel();

    public static function getSettings($current_settings);

    public static function saveSettings($post_id);

    public static function elementRendering($element);

    public static function elementRenderingCheckout($element, &$fields);

    public static function elementPublicUserProfileRendering($element, $customer_id);

    public static function elementAdminUserProfileRendering($element, $customer_id);

    public static function elementValidation($element, $validation_errors);

    public static function elementSave($element, $customer_id);
}