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
        $menu_icon.html('b').removeClass('mr-3 mt-0');
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
      var enable_selected_tweets_field = function enable_selected_tweets_field() {
        var $bot_networks_select = $('[name="bot-networks[]');

        $bot_networks_select.on('change', function () {
          var show_selected_tweets_field = false;
          $bot_networks_select.each(function (i) {
            var $this = $(this);
            if ($this.children("option").filter(":selected").text().indexOf('Twitter') > -1) {
              show_selected_tweets_field = true;
            }
          });

          if (show_selected_tweets_field) {
            $selected_tweets_field.removeClass('d-none');
          } else {
            $selected_tweets_field.addClass('d-none');
          }
        });
      };

      $('#test').click(function (ev) {
        ev.preventDefault();

        $('#add-author-fields').before('<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

        $('#bot-name').val('@coolbot');
        $('#author-1-name').val('Stefan');
        $('#author-1-url').val('https://twitter.com/fourtonfish');
        $('#author-2-name').val('John Doe');
        // $('#author-2-url').val('https://twitter.com/jd');
        $('#bot-description').val('generates random images.');

        var bot_info_network_select_html = $('#bot-info-1-network').html();

        $('#add-bot-info-fields').before('<div class="bot-info-fields form-row"><div class="form-group col-md-6"><select class="form-control js-select2" id="bot-info-2-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">' + bot_info_network_select_html.replace('-1-', '-2-') + '</select></div><div class="form-group col-md-6"><input type="url" class="form-control" id="bot-info-2-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot2"></div></div>');

        $('#bot-info-2-network').select2({
          tags: true,
          placeholder: $(this).attr('placeholder')
        });

        $('#bot-info-1-network').val('twitter-bots');
        $('#bot-info-1-network').trigger('change');

        $('#bot-info-2-network').val('tumblr-bots');
        $('#bot-info-2-network').trigger('change');

        $('#bot-info-1-url').val('https://twitter.com/coolbot');
        $('#bot-info-2-url').val('https://coolbot.tumblr.com/');

        $('#bot-selected-tweets').val('https://twitter.com/mycoolbot/status/123456789\nhttps://twitter.com/mycoolbot/status/987654321');
        $('#bot-tagline').val('This is a cool bot.');
        $('#bot-tags').val(['generative', 'images', 'nodejs']);
        $('#bot-tags').trigger('change');

        $('#bot-is-opensource').click();

        $('#bot-source-url').val('https://github.com/botwiki/botwiki.org');
        $('#bot-source-language').val('nodejs');
        $('#bot-source-language').trigger('change');

        $('html, body').animate({
          scrollTop: $form_submit_button.offset().top - 500
        }, 450);

        return false;
      });

      $('.js-select2').each(function (i) {
        $(this).select2({
          tags: true,
          placeholder: $(this).attr('placeholder')
        });
      });

      $('#submit-bot-form').submit(function () {
        $form_submit_button.attr('disabled', 'disabled').html('Please wait...');
        setTimeout(function () {
          $form_submit_button.html('Still working...');
        }, 4700);
      });

      $('#add-author-fields').click(function (ev) {
        ev.preventDefault();

        var new_id = $('.author-fields').length + 1;

        $(this).before('<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-' + new_id + '-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-' + new_id + '-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

        return false;
      });

      var bot_info_network_select_html = $('#bot-info-1-network').html();

      $('#add-bot-info-fields').click(function (ev) {
        ev.preventDefault();

        var new_id = $('.bot-info-fields').length + 1;

        $(this).before('<div class="bot-info-fields form-row"><div class="form-group col-md-6"><select class="form-control js-select2" id="bot-info-' + new_id + '-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">' + bot_info_network_select_html.replace('-1-', new_id) + '</select></div><div class="form-group col-md-6"><input type="url" class="form-control" id="bot-' + new_id + '-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot' + new_id + '"></div></div>');

        $('#bot-info-' + new_id + '-network').select2({
          tags: true,
          placeholder: $(this).attr('placeholder')
        });

        enable_selected_tweets_field();

        return false;
      });

      var $bot_source_info = $('#bot-source-info');

      $('#bot-is-opensource').click(function (ev) {

        if ($(this).is(':checked')) {
          $bot_source_info.removeClass('d-none');
        } else {
          $bot_source_info.addClass('d-none');
        }
      });

      var $apply_for_botmaker_badge = $('#apply-for-botmaker-badge'),
          $botaker_badge_application = $('#botmaker-badge-application');

      $apply_for_botmaker_badge.click(function (ev) {

        if ($(this).is(':checked')) {
          $botaker_badge_application.removeClass('d-none');
        } else {
          $botaker_badge_application.addClass('d-none');
        }
      });

      var $selected_tweets_field = $('#bot-selected-tweets-field');

      enable_selected_tweets_field();
    }
  }
};

