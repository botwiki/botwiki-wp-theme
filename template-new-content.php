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
    <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
      <?php
        the_post_thumbnail( 'post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ] );
      ?>
    </div>
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <?php
          echo get_post_field( 'post_content', $post_id);
        ?>

         <ul class="btn-list mt-5">
          <li>
            <a class="btn" href="#bots">New bots</a>
          </li>
          <li>
            <a class="btn" href="#resources">New resources</a>
          </li>
          <li>
            <a class="btn" href="/blog/">Our blog</a>
          </li>
        </ul>

        <h2 id="bots">New bots<a class="pilcrow" href="#bots">¶</a></h2>

        <?php
          global $wp_query;

          $wp_query = new WP_Query( array(
            'post_type'         => 'bot',
            'posts_per_page'    => '5',
            'post_status'       => 'publish',
            'orderby'           => 'publish_date',
            'order'             => 'DESC'
          ) );            

          get_template_part('loop');
        ?>

        <ul class="btn-list">
          <li>
            <a class="btn" href="/bot/">Browse all</a>
          </li>
          <li>
            <a class="btn" href="/random-bot/">Random bot</a>
          </li>
          <li>
            <a class="btn" href="/submit-your-bot/">Add your bot</a>
          </li>
        </ul>

        <h2 id="resources">New resources<a class="pilcrow" href="#resources">¶</a></h2>
        <?php
          global $wp_query;

          $wp_query = new WP_Query( array(
            'post_type'         => 'resource',
            'posts_per_page'    => '5',
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

        <ul class="btn-list">
          <li>
            <a class="btn" href="/?post_type=resource">Browse all</a>
          </li>
        </ul>

      </article>
    </div>
  </main>
<?php get_footer(); ?>
