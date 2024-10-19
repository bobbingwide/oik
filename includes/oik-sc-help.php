<?php // (C) Copyright Bobbing Wide 2012-2017, 2024

/**
 * To maintain backward compatibility with plugins which are dependent upon oik
 * using `oik_require( "includes/oik-sc-help.php" )` we need to require the oik-sc-help library
 */
oik_require_lib( "oik-sc-help" );

/**
 * oik continues to use unshared functions for its own shortcodes
 *
 */
 
/**
 * Implements "_sc__help" to provide 'default' help
 * 
 * - oik provides default help for a range of shortcodes.
 * - It only returns the translated help for the selected shortcode 
 * - Each plugin that implements '_sc__help' should only return the translation of the shortcode help for the selected shortcode.
 * - For backwards compatibility oik provides default help for some shortcodes not provided by oik; default WordPress shortcodes, Artisteer themes
 * - See the oik-sc-help plugin for shortcodes for other popular/recommended plugins
 * - See the oik-bob-bing-wide plugin for some additional oik shortcodes
 *
 * @param array $help array of translated help keyed by shortcode
 * @param string $shortcode 
 * @return array updated help
 */
function oik_lazy_sc__help( $help, $shortcode ) {
	switch ( $shortcode ) {
		case "ad": $l10n_help = __( 'Show an advertisement block (Artisteer theme)', 'oik' ); break;
		case "audio": $l10n_help = __( 'Embed audio files', 'oik' ); break;
		case "blog_title": $l10n_help = __( 'blog title (Artisteer theme)', 'oik' ); break;
		case "bw": $l10n_help = __( 'Expand to the logo for Bobbing Wide', 'oik' ); break;
		case "bw_abbr": $l10n_help = __( 'Format an abbreviation', 'oik' ); break;
		case "bw_acronym": $l10n_help = __( 'Format an acronym', 'oik' ); break;
		case "bw_accordion": $l10n_help = __( 'Display posts in an accordion', 'oik' ); break;
		case "bw_address": $l10n_help = __( 'Display the address', 'oik' ); break;
		case "bw_admin": $l10n_help = __( 'Display the Admin contact name', 'oik' ); break;
		case "bw_alt_slogan": $l10n_help = __( 'Alternative slogan', 'oik' ); break;
		case "bw_attachments": $l10n_help = __( 'List attachments with links', 'oik' ); break;
		case "bw_block": $l10n_help = __( 'Format an Artisteer block', 'oik' ); break;
		case "bw_blockquote": $l10n_help = __( 'Format a blockquote', 'oik' ); break;
		case "bw_bookmarks": $l10n_help = __( 'List bookmarks', 'oik' ); break;
		case "bw_business": $l10n_help = __( 'Display your Business name', 'oik' ); break;
		case "bw_button": $l10n_help = __( 'Show a link as a button', 'oik' ); break;
		case "bw_cite": $l10n_help = __( 'Cite a blockquote', 'oik' ); break;
		//case "bw_code": $l10n_help = __( 'Display the help and syntax for a shortcode', 'oik' ); break;
		//case "bw_codes": $l10n_help = __( 'Summarise active shortcodes', 'oik' ); break;
		case "bw_company": $l10n_help = __( 'Company name', 'oik' ); break;
		case "bw_contact": $l10n_help = __( 'Primary contact name', 'oik' ); break;
		case "bw_contact_button": $l10n_help = __( 'Contact form button', 'oik' ); break;
		case "bw_copyright": $l10n_help = __( 'Format a Copyright statement', 'oik' ); break;
		case "bw_directions": $l10n_help = __( 'Display a \'Google directions\' button.', 'oik' ); break;
		case "bw_domain": $l10n_help = __( 'Display the domain name', 'oik' ); break;
		case "bw_eblock": $l10n_help = __( 'end a [bw_block]', 'oik' ); break;
		case "bw_editcss": $l10n_help = __( 'Edit Custom CSS file button', 'oik' ); break; 
		case "bw_email": $l10n_help = __( 'Email primary contact (formal)', 'oik' ); break;
		case "bw_email_signature": $l10n_help = __( 'Format the email signature', 'oik' ); break;
		case "bw_emergency": $l10n_help = __( 'Emergency telephone number', 'oik' ); break;
		case "bw_facebook": $l10n_help = __( 'Facebook link', 'oik' ); break;
		case "bw_fax": $l10n_help = __( 'Fax number', 'oik' ); break;
		case "bw_flickr": $l10n_help = __( 'Flickr link', 'oik' ); break;
		case "bw_follow_me": $l10n_help = __( 'Display defined social media follow me links', 'oik' ); break;
		case "bw_formal": $l10n_help = __( 'Formal company name', 'oik' ); break;
		case "bw_geo": $l10n_help = __( 'Latitude and Longitude', 'oik' ); break;
		case "bw_google": $l10n_help = __( 'Google+ link', 'oik' ); break;
		case "bw_google-plus": $l10n_help = __( 'Google+ link', 'oik' ); break;
		case "bw_google_plus": $l10n_help = __( 'Google+ link', 'oik' ); break;
		case "bw_googleplus": $l10n_help = __( 'Google+ link', 'oik' ); break;
		case "bw_images": $l10n_help = __( 'Display attached images', 'oik' ); break;
		case "bw_instagram": $l10n_help = __( 'Follow me on Instagram', 'oik' ); break; 
		case "bw_linkedin": $l10n_help = __( 'Follow me on LinkedIn', 'oik' ); break;
		case "bw_list": $l10n_help = __( 'Simple list of pages/posts or custom post types', 'oik' ); break;
		case "bw_logo": $l10n_help = __( 'Display the company logo', 'oik' ); break;
		case "bw_mailto": $l10n_help = __( 'Mailto (inline)', 'oik' ); break;
		case "bw_mob": $l10n_help = __( 'Mobile phone number (inline)', 'oik' ); break;
		case "bw_mobile": $l10n_help = __( 'Mobile phone number (block)', 'oik' ); break;
		case "bw_module": $l10n_help = __( 'Information about a Drupal module', 'oik' ); break;
		case "bw_pdf": $l10n_help = __( 'Display attached PDF files', 'oik' ); break;
		case "bw_picasa": $l10n_help = __( 'Follow me on Picasa', 'oik' ); break;
		case "bw_pinterest": $l10n_help = __( 'Follow me on Pinterest', 'oik' ); break; 
		case "bw_portfolio": $l10n_help = __( 'Display matched portfolio files', 'oik' ); break;
		case "bw_posts": $l10n_help = __( 'Display posts', 'oik' ); break;
		case "bw_power": $l10n_help = __( 'Powered by WordPress', 'oik' ); break; 
		case "bw_qrcode": $l10n_help = __( 'Display an uploaded QR code image', 'oik' ); break;
		case "bw_show_googlemap": $l10n_help = __( 'Show Google map [bw_show_googlemap]', 'oik' ); break;
		case "bw_skype": $l10n_help = __( 'Skype name', 'oik' ); break;
		case "bw_slogan": $l10n_help = __( 'Primary slogan', 'oik' ); break;
		case "bw_tabs": $l10n_help = __( 'Display posts in tabbed blocks', 'oik' ); break;
		case "bw_tel": $l10n_help = __( 'Telephone number (inline)', 'oik' ); break;
		case "bw_telephone": $l10n_help = __( 'Telephone number (block)', 'oik' ); break;
		case "bw_thumbs": $l10n_help = __( 'List pages as fluid thumbnail links', 'oik' ); break;
		case "bw_tides": $l10n_help = __( 'Display tide times and heights', 'oik' ); break;
		case "bw_tree": $l10n_help = __( 'Simple tree of pages/posts or custom post types', 'oik' ); break;
		case "bw_twitter": $l10n_help = __( 'Follow me on Twitter', 'oik' ); break;
		case "bw_wpadmin": $l10n_help = __( 'Site: link to wp-admin', 'oik' ); break;
		case "bw_wtf": $l10n_help = __( 'WTF', 'oik' ); break;
		case "bw_youtube": $l10n_help = __( 'Follow me on YouTube', 'oik' ); break;
		case "bw_x": $l10n_help = __( 'Follow me on X', 'oik' ); break;
		case "bwtrace": $l10n_help = __( 'Trace facility form', 'oik' ); break;
		case "caption": $l10n_help = __( 'Display the caption for an image. Standard WordPress shortcode', 'oik' ); break;
		case "clear": $l10n_help = __( 'Clear divs ', 'oik' ); break;
		case "collage": $l10n_help = __( 'Display a collage (Artisteer theme)', 'oik' ); break;
		case "css": $l10n_help = __( 'Show a valid CSS icon (Artisteer theme)', 'oik' ); break;
		//case "div": $l10n_help = __( 'start a <div> tag', 'oik' ); break;
		//case "ediv": $l10n_help = __( 'end a <div> with <\/div>', 'oik' ); break;
		case "embed": $l10n_help = __( 'Embed media', 'oik' ); break;
		case "etag": $l10n_help = __( 'End a tag started with [stag]', 'oik' ); break;
		case "gallery": $l10n_help = __( 'Display the attached images in a gallery', 'oik' ); break;
		case "gpslides": $l10n_help = __( 'Display a Slideshow Gallery Pro slideshow', 'oik' ); break;
		case "login_link": $l10n_help = __( 'Login link (Artisteer themes)', 'oik' ); break;
		case "ngslideshow": $l10n_help = __( 'NextGen gallery slideshow', 'oik' ); break;
		//case "oik": $ __( 'Expand to the logo for oik', 'oik' ); break;case "oik" ),
		case "paypal": $l10n_help = __( 'Paypal shortcodes', 'oik' ); break;
		case "playlist": $l10n_help = __( 'Playlist', 'oik' ); break;
		case "post_link": $l10n_help = __( 'Produce a post link (Artisteer themes)', 'oik' ); break;
		case "rss": $l10n_help = __( 'Produce an RSS feeds button (Artisteer themes)', 'oik' ); break;
		case "rss_title": $l10n_help = __( 'Produce an RSS title (Artisteer themes)', 'oik' ); break;
		case "rss_url": $l10n_help = __( 'Produce an RSS URL (Artisteer themes)', 'oik' ); break;
		case "search": $l10n_help = __( 'Search button (Artisteer themes)', 'oik' ); break;
		case "sdiv": $l10n_help = __( 'Start a div', 'oik' ); break;
		case "sediv": $l10n_help = __( 'Start and end a div', 'oik' ); break;
		case "stag": $l10n_help = __( 'Start a tag', 'oik' ); break;
		case "template_url": $l10n_help = __( 'Produce a template_url (Artisteer themes)', 'oik' ); break;
		case "top": $l10n_help = __( 'Top of page button (Artisteer themes)', 'oik' ); break;
		case "video": $l10n_help = __( 'Embed video files', 'oik' ); break;
		case "wp_caption": $l10n_help = __( 'Caption (WordPress shortcode - alias)', 'oik' ); break;
		case "xhtml": $l10n_help = __( 'Valid XHTML button (Artisteer theme)', 'oik' ); break;
		case "year": $l10n_help = __( 'Current year (Artisteer theme)', 'oik' ); break;
 
		default: 
			$l10n_help = null;
	}

	if ( $l10n_help ) {
		$help[ $shortcode ] = $l10n_help;
	}
	return $help;

}

