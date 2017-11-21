<?php // (C) Copyright Bobbing Wide 2012-2016
_doing_it_wrong( __FILE__, '<br />Please use oik_require_lib( "oik_plugins" );<br />', "oik v3.0.0" );
bw_backtrace();
echo "<!-- ";
echo __FILE__;
var_dump( debug_backtrace() );
echo "-->";
oik_require_lib( "oik_plugins" );
