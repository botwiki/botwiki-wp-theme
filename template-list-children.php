<?php
  /* Template Name: List Children Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $children = get_children( array(
    'post_parent' => $post_id,
    'numberposts' => -1,
    'orderby' => 'publish_date',
    'order' => 'ASC',
    'post_status' => 'publish'
  ) );


  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);
?>
  <main role="main" class="container-fluid m-0 p-0">
    <?php if ( get_post_meta( $post_id, 'hide_featured_image', true ) !== 'on' ){ ?>
    <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
      <?php
        $post_thumbnail_id = get_post_thumbnail_id();
        the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load expand-image', 'title' => get_post($post_thumbnail_id)->post_title ]);
      ?>
    </div>
    <?php } ?>
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1 class="post-title text-center"><?php the_title(); ?></h1>
        <div class="post-content">
        <p class="text-center lead mb-5 mt-n3"><?php echo get_the_excerpt(); ?></p>          
        <?php
          echo get_post_field( 'post_content', $post_id);

          foreach ($children as $child) {
            $page_title = str_replace( 'Botwiki Interview: ', '', $child->post_title );
            $post = get_post( $child->ID ); 
            $slug = $post->post_name;
          ?>
            <div class="card pt-5 mt-5 mb-2">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-4 text-center p-l">
                    <!-- post thumbnail -->
                    <a href="<?php echo get_permalink( $child->ID ); ?>">
                    <?php
                      $post_thumbnail_id = get_post_thumbnail_id( $child->ID );
                      the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load mb-5', 'title' => get_post($post_thumbnail_id)->post_title ]);
                    ?>
                    </a>
                    <!-- /post thumbnail -->
                  </div>
                  <div class="col-sm-12 col-md-8">
                    <h2 id="<?php echo $slug; ?>">
                      <a href="<?php echo get_permalink( $child->ID ); ?>"><?php echo $page_title; ?></a>
                    </h2>
                    <p><?php echo get_the_excerpt( $child->ID ); ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
