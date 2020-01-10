<?php

class Botmaker_Badges_Count {
  public function __construct() {
    add_shortcode( 'botmaker-badges-count', array( $this, 'get_botmaker_badges_count' ) );
  }

  public function get_botmaker_badges_count() {
    global $helpers;
    $offset = 45;

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'bot',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'botmaker_badge_awarded',
                'value' => 'on'
            )
        )
    );

    $bots_with_badges = new WP_Query( $args );
    return number_format( $offset + $bots_with_badges->found_posts );
  }
}

$botmaker_badges_count_init = new Botmaker_Badges_Count();
