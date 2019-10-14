<?php
  get_header();
  $post_id = get_the_id();
  $post_type = get_post_type();
  $post_meta = get_post_meta( $post_id ); 
  $site_url = get_site_url();
?>
  <main role="main" class="container-fluid m-0 p-0">

  <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
    <!-- post thumbnail -->
    <?php if ( has_post_thumbnail() ) { ?>

    <?php
      $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
      $dominant_color_css = str_replace( '[', 'background-color:rgb( ', $dominant_color );
      $dominant_color_css = str_replace( ']', ' )', $dominant_color_css );
    ?>
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php if ( $post_type == 'bot' ){ ?>
        <a href="<?php echo get_the_post_thumbnail_url( $post_id ); ?>">
        <?php } ?>
          <?php
            $post_thumbnail_id = get_post_thumbnail_id();
            the_post_thumbnail( 'post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load expand-image webfeedsFeaturedVisual', 'title' => get_post( $post_thumbnail_id )->post_title ] );
          ?>
          <?php if ( $post_type == 'bot' ){ ?>
          </a>
        <?php } ?>

      </div>
    <?php } ?>
    <!-- /post thumbnail -->

    <div class="container">
      <!-- article -->
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- post title -->
        <h1><?php the_title(); ?></h1>
        <!-- /post title -->


          <?php
          if ( $post_type == 'bot' ){ ?>
            <ul class="btn-list">
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
                  ?>
                  <li>
                    <a class="btn" href="<?php echo $url; ?>">View on <?php echo $domain; ?></a>
                  </li>
                <?php }                 
              }
            }

            $bot_languages = wp_get_post_terms( $post_id, 'programing_language' );

            $bot_source_urls = array();
            if ( array_key_exists( 'bot_source_url', $post_meta ) && !empty( $post_meta['bot_source_url'][0] ) ){
              $bot_source_urls = preg_split( '/\r\n|[\r\n]/', $post_meta['bot_source_url'][0] );
            }

            if ( count( $bot_source_urls ) > 0 ){ ?>
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
          <?php }
          else if ( $post_type == 'resource' && !empty( $post_meta['resource_url'][0] ) ){

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
        <?php }       

        the_content(); // Dynamic Content 

        if ( $post_type === 'bot' ) { 
          $networks = get_the_terms( $post_id, 'network' );
          function get_network_name( $network ){
            return $network->name;
          }

          $network_names = array_map( 'get_network_name', $networks );
          $bot_tweets_html_meta = get_post_meta( $post_id, 'bot_tweets_html', true );
          $tumblr_script = '<script async src="https://assets.tumblr.com/post.js"></script>';

          $bot_tweets_html = preg_split( '/(<\/blockquote>|<\/iframe>|' . str_replace( "/", "\/", $tumblr_script ) . ')/i', $bot_tweets_html_meta, -1, PREG_SPLIT_NO_EMPTY );
          ?>
          <div class="row social-embeds">
          <?php foreach ( $bot_tweets_html as $tweet_html ) { ?>
            <div class="col-sm-12 col-md-6">
              <?php
              if ( strpos( $tweet_html, 'twitter-tweet' ) !== false ){
                echo $tweet_html . '</blockquote>';
              }
              elseif ( strpos( $tweet_html, 'mastodon-embed' ) !== false ){
                echo str_replace( '<blockquote>', '', $tweet_html ) . '</iframe>' ;
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
          <p class="post-tags mt-5 mb-5">
            <?php 
              $network_tags = array();
              if ( $networks ){
                foreach ( $networks as $network ) {
                  $network_tags[] = '<a href="' . $site_url . '/bot/?networks=' . $network->slug . '">' . $network->slug . '</a> ';
                }               
              }

              echo join( ' ', $network_tags );

              $languages = get_the_terms( $post_id, 'programing_language' );
              $language_tags = array();

              if ( $languages ){
                foreach ( $languages as $language ) {
                  $language_tags[] = '<a href="' . $site_url . '/languages/' . $language->slug . '">' . $language->slug . '</a> ';
                }

              }

              echo join( ' ', $language_tags );

              $tags = get_the_tags();
              $tags_array = array();

              if ( $tags ){
                foreach ( $tags as $tag ) {
                  $tags_array[] = '<a href="' . $site_url . '/bot/?tags=' . $tag->slug . '">' . $tag->slug . '</a> ';
                }
              }

              echo join( ' ', $tags_array );
              // the_tags( '', ' ', '<br>' );
            ?>
          </p>          
          <?php if ( count( $bot_source_urls ) > 0 ){
            global $helpers;
            ?>
            <h3 id="source-code">Source code</h3>
            <ul>
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

          if ( $post_type === 'post' ) {
            $post_date = get_the_time( 'F j, Y' );
            $post_date_full = $post_date . ' ' . get_the_time( 'g:i a' );
            $m = new \Moment\Moment( $post_date );
            $post_date_ago = $m->fromNow()->getRelative();
          ?>
            <p class="mt-5"><em>Posted <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span> in <?php the_category( ', ' ); ?></em></p>
            <p class="post-tags mt-5 mb-5"><?php the_tags( '', ' ', '<br>' ); // Separated by commas with a line break at the end ?></p>
          <?php }

          global $coauthors_plus;

          $coauthors = get_coauthors();
          $coauthors_count = count( $coauthors );

          if ( $coauthors_count > 1 || ( $coauthors_count === 1 && $coauthors[0]->data->ID !== "2" ) ){ ?>
            <?php if ( $post_type === 'bot' ){ ?>
              <h3 id="authors">Created by</h3>
            <?php }

            foreach ( $coauthors as $coauthor ) {
              $author_id = $coauthor->data->ID;
              if ( $author_id != 2 ){
                $author_data = get_userdata( intval( $author_id ) );

                // echo "<pre><code>";
                // var_dump( get_userdata( $author_id ) );
                // echo "</code></pre>";

                $nickname = get_the_author_meta( 'nickname', $author_id );
                $username = get_the_author_meta( 'user_nicename', $author_id );

                if ( user_can( $author_id, 'administrator' ) ){  
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
                $twitter_handle = str_replace( '@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );

                if ( empty( $profile_img_url ) ){
                  $profile_img_url = get_avatar_url( $author_id );
                }

              // $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
              // $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

             //  $background_img_dominant_color_css = str_replace( '[', 'background-color:rgb( ', $background_img_dominant_color );
             //  $background_img_dominant_color_css = str_replace( ']', ' )', $background_img_dominant_color_css );
              include( locate_template( 'author-card.php', false, false ) );  
            }
          } ?>
        <?php }
        if ( $post_type === 'bot' ) { ?>


          <h3 id="related-bots">More bots</h3>
          <div id="related-bots-wrapper" class="row list">
          <?php

          $related_bots = get_posts( array( 
            'posts_per_page' => 3,
            'orderby' => 'rand',
            'order' => 'ASC',
            'exclude' => array( $post_id ),
            'tag__in' => array_map( function( $tag ){ return $tag->term_id; }, $tags ),
            'post_type' => 'bot',
            'post_status' => 'publish',
            'suppress_filters' => true
          ) );

          foreach ( $related_bots as $related_bot ) {
            ?>
            <div class="col-sm-6 col-md-6 col-lg-3 list-item">
              <div class="card w-100">
                <a href="<?php echo get_permalink( $related_bot->ID ); ?>">
                  <img class="card-img-top" src="<?php echo get_the_post_thumbnail_url( $related_bot->ID ); ?>" alt="<?php echo $related_bot->post_title; ?>">
                </a>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="<?php echo get_permalink( $related_bot->ID ); ?>"><?php echo $related_bot->post_title; ?></a>  
                  </h5>
                  <p class="card-text"><?php echo $related_bot->post_excerpt; ?></p>
                </div>
              </div>
            </div>
          <?php } ?>
            <div class="col-sm-6 col-md-6 col-lg-3 list-item">
              <div class="card w-100">
                <a href="/random-bot/">
                  <img class="card-img-top" src="https://botwiki.org/wp-content/uploads/2019/07/robot-face-emoji-one.png" alt="Random bot" title="Go to a random bot page">
                </a>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="/random-bot/">????</a>  
                  </h5>
                  <p class="card-text">??????????</p>
                </div>
              </div>
            </div>          
          </div>
          <p><a class="btn" href="/bots/">See more</a></p>
        <?php }

        if ( $post_type === 'post' ||  $post_type === 'bot' || $post_type === 'resource' ) { ?>
          <h3 id="blog">Latest from our blog</h3>
          <div id="blog-latest-wrapper" class="row list">
          <?php

          $latest_blog_posts = get_posts( array( 
            'posts_per_page' => 4,
            'exclude' => array( $post_id ),            
            'post_type' => 'post',
            'post_status' => 'publish'
           ) );

          foreach ( $latest_blog_posts as $blog_post ) {
            ?>
            <div class="col-sm-6 col-md-6 col-lg-3 list-item">
              <div class="card w-100">
                <a href="<?php echo get_permalink( $blog_post->ID ); ?>">
                  <img class="card-img-top" src="<?php echo get_the_post_thumbnail_url( $blog_post->ID ); ?>" alt="<?php echo $blog_post->post_title; ?>">
                </a>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="<?php echo get_permalink( $blog_post->ID ); ?>"><?php echo $blog_post->post_title; ?></a>  
                  </h5>
                  <p class="card-text"><?php echo $blog_post->post_excerpt; ?></p>
                </div>
              </div>
            </div>
          <?php } ?>
          </div>
          <p><a class="btn" href="/blog/">See more</a></p>
          <!-- /post details -->
        <?php } ?>
      </article>
      <!-- /article -->
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
