<?php // (C) Copyright Bobbing Wide 2015

/**
 * Lazy code for implementing integration with the shortcake plugin
 *
 * We defer evaluating the syntax of all the shortcodes until just before
 * shortcake makes use of the information.
 *
 * Note: We could argue that Shortcake is inefficient when there are hundreds of shortcodes
 * since it seems to want to know the syntax for every single shortcode even though it may never be used.
 * AND, because may of oik's shortcode take an inordinate number of parameters the output could get very large indeed.
 * 
 *
 */


/**
 * Implement "admin_enqueue_scripts" actions for oik's integration with shortcake
 *
 * If we're editing content and shortcake is active then we should
 * register our shortcodes with shortcake
 * 
 * @TODO There are some shortcodes we wouldn't want to register to shortcake
 * 
 * @param string $hook indicating the admin page we're on
 */
function oik_shortcake_admin_enqueue_scripts( $hook ) {
  //bw_trace2();
  if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
     if ( function_exists( "shortcode_ui_register_for_shortcode" ) ) {
       oik_register_shortcodes_to_shortcake();  
     }
  }
}

/**
 * Query if the shortcode is already registered
 * 
 * Other plugins may have already registered the shortcode so there's no need to do it again
 *
 * @param string $shortcode - the shortcode tag
 * @return registered - the registered shortcode's definition
 */
function oik_registered_to_shortcake( $shortcode ) {
  $registered = shortcode_ui_get_register_shortcode( $shortcode );
  return( $registered );
}
 

/**
 * Register our shortcodes to shortcake
 *
 * The oik base plugin provides a service which allows each shortcode
 * to specify help, shortcode syntax etc.
 * Each shortcode implements a function with __syntax as the function name suffix
 * The $syntax array is constructed using the bw_skv helper API.
 * 
 * shortcake provides a similar service, although this is not currently lazy.
 *
 * For each of the registered shortcodes, if it's not already registered with shortcake,
 * then map the syntax to shortcake and register it. 
 * 
 */
function oik_register_shortcodes_to_shortcake() {
  do_action( "oik_add_shortcodes" );
  oik_require( 'shortcodes/oik-codes.php' );
  $sc_list = bw_shortcode_list();
	add_filter( "bw_sc_shortcake_compatible", "bw_sc_shortcake_compatible" );
	$sc_list = apply_filters( "bw_sc_shortcake_compatible", $sc_list );
  bw_trace2( $sc_list, "shortcode list", false );
  foreach ( $sc_list as $shortcode => $help ) {
    $registered = oik_registered_to_shortcake( $shortcode );
    if ( !$registered ) {
      $sc_syntax = _bw_lazy_sc_syntax( $shortcode );
      oik_register_shortcode_to_shortcake( $shortcode, $help, $sc_syntax );
    }  
  }
}

/**
 * Return the 'image' to use for the shortcode
 * 
 * The image can be one of the following
 * - a dashicon - fully prefixed with "dashicons-" at the start of the string
 * - plain text in dashicons styling (see bw)
 * - a genericon
 * - an actual image file
 * - just HTML. 
 * 
 *
 * This is a Quick and Dirty prototype. 
 * In a future version there will be an API to enable each shortcode to specify its own icon... on demand
 * It should not be necessary to register the information until it's needed.
 * 
 * @TODO - break down into quicker to resolve chunks. Only call functions when necessary
 * @TODO - populate with all the dashicons, genericons or 'texticons' that are relevant
 *
 */
function oik_select_shortcake_image( $shortcode, $help ) {
  $dashicons_images = array( "artisteer" => "dashicons-art"
                           , "wp" => "dashicons-wordpress" 
                           , "bw_pages" => "dashicons-admin-page"
                           , "bw_posts" => "dashicons-admin-post"
                           , "bw_wtf" => "dashicons-sos"
                           //, "bw_dash" => "genericons-minus"
                           , "gallery" => "dashicons-format-gallery"
                           , "bw_images" => "dashicons-format-image"
                           , "bw_attachments" => "dashicons-admin-media"
                           , "bw_alt_slogan" => "dashicons-megaphone"
                           , "add_to_cart" => "dashicons-cart"
                           , "add_to_cart_url" => "dashicons-cart"
                           , "audio" => "dashicons-format-audio"
                           , "bw_address" => "dashicons-business"
                           , "best_selling_products" => "dashicons-products"
                           , "bw" => "bw"
                           , "product" => "dashicons-products"
                           , "bw_countdown" => "dashicons-clock"
                           //, "bbboing" => oik_shortcake_image( "bbboing" )
                           //, "bw_accordion" => oik_shortcake_text( "Display posts in an accordion" ) 
                           //, "bw_twitter" => oik_shortcake_genericon( "twitter" )
                           //, "bw_facebook" => oik_shortcake_genericon( "facebook" )
                           , "bw_address" => "dashicons-location"
                           , "bw_name" => "dashicons-businessman"
                           , "bw_admin" => "dashicons-admin-users"
                           //, "bw_blockquote" => oik_shortcake_genericon( "quote" )
                           , "oik" => "oik"
                           , "bw_bookmarks" => "dashicons-admin-links"
                           , "bw_copyright" => "&#169;" // "©" // "&copy;"
                           ); 
  $image = bw_array_get( $dashicons_images, $shortcode, null );
  
  if ( !$image ) {
    $genericons_images = array( "bw_twitter" => "twitter" 
                              , "bw_youtube" => "youtube"
                              , "bw_facebook" => "facebook"
                              , "bw_blockquote" => "quote"
                              , "bw_dash" => "minus"
                              );
    $image = bw_array_get( $genericons_images, $shortcode, null );
    if ( $image ) {
      $image = oik_shortcake_genericon( $image );
    }  
  }
  
  /**
   * Try for an image
   * 
   * This works for the bbboing icon
   * Commented out for now.
   * oik_shortcake_image() should return the icon associated with the shortcode.
   */
  if ( !$image ) {
  
  }
  
  /** Default to the shortcode help
   */
  if ( !$image ) {
    $image = oik_shortcake_text( $help );
  }
  
  return( $image );

}

