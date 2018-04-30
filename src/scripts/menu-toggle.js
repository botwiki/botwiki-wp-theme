$(function() {
  'use strict';
  var $body = $('body'),
      $menu_toggle = $('#menu-toggle');

  $('#menu-icon').on('click', function(ev){
    var $menu_icon = $menu_icon || $('#menu-icon');

    if ($('#menu-toggle').is(':checked') === true){
      $('body').removeClass('menu-open');
      $menu_icon.html('b').removeClass('mr-3 mt-0');
    }
    else{
      $('body').addClass('menu-open');      
      $menu_icon.html('×').addClass('mr-3 mt-0');
    }
  });
});
