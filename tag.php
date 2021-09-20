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
      <h1><?php echo "All posts tagged " . implode( ' ', $tag_links ); ?></h1>
      <?php if ( in_array( 'opensource', $tags ) ){ ?>
        <ul class="btn-list">
          <li><a class="btn" href="/bot/?opensource=true/">Browse open-source bots</a></li>
        </ul>
      <?php } elseif ( in_array( 'twitterbot', $tags ) ) { ?>
        <ul class="btn-list">
          <li><a class="btn" href="/bots/twitterbots/">Browse Twitter bots</a></li>
        </ul>
      <?php } ?>
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>
		</div>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
