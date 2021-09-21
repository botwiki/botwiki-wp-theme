<?php

class Random_Bot_Redirect {
  public function __construct() {
    add_filter( 'template_redirect', array( $this, 'show_random_bot' ) );
  }

  public function show_random_bot( $content ) { 
    global $wp_query;
    if ( isset( $wp_query->query['name'] ) && $wp_query->query['name'] === 'random-bot' ){
      $wp_query->is_404 = false;
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
      $bot_user_agents = array( 'Twitterbot/' );

      if ( str_contains_arr( $user_agent, $bot_user_agents ) ){
      ?><!doctype html>
        <html lang="en-US" class="no-js">
          <head>
            <title>🤖🤖🤖❓❓❓</title>
            <meta itemprop="name" content="🤖🤖🤖❓❓❓"/>
            <meta itemprop="url" content="https://botwiki.org/random-bot/"/>
            <meta property="og:title" content="🤖🤖🤖❓❓❓" />
            <meta name="twitter:title" content="🤖🤖🤖❓❓❓" />
            <meta name="description" content="Explore the wonderful world of online bots, one random bot at a time." />
            <meta property="og:description" content="Explore the wonderful world of online bots, one random bot at a time." />
            <meta name="twitter:description" content="Explore the wonderful world of online bots, one random bot at a time." />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="https://botwiki.org" />
            <meta property="og:image" content="https://botwiki.org/wp-content/uploads/2018/03/bots-galore.png" /> 
            <meta property="twitter:image:src" content="https://botwiki.org/wp-content/uploads/2018/03/bots-galore.png" /> 
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:site" content="@botwikidotorg" />
            <meta name="twitter:domain" content="https://botwiki.org/" />
          </head>
          <body>
          </body>
        </html>
      <?php } else {
        $random_bot = new WP_Query(array(
          'post_type' => 'bot',
          'post_status' => 'publish',
          'posts_per_page' => 1,
          'orderby' => 'rand' 
        ) );
        wp_redirect( get_post_permalink( $random_bot->post->ID ) );
      }
    }
  }
}

$random_bot_redirect = new Random_Bot_Redirect();
