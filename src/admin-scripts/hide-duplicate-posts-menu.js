( function( $ ) {
    'use strict';
    $(window).load(function() {
      window.waitForElement = function(selector, callback) {
        if ($(selector).length) {
          callback();
        } else {
          var helpers = this;

          setTimeout(function() {
            window.waitForElement(selector, callback);
          }, 100);
        }
      };

      window.waitForElement('[id="menu-posts"]', function() {
        /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
        var $menu_posts = $('[id="menu-posts"]');

        if ($menu_posts.length > 1){
          $menu_posts.first().hide();  
        }
      });
    });
} )( jQuery );
