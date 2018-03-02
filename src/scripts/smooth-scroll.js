"use strict";
/*
  https://github.com/alicelieutier/smoothScroll
  Modified by Stefan Bohacek to use the HTML5 History API.
*/
function smooth_scroll_fn(){
  if(document.querySelectorAll === void 0 || window.pageYOffset === void 0 || history.pushState === void 0) { return; }

  var getTop = function(element) {

      if(element.nodeName === 'HTML'){
        return -window.pageYOffset;
      }
      return element.getBoundingClientRect().top + window.pageYOffset;
  };
  var easeInOutCubic = function (t) { return t<0.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1; };
  var position = function(start, end, elapsed, duration) {
      if (elapsed > duration){
        return end;
      }
      return start + (end - start) * easeInOutCubic(elapsed / duration); // <-- you can change the easing funtion there
  };
  var smoothScroll = function(el, duration, callback){
      duration = duration || 500;
      var start = window.pageYOffset,
          end;
      if (typeof el === 'number') {
        end = parseInt(el);
      } else {
        end = getTop(el) - 50;
      }
      var clock = Date.now();
      var requestAnimationFrame = window.requestAnimationFrame ||
          window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame ||
          function(fn){window.setTimeout(fn, 15);};
      var step = function(){
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
  var linkHandler = function(ev) {
      ev.preventDefault();
      var hash = this.hash.substring(1);
      if (window.history && window.history.pushState){
        history.pushState(null, null, '#' + hash);
      }
      smoothScroll(document.getElementById(hash), 500, function(el) {
      });
  };
  var internal = document.querySelectorAll('a[href^="#"]'), a;
  for(var i=internal.length; a=internal[--i];){
      a.addEventListener("click", linkHandler, false);
  }

  if(window.location.hash) {
    setTimeout(function(){
      smoothScroll(document.getElementById(window.location.hash.substring(1)), 200, function(el){});      
    }, 300);
  }


  return smoothScroll;
}

export {smooth_scroll_fn};
