<?php // (C) Copyright Bobbing Wide 2012-2014

/**
 * Deprecated variables
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use oik_require()
 * @see oik_require() 
 */
  $bobb_prefix = NULL;
  $bw_echo = NULL;
  
  $theme = "bobbingwide";     
  $art_theme = NULL;
  $bobb_theme = NULL;    



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use oik_require() 
 * @see oik_require()
 */
function bw_prefix_require( $file ) {
  _deprecated_function( __FUNCTION__, '0.11', 'wp_editor()' );
  global $bobb_prefix;
  $file = $bobb_prefix . $file ;
  require( $file );
}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use get_stylesheet_directory_uri().$file 
 * @see get_stylesheet_directory_uri()
 */
function bw_theme_image( $file ) {
  _deprecated_function( __FUNCTION__, '0.11', 'get_stylesheet_directory_uri()' );
  global $theme;
  $filename = bw_get_docroot_suffix();
  $filename.= "/themes/";
  $filename.= $theme;
  $filename.= "/";
  $filename.=$file;
  return( $filename) ;
} 



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_get_option()
 * @see bw_get_option()
 */
  function bw_get_company( $field ) {
    _deprecated_function( __FUNCTION__, '0.11', 'bw_get_option()' );
    global $company;
    /* WordPress code */
    global $bw_options;
    /* Need to put some code here to see if it's been loaded */
    $bw_options = get_option( "bw_options" );
    
    // bw_trace( $bw_options, __FUNCTION__,  __LINE__, __FILE__, "bw_options" );  
    if ( $bw_options === FALSE )
      $option = $company[ $field ] ; 
    else
      $option = $bw_options[ $field ] ; 
      
    // Note: A value that appears to be blank ( == '') may actually be FALSE ( == FALSE )
    // bw_trace( '!' .$option.'!', __FUNCTION__,  __LINE__, __FILE__, "option" );  
    return( $option ); 
  }
  
/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_set_option()
 * @see bw_set_option()
 */
  function bw_set_company( $field, $value=NULL ) {
    _deprecated_function( __FUNCTION__, '0.11', 'bw_set_option()' );
    global $bw_options;
    $bw_options = get_option( "bw_options" );
    $bw_options[ $field ] = $value;
    bw_trace2( $bw_options );
    update_option( "bw_options", $bw_options );
    return( $value );
  }  



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_googlemap_v3()
 * @see bw_googlemap_v3()

*/
function bw_googlemap( $club, $lat, $lng, $postcode ) {
  _deprecated_function( __FUNCTION__, '0.11', 'bw_googlemap_v3()' );
  $latlng = $lat . ',' . $lng ;
     
  //echo '<div class="sidebar-right">';
  //echo '<h2 class="bar-green-med-light">' . $club . '</a></h2>';
  //echo '<p>Use this button for ';
  //echo '<a href="http://maps.google.co.uk/maps?f=d&hl=en&daddr=' . $latlng . '" title="Google directions">Google directions</a></p>';
  //echo '</div>';
     
     
     bw_echo( '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=');
     bw_echo( bw_get_company( "google_maps_api_key" ));
     
     // ABQIAAAAEraXBMl-kX5b-Swk0AR98BQOEsTKtT06IEwsylb7Uz31uE8TkRRL_5k4o5givS__FLcKk7M8WMou8Q"');
     bw_echo( '" type="text/javascript"></script>');
     bw_echo( "<script type=\"text/javascript\">\n");

     bw_echo( "//<![CDATA[\n");
     bw_echo( "function load() {\n");
     bw_echo( 'if (GBrowserIsCompatible()) {');
     bw_echo( 'var map = new GMap2(document.getElementById("map"));');
     bw_echo( 'var point = new GLatLng( ' .  $latlng . ');');
     bw_echo( 'map.setCenter( point , 12);');
     bw_echo( 'map.addOverlay( new GMarker( point ));');
     bw_echo( 'map.openInfoWindow( point, document.createTextNode("' . $club . ' ' .  $postcode . '" ));');
     //map.setMapType( G_HYBRID_MAP); 
     bw_echo( 'var mapControl = new GMapTypeControl();');
     bw_echo( 'map.addControl( mapControl);');
     bw_echo( 'map.addControl( new GLargeMapControl());');
     bw_echo( '}');
     bw_echo( '}');
     bw_echo( '//]]>');
     bw_echo( '</script>');
     bw_echo( '<body onload="load()" onunload="GUnload()">');
     bw_echo( '<div id="map" style="width:' .bw_get_company( "width" ). 'px; height:'.bw_get_company( "height" ).'px"></div>');
     bw_echo( '</body>');
}
 



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/

