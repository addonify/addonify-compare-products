<?php
/**
 * Sanitizes SVG when rendering in the frontend.
 *
 * @since 1.0.0
 * @package Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/addonify-compare-products-helpers-functions
 */

if ( ! function_exists( 'addonify_compare_products_escape_svg' ) ) {
	/**
	 * Sanitizes SVG when rendering in the frontend.
	 *
	 * @since 1.0.0
	 * @param string $svg SVG code.
	 * @return string $svg Sanitized SVG code.
	 */
	function addonify_compare_products_escape_svg( $svg ) {

		$allowed_html = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
		);

		return wp_kses( $svg, $allowed_html );
	}
}



if ( ! function_exists( 'addonify_compare_products_get_compare_products_list' ) ) {
	/**
	 * Get the items from the compare cookie.
	 *
	 * @since 1.0.0
	 * @return array $svg Sanitized SVG code.
	 */
	function addonify_compare_products_get_compare_products_list() {

		return isset( $_POST['product_ids'] ) ? json_decode( wp_unslash( $_POST[ 'product_ids' ] ), 1 ) : array(); //phpcs:ignore
	}
}



/**
 * Get the items from the compare cookie.
 *
 * @since 1.0.0
 * @return array $svg Sanitized SVG code.
 */
if ( ! function_exists( 'addonify_compare_products_get_compare_cookie' ) ) {
	/**
	 * Get the items from the compare cookie.
	 *
	 * @since 1.0.0
	 * @return array $svg Sanitized SVG code.
	 */
	function addonify_compare_products_get_compare_cookie() {

		return ( array_key_exists( 'addonify-compare-products', $_COOKIE ) ) ? json_decode( stripslashes( $_COOKIE[ 'addonify-compare-products' ] ), 1 ) : array(); // phpcs:ignore
	}
}


/**
 * Count the number of items in the compare cookie.
 *
 * @since 1.0.0
 * @return int number of items in the compare cookie.
 */
if ( ! function_exists( 'addonify_compare_products_get_compare_cookie_count' ) ) {
	/**
	 * Count the number of items in the compare cookie.
	 *
	 * @since 1.0.0
	 * @return int number of items in the compare cookie.
	 */
	function addonify_compare_products_get_compare_cookie_count() {

		$compare_cookie = addonify_compare_products_get_compare_cookie();

		return ( is_array( $compare_cookie ) ) ? count( $compare_cookie ) : 0;
	}
}



if ( ! function_exists( 'addonify_compare_products_is_product_in_compare_cookie' ) ) {
	/**
	 * Checks if there a product is in the compare cookie.
	 *
	 * @since 1.0.0
	 * @param int $product_id Product ID.
	 * @return boolean true if there is product in the compare cookie, false otherwise.
	 */
	function addonify_compare_products_is_product_in_compare_cookie( $product_id ) {

		$compare_cookie = addonify_compare_products_get_compare_cookie();

		return ( is_array( $compare_cookie ) && in_array( $product_id, $compare_cookie ) ) ? true : false; // phpcs:ignore
	}
}



if ( ! function_exists( 'addonify_compare_products_is_empty_compare_cookie' ) ) {
	/**
	 * Checks if there are items in the compare cookie.
	 *
	 * @since 1.0.0
	 * @return boolean true if there are items in the compare cookie, false otherwise.
	 */
	function addonify_compare_products_is_empty_compare_cookie() {

		$compare_cookie = addonify_compare_products_get_compare_cookie();

		return ( is_array( $compare_cookie ) && count( $compare_cookie ) === 0 ) ? true : false;
	}
}
