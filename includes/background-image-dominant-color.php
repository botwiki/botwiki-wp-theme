<?php
  use ColorThief\ColorThief;

  add_action( 'save_post', 'get_thumbnail_dominant_color' );

  function get_thumbnail_dominant_color( $post_id ) {
    try {
      $thumbnail_path = str_replace(get_site_url() . '/wp-content', WP_CONTENT_DIR, get_the_post_thumbnail_url($post_id));
      $dominant_color = ColorThief::getColor($thumbnail_path);
      update_post_meta($post_id, 'dominant_color', json_encode($dominant_color));
    } catch (Exception $e) {
      // noop      
    }
  }
