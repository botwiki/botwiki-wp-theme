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

var _helpers = require("./helpers.js");

(function ($) {

  _helpers.helpers.hide_duplicate_posts_menu();
})(jQuery);

},{"./helpers.js":1}]},{},[2])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvYWRtaW4tc2NyaXB0cy9oZWxwZXJzLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyaXB0cy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksVUFBVTtBQUNaLGVBQWEscUJBQVMsUUFBVCxFQUFtQixRQUFuQixFQUE2QjtBQUN4QyxRQUFJLE9BQU8sUUFBUCxFQUFpQixNQUFyQixFQUE2QjtBQUMzQjtBQUNELEtBRkQsTUFFTztBQUNMLFVBQUksVUFBVSxJQUFkOztBQUVBLGlCQUFXLFlBQVc7QUFDcEIsZ0JBQVEsV0FBUixDQUFvQixRQUFwQixFQUE4QixRQUE5QjtBQUNELE9BRkQsRUFFRyxHQUZIO0FBR0Q7QUFDRixHQVhXO0FBWVosNkJBQTJCLHFDQUFVO0FBQ25DLFFBQUksVUFBVSxJQUFkOztBQUVBLFNBQUssV0FBTCxDQUFpQixtQkFBakIsRUFBc0MsWUFBVztBQUMvQztBQUNBLFVBQUksY0FBYyxPQUFPLG1CQUFQLENBQWxCOztBQUVBLFVBQUksWUFBWSxNQUFaLEdBQXFCLENBQXpCLEVBQTJCO0FBQ3pCLG9CQUFZLEtBQVosR0FBb0IsSUFBcEI7QUFDRDtBQUNGLEtBUEQ7QUFRRDtBQXZCVyxDQUFkOztRQTBCUSxPLEdBQUEsTzs7O0FDNUJSOztBQUVBOztBQUVBLENBQUMsVUFBUyxDQUFULEVBQVk7O0FBRVgsbUJBQVEseUJBQVI7QUFFRCxDQUpELEVBSUksTUFKSiIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIGhlbHBlcnMgPSB7XG4gIHdhaXRfZm9yX2VsOiBmdW5jdGlvbihzZWxlY3RvciwgY2FsbGJhY2spIHtcbiAgICBpZiAoalF1ZXJ5KHNlbGVjdG9yKS5sZW5ndGgpIHtcbiAgICAgIGNhbGxiYWNrKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHZhciBoZWxwZXJzID0gdGhpcztcblxuICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcbiAgICAgICAgaGVscGVycy53YWl0X2Zvcl9lbChzZWxlY3RvciwgY2FsbGJhY2spO1xuICAgICAgfSwgMTAwKTtcbiAgICB9XG4gIH0sXG4gIGhpZGVfZHVwbGljYXRlX3Bvc3RzX21lbnU6IGZ1bmN0aW9uKCl7XG4gICAgdmFyIGhlbHBlcnMgPSB0aGlzO1xuXG4gICAgdGhpcy53YWl0X2Zvcl9lbCgnW2lkPVwibWVudS1wb3N0c1wiXScsIGZ1bmN0aW9uKCkge1xuICAgICAgLyogUXVpY2sgZml4IGZvciBkdXBsaWNhdGUgUG9zdHMgYXBwZWFyaW5nIGluIGFkbWluLCBhZnRlciBtb2RpZnlpbmcgZGVmYXVsdCBwb3N0IHNsdWcuICovXG4gICAgICB2YXIgJG1lbnVfcG9zdHMgPSBqUXVlcnkoJ1tpZD1cIm1lbnUtcG9zdHNcIl0nKTtcblxuICAgICAgaWYgKCRtZW51X3Bvc3RzLmxlbmd0aCA+IDEpe1xuICAgICAgICAkbWVudV9wb3N0cy5maXJzdCgpLmhpZGUoKTsgIFxuICAgICAgfVxuICAgIH0pO1xuICB9XG59XG5cbmV4cG9ydCB7aGVscGVyc307XG4iLCJcInVzZSBzdHJpY3RcIjtcblxuaW1wb3J0IHtoZWxwZXJzfSBmcm9tIFwiLi9oZWxwZXJzLmpzXCI7XG5cbihmdW5jdGlvbigkKSB7XG5cbiAgaGVscGVycy5oaWRlX2R1cGxpY2F0ZV9wb3N0c19tZW51KCk7ICBcblxufSkoIGpRdWVyeSApO1xuIl19