function big_button( $class, $link, $text, $alt) {
   sdiv( "big_button_" .$class );
   sp();
   
   // createlink( $url, $text );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   //aalt( $alt );
   atitle( $alt );    
   ag();

   e( $text );  
   ea();
   ep(); 
   ediv();
   //ediv();
}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/
function button( $class, $link, $text, $alt) {
   
   // createlink( $url, $text );
   stag( "span",  "big_button_" .$class );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   atitle( $alt );    

   ag();     

   e( $text );  
   ea();
   etag( "span" );    
   stag( "span", "end_button_" . $class );
   //e( "&nbsp;*&nbsp;");
   etag( "span" );     
       

}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/

function sbutton( $class, $link, $text, $alt) {
   
   stag( "span",  "big_button_" .$class );
   // createlink( $url, $text );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   atitle( $alt );    

   ag();     

   e( $text );  
   ea();    
   etag( "span" );    
   stag( "span", "end_button_" . $class );
   e( "&nbsp;");
   etag( "span" );     

}


// *?** Need to create a link where I can add the prefix but the link text is nice

/*
function createlink( $file, $text ) {
 bw_echo( '<a href="' . $file . '">' . $text . '</a>' );
} 

function checkout( $url, $text) {
   createlink( $url, $text);
}

function checkoutid( $url, $text, $id) {
   bw_echo( '<a id="' .$id . '" href="' . $url . '" alt="' .$text . '" title="' .$text .'">' . $text . '</a>' );
}


function menulink( $file, $text ) {
  bw_echo( '<li>' );
  createlink( $file, $text );
  bw_echo( '</li>' );
}
*/




 

/**
 * This is a prototype function used to investigate what's necessary to make shortcode expansion "safe"
 
  An advanced shortcode processor needs to know the context in which 
   the shortcode is being expanded. There are times when we don't want to show the
   HTML since this may include information that would cause CSS to
   do some unexpected styling - so that needs to be stripped
   but there are other times when we do want this to happen
   This function is an experimental / exploratory function
   to find out what needs to be done, when, where and how.
*/   
function bw_clever( $atts, $hmm=NULL, $tag=NULL ) {
  /* The current_filter function lets us know why the shortcode is being expanded
     but we also need to know the purpose.
     
     e.g. the_title is used in a multitude of places
     when displayed on a post, page or widget we may want the text nicely formatted
     but in a page list we want plain text - with no shortcode expansion OR expanded but not styled
     How do we decide the best approach to this problem?
     
  */   
  $cf = current_filter();
  bw_trace( $cf, __FUNCTION__, __LINE__, __FILE__, "current_filter" );
 
  $admin = is_admin();
  // as you can see it's incomplete
  return( $tag );
}  

/** 
 * These are dummy functions to demonstrate my appalling understanding of php's OO implementation 
*/
function bw_nobbut() {
  return "";
}



function say( $text ) {
  isay ($text, "EN" );
}

function isay( $text, $lang=NULL ) {
  e( $text );  
} 


