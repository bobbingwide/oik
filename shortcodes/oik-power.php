<?php // (C) Copyright Bobbing Wide 2012-2014

/**
 * Implement [bw_power] shortcode
 * 
 * Display the "Proudly powered by WordPress" link to WordPress.org
 * 
 * `
 * <a href="http://wordpress.org/" title="Semantic Personal Publishing Platform" rel="generator">Proudly powered by WordPress</a>
 * `
 *
 * @param array $atts - shortcode parameters - none expected
 * @param string $content - not expected
 * @param string $tag - shortcode tag
 * @return string - the generated HTML
 */
function bw_power( $atts=null, $content=null, $tag=null ) {
  alink( "bw_power", "http://www.wordpress.org", __("Proudly powered by WordPress"), __("Semantic Personal Publishing Platform" ) );
  return( bw_ret());
}
