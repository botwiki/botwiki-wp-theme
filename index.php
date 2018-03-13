<?php get_header(); ?>

  <main role="main" class="container-fluid m-0 p-0">
		<!-- section -->
    <div class="container">


			<h1><?php _e( 'Latest Posts', 'botwiki' ); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
