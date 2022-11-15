<?php
#use Screen\Capture;
// use \ScreenshotMachine;
require_once dirname(__FILE__) . '/../vendor/screenshotmachine/screenshotmachine-php/ScreenshotMachine.php';

class BW_Helpers {

  function __construct(){
    add_action( 'wp_ajax_make_screenshot', array( $this, 'make_screenshot_ajax' ) );
    // add_action( 'wp_ajax_nopriv_make_screenshot', array( $this, 'make_screenshot_ajax' ) );
  }


  function make_screenshot_ajax(){
    $file_name = null;

    if ( isset( $_POST['url'] ) ){
      $screenshot_data = $this->make_screenshot( $_POST['url'] );
    }

    wp_send_json( $screenshot_data );
  }

  function make_screenshot( $options ){
    if ( !file_exists(  ABSPATH . 'temp/' ) ) {
      mkdir( ABSPATH . 'temp/' , 0777, true);
    }

    $default_width = 1200;
    $default_height = 1000;

    if ( is_array( $options ) ){
      $url = $options['url'];

      if ( !empty( $options['width'] ) ){
        $width = $options['width'];
      } else {
        $width = $default_width;
      }

      if ( !empty( $options['height'] ) ){
        $height = $options['height'];
      } else {
        $height = $default_height;
      }
    } else {
      $url = $options;
      $file_name = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $url ) ) . '-' . time();
      $width = $default_width;
      $height = $default_height;
      $options = array(
        'url' => $url,
        'width' => $width,
        'height' => $height,
      );
    }

    if ( !empty( $options['file_name'] ) ){
      $file_name = $options['file_name'] . '-' . time();
    } else {
      $file_name = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $url ) ) . '-' . time();
    }

    $options['file_name'] = urlencode( $options['file_name'] );
    $options['dimension'] = $width. 'x' . $height;
    $options['device'] = 'desktop';
    $options['format'] = 'png';
    $options['cacheLimit'] = "0";
    $options['delay'] = '6000';
    $options['zoom'] = '100';
    $options['hide'] = '%23layers';

    // $machine = new ScreenshotMachine( SCREENSHOT_MACHINE_API_KEY, SCREENSHOT_MACHINE_API_PHRASE );
    $machine = new ScreenshotMachine( SCREENSHOT_MACHINE_API_KEY, '' );
    $api_url = $machine->generate_screenshot_api_url( $options );

    $file_name = preg_replace( "/[^a-z0-9\.]/", "-", strtolower( $file_name ) ) . '.png';

    $image_path = ABSPATH . 'temp/' . $file_name;
    $image_url = get_site_url() . '/temp/' . $file_name;

    if ( !file_exists(  ABSPATH . 'temp/' ) ) {
      mkdir( ABSPATH . 'temp/' , 0777, true );
    }

    log_this( 'make_screenshot', array(
      'options' => $options,
      'image_path' => $image_path,
      'api_url' => $api_url
    ) );

    $ctx = stream_context_create( array( 'http' =>
        array(
            'timeout' => 120
        )
    ) );

    file_put_contents( $image_path, file_get_contents( $api_url, false, $ctx ) );

    // log_this( array(
    //   '$page_screenshot' => $page_screenshot,
    //   '$file_name' => $file_name
    // ) );

    $screenshot_data = array(
      'image_path' => $image_path,
      'image_url' => $image_url
    );

    return $screenshot_data;
  }

/*
  function __make_screenshot( $options ){
    if ( !file_exists(  ABSPATH . 'temp/' ) ) {
      mkdir( ABSPATH . 'temp/' , 0777, true);
    }
        
    $default_width = 1200;
    $default_height = 1000;

    if ( is_array( $options ) ){
      $url = $options['url'];

      if ( !empty( $options['width'] ) ){
        $width = $options['width'];
      } else {
        $width = $default_width;
      }

      if ( !empty( $options['height'] ) ){
        $height = $options['height'];
      } else {
        $height = $default_height;
      }

      if ( !empty( $options['file_name'] ) ){
        $file_name = $options['file_name'] . '-' . time();
      } else {
        $file_name = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $url ) ) . '-' . time();
      }

    } else {
      $url = $options;
      $file_name = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $url ) ) . '-' . time();
      $width = $default_width;
      $height = $default_height;
    }

    $page_screenshot = new Capture( $url );
    
    $page_screenshot->setUserAgentString( 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 OPR/70.0.3728.106' );
    // $page_screenshot->setUserAgentString( 'Mozilla/5.0 (compatible; MSIE 9.0; InfoChannel RNSafeBrowser/v.1.1.0G)' );
    // $page_screenshot->setDelay( 5000 );


    $page_screenshot->setWidth( $width );
    $page_screenshot->setHeight( $height );
    $page_screenshot->setClipWidth( $width );
    $page_screenshot->setClipHeight( $height );

    $page_screenshot->setImageType( 'png' );

    $page_screenshot->setOptions( [
        'ignore-ssl-errors' => 'yes',
    ] );

    // $page_screenshot->includeJs(new \Screen\Injection\LocalPath( get_template_directory() . '/includes/phantomjs/twitter-cleanup.js' ) );

    $file_name = preg_replace( "/[^a-z0-9\.]/", "-", strtolower( $file_name ) ) . '.png';

    $image_path = ABSPATH . 'temp/' . $file_name;
    $image_url = get_site_url() . '/temp/' . $file_name;

    if ( !file_exists(  ABSPATH . 'temp/' ) ) {
      mkdir( ABSPATH . 'temp/' , 0777, true );
    }

    $page_screenshot->save( $image_path );

    log_this( array(
      '$page_screenshot' => $page_screenshot
    ) );

    $page_screenshot->jobs->clean();

    $screenshot_data = array(
      'image_path' => $image_path,
      'image_url' => $image_url
    );

    return $screenshot_data;
  }
*/

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
      if ( get_the_author_meta( 'botwiki-unlisted-email', $admin->data->ID ) !== 'on' ){
        $admin_emails[] = get_the_author_meta( 'user_email', $admin->data->ID );
      }
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
    $url_parsed = parse_url( trim( $url ), -1 );

    $user = array(
      "url" => $url,
    );

    if ( strpos( $url, 'twitter.com/') != false ){
      $regex  = '#https?://twitter\.com/(?:\#!/)?(\w+)';

      if (preg_match("/https*:\/\/twitter.com\/(#!\/)?([^\/]*)/", $url, $match) ){
        $user['username'] = "@" . trim( str_replace( "@", "", $match[2] ) ) . "@twitter.com";
        $user['username_twitter'] = trim( str_replace( "@", "", $match[2] ) );
      }
    } elseif ( strpos( $url, "@") != false ){
      $user['username'] = "@" . str_replace("/@", "", $url_parsed["path"]) . "@" . $url_parsed["host"];
    }

    return $user;
  }


  function get_domain_from_url( $url ){
    $domain = $url;

    try {
      $info = parse_url( trim( $url ) );
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
           strpos( $url, 'beeping.town/') !== false ||
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
