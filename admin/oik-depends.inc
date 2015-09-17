<?php // (C) Copyright Bobbing Wide 2012-2015

bw_trace2( "oik-depends.inc loaded", null, false, BW_TRACE_DEBUG );

if ( !defined( "OIK_DEPENDS_INC_LOADED" ) ) {
  define( "OIK_DEPENDS_INC_LOADED", true  );

bw_backtrace( BW_TRACE_DEBUG );
oik_require_lib( "oik-depends" );

} /* endif !defined */
