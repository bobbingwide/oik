<?php // (C) Copyright Bobbing Wide 2011-2017

/**
 * Return a tel: or sms: link
 *
 * @param string $link - may be null|n|y|tel|sms|other
 * @param string $number - the telephone number - which may include SMS text; ?body="blah"
 * @return string link value or null
 */
function _bw_tel_link( $link, $number ) {
  if ( $link ) {
    $link = strtolower( $link );
    switch ( $link ) {
      case "n":
        $link = null;
        break;
        
      case "y":
      case "t":
        $link = "tel:";
        break;
        
      case "s":
        $link = "sms:";
        break;
        
      default:
        // Pass the given value through         
    }
    if ( $link ) {
      $link .= $number;
    }
  }
  return( $link );
}

/**
 * Create an enclosing link start or end tag
 *
 * If a link is being used then the "tag" defaults "span".
 *
 * @param array $atts - name value pairs
 * @param string $number - the telephone number to append to the link
 * @param book $start - true for start tag, false for end tag
 *
 */
function _bw_telephone_link( &$atts, $number, $start=true ) {
  static $link = null;
  if ( $start ) {
    $link = bw_array_get( $atts, "link", null );
    $link = _bw_tel_link( $link, $number ); 
    if ( $link ) { 
      $class = bw_array_get( $atts, "class", null );
      stag( "a", $class, null, kv( "href", $link ) );
      $atts['tag'] = bw_array_get( $atts, "tag", "span" ); 
    }
  } else {
    if ( $link ) {
      etag( "a" );
    }
  }     
} 
 

/**
 * Return the telephone number in desired HTML markup, if set or passed as number=
 *
 * @param array atts
 *   prefix = type of number: free form e.g. Tel, Home, Work, Mob, Cell
 *   sep = separator default ': '
 *   number = telephone number override
 *   tag = 'div' or 'span' or other HTML wrapping tag - with start and end
 *   index = field to obtain ( telephone, fax, mobile, emergency )
 *   alt = 1 if alternative number are required
 *   user=id|email|login|nicename
 *   link=null|n|y|tel|sms|other
 *
 * The telephone number is formatted using microformats
 * See ISBN: 978-1-59059-814-6 p. 140
 * 
 * Example:
 * [bw_telephone]
 * [bw_telephone number="023 92 410090" prefix="Portsmouth"]
 * [bw_telephone user=3]
 *
 */
function _bw_telephone( $atts=null ) {
  $prefix = bw_array_get_dcb( $atts, "prefix", "Tel" );
  $sep = bw_array_get( $atts, "sep", ": " );
  $number = bw_array_get( $atts, "number", null );
  $index = bw_array_get( $atts, "index", "telephone" );
  $class = bw_array_get( $atts, "class", null );
  $link = bw_array_get( $atts, "link", null );
  if ( !$number ) {
    $number = bw_get_option_arr( $index, "bw_options", $atts );
  } 
  if ( $number <> "" ) {
    _bw_telephone_link( $atts, $number, true );
    $tag = bw_array_get( $atts, "tag", "div" );
    stag( $tag,  "tel $class" );
    span( "type");
    e( $prefix );
    epan();
    span( "sep" );
    e( $sep );
    epan();
    span( "value" );
    e( $number );
    epan();
    etag( $tag );
    _bw_telephone_link( $atts, $number, false );
  }
  return( bw_ret());
}

/**
 * Implement [bw_telephone] shortcode to display the telephone number, if set
 */
function bw_telephone( $atts = null ) { 
  $atts['index'] = bw_array_get( $atts, "index", "telephone" );
  return( _bw_telephone( $atts ) );
}  

/**
 * Implement [bw_fax] shortcode to display the fax number, if set
 */
function bw_fax( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "fax" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Fax" );
  return( _bw_telephone( $atts ) );
}

/**
 * Implement [bw_mobile] shortcode to display the mobile (cell) number, if set  
 */
function bw_mobile( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "mobile" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Mobile" );
  return( _bw_telephone( $atts ) );
}

/**
 * Implement [bw_emergency] shortcode to display the emergency number, if set
 */
function bw_emergency( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "emergency" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Emergency" );
  $atts['class'] = bw_array_get( $atts, "class", "bw_emergency" );
  return( _bw_telephone( $atts ) );
}

/**
 * Implement [bw_tel] shortcode to display an inline telephone number, using span
 *
 * Note: When using the link= parameter the versions of the shortcodes which use tag=span create better HTML than those which uses tag=div.
 * This is because WordPress wpautop() logic can add unwanted (and unmatching) paragraph tags.
 *
 */ 
function bw_tel( $atts=null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "span" );
  return _bw_telephone( $atts );
}

/**
 * Inline [bw_mob] shortcode to display an inline mobile number, using span
 */ 
