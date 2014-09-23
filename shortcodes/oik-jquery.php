<?php // (C) Copyright Bobbing Wide 2012, 2013

/**
 * Determine the jQuery script file URL
 * 
 * @param string $script - the jquery script root
 * @param bool $debug - use debug or minified (packed) version
 * @return string - fully qualified script file URL or null
 * 
 * @link
 */
function bw_jquery_script( $script, $debug=false ) {
  if ( !$debug && defined('SCRIPT_DEBUG') && SCRIPT_DEBUG == true ) {
    $debug = true; 
  }
  add_filter( "bw_jquery_script_url", "bw_jquery_script_url", 5, 3 );
  $script_url = apply_filters( "bw_jquery_script_url", $script, $script, $debug ); 
  return( $script_url );  
}

/**
 * Default jQuery script file filter
 *
 * If no other plugin responds then we assume it's our script file
 * but if we don't find it then we have a look around.
 *
 * We currently assume that both the debug and minimised versions of the script file are going to be available.
 * ... sometimes we just copy whatever we have to the other version **?** 2013/06/09
 *
 */
function bw_jquery_script_url( $script_url, $script, $debug ) {
  $script_path = plugin_dir_path( __FILE__ ) ;
  $script_path .= "jquery/";
  $script_path .= bw_jquery_filename( $script, $debug );
  if ( !file_exists( $script_path ) ) {
    $script_path = bw_jquery_script_plugin_file( $script, $debug );  
  }
  if ( $script_path ) {
    $script_url = plugin_dir_url( $script_path ) . basename( $script_path );
  } else {
    $script_url = null;
  }
  return( $script_url );
}

/**
 * Locate a jQuery script file from the list of plugins given 
 *
 * @param string $plugin - a comma separated array of plugin script file locations
 * @param string $script - the jQuery script name e.g. cycle or cycle.all
 * @param book $debug - whether or not the debug version is required
 */
function bw_jquery_locate_script_file( $plugin, $script, $debug ) {
  $plugins = bw_as_array( $plugin );
  $found = false;
  for ( $p=0; !$found && ( $p < count( $plugins ) ) ; $p++ ) {
    $file = WP_PLUGIN_DIR;
    $file .= $plugins[$p];
    $file .= bw_jquery_filename( $script, $debug );
    if ( file_exists( $file ) ) {  
      $found = true;
    }  
  }
  if ( !$found ) { 
    $file = null;
  }
  return( $file );  
} 

/**
 * Return an array of known sources for particular jQuery scripts
 *
 * This list excludes oik - we've already looked there
 * Note: The plugins do not need to be activated in order for the file to be found
 * Note: This is a temporary solution until a jQuery library manager is developed.
 *
 * This tiny list of jQuery libraries and plugins has been built from personal experience.
 * It doesn't take into account any themes which may also deliver the jQuery code.
 * Nor any external hosting. 
 * Remember, this code is only being invoked since the jQuery script isn't already registered.
 * 
 * @param string $script - the base name of the jQuery script. cycle and cycle.all are basically the same script but with different names
 * @param string $plugins - the list of plugins to investigate. The shorter the list the better. 
 */
function _bw_jquery_known_sources( $script ) {
  $plugins = array( "cycle" => "jetpack/modules/shortcodes,tb-testimonials,picasaweb-photo-slide" 
                  , "cycle.all" => "nextgen-gallery"
                  );
  return( $plugins );
}  

/**
 * Determine whether or not the jQuery file is a .pack or .min or .dev or something else
 * 
 * @param string $script - the jQuery script e.g. cycle
 * @param bool $debug - whether or not script debugging is required
 * @return string - the script file e.g. jquery.cycle.min.js
 */
function bw_jquery_filename( $script, $debug ) {
  if ( !$debug ) { 
    $packormins = array( "cycle.all" => ".min", "countdown" => ".min" );
    $extra = bw_array_get( $packormins, $script, ".pack" );
  } else {
    $devs = array( "form" => "dev" );
    $extra = bw_array_get( $devs, $script, null );
  }
  $file = "jquery.$script$extra.js";
  return( $file );
}

