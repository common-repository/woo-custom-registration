<?php
/**
 * Created by PhpStorm.
 * User: ulver
 * Date: 13/02/2019
 * Time: 11:49
 */

class Woo_Custom_Form_Element_Radio implements Woocommerce_Custom_Registration_iForm_Element
{

    public static function getType()
    {
        return 'radio';
    }

    public static function getLabel()
    {
        return 'Radio';
    }

    public static function getSettings($current_settings)
    {
        $label = !empty($current_settings) ? $current_settings['label'] : '';
        $name = !empty($current_settings) ? $current_settings['name'] : '';
        $options = !empty($current_settings) ? $current_settings['options'] : '';
        $element_description = !empty($current_settings) ? $current_settings['description'] : '';

        $description = __('This field will be used as usermeta key name.', Woocommerce_Custom_Registration_i18n::$textdomain);
        $input_description = __('This is the field description.', Woocommerce_Custom_Registration_i18n::$textdomain);
        $input_options_description = __('Add the select options.', Woocommerce_Custom_Registration_i18n::$textdomain);

        $settings = <<<HTML
        <div class="woocommerce-custom-registration-form-block">
            <label>Label</label>
            <input type="text" name="woo_custom_registration_element_label" value="$label" required />
        </div>

        <div class="woocommerce-custom-registration-form-block">
            <label>Input name</label>
            <input type="text" name="woo_custom_registration_element_name" value="$name" required />
            <p>$description</p>
        </div>

        <div class="woocommerce-custom-registration-form-block">
            <label>Description</label>
            <input type="text" name="woo_custom_registration_element_description" value="$element_description" />
            <p>$input_description</p>
        </div>
        
        <div class="woocommerce-custom-registration-form-block">
            <label>Options</label>
            <input id="woo_custom_registration_element_options" name="woo_custom_registration_element_options" value="$options" />
            <p>$input_options_description</p>
        </div>

HTML;

        return $settings;
    }

    public static function saveSettings($post_id)
    {
        if (!isset($_POST['woo_custom_registration_element_label'])) {
            return;
        }

        if (!isset($_POST['woo_custom_registration_element_name'])) {
            return;
        }

        if (!isset($_POST['woo_custom_registration_element_description'])) {
            return;
        }

        if (!isset($_POST['woo_custom_form_element_order'])) {
            return;
        }

        if (!isset($_POST['woo_custom_registration_element_options'])) {
            return;
        }

        update_post_meta($post_id, 'woo_custom_register_element_order', sanitize_text_field($_POST['woo_custom_form_element_order']));

        update_post_meta($post_id, 'woo_custom_register_element', array(
            'type' => self::getType(),
            'label' => sanitize_text_field($_POST['woo_custom_registration_element_label']),
            'name' => Woocommerce_Custom_Registration_Utils::slugify($_POST['woo_custom_registration_element_name']),
            'description' => sanitize_text_field($_POST['woo_custom_registration_element_description']),
            'options' => sanitize_text_field($_POST['woo_custom_registration_element_options'])
        ));
    }

    public static function elementRendering($element)
    {
        if (empty($element)) {
            return;
        }

        $name = isset($element['name']) ? $element['name'] : '';
        $label = isset($element['label']) ? $element['label'] : '';
        $description = isset($element['description']) ? $element['description'] : '';
        $options = isset($element['options']) ? $element['options'] : '0';
        $options = explode('|', $options);
        $options = array_combine( array_filter($options, function ($item){
            return sanitize_title($item);
        }), $options);
        ?>

        <p class="form-row form-row-wide">
            <label><?php echo $label; ?></label>
            <?php $i = 0; foreach ($options as $key => $value) : ?>
                <label for="<?php echo $name . $i; ?>">
                    <input type="radio" name="<?php echo $name; ?>" id="<?php echo $name . $i; ?>" value="<?php echo $key; ?>"/>
                    <?php echo $value; ?>
                </label>
            <?php $i++; endforeach; ?>
            <span><?php echo $description; ?></span>
        </p>
        <?php
    }

    public static function elementValidation($element, $validation_errors)
    {

    }

    public static function elementSave($element, $customer_id)
    {

        if (empty($element)) {
            return;
        }

        if (isset($_POST[$element['name']])) {
            $field = sanitize_text_field($_POST[$element['name']]);
            update_user_meta($customer_id, $element['name'], $field);
        }
    }

    public static function elementAdminUserProfileRendering($element, $customer_id)
    {

        if (empty($element)) {
            return;
        }

        $value = get_user_meta($customer_id, $element['name'], true);

        ?>
        <tr>
            <th><label for="address"><?php echo $element['label'] ?></label></th>
            <td>
                <input type="text" name="<?php echo $element['name']; ?>"
                        id="<?php echo $element['name']; ?>" value="<?php echo $value; ?>" class="regular-text"/><br/>
                <span class="description"><?php echo $element['description']; ?></span>
            </td>
        </tr>
        <?php
    }

    public static function elementPublicUserProfileRendering($element, $customer_id)
    {

        if (empty($element)) {
            return;
        }

        $value = get_user_meta($customer_id, $element['name'], true);

        ?>
        <p class="form-row form-row-wide">
            <label for="<?php echo $element['name']; ?>"><?php echo $element['label']; ?> <?php echo $element['required'] === '1' ? '<span class="required">*</span></label>' : ''; ?>
                <input readonly type="text" class="input-text" name="<?php echo $element['name']; ?>"
                        id="<?php echo $element['name']; ?>" value="<?php echo $value; ?>"/>
                <span><?php echo $element['description']; ?></span>
        </p>
        <?php
    }

    public static function elementRenderingCheckout($element, &$fields)
    {

        if (empty($element)) {
            return;
        }

        $options = explode('|', $element['options']);
        $options = array_combine( array_filter($options, function ($item){
            return sanitize_title($item);
        }), $options);

        $fields['account'][$element['name']] = array(
            'label' => $element['label'],
            'description' => $element['description'],
            'type' => $element['type'],
            'options' => $options,
            'class' => array('form-row-wide'),
            'clear' => true
        );

        return $fields;

    }
}