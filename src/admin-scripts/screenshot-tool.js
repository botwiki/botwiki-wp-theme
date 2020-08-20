( function( $ ){
  'use strict';
  $( document ).on( 'click', '#get-screenshot', function( ev ){
    var $screenshotBtn = $( this ),
        screenshotBtnText = $screenshotBtn.html( ),
        $screenshotImg = $( '#screenshot-img' ),
        $pageUrlInput = $( '#page-url' ),
        pageUrl = $pageUrlInput.val( ).trim( ),
        $screenshot_hint = $( '#screenshot-hint' );

    if ( pageUrl ){
      var url_parts = pageUrl.split( '/' ),
          download_name = url_parts[url_parts.length - 1]; 

      $screenshotBtn.html( 'Processing' ).attr( 'disabled', true );
      $screenshotImg.attr( {
        'src': '',
        'download': ''
      } );
      $screenshot_hint.addClass( 'hidden' );

      // $.ajax( {  
      //   url: `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${ pageUrl }` 
      // } ).done( function( data ) {  
      //   // console.log( data ); 
      //   console.log( data.lighthouseResult.audits['final-screenshot'].details );
      //   $screenshot_hint.removeClass( 'hidden' );
      //   $screenshotBtn.html( screenshotBtnText ).attr( 'disabled', false ); 
      //   $screenshotImg.attr( {  
      //     // 'src': `data:image/png;base64, ${ data.screenshot }`,  
      //     'src': data.lighthouseResult.audits['final-screenshot'].details.data,
      //     'download': download_name 
      //   } );
      // } );
      $.post( ajaxurl, {
        action: 'make_screenshot',
        url: pageUrl
      } ).done( function( data ) {
        // console.log( data );
        $screenshot_hint.removeClass( 'hidden' );
        $screenshotBtn.html( screenshotBtnText ).attr( 'disabled', false );
        $screenshotImg.attr( {
          'src': data.image_url,
          'download': download_name
        } );
      } );
    }
    else{
      alert( 'You forgot the URL!' );
    }
  } );
} )( jQuery );