// Note: Any bw_debug's before the DOCTYPE and the page goes into quirks mode
// where's the earliest we can put these then? = perhaps in comments?
function bw_get_page() {
  $page = "";
  if ( !isset( $_GET['page'] ))
  {
     $page = $_SERVER['REQUEST_URI'];
     //bw_debug( "SERVER request uri: " . $page );
     if ( $page != $_SERVER['SCRIPT_NAME'] )
     {
        //bw_debug( "SCRIPT_NAME:" . $_SERVER['SCRIPT_NAME'] . " not the same as the REQUEST_URI:". $page . ", using SCRIPT_NAME" );
        $page = $_SERVER['SCRIPT_NAME'];  
     }   
  }   
  else
     $page = $_GET['page'];   
  //bw_debug( "bw_get_page: " . $page );   
  
  // Now we need to reduce this to the part of the page that we really want
  // on betterbyfar there may be an unwanted subdirectory
  // on real servers this subdirectory will not be present
  // how do we find out what the subdirectory is? 
  

  // bw_debug( "page before:" . $page ); 
  $docroot_suffix = bw_get_docroot_suffix();
  
  if ( $docroot_suffix <> '/' )
    $page = str_replace( $docroot_suffix, NULL, $page );
  else  
    $page = ltrim( $page, $docroot_suffix );   
  // bw_debug( "page after: " . $page );
   
  return $page;
}  

/**
 * let ajax know what to call on jQuery.post **?** not necessary as we can use WordPress's ajaxurl variable
 * 
 */

function bw_add_ajaxurl() { 
  // Do we need the protocol? 
  
  wp_enqueue_script( 'otherbits', plugin_dir_url( __FILE__). 'otherbits.js', array( 'jquery' ) );
  //wp_enqueue_script( 'otherbits2', plugin_dir_url( __FILE__). 'otherbits2.js', array( 'jquery' ) );
  $otherbits = array( "ajaxurl" => admin_url( 'admin-ajax.php' ));
  wp_localize_script( 'otherbits', 'otherbits', $otherbits ); 
} 

/**
 * Return all the jQuery/JavaScript to enable shortcode creation from the editor buttons 
   **?** Changed to not working 2012/03/19
 */
function bw_preload_button_options() {

  wp_enqueue_script( 'bw_shortcodes', plugin_dir_url( __FILE__). 'bw_shortcodes.js', array( 'jquery' ) );

  // $data = array( 'some_string' => __( 'Some string to translate' ) );
  oik_require( "shortcodes/oik-codes.php" );
  $data = bw_shortcode_list();
  wp_localize_script( 'bw_shortcodes', 'bw_shortcodes', $data );
}  



function oik_optional_plugins() {
  p( "oik comes with a set of companion plugins. Activate these when you find a need for them." );
  sul();
  li( "email signature - build an email signature for your email client" );
  li( "custom header image - define a custom header image for any post/page" );
  li( "oik sidebar - apply widget wrangler sidebar functionality to Artisteer themes" );
  li( "oik fields - allow custom fields to be shown within the content of your page or post" );
  li( "oik trace - problem determination trace " );
  li( "oik action trace - trace WordPress actions and filters" );
  li( "oik bbpress - strip tags from bbPress forum title tooltips" );
  li( "oik bob bing wide shortcodes - activate this plugin to enable the <code>[bw_plug]</code> shortcode" );
  li( "oik BuddyPress signup email - Send sign up verification emails via site admin for verification before accepting a user" );
  eul();
}

/**
 * @since 1.18
 * @deprecated 1.18 
 * @deprecated Use bw_geo() 
 * @see bw_geo()
 */
function bw_get_geo( $separator, $alt=null ) {
  $geo = bw_get_option( "lat", "bw_options$alt" );
  $geo.= $separator;
  $geo.= bw_get_option( "long", "bw_options$alt" );
  return( $geo );
}  

/**
 * @since 1.18
 * @deprecated 1.18
 * @deprecated use bw_get_option() e.g. $bw_options = get_option( 'bw_options' );
 */
global $bw_options;

/**
 * Echo an image tag. Wrapper to retimage()
 * @since 1.0
 * @deprecated 2.1-alpha.1029
 * @deprecated use e( retimage( $class, $jpg, $title, $width, $height, $extras ) );
 */
