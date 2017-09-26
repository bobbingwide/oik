<?php
/*

    Copyright 2012-2017 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/
oik_require( "includes/oik-sc-help.inc" );

/**
 * Create a link to the shortcode if in admin pages
 *
 * @param string $shortcode - the shortcode
 */
function bw_code_link( $shortcode ) {
  if ( is_admin() ) {
     alink( null, admin_url("admin.php?page=oik_sc_help&amp;code=$shortcode"), $shortcode );
  } else {
    e( $shortcode );
  }  
  e( " - " );
}

/**
 * Return the shortcode's callback function
 *
 * For functions registered using bw_add_shortcode() the callback function will be bw_shortcode_event.
 * This won't be unique so we then try to find the actual function passed. @see bw_get_shortcode_function() 
 *
 * @param string $shortcode - the shortcode tag
 * @return mixed - the registered callback function
 *
 */
function bw_get_shortcode_callback( $shortcode ) {
  global $shortcode_tags; 
  $callback = bw_array_get( $shortcode_tags, $shortcode, null );
  //bw_trace2( $callback, "shortcode callback" );
  return( $callback ); 
}

/**
 * Return the function to invoke for the shortcode
 * 
 * We need to cater for callbacks which are defined as object and function
 * `
 *    Array
        (
            [0] => AudioShortcode Object
                (
                )

            [1] => audio_shortcode
        )
 * `    
 * Given that $callback is passed in and that is_callable should have already been called
 * then we should always expect $callable_name to be set! 
 * But this doesn't mean that the $function should be callable.        
 *
 * @param string $shortcode - the shortcode to invoke
 * @param mixed $callback - default callback for the shortcode, if the 'all' or 'the_content' event is not defined for the shortcode.
 * @return string callable name for the shortcode function
 */
function bw_get_shortcode_function( $shortcode, $callback=null ) {
  global $bw_sc_ev;
  $events = bw_array_get( $bw_sc_ev, $shortcode, null );
  $function = bw_array_get_from( $events, 'all,the_content', $callback );
  $callable_name = null;
  if ( !is_callable( $function, false, $callable_name ) ) {
    $callable_name = bw_array_get( $function, 1, $shortcode );
    bw_trace2( $callable_name, "callable_name", false ); 
    //bw_trace2( $bw_sc_ev, "unexpected result!" );
    //bw_backtrace(); 
  } 
  return( $callable_name );  
}

/**
 * Return Yes / No to indicate if a shortcode expands in titles
 *
 * When you register a shortcode using bw_add_shortcode() you can decide whether or not it will be expanded during 'the_title' processing.
 * Shortcodes which are registered using add_shortcode() have to control their own expansion.
 * So, unless they have logic to test the current filter then they will be expanded in titles
 * due to the fact that oik adds the do_shortcode action for 'the_title'
 * We use a lower case "yes" to indicate this. 
 *
 * @param string $shortcode
 * @return string 'Yes' if it does, 'No' if it doesn't, 'yes' for unknown
 */
function bw_get_shortcode_expands_in_titles( $shortcode ) {
  $expand = bw_get_shortcode_title_expansion( $shortcode );
  if ( $expand === null ) {
    $sceit = __( "yes", "oik" );
  } elseif ( $expand === false ) {
    $sceit = __( "No", "oik" );
  } else {
    $sceit = __( "Yes", "oik" );
  }
  return( $sceit );
}

/**
 * Display the shortcode, syntax and link
 *
 * Note: This used to display a separate link to the oik shortcode server help.
 * Now this is incorporated into the syntax help, along with a link to the oik shortcode parameter
 *
 * @param string $shortcode - the shortcode tag
 * @param string $callback - the registered callback for the shortcode
 */
function bw_get_shortcode_syntax_link( $shortcode, $callback ) {
  stag( "tr" );
  stag( "td" );
  bw_code_link( $shortcode );
  do_action( "bw_sc_help", $shortcode );
  etag( "td" );
  stag( "td" );
  do_action( "bw_sc_syntax", $shortcode );
  etag( "td" );
  stag( "td" );
  e( bw_get_shortcode_expands_in_titles( $shortcode ) );
  etag( "td" );
  etag( "tr" );
}

