<?php
  global $wp;
  get_header();
  $post_id = get_the_id();
  $post_type = get_post_type();
  $post_meta = get_post_meta( $post_id ); 
  $site_url = get_site_url();
  $current_url = home_url( $wp->request );
  $prompt_donation = false;
?>
  <main role="main" class="container-fluid m-0 p-0 pt-3">

  <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
    <div class="container mt-5">
      <!-- article -->
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- post title -->
        <h1 class="text-center post-title mt-5"><span<?php
            $dominant_color = get_post_meta( $post_id, 'dominant_color', true );
            if ( $dominant_color ){
              $rgb = json_decode( $dominant_color );
              $contrast = sqrt(
                $rgb[0] * $rgb[0] * .241 +
                $rgb[1] * $rgb[1] * .691 +
                $rgb[2] * $rgb[2] * .068
              );

              $lum = ($rgb[0]+$rgb[0]+$rgb[2]+$rgb[1]+$rgb[1]+$rgb[1])/6;

              ?>
              style="box-shadow: inset 0 -20px 0 0 rgba(<?php echo $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . log( log10( $contrast ) ) ?>)"
            <?php }

        ?>><?php the_title(); ?></span></h1>
        <!-- /post title -->
        <div class="post-content">
          <p class="text-center lead mb-5 mt-n3 font-weight-bold"><?php echo get_the_excerpt(); ?></p>

          <!-- post thumbnail -->
          <?php if ( has_post_thumbnail() ) {
            $dominant_color_css = str_replace( '[', 'background-color:rgb( ', $dominant_color );
            $dominant_color_css = str_replace( ']', ' )', $dominant_color_css );
          ?>
            <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
              <?php
                $post_thumbnail_id = get_post_thumbnail_id();
                the_post_thumbnail( 'post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load expand-image webfeedsFeaturedVisual', 'title' => get_post( $post_thumbnail_id )->post_title ] );
              ?>
              <div class="image-border-shadow"></div>
            </div>
          <?php } ?>
          <!-- /post thumbnail -->
          <?php
          if ( in_array( $post_type, ['post', 'resource'] ) ) {
            $post_date = get_the_time( 'F j, Y' );
            $modified_date = get_the_modified_time( 'F j, Y' );
            $post_date_full = $post_date . ' ' . get_the_time( 'g:i a' );
            $modified_date_full = $post_date . ' ' . get_the_modified_time( 'g:i a' );
            if ( class_exists( 'Moment\Moment' ) ){
              $m = new \Moment\Moment( $post_date );
              $post_date_ago = $m->fromNow()->getRelative();
              $m = new \Moment\Moment( $modified_date );
              $modified_date_ago = $m->fromNow()->getRelative();
            } else {
              $post_date_ago = $post_date;              
              $modified_date_ago = $post_date;              
            }
            ?>
              <p class="mt-n4 mb-2 text-muted">
                📅 <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span>
              <?php
                if ( $post_type === 'post' ){
                  echo 'in ';
                  the_category( ' ' );
                }
              ?>
              <?php if ( $post_date_ago !== $modified_date_ago ){ ?>
              | Updated <span title="<?php echo $modified_date; ?>"><?php echo $modified_date_ago; ?></span>
              <?php } ?>
              </p>
            <?php }

            if ( $post_type == 'resource' && !empty( $post_meta['resource_url'][0] ) ){

              $info = parse_url( $post_meta['resource_url'][0] );
              $host = $info['host'];
              $host_names = explode( ".", $host );
              $domain = $host_names[count( $host_names )-2] . "." . $host_names[count( $host_names )-1];

            ?>
            <ul class="btn-list">
              <li>
                <a class="btn" href="<?php echo $post_meta['resource_url'][0]; ?>">View on <?php echo $domain; ?></a>
              </li>
            <?php        
          ?>
            </ul>
        <?php } ?>
        <div class="<?php
        if ( $post_type === 'bot' ) {
          echo "mt-3";
        }
        if ( $post_type === 'post' ) {
          echo "mt-5";
        }
        ?>">
          <?php
            if ( $post_type === 'bot' ) {?>
              <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-10 mb-3 pr-3">
                  <ul class="btn-list mt-1 mb-5">
                  <?php

                  $bot_urls = preg_split( '/\n|\r\n?/', $post_meta['bot_url'][0] );

                  $bot_source_url = '';

                  if ( array_key_exists( 'bot_source_url', $post_meta ) && !empty( $post_meta['bot_source_url'][0] ) ){
                    $bot_source_url = $post_meta['bot_source_url'][0];
                  }

                  if ( is_array( $bot_urls ) && $bot_urls[0] ){
                    foreach ( $bot_urls as $url ) {
                      if ( $url !== $bot_source_url ){
                        $info = parse_url( $url );
                        $host = $info['host'];
                        $host_names = explode( ".", $host );
                        $domain = $host_names[count( $host_names )-2] . "." . $host_names[count( $host_names )-1];
                        if ( $domain === 'twitter.com' ){
                          $label = 'View on Twitter';
                        } elseif ( $domain === 'tumblr.com' ) {
                          $label = 'View on Tumblr';
                        } elseif ( $domain === 'youtube.com' ) {
                          $label = 'View on YouTube';
                        } elseif ( $domain === 'twitch.tv' ) {
                          $label = 'View on Twitch';
                        } elseif ( $domain === 'instagram.com' ) {
                          $label = 'View on Instagram';
                        } elseif ( $domain === 'facebook.com' ) {
                          $label = 'View on Facebook';
                        } elseif ( $domain === 'm.me' ) {
                          $label = 'Chat via Facebook Messenger';
                        } elseif ( $domain === 't.me' ) {
                          $label = 'Chat via Telegram';
                        } elseif ( $domain === 'reddit.com' ) {
                          $label = 'View on Reddit';
                        } elseif ( $domain === 'discordapp.com' ) {
                          $label = 'Add to Discord';
                        } else {
                          $label = 'View on ' . $domain;
                        }
                        ?>
                        <li>
                          <a rel="me" class="btn" href="<?php echo $url; ?>"><?php echo $label; ?></a>
                        </li>
                      <?php }                 
                    }
                  }

                  $bot_languages = wp_get_post_terms( $post_id, 'programing_language' );

                  $bot_source_urls = array();
                  if ( array_key_exists( 'bot_source_url', $post_meta ) && !empty( $post_meta['bot_source_url'][0] ) ){
                    $bot_source_urls = preg_split( '/\r\n|[\r\n]/', $post_meta['bot_source_url'][0] );
                  }

                  if ( count( $bot_source_urls ) === 1 ){ ?>
                    <li>
                      <a class="btn view-source" href="<?php echo $bot_source_urls[0]; ?>">View source</a>
                    </li>
                  <?php } elseif ( count( $bot_source_urls ) > 1 ){ ?>
                    <li>
                      <a class="btn view-source" href="#source-code">View source</a>
                    </li>
                  <?php }

                  if( isset( $post_meta['output_archive_url'] ) && strlen( trim( $post_meta['output_archive_url'][0] ) ) !== 0 ){ ?>
                    <li>
                      <a class="btn" href="<?php echo $post_meta['output_archive_url'][0] ?>"
                         target="_blank"
                    <?php
                      if( isset( $post_meta['output_archive_date'] ) && strlen( trim( $post_meta['output_archive_date'][0] ) ) !== 0 ){ ?>
                        title="Archive created on <?php echo $post_meta['output_archive_date'][0] ?>"
                      <?php } ?>
                      >Download archive</a>
                    </li>
                    <?php } ?>
                  </ul>
                  <?php the_content(); ?>                 
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2 mb-4 sidebar">
                  <h3 class="sidebar-header">Networks</h3>
                  <ul class="list-unstyled ps-0 ps-md-3">

                  <?php

                    $networks = get_the_terms( $post_id, 'network' );
                    $network_names = [];

                    if ( $networks ){
                      $network_names = array_map( 'get_network_name', $networks );
                    }

                    $network_links = array();
                    if ( $networks ){
                      foreach ( $networks as $network ) {
                        $network_links[] = '<li><a class="text-decoration-none badge badge-secondary p-2 mb-2" href="' . $site_url . '/bot/?networks=' . $network->slug . '">' . $network->name . '</a></li>';
                      }
                    }

                    echo join( ' ', $network_links );
                    ?>
                  </ul>
                  <?php

                    $languages = get_the_terms( $post_id, 'programing_language' );
                    $language_links = array();

                    if ( $languages ){
                      foreach ( $languages as $language ) {
                        $language_links[] = '<li><a class="badge badge-secondary p-2 mb-2" href="' . $site_url . '/languages/' . $language->slug . '">' . $language->name . '</a></li>';
                      }
                      ?>
                      <h3 class="mt-2 mb-2 sidebar-header">Made with</h3>
                      <ul class="list-unstyled ps-0 ps-md-3">
                      <?php
                      echo join( ' ', $language_links );
                      ?>
                      </ul>
                    <?php } ?>
                </div>
              </div>
            <?php
            } else {
              the_content();
            }
          ?>
        </div>
        <?php
        if ( $post_type === 'bot' ) { 

          $bot_tweets_hide = ( array_key_exists('bot_tweets_hide', $post_meta ) && $post_meta['bot_tweets_hide'][0] === "on" );

          if ( !$bot_tweets_hide ){
            $bot_tweets_html_meta = get_post_meta( $post_id, 'bot_tweets_html', true );
            $tumblr_script = '<script async src="https://assets.tumblr.com/post.js"></script>';

            $bot_tweets_html = preg_split( '/(<\/blockquote>|<\/iframe>|' . str_replace( "/", "\/", $tumblr_script ) . ')/i', $bot_tweets_html_meta, -1, PREG_SPLIT_NO_EMPTY );
            ?>
            <div class="row list social-embeds p-2">
            <?php foreach ( $bot_tweets_html as $tweet_html ) {
              $tweet_html = str_replace( '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>', '', $tweet_html );
              ?>
              <div class="list-item col-sm-12 col-md-6">
                <?php
                if ( strpos( $tweet_html, 'twitter-tweet' ) !== false ){
                  echo $tweet_html . '</blockquote>';
                }
                elseif ( strpos( $tweet_html, 'mastodon-embed' ) !== false ){
                  $iframe = str_replace( '<blockquote>', '', $tweet_html ) . '</iframe>';

                  if (class_exists('\FTF_Fediverse_Embeds\Embed_Posts')){
                    $ftf_embed_posts = new \FTF_Fediverse_Embeds\Embed_Posts();
                    $iframe = $ftf_embed_posts->process_embeds($iframe, '');
                  }
                  echo $iframe;
                }
                elseif ( strpos( $tweet_html, 'player.twitch.tv' ) !== false ){ ?>
                  <div class="video-background">
                    <div class="video-wrapper"><?php echo $tweet_html . '</iframe>'; ?></div>
                  </div>
                <?php }
                elseif ( strpos( $tweet_html, 'tumblr-post' ) !== false ){
                  echo $tweet_html . $tumblr_script;
                }
                elseif ( strpos( $tweet_html, 'fb-root' ) !== false ){
                  $tweet_html = str_replace( '<blockquote>', '<div class="mt-5">', $tweet_html );
                  $tweet_html .= '</div>';
                  echo $tweet_html;
                }
                else {
                  echo $tweet_html;
                }
                ?>
              </div>
            <?php } ?>
            </div>
          <?php } ?>
          <p class="post-tags mt-0 mb-2">
          <?php 
            $tags = get_the_tags();
            $tags_array = array();

            if ( $tags ){
              foreach ( $tags as $tag ) {
                $tags_array[] = '<a class="text-decoration-none" href="' . $site_url . '/bot/?tags=' . $tag->slug . '">' . $tag->slug . '</a> ';
              }
            }

            echo join( ' ', $tags_array );
            // the_tags( '', ' ', '<br>' );
          ?>
          </p>           
          </div>
          <?php if ( count( $bot_source_urls ) > 1 ){
            global $helpers;
            ?>
            <h3 id="source-code">Source code</h3>
            <ul class="list-style-circle">
              <?php
              foreach ( $bot_source_urls as $url ) { ?>
                <li>
                  <a href="<?php echo $url; ?>">
                    <?php echo $helpers->get_domain_from_url( $url ); ?>
                  </a>
                </li>
              <?php } ?>
            </ul>
          <?php }
          } 

          if ( $post_type === 'single' ){ ?>
            <p class="post-tags mt-5 mb-5"><?php the_tags( '', ' ', '<br>' ); // Separated by commas with a line break at the end ?></p>
          <?php }
          global $coauthors_plus;

          $coauthors = get_coauthors();
          $coauthors_count = count( $coauthors );

          if ( $coauthors_count > 1 || ( $coauthors_count === 1 && $coauthors[0]->user_login !== 'botwiki' ) ){ ?>
            <?php if ( $post_type === 'bot' ){ ?>
              <h3 id="authors">Created by</h3>
              <?php } ?>
              <div class="row list">
              <?php
              foreach ( $coauthors as $coauthor ) { ?>
                <div class="col-sm-12 col-md-12 col-lg-<?php echo count( $coauthors ) === 1 ? '12' : '6'; ?> list-item">
                  <?php
                  $author_id = $coauthor->data->ID;
                  if ( $author_id != 2 ){
                    $author_data = get_userdata( intval( $author_id ) );

                    // echo "<pre><code>";
                    // var_dump( get_userdata( $author_id ) );
                    // echo "</code></pre>";

                    $nickname = get_the_author_meta( 'nickname', $author_id );
                    $username = get_the_author_meta( 'user_nicename', $author_id );

                    if ( user_can( $author_id, 'administrator' ) ){  
                      $prompt_donation = true;
                      $botwiki_team_role = get_the_author_meta( 'botwiki-team-role', $author_id );
                      if ( empty( $botwiki_team_role ) ){
                        $botwiki_team_role = "Botwiki team member.";      
                      }
                    }
                    else{
                      $botwiki_team_role = "Botwiki contributor.";    
                    }

                    $first_name = get_the_author_meta( 'nickname', $author_id );
                    $last_name = get_the_author_meta( 'last_name', $author_id );
                    $full_name = '';

                    if( empty( $first_name ) ){
                        $full_name = $last_name;
                    } elseif( empty( $last_name ) ){
                        $full_name = $first_name;
                    } else {
                        $full_name = "{$first_name} {$last_name}";
                    }

                    $botwiki_profile_page_url = $site_url . '/author/' . $username;
                    $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

                    $website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
                    $fediverse_handle = esc_attr( get_the_author_meta( 'fediverse-handle', $author_id ) );
                    $twitter_handle = '@' . str_replace( '@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );

                    if ( empty( $profile_img_url ) ){
                      $profile_img_url = get_avatar_url( $author_id, array( 'size' => 360, 'scheme' => 'https' ) );
                    }

                  // $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
                  // $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

                 //  $background_img_dominant_color_css = str_replace( '[', 'background-color:rgb( ', $background_img_dominant_color );
                 //  $background_img_dominant_color_css = str_replace( ']', ' )', $background_img_dominant_color_css );
                  include( locate_template( 'author-card.php', false, false ) );  
                }
              } ?>
            </div>
          <?php }
          ?></article><?php
          if ( $post_type === 'resource' && $prompt_donation ) { ?>
            <div class="container card mt-lg-4 mb-4 pl-0 pr-0">
              <div class="card-body mt-4">
                <div class="row">
                  <div class="col-sm-12">
                    <h3 class="mt-0 mb-3 d-inline">Enjoyed the tutorial?</h3>
                    <p class="mt-3"><strong>Consider supporting Botwiki!</strong></p>
                    <ul class="btn-list mt-2 mb-2">
                      <li>
                        <a class="btn mb-2" title="Support via Patreon" rel="me" href="https://www.patreon.com/botwiki">Become a patron</a>
                      </li>
                      <li>
                        <a class="btn mb-2" title="Donate via PayPal" rel="me" href="https://paypal.me/stefanbohacek">Donate</a>
                      </li>
                      <li> 
                        <a class="btn mb-2" title="View list of supporters" href="https://botwiki.org/about/supporters/">See our supporters</a>
                      </li> 
                      <li>  
                        <a class="btn mb-2" title="Twitter" rel="me" href="https://mastodon.social/@botwiki">Follow @botwiki</a> 
                      </li> 
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>

        <?php
        if ( in_array( $post_type, [ 'post', 'bot', 'resource' ] ) ) { ?>
        <div class="container">
          <div class="card mt-5 mb-5 pl-0 pr-0">
            <div class="card-body mt-3">
              <p>
                ✉️ <a class="stretched-link" href="mailto:stefan@botwiki.org?subject=Botwiki%20content%20issues&body=Hi%2C%20I%20would%20like%20to%20report%20an%20issue%20on%20page%20<?php echo $current_url;?>.%0D%0A%0D%0AThe%20issue%20is%20..."><strong>Contact us</strong> to report broken links and other content issues.</a>
              </p>
            </div>
          </div>
        </div>
        <?php
        }
        if ( $post_type === 'bot' ) { ?>
          <div class="container">
            <h3 id="related-bots">More bots</h3>
            <div id="related-bots-wrapper" class="row list">
            <?php

            $related_bots = get_posts( array( 
              'posts_per_page' => 3,
              'orderby' => 'rand',
              'order' => 'ASC',
              'exclude' => array( $post_id ),
              'tag__in' => array_map( function( $tag ){ return $tag->term_id; }, is_array( $tags ) ? $tags : [] ),
              'post_type' => 'bot',
              'post_status' => 'publish',
              'suppress_filters' => true
            ) );

            if ( count( $related_bots ) < 3 ){
              $used_post_ids = array_map( function( $bot ){
                return $bot->ID;
              }, $related_bots);

              $related_bots_fill = get_posts( array( 
                'posts_per_page' => 3 - count( $related_bots ),
                'orderby' => 'rand',
                'order' => 'ASC',
                'exclude' => $used_post_ids,
                'post_type' => 'bot',
                'post_status' => 'publish',
                'suppress_filters' => true
              ) );
              $related_bots = array_merge( $related_bots, $related_bots_fill );
            }

            foreach ( $related_bots as $related_bot ) {
              $post_thumbnail_url = get_the_post_thumbnail_url( $related_bot->ID, 'large' );
              $post_thumbnail_url_full = get_the_post_thumbnail_url( $related_bot->ID );
              $dominant_color  = get_post_meta( $related_bot->ID, 'dominant_color', true );
              $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
              $dominant_color_css = str_replace(']', ')', $dominant_color_css);

              ?>
              <div class="col-sm-12 col-md-4 col-lg-4 list-item">
                <div class="card w-100">
                  <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                    <a href="<?php echo get_permalink( $related_bot->ID ); ?>">
                      <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url;  ?>" data-src="<?php echo $post_thumbnail_url_full;  ?>" alt="<?php echo $related_bot->post_title; ?>">
                    </a>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">
                      <a class="stretched-link" href="<?php echo get_permalink( $related_bot->ID ); ?>"><?php echo $related_bot->post_title; ?></a>  
                    </h5>
                    <p class="card-text"><?php echo $related_bot->post_excerpt; ?></p>
                  </div>
                </div>
              </div>
            <?php } ?>
            </div>
            <p><a class="btn" href="/bots/">Browse bots</a><a class="btn" href="/random-bot/" title="Explore the wonderful world of online bots, one random bot at a time">See a random bot</a></p>
          </div>
        <!-- /article -->
        <?php }

        if ( $post_type === 'post' ||  $post_type === 'bot' || $post_type === 'resource' ) { ?>
          <div class="container">

            <h3 id="blog">Latest from the blog</h3>
            <div id="blog-latest-wrapper" class="row list">
            <?php

            $latest_blog_posts = get_posts( array( 
              'posts_per_page' => 3,
              'exclude' => array( $post_id ),            
              'post_type' => 'post',
              'post_status' => 'publish'
             ) );

            foreach ( $latest_blog_posts as $blog_post ) {
              $post_thumbnail_url = get_the_post_thumbnail_url( $blog_post->ID, 'large' );
              $post_thumbnail_url_full = get_the_post_thumbnail_url( $blog_post->ID );
              $dominant_color  = get_post_meta( $blog_post->ID, 'dominant_color', true );
              $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
              $dominant_color_css = str_replace(']', ')', $dominant_color_css);

              $author_id = $blog_post->post_author;
              $author_nickname = get_the_author_meta( 'nickname', $author_id );
              $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

              $post_date = get_the_time( 'F j, Y', $blog_post->ID );
              $post_date_full = $post_date . ' ' . get_the_time( 'g:i a', $blog_post->ID );

              $post_date_ago = $post_date;

              if ( class_exists( 'Moment\Moment' ) ){
                $m = new \Moment\Moment( $post_date );
                $post_date_ago = $m->fromNow()->getRelative();
              }

              ?>
              <div class="col-sm-12 col-md-4 col-lg-4 list-item">
                <div class="card w-100">
                  <a href="<?php echo get_permalink( $blog_post->ID ); ?>">
                    <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                      <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>" data-src="<?php echo $post_thumbnail_url_full; ?>" alt="<?php echo $blog_post->post_title; ?>">
                    </div>
                  </a>
                  <div class="card-body">
                    <h5 class="card-title">
                      <a class="stretched-link" href="<?php echo get_permalink( $blog_post->ID ); ?>"><?php echo $blog_post->post_title; ?></a>  
                    </h5>
                    <p class="card-text"><?php echo $blog_post->post_excerpt; ?></p>
                  </div>
                  <div class="card-footer">
                    <p class="text-muted mt-n2"><a href="<?php echo get_author_posts_url( $author_id, get_the_author_meta( 'user_nicename', $author_id ) ); ?>"><img width="22" height="22" loading="lazy" class="lazy-load u-photo rounded-circle mr-2" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="$<?php echo $author_nickname; ?>"><?php echo $author_nickname; ?></a> | <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span></p>
                  </div>                  
                </div>
              </div>
            <?php } ?>
            </div>
            <p><a class="btn" href="/blog/">Visit the blog</a></p>
            <!-- /post details -->
          </div>

        <?php } ?>
    </div>
    <div class="container">
      <?php
        if ( $post_type === 'post' ||  $post_type === 'bot' ) {
          include( locate_template( 'support-botwiki.php', false, false ) );
        }
      ?>          
    </div>
  <?php endwhile; ?>

  <?php else: ?>

    <!-- article -->
    <article>

      <h1><?php _e( 'Sorry, nothing to display.', 'botwiki' ); ?></h1>

    </article>
    <!-- /article -->

  <?php endif; ?>

  </section>
  <!-- /section -->
  </main>

<?php get_footer(); ?>
