// closure to avoid namespace collision
(function($) {       
  //$( 'div.art-header img.bw_logo' ).css( 'height', '125px' );
  //$( 'div.art-header a.bw_logo img.bw_logo' ).bind( 'click', function() { alert( "clicked" ); });
  
  //var vp = $(this).width();
  //vp += ' ' + $(this).height();
  //alert( vp );

  $(window).bind( 'resize', resizebwlogo );
  //$(this).bind( resize, function() { alert( "resizebwlogo" );  });

   //  resizebwlogo );

  //$( 'div.art-header img.bw_logo' ).bind( click, resizebwlogo );
  //this.viewportreport();

  function resizebwlogo() {
     //alert( "resize what was the blackness" );
     var ahheight = $( 'div.art-header' ).height();
     ahheight -= 20;
     var twwidth = $('div.art-header div.textwidget').width(); 
     var newheight = Math.min( ahheight, twwidth );
    
     $( 'div.art-header img.bw_logo' ).height( newheight );
     //.attr( "title", newheight );
    
  }



       //.datepicker("option", "dateFormat", "yy-mm-dd" );

//  function resizebwlogo() { 
//    alert( $(this).height );
  //function viewportreport( ) {
  //  var vp = $(this).height();
  //  vp += $(this).width();
  //  alert( vp );
  //}
}) (jQuery);
