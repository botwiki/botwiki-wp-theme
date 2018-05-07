$(function() {
  var $body = $('body'),
      $form_submit_button = $('#bot-form-submit');
  if ($body.hasClass('page-template-template-submit-your-bot')){

    /* Wake up the screenshot service on Glitch. */

    $.get({
      url: 'https://screenshot-beta.glitch.me'
    }).done(function( data ) {
      /* noop */
    });



    /* Disassociate bot author to allow logged in users to submit other people's bots. */

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

      $('#bot-name').val('@coolbot');
      $('#author-1-name').val('Stefan');
      $('#author-1-url').val('https://twitter.com/fourtonfish');
      $('#author-2-name').val('John Doe');
      // $('#author-2-url').val('https://twitter.com/jd');
      $('#bot-description').val('generates random images.');

      var bot_info_network_select_html = $('#bot-info-1-network').html();

      $('#add-bot-info-fields').before(`<div class="bot-info-fields form-row"><div class="form-group col-md-6"><select class="form-control js-select2" id="bot-info-2-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">${bot_info_network_select_html.replace('-1-', '-2-')}</select></div><div class="form-group col-md-6"><input type="url" class="form-control" id="bot-info-2-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot2"></div></div>`);

      $('#bot-info-2-network').select2({
        tags: true,
        placeholder: $(this).attr('placeholder')
      });

      $('#bot-info-1-network').val('twitter-bots');
      $('#bot-info-1-network').trigger('change');

      $('#bot-info-2-network').val('tumblr-bots');
      $('#bot-info-2-network').trigger('change');

      $('#bot-info-1-url').val('https://twitter.com/coolbot');
      $('#bot-info-2-url').val('https://coolbot.tumblr.com/');

      $('#bot-selected-tweets').val('https://twitter.com/mycoolbot/status/123456789\nhttps://twitter.com/mycoolbot/status/987654321');
      $('#bot-tagline').val('This is a cool bot.');
      $('#bot-tags').val(['generative', 'images', 'nodejs']);
      $('#bot-tags').trigger('change');

      $('#bot-is-opensource').click();

      $('#bot-source-url').val('https://github.com/botwiki/botwiki.org');
      $('#bot-source-language').val('nodejs');
      $('#bot-source-language').trigger('change');

      $('html, body').animate({
          scrollTop: $form_submit_button.offset().top - 500
      }, 450);

      return false;
    });

    $('.js-select2').each(function(i){
      $(this).select2({
        tags: true,
        placeholder: $(this).attr('placeholder')
      });
    });

    $('#submit-bot-form').submit(function(){
      $form_submit_button.attr('disabled', 'disabled').html('Please wait...');
      setTimeout(function(){
        $form_submit_button.html('Still working...');
      }, 4700);
    });

    $('#add-author-fields').click(function(ev){
      ev.preventDefault();

      var new_id = $('.author-fields').length + 1;

      $(this).before(`<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-${new_id}-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-${new_id}-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>`);

      return false;
    });

    var bot_info_network_select_html = $('#bot-info-1-network').html();

    $('#add-bot-info-fields').click(function(ev){
      ev.preventDefault();

      var new_id = $('.bot-info-fields').length + 1;

      $(this).before(`<div class="bot-info-fields form-row"><div class="form-group col-md-6"><select class="form-control js-select2" id="bot-info-${new_id}-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">${bot_info_network_select_html.replace('-1-', new_id)}</select></div><div class="form-group col-md-6"><input type="url" class="form-control" id="bot-${new_id}-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot${new_id}"></div></div>`);

      $(`#bot-info-${new_id}-network`).select2({
        tags: true,
        placeholder: $(this).attr('placeholder')
      });

      enable_selected_tweets_field();

      return false;
    });

    var $bot_source_info = $('#bot-source-info');

    $('#bot-is-opensource').click(function(ev){

      if ($(this).is(':checked')){
        $bot_source_info.removeClass('d-none');
      }
      else{
        $bot_source_info.addClass('d-none');
      }
    });

    var $apply_for_botmaker_badge = $('#apply-for-botmaker-badge'),
        $botaker_badge_application = $('#botmaker-badge-application');

    $apply_for_botmaker_badge.click(function(ev){

      if ($(this).is(':checked')){
        $botaker_badge_application.removeClass('d-none');
      }
      else{
        $botaker_badge_application.addClass('d-none');
      }
    });

    var $selected_tweets_field = $('#bot-selected-tweets-field');

    function enable_selected_tweets_field(){
      var $bot_networks_select = $('[name="bot-networks[]');

      $bot_networks_select.on('change', function(){
        var show_selected_tweets_field = false;
        $bot_networks_select.each(function(i){
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
    enable_selected_tweets_field();
  }
  if (typeof MediumEditor !== "undefined"){
    var bot_description_editor = new MediumEditor('#bot-description', {
      placeholder: {
        text: 'This bot makes...',
        hideOnClick: true
      },
      toolbar: {
        buttons: ['anchor', 'pre', 'quote']
      }
    });    
  }
});