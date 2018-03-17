<?php
  /* Template Name: Team Page Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $admins = get_users( array(
    'role'         => 'administrator',
    'orderby'      => 'registered',
    'order'        => 'ASC',
    // 'fields'       => 'all',
    'who'          => '',
  ) );

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
        <ul class="btn-list">
          <li>
            <a class="btn" target="_blank" href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Get in touch</a>
            <a class="btn" href="https://twitter.com/botwikidotorg">Tweet at us</a>
          </li>
        </ul>

        <?php
          echo get_post_field( 'post_content', $post_id);
        ?>

        <!-- <h2 id="admins">Site administrators<a class="pilcrow" href="#admins">¶</a></h2> -->

        <?php
          foreach ( $admins as $admin ) {

            $author_id = $admin->data->ID;
            $author_data = get_userdata( intval($author_id ));

            $nickname = get_the_author_meta( 'nickname', $author_id);
            $description = get_the_author_meta( 'description', $author_id);

            $first_name = get_the_author_meta( 'nickname', $author_id);
            $last_name = get_the_author_meta( 'last_name', $author_id);
            $full_name = '';

            if( empty($first_name)){
                $full_name = $last_name;
            } elseif( empty( $last_name )){
                $full_name = $first_name;
            } else {
                $full_name = "{$first_name} {$last_name}";
            }


            ?>
            <div class="media mb-5">
              <img class="mr-3" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
              <div class="media-body">
                <h3 id="<?php echo $nickname; ?>" class="mt-0 mb-1"><?php echo $nickname; ?><a class="pilcrow" href="#<?php echo $nickname; ?>">¶</a></h3>
                <?php echo $description; ?>
              </div>
            </div>



            <?php }

            $contributors = get_users( array(
              'role__not_in' => ['administrator'],
              'exclude'      => [2],
              'orderby'      => 'registered',
              'order'        => 'ASC'
            ) );

            if ( count( $contributors ) > 0 ){ ?>
              <h2 id="site-contributors">Site contributors<a class="pilcrow" href="#site-contributors">¶</a></h2>
              <?php

              foreach ( $contributors as $contributor ) {

                $author_id = $contributor->data->ID;
                $author_data = get_userdata( intval($author_id ));

                $nickname = get_the_author_meta( 'nickname', $author_id);
                $description = get_the_author_meta( 'description', $author_id);

                $first_name = get_the_author_meta( 'nickname', $author_id);
                $last_name = get_the_author_meta( 'last_name', $author_id);
                $full_name = '';

                if( empty($first_name)){
                    $full_name = $last_name;
                } elseif( empty( $last_name )){
                    $full_name = $first_name;
                } else {
                    $full_name = "{$first_name} {$last_name}";
                }
              ?>
              <div class="media mb-5">
                <img class="mr-3" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
                <div class="media-body">
                  <h3 id="<?php echo $nickname; ?>" class="mt-0 mb-1"><?php echo $nickname; ?><a class="pilcrow" href="#<?php echo $nickname; ?>">¶</a></h3>
                  <?php echo $description; ?>
                </div>
              </div>
            <?php }
          } ?>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
