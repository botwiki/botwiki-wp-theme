<?php get_header();
  if(is_author()){
    $author_id = get_query_var('author');

    $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
    $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

    if ( empty( $background_img_url )){
      $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', 2 ) );
      $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', 2 ) );    
    }

    $background_img_dominant_color_css = str_replace('[', 'background-color:rgb(', $background_img_dominant_color);
    $background_img_dominant_color_css = str_replace(']', ')', $background_img_dominant_color_css);


    if ( !empty( $background_img_url ) ){ ?>
      <div class="thumbnail-wrapper" style="<?php echo $background_img_dominant_color_css; ?>">
        <img src="<?php echo $background_img_url; ?>">
      </div>
    <?php }
  } ?>


  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
      <?php
        if(is_author()){
          $nickname = get_the_author_meta('nickname', $author_id);
          $username = get_the_author_meta('user_nicename', $author_id);
          $post_type = $wp_query->query['post_type'];
        ?>
          <h1>Browsing <?php echo $post_type; ?>s by <a href="/author/<?php echo $username ?>"><?php echo $nickname ?></a>...</h1>
      <?php } elseif ( is_tax() ){
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


      <?php if(is_author()){

        if ( user_can($author_id, 'administrator') ){  
          $botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
          if ( empty( $botwiki_team_role ) ){
            $botwiki_team_role = "Botwiki team member";      
          }
        }
        else{
          $botwiki_team_role = "Botwiki contributor";    
        }
        $botwiki_profile_page_url = get_site_url() . '/author/' . $username;

        include( locate_template( 'author-card.php', false, false ) );
      } ?>


		</div>
	</main>
<?php get_footer(); ?>
