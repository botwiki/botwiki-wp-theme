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
  },
  show_bot_submission_form_link_admin: function show_bot_submission_form_link_admin() {

    jQuery(function () {
      jQuery("body.post-type-bot .wrap .page-title-action").attr('href', '/submit-your-bot');
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
  _helpers.helpers.show_bot_submission_form_link_admin();
  _screenshotTool.screenshot_tool.init();
})(jQuery);

},{"./helpers.js":1,"./screenshot-tool.js":2}]},{},[3])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvYWRtaW4tc2NyaXB0cy9oZWxwZXJzLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyZWVuc2hvdC10b29sLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyaXB0cy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksVUFBVTtBQUNaLGVBQWEscUJBQVMsUUFBVCxFQUFtQixRQUFuQixFQUE2QjtBQUN4QyxRQUFJLE9BQU8sUUFBUCxFQUFpQixNQUFyQixFQUE2QjtBQUMzQjtBQUNELEtBRkQsTUFFTztBQUNMLFVBQUksVUFBVSxJQUFkOztBQUVBLGlCQUFXLFlBQVc7QUFDcEIsZ0JBQVEsV0FBUixDQUFvQixRQUFwQixFQUE4QixRQUE5QjtBQUNELE9BRkQsRUFFRyxHQUZIO0FBR0Q7QUFDRixHQVhXO0FBWVosNkJBQTJCLHFDQUFVO0FBQ25DLFFBQUksVUFBVSxJQUFkOztBQUVBLFNBQUssV0FBTCxDQUFpQixtQkFBakIsRUFBc0MsWUFBVztBQUMvQztBQUNBLFVBQUksY0FBYyxPQUFPLG1CQUFQLENBQWxCOztBQUVBLFVBQUksWUFBWSxNQUFaLEdBQXFCLENBQXpCLEVBQTJCO0FBQ3pCLG9CQUFZLEtBQVosR0FBb0IsSUFBcEI7QUFDRDtBQUNGLEtBUEQ7QUFRRCxHQXZCVztBQXdCWix1Q0FBcUMsK0NBQVU7O0FBRTdDLFdBQU8sWUFBVTtBQUNiLGFBQU8sNkNBQVAsRUFBc0QsSUFBdEQsQ0FBMkQsTUFBM0QsRUFBbUUsa0JBQW5FO0FBQ0gsS0FGRDtBQUlEO0FBOUJXLENBQWQ7O1FBaUNRLE8sR0FBQSxPOzs7QUNuQ1I7Ozs7O0FBRUEsSUFBSSxrQkFBa0I7QUFDcEIsUUFBTSxnQkFBVTtBQUNkLEtBQUMsVUFBUyxDQUFULEVBQVk7O0FBRVgsUUFBRSxRQUFGLEVBQVksRUFBWixDQUFlLE9BQWYsRUFBd0IsaUJBQXhCLEVBQTJDLFVBQVMsRUFBVCxFQUFZO0FBQ3JELFlBQUksc0JBQXNCLEVBQUUsSUFBRixDQUExQjtBQUFBLFlBQ0kscUJBQXFCLG9CQUFvQixJQUFwQixFQUR6QjtBQUFBLFlBRUksa0JBQWtCLEVBQUUsaUJBQUYsQ0FGdEI7QUFBQSxZQUdJLFlBQVksRUFBRSxXQUFGLENBSGhCO0FBQUEsWUFJSSxXQUFXLFVBQVUsR0FBVixHQUFnQixJQUFoQixFQUpmO0FBQUEsWUFLSSxtQkFBbUIsRUFBRSxrQkFBRixDQUx2Qjs7QUFPQSxZQUFJLFFBQUosRUFBYTtBQUNYLGNBQUksWUFBWSxTQUFTLEtBQVQsQ0FBZSxHQUFmLENBQWhCO0FBQUEsY0FDSSxnQkFBZ0IsVUFBVSxVQUFVLE1BQVYsR0FBbUIsQ0FBN0IsQ0FEcEI7O0FBR0EsOEJBQW9CLElBQXBCLENBQXlCLFlBQXpCLEVBQXVDLElBQXZDLENBQTRDLFVBQTVDLEVBQXdELElBQXhEO0FBQ0EsMEJBQWdCLElBQWhCLENBQXFCO0FBQ25CLG1CQUFPLEVBRFk7QUFFbkIsd0JBQVk7QUFGTyxXQUFyQjtBQUlBLDJCQUFpQixRQUFqQixDQUEwQixRQUExQjs7QUFFQSxZQUFFLElBQUYsQ0FBTztBQUNMLDZEQUErQyxRQUEvQztBQURLLFdBQVAsRUFFRyxJQUZILENBRVEsVUFBVSxJQUFWLEVBQWlCO0FBQ3ZCO0FBQ0EsNkJBQWlCLFdBQWpCLENBQTZCLFFBQTdCO0FBQ0EsZ0NBQW9CLElBQXBCLENBQXlCLGtCQUF6QixFQUE2QyxJQUE3QyxDQUFrRCxVQUFsRCxFQUE4RCxLQUE5RDtBQUNBLDRCQUFnQixJQUFoQixDQUFxQjtBQUNuQixpREFBaUMsS0FBSyxVQUFMLENBQWdCLElBRDlCO0FBRW5CLDBCQUFZO0FBRk8sYUFBckI7QUFJRCxXQVZEO0FBV0QsU0F0QkQsTUF1Qkk7QUFDRixnQkFBTSxxQkFBTjtBQUNEO0FBTUYsT0F2Q0Q7QUF3Q0QsS0ExQ0QsRUEwQ0ksTUExQ0o7QUEyQ0Q7QUE3Q21CLENBQXRCOztRQWlERSxlLEdBQUEsZTs7O0FDbkRGOztBQUVBOztBQUNBOztBQUVBLENBQUMsVUFBUyxDQUFULEVBQVk7O0FBRVgsbUJBQVEseUJBQVI7QUFDQSxtQkFBUSxtQ0FBUjtBQUNBLGtDQUFnQixJQUFoQjtBQUVELENBTkQsRUFNSSxNQU5KIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gZSh0LG4scil7ZnVuY3Rpb24gcyhvLHUpe2lmKCFuW29dKXtpZighdFtvXSl7dmFyIGE9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtpZighdSYmYSlyZXR1cm4gYShvLCEwKTtpZihpKXJldHVybiBpKG8sITApO3ZhciBmPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIrbytcIidcIik7dGhyb3cgZi5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGZ9dmFyIGw9bltvXT17ZXhwb3J0czp7fX07dFtvXVswXS5jYWxsKGwuZXhwb3J0cyxmdW5jdGlvbihlKXt2YXIgbj10W29dWzFdW2VdO3JldHVybiBzKG4/bjplKX0sbCxsLmV4cG9ydHMsZSx0LG4scil9cmV0dXJuIG5bb10uZXhwb3J0c312YXIgaT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2Zvcih2YXIgbz0wO288ci5sZW5ndGg7bysrKXMocltvXSk7cmV0dXJuIHN9KSIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgaGVscGVycyA9IHtcbiAgd2FpdF9mb3JfZWw6IGZ1bmN0aW9uKHNlbGVjdG9yLCBjYWxsYmFjaykge1xuICAgIGlmIChqUXVlcnkoc2VsZWN0b3IpLmxlbmd0aCkge1xuICAgICAgY2FsbGJhY2soKTtcbiAgICB9IGVsc2Uge1xuICAgICAgdmFyIGhlbHBlcnMgPSB0aGlzO1xuXG4gICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkge1xuICAgICAgICBoZWxwZXJzLndhaXRfZm9yX2VsKHNlbGVjdG9yLCBjYWxsYmFjayk7XG4gICAgICB9LCAxMDApO1xuICAgIH1cbiAgfSxcbiAgaGlkZV9kdXBsaWNhdGVfcG9zdHNfbWVudTogZnVuY3Rpb24oKXtcbiAgICB2YXIgaGVscGVycyA9IHRoaXM7XG5cbiAgICB0aGlzLndhaXRfZm9yX2VsKCdbaWQ9XCJtZW51LXBvc3RzXCJdJywgZnVuY3Rpb24oKSB7XG4gICAgICAvKiBRdWljayBmaXggZm9yIGR1cGxpY2F0ZSBQb3N0cyBhcHBlYXJpbmcgaW4gYWRtaW4sIGFmdGVyIG1vZGlmeWluZyBkZWZhdWx0IHBvc3Qgc2x1Zy4gKi9cbiAgICAgIHZhciAkbWVudV9wb3N0cyA9IGpRdWVyeSgnW2lkPVwibWVudS1wb3N0c1wiXScpO1xuXG4gICAgICBpZiAoJG1lbnVfcG9zdHMubGVuZ3RoID4gMSl7XG4gICAgICAgICRtZW51X3Bvc3RzLmZpcnN0KCkuaGlkZSgpOyAgXG4gICAgICB9XG4gICAgfSk7XG4gIH0sXG4gIHNob3dfYm90X3N1Ym1pc3Npb25fZm9ybV9saW5rX2FkbWluOiBmdW5jdGlvbigpe1xuXG4gICAgalF1ZXJ5KGZ1bmN0aW9uKCl7XG4gICAgICAgIGpRdWVyeShcImJvZHkucG9zdC10eXBlLWJvdCAud3JhcCAucGFnZS10aXRsZS1hY3Rpb25cIikuYXR0cignaHJlZicsICcvc3VibWl0LXlvdXItYm90Jyk7XG4gICAgfSk7XG5cbiAgfVxufVxuXG5leHBvcnQge2hlbHBlcnN9O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbnZhciBzY3JlZW5zaG90X3Rvb2wgPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgKGZ1bmN0aW9uKCQpIHtcblxuICAgICAgJChkb2N1bWVudCkub24oJ2NsaWNrJywgJyNnZXQtc2NyZWVuc2hvdCcsIGZ1bmN0aW9uKGV2KXtcbiAgICAgICAgdmFyICRnZXRfc2NyZWVuc2hvdF9idG4gPSAkKHRoaXMpLFxuICAgICAgICAgICAgc2NyZWVuc2hvdF9idG5fdHh0ID0gJGdldF9zY3JlZW5zaG90X2J0bi5odG1sKCksXG4gICAgICAgICAgICAkc2NyZWVuc2hvdF9pbWcgPSAkKCcjc2NyZWVuc2hvdC1pbWcnKSxcbiAgICAgICAgICAgICRwYWdlX3VybCA9ICQoJyNwYWdlLXVybCcpLFxuICAgICAgICAgICAgcGFnZV91cmwgPSAkcGFnZV91cmwudmFsKCkudHJpbSgpLFxuICAgICAgICAgICAgJHNjcmVlbnNob3RfaGludCA9ICQoJyNzY3JlZW5zaG90LWhpbnQnKTtcblxuICAgICAgICBpZiAocGFnZV91cmwpe1xuICAgICAgICAgIHZhciB1cmxfcGFydHMgPSBwYWdlX3VybC5zcGxpdCgnLycpLFxuICAgICAgICAgICAgICBkb3dubG9hZF9uYW1lID0gdXJsX3BhcnRzW3VybF9wYXJ0cy5sZW5ndGggLSAxXTsgXG5cbiAgICAgICAgICAkZ2V0X3NjcmVlbnNob3RfYnRuLmh0bWwoJ1Byb2Nlc3NpbmcnKS5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAgICRzY3JlZW5zaG90X2ltZy5hdHRyKHtcbiAgICAgICAgICAgICdzcmMnOiAnJyxcbiAgICAgICAgICAgICdkb3dubG9hZCc6ICcnXG4gICAgICAgICAgfSk7XG4gICAgICAgICAgJHNjcmVlbnNob3RfaGludC5hZGRDbGFzcygnaGlkZGVuJyk7XG5cbiAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgdXJsOiBgaHR0cHM6Ly9zY3JlZW5zaG90LWJldGEuZ2xpdGNoLm1lLz91cmw9JHtwYWdlX3VybH0md2lkdGg9MTIwMCZoZWlnaHQ9Njg1YFxuICAgICAgICAgIH0pLmRvbmUoZnVuY3Rpb24oIGRhdGEgKSB7XG4gICAgICAgICAgICAvLyBjb25zb2xlLmxvZyhkYXRhKTtcbiAgICAgICAgICAgICRzY3JlZW5zaG90X2hpbnQucmVtb3ZlQ2xhc3MoJ2hpZGRlbicpO1xuICAgICAgICAgICAgJGdldF9zY3JlZW5zaG90X2J0bi5odG1sKHNjcmVlbnNob3RfYnRuX3R4dCkuYXR0cignZGlzYWJsZWQnLCBmYWxzZSk7XG4gICAgICAgICAgICAkc2NyZWVuc2hvdF9pbWcuYXR0cih7XG4gICAgICAgICAgICAgICdzcmMnOiBgZGF0YTppbWFnZS9wbmc7YmFzZTY0LCAke2RhdGEuc2NyZWVuc2hvdC5kYXRhfWAsXG4gICAgICAgICAgICAgICdkb3dubG9hZCc6IGRvd25sb2FkX25hbWVcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH0pOyAgICAgICAgICBcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgIGFsZXJ0KCdZb3UgZm9yZ290IHRoZSBVUkwhJyk7XG4gICAgICAgIH1cblxuXG5cblxuXG4gICAgICB9KTtcbiAgICB9KSggalF1ZXJ5ICk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgc2NyZWVuc2hvdF90b29sXG59O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbmltcG9ydCB7aGVscGVyc30gZnJvbSBcIi4vaGVscGVycy5qc1wiO1xuaW1wb3J0IHtzY3JlZW5zaG90X3Rvb2x9IGZyb20gXCIuL3NjcmVlbnNob3QtdG9vbC5qc1wiO1xuXG4oZnVuY3Rpb24oJCkge1xuXG4gIGhlbHBlcnMuaGlkZV9kdXBsaWNhdGVfcG9zdHNfbWVudSgpO1xuICBoZWxwZXJzLnNob3dfYm90X3N1Ym1pc3Npb25fZm9ybV9saW5rX2FkbWluKCk7XG4gIHNjcmVlbnNob3RfdG9vbC5pbml0KCk7XG5cbn0pKCBqUXVlcnkgKTtcbiJdfQ==
