<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:51
 */

class Woocommerce_Custom_Registration_Form_Elements_Save
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
        $this->loader->add_action('woocommerce_created_customer', $this, 'form_elements_save');
        $this->loader->add_action('woocommerce_save_account_details', $this, 'form_elements_save');
    }

    public function form_elements_save($customer_id)
    {

        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);
            $classname::elementSave($element, $customer_id);
        }
    }

}