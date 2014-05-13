<?php // (C) Copyright Bobbing Wide 2013, 2014

/**
 * Return a list of the jQuery cycle effects 
 * @return string CSV separated jQuery cycle effects - alphabetical order
 */
function bw_cycle_fxs() {
  $fxs = "blindX|blindY|blindZ|cover|curtainX|curtainY|fade|fadeZoom|growX|growY|none|scrollUp|scrollDown|scrollLeft|scrollRight|scrollHorz|scrollVert|shuffle|slideX|slideY|toss|turnUp|turnDown|turnLeft|turnRight|uncover|wipe|zoom|";
  return( $fxs);
}

/***
 * Validate the fx for [bw_cycle] shortcode
 *
 * @param string $fx - required effect
 * @return string - returned value - defaults to "fade" if required is incorrect
 * 
 */
function bw_cycle_validate_fx( $fx ) {
  $fxs = bw_cycle_fxs();
  $pos = stripos( $fxs, "$fx|" );
  if ( $pos === false ) {
    bw_trace2( "Invalid fx" );
    $fx = "fade";
  } else {
    $fx = substr( $fxs, $pos, strlen( $fx ) );
  } 
  // e( "Fx=$fx" ); 
  return( $fx );
} 

/**
 * Create previous and next links
 *
 * Primarily for use with scrollVert. 
 * 
 * In the Centita theme, the links were in an ordered list.
 *
 * 
 *    <ol>
 *     <li class="previous"><a href="#">Previous</a></li>
 *     <li class="next"><a href="#">Next</a></li>
 *   </ol>
 * BUT span's are OK too!
 * 
 * @param string $class - the class parameter for the cycle
 */          
function bw_cycle_prevnext_links( $class ) {
  span( "${class}_prev");
  aname( "prev", "Prev" );
  epan();
  span( "${class}_next" );
  aname( "next", "Next" );
  epan();
} 

/**
 * Implement bw_cycle shortcode that will handle all the things that we've had to do by hand until now
 
   <pre>
   Create the jQuery
     [bw_jq .cycle method=cycle fx=fade script=cycle.all fit=1 width="100%" ]
     
   Create the CSS 
     [bw_css]
       div.cycle { width: 100% !important; }
       div.cycle img { max-width: 100% !important; }
     [/bw_css]
   
   Create the cycle div for the specified class
     [div class="cycle"]
   
   Invoke the shortcode
     [bw_pages etcetera]
     
   Create the end div
   [ediv]
   </pre>
   
  @TODO Doesn't yet build the internal CSS 
  
   
 */ 
function bw_cycle( $atts=null, $content=null, $tag=null ) {
  oik_require( "shortcodes/oik-jquery.php" );
  $class = bw_array_get( $atts, "class", "cycle" );
  $fx = bw_array_get( $atts, "fx", "fade" );
  $fx = bw_cycle_validate_fx( $fx );
  $fit = bw_array_get( $atts, "fit", 1 );
  $prevnext = bw_array_get( $atts, "prevnext", false );
  $selector = ".$class";
  bw_jquery_enqueue_script( "cycle.all" );
  bw_jquery_enqueue_style( "cycle.all" );
  $parms_array = array( "fx" => $fx, "fit" => $fit, "width" => "100%");
  if ( $prevnext ) {
    $parms_array['next'] = "span.${class}_next";  
    $parms_array['prev'] = "span.${class}_prev";
    bw_cycle_prevnext_links( $class );
  }
  $parms = bw_jkv( $parms_array );
  bw_jquery( $selector, "cycle", $parms, false );
  sdiv( $class );
  $atts['post_type'] = bw_array_get( $atts, "post_type", "attachment" );
  if ( $atts['post_type'] == "attachment" ) {
    oik_require( "shortcodes/oik-attachments.php" );
    e( bw_attachments( $atts ) );
  } else { 
    oik_require( "shortcodes/oik-pages.php" );
    e( bw_pages( $atts ) );
  }  
  ediv( $class ); 
  return( bw_ret() );
}

/**
 * Implement help hook for [bw_cycle] shortcode
 */
function bw_cycle__help( $shortcode="bw_cycle" ) { 
  return( __( "Display pages using jQuery cycle" ) );
}

/**
 * Syntax hook for [bw_cycle] shortcode
 */
function bw_cycle__syntax( $shortcode="bw_cycle" ) {
  $syntax = array( "fx" => bw_skv( "fade", bw_cycle_fxs(), "Cycle transition effects" ) 
                 , "class" => bw_skv( "cycle", "<i>class</i>", "CSS class names" )
                 , "fit" => bw_skv( 1, "0", "Fit parameter. Use fit=0 with fx=scrollVert|scrollHorz" )
                 , "prevnext" => bw_skv( null, "y", "Display Prev and Next links" )
                 );
  
  oik_require( "shortcodes/oik-pages.php" );
  $pages_syntax = bw_pages__syntax( $shortcode );
  $syntax = array_merge( $syntax, $pages_syntax );
  
  return( $syntax );
} 

/** 
function bw_cycle__example( $shortcode="bw_cycle" ) {
}    
*/ 
