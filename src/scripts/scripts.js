"use strict";

/* Legacy scripts */

import ready from "./ready.js";
import {smooth_scroll_fn} from "./smooth-scroll.js";
import {unshift_elements} from "./helpers.js";
import {lazyLoaderFn} from "./lazy-load-images.js";

ready(function(){
  var articleImages = document.querySelectorAll('img.wp-post-image');
  
  for (var i = 0, j = articleImages.length; i < j; i++){
    articleImages[i].dataset.src = articleImages[i].src;
    // articleImages[i].classList.add('lazy-load');
  }

  unshift_elements(document.getElementsByClassName('shifted'));
  smooth_scroll_fn();
  lazyLoaderFn().init();
});

window.onscroll=function(){
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
};

/* End of legacy scripts */

(function($) {
  var $body = $('body'),
      $menu_toggle = $('#menu-toggle');

  $(document).on('click', '#menu-icon', function(ev){
    var $menu_icon = $menu_icon || $('#menu-icon');

    console.log($menu_toggle);

    if ($('#menu-toggle').is(':checked') === true){
      console.log('1');
      $('body').removeClass('menu-open');
      $menu_icon.html('☰').removeClass('mr-3 mt-0');
    }
    else{
      console.log('0');      
      $('body').addClass('menu-open');      
      $menu_icon.html('×').addClass('mr-3 mt-0');
    }
  });
  
})( jQuery );