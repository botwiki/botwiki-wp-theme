"use strict";

var menu_toggle = {
  init: function(){
    var $body = $('body'),
        $menu_toggle = $('#menu-toggle');

    $('#menu-icon').on('click', function(ev){
      var $menu_icon = $menu_icon || $('#menu-icon');

      console.log($menu_toggle);

      if ($('#menu-toggle').is(':checked') === true){
        console.log('1');
        $('body').removeClass('menu-open');
        $menu_icon.html('b').removeClass('mr-3 mt-0');
      }
      else{
        console.log('0');      
        $('body').addClass('menu-open');      
        $menu_icon.html('×').addClass('mr-3 mt-0');
      }
    });
  }
}

export {
  menu_toggle
};




