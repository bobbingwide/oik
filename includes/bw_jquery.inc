<?php // (C) Copyright Bobbing Wide 2013

/**
 * Return the JavaScript code to create the given date
 * @param string $date - in format yyyy/mm/dd/hh/mm/ss, where the separators can be  space, hyphen, period or slash
 * @return string - JavaScript to create a new date enclosed in single quotes e.g. 'new Date( yyyy,mm,dd,hh,mm,ss )'
 */
function bw_jsdate( $date ) {
  $date = str_replace( array( " ", "-", ".", ":" ), "/", $date );
  $dates = explode( "/", $date );
  $dates[1] -= 1;
  $jsdate = implode( ",", $dates );
  return( "'new Date( $jsdate )'" );
}

/**
 * Reset literal JavaScript code that is now enclosed in pairs of double and single quotes "'js code'" 
 * Also convert escaped slashes back to normal slashes 
 * @param string $parms - the JSON encoded string we want to modify
 * @return string - the 'improved' JSON string
 *
 */
function bw_allow_js( $parms ) { 
  $parms = str_replace( "\"'", "", $parms );
  $parms = str_replace( "'\"", "", $parms );
  $parms = str_replace( "\\/", "/", $parms );
  return( $parms );
} 
 
/**
 *
 */
function bw_alter_atts( $atts, $drop=null, $reCamelCase=null ) {
  if ( $drop ) {
    $atts = bw_unset_atts( $atts, $drop );
  }
  if ( $reCamelCase ) {   
    $atts = bw_recase_atts( $atts, $reCamelCase );
  }
  return( $atts );  
}

/**
 * Return an array without the specified keys
 * @param array $atts - the original array that may contain the keys
 * @param mixed $drop - a string or array of keys to drop
 * @return array - the target array
 */
function bw_unset_atts( $atts, $drop ) {
  $ratts = $atts;
  $drops = bw_as_array( $drop ); 
  foreach ( $drops as $key => $value ) {
    unset( $ratts[$value] ) ;
  }
  return( $ratts );
}

/**
 * Return an array with the specified keys reverted to CamelCase
 * @param array $atts - the original array that may contain the keys
 * @param mixed $recase - a string or array of keys to "reCamelCase"
 * @return array - the target array
 */
function bw_recase_atts( $atts, $recase ) {
  $ratts = $atts; 
  $recases = bw_as_array( $recase );
  foreach ( $recases as $key => $value ) {
    $lowervalue = strtolower( $value );
    if ( isset( $atts[$lowervalue] ) ) {
      $ratts[$value] = $atts[$lowervalue];
      unset( $ratts[$lowervalue] );
    }
  }
  return( $ratts );
}

/**
 * Simple jQuery anonymous function
 * 
 * When we want to invoke a function when an item is clicked on or hovered over then
 * we need to be able to specify either an anonymous function as the $parms
 * OR choose a jQuery routine that accepts the child selector, method and parameters as parameters
 * 
 * This solution implements the simple anonymous function method.
 * Note: This solution is not intended to implement complex chaining nor effects that require multiple anonymous functions.
 * It's effective when you want to toggle/slideToggle the display when the "selector" is hovered over or clicked on
 *  
   <pre>
      $("button").click(
      function () { 
        $("p").slideToggle("slow");
      }
      );
   </pre>
 * 
 * @param string $selector - the selector for the hover/click
 * @param string $method - the method used to trigger the subsequent processing: e.g. "click" or "hover" 
 * @param string $af_selector - the selector for the anonymous function
 * @param string $af_selector - the method for the anonymous function: e.g. "slideToggle" or "toggle" 
 */
function bw_jquery_af( $selector, $method, $af_selector, $af_method, $af_parms=null ) {
  $parms = "function() { jQuery(\"$af_selector\").$af_method( $af_parms ); }";
  bw_jquery( $selector, $method, $parms );
}





