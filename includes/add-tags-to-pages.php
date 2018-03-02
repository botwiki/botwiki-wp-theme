<?php

class Add_Tags_To_Pages {
  public function __construct() {
    add_action('init', array( $this, 'tags_support_all' ) );
    add_action('pre_get_posts', array( $this, 'tags_support_query' ) );
  }

  public function tags_support_all() {
    register_taxonomy_for_object_type('post_tag', 'page');
  }

  public function tags_support_query($wp_query) {
    if ($wp_query->get('tag')){
      $wp_query->set('post_type', 'any');      
    }
  }
}

$add_tags_to_pages_init = new Add_Tags_To_Pages();
