"use strict";

var bot_submission = {
  init: function(){
    var $body = $('body');
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
            scrollTop: $('#bot-form-submit').offset().top - 500
        }, 450);


        return false;
      });

      $('.js-select2').each(function(i){
        $(this).select2({
          placeholder: $(this).attr('placeholder')
        });
      });


      $('#submit-bot-form').submit(function(){
        $('#bot-form-submit').attr('disabled', 'disabled').html('Please wait...');
      });

      $('#add-author-fields').click(function(ev){
        ev.preventDefault();

        var new_id = $('.author-fields').length + 1;

        $(this).before(`<div class="author-fields form-row"><div class="form-group col-md-6"><label for="author-${new_id}-name">Author's name</label><input type="text" class="form-control" id="author-${new_id}-name" name="author-${new_id}-name" placeholder="Author"></div><div class="form-group col-md-6"><label for="author-${new_id}-url">Author's URL</label><input type="url" class="form-control" id="author-${new_id}-url" name="author-${new_id}-url" placeholder="https://twitter.com/author"></div></div>`);

        return false;
      });

      var $bot_source_info = $('#bot-source-info');
      $bot_source_info.addClass('d-none');

      $('#bot-is-opensource').click(function(ev){

        if ($(this).is(':checked')){
          $bot_source_info.removeClass('d-none');
        }
        else{
          $bot_source_info.addClass('d-none');
        }

      });



    }
  }
}

export {
  bot_submission
};