/**
 * Display a link to "external" shortcode help
 *
 * Plugins can intercept the shortcode link to provide their own links to further documentation
 * e.g. diy-oik will produce a link to the user's own definition of the DIY shortcode.
 *
 * @param string $shortcode - the shortcode
 * @param mixed $callback - the callback function - which may not be passed
 */
function bw_sc_link( $shortcode, $callback=null ) {
  $function = bw_get_shortcode_function( $shortcode, $callback );
	oik_require( "admin/oik-admin.inc" );
	$link = oik_get_plugins_server();
  $link .= "/oik-shortcodes/$shortcode/$function"; 
  $link = apply_filters( "bw_sc_link", $link, $shortcode, $function );
  if ( $link ) {
    BW_::alink( NULL, $link, "$shortcode", sprintf( __( '%1$s help', "oik" ), $shortcode ) );   
  } else {
    e( $shortcode );
  } 
} 

/**
 * Table header for bw_codes
 *
 * Produce the table header for the bw_code shortcode
 * 
 * @param bool $table - set to true when a table is required
 */
function bw_help_table( $table=true ) {
  if ( $table ) {
    stag( "table", "widefat" );   
    stag( "thead" ); 
    stag( "tr" );
    bw_th( __( "Help", "oik" ) );
    bw_th( __( "Syntax", "oik" ) );
    bw_th( __( "Expands in titles?", "oik" ) );
    etag( "tr" );
    etag( "thead" );
 
    stag( "tbody" );
  }  
}

/**
 * table footer for bw_codes
 *
 * @param bool $table - set to true when a table is required
 */
function bw_help_etable( $table=true ) { 
  if ( $table ) {
    etag( "tbody" );
    etag( "table" );
  }  
}

/**
 * Return an associative array of shortcodes and their one line descriptions (help)
 *
 * The array is ordered by shortcode
 * @uses _bw_lazy_sc_help() rather than
 *
 * @param array $atts - attributes - currently unused
 * @return array - associative array of shortcode => description
 */ 
function bw_shortcode_list( $atts=null ) {
  global $shortcode_tags; 
  
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    $schelp = _bw_lazy_sc_help( $shortcode );
    $sc_list[$shortcode] = $schelp;
  }
  ksort( $sc_list );
  return( $sc_list );
}  

/**
 * Produce a table of shortcodes
 * 
 * @param array $atts - shortcode parameters
 */
function bw_list_shortcodes( $atts = NULL ) {
  global $shortcode_tags;
  $ordered = bw_array_get( $atts, "ordered", "N" );
  $ordered = bw_validate_torf( $ordered ); 
  //bw_trace2( $shortcode_tags );
  //bw_trace2( $ordered, "ordered" );
  if ( $ordered ) {
    ksort( $shortcode_tags );
  }
  //bw_trace2( $shortcode_tags, "shortcode_tags" );
  add_action( "bw_sc_help", "bw_sc_help" );
  add_action( "bw_sc_example", "bw_sc_example" );
  add_action( "bw_sc_syntax", "bw_sc_syntax", 10, 2 );
  bw_help_table();
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    bw_get_shortcode_syntax_link( $shortcode, $callback );
  }
  bw_help_etable();
}

/** 
 * Display a table of active shortcodes
 *
 * @param array $atts - shortcode parameters
 * @return results of the shortcode
 * @uses bw_list_shortcodes()
 */
function bw_codes( $atts = NULL ) {
  $text = "&#91;bw_codes] is intended to show you all the active shortcodes and give you some help on how to use them. ";
  $text .= "If a shortcode is not listed then it could be that the plugin that provides the shortcode is not activated. ";
  $text .= "Click on the link to find detailed help on the shortcode and its syntax. "; 
  e( $text );  
  $shortcodes = bw_list_shortcodes( $atts );
  return( bw_ret());
} 

/**
 * Display information about a specific shortcode
 *
 * If no shortcode is specified then we check to see if this is a shortcode or shortcode example and determine the shortcode from that.
 *
 * @param array $atts - shortcode parameters
 * @return results of the shortcode
 */
