(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var helpers = {
  wait_for_el: function wait_for_el(selector, callback) {
    if (jQuery(selector).length) {
      callback();
    } else {
      var helpers = this;

      setTimeout(function () {
        helpers.wait_for_el(selector, callback);
      }, 100);
    }
  },
  hide_duplicate_posts_menu: function hide_duplicate_posts_menu() {
    var helpers = this;

    this.wait_for_el('[id="menu-posts"]', function () {
      /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
      var $menu_posts = jQuery('[id="menu-posts"]');

      if ($menu_posts.length > 1) {
        $menu_posts.first().hide();
      }
    });
  }
};

exports.helpers = helpers;

},{}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var screenshot_tool = {
  init: function init() {
    (function ($) {

      $(document).on('click', '#get-screenshot', function (ev) {
        var $get_screenshot_btn = $(this),
            screenshot_btn_txt = $get_screenshot_btn.html(),
            $screenshot_img = $('#screenshot-img'),
            $page_url = $('#page-url'),
            page_url = $page_url.val().trim(),
            $screenshot_hint = $('#screenshot-hint');

        if (page_url) {
          var url_parts = page_url.split('/'),
              download_name = url_parts[url_parts.length - 1];

          $get_screenshot_btn.html('Processing').attr('disabled', true);
          $screenshot_img.attr({
            'src': '',
            'download': ''
          });
          $screenshot_hint.addClass('hidden');

          $.ajax({
            url: 'https://screenshot-beta.glitch.me/?url=' + page_url + '&width=1200&height=685'
          }).done(function (data) {
            // console.log(data);
            $screenshot_hint.removeClass('hidden');
            $get_screenshot_btn.html(screenshot_btn_txt).attr('disabled', false);
            $screenshot_img.attr({
              'src': 'data:image/png;base64, ' + data.screenshot.data,
              'download': download_name
            });
          });
        } else {
          alert('You forgot the URL!');
        }
      });
    })(jQuery);
  }
};