/**
 * Display an icon image
 *
 * @TODO: Implement a solution that doesn't expect the image to be in oik's folder
 * @TODO: Do a lookup for supported images
 *  
 */
function oik_shortcake_image( $shortcode ) {
  $icon = oik_url( "images/$shortcode-icon-256x256.jpg" );
  $image = retimage( null, $icon, $shortcode ); 
  return( $image );
}

/* 
 * Simulate a "texticon"
 *
 * For very short shortcodes we just return a few letters  
 */                                      
function oik_shortcake_text( $text ) { 
	$text = str_replace( "_", " ", $text );
  $texticon = "<p>";
  $texticon .= $text;
  $texticon .= "</p>";
  return( $texticon );
}

/**
 * Display a genericon instead of a dashicon
 */ 
function oik_shortcake_genericon( $icon ) {
  if ( !wp_style_is( 'genericons', 'registered' ) ) {
    wp_register_style( 'genericons', oik_url( 'css/genericons/genericons.css' ), false );
  }
  $font = "genericons";
  $enqueued = wp_style_is( $font, "enqueued" );
  if ( !$enqueued ) {
    wp_enqueue_style( $font );
  }
  $dash = retstag( "span", "genericon genericon-$icon dashicons" );
  $dash .= retetag( "span" );
  return( $dash );
}

/**
 * Map default and values to type and options
 *
 * The source structure for $data is fairly free form.
 *
 * Created using the bw_skv helper function
 * - $data['default'] - the default value - may be null, a literal, the result of a function call or a current piece of data
 * - $data['values'] - | separated values which may be enclosed in italics
 * - $data['notes'] - free form text not used in this function
 * 
 * The target array for each attr in the attrs array is
 *
 * `
 *  'attrs'         => array(
 *               array(
 *                   'label'   => 'Alignement',
 *                   'attr'    => 'float',
 *                   'type'    => 'select',
 *                   'placeholder' => 'right' // default value - for text and textarea fields, perhaps other types
 *                   'value'   => 'right', // default value 
 *                   'options' => array( // List of options  value => label
 *                       'left'  => 'Left',
 *                       'right' => 'Right',
 *                       'none'  => 'None'
 *                   ),
 *               ),
 * `
 * These are the 'types' that shortcake currently support
 * 
 * - 'text' => array(),
 * - 'textarea' => array(   'template' => 'shortcode-ui-field-textarea',
 * - 'url' => array(    'template' => 'shortcode-ui-field-url',
 * - 'select' => array(    'template' => 'shortcode-ui-field-select',
 * - 'checkbox' => array(  'template' => 'shortcode-ui-field-checkbox',
 * - 'radio' => array(    'template' => 'shortcode-ui-field-radio',
 * - 'email' => array(   'template' => 'shortcode-ui-field-email',
 * - 'number' => array(   'template' => 'shortcode-ui-field-number',
 * - 'date' => array(    'template' => 'shortcode-ui-field-date',
 *
 * The mapping performed below was built up using trial and error. 
 * In the longer term the bw_skv arrays may become the new target. 
 *   
 * @param array $attr - the field we're building for the parameter
 * @param string $shortcode - looks like it's not needed here yet!
 * @param string $help - ditto
 * @param string $parameter - ditto
 * @param array $data - see above
 * @return array - the updated attr array    
 */
