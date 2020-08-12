(function( $ ) {
	'use strict';

	$( document ).ready(function(){
		
		var $body = $('body');
		var selected_product_ids = [];
		var $footer_thumbnail_container = $('#addonify-footer-thumbnails');
		var $message_sel = $('#addonify-compare-footer-message');
		var $search_modal_sel = $('#addonify-compare-search-modal');
		var $search_result_container = $('#addonify-compare-search-results');
		var $compare_modal_sel = $('#addonify-compare-modal');
		var $compare_modal_content_sel = $('#addonify-compare-modal-content');
		var search_input_timer;
		var selected_items_has_changed = false;
		var compare_modal_is_open = false;
		var first_boot = true;


		// prevent default behavior
		$('#addonify-compare-products-footer-bar a, button.addonify-cp-button').click(function(e){
			e.preventDefault();
		})	

		// run function that should be initialized first
		init();

		
		// add item
		$body.on('click', 'button.addonify-cp-button, #addonify-compare-search-results .item-add', function(){

			var product_id = $(this).data('product_id');

			// product id is required
			if( ! product_id ) return;

			// hide search modal
			close_search_modal();


			// prevent duplicates
			if( ! selected_product_ids.includes( product_id ) ){

				selected_items_has_changed = true;
			
				// store product ids into list
				selected_product_ids.push( product_id );

				// store product ids list into cookies, cookies will be deleted after browser close
				Cookies.set('addonify_selected_product_ids', selected_product_ids );

				// mark button as selected
				$(this).addClass('selected');

				// generate thumbnails with preloader
				show_thumbnail_preloader( product_id.toString() );

				// replace thumbnail preloader image with actual image
				get_thumbnails_from_ajax( product_id.toString() );

				// close modal, if it is open
				$search_modal_sel.addClass('hidden');

				// show footers bar 
				$body.addClass('addonify-compare-footer-is-visible')

				// show hide footer message
				show_hide_footer_message();
				
			}
			else{
				selected_items_has_changed = false;
			}


			if( first_boot ){
				selected_items_has_changed = true;
				first_boot = false;
			}
			

			if( $(this).hasClass('addonify-cp-button') ){
				// button.addonify-cp-button

				if( selected_product_ids.length > 1 ){
					show_compare_modal();
				}
			}
			else{
				// "add" button from search modal

				// close and re-open modal
				close_compare_modal();
				show_compare_modal();
			}

		})

		
		// remove item
		$body.on('click', 'span.addonify-footer-remove', function(){

			var product_id = $(this).data('product_id');

			// product id is required
			if( ! product_id ) return;

			selected_items_has_changed = true;


			// mark button as not selected
			$('button.addonify-cp-button[data-product_id="' + product_id + '"]').removeClass('selected');;
			
			
			// remove id from selected_product_ids
			for( var i = 0; i < selected_product_ids.length; i++){
				if( selected_product_ids[i] == product_id ){
					selected_product_ids.splice(i, 1);
				}
			}

			// store product ids list into cookies, cookies will be deleted after browser close
			Cookies.set('addonify_selected_product_ids', selected_product_ids );

			$(this).parents('.addonify-footer-components').remove();

			// show hide footer message
			show_hide_footer_message();

			// if compare_modal is open, close and reopen with new data
			if( compare_modal_is_open ){
				close_compare_modal();
				show_compare_modal();
			}

		})


		// show search modal
		$body.on('click', '#addonify-footer-add', function(){
			$search_modal_sel.removeClass('hidden');
		})


		// close search modal
		$body.on('click', '#addonify-compare-search-close-button', function(){
			close_search_modal();
		})


		// on search
		$body.on('keyup', '#addonify-compare-search-query', function(){

			var query = $(this).val();

			clearTimeout(search_input_timer);
			search_input_timer = setTimeout(function () {
				// ajax search
				search_items_ajax( query );
			}, 500);

		})


		// footer compare button
		$body.on('click', '#addonify-footer-compare-btn', function(){

			if( compare_modal_is_open ){
				close_compare_modal();
			}
			else{
				if( first_boot ){
					selected_items_has_changed = true;
					first_boot = false;
				}
				show_compare_modal();
				selected_items_has_changed = false;
			}
		})


		// close compare modal
		$body.on('click', '#addonify-compare-close-button', function(){
			close_compare_modal();
		})


		// --------------------------------------------------------------------------------------


		function init(){

			// fetch items from cookies
			fetch_items_from_cookies();

			// if selected_product_ids is not empty
			if( selected_product_ids.length ){

				var product_ids = selected_product_ids.join(',');
				
				// mark buttons as selected
				mark_btn_as_selected(product_ids);

				// show preloader while image is loading
				show_thumbnail_preloader( product_ids );
				
				// generate thumbnail and dump into dom
				get_thumbnails_from_ajax( product_ids );
				
				// show footers bar 
				$body.addClass('addonify-compare-footer-is-visible')

			}


			// show hide footer message
			show_hide_footer_message();

		}


		function fetch_items_from_cookies(){
			var items_from_cookies = Cookies.get('addonify_selected_product_ids');

			// do not continue if cookies is empty
			if( ! items_from_cookies ) return;

			// generate selected_product_ids from cookies
			selected_product_ids = $.map( items_from_cookies.split(','), function(value){
				return parseInt(value);
			});

		}


		function mark_btn_as_selected(ids){
			var product_ids = ids.split(',');
			
			product_ids.forEach(function(product_id){
				$('button.addonify-cp-button[data-product_id="' + product_id + '"]').addClass('selected');
			})
		}


		function get_thumbnails_from_ajax(product_ids){

			// do not continue if product ids is empty
			if( ! product_ids.length ) return;

			var data = {
                'action': addonify_compare_ajax_object.action_get_thumbnails,
                'ids': product_ids
            };
	
            $.getJSON(addonify_compare_ajax_object.ajax_url, data, function(response) {
				$.each( response, function( key, val ) {
					$('.addonify-footer-thumbnail[data-product_id="'+ key +'"]').append('<img src="' + val + '">').removeClass('loading');
				});
            })

		}


		function show_thumbnail_preloader(product_ids){
			
			var ids_ar = product_ids.split(',');
			var template = '';

			// add placeholder, while thumbnail image is being loaded
			ids_ar.forEach(function(id){
				template += '<div class="addonify-footer-components" data-product_id="' + id + '"><div class="sortable addonify-footer-thumbnail loading" data-product_id="' + id + '"><span class="addonify-footer-remove" data-product_id="' + id + '"></span></div></div>';
			});

			$footer_thumbnail_container.append(template);
		}
		
		
		function show_hide_footer_message(){

			// $message_sel.
			if( selected_product_ids.length > 1 ){
				$message_sel.fadeOut();
			}
			else{
				$message_sel.fadeIn();
			}
		}


		function search_items_ajax(query){

			// do not continue if cookies is empty
			if( ! query.length ) return;

			// show loading animation
			$search_result_container.html('').addClass('loading');

			var data = {
                'action': addonify_compare_ajax_object.action_search_items,
                'query': query
            };
	
            $.get(addonify_compare_ajax_object.ajax_url, data, function( response ) {
				$search_result_container.removeClass('loading').html( response );
            })

		}

		
		function close_search_modal(){
			$search_modal_sel.addClass('hidden');
			$search_result_container.html('');
			$('#addonify-compare-search-query').val('');
		}


		function get_compare_contents_ajax(product_ids){

			// do not continue if product ids is empty
			if( ! product_ids || ! selected_items_has_changed ) return;

			var data = {
                'action': addonify_compare_ajax_object.action_get_compare_contents,
                'ids': product_ids
            };
	
            $.get(addonify_compare_ajax_object.ajax_url, data, function(response) {
				$compare_modal_content_sel.removeClass('loading').html(response);
            })

		}


		function show_compare_modal(){

			if( selected_items_has_changed ){
				// reset previous content
				$compare_modal_content_sel.html('');
			}

			compare_modal_is_open = true;
			
			$compare_modal_sel.removeClass('hidden');

			// show loading animation
			$compare_modal_content_sel.addClass('loading');

			// get contents from ajax
			get_compare_contents_ajax( selected_product_ids.join(',') );
		}


		function close_compare_modal(){
			$compare_modal_sel.addClass('hidden');
			compare_modal_is_open = false;
		}

	})

})( jQuery );
