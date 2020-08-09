(function( $ ) {
	'use strict';

	$( document ).ready(function(){
		
		var selected_product_ids = [];
		var $footer = $('#addonify-compare-footer');
		var $footer_thumbnail_container = $('#addonify-footer-thumbnails');
		var $message_sel = $('#addonify-compare-footer-message');

		// prevent empty anchor 
		$('#addonify-compare-products-footer-bar a').click(function(e){
			e.preventDefault();
		})	

		// run function that should be initialized first
		init();

		
		// add item
		$('button.addonify-cp-button').click(function(){

			var product_id = $(this).data('product_id');

			// product id is required
			if( ! product_id ) return;
			
			// prevent duplicates
			if( selected_product_ids.includes( product_id ) ) return;

			// store product ids into list
			selected_product_ids.push( product_id );

			// store product ids list into cookies, cookies will be deleted after browser close
			Cookies.set('addonify_selected_product_ids', selected_product_ids );

			show_thumbnail_preloader( product_id.toString() );

			// get thumbnail and dump into footer footer bar
			get_thumbnails_from_ajax( selected_product_ids.join(',') );

			// show footers bar 
			if( $footer.hasClass('hidden') ){
				$footer.removeClass('hidden');
			}


			// show hide footer message
			show_hide_footer_message();

		})


		// remove item
		$('body').on('click', 'span.addonify-footer-remove', function(){

			var product_id = $(this).data('product_id');

			// product id is required
			if( ! product_id ) return;
			
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

		})


		function fetch_items_from_cookies(){
			var items_from_cookies = Cookies.get('addonify_selected_product_ids');
			
			// do not continue if cookies is empty
			if( ! items_from_cookies ) return;

			// copy product ids into selected_product_ids
			selected_product_ids = items_from_cookies.split(',');
		}


		function init(){

			// fetch items from cookies
			fetch_items_from_cookies();

			// if selected_product_ids is not empty
			if( selected_product_ids.length ){

				var product_ids = selected_product_ids.join(',');

				// show preloader while image is loading
				show_thumbnail_preloader( product_ids );
				
				// generate thumbnail and dump into dom
				get_thumbnails_from_ajax( product_ids );
				
				// show footers bar 
				$footer.removeClass('hidden');

			}

			// show hide footer message
			show_hide_footer_message();

		}


		function get_thumbnails_from_ajax(product_ids){

			// do not continue if product ids is empty
			if( ! product_ids.length ) return;

			var data = {
                'action': addonify_compare_ajax_object.action,
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

			// add placeholder, while image is being loaded
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



	})

})( jQuery );
