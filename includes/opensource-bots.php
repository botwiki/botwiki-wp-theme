<?php

class Filter_Opensource_Bots {
  public function __construct() {
    add_action( 'pre_get_posts', array( $this, 'filter_opensource_bots' ) );
    add_action( 'query_vars', array( $this, 'add_query_vars' ) );
  }

  public function filter_opensource_bots( $wp_query ) {
    if ( $wp_query->is_main_query() && !empty( $_GET['opensource'] ) ) {
      $wp_query->set('meta_key', 'bot_source_url');
      $wp_query->set('meta_value', array(''));
      $wp_query->set('meta_compare', 'NOT IN');
    }
  }

  public function add_query_vars( $vars ) {
    $vars[] = 'opensource';
    return $vars;
  }
}

$filter_opensource_bots_init = new Filter_Opensource_Bots();
