<?php
if ( !defined( 'OIK_CODES_SHORTCODES_INCLUDED' ) ) {
define( 'OIK_CODES_SHORTCODES_INCLUDED', true );
/*

    Copyright 2012-2014 Bobbing Wide (email : herb@bobbingwide.com )

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
oik_require( "includes/oik-sc-help.inc" );

/**
 * Create a link to the shortcode if in admin pages
 *
 * @param string $shortcode - the shortcode
 */
function bw_code_link( $shortcode ) {
  if ( is_admin() ) {
     alink( null, admin_url("admin.php?page=oik_sc_help&amp;code=$shortcode"), $shortcode );
  } else {
    e( $shortcode );
  }  
  e( " - " );
}

/**
 * Return the shortcode's callback function
 *
 * For functions registered using bw_add_shortcode() the callback function will be bw_shortcode_event.
 * This won't be unique so we then try to find the actual function passed. @see bw_get_shortcode_function() 
 *
 * @param string $shortcode - the shortcode tag
 * @return mixed - the registered callback function
 *
 */
function bw_get_shortcode_callback( $shortcode ) {
  global $shortcode_tags; 
  $callback = bw_array_get( $shortcode_tags, $shortcode, null );
  bw_trace2( $callback, "shortcode callback" );
  return( $callback ); 
}

/**
 * Return the function to invoke for the shortcode
 * 
 * We need to cater for callbacks which are defined as object and function
 * <code>
 *    Array
        (
            [0] => AudioShortcode Object
                (
                )

            [1] => audio_shortcode
        )
 *  </code>     
 *  Given that $callback is passed in and that is_callable should have already been called
 *  then we should always expect $callable_name to be set! 
 *  But this doesn't mean that the $function should be callable.        
 *
 * @param string $shortcode - the shortcode to invoke
 * @param mixed $callback - default callback for the shortcode, if the 'all' or 'the_content' event is not defined for the shortcode.
 * @return string callable name for the shortcode function
 */
function bw_get_shortcode_function( $shortcode, $callback=null ) {
  global $bw_sc_ev;
  $events = bw_array_get( $bw_sc_ev, $shortcode, null );
  $function = bw_array_get_from( $events, 'all,the_content', $callback );
  $callable_name = null;
  if ( !is_callable( $function, false, $callable_name ) ) {
    $callable_name = bw_array_get( $function, 1, $shortcode );
    bw_trace2( $callable_name, "callable_name", false ); 
    //bw_trace2( $bw_sc_ev, "unexpected result!" );
    //bw_backtrace(); 
  } 
  return( $callable_name );  
}

/**
 * Return Yes / No to indicate if a shortcode expands in titles
 *
 * When you register a shortcode using bw_add_shortcode() you can decide whether or not it will be expanded during 'the_title' processing.
 * Shortcodes which are registered using add_shortcode() have to control their own expansion.
 * So, unless they have logic to test the current filter then they will be expanded in titles
 * due to the fact that oik adds the do_shortcode action for 'the_title'
 * We use a lower case "yes" to indicate this. 
 *
 * @param string $shortcode
 * @return string 'Yes' if it does, 'No' if it doesn't, 'yes' for unknown
 */
function bw_get_shortcode_expands_in_titles( $shortcode ) {
  $expand = bw_get_shortcode_title_expansion( $shortcode );
  if ( $expand === null ) {
    $sceit = __( "yes", "oik" );
  } elseif ( $expand === false ) {
    $sceit = __( "No", "oik" );
  } else {
    $sceit = __( "Yes", "oik" );
  }
  return( $sceit );
}

/**
 * Display the shortcode, syntax and link
 *
 * @param string $shortcode - the shortcode tag
 * @param string $callback - the registered callback for the shortcode
 */
function bw_get_shortcode_syntax_link( $shortcode, $callback ) {
  //p( "Shortcode $shortcode, callback $callback" );
  //bw_tablerow( array( $shortcode, $link ) );
  stag( "tr" );
  stag( "td" );
  bw_code_link( $shortcode );
  do_action( "bw_sc_help", $shortcode );
  //do_action( "bw_sc_example", $shortcode );
  etag( "td" );
  stag( "td" );
  do_action( "bw_sc_syntax", $shortcode );
  etag( "td" );
  
  stag( "td" );
  e( bw_get_shortcode_expands_in_titles( $shortcode ) );
  etag( "td" );
  
  stag( "td" );
  $function = bw_get_shortcode_function( $shortcode, $callback );
  $link = "http://www.oik-plugins.com/oik-shortcodes/$shortcode/$function"; 
  alink( NULL, $link, "$shortcode help" );   
  etag( "td");
  //bw_td( $shortcode );
  //bw_td( $link );
  etag( "tr" );
}

/**
 * Table header for bw_codes
 *
 * <table>
 * <tbody>
 * <tr>
 * <th>Shortcode</th>
 * <th>Help link</th>
 * <th>Syntax</th>
 * </tr>
 * 
 * @param bool $table - set to true when a table is required
 */
function bw_help_table( $table=true ) {
  if ( $table ) {
    stag( "table", "widefat" );   
    stag( "thead" ); 
    stag( "tr" );
    th( "Help" );
    th( "Syntax" );
    th( "Expands in titles?" );
    th( "more oik help" );
    etag( "tr" );
    etag( "thead" );
 
    stag( "tbody" );
  }  
}

/**
 * table footer for bw_codes
 *
 * @param bool $table - set to true when a table is required
 */
function bw_help_etable( $table=true ) { 
  if ( $table ) {
    etag( "tbody" );
    etag( "table" );
  }  
}

/**
 * Return an associative array of shortcodes and their one line descriptions (help)
 *
 * @param array $atts - attributes - currently unused
 * @return array - associative array of shortcode => description
 *
 * The array is ordered by shortcode
 * @uses _bw_lazy_sc_help() rather than
*/ 
function bw_shortcode_list( $atts=null ) {
  global $shortcode_tags; 
  
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    $schelp = _bw_lazy_sc_help( $shortcode );
    $sc_list[$shortcode] = $schelp;
  }
  ksort( $sc_list );
  return( $sc_list );
}  

