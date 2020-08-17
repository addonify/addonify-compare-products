(function( $ ) {
	'use strict';

	$(document).ready(function(){

		// ios style switch
		$('input.lc_switch').lc_switch();

		
		if( addonify_objects.color_picker_is_available ){
			// initiate wp color picker
			$('.color-picker').wpColorPicker();
		}


		if( addonify_objects.code_editor_is_available ){

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
		}


		// show hide overlay btn offset position ------------------------------

		let $button_position_sel = $('#addonify_cp_compare_products_btn_position');
		let $overlay_btn_wrapper_sel = $('#addonify-image-overlay-btn-offset-wrapper');


		// detect state change
		$button_position_sel.change(function(){
			show_hide_overlay_btn();
		});

		show_hide_overlay_btn();

		
		function show_hide_overlay_btn(){

			let state = $button_position_sel.val();
			let $parent = $overlay_btn_wrapper_sel.parents('tr');

			if( state == 'overlay_on_image' ){
				$parent.fadeIn();
			}
			else{
				$parent.fadeOut();
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
