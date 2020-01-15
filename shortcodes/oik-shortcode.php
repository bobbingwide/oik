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
	unset( $attributes[ 'shortcode' ] );
	unset( $attributes[ 'content' ] );
	bw_trace2( $attributes, "atts", false );

	//BW_::p( $shortcode );
	///BW_::p( $content );

	$result = bw_shortcode_event( $attributes, $content, $shortcode );

	return $result;


}
