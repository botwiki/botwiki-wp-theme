$( function() {
  $( document ).ready( function( $ ){
    function highlightCode(){
        document.querySelectorAll( 'pre code' ).forEach( ( block ) => {
            hljs.highlightBlock( block );
        } );

        let blocks = document.querySelectorAll('pre code.hljs');
            Array.prototype.forEach.call(blocks, function(block) {
            const language = block.result.language;
            block.insertAdjacentHTML( 'afterbegin', `<label>${language}</label>` );
        });        
    }

    if ( typeof hljs !== 'undefined' ){
        highlightCode();
    } else {
        setTimeout( highlightCode, 2000 );
    }
  } );
} );
