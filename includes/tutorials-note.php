<?php

class Tutorials_Note {
  public function __construct() {
    add_filter( 'the_content', array( $this, 'tutorials_add_note' ) );
  }

  public function tutorials_add_note( $content ) { 
    global $post;

    $post_title = get_the_title($post->ID);
    $parent = $post->post_parent;
    $parent_title = get_the_title($parent);

    if ($post_title === 'Tutorials' || $parent_title === 'Tutorials'){
      $content = '<div class="note"><p>Before you start making bots, consider reading <a href="'
               . get_site_url()
               . '/articles/essays">these essays and articles</a>. Also worth browsing: <a href="'
               . get_site_url()
               . '/resources/libraries-frameworks/#language">resources for cleaning up your bot\'s language</a>.</p></div>'
               . $content;

    }

    return $content;
  }
}

$tutorials_note = new Tutorials_Note();
