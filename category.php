<?php get_header(); ?>
  <main role="main" class="container">
		<!-- section -->
    <div class="mt-5">
			<h1 class="post-title"><?php _e( 'Posts labeled ', 'botwiki' ); single_cat_title(); ?></h1>
            <div class="post-content">
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>
            </div>
		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
