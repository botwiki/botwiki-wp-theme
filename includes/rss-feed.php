<?php

/* Customizing the RSS feed. */

class Custom_Post_Type_RSS {
  public function __construct() {
    add_filter( 'request', array( $this, 'rss_feed' ) );
    add_filter( 'the_excerpt_rss', array( $this, 'rss_feed_image' ) );
    add_filter( 'the_content_feed', array( $this, 'rss_feed_image' ) );
  }

  public function rss_feed( $query_var ) { 
    if ( isset( $query_var['feed'] ) )
        $query_var['post_type'] = array( 'post', 'bot', 'resource' );
    return $query_var;
  }

  public function rss_feed_image( $content ) { 
    global $post;
    if ( has_post_thumbnail( $post->ID ) ){
      $content = '<p>' . get_the_post_thumbnail( $post->ID, 'medium' ) . '</p>' . $content;
    }
    return $content;
  }
}

$custom_post_type_rss_init = new Custom_Post_Type_RSS();
