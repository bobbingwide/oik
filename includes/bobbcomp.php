<?php // (C) Copyright Bobbing Wide 2010-2018
/**
 * Company information
 *
 * namespace bw_oik; 
 */

/**
 * Determine the CMS type
 *
 * Sets the global $bw_cms_version for Drupal
 *
 * @return string global $bw_cms_type: "WordPress", "Drupal" or "unknown"  NOT TRANSLATABLE
 */
function bw_get_cms_type() {
  global $bw_cms_type, $bw_cms_version;
  if ( function_exists( 'is_blog_installed' )) {
    $bw_cms_type = 'WordPress';
    return( $bw_cms_type );       
  } elseif ( function_exists( 'drupal_bootstrap' )) {
    $bw_cms_type = 'Drupal'; 
    if (defined( 'VERSION' ) ) {
      $bw_cms_version = VERSION;
    } 
  } else {
    $bw_cms_type = "unknown";
  }
  return( $bw_cms_type );       
}

/** 
 * Return true if the CMS is WordPress
 */
function bw_is_wordpress() {
  return( bw_get_cms_type() == "WordPress" );
}
 
/**
 * Return true if the CMS is Drupal
 */ 
function bw_is_drupal() {
  return( bw_get_cms_type() == "Drupal" );
} 

if ( bw_is_wordpress() ) {
  /**
   * Get the value of an option field
   *
   * @param string $field field name within set
   * @param string $set option name
   * @return mixed option value
   */
	if ( !function_exists( "bw_get_option" ) ) {
  function bw_get_option( $field, $set="bw_options" ) {
    /* WordPress code */
    /* Need to put some code here to see if it's been loaded */
    $bw_options = get_option( $set );
    //bw_trace( $bw_options, __FUNCTION__,  __LINE__, __FILE__, "bw_options" );  
    if ( isset( $bw_options[ $field ] ) )
      $option = $bw_options[ $field ] ; 
    else
      $option = NULL;  
      
    // Note: A value that appears to be blank ( == '') may actually be FALSE ( == FALSE )
    // bw_trace( '!' .$option.'!', __FUNCTION__,  __LINE__, __FILE__, "option" );  
		//bw_backtrace();
    return( $option ); 
  }
	} 
}
else {
  /* 
   * Notice the trailing underscore - used to prevent these alternative APIs from being automatically documented
   */
  oik_require( "bobbnotwp.inc_" ); 
}

/**
 * **?** Should we allow the $atts array to be pre-mangled. Can we add useful objects? 
 */
function bw_prepare_shortcode_atts( $atts ) {
  gobang();
  $atts = apply_filters( "bw_prepare_shortcode_atts", $atts );
  return( $atts ); 
} 

/**
 * Given a valid user ID return the required field, which may be from a set such as bw_options
 * 
 * Q. Which do we use: get_the_author_meta() or get_user_meta() **?** 
 * A. get_user_meta() is simpler but it won't return the fields from the wp_user table
 * If we're obtaining a field from a set then we must use get_user_meta()
 * otherwise we'll use get_the_author_meta()
 *
 */
function bw_get_user_field( $ID, $field, $set=null ) {
  if ( $set ) { 
    $option = get_user_meta( $ID, $set, true );
    bw_trace2( $option, "option" );
    $option = bw_array_get( $option, $field, null );
  } else {
    $option = get_the_author_meta( $field, $ID );
  }
  bw_trace2( $option, "option" );
  return( $option );
}

/**
 * Return user information 
 *
 * @param string $id - a value that can be used to access the user
 * @param string $hint - get_user_by( $hint, $id ) if we can't guess
 * @return WP_user object from get_user_by()
 * 
 * Values for $field are: 
 *  id: numeric
 *  email: contains @
 *  slug: contains "-" indicating it's a user_nicename
 *  login: anything else 
 *
 * Perhaps we should MAP username to login or viceversa anyway! **?**
 * 
 * @uses is_email()
 *
 */
