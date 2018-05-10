<?php

class Random_Bot_Redirect {
  public function __construct() {
    add_filter( 'template_redirect', array( $this, 'show_random_bot' ) );
  }

  public function show_random_bot( $content ) { 
    global $wp_query;
    if ($wp_query->query['name'] === 'random-bot'){
      $random_bot = new WP_Query(array(
        'post_type' => 'bot',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'rand'
      ));
      wp_redirect( get_post_permalink($random_bot->post->ID) );
    }
  }
}

$random_bot_redirect = new Random_Bot_Redirect();
