<?php

class Archive_This_Page {
  public function __construct() {
    add_action( 'save_post', array( $this, 'archive_page' ), 10, 3 );
  }

  public function archive_page( $post_id, $post, $update ) {
    if ( !empty($post) && $post->post_status === 'publish' ){
      $page_url = get_permalink( $post_id );
      $internet_archive_url = 'https://web.archive.org/save/' . $page_url;

      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, $internet_archive_url); 
      curl_setopt($ch, CURLOPT_HEADER, TRUE); 
      curl_setopt($ch, CURLOPT_NOBODY, TRUE); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
      $head = curl_exec($ch); 
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
      curl_close($ch); 
    }
  }
}

$archive_this_page_init = new Archive_This_Page();
