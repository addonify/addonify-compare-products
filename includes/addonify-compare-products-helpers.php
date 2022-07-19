<?php 

if ( ! function_exists( 'addonify_compare_products_is_woocommerce_active' ) ) {

    function addonify_compare_products_is_woocommerce_active() {

        if ( in_array( 'woocommerce/woocommerce.php', get_option('active_plugins') ) ) {

            return true;
        }

        return false;
    }
}


if ( ! function_exists( 'addonify_compare_products_get_table_header_label' ) ) {

    function addonify_compare_products_get_table_header_label( $key ) {

        $table_headings = array(
            'image' => __( 'Image', 'addonify-compare-products' ),
            'title' => __( 'Title', 'addonify-compare-products' ),
            'price' => __( 'Price', 'addonify-compare-products' ),
            'rating' => __( 'Rating', 'addonify-compare-products' ),
            'description' => __( 'Description', 'addonify-compare-products' ),
            'add_to_cart_button' => __( 'Action', 'addonify-compare-products' ),
            'in_stock' => __( 'Stock Info', 'addonify-compare-products' )
        );

        return $table_headings[$key];
    }
}



if ( ! function_exists( 'addonify_compare_products_get_attributes' ) ) {

    function addonify_compare_products_get_attributes() {

        
    }
}