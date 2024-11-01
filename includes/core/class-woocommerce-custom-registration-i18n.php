<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.mugaict.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Custom_Registration
 * @subpackage Woocommerce_Custom_Registration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Custom_Registration
 * @subpackage Woocommerce_Custom_Registration/includes
 * @author     Umberto Russo <info@mugaict.com>
 */
class Woocommerce_Custom_Registration_i18n
{

    public static $textdomain = 'woo-custom-registration';

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            self::$textdomain,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }


}
