<?php // (C) Copyright Bobbing Wide 2012-2015

bw_trace2( "oik-depends.inc loaded" );

if ( !defined( "OIK_DEPENDS_INC_LOADED" ) ) {
  define( "OIK_DEPENDS_INC_LOADED", true  );

bw_backtrace();
//gobang();
oik_require_lib( "oik-depends" );
//var_dump( debug_backtrace() );
//gob();

 
} /* endif !defined */
