<?php
  /* Template Name: Team Page Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $admins = get_users( array(
    'role__in'         => array( 'administrator', 'author' ),
    'orderby'      => 'registered',
    'order'        => 'ASC',
    // 'fields'       => 'all',
    'who'          => '',
  ) );

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1 class="post-title text-center"><?php the_title(); ?></h1>
        <div class="post-content">
          <p class="text-center lead mb-5 mt-n3 font-weight-bold"><?php echo get_the_excerpt(); ?></p>
          <?php if ( get_post_meta( $post_id, 'hide_featured_image', true ) !== 'on' ){ ?>
            <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
              <?php
                $post_thumbnail_id = get_post_thumbnail_id();
                the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load expand-image', 'title' => get_post($post_thumbnail_id)->post_title ]);
              ?>
            </div>
          <?php } ?>
          <div
          <?php if ( get_post_meta( $post_id, 'hide_featured_image', true ) === 'on' ){ ?>
          class="text-sm-left text-md-center"
          <?php } ?>
          >
            <ul class="btn-list">
              <li>
                <a class="btn" target="_blank" href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Get in touch</a>
                <a class="btn" href="https://mastodon.social/@botwiki">Find us on Mastodon</a>
              </li>
            </ul>
          </div>
          <?php
            echo do_shortcode( get_post_field('post_content', $post_id) );
          ?>
<!-- 
        <h2 id="admins">
            <a class="pilcrow-link" href="#admins">
                <span class="link">Site administrators</span>
                <span class="pilcrow">¶</span>
            </a>
        </h2>
 -->
        <div class="container mt-5 ml-n3 mr-n3">
          <div class="row list">

          <?php
            foreach ( $admins as $index=>$admin ) {

              $author_id = $admin->data->ID;
              $author_data = get_userdata( intval($author_id ));

              $nickname = get_the_author_meta( 'nickname', $author_id);
              $username = get_the_author_meta('user_nicename', $author_id);
              $description = get_the_author_meta( 'description', $author_id);

              $first_name = get_the_author_meta( 'nickname', $author_id);
              $last_name = get_the_author_meta( 'last_name', $author_id);
              $full_name = '';

              if( empty($first_name)){
                  $full_name = $last_name;
              } elseif( empty( $last_name )){
                  $full_name = $first_name;
              } else {
                  $full_name = "{$first_name} {$last_name}";
              }

              if ( user_can($author_id, 'administrator') ){  
                $botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
                if ( empty( $botwiki_team_role ) ){
                  $botwiki_team_role = "Botwiki team member.";
                }
              }
              else{
                $botwiki_team_role = "Botwiki contributor.";    
              }
              // $website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
              // $twitter_handle = '@' . str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );
              // $fediverse_handle =  esc_attr( get_the_author_meta( 'fediverse-handle', $author_id ) );

              $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

              if ( empty( $profile_img_url ) ){
                $profile_img_url = get_avatar_url( $author_id, array( 'size' => 500, 'scheme' => 'https' ) );
              }

              $profile_img_url = str_replace( '?s=120', '?s=512', $profile_img_url );

              ?>
              <div class="col-sm-6 col-md-3 list-item">
                <div class="card w-100">
                  <a href="<?php echo get_author_posts_url( $author_id, $username ); ?>">
                    <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
                  </a>
                  <div class="card-body">
                    <h5 class="card-title">
                        <a class="stretched-link" href="<?php echo get_author_posts_url( $author_id, $username ); ?>"><?php echo $nickname; ?></a>  
                      </h5>
                    <p class="card-text"><?php echo $botwiki_team_role; ?></p>
                  </div>
                </div>
              </div>
              <?php } ?>
              </div>
            </div>
            <?php            

              $contributors = get_users( array(
                'role__not_in' => ['administrator'],
                'exclude'      => [2],
                'orderby'      => 'registered',
                'order'        => 'ASC'
              ) );

              if ( count( $contributors ) > 0 ){ ?>
                <h2 id="site-contributors">
                    <a class="pilcrow-link" href="#site-contributors">
                        <span class="link">Site contributors</span>
                        <span class="pilcrow">¶</span>
                    </a>
                </h2>


                <div class="row ml-n1 mr-n1">
                <?php

                foreach ( $contributors as $contributor ) {

                  $author_id = $contributor->data->ID;
                  $author_data = get_userdata( intval($author_id ));

                  $nickname = get_the_author_meta( 'nickname', $author_id);
                  $username = get_the_author_meta('user_nicename', $author_id);
                  $description = get_the_author_meta( 'description', $author_id);

                  $first_name = get_the_author_meta( 'nickname', $author_id);
                  $last_name = get_the_author_meta( 'last_name', $author_id);
                  $full_name = '';

                  if( empty($first_name)){
                      $full_name = $last_name;
                  } elseif( empty( $last_name )){
                      $full_name = $first_name;
                  } else {
                      $full_name = "{$first_name} {$last_name}";
                  }

                  $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );
                  $profile_img_url = str_replace( 'http://', 'https://', $profile_img_url );

                  if ( empty( $profile_img_url )){
                    $profile_img_url = get_avatar_url( $author_id, array( 'scheme' => 'https' ) );
                  }

                  $website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
                  $fediverse_handle =  esc_attr( get_the_author_meta( 'fediverse-handle', $author_id ) );
                  $twitter_handle = str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );
                ?>
              <div class="col-xs-6 col-sm-2 col-md-2 text-center">
                <a href="<?php echo get_author_posts_url($author_id, $username ); ?>">
                  <img width="512" height="512" loading="lazy" class="lazy-load rounded mb-2 mt-2 w-100" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>" title="<?php echo $full_name; ?>, click to view profile">
                </a>
              </div>
              <?php }
            } ?>

            <p class="mt-5">And a huge shoutout to <a href="/about/supporters/">our supporters</a> 🙌</p>
          </div>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
