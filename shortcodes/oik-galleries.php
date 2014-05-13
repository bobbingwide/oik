<?php // (C) Copyright Bobbing Wide 2012
if ( defined( 'OIK_GALLERIES_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_GALLERIES_SHORTCODES_INCLUDED', true );

/*
    Copyright 2011,2012 Bobbing Wide (email : herb@bobbingwide.com )

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

function nggallery__help( $shortcode="nggallery" ) {
  return( "Display a NextGEN gallery" );
}

function nggallery__syntax( $shortcode="nggallery" ) {
  $syntax = array( "id" => bw_skv( null, "<i>id</i>", "Gallery ID. Must be specified." )
                 , "images" => bw_skv( "0", "<i>numeric</i>", "Number of images per page. 0=unlimited" )
                 , "template" => bw_skv( "", "<i>template</i>|carousel|", "Name for a gallery template" ) 
                 );
  return( $syntax );
}

/**
 * Return a callable function that can be invoked using call_user_func()
 *
 * @param object/string $class - class name or actual instance of class name
 * @param string $method - class method name 
 * @returns $function - best attempt at a callable function 
 */
function bw_callablefunction( $class, $method ) {
  if ( is_object( $class ) ) {
    $function = array( $class, $method );
  } else {
    if ( class_exists( $class ) ) {
      $object = new $class();
      $function = array( $object, $method );
      bw_trace2();
    } else {
      $function = $method;
    }
  }
  return( $function );
}

/**
 * Give an example of the NextGEN gallery and a link to more examples
 * 
 * This function will probably work even if the NextGEN is not activated
 *
*/
function nggallery__example( $shortcode="nggallery" ) {
  bw_invoke_shortcode( $shortcode, 'id=1 template="carousel"', "To display NextGEN gallery with id=1 and the carousel template" );
  alink( null, "http://nextgen-gallery.com/gallery-page/", "Visit the NextGEN Gallery page" );
}

/**
 * Create the snippet for the NextGEN [nggallery] shortcode with parameters "id=1 template=carousel" - as for the example
 *
*/
function nggallery__snippet( $shortcode="nggallery" ) {
  _sc__snippet( $shortcode, "id=1 template=carousel" );
}

