<?php get_header();
  $site_url = get_site_url();
  $current_url = home_url( add_query_arg( array( $_GET ), $wp->request ) );

  if ( !empty( $_GET['opensource'] ) ){
    $glitch_link = '<li><a class="btn" href="https://glitch.com/@botwiki">Botwiki on Glitch 🎏</a></li>';
    $filter_opensource = '&opensource=true';
    $narrow_opensource_link = '';
  } else {
    $glitch_link = '';
    $filter_opensource = '';
    $narrow_opensource_url = $site_url . '/bot/?' .
                         ( !empty( $_GET['tags'] ) ? 'tags=' . $_GET['tags']  : ''  ) .
                         ( !empty( $_GET['networks'] ) ? 'networks=' . $_GET['networks']  : ''  ) .
                         ( !empty( $_GET['languages'] ) ? 'languages=' . $_GET['languages']  : ''  ) .
                         '&opensource=true';


    global $wp;
    $narrow_opensource_url = $current_url . ( strpos( $current_url, '?' ) === false ? '?' : '&' ) . 'opensource=true';

    $narrow_opensource_link = '<li><a class="btn" href="' . $narrow_opensource_url . '">Open source bots</a></li>';

  }

  if( is_author() ){
    $author_id = get_query_var( 'author' );

    $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $user->ID ) );

    if ( empty( $profile_img_url ) ){
      $profile_img_url = get_avatar_url( $author_id, array( 'size' => 360, 'scheme' => 'https' ) );
    }

    $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
    $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

    if ( empty( $background_img_url ) ){
      $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', 2 ) );
      $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', 2 ) );    
    }

    $background_img_dominant_color_css = str_replace( '[', 'background-color:rgb( ', $background_img_dominant_color );
    $background_img_dominant_color_css = str_replace( ']', ' )', $background_img_dominant_color_css );


    if ( !empty( $background_img_url ) ){ ?>
      <div class="thumbnail-wrapper" style="<?php echo $background_img_dominant_color_css; ?>">
        <img src="<?php echo $background_img_url; ?>">
      </div>
    <?php }
  } ?>


  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <?php
        if( is_author() ){
          $nickname = get_the_author_meta( 'nickname', $author_id );
          $username = get_the_author_meta( 'user_nicename', $author_id );
          $post_type = $wp_query->query['post_type'];          
        ?>
          <h1 class="post-title">Browsing <?php
            echo ( !empty( $_GET['opensource'] ) ? 'open source' : '' );
          ?> <?php echo $post_type; ?>s by <a href="/author/<?php echo $username ?>"><?php echo $nickname ?></a><?php
          if ( !empty( $_GET['tags'] ) ){ ?>
            tagged <a href="<?php echo $site_url . '/tag/' . $_GET['tags'] ;?>">#<?php echo $_GET['tags']; ?></a><?php } ?>&nbsp;...</h1>
            <div class="post-content">
      <?php } elseif ( is_tax() ){
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 

        if ( get_query_var( 'taxonomy' ) === 'programing_language' ) {
          $page_title = "Bots made with " . $term->name;
        } else {
          $page_title = "Posts tagged #" . $term->name;
        }
      ?>
        <h1 class="post-title"><?php echo $page_title; ?></h1>
        <div class="post-content">
        <?php

        if ( !empty( $term->description ) ){
          echo wpautop( $term->description );
        }
      }
        elseif ( $wp_query->query['post_type'] == 'bot' ) {

          if ( $_GET['networks'] || $_GET['languages'] || $_GET['tags'] ){
            function network_links( $network ){
              return "<a href='" . get_site_url() . "/bot/?networks=$network'>#$network</a> ";
            }

            if ( !empty( $_GET['networks'] ) ){
              $networks = implode( ' ', array_map( 'network_links', explode( ',', $_GET['networks'] ) )  );
            }


            function language_links( $language ){
              return "<a href='" . get_site_url() . "/bot/?languages=$language'>#$language</a> ";
            }

            if ( !empty( $_GET['languages'] ) ){
              $languages = implode( ' ', array_map( 'language_links', explode( ',', $_GET['languages'] ) )  );
            }

            function tag_links( $tag ){
              return "<a href='" . get_site_url() . "/bot/?tags=$tag'>#$tag</a> ";
            }

            if ( !empty( $_GET['tags'] ) ){
              $tag_links = implode( ' ', array_map( 'tag_links', explode( ',', $_GET['tags'] ) ) );
            }

            ?>
            <h1 class="post-title">Browsing <?php 
              echo ( !empty( $_GET['opensource'] ) ? 'open source' : '' );
            ?> bots tagged <?php echo $networks ; echo $languages ; echo rtrim( $tag_links ); ?>&nbsp;...</h1>
            <div class="post-content">
            <ul class="btn-list">
              <?php
                // echo $glitch_link;
                // echo $narrow_opensource_link;
                // $glitch_link = '';
                // $narrow_opensource_link = '';
              ?>
            </ul>
          <?php } else {?>
            <h1 class="post-title"><?php
            if ( !empty( $_GET['opensource'] ) ){ ?>
              Browsing <?php
              $os_bots = new WP_Query( array(
                'post_type'         => 'bot',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'meta_key'          => 'bot_source_url',
                'meta_value'        => array(''),
                'meta_compare'      => 'NOT IN',
              ) );    

              echo number_format( count( $os_bots->posts ) ); ?> open source bots...
            <?php } else { ?>
              Browsing <?php echo number_format( wp_count_posts( 'bot' )->publish ); ?> bots...
            <?php }?></h1>
            <div class="post-content">
            <ul class="btn-list">

            <?php if ( !empty( $_GET['opensource'] ) ){ ?>
              <li><a class="btn" href="/bots/open-source/#browse-bots-by-category">Browse by category</a></li>
              <li><a class="btn" href="/bots/open-source/#browse-bots-by-network">Browse by network</a></li>
              <!-- <li><a class="btn" href="/random-bot?opensource=true">Random bot</a></li> -->
              <li><a class="btn" href="/bots/#bots">What is a bot?</a></li>
            <?php } else {?>
              <li><a class="btn" href="/bots/#browse-bots-by-category">Browse by category</a></li>
              <li><a class="btn" href="/bots/#browse-bots-by-network">Browse by network</a></li>
              <li><a class="btn" href="/random-bot">Random bot</a></li>
              <li><a class="btn" href="/bots/#bots">What is a bot?</a></li>
              <?php
                // echo $glitch_link;
                // echo $narrow_opensource_link;
                // $glitch_link = '';
                // $narrow_opensource_link = '';              
              ?>
            <?php } ?>
            </ul>
          <?php } ?>
        <?php }
        else{ ?>
          <h1 class="post-title">Archives</h1>
          <div class="post-content">        
        <?php }

        if ( !empty( $_GET['tags'] ) ){
          $tags = explode( ',', $_GET['tags'] );

          if ( in_array( 'opensource', $tags ) ) { ?>
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?opensource=true">Browse opensource bots</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'cheapbotsdonequick', $tags ) ) { ?>          
            <div class="card pt-5 mt-5 mb-2">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-4 text-center p-l">
                      <img src="/wp-content/uploads/2018/08/cheap-bots-done-quick.png" class="lazy-load mb-5 wp-post-image" alt="" data-src="" title="Cheap Bots, Done Quick" srcset="/wp-content/uploads/2018/08/cheap-bots-done-quick.png 1920w, /wp-content/uploads/2018/08/cheap-bots-done-quick-250x110.png 250w, /wp-content/uploads/2018/08/cheap-bots-done-quick-768x339.png 768w, /wp-content/uploads/2018/08/cheap-bots-done-quick-700x309.png 700w, /wp-content/uploads/2018/08/cheap-bots-done-quick-120x53.png 120w" />
                  </div>
                  <div class="col-sm-12 col-md-8">
                    <h2 id="monthly-bot-challenge">Cheap Bots, Done Quick!</h2>
                    <?php
                    $cbdq_description = apply_filters( 'the_content', get_post_field( 'post_content', 8220 ) );
                    echo substr_replace( $cbdq_description, '', strpos( $cbdq_description, '<ul' ), strpos( $cbdq_description, '/ul>' ) -strpos( $cbdq_description, '<ul' ) + 4);
                    ?>
                    <ul class="btn-list">
                      <li>
                        <a class="btn" href="https://cheapbotsdonequick.com/">Visit</a>
                      </li>
                      <li>
                        <a class="btn" href="/projects/botwiki-interviews/george-buckenham/">Interview</a>
                      </li>
                      <li>
                        <a class="btn" href="https://www.patreon.com/v21/">Support</a>
                      </li>
                      <?php
                        echo $glitch_link;
                        echo $narrow_opensource_link;
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } elseif ( in_array( 'botsin.space', $tags ) || in_array( 'botsin-space', $tags ) ) { ?>          
            <div class="card pt-5 mt-5 mb-2">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-4 text-center p-l">
                      <img src="/wp-content/uploads/2018/08/cheap-bots-done-quick.png" class="lazy-load mb-5 wp-post-image" alt="" data-src="" title="Cheap Bots, Done Quick" srcset="/wp-content/uploads/2018/09/botsin-space-bots-glitch.png 603w, /wp-content/uploads/2018/09/botsin-space-bots-glitch-250x114.png 250w, /wp-content/uploads/2018/09/botsin-space-bots-glitch-120x55.png 120w" />
                  </div>
                  <div class="col-sm-12 col-md-8">
                    <h2 id="monthly-bot-challenge">botsin.space</h2>
                    <p>A Mastodon instance for hosting friendly bots.</p>
                    <ul class="btn-list">
                      <li>
                        <a class="btn" href="https://botsin.space/">Visit site</a>
                      </li>
                      <li>
                        <a class="btn" href="/projects/botwiki-interviews/botwiki-interview-colin-mitchell">Interview with the creator</a>
                      </li>
                      <?php
                        echo $glitch_link;
                        echo $narrow_opensource_link;
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } elseif ( in_array( 'images', $tags ) ) { ?>
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?tags=images,generative<?php echo $filter_opensource;?>">#images+generative</a></li>
              <li><a class="btn" href="/bot/?tags=images,iot<?php echo $filter_opensource;?>">#images+iot</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'text', $tags ) ) { ?>
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?tags=text,generative<?php echo $filter_opensource;?>">#text+generative</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'generative', $tags ) ) { ?>
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?tags=generative,images<?php echo $filter_opensource;?>">#generative+images</a></li>
              <li><a class="btn" href="/bot/?tags=generative,gif<?php echo $filter_opensource;?>">#generative+gif</a></li>
              <li><a class="btn" href="/bot/?tags=generative,emoji<?php echo $filter_opensource;?>">#generative+emoji</a></li>
              <li><a class="btn" href="/bot/?tags=generative,text<?php echo $filter_opensource;?>">#generative+text</a></li>
              <li><a class="btn" href="/bot/?tags=generative,audio<?php echo $filter_opensource;?>">#generative+audio</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'interactive', $tags ) ) { ?>
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?tags=interactive,game<?php echo $filter_opensource;?>">#interactive+game</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'iot', $tags ) ) { ?>          
            <ul class="btn-list">
              <li><a class="btn" href="/bot/?tags=iot,images<?php echo $filter_opensource;?>">#iot+images</a></li>
              <?php
                echo $glitch_link;
                echo $narrow_opensource_link;
              ?>
            </ul>
          <?php } elseif ( in_array( 'non-english', $tags ) && count( $tags ) > 1 ) { ?>          
            <ul class="btn-list">
              <li><a class="btn" href="/bots/non-english/">Browse all non-English bots</a></li>
            </ul>
          <?php } elseif ( in_array( 'mbc-winner', $tags ) ) { ?>
            <div class="card pt-5 mt-5 mb-2">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-4 text-center p-l">
                      <img src="/wp-content/uploads/2018/02/mbc-january-2016.png" class="lazy-load mb-5 wp-post-image" alt="" data-src="" title="Monthly Bot Challenge January 2016" srcset="/wp-content/uploads/2018/02/mbc-january-2016.png 1200w, /wp-content/uploads/2018/02/mbc-january-2016-250x95.png 250w, /wp-content/uploads/2018/02/mbc-january-2016-768x292.png 768w, /wp-content/uploads/2018/02/mbc-january-2016-700x267.png 700w, /wp-content/uploads/2018/02/mbc-january-2016-120x46.png 120w" sizes="( max-width: 1200px ) 100vw, 1200px" />
                  </div>
                  <div class="col-sm-12 col-md-8">
                    <h2 id="monthly-bot-challenge">Monthly Bot Challenge</h2>
                    <p>Monthly Bot Challenge is a recurring community event dedicated to showcasing friendly, useful, artistic online bots.</p>
                    <ul class="btn-list">
                      <li><a class="btn" href="/projects/monthly-bot-challenge/">Read more</a></li>
                      <?php
                        echo $glitch_link;
                        echo $narrow_opensource_link;
                      ?>                  
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <ul class="btn-list">
            <?php 
              echo $glitch_link;
              echo $narrow_opensource_link;
              ?>
            </ul>
          <?php }
        } ?>

  			<?php
          get_template_part( 'loop' );
          include( locate_template( 'support-botwiki.php', false, false ) );
          get_template_part( 'pagination' );

          if ( is_author() ){
            if ( user_can( $author_id, 'administrator' ) ){  
              $botwiki_team_role = get_the_author_meta( 'botwiki-team-role', $author_id );
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
		</div>
	</main>
<?php get_footer(); ?>
