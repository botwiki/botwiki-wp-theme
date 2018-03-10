"use strict";

function unshift_elements(shifted_els){
  if (shifted_els.length > 0){
    Array.prototype.forEach.call(shifted_els, function(el, index) {
      setTimeout(
        function(){
          el.classList.remove('shifted');
          el.classList.add('unshifted');
        },
        55 * index * index);
    });
  }  
}

export {
  unshift_elements
};
