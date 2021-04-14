/* Fix for the Email Posts to Subscribers plugin */
(function ($) {
  'use strict';
  
  function prepareFormPostData_elp(form, formData) {
    jQuery.each((form.serializeArray() || {}), function (i, field) {
      formData['elp_' + field.name] = field.value;
    });
    return formData;
  }

  function loadResponse_elp(response, form) {
    var status = response.status;

    var message_class = 'success';
    if(status === 'ERROR') {
      message_class = 'error';
    }
    
    if(status === 'ERRORBOT') {
      message_class = 'boterror';
    }

    var responseText = response['message_text'];
    var messageContainer = $(form).next('.elp_form_message');
    messageContainer.attr('class', 'elp_form_message ' + message_class);
    messageContainer.html(responseText);
    var esSuccessEvent = { 
      detail: { 
            elp_response : message_class, 
            msg: responseText
          }, 
      bubbles: true, 
      cancelable: true 
    };

    jQuery(form).trigger('elp_response', [ esSuccessEvent ]);
  }

  function EmailPostsSubscribersFun(form){
    var formData = {};
    formData = prepareFormPostData_elp(form, formData);
    formData['elp_submit'] = 'submitted';
    formData['action'] = 'email_posts_subscribers';
    //alert(formData.toSource());
    var actionUrl = elp_data.elp_ajax_url;
    jQuery(form).find('#elp-loading-image').show();
    $.ajax({
      type: 'POST',
      url: actionUrl,
      data: formData,
      dataType: 'json',
      success: function (response) {
        if( response && typeof response.status !== 'undefined' && (response.status === "SUCCESS" || response.status === "ERRORBOT") ) {
          jQuery(form).slideUp('slow');
          jQuery(form).hide();
        } else {
          jQuery(form).find('#elp-loading-image').hide();
        }
        jQuery(window).trigger('elp_submit.send_response', [jQuery(form) , response]);
        loadResponse_elp(response, form);
      },
      error: function (err) {
        //alert(err.toSource());
        //alert(JSON.stringify(err, null, 4));
        jQuery(form).find('#elp-loading-image').hide();
        console.log(err, 'error');
      },
    });

    return false;
  }

  $(document).ready(function () {
    $(document).on('submit', '.elp_form', function (e) {
      e.preventDefault();
      var form = $(this);
      EmailPostsSubscribersFun(form);
    });

  });

})(jQuery);


