"use strict";

var helpers = {
  hide_duplicate_posts_menu: function(){
    /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
    var $menu_posts = $('[id="menu-posts"]');

    if ($menu_posts.length > 1){
      $menu_posts.first().hide();  
    }
  }
}

export {helpers};
