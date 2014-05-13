// closure to avoid namespace collision
(function($){
	// creates the plugin
	tinymce.create('tinymce.plugins.bwshortc', {
		// creates control instances based on the control's id.
		// our button's id is "bwshortc_button"
		createControl : function(id, controlManager) {
			if (id == 'bwshortc_button') {
				// creates the button
				var button = controlManager.createButton('bwshortc_button', {
					title : 'oik Shortcodes', // title of the button
					image : '../wp-content/plugins/oik/bw-sc-icon.gif',
					onclick : function() {
                 // bw.bw_shortc_button_callback_TinyMCE();
                   bw.bw_shortc_button_callback_TinyMCE();

						// triggers the thickbox
						//var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                  //bw_shortcodes_local.inTiny = true;
						//W = W - 80;
						//H = H - 84;
						//tb_show( 'oik Shortcodes', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=bwshortc-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('bwshortc', tinymce.plugins.bwshortc);


})(jQuery);