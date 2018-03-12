<?php
  /* Template Name: Projects Page Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $projects = get_children( array(
    'post_parent' => $post_id,
    'numberposts' => -1,
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

        <?php echo get_post_field( 'post_content', $post_id); ?>
        <ul class="mt-3">
        <?php
          foreach ($projects as $project) {
            $post = get_post( $project->ID ); 
            $slug = $post->post_name;
          ?>
          <li><a href="#<?php echo $slug; ?>"><?php echo $project->post_title; ?></a></li>
          <?php } ?>

        </ul>

        <?php
          error_log( print_r( $projects, true ) );

          foreach ($projects as $project) {
            $post = get_post( $project->ID ); 
            $slug = $post->post_name;
          ?>

            <h3 id="<?php echo $slug; ?>"><?php echo $project->post_title; ?><a class="pilcrow" href="#<?php echo $slug; ?>">¶</a></h3>
            <?php

              $dominant_color  = get_post_meta( $project->ID, 'dominant_color', true );
              $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
              $dominant_color_css = str_replace(']', ')', $dominant_color_css);

            ?>
            <div class="thumbnail-wrapper mb-5" style="<?php echo $dominant_color_css; ?>">
              <?php
                echo get_the_post_thumbnail( $project->ID, 'post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ] );
              ?>
            </div>

            <p><?php echo $project->post_content; ?></p>


          <?php }
        ?>


      </article>
    </div>
  </main>
<?php get_footer(); ?>
