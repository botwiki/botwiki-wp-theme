<?php

/* Add featured image to the JSON API, courtesy of https://github.com/dalenguyen/wordpress-snippets/blob/master/get-featured-image-from-rest-api.php. */

class WP_JSON_API_Featured_Image {
  public function __construct() {
    add_filter( 'rest_api_init', array( $this, 'register_rest_images' ) );
  }

  public function register_rest_images() {
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

}

$wp_json_api_featured_image_init = new WP_JSON_API_Featured_Image();
