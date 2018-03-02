<?php get_header(); ?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
			<h1><?php echo sprintf( __( '%s Search Results for ', 'botwiki' ), $wp_query->found_posts ); echo get_search_query(); ?></h1>
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>

      <h3>Not quite what you're looking for?</h3>
      <ul>
        <li>
          <a href="https://github.com/botwiki/botwiki.org">Contribute to Botwiki on GitHub</a>
        </li>
        <li>
          <a href="<?php echo home_url(); ?>/submit-your-bot">Submit a bot to Botwiki</a>
        <ul>
          <li><em>Please be patient while we review the submissions</em> <span title="Thank you!">🙇</span></li>
        </ul>  
        </li>
        <li>
          <a href="https://botmakers.org/">Ask in the Botmakers community</a>
        </li>
        <li>
          <a href="https://twitter.com/botwikidotorg">Find us on Twitter</a>
        </li>
        <li>
          <a href="mailto:stefan@botwiki.org">Send us an email</a>
        </li>
      </ul>
		</div>
	</main>
<?php get_footer(); ?>
