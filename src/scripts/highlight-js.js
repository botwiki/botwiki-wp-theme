$( function(){
  'use strict';
  if ( typeof hljs !== 'undefined' ){
    hljs.initHighlighting.called = false;
    hljs.initHighlighting();
  }
} );
