<?php get_header(); ?>

  <main role="main" class="container">
		<!-- section -->
    <div class="mt-5">

			<h1><?php _e( 'Categories for ', 'botwiki' ); single_cat_title(); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</div>
		<!-- /section -->
	</main>


<?php get_footer(); ?>
