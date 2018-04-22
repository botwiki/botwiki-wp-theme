$(function() {
  'use strict';
    $('.expand-image').click(function(ev){
      ev.preventDefault();
      $(this).toggleClass('image-expanded');
      return false;
    });
});