function bw_get_user( $id, $hint="login" ) {
  if ( is_numeric( $id ) ) {
    $field = "id";
  } elseif ( is_email( $id ) ) {
    $field = "email";
  } elseif ( strpos( $id, "-" ) !== false ) {
    $field = "slug"; // user_nicename; 
  } else {
    $field = $hint;
  }   
  $user = get_user_by( $field, $id );
  // bw_trace2( $user );
  return( $user );
}

/**
 * Return a user option field - which may come from a composite field
 */
function bw_get_user_option( $user, $field, $set=null ) {
  //oik_require( "shortcodes/oik-user.php" );
  $user = bw_get_user( $user );
  if ( $user ) {
    $ID = $user->ID; 
    $option = bw_get_user_field( $ID, $field, $set );
  } else {
    bw_trace2( "Invalid user" );
    $option = null;
  } 
  bw_trace2( $option, "option" );
  return( $option ); 
}

/**
 * Return the default user ID
 *
 * Try the current post and if that's no good the current user (if $parm is true )
 * @param bool/string $parm - whether or not to attempt to retrieve the current user ID. In most cases this will be false.
 * @return integer - the default user ID to use or null
 *
 */
function bw_default_user( $parm=false ) {
  $post = bw_global_post();
  // bw_trace2( $post );
  if ( $post ) {
    $id = $post->post_author;
  } else {
    if ( $parm ) {
      $id = bw_get_current_user_id();
    } else {
      $id = null;
    }
  }
  return( $id );
}

/**
 * Return the current user ID if there is one
 * 
 * @return ID|null - the current user ID or null
 */
function bw_get_current_user_id() {
  $user = wp_get_current_user();
  if ( $user ) {
    $id = $user->ID;
  }
  else {
    $id = null;
  }
  return( $id );
}  

/**
 * Retrieve the requested option value depending on the $atts array
 *
 * 
 * - If the $field is available in $atts then this value is used, if it has a value. 
 * - We're trying to eliminate alt=1 or alt=2 in favour of user fields but only if oik-user is active.
 * - The alt= keyword overrides the user= keyword, for backward compatibility
 * - Use user=0 or alt=0 to force the use of "oik options"
 * - Use alt=1 or alt=2 to use "more options" or "more options 2"
 *
 * Note: The author is null when there is no current post being processed.
 * 
 * oik-user | alt= | user= | author | processing
 * -------- | ---- | ----- | ------ | --------------------------------------------
 * active   | set  | n/a   | n/a    | use the alt= value ( 0 is treated as '' )
 * active   | null | set   | n/a    | use the value specified for the user options
 * active   | null | null  | null   | use oik options
 * active   | null | null  | set    | use the user options
 * inactive | set  | n/a   | n/a    | use the alt= value
 * inactive | null | n/a   | n/a    | use the oik options 
 * 
 * @param string $field - the name of the field to obtain
 * @param string $set - the name of the set from which to obtain the field 
 * @param array $atts - the set of parameters which may include user= or alt= values
 * @return string - the value of the required option or null
 */
function bw_get_option_arr( $field, $set="bw_options", $atts=null ) {
	$option = bw_array_get( $atts, $field, null ); 
	if ( null === $option  ) {
		$alt = bw_array_get( $atts, "alt", null );
		if ( is_callable( "oiku_loaded" ) ) {
			if ( $alt === null ) {
				$user = bw_array_get_dcb( $atts, "user", false, "bw_default_user" );
				if ( $user ) {
					$option = bw_get_user_option( $user, $field, $set );
				}
			} else { 
				$user = null;
			}  
		} else {
			$user = null; 
		}
		if ( !$user ) { 
			if ( !$set ) {
				$set="bw_options";
			}
			if ( $alt == "0" )  
				$alt = "";
			$option = bw_get_option( $field, "$set$alt" );
		}
	}
	return( $option );  
}

/**
 * Return the tooltip value for "me" 
 * 
 * The name displayed in the tooltips comes from "me=" parameter, oik (user) options contact or user display_name field
 */
