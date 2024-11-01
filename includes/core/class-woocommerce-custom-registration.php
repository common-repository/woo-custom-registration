<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.mugaict.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Custom_Registration
 * @subpackage Woocommerce_Custom_Registration/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Custom_Registration
 * @subpackage Woocommerce_Custom_Registration/includes
 * @author     Umberto Russo <info@mugaict.com>
 */
class Woocommerce_Custom_Registration
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Custom_Registration_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woocommerce-custom-registration';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Custom_Registration_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Custom_Registration_i18n. Defines internationalization functionality.
     * - Woocommerce_Custom_Registration_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Custom_Registration_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/core/class-woocommerce-custom-registration-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/core/class-woocommerce-custom-registration-i18n.php';

        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/registration-form/class-woocommerce-custom-registration-form-elements-post-type.php';
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/registration-form/class-woocommerce-custom-registration-form-elements-handler.php';
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/registration-form/class-woocommerce-custom-registration-form-elements-rendering.php';
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/registration-form/class-woocommerce-custom-registration-form-elements-validating.php';
        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/registration-form/class-woocommerce-custom-registration-form-elements-save.php';

        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/interfaces/class-woocommerce-custom-registration-form-element-interface.php';

        require_once WOO_CUSTOM_REGISTRATION_DIRECTORY_PATH . 'includes/utils/class-woocommerce-custom-registration-utils.php';

        Woocommerce_Custom_Registration_Utils::requireTriggers();

        $this->loader = new Woocommerce_Custom_Registration_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woocommerce_Custom_Registration_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Woocommerce_Custom_Registration_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_hooks()
    {

        new Woocommerce_Custom_Registration_Form_Elements_Post_Type($this->version, $this->plugin_name, $this->loader);
        new Woocommerce_Custom_Registration_Form_Elements_Handler($this->version, $this->plugin_name, $this->loader);
        new Woocommerce_Custom_Registration_Form_Elements_Rendering($this->version, $this->plugin_name, $this->loader);
        new Woocommerce_Custom_Registration_Form_Elements_Validating($this->version, $this->plugin_name, $this->loader);
        new Woocommerce_Custom_Registration_Form_Elements_Save($this->version, $this->plugin_name, $this->loader);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woocommerce_Custom_Registration_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
