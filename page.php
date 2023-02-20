<?php
	$post_id = get_the_id(); 
	get_header();
?>

	<main role="main" class="container">
  <div class="mt-5">
		<?php if ( !is_front_page() ) { ?>
		<!-- post title -->
		<h1 class="text-center post-title mt-5"><span<?php
            $dominant_color = get_post_meta( $post_id, 'dominant_color', true );
            if ( $dominant_color ){
              $rgb = json_decode( $dominant_color );
              $contrast = sqrt(
                $rgb[0] * $rgb[0] * .241 +
                $rgb[1] * $rgb[1] * .691 +
                $rgb[2] * $rgb[2] * .068
              );

              $lum = ($rgb[0]+$rgb[0]+$rgb[2]+$rgb[1]+$rgb[1]+$rgb[1])/6;

              ?>
              style="box-shadow: inset 0 -20px 0 0 rgba(<?php echo $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . log( log10( $contrast ) ) ?>)"
            <?php }

        ?>><?php the_title(); ?></span></h1>
		<!-- /post title -->
		<?php } ?>
  </div>

  <?php
    if ( has_post_thumbnail()) {
      $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
      $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
      $dominant_color_css = str_replace(']', ')', $dominant_color_css);
    ?>
        <?php
        if ( !is_front_page() && get_post_meta( $post_id, 'hide_featured_image', true ) !== 'on' ){ ?>
          <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
            <a href="<?php echo get_the_post_thumbnail_url( $post_id ); ?>">
              <?php
                $post_thumbnail_id = get_post_thumbnail_id();
                the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load expand-image webfeedsFeaturedVisual', 'title' => get_post($post_thumbnail_id)->post_title ]);
              ?>
              <div class="image-border-shadow"></div>
            </a>
          </div>
      <?php } ?>
  <?php } ?>
  <!-- /post thumbnail -->
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			<?php if ( is_front_page()){ ?>
                <div id="menu-wrapper" class="menu-visible container-fluid pl-0 pr-0">
                    <?php botwiki_site_nav(); ?>
                </div>
            <?php } else { ?>
            <!-- article -->
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-content' ); ?>>
            <?php the_content(); ?>

            <br class="clear">
            <?php
                if ( strpos( get_the_title(), 'Botwiki Interview:' ) !== false ){

                    $post_date = get_the_time('F j, Y');
                    $post_date_full = $post_date . ' ' . get_the_time('g:i a');
                    $m = new \Moment\Moment($post_date);
                    $post_date_ago = $m->fromNow()->getRelative();
                ?>
                    <p class="mt-5"><em>Posted <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span>
                        by <a href="<?php echo get_author_posts_url($author_id, get_the_author_meta('nickname', $author_id)); ?>"><?php echo get_the_author_meta('nickname', $author_id); ?></a></em>.</p>
                    <p class="post-tags mt-5 mb-5"><?php the_tags('', ' ', '<br>'); // Separated by commas with a line break at the end ?></p>


                      <div class="container mt-5 pl-0 pr-0">
                        <div class="card mt-4 mb-4">
                          <div class="card-body mt-4">
                            <div class="row">
                              <div class="col-sm-12">
                                <h3 class="mt-0 mb-3 d-inline">Botwiki Interview</h3>
                                <p class="mt-3">This is an interview from the <strong>Botwiki Interview</strong> series.</p>
                                <ul class="btn-list mt-2 mb-2">
                                  <li>
                                    <a class="btn" title="Read more Botwiki interviews" href="/projects/botwiki-interviews/">Read more</a> 
                                  </li> 
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> 
                <?php }
            ?>
            <?php if ( get_post_field( 'post_name', wp_get_post_parent_id( $post_id ) ) === 'projects' ){ ?>
                <h3 id="more-projects">More projects</h3>
                <div id="more-projects-wrapper" class="row list">
                <?php

                $latest_projects = get_children( array(
                  'post_parent' => wp_get_post_parent_id( $post_id ),
                  'posts_per_page' => 4,
                  'exclude' => array( $post_id ),            
                  'post_type' => 'page',
                  'post_status' => 'publish'
                 ) );

                foreach ( $latest_projects as $project ) {
                    $post_thumbnail_url = get_the_post_thumbnail_url( $project->ID, 'large' );
                    $post_thumbnail_url_full = get_the_post_thumbnail_url( $project->ID );
                    $dominant_color  = get_post_meta( $project->ID, 'dominant_color', true );
                    $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
                    $dominant_color_css = str_replace(']', ')', $dominant_color_css);

                  ?>
                  <div class="col-sm-6 col-md-6 col-lg-3 list-item">
                    <div class="card w-100">
                      <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                        <a href="<?php echo get_permalink( $project->ID ); ?>">
                          <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>"  data-src="<?php echo $post_thumbnail_url_full; ?>" alt="<?php echo $blog_post->post_title; ?>">
                        </a>
                      </div>
                      <div class="card-body">
                        <h5 class="card-title">
                          <a class="stretched-link" href="<?php echo get_permalink( $project->ID ); ?>"><?php echo $project->post_title; ?></a>  
                        </h5>
                        <p class="card-text"><?php echo $project->post_excerpt; ?></p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                </div>
                <p><a class="btn" href="/projects/">Browse all</a></p>
            <?php } ?>
            <?php if ( !is_front_page() ){ ?>
                <h3 id="blog">Latest from the blog</h3>
                <div id="blog-latest-wrapper" class="row list">
                <?php

                $latest_blog_posts = get_posts( array( 
                  'posts_per_page' => 4,
                  'exclude' => array( $post_id ),            
                  'post_type' => 'post',
                  'post_status' => 'publish'
                 ) );

                foreach ( $latest_blog_posts as $blog_post ) {
                    $post_thumbnail_url = get_the_post_thumbnail_url( $blog_post->ID, 'large' );
                    $post_thumbnail_url_full = get_the_post_thumbnail_url( $blog_post->ID );
                    $dominant_color  = get_post_meta( $blog_post->ID, 'dominant_color', true );
                    $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
                    $dominant_color_css = str_replace(']', ')', $dominant_color_css);

                  ?>
                  <div class="col-sm-6 col-md-6 col-lg-3 list-item">
                    <div class="card w-100">
                      <div class="overflow-hidden" style="<?php echo $dominant_color_css; ?>">
                        <a href="<?php echo get_permalink( $blog_post->ID ); ?>">
                          <img loading="lazy" class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>"  data-src="<?php echo $post_thumbnail_url_full; ?>" alt="<?php echo $blog_post->post_title; ?>">
                        </a>
                      </div>
                      <div class="card-body">
                        <h5 class="card-title">
                          <a class="stretched-link" href="<?php echo get_permalink( $blog_post->ID ); ?>"><?php echo $blog_post->post_title; ?></a>  
                        </h5>
                        <p class="card-text"><?php echo $blog_post->post_excerpt; ?></p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                </div>
                <p><a class="btn" href="/blog/">Visit the blog</a></p>                
            <?php } ?>
        </article>
        <!-- /article -->
        <?php if ( !is_front_page() ){
              include( locate_template( 'support-botwiki.php', false, false ) );
        } ?>
      <?php } ?>
	<?php endwhile; ?>
	<?php else: ?>
		<!-- article -->
		<article>
			<h2><?php _e( 'Sorry, nothing to display.', 'botwiki' ); ?></h2>
		</article>
		<!-- /article -->
	<?php endif; ?>
  </main>
<?php get_footer(); ?>
