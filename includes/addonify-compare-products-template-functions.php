<?php
/**
 * Includes template functions.
 *
 * @package Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/addonify-compare-products-template-functions
 */

if ( ! function_exists( 'addonify_compare_products_locate_template' ) ) {

	/**
	 * Locates template in the theme files, if found and loads them. If template is not found in theme, loads the default template.
	 *
	 * @since 1.0.0
	 * @param string $template_name Name of the template to load.
	 * @param string $template_path Path of the template to load.
	 * @param string $default_path Path of the default template to load.
	 * @return string Path of the template to load.
	 */
	function addonify_compare_products_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		// Set template location for theme.
		if ( empty( $template_path ) ) :
			$template_path = 'addonify/';
		endif;

		// Set default plugin templates path.
		if ( ! $default_path ) :
			$default_path = plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/'; // Path to the template folder.
		endif;

		// Search template file in theme folder.
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);

		// Get plugins template file.
		if ( ! $template ) :
			$template = $default_path . $template_name;
		endif;

		return apply_filters( 'addonify_compare_products_locate_template', $template, $template_name, $template_path, $default_path );
	}
}




if ( ! function_exists( 'addonify_compare_products_get_template' ) ) {
	/**
	 * Get template file from plugin templates folder.
	 *
	 * @since 1.0.0
	 * @param string $template_name Name of the template to load.
	 * @param array  $args Arguments to pass to the template.
	 * @param string $template_path Path of the template to load.
	 * @param string $default_path Path of the default template to load.
	 */
	function addonify_compare_products_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		if ( is_array( $args ) && isset( $args ) ) :
			extract( $args ); //phpcs:ignore
		endif;

		$template_file = addonify_compare_products_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $template_file ) ) :
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $template_file ) ), '1.0.0' );
			return;
		endif;

		include $template_file;
	}
}