function bw_mob( $atts=null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "span" );
  $atts['index'] = bw_array_get( $atts, "index", "mobile" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Mobile" );
  return _bw_telephone( $atts );
}

/**
 * Implement [bw_skype] shortcode to display the Skype contact information
 * 
 * Skype Online Material: the Skype buttons and widgets available for download on the 
 * Skype Website at http://www.skype.com/share/buttons/ 
 * as such may be changed from time to time by Skype in its sole discretion.
 *  
 * Skype Buttons: http://www.skype.com/en/features/skype-buttons/create-skype-buttons/ 
*/
function bw_skype( $atts=null, $content=null, $tag=null ) {
  $atts['index'] = bw_array_get( $atts, "index", "skype" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Skype name" );
  $atts['class'] = bw_array_get( $atts, "class", "bw_skype" );
  $atts['number'] = bw_array_get( $atts, "number", null );
  if ( !$atts['number'] ) {
    $atts['number'] = bw_get_option_arr( $atts['index'], null, $atts );
  }
  return( _bw_telephone( $atts ) );
}


/**
 * Common syntax for telephone related shortcodes
 */
function _sc_telephone() {
  $syntax = array( "sep" => BW_::bw_skv( ": ", "<i>" . __( "string", "oik" ) . "</i>", __( "Separator string", "oik" ) )
                 , "alt" => BW_::bw_skv( null, "1", __( "Use alternative value", "oik" ) )
                 , "number" => BW_::bw_skv( null, "<i>" . __( "number", "oik" ) . "</i>", __( "Number override", "oik" ) )  
                 , "tag" => BW_::bw_skv( "div", "<i>" . __ ( "tag", "oik" ) . "</i>", __( "enclosing HTML tag", "oik" ) )
                 , "class" => BW_::bw_skv( "", "<i>" . __( "class names", "oik" ) . "</i>", __( "CSS class names", "oik" ) )
                 , "link" => BW_::bw_skv( null, "n|y|t|s|<i>" . __( "other", "oik" ) . "</i>:", __( "tel: or sms: link", "oik" ) )
                 );
  return( $syntax );
}

/** 
 * Syntax for [bw_telephone] shortcode
 */
function bw_telephone__syntax( $shortcode="bw_telephone" ) {
  $syntax = _sc_telephone();
  $syntax_telephone = array( "prefix" => BW_::bw_skv( __( "Tel", "oik" ), "<i>" . __( "string", "oik" ) . "</i>", __( "Prefix string", "oik" ) ) );
  $syntax_after = array( "index" => BW_::bw_skv( "telephone", "<i>" . __( "field", "oik" ) . "</i>", __( "oik options field to use", "oik" ) ) );
  $syntax_telephone += $syntax;
  $syntax_telephone += $syntax_after; 
  return( $syntax_telephone );
} 

/**
 * Syntax for [bw_tel] shortcode
 */
function bw_tel__syntax( $shortcode="bw_tel" ) {
  $syntax = bw_telephone__syntax();
  $syntax['tag'] = BW_::bw_skv( "span", "<i>" . __( "tag", "oik" ) . "</i>", __( "enclosing HTML tag", "oik" ) );
  return( $syntax );
}


/**
 * Syntax for [bw_fax] shortcode
 */
function bw_fax__syntax( $shortcode="bw_fax" ) {
  $syntax = _sc_telephone();
  $syntax_fax = array( "prefix" => BW_::bw_skv( __( "Fax", "oik" ) , "<i>" . __( "string", "oik" ) . "</i>", __( "Prefix string", "oik" ) ) );
  $syntax_after = array( "index" => BW_::bw_skv( "fax", "<i>" . __( "field", "oik" ) . "</i>", __( "oik options field to use", "oik" ) ) );
  $syntax_fax += $syntax;
  $syntax_fax += $syntax_after; 
  return( $syntax_fax );
}

/**
 * Syntax for [bw_mobile] shortcode
 */
function bw_mobile__syntax( $shortcode="bw_mobile" ) {
  $syntax = _sc_telephone();
  $syntax_mobile = array( "prefix" => BW_::bw_skv( __( "Mobile", "oik" ), "<i>" . __( "string", "oik" ) . "</i>", __( "Prefix string", "oik" ) ) );
  $syntax_after = array( "index" => BW_::bw_skv( "mobile", "<i>" . __( "field", "oik" ) . "</i>", __( "oik options field to use", "oik" ) ) );
  $syntax_mobile += $syntax;
  $syntax_mobile += $syntax_after; 
  return( $syntax_mobile );
}

/**
 * Syntax for [bw_mob] shortcode
 */
function bw_mob__syntax( $shortcode="bw_mob" ) {
  $syntax = bw_mobile__syntax();
  $syntax['tag'] = BW_::bw_skv( "span", "<i>" . __( "tag", "oik" ) . "</i>", __( "enclosing HTML tag", "oik" ) );
  return( $syntax );
}




