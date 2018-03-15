<?php
  /* Template Name: List Children Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $children = get_children( array(
    'post_parent' => $post_id,
    'numberposts' => -1,
    'order' => 'ASC',
    'post_status' => 'publish'
  ) );


  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
      <?php
        the_post_thumbnail( 'post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ] );
      ?>
    </div>
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>

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
                  <div class="col-sm-12 col-md-4 text-center">
                    <!-- post thumbnail -->
                    <?php
                      echo get_the_post_thumbnail( $child->ID, 'post-thumbnail', ['class' => 'lazy-load mb-5', 'title' => get_post(get_post_thumbnail_id( $child->ID ))->post_title ] );            
                    ?>
                    <!-- /post thumbnail -->
                  </div>
                  <div class="col-sm-12 col-md-8">
                    <h2 id="<?php echo $slug; ?>"><?php echo $page_title; ?></h2>
                    <p><?php echo get_the_excerpt( $child->ID ); ?></p>
                    <p><a class="btn" href="<?php echo get_permalink( $child->ID ); ?>">Read more</a></p>
                  </div>
                </div>
              </div>
            </div>
          <?php }
        ?>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
