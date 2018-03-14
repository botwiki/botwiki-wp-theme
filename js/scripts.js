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
        }, 3500);
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
    }
  }
};

exports.bot_submission = bot_submission;

},{}]},{},[5])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvc2NyaXB0cy9iYWNrLXRvLXRvcC5qcyIsInNyYy9zY3JpcHRzL2hlbHBlcnMuanMiLCJzcmMvc2NyaXB0cy9sYXp5LWxvYWQtaW1hZ2VzLmpzIiwic3JjL3NjcmlwdHMvbWVudS10b2dnbGUuanMiLCJzcmMvc2NyaXB0cy9zY3JpcHRzLmpzIiwic3JjL3NjcmlwdHMvc2VhcmNoLmpzIiwic3JjL3NjcmlwdHMvc21vb3RoLXNjcm9sbC5qcyIsInNyYy9zY3JpcHRzL3N1Ym1pdC15b3VyLWJvdC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBOzs7OztBQUVBLElBQUksY0FBYztBQUNoQixRQUFNLGdCQUFVO0FBQ2QsTUFBRSxNQUFGLEVBQVUsTUFBVixDQUFpQixZQUFVO0FBQ3pCO0FBQ0EsVUFBSSxZQUFZLFNBQVMsY0FBVCxDQUF3QixhQUF4QixDQUFoQjtBQUFBLFVBQ0ksb0JBQW9CLE9BQU8sV0FBUCxJQUFzQixTQUFTLGVBQVQsQ0FBeUIsU0FBL0MsSUFBNEQsU0FBUyxJQUFULENBQWMsU0FBMUUsSUFBdUYsQ0FEL0c7O0FBR0EsVUFBSSxvQkFBcUIsT0FBTyxNQUFQLEdBQWMsQ0FBdkMsRUFBMEM7QUFDeEMsa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixVQUF4QjtBQUNBLGtCQUFVLFNBQVYsQ0FBb0IsTUFBcEIsQ0FBMkIsWUFBM0I7QUFDRCxPQUhELE1BSUk7QUFDRixrQkFBVSxTQUFWLENBQW9CLE1BQXBCLENBQTJCLFVBQTNCO0FBQ0Esa0JBQVUsU0FBVixDQUFvQixHQUFwQixDQUF3QixZQUF4QjtBQUNEO0FBQ0YsS0FiRDtBQWNEO0FBaEJlLENBQWxCOztRQW9CRSxXLEdBQUEsVzs7O0FDdEJGOzs7OztBQUVBLFNBQVMsZ0JBQVQsQ0FBMEIsV0FBMUIsRUFBc0M7QUFDcEMsTUFBSSxZQUFZLE1BQVosR0FBcUIsQ0FBekIsRUFBMkI7QUFDekIsVUFBTSxTQUFOLENBQWdCLE9BQWhCLENBQXdCLElBQXhCLENBQTZCLFdBQTdCLEVBQTBDLFVBQVMsRUFBVCxFQUFhLEtBQWIsRUFBb0I7QUFDNUQsaUJBQ0UsWUFBVTtBQUNSLFdBQUcsU0FBSCxDQUFhLE1BQWIsQ0FBb0IsU0FBcEI7QUFDQSxXQUFHLFNBQUgsQ0FBYSxHQUFiLENBQWlCLFdBQWpCO0FBQ0QsT0FKSCxFQUtFLEtBQUssS0FBTCxHQUFhLEtBTGY7QUFNRCxLQVBEO0FBUUQ7QUFDRjs7UUFHQyxnQixHQUFBLGdCOzs7QUNoQkY7QUFDQTs7Ozs7Ozs7OztBQU9BLFNBQVMsWUFBVCxHQUF3QjtBQUN0QixNQUFJLG1CQUFvQixPQUFPLGdCQUFQLElBQTJCLFVBQVMsQ0FBVCxFQUFXLENBQVgsRUFBYztBQUFFLFdBQU8sV0FBUCxDQUFtQixPQUFLLENBQXhCLEVBQTJCLENBQTNCO0FBQWdDLEdBQW5HO0FBQUEsTUFDSSxzQkFBc0IsT0FBTyxtQkFBUCxJQUE4QixVQUFTLENBQVQsRUFBVyxDQUFYLEVBQWEsQ0FBYixFQUFnQjtBQUFFLFdBQU8sV0FBUCxDQUFtQixPQUFLLENBQXhCLEVBQTJCLENBQTNCO0FBQWdDLEdBRDFHOztBQUdBO0FBQ0E7QUFDQSxXQUFTLFlBQVQsQ0FBc0IsRUFBdEIsRUFBMEI7QUFDeEIsUUFBSSxNQUFNLENBQVY7QUFDQSxRQUFJLEdBQUcsWUFBUCxFQUFxQjtBQUNuQixTQUFHO0FBQ0QsZUFBTyxHQUFHLFNBQVY7QUFDRCxPQUZELFFBRVMsS0FBSyxHQUFHLFlBRmpCO0FBR0EsYUFBTyxHQUFQO0FBQ0Q7QUFDRjs7QUFFRCxNQUFJLGFBQWE7QUFDZixXQUFPLEVBRFE7QUFFZixzQkFBa0IsR0FGSDtBQUdmOztBQUVBLGtCQUFjLHdCQUFXO0FBQ3ZCLHVCQUFpQixRQUFqQixFQUEyQixXQUFXLGFBQXRDO0FBQ0EsdUJBQWlCLFFBQWpCLEVBQTJCLFdBQVcsYUFBdEM7QUFDRCxLQVJjOztBQVVmLHFCQUFpQiwyQkFBVztBQUMxQiwwQkFBb0IsUUFBcEIsRUFBOEIsV0FBVyxhQUF6QyxFQUF3RCxLQUF4RDtBQUNBLDBCQUFvQixRQUFwQixFQUE4QixXQUFXLGFBQXpDLEVBQXdELEtBQXhEO0FBQ0QsS0FiYzs7QUFlZixtQkFBZSxJQUFJLElBQUosR0FBVyxPQUFYLEVBZkE7O0FBaUJmLG1CQUFlLHlCQUFXO0FBQ3hCLFVBQUksTUFBTSxJQUFJLElBQUosR0FBVyxPQUFYLEVBQVY7QUFDQSxVQUFLLE1BQU0sV0FBVyxhQUFsQixJQUFvQyxHQUF4QyxFQUE2QztBQUMzQyxtQkFBVyxhQUFYLEdBQTJCLEdBQTNCO0FBQ0EsbUJBQVcsaUJBQVg7QUFDRDtBQUNGLEtBdkJjOztBQXlCZix1QkFBbUIsNkJBQVc7QUFDNUIsVUFBSSxVQUFVLE9BQU8sV0FBUCxJQUFzQixTQUFTLGVBQVQsQ0FBeUIsU0FBN0Q7QUFDQSxVQUFJLGFBQWEsT0FBTyxXQUFQLElBQXNCLFNBQVMsZUFBVCxDQUF5QixZQUFoRTtBQUNBLFVBQUksUUFBUTtBQUNWLGFBQUssVUFBVSxHQURMO0FBRVYsYUFBSyxVQUFVLFVBQVYsR0FBdUI7QUFGbEIsT0FBWjs7QUFLQSxVQUFJLElBQUksQ0FBUjtBQUNBLGFBQU8sSUFBSSxXQUFXLEtBQVgsQ0FBaUIsTUFBNUIsRUFBb0M7QUFDbEMsWUFBSSxRQUFRLFdBQVcsS0FBWCxDQUFpQixDQUFqQixDQUFaO0FBQ0EsWUFBSSxnQkFBZ0IsYUFBYSxLQUFiLENBQXBCO0FBQ0EsWUFBSSxjQUFjLE1BQU0sTUFBTixJQUFnQixDQUFsQzs7QUFFQSxZQUFLLGlCQUFpQixNQUFNLEdBQU4sR0FBWSxXQUE5QixJQUErQyxpQkFBaUIsTUFBTSxHQUExRSxFQUFnRjtBQUM5RSxjQUFJLFlBQVksTUFBTSxZQUFOLENBQW1CLGlCQUFuQixDQUFoQjs7QUFFQTtBQUNBO0FBQ0E7QUFDVjs7QUFFVSxnQkFBTSxTQUFOLEdBQWtCLE1BQU0sU0FBTixDQUFnQixPQUFoQixDQUF3Qix5QkFBeEIsRUFBbUQsaUJBQW5ELENBQWxCOztBQUdBLGNBQUksYUFBYSxPQUFPLEtBQVAsSUFBZ0IsV0FBVyxnQkFBNUMsRUFBOEQ7QUFDNUQsa0JBQU0sR0FBTixHQUFZLFNBQVo7QUFDRCxXQUZELE1BR0s7QUFDSCxrQkFBTSxHQUFOLEdBQVksTUFBTSxZQUFOLENBQW1CLFVBQW5CLENBQVo7QUFDRDs7QUFFRCxnQkFBTSxlQUFOLENBQXNCLFVBQXRCO0FBQ0EsZ0JBQU0sZUFBTixDQUFzQixpQkFBdEI7O0FBRUEscUJBQVcsS0FBWCxDQUFpQixNQUFqQixDQUF3QixDQUF4QixFQUEyQixDQUEzQjtBQUNBO0FBQ0Q7O0FBRUQ7QUFDRDs7QUFFRCxVQUFJLFdBQVcsS0FBWCxDQUFpQixNQUFqQixLQUE0QixDQUFoQyxFQUFtQztBQUNqQyxtQkFBVyxlQUFYO0FBQ0Q7QUFDRixLQXRFYzs7QUF3RWYsVUFBTSxnQkFBVztBQUNmO0FBQ0EsVUFBSSxDQUFDLFNBQVMsZ0JBQWQsRUFBZ0M7QUFDOUIsaUJBQVMsZ0JBQVQsR0FBNEIsVUFBUyxRQUFULEVBQW1CO0FBQzdDLGNBQUksTUFBTSxRQUFWO0FBQUEsY0FDSSxPQUFPLElBQUksZUFBSixDQUFvQixVQUQvQjtBQUFBLGNBRUksV0FBVyxJQUFJLGFBQUosQ0FBa0IsT0FBbEIsQ0FGZjtBQUdBLGVBQUssV0FBTCxDQUFpQixRQUFqQjtBQUNBLGNBQUksUUFBSixHQUFlLEVBQWY7QUFDQSxtQkFBUyxVQUFULENBQW9CLE9BQXBCLEdBQThCLFdBQVcsOENBQXpDO0FBQ0EsaUJBQU8sUUFBUCxDQUFnQixDQUFoQixFQUFtQixDQUFuQjtBQUNBLGlCQUFPLElBQUksUUFBWDtBQUNELFNBVEQ7QUFVRDs7QUFFRCx1QkFBaUIsTUFBakIsRUFBeUIsU0FBUyxlQUFULEdBQTJCO0FBQ2xELFlBQUksYUFBYSxTQUFTLGdCQUFULENBQTBCLGVBQTFCLENBQWpCOztBQUVBLGFBQUssSUFBSSxJQUFJLENBQWIsRUFBZ0IsSUFBSSxXQUFXLE1BQS9CLEVBQXVDLEdBQXZDLEVBQTRDO0FBQzFDLGNBQUksWUFBWSxXQUFXLENBQVgsQ0FBaEI7O0FBRUE7QUFDQTs7QUFFQSxxQkFBVyxLQUFYLENBQWlCLElBQWpCLENBQXNCLFNBQXRCO0FBQ0Q7O0FBRUQsbUJBQVcsWUFBWDtBQUNBLG1CQUFXLGlCQUFYOztBQUVBLDRCQUFvQixNQUFwQixFQUE0QixlQUE1QixFQUE2QyxLQUE3QztBQUNELE9BaEJEO0FBaUJEO0FBeEdjLEdBQWpCOztBQTJHQSxTQUFPLFVBQVA7QUFDRDs7UUFFTyxZLEdBQUEsWTs7O0FDdElSOzs7OztBQUVBLElBQUksY0FBYztBQUNoQixRQUFNLGdCQUFVO0FBQ2QsUUFBSSxRQUFRLEVBQUUsTUFBRixDQUFaO0FBQUEsUUFDSSxlQUFlLEVBQUUsY0FBRixDQURuQjs7QUFHQSxNQUFFLFlBQUYsRUFBZ0IsRUFBaEIsQ0FBbUIsT0FBbkIsRUFBNEIsVUFBUyxFQUFULEVBQVk7QUFDdEMsVUFBSSxhQUFhLGNBQWMsRUFBRSxZQUFGLENBQS9COztBQUVBLGNBQVEsR0FBUixDQUFZLFlBQVo7O0FBRUEsVUFBSSxFQUFFLGNBQUYsRUFBa0IsRUFBbEIsQ0FBcUIsVUFBckIsTUFBcUMsSUFBekMsRUFBOEM7QUFDNUMsZ0JBQVEsR0FBUixDQUFZLEdBQVo7QUFDQSxVQUFFLE1BQUYsRUFBVSxXQUFWLENBQXNCLFdBQXRCO0FBQ0EsbUJBQVcsSUFBWCxDQUFnQixHQUFoQixFQUFxQixXQUFyQixDQUFpQyxXQUFqQztBQUNELE9BSkQsTUFLSTtBQUNGLGdCQUFRLEdBQVIsQ0FBWSxHQUFaO0FBQ0EsVUFBRSxNQUFGLEVBQVUsUUFBVixDQUFtQixXQUFuQjtBQUNBLG1CQUFXLElBQVgsQ0FBZ0IsR0FBaEIsRUFBcUIsUUFBckIsQ0FBOEIsV0FBOUI7QUFDRDtBQUNGLEtBZkQ7QUFnQkQ7QUFyQmUsQ0FBbEI7O1FBeUJFLFcsR0FBQSxXOzs7QUMzQkY7O0FBSUE7O0FBQ0E7O0FBQ0E7O0FBQ0E7O0FBSUE7O0FBQ0E7O0FBQ0E7O0FBWEEsSUFBSSxPQUFPLFVBQVAsQ0FBa0IsSUFBbEIsQ0FBSjs7QUFPQTs7QUFNQTs7QUFHQSxFQUFFLFlBQVc7QUFDWCxpQkFBTyxJQUFQO0FBQ0EsMEJBQVksSUFBWjtBQUNBLHlCQUFZLElBQVo7QUFDQSxnQ0FBZSxJQUFmOztBQVFBOztBQUVBLE1BQUksZ0JBQWdCLFNBQVMsZ0JBQVQsQ0FBMEIsbUJBQTFCLENBQXBCOztBQUVBLE9BQUssSUFBSSxJQUFJLENBQVIsRUFBVyxJQUFJLGNBQWMsTUFBbEMsRUFBMEMsSUFBSSxDQUE5QyxFQUFpRCxHQUFqRCxFQUFxRDtBQUNuRCxrQkFBYyxDQUFkLEVBQWlCLE9BQWpCLENBQXlCLEdBQXpCLEdBQStCLGNBQWMsQ0FBZCxFQUFpQixHQUFoRDtBQUNBO0FBQ0Q7O0FBRUQsaUNBQWlCLFNBQVMsc0JBQVQsQ0FBZ0MsU0FBaEMsQ0FBakI7QUFDQTtBQUNBLHNDQUFlLElBQWY7QUFDRCxDQXhCRDs7O0FDbEJBOzs7OztBQUVBLElBQUksU0FBUztBQUNYLFFBQU0sZ0JBQVU7QUFDZCxRQUFJLDRCQUE0QixFQUFFLGlCQUFGLEVBQXFCLElBQXJCLENBQTBCLHdCQUExQixDQUFoQztBQUFBLFFBQ0ksMkJBQTJCLEVBQUUsMkJBQUYsQ0FEL0I7QUFBQSxRQUVJLDJCQUEyQixFQUFFLDJCQUFGLENBRi9COztBQUlBLE1BQUUsZUFBRixFQUFtQixLQUFuQixDQUF5QixVQUFTLEVBQVQsRUFBWTtBQUNuQyxRQUFFLGlCQUFGLEVBQXFCLFdBQXJCLENBQWlDLGlCQUFqQyxFQUFvRCxRQUFwRCxDQUE2RCxVQUE3RDtBQUNELEtBRkQ7QUFHRDtBQVRVLENBQWI7O1FBYUUsTSxHQUFBLE07OztBQ2ZGO0FBQ0E7Ozs7Ozs7O0FBSUEsU0FBUyxnQkFBVCxHQUEyQjtBQUN6QixRQUFHLFNBQVMsZ0JBQVQsS0FBOEIsS0FBSyxDQUFuQyxJQUF3QyxPQUFPLFdBQVAsS0FBdUIsS0FBSyxDQUFwRSxJQUF5RSxRQUFRLFNBQVIsS0FBc0IsS0FBSyxDQUF2RyxFQUEwRztBQUFFO0FBQVM7O0FBRXJILFFBQUksU0FBUyxTQUFULE1BQVMsQ0FBUyxPQUFULEVBQWtCOztBQUUzQixZQUFHLFFBQVEsUUFBUixLQUFxQixNQUF4QixFQUErQjtBQUM3QixtQkFBTyxDQUFDLE9BQU8sV0FBZjtBQUNEO0FBQ0QsZUFBTyxRQUFRLHFCQUFSLEdBQWdDLEdBQWhDLEdBQXNDLE9BQU8sV0FBcEQ7QUFDSCxLQU5EO0FBT0EsUUFBSSxpQkFBaUIsU0FBakIsY0FBaUIsQ0FBVSxDQUFWLEVBQWE7QUFBRSxlQUFPLElBQUUsR0FBRixHQUFRLElBQUUsQ0FBRixHQUFJLENBQUosR0FBTSxDQUFkLEdBQWtCLENBQUMsSUFBRSxDQUFILEtBQU8sSUFBRSxDQUFGLEdBQUksQ0FBWCxLQUFlLElBQUUsQ0FBRixHQUFJLENBQW5CLElBQXNCLENBQS9DO0FBQW1ELEtBQXZGO0FBQ0EsUUFBSSxXQUFXLFNBQVgsUUFBVyxDQUFTLEtBQVQsRUFBZ0IsR0FBaEIsRUFBcUIsT0FBckIsRUFBOEIsUUFBOUIsRUFBd0M7QUFDbkQsWUFBSSxVQUFVLFFBQWQsRUFBdUI7QUFDckIsbUJBQU8sR0FBUDtBQUNEO0FBQ0QsZUFBTyxRQUFRLENBQUMsTUFBTSxLQUFQLElBQWdCLGVBQWUsVUFBVSxRQUF6QixDQUEvQixDQUptRCxDQUlnQjtBQUN0RSxLQUxEO0FBTUEsUUFBSSxlQUFlLFNBQWYsWUFBZSxDQUFTLEVBQVQsRUFBYSxRQUFiLEVBQXVCLFFBQXZCLEVBQWdDO0FBQy9DLG1CQUFXLFlBQVksR0FBdkI7QUFDQSxZQUFJLFFBQVEsT0FBTyxXQUFuQjtBQUFBLFlBQ0ksR0FESjtBQUVBLFlBQUksT0FBTyxFQUFQLEtBQWMsUUFBbEIsRUFBNEI7QUFDMUIsa0JBQU0sU0FBUyxFQUFULENBQU47QUFDRCxTQUZELE1BRU87QUFDTCxrQkFBTSxPQUFPLEVBQVAsSUFBYSxFQUFuQjtBQUNEO0FBQ0QsWUFBSSxRQUFRLEtBQUssR0FBTCxFQUFaO0FBQ0EsWUFBSSx3QkFBd0IsT0FBTyxxQkFBUCxJQUN4QixPQUFPLHdCQURpQixJQUNXLE9BQU8sMkJBRGxCLElBRXhCLFVBQVMsRUFBVCxFQUFZO0FBQUMsbUJBQU8sVUFBUCxDQUFrQixFQUFsQixFQUFzQixFQUF0QjtBQUEyQixTQUY1QztBQUdBLFlBQUksT0FBTyxTQUFQLElBQU8sR0FBVTtBQUNqQixnQkFBSSxVQUFVLEtBQUssR0FBTCxLQUFhLEtBQTNCO0FBQ0EsbUJBQU8sTUFBUCxDQUFjLENBQWQsRUFBaUIsU0FBUyxLQUFULEVBQWdCLEdBQWhCLEVBQXFCLE9BQXJCLEVBQThCLFFBQTlCLENBQWpCO0FBQ0EsZ0JBQUksVUFBVSxRQUFkLEVBQXdCO0FBQ3BCLG9CQUFJLE9BQU8sUUFBUCxLQUFvQixVQUF4QixFQUFvQztBQUNoQyw2QkFBUyxFQUFUO0FBQ0g7QUFDSixhQUpELE1BSU87QUFDSCxzQ0FBc0IsSUFBdEI7QUFDSDtBQUNKLFNBVkQ7QUFXQTtBQUNILEtBekJEO0FBMEJBLFFBQUksY0FBYyxTQUFkLFdBQWMsQ0FBUyxFQUFULEVBQWE7QUFDM0IsV0FBRyxjQUFIO0FBQ0EsWUFBSSxPQUFPLEtBQUssSUFBTCxDQUFVLFNBQVYsQ0FBb0IsQ0FBcEIsQ0FBWDtBQUNBLFlBQUksT0FBTyxPQUFQLElBQWtCLE9BQU8sT0FBUCxDQUFlLFNBQXJDLEVBQStDO0FBQzdDLG9CQUFRLFNBQVIsQ0FBa0IsSUFBbEIsRUFBd0IsSUFBeEIsRUFBOEIsTUFBTSxJQUFwQztBQUNEO0FBQ0QscUJBQWEsU0FBUyxjQUFULENBQXdCLElBQXhCLENBQWIsRUFBNEMsR0FBNUMsRUFBaUQsVUFBUyxFQUFULEVBQWEsQ0FDN0QsQ0FERDtBQUVILEtBUkQ7QUFTQSxRQUFJLFdBQVcsU0FBUyxnQkFBVCxDQUEwQixjQUExQixDQUFmO0FBQUEsUUFBMEQsQ0FBMUQ7QUFDQSxTQUFJLElBQUksSUFBRSxTQUFTLE1BQW5CLEVBQTJCLElBQUUsU0FBUyxFQUFFLENBQVgsQ0FBN0IsR0FBNEM7QUFDeEMsVUFBRSxnQkFBRixDQUFtQixPQUFuQixFQUE0QixXQUE1QixFQUF5QyxLQUF6QztBQUNIOztBQUVELFFBQUcsT0FBTyxRQUFQLENBQWdCLElBQW5CLEVBQXlCO0FBQ3ZCLG1CQUFXLFlBQVU7QUFDbkIseUJBQWEsU0FBUyxjQUFULENBQXdCLE9BQU8sUUFBUCxDQUFnQixJQUFoQixDQUFxQixTQUFyQixDQUErQixDQUEvQixDQUF4QixDQUFiLEVBQXlFLEdBQXpFLEVBQThFLFVBQVMsRUFBVCxFQUFZLENBQUUsQ0FBNUY7QUFDRCxTQUZELEVBRUcsR0FGSDtBQUdEOztBQUdELFdBQU8sWUFBUDtBQUNEOztRQUVPLGdCLEdBQUEsZ0I7OztBQ3hFUjs7Ozs7QUFFQSxJQUFJLGlCQUFpQjtBQUNuQixRQUFNLGdCQUFVO0FBQ2QsUUFBSSxRQUFRLEVBQUUsTUFBRixDQUFaO0FBQUEsUUFDSSxzQkFBc0IsRUFBRSxrQkFBRixDQUQxQjtBQUVBLFFBQUksTUFBTSxRQUFOLENBQWUsd0NBQWYsQ0FBSixFQUE2RDs7QUFFM0QsUUFBRSxPQUFGLEVBQVcsS0FBWCxDQUFpQixVQUFTLEVBQVQsRUFBWTtBQUMzQixXQUFHLGNBQUg7O0FBRUEsVUFBRSxvQkFBRixFQUF3QixNQUF4QixDQUErQiwrYkFBL0I7O0FBRUEsVUFBRSxXQUFGLEVBQWUsR0FBZixDQUFtQixVQUFuQjtBQUNBLFVBQUUsZ0JBQUYsRUFBb0IsR0FBcEIsQ0FBd0IsUUFBeEI7QUFDQSxVQUFFLGVBQUYsRUFBbUIsR0FBbkIsQ0FBdUIsaUNBQXZCO0FBQ0EsVUFBRSxnQkFBRixFQUFvQixHQUFwQixDQUF3QixVQUF4QjtBQUNBO0FBQ0EsVUFBRSxrQkFBRixFQUFzQixHQUF0QixDQUEwQixtQ0FBMUI7QUFDQSxVQUFFLFdBQUYsRUFBZSxHQUFmLENBQW1CLDZCQUFuQjtBQUNBLFVBQUUsY0FBRixFQUFrQixHQUFsQixDQUFzQixxQkFBdEI7QUFDQSxVQUFFLGVBQUYsRUFBbUIsR0FBbkIsQ0FBdUIsR0FBdkI7QUFDQSxVQUFFLGVBQUYsRUFBbUIsT0FBbkIsQ0FBMkIsUUFBM0I7QUFDQSxVQUFFLFdBQUYsRUFBZSxHQUFmLENBQW1CLENBQUMsSUFBRCxFQUFPLElBQVAsRUFBYSxJQUFiLENBQW5CO0FBQ0EsVUFBRSxXQUFGLEVBQWUsT0FBZixDQUF1QixRQUF2Qjs7QUFFQSxVQUFFLG9CQUFGLEVBQXdCLEtBQXhCOztBQUVBLFVBQUUsaUJBQUYsRUFBcUIsR0FBckIsQ0FBeUIsd0NBQXpCO0FBQ0EsVUFBRSxzQkFBRixFQUEwQixHQUExQixDQUE4QixJQUE5QjtBQUNBLFVBQUUsc0JBQUYsRUFBMEIsT0FBMUIsQ0FBa0MsUUFBbEM7O0FBRUEsVUFBRSxZQUFGLEVBQWdCLE9BQWhCLENBQXdCO0FBQ3BCLHFCQUFXLG9CQUFvQixNQUFwQixHQUE2QixHQUE3QixHQUFtQztBQUQxQixTQUF4QixFQUVHLEdBRkg7O0FBS0EsZUFBTyxLQUFQO0FBQ0QsT0E5QkQ7O0FBZ0NBLFFBQUUsYUFBRixFQUFpQixJQUFqQixDQUFzQixVQUFTLENBQVQsRUFBVztBQUMvQixVQUFFLElBQUYsRUFBUSxPQUFSLENBQWdCO0FBQ2QsdUJBQWEsRUFBRSxJQUFGLEVBQVEsSUFBUixDQUFhLGFBQWI7QUFEQyxTQUFoQjtBQUdELE9BSkQ7O0FBT0EsUUFBRSxrQkFBRixFQUFzQixNQUF0QixDQUE2QixZQUFVO0FBQ3JDLDRCQUFvQixJQUFwQixDQUF5QixVQUF6QixFQUFxQyxVQUFyQyxFQUFpRCxJQUFqRCxDQUFzRCxnQkFBdEQ7QUFDQSxtQkFBVyxZQUFVO0FBQ25CLDhCQUFvQixJQUFwQixDQUF5QixrQkFBekI7QUFDRCxTQUZELEVBRUcsSUFGSDtBQUdELE9BTEQ7O0FBT0EsUUFBRSxvQkFBRixFQUF3QixLQUF4QixDQUE4QixVQUFTLEVBQVQsRUFBWTtBQUN4QyxXQUFHLGNBQUg7O0FBRUEsWUFBSSxTQUFTLEVBQUUsZ0JBQUYsRUFBb0IsTUFBcEIsR0FBNkIsQ0FBMUM7O0FBRUEsVUFBRSxJQUFGLEVBQVEsTUFBUixDQUFlLCtiQUFmOztBQUVBLGVBQU8sS0FBUDtBQUNELE9BUkQ7O0FBVUEsVUFBSSxtQkFBbUIsRUFBRSxrQkFBRixDQUF2QjtBQUNBLHVCQUFpQixRQUFqQixDQUEwQixRQUExQjs7QUFFQSxRQUFFLG9CQUFGLEVBQXdCLEtBQXhCLENBQThCLFVBQVMsRUFBVCxFQUFZOztBQUV4QyxZQUFJLEVBQUUsSUFBRixFQUFRLEVBQVIsQ0FBVyxVQUFYLENBQUosRUFBMkI7QUFDekIsMkJBQWlCLFdBQWpCLENBQTZCLFFBQTdCO0FBQ0QsU0FGRCxNQUdJO0FBQ0YsMkJBQWlCLFFBQWpCLENBQTBCLFFBQTFCO0FBQ0Q7QUFFRixPQVREO0FBYUQ7QUFDRjtBQS9Fa0IsQ0FBckI7O1FBbUZFLGMsR0FBQSxjIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gZSh0LG4scil7ZnVuY3Rpb24gcyhvLHUpe2lmKCFuW29dKXtpZighdFtvXSl7dmFyIGE9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtpZighdSYmYSlyZXR1cm4gYShvLCEwKTtpZihpKXJldHVybiBpKG8sITApO3ZhciBmPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIrbytcIidcIik7dGhyb3cgZi5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGZ9dmFyIGw9bltvXT17ZXhwb3J0czp7fX07dFtvXVswXS5jYWxsKGwuZXhwb3J0cyxmdW5jdGlvbihlKXt2YXIgbj10W29dWzFdW2VdO3JldHVybiBzKG4/bjplKX0sbCxsLmV4cG9ydHMsZSx0LG4scil9cmV0dXJuIG5bb10uZXhwb3J0c312YXIgaT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2Zvcih2YXIgbz0wO288ci5sZW5ndGg7bysrKXMocltvXSk7cmV0dXJuIHN9KSIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgYmFja190b190b3AgPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgJCh3aW5kb3cpLnNjcm9sbChmdW5jdGlvbigpe1xuICAgICAgLyogVE9ETzogTGVnYWN5IHNjcmlwdCwgcmV3cml0ZSB3aXRoIGpRdWVyeS4gKi9cbiAgICAgIHZhciBiYWNrVG9Ub3AgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmFjay10by10b3AnKSxcbiAgICAgICAgICBkb2N1bWVudFNjcm9sbFRvcCA9IHdpbmRvdy5wYWdlWU9mZnNldCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsVG9wIHx8IGRvY3VtZW50LmJvZHkuc2Nyb2xsVG9wIHx8IDA7XG5cbiAgICAgIGlmIChkb2N1bWVudFNjcm9sbFRvcCA+IChzY3JlZW4uaGVpZ2h0LzIpKXtcbiAgICAgICAgYmFja1RvVG9wLmNsYXNzTGlzdC5hZGQoJ3NsaWRlLXVwJyk7XG4gICAgICAgIGJhY2tUb1RvcC5jbGFzc0xpc3QucmVtb3ZlKCdzbGlkZS1kb3duJyk7XG4gICAgICB9XG4gICAgICBlbHNle1xuICAgICAgICBiYWNrVG9Ub3AuY2xhc3NMaXN0LnJlbW92ZSgnc2xpZGUtdXAnKTtcbiAgICAgICAgYmFja1RvVG9wLmNsYXNzTGlzdC5hZGQoJ3NsaWRlLWRvd24nKTtcbiAgICAgIH1cbiAgICB9KTtcbiAgfVxufVxuXG5leHBvcnQge1xuICBiYWNrX3RvX3RvcFxufTtcbiIsIlwidXNlIHN0cmljdFwiO1xuXG5mdW5jdGlvbiB1bnNoaWZ0X2VsZW1lbnRzKHNoaWZ0ZWRfZWxzKXtcbiAgaWYgKHNoaWZ0ZWRfZWxzLmxlbmd0aCA+IDApe1xuICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwoc2hpZnRlZF9lbHMsIGZ1bmN0aW9uKGVsLCBpbmRleCkge1xuICAgICAgc2V0VGltZW91dChcbiAgICAgICAgZnVuY3Rpb24oKXtcbiAgICAgICAgICBlbC5jbGFzc0xpc3QucmVtb3ZlKCdzaGlmdGVkJyk7XG4gICAgICAgICAgZWwuY2xhc3NMaXN0LmFkZCgndW5zaGlmdGVkJyk7XG4gICAgICAgIH0sXG4gICAgICAgIDU1ICogaW5kZXggKiBpbmRleCk7XG4gICAgfSk7XG4gIH0gIFxufVxuXG5leHBvcnQge1xuICB1bnNoaWZ0X2VsZW1lbnRzXG59O1xuIiwiXCJ1c2Ugc3RyaWN0XCI7XG4vKlxuICBMYXp5IExvYWQgSW1hZ2VzIHdpdGhvdXQgalF1ZXJ5XG4gIGh0dHA6Ly9rYWl6YXUuZ2l0aHViLmNvbS9MYXp5LUxvYWQtSW1hZ2VzLXdpdGhvdXQtalF1ZXJ5L1xuICBPcmlnaW5hbCBieSBNaWtlIFB1bGFza2kgLSBodHRwOi8vd3d3Lm1pa2VwdWxhc2tpLmNvbVxuICBNb2RpZmllZCBieSBLYWkgWmF1IC0gaHR0cDovL2thaXphdS5jb21cbiovXG5cbmZ1bmN0aW9uIGxhenlMb2FkZXJGbigpIHtcbiAgdmFyIGFkZEV2ZW50TGlzdGVuZXIgPSAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIgfHwgZnVuY3Rpb24obixmKSB7IHdpbmRvdy5hdHRhY2hFdmVudCgnb24nK24sIGYpOyB9LFxuICAgICAgcmVtb3ZlRXZlbnRMaXN0ZW5lciA9IHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyIHx8IGZ1bmN0aW9uKG4sZixiKSB7IHdpbmRvdy5kZXRhY2hFdmVudCgnb24nK24sIGYpOyB9O1xuXG4gIC8vIEZvciBJRTcgY29tcGF0aWJpbGl0eVxuICAvLyBBZGFwdGVkIGZyb20gaHR0cDovL3d3dy5xdWlya3Ntb2RlLm9yZy9qcy9maW5kcG9zLmh0bWxcbiAgZnVuY3Rpb24gZ2V0T2Zmc2V0VG9wKGVsKSB7XG4gICAgdmFyIHZhbCA9IDA7XG4gICAgaWYgKGVsLm9mZnNldFBhcmVudCkge1xuICAgICAgZG8ge1xuICAgICAgICB2YWwgKz0gZWwub2Zmc2V0VG9wO1xuICAgICAgfSB3aGlsZSAoZWwgPSBlbC5vZmZzZXRQYXJlbnQpO1xuICAgICAgcmV0dXJuIHZhbDtcbiAgICB9XG4gIH0gICAgICBcblxuICB2YXIgbGF6eUxvYWRlciA9IHtcbiAgICBjYWNoZTogW10sXG4gICAgbW9iaWxlU2NyZWVuU2l6ZTogNTAwLFxuICAgIC8vdGlueUdpZjogJ2RhdGE6aW1hZ2UvZ2lmO2Jhc2U2NCxSMGxHT0RsaEFRQUJBQUFBQUNINUJBRUtBQUVBTEFBQUFBQUJBQUVBQUFJQ1RBRUFPdz09JyxcblxuICAgIGFkZE9ic2VydmVyczogZnVuY3Rpb24oKSB7XG4gICAgICBhZGRFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBsYXp5TG9hZGVyLnRocm90dGxlZExvYWQpO1xuICAgICAgYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgbGF6eUxvYWRlci50aHJvdHRsZWRMb2FkKTtcbiAgICB9LFxuXG4gICAgcmVtb3ZlT2JzZXJ2ZXJzOiBmdW5jdGlvbigpIHtcbiAgICAgIHJlbW92ZUV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGxhenlMb2FkZXIudGhyb3R0bGVkTG9hZCwgZmFsc2UpO1xuICAgICAgcmVtb3ZlRXZlbnRMaXN0ZW5lcigncmVzaXplJywgbGF6eUxvYWRlci50aHJvdHRsZWRMb2FkLCBmYWxzZSk7XG4gICAgfSxcblxuICAgIHRocm90dGxlVGltZXI6IG5ldyBEYXRlKCkuZ2V0VGltZSgpLFxuXG4gICAgdGhyb3R0bGVkTG9hZDogZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgbm93ID0gbmV3IERhdGUoKS5nZXRUaW1lKCk7XG4gICAgICBpZiAoKG5vdyAtIGxhenlMb2FkZXIudGhyb3R0bGVUaW1lcikgPj0gMjAwKSB7XG4gICAgICAgIGxhenlMb2FkZXIudGhyb3R0bGVUaW1lciA9IG5vdztcbiAgICAgICAgbGF6eUxvYWRlci5sb2FkVmlzaWJsZUltYWdlcygpO1xuICAgICAgfVxuICAgIH0sXG5cbiAgICBsb2FkVmlzaWJsZUltYWdlczogZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgc2Nyb2xsWSA9IHdpbmRvdy5wYWdlWU9mZnNldCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsVG9wO1xuICAgICAgdmFyIHBhZ2VIZWlnaHQgPSB3aW5kb3cuaW5uZXJIZWlnaHQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudEhlaWdodDtcbiAgICAgIHZhciByYW5nZSA9IHtcbiAgICAgICAgbWluOiBzY3JvbGxZIC0gMjAwLFxuICAgICAgICBtYXg6IHNjcm9sbFkgKyBwYWdlSGVpZ2h0ICsgMjAwXG4gICAgICB9O1xuXG4gICAgICB2YXIgaSA9IDA7XG4gICAgICB3aGlsZSAoaSA8IGxhenlMb2FkZXIuY2FjaGUubGVuZ3RoKSB7XG4gICAgICAgIHZhciBpbWFnZSA9IGxhenlMb2FkZXIuY2FjaGVbaV07XG4gICAgICAgIHZhciBpbWFnZVBvc2l0aW9uID0gZ2V0T2Zmc2V0VG9wKGltYWdlKTtcbiAgICAgICAgdmFyIGltYWdlSGVpZ2h0ID0gaW1hZ2UuaGVpZ2h0IHx8IDA7XG5cbiAgICAgICAgaWYgKChpbWFnZVBvc2l0aW9uID49IHJhbmdlLm1pbiAtIGltYWdlSGVpZ2h0KSAmJiAoaW1hZ2VQb3NpdGlvbiA8PSByYW5nZS5tYXgpKSB7XG4gICAgICAgICAgdmFyIG1vYmlsZVNyYyA9IGltYWdlLmdldEF0dHJpYnV0ZSgnZGF0YS1zcmMtbW9iaWxlJyk7XG5cbiAgICAgICAgICAvLyBpbWFnZS5vbmxvYWQgPSBmdW5jdGlvbigpIHtcbiAgICAgICAgICAvLyAgIHRoaXMuY2xhc3NOYW1lID0gdGhpcy5jbGFzc05hbWUucmVwbGFjZSgvKF58XFxzKylsYXp5LWxvYWQoXFxzK3wkKS8sICckMWxhenktbG9hZGVkJDInKTtcbiAgICAgICAgICAvLyB9O1xuLy8gICAgICAgIFRlbXBvcmFyeSBmaXggZm9yIFNhZmFyaSFcblxuICAgICAgICAgIGltYWdlLmNsYXNzTmFtZSA9IGltYWdlLmNsYXNzTmFtZS5yZXBsYWNlKC8oXnxcXHMrKWxhenktbG9hZChcXHMrfCQpLywgJyQxbGF6eS1sb2FkZWQkMicpO1xuXG5cbiAgICAgICAgICBpZiAobW9iaWxlU3JjICYmIHNjcmVlbi53aWR0aCA8PSBsYXp5TG9hZGVyLm1vYmlsZVNjcmVlblNpemUpIHtcbiAgICAgICAgICAgIGltYWdlLnNyYyA9IG1vYmlsZVNyYztcbiAgICAgICAgICB9XG4gICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBpbWFnZS5zcmMgPSBpbWFnZS5nZXRBdHRyaWJ1dGUoJ2RhdGEtc3JjJyk7XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgaW1hZ2UucmVtb3ZlQXR0cmlidXRlKCdkYXRhLXNyYycpO1xuICAgICAgICAgIGltYWdlLnJlbW92ZUF0dHJpYnV0ZSgnZGF0YS1zcmMtbW9iaWxlJyk7XG5cbiAgICAgICAgICBsYXp5TG9hZGVyLmNhY2hlLnNwbGljZShpLCAxKTtcbiAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGkrKztcbiAgICAgIH1cblxuICAgICAgaWYgKGxhenlMb2FkZXIuY2FjaGUubGVuZ3RoID09PSAwKSB7XG4gICAgICAgIGxhenlMb2FkZXIucmVtb3ZlT2JzZXJ2ZXJzKCk7XG4gICAgICB9XG4gICAgfSxcblxuICAgIGluaXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgLy8gUGF0Y2ggSUU3LSAocXVlcnlTZWxlY3RvckFsbClcbiAgICAgIGlmICghZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCkge1xuICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsID0gZnVuY3Rpb24oc2VsZWN0b3IpIHtcbiAgICAgICAgICB2YXIgZG9jID0gZG9jdW1lbnQsXG4gICAgICAgICAgICAgIGhlYWQgPSBkb2MuZG9jdW1lbnRFbGVtZW50LmZpcnN0Q2hpbGQsXG4gICAgICAgICAgICAgIHN0eWxlVGFnID0gZG9jLmNyZWF0ZUVsZW1lbnQoJ1NUWUxFJyk7XG4gICAgICAgICAgaGVhZC5hcHBlbmRDaGlsZChzdHlsZVRhZyk7XG4gICAgICAgICAgZG9jLl9fcXNhZWxzID0gW107XG4gICAgICAgICAgc3R5bGVUYWcuc3R5bGVTaGVldC5jc3NUZXh0ID0gc2VsZWN0b3IgKyAne3g6ZXhwcmVzc2lvbihkb2N1bWVudC5fX3FzYWVscy5wdXNoKHRoaXMpKX0nO1xuICAgICAgICAgIHdpbmRvdy5zY3JvbGxCeSgwLCAwKTtcbiAgICAgICAgICByZXR1cm4gZG9jLl9fcXNhZWxzO1xuICAgICAgICB9O1xuICAgICAgfVxuXG4gICAgICBhZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gX2xhenlMb2FkZXJJbml0KCkge1xuICAgICAgICB2YXIgaW1hZ2VOb2RlcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2ltZ1tkYXRhLXNyY10nKTtcblxuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGltYWdlTm9kZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICB2YXIgaW1hZ2VOb2RlID0gaW1hZ2VOb2Rlc1tpXTtcblxuICAgICAgICAgIC8vIEFkZCBhIHBsYWNlaG9sZGVyIGlmIG9uZSBkb2Vzbid0IGV4aXN0XG4gICAgICAgICAgLy9pbWFnZU5vZGUuc3JjID0gaW1hZ2VOb2RlLnNyYyB8fCBsYXp5TG9hZGVyLnRpbnlHaWY7XG5cbiAgICAgICAgICBsYXp5TG9hZGVyLmNhY2hlLnB1c2goaW1hZ2VOb2RlKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGxhenlMb2FkZXIuYWRkT2JzZXJ2ZXJzKCk7XG4gICAgICAgIGxhenlMb2FkZXIubG9hZFZpc2libGVJbWFnZXMoKTtcblxuICAgICAgICByZW1vdmVFdmVudExpc3RlbmVyKCdsb2FkJywgX2xhenlMb2FkZXJJbml0LCBmYWxzZSk7XG4gICAgICB9KTtcbiAgICB9XG4gIH07XG5cbiAgcmV0dXJuIGxhenlMb2FkZXI7XG59XG5cbmV4cG9ydCB7bGF6eUxvYWRlckZufTtcbiIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgbWVudV90b2dnbGUgPSB7XG4gIGluaXQ6IGZ1bmN0aW9uKCl7XG4gICAgdmFyICRib2R5ID0gJCgnYm9keScpLFxuICAgICAgICAkbWVudV90b2dnbGUgPSAkKCcjbWVudS10b2dnbGUnKTtcblxuICAgICQoJyNtZW51LWljb24nKS5vbignY2xpY2snLCBmdW5jdGlvbihldil7XG4gICAgICB2YXIgJG1lbnVfaWNvbiA9ICRtZW51X2ljb24gfHwgJCgnI21lbnUtaWNvbicpO1xuXG4gICAgICBjb25zb2xlLmxvZygkbWVudV90b2dnbGUpO1xuXG4gICAgICBpZiAoJCgnI21lbnUtdG9nZ2xlJykuaXMoJzpjaGVja2VkJykgPT09IHRydWUpe1xuICAgICAgICBjb25zb2xlLmxvZygnMScpO1xuICAgICAgICAkKCdib2R5JykucmVtb3ZlQ2xhc3MoJ21lbnUtb3BlbicpO1xuICAgICAgICAkbWVudV9pY29uLmh0bWwoJ+KYsCcpLnJlbW92ZUNsYXNzKCdtci0zIG10LTAnKTtcbiAgICAgIH1cbiAgICAgIGVsc2V7XG4gICAgICAgIGNvbnNvbGUubG9nKCcwJyk7ICAgICAgXG4gICAgICAgICQoJ2JvZHknKS5hZGRDbGFzcygnbWVudS1vcGVuJyk7ICAgICAgXG4gICAgICAgICRtZW51X2ljb24uaHRtbCgnw5cnKS5hZGRDbGFzcygnbXItMyBtdC0wJyk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgbWVudV90b2dnbGVcbn07XG5cblxuXG5cbiIsIlwidXNlIHN0cmljdFwiO1xuXG4kID0galF1ZXJ5Lm5vQ29uZmxpY3QodHJ1ZSk7XG5cbmltcG9ydCB7c2VhcmNofSBmcm9tIFwiLi9zZWFyY2guanNcIjtcbmltcG9ydCB7bWVudV90b2dnbGV9IGZyb20gXCIuL21lbnUtdG9nZ2xlLmpzXCI7XG5pbXBvcnQge2JhY2tfdG9fdG9wfSBmcm9tIFwiLi9iYWNrLXRvLXRvcC5qc1wiO1xuaW1wb3J0IHtib3Rfc3VibWlzc2lvbn0gZnJvbSBcIi4vc3VibWl0LXlvdXItYm90LmpzXCI7XG5cbi8qIFRPRE86IExlZ2FjeSBjb2RlLCBuZWVkcyBhIGNsZWFudXAuICovXG5cbmltcG9ydCB7c21vb3RoX3Njcm9sbF9mbn0gZnJvbSBcIi4vc21vb3RoLXNjcm9sbC5qc1wiO1xuaW1wb3J0IHt1bnNoaWZ0X2VsZW1lbnRzfSBmcm9tIFwiLi9oZWxwZXJzLmpzXCI7XG5pbXBvcnQge2xhenlMb2FkZXJGbn0gZnJvbSBcIi4vbGF6eS1sb2FkLWltYWdlcy5qc1wiO1xuXG4vKiBFTkQgVE9ETyAqL1xuXG5cbiQoZnVuY3Rpb24oKSB7XG4gIHNlYXJjaC5pbml0KCk7XG4gIG1lbnVfdG9nZ2xlLmluaXQoKTtcbiAgYmFja190b190b3AuaW5pdCgpO1xuICBib3Rfc3VibWlzc2lvbi5pbml0KCk7XG5cblxuXG5cblxuXG5cbiAgLyogVE9ETzogTGVnYWN5IGNvZGUsIG5lZWRzIGEgY2xlYW51cC4gKi9cblxuICB2YXIgYXJ0aWNsZUltYWdlcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2ltZy53cC1wb3N0LWltYWdlJyk7XG4gIFxuICBmb3IgKHZhciBpID0gMCwgaiA9IGFydGljbGVJbWFnZXMubGVuZ3RoOyBpIDwgajsgaSsrKXtcbiAgICBhcnRpY2xlSW1hZ2VzW2ldLmRhdGFzZXQuc3JjID0gYXJ0aWNsZUltYWdlc1tpXS5zcmM7XG4gICAgLy8gYXJ0aWNsZUltYWdlc1tpXS5jbGFzc0xpc3QuYWRkKCdsYXp5LWxvYWQnKTtcbiAgfVxuXG4gIHVuc2hpZnRfZWxlbWVudHMoZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgnc2hpZnRlZCcpKTtcbiAgc21vb3RoX3Njcm9sbF9mbigpO1xuICBsYXp5TG9hZGVyRm4oKS5pbml0KCk7XG59KTtcbiIsIlwidXNlIHN0cmljdFwiO1xuXG52YXIgc2VhcmNoID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgIHZhciAkc2VhcmNoX2ZpbHRlcl9jaGVja2JveGVzID0gJCgnI3NlYXJjaC1maWx0ZXJzJykuZmluZCgnaW5wdXRbdHlwZT1cImNoZWNrYm94XCJdJyksXG4gICAgICAgICRzZWFyY2hfZmlsdGVyX3R1dG9yaWFscyA9ICQoJyNzZWFyY2gtZmlsdGVycy10dXRvcmlhbHMnKSxcbiAgICAgICAgJHNlYXJjaF9maWx0ZXJfcmVzb3VyY2VzID0gJCgnI3NlYXJjaC1maWx0ZXJzLXJlc291cmNlcycpO1xuXG4gICAgJCgnI3NlYXJjaC1pbnB1dCcpLmNsaWNrKGZ1bmN0aW9uKGV2KXtcbiAgICAgICQoJyNzZWFyY2gtZmlsdGVycycpLnJlbW92ZUNsYXNzKCdzbGlkZS11cC1oaWRkZW4nKS5hZGRDbGFzcygnc2xpZGUtdXAnKTtcbiAgICB9KTtcbiAgfVxufVxuXG5leHBvcnQge1xuICBzZWFyY2hcbn07XG5cblxuXG5cbiIsIlwidXNlIHN0cmljdFwiO1xuLypcbiAgaHR0cHM6Ly9naXRodWIuY29tL2FsaWNlbGlldXRpZXIvc21vb3RoU2Nyb2xsXG4gIE1vZGlmaWVkIGJ5IFN0ZWZhbiBCb2hhY2VrIHRvIHVzZSB0aGUgSFRNTDUgSGlzdG9yeSBBUEkuXG4qL1xuZnVuY3Rpb24gc21vb3RoX3Njcm9sbF9mbigpe1xuICBpZihkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsID09PSB2b2lkIDAgfHwgd2luZG93LnBhZ2VZT2Zmc2V0ID09PSB2b2lkIDAgfHwgaGlzdG9yeS5wdXNoU3RhdGUgPT09IHZvaWQgMCkgeyByZXR1cm47IH1cblxuICB2YXIgZ2V0VG9wID0gZnVuY3Rpb24oZWxlbWVudCkge1xuXG4gICAgICBpZihlbGVtZW50Lm5vZGVOYW1lID09PSAnSFRNTCcpe1xuICAgICAgICByZXR1cm4gLXdpbmRvdy5wYWdlWU9mZnNldDtcbiAgICAgIH1cbiAgICAgIHJldHVybiBlbGVtZW50LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcCArIHdpbmRvdy5wYWdlWU9mZnNldDtcbiAgfTtcbiAgdmFyIGVhc2VJbk91dEN1YmljID0gZnVuY3Rpb24gKHQpIHsgcmV0dXJuIHQ8MC41ID8gNCp0KnQqdCA6ICh0LTEpKigyKnQtMikqKDIqdC0yKSsxOyB9O1xuICB2YXIgcG9zaXRpb24gPSBmdW5jdGlvbihzdGFydCwgZW5kLCBlbGFwc2VkLCBkdXJhdGlvbikge1xuICAgICAgaWYgKGVsYXBzZWQgPiBkdXJhdGlvbil7XG4gICAgICAgIHJldHVybiBlbmQ7XG4gICAgICB9XG4gICAgICByZXR1cm4gc3RhcnQgKyAoZW5kIC0gc3RhcnQpICogZWFzZUluT3V0Q3ViaWMoZWxhcHNlZCAvIGR1cmF0aW9uKTsgLy8gPC0tIHlvdSBjYW4gY2hhbmdlIHRoZSBlYXNpbmcgZnVudGlvbiB0aGVyZVxuICB9O1xuICB2YXIgc21vb3RoU2Nyb2xsID0gZnVuY3Rpb24oZWwsIGR1cmF0aW9uLCBjYWxsYmFjayl7XG4gICAgICBkdXJhdGlvbiA9IGR1cmF0aW9uIHx8IDUwMDtcbiAgICAgIHZhciBzdGFydCA9IHdpbmRvdy5wYWdlWU9mZnNldCxcbiAgICAgICAgICBlbmQ7XG4gICAgICBpZiAodHlwZW9mIGVsID09PSAnbnVtYmVyJykge1xuICAgICAgICBlbmQgPSBwYXJzZUludChlbCk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBlbmQgPSBnZXRUb3AoZWwpIC0gNTA7XG4gICAgICB9XG4gICAgICB2YXIgY2xvY2sgPSBEYXRlLm5vdygpO1xuICAgICAgdmFyIHJlcXVlc3RBbmltYXRpb25GcmFtZSA9IHdpbmRvdy5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHxcbiAgICAgICAgICB3aW5kb3cubW96UmVxdWVzdEFuaW1hdGlvbkZyYW1lIHx8IHdpbmRvdy53ZWJraXRSZXF1ZXN0QW5pbWF0aW9uRnJhbWUgfHxcbiAgICAgICAgICBmdW5jdGlvbihmbil7d2luZG93LnNldFRpbWVvdXQoZm4sIDE1KTt9O1xuICAgICAgdmFyIHN0ZXAgPSBmdW5jdGlvbigpe1xuICAgICAgICAgIHZhciBlbGFwc2VkID0gRGF0ZS5ub3coKSAtIGNsb2NrO1xuICAgICAgICAgIHdpbmRvdy5zY3JvbGwoMCwgcG9zaXRpb24oc3RhcnQsIGVuZCwgZWxhcHNlZCwgZHVyYXRpb24pKTtcbiAgICAgICAgICBpZiAoZWxhcHNlZCA+IGR1cmF0aW9uKSB7XG4gICAgICAgICAgICAgIGlmICh0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgIGNhbGxiYWNrKGVsKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgIHJlcXVlc3RBbmltYXRpb25GcmFtZShzdGVwKTtcbiAgICAgICAgICB9XG4gICAgICB9O1xuICAgICAgc3RlcCgpO1xuICB9O1xuICB2YXIgbGlua0hhbmRsZXIgPSBmdW5jdGlvbihldikge1xuICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcbiAgICAgIHZhciBoYXNoID0gdGhpcy5oYXNoLnN1YnN0cmluZygxKTtcbiAgICAgIGlmICh3aW5kb3cuaGlzdG9yeSAmJiB3aW5kb3cuaGlzdG9yeS5wdXNoU3RhdGUpe1xuICAgICAgICBoaXN0b3J5LnB1c2hTdGF0ZShudWxsLCBudWxsLCAnIycgKyBoYXNoKTtcbiAgICAgIH1cbiAgICAgIHNtb290aFNjcm9sbChkb2N1bWVudC5nZXRFbGVtZW50QnlJZChoYXNoKSwgNTAwLCBmdW5jdGlvbihlbCkge1xuICAgICAgfSk7XG4gIH07XG4gIHZhciBpbnRlcm5hbCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ2FbaHJlZl49XCIjXCJdJyksIGE7XG4gIGZvcih2YXIgaT1pbnRlcm5hbC5sZW5ndGg7IGE9aW50ZXJuYWxbLS1pXTspe1xuICAgICAgYS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgbGlua0hhbmRsZXIsIGZhbHNlKTtcbiAgfVxuXG4gIGlmKHdpbmRvdy5sb2NhdGlvbi5oYXNoKSB7XG4gICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgc21vb3RoU2Nyb2xsKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHdpbmRvdy5sb2NhdGlvbi5oYXNoLnN1YnN0cmluZygxKSksIDIwMCwgZnVuY3Rpb24oZWwpe30pOyAgICAgIFxuICAgIH0sIDMwMCk7XG4gIH1cblxuXG4gIHJldHVybiBzbW9vdGhTY3JvbGw7XG59XG5cbmV4cG9ydCB7c21vb3RoX3Njcm9sbF9mbn07XG4iLCJcInVzZSBzdHJpY3RcIjtcblxudmFyIGJvdF9zdWJtaXNzaW9uID0ge1xuICBpbml0OiBmdW5jdGlvbigpe1xuICAgIHZhciAkYm9keSA9ICQoJ2JvZHknKSxcbiAgICAgICAgJGZvcm1fc3VibWl0X2J1dHRvbiA9ICQoJyNib3QtZm9ybS1zdWJtaXQnKTtcbiAgICBpZiAoJGJvZHkuaGFzQ2xhc3MoJ3BhZ2UtdGVtcGxhdGUtdGVtcGxhdGUtc3VibWl0LXlvdXItYm90Jykpe1xuXG4gICAgICAkKCcjdGVzdCcpLmNsaWNrKGZ1bmN0aW9uKGV2KXtcbiAgICAgICAgZXYucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAkKCcjYWRkLWF1dGhvci1maWVsZHMnKS5iZWZvcmUoJzxkaXYgY2xhc3M9XCJhdXRob3ItZmllbGRzIGZvcm0tcm93XCI+PGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgY29sLW1kLTZcIj48bGFiZWwgZm9yPVwiYXV0aG9yLSR7bmV3X2lkfS1uYW1lXCI+QXV0aG9yXFwncyBuYW1lPC9sYWJlbD48aW5wdXQgdHlwZT1cInRleHRcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIGlkPVwiYXV0aG9yLTItbmFtZVwiIG5hbWU9XCJhdXRob3ItbmFtZXNbXVwiIHBsYWNlaG9sZGVyPVwiQXV0aG9yXCI+PC9kaXY+PGRpdiBjbGFzcz1cImZvcm0tZ3JvdXAgY29sLW1kLTZcIj48bGFiZWwgZm9yPVwiYXV0aG9yLTItdXJsXCI+QXV0aG9yXFwncyBVUkw8L2xhYmVsPjxpbnB1dCB0eXBlPVwidXJsXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2xcIiBpZD1cImF1dGhvci0yLXVybFwiIG5hbWU9XCJhdXRob3ItdXJsc1tdXCIgcGxhY2Vob2xkZXI9XCJodHRwczovL3R3aXR0ZXIuY29tL2F1dGhvclwiPjwvZGl2PjwvZGl2PicpO1xuXG4gICAgICAgICQoJyNib3QtbmFtZScpLnZhbCgnQGNvb2xib3QnKTtcbiAgICAgICAgJCgnI2F1dGhvci0xLW5hbWUnKS52YWwoJ1N0ZWZhbicpO1xuICAgICAgICAkKCcjYXV0aG9yLTEtdXJsJykudmFsKCdodHRwczovL3R3aXR0ZXIuY29tL2ZvdXJ0b25maXNoJyk7XG4gICAgICAgICQoJyNhdXRob3ItMi1uYW1lJykudmFsKCdKb2huIERvZScpO1xuICAgICAgICAvLyAkKCcjYXV0aG9yLTItdXJsJykudmFsKCdodHRwczovL3R3aXR0ZXIuY29tL2pkJyk7XG4gICAgICAgICQoJyNib3QtZGVzY3JpcHRpb24nKS52YWwoJ1RoaXMgYm90IGdlbmVyYXRlcyByYW5kb20gaW1hZ2VzLicpO1xuICAgICAgICAkKCcjYm90LXVybHMnKS52YWwoJ2h0dHBzOi8vdHdpdHRlci5jb20vY29vbGJvdCcpO1xuICAgICAgICAkKCcjYm90LXRhZ2xpbmUnKS52YWwoJ1RoaXMgaXMgYSBjb29sIGJvdC4nKTtcbiAgICAgICAgJCgnI2JvdC1uZXR3b3JrcycpLnZhbCgnNScpO1xuICAgICAgICAkKCcjYm90LW5ldHdvcmtzJykudHJpZ2dlcignY2hhbmdlJyk7XG4gICAgICAgICQoJyNib3QtdGFncycpLnZhbChbJzkyJywgJzY0JywgJzU1J10pO1xuICAgICAgICAkKCcjYm90LXRhZ3MnKS50cmlnZ2VyKCdjaGFuZ2UnKTtcblxuICAgICAgICAkKCcjYm90LWlzLW9wZW5zb3VyY2UnKS5jbGljaygpO1xuXG4gICAgICAgICQoJyNib3Qtc291cmNlLXVybCcpLnZhbCgnaHR0cHM6Ly9naXRodWIuY29tL2JvdHdpa2kvYm90d2lraS5vcmcnKTtcbiAgICAgICAgJCgnI2JvdC1zb3VyY2UtbGFuZ3VhZ2UnKS52YWwoJzI1Jyk7XG4gICAgICAgICQoJyNib3Qtc291cmNlLWxhbmd1YWdlJykudHJpZ2dlcignY2hhbmdlJyk7XG5cbiAgICAgICAgJCgnaHRtbCwgYm9keScpLmFuaW1hdGUoe1xuICAgICAgICAgICAgc2Nyb2xsVG9wOiAkZm9ybV9zdWJtaXRfYnV0dG9uLm9mZnNldCgpLnRvcCAtIDUwMFxuICAgICAgICB9LCA0NTApO1xuXG5cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgfSk7XG5cbiAgICAgICQoJy5qcy1zZWxlY3QyJykuZWFjaChmdW5jdGlvbihpKXtcbiAgICAgICAgJCh0aGlzKS5zZWxlY3QyKHtcbiAgICAgICAgICBwbGFjZWhvbGRlcjogJCh0aGlzKS5hdHRyKCdwbGFjZWhvbGRlcicpXG4gICAgICAgIH0pO1xuICAgICAgfSk7XG5cblxuICAgICAgJCgnI3N1Ym1pdC1ib3QtZm9ybScpLnN1Ym1pdChmdW5jdGlvbigpe1xuICAgICAgICAkZm9ybV9zdWJtaXRfYnV0dG9uLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJykuaHRtbCgnUGxlYXNlIHdhaXQuLi4nKTtcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICRmb3JtX3N1Ym1pdF9idXR0b24uaHRtbCgnU3RpbGwgd29ya2luZy4uLicpO1xuICAgICAgICB9LCAzNTAwKTtcbiAgICAgIH0pO1xuXG4gICAgICAkKCcjYWRkLWF1dGhvci1maWVsZHMnKS5jbGljayhmdW5jdGlvbihldil7XG4gICAgICAgIGV2LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgdmFyIG5ld19pZCA9ICQoJy5hdXRob3ItZmllbGRzJykubGVuZ3RoICsgMTtcblxuICAgICAgICAkKHRoaXMpLmJlZm9yZSgnPGRpdiBjbGFzcz1cImF1dGhvci1maWVsZHMgZm9ybS1yb3dcIj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxsYWJlbCBmb3I9XCJhdXRob3ItJHtuZXdfaWR9LW5hbWVcIj5BdXRob3JcXCdzIG5hbWU8L2xhYmVsPjxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzPVwiZm9ybS1jb250cm9sXCIgaWQ9XCJhdXRob3ItMi1uYW1lXCIgbmFtZT1cImF1dGhvci1uYW1lc1tdXCIgcGxhY2Vob2xkZXI9XCJBdXRob3JcIj48L2Rpdj48ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBjb2wtbWQtNlwiPjxsYWJlbCBmb3I9XCJhdXRob3ItMi11cmxcIj5BdXRob3JcXCdzIFVSTDwvbGFiZWw+PGlucHV0IHR5cGU9XCJ1cmxcIiBjbGFzcz1cImZvcm0tY29udHJvbFwiIGlkPVwiYXV0aG9yLTItdXJsXCIgbmFtZT1cImF1dGhvci11cmxzW11cIiBwbGFjZWhvbGRlcj1cImh0dHBzOi8vdHdpdHRlci5jb20vYXV0aG9yXCI+PC9kaXY+PC9kaXY+Jyk7XG5cbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgfSk7XG5cbiAgICAgIHZhciAkYm90X3NvdXJjZV9pbmZvID0gJCgnI2JvdC1zb3VyY2UtaW5mbycpO1xuICAgICAgJGJvdF9zb3VyY2VfaW5mby5hZGRDbGFzcygnZC1ub25lJyk7XG5cbiAgICAgICQoJyNib3QtaXMtb3BlbnNvdXJjZScpLmNsaWNrKGZ1bmN0aW9uKGV2KXtcblxuICAgICAgICBpZiAoJCh0aGlzKS5pcygnOmNoZWNrZWQnKSl7XG4gICAgICAgICAgJGJvdF9zb3VyY2VfaW5mby5yZW1vdmVDbGFzcygnZC1ub25lJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZXtcbiAgICAgICAgICAkYm90X3NvdXJjZV9pbmZvLmFkZENsYXNzKCdkLW5vbmUnKTtcbiAgICAgICAgfVxuXG4gICAgICB9KTtcblxuXG5cbiAgICB9XG4gIH1cbn1cblxuZXhwb3J0IHtcbiAgYm90X3N1Ym1pc3Npb25cbn07XG5cblxuXG5cbiJdfQ==
