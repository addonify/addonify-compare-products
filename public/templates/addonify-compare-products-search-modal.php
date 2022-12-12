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

?>
<div id="addonify-compare-search-modal-overlay" class="addonify-compare-hidden"></div><!-- #addonify-compare-search-modal-overlay.addonify-compare-hidden -->

<div id="addonify-compare-search-modal" class="addonify-compare-hidden" >
	<div class="addonify-compare-search-model-inner">

		<button id="addonify-compare-search-close-button" class="addonify-compare-all-close-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button><!-- #addonify-compare-search-close-button.addonify-compare-all-close-btn -->

		<div class="addonify-compare-search-modal-content">
			<input type="text" name="query" value="" id="addonify-compare-search-query" placeholder="<?php echo esc_attr__( 'Search here', 'addonify-compare-products' ); ?>"><!-- #addonify-compare-search-query -->
			<div id="addonify-compare-search-results" class="addonify-compare-scrollbar"></div><!-- #addonify-compare-search-results.addonify-compare-scrollbar -->
		</div><!-- .addonify-compare-search-modal-content -->

	</div><!-- .addonify-compare-search-model-inner -->
</div><!-- #addonify-compare-search-modal.addonify-compare-hidden -->
