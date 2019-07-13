<?php
	get_header();

	global $helpers;
  $site_url = get_site_url();
	$author_id = get_query_var('author');
	$author_data = get_userdata( intval($author_id ));

	// echo "<pre><code>";
	// var_dump(get_userdata( $author_id ));
	// echo "</code></pre>";

	$nickname = get_the_author_meta('nickname', $author_id);
	$username = get_the_author_meta('user_nicename', $author_id);
  $description = get_the_author_meta('description', $author_id);

  if ( user_can($author_id, 'administrator') ){  
  	$botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
    if ( empty( $botwiki_team_role ) ){
      $botwiki_team_role = "Botwiki team member.";
    }
  }
  else{
    $botwiki_team_role = "Botwiki contributor.";    
  }

	$first_name = get_the_author_meta('nickname', $author_id);
	$last_name = get_the_author_meta('last_name', $author_id);
	$full_name = '';

	if( empty($first_name)){
	    $full_name = $last_name;
	} elseif( empty( $last_name )){
	    $full_name = $first_name;
	} else {
	    $full_name = "{$first_name} {$last_name}";
	}

	// $email = $author_data['user_email'];
	$website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
	$twitter_handle = str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );

  $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

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
?>

	<main role="main" class="container">
    <div class="mt-5">
      <div class="row">
        <div class="col-sm-12 col-md-2 text-center">
          <img class="mr-3 mb-4 u-photo rounded" src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
        </div>
        <div class="col-sm-12 col-md-10">
          <h1 class="mt-0 mb-3"><?php echo $nickname; ?></h1>
        </div>
        <div class="col-sm-12">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <p class="card-text font-weight-bold"><?php echo $botwiki_team_role; ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="p-note">
            <?php echo $description; ?>
          </div>
          <div>
          <ul class="btn-list mt-5">
            <?php if ( !empty( $website_url )){ ?> 
              <li> 
                <a class="btn" title="Personal website" rel="me" href="<?php echo $website_url; ?>"><?php echo $helpers->get_domain_from_url($website_url); ?>
                </a>  
              </li> 
            <?php } ?>  
            <?php if ( !empty( $twitter_handle )){ ?> 
              <li>  
                <a class="btn" title="Twitter" rel="me" href="https://twitter.com/<?php echo $twitter_handle; ?>">@<?php echo $twitter_handle; ?></a> 
              </li> 
            <?php } ?>  
          </ul>            
          </div>
        </div>
      </div>

			<?php if ( count_user_posts( $author_id ) > 0 ) {  ?> 

				<h1 id="blog">My blog posts</h1>
				<?php

          global $wp_query;

          $wp_query = new WP_Query( array(
            'post_type'         => 'post',
            'author'            => $author_id,
            'paged'              =>  (get_query_var('paged')) ? get_query_var('paged') : 1,
            'page'              =>  (get_query_var('paged')) ? get_query_var('paged') : 1,
            'posts_per_page'    => '6',
            'post_status'       => 'publish',
            'orderby'           => 'publish_date',
            'order'             => 'DESC'
          ) );            

					get_template_part('loop', 'author');
					get_template_part('pagination');
				?>

			<?php } ?>

			<?php
          global $wp_query;

          $wp_query = new WP_Query( array(
            'post_type'         => 'bot',
            'posts_per_page'    => -1,
            'author'						=> $author_id,
            'post_status'       => 'publish',
            'orderby'           => 'publish_date',
            'order'             => 'DESC'
          ) );

          if ( $wp_query->post_count > 0 ){ ?>
            <h1 id="bots">My bots</h1>


            <?php
              $posts = $wp_query->posts;

              $author_tags_bots = array();
              $author_tags_bots_html = array();

              foreach ($posts as $post) {
                $author_tags_bots = array_merge($author_tags_bots, array_map( array( $helpers, 'get_tag_slug'), wp_get_post_tags( $post->ID ) ) );
              }

              $author_tags_bots = array_unique( $author_tags_bots );

              sort( $author_tags_bots );

              $posts = array_slice( $posts, 0, 6 );

              $include_description = ( $atts['description'] === 'yes' || $atts['description'] === 'true' );

              $bot_list_html = '<div class="container mt-5"><div class="row list">';

              foreach ($posts as $post) {
                $post_id = $post->ID;
                $post_meta = get_post_meta( $post_id );
                if ( get_post_status( $post_id ) === 'publish' ){
                  $bot_url = post_permalink( $post_id );
                  $bot_source_url = $post_meta['bot_source_url'][0];

                  $bot_title = $post_meta['card_title'];
                  if ( empty( $bot_title ) ){
                    $bot_title = get_the_title( $post_id );
                  }


                  $bot_thumbnail_url = get_the_post_thumbnail_url( $post_id );

                  $bot_thumbnail = get_the_post_thumbnail( $post_id, 'full', array( 'class' => 'card-img-top' ) );

                  $bot_description = get_the_excerpt( $post_id );

                  $bot_list_html .= '<div class="col-sm-12 col-md-6 col-lg-4 no-pad list-item">' .
                                     ' <div class="card w-100">' .
                                     '    <a href="' . $bot_url . '">' .
                                     '      <img src="' . $bot_thumbnail_url . '" data-src="' . $bot_thumbnail_url . '" class="card-img-top lazy-load" >' .
                                     '    </a>' .
                                     '    <div class="card-body">' .
                                     '      <h5 class="card-title">' . $bot_title . '</h5>' .
                                     '      <p class="card-text">' . $bot_description . '</p>' .
                                     '    </div>' .
                                     '    <div class="card-footer">' .
                                     '      <a href="' . $bot_url . '" class="btn mb-0">View bot</a>' .
                                     ( !empty( $bot_source_url ) ? '<a href="' . $bot_source_url . '" class="btn mb-0">View source</a>' : '' ) .
                                     '    </div>' .
                                     '  </div>' .
                                     '</div>';
                }
              }

              $bot_list_html .= '</div>';


              $bot_list_html .= '</div>';
              echo $bot_list_html;

              ?>
              <a class="btn mt-2" href="<?php echo '/author/' . $username . '/?post_type=bot' ?>">View all bots</a>

              <p class="post-tags mt-5 mb-5"><?php

                foreach ( $author_tags_bots as $tag ) {
                  $author_tags_bots_html[] = '<a href="' . $site_url . '/author/' . $username . '/?post_type=bot&tags=' . $tag . '">' . $tag . '</a> ';
                }

                echo join( ' ', $author_tags_bots_html );
              ?></p>
          <?php }
        ?>
      <?php
        global $wp_query;

        $wp_query = new WP_Query( array(
          'post_type'         => 'resource',
          'posts_per_page'    => '5',
          'author'            => $author_id,
          'post_status'       => 'publish',
          'orderby'           => 'publish_date',
          'order'             => 'DESC'
        ) );

        if ( $wp_query->post_count > 0 ){ ?>
          <h1 id="resources">My resources</h1>
          <?php
            $posts = $wp_query->posts;

            $author_tags_resources = array();
            $author_tags_resources_html = array();

            foreach ($posts as $post) {
              $author_tags_resources = array_merge($author_tags_resources, array_map( array( $helpers, 'get_tag_slug'), wp_get_post_tags( $post->ID ) ) );
            }

            $author_tags_resources = array_unique( $author_tags_resources );
            sort( $author_tags_resources );

            get_template_part('loop');
          ?>
          <a class="btn mt-2" href="<?php echo '/author/' . $username . '/?post_type=resource' ?>">View all resources</a>
          <p class="post-tags mt-5 mb-5"><?php
            foreach ( $author_tags_resources as $tag ) {
              $author_tags_resources_html[] = '<a href="' . $site_url . '/author/' . $username . '/?post_type=resource&tags=' . $tag . '">' . $tag . '</a> ';
            }

            echo join( ' ', $author_tags_resources_html );
          ?></p>
        <?php }        
      ?>
		</div>
	</main>

<?php get_footer(); ?>