function bw_get_me( $atts=null ) {
  $me = bw_array_get( $atts, "me", null );
  if ( null === $me ) {
    $me = bw_get_option_arr( "contact", "bw_options", $atts );
    if ( null === $me || "" === $me ) {
      $me = bw_get_option_arr( "display_name", null, $atts );
      if ( null === $me ) {
        $me = __( "me", "oik" );
      }
    }
  }
  return( $me ); 
}

/**
 * Display the value of a 'field' in a span with a class of the field name
 *
 * @param string $field - the name of the option field to display
 * @return string - the formatted field
 */
function bw_output( $field ) {
  $fieldvalue = bw_get_option( $field );
  span( $field );
  e( $fieldvalue );
  epan();
  return( bw_ret() );
}

/**
 * Implement the [clear] shortcode to create a div to clear the floats
 * 
 * class cleared is used for Artisteer themes
 * class clear is the simpler version in oik
 */
function bw_clear( $atts=null ) {
  sediv( "cleared clear" );
  return( bw_ret());
} 
 
/** 
 * Implement the [oik] shortcode
 *
 * Format's OIK - in lower case? - as an abbreviation for "OIK Information Kit" 
 *  
 * Note: bw_oik() is needed here since it's used in the oik admin pages
 * 
 * @param array $atts - shortcode parameters
 * @param string $content - not expected
 * @param string $tag - the shortcode tag 
 * @return string - the generated HTML 
 */
function bw_oik( $atts=null ) {
  $class = bw_array_get( $atts, "class", "bw_oik" );
  $bw = nullretstag( "span", $class );
  $bw .= retstag( "abbr", null, null, kv( "title", __( "OIK Information Kit", "oik" ) ) );
  $bw .= "oik"; 
  $bw .= retetag( "abbr" );
  $bw .= nullretetag( "span", $class ); 
  //bw_trace2( $bw, "bw" );
  return( $bw );
}

/** 
 * bw_oik_long - spells out the expanded backronym for OIK
 *
 * which used to be Often Included Key-information
 * but is now "OIK Information Kit"
 *   
 */
function bw_oik_long( $atts=null ) {
  $class = bw_array_get( $atts, "class", "bw_oik" );
  $bw = nullretstag( "span", $class ); 
  $bw .= __( "OIK Information Kit", "oik" ); 
  $bw .= nullretetag( "span", $class ); 
  return( $bw );
}

/**
 * Start a div tag
 * Use in conjunction with ediv to add custom divs in pages, posts or blocks
 * e.g. [div class="blah"]blah goes here[ediv]
 */
function bw_sdiv( $atts ) {
  $class = bw_array_get( $atts, 'class', null );
  $id = bw_array_get( $atts, 'id', null );
  sdiv( $class, $id );
  return( bw_ret());
}

/**
 * End a div tag 
 */ 
function bw_ediv( $atts ) {
  ediv();
  return( bw_ret());
}

/**  
 * Create an empty div for a particular set of classes, id or both
 * e.g. [sediv class="bd-100"] 
 */
function bw_sediv( $atts=null ) {
  $class = bw_array_get( $atts, 'class', null );
  $id = bw_array_get( $atts, 'id', null );
  sediv( $class, $id );
  return( bw_ret());
}

/**
 * Set a default value for an empty attribute value from the oik options or a hardcoded value
 * @param string $bw_value - value passed... if not set then
 * @param string $bw_field - get the oik option value - this is the field name of the oik option - e.g. 'company'
 * @param string $bw_default - the (hardcoded) default value if the oik option is not set
 * @param string $set - options set from which the field should be returned 
 *
 * e.g. 
 * $width = bw_default_empty_att( $width, "width", "100%" );
 * 
*/
function bw_default_empty_att( $bw_value=NULL, $bw_field=NULL, $bw_default=NULL, $set="bw_options" ) {
  $val = $bw_value;
  if ( empty( $val )) {
    $val = bw_get_option( $bw_field, $set );
    if ( empty( $val ))
      $val = $bw_default;
  } 
  return( $val );
}

