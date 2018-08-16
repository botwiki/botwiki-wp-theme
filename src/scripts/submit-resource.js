/* globals MediumEditor */

$(function() {
  var $body = $('body'),
      $form_submit_button = $('#resource-form-submit');

  function enable_selected_tweets_field(){
    var $resource_networks_select = $('[name="resource-networks[]'),
        $selected_tweets_field = $('#resource-selected-tweets-field');

    $resource_networks_select.on('change', function(){
      var show_selected_tweets_field = false;
      $resource_networks_select.each(function(i){
        var $this = $(this);
        if ($this.children("option").filter(":selected").text().indexOf('Twitter') > -1){
          show_selected_tweets_field = true;
        }
      });

      if (show_selected_tweets_field){
        $selected_tweets_field.removeClass('d-none');
      }
      else{
        $selected_tweets_field.addClass('d-none');            
      }
    });
  }

  if ($body.hasClass('page-template-template-submit-resource')){

    /* Wake up the screenshot service on Glitch. */

    $.get({
      url: 'https://screenshot-beta.glitch.me'
    }).done(function( data ) {
      /* noop */
    });



    /* Disassociate resource author to allow logged in users to submit other people's resources. */

    $('#disassociate-author').click(function(ev){
      ev.preventDefault();
      $('#logged-in-author').remove();
      $('#disassociate-author-input').val('true');
      $('#add-author-fields').click();
      return false;
    });
    

    /* Test submission, only available to admins. */

    $('#test').click(function(ev){
      ev.preventDefault();

      $('#add-author-fields').before('<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

      $('#resource-name').val('One cool resource');
      $('#author-1-name').val('Stefan');
      $('#author-1-url').val('https://twitter.com/fourtonfish');
      $('#author-2-name').val('John Doe');
      // $('#author-2-url').val('https://twitter.com/jd');
      var resource_description_editor = new MediumEditor('#resource-description', {});

      resource_description_editor.setContent('generates random images.');

      $('#resource-url').val('https://coolresource.github.com/');

      $('#resource-selected-tweets').val('https://twitter.com/mycoolresource/status/123456789\nhttps://twitter.com/mycoolresource/status/987654321');
      $('#resource-tagline').val('This is a cool resource.');
      $('#resource-networks').val(['twitter-bots']);
      $('#resource-networks').trigger('change');

      $('#resource-tags').val(['generative', 'images', 'nodejs']);
      $('#resource-tags').trigger('change');

      $('#resource-is-opensource').click();

      $('#resource-source-url').val('https://github.com/resourcewiki/resourcewiki.org');
      $('#resource-source-language').val('nodejs');
      $('#resource-source-language').trigger('change');

      $('html, body').animate({
          scrollTop: $form_submit_button.offset().top - 500
      }, 450);

      return false;
    });

    $('.js-select2').each(function(i){
      $(this).select2({
        tags: true,
        placeholder: $(this).attr('placeholder'),
        minimumInputLength: parseInt($(this).data('minimum-input-length')) || 3
      });
    });

    $('#submit-resource-form').submit(function(){
      $form_submit_button.attr('disabled', 'disabled').html('Please wait...');
      setTimeout(function(){
        $form_submit_button.html('Still working...');
      }, 4700);
    });

    var $resource_type_select = $('[name="resource-type'),
        $resource_type_name = $('.resource-type-name');

    $resource_type_select.on('change', function(ev){
      var resource_type_name = $(this).children("option").filter(":selected").text().toLowerCase();
      $resource_type_name.html(resource_type_name);
      $('#resource-name').attr('placeholder', `My ${resource_type_name}...`);
      $('#resource-tagline').attr('placeholder', `A useful ${resource_type_name}.`);
    });

    $('#add-author-fields').click(function(ev){
      ev.preventDefault();

      var new_id = $('.author-fields').length + 1;

      $(this).before(`<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-${new_id}-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-${new_id}-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>`);

      return false;
    });

    var resource_info_network_select_html = $('#resource-network').html();

    $('#add-resource-info-fields').click(function(ev){
      ev.preventDefault();

      var new_id = $('.resource-info-fields').length + 1;

      $(this).before(`<div class="resource-info-fields form-row"><div class="form-group col-md-6"><select class="form-control js-select2" id="resource-info-${new_id}-network" name="resource-networks[]" placeholder="Twitter, Tumblr, Slack,...">${resource_info_network_select_html.replace('-1-', new_id)}</select></div><div class="form-group col-md-6"><input type="url" class="form-control" id="resource-${new_id}-url" name="resource-url" placeholder="https://twitter.com/onecoolresource${new_id}"></div></div>`);

      $(`#resource-info-${new_id}-network`).select2({
        tags: true,
        placeholder: $(this).attr('placeholder')
      });

      enable_selected_tweets_field();

      return false;
    });

    var $resource_source_info = $('#resource-source-info');

    $('#resource-is-opensource').click(function(ev){

      if ($(this).is(':checked')){
        $resource_source_info.removeClass('d-none');
      }
      else{
        $resource_source_info.addClass('d-none');
      }
    });

    var $apply_for_botmaker_badge = $('#apply-for-botmaker-badge'),
        $resourceaker_badge_application = $('#botmaker-badge-application');

    $apply_for_botmaker_badge.click(function(ev){

      if ($(this).is(':checked')){
        $resourceaker_badge_application.removeClass('d-none');
      }
      else{
        $resourceaker_badge_application.addClass('d-none');
      }
    });

    enable_selected_tweets_field();
  }
  if (typeof MediumEditor !== "undefined"){
    var resource_description_editor = new MediumEditor('#resource-description', {
      placeholder: {
        text: 'It helps you/teaches you...',
        hideOnClick: true
      },
      toolbar: {
        buttons: ['anchor', 'pre', 'quote']
      }
    });    
  }
});