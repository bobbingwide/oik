<?php // (C) Copyright Bobbing Wide 2013


function bw_format_flex( $atts, $post ) {

}


/** 
Flex slider COULD be coded using oik shortcodes

[bw_jq ".flexslider" flexslider script=flexslider ]

[div class=w60pc]
  [div class="flexslider"]
    [bw_list post_type="oik-plugins" numberposts=3 thumbnail=full class=slides]
  [ediv]
[ediv]

[div class=w40pc]what the
  [bw_wtf]
[ediv]




<div class="flex-container">
<div class="flexslider">
<ul class="slides">
<li style="display: list-item;">
<img src="http://qw/japics/wordpress/wp-content/themes/elitist-h/framework/timthumb.php?src=http://qw/japics/wordpress/wp-content/uploads/2013/05/Hassleblad-with-background.jpg&amp;w=940&amp;h=460&amp;zc=1" alt="Hasselblad" class="slider_img">
</li> 
</ul>
</div><!-- /flexslider -->
</div>

*/


/**
 * Implement [bw_flex] shortcode 
 */
function bw_flex( $atts=null, $content=null, $tags=null ) {
  gobang();
  return( bw_ret() ); 

}

