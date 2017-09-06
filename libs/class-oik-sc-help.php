<?php // (C) Copyright BobbingWide 2017
if ( !defined( "CLASS_OIK_SC_HELP_INCLUDED" ) ) {
define( "CLASS_OIK_SC_HELP_INCLUDED", "3.2.0" );

/**
 * Shortcode help 
 * 
 * Library: class-oik-sc-help
 * Depends: 
 * Provides: oik_sc_help class
 *
 * These functions are clones of functions in oik-sc-help.inc
 * The original functions may be deprecated in the future.
 * To use the new functions prefix the original function call with oik_sc_help:: 
 */

class oik_sc_help {


	/**
	 * Return shortcode help
	 *
	 * @param string $shortcode - the shortcode e.g. bw_codes
	 * @return string - the shortcode help
	 */
	static function _bw_lazy_sc_help( $shortcode ) { 
		$funcname = bw_load_shortcode_suffix( $shortcode, "__help" ); 
		$help = $funcname( $shortcode );
		return( $help );
	}

	/**
	 * Display shortcode help
	 *
	 * @param string $shortcode - the shortcode e.g. bw_codes
	 */   
	static function bw_lazy_sc_help( $shortcode ) {
		e( self::_bw_lazy_sc_help( $shortcode )) ;
	}
	
  

} /* end class */
} else {
	//echo __FILE__;

} /* end if !defined */

