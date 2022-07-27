<?php 

if ( ! function_exists( 'addonify_compare_products_comparison_table_settings_fields' ) ) {

    function addonify_compare_products_comparison_table_settings_fields() {

        return array(
            'fields_to_compare' => array(
                'label' => __( 'Content to Display', 'addonify-compare-products' ),
                'description' => __( 'Choose content that you want to display in comparison table.', 'addonify-compare-products' ),
                'type'  => 'checkbox',
                'className' => 'fullwidth',
                'choices' => apply_filters(
                    'addonify_compare_products/comparison_table_content_choices',
                        array(
                        'image' => __( 'Image', 'addonify-compare-products' ),
                        'title' => __( 'Title', 'addonify-compare-products' ),
                        'price' => __( 'Price', 'addonify-compare-products' ),
                        'rating' => __( 'Rating', 'addonify-compare-products' ),
                        'description' => __( 'Description', 'addonify-compare-products' ),
                        'in_stock' => __( 'Stock Info', 'addonify-compare-products' ),
                        'add_to_cart_button' => __( 'Add to Cart Button', 'addonify-compare-products' ),
                    )
                ),
                'dependent' => array('enable_product_comparison'),
                'value' => addonify_compare_products_get_option( 'fields_to_compare' )
            ),
            'display_comparison_table_fields_header' => array(
                'type'                      => 'switch',
                'className'                 => '',
                'label'                     => __( 'Show Table Fields Header', 'addonify-compare-products' ),
                'description'               => '',
                'dependent'                 => array('enable_product_comparison'),
                'value'                     => addonify_compare_products_get_option( 'display_comparison_table_fields_header' )
            ),
        );
    }
}


if ( ! function_exists( 'addonify_compare_products_comparison_table_add_to_settings_fields' ) ) {

    function addonify_compare_products_comparison_table_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_comparison_table_settings_fields() );
    }

    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_comparison_table_add_to_settings_fields' );
}


if ( ! function_exists( 'addonify_compare_products_comparison_table_styles_settings_fields' ) ) {

    function addonify_compare_products_comparison_table_styles_settings_fields() {

        return array(
            'table_title_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Table Title Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'table_title_color' )
            ),
            'table_title_color_hover' => array(
                'type'                        => 'color',
                'label'                       => __( 'Table Title Color on Hover', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'table_title_color' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_comparison_table_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_comparison_table_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_comparison_table_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_comparison_table_styles_add_to_settings_fields' );
}