/** 
 * Return the array[index] or build the result by calling $callback, passing the $default as the arg.
 *
 * @param array $array array from which to obtain the value
 * @param string $index - index of value to obtain]
 * @param mixed $default - parameter to the $callback function 
 * @param string $callback - function name to invoke - defaults to invoking __()
 *
 * Notes: dcb = deferred callback
 * Use this function when applying the default might take some time but would be unnecessary if the $array[$index] is already set.
 *
 * You can also use this function when the default value is a string that you want to be translated.
 *
 * 2012/10/23 - When the parameter was passed as a null value e.g. "" then it was being treated as NULL
 * hence the default processing took effect. 
 * In this new verision we replace the NULLs in the code body with $default
 * So bw_array_get() can return a given NULL value which will then override the default.
 * In this case, if the parameter that is passed turns out to be the default value then this will also be translated.
 * Note: It could could still match a default null value
 * Also: We don't expect a null value for the default callback function __()
 * 2012/12/04 - we have to allow for the value being set as 0 which differs from a default value of NULL
 * so the comparison needs to be identical ( === ) rather than equal ( == )
 * 
 * 2014/02/27 - In cases where value found may be the same as the default and the dcb function could mess this up
 * then it's advisable to NOT use this function.  
 */
if ( !function_exists( "bw_array_get_dcb" ) ) {
function bw_array_get_dcb( $array, $index, $default = NULL, $callback='__', $text_domain="oik" ) {
  $value = bw_array_get( $array, $index, $default );
  if ( $value === $default ) {
    if ( is_callable( $callback ) ) {
      $value = call_user_func( $callback, $default, $text_domain ); 
    } else {
      bw_backtrace();
    }
  }  
  return( $value );  
}
}

/**
 * Set a default value for an empty array[ index]
 * @param bw_array - array containing the value
 * @param bw_index - index to check... if not set then 
 * @param bw_field - get the oik option value - this is the field name of the oik option - e.g. 'company'
 * @param bw_default - the (hardcoded) default value if the oik option is not set
 *
 * e.g. 
 * $width = bw_default_empty_arr( $atts, "width", "width", "100%" );
 * 
*/
function bw_default_empty_arr( $bw_array=NULL, $bw_index=NULL, $bw_field=NULL, $bw_default=NULL ) {
  $val = bw_array_get( $bw_array, $bw_index, NULL );
  if ( empty( $val )) {
    bw_trace( $bw_field, __FUNCTION__, __LINE__, __FILE__, "bw_field" );
    $val = bw_get_option( $bw_field );
    bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value" );
    if ( empty( $val ))
      $val = $bw_default;
  } 
  bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value" );
  return( $val );
}


/**
 * return a nice SEO title
 * taking into account which plugins are being used
 */
if (!function_exists( 'bw_wp_title' )) {
  function bw_wp_title() {
    if ( class_exists( 'WPSEO_Frontend' )) {
      $title = wp_title('', false );
    }
    else {
      $title = wp_title( '|', false, 'right' ); 
      $title .= get_bloginfo( 'name' );
    }
    return $title;
  }
}

/**
 * Functions moved to other locations
 * Moved bw_blockquote to shortcodes/oik-blockquote.php
 * Moved bw_cite to shortcodes/oik-cite.php
 */


/**
 * Formats a range of years
 *
 * $yearfrom | $yearto | result
 * --------  | ------- | ------
 * x         | x       | x
 * x         | y       | x,y
 * x         | z       | x-z
 *
 * where: z >= (y + 1) = (x + 1)
 * 
 * - if $atts['sep'] is set we use that
 * - else we use the translation of the chosen separator
 * 
 * @param string $yearfrom the starting year
 * @param string $yearto the ending year
 * @param array $atts which may contain a value for 'sep'
 * 
 */
