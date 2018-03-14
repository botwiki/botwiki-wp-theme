"use strict";

var bot_submission = {
  init: function(){
    var $body = $('body'),
        $form_submit_button = $('#bot-form-submit');
    if ($body.hasClass('page-template-template-submit-your-bot')){

      $('#test').click(function(ev){
        ev.preventDefault();

        $('#add-author-fields').before('<div class="author-fields form-row"><div class="form-group col-md-6"><label for="author-${new_id}-name">Author\'s name</label><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><label for="author-2-url">Author\'s URL</label><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

        $('#bot-name').val('@coolbot');
        $('#author-1-name').val('Stefan');
        $('#author-1-url').val('https://twitter.com/fourtonfish');
        $('#author-2-name').val('John Doe');
        // $('#author-2-url').val('https://twitter.com/jd');
        $('#bot-description').val('This bot generates random images.');
        $('#bot-urls').val('https://twitter.com/coolbot');
        $('#bot-selected-tweets').val('https://twitter.com/mycoolbot/status/123456789\nhttps://twitter.com/mycoolbot/status/987654321');
        $('#bot-tagline').val('This is a cool bot.');
        $('#bot-networks').val('5');
        $('#bot-networks').trigger('change');
        $('#bot-tags').val(['92', '64', '55']);
        $('#bot-tags').trigger('change');

        $('#bot-is-opensource').click();

        $('#bot-source-url').val('https://github.com/botwiki/botwiki.org');
        $('#bot-source-language').val('25');
        $('#bot-source-language').trigger('change');

        $('html, body').animate({
            scrollTop: $form_submit_button.offset().top - 500
        }, 450);


        return false;
      });

      $('.js-select2').each(function(i){
        $(this).select2({
          placeholder: $(this).attr('placeholder')
        });
      });


      $('#submit-bot-form').submit(function(){
        $form_submit_button.attr('disabled', 'disabled').html('Please wait...');
        setTimeout(function(){
          $form_submit_button.html('Still working...');
        }, 4500);
      });

      $('#add-author-fields').click(function(ev){
        ev.preventDefault();

        var new_id = $('.author-fields').length + 1;

        $(this).before('<div class="author-fields form-row"><div class="form-group col-md-6"><label for="author-${new_id}-name">Author\'s name</label><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><label for="author-2-url">Author\'s URL</label><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>');

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

      var $bot_networks_select = $('#bot-networks'),
          $selected_tweets_field = $('#bot-selected-tweets-field');


      $bot_networks_select.on('change', function(){
        if ($bot_networks_select.children("option").filter(":selected").text().indexOf('Twitter') > -1){
          $selected_tweets_field.removeClass('d-none');
        }
        else{
          $selected_tweets_field.addClass('d-none');
        }
      });


    }
  }
}

export {
  bot_submission
};




