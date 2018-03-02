<?php get_header(); ?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <h1><?php echo "Posts tagged #" . single_tag_title('', false); ?></h1>
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>
		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
