// $( function(){
//   'use strict';
//   const bots = {
//     'crookedcosmos': [
//         'https://botwiki.org/wp-content/uploads/2020/01/D-An1_cXUAE2gBb.png',
//         'https://botwiki.org/wp-content/uploads/2020/01/D-DUdoVXYAEgefA.png',
//         'https://botwiki.org/wp-content/uploads/2020/01/D-GzbVyWkAA4xJx.png',
//         'https://botwiki.org/wp-content/uploads/2020/01/DW_PzjxWsAA2R7g.png',
//         'https://botwiki.org/wp-content/uploads/2020/01/DYfHVTvWsAA8vu8-1.jpeg',
//     ],
//     'muted-mountains': [
//         'https://botwiki.org/wp-content/uploads/2020/02/ECgJCClVUAAyS1u-scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EEB_PmAU4AETCxL-scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EGNCKy3U4AAHVn--scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EGZDNSbUEAE-cli-scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EKdHfy9VAAAuinm-scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EMkusntUYAArSCg-scaled.jpeg',
//         'https://botwiki.org/wp-content/uploads/2020/02/EOMlbNQVUAAv3R4-scaled.jpeg'
//     ]
//   }


//   window.bwHelpers.randomFromArray( Object.keys( bots ), 2 ).forEach( function( botName, i ){
//       const bot = bots[botName];
//       const image = window.bwHelpers.randomFromArray( bot );
//       console.log( botName, image );

//       let imgStyle = `
//           position: absolute;
//           top: ${ 100 * ( i + 1 ) }px;
//           ${ i % 2 === 0 ? 'left' : 'right' }: 100px;
//           width: 300px;
//           z-index: 0;
//       `;

//       // $( 'body' ).append( `<img class="lazy-load" style="${ imgStyle }" id="bot-showcase-img-${i}" src="${ image }" />` );
//       $( 'body' ).append( `<img style="${ imgStyle }" id="bot-showcase-img-${i}" src="${ image }" />` );
//       console.log( $( `#bot-showcase-img-${i}` ) );

//   } );

//   var imageNodes = document.querySelectorAll('.lazy-load');

//   for (var i = 0; i < imageNodes.length; i++) {
//     var imageNode = imageNodes[i];

//     // Add a placeholder if one doesn't exist
//     //imageNode.src = imageNode.src || lazyLoader.tinyGif;

//     console.log( 'imageNode', imageNode );

//     lazyLoader.cache.push(imageNode);
//   }

//   lazyLoader.addObservers();
//   lazyLoader.loadVisibleImages();



// } );
