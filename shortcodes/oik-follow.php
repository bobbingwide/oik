<?php // (C) Copyright Bobbing Wide 2010-2019

/**
 * Create a styled follow me button
 * 
 * @param array atts - array of shortcode attributes
 * 
 * network= the name of the social network - this gets lowercased to choose the button class and the oik option field
 * url = override of who to follow. value defaults to the oik option for the network
 * me = who to follow - defaults to "me"
 * text = currently ignored 
 * theme = currently supports 'dash' or 'gener' or leave null
 */
function bw_follow( $atts=null ) {
  $social_network = bw_array_get( $atts, 'network', 'Facebook' );
  $lc_social = strtolower( $social_network );
	$me = bw_array_get( $atts, "me", null );
	if ( !$me ) {
		$social = bw_array_get( $atts, 'url', null );
  	if ( !$social ) {
			$social = bw_get_option_arr( $lc_social, null, $atts );
		}
  }	else {
		$social = $me;
	}
  if ( $social ) {
    bw_follow_link( $social, $lc_social, $social_network, $atts );  
  }
  return( bw_ret());
}

/**
 * Return the preferred hostname for the social network
 * 
 * @param string $lc_social - lower case name of the social network
 * @return string
 */
function _bw_social_host( $lc_social ) {
  $hosts = array( "google" => "profiles.google.com"
                , "picasa" => "picasaweb.google.com"
								, "github" => "github.com"
								, "wordpress" => "profiles.wordpress.org" 
                );
  $host = bw_array_get( $hosts, $lc_social, "www.${lc_social}.com" );
  return( $host );
}

/**
 * Returns the URL for the social network 
 * 
 * @param string $lc_social - lower case version of the social network name
 * @param string $social - stored value - may only be the user name - e.g. the Twitter username without @
 * @return string $social_url - that might work
 */
function bw_social_url( $lc_social, $social ) {
  $url = parse_url( $social );
  $social_url = bw_array_get( $url, "scheme", "https" );
  $social_url .= "://";
	$host = bw_array_get( $url, "host", null );
	if ( !$host ) {
		$host = _bw_social_host( $lc_social );
	}
  $social_url .= $host;
  $path = "/"; 
  $path .= bw_array_get( $url, "path", $social );
  $path = str_replace( "//", "/", $path );
  $social_url .= $path;
  return( $social_url );
}  

/**
 * Implement [bw_twitter] shortcode
 *
 * Supports me= as a positional parameter overriding the stored values 
 */
function bw_twitter( $atts=null ) {
	$atts['me'] = bw_array_get_from( $atts, "me,0", null );
	if ( $atts['me'] ) {
		$atts['theme'] = bw_array_get( $atts, 'theme', 'dash' );
	}
  $atts['network'] = "Twitter" ;
  return( bw_follow( $atts ) );  
}

/**
 * Implement [bw_facebook] shortcode 
 */
function bw_facebook( $atts=null ) {
  $atts['network'] = "Facebook" ;
  return( bw_follow( $atts ) );
}

/**
 * Implement [bw_linkedin] shortcode 
 */
function bw_linkedin( $atts=null ) { 
  $atts['network'] = "LinkedIn";  
  return( bw_follow( $atts ) );
} 
   
/**
 * Implement [bw_youtube] shortcode 
 */
function bw_youtube( $atts=null ) { 
  $atts['network'] = "YouTube";  
  return( bw_follow( $atts ) );
}

/**
 * Implement [bw_picasa] shortcode 
 */
function bw_picasa( $atts=null ) { 
  $atts['network'] = "Picasa";  
  return( bw_follow( $atts ) );
}
    
/**
 * Implement [bw_flickr] shortcode 
 */
function bw_flickr( $atts=null ) {
  $atts['network'] = "Flickr";  
  return( bw_follow( $atts ));
}

/**
 * Implement [bw_google] shortcode 
 */
function bw_google_plus( $atts=null ) { 
  $atts['network'] = "GooglePlus";  
  return( bw_follow( $atts ));
}

/**
 * Implement [bw_instagram] shortcode 
 *
 * Instagram provides the inline CSS and HTML below from @link http://instagram.com/accounts/badges/
 * but [bw_instagram] just uses a simplified version of the image: instagram_48.png
 *
 
echo "<style>.ig-b- { display: inline-block; }";
echo ".ig-b- img { visibility: hidden; }";
echo ".ig-b-:hover { background-position: 0 -60px; } .ig-b-:active { background-position: 0 -120px; }";
echo ".ig-b-48 { width: 48px; height: 48px; background: url(//badges.instagram.com/static/images/ig-badge-sprite-48.png) no-repeat 0 0; }";
echo "@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) { ";
echo ".ig-b-48 { background-image: url(//badges.instagram.com/static/images/ig-badge-sprite-48@2x.png); background-size: 60px 178px; } }</style>";
echo '<a href="http://instagram.com/bobbingwide?ref=badge" class="ig-b- ig-b-48"><img src="//badges.instagram.com/static/images/ig-badge-48.png" alt="Instagram" /></a>';
 */
