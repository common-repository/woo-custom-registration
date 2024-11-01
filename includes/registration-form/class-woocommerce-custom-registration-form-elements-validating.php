<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:51
 */

class Woocommerce_Custom_Registration_Form_Elements_Validating
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
        $this->loader->add_action('woocommerce_register_post', $this, 'form_elements_validating', 10, 3);
        $this->loader->add_action('woocommerce_save_account_details_errors', $this, 'form_elements_validating_user_profile', 10);
    }

    public function form_elements_validating($username, $email, $validation_errors)
    {

        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);
            $classname::elementValidation($element, $validation_errors);
        }
    }

    public function form_elements_validating_user_profile($validation_errors)
    {

        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);
            $classname::elementValidation($element, $validation_errors);
        }
    }

}