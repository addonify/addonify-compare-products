(function ($) {
	'use strict';

	$(document).ready(function () {

		var body = $('body');
		let dockThumbnailContainer = $('#addonify-compare-dock-thumbnails');
		var dockMessage = $('#addonify-compare-dock-message');
		var searchModal = $('#addonify-compare-search-modal');
		var searchResultsContainer = $('#addonify-compare-search-results');
		var compareModal = $('#addonify-compare-modal');
		var compareModalContent = $('#addonify-compare-modal-content');
		var modalOverlay = $('#addonify-compare-modal-overlay');
		var searchModalOverlay = $('#addonify-compare-search-modal-overlay');
		var docCompareButton = $('.addonify-dock-compare-button');
		let localDataExpiration = addonifyCompareProductsJSObject.localDataExpiresIn;
		var searchInputTimer;

		/**
		 * Name of this plugin.
		 * 
		 * @var string
		 */
		let plugin_name = 'addonify_compare_products_plugin';

		/**
		 * Number of products in compare list.
		 * 
		 * @var string
		 */
		let compareItemsCount = getProductids().length;


		// run function that should be initialized first
		addonifyCompareProductsInit();

		//add item to compare
		body.on('click', '.addonify-cp-button, #addonify-compare-search-results .item-add', function (event) {

			event.preventDefault();

			var thisButton = $(this);

			var productId = thisButton.data('product_id');

			let product_ids = getProductids();

			if ( product_ids instanceof Array &&  product_ids.indexOf(productId) > -1 ) {
				console.log('Product already exists in compare list.')
				return;
			}

			product_ids.push(productId)

			//console.log(productId);

			$.ajax({
				url: addonifyCompareProductsJSObject.ajaxURL,
				type: 'POST',
				data: {
					action: addonifyCompareProductsJSObject.actionAddProduct,
					product_id: productId,
					product_ids: JSON.stringify( product_ids ),
					nonce: addonifyCompareProductsJSObject.nonce
				},
				success: function (response) {

					//console.log(response);

					if (response.success) {

						compareModalContent.html(response.compareModalContent);

						setProductids(product_ids)

						compareItemsCount = compareItemsCount + 1;

						thisButton.addClass('selected').attr('disabled', 'disabled');

						if (thisButton.hasClass('item-add')) {
							addonifyCompareProductsCloseSearchModal();
						}
						$('#addonify-compare-dock-thumbnails').append(response.product_image);

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

			let product_ids = getProductids();

			let index = product_ids.indexOf(productId)

			if ( index === -1 ) {
				console.log('Product does not exist in compare list.')
				return;
			}

			product_ids.splice( index, 1 )

			setProductids( product_ids )

			compareItemsCount = compareItemsCount - 1;

			$( '#addonify-compare-products-table tbody ' ).find( 'td[data-product_id=' + productId + ']' ).remove();

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

		});

		// show compare modal
		body.on('click', '#addonify-compare-dock-compare-btn', function (event) {
			event.preventDefault();

			body.addClass('addonify-compare-disable-scroll'); // CSS: body add overflow hidden.

			modalOverlay.removeClass('addonify-compare-hidden');

			body.removeClass('addonify-compare-dock-is-visible');

			compareModal.removeClass('addonify-compare-hidden');

			// show loading animation
			compareModalContent.addClass('loading');

			let product_ids = getLocalItem( 'product_ids' );
		});


		// show search modal
		body.on('click', '#addonify-compare-dock-add-item', function () {
			body.addClass('addonify-compare-disable-scroll');
			body.removeClass('addonify-compare-dock-is-visible');
			searchModalOverlay.removeClass('addonify-compare-hidden');
			searchModal.removeClass('addonify-compare-hidden');
			$('#addonify-compare-search-query').focus();
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

			let product_ids = getLocalItem( 'product_ids' );

			// only do ajax call if localstorage is not empty
			if ( product_ids instanceof Array && product_ids.length > 0 ) {
				$.ajax({
					url : addonifyCompareProductsJSObject.ajaxURL,
					type: 'POST',
					data: {
						action: addonifyCompareProductsJSObject.actionInit,
						product_ids: JSON.stringify( product_ids ),
						nonce: addonifyCompareProductsJSObject.nonce
					},
					success: function ( response ) {
						// update compare modal.
						compareModalContent.html(response.compareModalContent);

						// update other required divs.
						$.each( response.html, function ( i, val ) {
							$(i).replaceWith(val);
						})

						product_ids.forEach( function( val ) {
							$('.addonify-cp-button[data-product_id=' + val + ']').addClass('selected');
						} )

						check_for_shortcode(response.compareModalContent);
					}
				});
			}

			addonifyCompareProductsDockMessage();

			addonifyCompareProductsDockCompareButton();

			if (Number(compareItemsCount) != 0) {
				body.addClass('addonify-compare-dock-is-visible');
			} else {
				body.removeClass('addonify-compare-dock-is-visible');
			}
		}

		/**
		 * Display dock if products available in storage.
		 */
		function addonifyCompareProductsDisplayDock() {
			if (compareItemsCount === 0) {
				body.removeClass('addonify-compare-dock-is-visible');
			} else {
				body.addClass('addonify-compare-dock-is-visible');
			}
		}

		/**
		 * Displays compare button if two or more products available.
		 */
		function addonifyCompareProductsDockCompareButton() {
			if (compareItemsCount > 1) {
				docCompareButton.show();
			} else {
				docCompareButton.hide();
			}
		}

		/**
		 * Display message in dock if products less than two top compare.
		 */
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

		/**
		 * Display message in table if less than two items to compare.
		 */
		function addonifyCompareProductsComparisonTableMessage() {

			if (compareItemsCount < 2) {
				$('#addonify-compare-products-notice').removeClass('addonify-compare-hidden');
				$('#addonify-compare-products-table').hide();
			} else {
				$('#addonify-compare-products-notice').addClass('addonify-compare-hidden');
			}
		}

		/**
		 * Search products for comparison.
		 * 
		 * @param {string} searchVal Value to search in products.
		 */
		function addonifyCompareProductsSearchProducts(searchVal) {

			// Do not continue if search value is empty.
			if (!searchVal.length) return;

			// Show loading animation.
			$('#addonify-compare-search-results').html('').addClass('loading');

			let product_ids = getProductids();

			var data = {
				'action': addonifyCompareProductsJSObject.actionSearchProducts,
				'query': searchVal,
				'nonce': addonifyCompareProductsJSObject.nonce,
				'product_ids' : JSON.stringify( product_ids ),
			};

			$.post(
				addonifyCompareProductsJSObject.ajaxURL,
				data,
				function (response) {
					$('#addonify-compare-search-results').removeClass('loading').html(response);
				}
			);
		}

		/**
		 * Closes search modal after product is added.
		 */
		function addonifyCompareProductsCloseSearchModal() {
			searchResultsContainer.html('');
			$('#addonify-compare-search-query').val('');
			body.removeClass('addonify-compare-disable-scroll');
			searchModal.addClass('addonify-compare-hidden');
			searchModalOverlay.addClass('addonify-compare-hidden');
		}

		/**
		 * Return product ids stored in localstorage.
		 * 
		 * @returns {array|false} product ids.
		 */
		function getProductids() {
			return getLocalItem( 'product_ids' );
		}

		/**
		 * Save product ids in localstorage.
		 *
		 * @param {Object|string} val Value to be inserted.
		 */
		function setProductids( val ) {
			setLocalItem( 'product_ids', val );
		}

		/**
		 * Store item in localstorage.
		 * 
		 * @param {int} productId Product ID.
		 * @param {mixed} val Value to be stored in localstorage.
		 */
		function setLocalItem( name, val ) {
			if ( typeof val === 'object' ) {
				val = JSON.stringify( val )
			}
			const d = new Date();
			d.setTime( d.getTime() + (localDataExpiration * 24 * 60 * 60 * 1000) );
			let expires = d.getTime();
			localStorage.setItem( plugin_name + '_' + name, val )
			localStorage.setItem( plugin_name + '_deadline', expires )
		}

		/**
		 * Parse string to json.
		 *
		 * @param {string} json_str Json string.
		 * @return {object|false} Json object
		 */
		function parseJson( json_str ) {
			let json_val
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
		 * @returns {array|false}
		 */
		function getLocalItem( name ) {
			let localDeadline = localStorage.getItem( plugin_name + '_deadline' )
			if ( null !== localDeadline ) {
				const d = new Date();
				if ( d.getTime() < parseInt( localDeadline ) ) {
					return jsonToArray( parseJson( localStorage.getItem( plugin_name + '_' + name ) ) )
				} else {
					localStorage.removeItem( plugin_name + '_' + name )
					localStorage.removeItem( plugin_name + '_deadline' )
				}
			}
			return [];
		}

		/**
		 * Converts json to Array
		 * 
		 * @param {object} json Json object
		 * @returns {object|false} An array
		 */
		function jsonToArray(json){
			if ( json !== null && typeof json === 'object' ) {
				let result = new Array;
				let keys = Object.keys(json);
				if (keys.length > 0) {
					keys.forEach(function(key){
						result[key]= json[key];
					});
				}
				return result;
			} else {
				return false;
			}
		}

		/**
		 * Loads shortcode content if found on page.
		 */
		function check_for_shortcode( html ) {
			if ( body.has('#addonify-compare-products-comparison-table-on-page').length > 0 ) {
				$('#addonify-compare-products-comparison-table-on-page').html(html)
			}
		}

	})

})(jQuery);
