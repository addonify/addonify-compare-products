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
<div id="addonify-compare-modal-overlay" class="addonify-compare-hidden"></div><!-- #addonify-compare-modal-overlay.addonify-compare-hidden -->

<div id="addonify-compare-modal" class="addonify-compare-hidden">
	<div id="addonify-compare-model-inner" class="addonify-compare-model-inner">
		<button id="addonify-compare-close-button" class="addonify-cp-fake-button addonify-compare-all-close-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
		<div id="addonify-compare-modal-content" class="addonify-compare-scrollbar"></div>
	</div><!-- .addonify-compare-modal-inner -->
</div><!-- #addonify-compare-modal.addonify-compare-hidden -->