/**  
 * These functions return the help and syntax for the shortcodes that are activated
 */
function div__help() {
    /* translators: %s <div> - hardcoded */
  return( sprintf( __( 'start a %1$s tag', "oik" ), "&lt;div&gt;" ) );
}

function div__syntax( ) {
  $syntax = _sc_classes();
  return( $syntax );
}

function sdiv__syntax() {
  $syntax = _sc_classes();
  return( $syntax );
}

// We don't provide an example for the [div] shortcode as we want to deliver it using the shortcode server
// where it can be created in the __oik_sc_example field
/*
function div__example() {

  return( $example );
}
*/
 
/**
 */
function ediv__help() {
    /* translators: %1: <div> %2: </div> both hardcoded */
  return( esc_html( sprintf( __( 'end a %1$s with %2$s', "oik" ), "<div>", "</div>" ) ) );
}

function ediv__syntax() {
  return( null );
}

/**
 * Syntax for [stag] shortcode  
 */
function stag__syntax( $shortcode="stag" ) {
  $syntax = array( "name" => BW_::bw_skv( "", "<i>" . __( "tag", "oik" ) . "</i>", __( "HTML start tag", "oik" ) ) );
  $syntax += _sc_classes();
  return( $syntax );
}

/**
 * Syntax for [etag] shortcode
 */