function bw_code( $atts=null, $content=null, $tag=null ) {
  $shortcode = bw_array_get( $atts, "shortcode", null );
  if ( !$shortcode ) {
    $link_text = bw_array_get( $atts, 0, null );
    if ( !$link_text ) {
      $post_id = bw_global_post_id();
      $shortcode_id = get_post_meta( $post_id, "_sc_param_code", true );
      if ( $shortcode_id ) {
        $post_id = $shortcode_id;
      }  
      $shortcode = get_post_meta( $post_id, "_oik_sc_code", true ); 
      if ( $shortcode ) { 
        $atts['syntax'] = bw_array_get( $atts, "syntax", "y" ); 
        $atts['help'] = bw_array_get( $atts, "help", "n" ); 
        $atts['example'] = bw_array_get( $atts, "example", "n" ); 
      }
    }  
  }
  if ( $shortcode ) {
    $help = bw_array_get( $atts, "help", "Y" );
    $syntax = bw_array_get(  $atts,  "syntax", "Y" );
    $example = bw_array_get( $atts, "example", "Y" );
    $live = bw_array_get( $atts, "live", "N" );
    $snippet = bw_array_get( $atts, "snippet", "N" );
    
    $help = bw_validate_torf( $help );
    if ( $help ) {
      p( "Help for shortcode: [${shortcode}]", "bw_code_help" );
      //bw_trace2( $shortcode, "before do_action" );
      do_action( "bw_sc_help", $shortcode );
    }  
    $syntax = bw_validate_torf( $syntax );
    if ( $syntax ) {
      p( "Syntax", "bw_code_syntax" ); 
      do_action( "bw_sc_syntax", $shortcode );
    }  
    $example = bw_validate_torf( $example );
    if ( $example ) {
      p( "Example", "bw_code_example");
      do_action( "bw_sc_example", $shortcode );
    }

    $live = bw_validate_torf( $live ) ;
    if ( $live ) {
      p("Live example", "bw_code_live_example" );
      $live_example = bw_do_shortcode( '['.$shortcode.']' );
      e( $live_example );
    }
    
    $snippet = bw_validate_torf( $snippet );
    if ( $snippet ) {
      p( "Snippet", "bw_code_snippet" );
      do_action( "bw_sc_snippet", $shortcode );
    }
  } else {
    $link_text = bw_array_get( $atts, 0, null );
    if ( $link_text ) {
      bw_code_example_link( $atts );
    } else {
      return( bw_code( array( "shortcode" => "bw_code" ) ) );
    }
  } 
  return( bw_trace2( bw_ret(), "bw_code_return"));
}


/**
 * Create a nicely formatted link to the definition of the shortcode
 *
 * When the shortcode= parameter is not specified then we assume that this is an example
 * that we want to both show AND make a link to the help in oik-plugins.
 * The first word is expected to be the shortcode and the rest are parameters
 * e.g. [bw_code bw_code shortcode=bw_code] 
 * 
 * @param $atts -  shortcode parameters
 * `
 Array
(
    [0] => Array
        (
            [0] => bw_link
            [1] => 1234
        )

    [1] => 
    [2] => bw_code
 * `
 * 
 * @see http://www.undermyhat.org/blog/2012/05/how-to-properly-escape-shortcodes-in-wordpress/
 * 
 */ 
function bw_code_example_link( $atts ) {
  $shortcode_string = bw_array_get( $atts, 0, null );
  $link_text = "&#91;";
  $link_text .= $shortcode_string; 
  $link_text .= "]";
  $shortcodes = explode( " ", $shortcode_string );
  $shortcode = $shortcodes[0];
  $callback =  bw_get_shortcode_callback( $shortcode );
  if ( $callback ) {
    $function = bw_get_shortcode_function( $shortcode, $callback );
  } else {
    $function = null;
  }
  if ( $function ) {
    $link = "http://www.oik-plugins.com/oik-shortcodes/$shortcode/$function";  
    //$link = "http://qw/wordpress/oik-shortcodes/$shortcode/$function";  
       
    alink( "bw_code $shortcode", $link, $link_text, "Link to help for shortcode: $shortcode" );   
  } else { 
    span( "bw_code $shortcode" );
    e( $link_text );
    epan();
  }   
}

/**
 * Implement "bw_sc_shortcake_compatible" for oik 
 * 
 * Filter out the shortcodes that aren't compatible with shortcake.
 *
 * Note: In this first version of oik we'll handle shortcodes for other plugins as well.
 * @TODO Complete the list and/or implement a proper solution
 *
 * @param array $sclist array of shortcodes
 * @return array updated shortcode list
 */
 
