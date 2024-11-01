<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:51
 */

class Woocommerce_Custom_Registration_Form_Elements_Rendering
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
        $this->loader->add_action('woocommerce_register_form_start', $this, 'form_elements_rendering_registration');
        $this->loader->add_action('show_user_profile', $this, 'extra_user_profile_fields_admin');
        $this->loader->add_action('edit_user_profile', $this, 'extra_user_profile_fields_admin');
        $this->loader->add_action('woocommerce_edit_account_form', $this, 'extra_user_profile_fields_public');
        $this->loader->add_filter('woocommerce_checkout_fields', $this, 'form_elements_rendering_checkout');
    }

    public function form_elements_rendering_registration()
    {

        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
            'numberposts' => -1,
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'woo_custom_register_element_order'
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);
            $classname::elementRendering($element);
        }

    }

    public function form_elements_rendering_checkout($fields)
    {

        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
            'numberposts' => -1,
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'woo_custom_register_element_order'
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);
            $classname::elementRenderingCheckout($element, $fields);
        }

        return $fields;

    }

    public function extra_user_profile_fields_admin($user)
    {
        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
            'numberposts' => -1,
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'woo_custom_register_element_order'
        );
        $query = new WP_Query($args);

        ?>
        <h3><?php _e("Woocommerce extra profile information", Woocommerce_Custom_Registration_i18n::$textdomain); ?></h3>

        <table class="form-table">
            <?php
            foreach ($query->get_posts() as $post) {
                $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
                $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);

                $classname::elementAdminUserProfileRendering($element, $user->ID);
            }
            ?>
        </table>
        <?php
    }

    public function extra_user_profile_fields_public()
    {
        $args = array(
            'post_type' => Woocommerce_Custom_Registration_Form_Elements_Post_Type::$cpt,
            'post_status' => 'publish',
            'numberposts' => -1,
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'woo_custom_register_element_order'
        );
        $query = new WP_Query($args);

        foreach ($query->get_posts() as $post) {
            $element = get_post_meta($post->ID, 'woo_custom_register_element', true);
            $classname = Woocommerce_Custom_Registration_Utils::getClassNameByElement($element['type']);

            $classname::elementPublicUserProfileRendering($element, get_current_user_id());
        }
    }
}