<?php

class Profile_Page_Link {
  public function __construct() {
    add_action( 'admin_bar_menu', array( $this, 'show_profile_page_link' ), 100 );
  }

  public function show_profile_page_link( $wp_admin_bar ) { 
    $username = get_the_author_meta( 'user_nicename', get_current_user_id() );
    $botwiki_profile_page_url = get_site_url() . '/author/' . $username;

    $args = array(
      'id' => 'show-profile-page-link',
      'title' => 'My Profile Page', 
      'href' => $botwiki_profile_page_url, 
      'meta' => array(
        'class' => 'show-profile-page-link', 
        'title' => 'Show my profile page'
      )
    );
    $wp_admin_bar->add_node($args);
  }
}

$profile_page_link_init = new Profile_Page_Link();
