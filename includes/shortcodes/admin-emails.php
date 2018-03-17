<?php

class Admin_Emails_Shortcode {
  public function __construct() {
    add_shortcode( 'admin-emails', array( $this, 'get_admin_emails' ) );
  }

  public function get_admin_emails( $atts ) {
    global $helpers;

    if ( empty( $atts['text'] ) ){
      $text = 'send us an email';
    }
    else{
      $text = $atts['text'];
    }

    return '<a href="mailto:' . $helpers->get_admin_emails() . '">' . $text . '</a>';
  }

  public function btn_list_shortcode( $atts ) {
    $is_external = false;

    $post_ids = explode( ',', $atts['ids'] );
    $link_list_html = '<ul class="btn-list mb-2">';

    foreach ($post_ids as $post_id) {
      $link_url = get_post_meta( $post_id, 'resource_url', true );

      if ( empty( $link_url ) ){
        $link_url = post_permalink( $post_id );
      }
      else{
        $is_external = true;
      }

      $link_title = get_the_title( $post_id );
      $link_list_html .= '<li><a data-resource-id="' . $post_id . '" href="' . $link_url . '" class="btn">' . $link_title . '</a>' 
                      . ($is_external ? ': ' . get_the_excerpt($post_id) . ' (' .  parse_url($link_url)['host'] . ')' : '')
                      . '</li>' ;
    }

    $link_list_html .= '</ul>';

    return $link_list_html;
  }
}

$admin_emails_shortcode_init = new Admin_Emails_Shortcode();
