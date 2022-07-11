<?php 

if ( ! function_exists( 'addonify_compare_products_comparision_table_settings_fields' ) ) {

    function addonify_compare_products_comparision_table_settings_fields() {

        return array(
            'fields_to_compare' => array(
                'label' => __( 'Content to Display', 'addonify-compare-products' ),
                'description' => __( 'Choose content that you want to display in comparision table.', 'addonify-compare-products' ),
                'type'  => 'checkbox',
                'typeStyle' => 'buttons',
                'className' => 'fullwidth',
                'choices' => array(
                    'image' => __( 'Image', 'addonify-compare-products' ),
                    'title' => __( 'Title', 'addonify-compare-products' ),
                    'price' => __( 'Price', 'addonify-compare-products' ),
                    'rating' => __( 'Rating', 'addonify-compare-products' ),
                    'excerpt' => __( 'Excerpt', 'addonify-compare-products' ),
                    'meta' => __( 'Meta', 'addonify-compare-products' ),
                    'add_to_cart' => __( 'Add to Cart', 'addonify-compare-products' ),
                ),
                'dependent' => array('enable_product_comparision'),
                'value' => addonify_compare_products_get_option( 'fields_to_compare' )
            )
        );
    }
}


if ( ! function_exists( 'addonify_compare_products_comparision_table_add_to_settings_fields' ) ) {

    function addonify_compare_products_comparision_table_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_comparision_table_settings_fields() );
    }

    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_comparision_table_add_to_settings_fields' );
}


if ( ! function_exists( 'addonify_compare_products_comparision_table_styles_settings_fields' ) ) {

    function addonify_compare_products_comparision_table_styles_settings_fields() {

        return array(
            'table_title_color' => array(
                'type'                        => 'color',
                'label'                       => __( 'Table Title Color', 'addonify-compare-products' ),
                'isAlphaPicker'               => true,
                'className'                   => '',
                'value'                       => addonify_compare_products_get_option( 'table_title_color' )
            ),
        );
    }
}

if ( ! function_exists( 'addonify_compare_products_comparision_table_styles_add_to_settings_fields' ) ) {

    function addonify_compare_products_comparision_table_styles_add_to_settings_fields( $settings_fields ) {

        return array_merge( $settings_fields, addonify_compare_products_comparision_table_styles_settings_fields() );
    }
    
    add_filter( 'addonify_compare_products/settings_fields', 'addonify_compare_products_comparision_table_styles_add_to_settings_fields' );
}