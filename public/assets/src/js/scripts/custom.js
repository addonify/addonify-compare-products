(function ($) {
	'use strict';

	$(document).ready(function () {

		var body = $('body');
		var dockThumbnailContainer = $('#addonify-compare-dock-thumbnails');
		var dockMessage = $('#addonify-compare-dock-message');
		var searchModal = $('#addonify-compare-search-modal');
		var searchResultsContainer = $('#addonify-compare-search-results');
		var compareModal = $('#addonify-compare-modal');
		var compareModalContent = $('#addonify-compare-modal-content');
		var searchInputTimer;
		var modalOverlay = $('#addonify-compare-modal-overlay');
		var searchModalOverlay = $('#addonify-compare-search-modal-overlay');
		var compareItemsCount = addonifyCompareProductsJSObject.compareItemsCount;
		var docCompareButton = $('.addonify-dock-compare-button');
		var products_to_compare = new Array;

		/**
		 * Name of this plugin.
		 * 
		 * @var string
		 */
		let plugin_name = 'addonify-compare-products';


		// run function that should be initialized first
		addonifyCompareProductsInit();

		//add item to compare
		body.on('click', '.addonify-cp-button, #addonify-compare-search-results .item-add', function (event) {

			event.preventDefault();

			var thisButton = $(this);

			var productId = thisButton.data('product_id');

			//console.log(productId);

			$.ajax({
				url: addonifyCompareProductsJSObject.ajaxURL,
				type: 'POST',
				data: {
					action: addonifyCompareProductsJSObject.actionAddProduct,
					product_id: productId,
					nonce: addonifyCompareProductsJSObject.nonce
				},
				success: function (response) {

					//console.log(response);

					if (response.success) {

						compareItemsCount = response.items_count;

						thisButton.addClass('selected').attr('disabled', 'disabled');

						if (thisButton.hasClass('item-add')) {
							addonifyCompareProductsCloseSearchModal();
						}

						dockThumbnailContainer.append(response.product_image);

						$('.addonify-compare-dock-thumbnail[data-product_id="' + productId + '"]').append(response.product_image).removeClass('loading');

						addonifyCompareProductsComparisonTableMessage();

						addonifyCompareProductsDisplayDock();

						addonifyCompareProductsDockCompareButton();

						// show hide dock message
						addonifyCompareProductsDockMessage();
					}
				}
			});
		});

		// remove item
		body.on('click', '.addonify-compare-dock-remove-item-btn, .addonify-remove-compare-products', function (e) {

			e.preventDefault();

			var thisButton = $(this);

			var productId = thisButton.data('product_id');

			$.ajax({
				url: addonifyCompareProductsJSObject.ajaxURL,
				type: 'POST',
				data: {
					action: addonifyCompareProductsJSObject.actionRemoveProduct,
					product_id: productId,
					nonce: addonifyCompareProductsJSObject.nonce
				},
				success: function (response) {
					//console.log(response);
					if (response.success) {
						
						compareItemsCount = response.items_count;

						if (thisButton.hasClass('addonify-compare-table-remove-btn')) {
							$('td[data-product_id="' + productId + '"]').remove();
							// mark button as not selected
							$('button.addonify-cp-button[data-product_id="' + productId + '"]').removeClass('selected').removeAttr('disabled');

							if (compareItemsCount < 2) {

								body.removeClass('addonify-compare-disable-scroll'); // CSS: body add overflow hidden.

								modalOverlay.addClass('addonify-compare-hidden');

								compareModal.addClass('addonify-compare-hidden');
							}
						}

						if (thisButton.hasClass('addonify-compare-docker-remove-button')) {

							// mark button as not selected
							$('button.addonify-cp-button[data-product_id="' + productId + '"]').removeClass('selected').removeAttr('disabled');

							// remove thumbnail from dock
							$('.addonify-compare-dock-components[data-product_id="' + productId + '"]').remove();
						}

						if ($('.addonify-compare-dock-components[data-product_id="' + productId + '"]')) {
							$('.addonify-compare-dock-components[data-product_id="' + productId + '"]').remove();
						}

						addonifyCompareProductsComparisonTableMessage();

						addonifyCompareProductsDisplayDock();

						addonifyCompareProductsDockCompareButton();

						// show hide dock message
						addonifyCompareProductsDockMessage();
					}
				}
			});

		});

		body.on('click', '#addonify-compare-dock-compare-btn', function (event) {
			event.preventDefault();

			body.addClass('addonify-compare-disable-scroll'); // CSS: body add overflow hidden.

			modalOverlay.removeClass('addonify-compare-hidden');

			body.removeClass('addonify-compare-dock-is-visible');

			compareModal.removeClass('addonify-compare-hidden');

			// show loading animation
			compareModalContent.addClass('loading');

			$.ajax({
				url: addonifyCompareProductsJSObject.ajaxURL,
				type: 'POST',
				data: {
					action: addonifyCompareProductsJSObject.actionGetCompareContent,
				},
				success: function (response) {
					if (response) {
						compareModalContent.removeClass('loading').html(response);
						compareModal.removeClass('addonify-compare-hidden');
					}
				}
			});
		});


		// show search modal
		body.on('click', '#addonify-compare-dock-add-item', function () {
			body.addClass('addonify-compare-disable-scroll');
			body.removeClass('addonify-compare-dock-is-visible');
			searchModalOverlay.removeClass('addonify-compare-hidden');
			searchModal.removeClass('addonify-compare-hidden');
		})


		// close search modal
		body.on('click', '#addonify-compare-search-close-button, #addonify-compare-search-modal-overlay', function () {

			body.removeClass('addonify-compare-disable-scroll');
			body.addClass('addonify-compare-dock-is-visible');
			addonifyCompareProductsCloseSearchModal();
		})


		// on search
		body.on('keyup', '#addonify-compare-search-query', function () {

			var searchVal = $(this).val();

			clearTimeout(searchInputTimer);

			searchInputTimer = setTimeout(function () {
				// ajax search
				addonifyCompareProductsSearchProducts(searchVal);
			}, 500);
		})



		// close compare modal
		body.on('click', '#addonify-compare-close-button, #addonify-compare-modal-overlay', function () {

			body.removeClass('addonify-compare-disable-scroll');

			modalOverlay.addClass('addonify-compare-hidden');

			addonifyCompareProductsDisplayDock();

			compareModal.addClass('addonify-compare-hidden');

			body.removeClass('addonify-compare-modal-is-visible');
		});


		// --------------------------------------------------------------------------------------


		function addonifyCompareProductsInit() {

			//console.log(addonifyCompareProductsJSObject);

			addonifyCompareProductsDockMessage();

			addonifyCompareProductsDockCompareButton();

			if (Number(compareItemsCount) != 0) {
				body.addClass('addonify-compare-dock-is-visible');
			} else {
				body.removeClass('addonify-compare-dock-is-visible');
			}
		}


		function addonifyCompareProductsDisplayDock() {
			if (compareItemsCount === 0) {
				body.removeClass('addonify-compare-dock-is-visible');
			} else {
				body.addClass('addonify-compare-dock-is-visible');
			}
		}


		function addonifyCompareProductsDockCompareButton() {
			if (compareItemsCount > 1) {
				docCompareButton.show();
			} else {
				docCompareButton.hide();
			}
		}

		function addonifyCompareProductsDockMessage() {

			if (compareItemsCount < 2) {
				dockMessage.removeClass('addonify-compare-hidden');
				$('#addonify-compare-dock-inner').removeClass('full');
			}
			else {
				dockMessage.addClass('addonify-compare-hidden');
				$('#addonify-compare-dock-inner').addClass('full');
			}
		}

		function addonifyCompareProductsComparisonTableMessage() {

			if (compareItemsCount < 2) {
				$('#addonify-compare-products-notice').removeClass('addonify-compare-hidden');
				$('#addonify-compare-products-table').hide();
			} else {
				$('#addonify-compare-products-notice').addClass('addonify-compare-hidden');
			}
		}

		function addonifyCompareProductsSearchProducts(searchVal) {

			// Do not continue if search value is empty.
			if (!searchVal.length) return;

			// Show loading animation.
			$('#addonify-compare-search-results').html('').addClass('loading');

			var data = {
				'action': addonifyCompareProductsJSObject.actionSearchProducts,
				'query': searchVal,
				'nonce': addonifyCompareProductsJSObject.nonce
			};

			$.post(
				addonifyCompareProductsJSObject.ajaxURL,
				data,
				function (response) {
					$('#addonify-compare-search-results').removeClass('loading').html(response);
				}
			);
		}

		function addonifyCompareProductsCloseSearchModal() {
			searchResultsContainer.html('');
			$('#addonify-compare-search-query').val('');
			body.removeClass('addonify-compare-disable-scroll');
			searchModal.addClass('addonify-compare-hidden');
			searchModalOverlay.addClass('addonify-compare-hidden');
		}

		/**
		 * Sets cookie under plugin name.
		 *
		 * @param {mixed} cvalue Value to store in cookie.
		 * @param {int} exdays Expiration of cookie(in days).
		 */
		function setTheCookie(cvalue, exdays) {
			const d = new Date();
			d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
			let expires = "expires="+d.toUTCString();
			document.cookie = plugin_name + "=" + cvalue + ";" + expires + ";path=/";
		}
		
		/**
		 * Returns values stored in cookie.
		 *
		 * @returns mixed
		 */
		function getTheCookie() {
			let name = plugin_name + "=";
			let cookieArray = document.cookie.split(';');
			for(let i = 0; i < cookieArray.length; i++) {
				let c = cookieArray[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}

		/**
		 * Converts json to Map
		 * 
		 * @param {object} json Json object
		 * @returns {object} A map object
		 */
		function jsonToMap(json){
			let result = new Map;
			let keys = Object.keys(json);
			keys.forEach(function(key){
				result.set(key, json[key]);
			});
			return result;
		}

		/**
		 * Store item in localstorage.
		 * 
		 * @param {int} productId Product ID.
		 * @param {mixed} val Value to be stored in localstorage.
		 */
		function setLocalItem( productId, val ) {
			localStorage.setItem( plugin_name + '_' + productId, val )
		}

		/**
		 * Parse string to json.
		 *
		 * @param {string} json_str Json string.
		 * @return {object|false} Json object
		 */
		function parseJson( json_str ) {
			try {
				json_val = JSON.parse(json_str)
			} catch(e) {
				return false;
			}
			return json_val
		}

		/**
		 * Get item from localstorage.
		 *
		 * @param {int} productId Product Id.
		 * @returns string
		 */
		function getLocalItem( productId ) {
			return localStorage.getItem( plugin_name + '_' + productId )
		}

		/**
		 * Get HTML Template for Compare Modal.
		 *
		 * @returns {string}
		 */
		function getModalTemplate() {

			transformRowToColumn( products_to_compare )

			let compare_modal_template = 
			`
			<div id="addonify-compare-products-table-wrapper">

				<button id="addonify-compare-close-button" class="addonify-cp-fake-button addonify-compare-all-close-btn">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
				<p id="addonify-compare-products-notice" class="`+ message_css_classses +`">
					` + no_table_rows_message + `
				</p><!-- #addonify-compare-products-notice -->
					<table id="addonify-compare-products-table" class="<?php echo esc_attr( implode( ' ', $table_css_classes ) ); ?>">
						<tbody>`;
			if ( products_to_compare instanceof Map && products_to_compare.size > 0 ) {
				if ( products_to_compare.has('product_id') ) {
					product_ids = products_to_compare.get('product_id')
					if ( product_ids.isArray() && product_ids.length > 0 ) {
						products_to_compare.delete('product_id')
						products_to_compare.forEach( function( val, index ) {
							if ( index !== 'product_id' ) {
								compare_modal_template = '<tr>';
								compare_modal_template = '<td class="' ;
								compare_modal_template = '</tr>';
							}
						} );
					}
				}
			}

			compare_modal_template += `
						</tbody>
					</table><!-- #addonify-compare-products-table -->
				
			</div><!-- #addonify-compare-products-table-wrapper -->
			`
			return compare_modal_template;
		}

		/**
		 * Transforms row values of array into column of a two dimensional array.
		 * 
		 * @param {object} productMap Array Map to be transformed
		 * @return {object} Array Map of string with array.
		 */
		function transformRowToColumn( productMap ) {
			let map = new Map
			let image, title, price, rating, description, stock_info, product_id
			if ( productMap instanceof Map && productMap.size > 0 ) {
				image = title = price = rating = description = stock_info = product_id = new Array
				productMap.forEach( function ( val ) {
					if ( val instanceof Map ) {
						image.push(val.get('image'))
						title.push(val.get('title'))
						price.push(val.get('price'))
						description.push(val.get('description'))
						stock_info.push(val.get('stock_info'))
						rating.push(val.get('rating'))
						product_id.push(val.get('product_id'))
					}
				} )
				map.set('image', image)
				map.set('title', title)
				map.set('price', price)
				map.set('description', description)
				map.set('stock_info', stock_info)
				map.set('rating', rating)
				map.set('product_id', product_id)
			}
			return map
		}
	})

})(jQuery);
