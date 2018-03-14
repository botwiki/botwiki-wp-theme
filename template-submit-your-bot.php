<?php
  use ColorThief\ColorThief;
  global $helpers;

  /* Template Name: Submit Your Bot Page Template */
  get_header();
  $post_id = get_the_ID();

  if ( !empty( $_POST ) ){
    if (
      ( isset( $_POST['bot-name'] ) && !empty( $_POST['bot-name'] ) ) &&
      ( isset( $_POST['bot-description'] ) && !empty( $_POST['bot-description'] ) ) &&
      ( isset( $_POST['bot-networks'] ) && !empty( $_POST['bot-networks'] ) ) &&
      ( isset( $_POST['bot-urls'] ) && !empty( $_POST['bot-urls'] ) ) &&
      ( isset( $_POST['bot-tagline'] ) && !empty( $_POST['bot-tagline'] ) ) &&
      ( isset( $_POST['bot-tags'] ) && !empty( $_POST['bot-tags'] ) )
    ) {

      wp_mail( get_the_author_meta('user_email', 1), 'New bot submission', print_r( $_POST, true ) );      

      function add_post_thumbnail( $post_id, $image_path, $description ){
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_path);
        $filename = basename($image_path);
        if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
        else                                    $file = $upload_dir['basedir'] . '/' . $filename;
        file_put_contents($file, $image_data);

        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => $description,
          'post_content' => '',
          'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
        $res2 = set_post_thumbnail( $post_id, $attach_id );
      }


      $bot_authors = array();

      foreach ($_POST['author-names'] as $index => $author_name) {
        if (!empty( $_POST['author-names'][$index] )){
          array_push($bot_authors, trim( $author_name ) . 
                    ( array_key_exists( $index, $_POST['author-urls']  ) && !empty( $_POST['author-urls'][$index] )
                    ? ', ' . trim( $_POST['author-urls'][$index] ) : '' ) );
        }
      }

      $bot_author_info = implode( "\n", $bot_authors );

      $bot_description = trim($_POST['bot-description'] );
      $bot_urls = trim( $_POST['bot-urls'] );

      $post_content = '';

      global $helpers;

      $main_bot_url = str_replace( array( "\n", "\r" ), '', explode("\n", $bot_urls )[0] );      


      $created_by_html_array = array();
      $author_tags = array();

      foreach ($bot_authors as $bot_author) {
        $bot_author_info_arr = explode(',', $bot_author);



        if ( count( $bot_author_info_arr ) === 2 ){
          array_push( $created_by_html_array, '<a href="' . $bot_author_info_arr[1] . '">' . $bot_author_info_arr[0] . '</a>');

          $twitter_username = $helpers->get_twitter_username_from_url( $bot_author_info_arr[1] );

          if ( $twitter_username ){
            array_push( $author_tags , $twitter_username );
          }
        }
        else{
          array_push( $created_by_html_array, $bot_author_info_arr[0]);          
        }
      }




      if ( count( $_POST['bot-networks'] ) == 1 ){

        $post_content .= '<p><a href="' . $main_bot_url . '">' . $_POST['bot-name'] . '</a> is a '
                      . get_term( $_POST['bot-networks'][0], 'network' )->name
                      . " bot created by " . $helpers->join_with_and( $created_by_html_array ) . " that\n\n"
                      . $bot_description . "</p>";
      }
      else{

        function get_network_name( $network_term_id ){
          return get_term( $network_term_id, 'network' )->name;
        }

        $post_content .= '<p><a href="' . $main_bot_url . '">' . $_POST['bot-name'] . '</a> is a bot for '
                      . $helpers->join_with_and( array_map( 'get_network_name', $_POST['bot-networks']) )
                      . " created by " . $helpers->join_with_and( $created_by_html_array ) . " that\n\n"
                      . $bot_description . "</p>";        
      }


      $bot_meta = array();
      $bot_meta['bot_url'] = implode( "\n", explode("\n", $bot_urls ) );
      $bot_meta['bot_source_url'] = trim( $_POST['bot-source-url'] );      
      $bot_meta['bot_tweets'] = trim( $_POST['bot-selected-tweets'] );      

      $screenshotable_url = false;

      foreach ( explode("\n", $bot_urls ) as $bot_url) {
        if ( strpos( $bot_url, 'twitter.com/') ){
          $screenshotable_url = $bot_url;
        }
      }

      if ( $screenshotable_url === false ){
        foreach ( explode("\n", $bot_urls ) as $bot_url) {
          if ( strpos( $bot_url, 'tumblr.com/') ){
            $screenshotable_url = $bot_url;
          }
        }      
      }

      $screenshotable_url = str_replace( array( "\n", "\r" ), '', $screenshotable_url );

      $bot_tags = array();

      foreach ($_POST['bot-tags'] as $bot_tag) {
        array_push( $bot_tags, intval( $bot_tag ) );
      }

      if ( count( $author_tags ) > 0 ){
        $bot_tags = array_merge( $bot_tags, $author_tags );
      }

      if ( isset( $_POST['bot-source-url'] ) && !empty( $_POST['bot-source-url'] ) ){
        array_push( $bot_tags, 'opensource' );
      }


      $post_data = array(
        'post_author' => ( is_user_logged_in() ? get_current_user_id() : 2 ),
        'post_content' => $post_content,
        'post_title' => $_POST['bot-name'],
        'post_excerpt' => $_POST['bot-tagline'],
        'post_status' => 'draft',
        'post_type' => 'bot',
        'post_category' => '',
        'tax_input' => array(
          'post_tag' => $bot_tags
        ),
        'meta_input' => $bot_meta
      );

      $new_post_id = wp_insert_post($post_data);

      update_post_meta($new_post_id, 'bot_author_info', $bot_author_info);

      foreach ($bot_meta as $key => $value) {
        update_post_meta( $new_post_id, $key, $value );
      }

      foreach ($_POST['bot-networks'] as $network) {
        wp_set_object_terms( $new_post_id, intval( $network ), 'network' );
      }

      foreach ($_POST['bot-source-language'] as $language) {
        wp_set_object_terms( $new_post_id, intval( $language ), 'programing_language' );
      }

      if ( $screenshotable_url !== false ){
        $screenshot_data = file_get_contents("https://screenshot-beta.glitch.me/?url=" . $screenshotable_url . "&width=1200&height=685");

        try {
          // TODO: Proper error handling.
          $screenshot_data_json = json_decode( $screenshot_data );
  
          $image_path = ABSPATH . 'temp/' . str_replace( '@', '', $_POST['bot-name'] ) . '.png';

          if ( !file_exists(  ABSPATH . 'temp/' ) ) {
            mkdir( ABSPATH . 'temp/' , 0777, true);
          }

          // if ( !file_exists( $image_path ) ) {
          //   touch( $image_path );
          // }

          $ifp = fopen( $image_path, 'w+' ); 
          fwrite( $ifp, base64_decode( $screenshot_data_json->screenshot->data ) );
          fclose( $ifp ); 

          try {
            $dominant_color = ColorThief::getColor($image_path);
            update_post_meta($new_post_id, 'dominant_color', json_encode($dominant_color));
          } catch (Exception $e) { /* NOOP */ }

          add_post_thumbnail( $new_post_id, $image_path, $bot_description );


          global $wpdb;
          $query = "UPDATE " . $wpdb->prefix . "posts SET post_status='pending' WHERE ID = '" . $new_post_id . "'";
          $wpdb->query($query);

        } catch (Exception $e) {
          /* NOOP */
        }
      }
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
            <p><strong>Thank you for your submission!</strong> Please be patient while we review it 😊</p> 

            <ul class="btn-list mt-4">
              <li>
                <a class="btn" href="<?php echo get_permalink(); ?>">Add one more</a>
              </li>
              <li>
                <a class="btn" href="/bots/#browse-bots-by-category">Browse bots</a>
              </li>
              <li>
                <a class="btn" href="<?php echo get_site_url(); ?>">Back to home page</a>
              </li>
            </ul>
          </article>
        </div>
      </main>    
    <?php }
    else{ ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1><?php the_title(); ?></h1>
            <p>Please <a href="<?php echo get_permalink(); ?>">return to the previous page</a> and make sure all required fields are filled out. <strong>Thank you!</strong></p>
          </article>
        </div>
      </main>

    <?php }
  }
  else { ?>
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/css/select2.min.css' media='all' />

    <main role="main" class="container-fluid m-0 p-0">
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php
          the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
        ?>
      </div>
      <div class="container">
        <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>

          <?php if ( is_user_logged_in() && get_current_user_id() === 1 ) {?>
            <ul class="btn-list">
              <li>
                <button class="btn" id="test" href="#">Test</button>
              </li>
  <!-- 
              <li>
                <a class="btn" href="#">Button</a>
              </li>
              <li>
                <a class="btn" href="#">Button</a>
              </li>
   -->
            </ul>

          <?php } ?>

          <?php echo get_post_field('post_content', $post_id) ?>
          <form id="submit-bot-form" method="post" class="mt-5">
            <div class="author-fields form-row">
              <div class="form-group col-md-6">
                <label for="author-1-name">Author's name</label>
                <input type="text" class="form-control" id="author-1-name" name="author-names[]" placeholder="Author">
              </div>
              <div class="form-group col-md-6">
                <label for="author-1-url">Author's URL</label>
                <input type="url" class="form-control" id="author-1-url" name="author-urls[]" placeholder="https://twitter.com/author">
              </div>
            </div>
            <div class="form-group">
              <button id="add-author-fields" class="btn">Add more authors</button>
            </div>
            <div class="form-group">
              <label for="bot-name">What's your bot's name? <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="bot-name" name="bot-name" placeholder="@coolbot">
            </div>
            <div class="form-group">
              <label for="bot-description">What does your bot do? <sup title="This field is required.">*</sup></label>
              <textarea required class="form-control" id="bot-description" name="bot-description" rows="3" placeholder="This bot makes..."></textarea>
              <small id="bot-description-help" class="form-text text-muted">Include any relevant links to your blog.</small>
            </div>
            <div class="form-group">
              <label for="bot-networks">Where does your bot operate? <sup title="This field is required.">*</sup></label>

              <select required class="form-control js-select2" id="bot-networks" name="bot-networks[]" multiple="multiple" placeholder="Twitter, Tumblr, Slack...">
              <?php
                $networks = get_terms( 'network', array(
                    'hide_empty' => false,
                ) );

                foreach ($networks as $network) { ?>
                  <option value="<?php echo $network->term_id ?>"><?php echo $network->name ?></option>
                <?php }
              ?> 
              </select>

              <small id="bot-networks-help" class="form-text text-muted">List all networks where your bot posts. Missing something?
              <a href="mailto:<?php echo $helpers->get_admin_emails(); ?>" target="_blank">Let us know.</a>
              </small>
            </div>
            <div class="form-group">
              <label for="bot-urls">Where can we see your bot? <sup title="This field is required.">*</sup></label>
              <textarea required class="form-control" id="bot-urls" name="bot-urls" rows="3" placeholder="https://twitter.com/mycoolbot&#x0a;https://mycoolbot.tumblr.com"></textarea>
              <small id="bot-urls-help" class="form-text text-muted">Links to your bot, one on each line, please.</small>
            </div>
            <div id="bot-selected-tweets-field" class="form-group d-none">
              <label for="bot-selected-tweets">Choose two tweets from your bot that you like</label>
              <textarea class="form-control" id="bot-selected-tweets" name="bot-selected-tweets" rows="3" placeholder="https://twitter.com/mycoolbot/status/123456789&#x0a;https://twitter.com/mycoolbot/status/987654321"></textarea>
              <small id="bot-selected-tweets-help" class="form-text text-muted">Paste just the URLs, one on each line, please.</small>
            </div>
            <div class="form-group">
              <label for="bot-tagline">A short tagline <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="bot-tagline" name="bot-tagline" placeholder="A bot that does cool stuff.">
              <small id="bot-tagline-help" class="form-text text-muted">This shows up in search.</small>
            </div>
            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="bot-is-opensource" name="bot-is-opensource">
              <label class="form-check-label" for="bot-is-opensource">This bot is open-source</label>
            </div>
            <div id="bot-source-info" class="mt-3 d-none">
              <div class="form-group">
                <label for="bot-source-url">Link to your bot's source code</label>
                <input type="url" class="form-control" id="bot-source-url" name="bot-source-url" placeholder="https://github.com/me/mycoolbot">
                <small id="bot-source-url-help" class="form-text text-muted">Link to your bot's repo on GitHub, Bitbucket, etc.</small>
              </div>
              <div class="form-group">
                <label for="bot-source-language">What language(s) did you use?</label>

                <select class="form-control js-select2" id="bot-source-language" name="bot-source-language[]" multiple="multiple" placeholder="node.js, Python, Java...">
                <?php
                  $languages = get_terms( 'programing_language', array(
                      'hide_empty' => false,
                  ) );

                  foreach ($languages as $language) { ?>
                    <option value="<?php echo $language->term_id ?>"><?php echo $language->name ?></option>
                  <?php }
                ?> 
                </select>

                <small id="bot-source-language-help" class="form-text text-muted">Yes, node.js is technically a JavaScript framework, bear with us.</small>
              </div>
            </div>
            <div class="form-group">
              <label for="bot-tags">Tag your bot <sup title="This field is required.">*</sup></label>

              <select required class="form-control js-select2" id="bot-tags" name="bot-tags[]" multiple="multiple">
              <?php
                $tags = get_tags( array(
                  'hide_empty' => true
                ) );

                foreach ($tags as $tag) { ?>
                  <option value="<?php echo $tag->term_id ?>"><?php echo $tag->name ?></option>
                <?php }
              ?> 
              </select>

              <small id="bot-tags-help" class="form-text text-muted">Add as many relevant tags as you can, this will make it easier for others to find it. (Don't overdo it!)</small>
            </div>

            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="apply-for-botmaker-badge" name="apply-for-botmaker-badge">
              <label class="form-check-label" for="apply-for-botmaker-badge">Apply for <a target="_blank" href="/botmaker-badges/">Botmaker Badge</a></label>
            </div>
            <div id="botmaker-badge-application" class="mt-3 d-none">
              <div class="form-group">
                <input type="email" class="form-control" id="bot-author-email" name="bot-author-email" placeholder="coolbotauthor@email.com">
                <small id="bot-author-email-help" class="form-text text-muted">We will send your badge to this email. No spam, ever!</small>
              </div>
            </div>


            <button id="bot-form-submit" type="submit" class="btn mt-4">Okay, looks good</button>
          </form>
        </article>
      </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/js/select2.min.js"></script>

  <?php }
?>
<?php get_footer(); ?>
