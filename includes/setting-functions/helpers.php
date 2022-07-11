<?php 
/**
 * Get all pages in array of page_id and page_title pairs.
 * 
 * @since 1.0.0
 * @return array
 */
if ( ! function_exists( 'addonify_compare_products_get_pages' ) ) {

	function addonify_compare_products_get_pages() {

		$pages  =  get_pages();

		$page_list = array();

		if( ! empty( $pages ) ) {

			foreach( $pages as $page ) {

				$page_list[ $page->ID ] = $page->post_title;
			}
		}

		return $page_list;
	}
} 


/**
 * Sanitize multiple choices values.
 * 
 * @since 1.0.7
 * @param array $args
 * @return array $sanitized_values
 */
if ( ! function_exists( 'addonify_compare_products_sanitize_multi_choices' ) ) {

    function addonify_compare_products_sanitize_multi_choices( $args ) {

        if ( 
            is_array( $args['choices'] ) && 
            count( $args['choices'] ) && 
            is_array( $args['values'] ) && 
            count( $args['values'] ) 
        ) {

            $sanitized_values = array();

            foreach ( $args['values'] as $value ) {
                
                if ( array_key_exists( $value, $args['choices'] ) ) {

                    $sanitized_values[] = $value;
                }
            }

            return $sanitized_values;
        }

        return array();
    }
}