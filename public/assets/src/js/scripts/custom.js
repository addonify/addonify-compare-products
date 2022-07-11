(function ($) {
	'use strict';

	$(document).ready(function () {

		var $body = $('body');
		var selected_product_ids = [];
		var $footer_thumbnail_container = $('#addonify-compare-dock-thumbnails');
		var $message_sel = $('#addonify-compare-dock-message');
		var $search_modal_sel = $('#addonify-compare-search-modal');
		var $search_result_container = $('#addonify-compare-search-results');
		var $compare_modal_sel = $('#addonify-compare-modal');
		var $compare_modal_content_sel = $('#addonify-compare-modal-content');
		var $footer_compare_btn = $('#addonify-compare-dock-compare-btn');
		var search_input_timer;
		var items_list_has_changed = false;
		var compare_modal_is_open = false;
		var first_boot = true;
		var display_type = addonify_compare_ajax_object.display_type;
		var page_url = addonify_compare_ajax_object.comparision_page_url;
		var modalOverlay = $('#addonify-compare-modal-overlay');
		var searchModalOverlay = $('#addonify-compare-search-modal-overlay');


		$("#addonify-compare-dock-thumbnails").sortable({
			stop: function () { get_selected_product_ids_from_dom(); }
		}).disableSelection();


		// prevent default behavior
		$('#addonify-compare-products-footer-bar a, button.addonify-cp-button').click(function (e) {
			e.preventDefault();
		})

		// run function that should be initialized first
		init();


		// add item
		$body.on('click', 'button.addonify-cp-button, #addonify-compare-search-results .item-add', function (event) {

			event.preventDefault();

			var product_id = $(this).data('product_id');

			// product id is required
			if (!product_id) return;

			// hide search modal
			close_search_modal();


			// prevent duplicates
			if (!selected_product_ids.includes(product_id)) {

				// disable button
				$(this).attr('disabled', true);

				items_list_has_changed = true;

				// store product ids into list
				selected_product_ids.push(product_id);

				// store product ids list into cookies, cookies will be deleted after browser close
				setCookie(selected_product_ids, product_id);

				// mark button as selected
				$(this).addClass('selected');

				// close modal, if it is open
				$search_modal_sel.addClass('addonify-compare-hidden');


				// generate thumbnails with preloader
				show_thumbnail_preloader(product_id.toString());

				// replace thumbnail preloader image with actual image
				get_thumbnails_from_ajax(product_id.toString());

				// show footers bar 
				$body.addClass('addonify-compare-dock-is-visible');

				// show hide footer message
				show_hide_footer_message();


			}
			else {
				items_list_has_changed = false;
			}


			show_hide_footer_bar();

			show_hide_footer_compare_button();


			if (first_boot) {
				items_list_has_changed = true;
				first_boot = false;
			}

		})


		// remove item
		$body.on('click', '.addonify-compare-dock-remove-item-btn', function (e) {

			e.preventDefault();

			var product_id = $(this).data('product_id');

			// product id is required
			if (!product_id) return;

			items_list_has_changed = true;


			// mark button as not selected
			$('button.addonify-cp-button[data-product_id="' + product_id + '"]').removeClass('selected').removeAttr('disabled');


			// remove id from selected_product_ids
			for (var i = 0; i < selected_product_ids.length; i++) {
				if (selected_product_ids[i] == product_id) {
					selected_product_ids.splice(i, 1);
				}
			}

			// store product ids list into cookies, cookies will be deleted after browser close
			setCookie(selected_product_ids);

			// remove thumbnail from footer
			$('.addonify-compare-dock-components[data-product_id="' + product_id + '"]').remove();

			show_hide_footer_bar();

			show_hide_footer_compare_button();

			// show hide footer message
			show_hide_footer_message();

			// if compare_modal is open, close and reopen with new data
			if (compare_modal_is_open) {
				close_compare_modal();
				items_list_has_changed = true;
				show_compare_modal();
			}

		})


		// show search modal
		$body.on('click', '#addonify-compare-dock-add-item', function () {
			$body.removeClass('addonify-compare-dock-is-visible');
			searchModalOverlay.removeClass('addonify-compare-hidden');
			$search_modal_sel.removeClass('addonify-compare-hidden');
		})


		// close search modal
		$body.on('click', '#addonify-compare-search-close-button, #addonify-compare-search-modal-overlay', function () {
			$body.addClass('addonify-compare-dock-is-visible');
			searchModalOverlay.addClass('addonify-compare-hidden');
			close_search_modal();
		})


		// on search
		$body.on('keyup', '#addonify-compare-search-query', function () {

			var query = $(this).val();

			clearTimeout(search_input_timer);
			search_input_timer = setTimeout(function () {
				// ajax search
				search_items_ajax(query);
			}, 500);

		})


		// main compare button in footer
		$body.on('click', '#addonify-compare-dock-compare-btn', function () {

			$body.addClass('addonify-compare-modal-is-visible');

			if (display_type == 'page') {
				window.location.href = page_url;
				return;
			}


			// following code will not be executed if display type is "page"

			if (compare_modal_is_open) {

				close_compare_modal();
			}
			else {
				if (first_boot) {
					items_list_has_changed = true;
					first_boot = false;
				}

				show_compare_modal();
			}
		})


		// close compare modal
		$body.on('click', '#addonify-compare-close-button, #addonify-compare-modal-overlay', function () {

			close_compare_modal();
			$body.removeClass('addonify-compare-modal-is-visible');
		});

		// --------------------------------------------------------------------------------------


		function init() {

			prepare_overlay_buttons();

			// fetch items from cookies
			fetch_items_from_cookies();


			show_hide_footer_compare_button();

			// if selected_product_ids is not empty
			if (selected_product_ids.length) {

				var product_ids = selected_product_ids.join(',');

				// mark buttons as selected
				mark_btn_as_selected(product_ids);

				// show preloader while image is loading
				show_thumbnail_preloader(product_ids);

				// generate thumbnail and dump into dom
				get_thumbnails_from_ajax(product_ids);

				// show footers bar 
				show_hide_footer_bar();

			}


			// show hide footer message
			show_hide_footer_message();

		}


		function fetch_items_from_cookies() {

			var items_from_cookies = Cookies.get('addonify_compare_product_selected_product_ids');

			// do not continue if cookies is empty
			if (!items_from_cookies) return;

			// generate selected_product_ids from cookies
			selected_product_ids = $.map(items_from_cookies.split(','), function (value) {
				return parseInt(value);
			});

		}


		function prepare_overlay_buttons() {

			var overlay_btn_wrapper_class = 'addonify-overlay-btn-wrapper';
			var overlay_btn_class = 'addonify-overlay-btn';

			var $overlay_btn_wrapper_sel = $('.' + overlay_btn_wrapper_class);
			var $overlay_parent_container = $('.addonify-overlay-buttons');

			if ($overlay_btn_wrapper_sel.length) {

				//  wrapper div already exists
				$overlay_parent_container.each(function () {

					// clone original button
					var btn_clone = $('button.' + overlay_btn_class, this).clone();

					// delete oroginal buttons
					$('button.' + overlay_btn_class, this).remove();

					// append to wrapper class
					$('.' + overlay_btn_wrapper_class, this).append(btn_clone);
				})
			}
			else {
				// wrap all buttons into a single div
				$overlay_parent_container.each(function () {
					$('button.' + overlay_btn_class, this).wrapAll('<div class=" ' + overlay_btn_wrapper_class + ' " />');
				});

				var img_height = $('img.attachment-woocommerce_thumbnail').height();

				// set height of the button wrapper div
				$('.' + overlay_btn_wrapper_class).css('height', img_height + 'px');


				$('.' + overlay_btn_wrapper_class).hover(function () {
					$(this).css('opacity', 1);
				}, function () {
					$(this).css('opacity', 0);
				})
			}


		}


		function show_hide_footer_compare_button() {
			if (selected_product_ids.length > 1) {
				$footer_compare_btn.fadeIn();
			}
			else {
				$footer_compare_btn.fadeOut();
			}

		}


		function show_hide_footer_bar() {
			if (selected_product_ids.length) {
				$body.addClass('addonify-compare-dock-is-visible');
			}
			else {
				$body.removeClass('addonify-compare-dock-is-visible');
			}

		}


		function mark_btn_as_selected(ids) {
			var product_ids = ids.split(',');

			product_ids.forEach(function (product_id) {
				$('button.addonify-cp-button[data-product_id="' + product_id + '"]').addClass('selected').attr('disabled', true);
			})
		}


		function get_thumbnails_from_ajax(product_ids) {

			// do not continue if product ids is empty
			if (!product_ids.length) return;

			var data = {
				'action': addonify_compare_ajax_object.action_get_thumbnails,
				'ids': product_ids
			};

			$.getJSON(addonify_compare_ajax_object.ajax_url, data, function (response) {
				$.each(response, function (key, val) {
					$('.addonify-compare-dock-thumbnail[data-product_id="' + key + '"]').append('<img src="' + val + '">').removeClass('loading');
				});
			})

		}


		function show_thumbnail_preloader(product_ids) {

			var ids_ar = product_ids.split(',');
			var template = '';
			var removeButtonIcon = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z' /></svg>"

			// add placeholder, while thumbnail image is being loaded
			ids_ar.forEach(function (id) {
				template += '<div class="addonify-compare-dock-components" data-product_id="' + id + '"><div class="sortable addonify-compare-dock-thumbnail loading" data-product_id="' + id + '"><span class="addonify-compare-dock-remove-item-btn" data-product_id="' + id + '">' + removeButtonIcon + '</span></div></div>';
			});

			$footer_thumbnail_container.append(template);
		}


		function show_hide_footer_message() {

			if (selected_product_ids.length < 2) {
				$message_sel.removeClass('addonify-compare-hidden');
			}
			else {
				$message_sel.addClass('addonify-compare-hidden');
			}

		}


		function search_items_ajax(query) {

			// do not continue if cookies is empty
			if (!query.length) return;

			// show loading animation
			$search_result_container.html('').addClass('loading');

			var data = {
				'action': addonify_compare_ajax_object.action_search_items,
				'query': query,
				'nonce': addonify_compare_ajax_object.nonce
			};

			$.post(addonify_compare_ajax_object.ajax_url, data, function (response) {
				$search_result_container.removeClass('loading').html(response);
			})

		}


		function close_search_modal() {
			$search_modal_sel.addClass('addonify-compare-hidden');
			$search_result_container.html('');
			$('#addonify-compare-search-query').val('');
		}


		function get_compare_contents_ajax(product_ids) {

			// do not continue if product ids is empty
			if (!product_ids || !items_list_has_changed) return;

			var data = {
				'action': addonify_compare_ajax_object.action_get_compare_contents
			};

			$.get(addonify_compare_ajax_object.ajax_url, data, function (response) {
				$compare_modal_content_sel.removeClass('loading').html(response);
			})

		}


		function show_compare_modal() {

			modalOverlay.removeClass('addonify-compare-hidden');

			$body.removeClass('addonify-compare-dock-is-visible');

			if (items_list_has_changed) {
				// reset previous content
				$compare_modal_content_sel.html('');
			}

			compare_modal_is_open = true;

			$compare_modal_sel.removeClass('addonify-compare-hidden');

			// show loading animation
			$compare_modal_content_sel.addClass('loading');

			// get contents from ajax
			get_compare_contents_ajax(selected_product_ids.join(','));
		}


		function close_compare_modal() {

			modalOverlay.addClass('addonify-compare-hidden');

			$body.addClass('addonify-compare-dock-is-visible');

			$compare_modal_sel.addClass('addonify-compare-hidden');
			compare_modal_is_open = false;
			items_list_has_changed = false;
		}


		function get_selected_product_ids_from_dom() {

			selected_product_ids = [];

			$footer_thumbnail_container.find('.addonify-compare-dock-components').each(function () {
				selected_product_ids.push($(this).data('product_id'));
			});

			items_list_has_changed = true;

			setCookie(selected_product_ids);

			if (compare_modal_is_open) {

				close_compare_modal();
				items_list_has_changed = true;
				show_compare_modal();

			}

		}


		function setCookie(ids) {

			ids = ids.join(',');

			if (addonify_compare_ajax_object.cookie_expire == 'browser') {
				Cookies.set('addonify_compare_product_selected_product_ids', ids, { path: addonify_compare_ajax_object.cookie_path, domain: addonify_compare_ajax_object.cookie_domain });
			}
			else {
				Cookies.set('addonify_compare_product_selected_product_ids', ids, { path: addonify_compare_ajax_object.cookie_path, domain: addonify_compare_ajax_object.cookie_domain, expires: parseInt(addonify_compare_ajax_object.cookie_expire) });
			}

		}

	})

})(jQuery);
