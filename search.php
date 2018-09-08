<?php
  get_header();
  global $helpers;
  $search_query = get_search_query();
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="container">
			<h1><?php echo sprintf( __( '%s Search Result(s) for ', 'botwiki' ), $wp_query->found_posts ); echo '<em>'. $search_query . '</em>'; ?></h1>
      <?php
 
       $possible_tags = explode( ' ', $search_query );
        $tags_html = array();

        get_search_form( true );

        foreach ( $possible_tags as $tag ) {
          /*
            TODO: Very basic pluralization, consider refactoring in the future.
          */
          if ( substr( $tag, -1) === 's' ){
            $tag_singular = substr( $tag, 0, -1 );;
            $tag_plural = $tag;
          } else {
            $tag_singular = $tag;
            $tag_plural = $tag . 's';
          }

          if ( term_exists( $tag_singular, 'post_tag' ) ){
            $tags_html[] = "<a href='/tag/$tag_singular'>$tag_singular</a> ";
          }

          if ( term_exists( $tag_plural, 'post_tag' ) ){
            $tags_html[] = "<a href='/tag/$tag_plural'>$tag_plural</a> ";
          }
        }
        
        echo '<p class="mb-5 post-tags"><strong class="mr-3">Related tags:</strong>';
        echo( implode( '', $tags_html ) );
        echo '</p>';

        get_template_part( 'loop' );
        get_template_part( 'pagination' );

 

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
