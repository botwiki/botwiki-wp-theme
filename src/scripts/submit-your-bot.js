/* globals MediumEditor */

$( function() {
  let $body = $( 'body' ),
      $form_submit_button = $( '#bot-form-submit' );

  function enable_selected_tweets_field(){
    let $bot_networks_select = $( '[name="bot-networks[]' ),
        $selected_tweets_field = $( '#bot-selected-tweets-field' ),
        $selected_tweets_label = $( '#bot-selected-tweets-label' );


    $bot_networks_select.on( 'change', function(){
      let show_selected_tweets_field = false;
      $bot_networks_select.each( function( i ){
        let $this = $( this ),
            selected_network = $this.children( "option" ).filter( ":selected" ).text();

        if ( selected_network.indexOf( 'Twitter' ) > -1 ){
          $selected_tweets_label.html( 'tweets' );
          show_selected_tweets_field = true;
        }
        if ( selected_network.indexOf( 'Mastodon' ) > -1 ){
          $selected_tweets_label.html( 'toots' );
          show_selected_tweets_field = true;
        }
      } );

      if ( show_selected_tweets_field ){
        $selected_tweets_field.removeClass( 'd-none' );
      }
      else{
        $selected_tweets_field.addClass( 'd-none' );            
      }
    } );
  }

  if ( $body.hasClass( 'page-template-template-submit-your-bot' ) ){

    /* Disassociate bot author to allow logged in users to submit other people's bots. */

    let $disassociateAuthorBtn = $( '#disassociate-author' );

    $disassociateAuthorBtn.click( function( ev ){
      ev.preventDefault();
      $( '#logged-in-author' ).remove();
      $( '#disassociate-author-input' ).val( 'true' );
      $( '#add-author-fields' ).click();
      return false;
    } );
    

    /* Test submission, only available to admins. */

    $( '#test' ).click( function( ev ){
      ev.preventDefault();
      $disassociateAuthorBtn.click();

      $( '#add-author-fields' ).before( '<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-2-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-2-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>' );

      $( '#bot-name' ).val( '@coolbot' );
      $( '#author-1-name' ).val( 'Stefan' );
      $( '#author-1-url' ).val( 'https://twitter.com/fourtonfish' );
      $( '#author-2-name' ).val( 'John Doe' );

      window.bot_description_editor.setContent('generates random images.');

      let bot_info_network_select_html = $( '#bot-info-1-network' ).html();

      $( '#add-bot-info-fields' ).before( `<div class="bot-info-fields form-row"><div class="form-group col-md-6"><input type="url" class="form-control" id="bot-info-2-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot2"></div><div class="form-group col-md-6"><select class="form-control js-select2" id="bot-info-2-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">${bot_info_network_select_html.replace( '-1-', '-2-' )}</select></div></div>` );

      $( '#bot-info-2-network' ).select2( {
        tags: true,
        placeholder: $( this ).attr( 'placeholder' )
      } );

      $( '#bot-info-1-network' ).val( 'twitter-bots' ).trigger( 'change' );

      $( '#bot-info-2-network' ).val( 'tumblr-bots' ).trigger( 'change' );

      $( '#bot-info-1-url' ).val( 'https://twitter.com/coolbot' );
      $( '#bot-info-2-url' ).val( 'https://coolbot.tumblr.com/' );

      $( '#bot-selected-tweets' ).val( 'https://twitter.com/mycoolbot/status/123456789\nhttps://twitter.com/mycoolbot/status/987654321' );
      $( '#bot-tagline' ).val( 'This is a cool bot.' );

      $( '#bot-tags' ).append(
        `<option value="generative">generative</option>
         <option value="images">images</option>`
       ).val( ['generative', 'images'] ).trigger( 'change' );

      $( '#bot-is-opensource' ).click();

      $( '#bot-source-url' ).val( 'https://github.com/botwiki/botwiki.org' );
      $( '#bot-source-language' ).val( 'nodejs' ).trigger( 'change' );

      $( 'html, body' ).animate( {
          scrollTop: $form_submit_button.offset().top - 500
      }, 450 );

      return false;
    } );

    $( '.js-select2' ).each( function( i ){
      let $this = $( this ),
          ajax_url = $this.data( 'ajax' );

      if ( ajax_url ){
        window.processSearchResults = function( results ){
          let data = [];

          $.each( results, function ( k, v ) {
            let tag_name = v.name;
            data[ k ] = {
              id: tag_name,
              text: tag_name
            };
          } );
          return data;
        };

        $this.select2( {
          tags: true,
          placeholder: $( this ).attr( 'placeholder' ),          
          minimumInputLength: parseInt( $( this ).data( 'minimum-input-length' ) ) || 3,
          ajax:{
            url: ajax_url,
            dataType: 'json',
            // delay: 250,
            data: function ( params ) {
              let query = {
                search: params.term
              };
              return query;
            },
            processResults: function ( data, page, query ) {
              let results = window.processSearchResults( data );

              return {
                results: results.sort( function( a,b ){
                  return window.levenshteinDistance( a.text, page.term ) - window.levenshteinDistance( b.text, page.term );
                } )
              };
            }
          }
        } );
      }
      else{
        $this.select2( {
          tags: true,
          placeholder: $( this ).attr( 'placeholder' ),
          minimumInputLength: parseInt( $( this ).data( 'minimum-input-length' ) ) || 3
        } );
      }
    } );

    $( '#submit-bot-form' ).submit( function(){
      $form_submit_button.attr( 'disabled', 'disabled' ).html( 'Please wait...' );
      setTimeout( function(){
        $form_submit_button.html( 'Still working...' );
      }, 4700 );
    } );

    $( '#add-author-fields' ).click( function( ev ){
      ev.preventDefault();

      let new_id = $( '.author-fields' ).length + 1;

      $( this ).before( `<div class="author-fields form-row"><div class="form-group col-md-6"><input type="text" class="form-control" id="author-${new_id}-name" name="author-names[]" placeholder="Author"></div><div class="form-group col-md-6"><input type="url" class="form-control" id="author-${new_id}-url" name="author-urls[]" placeholder="https://twitter.com/author"></div></div>` );

      return false;
    } );

    let bot_info_network_select_html = $( '#bot-info-1-network' ).html();

    $( '#add-bot-info-fields' ).click( function( ev ){
      ev.preventDefault();

      let new_id = $( '.bot-info-fields' ).length + 1;

      $( this ).before( `<div class="bot-info-fields form-row">
          <div class="form-group col-md-6">
            <input type="url" class="form-control" id="bot-${new_id}-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot${new_id}">
          </div>
          <div class="form-group col-md-6">
            <select class="form-control js-select2" id="bot-info-${new_id}-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,...">${bot_info_network_select_html.replace( '-1-', new_id )}
            </select>
          </div>
        </div>` );

      $( `#bot-info-${new_id}-network` ).select2( {
        tags: true,
        placeholder: $( this ).attr( 'placeholder' )
      } );

      enable_selected_tweets_field();

      return false;
    } );

    let $bot_source_info = $( '#bot-source-info' );

    $( '#bot-is-opensource' ).click( function( ev ){

      if ( $( this ).is( ':checked' ) ){
        $bot_source_info.removeClass( 'd-none' );
      }
      else{
        $bot_source_info.addClass( 'd-none' );
      }
    } );

    let $botSubmittedNotice = $( '#bot-submitted-output' );

    $( document ).on( 'change input propertychange', '[name="bot-urls[]"]', function( ev ){
      let $inputField = $( this ),
          $networkField = $inputField.parents( '.bot-info-fields' ).find( '[name="bot-networks[]"]'),
          inputFieldId = $inputField.attr( 'id' ),
          inputFieldValue = $inputField.val().trim();

      if ( inputFieldValue.indexOf( 'twitter.com' ) !== -1 ){
        $networkField.val( 'twitter-bots' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'tumblr.com' ) !== -1 ){
        $networkField.val( 'tumblr-bots' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'mastodon.social' ) !== -1 ){
        $networkField.val( 'mastodon' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'botsin.space' ) !== -1 ){
        $networkField.val( 'mastodon' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'reddit.com' ) !== -1 ){
        $networkField.val( 'reddit-bots' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'facebook.com' ) !== -1 ){
        $networkField.val( 'facebook-bots' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'instagram.com' ) !== -1 ){
        $networkField.val( 'instagram-bots' ).trigger( 'change' );
      } else if ( inputFieldValue.indexOf( 'youtube.com' ) !== -1 ){
        $networkField.val( 'youtube-bots' ).trigger( 'change' );
      }

      if ( inputFieldId.indexOf( 'bot-' ) !== -1 && inputFieldId.indexOf( '-url' ) !== -1  ){
        if ( inputFieldValue && inputFieldValue.length > 5 && inputFieldValue.indexOf( 'http' ) !== -1 ){
          $.ajax( {
            url: `/wp-json/wp/v2/bot?bot_url=${ inputFieldValue }`,
            success: function( data ) {
              if ( typeof data !== 'undefined' && data && data.length > 0 && data.length < 3 ) {
                let resultsHtml = '';

                try{
                  data = JSON.parse( data );
                } catch( err ){ /* noop */ }

                data.forEach( function( bot ){
                  resultsHtml += `<div class="media">
                    <a href="${ bot.link }" target="_blank">
                      <img width="120px" src="${ bot.featured_image_url }" class="mr-3" alt="Screenshot of ${ bot.title.rendered }" title="Screenshot of ${ bot.title.rendered }">
                    </a>
                    <div class="media-body">
                      <h5 class="mt-0"><a href="${ bot.link }" target="_blank">${ bot.title.rendered }</a></h5>
                      <p>${ bot.excerpt.rendered }</p>
                    </div>
                  </div>`;
                } );
                $botSubmittedNotice.html( resultsHtml ).parent().removeClass( 'd-none' );
              }
            }
          } );
        } else {
          $botSubmittedNotice.html( '' ).parent().addClass( 'd-none' );
        }
      }
    } );

    let $apply_for_botmaker_badge = $( '#apply-for-botmaker-badge' ),
        $botaker_badge_application = $( '#botmaker-badge-application' );

    $apply_for_botmaker_badge.click( function( ev ){

      if ( $( this ).is( ':checked' ) ){
        $botaker_badge_application.removeClass( 'd-none' );
      }
      else{
        $botaker_badge_application.addClass( 'd-none' );
      }
    } );

    enable_selected_tweets_field();
  }
  if ( typeof MediumEditor !== "undefined" ){
    window.bot_description_editor = new MediumEditor( '#bot-description', {
      placeholder: {
        text: 'This bot makes...',
        hideOnClick: true
      },
      toolbar: {
        buttons: ['anchor', 'pre', 'quote']
      }
    } );
  }
} );