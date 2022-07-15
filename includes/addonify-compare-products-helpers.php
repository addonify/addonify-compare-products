<?php 

if ( ! function_exists( 'addonify_compare_products_is_woocommerce_active' ) ) {

    function addonify_compare_products_is_woocommerce_active() {

        if ( in_array( 'woocommerce/woocommerce.php', get_option('active_plugins') ) ) {

            return true;
        }

        return false;
    }
}