<?php
/**
 * Implements generic dynamic shortcode block for oik shortcodes
 *
 * @param array $attributes including shortcode ( mandatory ) and content ( optional )
 * @return generated HTML
 */
function oik_shortcode_block( $attributes ) {
	bw_trace2();
	$shortcode = bw_array_get( $attributes, "shortcode", null );
	$content = bw_array_get( $attributes, "content", null );

	$parameters = bw_array_get( $attributes, 'parameters', null );

	unset( $attributes[ 'shortcode' ] );
	unset( $attributes[ 'content' ] );
	unset( $attributes[ 'parameters' ] );

	if ( $parameters ) {
		$parameters = trim( $parameters );
		$extra_atts = shortcode_parse_atts( $parameters );
		$attributes += $extra_atts;
	}
	bw_trace2( $attributes, "atts", false );

	//BW_::p( $shortcode );
	///BW_::p( $content );
	///
	do_action( "oik_add_shortcodes" );

	$result = bw_shortcode_event( $attributes, $content, $shortcode );
	if ( null === $result ) {
		$result = "<!-- No result for shortcode $shortcode -->";
	}

	return $result;


}