/**
 * Produce a table of shortcodes
 * @param array $atts - shortcode parameters
 */
function bw_list_shortcodes( $atts = NULL ) {
  global $shortcode_tags;
  $ordered = bw_array_get( $atts, "ordered", "N" );
  $ordered = bw_validate_torf( $ordered ); 
  //bw_trace2( $shortcode_tags );
  //bw_trace2( $ordered, "ordered" );
  if ( $ordered ) {
    ksort( $shortcode_tags );
  }
  //bw_trace2( $shortcode_tags, "shortcode_tags" );
  add_action( "bw_sc_help", "bw_sc_help" );
  add_action( "bw_sc_example", "bw_sc_example" );
  add_action( "bw_sc_syntax", "bw_sc_syntax" );
  bw_help_table();
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    bw_get_shortcode_syntax_link( $shortcode, $callback );
  }
  bw_help_etable();
}

/** 
 * Display a table of active shortcodes
 *
 * @param array $atts - shortcode parameters
 * @return results of the shortcode
 * @uses bw_list_shortcodes()
 */
function bw_codes( $atts = NULL ) {
  $text = "&#91;bw_codes] is intended to show you all the active shortcodes and give you some help on how to use them. ";
  $text .= "If a shortcode is not listed then it could be that the plugin that provides the shortcode is not activated. ";
  $text .= "Click on the link to find detailed help on the shortcode and its syntax. "; 
  e( $text );  
  $shortcodes = bw_list_shortcodes( $atts );
  return( bw_ret());
} 

/**
 * Display information about a specific shortcode
 *
 * If no shortcode is specified then we check to see if this is a shortcode or shortcode example and determine the shortcode from that.
 *
 * @param array $atts - shortcode parameters
 * @return results of the shortcode
 */
