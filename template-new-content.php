<?php
  /* Template Name: What's New? Page Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);
?>
  <main role="main" class="container-fluid m-0 p-0">
    <h1 class="text-center post-title mt-5"><?php the_title(); ?></h1>

    <!-- <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>"> -->
      <?php
        // $post_thumbnail_id = get_post_thumbnail_id();
        // the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load expand-image', 'title' => get_post($post_thumbnail_id)->post_title ]);
      ?>
    <!-- </div> -->
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <div class="post-content">
           <ul class="btn-list mt-5 text-sm-left text-md-center">
            <li>
              <a class="btn" href="#blog">Botwiki blog</a>
            </li>
            <li>
              <a class="btn" href="#bots">New bots</a>
            </li>
            <li>
              <a class="btn" href="#resources">New resources</a>
            </li>
            <li>
              <a class="btn" href="https://twitter.com/newonbotwiki">@newonbotwiki</a>
            </li>
          </ul>        

          <?php
            echo get_post_field( 'post_content', $post_id);
          ?>
          <h2 id="blog">
              <a class="pilcrow-link" href="#blog">
                  <span class="link">Botwiki blog</span>
                  <span class="pilcrow">¶</span>
              </a>
          </h2>

          <?php
            global $wp_query;

            $wp_query = new WP_Query( array(
              'post_type'         => 'post',
              'posts_per_page'    => '6',
              'post_status'       => 'publish',
              'orderby'           => 'publish_date',
              'order'             => 'DESC'
            ) );            

            get_template_part('loop');
          ?>

          <ul class="btn-list mt-0">
            <li>
              <a class="btn" href="/blog/">Visit blog</a>
            </li>
          </ul>

          <h2 id="bots">
              <a class="pilcrow-link" href="#bots">
                  <span class="link">New bots</span>
                  <span class="pilcrow">¶</span>
              </a>
          </h2>

          <?php
            global $wp_query;

            $wp_query = new WP_Query( array(
              'post_type'         => 'bot',
              'posts_per_page'    => '6',
              'post_status'       => 'publish',
              'meta_key'          => 'bot_is_featured',
              'meta_value'        => 'on',
              'meta_compare'      => 'IN',
              'orderby'           => 'publish_date',
              'order'             => 'DESC',
            ) );            

            get_template_part('loop');
          ?>

          <ul class="btn-list mt-0">
            <li>
              <a class="btn" href="/bots/">Browse all</a>
            </li>
            <li>
              <a class="btn" href="/random-bot/">Random bot</a>
            </li>
            <li>
              <a class="btn" href="/submit-your-bot/">Add your bot</a>
            </li>
          </ul>

          <h2 id="resources">
              <a class="pilcrow-link" href="#resources">
                  <span class="link">New resources</span>
                  <span class="pilcrow">¶</span>
              </a>
          </h2>

          <?php
            global $wp_query;

            $wp_query = new WP_Query( array(
              'post_type'         => 'resource',
              'posts_per_page'    => '6',
              'post_status'       => 'publish',
              'orderby'           => 'publish_date',
              'order'             => 'DESC',
              // 'tax_query' => array(
              //     array(
              //         'taxonomy' => 'resource_type',
              //         'field'    => 'slug',
              //         'terms'    => array('tutorial'),
              //         'operator' => 'NOT IN'
              //     )
              // )
            ) );            

            get_template_part('loop');
           ?>

          <ul class="btn-list mt-0">
            <li>
              <a class="btn" href="/resources/">Browse all</a>
            </li>
            <li>
              <a class="btn" href="/submit-resource/">Add your resource</a>
            </li>
          </ul>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