/**
 * Determine a potential source for a script file and whether or not it's a .pack or .min file  
 * 
 * The $plugins array indicates where the script might exist
 * The $packormins array indicates if the minified script is called ".pack.js". or ".min.js"
 * We default to ".pack" so only need to perform str_replace if it's not that.
 *
 * @link http://jquery.malsup.com/cycle/ cycle -  Cycle Plugin (with Transition Definitions) jetpack 2.9999.8
 * @link http://jquery.malsup.com/cycle/ cycle.all - Cycle Plugin (with Transition Definitions) nextgen-gallery 2.9995  
 * 
 * 
 */
function bw_jquery_script_plugin_file( $script, $debug ) {
  $plugins = _bw_jquery_known_sources( $script );
  $plugin = bw_array_get( $plugins, $script, null );
  if ( $plugin ) {
    $script_file = bw_jquery_locate_script_file( $plugin, $script, $debug );
  } else {
    $script_file = null;
  }
  return( $script_file );
}   

/**
 * Determine the dependencies for the jQuery script
 * 
 * @param string $script - the name of the jQuery script e.g. nivo.slider-31
 * @return array - scripts upon which this script are dependent. Defaults to array( 'jquery' )
 */
function bw_jquery_dependencies( $script ) {
  static $dependencies = array( "nivo.slider" => "jquery" 
                              , "anything" => "jquery" 
                              , "accordion" => "jquery-ui"
                              , "flexslider" => "jquery"   
                              , "cycle" => "jquery"
                              );
                              
  $dependence = bw_array_get( $dependencies, $script, 'jquery' );
  $dependence = bw_as_array( $dependence );
  return( $dependence );
}

/**
 * Wrapper to wp_script_is() to find out if we need to register and enqueue the script
 * 
 * @param string - script name
 * @return bool - whether or not the script has been enqueued
 *
 * @uses wp_script_is() 
 * If the script is already "enqueued" then fine
 * If it's already "registered" then enqueue it.
 * Otherwise: return false
 */
function bw_jquery_script_is( $script ) {
  global $wp_version;
  if ( version_compare( $wp_version, '3.5', "lt" ) ) {
    $enqueued = false;
  } else {
    $enqueued = wp_script_is( $script, "enqueued" );
  }  
  if ( !$enqueued ) {
    $registered = wp_script_is( $script, "registered" );
    if ( $registered ) {
      wp_enqueue_script( $script );
      $enqueued = true;
    }
  } 
  bw_trace2( $enqueued, "enqueued?" );
  return( $enqueued );  
}

/**
 * Enqueue the jQuery script identifying dependencies
 * 
 * @param string $script - the jQuery script including version number if part of file name
 * @param bool $debug - whether or not debug is enabled
 * @return string
 */
function bw_jquery_enqueue_script( $script, $debug=false ) {
  $enqueued = bw_jquery_script_is( $script );  
  if ( !$enqueued ) {
    $script_url = bw_jquery_script( $script, $debug ); 
    // p( "$script_url:$script" );
    $dependence = bw_jquery_dependencies( $script );
    bw_trace2( $dependence, "dependence" );
    $enqueued = wp_enqueue_script( $script, $script_url, $dependence ); 
  }
  return( $enqueued );  
}

/**
 * Enqueue any CSS for the selected jQuery
 *
 * The css files are delivered from the oik/css directory
 *
 * @param string $script - the jQuery script name
 *   
 */
function bw_jquery_enqueue_style_url( $script ) {
  $styles = array( "flexslider" => "flexslider.css" 
                 , "fancybox-1.3.4" => "jquery.fancybox-1.3.4.css" 
                 , "countdown" => "jquery.countdown.css"
                 );
  $style = bw_array_get( $styles, $script, null );
  if ( $style ) {
    wp_enqueue_style( "$script-css", oik_url( "css/$style") );
  }
}

/**
 * Enqueue style files required for this script
 * 
 * In order for the jQuery code to have any visual effect we need a certain amount of styling
 * this is provided by the jQuery UI themeroller
 *
 * @link http://jqueryui.com/download/
 */