function etag__syntax( $shortcode="etag" ) {
  $syntax = array( "name" => BW_::bw_skv( "", "<i>" . __( "tag", "oik" ) . "</i>", __( "paired HTML tag for the stag shortcode", "oik" ) ) );
  return( $syntax );
}

/**
 * Syntax for [bw_copyright] shortcode
 */
function bw_copyright__syntax( $shortcode="bw_copyright" ) {
  $syntax = array( "prefix" => BW_::bw_skv( __( "&copy; Copyright", "oik" ), "<i>" . __( "string", "oik" ) . "</i>", __( "Copyright text prefix", "oik" ) )
                 , "company" => BW_::bw_skv( bw_get_option( "company" ), "<i>" . __( "company name", "oik" ) . "</i>", __( "from oik options - company", "oik" ) )
                 , "suffix" => BW_::bw_skv( __( "All rights reserved.", "oik" ), "<i>" . __( "string", "oik" ) . "</i>", __( "copyright suffix text", "oik" ) )
                 , "from" => BW_::bw_skv( bw_get_option( "yearfrom" ), "<i>" . __( "year", "oik" ) . "</i>", __( "from oik options - yearfrom", "oik" ) )
                 , "sep" => BW_::bw_skv( "-", ",|<i>" . __( "string", "oik" ) . "</i>", __( "default: ',' if one year difference '-' otherwise", "oik" ) )
                 );
  return( $syntax );
}

