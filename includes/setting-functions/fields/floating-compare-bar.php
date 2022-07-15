<?php 

if ( ! function_exists( 'addonify_compare_products_floating_bar_styles_settings_fields' ) ) {

    function addonify_compare_products_floating_bar_styles_settings_fields() {

        return array(
            'floating_bar_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_bck_color' )
            ),
            'floating_bar_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Text Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_text_color' )
            ),
            'floating_bar_add_button_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Text Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_add_button_text_color' )
            ),
            'floating_bar_add_button_text_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Text Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_add_item_text_color_hover' )
            ),
            'floating_bar_add_button_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_add_item_bck_color' )
            ),
            'floating_bar_add_button_bck_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Background Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_add_button_bck_color_hover' )
            ),
            'floating_bar_compare_button_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Compare Button Text Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_compare_button_text_color' )
            ),
            'floating_bar_compare_button_text_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Compare Button Text Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_compare_items_text_color_hover' )
            ),
            'floating_bar_compare_button_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Compare Button Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_compare_button_bck_color' )
            ),
            'floating_bar_compare_button_bck_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Compare Button Background Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'floating_bar_compare_button_bck_color_hover' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_floating_bar_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_floating_bar_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_floating_bar_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_floating_bar_styles_add_to_settings_fields' );
}