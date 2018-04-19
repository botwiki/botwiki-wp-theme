<?php

/* Add custom post types to the RSS feed. */

class Custom_Post_Type_RSS {
  public function __construct() {
    add_filter( 'request', array( $this, 'rss_feed' ) );
  }

  public function rss_feed( $query_var ) { 
    if ( isset( $query_var['feed'] ) )
        $query_var['post_type'] = array( 'post', 'bot', 'resource' );
    return $query_var;
  }
}

$custom_post_type_rss_init = new Custom_Post_Type_RSS();
