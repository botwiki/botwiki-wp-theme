<?php get_header(); ?>

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


  <main role="main" class="container-fluid m-0 p-0">
		<!-- section -->
    <div class="container">
			<h1><?php _e( 'Latest posts', 'botwiki' ); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
