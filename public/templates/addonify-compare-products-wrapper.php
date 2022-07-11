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

<div id="addonify-compare-dock" >
	<div id="addonify-compare-dock-message" class="hidden" >
		<?php echo esc_html__( 'Select more than one item for comparision.', 'addonify-compare-products' ); ?>
	</div>

	<div id="addonify-compare-dock-inner">

		<!-- thumbnails will be added here by javascript -->
		<div id="addonify-compare-dock-thumbnails"></div>

		<!-- add product button -->
		<div class="addonify-compare-dock-components">
			<button class="addonify-cp-fake-button" id="addonify-compare-dock-add-item" aria-label="<?php echo esc_attr__( 'Add product', 'addonify-compare-products' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
					<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
				</svg>
			</button>
		</div>

		<!-- compare button -->
		<div class="addonify-compare-dock-components">
			<button id="addonify-compare-dock-compare-btn">
				<?php echo esc_html( $label ); ?>
			</button>
		</div>
	</div>
</div> 


<!-- search modal -->
<div id="addonify-compare-search-modal-overlay" class="addonify-compare-hidden"></div>
<div id="addonify-compare-search-modal" class="addonify-compare-hidden" >
	<div class="addonify-compare-search-model-inner">

		<button id="addonify-compare-search-close-button" class="addonify-compare-all-close-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>

		<div class="addonify-compare-search-modal-content">
			<input type="text" name="query" value="" id="addonify-compare-search-query" placeholder="<?php echo esc_attr__( 'Search here', 'addonify-compare-products' ); ?>">
			<div id="addonify-compare-search-results"></div>
		</div>

	</div>
</div>