/**
 * Syntax for [ad] shortcode - Artisteer themes
 */  
function ad__syntax( $shortcode='ad' ) {
  $syntax = array( "code" => BW_::bw_skv( 1, "2|3|4|5", __( "Advertisement selection - Artisteer theme options", "oik" ) )
                 , "align" => BW_::bw_skv( "left", "center|right", __( "Alignment", "oik" ) )
                 , "inline" => BW_::bw_skv( 0, "1", __( "0 if inline, 1 for block", "oik" ) )
                 );
  return( $syntax );
}

/**
 * Syntax for [post_link] shortcode - Artisteer themes
 * 
 * If the name= parameter starts with "/Blog%20Posts/" then the post name is considered to be a post, otherwise it's a page
 * A bit of a crappy shortcode if you ask me Herb 2014/05/10
 *
 */
function post_link__syntax( $shortcode='post_link' ) {
  $syntax = array( "name" => BW_::bw_skv( "/", "<i>" . __( "page name" , "oik" ) . "</i>", __( "Page to link to", "oik" ) ) );
  return( $syntax  );
}

/**
 * Syntax for [collage] shortcode - Artisteer themes
 */
function collage__syntax( $shortcode="collage" ) {
  $syntax = array( "id" => BW_::bw_skv( null, "<i>" . __( "collage ID" ,"oik" ) . "</i>", __( "Index of the theme_collages post meta to display", "oik" ) ) );
  return( $syntax  );
}






