<?php // (C) Copyright Bobbing Wide 2009-2016
/**
 * Pre-deprecation notice 
 * 
 * All of the functions from bobbforms.inc have been moved to the shared library /libs/bobbforms.lib
 * Some time in the future inclusion of this file will display a deprecated notice
 * in the mean time, we'll use oik_require_lib( "bobbforms" ) to load the correct file.
 * 
 * There should be no plugins and themes which attempt to include this file when oik is not active
 * so this call is expected to work.
 */
if ( !defined( "BOBBFORMS_INCLUDED" ) ) {
	oik_require_lib( "bobbforms" );
} /* end !defined */
