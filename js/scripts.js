(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var back_to_top = {
  init: function init() {
    $(window).scroll(function () {
      /* TODO: Legacy script, rewrite with jQuery. */
      var backToTop = document.getElementById('back-to-top'),
          documentScrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

      if (documentScrollTop > screen.height / 2) {
        backToTop.classList.add('slide-up');
        backToTop.classList.remove('slide-down');
      } else {
        backToTop.classList.remove('slide-up');
        backToTop.classList.add('slide-down');
      }
    });
  }
};

exports.back_to_top = back_to_top;

},{}],2:[function(require,module,exports){
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

},{}],3:[function(require,module,exports){
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

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var menu_toggle = {
  init: function init() {
    var $body = $('body'),
        $menu_toggle = $('#menu-toggle');

    $('#menu-icon').on('click', function (ev) {
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
  }
};

exports.menu_toggle = menu_toggle;

},{}],5:[function(require,module,exports){
"use strict";

var _search = require("./search.js");

var _menuToggle = require("./menu-toggle.js");

var _backToTop = require("./back-to-top.js");

var _submitYourBot = require("./submit-your-bot.js");

var _smoothScroll = require("./smooth-scroll.js");

var _helpers = require("./helpers.js");

var _lazyLoadImages = require("./lazy-load-images.js");

$ = jQuery.noConflict(true);

/* TODO: Legacy code, needs a cleanup. */

/* END TODO */

$(function () {
  _search.search.init();
  _menuToggle.menu_toggle.init();
  _backToTop.back_to_top.init();
  _submitYourBot.bot_submission.init();

  /* TODO: Legacy code, needs a cleanup. */

  var articleImages = document.querySelectorAll('img.wp-post-image');

  for (var i = 0, j = articleImages.length; i < j; i++) {
    articleImages[i].dataset.src = articleImages[i].src;
    // articleImages[i].classList.add('lazy-load');
  }

  (0, _helpers.unshift_elements)(document.getElementsByClassName('shifted'));
  (0, _smoothScroll.smooth_scroll_fn)();
  (0, _lazyLoadImages.lazyLoaderFn)().init();
});

},{"./back-to-top.js":1,"./helpers.js":2,"./lazy-load-images.js":3,"./menu-toggle.js":4,"./search.js":6,"./smooth-scroll.js":7,"./submit-your-bot.js":8}],6:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var search = {
  init: function init() {
    var $search_filter_checkboxes = $('#search-filters').find('input[type="checkbox"]'),
        $search_filter_tutorials = $('#search-filters-tutorials'),
        $search_filter_resources = $('#search-filters-resources');

    $('#search-input').click(function (ev) {
      $('#search-filters').removeClass('slide-up-hidden').addClass('slide-up');
    });
  }
};

exports.search = search;

},{}],7:[function(require,module,exports){
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

},{}],8:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var bot_submission = {
  init: function init() {
    var $body = $('body'),
        $form_submit_button = $('#bot-form-submit');
    if ($body.hasClass('page-template-template-submit-your-bot')) {

      $('#test').click(function (ev) {
        ev.preventDefault();

        $('#add-author-fields').before('<div class="author-fields form-row"><div class="form-group col-md-6"><label for="author-${new_id}-name">Author\'s name</label><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><label for="author-2-url">Author\'s URL</label><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

        $('#bot-name').val('@coolbot');
        $('#author-1-name').val('Stefan');
        $('#author-1-url').val('https://twitter.com/fourtonfish');
        $('#author-2-name').val('John Doe');
        // $('#author-2-url').val('https://twitter.com/jd');
        $('#bot-description').val('This bot generates random images.');
        $('#bot-urls').val('https://twitter.com/coolbot');
        $('#bot-selected-tweets').val('https://twitter.com/mycoolbot/status/123456789\nhttps://twitter.com/mycoolbot/status/987654321');
        $('#bot-tagline').val('This is a cool bot.');
        $('#bot-networks').val('5');
        $('#bot-networks').trigger('change');
        $('#bot-tags').val(['92', '64', '55']);
        $('#bot-tags').trigger('change');

        $('#bot-is-opensource').click();

        $('#bot-source-url').val('https://github.com/botwiki/botwiki.org');
        $('#bot-source-language').val('25');
        $('#bot-source-language').trigger('change');

        $('html, body').animate({
          scrollTop: $form_submit_button.offset().top - 500
        }, 450);

        return false;
      });

      $('.js-select2').each(function (i) {
        $(this).select2({
          placeholder: $(this).attr('placeholder')
        });
      });

      $('#submit-bot-form').submit(function () {
        $form_submit_button.attr('disabled', 'disabled').html('Please wait...');
        setTimeout(function () {
          $form_submit_button.html('Still working...');
        }, 4500);
      });

      $('#add-author-fields').click(function (ev) {
        ev.preventDefault();

        var new_id = $('.author-fields').length + 1;

        $(this).before('<div class="author-fields form-row"><div class="form-group col-md-6"><label for="author-${new_id}-name">Author\'s name</label><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><label for="author-2-url">Author\'s URL</label><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

        return false;
      });

      var $bot_source_info = $('#bot-source-info');
      $bot_source_info.addClass('d-none');

      $('#bot-is-opensource').click(function (ev) {

        if ($(this).is(':checked')) {
          $bot_source_info.removeClass('d-none');
        } else {
          $bot_source_info.addClass('d-none');
        }
      });

      var $bot_networks_select = $('#bot-networks'),
          $selected_tweets_field = $('#bot-selected-tweets-field');

      $bot_networks_select.on('change', function () {
        if ($bot_networks_select.children("option").filter(":selected").text().indexOf('Twitter') > -1) {
          $selected_tweets_field.removeClass('d-none');
        } else {
          $selected_tweets_field.addClass('d-none');
        }
      });
    }
  }
};

exports.bot_submission = bot_submission;

},{}]},{},[5])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvc2NyaXB0cy9iYWNrLXRvLXRvcC5qcyIsInNyYy9zY3JpcHRzL2hlbHBlcnMuanMiLCJzcmMvc2NyaXB0cy9sYXp5LWxvYWQtaW1hZ2VzLmpzIiwic3JjL3NjcmlwdHMvbWVudS10b2dnbGUuanMiLCJzcmMvc2NyaXB0cy9zY3JpcHRzLmpzIiwic3JjL3NjcmlwdHMvc2VhcmNoLmpzIiwic3JjL3NjcmlwdHMvc21vb3RoLXNjcm9sbC5qcyIsInNyYy9zY3JpcHRzL3N1Ym1pdC15b3VyLWJvdC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksY0FBYztBQUNoQixRQUFNLGdCQUFVO0FBQ2QsTUFBRSxNQUFGLEVBQVUsTUFBVixDQUFpQixZQUFVO0FBQ3pCO0FBQ0EsVUFBSSxZQUFZLFNBQVMsY0FBVCxDQUF3QixhQUF4QixDQUFoQjtBQUFBLFVBQ0ksb0JBQW9CLE9BQU8sV0FBUCxJQUFzQixTQUFTLGVBQVQsQ0FBeUIsU0FBL0MsSUFBNEQsU0FBUyxJQUFULENBQWMsU0FBMUUsSUFBdUYsQ0FEL0c7O0FBR0EsVUFBSSxvQkFBcUIsT0FBTyxNQUFQLEdBQWMsQ0FBdkMsRUFBMEM7QUFDeEMsa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixVQUF4QjtBQUNBLGtCQUFVLFNBQVYsQ0FBb0IsTUFBcEIsQ0FBMkIsWUFBM0I7QUFDRCxPQUhELE1BSUk7QUFDRixrQkFBVSxTQUFWLENBQW9CLE1BQXBCLENBQTJCLFVBQTNCO0FBQ0Esa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixZQUF4QjtBQUNEO0FBQ0YsS0FiRDtBQWNEO0FBaEJlLENBQWxCOztRQW9CRSxXLEdBQUEsVzs7O0FDdEJGOzs7OztBQUVBLFNBQVMsZ0JBQVQsQ0FBMEIsV0FBMUIsRUFBc0M7QUFDcEMsTUFBSSxZQUFZLE1BQVosR0FBcUIsQ0FBekIsRUFBMkI7QUFD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b0JBQUYsRUFBd0IsS0FBeEIsQ0FBOEIsVUFBUyxFQUFULEVBQVk7O0FBRXhDLFlBQUksRUFBRSxJQUFGLEVBQVEsRUFBUixDQUFXLFVBQVgsQ0FBSixFQUEyQjtBQUN6QiwyQkFBaUIsV0FBakIsQ0FBNkIsUUFBN0I7QUFDRCxTQUZELE1BR0k7QUFDRiwyQkFBaUIsUUFBakIsQ0FBMEIsUUFBMUI7QUFDRDtBQUNGLE9BUkQ7O0FBVUEsVUFBSSx1QkFBdUIsRUFBRSxlQUFGLENBQTNCO0FBQUEsVUFDSSx5QkFBeUIsRUFBRSw0QkFBRixDQUQ3Qjs7QUFJQSwyQkFBcUIsRUFBckIsQ0FBd0IsUUFBeEIsRUFBa0MsWUFBVTtBQUMxQyxZQUFJLHFCQUFxQixRQUFyQixDQUE4QixRQUE5QixFQUF3QyxNQUF4QyxDQUErQyxXQUEvQyxFQUE0RCxJQUE1RCxHQUFtRSxPQUFuRSxDQUEyRSxTQUEzRSxJQUF3RixDQUFDLENBQTdGLEVBQStGO0FBQzdGLGlDQUF1QixXQUF2QixDQUFtQyxRQUFuQztBQUNELFNBRkQsTUFHSTtBQUNGLGlDQUF1QixRQUF2QixDQUFnQyxRQUFoQztBQUNEO0FBQ0YsT0FQRDtBQVVEO0FBQ0Y7QUEzRmtCLENBQXJCOztRQStGRSxjLEdBQUEsYyIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIGJhY2tfdG9fdG9wID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgICQod2luZG93KS5zY3JvbGwoZnVuY3Rpb24oKXtcbiAgICAgIC8qIFRPRE86IExlZ2FjeSBzY3JpcHQsIHJld3JpdGUgd2l0aCBqUXVlcnkuICovXG4gICAgICB2YXIgYmFja1RvVG9wID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhY2stdG8tdG9wJyksXG4gICAgICAgICAgZG9jdW1lbnRTY3JvbGxUb3AgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcCB8fCBkb2N1bWVudC5ib2R5LnNjcm9sbFRvcCB8fCAwO1xuXG4gICAgICBpZiAoZG9jdW1lbnRTY3JvbGxUb3AgPiAoc2NyZWVuLmhlaWdodC8yKSl7XG4gICAgICAgIGJhY2tUb1RvcC5jbGFzc0xpc3QuYWRkKCdzbGlkZS11cCcpO1xuICAgICAgICBiYWNrVG9Ub3AuY2xhc3NMaXN0LnJlbW92ZSgnc2xpZGUtZG93bicpO1xuICAgICAgfVxuICAgICAgZWxzZXtcbiAgICAgICAgYmFja1RvVG9wLmNsYXNzTGlzdC5yZW1vdmUoJ3NsaWRlLXVwJyk7XG4gICAgICAgIGJhY2tUb1RvcC5jbGFzc0xpc3QuYWRkKCdzbGlkZS1kb3duJyk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgYmFja190b190b3Bcbn07XG4iLCJcInVzZSBzdHJpY3RcIjtcblxuZnVuY3Rpb24gdW5zaGlmdF9lbGVtZW50cyhzaGlmdGVkX2Vscyl7XG4gIGlmIChzaGlmdGVkX2Vscy5sZW5ndGggPiAwKXtcbiAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHNoaWZ0ZWRfZWxzLCBmdW5jdGlvbihlbCwgaW5kZXgpIHtcbiAgICAgIHNldFRpbWVvdXQoXG4gICAgICAgIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgZWwuY2xhc3NMaXN0LnJlbW92ZSgnc2hpZnRlZCcpO1xuICAgICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoJ3Vuc2hpZnRlZCcpO1xuICAgICAgICB9LFxuICAgICAgICA1NSAqIGluZGV4ICogaW5kZXgpO1xuICAgIH0pO1xuICB9ICBcbn1cblxuZXhwb3J0IHtcbiAgdW5zaGlmdF9lbGVtZW50c1xufTtcbiIsIlwidXNlIHN0cmljdFwiO1xuLypcbiAgTGF6eSBMb2FkIEltYWdlcyB3aXRob3V0IGpRdWVyeVxuICBodHRwOi8va2FpemF1LmdpdGh1Yi5jb20vTGF6eS1Mb2FkLUltYWdlcy13aXRob3V0LWpRdWVyeS9cbiAgT3JpZ2luYWwgYnkgTWlrZSBQdWxhc2tpIC0gaHR0cDovL3d3dy5taWtlcHVsYXNraS5jb21cbiAgTW9kaWZpZWQgYnkgS2FpIFphdSAtIGh0dHA6Ly9rYWl6YXUuY29tXG4qL1xuXG5mdW5jdGlvbiBsYXp5TG9hZGVyRm4oKSB7XG4gIHZhciBhZGRFdmVudExpc3RlbmVyID0gIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyIHx8IGZ1bmN0aW9uKG4sZikgeyB3aW5kb3cuYXR0YWNoRXZlbnQoJ29uJytuLCBmKTsgfSxcbiAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIgPSB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lciB8fCBmdW5jdGlvbihuLGYsYikgeyB3aW5kb3cuZGV0YWNoRXZlbnQoJ29uJytuLCBmKTsgfTtcblxuICAvLyBGb3IgSUU3IGNvbXBhdGliaWxpdHlcbiAgLy8gQWRhcHRlZCBmcm9tIGh0dHA6Ly93d3cucXVpcmtzbW9kZS5vcmcvanMvZmluZHBvcy5odG1sXG4gIGZ1bmN0aW9uIGdldE9mZnNldFRvcChlbCkge1xuICAgIHZhciB2YWwgPSAwO1xuICAgIGlmIChlbC5vZmZzZXRQYXJlbnQpIHtcbiAgICAgIGRvIHtcbiAgICAgICAgdmFsICs9IGVsLm9mZnNldFRvcDtcbiAgICAgIH0gd2hpbGUgKGVsID0gZWwub2Zmc2V0UGFyZW50KTtcbiAgICAgIHJldHVybiB2YWw7XG4gICAgfVxuICB9ICAgICAgXG5cbiAgdmFyIGxhenlMb2FkZXIgPSB7XG4gICAgY2FjaGU6IFtdLFxuICAgIG1vYmlsZVNjcmVlblNpemU6IDUwMCxcbiAgICAvL3RpbnlHaWY6ICdkYXRhOmltYWdlL2dpZjtiYXNlNjQsUjBsR09EbGhBUUFCQUFBQUFDSDVCQUVLQUFFQUxBQUFBQUFCQUFFQUFBSUNUQUVBT3c9PScsXG5cbiAgICBhZGRPYnNlcnZlcnM6IGZ1bmN0aW9uKCkge1xuICAgICAgYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgbGF6eUxvYWRlci50aHJvdHRsZWRMb2FkKTtcbiAgICAgIGFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCk7XG4gICAgfSxcblxuICAgIHJlbW92ZU9ic2VydmVyczogZnVuY3Rpb24oKSB7XG4gICAgICByZW1vdmVFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBsYXp5TG9hZGVyLnRocm90dGxlZExvYWQsIGZhbHNlKTtcbiAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCwgZmFsc2UpO1xuICAgIH0sXG5cbiAgICB0aHJvdHRsZVRpbWVyOiBuZXcgRGF0ZSgpLmdldFRpbWUoKSxcblxuICAgIHRocm90dGxlZExvYWQ6IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIG5vdyA9IG5ldyBEYXRlKCkuZ2V0VGltZSgpO1xuICAgICAgaWYgKChub3cgLSBsYXp5TG9hZGVyLnRocm90dGxlVGltZXIpID49IDIwMCkge1xuICAgICAgICBsYXp5TG9hZGVyLnRocm90dGxlVGltZXIgPSBub3c7XG4gICAgICAgIGxhenlMb2FkZXIubG9hZFZpc2libGVJbWFnZXMoKTtcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgbG9hZFZpc2libGVJbWFnZXM6IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIHNjcm9sbFkgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcDtcbiAgICAgIHZhciBwYWdlSGVpZ2h0ID0gd2luZG93LmlubmVySGVpZ2h0IHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQ7XG4gICAgICB2YXIgcmFuZ2UgPSB7XG4gICAgICAgIG1pbjogc2Nyb2xsWSAtIDIwMCxcbiAgICAgICAgbWF4OiBzY3JvbGxZICsgcGFnZUhlaWdodCArIDIwMFxuICAgICAgfTtcblxuICAgICAgdmFyIGkgPSAwO1xuICAgICAgd2hpbGUgKGkgPCBsYXp5TG9hZGVyLmNhY2hlLmxlbmd0aCkge1xuICAgICAgICB2YXIgaW1hZ2UgPSBsYXp5TG9hZGVyLmNhY2hlW2ldO1xuICAgICAgICB2YXIgaW1hZ2VQb3NpdGlvbiA9IGdldE9mZnNldFRvcChpbWFnZSk7XG4gICAgICAgIHZhciBpbWFnZUhlaWdodCA9IGltYWdlLmhlaWdodCB8fCAwO1xuXG4gICAgICAgIGlmICgoaW1hZ2VQb3NpdGlvbiA+PSByYW5nZS5taW4gLSBpbWFnZUhlaWdodCkgJiYgKGltYWdlUG9zaXRpb24gPD0gcmFuZ2UubWF4KSkge1xuICAgICAgICAgIHZhciBtb2JpbGVTcmMgPSBpbWFnZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3JjLW1vYmlsZScpO1xuXG4gICAgICAgICAgLy8gaW1hZ2Uub25sb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgLy8gICB0aGlzLmNsYXNzTmFtZSA9IHRoaXMuY2xhc3NOYW1lLnJlcGxhY2UoLyhefFxccyspbGF6eS1sb2FkKFxccyt8JCkvLCAnJDFsYXp5LWxvYWRlZCQyJyk7XG4gICAgICAgICAgLy8gfTtcbi8vICAgICAgICBUZW1wb3JhcnkgZml4IGZvciBTYWZhcmkhXG5cbiAgICAgICAgICBpbWFnZS5jbGFzc05hbWUgPSBpbWFnZS5jbGFzc05hbWUucmVwbGFjZSgvKF58XFxzKylsYXp5LWxvYWQoXFxzK3wkKS8sICckMWxhenktbG9hZGVkJDInKTtcblxuXG4gICAgICAgICAgaWYgKG1vYmlsZVNyYyAmJiBzY3JlZW4ud2lkdGggPD0gbGF6eUxvYWRlci5tb2JpbGVTY3JlZW5TaXplKSB7XG4gICAgICAgICAgICBpbWFnZS5zcmMgPSBtb2JpbGVTcmM7XG4gICAgICAgICAgfVxuICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgaW1hZ2Uuc3JjID0gaW1hZ2UuZ2V0QXR0cmlidXRlKCdkYXRhLXNyYycpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIGltYWdlLnJlbW92ZUF0dHJpYnV0ZSgnZGF0YS1zcmMnKTtcbiAgICAgICAgICBpbWFnZS5yZW1vdmVBdHRyaWJ1dGUoJ2RhdGEtc3JjLW1vYmlsZScpO1xuXG4gICAgICAgICAgbGF6eUxvYWRlci5jYWNoZS5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgY29udGludWU7XG4gICAgICAgIH1cblxuICAgICAgICBpKys7XG4gICAgICB9XG5cbiAgICAgIGlmIChsYXp5TG9hZGVyLmNhY2hlLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICBsYXp5TG9hZGVyLnJlbW92ZU9ic2VydmVycygpO1xuICAgICAgfVxuICAgIH0sXG5cbiAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgIC8vIFBhdGNoIElFNy0gKHF1ZXJ5U2VsZWN0b3JBbGwpXG4gICAgICBpZiAoIWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwpIHtcbiAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCA9IGZ1bmN0aW9uKHNlbGVjdG9yKSB7XG4gICAgICAgICAgdmFyIGRvYyA9IGRvY3VtZW50LFxuICAgICAgICAgICAgICBoZWFkID0gZG9jLmRvY3VtZW50RWxlbWVudC5maXJzdENoaWxkLFxuICAgICAgICAgICAgICBzdHlsZVRhZyA9IGRvYy5jcmVhdGVFbGVtZW50KCdTVFlMRScpO1xuICAgICAgICAgIGhlYWQuYXBwZW5kQ2hpbGQoc3R5bGVUYWcpO1xuICAgICAgICAgIGRvYy5fX3FzYWVscyA9IFtdO1xuICAgICAgICAgIHN0eWxlVGFnLnN0eWxlU2hlZXQuY3NzVGV4dCA9IHNlbGVjdG9yICsgJ3t4OmV4cHJlc3Npb24oZG9jdW1lbnQuX19xc2FlbHMucHVzaCh0aGlzKSl9JztcbiAgICAgICAgICB3aW5kb3cuc2Nyb2xsQnkoMCwgMCk7XG4gICAgICAgICAgcmV0dXJuIGRvYy5fX3FzYWVscztcbiAgICAgICAgfTtcbiAgICAgIH1cblxuICAgICAgYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uIF9sYXp5TG9hZGVySW5pdCgpIHtcbiAgICAgICAgdmFyIGltYWdlTm9kZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbWdbZGF0YS1zcmNdJyk7XG5cbiAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBpbWFnZU5vZGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgdmFyIGltYWdlTm9kZSA9IGltYWdlTm9kZXNbaV07XG5cbiAgICAgICAgICAvLyBBZGQgYSBwbGFjZWhvbGRlciBpZiBvbmUgZG9lc24ndCBleGlzdFxuICAgICAgICAgIC8vaW1hZ2VOb2RlLnNyYyA9IGltYWdlTm9kZS5zcmMgfHwgbGF6eUxvYWRlci50aW55R2lmO1xuXG4gICAgICAgICAgbGF6eUxvYWRlci5jYWNoZS5wdXNoKGltYWdlTm9kZSk7XG4gICAgICAgIH1cblxuICAgICAgICBsYXp5TG9hZGVyLmFkZE9ic2VydmVycygpO1xuICAgICAgICBsYXp5TG9hZGVyLmxvYWRWaXNpYmxlSW1hZ2VzKCk7XG5cbiAgICAgICAgcmVtb3ZlRXZlbnRMaXN0ZW5lcignbG9hZCcsIF9sYXp5TG9hZGVySW5pdCwgZmFsc2UpO1xuICAgICAgfSk7XG4gICAgfVxuICB9O1xuXG4gIHJldHVybiBsYXp5TG9hZGVyO1xufVxuXG5leHBvcnQge2xhenlMb2FkZXJGbn07XG4iLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIG1lbnVfdG9nZ2xlID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgIHZhciAkYm9keSA9ICQoJ2JvZHknKSxcbiAgICAgICAgJG1lbnVfdG9nZ2xlID0gJCgnI21lbnUtdG9nZ2xlJyk7XG5cbiAgICAkKCcjbWVudS1pY29uJykub24oJ2NsaWNrJywgZnVuY3Rpb24oZXYpe1xuICAgICAgdmFyICRtZW51X2ljb24gPSAkbWVudV9pY29uIHx8ICQoJyNtZW51LWljb24nKTtcblxuICAgICAgY29uc29sZS5sb2coJG1lbnVfdG9nZ2xlKTtcblxuICAgICAgaWYgKCQoJyNtZW51LXRvZ2dsZScpLmlzKCc6Y2hlY2tlZCcpID09PSB0cnVlKXtcbiAgICAgICAgY29uc29sZS5sb2coJzEnKTtcbiAgICAgICAgJCgnYm9keScpLnJlbW92ZUNsYXNzKCdtZW51LW9wZW4nKTtcbiAgICAgICAgJG1lbnVfaWNvbi5odG1sKCfimLAnKS5yZW1vdmVDbGFzcygnbXItMyBtdC0wJyk7XG4gICAgICB9XG4gICAgICBlbHNle1xuICAgICAgICBjb25zb2xlLmxvZygnMCcpOyAgICAgIFxuICAgICAgICAkKCdib2R5JykuYWRkQ2xhc3MoJ21lbnUtb3BlbicpOyAgICAgIFxuICAgICAgICAkbWVudV9pY29uLmh0bWwoJ8OXJykuYWRkQ2xhc3MoJ21yLTMgbXQtMCcpO1xuICAgICAgfVxuICAgIH0pO1xuICB9XG59XG5cbmV4cG9ydCB7XG4gIG1lbnVfdG9nZ2xlXG59O1xuXG5cblxuXG4iLCJcInVzZSBzdHJpY3RcIjtcblxuJCA9IGpRdWVyeS5ub0NvbmZsaWN0KHRydWUpO1xuXG5pbXBvcnQge3NlYXJjaH0gZnJvbSBcIi4vc2VhcmNoLmpzXCI7XG5pbXBvcnQge21lbnVfdG9nZ2xlfSBmcm9tIFwiLi9tZW51LXRvZ2dsZS5qc1wiO1xuaW1wb3J0IHtiYWNrX3RvX3RvcH0gZnJvbSBcIi4vYmFjay10by10b3AuanNcIjtcbmltcG9ydCB7Ym90X3N1Ym1pc3Npb259IGZyb20gXCIuL3N1Ym1pdC15b3VyLWJvdC5qc1wiO1xuXG4vKiBUT0RPOiBMZWdhY3kgY29kZSwgbmVlZHMgYSBjbGVhbnVwLiAqL1xuXG5pbXBvcnQge3Ntb290aF9zY3JvbGxfZm59IGZyb20gXCIuL3Ntb290aC1zY3JvbGwuanNcIjtcbmltcG9ydCB7dW5zaGlmdF9lbGVtZW50c30gZnJvbSBcIi4vaGVscGVycy5qc1wiO1xuaW1wb3J0IHtsYXp5TG9hZGVyRm59IGZyb20gXCIuL2xhenktbG9hZC1pbWFnZXMuanNcIjtcblxuLyogRU5EIFRPRE8gKi9cblxuXG4kKGZ1bmN0aW9uKCkge1xuICBzZWFyY2guaW5pdCgpO1xuICBtZW51X3RvZ2dsZS5pbml0KCk7XG4gIGJhY2tfdG9fdG9wLmluaXQoKTtcbiAgYm90X3N1Ym1pc3Npb24uaW5pdCgpO1xuXG5cblxuXG5cblxuXG4gIC8qIFRPRE86IExlZ2FjeSBjb2RlLCBuZWVkcyBhIGNsZWFudXAuICovXG5cbiAgdmFyIGFydGljbGVJbWFnZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbWcud3AtcG9zdC1pbWFnZScpO1xuICBcbiAgZm9yICh2YXIgaSA9IDAsIGogPSBhcnRpY2xlSW1hZ2VzLmxlbmd0aDsgaSA8IGo7IGkrKyl7XG4gICAgYXJ0aWNsZUltYWdlc1tpXS5kYXRhc2V0LnNyYyA9IGFydGljbGVJbWFnZXNbaV0uc3JjO1xuICAgIC8vIGFydGljbGVJbWFnZXNbaV0uY2xhc3NMaXN0LmFkZCgnbGF6eS1sb2FkJyk7XG4gIH1cblxuICB1bnNoaWZ0X2VsZW1lbnRzKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3NoaWZ0ZWQnKSk7XG4gIHNtb290aF9zY3JvbGxfZm4oKTtcbiAgbGF6eUxvYWRlckZuKCkuaW5pdCgpO1xufSk7XG4iLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIHNlYXJjaCA9IHtcbiAgaW5pdDogZnVuY3Rpb24oKXtcbiAgICB2YXIgJHNlYXJjaF9maWx0ZXJfY2hlY2tib3hlcyA9ICQoJyNzZWFyY2gtZmlsdGVycycpLmZpbmQoJ2lucHV0W3R5cGU9XCJjaGVja2JveFwiXScpLFxuICAgICAgICAkc2VhcmNoX2ZpbHRlcl90dXRvcmlhbHMgPSAkKCcjc2VhcmNoLWZpbHRlcnMtdHV0b3JpYWxzJyksXG4gICAgICAgICRzZWFyY2hfZmlsdGVyX3Jlc291cmNlcyA9ICQoJyNzZWFyY2gtZmlsdGVycy1yZXNvdXJjZXMnKTtcblxuICAgICQoJyNzZWFyY2gtaW5wdXQnKS5jbGljayhmdW5jdGlvbihldil7XG4gICAgICAkKCcjc2VhcmNoLWZpbHRlcnMnKS5yZW1vdmVDbGFzcygnc2xpZGUtdXAtaGlkZGVuJykuYWRkQ2xhc3MoJ3NsaWRlLXVwJyk7XG4gICAgfSk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgc2VhcmNoXG59O1xuXG5cblxuXG4iLCJcInVzZSBzdHJpY3RcIjtcbi8qXG4gIGh0dHBzOi8vZ2l0aHViLmNvbS9hbGljZWxpZXV0aWVyL3Ntb290aFNjcm9sbFxuICBNb2RpZmllZCBieSBTdGVmYW4gQm9oYWNlayB0byB1c2UgdGhlIEhUTUw1IEhpc3RvcnkgQVBJLlxuKi9cbmZ1bmN0aW9uIHNtb290aF9zY3JvbGxfZm4oKXtcbiAgaWYoZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCA9PT0gdm9pZCAwIHx8IHdpbmRvdy5wYWdlWU9mZnNldCA9PT0gdm9pZCAwIHx8IGhpc3RvcnkucHVzaFN0YXRlID09PSB2b2lkIDApIHsgcmV0dXJuOyB9XG5cbiAgdmFyIGdldFRvcCA9IGZ1bmN0aW9uKGVsZW1lbnQpIHtcblxuICAgICAgaWYoZWxlbWVudC5ub2RlTmFtZSA9PT0gJ0hUTUwnKXtcbiAgICAgICAgcmV0dXJuIC13aW5kb3cucGFnZVlPZmZzZXQ7XG4gICAgICB9XG4gICAgICByZXR1cm4gZWxlbWVudC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS50b3AgKyB3aW5kb3cucGFnZVlPZmZzZXQ7XG4gIH07XG4gIHZhciBlYXNlSW5PdXRDdWJpYyA9IGZ1bmN0aW9uICh0KSB7IHJldHVybiB0PDAuNSA/IDQqdCp0KnQgOiAodC0xKSooMip0LTIpKigyKnQtMikrMTsgfTtcbiAgdmFyIHBvc2l0aW9uID0gZnVuY3Rpb24oc3RhcnQsIGVuZCwgZWxhcHNlZCwgZHVyYXRpb24pIHtcbiAgICAgIGlmIChlbGFwc2VkID4gZHVyYXRpb24pe1xuICAgICAgICByZXR1cm4gZW5kO1xuICAgICAgfVxuICAgICAgcmV0dXJuIHN0YXJ0ICsgKGVuZCAtIHN0YXJ0KSAqIGVhc2VJbk91dEN1YmljKGVsYXBzZWQgLyBkdXJhdGlvbik7IC8vIDwtLSB5b3UgY2FuIGNoYW5nZSB0aGUgZWFzaW5nIGZ1bnRpb24gdGhlcmVcbiAgfTtcbiAgdmFyIHNtb290aFNjcm9sbCA9IGZ1bmN0aW9uKGVsLCBkdXJhdGlvbiwgY2FsbGJhY2spe1xuICAgICAgZHVyYXRpb24gPSBkdXJhdGlvbiB8fCA1MDA7XG4gICAgICB2YXIgc3RhcnQgPSB3aW5kb3cucGFnZVlPZmZzZXQsXG4gICAgICAgICAgZW5kO1xuICAgICAgaWYgKHR5cGVvZiBlbCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgZW5kID0gcGFyc2VJbnQoZWwpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgZW5kID0gZ2V0VG9wKGVsKSAtIDUwO1xuICAgICAgfVxuICAgICAgdmFyIGNsb2NrID0gRGF0ZS5ub3coKTtcbiAgICAgIHZhciByZXF1ZXN0QW5pbWF0aW9uRnJhbWUgPSB3aW5kb3cucmVxdWVzdEFuaW1hdGlvbkZyYW1lIHx8XG4gICAgICAgICAgd2luZG93Lm1velJlcXVlc3RBbmltYXRpb25GcmFtZSB8fCB3aW5kb3cud2Via2l0UmVxdWVzdEFuaW1hdGlvbkZyYW1lIHx8XG4gICAgICAgICAgZnVuY3Rpb24oZm4pe3dpbmRvdy5zZXRUaW1lb3V0KGZuLCAxNSk7fTtcbiAgICAgIHZhciBzdGVwID0gZnVuY3Rpb24oKXtcbiAgICAgICAgICB2YXIgZWxhcHNlZCA9IERhdGUubm93KCkgLSBjbG9jaztcbiAgICAgICAgICB3aW5kb3cuc2Nyb2xsKDAsIHBvc2l0aW9uKHN0YXJ0LCBlbmQsIGVsYXBzZWQsIGR1cmF0aW9uKSk7XG4gICAgICAgICAgaWYgKGVsYXBzZWQgPiBkdXJhdGlvbikge1xuICAgICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICBjYWxsYmFjayhlbCk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICByZXF1ZXN0QW5pbWF0aW9uRnJhbWUoc3RlcCk7XG4gICAgICAgICAgfVxuICAgICAgfTtcbiAgICAgIHN0ZXAoKTtcbiAgfTtcbiAgdmFyIGxpbmtIYW5kbGVyID0gZnVuY3Rpb24oZXYpIHtcbiAgICAgIGV2LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICB2YXIgaGFzaCA9IHRoaXMuaGFzaC5zdWJzdHJpbmcoMSk7XG4gICAgICBpZiAod2luZG93Lmhpc3RvcnkgJiYgd2luZG93Lmhpc3RvcnkucHVzaFN0YXRlKXtcbiAgICAgICAgaGlzdG9yeS5wdXNoU3RhdGUobnVsbCwgbnVsbCwgJyMnICsgaGFzaCk7XG4gICAgICB9XG4gICAgICBzbW9vdGhTY3JvbGwoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoaGFzaCksIDUwMCwgZnVuY3Rpb24oZWwpIHtcbiAgICAgIH0pO1xuICB9O1xuICB2YXIgaW50ZXJuYWwgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdhW2hyZWZePVwiI1wiXScpLCBhO1xuICBmb3IodmFyIGk9aW50ZXJuYWwubGVuZ3RoOyBhPWludGVybmFsWy0taV07KXtcbiAgICAgIGEuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGxpbmtIYW5kbGVyLCBmYWxzZSk7XG4gIH1cblxuICBpZih3aW5kb3cubG9jYXRpb24uaGFzaCkge1xuICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgIHNtb290aFNjcm9sbChkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh3aW5kb3cubG9jYXRpb24uaGFzaC5zdWJzdHJpbmcoMSkpLCAyMDAsIGZ1bmN0aW9uKGVsKXt9KTsgICAgICBcbiAgICB9LCAzMDApO1xuICB9XG5cblxuICByZXR1cm4gc21vb3RoU2Nyb2xsO1xufVxuXG5leHBvcnQge3Ntb290aF9zY3JvbGxfZm59O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbnZhciBib3Rfc3VibWlzc2lvbiA9IHtcbiAgaW5pdDogZnVuY3Rpb24oKXtcbiAgICB2YXIgJGJvZHkgPSAkKCdib2R5JyksXG4gICAgICAgICRmb3JtX3N1Ym1pdF9idXR0b24gPSAkKCcjYm90LWZvcm0tc3VibWl0Jyk7XG4gICAgaWYgKCRib2R5Lmhhc0NsYXNzKCdwYWdlLXRlbXBsYXRlLXRlbXBsYXRlLXN1Ym1pdC15b3VyLWJvdCcpKXtcblxuICAgICAgJCgnI3Rlc3QnKS5jbGljayhmdW5jdGlvbihldil7XG4gICAgICAgIGV2LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgJCgnI2FkZC1hdXRob3ItZmllbGRzJykuYmVmb3JlKCc8ZGl2IGNsYXNzPVwiYXV0aG9yLWZpZWxkcyBmb3JtLXJvd1wiPjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PGxhYmVsIGZvcj1cImF1dGhvci0ke25ld19pZH0tbmFtZVwiPkF1dGhvclxcJ3MgbmFtZTwvbGFiZWw+PGlucHV0IHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImF1dGhvci0yLW5hbWVcIiBuYW1lPVwiYXV0aG9yLW5hbWVzW11cIiBwbGFjZWhvbGRlcj1cIkF1dGhvclwiPjwvZGl2PjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PGxhYmVsIGZvcj1cImF1dGhvci0yLXVybFwiPkF1dGhvclxcJ3MgVVJMPC9sYWJlbD48aW5wdXQgdHlwZT1cInVybFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgaWQ9XCJhdXRob3ItMi11cmxcIiBuYW1lPVwiYXV0aG9yLXVybHNbXVwiIHBsYWNlaG9sZGVyPVwiaHR0cHM6Ly90d2l0dGVyLmNvbS9hdXRob3JcIj48L2Rpdj48L2Rpdj4nKTtcblxuICAgICAgICAkKCcjYm90LW5hbWUnKS52YWwoJ0Bjb29sYm90Jyk7XG4gICAgICAgICQoJyNhdXRob3ItMS1uYW1lJykudmFsKCdTdGVmYW4nKTtcbiAgICAgICAgJCgnI2F1dGhvci0xLXVybCcpLnZhbCgnaHR0cHM6Ly90d2l0dGVyLmNvbS9mb3VydG9uZmlzaCcpO1xuICAgICAgICAkKCcjYXV0aG9yLTItbmFtZScpLnZhbCgnSm9obiBEb2UnKTtcbiAgICAgICAgLy8gJCgnI2F1dGhvci0yLXVybCcpLnZhbCgnaHR0cHM6Ly90d2l0dGVyLmNvbS9qZCcpO1xuICAgICAgICAkKCcjYm90LWRlc2NyaXB0aW9uJykudmFsKCdUaGlzIGJvdCBnZW5lcmF0ZXMgcmFuZG9tIGltYWdlcy4nKTtcbiAgICAgICAgJCgnI2JvdC11cmxzJykudmFsKCdodHRwczovL3R3aXR0ZXIuY29tL2Nvb2xib3QnKTtcbiAgICAgICAgJCgnI2JvdC1zZWxlY3RlZC10d2VldHMnKS52YWwoJ2h0dHBzOi8vdHdpdHRlci5jb20vbXljb29sYm90L3N0YXR1cy8xMjM0NTY3ODlcXG5odHRwczovL3R3aXR0ZXIuY29tL215Y29vbGJvdC9zdGF0dXMvOTg3NjU0MzIxJyk7XG4gICAgICAgICQoJyNib3QtdGFnbGluZScpLnZhbCgnVGhpcyBpcyBhIGNvb2wgYm90LicpO1xuICAgICAgICAkKCcjYm90LW5ldHdvcmtzJykudmFsKCc1Jyk7XG4gICAgICAgICQoJyNib3QtbmV0d29ya3MnKS50cmlnZ2VyKCdjaGFuZ2UnKTtcbiAgICAgICAgJCgnI2JvdC10YWdzJykudmFsKFsnOTInLCAnNjQnLCAnNTUnXSk7XG4gICAgICAgICQoJyNib3QtdGFncycpLnRyaWdnZXIoJ2NoYW5nZScpO1xuXG4gICAgICAgICQoJyNib3QtaXMtb3BlbnNvdXJjZScpLmNsaWNrKCk7XG5cbiAgICAgICAgJCgnI2JvdC1zb3VyY2UtdXJsJykudmFsKCdodHRwczovL2dpdGh1Yi5jb20vYm90d2lraS9ib3R3aWtpLm9yZycpO1xuICAgICAgICAkKCcjYm90LXNvdXJjZS1sYW5ndWFnZScpLnZhbCgnMjUnKTtcbiAgICAgICAgJCgnI2JvdC1zb3VyY2UtbGFuZ3VhZ2UnKS50cmlnZ2VyKCdjaGFuZ2UnKTtcblxuICAgICAgICAkKCdodG1sLCBib2R5JykuYW5pbWF0ZSh7XG4gICAgICAgICAgICBzY3JvbGxUb3A6ICRmb3JtX3N1Ym1pdF9idXR0b24ub2Zmc2V0KCkudG9wIC0gNTAwXG4gICAgICAgIH0sIDQ1MCk7XG5cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9KTtcblxuICAgICAgJCgnLmpzLXNlbGVjdDInKS5lYWNoKGZ1bmN0aW9uKGkpe1xuICAgICAgICAkKHRoaXMpLnNlbGVjdDIoe1xuICAgICAgICAgIHBsYWNlaG9sZGVyOiAkKHRoaXMpLmF0dHIoJ3BsYWNlaG9sZGVyJylcbiAgICAgICAgfSk7XG4gICAgICB9KTtcblxuXG4gICAgICAkKCcjc3VibWl0LWJvdC1mb3JtJykuc3VibWl0KGZ1bmN0aW9uKCl7XG4gICAgICAgICRmb3JtX3N1Ym1pdF9idXR0b24uYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKS5odG1sKCdQbGVhc2Ugd2FpdC4uLicpO1xuICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgJGZvcm1fc3VibWl0X2J1dHRvbi5odG1sKCdTdGlsbCB3b3JraW5nLi4uJyk7XG4gICAgICAgIH0sIDQ1MDApO1xuICAgICAgfSk7XG5cbiAgICAgICQoJyNhZGQtYXV0aG9yLWZpZWxkcycpLmNsaWNrKGZ1bmN0aW9uKGV2KXtcbiAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICB2YXIgbmV3X2lkID0gJCgnLmF1dGhvci1maWVsZHMnKS5sZW5ndGggKyAxO1xuXG4gICAgICAgICQodGhpcykuYmVmb3JlKCc8ZGl2IGNsYXNzPVwiYXV0aG9yLWZpZWxkcyBmb3JtLXJvd1wiPjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PGxhYmVsIGZvcj1cImF1dGhvci0ke25ld19pZH0tbmFtZVwiPkF1dGhvclxcJ3MgbmFtZTwvbGFiZWw+PGlucHV0IHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImF1dGhvci0yLW5hbWVcIiBuYW1lPVwiYXV0aG9yLW5hbWVzW11cIiBwbGFjZWhvbGRlcj1cIkF1dGhvclwiPjwvZGl2PjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PGxhYmVsIGZvcj1cImF1dGhvci0yLXVybFwiPkF1dGhvclxcJ3MgVVJMPC9sYWJlbD48aW5wdXQgdHlwZT1cInVybFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgaWQ9XCJhdXRob3ItMi11cmxcIiBuYW1lPVwiYXV0aG9yLXVybHNbXVwiIHBsYWNlaG9sZGVyPVwiaHR0cHM6Ly90d2l0dGVyLmNvbS9hdXRob3JcIj48L2Rpdj48L2Rpdj4nKTtcblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9KTtcblxuICAgICAgdmFyICRib3Rfc291cmNlX2luZm8gPSAkKCcjYm90LXNvdXJjZS1pbmZvJyk7XG4gICAgICAkYm90X3NvdXJjZV9pbmZvLmFkZENsYXNzKCdkLW5vbmUnKTtcblxuICAgICAgJCgnI2JvdC1pcy1vcGVuc291cmNlJykuY2xpY2soZnVuY3Rpb24oZXYpe1xuXG4gICAgICAgIGlmICgkKHRoaXMpLmlzKCc6Y2hlY2tlZCcpKXtcbiAgICAgICAgICAkYm90X3NvdXJjZV9pbmZvLnJlbW92ZUNsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNle1xuICAgICAgICAgICRib3Rfc291cmNlX2luZm8uYWRkQ2xhc3MoJ2Qtbm9uZScpO1xuICAgICAgICB9XG4gICAgICB9KTtcblxuICAgICAgdmFyICRib3RfbmV0d29ya3Nfc2VsZWN0ID0gJCgnI2JvdC1uZXR3b3JrcycpLFxuICAgICAgICAgICRzZWxlY3RlZF90d2VldHNfZmllbGQgPSAkKCcjYm90LXNlbGVjdGVkLXR3ZWV0cy1maWVsZCcpO1xuXG5cbiAgICAgICRib3RfbmV0d29ya3Nfc2VsZWN0Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICBpZiAoJGJvdF9uZXR3b3Jrc19zZWxlY3QuY2hpbGRyZW4oXCJvcHRpb25cIikuZmlsdGVyKFwiOnNlbGVjdGVkXCIpLnRleHQoKS5pbmRleE9mKCdUd2l0dGVyJykgPiAtMSl7XG4gICAgICAgICAgJHNlbGVjdGVkX3R3ZWV0c19maWVsZC5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAkc2VsZWN0ZWRfdHdlZXRzX2ZpZWxkLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgfVxuICAgICAgfSk7XG5cblxuICAgIH1cbiAgfVxufVxuXG5leHBvcnQge1xuICBib3Rfc3VibWlzc2lvblxufTtcblxuXG5cblxuIl19
