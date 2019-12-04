( function( $ ) {
  'use strict';

  var $archive_btn = $('#output-archive-btn'),
      $archive_btn_remove = $('#output-archive-remove'),
      $archive_file_wrapper = $('#output-archive-file-wrapper'),
      $archive_date_wrapper = $('#output-archive-date-wrapper'),
      $archive_date = $('#output-archive-date'),
      $archive_file = $('#output-archive-file'),
      $output_archive_url = $('#output-archive-url'),
      $output_archive_link = $('#output-archive-link'),
      $output_archive_filename = $('#output-archive-filename'),      
      archive_select_frame;


  $archive_btn_remove.on('click', function(ev){
    ev.preventDefault();

    var confirm_archive_remove = confirm('Are you sure you want to remove archive?');

    if (confirm_archive_remove === true){
      $archive_date.val('');
      $output_archive_url.val('');
      $output_archive_filename.val('');
      $output_archive_link.attr('href', '#');
      $archive_file_wrapper.addClass('hidden');
      $archive_date_wrapper.addClass('hidden');
    }

    return false;
  });

  $archive_btn.on('click', function(ev){
    ev.preventDefault();

    if ( archive_select_frame != null ) {
      archive_select_frame.open();
      return;
    }

    archive_select_frame = wp.media({
      title: 'Select bot\'s archive file',
      button: {
        text: 'Yep, this one'
      },
      multiple: false
    });

    
    archive_select_frame.on( 'select', function() {
      var attachment = archive_select_frame.state().get('selection').first().toJSON();
      console.log(attachment);

      $archive_file_wrapper.removeClass('hidden');
      $archive_date_wrapper.removeClass('hidden');

      $output_archive_url.val(attachment.url);
      $output_archive_filename.val(attachment.filename);
      $output_archive_link.attr('href', attachment.url);


      // imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );
      // imgIdInput.val( attachment.id );
      // addImgLink.addClass( 'hidden' );
      // delImgLink.removeClass( 'hidden' );
    });

    archive_select_frame.open();
  });
} )( jQuery );
