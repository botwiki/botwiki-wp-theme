$(function() {
  'use strict';
  $( '.js-select2' ).each(function(i){
    var $this = $( this ),
        ajax_url = $this.data( 'ajax' );

    console.log( $this );

    if (ajax_url){
      function process_search_results(results){
        var data = [];

        $.each(results, function (k, v) {
          var tag_name = v.name;
          data[ k ] = {
            id: tag_name,
            text: tag_name
          };
        });
        return data;
      }

      let options = {

      };

      console.log( 'required?', $this.attr( 'required' ) );

      $this.select2({
        tags: true,
        placeholder: $( this ).attr( 'placeholder' ),          
        minimumInputLength: parseInt($( this ).data( 'minimum-input-length' )) || 3,
        ajax:{
          url: ajax_url,
          dataType: 'json',
          // delay: 250,
          data: function (params) {
            var query = {
              search: params.term
            }
            return query;
          },
          processResults: function (data, page, query) {
            var results = process_search_results(data);

            return {
              results: results.sort(function(a,b){
                return levenshtein_distance(a.text, page.term) - levenshtein_distance(b.text, page.term);
              })
            };
          }
        }
      })
    }
    else{

      let options = {
        tags: $( this ).data( 'tags' ),
        multiple: $( this ).data( 'multiple' ),
        placeholder: $( this ).attr( 'placeholder' ),
        minimumInputLength: parseInt($( this ).data( 'minimum-input-length' )) || 3
      };

      if ( !$this.attr( 'required' ) ){
        options.allowClear = true;
      }

      $this.select2( options );

      if ( $this.data( 'clear' ) ){
        $this.val(null).trigger( 'change' );

      }
    }
  });

});
