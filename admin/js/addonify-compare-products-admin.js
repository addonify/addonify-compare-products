(function( $ ) {
	'use strict';

	$(document).ready(function(){

		// ios style switch
		$('input.lc_switch').lc_switch();


		// initiate wp color picker
		if( $('.color-picker').length ) {
			$('.color-picker').wpColorPicker();
		}


		// code editor
		if( $('#addonify_cp_custom_css').length ) {
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 2,
					tabSize: 2
				}
			);
			var editor = wp.codeEditor.initialize( $('#addonify_cp_custom_css'), editorSettings );
		}


		// show hide "select compare page" option if display type == comparision page
		
		var $compare_page_tr = $('#addonify_cp_compare_page').parents('tr');
		var $compare_display_type = $('#addonify_cp_compare_products_display_type');

		show_hide_select_compare_page_option();

		$compare_display_type.change(function(){
			show_hide_select_compare_page_option();
		})

		function show_hide_select_compare_page_option(){
			
			if( $compare_display_type.val() == 'page' ) {
				$compare_page_tr.fadeIn();
			}
			else{
				$compare_page_tr.fadeOut();
			}

		}



		// show hide content colors ------------------------------

		let $style_options_sel = $('#addonify_cp_load_styles_from_plugin');
		let $content_colors_sel = $('#addonify-content-colors-container');

		// self activate
		show_hide_content_colors();

		// detect state change
		$('body').delegate('#addonify_cp_load_styles_from_plugin', 'lcs-statuschange', function() {
			show_hide_content_colors();
		});

		
		function show_hide_content_colors(){

			let state = $style_options_sel.is(":checked") 

			if( state ){
				$content_colors_sel.slideDown();
			}
			else{
				$content_colors_sel.slideUp();
			}
		}
	
	})

})( jQuery );
