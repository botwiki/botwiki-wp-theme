<?php

class BW_Helpers {
  function join_with_and( $array ) {
    $oxf_comma = ( count( $array ) > 2 ? ',' : '' );
    return join( $oxf_comma . ' and ', array_filter( array_merge( array( join( ', ', array_slice( $array, 0, -1 ) ) ), array_slice( $array, -1 ) ), 'strlen' ) );
  }

	function get_admin_emails() {
    $admins = get_users( array(
      'role'         => 'administrator',
      'orderby'      => 'registered',
      'order'        => 'ASC',
      'who'          => '',
    ) );
    $admin_emails = array();
    foreach ( $admins as $admin ) {
      $admin_emails[] = get_the_author_meta('user_email', $admin->data->ID);        
    }
    return implode( ',', $admin_emails );
	}

  function get_twitter_username_from_url( $url ){
    // error_log( $url );

    if ( strpos( $url, 'twitter.com/') === -1 ){
      return false;
    }
    else{
      $regex  = '#https?://twitter\.com/(?:\#!/)?(\w+)';

      error_log( print_r( preg_match($regex, $url, $match), true ) );

      if (preg_match($regex, $url, $match)) {
        error_log( print_r( $match, true ) );

        return $match;
      }
    }
  }
}


$helpers = new BW_Helpers();
