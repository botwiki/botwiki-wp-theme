$(function() {
  let $forms = $( 'form[id^="submit-"' );
  if ( $forms && $forms.length ){
    window.formWasEdited = false;
    window.isSubmittingForm = false;

    $forms.find( 'input' ).blur(function(){
      window.formWasEdited = true;
    } );

    $forms.submit( function( ev ) {
      window.isSubmittingForm = true;
    } );

    $( window ).bind( 'beforeunload', function(){
      if ( window.formWasEdited && !window.isSubmittingForm ){
        return 'Are you sure you want to leave?';
      }
    } );
  }
});