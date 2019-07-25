<?php

class Search_Filters {
  public function __construct() {
    add_action('pre_get_posts', array( $this, 'filter_search_results' ) );
  }

  public function filter_search_results($wp_query) {

    if ($wp_query->is_search && !is_admin() ) {
      if ( isset( $_GET['search-filters-options'] ) && !empty( $_GET['search-filters-options'] )){
        $search_filters_options = $_GET['search-filters-options'];

        if ( in_array( 'resources' , $search_filters_options ) ||
             in_array( 'tutorials' , $search_filters_options ) ){

          $post_types = array();
          $post_types[] = 'resource';

          if ( in_array( 'tutorials' , $search_filters_options ) ){
            $wp_query->set( 'resource_type', 'tutorial' );
          }

        }
        if ( in_array( 'bots' , $search_filters_options ) ){
          $post_types = array();
          $post_types[] = 'bot';
        }

        $wp_query->set( 'post_type', $post_types );
      }
    }

    return $wp_query;
  }
}

$search_filters_init = new Search_Filters();
