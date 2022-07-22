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




if ( ! function_exists( 'addonify_compare_products_get_compare_button_icons' ) ) {

    function addonify_compare_products_get_compare_button_icons() {

        return apply_filters(
            'addonify_compare_products/compare_button_icons',
            array(
                'icon_one' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,2a10.032,10.032,0,0,1,7.122,3H16a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1h4.143A1.858,1.858,0,0,0,22,5.143V1a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1V3.078A11.981,11.981,0,0,0,.05,10.9a1.007,1.007,0,0,0,1,1.1h0a.982.982,0,0,0,.989-.878A10.014,10.014,0,0,1,12,2Z"/><path d="M22.951,12a.982.982,0,0,0-.989.878A9.986,9.986,0,0,1,4.878,19H8a1,1,0,0,0,1-1H9a1,1,0,0,0-1-1H3.857A1.856,1.856,0,0,0,2,18.857V23a1,1,0,0,0,1,1H3a1,1,0,0,0,1-1V20.922A11.981,11.981,0,0,0,23.95,13.1a1.007,1.007,0,0,0-1-1.1Z"/></svg>',
                'icon_two' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.485,10.975,12,17.267,1.515,10.975A1,1,0,1,0,.486,12.69l11,6.6a1,1,0,0,0,1.03,0l11-6.6a1,1,0,1,0-1.029-1.715Z"/><path d="M22.485,15.543,12,21.834,1.515,15.543A1,1,0,1,0,.486,17.258l11,6.6a1,1,0,0,0,1.03,0l11-6.6a1,1,0,1,0-1.029-1.715Z"/><path d="M12,14.773a2.976,2.976,0,0,1-1.531-.425L.485,8.357a1,1,0,0,1,0-1.714L10.469.652a2.973,2.973,0,0,1,3.062,0l9.984,5.991a1,1,0,0,1,0,1.714l-9.984,5.991A2.976,2.976,0,0,1,12,14.773ZM2.944,7.5,11.5,12.633a.974.974,0,0,0,1,0L21.056,7.5,12.5,2.367a.974.974,0,0,0-1,0h0Z"/></svg>',
                'icon_three' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.421,16.583,20.13,13.292a1,1,0,1,0-1.413,1.414L21.007,17A9.332,9.332,0,0,1,14.321,14.2a.982.982,0,0,0-1.408.08L12.9,14.3a1,1,0,0,0,.075,1.382A11.177,11.177,0,0,0,21.01,19l-2.293,2.293A1,1,0,1,0,20.13,22.7l3.291-3.291A2,2,0,0,0,23.421,16.583Z"/><path d="M21.007,7l-2.29,2.29a.892.892,0,0,0-.054.082.992.992,0,0,0,1.467,1.332L21.836,9l1.586-1.585a2,2,0,0,0,.457-2.1,1.969,1.969,0,0,0-.458-.728L20.13,1.3a1,1,0,1,0-1.413,1.413L21.01,5.005c-4.933.012-7.637,2.674-10.089,5.474C8.669,7.937,6,5.4,1.487,5.046L1.006,5a1,1,0,0,0-1,1,1.02,1.02,0,0,0,1,1c.072,0,.287.033.287.033C5.189,7.328,7.425,9.522,9.6,12c-2.162,2.466-4.383,4.7-8.247,4.96l-.4.021a.994.994,0,1,0,.124,1.985c.156-.007.41-.013.535-.023,5.02-.387,7.743-3.6,10.171-6.409C14.235,9.7,16.551,7.018,21.007,7Z"/></svg>',
                'icon_four' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,8H0V6H19V1.872l4.629,4.236a1.113,1.113,0,0,1,0,1.66L19,12ZM5,11.872.371,16.108a1.113,1.113,0,0,0,0,1.66L5,22V18H24V16H5Z"/></svg>'
            )
        );
    }
}