/* This is NOT part of the docblock! 
 *
 * Decisions are:
 * Value | Meaning
 * ----- | --------------
 * blank | not yet decided
 * n | shortcode will not be registered to shortcake, shortcake will not attempt to expand it
 * y | shortcode will be registered to shortcake, shortcake will attempt to expand it
 * 0 | shortcode will not work properly if written using positional parameters - but we'll let it pass for the time being
 *
 *
 * We'll err towards y even though the shortcode may produce a lot of output
 * diy-oik shortcodes should implement their own filter.
 * In this list diy-oik shortcodes had a ? - as I was using the wrong version of diy-oik
 * Shortcodes which used to have a ? may now show the implementing function
 *
 * Decision | shortcode | description
 * -------- | --------- | --------------------------
 y |   OIK | Spells out the <span class="bw_oik"><abbr  title="OIK Information Kit">oik</abbr></span> backronym
 y |   api | Simple API link
 y |   apiref | ?
 y |  apis | Link to API definitions
 y |   archives | ?
 y |  artisteer | Styled form of Artisteer
   |  audio | Displays uploaded audio file as an audio player
   |  bandcamp | ?
   |  bbboing | Obfuscate some text but leave it readable, apparently
   |  blip.tv | ?
 y |   bp | Styled form of BuddyPress
 y |  bw | Expand to the logo for Bobbing Wide
 y  | bw_abbr | Format an abbreviation
   | bw_accordion | Display posts in an accordion
 y | bw_acronym | Format an acronym
   | bw_action | ?
 y  | bw_address | Display the address
 y | bw_admin | Display the Admin contact name
 y  | bw_alt_slogan | Alternative slogan
 y | bw_api | Dynamic API syntax help
   | bw_attachments | List attachments with links
   | bw_autop | Dynamically re-enable/disable automatic paragraph generation
   | bw_background | Use attached image as the background
   | bw_block | Format an Artisteer block
   | bw_blockquote | Format a blockquote
   | bw_blog | Select blog to process
   | bw_blogs | List blogs using bw_pages style display
   | bw_bookmarks | List bookmarks
   | bw_business | Display your Business name
   | bw_button | Show a link as a button
   | bw_cite | Cite a blockquote
  n | bw_code | Display the help and syntax for a shortcode
  n | bw_codes | Display the currently available shortcodes
   | bw_company | Company name
   | bw_contact | Primary contact name
   | bw_contact_button | Contact form button
   | bw_contact_form | Display a contact form for the specific user
   | bw_copyright | Format a Copyright statement
   | bw_count | Count posts for the selected post type
   | bw_countdown | Countdown timer
   | bw_crumbs | Display breadcrumbs
   | bw_css | Add internal CSS styling
   | bw_csv | Display CSV data in a table or list
   | bw_cycle | Display pages using jQuery cycle
   | bw_dash | Display a dash icon
   | bw_directions | Display a 'Google directions' button.
   | bw_domain | Display the domain name
   | bw_eblock | end a| bw_block]
   | bw_editcss | Edit Custom CSS file button
   | bw_email | Email primary contact (formal)
   | bw_emergency | Emergency telephone number
   | bw_facebook | Facebook link
   | bw_fax | Fax number
   | bw_field | Format custom fields without labels
   | bw_fields | Format custom fields, with labels
   | bw_flickr | Flickr link
   | bw_follow_me | Display defined social media follow me links
   | bw_formal | Formal company name
   | bw_geo | Latitude and Longitude
  n | bw_geshi | Generic Syntax Highlighting
   | bw_google | Google+ link
   | bw_google-plus | Google+ link
   | bw_google_plus | Google+ link
   | bw_googleplus | Google+ link
   | bw_graphviz | Display a GraphViz diagram
   | bw_iframe | Embed a page in an iframe
   | bw_images | Display attached images
   | bw_instagram | Follow me on Instagram
   | bw_jq | Perform a jQuery method
   | bw_link | Display a link to a post.
   | bw_linkedin | Follow me on LinkedIn
   | bw_list | Simple list of pages/posts or custom post types
   | bw_login | Display the login form or protected content
   | bw_loginout | Display the Login or Logout link
   | bw_logo | Display the company logo
   | bw_mailto | Mailto (inline)
   | bw_mob | Mobile phone number (inline) 
   | bw_mobile | Mobile phone number (block)
   | bw_more | Hide remaining content behind 'read more' button
   | bw_mshot | ?
   | bw_navi | Simple paginated list
   | bw_new | Display a form to create a new post
   | bw_option | Display the value of an option field
   | bw_otd | Display 'On this day' in history related content 
   | bw_page | Add page button
   | bw_pages | Display page thumbnails and excerpts as links
   | bw_parent | Display a link back to the parent page
   | bw_pdf | Display attached PDF files
   | bw_picasa | Follow me on Picasa
   | bw_pinterest | Follow me on Pinterest
   | bw_plug | Show plugin information
   | bw_popup | Display a popup after a timed delay
   | bw_portfolio | Display matched portfolio files
   | bw_post | Add Post button
   | bw_posts | Display posts
   | bw_power | Powered by WordPress
   | bw_qrcode | Display an uploaded QR code image
   | bw_register | Display a link to the Registration form, if Registration is enabled
   | bw_related | Display related content
   | bw_rpt | ?
   | bw_rwd | Dynamically generate oik responsive web design CSS classes
   | bw_search | Display search form
   | bw_show_googlemap | Show Google map| bw_show_googlemap]
   | bw_skype | Skype name
   | bw_slogan | Primary slogan
   | bw_table | Display custom post data in a tabular form
   | bw_tabs | Display posts in tabbed blocks
   | bw_tel | Telephone number (inline)
   | bw_telephone | Telephone number (block)
   | bw_testimonials | Display testimonials
   | bw_text | ?
   | bw_thumbs | List pages as fluid thumbnail links
   | bw_tides | Display times and tides for a UK location
   | bw_tree | Simple tree of pages/posts or custom post types
   | bw_twitter | Follow me on Twitter
   | bw_user | Display information about a user
   | bw_users | Display information about site users
   | bw_video | Display the video specified (url=) or attached videos
   | bw_wpadmin | Site: link to wp-admin
   | bw_wtf | WTF
   | bw_youtube | Follow me on YouTube
   | bwtrace | Trace facility form
   | bwtroff | Force trace off
   | bwtron | Force trace on
   | caption | Display the caption for an image. Standard WordPress shortcode
   | classes | Link to class definitions
   | clear | Clear divs 
   | cloned | Display clones of this content
  0| codes | Create links to related shortcodes
   | contact-field | Display Grunion Contact field
   | contact-form | Display Grunion Contact form
   | cookies | Display table of cookies, by category
   | dailymotion | ?
   | dailymotion-channel | ?
   | digg | ?
   | div | start a &lt;div&gt; tag
   | diy | ?
   | drupal | Styled form of Drupal
   | ediv | end a &lt;div&gt; with &lt;/div&gt;
   | embed | Embed media
   | etag | End a tag started with  stag]
   | facebook | ?
   | file | Display reference for a file
   | files | Link to files definitions
   | flickr | ?
   | footer_backtotop | ?
   | footer_childtheme_link | ?
   | footer_copyright | ?
   | footer_genesis_link | ?
   | footer_loginout | ?
   | footer_studiopress_link | ?
   | footer_wordpress_link | ?
   | gallery | Display the attached images in a gallery
   | getnivo | ?
   | getoik | ?
   | gist | ?
   | googlemaps | ?
   | googleplus | ?
   | googlevideo | ?
   | gpslides | Display a Slideshow Gallery Pro slideshow
   | hook | ?
   | hooks | Link to hook definitions
   | instagram | ?
   | lartisteer | Link to Artisteer 
   | lazy | ?
 y  | lbp | Link to BuddyPress
   | lbw | Link to Bobbing Wide sites
   | ldrupal | Link to drupal.org
   | loik | Link to| oik]-plugins
   | loikeu | ?
   | loikp | ?
   | loikuk | ?
   | lssc | ?
   | lwp | Link to WordPress.org
   | lwpms | Link to WordPress Multi Site
   | md | ?
   | medium | ?
   | mixcloud | ?
   | ngslideshow | NextGen gallery slideshow
   | nivo | Display the nivo slideshow for attachments or other post types.
   | oik | Expand to the logo for oik
   | oik_edd_apikey | get API key form for EDD
   | oikp_download | Produce a download button for a plugin
   | oikth_download | Produce a download button for a theme
   | paypal | Paypal shortcodes
   | playlist | Playlist
   | polldaddy | ?
   | post_author | ?
   | post_author_link | ?
   | post_author_posts_link | ?
   | post_categories | ?
   | post_comments | ?
   | post_date | ?
   | post_edit | ?
   | post_modified_date | ?
   | post_modified_time | ?
   | post_tags | ?
   | post_terms | ?
   | post_time | ?
   | presentation | ?
   | recipe | Embeds a recipe
   | scribd | ?
   | sdiv | Start a div
   | sediv | Start and end a div
   | slide | ?
   | slideshare | ?
   | slideshow | ?
   | smart | ?
   | soundcloud | ?
   | stag | Start a tag
   | ted | ?
   | twitter-timeline | ?
   | us_tides | Display tide times and heights for a US location
   | video | Embed video files
   | videopress | ?
   | vimeo | ?
   | vine | ?
   | wp | Display a styled form of WordPress. 
   | wp_caption | Display the caption for an image. Standard WordPress shortcode
   | wpms | Styled form of WordPress Multi Site
   | wpseo_breadcrumb | ?
   | wpseo_sitemap | ?
   | wpvideo | ?
   | wufoo | ?
   | youtube | ?
 
 */										