function bw_year_range( $yearfrom, $yearto, $atts=NULL ) {
  if ( !$yearfrom ) {
    $yearfrom = $yearto;
  }  
  $diff = $yearto - $yearfrom;
  switch ( $diff ) {
    case 0:
      $years = $yearfrom;
      break;
    case 1:
      $sep = bw_array_get( $atts, "sep", null);
			if ( null === $sep ) {
				/* translators: "Separator between two adjacent years e.g. 2017,2018 " */
				$sep = __( ',', "oik" );
			}
      $years = "$yearfrom$sep$yearto";
      break;
    default: /* more than one OR negative - which is a bit of a boo boo */
      $sep = bw_array_get( $atts, "sep", null );
			if ( null === $sep ) {
				/* translators: "Separator for a year range e.g. 2011-2017 " */
				$sep = __( '-', "oik" );
			}
      $years = "$yearfrom$sep$yearto";
  }
  return $years ;    
} 

/**
 * Determines the date of the blog from the date of the earliest registered user
 * 
  `
    [0] => stdClass Object
        (
            [ID] => 1
            [user_login] => admin
            [user_pass] => $P$BijsY7/BdZ9AzR8YdJwYVVt68FBovk0
            [user_nicename] => admin
            [user_email] => info@bobbingwide.com
            [user_url] => 
            [user_registered] => 2010-12-23 12:22:39
            [user_activation_key] => qLc3INyEWwBOsfFDnZeV
            [user_status] => 0
            [display_name] => admin
        )

	`
 *
 * @return string year from 
 */
function bw_get_yearfrom() {
  $users = bw_get_users();
  $yearfrom = bw_format_date( $users[0]->user_registered, 'Y' );
  return $yearfrom;
}


/** 
 * Simple wrapper to get_users
 *
 * @param array $atts - array of key value pairs 
 * Default parameters to get_users() are:
 * <pre>
 
				'blog_id' => $GLOBALS['blog_id'],
				'role' => '',
				'meta_key' => '',
				'meta_value' => '',
				'meta_compare' => '',
				'include' => array(),
				'exclude' => array(),
				'search' => '',
				'orderby' => 'login',
				'order' => 'ASC',
				'offset' => '',
				'number' => '',
				'count_total' => true,
				'fields' => 'all',
				'who' => ''
  </pre>                                
 * @return array - array of user objects  
 *
 */
function bw_get_users( $atts = NULL ) {
  $atts['orderby'] = bw_array_get( $atts, "orderby", "registered" );
  $atts['number'] = bw_array_get( $atts, "number", 1 );
  $users = get_users( $atts );
  return( $users );
}

/** 
 * Build a simple ID, title array from an array of $user objects
 * @param array $user - array of user objects
 * @return array - associative array of user ID to user_title
 */
if ( !function_exists( "bw_user_array" ) ) { 
function bw_user_array( $users ) {
  $options = array();
  foreach ($users as $user ) {
    $options[$user->ID] = $user->display_name; 
  }
  //bw_trace2( $options );
  return( $options );
}

/**
 * Return an associative array of all users
 * @return array - associative array of user ID to user_title
 */
function bw_user_list() {
  $users = bw_get_users( array( "number" => "" )) ;
  $userlist = bw_user_array( $users );
  return( $userlist );
}
}

/** 
 * Displays the copyright statement for the company
 * 
 * - showing start and end years
 * - e.g. (C) Copyright [bw_company] [bw_from]&sep[year]. &suffix
 * - where [bw_from] is the first year of the site
 * - &sep is the separator ( defaults to ',' for one year apart and '-' for a range ) 
 * - [year] represents the current year
 * -
 * - &suffix defaults to "All rights reserved."
 *
 * @param array $atts
 * @return string Copyright statement
 */ 
