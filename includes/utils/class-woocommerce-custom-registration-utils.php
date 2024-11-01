<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 11:57
 */

class Woocommerce_Custom_Registration_Utils
{
    public static function requireTriggers()
    {
        $files = array_filter(glob(WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/form-elements/*'), 'is_file');
        foreach ($files as $file) {
            require_once $file;
        }
    }

    public static function getElementsClassName()
    {
        $return = array();
        $classnames = array_filter(glob(WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/form-elements/*'), 'is_file');
        foreach ($classnames as $classname) {
            $classname = basename($classname);
            $classname = str_replace('class-', '', $classname);
            $classname = str_replace('.php', '', $classname);
            $classname = explode('-', $classname);

            $classname = array_map(function ($item) {
                return ucfirst($item);
            }, $classname);

            $classname = implode('_', $classname);

            if (in_array("Woocommerce_Custom_Registration_iForm_Element", class_implements($classname))) {
                $return[] = $classname;
            }
        }

        return $return;
    }

    public static function getClassNameByElement($element)
    {
        $classnames = array_filter(glob(WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/form-elements/*'), 'is_file');
        foreach ($classnames as $classname) {
            $classname = basename($classname);
            $classname = str_replace('class-', '', $classname);
            $classname = str_replace('.php', '', $classname);
            $classname = explode('-', $classname);

            $classname = array_map(function ($item) {
                return ucfirst($item);
            }, $classname);

            $classname = implode('_', $classname);

            if (in_array("Woocommerce_Custom_Registration_iForm_Element", class_implements($classname))) {
                if ($element === $classname::getType()) {
                    return $classname;
                    break;
                }

            }
        }

        return '';
    }

    public static function slugify($text)
    {
        return str_replace('-', '_', sanitize_title($text));
    }
}