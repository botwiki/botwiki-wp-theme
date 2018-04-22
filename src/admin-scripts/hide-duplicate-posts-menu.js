$(function() {
  'use strict';

  function wait_for_el(selector, callback) {
    if ($(selector).length) {
      callback();
    } else {
      var helpers = this;

      setTimeout(function() {
        helpers.wait_for_el(selector, callback);
      }, 100);
    }
  };

  wait_for_el('[id="menu-posts"]', function() {
    /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
    var $menu_posts = $('[id="menu-posts"]');

    if ($menu_posts.length > 1){
      $menu_posts.first().hide();  
    }
  });

});
