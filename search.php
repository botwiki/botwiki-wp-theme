<?php
  get_header();
  global $helpers;
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
			<h1><?php echo sprintf( __( '%s Search Results for ', 'botwiki' ), $wp_query->found_posts ); echo get_search_query(); ?></h1>

      <form class="form mt-2 mb-5 text-right pr-0 w-100" method="get" action="<?php echo home_url(); ?>" role="search">
        <input id="search-input" class="form-control mr-sm-2" type="search" name="s" placeholder="<?php _e( 'Search...', 'botwiki' ); ?>" aria-label="Search" value="<?php echo get_search_query(); ?>">
        <div id="search-filters" class="mt-3 text-left<?php if ( is_front_page() ) { echo ' d-none'; } ?>">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="search-filters-bots" name="search-filters-options[]" value="bots"<?php if ( in_array('bots', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
            <label class="form-check-label" for="search-filters-bots">Bots</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="search-filters-tutorials" name="search-filters-options[]" value="tutorials"<?php if ( in_array('tutorials', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
            <label class="form-check-label" for="search-filters-tutorials">Tutorials</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="search-filters-resources" name="search-filters-options[]" value="resources"<?php if ( in_array('resources', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
            <label class="form-check-label" for="search-filters-resources">All resources</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="search-filters-everything" name="search-filters-options[]" value="everything"<?php if ( in_array('everything', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
            <label class="form-check-label" for="search-filters-everything">Everything</label>
          </div>
          <div class="text-left mt-3">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" role="button">Search</button>
          </div>

        </div>
      </form>


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
          <a href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Send us an email</a>
        </li>
      </ul>
		</div>
	</main>
<?php get_footer(); ?>
