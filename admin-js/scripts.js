(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var helpers = {
  hide_duplicate_posts_menu: function hide_duplicate_posts_menu() {
    /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
    var $menu_posts = $('[id="menu-posts"]');

    if ($menu_posts.length > 1) {
      $menu_posts.first().hide();
    }
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvYWRtaW4tc2NyaXB0cy9oZWxwZXJzLmpzIiwic3JjL2FkbWluLXNjcmlwdHMvc2NyaXB0cy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksVUFBVTtBQUNaLDZCQUEyQixxQ0FBVTtBQUNuQztBQUNBLFFBQUksY0FBYyxFQUFFLG1CQUFGLENBQWxCOztBQUVBLFFBQUksWUFBWSxNQUFaLEdBQXFCLENBQXpCLEVBQTJCO0FBQ3pCLGtCQUFZLEtBQVosR0FBb0IsSUFBcEI7QUFDRDtBQUNGO0FBUlcsQ0FBZDs7UUFXUSxPLEdBQUEsTzs7O0FDYlI7O0FBRUE7O0FBRUEsQ0FBQyxVQUFTLENBQVQsRUFBWTs7QUFFWCxtQkFBUSx5QkFBUjtBQUVELENBSkQsRUFJSSxNQUpKIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gZSh0LG4scil7ZnVuY3Rpb24gcyhvLHUpe2lmKCFuW29dKXtpZighdFtvXSl7dmFyIGE9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtpZighdSYmYSlyZXR1cm4gYShvLCEwKTtpZihpKXJldHVybiBpKG8sITApO3ZhciBmPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIrbytcIidcIik7dGhyb3cgZi5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGZ9dmFyIGw9bltvXT17ZXhwb3J0czp7fX07dFtvXVswXS5jYWxsKGwuZXhwb3J0cyxmdW5jdGlvbihlKXt2YXIgbj10W29dWzFdW2VdO3JldHVybiBzKG4/bjplKX0sbCxsLmV4cG9ydHMsZSx0LG4scil9cmV0dXJuIG5bb10uZXhwb3J0c312YXIgaT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2Zvcih2YXIgbz0wO288ci5sZW5ndGg7bysrKXMocltvXSk7cmV0dXJuIHN9KSIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgaGVscGVycyA9IHtcbiAgaGlkZV9kdXBsaWNhdGVfcG9zdHNfbWVudTogZnVuY3Rpb24oKXtcbiAgICAvKiBRdWljayBmaXggZm9yIGR1cGxpY2F0ZSBQb3N0cyBhcHBlYXJpbmcgaW4gYWRtaW4sIGFmdGVyIG1vZGlmeWluZyBkZWZhdWx0IHBvc3Qgc2x1Zy4gKi9cbiAgICB2YXIgJG1lbnVfcG9zdHMgPSAkKCdbaWQ9XCJtZW51LXBvc3RzXCJdJyk7XG5cbiAgICBpZiAoJG1lbnVfcG9zdHMubGVuZ3RoID4gMSl7XG4gICAgICAkbWVudV9wb3N0cy5maXJzdCgpLmhpZGUoKTsgIFxuICAgIH1cbiAgfVxufVxuXG5leHBvcnQge2hlbHBlcnN9O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbmltcG9ydCB7aGVscGVyc30gZnJvbSBcIi4vaGVscGVycy5qc1wiO1xuXG4oZnVuY3Rpb24oJCkge1xuXG4gIGhlbHBlcnMuaGlkZV9kdXBsaWNhdGVfcG9zdHNfbWVudSgpOyAgXG5cbn0pKCBqUXVlcnkgKTtcbiJdfQ==
