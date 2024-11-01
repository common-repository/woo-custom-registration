<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 10:51
 */

class Woocommerce_Custom_Registration_Form_Elements_Post_Type
{
    private $loader;
    public static $cpt = 'woo-custom-regis';
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
        $this->loader->add_action('init', $this, 'register_cpt');
        $this->loader->add_action('add_meta_boxes', $this, 'register_metabox');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'add_style_js');
        $this->loader->add_filter('manage_' . self::$cpt . '_posts_columns', $this, 'columns');
        $this->loader->add_filter('manage_' . self::$cpt . '_posts_custom_column', $this, 'custom_columns', 10, 2);
    }

    public function register_cpt()
    {
        $args = array(
            'label' => __('Registration form', Woocommerce_Custom_Registration_i18n::$textdomain),
            'description' => '',
            'labels' => array(
                'name' => _x('Form elements', 'Post Type General Name', Woocommerce_Custom_Registration_i18n::$textdomain),
                'singular_name' => _x('Form element', 'Post Type Singular Name', Woocommerce_Custom_Registration_i18n::$textdomain),
                'menu_name' => __('Registration form', Woocommerce_Custom_Registration_i18n::$textdomain),
                'parent_item_colon' => __('Parent Item:', Woocommerce_Custom_Registration_i18n::$textdomain),
                'all_items' => __('Registration form', Woocommerce_Custom_Registration_i18n::$textdomain),
                'view_item' => __('View Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'add_new_item' => __('Add New Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'add_new' => __('Add New Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'edit_item' => __('Edit Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'update_item' => __('Update Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'search_items' => __('Search Form element', Woocommerce_Custom_Registration_i18n::$textdomain),
                'not_found' => __('Not found', Woocommerce_Custom_Registration_i18n::$textdomain),
                'not_found_in_trash' => __('Not found in Trash', Woocommerce_Custom_Registration_i18n::$textdomain),
            ),
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'capabilities' => array('create_posts' => true),
            'map_meta_cap' => true,
            'show_in_menu' => 'woocommerce'
        );

        register_post_type(self::$cpt, $args);
    }

    public function register_metabox()
    {
        add_meta_box(self::$cpt . '-meta-box-form-element', __('Settings', Woocommerce_Custom_Registration_i18n::$textdomain), array($this, 'meta_box'), self::$cpt, 'normal', 'high');
    }

    public function meta_box(WP_Post $post)
    {
        $settings = get_post_meta($post->ID, 'woo_custom_register_element', true);
        $order = get_post_meta($post->ID, 'woo_custom_register_element_order', true);
        ?>
        <div class="woocommerce-custom-registration-form-block">
            <label>Type</label>
            <select name="woo_custom_form_element_type" id="woo_custom_form_element_type">
                <?php
                foreach (Woocommerce_Custom_Registration_Utils::getElementsClassName() as $elementClass) {
                    ?>
                    <option <?php selected($elementClass::getType(), isset($settings['type']) ? $settings['type'] : '', true) ?>
                            value="<?php echo $elementClass::getType(); ?>"><?php echo $elementClass::getLabel(); ?></option>
                    <?php
                }
                ?>
            </select>
            <img class="woocommerce-custom-registration-form-loader"
                 src="<?php echo WOO_CUSTOM_REGISTRATION_DIRECTORY_URL . 'assets/loader.svg' ?>">
        </div>
        <div id="woocommerce-custom-registration-form-common-wrapper">
            <div class="woocommerce-custom-registration-form-block">
                <label>Order</label>
                <input type="number" min="0" step="1" name="woo_custom_form_element_order"
                       value="<?php echo empty($order) ? 0 : $order; ?>" id="woo_custom_form_element_order">
            </div>
        </div>
        <div id="woocommerce-custom-registration-form-wrapper"></div>
        <input type="hidden" id="woo_custom_form_current_post_id" name="current_post_id"
               value="<?php echo $post->ID; ?>">
        <?php
        wp_nonce_field('woo_custom_registration_save_meta_data', 'woo_custom_registration_save_meta_data_nonce');
    }

    public function add_style_js()
    {
        $screen = get_current_screen();
        if ($screen->id !== 'woo-custom-regis') {
            return;
        }
        wp_enqueue_style($this->plugin_name . '-cpt-css', WOO_CUSTOM_REGISTRATION_DIRECTORY_URL . 'includes/registration-form/css/woo-custom-registration-form.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-cpt-css-tagsinput', WOO_CUSTOM_REGISTRATION_DIRECTORY_URL . 'assets/css/jquery.tagsinput.min.css', array(), $this->version, 'all');
        wp_enqueue_script($this->plugin_name . '-cpt-script-tagsinput', WOO_CUSTOM_REGISTRATION_DIRECTORY_URL . 'assets/js/jquery.tagsinput.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-cpt-script', WOO_CUSTOM_REGISTRATION_DIRECTORY_URL . 'includes/registration-form/js/woo-custom-registration-form.js', array('jquery', $this->plugin_name . '-cpt-script-tagsinput'), $this->version, false);
        wp_localize_script($this->plugin_name . '-cpt-script', 'woo_custom_registration', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'fetch_element_nonce' => wp_create_nonce("fetch_element_nonce"),
            'select_add_option_txt' => __('Add', Woocommerce_Custom_Registration_i18n::$textdomain)
        ));
    }

    public function columns($posts_columns)
    {
        unset($posts_columns['date']);
        $posts_columns['type'] = __('Type', Woocommerce_Custom_Registration_i18n::$textdomain);
        $posts_columns['order'] = __('Order', Woocommerce_Custom_Registration_i18n::$textdomain);
        $posts_columns['date'] = __('Date', Woocommerce_Custom_Registration_i18n::$textdomain);
        return $posts_columns;
    }

    public function custom_columns($column_name, $post_id)
    {
        $post = get_post($post_id);
        if (is_null($post)) {
            return;
        }

        $current_settings = get_post_meta($post_id, 'woo_custom_register_element', true);
        $order = get_post_meta($post->ID, 'woo_custom_register_element_order', true);

        switch ($column_name) {
            case 'type':
                echo htmlspecialchars(isset($current_settings['type']) ? $current_settings['type'] : '');
                break;
            case 'order':
                echo htmlspecialchars($order);
                break;
        }
    }
}