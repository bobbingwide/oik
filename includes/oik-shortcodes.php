<?php // (C) Copyright Bobbing Wide 2011-2015

/**
 * Implement "oik_add_shortcodes" action for oik
 *
 * Deferred registration of shortcodes until they're actually needed
 */
function bw_oik_lazy_add_shortcodes() {
  //bw_add_shortcode_event( "bw_wtf");
  //bw_add_shortcode_event( "bw_wtf", NULL, "the_title", "bw_strip_tags" );
  //bw_add_shortcode_file( "bw_wtf", oik_path( "shortcodes/oik-wtf.php" ) );
  bw_add_shortcode( "bw_wtf", "bw_wtf", oik_path( "shortcodes/oik-wtf.php" ), false ); 

  //bw_add_shortcode_event( 'bw_directions', 'bw_directions', 'the_content,widget_text' );
  //bw_add_shortcode_file( 'bw_directions', oik_path( "shortcodes/oik-geo.php" ) );
  bw_add_shortcode( 'bw_directions', 'bw_directions', oik_path( "shortcodes/oik-geo.php" ), false ); 

  bw_add_shortcode( 'bw', 'bw_bw', oik_path( "shortcodes/oik-bw.php" ) );
  //bw_add_shortcode_event( "bw", "bw" );
  //bw_add_shortcode_event( "bw", "bw", 'the_title', 'bw_admin_strip_tags' );

  //bw_add_shortcode_event( 'oik', 'bw_oik' );
  //bw_add_shortcode_event( "oik", "bw_oik", 'the_title', 'bw_admin_strip_tags' );
  bw_add_shortcode( "oik", "bw_oik" );

  bw_add_shortcode( 'bw_address', 'bw_address', oik_path( "shortcodes/oik-address.php" ), false );

  bw_add_shortcode( 'bw_mailto', 'bw_mailto', oik_path( "shortcodes/oik-email.php" ), false );
  bw_add_shortcode( 'bw_email', 'bw_email', oik_path( "shortcodes/oik-email.php" ), false );

  bw_add_shortcode( 'bw_geo', 'bw_geo', oik_path( "shortcodes/oik-geo.php"), false );
  bw_add_shortcode( 'bw_telephone', 'bw_telephone', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_fax', 'bw_fax', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_mobile', 'bw_mobile', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_skype', 'bw_skype', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_tel', 'bw_tel', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_mob', 'bw_mob', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_wpadmin', 'bw_wpadmin', oik_path( "shortcodes/oik-domain.php" ), false );
  bw_add_shortcode( 'bw_domain', 'bw_domain', oik_path( "shortcodes/oik-domain.php" ) );
  bw_add_shortcode( 'bw_show_googlemap', 'bw_show_googlemap', oik_path( "shortcodes/oik-googlemap.php" ), false );
  bw_add_shortcode( 'bw_contact', 'bw_contact', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_company', 'bw_company', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_business', 'bw_business', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_formal', 'bw_formal', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_slogan', 'bw_slogan', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_alt_slogan', 'bw_alt_slogan', oik_path( "shortcodes/oik-company.php" ) );
  bw_add_shortcode( 'bw_admin', 'bw_admin_sc', oik_path( "shortcodes/oik-company.php" ) );

  bw_add_shortcode( 'bw_twitter', 'bw_twitter', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_facebook', 'bw_facebook', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_linkedin', 'bw_linkedin', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_youtube', 'bw_youtube', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_flickr', 'bw_flickr', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_picasa', 'bw_picasa', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_googleplus', 'bw_google_plus', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_google_plus', 'bw_google_plus', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_google-plus', 'bw_google_plus', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_google', 'bw_google_plus', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_instagram', 'bw_instagram', oik_path( "shortcodes/oik-follow.php" ), false );
  bw_add_shortcode( 'bw_pinterest', 'bw_pinterest', oik_path( "shortcodes/oik-follow.php" ), false );

  bw_add_shortcode( 'bw_follow_me', 'bw_follow_me', oik_path( "shortcodes/oik-follow.php" ), false );

  bw_add_shortcode( 'clear', 'bw_clear' );


  //bw_add_shortcode_event( 'bw_logo', 'bw_logo', 'the_content,widget_text,settings_page_bw_email_signature' );
  //bw_add_shortcode_file( "bw_logo", oik_path( "shortcodes/oik-logo.php" ) );
  bw_add_shortcode( 'bw_logo', 'bw_logo', oik_path( "shortcodes/oik-logo.php" ), false ); 

  //bw_add_shortcode_event( 'bw_qrcode', 'bw_qrcode', 'the_content,widget_text,settings_page_bw_email_signature');
  //bw_add_shortcode_file( 'bw_qrcode', oik_path( "shortcodes/oik-qrcode.php" ) );
  bw_add_shortcode( 'bw_qrcode', 'bw_qrcode', oik_path( "shortcodes/oik-qrcode.php" ), false );

  // Include [div]/[sdiv], [ediv] and [sediv] 
  bw_add_shortcode( 'div', 'bw_sdiv' );
  bw_add_shortcode( 'sdiv', 'bw_sdiv' );
  bw_add_shortcode( 'ediv', 'bw_ediv' );
  bw_add_shortcode( 'sediv', 'bw_sediv' );

  bw_add_shortcode( 'bw_emergency', 'bw_emergency', oik_path( "shortcodes/oik-phone.php" ) );
  bw_add_shortcode( 'bw_abbr', 'bw_abbr', oik_path( "shortcodes/oik-abbr.php" ) );
  bw_add_shortcode( 'bw_acronym', 'bw_acronym', oik_path( "shortcodes/oik-acronym.php" ) );
  bw_add_shortcode( 'bw_blockquote', 'bw_blockquote', oik_path( "shortcodes/oik-blockquote.php" ) );
  bw_add_shortcode( 'bw_cite', 'bw_cite', oik_path( "shortcodes/oik-cite.php" ) );
  bw_add_shortcode( 'bw_copyright', 'bw_copyright' );
  bw_add_shortcode( 'stag', 'bw_stag' ); 
  bw_add_shortcode( 'etag', 'bw_etag' );


  /* We shouldn't let any of these expand in titles */
  bw_add_shortcode( "bw_tree", "bw_tree", oik_path("shortcodes/oik-tree.php"), false );
  bw_add_shortcode( "bw_posts", "bw_posts", oik_path("shortcodes/oik-posts.php"), false );
  bw_add_shortcode( 'bw_pages', 'bw_pages', oik_path("shortcodes/oik-pages.php"), false );
  bw_add_shortcode( 'bw_list', 'bw_list', oik_path("shortcodes/oik-list.php"), false );
  bw_add_shortcode( 'bw_bookmarks', 'bw_bookmarks', oik_path("shortcodes/oik-bookmarks.php"), false );
  bw_add_shortcode( 'bw_attachments', 'bw_attachments', oik_path("shortcodes/oik-attachments.php"), false );
  bw_add_shortcode( 'bw_pdf', 'bw_pdf', oik_path("shortcodes/oik-attachments.php"), false );
  bw_add_shortcode( 'bw_images', 'bw_images', oik_path("shortcodes/oik-attachments.php"), false );
  bw_add_shortcode( 'bw_portfolio', 'bw_portfolio', oik_path("shortcodes/oik-attachments.php"), false );
  bw_add_shortcode( 'bw_thumbs', 'bw_thumbs', oik_path("shortcodes/oik-thumbs.php"), false );

  bw_add_shortcode( 'bw_button', 'bw_button_shortcodes', oik_path("shortcodes/oik-button.php"), false );
  bw_add_shortcode( 'bw_contact_button', 'bw_contact_button', oik_path("shortcodes/oik-button.php"), false );

  bw_add_shortcode( 'bw_block', 'bw_block', oik_path("shortcodes/oik-blocks.php"), false );
  bw_add_shortcode( 'bw_eblock', 'bw_eblock', oik_path("shortcodes/oik-blocks.php"), false );
  bw_add_shortcode( 'paypal', 'bw_pp_shortcodes', oik_path( "shortcodes/oik-paypal.php"), false );

  /* Allow the NextGEN slideshow to be used in widgets as well as in context 
  */
  bw_add_shortcode_event( 'ngslideshow', 'NextGEN_shortcodes::show_slideshow', 'the_content,widget_text' );
  // bw_add_shortcode_file ( 'ngslideshow', oik_path( "shortcodes/oik-slideshows.php") );

  bw_add_shortcode( 'gpslides', 'bw_gp_slideshow', oik_path( "shortcodes/oik-slideshows.php"), false  );

  /* Shortcodes for each of the more useful APIs */
  bw_add_shortcode( 'bwtron', 'bw_trace_on', oik_path( "shortcodes/oik-trace.php") , false );
  bw_add_shortcode( 'bwtroff', 'bw_trace_off', oik_path( "shortcodes/oik-trace.php") , false );
  bw_add_shortcode( 'bwtrace', 'bw_trace_button', oik_path( "shortcodes/oik-trace.php") , false );

  add_action( "bw_sc_help", "bw_sc_help" );
  add_action( "bw_sc_syntax", "bw_sc_syntax" );
  add_action( "bw_sc_example", "bw_sc_example");
  add_action( "bw_sc_snippet", "bw_sc_snippet" );

  bw_add_shortcode_file( 'portfolio_slideshow', oik_path( "shortcodes/oik-slideshows.php"), false );
  bw_add_shortcode_file( 'nggallery', oik_path( "shortcodes/oik-galleries.php" ) );

  bw_add_shortcode( "bw_power", "bw_power", oik_path( "shortcodes/oik-power.php" ) );
  bw_add_shortcode( 'bw_editcss', 'bw_editcss', oik_path("shortcodes/oik-bob-bing-wide.php"), false );
  bw_add_shortcode( "bw_table", "bw_table", oik_path("shortcodes/oik-table.php"), false );

  // New shortcodes for oik v2.0
  bw_add_shortcode( "bw_parent", "bw_parent", oik_path( "shortcodes/oik-parent.php" ), false );
  bw_add_shortcode( "bw_iframe", "bw_iframe", oik_path( "shortcodes/oik-iframe.php" ), false );
  bw_add_shortcode( "bw_jq", "bwsc_jquery", oik_path( "shortcodes/oik-jquery.php" ), false );
  bw_add_shortcode( "bw_accordion", "bw_accordion", oik_path( "shortcodes/oik-accordion.php" ), false );
  bw_add_shortcode( "bw_tabs", "bw_tabs", oik_path( "shortcodes/oik-tabs.php" ), false );
  bw_add_shortcode( "bw_login", "bw_login_shortcode", oik_path( "shortcodes/oik-login.php" ), false );
  bw_add_shortcode( "bw_loginout", "bw_loginout_shortcode", oik_path( "shortcodes/oik-login.php" ), false );
  bw_add_shortcode( "bw_register", "bw_register_shortcode", oik_path( "shortcodes/oik-login.php" ), false );
  bw_add_shortcode( "bw_link", "bw_link", oik_path( "shortcodes/oik-link.php" ), false );
  bw_add_shortcode( "bw_contact_form", "bw_contact_form", oik_path( "shortcodes/oik-contact-form.php" ), false );
   

  bw_add_shortcode( "bw_countdown", "bw_countdown", oik_path( "shortcodes/oik-countdown.php" ), false );
  /* New shortcode for oik v2.0.2 / v2.1 */
  bw_add_shortcode( "bw_cycle", "bw_cycle", oik_path( "shortcodes/oik-cycle.php" ), false ); 
  /* New shortcode for oik v2.3 */
  bw_add_shortcode( "bw_count", "bw_count", oik_path( "shortcodes/oik-count.php" ) );
  
  
  bw_add_shortcode( "bw_navi", "bw_navi", oik_path( "shortcodes/oik-navi.php" ), false );
  
  add_filter( "oik_shortcode_result", "oik_navi_shortcode_result", 10, 4 );
  add_filter( "oik_shortcode_atts", "oik_navi_shortcode_atts", 10, 3 );
  
} 

/**
 * Implement "oik_shortcode_atts" filter for pagination
 *
 * If the shortcode parameters includes "posts_per_page" then we need to consider pagination
 * This means that need to be able to access the WP_Query instance used to retrieve posts
 * So we have to create some 'slightly hidden' entries in the $atts array.
 * 
 * 'bwscid' - the shortcode identifier
 * 'paged' - instructs WP_Query to access a particular page
 * 'bw_query' - the new instance of WP_Query to be used to perform the DB access
 *
 *
 * @param array $atts - shortcode parameters to filter
 * @param string $content - content of enclosed shortcode
 * @param string $tag - shortcode name
 * @return array - the filtered atts array
 */
function oik_navi_shortcode_atts(  $atts=null, $content=null, $tag=null ) {
  $posts_per_page = bw_array_get( $atts, "posts_per_page", null );
  if ( $posts_per_page ) {
    oik_require( "shortcodes/oik-navi.php" );
    $bwscid = bw_get_shortcode_id( true );
    $page = bw_check_paged_shortcode( $bwscid );
    $atts['bwscid'] = $bwscid;
    $atts['paged'] = $page;
    if ( !is_numeric( $posts_per_page ) ) {
      $atts['posts_per_page'] = get_option( "posts_per_page" ); 
    }  
    $atts['bw_query'] = new WP_Query();
  }  
  return( $atts );
}

/**
 * Implement "oik_shortcode_result" for pagination
 *
 * @param string $result - the result of the shortcode expansion so far
 * @param array $atts - shortcode parameters - including our amendments
 * @param string $content - future use
 * @param string $tag - future use
 * @return string - the modified result
 *
 */
function oik_navi_shortcode_result( $result=null, $atts=null, $content=null, $tag=null ) {
  $posts_per_page = bw_array_get( $atts, "posts_per_page", null );
  if ( $posts_per_page ) {
    oik_require( "shortcodes/oik-navi.php" ); // belt and braces 
    oik_navi_s2eofn_from_query( $atts );
    $prepend = bw_ret();
    $result = $prepend . $result; 
    oik_navi_lazy_paginate_links( $atts );
    $result .= bw_ret(); 
  }
  return( $result ); 
} 


