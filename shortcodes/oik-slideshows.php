<?php
if ( defined( 'OIK_SLIDESHOWS_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_SLIDESHOWS_SHORTCODES_INCLUDED', true );
/*

    Copyright 2011, 2012 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Safely invoke SlideShow Gallery Pro
*/ 
function bw_gp_slideshow( $atts, $hmm=NULL, $tag=NULL ) {
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  bw_trace( $tag, __FUNCTION__, __LINE__, __FILE__, "tag" ); 
  $continue = true; 
   
  if ( $continue && ( 'the_content' != current_filter() ) ) {
    $content = '&#91;' . $tag . ']';  
    $continue = FALSE;
      // $content .= ' !?#';
  }  
  
  if ( $continue && !class_exists( "Gallery" ) ) {
    $content = '&#91;' . $tag . '] <b>Slideshow Gallery Pro not activated</b>';
    $continue = FALSE;
  }
    
  if ( $continue ) {
    $Gallery = new Gallery();
    $content = $Gallery->embed( $atts );
  }
  
  bw_trace( $content,  __FUNCTION__, __LINE__, __FILE__, "content" );
  
  return $content;
}



/**
 * Information extracted from
 * portfolio-slideshow\inc\shortcode.php (Version 1.3.5 ) 
 
	extract( shortcode_atts( array(
 * 
*/ 

function portfolio_slideshow__help( $shortcode="portfolio_slideshow" ) {
  return( "Mobile friendly portfolio slideshow" );
} 

/**
 * Since the shortcode has been selected we know that it is active therefore we 
 * can assume that the $ps_options fields have been populated
 * If the fields are not set then we may get Notices:
 * 
 */
function portfolio_slideshow__syntax( $shortcode="portfolio_slideshow" ) {

  global $ps_options;

  $syntax = array( "size" => bw_skv( $ps_options['size'], "thumbnail|medium|large|full", "Image size" )
                 , 'nowrap' => bw_skv( $ps_options['nowrap'], "true|false", "Disable slideshow wrapping" )
                 , 'speed' => bw_skv( $ps_options['speed'], "<i>number</i>", "Transition speed in milliseconds" )
                 , 'trans' => bw_skv( $ps_options['trans'], "fade|scrollHorz", "Transition effect" )
                 , 'timeout' => bw_skv( $ps_options['timeout'], "<i>number</i>", "Time per slide when slideshow is playing" )
                 , 'exclude_featured' => bw_skv( $ps_options['exclude_featured'], "true|false", "Exclude the featured image" )
                 , 'autoplay' => bw_skv( $ps_options['autoplay'], "true|false", "Autoplay the slideshow" )
                 , 'pagerpos' => bw_skv( $ps_options['pagerpos'], "top|bottom|disabed", "Position of the pager" )
                 , 'navpos' => bw_skv( $ps_options['navpos'], "top|bottom|disabled", "Position of the navigation links" )
                 , 'showcaps' => bw_skv( $ps_options['showcaps'], "true|false", "Show the image caption" )
                 , 'showtitles' => bw_skv( $ps_options['showtitles'], "true|false", "Show the image title" )
                 , 'showdesc' => bw_skv( $ps_options['showdesc'], "true|false", "Show the image description" )
                 , 'click' =>	bw_skv( $ps_options['click'], "advance|openurl", "Behavior when image clicked" )
                 , 'thumbs' => bw_skv( '', "true", "Alternative to pagerpos=bottom" )
                 , 'fluid'	=>	bw_skv( $ps_options['allowfluid'], "true|false", "Support fluid layouts" )
                 , 'slideheight' => bw_skv( '', "<i>number</i>", "Force slide container height, in pixels" )
                 , 'id' => bw_skv( '', "post-id", "ID of post from which the slides should be taken" )
                 , 'exclude' => bw_skv( '', "id1,id2,etc", "IDs of attachments to exclude" )
                 , 'include' => bw_skv( '', "id1,id2,etc", "IDs of attachments to include" )
                 );
  return( $syntax );
}  

function portfolio_slideshow__example( $shortcode="portfolio_slideshow" ) {
  
  p( "To display the slideshow using thumbnail sized images, changing every 10 secs, using <i>fadeZoom</i>" );
  $example = "[$shortcode size=thumbnail speed=10000 trans=\"fadeZoom\"]";
  p( $example );
  // e( do_shortcode( $example ));
    
  p( "Note: Default values are set on the Portfolio slideshow options page" );
  p( "You can choose from any documented jQuery cycle transition listed here:" );
  alink( null, "http://jquery.malsup.com/cycle/begin.html", "jQuery Cycle Plugin - beginner demos" );
   
  
}               
                 
  

