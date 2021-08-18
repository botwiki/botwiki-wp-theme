$ = jQuery.noConflict(true);

$(function() {
  let $forms = $( 'form' );
  if ( $forms && $forms.length ){
    window.isSubmittingForm = false;

    $forms.submit( function( ev ) {
      window.isSubmittingForm = true;
    } );

    $( window ).bind( 'beforeunload', function(){
      if ( !window.isSubmittingForm ){
        return 'Are you sure you want to leave?';
      }
    } );
  }
});