"use strict";

var search = {
  init: function(){
    var $search_filter_checkboxes = $('#search-filters').find('input[type="checkbox"]'),
        $search_filter_tutorials = $('#search-filters-tutorials'),
        $search_filter_resources = $('#search-filters-resources');

    $('#search-input').click(function(ev){
      $('#search-filters').removeClass('d-none');
    });
  }
}

export {
  search
};




