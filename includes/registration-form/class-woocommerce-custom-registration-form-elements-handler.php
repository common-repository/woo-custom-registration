<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:51
 */

class Woocommerce_Custom_Registration_Form_Elements_Handler
{
    private $loader;
    private $version;
    private $plugin_name;

    public function __construct($version, $plugin_name, $loader)
    {
        $this->loader = $loader;
        $this->version = $version;
        $this->plugin_name = $plugin_name;
        $this->init_hook();
    }

    private function init_hook()
    {
        $this->loader->add_action('wp_ajax_fetch_element_settings', $this, 'fetch_element_settings');
        $this->loader->add_action('save_post_' . Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt, $this, 'save_meta_data');
    }

    public function fetch_element_settings()
    {

        check_ajax_referer('fetch_element_nonce', 'security');

        if (!isset($_POST['element'])) {
            wp_die();
        }

        $element = sanitize_text_field($_POST['element']);

        $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element);

        $current_settings = get_post_meta($_POST['post_id'], 'woo_custom_register_element', true);

        echo json_encode(array(
            'settings' => $classname::getSettings($current_settings)
        ));

        wp_die();
    }

    public function save_meta_data($post_id)
    {
        if (!isset($_POST['woo_custom_registration_save_meta_data_nonce']) || !wp_verify_nonce($_POST['woo_custom_registration_save_meta_data_nonce'], 'woo_custom_registration_save_meta_data')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        if (!isset($_POST['woo_custom_form_element_type']))
            return $post_id;

        $element = sanitize_text_field($_POST['woo_custom_form_element_type']);
        $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element);
        $classname::saveSettings($post_id);
    }

}