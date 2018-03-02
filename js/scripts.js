(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
function unshift_elements(shifted_els) {
  if (shifted_els.length > 0) {
    Array.prototype.forEach.call(shifted_els, function (el, index) {
      setTimeout(function () {
        el.classList.remove('shifted');
        el.classList.add('unshifted');
      }, 55 * index * index);
    });
  }
}

exports.unshift_elements = unshift_elements;

},{}],2:[function(require,module,exports){
"use strict";
/*
  Lazy Load Images without jQuery
  http://kaizau.github.com/Lazy-Load-Images-without-jQuery/
  Original by Mike Pulaski - http://www.mikepulaski.com
  Modified by Kai Zau - http://kaizau.com
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});
function lazyLoaderFn() {
  var addEventListener = window.addEventListener || function (n, f) {
    window.attachEvent('on' + n, f);
  },
      removeEventListener = window.removeEventListener || function (n, f, b) {
    window.detachEvent('on' + n, f);
  };

  // For IE7 compatibility
  // Adapted from http://www.quirksmode.org/js/findpos.html
  function getOffsetTop(el) {
    var val = 0;
    if (el.offsetParent) {
      do {
        val += el.offsetTop;
      } while (el = el.offsetParent);
      return val;
    }
  }

  var lazyLoader = {
    cache: [],
    mobileScreenSize: 500,
    //tinyGif: 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',

    addObservers: function addObservers() {
      addEventListener('scroll', lazyLoader.throttledLoad);
      addEventListener('resize', lazyLoader.throttledLoad);
    },

    removeObservers: function removeObservers() {
      removeEventListener('scroll', lazyLoader.throttledLoad, false);
      removeEventListener('resize', lazyLoader.throttledLoad, false);
    },

    throttleTimer: new Date().getTime(),

    throttledLoad: function throttledLoad() {
      var now = new Date().getTime();
      if (now - lazyLoader.throttleTimer >= 200) {
        lazyLoader.throttleTimer = now;
        lazyLoader.loadVisibleImages();
      }
    },

    loadVisibleImages: function loadVisibleImages() {
      var scrollY = window.pageYOffset || document.documentElement.scrollTop;
      var pageHeight = window.innerHeight || document.documentElement.clientHeight;
      var range = {
        min: scrollY - 200,
        max: scrollY + pageHeight + 200
      };

      var i = 0;
      while (i < lazyLoader.cache.length) {
        var image = lazyLoader.cache[i];
        var imagePosition = getOffsetTop(image);
        var imageHeight = image.height || 0;

        if (imagePosition >= range.min - imageHeight && imagePosition <= range.max) {
          var mobileSrc = image.getAttribute('data-src-mobile');

          // image.onload = function() {
          //   this.className = this.className.replace(/(^|\s+)lazy-load(\s+|$)/, '$1lazy-loaded$2');
          // };
          //        Temporary fix for Safari!

          image.className = image.className.replace(/(^|\s+)lazy-load(\s+|$)/, '$1lazy-loaded$2');

          if (mobileSrc && screen.width <= lazyLoader.mobileScreenSize) {
            image.src = mobileSrc;
          } else {
            image.src = image.getAttribute('data-src');
          }

          image.removeAttribute('data-src');
          image.removeAttribute('data-src-mobile');

          lazyLoader.cache.splice(i, 1);
          continue;
        }

        i++;
      }

      if (lazyLoader.cache.length === 0) {
        lazyLoader.removeObservers();
      }
    },

    init: function init() {
      // Patch IE7- (querySelectorAll)
      if (!document.querySelectorAll) {
        document.querySelectorAll = function (selector) {
          var doc = document,
              head = doc.documentElement.firstChild,
              styleTag = doc.createElement('STYLE');
          head.appendChild(styleTag);
          doc.__qsaels = [];
          styleTag.styleSheet.cssText = selector + '{x:expression(document.__qsaels.push(this))}';
          window.scrollBy(0, 0);
          return doc.__qsaels;
        };
      }

      addEventListener('load', function _lazyLoaderInit() {
        var imageNodes = document.querySelectorAll('img[data-src]');

        for (var i = 0; i < imageNodes.length; i++) {
          var imageNode = imageNodes[i];

          // Add a placeholder if one doesn't exist
          //imageNode.src = imageNode.src || lazyLoader.tinyGif;

          lazyLoader.cache.push(imageNode);
        }

        lazyLoader.addObservers();
        lazyLoader.loadVisibleImages();

        removeEventListener('load', _lazyLoaderInit, false);
      });
    }
  };

  return lazyLoader;
}

exports.lazyLoaderFn = lazyLoaderFn;

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = ready;
function ready(fn) {
  if (document.readyState !== 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

},{}],4:[function(require,module,exports){
"use strict";

/* Legacy scripts */

var _ready = require("./ready.js");

var _ready2 = _interopRequireDefault(_ready);

var _smoothScroll = require("./smooth-scroll.js");

var _helpers = require("./helpers.js");

var _lazyLoadImages = require("./lazy-load-images.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(0, _ready2.default)(function () {
  var articleImages = document.querySelectorAll('img.wp-post-image');

  for (var i = 0, j = articleImages.length; i < j; i++) {
    articleImages[i].dataset.src = articleImages[i].src;
    // articleImages[i].classList.add('lazy-load');
  }

  (0, _helpers.unshift_elements)(document.getElementsByClassName('shifted'));
  (0, _smoothScroll.smooth_scroll_fn)();
  (0, _lazyLoadImages.lazyLoaderFn)().init();
});

window.onscroll = function () {
  var backToTop = document.getElementById('back-to-top'),
      documentScrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

  if (documentScrollTop > screen.height / 2) {
    backToTop.classList.add('slide-up');
    backToTop.classList.remove('slide-down');
  } else {
    backToTop.classList.remove('slide-up');
    backToTop.classList.add('slide-down');
  }
};

/* End of legacy scripts */

(function ($) {
  var $body = $('body'),
      $menu_toggle = $('#menu-toggle');

  $(document).on('click', '#menu-icon', function (ev) {
    var $menu_icon = $menu_icon || $('#menu-icon');

    console.log($menu_toggle);

    if ($('#menu-toggle').is(':checked') === true) {
      console.log('1');
      $('body').removeClass('menu-open');
      $menu_icon.html('☰').removeClass('mr-3 mt-0');
    } else {
      console.log('0');
      $('body').addClass('menu-open');
      $menu_icon.html('×').addClass('mr-3 mt-0');
    }
  });
})(jQuery);

},{"./helpers.js":1,"./lazy-load-images.js":2,"./ready.js":3,"./smooth-scroll.js":5}],5:[function(require,module,exports){
"use strict";
/*
  https://github.com/alicelieutier/smoothScroll
  Modified by Stefan Bohacek to use the HTML5 History API.
*/

Object.defineProperty(exports, "__esModule", {
    value: true
});
function smooth_scroll_fn() {
    if (document.querySelectorAll === void 0 || window.pageYOffset === void 0 || history.pushState === void 0) {
        return;
    }

    var getTop = function getTop(element) {

        if (element.nodeName === 'HTML') {
            return -window.pageYOffset;
        }
        return element.getBoundingClientRect().top + window.pageYOffset;
    };
    var easeInOutCubic = function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    };
    var position = function position(start, end, elapsed, duration) {
        if (elapsed > duration) {
            return end;
        }
        return start + (end - start) * easeInOutCubic(elapsed / duration); // <-- you can change the easing funtion there
    };
    var smoothScroll = function smoothScroll(el, duration, callback) {
        duration = duration || 500;
        var start = window.pageYOffset,
            end;
        if (typeof el === 'number') {
            end = parseInt(el);
        } else {
            end = getTop(el) - 50;
        }
        var clock = Date.now();
        var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || function (fn) {
            window.setTimeout(fn, 15);
        };
        var step = function step() {
            var elapsed = Date.now() - clock;
            window.scroll(0, position(start, end, elapsed, duration));
            if (elapsed > duration) {
                if (typeof callback === 'function') {
                    callback(el);
                }
            } else {
                requestAnimationFrame(step);
            }
        };
        step();
    };
    var linkHandler = function linkHandler(ev) {
        ev.preventDefault();
        var hash = this.hash.substring(1);
        if (window.history && window.history.pushState) {
            history.pushState(null, null, '#' + hash);
        }
        smoothScroll(document.getElementById(hash), 500, function (el) {});
    };
    var internal = document.querySelectorAll('a[href^="#"]'),
        a;
    for (var i = internal.length; a = internal[--i];) {
        a.addEventListener("click", linkHandler, false);
    }

    if (window.location.hash) {
        setTimeout(function () {
            smoothScroll(document.getElementById(window.location.hash.substring(1)), 200, function (el) {});
        }, 300);
    }

    return smoothScroll;
}

exports.smooth_scroll_fn = smooth_scroll_fn;

},{}]},{},[4])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvc2NyaXB0cy9oZWxwZXJzLmpzIiwic3JjL3NjcmlwdHMvbGF6eS1sb2FkLWltYWdlcy5qcyIsInNyYy9zY3JpcHRzL3JlYWR5LmpzIiwic3JjL3NjcmlwdHMvc2NyaXB0cy5qcyIsInNyYy9zY3JpcHRzL3Ntb290aC1zY3JvbGwuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUNBQTs7Ozs7QUFFQSxTQUFTLGdCQUFULENBQTBCLFdBQTFCLEVBQXNDO0FBQ3BDLE1BQUksWUFBWSxNQUFaLEdBQXFCLENBQXpCLEVBQTJCO0FBQ3pCLFVBQU0sU0FBTixDQUFnQixPQUFoQixDQUF3QixJQUF4QixDQUE2QixXQUE3QixFQUEwQyxVQUFTLEVBQVQsRUFBYSxLQUFiLEVBQW9CO0FBQzVELGlCQUNFLFlBQVU7QUFDUixXQUFHLFNBQUgsQ0FBYSxNQUFiLENBQW9CLFNBQXBCO0FBQ0EsV0FBRyxTQUFILENBQWEsR0FBYixDQUFpQixXQUFqQjtBQUNELE9BSkgsRUFLRSxLQUFLLEtBQUwsR0FBYSxLQUxmO0FBTUQsS0FQRDtBQVFEO0FBQ0Y7O1FBRU8sZ0IsR0FBQSxnQjs7O0FDZlI7QUFDQTs7Ozs7Ozs7OztBQU9BLFNBQVMsWUFBVCxHQUF3QjtBQUN0QixNQUFJLG1CQUFvQixPQUFPLGdCQUFQLElBQTJCLFVBQVMsQ0FBVCxFQUFXLENBQVgsRUFBYztBQUFFLFdBQU8sV0FBUCxDQUFtQixPQUFLLENBQXhCLEVBQTJCLENBQTNCO0FBQWdDLEdBQW5HO0FBQUEsTUFDSSxzQkFBc0IsT0FBTyxtQkFBUCxJQUE4QixVQUFTLENBQVQsRUFBVyxDQUFYLEVBQWEsQ0FBYixFQUFnQjtBQUFFLFdBQU8sV0FBUCxDQUFtQixPQUFLLENBQXhCLEVBQTJCLENBQTNCO0FBQWdDLEdBRDFHOztBQUdBO0FBQ0E7QUFDQSxXQUFTLFlBQVQsQ0FBc0IsRUFBdEIsRUFBMEI7QUFDeEIsUUFBSSxN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LFNBQVIsQ0FBa0IsSUFBbEIsRUFBd0IsSUFBeEIsRUFBOEIsTUFBTSxJQUFwQztBQUNEO0FBQ0QscUJBQWEsU0FBUyxjQUFULENBQXdCLElBQXhCLENBQWIsRUFBNEMsR0FBNUMsRUFBaUQsVUFBUyxFQUFULEVBQWEsQ0FDN0QsQ0FERDtBQUVILEtBUkQ7QUFTQSxRQUFJLFdBQVcsU0FBUyxnQkFBVCxDQUEwQixjQUExQixDQUFmO0FBQUEsUUFBMEQsQ0FBMUQ7QUFDQSxTQUFJLElBQUksSUFBRSxTQUFTLE1BQW5CLEVBQTJCLElBQUUsU0FBUyxFQUFFLENBQVgsQ0FBN0IsR0FBNEM7QUFDeEMsVUFBRSxnQkFBRixDQUFtQixPQUFuQixFQUE0QixXQUE1QixFQUF5QyxLQUF6QztBQUNIOztBQUVELFFBQUcsT0FBTyxRQUFQLENBQWdCLElBQW5CLEVBQXlCO0FBQ3ZCLG1CQUFXLFlBQVU7QUFDbkIseUJBQWEsU0FBUyxjQUFULENBQXdCLE9BQU8sUUFBUCxDQUFnQixJQUFoQixDQUFxQixTQUFyQixDQUErQixDQUEvQixDQUF4QixDQUFiLEVBQXlFLEdBQXpFLEVBQThFLFVBQVMsRUFBVCxFQUFZLENBQUUsQ0FBNUY7QUFDRCxTQUZELEVBRUcsR0FGSDtBQUdEOztBQUdELFdBQU8sWUFBUDtBQUNEOztRQUVPLGdCLEdBQUEsZ0IiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbmZ1bmN0aW9uIHVuc2hpZnRfZWxlbWVudHMoc2hpZnRlZF9lbHMpe1xuICBpZiAoc2hpZnRlZF9lbHMubGVuZ3RoID4gMCl7XG4gICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChzaGlmdGVkX2VscywgZnVuY3Rpb24oZWwsIGluZGV4KSB7XG4gICAgICBzZXRUaW1lb3V0KFxuICAgICAgICBmdW5jdGlvbigpe1xuICAgICAgICAgIGVsLmNsYXNzTGlzdC5yZW1vdmUoJ3NoaWZ0ZWQnKTtcbiAgICAgICAgICBlbC5jbGFzc0xpc3QuYWRkKCd1bnNoaWZ0ZWQnKTtcbiAgICAgICAgfSxcbiAgICAgICAgNTUgKiBpbmRleCAqIGluZGV4KTtcbiAgICB9KTtcbiAgfSAgXG59XG5cbmV4cG9ydCB7dW5zaGlmdF9lbGVtZW50c307XG4iLCJcInVzZSBzdHJpY3RcIjtcbi8qXG4gIExhenkgTG9hZCBJbWFnZXMgd2l0aG91dCBqUXVlcnlcbiAgaHR0cDovL2thaXphdS5naXRodWIuY29tL0xhenktTG9hZC1JbWFnZXMtd2l0aG91dC1qUXVlcnkvXG4gIE9yaWdpbmFsIGJ5IE1pa2UgUHVsYXNraSAtIGh0dHA6Ly93d3cubWlrZXB1bGFza2kuY29tXG4gIE1vZGlmaWVkIGJ5IEthaSBaYXUgLSBodHRwOi8va2FpemF1LmNvbVxuKi9cblxuZnVuY3Rpb24gbGF6eUxvYWRlckZuKCkge1xuICB2YXIgYWRkRXZlbnRMaXN0ZW5lciA9ICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lciB8fCBmdW5jdGlvbihuLGYpIHsgd2luZG93LmF0dGFjaEV2ZW50KCdvbicrbiwgZik7IH0sXG4gICAgICByZW1vdmVFdmVudExpc3RlbmVyID0gd2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIgfHwgZnVuY3Rpb24obixmLGIpIHsgd2luZG93LmRldGFjaEV2ZW50KCdvbicrbiwgZik7IH07XG5cbiAgLy8gRm9yIElFNyBjb21wYXRpYmlsaXR5XG4gIC8vIEFkYXB0ZWQgZnJvbSBodHRwOi8vd3d3LnF1aXJrc21vZGUub3JnL2pzL2ZpbmRwb3MuaHRtbFxuICBmdW5jdGlvbiBnZXRPZmZzZXRUb3AoZWwpIHtcbiAgICB2YXIgdmFsID0gMDtcbiAgICBpZiAoZWwub2Zmc2V0UGFyZW50KSB7XG4gICAgICBkbyB7XG4gICAgICAgIHZhbCArPSBlbC5vZmZzZXRUb3A7XG4gICAgICB9IHdoaWxlIChlbCA9IGVsLm9mZnNldFBhcmVudCk7XG4gICAgICByZXR1cm4gdmFsO1xuICAgIH1cbiAgfSAgICAgIFxuXG4gIHZhciBsYXp5TG9hZGVyID0ge1xuICAgIGNhY2hlOiBbXSxcbiAgICBtb2JpbGVTY3JlZW5TaXplOiA1MDAsXG4gICAgLy90aW55R2lmOiAnZGF0YTppbWFnZS9naWY7YmFzZTY0LFIwbEdPRGxoQVFBQkFBQUFBQ0g1QkFFS0FBRUFMQUFBQUFBQkFBRUFBQUlDVEFFQU93PT0nLFxuXG4gICAgYWRkT2JzZXJ2ZXJzOiBmdW5jdGlvbigpIHtcbiAgICAgIGFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCk7XG4gICAgICBhZGRFdmVudExpc3RlbmVyKCdyZXNpemUnLCBsYXp5TG9hZGVyLnRocm90dGxlZExvYWQpO1xuICAgIH0sXG5cbiAgICByZW1vdmVPYnNlcnZlcnM6IGZ1bmN0aW9uKCkge1xuICAgICAgcmVtb3ZlRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgbGF6eUxvYWRlci50aHJvdHRsZWRMb2FkLCBmYWxzZSk7XG4gICAgICByZW1vdmVFdmVudExpc3RlbmVyKCdyZXNpemUnLCBsYXp5TG9hZGVyLnRocm90dGxlZExvYWQsIGZhbHNlKTtcbiAgICB9LFxuXG4gICAgdGhyb3R0bGVUaW1lcjogbmV3IERhdGUoKS5nZXRUaW1lKCksXG5cbiAgICB0aHJvdHRsZWRMb2FkOiBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBub3cgPSBuZXcgRGF0ZSgpLmdldFRpbWUoKTtcbiAgICAgIGlmICgobm93IC0gbGF6eUxvYWRlci50aHJvdHRsZVRpbWVyKSA+PSAyMDApIHtcbiAgICAgICAgbGF6eUxvYWRlci50aHJvdHRsZVRpbWVyID0gbm93O1xuICAgICAgICBsYXp5TG9hZGVyLmxvYWRWaXNpYmxlSW1hZ2VzKCk7XG4gICAgICB9XG4gICAgfSxcblxuICAgIGxvYWRWaXNpYmxlSW1hZ2VzOiBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBzY3JvbGxZID0gd2luZG93LnBhZ2VZT2Zmc2V0IHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5zY3JvbGxUb3A7XG4gICAgICB2YXIgcGFnZUhlaWdodCA9IHdpbmRvdy5pbm5lckhlaWdodCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50SGVpZ2h0O1xuICAgICAgdmFyIHJhbmdlID0ge1xuICAgICAgICBtaW46IHNjcm9sbFkgLSAyMDAsXG4gICAgICAgIG1heDogc2Nyb2xsWSArIHBhZ2VIZWlnaHQgKyAyMDBcbiAgICAgIH07XG5cbiAgICAgIHZhciBpID0gMDtcbiAgICAgIHdoaWxlIChpIDwgbGF6eUxvYWRlci5jYWNoZS5sZW5ndGgpIHtcbiAgICAgICAgdmFyIGltYWdlID0gbGF6eUxvYWRlci5jYWNoZVtpXTtcbiAgICAgICAgdmFyIGltYWdlUG9zaXRpb24gPSBnZXRPZmZzZXRUb3AoaW1hZ2UpO1xuICAgICAgICB2YXIgaW1hZ2VIZWlnaHQgPSBpbWFnZS5oZWlnaHQgfHwgMDtcblxuICAgICAgICBpZiAoKGltYWdlUG9zaXRpb24gPj0gcmFuZ2UubWluIC0gaW1hZ2VIZWlnaHQpICYmIChpbWFnZVBvc2l0aW9uIDw9IHJhbmdlLm1heCkpIHtcbiAgICAgICAgICB2YXIgbW9iaWxlU3JjID0gaW1hZ2UuZ2V0QXR0cmlidXRlKCdkYXRhLXNyYy1tb2JpbGUnKTtcblxuICAgICAgICAgIC8vIGltYWdlLm9ubG9hZCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICAgIC8vICAgdGhpcy5jbGFzc05hbWUgPSB0aGlzLmNsYXNzTmFtZS5yZXBsYWNlKC8oXnxcXHMrKWxhenktbG9hZChcXHMrfCQpLywgJyQxbGF6eS1sb2FkZWQkMicpO1xuICAgICAgICAgIC8vIH07XG4vLyAgICAgICAgVGVtcG9yYXJ5IGZpeCBmb3IgU2FmYXJpIVxuXG4gICAgICAgICAgaW1hZ2UuY2xhc3NOYW1lID0gaW1hZ2UuY2xhc3NOYW1lLnJlcGxhY2UoLyhefFxccyspbGF6eS1sb2FkKFxccyt8JCkvLCAnJDFsYXp5LWxvYWRlZCQyJyk7XG5cblxuICAgICAgICAgIGlmIChtb2JpbGVTcmMgJiYgc2NyZWVuLndpZHRoIDw9IGxhenlMb2FkZXIubW9iaWxlU2NyZWVuU2l6ZSkge1xuICAgICAgICAgICAgaW1hZ2Uuc3JjID0gbW9iaWxlU3JjO1xuICAgICAgICAgIH1cbiAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGltYWdlLnNyYyA9IGltYWdlLmdldEF0dHJpYnV0ZSgnZGF0YS1zcmMnKTtcbiAgICAgICAgICB9XG5cbiAgICAgICAgICBpbWFnZS5yZW1vdmVBdHRyaWJ1dGUoJ2RhdGEtc3JjJyk7XG4gICAgICAgICAgaW1hZ2UucmVtb3ZlQXR0cmlidXRlKCdkYXRhLXNyYy1tb2JpbGUnKTtcblxuICAgICAgICAgIGxhenlMb2FkZXIuY2FjaGUuc3BsaWNlKGksIDEpO1xuICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICB9XG5cbiAgICAgICAgaSsrO1xuICAgICAgfVxuXG4gICAgICBpZiAobGF6eUxvYWRlci5jYWNoZS5sZW5ndGggPT09IDApIHtcbiAgICAgICAgbGF6eUxvYWRlci5yZW1vdmVPYnNlcnZlcnMoKTtcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAvLyBQYXRjaCBJRTctIChxdWVyeVNlbGVjdG9yQWxsKVxuICAgICAgaWYgKCFkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKSB7XG4gICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwgPSBmdW5jdGlvbihzZWxlY3Rvcikge1xuICAgICAgICAgIHZhciBkb2MgPSBkb2N1bWVudCxcbiAgICAgICAgICAgICAgaGVhZCA9IGRvYy5kb2N1bWVudEVsZW1lbnQuZmlyc3RDaGlsZCxcbiAgICAgICAgICAgICAgc3R5bGVUYWcgPSBkb2MuY3JlYXRlRWxlbWVudCgnU1RZTEUnKTtcbiAgICAgICAgICBoZWFkLmFwcGVuZENoaWxkKHN0eWxlVGFnKTtcbiAgICAgICAgICBkb2MuX19xc2FlbHMgPSBbXTtcbiAgICAgICAgICBzdHlsZVRhZy5zdHlsZVNoZWV0LmNzc1RleHQgPSBzZWxlY3RvciArICd7eDpleHByZXNzaW9uKGRvY3VtZW50Ll9fcXNhZWxzLnB1c2godGhpcykpfSc7XG4gICAgICAgICAgd2luZG93LnNjcm9sbEJ5KDAsIDApO1xuICAgICAgICAgIHJldHVybiBkb2MuX19xc2FlbHM7XG4gICAgICAgIH07XG4gICAgICB9XG5cbiAgICAgIGFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiBfbGF6eUxvYWRlckluaXQoKSB7XG4gICAgICAgIHZhciBpbWFnZU5vZGVzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW1nW2RhdGEtc3JjXScpO1xuXG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgaW1hZ2VOb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgIHZhciBpbWFnZU5vZGUgPSBpbWFnZU5vZGVzW2ldO1xuXG4gICAgICAgICAgLy8gQWRkIGEgcGxhY2Vob2xkZXIgaWYgb25lIGRvZXNuJ3QgZXhpc3RcbiAgICAgICAgICAvL2ltYWdlTm9kZS5zcmMgPSBpbWFnZU5vZGUuc3JjIHx8IGxhenlMb2FkZXIudGlueUdpZjtcblxuICAgICAgICAgIGxhenlMb2FkZXIuY2FjaGUucHVzaChpbWFnZU5vZGUpO1xuICAgICAgICB9XG5cbiAgICAgICAgbGF6eUxvYWRlci5hZGRPYnNlcnZlcnMoKTtcbiAgICAgICAgbGF6eUxvYWRlci5sb2FkVmlzaWJsZUltYWdlcygpO1xuXG4gICAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBfbGF6eUxvYWRlckluaXQsIGZhbHNlKTtcbiAgICAgIH0pO1xuICAgIH1cbiAgfTtcblxuICByZXR1cm4gbGF6eUxvYWRlcjtcbn1cblxuZXhwb3J0IHtsYXp5TG9hZGVyRm59O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbmV4cG9ydCBkZWZhdWx0IGZ1bmN0aW9uIHJlYWR5KGZuKSB7XG4gIGlmIChkb2N1bWVudC5yZWFkeVN0YXRlICE9PSAnbG9hZGluZycpe1xuICAgIGZuKCk7XG4gIH0gZWxzZSB7XG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZuKTtcbiAgfVxufVxuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbi8qIExlZ2FjeSBzY3JpcHRzICovXG5cbmltcG9ydCByZWFkeSBmcm9tIFwiLi9yZWFkeS5qc1wiO1xuaW1wb3J0IHtzbW9vdGhfc2Nyb2xsX2ZufSBmcm9tIFwiLi9zbW9vdGgtc2Nyb2xsLmpzXCI7XG5pbXBvcnQge3Vuc2hpZnRfZWxlbWVudHN9IGZyb20gXCIuL2hlbHBlcnMuanNcIjtcbmltcG9ydCB7bGF6eUxvYWRlckZufSBmcm9tIFwiLi9sYXp5LWxvYWQtaW1hZ2VzLmpzXCI7XG5cbnJlYWR5KGZ1bmN0aW9uKCl7XG4gIHZhciBhcnRpY2xlSW1hZ2VzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW1nLndwLXBvc3QtaW1hZ2UnKTtcbiAgXG4gIGZvciAodmFyIGkgPSAwLCBqID0gYXJ0aWNsZUltYWdlcy5sZW5ndGg7IGkgPCBqOyBpKyspe1xuICAgIGFydGljbGVJbWFnZXNbaV0uZGF0YXNldC5zcmMgPSBhcnRpY2xlSW1hZ2VzW2ldLnNyYztcbiAgICAvLyBhcnRpY2xlSW1hZ2VzW2ldLmNsYXNzTGlzdC5hZGQoJ2xhenktbG9hZCcpO1xuICB9XG5cbiAgdW5zaGlmdF9lbGVtZW50cyhkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdzaGlmdGVkJykpO1xuICBzbW9vdGhfc2Nyb2xsX2ZuKCk7XG4gIGxhenlMb2FkZXJGbigpLmluaXQoKTtcbn0pO1xuXG53aW5kb3cub25zY3JvbGw9ZnVuY3Rpb24oKXtcbiAgdmFyIGJhY2tUb1RvcCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYWNrLXRvLXRvcCcpLFxuICAgICAgZG9jdW1lbnRTY3JvbGxUb3AgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcCB8fCBkb2N1bWVudC5ib2R5LnNjcm9sbFRvcCB8fCAwO1xuXG4gIGlmIChkb2N1bWVudFNjcm9sbFRvcCA+IChzY3JlZW4uaGVpZ2h0LzIpKXtcbiAgICBiYWNrVG9Ub3AuY2xhc3NMaXN0LmFkZCgnc2xpZGUtdXAnKTtcbiAgICBiYWNrVG9Ub3AuY2xhc3NMaXN0LnJlbW92ZSgnc2xpZGUtZG93bicpO1xuICB9XG4gIGVsc2V7XG4gICAgYmFja1RvVG9wLmNsYXNzTGlzdC5yZW1vdmUoJ3NsaWRlLXVwJyk7XG4gICAgYmFja1RvVG9wLmNsYXNzTGlzdC5hZGQoJ3NsaWRlLWRvd24nKTtcbiAgfVxufTtcblxuLyogRW5kIG9mIGxlZ2FjeSBzY3JpcHRzICovXG5cbihmdW5jdGlvbigkKSB7XG4gIHZhciAkYm9keSA9ICQoJ2JvZHknKSxcbiAgICAgICRtZW51X3RvZ2dsZSA9ICQoJyNtZW51LXRvZ2dsZScpO1xuXG4gICQoZG9jdW1lbnQpLm9uKCdjbGljaycsICcjbWVudS1pY29uJywgZnVuY3Rpb24oZXYpe1xuICAgIHZhciAkbWVudV9pY29uID0gJG1lbnVfaWNvbiB8fCAkKCcjbWVudS1pY29uJyk7XG5cbiAgICBjb25zb2xlLmxvZygkbWVudV90b2dnbGUpO1xuXG4gICAgaWYgKCQoJyNtZW51LXRvZ2dsZScpLmlzKCc6Y2hlY2tlZCcpID09PSB0cnVlKXtcbiAgICAgIGNvbnNvbGUubG9nKCcxJyk7XG4gICAgICAkKCdib2R5JykucmVtb3ZlQ2xhc3MoJ21lbnUtb3BlbicpO1xuICAgICAgJG1lbnVfaWNvbi5odG1sKCfimLAnKS5yZW1vdmVDbGFzcygnbXItMyBtdC0wJyk7XG4gICAgfVxuICAgIGVsc2V7XG4gICAgICBjb25zb2xlLmxvZygnMCcpOyAgICAgIFxuICAgICAgJCgnYm9keScpLmFkZENsYXNzKCdtZW51LW9wZW4nKTsgICAgICBcbiAgICAgICRtZW51X2ljb24uaHRtbCgnw5cnKS5hZGRDbGFzcygnbXItMyBtdC0wJyk7XG4gICAgfVxuICB9KTtcbiAgXG59KSggalF1ZXJ5ICk7IiwiXCJ1c2Ugc3RyaWN0XCI7XG4vKlxuICBodHRwczovL2dpdGh1Yi5jb20vYWxpY2VsaWV1dGllci9zbW9vdGhTY3JvbGxcbiAgTW9kaWZpZWQgYnkgU3RlZmFuIEJvaGFjZWsgdG8gdXNlIHRoZSBIVE1MNSBIaXN0b3J5IEFQSS5cbiovXG5mdW5jdGlvbiBzbW9vdGhfc2Nyb2xsX2ZuKCl7XG4gIGlmKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwgPT09IHZvaWQgMCB8fCB3aW5kb3cucGFnZVlPZmZzZXQgPT09IHZvaWQgMCB8fCBoaXN0b3J5LnB1c2hTdGF0ZSA9PT0gdm9pZCAwKSB7IHJldHVybjsgfVxuXG4gIHZhciBnZXRUb3AgPSBmdW5jdGlvbihlbGVtZW50KSB7XG5cbiAgICAgIGlmKGVsZW1lbnQubm9kZU5hbWUgPT09ICdIVE1MJyl7XG4gICAgICAgIHJldHVybiAtd2luZG93LnBhZ2VZT2Zmc2V0O1xuICAgICAgfVxuICAgICAgcmV0dXJuIGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCkudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0O1xuICB9O1xuICB2YXIgZWFzZUluT3V0Q3ViaWMgPSBmdW5jdGlvbiAodCkgeyByZXR1cm4gdDwwLjUgPyA0KnQqdCp0IDogKHQtMSkqKDIqdC0yKSooMip0LTIpKzE7IH07XG4gIHZhciBwb3NpdGlvbiA9IGZ1bmN0aW9uKHN0YXJ0LCBlbmQsIGVsYXBzZWQsIGR1cmF0aW9uKSB7XG4gICAgICBpZiAoZWxhcHNlZCA+IGR1cmF0aW9uKXtcbiAgICAgICAgcmV0dXJuIGVuZDtcbiAgICAgIH1cbiAgICAgIHJldHVybiBzdGFydCArIChlbmQgLSBzdGFydCkgKiBlYXNlSW5PdXRDdWJpYyhlbGFwc2VkIC8gZHVyYXRpb24pOyAvLyA8LS0geW91IGNhbiBjaGFuZ2UgdGhlIGVhc2luZyBmdW50aW9uIHRoZXJlXG4gIH07XG4gIHZhciBzbW9vdGhTY3JvbGwgPSBmdW5jdGlvbihlbCwgZHVyYXRpb24sIGNhbGxiYWNrKXtcbiAgICAgIGR1cmF0aW9uID0gZHVyYXRpb24gfHwgNTAwO1xuICAgICAgdmFyIHN0YXJ0ID0gd2luZG93LnBhZ2VZT2Zmc2V0LFxuICAgICAgICAgIGVuZDtcbiAgICAgIGlmICh0eXBlb2YgZWwgPT09ICdudW1iZXInKSB7XG4gICAgICAgIGVuZCA9IHBhcnNlSW50KGVsKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGVuZCA9IGdldFRvcChlbCkgLSA1MDtcbiAgICAgIH1cbiAgICAgIHZhciBjbG9jayA9IERhdGUubm93KCk7XG4gICAgICB2YXIgcmVxdWVzdEFuaW1hdGlvbkZyYW1lID0gd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZSB8fFxuICAgICAgICAgIHdpbmRvdy5tb3pSZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHwgd2luZG93LndlYmtpdFJlcXVlc3RBbmltYXRpb25GcmFtZSB8fFxuICAgICAgICAgIGZ1bmN0aW9uKGZuKXt3aW5kb3cuc2V0VGltZW91dChmbiwgMTUpO307XG4gICAgICB2YXIgc3RlcCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgdmFyIGVsYXBzZWQgPSBEYXRlLm5vdygpIC0gY2xvY2s7XG4gICAgICAgICAgd2luZG93LnNjcm9sbCgwLCBwb3NpdGlvbihzdGFydCwgZW5kLCBlbGFwc2VkLCBkdXJhdGlvbikpO1xuICAgICAgICAgIGlmIChlbGFwc2VkID4gZHVyYXRpb24pIHtcbiAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgY2FsbGJhY2soZWwpO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgcmVxdWVzdEFuaW1hdGlvbkZyYW1lKHN0ZXApO1xuICAgICAgICAgIH1cbiAgICAgIH07XG4gICAgICBzdGVwKCk7XG4gIH07XG4gIHZhciBsaW5rSGFuZGxlciA9IGZ1bmN0aW9uKGV2KSB7XG4gICAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgdmFyIGhhc2ggPSB0aGlzLmhhc2guc3Vic3RyaW5nKDEpO1xuICAgICAgaWYgKHdpbmRvdy5oaXN0b3J5ICYmIHdpbmRvdy5oaXN0b3J5LnB1c2hTdGF0ZSl7XG4gICAgICAgIGhpc3RvcnkucHVzaFN0YXRlKG51bGwsIG51bGwsICcjJyArIGhhc2gpO1xuICAgICAgfVxuICAgICAgc21vb3RoU2Nyb2xsKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGhhc2gpLCA1MDAsIGZ1bmN0aW9uKGVsKSB7XG4gICAgICB9KTtcbiAgfTtcbiAgdmFyIGludGVybmFsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnYVtocmVmXj1cIiNcIl0nKSwgYTtcbiAgZm9yKHZhciBpPWludGVybmFsLmxlbmd0aDsgYT1pbnRlcm5hbFstLWldOyl7XG4gICAgICBhLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBsaW5rSGFuZGxlciwgZmFsc2UpO1xuICB9XG5cbiAgaWYod2luZG93LmxvY2F0aW9uLmhhc2gpIHtcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICBzbW9vdGhTY3JvbGwoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQod2luZG93LmxvY2F0aW9uLmhhc2guc3Vic3RyaW5nKDEpKSwgMjAwLCBmdW5jdGlvbihlbCl7fSk7ICAgICAgXG4gICAgfSwgMzAwKTtcbiAgfVxuXG5cbiAgcmV0dXJuIHNtb290aFNjcm9sbDtcbn1cblxuZXhwb3J0IHtzbW9vdGhfc2Nyb2xsX2ZufTtcbiJdfQ==
