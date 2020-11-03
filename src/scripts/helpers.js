window.bwHelpers = {
  randomFromArray: function( arr, count ) {
    let newArray = [];
    count = count || 1;
    for ( let i = 0; i < count; i++){
      arr.sort( function(){ return Math.round( Math.random() ) ; } );
      newArray.push( arr.pop() );
    }
    return newArray;
  }
}