function image( $class, $jpg, $title=NULL, $width=NULL, $height=NULL, $extras=null ) {
  $img = retimage( $class, $jpg, $title, $width, $height, $extras ); 
  bw_echo( $img );
}

/**
 * @since 1.0
 * @deprecated 2.1-alpha.1029
 * @deprecated use alink( null, retimage() );
 */   
function bw_image_link( $link 
                      , $image
                      , $imagetext
                      , $width=302
                      , $height=227
                      ) {
  $linktext = bw_get_linktext( $link );
  $a = '<a title="';
  $a.= $linktext;
  $a.= '" href="';
  $a.= bw_expand_link( $link );
  $a.= '">';
  bw_echo( $a . "\n" );
  
  e( retimage( "", $image, $imagetext, $width, $height ));
  etag( "a" );
} 

/*
 * @deprecated 2.2-alpha.0403
 * @deprecated use _bw_get_plugins()
 */
function bw_get_plugins() {
  require_once( ABSPATH . "wp-admin/includes/plugin.php" );
  $plugins = get_plugins();
  bw_trace2( $plugins );
}



/**
 * @deprecated 2.2-alpha.0403
 * 
 * Load module information for a Drupal module
 * **?** TBD No idea how to get this information at present
 * perhaps I just load it from a bobbingwidewebdevelopment.com/rss feed
 * Drupal.org probably needs to know the node id of the module before it can show detailed stuff.
 */
function bw_get_module_info( $name ) {
  $moduleinfo->name = $name;
  $moduleinfo->short_description = $name;
  return( $moduleinfo );
}


/** 
 * @deprecated 2.2-alpha.0403
 * 
 * Added [bw_mod name="module name" link="URL" table="t,y/n/1/0"] shortcode
 */
function bw_module( $atts ) {
  $name = bw_array_get( $atts, 'name', "oik" );
  $link = bw_array_get( $atts, 'link', "http://www.bobbingwide.com" );
  $table = bw_array_get( $atts, 'table', "n" ); 
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  
  $table = bw_validate_torf( $table );
  
  bw_trace( $table, __FUNCTION__, __LINE__, __FILE__, "table" );
  
  $moduleinfo = bw_get_module_info( $name );
  
  bw_trace( $moduleinfo, __FUNCTION__, __LINE__, __FILE__, "moduleinfo" );
  
  bw_format_modlink( $name, $link, $moduleinfo );
  return( bw_ret());  
}

/**
 * @deprecated 2.2-alpha.0403
  * Create a link to the Drupal module
  * (http://drupal.org/project/
  */
function bw_format_modlink( $name, $link, $moduleinfo ) {  
  $title = "Link to the $moduleinfo->name ($name: $moduleinfo->short_description) module on drupal.org" ;
  alink( NULL, "http://drupal.org/project/".$name, $moduleinfo->name, $title ); 
  if (empty( $link )) {
    $link = "http://www.bobbingwidewebdevelopment.com/modules/$name";
  }
  e( "(" );
  alink( "bwlink", $link, "...", "Link to bobbing wide's notes on ".$name );
  e( ")" ); 
}

/** 
 * @deprecated 2.2-alpha.0403
 * @deprecated - use indivdual APIs, stag(), alink() and etag() as below.
 */
function tdlink( $class=NULL, $url, $text, $title=NULL, $id=NULL ) {
  stag( "td" );
  alink( $classm, $url, $text, $title, $id );
  etag( "td" );
}  


/**
 * @deprecated 2.3-alpha.0511
 * @deprecated - Only use gobang() to receive the following message 'Fatal error: Call to undefined function gobang()'
 * 
 * This function is NOT intended to exist. 
 * You should not call it in production code.
 * We use it during debugging when we want to trigger a Fatal error.
 * Of course you can choose to use any undefined function; I simply plumped for gobang();
 */
if ( false ) {
  function gobang() {
    $fatal = _doing_it_wrong( __FUNCTION__, "gobang() is not expected to exist", "3.9.1" );
  }
}  
