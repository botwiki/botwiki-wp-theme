<?php

/* Fixes and enhancements for the WP JSON API. */

class WP_JSON_API_Fixes_And_Enhancements {
  public function __construct() {
    add_filter( 'rest_api_init', array( $this, 'add_meta_fields' ) );
    add_filter( 'rest_api_init', array( $this, 'extra_query_strings' ) );
    add_filter( 'rest_prepare_bot', array( $this, 'add_bot_post_type_meta' ), 10, 3 );
    // add_filter( 'rest_post_dispatch', array( $this, 'api_response_cleanup' ), 10, 3 );
  }

  public function api_response_cleanup( $response, $server, $request ){
    return $response;
  }

  public function add_bot_post_type_meta( $data, $post, $context ) {
    $bot_url = get_post_meta( $post->ID, 'bot_url', true );

    if( !empty( $bot_url ) ) {
        $data->data['bot_url'] = $bot_url;
    }

    return $data;
  }

  public function add_meta_fields() {

    global $wp_post_types;

    register_rest_field( array_keys( $wp_post_types ),
        /* Add featured image to the JSON API, courtesy of https://github.com/dalenguyen/wordpress-snippets/blob/master/get-featured-image-from-rest-api.php. */
        'featured_image_url',
        array(
            'get_callback'    => array( $this, 'get_rest_featured_image' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array_keys( $wp_post_types ),
        'bot_urls',
        array(
            'get_callback'    => array( $this, 'get_rest_bot_url' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array_keys( $wp_post_types ),
        'bot_source_urls',
        array(
            'get_callback'    => array( $this, 'get_rest_bot_source_urls' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );


    register_rest_field( array_keys( $wp_post_types ),
        'bot_output_archive_url',
        array(
            'get_callback'    => array( $this, 'get_rest_bot_output_archive_url' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array_keys( $wp_post_types ),
        'source_languages',
        array(
            'get_callback'    => array( $this, 'get_rest_bot_source_languages' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array_keys( $wp_post_types ),
        'bot_author_info',
        array(
            'get_callback'    => array( $this, 'get_rest_bot_author_info' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array_keys( $wp_post_types ),
        'tags_full',
        array(
            'get_callback'    => array( $this, 'get_rest_tags_full' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );
  }

  public function get_rest_bot_url( $object, $field_name, $request ) {
    $bot_urls = null;
    $bot_url = get_post_meta( $object['id'], 'bot_url', true );

    if ( strlen( $bot_url ) > 0 ){
      $bot_urls = preg_split('/\n|\r\n?/', $bot_url);
    }
    return $bot_urls;
  }

  public function get_rest_bot_source_urls( $object, $field_name, $request ) {
    $bot_source_urls = null;
    $bot_source_url = get_post_meta( $object['id'], 'bot_source_url', true );

    if ( strlen( $bot_source_url ) > 0 ){
      $bot_source_urls = preg_split('/\n|\r\n?/', $bot_source_url);
    }

    return $bot_source_urls;
  }

  public function get_rest_bot_output_archive_url( $object, $field_name, $request ) {
    $bot_output_archive_url = get_post_meta( $object['id'], 'output_archive_url', true );
    return $bot_output_archive_url;
  }

  public function get_rest_bot_source_languages( $object, $field_name, $request ) {
    $bot_source_languages = wp_get_post_terms( $object['id'], 'programing_language' );
    return $bot_source_languages;
  }

  public function get_rest_tags_full( $object, $field_name, $request ) {
    $tags_array = get_the_tags( $object['id'] );

    if ( $tags_array ){
      $tags_full = array_map(function ($tag) {
        return $tag->slug;
      }, $tags_array);
    } else {
      $tags_full = $tags_array;
    }

    return $tags_full;
  }

  public function get_rest_bot_author_info( $object, $field_name, $request ) {
    $bot_author_info = get_post_meta( $object['id'], 'bot_author_info', true );

    if ( strlen( $bot_author_info ) > 0 ){
      $bot_author_info = preg_split('/\n|\r\n?/', $bot_author_info);
    }

    return $bot_author_info;
  }

  public function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
      $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
      return $img[0];
    }
    return false;
  }

  public function extra_query_strings(){
    foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) {
      add_filter('rest_' . $post_type->name . '_query', array( $this, 'wp_rest_fix_post_tags' ), 10, 2);
      add_filter('rest_' . $post_type->name . '_query', array( $this, 'wp_rest_filter_opensource' ), 10, 2);
      add_filter('rest_' . $post_type->name . '_query', array( $this, 'wp_rest_query_bot_url' ), 10, 2);
    }
    foreach (get_taxonomies(array('show_in_rest' => true), 'objects') as $tax_type) {
      add_filter('rest_' . $tax_type->name . '_query', array( $this, 'wp_rest_fix_post_tags' ), 10, 2);
      add_filter('rest_' . $tax_type->name . '_query', array( $this, 'wp_rest_filter_opensource' ), 10, 2);
      add_filter('rest_' . $tax_type->name . '_query', array( $this, 'wp_rest_query_bot_url' ), 10, 2);
    }
  }

  public function wp_rest_fix_post_tags( $args, $request ){
    if ( empty( $request['tag'] ) ) {
      return $args;
    }

    $args['tag_slug__and'] = explode( ',', $request['tag'] );

    return $args;
  }

  public function wp_rest_filter_opensource( $args, $request ){
    if ( empty( $request['opensource'] ) ) {
      return $args;
    }

    $args['meta_key'] = 'bot_source_url';
    $args['meta_value'] = array('');
    $args['meta_compare'] = 'NOT IN';

    return $args;
  }

  public function wp_rest_query_bot_url( $args, $request ){
    if ( empty( $request['bot_url'] ) ) {
      return $args;
    }

    $bot_url = strtolower( trim( $request['bot_url'] ) );
    $info = parse_url($bot_url);

    // log_this(array(
    //   "info" => $info,
    // ));

    $host = $info['host'];
    // Temporary fix for Botwiki browser extension.
    $host = str_replace("mastodon.socialmastodon.social", "mastodon.social", $host);

    $host_names = explode(".", $host);
    $domain = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
    $path = $info['path'];

    $url_variations = array(
      $bot_url,
      'http://' . $domain . $path,
      'https://' . $domain . $path,
      'http://www.' . $domain . $path,
      'https://www.' . $domain . $path
    );

    $meta_query = array();
    $meta_query['relation'] = 'OR';

    foreach ( $url_variations as $url ) {
      $meta_query[] = array(
        'meta_key' => 'bot_url',
        'value' => $url,
        'compare' => 'LIKE'
      );
    }

    $bot_search = new WP_Query( array(
      'post_type' => 'bot',
      'meta_query' => $meta_query
    ) );

    if ( !empty( $bot_search->posts ) ){
      foreach ( $bot_search->posts as $bot ) {
        $bot_urls = explode( "\n", get_post_meta( $bot->ID, 'bot_url', true ) );

        $bot_urls = array_map( function( $url ){
          return strtolower( trim( $url ) );
        }, $bot_urls );

        if ( count( array_intersect( $bot_urls, $url_variations ) ) > 0 ){
          $post_id = $bot->ID;
          break;
        }
      }
    }

    if ( !empty( $post_id ) ){
      $args['p'] = $post_id;
    } else {
      $args['p'] = '1';
    };

    return $args;
  }
}

$wp_json_api_fixes_and_enhancements_init = new WP_JSON_API_Fixes_And_Enhancements();
