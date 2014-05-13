/* (C) Copyright Bobbing Wide 2014 */
tinymce.PluginManager.add('bwshortc', function(editor) {
	editor.addCommand('InsertShortcode', function() {
      bw.bw_shortc_button_callback_TinyMCE();
	});

	editor.addButton('bwshortc', {
		icon: 'bw-sc-icon',
		tooltip: 'Insert shortcode',
		cmd: 'InsertShortcode'
	});

	editor.addMenuItem('bwshortc', {
		icon: 'bw-sc-icon',
		text: '[]',
		cmd: 'InsertShortcode',
		context: 'insert'
	});
});
