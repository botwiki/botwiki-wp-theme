(function () {
  var hideElements = [
    '#signin-dropdown',
    '.topbar',
    '.SidebarCommonModules',
  ];
  // #page-container{padding:0;}

  hideElements.forEach( function( selector ){
    el = document.querySelector( selector );
    if (el) {
      el.style.display = 'none';
    }
  } );
})();