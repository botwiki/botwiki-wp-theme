<?php get_header(); ?>
	<!-- post thumbnail -->
	<?php if ( has_post_thumbnail()) { ?>

	<?php
    $dominant_color  = get_post_meta( get_the_id(), 'dominant_color', true );
    $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
    $dominant_color_css = str_replace(']', ')', $dominant_color_css);
	?>
		<div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php
				if ( !is_front_page() ){
					the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
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
        <div class="col-sm-12 pt-4 pr-3 pl-3 pb-4">
          <?php echo get_search_form( false ); ?>
        </div>
			<?php } ?>
			<br class="clear">
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