exports.screenshot_tool = screenshot_tool;

},{}],3:[function(require,module,exports){
"use strict";

var _helpers = require("./helpers.js");

var _screenshotTool = require("./screenshot-tool.js");

(function ($) {

  _helpers.helpers.hide_duplicate_posts_menu();
  _screenshotTool.screenshot_tool.init();
})(jQuery);

},{"./helpers.js":1,"./screenshot-tool.js":2}]},{},[3])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvYWRtaW4tc2NyaXB0cy9oZWxwZXJzLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyZWVuc2hvdC10b29sLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyaXB0cy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksVUFBVTtBQUNaLGVBQWEscUJBQVMsUUFBVCxFQUFtQixRQUFuQixFQUE2QjtBQUN4QyxRQUFJLE9BQU8sUUFBUCxFQUFpQixNQUFyQixFQUE2QjtBQUMzQjtBQUNELEtBRkQsTUFFTztBQUNMLFVBQUksVUFBVSxJQUFkOztBQUVBLGlCQUFXLFlBQVc7QUFDcEIsZ0JBQVEsV0FBUixDQUFvQixRQUFwQixFQUE4QixRQUE5QjtBQUNELE9BRkQsRUFFRyxHQUZIO0FBR0Q7QUFDRixHQVhXO0FBWVosNkJBQTJCLHFDQUFVO0FBQ25DLFFBQUksVUFBVSxJQUFkOztBQUVBLFNBQUssV0FBTCxDQUFpQixtQkFBakIsRUFBc0MsWUFBVztBQUMvQztBQUNBLFVBQUksY0FBYyxPQUFPLG1CQUFQLENBQWxCOztBQUVBLFVBQUksWUFBWSxNQUFaLEdBQXFCLENBQXpCLEVBQTJCO0FBQ3pCLG9CQUFZLEtBQVosR0FBb0IsSUFBcEI7QUFDRDtBQUNGLEtBUEQ7QUFRRDtBQXZCVyxDQUFkOztRQTBCUSxPLEdBQUEsTzs7O0FDNUJSOzs7OztBQUVBLElBQUksa0JBQWtCO0FBQ3BCLFFBQU0sZ0JBQVU7QUFDZCxLQUFDLFVBQVMsQ0FBVCxFQUFZOztBQUVYLFFBQUUsUUFBRixFQUFZLEVBQVosQ0FBZSxPQUFmLEVBQXdCLGlCQUF4QixFQUEyQyxVQUFTLEVBQVQsRUFBWTtBQUNyRCxZQUFJLHNCQUFzQixFQUFFLElBQUYsQ0FBMUI7QUFBQSxZQUNJLHFCQUFxQixvQkFBb0IsSUFBcEIsRUFEekI7QUFBQSxZQUVJLGtCQUFrQixFQUFFLGlCQUFGLENBRnRCO0FBQUEsWUFHSSxZQUFZLEVBQUUsV0FBRixDQUhoQjtBQUFBLFlBSUksV0FBVyxVQUFVLEdBQVYsR0FBZ0IsSUFBaEIsRUFKZjtBQUFBLFlBS0ksbUJBQW1CLEVBQUUsa0JBQUYsQ0FMdkI7O0FBT0EsWUFBSSxRQUFKLEVBQWE7QUFDWCxjQUFJLFlBQVksU0FBUyxLQUFULENBQWUsR0FBZixDQUFoQjtBQUFBLGNBQ0ksZ0JBQWdCLFVBQVUsVUFBVSxNQUFWLEdBQW1CLENBQTdCLENBRHBCOztBQUdBLDhCQUFvQixJQUFwQixDQUF5QixZQUF6QixFQUF1QyxJQUF2QyxDQUE0QyxVQUE1QyxFQUF3RCxJQUF4RDtBQUNBLDBCQUFnQixJQUFoQixDQUFxQjtBQUNuQixtQkFBTyxFQURZO0FBRW5CLHdCQUFZO0FBRk8sV0FBckI7QUFJQSwyQkFBaUIsUUFBakIsQ0FBMEIsUUFBMUI7O0FBRUEsWUFBRSxJQUFGLENBQU87QUFDTCw2REFBK0MsUUFBL0M7QUFESyxXQUFQLEVBRUcsSUFGSCxDQUVRLFVBQVUsSUFBVixFQUFpQjtBQUN2QjtBQUNBLDZCQUFpQixXQUFqQixDQUE2QixRQUE3QjtBQUNBLGdDQUFvQixJQUFwQixDQUF5QixrQkFBekIsRUFBNkMsSUFBN0MsQ0FBa0QsVUFBbEQsRUFBOEQsS0FBOUQ7QUFDQSw0QkFBZ0IsSUFBaEIsQ0FBcUI7QUFDbkIsaURBQWlDLEtBQUssVUFBTCxDQUFnQixJQUQ5QjtBQUVuQiwwQkFBWTtBQUZPLGFBQXJCO0FBSUQsV0FWRDtBQVdELFNBdEJELE1BdUJJO0FBQ0YsZ0JBQU0scUJBQU47QUFDRDtBQU1GLE9BdkNEO0FBd0NELEtBMUNELEVBMENJLE1BMUNKO0FBMkNEO0FBN0NtQixDQUF0Qjs7UUFpREUsZSxHQUFBLGU7OztBQ25ERjs7QUFFQTs7QUFDQTs7QUFFQSxDQUFDLFVBQVMsQ0FBVCxFQUFZOztBQUVYLG1CQUFRLHlCQUFSO0FBQ0Esa0NBQWdCLElBQWhCO0FBRUQsQ0FMRCxFQUtJLE1BTEoiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbnZhciBoZWxwZXJzID0ge1xuICB3YWl0X2Zvcl9lbDogZnVuY3Rpb24oc2VsZWN0b3IsIGNhbGxiYWNrKSB7XG4gICAgaWYgKGpRdWVyeShzZWxlY3RvcikubGVuZ3RoKSB7XG4gICAgICBjYWxsYmFjaygpO1xuICAgIH0gZWxzZSB7XG4gICAgICB2YXIgaGVscGVycyA9IHRoaXM7XG5cbiAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKSB7XG4gICAgICAgIGhlbHBlcnMud2FpdF9mb3JfZWwoc2VsZWN0b3IsIGNhbGxiYWNrKTtcbiAgICAgIH0sIDEwMCk7XG4gICAgfVxuICB9LFxuICBoaWRlX2R1cGxpY2F0ZV9wb3N0c19tZW51OiBmdW5jdGlvbigpe1xuICAgIHZhciBoZWxwZXJzID0gdGhpcztcblxuICAgIHRoaXMud2FpdF9mb3JfZWwoJ1tpZD1cIm1lbnUtcG9zdHNcIl0nLCBmdW5jdGlvbigpIHtcbiAgICAgIC8qIFF1aWNrIGZpeCBmb3IgZHVwbGljYXRlIFBvc3RzIGFwcGVhcmluZyBpbiBhZG1pbiwgYWZ0ZXIgbW9kaWZ5aW5nIGRlZmF1bHQgcG9zdCBzbHVnLiAqL1xuICAgICAgdmFyICRtZW51X3Bvc3RzID0galF1ZXJ5KCdbaWQ9XCJtZW51LXBvc3RzXCJdJyk7XG5cbiAgICAgIGlmICgkbWVudV9wb3N0cy5sZW5ndGggPiAxKXtcbiAgICAgICAgJG1lbnVfcG9zdHMuZmlyc3QoKS5oaWRlKCk7ICBcbiAgICAgIH1cbiAgICB9KTtcbiAgfVxufVxuXG5leHBvcnQge2hlbHBlcnN9O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbnZhciBzY3JlZW5zaG90X3Rvb2wgPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgKGZ1bmN0aW9uKCQpIHtcblxuICAgICAgJChkb2N1bWVudCkub24oJ2NsaWNrJywgJyNnZXQtc2NyZWVuc2hvdCcsIGZ1bmN0aW9uKGV2KXtcbiAgICAgICAgdmFyICRnZXRfc2NyZWVuc2hvdF9idG4gPSAkKHRoaXMpLFxuICAgICAgICAgICAgc2NyZWVuc2hvdF9idG5fdHh0ID0gJGdldF9zY3JlZW5zaG90X2J0bi5odG1sKCksXG4gICAgICAgICAgICAkc2NyZWVuc2hvdF9pbWcgPSAkKCcjc2NyZWVuc2hvdC1pbWcnKSxcbiAgICAgICAgICAgICRwYWdlX3VybCA9ICQoJyNwYWdlLXVybCcpLFxuICAgICAgICAgICAgcGFnZV91cmwgPSAkcGFnZV91cmwudmFsKCkudHJpbSgpLFxuICAgICAgICAgICAgJHNjcmVlbnNob3RfaGludCA9ICQoJyNzY3JlZW5zaG90LWhpbnQnKTtcblxuICAgICAgICBpZiAocGFnZV91cmwpe1xuICAgICAgICAgIHZhciB1cmxfcGFydHMgPSBwYWdlX3VybC5zcGxpdCgnLycpLFxuICAgICAgICAgICAgICBkb3dubG9hZF9uYW1lID0gdXJsX3BhcnRzW3VybF9wYXJ0cy5sZW5ndGggLSAxXTsgXG5cbiAgICAgICAgICAkZ2V0X3NjcmVlbnNob3RfYnRuLmh0bWwoJ1Byb2Nlc3NpbmcnKS5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAgICRzY3JlZW5zaG90X2ltZy5hdHRyKHtcbiAgICAgICAgICAgICdzcmMnOiAnJyxcbiAgICAgICAgICAgICdkb3dubG9hZCc6ICcnXG4gICAgICAgICAgfSk7XG4gICAgICAgICAgJHNjcmVlbnNob3RfaGludC5hZGRDbGFzcygnaGlkZGVuJyk7XG5cbiAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgdXJsOiBgaHR0cHM6Ly9zY3JlZW5zaG90LWJldGEuZ2xpdGNoLm1lLz91cmw9JHtwYWdlX3VybH0md2lkdGg9MTIwMCZoZWlnaHQ9Njg1YFxuICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24oIGRhdGEgKSB7XG4gICAgICAgICAgICAvLyBjb25zb2xlLmxvZyhkYXRhKTtcbiAgICAgICAgICAgICRzY3JlZW5zaG90X2hpbnQucmVtb3ZlQ2xhc3MoJ2hpZGRlbicpO1xuICAgICAgICAgICAgJGdldF9zY3JlZW5zaG90X2J0bi5odG1sKHNjcmVlbnNob3RfYnRuX3R4dCkuYXR0cignZGlzYWJsZWQnLCBmYWxzZSk7XG4gICAgICAgICAgICAkc2NyZWVuc2hvdF9pbWcuYXR0cih7XG4gICAgICAgICAgICAgICdzcmMnOiBgZGF0YTppbWFnZS9wbmc7YmFzZTY0LCAke2RhdGEuc2NyZWVuc2hvdC5kYXRhfWAsXG4gICAgICAgICAgICAgICdkb3dubG9hZCc6IGRvd25sb2FkX25hbWVcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH0pOyAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgIGFsZXJ0KCdZb3UgZm9yZ290IHRoZSBVUkwhJyk7XG4gICAgICAgIH1cblxuXG5cblxuXG4gICAgICB9KTtcbiAgICB9KSggalF1ZXJ5ICk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgc2NyZWVuc2hvdF90b29sXG59O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbmltcG9ydCB7aGVscGVyc30gZnJvbSBcIi4vaGVscGVycy5qc1wiO1xuaW1wb3J0IHtzY3JlZW5zaG90X3Rvb2x9IGZyb20gXCIuL3NjcmVlbnNob3QtdG9vbC5qc1wiO1xuXG4oZnVuY3Rpb24oJCkge1xuXG4gIGhlbHBlcnMuaGlkZV9kdXBsaWNhdGVfcG9zdHNfbWVudSgpO1xuICBzY3JlZW5zaG90X3Rvb2wuaW5pdCgpO1xuXG59KSggalF1ZXJ5ICk7XG4iXX0=