function bw_jquery_enqueue_style( $script ) { 
  //wp_enqueue_style( $script );
  //wp_enqueue_style( "jquery-ui-datepicker-css", plugin_dir_url( __FILE__). "css/jquery.ui.datepicker.css" ); 
  //wp_enqueue_style( "jquery-ui-theme-css", oik_url( "css/jquery.ui.theme.css" ) );
  wp_enqueue_style( "jquery-ui-theme-css", oik_url( "css/jquery-ui-1.9.2.custom.css" ) );
  //wp_enqueue_style( "jquery-ui-theme-css", oik_url( "css/jquery-ui-1.8.19.custom.css" ) );
  //do_action( "bw_jquery_enqueue_style_file", $script );
  bw_jquery_enqueue_style_url( $script ); 
}

/**
 * Implement the [bw_jq] shortcode
 * 
 * Notes: This is an "advanced shortcode" that will accept the selector and method as unnamed parameters.
 * If the selector and method are specified then we enqueue the script and associated style file and invoke the method on the selector
 * with any additional shortcode parameters converted into jQuery parameters
 * ELSE, if (only) the script= parameter is specified we just enqueue the script
 * ELSE, if the method is "?" then we produce a table of the registered jQuery scripts
 * ELSE we use the src= parameter or load attached scripts.
 * 
 * @param array $atts - key value pairs for 'selector', 'method', 'script', 'src' and parms
 * @param string $content - should be null - if not treat as parameters?
 * @param string $tag 
 * @return string expanded shortcode
 *
 */
function bwsc_jquery( $atts=null, $content=null, $tag=null ) {
  bw_trace2();
  bw_backtrace();
  $selector = bw_array_get_from( $atts, "selector,0", null );
  $method = bw_array_get_from( $atts, "method,1", null );
  if ( !$method ) {
    $method = str_replace( ".", "", $selector );
  }
  $script = bw_array_get( $atts, "script", $method );
  if ( $selector && $method ) { 
    $windowload = bw_array_get( $atts, "windowload", false );
    $debug = bw_array_get( $atts, "debug", false );   
    unset( $atts['selector'] );
    unset( $atts['method'] );
    unset( $atts['debug'] );
    unset( $atts['windowload'] );
    unset( $atts[0] );
    unset( $atts[1] );
    unset( $atts['script'] );
    $parms = bw_jkv( $atts );
    bw_jquery_enqueue_script( $script, $debug );
    bw_jquery_enqueue_style( $script );
    bw_jquery( $selector, $method, $parms, $windowload );
  } elseif ( $script ) {
    $debug = bw_array_get( $atts, "debug", false ); 
    bw_jquery_enqueue_script( $script, $debug );
  } elseif ( "?" == $method ) {
    bw_list_wp_scripts();
  } else {
    bw_jquery_src( $atts ); 
  }    
  return( bw_ret() );
}

/**
 * Directly add the jQuery/JavaScript file to the generated HTML
 *  
 * We can't do this since it could come out before jQuery is defined!
 * Note: This is OK for external JavaScript files but not for jQuery code.
 */
function bw_jquery_javascript( $src ) {
  $parms = kv( "src", $src );
  $parms .= kv( "type", "text/javascript");
  stag( "script", null, null, $parms ); 
  etag( "script" );
}     

/**
 * Enqueue the defined jQuery source file(s)
 *
 * If the src= parameter is set
 * and it's a numeric value then treat this as an attachment ID  else treat it as the URL
 * otherwise load the attached jQuery files and enqueue those.
 * 
 * @param mixed $atts - array that may contain src=ID or src=URL
 *  
 */
function bw_jquery_src( $atts ) {
  $src = bw_array_get( $atts, "src", null );
  // bw_trace2( $src );
  if ( $src ) {
    if ( is_numeric( $src ) ) {
      $src = wp_get_attachment_url( $src );
    }
    $inline = bw_array_get( $atts, "inline", false );
    $inline = bw_validate_torf( $inline ); 
    if ( $inline ) {
      bw_jquery_javascript( $src );
    } else {   
      wp_enqueue_script( sanitize_key( $src ), $src, array( "jquery"), null ); 
    }  
  } else {
    bw_jquery_enqueue_attached_scripts();
  }
}

/**
 * Enqueue any attached scripts 
 *
 * Using the [bw_jq] shortcode without any parameters will load any application/javascript files which have been attached to the current post
 * This is useful when someone provides you with some JavaScript to run on a page and you don't know how to do it.
 * 
 */
