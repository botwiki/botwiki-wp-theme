<?php

/* Fixes and enhancements for the WP JSON API. */

class WP_JSON_API_Fixes_And_Enhancements {
  public function __construct() {
    add_filter( 'rest_api_init', array( $this, 'register_rest_images' ) );
    add_filter( 'rest_api_init', array( $this, 'fix_post_tags' ) );
  }

  public function register_rest_images() {
    /* Add featured image to the JSON API, courtesy of https://github.com/dalenguyen/wordpress-snippets/blob/master/get-featured-image-from-rest-api.php. */

    global $wp_post_types;

    register_rest_field( array_keys( $wp_post_types ),
        'featured_image_url',
        array(
            'get_callback'    => array( $this, 'get_rest_featured_image' ),
            'update_callback' => null,
            'schema'          => null,
        )
    );
  }

  public function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
      $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
      return $img[0];
    }
    return false;
  }

  public function fix_post_tags(){
    foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) {
      add_filter('rest_' . $post_type->name . '_query', array( $this, 'wp_rest_fix_post_tags' ), 10, 2);
    }
    foreach (get_taxonomies(array('show_in_rest' => true), 'objects') as $tax_type) {
      add_filter('rest_' . $tax_type->name . '_query', array( $this, 'wp_rest_fix_post_tags' ), 10, 2);
    }
  }

  public function wp_rest_fix_post_tags( $args, $request ){
    if ( empty( $request['tag'] ) ) {
      return $args;
    }

    $args['tag_slug__and'] = explode( ',', $request['tag'] );

    return $args;
  }
}

$wp_json_api_fixes_and_enhancements_init = new WP_JSON_API_Fixes_And_Enhancements();
