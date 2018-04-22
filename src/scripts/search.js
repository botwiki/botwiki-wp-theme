$(function() {
  'use strict';
    var $search_filter_checkboxes = $('#search-filters').find('input[type="checkbox"]'),
        $search_filter_tutorials = $('#search-filters-tutorials'),
        $search_filter_resources = $('#search-filters-resources');

    $('#search-input').click(function(ev){
      $('#search-filters').removeClass('slide-up-hidden').addClass('slide-up');
    });
});
