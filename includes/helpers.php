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
    if ( strpos( $url, 'twitter.com/') != -1 ){
      $regex  = '#https?://twitter\.com/(?:\#!/)?(\w+)';

      if (preg_match("/https*:\/\/twitter.com\/(#!\/)?([^\/]*)/", $url, $match) ){
        return trim( $match[2] );
      }
    }
    return false;
  }

  function get_username_from_url( $url ){
    $networks = [
      'twitter.com',
      'botsin.space',
      'mastodon.social',
    ];

    foreach ( $networks as $network ) {
      if ( strpos( $url, $network . '/') != -1 ){
        $regex  = '#https?://' . str_replace('.', '\.', $network) . '/(?:\#!/)?(\w+)';
        if ( preg_match( "/https*:\/\/" . $network . "\/(#!\/)?([^\/]*)/", $url, $match ) ){
          return trim( str_replace( '@', '', $match[2] ) );
        }
      }
    }

    return false;
  }

  function get_domain_from_url( $url ){
    $domain = $url;

    try {
      $info = parse_url($url);
      $host = $info['host'];
      $host_names = explode(".", $host);
      $domain = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
    } catch (Exception $e) { /* NOOP */ }

    return $domain;
  }

  function get_tag_slug( $tag ){
    return $tag->slug;
  }


  function starts_with( $haystack, $needle ){
       $length = strlen($needle);
       return (substr($haystack, 0, $length) === $needle);
  }

  function ends_with( $haystack, $needle )  {
      $length = strlen($needle);
      if ($length == 0) {
          return true;
      }

      return (substr($haystack, -$length) === $needle);
  }

  function get_screenshotable_url( $urls ){
    $screenshotable_url = false;
    foreach ( $urls as $url) {
      if ( strpos( $url, 'twitter.com/') !== false ||
           strpos( $url, 'botsin.space/') !== false ||
           strpos( $url, 'mastodon.social/') !== false ||
           strpos( $url, 'tumblr.com/') ){

        $screenshotable_url = trim( $url );
        break;
      }
    }

    return $screenshotable_url;
  }
}

$helpers = new BW_Helpers();
