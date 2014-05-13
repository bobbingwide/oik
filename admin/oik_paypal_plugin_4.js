
tinymce.PluginManager.add('bwpaypal', function(editor) {

	editor.addCommand('InsertPayPal', function() {
      // Discard any existing form and rebuild
      jQuery('#bwpaypal-form').remove();
	
	// executes this when the DOM is ready
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="bwpaypal-form"><table id="bwpaypal-table" class="form-table"><tr>\
				<th><label for="bwpaypal-type">Type</label></th>\
				<td><select name="type" id="bwpaypal-type">\
					<option value="pay">Pay Now</option>\
					<option value="buy">Buy Now</option>\
               <option value="donate">Donate</option>\
               <option value="add">Add to Cart</option>\
					<option value="view">View Cart / Checkout</option>\
				</select><br />\
				<small>select button type</small></td>\
			</tr>\
			<tr>\
				<th><label for="bwpaypal-amount">Amount</label></th>\
				<td><input type="text" id="bwpaypal-amount" name="amount" value="" /><br />\
				<small>specify the price</small></td>\
			</tr>\
			<tr>\
				<th><label for="bwpaypal-productname">Product Name</label></th>\
				<td><input type="text" name="productname" id="bwpaypal-productname" value="" /><br />\
				<small>specify the product name</small>\
			</tr>\
			<tr>\
				<th><label for="bwpaypal-sku">Product SKU</label></th>\
				<td><input type="text" name="sku" id="bwpaypal-sku" value="" /><br />\
				<small>specify product sku</small></td>\
			</tr>\
			<tr>\
				<th><label for="bwpaypal-extra">Product Input</label></th>\
				<td><input type="text" name="extra" id="bwpaypal-extra" value="" /><br />\
				<small>specify product extra info</small></td>\
			</tr>\
			<tr>\
				<th><label for="bwpaypal-shipadd">Shipping Address Required</label></th>\
				<td><select name="shipadd" id="bwpaypal-shipadd">\
				<option value="0">prompt for an address, but do not require one</option>\
				<option value="1">do not prompt for an address</option>\
				<option value="2">prompt for an address, and require one</option>\
				</select>\
				</td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="bwpaypal-submit" class="button-primary" value="Insert PayPal Button" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#bwpaypal-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
         //'shipcost'	: '',
          //'shipcost2'	: '',								
          //'weight'	: ''

			var options = { 
				'type' : '',
				'amount'    : '',
				'productname' : '',
				'sku'       : '',
				'extra'    : '',
				'shipadd'	: ''
			 			};
			var shortcode = '[paypal';
			
			for( var index in options) {
				var value = table.find('#bwpaypal-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});

      // triggers the thickbox
      var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
      W = W - 80;
      H = H - 84;
      tb_show( 'oik PayPal', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=bwpaypal-form' );


	});




      editor.addButton('bwpaypal', {
         icon: 'bw-pp-icon',
         //   text: 'PayPal',
         tooltip: 'Insert PayPal shortcode',
         cmd: 'InsertPayPal'
      });

   });
