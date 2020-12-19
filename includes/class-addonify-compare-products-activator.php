<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.themebeez.com
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

	public static function activate() {

		// create page only once.
		// do not regenerate even if plugin is deleted by user.

		if ( get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' ) ) {
			return;
		}

		// Create page object.
		$new_page = array(
			'post_title' => __( 'Compare Products', 'addonify-compare-products' ),
			'post_content' => '[addonify-compare-products]',
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_type' => 'page',
		);

		// Insert the post into the database.
		$page_id = wp_insert_post( $new_page );

		update_option( ADDONIFY_CP_DB_INITIALS . 'page_id', $page_id );

	}

}