if ( ! function_exists( 'addonify_compare_products_render_compare_button' ) ) {
	/**
	 * Renders the compare button in products loop.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_compare_button() {

		global $product;

		$compare_button_css_classes = array( 'button', 'addonify-cp-button' );

		$button_icon = '';

		if (
			addonify_compare_products_get_option( 'compare_products_btn_show_icon' ) &&
			addonify_compare_products_get_option( 'compare_products_btn_icon' )
		) {

			$compare_button_icon_key = addonify_compare_products_get_option( 'compare_products_btn_icon' );

			$compare_button_icons = addonify_compare_products_get_compare_button_icons();

			$button_icon = $compare_button_icons[ $compare_button_icon_key ];

			if ( addonify_compare_products_get_option( 'compare_products_btn_icon_position' ) === 'left' ) {
				$compare_button_css_classes[] = 'icon-position-left';
			} else {
				$compare_button_css_classes[] = 'icon-position-right';
			}
		}

		$compare_button_args = array(
			'product_id'  => $product->get_id(),
			'label'       => addonify_compare_products_get_option( 'compare_products_btn_label' ),
			'classes'     => apply_filters( 'addonify_compare_products_compare_button_css_classes', $compare_button_css_classes ),
			'button_icon' => $button_icon,
		);

		addonify_compare_products_get_template(
			'addonify-compare-products-button.php',
			apply_filters( 'addonify_compare_products_compare_button_args', $compare_button_args )
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_comparison_modal' ) ) {
	/**
	 * Renders the comparison modal.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_comparison_modal() {

		if ( 'page' === addonify_compare_products_get_option( 'compare_products_display_type' ) ) {
			return;
		}

		addonify_compare_products_get_template( 'addonify-compare-products-comparison-modal.php' );
	}
}


if ( ! function_exists( 'addonify_compare_products_render_docker_modal' ) ) {
	/**
	 * Renders the docker modal.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_docker_modal() {

		$docker_modal_args = array(
			'inner_css_classes' => array(),
		);

		addonify_compare_products_get_template(
			'addonify-compare-products-docker-modal.php',
			apply_filters(
				'addonify_compare_products_docker_modal_args',
				$docker_modal_args
			)
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_search_modal' ) ) {
	/**
	 * Renders the search modal.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_search_modal() {

		addonify_compare_products_get_template( 'addonify-compare-products-search-modal.php' );
	}
}



if ( ! function_exists( 'addonify_compare_products_render_comparison_content' ) ) {

	/**
	 * Renders the comparison table.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_comparison_content() {

		$comparison_content_args = array(
			'no_table_rows_message' => '',
			'message_css_classes'   => array( 'addonify-compare-products-notice' ),
			'table_css_classes'     => array(),
			'table_rows'            => array(),
			'no_of_products'        => 0,
		);

		$content_to_display = ( addonify_compare_products_get_option( 'fields_to_compare' ) ) ? json_decode( addonify_compare_products_get_option( 'fields_to_compare' ), true ) : array();

		if (
			is_array( $content_to_display ) &&
			count( $content_to_display ) > 1
		) {

			$comparison_content_args['table_rows'] = array( 'product_id' => array( '0' ) );

			$comparison_content_args['table_rows']['remove_button'] = array();

			if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {

				$comparison_content_args['table_css_classes'][] = 'has-header';

				$comparison_content_args['table_rows']['remove_button'][] = '';
			} else {

				$comparison_content_args['table_css_classes'][] = 'no-header';
			}

			if ( in_array( 'title', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['title'] = array( __( 'Title', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'image', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['image'] = array( __( 'Image', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'price', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['price'] = array( __( 'Price', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'description', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['description'] = array( __( 'Description', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'rating', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['rating'] = array( __( 'Rating', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'in_stock', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['in_stock'] = array( __( 'In Stock', 'addonify-compare-products' ) );
				}
			}

			if ( in_array( 'add_to_cart_button', $content_to_display, true ) ) {
				if ( (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' ) === 1 ) {
					$comparison_content_args['table_rows']['add_to_cart_button'] = array( __( 'Action', 'addonify-compare-products' ) );
				}
			}

			$products = addonify_compare_products_get_compare_products_list();

			$comparison_content_args['no_of_products'] = count( $products );

			if ( is_array( $products ) && $comparison_content_args['no_of_products'] > 0 ) {

				if ( $comparison_content_args['no_of_products'] > 1 ) {
					foreach ( $products as $product_id ) {

						$product = wc_get_product( $product_id );

						$comparison_content_args['table_rows']['product_id'][] = $product_id;

						$delete_button = '<button class="addonify-remove-compare-products addonify-compare-table-remove-btn" data-product_id="' . esc_attr( $product_id ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g id="_01_align_center" data-name="01 align center"><path d="M22,4H17V2a2,2,0,0,0-2-2H9A2,2,0,0,0,7,2V4H2V6H4V21a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V6h2ZM9,2h6V4H9Zm9,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V6H18Z"/><rect x="9" y="10" width="2" height="8"/><rect x="13" y="10" width="2" height="8"/></g></svg></button>';

						$comparison_content_args['table_rows']['remove_button'][] = $delete_button;

						if ( in_array( 'title', $content_to_display, true ) ) {
							$comparison_content_args['table_rows']['title'][] = '<a class="product-title-link" href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_title() ) . '</a>';
						}

						if ( in_array( 'image', $content_to_display, true ) ) {

							$comparison_content_args['table_rows']['image'][] = '<a href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_image() ) . '</a>';
						}

						if ( in_array( 'price', $content_to_display, true ) ) {

							$comparison_content_args['table_rows']['price'][] = '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
						}

						if ( in_array( 'description', $content_to_display, true ) ) {

							$comparison_content_args['table_rows']['description'][] = wp_kses_post( $product->get_short_description() );
						}

						if ( in_array( 'rating', $content_to_display, true ) ) {

							$ratings = wc_get_rating_html( $product->get_average_rating() );
							$comparison_content_args['table_rows']['rating'][] = ( $ratings ) ? wp_kses_post( $ratings ) : '-';
						}

						if ( in_array( 'in_stock', $content_to_display, true ) ) {

							$comparison_content_args['table_rows']['in_stock'][] = ( $product->is_in_stock() ) ? __( 'Yes', 'addonify-compare-products' ) : __( 'No', 'addonify-compare-products' );
						}

						if ( in_array( 'add_to_cart_button', $content_to_display, true ) ) {

							$comparison_content_args['table_rows']['add_to_cart_button'][] = do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
						}
					}
				} else {
					$comparison_content_args['no_table_rows_message'] = __( 'At least two products are needed for comparison. There is only one product in the comparison list.', 'addonify-compare-products' );
				}
			} else {

				$comparison_content_args['no_table_rows_message'] = __( 'There are no products to compare.', 'addonify-compare-products' );
			}
		}

		addonify_compare_products_get_template(
			'addonify-compare-products-content.php',
			apply_filters(
				'addonify_compare_products_comparison_content_args',
				$comparison_content_args
			)
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_docker_message' ) ) {
	/**
	 * Renders the message in the docker.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_docker_message() {

		$docker_message_args = array(
			'css_classes' => array(),
			'message'     => esc_html__( 'Select more than one item for comparison.', 'addonify-compare-products' ),
		);

		addonify_compare_products_get_template(
			'docker/message.php',
			apply_filters( 'addonify_compare_products_docker_message_args', $docker_message_args )
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_docker_add_button' ) ) {
	/**
	 * Renders the add button in the docker.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_docker_add_button() {

		addonify_compare_products_get_template( 'docker/add-button.php' );
	}
}




if ( ! function_exists( 'addonify_compare_products_render_docker_content' ) ) {

	/**
	 * Renders the docker content.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_docker_content() {

		$docker_content_args = array(
			'products' => addonify_compare_products_get_compare_products_list(),
		);

		addonify_compare_products_get_template(
			'docker/content.php',
			apply_filters( 'addonify_compare_products_docker_content_args', $docker_content_args )
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_docker_compare_button' ) ) {
	/**
	 * Render the compare button in docker.
	 *
	 * @since 1.0.0
	 */
	function addonify_compare_products_render_docker_compare_button() {
		$docker_compare_button_args = array(
			'button_label'      => esc_html__( 'Compare', 'addonify-compare-products' ),
			'compare_page_link' => '',
		);

		if ( 'page' === addonify_compare_products_get_option( 'compare_products_display_type' ) ) {
			$docker_compare_button_args['compare_page_link'] = get_permalink( (int) addonify_compare_products_get_option( 'compare_page' ) );
		}

		addonify_compare_products_get_template(
			'docker/compare-button.php',
			apply_filters( 'addonify_compare_products_docker_compare_button_args', $docker_compare_button_args )
		);
	}
}




if ( ! function_exists( 'addonify_compare_products_render_docker_search_result' ) ) {
	/**
	 * Renders the search result in the search modal.
	 *
	 * @since 1.0.0
	 * @param array $args Arguments for searching.
	 */
	function addonify_compare_products_render_docker_search_result( $args ) {

		addonify_compare_products_get_template(
			'addonify-compare-products-search-result.php',
			apply_filters( 'addonify_compare_products_docker_search_result_args', $args )
		);
	}
}
