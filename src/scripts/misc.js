$(function() {
  'use strict';
  
  /* Close site menu with Escape key. */

  let $menuToggle = $( '#menu-toggle' );
  $( document ).keyup( function( ev ) {
    if ( ev.key === 'Escape' ) {
      if ( $menuToggle.is( ':checked') ){
          $menuToggle.click();
      }
    }
  });
});
