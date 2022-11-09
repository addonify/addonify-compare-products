<?php

if ( ! function_exists( 'addonify_compare_products_search_modal_settings_fields' ) ) {

    function addonify_compare_products_search_modal_settings_fields() {

        return array(
        );
    }
}


if ( ! function_exists( 'addonify_compare_products_compare_modal_add_to_settings_fields' ) ) {

    function addonify_compare_products_compare_modal_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_search_modal_settings_fields() );
    }

    add_filter( 'addonify_compare_products_settings_fields', 'addonify_compare_products_compare_modal_add_to_settings_fields' );
}


if ( ! function_exists( 'addonify_compare_products_search_modal_styles_settings_fields' ) ) {

    function addonify_compare_products_search_modal_styles_settings_fields() {

        return array(
            'search_modal_overlay_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Overlay Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_overlay_bck_color' )
            ),
            'search_modal_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_bck_color' )
            ),
            'search_modal_add_btn_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Label Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_add_btn_text_color' )
            ),
            'search_modal_add_btn_text_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Label Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_add_btn_text_color_hover' )
            ),
            'search_modal_add_btn_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color' )
            ),
            'search_modal_add_btn_bck_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Add Button Background Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color_hover' )
            ),
            'search_modal_close_btn_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Close Button Label Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_close_btn_text_color' )
            ),
            'search_modal_close_btn_text_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Close Button On Hover Label Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_close_btn_text_color_hover' )
            ),
            'search_modal_close_btn_border_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Close Button Border Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_close_btn_border_color' )
            ),
            'search_modal_close_btn_border_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Close Button On Hover Border Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'search_modal_close_btn_border_color_hover' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_search_modal_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_search_modal_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_search_modal_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products_settings_fields', 'addonify_compare_products_search_modal_styles_add_to_settings_fields' );
}