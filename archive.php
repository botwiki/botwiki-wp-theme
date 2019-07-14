<?php get_header();
  $site_url = get_site_url();

  if(is_author()){
    $author_id = get_query_var('author');

    $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $user->ID ) );

    if ( empty( $profile_img_url )){
      $profile_img_url = get_avatar_url($author_id);
    }

    $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
    $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

    if ( empty( $background_img_url )){
      $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', 2 ) );
      $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', 2 ) );    
    }

    $background_img_dominant_color_css = str_replace('[', 'background-color:rgb(', $background_img_dominant_color);
    $background_img_dominant_color_css = str_replace(']', ')', $background_img_dominant_color_css);


    if ( !empty( $background_img_url ) ){ ?>
      <div class="thumbnail-wrapper" style="<?php echo $background_img_dominant_color_css; ?>">
        <img src="<?php echo $background_img_url; ?>">
      </div>
    <?php }
  } ?>


  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <?php
        if(is_author()){
          $nickname = get_the_author_meta('nickname', $author_id);
          $username = get_the_author_meta('user_nicename', $author_id);
          $post_type = $wp_query->query['post_type'];          
        ?>
          <h1>Browsing <?php
            echo ( !empty( $_GET['opensource'] ) ? 'open source' : '' );
          ?> <?php echo $post_type; ?>s by <a href="/author/<?php echo $username ?>"><?php echo $nickname ?></a><?php
          if ( !empty( $_GET['tags'] )){ ?>
            tagged <a href="<?php echo $site_url . '/tag/' . $_GET['tags'] ;?>">#<?php echo $_GET['tags']; ?></a><?php } ?>&nbsp;...</h1>
      <?php } elseif ( is_tax() ){
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 

        if ( !empty( $term->description ) ){
          $page_title = str_replace( '.', '', $term->description );
        } else {
          $page_title = "Posts tagged #" . $term->name;
        }
      ?>
        <h1><?php echo $page_title; ?></h1>
      <?php }
      elseif ( $wp_query->query['post_type'] == 'bot' ) {

        if ( $_GET['networks'] || $_GET['languages'] || $_GET['tags'] ){
          function network_links( $network ){
            return "<a href='" . get_site_url() . "/networks/$network'>#$network</a> ";
          }

          if ( !empty( $_GET['networks'] ) ){
            $networks = implode( ' ', array_map( 'network_links', explode( ',', $_GET['networks'] ) )  );
          }


          function language_links( $language ){
            return "<a href='" . get_site_url() . "/languages/$language'>#$language</a> ";
          }

          if ( !empty( $_GET['languages'] ) ){
            $languages = implode( ' ', array_map( 'language_links', explode( ',', $_GET['languages'] ) )  );
          }

          function tag_links( $tag ){
            return "<a href='" . get_site_url() . "/tag/$tag'>#$tag</a> ";
          }

          if ( !empty( $_GET['tags'] ) ){
            $tag_links = implode( ' ', array_map( 'tag_links', explode( ',', $_GET['tags'] ) ) );
          }

          ?>
          <h1>Browsing <?php 
            echo ( !empty( $_GET['opensource'] ) ? 'open source' : '' );
          ?> bots tagged <?php echo $networks ; echo $languages ; echo rtrim( $tag_links ); ?>&nbsp;...</h1>
        <?php }
        else {?>
          <h1>Browsing all <?php
            echo ( !empty( $_GET['opensource'] ) ? 'open source' : '' );
          ?> bots...</h1>
        <?php } ?>
      <?php }
      else{ ?>
        <h1>Archives</h1>
      <?php } ?>

      <?php
      if ( !empty( $_GET['tags'] ) ){
        $tags = explode( ',', $_GET['tags'] );

        if ( in_array( 'opensource', $tags ) ) { ?>
          <a class="btn mb-3" href="/bot/?opensource=true">Browse opensource bots</a>
        <?php } elseif ( in_array( 'generative', $tags ) ) { ?>
          <a class="btn mb-3" href="/bot/?tags=generative,images">#generative+images</a>
          <a class="btn mb-3" href="/bot/?tags=generative,text">#generative+text</a>
          <a class="btn mb-3" href="/bot/?tags=generative,emoji">#generative+emoji</a>
        <?php } elseif ( in_array( 'iot', $tags ) ) { ?>          
          <a class="btn mb-3" href="/bot/?tags=iot,images">#iot+images</a>
        <?php } elseif ( in_array( 'mbc-winner', $tags ) ) { ?>
          <div class="card pt-5 mt-5 mb-2">
            <div class="container">
              <div class="row">
                <div class="col-sm-12 col-md-4 text-center p-l">
                    <img src="https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016.png" class="lazy-load mb-5 wp-post-image" alt="" data-src="" title="Monthly Bot Challenge January 2016" srcset="https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016.png 1200w, https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016-250x95.png 250w, https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016-768x292.png 768w, https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016-700x267.png 700w, https://botwiki.org/wp-content/uploads/2018/02/mbc-january-2016-120x46.png 120w" sizes="(max-width: 1200px) 100vw, 1200px" />
                </div>
                <div class="col-sm-12 col-md-8">
                  <h2 id="monthly-bot-challenge">Monthly Bot Challenge</h2>
                  <p>Monthly Bot Challenge is a recurring community event dedicated to showcasing friendly, useful, artistic online bots.</p>
                  <p><a class="btn" href="/projects/monthly-bot-challenge/">Read more</a></p>
                </div>
              </div>
            </div>
          </div>




        <?php }
      } ?>

			<?php get_template_part( 'loop' ); ?>
			<?php get_template_part( 'pagination' ); ?>

      <?php if(is_author()){
        if ( user_can($author_id, 'administrator') ){  
          $botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
          if ( empty( $botwiki_team_role ) ){
            $botwiki_team_role = "Botwiki team member.";
          }
        }
        else{
          $botwiki_team_role = "Botwiki contributor.";    
        }
        $botwiki_profile_page_url = get_site_url() . '/author/' . $username;

        include( locate_template( 'author-card.php', false, false ) );
      } ?>


		</div>
	</main>
<?php get_footer(); ?>
