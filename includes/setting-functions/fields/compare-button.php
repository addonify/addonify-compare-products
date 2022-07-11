<?php 

if ( ! function_exists( 'addonify_compare_products_compare_button_settings_fields' ) ) {

    function addonify_compare_products_compare_button_settings_fields() {

        return array(
            'compare_products_btn_position' => array(
                'type'                      => 'select',
                'className'                 => '',
                'label'                     => __( 'Button Position', 'addonify-compare-products' ),
                'description'               => __( 'Choose where to place the compare product button.', 'addonify-compare-products' ),
                'choices' => array(
                    'after_add_to_cart'     => __( 'After Add to Cart Button', 'addonify-compare-products' ),
                    'before_add_to_cart'    => __( 'Before Add to Cart Button', 'addonify-compare-products' ),
                ),
                'dependent'                 => array('enable_product_comparision'),
                'value'                     => addonify_compare_products_get_option( 'compare_products_btn_position' )
            ),
            'compare_products_btn_label' => array(
                'type'                      => 'text',
                'className'                 => '',
                'label'                     => __( 'Button Label', 'addonify-compare-products' ),
                'description'               => __( 'Label for compare product button.', 'addonify-compare-products' ),
                'dependent'                 => array('enable_product_comparision'),
                'value'                     => addonify_compare_products_get_option( 'compare_products_btn_label' )
            ),
        );
    }
}


if ( ! function_exists( 'addonify_compare_products_compare_button_add_to_settings_fields' ) ) {

    function addonify_compare_products_compare_button_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_compare_button_settings_fields() );
    }

    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_compare_button_add_to_settings_fields' );
}


if ( ! function_exists( 'addonify_compare_products_compare_button_styles_settings_fields' ) ) {

    function addonify_compare_products_compare_button_styles_settings_fields() {

        return array(
            'compare_btn_text_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Label Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'compare_btn_text_color' )
            ),
            'compare_btn_bck_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Background Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'compare_btn_bck_color' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_compare_button_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_compare_button_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_compare_button_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_compare_button_styles_add_to_settings_fields' );
}