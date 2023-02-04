<?php
  get_header();
  global $helpers;
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <article id="post-404">
        <div class="row mt-5 mb-5">
          <div class="col-sm-12 col-md-6">
            <h1><?php _e( 'Page not found', 'botwiki' ); ?></h1>
            <p>Sorry about that!</p>            
            <ul>
              <li>
                <a href="<?php echo home_url(); ?>">Go back to the main page</a>
              </li>
              <li>
                <a href="<?php echo home_url(); ?>/contribute/">Contribute to Botwiki</a>
              </li>
              <li>
                <a href="<?php echo home_url(); ?>/bots/">Look at some cool bots</a>
              </li>
              <li>
                <a href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Send us an email</a>
              </li>
              <li>
                <a href="https://mastodon.social/@botwiki">Complain about this in public</a>
              </li>
            </ul>
          </div>
          <div class="col-sm-12 col-md-6">
            <p class="text-center">
              <div id="robots" class="mt-5 text-center"></div>
              <!-- <img src="<?php echo get_template_directory_uri(); ?>/images/other/gear.jpg"> -->
            </p>
          </div>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
