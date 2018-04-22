$ = jQuery.noConflict(true);

$(function() {
  'use strict';
  $(window).scroll(function(){
    /* TODO: Legacy script, rewrite with jQuery. */
    var backToTop = document.getElementById('back-to-top'),
        documentScrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

    if (documentScrollTop > (screen.height/2)){
      backToTop.classList.add('slide-up');
      backToTop.classList.remove('slide-down');
    }
    else{
      backToTop.classList.remove('slide-up');
      backToTop.classList.add('slide-down');
    }
  });
});