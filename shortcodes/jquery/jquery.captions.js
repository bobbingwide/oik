/**
 * (C) Copyright Bobbing Wide 2013
 * Simple jQuery to slide image captions when hovering over the image
 * Inspired by the jQuery from the FX-image plugin 
 * @link http://miguras.com/wordpress/fx/wp-content/plugins/FX-Image/js/mig-fx-effects.js?ver=3.6
 
 
 <div class="w50pc captions">
<p><a id="link-8710" href="http://qw/wordpress/oik_todo/support-link-parameter-on-oik-nivo-slider-similar-to-the-gallery-shortcode/1968-caravan-2/" title="1968-Caravan">
<img class="bw_thumbnail" src="http://qw/wordpress/wp-content/uploads/2013/08/1968-Caravan.jpg" title="1968-Caravan" alt="1968-Caravan"></a>
<span class="title">1968-Caravan</span></p>
<p class="caption">Caravan - first album</p>
<p class="description">
</p><div class="cleared"></div>
</div>

<div class="fx-gallery-wrapper one one-third no" style="float:left;">
<div class="fx-gallery-content"><a href="http://miguras.com/wordpress/fx/wp-content/uploads/2013/01/test5.jpg">
<img alt="test5" src="http://miguras.com/wordpress/fx/wp-content/uploads/2013/01/test5.jpg" width="593" height="395" style="max-width: 100%; border: none; padding: 0px; margin: 0px;"></a>
</div>
<div class="fx-gallery-title" style="opacity: 0.8;">
<h3 style="margin: 0px; font-size: 110%;">Title Ex</h3>
</div>
<div class="fx-gallery-caption light" style="background-color: rgb(0, 0, 0); text-align: left; opacity: 0.8; bottom: 0px;">this is a caption example for this image. You can write here whatever you want</div>
<a href="http://miguras.com/wordpress/fx/wp-content/uploads/2013/01/test5.jpg">
<div data-link="image" data-target="_" class="fx-gallery-overlay white" style="opacity: 0.4;"></div></a>
<a><div class="fx-gallery-icon" data-link="image" data-target="_" style="opacity: 0; top: 40.5%;">
<img src="http://miguras.com/wordpress/fx/wp-content/plugins/FX-Image/images/Magnifying-Glass.png">
</div>
</a>
</div>
 */
jQuery(document).ready(function() {
  //alert( "Captions" );
  jQuery( ".captions").hover(
    function(){
      //jQuery(this).find('span.title').stop().animate({ opacity:0.8}, 200);
      //jQuery(this).find('.fx-gallery-icon').stop().animate({opacity:0.7}, 200).stop().animate({top:'40.5%'}, {duration: 300, queue: false})
		//		jQuery(this).find('.fx-gallery-title').stop().animate({opacity:'0.8'}, {duration: 300, queue: false})
		//jQuery(this).find('p.caption').stop().animate({bottom: '10%', left: '2%'}, {duration: 300, queue: false}).animate( { opacity:0.8 } );
		jQuery(this).find('p.caption').animate( { opacity:0.8 } );
						
    },
    function(){
      // jQuery(this).find('.fx-gallery-overlay').stop().animate({ opacity:0}, 200)
      // jQuery(this).find('.fx-gallery-icon').stop().animate({ top:'-100%'}).stop().animate({ opacity:0}, 200)
		jQuery(this).find('p.caption').stop().animate({ opacity: '0'}) ;
      //.animate( {bottom:'10%'} );
      //jQuery(this).find('span.title').stop().animate({  opacity:'0'})
    }
  );
});
