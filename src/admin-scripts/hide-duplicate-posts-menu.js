( function( $ ) {
    'use strict';
    $(window).load(function() {
      function waitForElement(selector, callback) {
        if ($(selector).length) {
          callback();
        } else {
          var helpers = this;

          setTimeout(function() {
            helpers.wait_for_el(selector, callback);
          }, 100);
        }
      };

      waitForElement('[id="menu-posts"]', function() {
        /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
        var $menu_posts = $('[id="menu-posts"]');

        if ($menu_posts.length > 1){
          $menu_posts.first().hide();  
        }
      });
    });
} )( jQuery );
