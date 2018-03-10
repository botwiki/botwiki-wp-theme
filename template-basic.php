<?php
  /* Template Name: Basic Page Template */
  get_header();
  $post_id = get_the_ID();
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
      <?php
        the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
      ?>
    </div>
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <ul class="btn-list">
          <li>
            <a class="btn" href="#">Button</a>
          </li>
        </ul>

        <?php echo get_post_field('post_content', $post_id) ?>

      </article>
    </div>
  </main>
<?php get_footer(); ?>
