<?php

class Random_Bot_Redirect {
  public function __construct() {
    add_filter( 'template_redirect', array( $this, 'show_random_bot' ) );
  }

  public function show_random_bot( $content ) { 
    global $wp_query;
    if ($wp_query->query['name'] === 'random-bot'){
      $all_bots = new WP_Query(array(
        'post_type' => 'bot',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'ignore_sticky_posts'=> true
      ));
      $random_bot = $all_bots->posts[array_rand($all_bots->posts)];
      $random_bot_url = get_post_permalink($random_bot->ID);
      wp_redirect($random_bot_url);
    }
  }
}

$random_bot_redirect = new Random_Bot_Redirect();
