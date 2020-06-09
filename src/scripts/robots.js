/* globals MediumEditor */

$( function() {
  function randomFromArray( arr ){
    return arr[Math.floor( Math.random() * arr.length )];
  }

  const images = [
    '/wp-content/themes/botwiki/images/robots/EFFCy_oX4AESj3i.jpeg',
    '/wp-content/themes/botwiki/images/robots/EFKMZbiX4AcQCbN.png',
    '/wp-content/themes/botwiki/images/robots/EFQM7GoXoAAQN6r.png',
    '/wp-content/themes/botwiki/images/robots/EGUA_S0WsAIE03o.png',
    '/wp-content/themes/botwiki/images/robots/EHqDhb3X0AIgaSX.png',
    '/wp-content/themes/botwiki/images/robots/EI54X3oXUAA-i8k.png',
    '/wp-content/themes/botwiki/images/robots/EIFTnxIXkAAKlnM.png',
    '/wp-content/themes/botwiki/images/robots/EIIvWWBWsAUGDtX.png',
    '/wp-content/themes/botwiki/images/robots/EJYIvKRXUAATZt7.png',
    '/wp-content/themes/botwiki/images/robots/EKrm5zOWsAAAwIQ.png',
    '/wp-content/themes/botwiki/images/robots/EMzpr0WX0AUPKAD.png',
    '/wp-content/themes/botwiki/images/robots/ENV--axWoAE2R95.png',
    '/wp-content/themes/botwiki/images/robots/EOzcyTKWAAEIr8u.png',
    '/wp-content/themes/botwiki/images/robots/EP9_Xa7X0AE9-nz.png',
    '/wp-content/themes/botwiki/images/robots/EQMlNajWkAA83Sw.png',
    '/wp-content/themes/botwiki/images/robots/ERA8F2LUwAAdnOI.png',
    '/wp-content/themes/botwiki/images/robots/ERbVQjfXsAAwDRB.png',
    '/wp-content/themes/botwiki/images/robots/ERYwdhZXsAAcYuL.png',
    '/wp-content/themes/botwiki/images/robots/EUlexoMUwAYuMj_.jpeg',
    '/wp-content/themes/botwiki/images/robots/EUQBeAeXgAYxhP5.png',
    '/wp-content/themes/botwiki/images/robots/EV17armWoAM3cMw.png',
    '/wp-content/themes/botwiki/images/robots/EVIPh3sWAAMgwXN.png',
    '/wp-content/themes/botwiki/images/robots/EWspV_dUcAAqkTF.png',
    '/wp-content/themes/botwiki/images/robots/EYTv8FDX0AIBNFo.jpeg',
    '/wp-content/themes/botwiki/images/robots/EZq_8QTX0AYo6wn.jpeg'
  ];

  let robotsContainer = document.getElementById( 'robots' );
  let firstRobot = document.querySelector( '.robot-1' );

  robotsContainer.innerHTML = `<img class="robot robot-1" src="${ images[0] }">`; 

  let index = 1,
      totalCount = 1,
      colSize = Math.floor( robotsContainer.offsetHeight / 100 ),
      rowSize = Math.floor( robotsContainer.offsetWidth / 100 ),
      totalCountFinal = rowSize * colSize;

  let css = '',
      head = document.head || document.getElementsByTagName('head')[0],
      style = document.createElement( 'style' );

  for ( let i = 0; i <= totalCountFinal; i++ ) {
    css += `.robot:nth-child(${ i }) { animation-delay: ${ 60/totalCountFinal/200 * i }s; }`;
  }

  head.appendChild( style );
  style.type = 'text/css';
  style.appendChild( document.createTextNode( css ) );


  while ( totalCount < totalCountFinal ){
    document.getElementById( 'robots' ).innerHTML = document.getElementById( 'robots' ).innerHTML + `<img class="robot robot-${ totalCount }" src="${ images[index] }">`;
    if ( index < images.length - 1 ){
      index++;
    } else {
      index = 0;
    }
    totalCount++;
  }


  // let imageEls = document.querySelectorAll( '.robot img' );

  // let mouseenterIntervals = [];

  // imageEls.forEach( function( img, i ){
  //   img.addEventListener( 'mouseenter', function( event ) {
  //     event.target.src = randomFromArray( images );

  //     mouseenterIntervals.push(
  //       setInterval( function() {
  //         event.target.src = randomFromArray( images );
  //       }, 100 ) );
  //     }, false )

  //   img.addEventListener( 'mouseleave', function( event ) {
  //     clearInterval( mouseenterIntervals[i] );
  //   }, false )

  // } );

  setTimeout( function(){
    [300, 750, 1250].forEach( function( interval ){
      setInterval( function(){
        let robots = document.querySelectorAll('.robot');
        let robot = randomFromArray( robots );
        robot.src = randomFromArray( images );
      }, interval );
    } );
  }, 1000 );

} );