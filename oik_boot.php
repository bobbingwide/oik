<?php // (C) Copyright Bobbing Wide 2012-2015
if ( !defined( 'OIK_BOOT_INCLUDED' ) ) {
define( 'OIK_BOOT_INCLUDED', true );
/**
 * Return the path of the oik base plugin or any particular file
 *
 * Note: You can either use oik_path() to find where oik is installed OR
 * use add_action( "init", "oik_init" ); to let oik initialise itself
 * and then you don't have to worry about including the oik header files 
 * until you need them.
 *
 * New version:
 * use add_action( "oik_loaded", 'your_init_function' );
 * to know when oik has been loaded so you can use the APIs
 * 
 * Note: oik_boot may be loaded before WordPress has done its stuff, so we may need to define some constants ourselves
 */
if (!function_exists( 'oik_path' )) {
  if ( !defined('ABSPATH') )
    define( 'ABSPATH', dirname( dirname( dirname ( dirname( __FILE__ )))) . '/' );

  if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' ); // no trailing slash, full paths only - WP_CONTENT_URL is defined further down
          
  if ( !defined('WP_PLUGIN_DIR') )
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' ); // full path, no trailing slash

  function oik_path( $file = NULL, $plugin='oik') {
  
    return( WP_PLUGIN_DIR . '/'. $plugin. '/' . $file );
  }
}
 
/**
 * invoke require_once on an oik include file or other file
 *
 * @param string $include_file - the include file (or any other file) that you want to load
 * @param string $plugin - the plugin in which the file is located (default="oik")
 * @uses oik_path()
 */
 
if (!function_exists( 'oik_require' )) {
  function oik_require( $include_file = "bobbfunc.inc", $plugin="oik" ) {
    $path = oik_path( $include_file, $plugin );
    if ( !file_exists( $path ) ) { 
      echo "<!-- File does not exist:$path! -->" ;
      if ( !is_file( $path ) ) {
        echo "<!-- File is not a real file:$path! -->" ;
      }
      //print_r( debug_backtrace() );
      //gobang();
      
    }  
    require_once( $path ); 
  }  
} 

if( !function_exists( "oik_require2" )) {
/**
 * Load a file which could have been relocated from one plugin to another
 * 
 * @param string $include_file - file name within the chosen $pluging e.g. admin/oik-header.inc
 * @param string $to_plugin - the first plugin to try - this is the "to" plugin to where the file has been relocated
 * @param string $from_plugin - this is the original plugin, defaulting to "oik"
 * 
 * Note: we try to be as efficient as possible loading the "new" file 
 * Note: this code does not allow for files to be renamed during relocation
 * This code does REQUIRE the file to exist somewhere! 
 */
function oik_require2( $include_file="bobbfunc.inc", $to_plugin, $from_plugin="oik" ) {
  $new_path = oik_path( $include_file, $to_plugin );
  if ( file_exists( $new_path ) ) {
    require_once( $new_path );
  } else {
    oik_require( $include_file, $from_plugin );
  }  
}
}
  
/**
 * load up the functions required to allow use of the bw/oik API
 *
 * Notes: a plugin that is dependent upon oik and/or uses the trace facility
 * should either call add_action( "init", "oik_init" ); to let oik load the required API files
 * OR, if add_action() is not yet available, call this function, if it's available.
 * In most cases all that is required initially is bwtrace.inc
*/ 
if ( !function_exists( "oik_init" ) ) {
function oik_init( ) {
  oik_require( 'bwtrace.php' );
}
} 
 
/** 
 * Return the array[index] or a default value if not set
 * 
 * @param mixed $array - an array or object or scalar item from which to find $index
 * @param scalar $index - the array index or object property to obtain
 * @param string $default - the default value to return 
 * @return mixed - the value found at the given index
 *
 * Notes: This routine may produce a Warning message if the $index is not scalar
 * I can't change it yet since there are other bits of code that may go wrong if I attempt 
 * to deal with an invalid  $index parameter. 
 */
if ( !function_exists( 'bw_array_get' ) ) {
  function bw_array_get( $array = NULL, $index, $default=NULL ) { 
    if ( is_array( $index ) ) {
      bw_backtrace();
      //gobang();
    //  sometimes we get passed an empty array as the index to the array - what should we do in this case **?** Herb 2013/10/24
    }
    if ( isset( $array ) ) {
      if ( is_array( $array ) ) {
        if ( isset( $array[$index] ) || array_key_exists( $index, $array ) ) {
          $value = $array[$index];
        } else {
          $value = $default;
        }  
      } elseif ( is_object( $array ) ) {
        if ( property_exists( $array, $index ) ) {
          $value = $array->$index;
        } else {
          $value = $default;
        } 
      } else {
        $value = $default;
      }  
    } else {
      $value = $default;
    }  
    return( $value );
  }
}

/**
 * Simple implementation of plugin dependency logic
 *
 * @param string $plugin - the plugin file name
 * @param string $dependencies - the list of plugins upon which this plugin is dependent
 * @param string $callback - the callback function to invoke when the dependencies aren't satisfied
 *
 * Instead of calling this module during activation we invoke it in response to the 
 * after_plugin_row_$plugin_basename action.
 * 
 * This gives us a bit more control over the information we provide.
 * IF the oik plugin is not activated then oik_lazy_depends() will not be defined
 * 
*/
function oik_depends( $plugin=null, $dependencies="oik", $callback=null ) {
  //bw_trace2();
  if ( function_exists( "oik_load_plugins" )) {
    oik_load_plugins();
  }  
  if ( function_exists( "oik_lazy_depends" ) ) {  
    oik_lazy_depends( $plugin, $dependencies, $callback );
  } else {
    if ( is_callable( $callback ) ) {
      call_user_func( $callback, $plugin, $dependencies, "missing" );
    }  
  }  
}


} /* end if !defined */
