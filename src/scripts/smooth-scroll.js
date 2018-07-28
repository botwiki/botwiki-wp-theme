//$ = jQuery.noConflict(true);
// Source: https://css-tricks.com/snippets/jquery/smooth-scrolling/
$(function() {
  'use strict';
  $('a[href*="#"]')
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    if (
      location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') 
      && 
      location.hostname === this.hostname
    ) {
      var target = $(this.hash);

      if (window.history && window.history.pushState && target.selector){
        if (target.selector === '#header'){
          history.pushState(null, null, window.location.pathname);
        }
        else{
          history.pushState(null, null, target.selector);
        }
      }

      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 500, function() {
          var $target = $(target);
          $target.focus();
          if ($target.is(':focus')) {
            return false;
          } else {
            $target.attr('tabindex','-1');
            $target.focus();
          }
        });
      }
    }
  });
});