<?php function render_post( $index, $post_id ){ ?>
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
    <div class="post-thumbnail post-thumbnail-<?php echo $index;?>">
      <a href="<?php echo $link_url; ?>" title="<?php get_the_title(); ?>">
        <div class="post-thumbnail-img" style="background-image:url('<?php
          echo get_the_post_thumbnail_url( $post_thumbnail_id );
        ?>');">
        </div>
        <!-- post title -->
        <p class="post-thumbnail-title">
          <?php the_title(); ?>
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

        if ( $index === 1 ){ ?>
          <div class="row">
            <div class="col-sm-12 col-md-8 text-center m-0 p-0">
              <?php render_post( $index, $post->ID ); ?>
            </div>
          <?php } elseif ( $index === 2 ) { ?>
            <div class="col-sm-12 col-md-4">
              <div class="row">

                <div class="col-sm-12 text-center m-0 p-0">
                  <?php render_post( $index, $post->ID ); ?>
                </div>
            <?php } elseif ( $index === 3 ) { ?>
                <div class="col-sm-12 text-center m-0 p-0">
                  <?php render_post( $index, $post->ID ); ?>
                </div>
              </div>
            </div>
          </div>
        <?php } elseif ( $index === 4 ) { ?>
          <div class="row">
            <div class="col-sm-12 col-md-4 text-center m-0 p-0">
              <?php render_post( $index, $post->ID ); ?>
            </div>
        <?php } elseif ( $index === $wp_query->post_count ) { ?>
            <div class="col-sm-12 col-md-4 text-center m-0 p-0">
              <?php render_post( $index, $post->ID ); ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="col-sm-12 col-md-4 text-center m-0 p-0">
            <?php render_post( $index, $post->ID ); ?>
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
