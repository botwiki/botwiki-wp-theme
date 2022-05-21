<?php

class New_On_Botwiki {
  public function __construct() {
    add_filter( 'transition_post_status', array( $this, 'post_update' ), 10, 3 );
  }

  public static function get_twitter_handles( $author_info ){
    $author_info = preg_split( '/\n|\r\n?/', $author_info );
    $twitter_handles = array();

    if ( !empty( $author_info ) ){
      foreach ( $author_info as $author ) {
        $author_split = explode( ',', $author );

        if ( is_array( $author_split ) && count( $author_split ) === 2 ){
          $author_url = $author_split[1];

          if ( strpos( $author_url, 'twitter.com/' ) !== false ){
            global $helpers;

            $twitter_handle = $helpers->get_username_from_url( $author_url );

            if ( !empty( $twitter_handle ) ){
              array_push( $twitter_handles, $twitter_handle );
            }
          }
        }
      }

      if ( count( $twitter_handles ) !== 0 ){
        $twitter_handles = implode( ", ", array_map( function( $handle ){
          return '@' . $handle;
        }, $twitter_handles ) );
      }
    }

    return $twitter_handles; 
  }

  public function post_update( $new_status, $old_status, $post ) {
    if ( empty( NEWONBOTWIKI_TWITTER_ACCESS_TOKEN ) ||
         empty( NEWONBOTWIKI_TWITTER_ACCESS_TOKEN_SECRET ) ||
         empty( NEWONBOTWIKI_TWITTER_API_KEY ) ||
         empty( NEWONBOTWIKI_TWITTER_API_SECRET ) ){
      return false;
    }

    if ( !empty( $post ) && $new_status === 'publish' && $old_status !== 'publish' ){
      $post_id = $post->ID;
      $published_tweet_url = get_post_meta( $post_id, 'published_tweet_url', true );
      
      if ( empty( $published_tweet_url ) && !wp_is_post_revision( $post_id ) ) {
        $permalink = get_permalink( $post_id );
        $status_text = null;

        if ( $post->post_type === 'bot' ){
          // $bot_is_featured = get_post_meta( $post_id, 'bot_is_featured' );
          // $bot_is_nsfw = get_post_meta( $post_id, 'bot_is_nsfw' );

          $status_text = 'New bot was added to Botwiki!';
          $bot_url = 'botwiki.org/bot/' . $post->post_name; 
          $bot_urls = preg_split( '/\n|\r\n?/', get_post_meta( $post_id, 'bot_tweets', true ) );

          $example_tweet_url = false;
          $via_text = '';

          if ( $bot_urls ){
            foreach ( $bot_urls as $url ) {
              if ( strpos( $url, 'twitter.com' ) !== -1 ){
                $example_tweet_url = $url;
                break;
              }
            }
          }

          $coauthors = get_coauthors( $post_id );

          if ( !empty( $coauthors ) && $coauthors[0]->user_login !== 'botwiki' ){
            $bot_author_info = implode( "\n", array_map( function( $author ){
              $twitter_handle = esc_attr( get_the_author_meta( 'twitter-handle', $author->ID ) );
              $author_twitter_url = !empty( $twitter_handle ) ? 'https://twitter.com/' . str_replace('@', '', $twitter_handle ) : '';
              return $author->display_name . ',' . $author_twitter_url;
            }, $coauthors ) );

          } else {
            $bot_author_info = get_post_meta( $post_id, 'bot_author_info', true );
          }

          if ( strlen( $bot_author_info ) > 0 ){
            $twitter_handles = self::get_twitter_handles( $bot_author_info );

            if ( !empty( $twitter_handles ) ){
              $via_text .= ' via ' .  $twitter_handles;           
            }
          }

          if ( $example_tweet_url !== false && !empty( $example_tweet_url ) ){
            $status_text .= $via_text . "\n\n🤖 " . $bot_url . "\n\n" . $example_tweet_url ;
          } else {
            $status_text .= ' ' . $bot_url . $via_text;
          }

        } elseif ( $post->post_type === 'resource' ){

          $post_terms = wp_get_post_terms( $post->ID, 'resource_type' );
          $term = $post_terms[0];
          $resource_type = strtolower( $term->name );
          $resource_type_slug = strtolower( $term->slug );

          $resource_url = get_post_meta( $post_id, 'resource_url', true );

          if ( empty( $resource_url ) ){
            $resource_url = 'botwiki.org/resource/' . $resource_type_slug . '/' . $post->post_name; 
          }


          $status_text = 'New ' . $resource_type . ' was added to Botwiki! ' . $resource_url;

          $coauthors = get_coauthors( $post_id );

          if ( !empty( $coauthors ) && $coauthors[0]->user_login !== 'botwiki' ){
            $resource_author_info = implode( "\n", array_map( function( $author ){
              $twitter_handle = esc_attr( get_the_author_meta( 'twitter-handle', $author->ID ) );
              $author_twitter_url = !empty( $twitter_handle ) ? 'https://twitter.com/' . str_replace('@', '', $twitter_handle ) : '';
              return $author->display_name . ',' . $author_twitter_url;
            }, $coauthors ) );

          } else {
            $resource_author_info = get_post_meta( $post_id, 'resource_author_info', true );
          }

          if ( strlen( $resource_author_info ) > 0 ){
            $twitter_handles = self::get_twitter_handles( $resource_author_info );

            if ( !empty( $twitter_handles ) ){
              $status_text .= ' Via ' .  $twitter_handles;           
            }
          }
        } elseif ( $post->post_type === 'post' && get_post_status( $post ) === 'publish' ){
          $status_text = 'New blog post was posted on Botwiki! ' . get_permalink( $post );
        }

        if ( !empty( $status_text ) ){
          if ( ENVIRONMENT === 'production' ){
            /* Post update on Twitter */

            $api_keys = array(
                'oauth_access_token' => NEWONBOTWIKI_TWITTER_ACCESS_TOKEN,
                'oauth_access_token_secret' => NEWONBOTWIKI_TWITTER_ACCESS_TOKEN_SECRET,
                'consumer_key' => NEWONBOTWIKI_TWITTER_API_KEY,
                'consumer_secret' => NEWONBOTWIKI_TWITTER_API_SECRET
            );

            $twitter_api_url = 'https://api.twitter.com/1.1/statuses/update.json';
            $request_method = 'POST';
            $post_fields = array(
              'status' => $status_text
            );

            $twitter = new TwitterAPIExchange( $api_keys );
            $response = json_decode(
                          $twitter->buildOauth( $twitter_api_url, $request_method )
                                  ->setPostfields( $post_fields )
                                  ->performRequest()
                        );
            try {
              $tweet_url = 'https://twitter.com/' . $response->user->screen_name . '/status/' . $response->id_str;
              update_post_meta( $post_id, 'published_tweet_url', $tweet_url  );            
            } catch (Exception $e) {
              /* noop */
            }

            /* Post update on Mastodon */

            $headers = [
              'Authorization: Bearer ' . NEWONBOTWIKI_MASTODON_ACCESS_TOKEN
            ];
            
            $status_data = array(
              "status" => str_replace('@', 'https://twitter.com/', $status_text),
              "language" => "eng",
              "visibility" => "public"
            );
            
            $ch_status = curl_init();
            curl_setopt($ch_status, CURLOPT_URL, "https://botsin.space//api/v1/statuses");
            curl_setopt($ch_status, CURLOPT_POST, 1);
            curl_setopt($ch_status, CURLOPT_POSTFIELDS, $status_data);
            curl_setopt($ch_status, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_status, CURLOPT_HTTPHEADER, $headers);
            
            $output_status = json_decode(curl_exec($ch_status));
            
            curl_close ($ch_status);
            
          } else {
            log_this( '@newonbotwiki', array(
              'status_text' => $status_text
            ) );
          }
        }
      }
    }
  }
}

$newonbotwiki_init = new New_On_Botwiki();
