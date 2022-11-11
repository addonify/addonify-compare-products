<?php
/**
 * Fired during plugin activation
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products_Activator {

	/**
	 * Creates a page with the title, "Compare Products", adds shortcode as the content, and set the page in the database.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		// Create page only once.
		// Do not regenerate even if plugin is deleted by user.
		if ( get_option( ADDONIFY_CP_DB_INITIALS . 'compare_page' ) ) {
			return;
		}

		// Create page object.
		$new_page = array(
			'post_title'   => __( 'Compare Products', 'addonify-compare-products' ),
			'post_content' => '[addonify_compare_products]',
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
			'post_type'    => 'page',
		);

		// Insert the post into the database.
		$page_id = wp_insert_post( $new_page );

		update_option( ADDONIFY_CP_DB_INITIALS . 'compare_page', $page_id );
	}
}
