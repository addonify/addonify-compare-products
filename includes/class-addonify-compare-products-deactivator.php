<?php

class Addonify_Compare_Products_Deactivator {

	public static function deactivate() {

		// get page id
		$page_id = (int) get_option( ADDONIFY_CP_DB_INITIALS .'page_id' );

		// delete post
		wp_delete_post( $page_id, true );


	}

}
