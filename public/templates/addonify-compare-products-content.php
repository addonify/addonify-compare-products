<?php
/**
 * Template for the front end part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 */

/**
 * Template for the front end part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>

<div id="addonify-compare-products-table-wrapper" >

	<p id="addonify-compare-products-notice" class="<?php echo esc_attr( implode( ' ', $message_css_classes ) ); ?>"><?php echo esc_html( $no_table_rows_message ); ?></p><!-- #addonify-compare-products-notice -->

	<?php 
	if ( $table_rows ) { 
		?>
		<table id="addonify-compare-products-table" class="<?php echo esc_attr( implode( ' ', $table_css_classes ) ); ?>">
			<tbody>
				<?php
				foreach ( $table_rows as $tablet_col => $col_content ) {
					if ( $tablet_col != 'product_id' ) {
						echo '<tr>';
						foreach ( $col_content as $key => $value ) {
							echo '<td class="' . ( ( $key === 0 ) ? 'acp-table-head' : 'acp-table-row-' . $key . ' acp-table-field-' . $tablet_col ) . '" data-product_id="' . esc_attr( $table_rows['product_id'][$key] ) . '">' . wp_kses_post( $value ) . '</td>';
						}
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table><!-- #addonify-compare-products-table -->
		<?php 
	} 
	?>
	
</div><!-- #addonify-compare-products-table-wrapper -->
