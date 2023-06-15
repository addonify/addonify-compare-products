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

<div id="addonify-compare-products-table-wrapper">
	<p id="addonify-compare-products-notice" class="<?php echo esc_attr( implode( ' ', $message_css_classes ) ); ?>">
		<?php echo esc_html( $no_table_rows_message ); ?>
	</p><!-- #addonify-compare-products-notice -->

	<?php
	if ( $no_of_products > 1 ) {
		?>
		<table id="addonify-compare-products-table" class="<?php echo esc_attr( implode( ' ', $table_css_classes ) ); ?>">
			<tbody>
				<?php
				foreach ( $table_rows as $table_col => $col_content ) {

					if ( 'product_id' !== $table_col ) {

						echo '<tr>';

						foreach ( $col_content as $key => $value ) {
							echo '<td class="' . 'adfy-compare-products-table-row-' . $key . ' adfy-compare-products-td-field-' . $table_col . '" data-product_id="' . esc_attr( $table_rows['product_id'][ $key ] ) . '">' . '<div class="adfy-compare-products-table-row-content">' . ( $value ) . '</div></td>'; //phpcs:ignore
						}

						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table><!-- #addonify-compare-products-table -->
		<?php
	}
	?>
</div><!-- #addonify-compare-products-table-wrapper -->
