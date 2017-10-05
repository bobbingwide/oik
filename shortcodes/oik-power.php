<?php // (C) Copyright Bobbing Wide 2012-2017

/**
 * Implements [bw_power] shortcode
 * 
 * Display the "Proudly powered by WordPress" link to WordPress.org
 * 
 * `
 * <a href="https://wordpress.org/" title="Semantic Personal Publishing Platform" rel="generator">Proudly powered by WordPress</a>
 * `
 *
 * @param array $atts - shortcode parameters - none expected
 * @param string $content - not expected
 * @param string $tag - shortcode tag
 * @return string - the generated HTML
 */
function bw_power( $atts=null, $content=null, $tag=null ) {
  BW_::alink( "bw_power", "https://www.wordpress.org", __( "Proudly powered by WordPress", "oik" ), __( "Semantic Personal Publishing Platform", "oik" ) );
  return( bw_ret());
}
