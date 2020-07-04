<?php

class Tutorials_Note {
  public function __construct() {
    add_filter( 'the_content', array( $this, 'tutorials_add_note' ) );
  }

  public function tutorials_add_note( $content ) { 
    global $post;
    $post_type = get_post_type( $post->ID );
    // error_log( print_r( $post_type, true ) );

    if( $post_type === 'resource'){
      $post_terms = get_the_terms( $post->ID, 'resource_type');

      foreach ( $post_terms as $term ) {
        if ( $term->slug === 'tutorial' ){
          $content = '<div class="note mt-5"><p>Before you start making bots, consider reading <a href="'
                   . get_site_url()
                   . '/learn/#essays">these essays and articles</a>. Also worth browsing: <a href="'
                   . get_site_url()
                   . '/resources/libraries-and-frameworks/#language-filter">resources for cleaning up your bot\'s language</a>.</p></div>'
                   . $content;
        }
      }
    }
    return $content;
  }
}

$tutorials_note = new Tutorials_Note();
