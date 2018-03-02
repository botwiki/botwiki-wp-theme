<?php get_header(); ?>

  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <?php
      if ( is_tax() ){
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
      ?>
        <h1><?php echo "Posts tagged #" . $term->name; ?></h1>
      <?php }
      elseif ( $wp_query->query['post_type'] == 'bot' ) {

        if ( $_GET['networks'] || $_GET['languages'] || $_GET['tags'] ){
          function network_links( $network ){
            return "<a href='" . get_site_url() . "/networks/$network'>#$network</a> ";
          }

          if ( !empty( $_GET['networks'] ) ){
            $networks = implode( ' ', array_map( 'network_links', explode( ',', $_GET['networks'] ) )  );
          }


          function language_links( $language ){
            return "<a href='" . get_site_url() . "/languages/$language'>#$language</a> ";
          }

          if ( !empty( $_GET['languages'] ) ){
            $languages = implode( ' ', array_map( 'language_links', explode( ',', $_GET['languages'] ) )  );
          }

          function tag_links( $tag ){
            return "<a href='" . get_site_url() . "/tag/$tag'>#$tag</a> ";
          }

          if ( !empty( $_GET['tags'] ) ){
            $tags = implode( ' ', array_map( 'tag_links', explode( ',', $_GET['tags'] ) ) );
          }

          ?>
          <h1>Browsing bots tagged <?php echo $networks; echo $languages; echo $tags; ?>...</h1>
        <?php }
        else {?>
          <h1>Browsing all bots...</h1>
        <?php } ?>
      <?php }
      else{ ?>
        <h1>Archives</h1>
      <?php } ?>
			<?php get_template_part( 'loop' ); ?>
			<?php get_template_part( 'pagination' ); ?>
		</div>
	</main>
<?php get_footer(); ?>
