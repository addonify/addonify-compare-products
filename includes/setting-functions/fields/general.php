<?php 

if ( ! function_exists( 'addonify_compare_products_general_setting_fields' ) ) {

    function addonify_compare_products_general_setting_fields() {

        return array(
            'enable_product_comparision' => array(
                'label'			  => __( 'Enable products compare', 'addonify-compare-products' ),
                'description'     => __( 'If disabled, addonify wishlist plugin functionality will not be functional.', 'addonify-compare-products' ),
                'type'            => 'switch',
                'className'       => '',
                'badge'           => 'Required',
                'value' => addonify_compare_products_get_option( 'enable_product_comparision' )
            ),
            'compare_products_display_type' => array(
                'type' => 'select',
                'className'      => '',
                'placeholder'    => __('Select a page', 'addonify-compare-products'),
                'label'          => __( 'Products Compare Page', 'addonify-compare-products' ),
                'description'    => __( 'Select a page to display wishlist table.', 'addonify-compare-products'),
                'dependent'      => array('enable_product_comparision'),
                'choices'        => array(
                    'popup'      => __( 'Popup Modal', 'addonify-compare-products' ),
                    'page'       => __( 'Page', 'addonify-compare-products' ),
                ),
                'value'          => addonify_compare_products_get_option( 'compare_products_display_type' )
            ),
            'compare_page' => array(
                'type' => 'select',
                'className'      => '',
                'placeholder'    => __('Select a page', 'addonify-compare-products'),
                'label'          => __( 'Products Compare Page', 'addonify-compare-products' ),
                'description'    => __( 'Select a page to display wishlist table.', 'addonify-compare-products'),
                'dependent'      => array('enable_product_comparision'),
                'choices'        => addonify_compare_products_get_pages(),
                'value'          => addonify_compare_products_get_option( 'compare_page' )
            ),
            'compare_products_cookie_expires' => array(
                'type'          => 'number',
                'className'     => '',
                'typeStyle'    => 'toggle', // 'default', 'toggle' & slider
                'label'         => __( 'Save compare Cookie for [ x ] days', 'addonify-compare-products' ),
                'dependent'     => array('enable_product_comparision'),
                'description'   => __( 'Set the number of days to save the compare products data in browser cookie.', 'addonify-wsihlist' ),
                'value'         => addonify_compare_products_get_option( 'compare_products_cookie_expires' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_general_add_to_settings_fields' ) ) {

    function addonify_compare_products_general_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_general_setting_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_general_add_to_settings_fields' );
}


if ( ! function_exists( 'addonify_compare_products_general_styles_settings_fields' ) ) {

    function addonify_compare_products_general_styles_settings_fields() {

        return array(
            'load_styles_from_plugin' => array(
                'type'              => 'switch',
                'className'         => '',
                'label'             => __( 'Enable Styles from Plugin', 'addonify-compare-products' ),
                'description'       => __( 'Enable to apply styles and colors from the plugin.', 'addonify-compare-products' ),
                'value'             => addonify_compare_products_get_option( 'load_styles_from_plugin' )
            )
        );
    }
}


if ( ! function_exists( 'addonify_compare_products_general_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_general_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_general_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_general_styles_add_to_settings_fields' );
}