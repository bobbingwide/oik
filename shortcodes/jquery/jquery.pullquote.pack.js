/**
 *
 * jQuery pullquote
 *
 * (C) Copyright Bobbing Wide 2013
 * 
 * Free to use under the GPLv2 license.
 * http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Based on:
 * @link  http://brettterpstra.com/share/pully/pully.jquery.js
 * @link https://www.inkling.com/read/javascript-jquery-david-sawyer-mcfarland-2nd/chapter-4/automatic-pull-quotes
 * @link http://css-tricks.com/better-pull-quotes/
 *
 * Example usage in oik shortcodes.
 * 
 * [bw_css] .pullquote { float: right; }
 * [/bw_css]
 * 
 * [bw_jq span.pq pullquote]
 * some interesting stuff
 * <span class=pq>This will appear in a pull quote</pq>
 * some more interesting stuff
 * [bw_jq span.pq pullquote class="pullquote-left"]
 *  
 */
(function( $ ){
  $.fn.pullquote = function(options) {
    var settings = {
      'class'  : 'pullquote', // new class to apply to the inserted span
      'prependto' : 'p', // or selector, e.g. 'p' or '.section'
      'inline' : false  // set to true when you want the pull quote to be inline, rather than prepended to the parent tag.
    };
    return this.each(function(){
      if (options) {
        $.extend(settings,options);
      }
      if ( settings.inline ) {
         var $prependto = $(this );
      } else {
        var $prependto = $(this).closest( settings.prependto );
        // $prependto.css('position', 'relative' );
      }
      $(this).clone().addClass( settings.class).prependTo( $prependto );
    });
  };
})( jQuery );


/** Chris Coyier's code puts the pull quote at the beginning of the paragraph
  Brett's allows you to select where to put it   e.g. prependtoparent=.section - but that's a bit trickier
  David Sawyer McFarland's put it inline - 
  
*/

/**
  	$(document).ready(function() { 
    	  $('span.pull-right').each(function(index) { 
    		var $parentParagraph = $(this).parent('p'); 
    		$parentParagraph.css('position', 'relative'); 
    		$(this).clone() 
    		  .addClass('pulled-right') 
    		  .prependTo($parentParagraph); 
    	  }); 
    	$('span.pull-left').each(function(index) { 
    		var $parentParagraph = $(this).parent('p'); 
    		$parentParagraph.css('position', 'relative'); 
    		$(this).clone() 
    		  .addClass('pulled-left') 
    		  .prependTo($parentParagraph); 
    	  });
    	}); 
      
*/      