function bw_instagram( $atts=null ) { 
  $atts['network'] = "Instagram";  
  return( bw_follow( $atts ));
}

/**
 * Implement [bw_pinterest] shortcode 
 */
function bw_pinterest( $atts=null ) { 
  $atts['network'] = "Pinterest";  
  return( bw_follow( $atts ));
}

/**
 * Implement [bw_github] shortcode
 * 
 * We can't actually use bw_github() since it's already used in oik-bob-bing-wide for [github]
 * 
 */
function bw_github_shortcode( $atts=null, $content=null, $tag=null ) {
	$atts['network'] = 'github';
	return( bw_follow( $atts ));
}

/**
 * Produce a 'follow me' button if there is a value for the selected social network
 * 
 * @param array $atts - parameters
 * Note: $atts['network'] and $atts['me'] are expected to have been set by the calling routine.
 */ 
function bw_follow_e( $atts=null ) {
  $social_network = $atts['network'];
  $lc_social = strtolower( $social_network );
  $social = bw_get_option_arr( $lc_social, null, $atts );
  if ( $social ) {
    bw_follow_link( $social, $lc_social, $social_network, $atts );
  }     
}

/**
 * Implement [bw_follow_me] shortcode
 *
 * Produce a Follow me button for each of these networks:
 * Twitter, Facebook, LinkedIn, YouTube, Flickr, Pinterest, Instagram, GitHub and WordPress
 * or a selected set identified by network= parameter
 * 
 * @param array $atts - array of parameters
 * @return string - a set of "Follow me" links for the networks.
 */
function bw_follow_me( $atts=null ) {
	$networks = bw_follow_me_networks( $atts );
	$atts['me'] = bw_get_me( $atts ); 
	foreach ( $networks as $network ) {
		$atts['network'] = $network;
		bw_follow_e( $atts );
	}
	return( bw_ret());
}

/**
 * Returns the array of networks for the follow me links
 * @param $atts
 * @return array
 */

function bw_follow_me_networks( $atts ) {
	$all_networks = bw_follow_me_list_networks();
	$network = bw_array_get( $atts, 'network', null);
	$network = trim( $network );
	$lc_network = strtolower( $network );
	$lc_networks = explode( ',', $lc_network );
	if ( $network && count( $lc_networks )) {
		$networks = [];
		foreach ( $lc_networks as $lc_network ) {
			$network = bw_array_get( $all_networks, $lc_network, null );
			if ( $network ) {
				$networks[] = $network;
			}
		}

	} else {
		$networks = $all_networks;
	}
	return $networks;
}

/**
 * Returns the default set of networks, which no longer includes GooglePlus
 * @return array
 */
function bw_follow_me_list_networks() {
	$lc_networks = [];
	$networks = array(
		'Twitter',
		'Facebook',
		'LinkedIn',
		'YouTube',
		'Flickr',
		'Pinterest',
		'Instagram',
		'GitHub',
		'WordPress'
	);
	foreach ( $networks as $network ) {
		$lc_network = strtolower( $network );
		$lc_networks[ $lc_network ] = $network;
	}
	return $lc_networks;
}

/**
 * Create the link for the selected theme= parameter
 * 
 *
 */
function bw_follow_link( $social, $lc_social, $social_network, $atts ) {
  $social = bw_social_url( $lc_social, $social );
  $me = bw_get_me( $atts );
  $theme = bw_array_get( $atts, "theme", null );
  $class = bw_array_get( $atts, "class", null );
  $theme_functions = array( "dash" => "bw_follow_link_dash"
                          , "gener" => "bw_follow_link_gener"
                          );
  $themefunc = bw_array_get( $theme_functions, $theme, "bw_follow_link_" );
  call_user_func( $themefunc, $social, $lc_social, $social_network, $me, $class );
}

/**
 * Create a follow me link using dashicons
 * 
 * WordPress's dashicons font currently supports:
 *  facebook
 *  twitter
 *  googleplus
 * 
 * We can try simulating LinkedIn using "in"
 * but it's not as good as using the genericons font. 
 *
 * @param string $social - the URL
 * @param string $lc_social - lower case version of the social_network e.g. facebook
 * @param string $social_network - the social network e.g. Facebook
 * @param string $me - whoever me has resolved to be
 */
