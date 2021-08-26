<?php function render_post( $page, $index, $post_id ){ ?>
  <!-- post thumbnail -->
  <?php
    $is_external = false;
    $link_url = get_post_meta( $post_id, 'resource_url', true );
    if ( empty( $link_url ) ){
      $link_url = get_post_permalink( $post_id );
    }
    else{
      $is_external = true;
    }

    $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
    $dominant_color_css = str_replace( '[', 'background-color:rgb( ', $dominant_color );
    $dominant_color_css = str_replace( ']', ' )', $dominant_color_css );

    ?>
    <div class="blog-post-thumbnail post-thumbnail post-thumbnail-<?php echo $page; ?>-<?php echo $index; ?>" style="<?php echo $dominant_color_css; ?>">
      <a href="<?php echo $link_url; ?>" title="<?php get_the_title(); ?>">
        <img class="post-thumbnail-img lazy-load"
             src="<?php echo get_the_post_thumbnail_url( $post_thumbnail_id, 'medium_large' ); ?>"
             data-src="<?php echo get_the_post_thumbnail_url( $post_thumbnail_id, 'medium_large' ); ?>"
        >
        <!-- post title -->
        <p class="post-thumbnail-title">
          <?php
          the_title();

          $post_date = get_the_time('F j, Y');
          $post_date_full = $post_date . ' ' . get_the_time('g:i a');
          $post_author = get_the_author();

          global $coauthors_plus;

          $coauthors = get_coauthors();
          $author_id = $coauthor[0]->data->ID;

          $author_email = esc_attr( get_the_author_meta( 'email', $author_id ) );
          $profile_img_url = esc_attr( get_avatar_url( $author_email, array( 'size' => 22 ) ) );

          if ( class_exists( 'Moment\Moment' ) ){
            $m = new \Moment\Moment($post_date);
            $post_date_ago = $m->fromNow()->getRelative();
            echo " <span class='post-thumbnail-date'><img width=\"22\" height=\"22\" loading=\"lazy\" class=\"lazy-load u-photo rounded-circle mr-2\"  src=\"$profile_img_url\" data-src=\"$profile_img_url\"> $post_author | $post_date_ago</span>";
          }
          ?>
        </p>
        <!-- /post title -->

      </div>
    </a>
  <!-- /post thumbnail -->  
<?php } ?>

<div class="container">
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
      <?php
        $index = $wp_query->current_post + 1;
        $page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

        if ( $page === 1 && $index === 1 ){ ?>
          <div class="row">
            <div class="col-sm-12 col-md-8 text-center m-0 p-1">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page !== 1 && $index === 1 ) { ?>
          <div class="row">
              <div class="col-sm-12 col-md-4 text-center p-1 m-0">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
        <?php } elseif ( $page === 1 && $index === 2 ) { ?>
          <div class="col-sm-12 col-md-4">
            <div class="row">
              <div class="col-sm-12 text-center m-0 p-1">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
          <?php } elseif ( $page === 1 && $index === 3 ) { ?>
              <div class="col-sm-12 text-center m-0 p-1">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
            </div>
          </div>
        </div>
        <?php } elseif ( $page === 1 && $index === 4 ) { ?>
          <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && in_array( $index, [5, 6, 7 ] ) ) { ?>
            <div class="col-sm-12 col-md-6 col-lg-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index > $wp_query->post_count - 8 && $index < $wp_query->post_count   ) { ?>
            <div class="col-sm-12 col-md-6 col-lg-3 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count ) { ?>
            <div class="col-sm-12 col-md-6 col-lg-3 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
          </div>
        <?php } elseif ( $page === 1 && $index < 8 ) { ?>
            <div class="col-sm-12 col-md-6 col-lg-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } else { ?>
          <div class="col-sm-12 col-md-4 text-center p-1 m-0">
            <?php render_post( $page, $index, $post->ID ); ?>
          </div>
        <?php } 
    ?>


  <?php endwhile; ?>
  </div>

<?php else: ?>

  <!-- article -->
  <article>
    <h2><?php _e( 'Sorry, nothing to display.', 'botwiki' ); ?></h2>
  </article>
  <!-- /article -->

<?php endif; ?>
