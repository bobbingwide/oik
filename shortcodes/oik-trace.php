<?php
if ( defined( 'OIK_TRACE_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_TRACE_SHORTCODES_INCLUDED', true );
/*

    Copyright 2012 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/**
 * Note that the function is based on the shortcode not the implementing function
 * This enables both the shortcode and the help and examples to be implemented as lazy functions
 * **?** Actually it's an oversight! Herb 2012/03/20
*/
function bwtrace__syntax( $shortcode='bwtrace' ) {
  $syntax = array( "text" => bw_skv( " ", "text", "text for the trace button" )
                 , "option" => bw_skv( "", "view|reset", "trace control links to display" )
                 );
  return( $syntax );
}

function bwtrace__example( $shortcode='bwtrace' ) {    
  bw_invoke_shortcode( $shortcode, null, "To display the trace options and trace reset buttons." );    
  bw_invoke_shortcode( $shortcode, "option=view", "To display a link to the active trace file." );
  bw_invoke_shortcode( $shortcode, "option=reset", "To display the trace reset only" );
}                   


/**
 * Shortcode for toggling or setting trace options 
 * Provide a button for controlling trace
 *
 * @param array $atts - shortcode options
 *  option=view, reset, other
 *  text=text for the trace options button
 * @return string - the expanded shortcode 
 * 
 * This shortcode has no effect if trace is not enabled. i.e. it doesn't return anything
*/
function bw_trace_button( $atts = NULL ) {
  global $bw_trace_on;
  if ( $bw_trace_on ) {   
    $option = bw_array_get( $atts, 'option', NULL );

    switch ( $option ) {
      case 'view':
        oik_require2( 'admin/oik-bwtrace.inc', "oik-bwtrace" );
        $bw_trace_url = bw_trace_url();
        alink( NULL, $bw_trace_url, "View trace log", "View trace output in your browser. $bw_trace_url");
        break;
        
      case 'reset':
        bw_trace_reset_form();
        break; 
        
      default:     
        $url = get_site_url( NULL, 'wp-admin/options-general.php?page=bw_trace_options' );    
        $text = bw_array_get( $atts, 'text', "Trace options" );
        $img = retimage( null, oik_url( 'images/oik-trace_48.png'), $text );
        alink(  null, $url, $img, $text );         
        bw_trace_reset_form();
        break;  
        
    }
  }    
  return( bw_ret());  
}

/**
 * Create the Trace reset button for use somewhere in any page
 */
function bw_trace_reset_form() {
  oik_require( "bobbforms.inc" );
  e( '<form method="post" action="" class="bw_inline">' ); 
  e( "<input type=\"submit\" name=\"_bw_trace_reset\" value=\"Trace reset\" />" ); 
  etag( "form" );
}