function bw_follow_link_dash( $social, $lc_social, $social_network, $me, $class ) {
  // bw_dash( $social );
  if ( $lc_social == "facebook" ) {
    $lc_social .= "-alt";
  }
  wp_enqueue_style( 'dashicons' );
  $dash = retstag( "span", "dashicons dashicons-$lc_social bw_follow_me $class" );
  if ( $lc_social == "linkedin" ) {
    $dash .= retstag( "span" );
    $dash .= "in";
    $dash .= retetag( "span" );
  }
  $dash .= retetag( "span" );
	$dash .= bw_follow_hash_at( $me );
  $follow_me_tooltip = sprintf( __( 'Follow %1$s on %2$s', "oik" ), $me, $social_network );
  BW_::alink( null, $social, $dash, $follow_me_tooltip );  
}

/**
 * Append the @name or #hashtag 
 *
 */
function bw_follow_hash_at( $me ) {

	$extra = null;
	if ( $me ) {
		$char = $me[0];
		switch ( $char ) {
			case '@':
			case '#':
				$extra = $me;
				break;
			
			default:
		}
	}	
	return $extra;
}

/**
 * Create a follow me link using genericons
 * 
 * The genericons font currently supports:
 * 
 * - facebook
 * - twitter
 * - googleplus
 * - linkedin
 * - pinterest
 * - instagram
 * - flickr
 * - foursquare
 * - github 
 * - youtube
 * but not
 * - picasa
 * 
 * That's not considered to be a great problem! 
 * From 
Twitch and Spotify mark the last social icons that will be added to Genericons.
Future social icons will have to happen in a separate font. 
 *
 * @param string $social - the URL
 * @param string $lc_social - lower case version of the social_network e.g. facebook
 * @param string $social_network - the social network e.g. Facebook
 * @param string $me - whoever me has resolved to be
 */
function bw_follow_link_gener( $social, $lc_social, $social_network, $me, $class ) {
  switch ( $lc_social ) {
    case "googleplus":
    case "facebook":
      $lc_social .= "-alt";
  }
  
  if ( !wp_style_is( 'genericons', 'registered' ) ) {
    wp_register_style( 'genericons', oik_url( 'css/genericons/genericons.css' ), false );
  }
  wp_enqueue_style( 'genericons' );
  $dash = retstag( "span", "genericon genericon-$lc_social bw_follow_me $class" );
  $dash .= retetag( "span" );
	$dash .= bw_follow_hash_at( $me );
  $follow_me_tooltip = sprintf( __( 'Follow %1$s on %2$s', "oik" ), $me, $social_network );
  BW_::alink( null, $social, $dash, $follow_me_tooltip );  
} 

/**
 * Displays a default Follow me link using oik icons
 *
 * - Now supports two sets: new and old
 * - The original solution ( from 2011 to Sep 2017) used .png files of 48x48 pixels called $lc_social_48.png
 * - These original files are now copied to $lc_social_old.png
 * - To use the original files pass the class name of "old" in the class= parameter.
 * - To use the new files don't use class name of old.
 * 
 * $class         | suffix used | class used
 * -------------- | ----------- | ----------
 * contains "old" | old         | as passed
 * null           | 48					| " bw_follow_new"
 * other          | 48 			  	| other with " bw_follow_new" appended
 * 
 * @param string $social The social network URL
 * @param string $lc_social - lower case social network
 * @param string $social_network - untranslated social network 
 * @param string $me - whoever me has resolved to be
 * @param string $class - CSS classes for styling
 */ 
function bw_follow_link_( $social, $lc_social, $social_network, $me, $class ) {
	$suffix = "48";
	if ( false !== strpos( $class, "old" ) ) {
		$suffix = "old";
	}	else {
		$class .= " bw_follow_new";
	} 
  $imagefile = oik_url( 'images/'. $lc_social . '_' . $suffix . '.png' );
  $follow_me_tooltip = sprintf( __( 'Follow %1$s on %2$s', "oik" ), $me, $social_network );
  $image = retimage( "bw_follow ", $imagefile, $follow_me_tooltip );
	//$image .= bw_follow_hash_at( $me );
  BW_::alink( $class , $social, $image, $follow_me_tooltip );
}

/**
 * Syntax for [bw_follow_me] shortcode
 */
function bw_follow_me__syntax( $shortcode="bw_follow_me" ) {

	$networks = bw_follow_me_list_networks();
	$networks = implode( ',', $networks );
  $syntax = array( "theme" => BW_::bw_skv( null, "gener|dash", __( "Icon font selection", "oik" ) )
                 , "class" => BW_::bw_skv( null, "<i>" . __( "class names", "oik" ) . "</i>", __( "CSS class names", "oik" ) )
                 , "alt" => BW_::bw_skv( null, "0", __( "Use option values", "oik" ) )
	            , "network" => BW_::bw_skv( $networks, "<i>" .  __( "network1,network2", "oik") . "</i>", __("CSV list of network names", "oik" ) )
                 );
  return( $syntax );
}                 
