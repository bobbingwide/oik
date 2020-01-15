/** 
 * Defines generic oik shortcodes
 *
 * The generic oik shortcode block allows the user to select the shortcode from a list of shortcodes.
 * After selection the set of parameters for the shortcode is defined.
 * And the appropriate fields created.
 * 
 * I stil have very little idea how this should work!
 * 
 * 
 * @copyright (C) Copyright Bobbing Wide 2018
 * @author Herb Miller @bobbingwide
 
 */
 
const postTypeParmValues =  
{ post: "Post",
  page: "Page",
	attachment: "Attachment",
	custom: "Custom",
};

const bw_post_attrs = {};
const bw_page_attrs = {};

const bw_posts_attrs = 
{ 
	post_type: postTypeParmValues,
};

const bw_pages_attrs = 
{ 
	post_type: postTypeParmValues,
};

const bw_shortcodes = 
{
	bw_post: "bw_post",
	bw_posts: "bw_posts",
	bw_page: "bw_page",
	bw_pages: "bw_pages",
};



let bw_shortcodes_attrs = 
{ 
	bw_post: bw_post_attrs, 
	bw_posts: bw_posts_attrs,
	bw_page: bw_page_attrs,
	bw_pages: bw_pages_attrs,
};


import { get, has } from 'lodash';

	
/**
 * Returns shortcode's attributes	
 *
 * @param string shortcode name e.g. bw_posts
 * @return array attributes for the shortcode
 */
function getAttributes( shortcode ) {
	console.log( shortcode );
	var attributes = null;
	if ( has( bw_shortcodes_attrs, shortcode ) ) {
		attributes = get( bw_shortcodes_attrs, shortcode );
		console.log( attributes );
	} else {
		console.log( "Not set" );
	}
	return attributes;
}

export { bw_shortcodes, bw_shortcodes_attrs, getAttributes };



