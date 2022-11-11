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
