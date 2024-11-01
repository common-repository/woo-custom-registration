<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.mugaict.com
 * @since             1.0.0
 * @package           Woocommerce_Custom_Registration
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce custom registration
 * Plugin URI:        http://www.mugaict.com
 * Description:       This plugin allows you to customize the WooCommerce registration form and collect extra user data.
 * Version:           1.0.0
 * Author:            MUGA ICT
 * Author URI:        https://www.mugaict.com/chi-siamo/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-custom-registration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

include_once(ABSPATH.'wp-admin/includes/plugin.php');

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOO_CUSTOM_REGISTRATION_VERSION', '1.0.0');
defined('WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH') ?: define('WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH', plugin_dir_path(__FILE__));
defined('WOO_CUSTOM_REGISTRATION_DIRECTORY_URL') ?: define('WOO_CUSTOM_REGISTRATION_DIRECTORY_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-custom-registration-activator.php
 */
function activate_woocommerce_custom_registration()
{
    require_once plugin_dir_path(__FILE__) . 'includes/core/class-woocommerce-custom-registration-activator.php';
    Woocommerce_Custom_Registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-custom-registration-deactivator.php
 */
function deactivate_woocommerce_custom_registration()
{
    require_once plugin_dir_path(__FILE__) . 'includes/core/class-woocommerce-custom-registration-deactivator.php';
    Woocommerce_Custom_Registration_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woocommerce_custom_registration');
register_deactivation_hook(__FILE__, 'deactivate_woocommerce_custom_registration');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/core/class-woocommerce-custom-registration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_custom_registration()
{

    $plugin = new Woocommerce_Custom_Registration();
    $plugin->run();

}

function woocommerce_custom_registration_dependency_missing(){
    ?>
    <div class="notice notice-error">
        <p><?php echo '<strong>WooCommerce Custom Registration needs <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">"WooCommerce"</a> in order to work, please install and activate this plugin.</strong>'; ?></p>
    </div>
    <?php
}

if ( ! is_plugin_active('woocommerce/woocommerce.php') ) {
    add_action( 'admin_notices', 'woocommerce_custom_registration_dependency_missing' );
}else {
    run_woocommerce_custom_registration();
}