function bw_sc_shortcake_compatible( $sclist ) {
	bw_trace2();
	bw_backtrace();
	unset( $sclist['bw_code']);
	// unset( $sclist['bw_codes']);
	unset( $sclist['bw_cycle']);
	unset( $sclist['bw_geshi']);
	unset( $sclist['codes']);
	//gob();
	return( $sclist );
}


/**
 * Help for [bw_code] shortcode
 */
function bw_code__help() {
  return( __( "Display the help and syntax for a shortcode", null ) );
}

/**
 * Syntax for [bw_code] shortcode
 */
function bw_code__syntax() {
  $syntax = array( "shortcode" => BW_::bw_skv( "bw_code", "<i>" . __( "shortcode", null) . "</i>", __( "The shortcode you want explained", null ) )  
                 , "help" => BW_::bw_skv( "Y", "N", __( "Display help for the shortcode", null ) )
                 , "syntax" => BW_::bw_skv( "Y", "N", __( "Display the syntax", null ) )
                 , "example" => BW_::bw_skv( "Y", "N", __("Display an example, if possible", null ) )
                 , "live" => BW_::bw_skv( "N", "Y", __( "Display the live results using default values", null ) )
                 , "snippet" => BW_::bw_skv( "N", "Y", __( "Display the generated HTML", null ) )
                 );
  return( $syntax );
}