function bw_copyright( $atts = NULL ) {
	$copyright = bw_array_get( $atts, "prefix", null );
	if ( null === $copyright ) {
		$copyright = __( "&copy; Copyright", "oik" );
	}
	$company = bw_array_get( $atts, "company", null );
	if ( null === $company ) { 
		$company = bw_get_option( "company", "bw_options" );
	}
	$expanded_company = bw_do_shortcode( $company );
	$suffix = bw_array_get( $atts, "suffix", null );
	if ( null === $suffix ) {
		$suffix = __( "All rights reserved.", "oik" );
	}
	$yearto = bw_format_date( null, 'Y');
	$yearfrom = bw_array_get_dcb( $atts, "from", 'yearfrom', "bw_get_option", "bw_options" );
	$years = bw_year_range( $yearfrom, $yearto, $atts );
	span( "bw_copyright" );
	/* translators: "Copyright-prefix company-name year-range. " */
	e( sprintf( __( '%1$s %2$s %3$s.&nbsp;', "oik" ), $copyright, $expanded_company, $years ) );
	span( "suffix" );
	e( $suffix );
	epan();
	epan();
	return( bw_ret());
}

function bw_stag( $atts = NULL ) {
  $tag = bw_array_get( $atts, "name", NULL );
  $class = bw_array_get( $atts, "class", NULL );
  $id = bw_array_get( $atts, "id", NULL );
  stag( $tag, $class, $id );
  return( bw_ret());
}

function bw_etag( $atts = NULL ) {
  $tag = bw_array_get( $atts, "name", NULL );
  etag( $tag );
  return( bw_ret());
}

/**
 * Return the value from a list of possible parameters
 *
 * @param array $atts - an array of key value pairs
 * @param mixed $from - a list e.g. ( "api,func" ) or array of key names  
 * @param string $default - the default value if not set 
 * @return string - the first value found or the default
 */
if ( !function_exists( 'bw_array_get_from')) {
	function bw_array_get_from( $atts, $from, $default ) {
		$from  =bw_as_array( $from );
		$fc    =count( $from );
		$f     =0;
		$result=null;
		while ( ( $f < $fc ) && $result === null ) {
			$result=bw_array_get( $atts, $from[ $f ], null );
			$f ++;
		}
		if ( ! $result ) {
			$result=$default;
		}

		return ( $result );
	}
}

// Moved bw_as_array() to libs/bobbfunc.php

/**
 * Return all the unkeyed items as an unkeyed array
 * 
 * @param mixed $array - array from which to extract the unkeyed values
 * @param bool $split - whether or not to break down the values further
 * @return mixed array of results
 *
 * e.g. 
   $atts = array( "san ta"
                , "claus"
                , "zip" => "a"
                , "diddy" => "do"
                , "dah" => "day"
                , "the"
                , "mo,vie"
                );
   $unkeyed = array( "san", "ta", "claus", "the", "mo", "vie" );
                
 */
function bw_array_get_unkeyed( $array=null, $split=true ) {
  $unkeyed = array();
  if ( is_array( $array) && count( $array ) ) {
    foreach ( $array as $key => $value ) {
      if ( is_numeric( $key ) ) {
        if ( $split ) {
          $value = str_replace( "," , " ", $value );  
          $values = explode( " ", $value );
          foreach ( $values as $valu ) {
            $unkeyed[] = $valu;
          }  
        } else {
          $unkeyed[] = $value ;
        }  
      }
    }
  }
  return( $unkeyed );
}





if ( bw_is_wordpress() ) {
  /**
    * In WordPress Artisteer provides code to deal with buttons
    * but in Drupal it needs more wrapping.
    */
  function art_button( $linkurl, $text, $title=NULL, $class=NULL  ) {
    BW_::alink( "button " . $class , $linkurl, $text, $title ) ;
  }
}
else {
  function art_button( $linkurl, $text, $title=NULL, $class=NULL  ) {
    span("art-button-wrapper" );
    sepan("art-button-l l");
    sepan("art-button-r r");
    BW_::alink( "button art-button " . $class , $linkurl, $text, $title ) ;
    epan();
  }
}
