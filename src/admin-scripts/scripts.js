"use strict";

import ready from "./ready.js";
import {smooth_scroll_fn} from "./smooth-scroll.js";
import {unshift_elements} from "./helpers.js";

ready(function(){
  unshift_elements(document.getElementsByClassName('shifted'));
  smooth_scroll_fn();
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