/* (C) Copyright Bobbing Wide 2012
*/

// This file is an extension to the quicktags.js file
// Its purpose is to add a button labeled [] which is used to insert ANY shortcode


(function($) {

  QTags.addButton( 'bwshortc_button', '[oik]', my_callback );
  var bw_shortcodes_local = { data: null };
  
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
      $.getJSON( ajaxurl, data, function( data ) {
         bw_add_shortcodes( data );       
      });
    }
    else {
      bw_add_shortcodes( bw_shortcodes_local.data );
    }
    $("#bwshortc-type option:selected").attr('selected', '');
  }

  function bw_add_shortcode_syntax( data ) {
    $('#bwshortc-status').remove();

    jQuery.each( data, function( key, value ) {
      bw_add_row( key, value );
    });
  }


  function bw_add_shortcode_help( data ) {
    $('#bwshortc-help-text').html( data );
  }

  function bw_load_shortcode_syntax( sc ) { 


     var data = { action: 'oik_ajax_load_shortcode_syntax' 
                , shortcode: sc
                  };
     $.getJSON( ajaxurl, data, function( data ) {
        bw_add_shortcode_syntax( data );       
     });
  }


  function bw_load_shortcode_help( sc ) { 
     var data = { action: 'oik_ajax_load_shortcode_help' 
                , shortcode: sc
                  };
     $.get( ajaxurl, data, function( data ) {
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
  return( array( "default" => $default
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
    $('table#bwshortc-table').append( tr );
  }


  function my_callback() { 

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

  function bw_create_thickbox() {

     // creates a form to be displayed everytime the button is clicked
     // you should achieve this using AJAX instead of direct html code like this
    //var form = $('#bwshortc_form' );
    // <small>select the shortcode</small>\

     // Discard any existing form and rebuild
     $('#bwshortc-form').remove();

     var form = jQuery('<div id="bwshortc-form">\
                       <label for="bwshortc-type">Shortcode</label>\
                       <select name="type" id="bwshortc-type"></select>\
                       <table id="bwshortc-table" class="form-table">\
                       </table>\
     <p class="submit">\
        <input type="button" id="bwshortc-submit" class="button-primary" value="Insert Shortcode" name="submit" />\
        <input type="button" id="bwshortc-help" class="button-secondary" value="Help" name="help" />\
     </p>\
     <div id="bwshortc-help-text"></div>\
     </div>');

     form.appendTo('body').hide();


     // handles the click event of the submit button
     form.find('#bwshortc-submit').click(function(){
        // defines the options and their default values
        // again, this is not the most elegant way to do this
        // but well, this gets the job done nonetheless
        //'shipcost'	: '',
         //'shipcost2'	: '',								
         //'weight'	: ''

               var shortcode = '[';
        shortcode += $('#bwshortc-type').val();

         $('table#bwshortc-table input').each( function(index) {
           //alert(index + ': ' + $(this).val());
           var value = $(this).val();
           if ( value != "") {
              var key = $(this).attr( "name" );
              shortcode += ' ' + key + '="' + value + '"';
           }
                   
        });

        shortcode += ']';

        // inserts the shortcode into the active editor
        //tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        QTags.insertContent( shortcode ); 
           


        // closes Thickbox
        tb_remove();
     });

     form.find( '#bwshortc-help' ).click( function() {
       var shortcode = $('#bwshortc-type').val();
       alert( "help:" + shortcode );
       bw_load_shortcode_help( shortcode );
     });

     
     var scs = jQuery('#bwshortc-type' ); // scs = short code selector
     scs.change( function() {
        var sc = $(this).val();
        scs.after('<p id="bwshortc-status">Loading syntax for shortcode:' + sc +'</p>' );
        $('table#bwshortc-table tr').empty();
        bw_load_shortcode_syntax( sc );
        bw_add_shortcode_help("");
     });

  }

  // executes this when the DOM is ready
	jQuery(function(){
		
	});

})(jQuery);