function bw_code( $atts=null, $content=null, $tag=null ) {
  $shortcode = bw_array_get( $atts, "shortcode", null );
  if ( !$shortcode ) {
    $link_text = bw_array_get( $atts, 0, null );
    if ( !$link_text ) {
      $post_id = bw_global_post_id();
      $shortcode_id = get_post_meta( $post_id, "_sc_param_code", true );
      if ( $shortcode_id ) {
        $post_id = $shortcode_id;
      }  
      $shortcode = get_post_meta( $post_id, "_oik_sc_code", true ); 
      if ( $shortcode ) { 
        $atts['syntax'] = bw_array_get( $atts, "syntax", "y" ); 
        $atts['help'] = bw_array_get( $atts, "help", "n" ); 
        $atts['example'] = bw_array_get( $atts, "example", "n" ); 
      }
    }  
  }
  if ( $shortcode ) {
    $help = bw_array_get( $atts, "help", "Y" );
    $syntax = bw_array_get(  $atts,  "syntax", "Y" );
    $example = bw_array_get( $atts, "example", "Y" );
    $live = bw_array_get( $atts, "live", "N" );
    $snippet = bw_array_get( $atts, "snippet", "N" );
    
    $help = bw_validate_torf( $help );
    if ( $help ) {
      p( "Help for shortcode: [${shortcode}]", "bw_code_help" );
      //bw_trace2( $shortcode, "before do_action" );
      do_action( "bw_sc_help", $shortcode );
    }  
    $syntax = bw_validate_torf( $syntax );
    if ( $syntax ) {
      p( "Syntax", "bw_code_syntax" ); 
      do_action( "bw_sc_syntax", $shortcode );
    }  
    $example = bw_validate_torf( $example );
    if ( $example ) {
      p( "Example", "bw_code_example");
      do_action( "bw_sc_example", $shortcode );
    }

    $live = bw_validate_torf( $live ) ;
    if ( $live ) {
      p("Live example", "bw_code_live_example" );
      $live_example = bw_do_shortcode( '['.$shortcode.']' );
      e( $live_example );
    }
    
    $snippet = bw_validate_torf( $snippet );
    if ( $snippet ) {
      p( "Snippet", "bw_code_snippet" );
      do_action( "bw_sc_snippet", $shortcode );
    }
  } else {
    $link_text = bw_array_get( $atts, 0, null );
    if ( $link_text ) {
      bw_code_example_link( $atts );
    } else {
      return( bw_code( array( "shortcode" => "bw_code" ) ) );
    }
  } 
  return( bw_trace2( bw_ret(), "bw_code_return"));
}


/**
 * Create a nicely formatted link to the definition of the shortcode
 *
 * When the shortcode= parameter is not specified then we assume that this is an example
 * that we want to both show AND make a link to the help in oik-plugins.
 * The first word is expected to be the shortcode and the rest are parameters
 * e.g. [bw_code bw_code shortcode=bw_code] 
 * 
 * @param $atts -  shortcode parameters
 Array
(
    [0] => Array
        (
            [0] => bw_link
            [1] => 1234
        )

    [1] => 
    [2] => bw_code
 *
 * @see http://www.undermyhat.org/blog/2012/05/how-to-properly-escape-shortcodes-in-wordpress/
 * 
 */ 
function bw_code_example_link( $atts ) {
  $shortcode_string = bw_array_get( $atts, 0, null );
  $link_text = "&#91;";
  $link_text .= $shortcode_string; 
  $link_text .= "]";
  $shortcodes = explode( " ", $shortcode_string );
  $shortcode = $shortcodes[0];
  $callback =  bw_get_shortcode_callback( $shortcode );
  if ( $callback ) {
    $function = bw_get_shortcode_function( $shortcode, $callback );
  } else {
    $function = null;
  }
  if ( $function ) {
    $link = "http://www.oik-plugins.com/oik-shortcodes/$shortcode/$function";  
    //$link = "http://qw/wordpress/oik-shortcodes/$shortcode/$function";  
       
    alink( "bw_code $shortcode", $link, $link_text, "Link to help for shortcode: $shortcode" );   
  } else { 
    span( "bw_code $shortcode" );
    e( $link_text );
    epan();
  }   
}
   

} /* end !defined */
