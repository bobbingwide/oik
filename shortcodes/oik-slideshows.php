<?php
if ( defined( 'OIK_SLIDESHOWS_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_SLIDESHOWS_SHORTCODES_INCLUDED', true );
/*

    Copyright 2011-2017 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Help for [portfolio_slideshow] shortcode
 * 
 * - Information extracted from portfolio-slideshow\inc\shortcode.php (Version 1.3.5 ) 
 * - updated for Version 1.12.0
 
	extract( shortcode_atts( array(
 * 
*/ 

function portfolio_slideshow__help( $shortcode="portfolio_slideshow" ) {
  return( __( "Mobile friendly portfolio slideshow", "oik" ) );
} 

/**
 * Since the shortcode has been selected we know that it is active therefore we 
 * can assume that the $ps_options fields have been populated
 * If the fields are not set then we may get Notices:
 * 
 */
function portfolio_slideshow__syntax( $shortcode="portfolio_slideshow" ) {

  global $ps_options;

  $syntax = array( "size" => BW_::bw_skv( $ps_options['size'], "thumbnail|medium|large|full", __( "Image size", "oik" ) )
                 , 'nowrap' => BW_::bw_skv( $ps_options['nowrap'], "true|false", __( "Disable slideshow wrapping", "oik" ) )
                 , 'speed' => BW_::bw_skv( $ps_options['speed'], "<i>" . __( "number", "oik" ) . "</i>", __( "Transition speed in milliseconds", "oik" ) )
                 , 'trans' => BW_::bw_skv( $ps_options['trans'], "fade|scrollHorz", __( "Transition effect", "oik" ) )
                 , 'timeout' => BW_::bw_skv( $ps_options['timeout'], "<i>" . __( "number", "oik" ) . "</i>", __( "Time per slide when slideshow is playing", "oik" ) )
                 , 'exclude_featured' => BW_::bw_skv( $ps_options['exclude_featured'], "true|false", __( "Exclude the featured image", "oik" ) )
                 , 'autoplay' => BW_::bw_skv( $ps_options['autoplay'], "true|false", __( "Autoplay the slideshow", "oik" ) )
                 , 'pagerpos' => BW_::bw_skv( $ps_options['pagerpos'], "top|bottom|disabled", __( "Position of the pager", "oik" ) )
                 , 'navpos' => BW_::bw_skv( $ps_options['navpos'], "top|bottom|disabled", __( "Position of the navigation links", "oik" ) )
                 , 'showcaps' => BW_::bw_skv( $ps_options['showcaps'], "true|false", __( "Show the image caption", "oik" ) )
                 , 'showtitles' => BW_::bw_skv( $ps_options['showtitles'], "true|false", __( "Show the image title", "oik" ) )
                 , 'showdesc' => BW_::bw_skv( $ps_options['showdesc'], "true|false", __( "Show the image description", "oik" ) )
                 , 'click' =>	BW_::bw_skv( $ps_options['click'], "advance|openurl", __( "Behavior when image clicked", "oik" ) )
								 , 'target' => BW_::bw_skv( $ps_options['target'], "_self|_blank|_parent", __( "New URL opens in", "oik" ) )  
								 , 'centered' => BW_::bw_skv( $ps_options['centered'], "true|false", __( "Display centered slideshow", "oik" ) )  
                 , 'thumbs' => BW_::bw_skv( '', "true", __( "Alternative to pagerpos=bottom", "oik" ) )
                 //, 'fluid'	=>	BW_::bw_skv( $ps_options['allowfluid'], "true|false", "Support fluid layouts" )
                 , 'slideheight' => BW_::bw_skv( '', "<i>" . __( "number", "oik" ) . "</i>", __( "Force slide container height, in pixels", "oik" ) )
                 , 'id' => BW_::bw_skv( '', "post-id", __( "ID of post from which the slides should be taken", "oik" ) )
                 , 'exclude' => BW_::bw_skv( '', "id1,id2,etc", __( "IDs of attachments to exclude", "oik" ) )
                 , 'include' => BW_::bw_skv( '', "id1,id2,etc", __( "IDs of attachments to include", "oik" ) )
                 );
  return( $syntax );
	
}

/**
 * Example for [portfolio_slideshow]
 */   
function portfolio_slideshow__example( $shortcode="portfolio_slideshow" ) {
  BW_::p( __( "To display the slideshow using thumbnail sized images, changing every 10 secs, using <i>fadeZoom</i>", "oik" ) );
  $example = "[$shortcode size=thumbnail speed=10000 trans=\"fadeZoom\"]";
  BW_::p( $example );
  BW_::p( __( "Note: Default values are set on the Portfolio slideshow options page.", "oik" ) );
  BW_::p( __( "You can choose from any documented jQuery cycle transition listed here:", "oik" ) );
  BW_::alink( null, "http://jquery.malsup.com/cycle/begin.html", __( "jQuery Cycle Plugin - beginner demos", "oik" ) );
}               
                 
  

