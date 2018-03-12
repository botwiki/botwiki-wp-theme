"use strict";

$ = jQuery.noConflict(true);

import {search} from "./search.js";
import {menu_toggle} from "./menu-toggle.js";
import {back_to_top} from "./back-to-top.js";
import {bot_submission} from "./submit-your-bot.js";

/* TODO: Legacy code, needs a cleanup. */

import {smooth_scroll_fn} from "./smooth-scroll.js";
import {unshift_elements} from "./helpers.js";
import {lazyLoaderFn} from "./lazy-load-images.js";

/* END TODO */


$(function() {
  search.init();
  menu_toggle.init();
  back_to_top.init();
  bot_submission.init();







  /* TODO: Legacy code, needs a cleanup. */

  var articleImages = document.querySelectorAll('img.wp-post-image');
  
  for (var i = 0, j = articleImages.length; i < j; i++){
    articleImages[i].dataset.src = articleImages[i].src;
    // articleImages[i].classList.add('lazy-load');
  }

  unshift_elements(document.getElementsByClassName('shifted'));
  smooth_scroll_fn();
  lazyLoaderFn().init();
});