function bw_jquery_enqueue_attached_scripts() {
  oik_require( "includes/bw_posts.inc" );
  $atts = array( "post_type" => "attachment" 
               , "post_mime_type" => "application/javascript"
               ); 
  $posts = bw_get_posts( $atts );
  if ( $posts ) {
    foreach ( $posts as $post ) {
      $src = wp_get_attachment_url( $post->ID );
      $enqueued = wp_enqueue_script( sanitize_key( $src ), $src, array( "jquery") ); 
    }
  }
}  
 
/**
 * Return a list of jQuery scripts 
 * 
 * @links https://twitter.com/jsgarvin/status/113713471689994241 ( 2011/11/13 )
 * Definition of divulate: New Web Development Term. divular: adj; arranged in html div tags, antonym of tabular, related forms: divulate, divularize.
 * I tweeted back that I was just about to apply some "divulation" ( 2012/12/24) 
 * This is the first place I'm thinking about doing it.
 
Array
(
    [0] => WP_Scripts Object
        (
            [base_url] => 
            [content_url] => 
            [default_version] => 
            [in_footer] => Array
                (
                )

            [concat] => 
            [concat_version] => 
            [do_concat] => 
            [print_html] => 
            [print_code] => 
            [ext_handles] => 
            [ext_version] => 
            [default_dirs] => 
            [registered] => Array
                (
                )

            [queue] => Array
                (
                )

            [to_do] => Array
                (
                )

            [done] => Array
                (
                )

            [args] => Array
                (
                )

            [groups] => Array
                (
                )

            [group] => 0
        )

)


wp_scripts->registered is an array of _WP_Dependency objects

We want to match the $script with part of the [handle] OR the [src] field

 
 
 
[jquery-ui-accordion] => _WP_Dependency Object
                (
                    [handle] => jquery-ui-accordion
                    [src] => /wp-includes/js/jquery/ui/jquery.ui.accordion.min.js
                    [deps] => Array
                        (
                            [0] => jquery-ui-core
                            [1] => jquery-ui-widget
                        )

                    [ver] => 1.8.20
                    [args] => 1
                    [extra] => Array
                        (
                        )

                ) 
 * 
 */
function bw_list_wp_scripts() {
  global $wp_scripts;
  //bw_trace2( $wp_scripts, "global scripts" ); 
  stag( "table" );
  stag( "thead" );
  stag( "tr" );
  th( "handle" );
  th( "version" );
  th( "dependencies" );
  etag( "tr" );
  etag( "thead" );
  stag( "tbody" );
  foreach ( $wp_scripts->registered as $key => $script ) {
    bw_list_wp_script( $key, $script );
  }  
  etag ("tbody" );
  etag( "table" );
}

/**
 * Create a table row for a jQuery script
 *
 */  
function bw_list_wp_script( $key, $script ) {
  stag( "tr" );
  if ( $script->handle <> $key ) {
    td( $key );  
    // gobang();
  } else {
    $unmin = str_replace( ".min", "", $script->src );
    //$unmin = $script->src; 
    $link = retlink( null, $unmin, $script->handle ); 
    td( $link  );
  }  
  td( $script->ver );
  td( implode( ",", $script->deps ) );
  etag( "tr" );
} 

function bw_jq__help( $shortcode = "bw_jq" ) {
  return( "Perform a jQuery method" );
}

function bw_jq__syntax( $shortcode = "bw_jq" ) {
  $syntax = array( "script" => bw_skv( null, "<i>script-name</i>", "Handle for the jQuery script" )
                 , "selector" => bw_skv( null, "<i>selector</i>", "jQuery selector" )
                 , "method" => bw_skv( null, "<i>method</i>", "jQuery method to perform" )
                 , "debug" => bw_skv( false, "<i>bool</i>", "Use true when you want to debug the jQuery" )
                 , "windowload" => bw_skv( false, "<i>bool</i>", "Use true when the jQuery is to run when the window has loaded" )
                 , "parms" => bw_skv( null, "<i>parm=value1,parm2=value2</i>", "Variable list of parameters" )
                 , "src" => bw_skv( null, "<i>ID</i>|<i>URL</i>", "ID or full URL of JavaScript" )
                 , "inline" => bw_skv( null, "T|Y", "Set to T=True or Y=Yes when the script tag must be inline" )
                 );
  return( $syntax );
}                  

function bw_jq__example( $shortcode = "bw_jq" ) {
 p( "Example to be completed! " );
}

