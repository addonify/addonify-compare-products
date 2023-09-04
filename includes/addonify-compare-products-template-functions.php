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
	 *
	 * @param array $args Button arguments.
	 */
	function addonify_compare_products_render_compare_button( $args = array() ) {

		addonify_compare_products_get_template(
			'addonify-compare-products-button.php',
			apply_filters( 'addonify_compare_products_compare_button_args', $args )
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
			'table_css_classes'     => array( 'addonify-compare-products-table' ),
			'table_rows'            => array(),
			'no_of_products'        => 0,
			'table_fields'          => array(),
			'wc_product_ids'        => array(),
		);

		$content_to_display = ( addonify_compare_products_sortable_setting_value( 'compare_table_fields' ) ) ? json_decode( addonify_compare_products_sortable_setting_value( 'compare_table_fields' ), true ) : array();

		$comparison_content_args['table_fields'] = $content_to_display;

		if (
			is_array( $content_to_display ) &&
			count( $content_to_display ) > 1
		) {

			$display_compare_table_fields_head = (int) addonify_compare_products_get_option( 'display_comparison_table_fields_header' );

			$comparison_content_args['table_rows'] = array( 'product_id' => array() );

			$comparison_content_args['table_rows']['remove_button'] = array();

			if ( 1 === $display_compare_table_fields_head ) {

				$comparison_content_args['table_css_classes'][] = 'has-header';

				$comparison_content_args['table_rows']['product_id'][] = 0;

				$comparison_content_args['table_rows']['remove_button'][] = '';
			} else {

				$comparison_content_args['table_css_classes'][] = 'has-no-header';
			}

			$compare_table_defined_fields = addonify_compare_products_get_compare_table_fields();

			foreach ( $content_to_display as $table_field ) {
				if ( isset( $table_field['status'] ) && true === $table_field['status'] ) {

					if ( isset( $table_field['id'] ) && 'attributes' !== $table_field['id'] ) {
						if ( 1 === $display_compare_table_fields_head ) {
							$comparison_content_args['table_rows'][ $table_field['id'] ] = array( $compare_table_defined_fields[ $table_field['id'] ] );
						} else {
							$comparison_content_args['table_rows'][ $table_field['id'] ] = array();
						}
					}

					if ( isset( $table_field['id'] ) && 'attributes' === $table_field['id'] ) {

						$selected_attributes = ( addonify_compare_products_sortable_setting_value( 'product_attributes_to_compare' ) ) ?
						json_decode( addonify_compare_products_sortable_setting_value( 'product_attributes_to_compare' ), true ) :
						array();

						if ( $selected_attributes ) {
							foreach ( $selected_attributes as $attribute ) {
								if ( isset( $attribute['status'] ) && true === $attribute['status'] ) {
									if ( 1 === $display_compare_table_fields_head ) {
										$comparison_content_args['table_rows'][ 'attribute_id_' . $attribute['id'] ] = array( $attribute['name'] );
									} else {
										$comparison_content_args['table_rows'][ 'attribute_id_' . $attribute['id'] ] = array();
									}
								}
							}
						}
					}
				}
			}

			$products = addonify_compare_products_get_compare_products_list();

			$comparison_content_args['wc_product_ids'] = $products;

			$comparison_content_args['no_of_products'] = count( $products );

			if ( is_array( $products ) && $comparison_content_args['no_of_products'] > 0 ) {

				if ( $comparison_content_args['no_of_products'] > 1 ) {

					foreach ( $products as $product_id ) {

						$product = wc_get_product( $product_id );

						$comparison_content_args['table_rows']['product_id'][] = $product_id;

						$comparison_content_args['table_rows']['remove_button'][] = addonify_compare_products_product_remove_button( $product );

						foreach ( $content_to_display as $table_field ) {

							if ( isset( $table_field['status'] ) && true === $table_field['status'] ) {

								if ( isset( $table_field['id'] ) && 'attributes' !== $table_field['id'] ) {
									$comparison_content_args['table_rows'][ $table_field['id'] ][] = call_user_func( "addonify_compare_products_product_{$table_field['id']}", $product );
								}

								if ( isset( $table_field['id'] ) && 'attributes' === $table_field['id'] ) {
									$selected_attributes = ( addonify_compare_products_sortable_setting_value( 'product_attributes_to_compare' ) ) ?
									json_decode( addonify_compare_products_sortable_setting_value( 'product_attributes_to_compare' ), true ) :
									array();

									if ( $selected_attributes ) {
										foreach ( $selected_attributes as $attribute ) {
											if ( isset( $attribute['status'] ) && true === $attribute['status'] ) {
												$comparison_content_args['table_rows'][ 'attribute_id_' . $attribute['id'] ][] = addonify_compare_products_product_attribute_properties( $product, (int) $attribute['id'] );
											}
										}
									}
								}
							}
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


if ( ! function_exists( 'addonify_compare_products_product_remove_button' ) ) {
	/**
	 * HTML definition of product remove button displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_remove_button( $product ) {

		$remove_button = '<button class="addonify-remove-compare-products addonify-compare-table-remove-btn" data-product_id="' . esc_attr( $product->get_id() ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g id="_01_align_center" data-name="01 align center"><path d="M22,4H17V2a2,2,0,0,0-2-2H9A2,2,0,0,0,7,2V4H2V6H4V21a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V6h2ZM9,2h6V4H9Zm9,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V6H18Z"/><rect x="9" y="10" width="2" height="8"/><rect x="13" y="10" width="2" height="8"/></g></svg></button>';

		return apply_filters(
			'addonify_compare_products_filter_product_remove_button',
			$remove_button,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_image' ) ) {
	/**
	 * HTML definition of product image displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_image( $product ) {

		$product_image = '<a href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_image() ) . '</a>';

		return apply_filters(
			'addonify_compare_products_filter_product_image',
			$product_image,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_title' ) ) {
	/**
	 * HTML definition of product title displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_title( $product ) {

		$product_title = '<a class="product-title-link" href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_title() ) . '</a>';

		return apply_filters(
			'addonify_compare_products_filter_product_title',
			$product_title,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_price' ) ) {
	/**
	 * HTML definition of product price displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_price( $product ) {

		$product_price = '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';

		return apply_filters(
			'addonify_compare_products_filter_product_price',
			$product_price,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_description' ) ) {
	/**
	 * HTML definition of product remove button displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_description( $product ) {

		$product_description = wp_kses_post( $product->get_short_description() );

		return apply_filters(
			'addonify_compare_products_filter_product_description',
			$product_description,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_rating' ) ) {
	/**
	 * HTML definition of product rating displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_rating( $product ) {

		$rating         = wc_get_rating_html( $product->get_average_rating() );
		$ratings_count  = $product->get_rating_counts() ? count( $product->get_rating_counts() ) : 0;
		$product_rating = ( $rating ) ? wp_kses_post( $rating ) . '(' . esc_html( $ratings_count ) . ')' : esc_html__( 'N/A', 'addonify-compare-products' );

		return apply_filters(
			'addonify_compare_products_filter_product_rating',
			$product_rating,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_in_stock' ) ) {
	/**
	 * HTML definition of product stock status displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_in_stock( $product ) {

		$product_status = $product->get_stock_status();

		$stock_status = '-';

		switch ( $product_status ) {
			case 'instock':
				$stock_status = '<span class="stock in-stock">' . esc_html__( 'In stock', 'addonify-compare-products' ) . '</span>';
				break;
			case 'outofstock':
				$stock_status = '<span class="stock out-of-stock">' . esc_html__( 'Out of stock', 'addonify-compare-products' ) . '</span>';
				break;
			default:
		}

		return apply_filters(
			'addonify_compare_products_filter_product_stock_status',
			$stock_status,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_add_to_cart_button' ) ) {
	/**
	 * HTML definition of product add to cart button displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_add_to_cart_button( $product ) {

		$product_add_to_cart_button = do_shortcode( '[add_to_cart id="' . $product->get_id() . '" show_price="false" style="" ]' );

		return apply_filters(
			'addonify_compare_products_filter_product_add_to_cart_button',
			$product_add_to_cart_button,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_attribute_properties' ) ) {
	/**
	 * HTML definition of default product attribute displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 * @param int        $attribute_id Attribute taxonomy ID.
	 */
	function addonify_compare_products_product_attribute_properties( $product, $attribute_id ) {

		$product_attributes = $product->get_attributes();

		$attribute_value_names = array();

		if ( $product_attributes ) {

			foreach ( $product_attributes as $attribute ) {

				if ( $attribute->is_taxonomy() ) {

					$attribute_taxonomy = $attribute->get_taxonomy_object();

					if ( (int) $attribute_taxonomy->attribute_id === (int) $attribute_id ) {

						$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

						foreach ( $attribute_values as $attribute_value ) {
							$attribute_value_names[] = esc_html( $attribute_value->name );
						}
					}
				}
			}
		}

		$product_attribute_properties = ( $attribute_value_names ) ? wpautop( wptexturize( implode( ', ', $attribute_value_names ) ) ) : esc_html__( 'N/A', 'addonify-compare-products' );

		return apply_filters(
			'addonify_compare_products_filter_product_attribute_properties',
			$product_attribute_properties,
			$product,
			$attribute_id
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_weight' ) ) {
	/**
	 * HTML definition of product weight displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_weight( $product ) {

		$product_weight = ( $product->has_weight() && $product->get_weight() ) ? wc_format_weight( $product->get_weight() ) : esc_html__( 'N/A', 'addonify-compare-products' );

		return apply_filters(
			'addonify_compare_products_filter_product_weight',
			$product_weight,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_dimensions' ) ) {
	/**
	 * HTML definition of product dimensions displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_dimensions( $product ) {

		$product_dimensions = ( $product->has_dimensions() && $product->get_dimensions( false ) ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : esc_html__( 'N/A', 'addonify-compare-products' );

		return apply_filters(
			'addonify_compare_products_filter_product_dimensions',
			$product_dimensions,
			$product
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_product_additional_information' ) ) {
	/**
	 * HTML definition of product additional information displayed in compare table.
	 *
	 * @since 1.1.9
	 *
	 * @param WC_Product $product Product Object.
	 */
	function addonify_compare_products_product_additional_information( $product ) {

		$product_attributes = $product->get_attributes();

		$custom_attributes = array();

		if ( $product_attributes ) {

			foreach ( $product_attributes as $index => $attribute ) {

				if ( ! $attribute->is_taxonomy() ) {

					$custom_attributes[ $index ]['label'] = wc_attribute_label( $attribute->get_name() );

					$values = $attribute->get_options();

					$custom_attributes[ $index ]['values'] = ( $values ) ? wptexturize( implode( ', ', $values ) ) : '';
				}
			}
		}

		$additional_information_html = '';

		if ( $custom_attributes ) {
			foreach ( $custom_attributes as $custom_attribute ) {
				$additional_information_html .= '<div class="custom-attribute-' . $custom_attribute['label'] . '">';
				$additional_information_html .= '<label class="attribute-label">' . $custom_attribute['label'] . '</label> - ' . $custom_attribute['values'];
				$additional_information_html .= '</div>';
			}
		} else {
			$additional_information_html = esc_html__( 'N/A', 'addonify-compare-products' );
		}

		return apply_filters(
			'addonify_compare_products_filter_product_additional_information',
			$additional_information_html,
			$product
		);
	}
}
