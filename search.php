<?php
  get_header();
  global $helpers;
  $search_query = trim( get_search_query() );
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
			<h1 class="post-title"><?php

      $found_posts = $wp_query->found_posts;
      $title = '';

      if ( $found_posts === 0 ){
        $title = "Nothing found for <em>$search_query</em>";
      } elseif ( $found_posts === 1 ) {
        $title = "One result found for <em>$search_query</em>";
      } else {
        $title = number_format( $found_posts ) . " results found for <em>$search_query</em>";
      }

      echo $title;
      ?></h1>
      <div class="post-content">
        <?php

          get_search_form( true );
          
          if ( in_array( $search_query, [ 'twitter', 'twitter bot', 'twitterbot', 'twitter bots', 'twitterbots', 'tweet' ] ) ){ ?>
            <p><a class="btn" href="/bots/twitterbots/">Browse Twitter bots</a></p>
          <?php }

          get_template_part( 'loop' );

          $possible_tags = explode( ' ', $search_query );
          $tags_html = array();

          foreach ( $possible_tags as $tag ) {
            /*
              TODO: Very basic pluralization, consider refactoring in the future.
            */
            if ( substr( $tag, -1) === 's' ){
              $tag_singular = substr( $tag, 0, -1 );;
              $tag_plural = $tag;
            } else {
              $tag_singular = $tag;
              $tag_plural = $tag . 's';
            }

            if ( term_exists( $tag_singular, 'post_tag' ) ){
              $tags_html[] = "<a href='/tag/$tag_singular'>$tag_singular</a> ";
            }

            if ( term_exists( $tag_plural, 'post_tag' ) ){
              $tags_html[] = "<a href='/tag/$tag_plural'>$tag_plural</a> ";
            }
          }

          if ( $tags_html ){
            echo '<p class="lead mt-5 "><strong class="mr-3">Related tags</strong></p><p class="post-tags mb-5">';
            echo( implode( '', $tags_html ) );
            echo '</p>';          
          }

          if ($wp_query->found_posts > 0){
            get_template_part( 'pagination' );
          ?>
          <h3>Not quite what you're looking for?</h3>
          <?php }?>
          <div class="container">
            <ul>
              <li>
                <a title="Add your bot, tutorial, and other botmaking resources to Botwiki" href="<?php echo home_url(); ?>/contribute/">Contribute to Botwiki</a>
                <ul>
                  <li><em>Please be patient while we review the submissions</em> <span title="Thank you!">🙇</span></li>
                </ul>  
              </li>
              <li>
                <a href="https://botmakers.org/">Ask in the Botmakers community</a>
              </li>
              <li>
                <a href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Send us an email</a>
              </li>
              <li>
                <a href="https://twitter.com/botwikidotorg">Find us on Twitter</a>
              </li>
            </ul>
          </div>


          <div class="container">

            <h3 id="bots">Latest bots</h3>
            <div id="bots-latest-wrapper" class="row list">
            <?php
            remove_all_filters( 'pre_get_posts' );

            $latest_bots = get_posts( array( 
              'posts_per_page' => 4,
              'post_type' => 'bot',
              'post_status' => 'publish'
             ) );

            foreach ( $latest_bots as $bot ) {
              $post_thumbnail_url = get_the_post_thumbnail_url( $bot->ID, 'medium' );
              $post_thumbnail_url_full = get_the_post_thumbnail_url( $bot->ID );
              $dominant_color  = get_post_meta( $bot->ID, 'dominant_color', true );
              $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
              $dominant_color_css = str_replace(']', ')', $dominant_color_css);
              ?>
              <div class="col-sm-6 col-md-6 col-lg-3 list-item">
                <div class="card w-100">
                  <a href="<?php echo get_permalink( $bot->ID ); ?>">
                    <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                      <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>" data-src="<?php echo $post_thumbnail_url_full; ?>" alt="<?php echo $bot->post_title; ?>">
                    </div>
                  </a>
                  <div class="card-body">
                    <h5 class="card-title">
                      <a href="<?php echo get_permalink( $bot->ID ); ?>"><?php echo $bot->post_title; ?></a>  
                    </h5>
                    <p class="card-text"><?php echo $bot->post_excerpt; ?></p>
                  </div>
                </div>
              </div>
            <?php } ?>
            </div>
            <p><a class="btn" href="/bots/">Explore</a></p>
            <!-- /post details -->
          </div>
          <div class="container">

            <h3 id="blog">Latest from the blog</h3>
            <div id="blog-latest-wrapper" class="row list">
            <?php
            remove_all_filters( 'pre_get_posts' );

            $latest_blog_posts = get_posts( array( 
              'posts_per_page' => 4,
              'post_type' => 'post',
              'post_status' => 'publish'
             ) );

            foreach ( $latest_blog_posts as $blog_post ) {
              $post_thumbnail_url = get_the_post_thumbnail_url( $blog_post->ID, 'medium' );
              $post_thumbnail_url_full = get_the_post_thumbnail_url( $blog_post->ID );
              $dominant_color  = get_post_meta( $blog_post->ID, 'dominant_color', true );
              $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
              $dominant_color_css = str_replace(']', ')', $dominant_color_css);


              ?>
              <div class="col-sm-6 col-md-6 col-lg-3 list-item">
                <div class="card w-100">
                  <a href="<?php echo get_permalink( $blog_post->ID ); ?>">
                    <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                      <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>" data-src="<?php echo $post_thumbnail_url_full; ?>" alt="<?php echo $blog_post->post_title; ?>">
                    </div>
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
            <p><a class="btn" href="/blog/">Visit the blog</a></p>
            <!-- /post details -->
          </div>

  		</div>
    </div>
	</main>
<?php get_footer(); ?>
