<?php


require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/general.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/compare-button.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/floating-compare-bar.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/add-to-compare-modal.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/compare-modal.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/comparison-table.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/custom-css.php';


if ( ! function_exists( 'addonify_compare_products_settings_defaults' ) ) {

    function addonify_compare_products_settings_defaults( $setting_id = '' ) {

        $defaults = apply_filters( 
            'addonify_compare_products/setting_defaults',  
            array(
                'enable_product_comparision' => true,
                'compare_products_btn_position' => 'after_add_to_cart',
                'compare_products_btn_label' => __( 'Compare', 'addonify-compare-products' ),
                'compare_products_display_type' => 'popup',
                'compare_page' => '',
                'compare_products_cookie_expires' => 30,
                'fields_to_compare' => serialize( array( 'image', 'title', 'price', 'add_to_cart', 'rating', 'excerpt' ) ),
                'load_styles_from_plugin' => false,
                'compare_btn_text_color' => '#000000',
                'compare_btn_bck_color' => '#eeeeee',
                'modal_overlay_bck_color' => '#000000',
                'modal_bck_color' => '#ffffff',
                'table_title_color' => '#000000',
                'close_btn_text_color' => '#d3ced2',
                'close_btn_bck_color' => '#f5c40e',
                'close_btn_text_color_hover' => '#d3ced2',
                'close_btn_bck_color_hover' => '#f5c40e',
                'custom_css' => ''
            )
        );

        return ( $setting_id && isset( $defaults[ $setting_id ] ) ) ? $defaults[ $setting_id ] : $defaults;
    }
}


if ( ! function_exists( 'addonify_compare_products_get_option' ) ) {

    function addonify_compare_products_get_option( $setting_id ) {

        return get_option( ADDONIFY_CP_DB_INITIALS . $setting_id, addonify_compare_products_settings_defaults( $setting_id ) );
    }
}


if ( ! function_exists( 'addonify_compare_products_get_settings_values' ) ) {

    function addonify_compare_products_get_settings_values() {

        if ( addonify_compare_products_settings_defaults() ) {

            $settings_values = array();

            $setting_fields = addonify_compare_products_settings_fields();

            foreach ( addonify_compare_products_settings_defaults() as $id => $value ) {

                $setting_type = $setting_fields[$id]['type'];

                switch ( $setting_type ) {
                    case 'switch':
                        $settings_values[$id] = ( addonify_compare_products_get_option( $id ) == '1' ) ? true : false;
                        break;
                    case 'number':
                        $settings_values[$id] = addonify_compare_products_get_option( $id );
                        break;
                    default:
                        $settings_values[$id] = addonify_compare_products_get_option( $id );
                }
            }

            return $settings_values;
        }
    }
}


if ( ! function_exists( 'addonify_compare_products_update_settings' ) ) {

    function addonify_compare_products_update_settings( $settings = '' ) {

        if ( 
            is_array( $settings ) &&
            count( $settings ) > 0
        ) {
            $setting_fields = addonify_compare_products_settings_fields();

            foreach ( $settings as $id => $value ) {

                $sanitized_value = null;

                $setting_type = $setting_fields[$id]['type'];

                switch ( $setting_type ) {
                    case 'text':
                        $sanitized_value = sanitize_text_field( $value );
                        break;
                    case 'textarea':
                        $sanitized_value = sanitize_textarea_field( $value );
                        break;
                    case 'switch':
                        $sanitized_value = ( $value == true ) ? '1' : '0';
                        break;
                    case 'number':
                        $sanitized_value = (int) $value;
                        break;
                    case 'color':
                        $sanitized_value = sanitize_text_field( $value );
                        break;
                    case 'select':
                        $setting_choices = $setting_fields[$id]['choices'];
                        $sanitized_value = ( array_key_exists( $value, $setting_choices ) ) ? sanitize_text_field( $value ) : $setting_choices[0];
                        break;
                    case 'checkbox':
                        $sanitize_args = array(
                            'choices' => $settings_fields[$key]['choices'],
                            'values' => $value
                        );
                        $sanitized_value = addonify_compare_products_sanitize_multi_choices( $sanitize_args );
                        $sanitized_value = serialize( $value );                     
                        break;
                    default:
                        $sanitized_value = sanitize_text_field( $value );
                }

                if ( ! update_option( ADDONIFY_CP_DB_INITIALS . $id, $sanitized_value ) ) {
                    return false;
                }
            }

            return true;
        }        
    }
}


if ( ! function_exists( 'addonify_compare_products_settings_fields' ) ) {

    function addonify_compare_products_settings_fields() {

        return apply_filters( 'addonify_compare_products/settings_fields', array() );
    }
}


/**
 * Define settings sections and respective settings fields.
 * 
 * @since 1.0.7
 * @return array
 */
if ( ! function_exists( 'addonify_compare_products_get_settings_fields' ) ) {

    function addonify_compare_products_get_settings_fields() {

        return array(
            'settings_values' => addonify_compare_products_get_settings_values(),
            'tabs' => array(
                'settings' => array(
                    'title' => __( 'Settings', 'addonify-compare-products' ),
                    'sections' => array(
                        'general' => array(
                            'title' => __( 'General Options', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_general_setting_fields(),
                        ),
                        'compare_button' => array(
                            'title' => __('Compare Button Options', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_compare_button_settings_fields(),
                        ),
                        'comparision_table' => array(
                            'title' => __( 'Comparision Table', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_comparison_table_settings_fields()
                        )
                    )
                ),
                'styles' => array(
                    'sections' => array(
                        'general' => array(
                            'title' => __( 'General', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_general_styles_settings_fields(),
                        ),
                        'compare_button_colors' => array(
                            'title' => __( 'Compare Button Colors', 'addonify-compare-products' ),
                            'description' => '',
                            'dependent'  => array('load_styles_from_plugin'),
                            'fields' => addonify_compare_products_compare_button_styles_settings_fields()
                        ),
                        'comparision_table_color' => array(
                            'title' => __( 'Comparision Table Colors', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_comparison_table_styles_settings_fields()
                        ),
                        'compare_modal_color' => array(
                            'title' => __( 'Compare Modal Colors', 'addonify-compare-products' ),
                            'description' => '',
                            'fields' => addonify_compare_products_compare_modal_styles_settings_fields()
                        ),
                        'custom_css' => array(
                            'title' => __( 'Developer', 'addonify-compare-products' ),
                            'description' => '',
                            'dependent'  => array('load_styles_from_plugin'),
                            'fields' => addonify_compare_products_custom_css_settings_fields()
                        )
                    )
                ),
                'products' => array(
                    'recommended' => array(
                        // Recommend plugins here.
                        'content' => __( 'Coming soon....', 'addonify-compare-products' ),
                    )
                ),
            ),
        );
    }
}