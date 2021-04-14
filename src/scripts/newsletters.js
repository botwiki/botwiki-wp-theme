$(function() {
  'use strict';
  /* Fix for the Email Posts to Subscribers plugin */
  $(document).on('submit', '.elp_form', function (e) {
    e.preventDefault();
    var form = $(this);
    EmailPostsSubscribersFun(form);
  });

});