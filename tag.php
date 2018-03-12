<?php
  get_header();
  global $wp_query;
  $tags = preg_split( "/(\+|,)/", $wp_query->query['tag'] );

  $tag_links = array();

  foreach ($tags as $tag) {
    $tag_links[] = '<a href="/tag/' . $tag . '">#' . $tag . '</a>';
  }
?>

  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <h1><?php echo "Posts tagged " . implode( ' ', $tag_links ); ?></h1>
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>
		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
