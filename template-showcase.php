<?php
  /* Template Name: Bot Showcase */
  global $helpers;
  get_header();
  $post_id = get_the_ID();
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1 class="post-title text-center"><?php the_title(); ?></h1>
        <div class="post-content">
          <?php
            echo get_post_field( 'post_content', $post_id );

            // TODO: Get a list of specific bots by ID
      
            global $wpdb;
            $result = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_content LIKE '%wp:lazyblock/bot-output%' AND post_type = 'bot' AND post_status = 'publish'" );
              if ( $result ){
                foreach ( $result as $bot ){
                  $blocks = parse_blocks( $bot->post_content );
                  $bot_slug = $bot->post_name;

                  ?>
                  <h2 id="<?php echo $bot_slug; ?>" class="text-center">
                    <a href="/bot/<?php echo $bot_slug; ?>"><?php echo $bot->post_title; ?></a>
                  </h2>

                  <?php
                    foreach ( $blocks as $block ) {
                      if ( $block['blockName'] === 'lazyblock/bot-output' ){
                        $bot_output = $block['attrs']['bot-output'];

                        log_this( array(
                          'post_title' => $bot->post_title,
                          'post_name' => $bot->post_name,
                          'bot_output' => $bot_output
                        ) );

                        echo '<div class="bot-showcase">';
                        echo str_replace( '</body></html>', '', render_block( $block ) );
                        echo '</div>';
                    }
                  }
                }
              }
          ?>

           <ul class="btn-list mt-5">
            <li>
              <a class="btn" href="/bots/">Browse all bots</a>
            </li>
            <li>
              <a class="btn" href="https://twitter.com/botwikidotorg">@botwikidotorg</a>
            </li>
            <li>
              <a class="btn" href="https://twitter.com/newonbotwiki">@newonbotwiki</a>
            </li>
          </ul>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
