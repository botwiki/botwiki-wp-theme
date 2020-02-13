( function( $ ) {
  'use strict';
  $(document).on('click', '#get-screenshot', function(ev){
    var $get_screenshot_btn = $(this),
        screenshot_btn_txt = $get_screenshot_btn.html(),
        $screenshot_img = $('#screenshot-img'),
        $page_url = $('#page-url'),
        page_url = $page_url.val().trim(),
        $screenshot_hint = $('#screenshot-hint');

    if (page_url){
      var url_parts = page_url.split('/'),
          download_name = url_parts[url_parts.length - 1]; 

      $get_screenshot_btn.html('Processing').attr('disabled', true);
      $screenshot_img.attr({
        'src': '',
        'download': ''
      });
      $screenshot_hint.addClass('hidden');

      $.post( ajaxurl, {
        action: 'make_screenshot',
        url: page_url
      }).done(function( data ) {
        // console.log(data);
        $screenshot_hint.removeClass('hidden');
        $get_screenshot_btn.html(screenshot_btn_txt).attr('disabled', false);
        $screenshot_img.attr({
          'src': data.image_url,
          'download': download_name
        });
      });
    }
    else{
      alert('You forgot the URL!');
    }
  });
} )( jQuery );
