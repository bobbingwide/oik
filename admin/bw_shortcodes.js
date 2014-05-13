/* (C) Copyright Bobbing Wide 2011, 2012
*/
  var bw_shortcodes_local = { data: null, inTiny: false };
  var bw = function() { alert( "bw" ); };

  bw.bw_shortc_button_callback_QTags = function() { 
    bw_shortcodes_local.inTiny = false;
    bw_shortc_button_callback();
  }

  bw.bw_shortc_button_callback_TinyMCE = function() {
    bw_shortcodes_local.inTiny = true;
    bw_shortc_button_callback();
  }


  function bw_shortc_button_callback() {
    bw_create_thickbox();
    bw_load_shortcodes();
    // triggers the thickbox
    var width = jQuery( 'div.bwshortc-form' ).width();
    var H = jQuery( 'div.bwshortc-form' ).height()
    var W = ( 720 < width ) ? 720 : width;
    //W = W - 80;
    //H = H - 84;
    tb_show( 'oik Shortcodes', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=bwshortc-form' );

    //tb_show( 'oik Shortcodes', '#TB_inline?&inlineId=bwshortc-form' );
    //tb_show( 'oik shortcodes', '#TB_iframe=true' );
  }


  /**  
    *  Insert the content where the cursor is
    *  How do I decide whether to use the tinyMCE or QTags method?
    * 
   */
  function bw_insert_content( shortcode ) {
    if ( bw_shortcodes_local.inTiny == true ) {
       tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
    } else {
       QTags.insertContent( shortcode ); 
    }
  }
  
  function bw_add_shortcodes( data ) {       
    var scs = jQuery('#bwshortc-type' ); // scs = short code selector
    //alert( data );

    var opt = '<option value="">Select a shortcode</option>'; 
    scs.append( opt );

    jQuery.each( data, function( key, value ) {
      var opt = '<option value="' + key + '">[' + key + '] - ' +  value + '</option>'; 
      scs.append( opt );
    }); 
    bw_shortcodes_local.data = data;
  }

  function bw_load_shortcodes() {
    if (bw_shortcodes_local.data == null ) {
      var data = { action: 'oik_ajax_list_shortcodes' 
                   };
      jQuery.getJSON( ajaxurl, data, function( data ) {
         bw_add_shortcodes( data );       
      });
    }
    else {
      bw_add_shortcodes( bw_shortcodes_local.data );
    }
    jQuery("#bwshortc-type option:selected").attr('selected', '');
  }

  function bw_add_shortcode_syntax( data ) {
    jQuery('#bwshortc-status').remove();

    jQuery.each( data, function( key, value ) {
      bw_add_row( key, value );
    });
  }


  function bw_add_shortcode_help( data ) {
    jQuery('#bwshortc-help-text').html( data );
  }

  function bw_load_shortcode_syntax( sc ) { 


     var data = { action: 'oik_ajax_load_shortcode_syntax' 
                , shortcode: sc
                  };
     jQuery.getJSON( ajaxurl, data, function( data ) {
        bw_add_shortcode_syntax( data );       
     });
  }


  function bw_load_shortcode_help( sc ) { 
     var data = { action: 'oik_ajax_load_shortcode_help' 
                , shortcode: sc
                  };
     jQuery.get( ajaxurl, data, function( data ) {
        bw_add_shortcode_help( data );       
     });
  }

  function bw_get( value, default_value ) {
    if (value == undefined) { 
       return( default_value );
    } else {
       return( value );
    }
  }

  /*
  The syntax for each keyword should be an array containing:
  return( array( "default" => jQuerydefault
               , "values" => $values
               , "notes" => $notes
               )  );
  */
  function bw_add_row( key, value ) {
    var key = key;
    var desc = bw_get( value.notes, value );
    var def = bw_get( value.default, "");
    var values = bw_get( value.values, "" );
    var tr = '<tr>';
    

    tr += '<th><label for="bwshortc-link">' + key + '=</label>';
    tr += '<br /><small>' + desc; 
    tr += '</small>'; 
    tr += '</th>';
    tr += '<td><input link="text" name="' + key + '" id="bwshortc-' + key + '" value="" />';
    tr += '<br />Default: <b>' + def;
    tr += '</b> Values: ' + values;
    tr += '</td>';
    tr += '</tr>';
    //alert( tr );
    jQuery('table#bwshortc-table').append( tr );
  }



  function bw_build_shortcode() {
    var shortcode = '[';
    shortcode += jQuery('#bwshortc-type').val();

    jQuery('table#bwshortc-table input').each( function(index) {
      //alert(index + ': ' + jQuery(this).val());
      var value = jQuery(this).val();
      if ( value != "") {
        var key = jQuery(this).attr( "name" );
        shortcode += ' ' + key + '="' + value + '"';
      }

    });

    shortcode += ']';
    return( shortcode );
  }

  function bw_create_thickbox() {

     // creates a form to be displayed everytime the button is clicked
     // you should achieve this using AJAX instead of direct html code like this
    //var form = jQuery('#bwshortc_form' );
    // <small>select the shortcode</small>\

     // Discard any existing form and rebuild
     jQuery('#bwshortc-form').remove();

     var form = jQuery('<div id="bwshortc-form">\
                       <label for="bwshortc-type">Shortcode</label>\
                       <select name="type" id="bwshortc-type"></select>\
                       <table id="bwshortc-table" class="form-table">\
                       </table>\
     <p class="submit">\
        <input type="button" id="bwshortc-submit" class="button-primary" value="Insert Shortcode" name="submit" />\
        <input type="button" id="bwshortc-help" class="button-secondary" value="Information" name="help" />\
     </p>\
     <div id="bwshortc-help-text"></div>\
     </div>');

     form.appendTo('body').hide();


     // handles the click event of the submit button
     jQuery( '#bwshortc-submit' ).click(function() {
       var shortcode = bw_build_shortcode();
       bw_insert_content( shortcode ); 
       tb_remove();
     });

     jQuery( '#bwshortc-help' ).click( function() {
       var shortcode = jQuery('#bwshortc-type').val();
       //alert( "help:" + shortcode );
       bw_load_shortcode_help( shortcode );
     });
     
     var scs = jQuery('#bwshortc-type' ); // scs = short code selector
     scs.change( function() {
        var sc = jQuery(this).val();
        scs.after('<p id="bwshortc-status">Loading syntax for shortcode:' + sc +'</p>' );
        jQuery('table#bwshortc-table tr').empty();
        bw_load_shortcode_syntax( sc );
        bw_add_shortcode_help("");
     });

  }




  // executes this when the DOM is ready
	jQuery(function(){
		
	});



// This is an extension to the quicktags.js file to add a button labeled [] which is used to insert ANY active shortcode
(function($) {

  QTags.addButton( 'bwshortc_button', '[]', bw.bw_shortc_button_callback_QTags);


})(jQuery);