/**
 * Example of [bw_code] for the [oik] shortcode
 */
function bw_code__example() {
	//oik_require( "shortcodes/oik-codes.php" );
  br();
  e( __( "e.g.", "oik" ) . " [bw_code shortcode=\"oik\"]" );
  br();
  e( __( "Display information about the [oik] shortcode", "oik" ) );
  br();
  oik__help();
  br();
  bw_lazy_sc_syntax( "oik" );
  oik__example();
}

/**
 * Syntax for [bw_codes]
 */
function bw_codes__syntax() {
  $syntax = array( "ordered" => BW_::bw_skv( "N", "Y", __( "Sort the shortcode codes by name.", "oik" ) ) 
                 ); 
  return( $syntax );
}

/**
 * Help for [bw_codes] shortcode
 */
function bw_codes__help() {
  return( __( "Display the currently available shortcodes", "oik" ) );
}

/**
 * Example for [bw_codes]
 * 
 * These notes used to be included in the output, but are no longer relevant.
 * - // br( "Note: The default display lists the order in which the shortcodes are <i>evaluated</i>" );
 * - // e( "If you have a problem with hyphenated shortcodes not being chosen then it could be due to the order in which the shortcodes were registered using add_shortcode();" );
 * - // because the shortest shortcode has been added before the longer ones. 
 * - // See wp-1, wp-2, wp and wp-3 ");
 */
function bw_codes__example() {
  e( __( "The currently available shortcodes are displayed in a table with a brief description, the known syntax and a link to further help.", "oik" ) );
	br();
  alink( null, "http://www.oik-plugins.com/oik-shortcodes/bw_codes/bw_codes", __( "[bw_codes] - list shortcodes", "oik" ) );
  
}


