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


		// run function that should be initialized first
		addonifyCompareProductsInit();


		body.on('click', 'button.addonify-cp-button, #addonify-compare-search-results .item-add', function (event) {

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
	})

})(jQuery);
