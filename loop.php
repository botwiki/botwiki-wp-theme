<div class="container mt-5">
  <div class="row list">
    <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
      <div class="col-sm-12 col-md-6 col-lg-4 list-item">

        <div class="card mb-3 w-100">
        <!-- article -->
        <?php
          $is_external = false;
          $link_url = get_post_meta( $post->ID, 'resource_url', true );
          if ( empty( $link_url ) ){
            $link_url = get_post_permalink( $post->ID );
          }
          else{
            $is_external = true;
          }

          if ( has_post_thumbnail() ){ ?>
            <a href="<?php echo $link_url; ?>" title="<?php  get_the_title(); ?>">
              <?php 
                $post_thumbnail_id = get_post_thumbnail_id();
                echo get_the_post_thumbnail( $post->ID, 'medium', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id, 'small' ),'class' => 'lazy-load card-img-top', 'title' => get_post( $post_thumbnail_id )->post_title ] );
              ?>
            </a>
          <?php } ?>
          <div class="card-body" id="post-<?php the_ID(); ?>">
            <h5 class="card-title"><a href="<?php echo $link_url; ?>" title="<?php get_the_title(); ?>"><?php the_title(); ?></a></h5>
            <p class="card-text">   
            <?php
              bw_excerpt( 'html5wp_index' );
              $post_id = get_the_ID();
              $post_type = get_post_type( $post_id );
              $post_meta = get_post_meta( $post_id );
            ?>
            <!-- post details -->
            <?php if ( is_home() || $post_type == 'tutorial' ){ 
              $post_date = get_the_time( 'F j, Y' );
              $post_date_full = $post_date . ' ' . get_the_time( 'g:i a' );
              $m = new \Moment\Moment( $post_date );
              $post_date_ago = $m->fromNow()->getRelative();
            ?>

            <p><em>Posted <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span>
              by <a href="<?php echo get_author_posts_url( $author_id, get_the_author_meta( 'user_nicename', $author_id ) ); ?>"><?php echo get_the_author_meta( 'nickname', $author_id ); ?></a></em></p>
            <?php } ?>

            </p>
        <!-- /post thumbnail -->
            </div>
          </div>
        </div>
      <?php endwhile; ?>
  <?php else: ?>
    <!-- article -->
    <div class="col-sm-12">
      <article>
        <h2><?php _e( 'Sorry, nothing to display.', 'botwiki' ); ?></h2>
      </article>
    </div>
    <!-- /article -->
  <?php endif; ?>
  </div>
</div>
