/**
 * (C) Copyright Bobbing Wide 2013
 * Simple jQuery to set links in the oik-nivo-slider to open in a new window.
 * Adds target="_blank" to any anchor tag (a) within the class of .nivoSlider
 */
jQuery(document).ready(function() {
	jQuery(".nivoSlider a").attr("target","_blank");
});
