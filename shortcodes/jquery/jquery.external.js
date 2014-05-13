/**
 * (C) Copyright Bobbing Wide 2013 
 * @link http://www.sitepoint.com/forums/showthread.php?773754-External-link-warning-message-on-all-href-links-EXCEPT-one
 */
(function($) {
   $.fn.easyconfirm = function(options) {
      this.each( function() {
        $(this).click( function() { 
           var link = $(this).attr('href');
           alert( link + "clicked" ); 
        });
      });
      return false;
   };
})(jQuery);


