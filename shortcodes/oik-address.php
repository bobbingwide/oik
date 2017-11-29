<?php // (C) Copyright Bobbing Wide 2011-2017

/**
 * Implement [bw_address] shortcode to display an address using Microformats
 *
 * When the tag=div then we get a blocked version of the address.
 * When tag=span the address should be inline.
 * 
 * @param array $atts shortcode parameters
 * @param string $content not expected
 * @param string $shortcode_tag shortcode tag
 * @return string generated HTML
 */
function bw_address( $atts=null, $content=null, $shortcode_tag=null ) {
  $type = bw_array_get( $atts, "type", __( "Work", "oik" ) );
	$tag = bw_array_get( $atts, "tag", "div" );
  stag( $tag, "adr bw_address" );
    stag( $tag, "type");
    e( $type );
    etag( $tag );
    stag( $tag, "extended-address");
    e( bw_get_option_arr( "extended-address", "bw_options", $atts ) );
    etag( $tag );
    stag( $tag, "street-address");
    e( bw_get_option_arr( "street-address", "bw_options", $atts ) );
    etag( $tag );  
    stag( $tag, "locality");
    e( bw_get_option_arr( "locality", "bw_options", $atts ) );
    etag( $tag );      
    stag( $tag, "region");
    e( bw_get_option_arr( "region", "bw_options", $atts ) );
    etag( $tag );      
    stag( $tag, "postal-code");
    e( bw_get_option_arr( "postal-code", "bw_options", $atts ) );
    etag( $tag );
    stag( $tag, "country-name");
    e( bw_get_option_arr( "country-name", "bw_options", $atts ) );
    etag( $tag );
  etag( $tag );
  return( bw_ret() );
}

/**
 * Syntax for [bw_address]
 * 
 * @param string $shortcode
 * @return array defining the syntax 
 */
function bw_address__syntax( $shortcode="bw_address" ) {
  $syntax = array( "type" => BW_::bw_skv( __( "Work", "oik" ), "<i>" . __( "type", "oik" ) . "</i>", __( "Address type.", "oik" ) )
                 , "alt" => BW_::bw_skv( "", "1", __( "suffix for alternative address", "oik" ) )
								 , "tag" => BW_::bw_skv( "div", "span", __( "HTML formatting tag", "oik" ) )
                 );
  return( $syntax );
}

function bw_address__example( $shortcode="bw_address" ) {
  $text = __( "Display the address defined in oik options", "oik" ) ;
  $example = '';
  bw_invoke_shortcode( $shortcode, $example, $text );
}
