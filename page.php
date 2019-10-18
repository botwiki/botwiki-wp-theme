<?php
	$post_id = get_the_id(); 
	get_header();

	if ( has_post_thumbnail()) {
    $dominant_color  = get_post_meta( get_the_id(), 'dominant_color', true );
    $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
    $dominant_color_css = str_replace(']', ')', $dominant_color_css);
	?>
		<div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php
				if ( !is_front_page() ){
					$post_thumbnail_id = get_post_thumbnail_id();
					the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load expand-image webfeedsFeaturedVisual', 'title' => get_post($post_thumbnail_id)->post_title ]);
				}
				?>
			</a>
		</div>
	<?php } ?>
	<!-- /post thumbnail -->
	<main role="main" class="container">
  <div class="mt-5">
		<?php if ( !is_front_page() ) { ?>
		<!-- post title -->
		<h1><?php the_title(); ?></h1>
		<!-- /post title -->
		<?php } ?>
  </div>
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php the_content(); ?>
			<?php if (is_front_page()){ ?>
        <div class="col-sm-12 pt-4 pr-5 pl-5 pb-4">
          <?php echo get_search_form( false ); ?>
        </div>
			<?php } ?>
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
					<div class="note">
						<p>This is an interview from the Botwiki Interview series. <a href="/projects/botwiki-interviews/">Read more.</a>
					</p>
				<?php }
			?>
            <?php if ( !is_front_page() ){ ?>
                <h3 id="blog">Latest from our blog</h3>
                <div id="blog-latest-wrapper" class="row list">
                <?php

                $latest_blog_posts = get_posts( array( 
                  'posts_per_page' => 4,
                  'exclude' => array( $post_id ),            
                  'post_type' => 'post',
                  'post_status' => 'publish'
                 ) );

                foreach ( $latest_blog_posts as $blog_post ) {
                    $post_thumbnail_url = get_the_post_thumbnail_url( $blog_post->ID );
                  ?>
                  <div class="col-sm-6 col-md-6 col-lg-3 list-item">
                    <div class="card w-100">
                      <a href="<?php echo get_permalink( $blog_post->ID ); ?>">
                        <img class="lazy-load card-img-top" src="<?php echo $post_thumbnail_url; ?>"  data-src="<?php echo $post_thumbnail_url; ?>" alt="<?php echo $blog_post->post_title; ?>">
                      </a>
                      <div class="card-body">
                        <h5 class="card-title">
                          <a href="<?php echo get_permalink( $blog_post->ID ); ?>"><?php echo $blog_post->post_title; ?></a>  
                        </h5>
                        <p class="card-text"><?php echo $blog_post->post_excerpt; ?></p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                </div>
                <p><a class="btn" href="/blog/">See more</a></p>
            <?php } ?>
		</article>
		<!-- /article -->
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