exports.bot_submission = bot_submission;

},{}]},{},[5])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvc2NyaXB0cy9iYWNrLXRvLXRvcC5qcyIsInNyYy9zY3JpcHRzL2hlbHBlcnMuanMiLCJzcmMvc2NyaXB0cy9sYXp5LWxvYWQtaW1hZ2VzLmpzIiwic3JjL3NjcmlwdHMvbWVudS10b2dnbGUuanMiLCJzcmMvc2NyaXB0cy9zY3JpcHRzLmpzIiwic3JjL3NjcmlwdHMvc2VhcmNoLmpzIiwic3JjL3NjcmlwdHMvc21vb3RoLXNjcm9sbC5qcyIsInNyYy9zY3JpcHRzL3N1Ym1pdC15b3VyLWJvdC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksY0FBYztBQUNoQixRQUFNLGdCQUFVO0FBQ2QsTUFBRSxNQUFGLEVBQVUsTUFBVixDQUFpQixZQUFVO0FBQ3pCO0FBQ0EsVUFBSSxZQUFZLFNBQVMsY0FBVCxDQUF3QixhQUF4QixDQUFoQjtBQUFBLFVBQ0ksb0JBQW9CLE9BQU8sV0FBUCxJQUFzQixTQUFTLGVBQVQsQ0FBeUIsU0FBL0MsSUFBNEQsU0FBUyxJQUFULENBQWMsU0FBMUUsSUFBdUYsQ0FEL0c7O0FBR0EsVUFBSSxvQkFBcUIsT0FBTyxNQUFQLEdBQWMsQ0FBdkMsRUFBMEM7QUFDeEMsa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixVQUF4QjtBQUNBLGtCQUFVLFNBQVYsQ0FBb0IsTUFBcEIsQ0FBMkIsWUFBM0I7QUFDRCxPQUhELE1BSUk7QUFDRixrQkFBVSxTQUFWLENBQW9CLE1BQXBCLENBQTJCLFVBQTNCO0FBQ0Esa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixZQUF4QjtBQUNEO0FBQ0YsS0FiRDtBQWNEO0FBaEJlLENBQWxCOztRQW9CRSxXLEdBQUEsVzs7O0FDdEJGOzs7OztBQUVBLFNBQVMsZ0JBQVQsQ0FBMEIsV0FBMUIsRUFBc0M7QUFDcEMsTUFBSSxZQUFZLE1BQVosR0FBcUIsQ0FBekIsRUFBMkI7QUFD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OiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIGJhY2tfdG9fdG9wID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgICQod2luZG93KS5zY3JvbGwoZnVuY3Rpb24oKXtcbiAgICAgIC8qIFRPRE86IExlZ2FjeSBzY3JpcHQsIHJld3JpdGUgd2l0aCBqUXVlcnkuICovXG4gICAgICB2YXIgYmFja1RvVG9wID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhY2stdG8tdG9wJyksXG4gICAgICAgICAgZG9jdW1lbnRTY3JvbGxUb3AgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcCB8fCBkb2N1bWVudC5ib2R5LnNjcm9sbFRvcCB8fCAwO1xuXG4gICAgICBpZiAoZG9jdW1lbnRTY3JvbGxUb3AgPiAoc2NyZWVuLmhlaWdodC8yKSl7XG4gICAgICAgIGJhY2tUb1RvcC5jbGFzc0xpc3QuYWRkKCdzbGlkZS11cCcpO1xuICAgICAgICBiYWNrVG9Ub3AuY2xhc3NMaXN0LnJlbW92ZSgnc2xpZGUtZG93bicpO1xuICAgICAgfVxuICAgICAgZWxzZXtcbiAgICAgICAgYmFja1RvVG9wLmNsYXNzTGlzdC5yZW1vdmUoJ3NsaWRlLXVwJyk7XG4gICAgICAgIGJhY2tUb1RvcC5jbGFzc0xpc3QuYWRkKCdzbGlkZS1kb3duJyk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgYmFja190b190b3Bcbn07XG4iLCJcInVzZSBzdHJpY3RcIjtcblxuZnVuY3Rpb24gdW5zaGlmdF9lbGVtZW50cyhzaGlmdGVkX2Vscyl7XG4gIGlmIChzaGlmdGVkX2Vscy5sZW5ndGggPiAwKXtcbiAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHNoaWZ0ZWRfZWxzLCBmdW5jdGlvbihlbCwgaW5kZXgpIHtcbiAgICAgIHNldFRpbWVvdXQoXG4gICAgICAgIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgZWwuY2xhc3NMaXN0LnJlbW92ZSgnc2hpZnRlZCcpO1xuICAgICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoJ3Vuc2hpZnRlZCcpO1xuICAgICAgICB9LFxuICAgICAgICA1NSAqIGluZGV4ICogaW5kZXgpO1xuICAgIH0pO1xuICB9ICBcbn1cblxuZXhwb3J0IHtcbiAgdW5zaGlmdF9lbGVtZW50c1xufTtcbiIsIlwidXNlIHN0cmljdFwiO1xuLypcbiAgTGF6eSBMb2FkIEltYWdlcyB3aXRob3V0IGpRdWVyeVxuICBodHRwOi8va2FpemF1LmdpdGh1Yi5jb20vTGF6eS1Mb2FkLUltYWdlcy13aXRob3V0LWpRdWVyeS9cbiAgT3JpZ2luYWwgYnkgTWlrZSBQdWxhc2tpIC0gaHR0cDovL3d3dy5taWtlcHVsYXNraS5jb21cbiAgTW9kaWZpZWQgYnkgS2FpIFphdSAtIGh0dHA6Ly9rYWl6YXUuY29tXG4qL1xuXG5mdW5jdGlvbiBsYXp5TG9hZGVyRm4oKSB7XG4gIHZhciBhZGRFdmVudExpc3RlbmVyID0gIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyIHx8IGZ1bmN0aW9uKG4sZikgeyB3aW5kb3cuYXR0YWNoRXZlbnQoJ29uJytuLCBmKTsgfSxcbiAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIgPSB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lciB8fCBmdW5jdGlvbihuLGYsYikgeyB3aW5kb3cuZGV0YWNoRXZlbnQoJ29uJytuLCBmKTsgfTtcblxuICAvLyBGb3IgSUU3IGNvbXBhdGliaWxpdHlcbiAgLy8gQWRhcHRlZCBmcm9tIGh0dHA6Ly93d3cucXVpcmtzbW9kZS5vcmcvanMvZmluZHBvcy5odG1sXG4gIGZ1bmN0aW9uIGdldE9mZnNldFRvcChlbCkge1xuICAgIHZhciB2YWwgPSAwO1xuICAgIGlmIChlbC5vZmZzZXRQYXJlbnQpIHtcbiAgICAgIGRvIHtcbiAgICAgICAgdmFsICs9IGVsLm9mZnNldFRvcDtcbiAgICAgIH0gd2hpbGUgKGVsID0gZWwub2Zmc2V0UGFyZW50KTtcbiAgICAgIHJldHVybiB2YWw7XG4gICAgfVxuICB9ICAgICAgXG5cbiAgdmFyIGxhenlMb2FkZXIgPSB7XG4gICAgY2FjaGU6IFtdLFxuICAgIG1vYmlsZVNjcmVlblNpemU6IDUwMCxcbiAgICAvL3RpbnlHaWY6ICdkYXRhOmltYWdlL2dpZjtiYXNlNjQsUjBsR09EbGhBUUFCQUFBQUFDSDVCQUVLQUFFQUxBQUFBQUFCQUFFQUFBSUNUQUVBT3c9PScsXG5cbiAgICBhZGRPYnNlcnZlcnM6IGZ1bmN0aW9uKCkge1xuICAgICAgYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgbGF6eUxvYWRlci50aHJvdHRsZWRMb2FkKTtcbiAgICAgIGFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCk7XG4gICAgfSxcblxuICAgIHJlbW92ZU9ic2VydmVyczogZnVuY3Rpb24oKSB7XG4gICAgICByZW1vdmVFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBsYXp5TG9hZGVyLnRocm90dGxlZExvYWQsIGZhbHNlKTtcbiAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCwgZmFsc2UpO1xuICAgIH0sXG5cbiAgICB0aHJvdHRsZVRpbWVyOiBuZXcgRGF0ZSgpLmdldFRpbWUoKSxcblxuICAgIHRocm90dGxlZExvYWQ6IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIG5vdyA9IG5ldyBEYXRlKCkuZ2V0VGltZSgpO1xuICAgICAgaWYgKChub3cgLSBsYXp5TG9hZGVyLnRocm90dGxlVGltZXIpID49IDIwMCkge1xuICAgICAgICBsYXp5TG9hZGVyLnRocm90dGxlVGltZXIgPSBub3c7XG4gICAgICAgIGxhenlMb2FkZXIubG9hZFZpc2libGVJbWFnZXMoKTtcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgbG9hZFZpc2libGVJbWFnZXM6IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIHNjcm9sbFkgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcDtcbiAgICAgIHZhciBwYWdlSGVpZ2h0ID0gd2luZG93LmlubmVySGVpZ2h0IHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQ7XG4gICAgICB2YXIgcmFuZ2UgPSB7XG4gICAgICAgIG1pbjogc2Nyb2xsWSAtIDIwMCxcbiAgICAgICAgbWF4OiBzY3JvbGxZICsgcGFnZUhlaWdodCArIDIwMFxuICAgICAgfTtcblxuICAgICAgdmFyIGkgPSAwO1xuICAgICAgd2hpbGUgKGkgPCBsYXp5TG9hZGVyLmNhY2hlLmxlbmd0aCkge1xuICAgICAgICB2YXIgaW1hZ2UgPSBsYXp5TG9hZGVyLmNhY2hlW2ldO1xuICAgICAgICB2YXIgaW1hZ2VQb3NpdGlvbiA9IGdldE9mZnNldFRvcChpbWFnZSk7XG4gICAgICAgIHZhciBpbWFnZUhlaWdodCA9IGltYWdlLmhlaWdodCB8fCAwO1xuXG4gICAgICAgIGlmICgoaW1hZ2VQb3NpdGlvbiA+PSByYW5nZS5taW4gLSBpbWFnZUhlaWdodCkgJiYgKGltYWdlUG9zaXRpb24gPD0gcmFuZ2UubWF4KSkge1xuICAgICAgICAgIHZhciBtb2JpbGVTcmMgPSBpbWFnZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3JjLW1vYmlsZScpO1xuXG4gICAgICAgICAgLy8gaW1hZ2Uub25sb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgLy8gICB0aGlzLmNsYXNzTmFtZSA9IHRoaXMuY2xhc3NOYW1lLnJlcGxhY2UoLyhefFxccyspbGF6eS1sb2FkKFxccyt8JCkvLCAnJDFsYXp5LWxvYWRlZCQyJyk7XG4gICAgICAgICAgLy8gfTtcbi8vICAgICAgICBUZW1wb3JhcnkgZml4IGZvciBTYWZhcmkhXG5cbiAgICAgICAgICBpbWFnZS5jbGFzc05hbWUgPSBpbWFnZS5jbGFzc05hbWUucmVwbGFjZSgvKF58XFxzKylsYXp5LWxvYWQoXFxzK3wkKS8sICckMWxhenktbG9hZGVkJDInKTtcblxuXG4gICAgICAgICAgaWYgKG1vYmlsZVNyYyAmJiBzY3JlZW4ud2lkdGggPD0gbGF6eUxvYWRlci5tb2JpbGVTY3JlZW5TaXplKSB7XG4gICAgICAgICAgICBpbWFnZS5zcmMgPSBtb2JpbGVTcmM7XG4gICAgICAgICAgfVxuICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgaW1hZ2Uuc3JjID0gaW1hZ2UuZ2V0QXR0cmlidXRlKCdkYXRhLXNyYycpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIGltYWdlLnJlbW92ZUF0dHJpYnV0ZSgnZGF0YS1zcmMnKTtcbiAgICAgICAgICBpbWFnZS5yZW1vdmVBdHRyaWJ1dGUoJ2RhdGEtc3JjLW1vYmlsZScpO1xuXG4gICAgICAgICAgbGF6eUxvYWRlci5jYWNoZS5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgY29udGludWU7XG4gICAgICAgIH1cblxuICAgICAgICBpKys7XG4gICAgICB9XG5cbiAgICAgIGlmIChsYXp5TG9hZGVyLmNhY2hlLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICBsYXp5TG9hZGVyLnJlbW92ZU9ic2VydmVycygpO1xuICAgICAgfVxuICAgIH0sXG5cbiAgICBpbml0OiBmdW5jdGlvbigpIHtcbiAgICAgIC8vIFBhdGNoIElFNy0gKHF1ZXJ5U2VsZWN0b3JBbGwpXG4gICAgICBpZiAoIWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwpIHtcbiAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCA9IGZ1bmN0aW9uKHNlbGVjdG9yKSB7XG4gICAgICAgICAgdmFyIGRvYyA9IGRvY3VtZW50LFxuICAgICAgICAgICAgICBoZWFkID0gZG9jLmRvY3VtZW50RWxlbWVudC5maXJzdENoaWxkLFxuICAgICAgICAgICAgICBzdHlsZVRhZyA9IGRvYy5jcmVhdGVFbGVtZW50KCdTVFlMRScpO1xuICAgICAgICAgIGhlYWQuYXBwZW5kQ2hpbGQoc3R5bGVUYWcpO1xuICAgICAgICAgIGRvYy5fX3FzYWVscyA9IFtdO1xuICAgICAgICAgIHN0eWxlVGFnLnN0eWxlU2hlZXQuY3NzVGV4dCA9IHNlbGVjdG9yICsgJ3t4OmV4cHJlc3Npb24oZG9jdW1lbnQuX19xc2FlbHMucHVzaCh0aGlzKSl9JztcbiAgICAgICAgICB3aW5kb3cuc2Nyb2xsQnkoMCwgMCk7XG4gICAgICAgICAgcmV0dXJuIGRvYy5fX3FzYWVscztcbiAgICAgICAgfTtcbiAgICAgIH1cblxuICAgICAgYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uIF9sYXp5TG9hZGVySW5pdCgpIHtcbiAgICAgICAgdmFyIGltYWdlTm9kZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbWdbZGF0YS1zcmNdJyk7XG5cbiAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBpbWFnZU5vZGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgdmFyIGltYWdlTm9kZSA9IGltYWdlTm9kZXNbaV07XG5cbiAgICAgICAgICAvLyBBZGQgYSBwbGFjZWhvbGRlciBpZiBvbmUgZG9lc24ndCBleGlzdFxuICAgICAgICAgIC8vaW1hZ2VOb2RlLnNyYyA9IGltYWdlTm9kZS5zcmMgfHwgbGF6eUxvYWRlci50aW55R2lmO1xuXG4gICAgICAgICAgbGF6eUxvYWRlci5jYWNoZS5wdXNoKGltYWdlTm9kZSk7XG4gICAgICAgIH1cblxuICAgICAgICBsYXp5TG9hZGVyLmFkZE9ic2VydmVycygpO1xuICAgICAgICBsYXp5TG9hZGVyLmxvYWRWaXNpYmxlSW1hZ2VzKCk7XG5cbiAgICAgICAgcmVtb3ZlRXZlbnRMaXN0ZW5lcignbG9hZCcsIF9sYXp5TG9hZGVySW5pdCwgZmFsc2UpO1xuICAgICAgfSk7XG4gICAgfVxuICB9O1xuXG4gIHJldHVybiBsYXp5TG9hZGVyO1xufVxuXG5leHBvcnQge2xhenlMb2FkZXJGbn07XG4iLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIG1lbnVfdG9nZ2xlID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgIHZhciAkYm9keSA9ICQoJ2JvZHknKSxcbiAgICAgICAgJG1lbnVfdG9nZ2xlID0gJCgnI21lbnUtdG9nZ2xlJyk7XG5cbiAgICAkKCcjbWVudS1pY29uJykub24oJ2NsaWNrJywgZnVuY3Rpb24oZXYpe1xuICAgICAgdmFyICRtZW51X2ljb24gPSAkbWVudV9pY29uIHx8ICQoJyNtZW51LWljb24nKTtcblxuICAgICAgY29uc29sZS5sb2coJG1lbnVfdG9nZ2xlKTtcblxuICAgICAgaWYgKCQoJyNtZW51LXRvZ2dsZScpLmlzKCc6Y2hlY2tlZCcpID09PSB0cnVlKXtcbiAgICAgICAgY29uc29sZS5sb2coJzEnKTtcbiAgICAgICAgJCgnYm9keScpLnJlbW92ZUNsYXNzKCdtZW51LW9wZW4nKTtcbiAgICAgICAgJG1lbnVfaWNvbi5odG1sKCdiJykucmVtb3ZlQ2xhc3MoJ21yLTMgbXQtMCcpO1xuICAgICAgfVxuICAgICAgZWxzZXtcbiAgICAgICAgY29uc29sZS5sb2coJzAnKTsgICAgICBcbiAgICAgICAgJCgnYm9keScpLmFkZENsYXNzKCdtZW51LW9wZW4nKTsgICAgICBcbiAgICAgICAgJG1lbnVfaWNvbi5odG1sKCfDlycpLmFkZENsYXNzKCdtci0zIG10LTAnKTtcbiAgICAgIH1cbiAgICB9KTtcbiAgfVxufVxuXG5leHBvcnQge1xuICBtZW51X3RvZ2dsZVxufTtcblxuXG5cblxuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbiQgPSBqUXVlcnkubm9Db25mbGljdCh0cnVlKTtcblxuaW1wb3J0IHtzZWFyY2h9IGZyb20gXCIuL3NlYXJjaC5qc1wiO1xuaW1wb3J0IHttZW51X3RvZ2dsZX0gZnJvbSBcIi4vbWVudS10b2dnbGUuanNcIjtcbmltcG9ydCB7YmFja190b190b3B9IGZyb20gXCIuL2JhY2stdG8tdG9wLmpzXCI7XG5pbXBvcnQge2JvdF9zdWJtaXNzaW9ufSBmcm9tIFwiLi9zdWJtaXQteW91ci1ib3QuanNcIjtcblxuLyogVE9ETzogTGVnYWN5IGNvZGUsIG5lZWRzIGEgY2xlYW51cC4gKi9cblxuaW1wb3J0IHtzbW9vdGhfc2Nyb2xsX2ZufSBmcm9tIFwiLi9zbW9vdGgtc2Nyb2xsLmpzXCI7XG5pbXBvcnQge3Vuc2hpZnRfZWxlbWVudHN9IGZyb20gXCIuL2hlbHBlcnMuanNcIjtcbmltcG9ydCB7bGF6eUxvYWRlckZufSBmcm9tIFwiLi9sYXp5LWxvYWQtaW1hZ2VzLmpzXCI7XG5cbi8qIEVORCBUT0RPICovXG5cblxuJChmdW5jdGlvbigpIHtcbiAgc2VhcmNoLmluaXQoKTtcbiAgbWVudV90b2dnbGUuaW5pdCgpO1xuICBiYWNrX3RvX3RvcC5pbml0KCk7XG4gIGJvdF9zdWJtaXNzaW9uLmluaXQoKTtcblxuXG5cblxuXG5cblxuICAvKiBUT0RPOiBMZWdhY3kgY29kZSwgbmVlZHMgYSBjbGVhbnVwLiAqL1xuXG4gIHZhciBhcnRpY2xlSW1hZ2VzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnaW1nLndwLXBvc3QtaW1hZ2UnKTtcbiAgXG4gIGZvciAodmFyIGkgPSAwLCBqID0gYXJ0aWNsZUltYWdlcy5sZW5ndGg7IGkgPCBqOyBpKyspe1xuICAgIGFydGljbGVJbWFnZXNbaV0uZGF0YXNldC5zcmMgPSBhcnRpY2xlSW1hZ2VzW2ldLnNyYztcbiAgICAvLyBhcnRpY2xlSW1hZ2VzW2ldLmNsYXNzTGlzdC5hZGQoJ2xhenktbG9hZCcpO1xuICB9XG5cbiAgdW5zaGlmdF9lbGVtZW50cyhkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdzaGlmdGVkJykpO1xuICBzbW9vdGhfc2Nyb2xsX2ZuKCk7XG4gIGxhenlMb2FkZXJGbigpLmluaXQoKTtcbn0pO1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG5cbnZhciBzZWFyY2ggPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgdmFyICRzZWFyY2hfZmlsdGVyX2NoZWNrYm94ZXMgPSAkKCcjc2VhcmNoLWZpbHRlcnMnKS5maW5kKCdpbnB1dFt0eXBlPVwiY2hlY2tib3hcIl0nKSxcbiAgICAgICAgJHNlYXJjaF9maWx0ZXJfdHV0b3JpYWxzID0gJCgnI3NlYXJjaC1maWx0ZXJzLXR1dG9yaWFscycpLFxuICAgICAgICAkc2VhcmNoX2ZpbHRlcl9yZXNvdXJjZXMgPSAkKCcjc2VhcmNoLWZpbHRlcnMtcmVzb3VyY2VzJyk7XG5cbiAgICAkKCcjc2VhcmNoLWlucHV0JykuY2xpY2soZnVuY3Rpb24oZXYpe1xuICAgICAgJCgnI3NlYXJjaC1maWx0ZXJzJykucmVtb3ZlQ2xhc3MoJ3NsaWRlLXVwLWhpZGRlbicpLmFkZENsYXNzKCdzbGlkZS11cCcpO1xuICAgIH0pO1xuICB9XG59XG5cbmV4cG9ydCB7XG4gIHNlYXJjaFxufTtcblxuXG5cblxuIiwiXCJ1c2Ugc3RyaWN0XCI7XG4vKlxuICBodHRwczovL2dpdGh1Yi5jb20vYWxpY2VsaWV1dGllci9zbW9vdGhTY3JvbGxcbiAgTW9kaWZpZWQgYnkgU3RlZmFuIEJvaGFjZWsgdG8gdXNlIHRoZSBIVE1MNSBIaXN0b3J5IEFQSS5cbiovXG5mdW5jdGlvbiBzbW9vdGhfc2Nyb2xsX2ZuKCl7XG4gIGlmKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwgPT09IHZvaWQgMCB8fCB3aW5kb3cucGFnZVlPZmZzZXQgPT09IHZvaWQgMCB8fCBoaXN0b3J5LnB1c2hTdGF0ZSA9PT0gdm9pZCAwKSB7IHJldHVybjsgfVxuXG4gIHZhciBnZXRUb3AgPSBmdW5jdGlvbihlbGVtZW50KSB7XG5cbiAgICAgIGlmKGVsZW1lbnQubm9kZU5hbWUgPT09ICdIVE1MJyl7XG4gICAgICAgIHJldHVybiAtd2luZG93LnBhZ2VZT2Zmc2V0O1xuICAgICAgfVxuICAgICAgcmV0dXJuIGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCkudG9wICsgd2luZG93LnBhZ2VZT2Zmc2V0O1xuICB9O1xuICB2YXIgZWFzZUluT3V0Q3ViaWMgPSBmdW5jdGlvbiAodCkgeyByZXR1cm4gdDwwLjUgPyA0KnQqdCp0IDogKHQtMSkqKDIqdC0yKSooMip0LTIpKzE7IH07XG4gIHZhciBwb3NpdGlvbiA9IGZ1bmN0aW9uKHN0YXJ0LCBlbmQsIGVsYXBzZWQsIGR1cmF0aW9uKSB7XG4gICAgICBpZiAoZWxhcHNlZCA+IGR1cmF0aW9uKXtcbiAgICAgICAgcmV0dXJuIGVuZDtcbiAgICAgIH1cbiAgICAgIHJldHVybiBzdGFydCArIChlbmQgLSBzdGFydCkgKiBlYXNlSW5PdXRDdWJpYyhlbGFwc2VkIC8gZHVyYXRpb24pOyAvLyA8LS0geW91IGNhbiBjaGFuZ2UgdGhlIGVhc2luZyBmdW50aW9uIHRoZXJlXG4gIH07XG4gIHZhciBzbW9vdGhTY3JvbGwgPSBmdW5jdGlvbihlbCwgZHVyYXRpb24sIGNhbGxiYWNrKXtcbiAgICAgIGR1cmF0aW9uID0gZHVyYXRpb24gfHwgNTAwO1xuICAgICAgdmFyIHN0YXJ0ID0gd2luZG93LnBhZ2VZT2Zmc2V0LFxuICAgICAgICAgIGVuZDtcbiAgICAgIGlmICh0eXBlb2YgZWwgPT09ICdudW1iZXInKSB7XG4gICAgICAgIGVuZCA9IHBhcnNlSW50KGVsKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGVuZCA9IGdldFRvcChlbCkgLSA1MDtcbiAgICAgIH1cbiAgICAgIHZhciBjbG9jayA9IERhdGUubm93KCk7XG4gICAgICB2YXIgcmVxdWVzdEFuaW1hdGlvbkZyYW1lID0gd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZSB8fFxuICAgICAgICAgIHdpbmRvdy5tb3pSZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHwgd2luZG93LndlYmtpdFJlcXVlc3RBbmltYXRpb25GcmFtZSB8fFxuICAgICAgICAgIGZ1bmN0aW9uKGZuKXt3aW5kb3cuc2V0VGltZW91dChmbiwgMTUpO307XG4gICAgICB2YXIgc3RlcCA9IGZ1bmN0aW9uKCl7XG4gICAgICAgICAgdmFyIGVsYXBzZWQgPSBEYXRlLm5vdygpIC0gY2xvY2s7XG4gICAgICAgICAgd2luZG93LnNjcm9sbCgwLCBwb3NpdGlvbihzdGFydCwgZW5kLCBlbGFwc2VkLCBkdXJhdGlvbikpO1xuICAgICAgICAgIGlmIChlbGFwc2VkID4gZHVyYXRpb24pIHtcbiAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgY2FsbGJhY2soZWwpO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgcmVxdWVzdEFuaW1hdGlvbkZyYW1lKHN0ZXApO1xuICAgICAgICAgIH1cbiAgICAgIH07XG4gICAgICBzdGVwKCk7XG4gIH07XG4gIHZhciBsaW5rSGFuZGxlciA9IGZ1bmN0aW9uKGV2KSB7XG4gICAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgdmFyIGhhc2ggPSB0aGlzLmhhc2guc3Vic3RyaW5nKDEpO1xuICAgICAgaWYgKHdpbmRvdy5oaXN0b3J5ICYmIHdpbmRvdy5oaXN0b3J5LnB1c2hTdGF0ZSl7XG4gICAgICAgIGhpc3RvcnkucHVzaFN0YXRlKG51bGwsIG51bGwsICcjJyArIGhhc2gpO1xuICAgICAgfVxuICAgICAgc21vb3RoU2Nyb2xsKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGhhc2gpLCA1MDAsIGZ1bmN0aW9uKGVsKSB7XG4gICAgICB9KTtcbiAgfTtcbiAgdmFyIGludGVybmFsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnYVtocmVmXj1cIiNcIl0nKSwgYTtcbiAgZm9yKHZhciBpPWludGVybmFsLmxlbmd0aDsgYT1pbnRlcm5hbFstLWldOyl7XG4gICAgICBhLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBsaW5rSGFuZGxlciwgZmFsc2UpO1xuICB9XG5cbiAgaWYod2luZG93LmxvY2F0aW9uLmhhc2gpIHtcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICBzbW9vdGhTY3JvbGwoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQod2luZG93LmxvY2F0aW9uLmhhc2guc3Vic3RyaW5nKDEpKSwgMjAwLCBmdW5jdGlvbihlbCl7fSk7ICAgICAgXG4gICAgfSwgMzAwKTtcbiAgfVxuXG5cbiAgcmV0dXJuIHNtb290aFNjcm9sbDtcbn1cblxuZXhwb3J0IHtzbW9vdGhfc2Nyb2xsX2ZufTtcbiIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgYm90X3N1Ym1pc3Npb24gPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgdmFyICRib2R5ID0gJCgnYm9keScpLFxuICAgICAgICAkZm9ybV9zdWJtaXRfYnV0dG9uID0gJCgnI2JvdC1mb3JtLXN1Ym1pdCcpO1xuICAgIGlmICgkYm9keS5oYXNDbGFzcygncGFnZS10ZW1wbGF0ZS10ZW1wbGF0ZS1zdWJtaXQteW91ci1ib3QnKSl7XG5cbiAgICAgICQoJyN0ZXN0JykuY2xpY2soZnVuY3Rpb24oZXYpe1xuICAgICAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICQoJyNhZGQtYXV0aG9yLWZpZWxkcycpLmJlZm9yZSgnPGRpdiBjbGFzcz1cImF1dGhvci1maWVsZHMgZm9ybS1yb3dcIj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgaWQ9XCJhdXRob3ItMi1uYW1lXCIgbmFtZT1cImF1dGhvci1uYW1lc1tdXCIgcGxhY2Vob2xkZXI9XCJBdXRob3JcIj48L2Rpdj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxpbnB1dCB0eXBlPVwidXJsXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImF1dGhvci0yLXVybFwiIG5hbWU9XCJhdXRob3ItdXJsc1tdXCIgcGxhY2Vob2xkZXI9XCJodHRwczovL3R3aXR0ZXIuY29tL2F1dGhvclwiPjwvZGl2PjwvZGl2PicpO1xuXG4gICAgICAgICQoJyNib3QtbmFtZScpLnZhbCgnQGNvb2xib3QnKTtcbiAgICAgICAgJCgnI2F1dGhvci0xLW5hbWUnKS52YWwoJ1N0ZWZhbicpO1xuICAgICAgICAkKCcjYXV0aG9yLTEtdXJsJykudmFsKCdodHRwczovL3R3aXR0ZXIuY29tL2ZvdXJ0b25maXNoJyk7XG4gICAgICAgICQoJyNhdXRob3ItMi1uYW1lJykudmFsKCdKb2huIERvZScpO1xuICAgICAgICAvLyAkKCcjYXV0aG9yLTItdXJsJykudmFsKCdodHRwczovL3R3aXR0ZXIuY29tL2pkJyk7XG4gICAgICAgICQoJyNib3QtZGVzY3JpcHRpb24nKS52YWwoJ2dlbmVyYXRlcyByYW5kb20gaW1hZ2VzLicpO1xuXG4gICAgICAgIHZhciBib3RfaW5mb19uZXR3b3JrX3NlbGVjdF9odG1sID0gJCgnI2JvdC1pbmZvLTEtbmV0d29yaycpLmh0bWwoKTtcblxuICAgICAgICAkKCcjYWRkLWJvdC1pbmZvLWZpZWxkcycpLmJlZm9yZShgPGRpdiBjbGFzcz1cImJvdC1pbmZvLWZpZWxkcyBmb3JtLXJvd1wiPjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PHNlbGVjdCBjbGFzcz1cImZvcm0tY29udHJvbCBqcy1zZWxlY3QyXCIgaWQ9XCJib3QtaW5mby0yLW5ldHdvcmtcIiBuYW1lPVwiYm90LW5ldHdvcmtzW11cIiBwbGFjZWhvbGRlcj1cIlR3aXR0ZXIsIFR1bWJsciwgU2xhY2ssLi4uXCI+JHtib3RfaW5mb19uZXR3b3JrX3NlbGVjdF9odG1sLnJlcGxhY2UoJy0xLScsICctMi0nKX08L3NlbGVjdD48L2Rpdj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxpbnB1dCB0eXBlPVwidXJsXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImJvdC1pbmZvLTItdXJsXCIgbmFtZT1cImJvdC11cmxzW11cIiBwbGFjZWhvbGRlcj1cImh0dHBzOi8vdHdpdHRlci5jb20vb25lY29vbGJvdDJcIj48L2Rpdj48L2Rpdj5gKTtcblxuICAgICAgICAkKCcjYm90LWluZm8tMi1uZXR3b3JrJykuc2VsZWN0Mih7XG4gICAgICAgICAgdGFnczogdHJ1ZSxcbiAgICAgICAgICBwbGFjZWhvbGRlcjogJCh0aGlzKS5hdHRyKCdwbGFjZWhvbGRlcicpXG4gICAgICAgIH0pO1xuXG4gICAgICAgICQoJyNib3QtaW5mby0xLW5ldHdvcmsnKS52YWwoJ3R3aXR0ZXItYm90cycpO1xuICAgICAgICAkKCcjYm90LWluZm8tMS1uZXR3b3JrJykudHJpZ2dlcignY2hhbmdlJyk7XG5cbiAgICAgICAgJCgnI2JvdC1pbmZvLTItbmV0d29yaycpLnZhbCgndHVtYmxyLWJvdHMnKTtcbiAgICAgICAgJCgnI2JvdC1pbmZvLTItbmV0d29yaycpLnRyaWdnZXIoJ2NoYW5nZScpO1xuXG4gICAgICAgICQoJyNib3QtaW5mby0xLXVybCcpLnZhbCgnaHR0cHM6Ly90d2l0dGVyLmNvbS9jb29sYm90Jyk7XG4gICAgICAgICQoJyNib3QtaW5mby0yLXVybCcpLnZhbCgnaHR0cHM6Ly9jb29sYm90LnR1bWJsci5jb20vJyk7XG5cbiAgICAgICAgJCgnI2JvdC1zZWxlY3RlZC10d2VldHMnKS52YWwoJ2h0dHBzOi8vdHdpdHRlci5jb20vbXljb29sYm90L3N0YXR1cy8xMjM0NTY3ODlcXG5odHRwczovL3R3aXR0ZXIuY29tL215Y29vbGJvdC9zdGF0dXMvOTg3NjU0MzIxJyk7XG4gICAgICAgICQoJyNib3QtdGFnbGluZScpLnZhbCgnVGhpcyBpcyBhIGNvb2wgYm90LicpO1xuICAgICAgICAkKCcjYm90LXRhZ3MnKS52YWwoWydnZW5lcmF0aXZlJywgJ2ltYWdlcycsICdub2RlanMnXSk7XG4gICAgICAgICQoJyNib3QtdGFncycpLnRyaWdnZXIoJ2NoYW5nZScpO1xuXG4gICAgICAgICQoJyNib3QtaXMtb3BlbnNvdXJjZScpLmNsaWNrKCk7XG5cbiAgICAgICAgJCgnI2JvdC1zb3VyY2UtdXJsJykudmFsKCdodHRwczovL2dpdGh1Yi5jb20vYm90d2lraS9ib3R3aWtpLm9yZycpO1xuICAgICAgICAkKCcjYm90LXNvdXJjZS1sYW5ndWFnZScpLnZhbCgnbm9kZWpzJyk7XG4gICAgICAgICQoJyNib3Qtc291cmNlLWxhbmd1YWdlJykudHJpZ2dlcignY2hhbmdlJyk7XG5cbiAgICAgICAgJCgnaHRtbCwgYm9keScpLmFuaW1hdGUoe1xuICAgICAgICAgICAgc2Nyb2xsVG9wOiAkZm9ybV9zdWJtaXRfYnV0dG9uLm9mZnNldCgpLnRvcCAtIDUwMFxuICAgICAgICB9LCA0NTApO1xuXG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgIH0pO1xuXG4gICAgICAkKCcuanMtc2VsZWN0MicpLmVhY2goZnVuY3Rpb24oaSl7XG4gICAgICAgICQodGhpcykuc2VsZWN0Mih7XG4gICAgICAgICAgdGFnczogdHJ1ZSxcbiAgICAgICAgICBwbGFjZWhvbGRlcjogJCh0aGlzKS5hdHRyKCdwbGFjZWhvbGRlcicpXG4gICAgICAgIH0pO1xuICAgICAgfSk7XG5cbiAgICAgICQoJyNzdWJtaXQtYm90LWZvcm0nKS5zdWJtaXQoZnVuY3Rpb24oKXtcbiAgICAgICAgJGZvcm1fc3VibWl0X2J1dHRvbi5hdHRyKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpLmh0bWwoJ1BsZWFzZSB3YWl0Li4uJyk7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAkZm9ybV9zdWJtaXRfYnV0dG9uLmh0bWwoJ1N0aWxsIHdvcmtpbmcuLi4nKTtcbiAgICAgICAgfSwgNDcwMCk7XG4gICAgICB9KTtcblxuICAgICAgJCgnI2FkZC1hdXRob3ItZmllbGRzJykuY2xpY2soZnVuY3Rpb24oZXYpe1xuICAgICAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgIHZhciBuZXdfaWQgPSAkKCcuYXV0aG9yLWZpZWxkcycpLmxlbmd0aCArIDE7XG5cbiAgICAgICAgJCh0aGlzKS5iZWZvcmUoYDxkaXYgY2xhc3M9XCJhdXRob3ItZmllbGRzIGZvcm0tcm93XCI+PGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgY29sLW1kLTZcIj48aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIGlkPVwiYXV0aG9yLSR7bmV3X2lkfS1uYW1lXCIgbmFtZT1cImF1dGhvci1uYW1lc1tdXCIgcGxhY2Vob2xkZXI9XCJBdXRob3JcIj48L2Rpdj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxpbnB1dCB0eXBlPVwidXJsXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImF1dGhvci0ke25ld19pZH0tdXJsXCIgbmFtZT1cImF1dGhvci11cmxzW11cIiBwbGFjZWhvbGRlcj1cImh0dHBzOi8vdHdpdHRlci5jb20vYXV0aG9yXCI+PC9kaXY+PC9kaXY+YCk7XG5cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgfSk7XG5cbiAgICAgIHZhciBib3RfaW5mb19uZXR3b3JrX3NlbGVjdF9odG1sID0gJCgnI2JvdC1pbmZvLTEtbmV0d29yaycpLmh0bWwoKTtcblxuICAgICAgJCgnI2FkZC1ib3QtaW5mby1maWVsZHMnKS5jbGljayhmdW5jdGlvbihldil7XG4gICAgICAgIGV2LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgdmFyIG5ld19pZCA9ICQoJy5ib3QtaW5mby1maWVsZHMnKS5sZW5ndGggKyAxO1xuXG4gICAgICAgICQodGhpcykuYmVmb3JlKGA8ZGl2IGNsYXNzPVwiYm90LWluZm8tZmllbGRzIGZvcm0tcm93XCI+PGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgY29sLW1kLTZcIj48c2VsZWN0IGNsYXNzPVwiZm9ybS1jb250cm9sIGpzLXNlbGVjdDJcIiBpZD1cImJvdC1pbmZvLSR7bmV3X2lkfS1uZXR3b3JrXCIgbmFtZT1cImJvdC1uZXR3b3Jrc1tdXCIgcGxhY2Vob2xkZXI9XCJUd2l0dGVyLCBUdW1ibHIsIFNsYWNrLC4uLlwiPiR7Ym90X2luZm9fbmV0d29ya19zZWxlY3RfaHRtbC5yZXBsYWNlKCctMS0nLCBuZXdfaWQpfTwvc2VsZWN0PjwvZGl2PjxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIGNvbC1tZC02XCI+PGlucHV0IHR5cGU9XCJ1cmxcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIGlkPVwiYm90LSR7bmV3X2lkfS11cmxcIiBuYW1lPVwiYm90LXVybHNbXVwiIHBsYWNlaG9sZGVyPVwiaHR0cHM6Ly90d2l0dGVyLmNvbS9vbmVjb29sYm90JHtuZXdfaWR9XCI+PC9kaXY+PC9kaXY+YCk7XG5cbiAgICAgICAgJChgI2JvdC1pbmZvLSR7bmV3X2lkfS1uZXR3b3JrYCkuc2VsZWN0Mih7XG4gICAgICAgICAgdGFnczogdHJ1ZSxcbiAgICAgICAgICBwbGFjZWhvbGRlcjogJCh0aGlzKS5hdHRyKCdwbGFjZWhvbGRlcicpXG4gICAgICAgIH0pO1xuXG4gICAgICAgIGVuYWJsZV9zZWxlY3RlZF90d2VldHNfZmllbGQoKTtcblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9KTtcblxuICAgICAgdmFyICRib3Rfc291cmNlX2luZm8gPSAkKCcjYm90LXNvdXJjZS1pbmZvJyk7XG5cbiAgICAgICQoJyNib3QtaXMtb3BlbnNvdXJjZScpLmNsaWNrKGZ1bmN0aW9uKGV2KXtcblxuICAgICAgICBpZiAoJCh0aGlzKS5pcygnOmNoZWNrZWQnKSl7XG4gICAgICAgICAgJGJvdF9zb3VyY2VfaW5mby5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAkYm90X3NvdXJjZV9pbmZvLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgfVxuICAgICAgfSk7XG5cbiAgICAgIHZhciAkYXBwbHlfZm9yX2JvdG1ha2VyX2JhZGdlID0gJCgnI2FwcGx5LWZvci1ib3RtYWtlci1iYWRnZScpLFxuICAgICAgICAgICRib3Rha2VyX2JhZGdlX2FwcGxpY2F0aW9uID0gJCgnI2JvdG1ha2VyLWJhZGdlLWFwcGxpY2F0aW9uJyk7XG5cbiAgICAgICRhcHBseV9mb3JfYm90bWFrZXJfYmFkZ2UuY2xpY2soZnVuY3Rpb24oZXYpe1xuXG4gICAgICAgIGlmICgkKHRoaXMpLmlzKCc6Y2hlY2tlZCcpKXtcbiAgICAgICAgICAkYm90YWtlcl9iYWRnZV9hcHBsaWNhdGlvbi5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAkYm90YWtlcl9iYWRnZV9hcHBsaWNhdGlvbi5hZGRDbGFzcygnZC1ub25lJyk7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuXG4gICAgICB2YXIgJHNlbGVjdGVkX3R3ZWV0c19maWVsZCA9ICQoJyNib3Qtc2VsZWN0ZWQtdHdlZXRzLWZpZWxkJyk7XG5cbiAgICAgIGZ1bmN0aW9uIGVuYWJsZV9zZWxlY3RlZF90d2VldHNfZmllbGQoKXtcbiAgICAgICAgdmFyICRib3RfbmV0d29ya3Nfc2VsZWN0ID0gJCgnW25hbWU9XCJib3QtbmV0d29ya3NbXScpO1xuXG4gICAgICAgICRib3RfbmV0d29ya3Nfc2VsZWN0Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICAgIHZhciBzaG93X3NlbGVjdGVkX3R3ZWV0c19maWVsZCA9IGZhbHNlO1xuICAgICAgICAgICRib3RfbmV0d29ya3Nfc2VsZWN0LmVhY2goZnVuY3Rpb24oaSl7XG4gICAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICAgICAgaWYgKCR0aGlzLmNoaWxkcmVuKFwib3B0aW9uXCIpLmZpbHRlcihcIjpzZWxlY3RlZFwiKS50ZXh0KCkuaW5kZXhPZignVHdpdHRlcicpID4gLTEpe1xuICAgICAgICAgICAgICBzaG93X3NlbGVjdGVkX3R3ZWV0c19maWVsZCA9IHRydWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfSk7XG5cbiAgICAgICAgICBpZiAoc2hvd19zZWxlY3RlZF90d2VldHNfZmllbGQpe1xuICAgICAgICAgICAgJHNlbGVjdGVkX3R3ZWV0c19maWVsZC5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgICAgfVxuICAgICAgICAgIGVsc2V7XG4gICAgICAgICAgICAkc2VsZWN0ZWRfdHdlZXRzX2ZpZWxkLmFkZENsYXNzKCdkLW5vbmUnKTsgICAgICAgICAgICBcbiAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgICAgZW5hYmxlX3NlbGVjdGVkX3R3ZWV0c19maWVsZCgpO1xuICAgIH1cbiAgfVxufVxuXG5leHBvcnQge1xuICBib3Rfc3VibWlzc2lvblxufTtcbiJdfQ==
