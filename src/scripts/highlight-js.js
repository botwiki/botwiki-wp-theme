$( function(){
  'use strict';
  if ( typeof hljs !== 'undefined' ){
    hljs.initHighlighting.called = false;
    hljs.initHighlighting();

    let blocks = document.querySelectorAll('pre code.hljs');
        Array.prototype.forEach.call(blocks, function(block) {
        const language = block.result.language;
        block.insertAdjacentHTML( 'afterbegin', `<label>${language}</label>` );
    });        
  }
} );
