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
        <div class="post-thumbnail-img" style="background-image:url('<?php
          echo get_the_post_thumbnail_url( $post_thumbnail_id );
        ?>');">
        </div>
        <!-- post title -->
        <p class="post-thumbnail-title">
          <?php
          the_title();

          $post_date = get_the_time('F j, Y');
          $post_date_full = $post_date . ' ' . get_the_time('g:i a');
          $m = new \Moment\Moment($post_date);
          $post_date_ago = $m->fromNow()->getRelative();
          echo " <span class='post-thumbnail-date'>$post_date_ago</span>";
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
            <div class="col-sm-12 col-md-8 text-center m-0 p-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $page !== 1 && $index === 1 ) { ?>
          <div class="row">
              <div class="col-sm-12 col-md-4 text-center m-0 p-0">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
        <?php } elseif ( $page === 1 && $index === 2 ) { ?>
          <div class="col-sm-12 col-md-4">
            <div class="row">
              <div class="col-sm-12 text-center m-0 p-0">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
          <?php } elseif ( $page === 1 && $index === 3 ) { ?>
              <div class="col-sm-12 text-center m-0 p-0">
                <?php render_post( $page, $index, $post->ID ); ?>
              </div>
            </div>
          </div>
        </div>
        <?php } elseif ( $page === 1 && $index === 4 ) { ?>
          <div class="row">
            <div class="col-sm-12 col-md-4 text-center m-0 p-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $index === $wp_query->post_count ) { ?>
            <div class="col-sm-12 col-md-4 text-center m-0 p-0">
              <?php render_post( $page, $index, $post->ID ); ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="col-sm-12 col-md-4 text-center m-0 p-0">
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
