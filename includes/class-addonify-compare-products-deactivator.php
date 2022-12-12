<?php
/**
 * Fired during plugin deactivation.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products_Deactivator {

	/**
	 * Deletes the compare page by getting page id from the database.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {

		// Get compare page id from database.
		$page_id = (int) get_option( ADDONIFY_CP_DB_INITIALS . 'compare_page' );

		// Delete the compare page.
		wp_delete_post( $page_id, true );
	}
}
