<?php
  get_header();
  global $helpers;
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
			<h1><?php echo sprintf( __( '%s Search Result(s) for ', 'botwiki' ), $wp_query->found_posts ); echo '<em>'. get_search_query() . '</em>'; ?></h1>
      <?php
        get_search_form(true);
        get_template_part('loop');
        get_template_part('pagination');

      if ($wp_query->found_posts > 0){ ?>      
      <h3>Not quite what you're looking for?</h3>
      <?php }?>
      <ul>
        <li>
          <a title="Add your bot, tutorial, and other botmaking resources to Botwiki" href="<?php echo home_url(); ?>/contribute/">Contribute to Botwiki</a>
          <ul>
            <li><em>Please be patient while we review the submissions</em> <span title="Thank you!">🙇</span></li>
          </ul>  
        </li>
        <li>
          <a href="https://botmakers.org/">Ask in the Botmakers community</a>
        </li>
        <li>
          <a href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Send us an email</a>
        </li>
        <li>
          <a href="https://twitter.com/botwikidotorg">Find us on Twitter</a>
        </li>
      </ul>
		</div>
	</main>
<?php get_footer(); ?>
