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

    ?>
    <div class="post-thumbnail post-thumbnail-<?php echo $page; ?>-<?php echo $index; ?>">
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

          $m = new \Moment\Moment($post_date);
          $post_date_ago = $m->fromNow()->getRelative();
          echo " <span class='post-thumbnail-date'>$post_date_ago by $post_author</span>";
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
            <div class="col-sm-12 col-md-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === 5 ) { ?>
            <div class="col-sm-12 col-md-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === 6 ) { ?>
            <div class="col-sm-12 col-md-5 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === 7 ) { ?>
            <div class="col-sm-12 col-md-3 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count ) { ?>
            <div class="col-sm-12 col-md-4 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
          </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 6 ) { ?>
            <div class="col-sm-12 col-md-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 5 ) { ?>
            <div class="col-sm-12 col-md-6 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 4 ) { ?>
            <div class="col-sm-12 col-md-7 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 3 ) { ?>
            <div class="col-sm-12 col-md-5 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 2 ) { ?>
            <div class="col-sm-12 col-md-4 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index === $wp_query->post_count - 1 ) { ?>
            <div class="col-sm-12 col-md-4 text-center p-1 m-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page === 1 && $index < 8 ) { ?>
            <div class="col-sm-12 col-md-6 text-center p-1 m-0">
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