function oik_map_skv_to_attr( $attr, $shortcode, $help, $parameter, $data ) {
  $default = $data['default'];
  $default = str_replace( "<i>", "", $default );
  $default = str_replace( "</i>", "", $default );
  if ( is_numeric( $default ) ) {
    $attr['type'] = "number";
  }
  if ( $default ) {
    $attr['placeholder'] = $default;
  }  
   
  $values = $data['values'];
  $values = str_replace( "<i>", "", $values );
  $values = str_replace( "</i>", "", $values );
  $options = explode( "|", $values );
  $first_alternative = $options[0];
  $lc_alternative = strtolower( $first_alternative );
  /** 
   * If it looks like a select then don't try to map it to something else
   * This stops us from treating the 'date' value for 'orderby' incorrectly
   * 
   * Q. Should we map "text" to "textarea" ?
   * 
   */
  if ( count( $options ) > 1 ) {
    $attr['type'] = 'select';
  } else {
    $mapping = array( "asc" => "select" 
                    , "desc" => "select" 
                    , "email" => "email" 
                    , "id" => "number"
                    , "url" => "url"
                    , "n" =>  "checkbox" //   "select
                    , "y" => "checkbox" //  "select" 
                    , "date" => "date" 
                    , "numeric" => "number" 
                    , "textarea" => "textarea"
                    );
    $attr['type'] = bw_array_get( $mapping, $lc_alternative, $attr['type'] );
  }
  /** 
   * If we're creating a select then
     a. Set the default value
     b. Produce a consistent sort sequence
   */
  if ( $attr['type'] == "select" ) {
    array_unshift( $options, $default );
    sort( $options );
    $attr['options'] =  bw_assoc( $options ); 
    $attr['value'] = $default;
  }
  // bw_trace2( $attr );
  return( $attr );
}

/**
 * Register a shortcode to shortcake
 *
 *
 * We need to map our shortcode's syntax into shortcake's
 * 
 * `
 *  [numberposts] => Array
 *              (
 *                  [default] => 5
 *                  [values] => numeric
 *                  [notes] => number to return
 *              )
 * `              
 * 
 *                                   
                                       
    shortcode_ui_register_for_shortcode( "wp" 
                                       , array( 'label' => "WordPress"
                                              , 'listItemImage' => 'dashicons-wordpress'
                                              , 'attrs' => array( array( 'label' => 'Version', 'attr' => 'v', 'type' => 'text' )    
                                                                , array( 'label' => 'PHP version', 'attr' => 'p', 'type' => 'text' )
                                                                )
                                              )
                                       );
 * @param string $shortcode - the shortcode tag
 * @param string $help - one line description of the shortcode
 * @param string $syntax - array of parameters - built using bw_skv helper function
 */
function oik_register_shortcode_to_shortcake( $shortcode, $help, $syntax ) {
  $attrs = array();
  if ( is_array( $syntax ) && count( $syntax ) ) {
    foreach ( $syntax as $parameter => $data ) {
		  $parameters = explode( ",", $parameter );
			foreach ( $parameters as $parameter ) { 
				$notes = $data['notes'];
				$default = $data['default'];
				list( $parameter ) = explode( "|", $parameter );
				$attr = array( 'label' => "$parameter - $notes"
										 , 'attr' => $parameter
										 , 'type' => 'text'
										 ); 
				$attr = oik_map_skv_to_attr( $attr, $shortcode, $help, $parameter, $data );              
				$attrs[] = $attr;
			}	
    }
  }
  $parm2 = array();
  $parm2['label'] = $shortcode; // - $help";
  $parm2['listItemImage'] = oik_select_shortcake_image( $shortcode, $help );
  $parm2['attrs'] = $attrs;
	$parm2['inner_content'] = oik_shortcake_inner_content( $shortcode, $syntax );
  shortcode_ui_register_for_shortcode( $shortcode, $parm2 );
}

/** 
 * Set the "inner_content" attribute
 *
 * This is a hardcoded test of the logic that caters for shortcodes which accept content
 * 
 * - bw_geshi was the example used in issue #126
 * - bw_csv is the one that's causing most problems - issue #317 
 * - bw_blockquote is also a good example
 * 
 * I'm sure there are many more but this'll do for now.
 *
 * @TODO - populate the 'inner_content' as an array when true
 *
 * @param string $shortcode - shortcode name
 * @param array $syntax oik shortcode syntax - may contain "content" key
 * 
 *
 */
function oik_shortcake_inner_content( $shortcode, $syntax ) {
  $inner_content = false;
  switch ( $shortcode ) {
		case 'bw_api':
		case 'bw_blockquote':
	  case 'bw_geshi':
		case 'bw_csv':
		case 'caption':
		case 'wp_caption':
		  $inner_content = true;
		  break;
		
	}
	return( $inner_content );
}
		  

/**
 * Function to invoke when the oik-shortcake module is loaded
 * 
 */
function oik_shortcake_loaded() {
  //bw_trace2();
  add_action( "admin_enqueue_scripts", "oik_shortcake_admin_enqueue_scripts", 9 );
}

oik_shortcake_loaded();
