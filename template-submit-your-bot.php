<?php
  use ColorThief\ColorThief;
  global $helpers;

  /* Template Name: Submit Your Bot Page Template */
  get_header();
  $post_id = get_the_ID();

  $bot_name_val = !empty( $_REQUEST['bot-name'] ) ? $_REQUEST['bot-name'] : '';
  $bot_urls_val = !empty( $_REQUEST['bot-urls'] ) ? $_REQUEST['bot-urls'][0] : '';
  $bot_description_val = !empty( $_REQUEST['bot-description'] ) ? $_REQUEST['bot-description'] : '';
  $bot_tagline_val = !empty( $_REQUEST['bot-tagline'] ) ? $_REQUEST['bot-tagline'] : '';

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace( '[', 'background-color:rgb( ', $dominant_color );
  $dominant_color_css = str_replace( ']', ' )', $dominant_color_css );

  if ( !empty( $_POST ) ){
    // error_log( print_r( $_POST, true ) );

    if ( 
      ( isset( $_POST['bot-name'] ) && !empty( $_POST['bot-name'] ) ) &&
      ( isset( $_POST['bot-description'] ) && !empty( $_POST['bot-description'] ) ) &&
      ( isset( $_POST['bot-networks'] ) && !empty( $_POST['bot-networks'] ) ) &&
      ( isset( $_POST['bot-urls'] ) && !empty( $_POST['bot-urls'] ) ) &&
      ( isset( $_POST['bot-tagline'] ) && !empty( $_POST['bot-tagline'] ) ) &&
      ( isset( $_POST['bot-tags'] ) && !empty( $_POST['bot-tags'] ) )
    ) {

      if ( get_current_user_id() !== 1 ){
        if ( 
          ( isset( $_POST['apply-for-botmaker-badge'] ) && !empty( $_POST['apply-for-botmaker-badge'] ) ) &&
          ( isset( $_POST['bot-author-email'] ) && !empty( $_POST['bot-author-email'] ) )
        ){
          $email_subject = 'Badge request and new bot submission';         
        }
        else{
          $email_subject = 'New bot submission';          
        }
        wp_mail( get_the_author_meta( 'user_email', 1 ), $email_subject, print_r( $_POST, true ) );      
      }

      function add_post_thumbnail( $post_id, $image_path, $description ){
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents( $image_path );
        $filename = basename( $image_path );

        if ( wp_mkdir_p( $upload_dir['path'] ) ){
          $file = $upload_dir['path'] . '/' . $filename;
        }
        else{
          $file = $upload_dir['basedir'] . '/' . $filename;
        }

        file_put_contents( $file, $image_data );
        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array( 
          'post_mime_type' => $wp_filetype['type'],
          // 'post_title' => $description,
          'post_title' => $_POST['bot-name'],
          'post_content' => '',
          'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
        $res2 = set_post_thumbnail( $post_id, $attach_id );
      }

      $bot_authors = array();

      if ( !empty( $_POST['author-names'] ) ){
        foreach ( $_POST['author-names'] as $index => $author_name ) {
          if ( !empty( $_POST['author-names'][$index] ) ){
            array_push( $bot_authors, trim( $author_name ) . 
                      ( array_key_exists( $index, $_POST['author-urls']  ) && !empty( $_POST['author-urls'][$index] )
                      ? ', ' . trim( $_POST['author-urls'][$index] ) : '' ) );
          }
        }
      }

      $bot_author_info = implode( "\n", $bot_authors );

      $bot_description = trim( $_POST['bot-description'] );
      $bot_urls = $_POST['bot-urls'];

      $post_content = '<!-- wp:paragraph -->';

      global $helpers;

      $main_bot_url = $bot_urls[0];


      $created_by_html_array = array();
      $author_tags = array();

      foreach ( $bot_authors as $bot_author ) {
        $bot_author_info_arr = explode( ',', $bot_author );

        if ( count( $bot_author_info_arr ) === 2 ){
          array_push( $created_by_html_array, '<a href="' . trim( $bot_author_info_arr[1] ) . '">' . trim( $bot_author_info_arr[0] ) . '</a>' );

          $author_username = $helpers->get_username_from_url( $bot_author_info_arr[1] );

          if ( $author_username ){
            array_push( $author_tags , $author_username );
          }
        }
        else{
          array_push( $created_by_html_array, $bot_author_info_arr[0] );          
        }
      }

      if ( count( $_POST['bot-networks'] ) == 1 ){

        $post_content .= '<p><a href="' . $main_bot_url . '">' . trim( $_POST['bot-name'] ) . '</a> is a '
                      . get_term_by( 'slug', $_POST['bot-networks'][0], 'network' )->name
                      . " bot" 
                      . ( count( $bot_authors ) > 0 ? " created by " : "" )
                      . $helpers->join_with_and( $created_by_html_array ) . " that\n\n"
                      . $bot_description . "</p>";
      }
      else{

        function get_network_name( $network_term_slug ){
          return get_term_by( 'slug', $network_term_slug, 'network' )->name;
        }

        $post_content .= '<p><a href="' . $main_bot_url . '">' . $_POST['bot-name'] . '</a> is a '
                      . $helpers->join_with_and( array_map( 'get_network_name', $_POST['bot-networks'] ) )
                      . ( count( $bot_authors ) > 0 ? " bot created by " : "" )
                      . $helpers->join_with_and( $created_by_html_array ) . " that\n\n"
                      . $bot_description . "</p>";        
      }

      $post_content = str_replace(
        array(
          '</p></p>',
          '</p> <p>'
        ),
        array(
          '</p>',
          '</p>'
        ),
        $post_content
      );

      $bot_meta = array();
      $bot_meta['bot_is_featured'] = 'on';
      $bot_meta['bot_url'] = trim( implode( "\n", $bot_urls ) );
      $bot_meta['bot_source_url'] = trim( $_POST['bot-source-url'] );      
      $bot_meta['bot_tweets'] = trim( $_POST['bot-selected-tweets'] );      
      $bot_meta['bot_author_email'] = trim( $_POST['bot-author-email'] );

      $screenshotable_url = $helpers->get_screenshotable_url( $bot_urls );

      if ( $screenshotable_url === false ){
        $screenshotable_url = $main_bot_url;
      }

      $screenshotable_url = trim( str_replace( array( "\n", "\r" ), '', $screenshotable_url ) );
      $bot_tags = array();

      foreach ( $_POST['bot-tags'] as $bot_tag ) {
        array_push( $bot_tags, $bot_tag );
      }

      foreach ( $bot_urls as $bot_url ) {
        if ( strpos( $bot_url, 'botsin.space/' ) ){
          array_push( $bot_tags, 'botsin.space' );
          break;
        }
      }

      if ( isset( $_POST['bot-is-interactive'] ) ){
        array_push( $bot_tags, 'interactive' );        
      }

      if ( is_user_logged_in() && isset( $_POST['disassociate-author-input'] ) && $_POST['disassociate-author-input'] === 'false' ){
        $twitter_handle = str_replace( '@', '', esc_attr( get_the_author_meta( 'twitter-handle', get_current_user_id() ) ) );

        if ( !empty( $twitter_handle ) ){
          array_push( $bot_tags, $twitter_handle );
        }
      }

      if ( count( $author_tags ) > 0 ){
        $bot_tags = array_merge( $bot_tags, $author_tags );
      }

      // if ( isset( $_POST['bot-source-url'] ) && !empty( $_POST['bot-source-url'] ) ){
      //   array_push( $bot_tags, 'opensource' );
      // }

      $post_data = array( 
        'post_author' => ( ( is_user_logged_in() && isset( $_POST['disassociate-author-input'] ) && $_POST['disassociate-author-input'] === 'false' ) ? get_current_user_id() : 2 ),
        // 'post_content' => $post_content,
        'post_content' => $post_content . '<!-- /wp:paragraph -->',
        'post_title' => $_POST['bot-name'],
        'post_excerpt' => $_POST['bot-tagline'],
        'post_status' => 'draft',
        'post_type' => 'bot',
        'post_category' => '',
        // 'tax_input' => array( 
        //   'post_tag' => $bot_tags
        // ),
        'meta_input' => $bot_meta
      );

      $new_post_id = wp_insert_post( $post_data );

      wp_set_object_terms( $new_post_id, $bot_tags, 'post_tag' );
      update_post_meta( $new_post_id, 'bot_author_info', $bot_author_info );

      foreach ( $bot_meta as $key => $value ) {
        update_post_meta( $new_post_id, $key, $value );
      }

      if ( count( array_intersect( $_POST['bot-networks'], array( 'mastodon', 'gnu-social' ) ) ) > 0 ){
        array_push( $_POST['bot-networks'], 'fediverse' );      
      }

      wp_set_object_terms( $new_post_id, $_POST['bot-networks'], 'network' );
      wp_set_object_terms( $new_post_id, $_POST['bot-source-language'], 'programing_language' );

      if ( $screenshotable_url !== false ){
        try {
          $screenshot = $helpers->make_screenshot( array(
            'url' => $screenshotable_url,
            'file_name' => trim( $_POST['bot-name'] )
          ) );

          try {
            $dominant_color = ColorThief::getColor( $screenshot['image_path'] );
            update_post_meta( $new_post_id, 'dominant_color', json_encode( $dominant_color ) );
          } catch (Exception $e) {
            /* noop */            
          }

          add_post_thumbnail( $new_post_id, $screenshot['image_path'], $bot_description );

          if ( !is_user_logged_in() || $_POST['disassociate-author-input'] !== 'false' ){
            global $wpdb;
            $query = "UPDATE " . $wpdb->prefix . "posts SET post_status='pending' WHERE ID = '" . $new_post_id . "'";
            $wpdb->query( $query );
          }

        } catch ( Exception $e ) {
          // TODO: Proper error handling.
          log_this( $e->getMessage() );
        }
      }
    ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            $post_thumbnail_id = get_post_thumbnail_id();
            the_post_thumbnail( 'post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ] );
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1>Thank you</h1>
            <?php if ( ( is_user_logged_in() && $_POST['disassociate-author-input'] === 'false' ) ){ ?>
              <p><strong>Thank you!</strong> Make sure to <a href="/wp-admin/edit.php?post_status=draft&post_type=bot&author=<?php echo get_current_user_id(); ?>">submit your bots for review</a>.</p> 
            <?php } else { ?>
              <p><strong>Thank you for your submission!</strong> Please be patient while we review it 😊</p> 
              <p>You can <a href="https://twitter.com/newonbotwiki" target="_blank">follow @newonbotwiki</a> to see when the site gets updated.</p>
            <?php } ?>
            <ul class="btn-list mt-4">
              <li>
                <a class="btn" href="<?php echo get_permalink(); ?>">Add one more</a>
              </li>
              <li>
                <a class="btn" href="/submit-resource/">Add a resource</a>
              </li>
              <li>
                <a class="btn" href="/bots/#browse-bots-by-category">Browse bots</a>
              </li>
              <li>
                <a class="btn" href="<?php echo get_site_url(); ?>">Back to home page</a>
              </li>
            </ul>
            <div class="row mt-5">
                <div class="col-sm-12 col-md-6">
                  <blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">We are a diverse group of enthusiasts who make and share fun and creative online bots. Come join us! 😊 <a href="https://t.co/4FH6OgVuCG">https://t.co/4FH6OgVuCG</a></p>&mdash; A friendly encyclopedia of 🤖💻💾 ( @botwikidotorg ) <a href="https://twitter.com/botwikidotorg/status/984405698103726082?ref_src=twsrc%5Etfw">April 12, 2018</a></blockquote>
                </div>
                <div class="col-sm-12 col-md-6">
                  <blockquote class="twitter-tweet"><p lang="en" dir="ltr">New bot was added to Botwiki! <a href="https://t.co/RUepf5f8Im">https://t.co/RUepf5f8Im</a></p>&mdash; New on Botwiki ✨ (@newonbotwiki) <a href="https://twitter.com/newonbotwiki/status/1206599872016719877?ref_src=twsrc%5Etfw">December 16, 2019</a></blockquote>                    
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
          </article>
        </div>
      </main>    
    <?php }
    else{ ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            $post_thumbnail_id = get_post_thumbnail_id();
            the_post_thumbnail( 'post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ] );
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
    <link rel='stylesheet' href='<?php bloginfo( 'template_directory' ) ?>/libs/medium-editor/5.23.3/css/medium-editor.css' media='all' />
    <link rel='stylesheet' href='<?php bloginfo( 'template_directory' ) ?>/libs/medium-editor/5.23.3/css/themes/default.css' media='all' />
    <main role="main" class="container-fluid m-0 p-0">
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php
          $post_thumbnail_id = get_post_thumbnail_id();
          the_post_thumbnail( 'post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ] );
        ?>
      </div>
      <div class="container">
        <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
          <ul class="btn-list">
            <li>
              <a class="btn" href="https://twitter.com/newonbotwiki" target="_blank">Follow @newonbotwiki</a>
            </li>
            <?php if ( is_user_logged_in() && get_current_user_id() === 1 ) {?>
            <li>
              <button class="btn" id="test" href="#">Test submission</button>
            </li>
            <?php } ?>
          </ul>
          <?php echo do_shortcode( get_post_field( 'post_content', $post_id ) ); ?>
          <form id="submit-bot-form" method="post" class="mt-5">
          <?php if ( is_user_logged_in() ) {
            $author_id = get_current_user_id();
            $username = get_the_author_meta( 'user_nicename', get_current_user_id() );
            $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

            if ( empty( $profile_img_url ) ){
              $profile_img_url = get_avatar_url( $author_id, array( 'size' => 360 ) );
            }
            $botwiki_profile_page_url = get_site_url() . '/author/' . $username;
          ?>
            <div id="logged-in-author" class="card mb-5">
              <div class="card-body">
                <div class="container">
                  <div class="row">
                    <div class="col-sm-2">
                      <a href="<?php echo $botwiki_profile_page_url; ?>">
                        <img class="lazy-load img-thumbnail" style="width: 100%; height: 100%; max-width: 100px; max-height: 100px;" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>">
                      </a>
                    </div>
                    <div class="col-sm-10">
                      <h5 class="card-title mt-1">You are logged in</h5>
                      <p class="card-text">This bot will be added to <a href="<?php echo $botwiki_profile_page_url; ?>">your profile</a>.</p>
                      <p>
                        <a id="disassociate-author" href="#"><em>This is not my bot.</em></a>
                        <input type="hidden" name="disassociate-author-input" value="false">
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } else { ?>
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
          <?php } ?>
            <div class="form-group">
              <button id="add-author-fields" class="btn">Add more authors</button>
            </div>
            <div class="form-group">
              <label for="bot-name">What's your bot's name? <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="bot-name" name="bot-name" placeholder="@coolbot" value="<?php echo $bot_name_val; ?>">
            </div>
            <div class="bot-info-fields form-row">
              <div class="form-group col-md-12 mb-1">
                <label>Where can we see your bot?<sup title="This field is required.">*</sup></label>
              </div>
              <div class="form-group col-md-6">
                <label for="bot-info-1-network">Network</label>
                <select required class="form-control js-select2" id="bot-info-1-network" name="bot-networks[]" placeholder="Twitter, Tumblr, Slack,..." data-minimum-input-length="0" data-tags="true">
                <?php
                  $networks = get_terms( 'network', array( 
                      'hide_empty' => false,
                  ) );

                  foreach ( $networks as $network ) { ?>
                    <option <?php if ( $network->name === 'Twitter' ){ echo 'selected '; } ?> value="<?php echo $network->slug ?>"><?php echo $network->name ?></option>
                  <?php }
                ?> 
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="bot-info-1-url">URL</label>
                <input required type="url" class="form-control" id="bot-info-1-url" name="bot-urls[]" placeholder="https://twitter.com/onecoolbot" value="<?php echo $bot_urls_val; ?>">
              </div>
            </div>
            <div class="form-group">
              <button id="add-bot-info-fields" class="btn">Add more networks</button>
            </div>            
            <div id="bot-submitted-notice" class="d-none alert alert-warning" role="alert">
              <p><strong>Heads up, it looks like someone already added this bot.</strong></p>
              <div id="bot-submitted-output"></div>
              <p>If you are the creator of the bot and would like to update your bot's description or apply for the <a href="/projects/botmaker-badges/" target="_blank">Botmaker Bagde</a>, feel free to reach out <a href="mailto:stefan@botwiki.org" target="_blank">via email</a> or <a href="https://twitter.com/botwikidotorg" target="_blank">on Twitter</a>.</p>
            </div>
            <div class="form-group">
              <label for="bot-description">What does your bot do? <sup title="This field is required.">*</sup></label>
              <textarea required class="form-control" id="bot-description" name="bot-description" rows="3" placeholder="This bot makes..."><?php echo $bot_description_val; ?></textarea>
              <small id="bot-description-help" class="form-text text-muted">You can select text to add links.</small>
            </div>
            <div class="form-group">
              <label for="bot-tagline">A short tagline <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="bot-tagline" name="bot-tagline" placeholder="A bot that does cool stuff." value="<?php echo $bot_tagline_val; ?>">
              <small id="bot-tagline-help" class="form-text text-muted">This shows up in search.</small>
            </div>
            <div id="bot-selected-tweets-field" class="form-group">
              <label for="bot-selected-tweets">Choose two <span id="bot-selected-tweets-label">tweets</span> from your bot that you like</label>
              <textarea class="form-control" id="bot-selected-tweets" name="bot-selected-tweets" rows="3" placeholder="https://twitter.com/mycoolbot/status/123456789&#x0a;https://twitter.com/mycoolbot/status/987654321"></textarea>
              <small id="bot-selected-tweets-help" class="form-text text-muted">Paste just the URLs, one on each line, please.</small>
              <?php if ( !empty( $bot_urls_val ) ){
                global $helpers;
                $twitter_username = $helpers->get_twitter_username_from_url( $bot_urls_val );
              ?>
                <p>
                  <a href="https://socialbearing.com/search/user/<?php echo $twitter_username; ?>" target="_blank">Social Bearing</a>
                </p>
              <?php } ?>
            </div>
            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="bot-is-interactive" name="bot-is-interactive">
              <label class="form-check-label" for="bot-is-interactive">This bot is interactive</label>
              <small id="bot-is-interactive-help" class="form-text text-muted">This bot responds to messages, applies effects to images, etc.</small>
            </div>
            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="bot-is-opensource" name="bot-is-opensource">
              <label class="form-check-label" for="bot-is-opensource">This bot is open-source</label>
            </div>
            <div id="bot-source-info" class="mt-3 d-none">
              <div class="form-group">
                <label for="bot-source-url">Link( s ) to your bot's source code</label>
                <textarea class="form-control" id="bot-source-url" name="bot-source-url" placeholder="https://github.com/me/mycoolbot"></textarea>
                <small id="bot-source-url-help" class="form-text text-muted">Link to your bot's repo on GitHub, Bitbucket, etc. You can add multiple URLs, one on each line.</small>
              </div>
              <div class="form-group">
                <label for="bot-source-language">What language( s ) did you use?</label>
                <select class="form-control js-select2" id="bot-source-language" name="bot-source-language[]" multiple="multiple" placeholder="node.js, Python, Java..." data-minimum-input-length="0" data-tags="true">
                <?php
                  $languages = get_terms( 'programing_language', array( 
                      'hide_empty' => false,
                  ) );
                  foreach ( $languages as $language ) { ?>
                    <option value="<?php echo $language->slug ?>"><?php echo $language->name ?></option>
                  <?php }
                ?> 
                </select>
                <small id="bot-source-language-help" class="form-text text-muted">Yes, node.js is technically a JavaScript framework, bear with us.</small>
              </div>
            </div>
            <div class="form-group mt-3">
              <label for="bot-tags">Tag your bot <sup title="This field is required.">*</sup></label>
              <select required class="form-control js-select2" id="bot-tags" name="bot-tags[]" multiple="multiple" data-minimum-input-length="1" data-tags="true" data-ajax="/wp-json/wp/v2/tags?search=" placeholder="Type to search...">
              </select>
              <div id="bot-tags-help" class="form-text form-help-text text-muted mt-3">
                <p>Add as many relevant tags as you can, this will make it easier for others to find your submission.</p>
                <p>Note that <strong>you don't need to add tags based on your bot's network or programming language</strong>, for example <code>#twitterbot</code> or <code>#python</code>. These tags are added automatically based on provided information.</p>
              </div>
            </div>
            <div class="form-check mt-3 mb-2">
              <input type="checkbox" class="form-check-input" id="apply-for-botmaker-badge" name="apply-for-botmaker-badge">
              <label class="form-check-label" for="apply-for-botmaker-badge">Apply for <a target="_blank" href="/botmaker-badges/">Botmaker Badge</a></label>
            </div>
            <div id="botmaker-badge-application" class="mt-3 d-none">
              <div class="form-group">
                <label for="bot-author-email">List of email addresses of all authors</label>
                <textarea class="form-control" id="bot-author-email" name="bot-author-email" rows="3" placeholder="coolbotauthor@email.com"></textarea>                
                <small id="bot-author-email-help" class="form-text text-muted">We will send your badge to these email addresses. No spam, ever!</small>
              </div>
            </div>
            <button id="bot-form-submit" type="submit" class="btn mt-4">Okay, looks good</button>
          </form>
        </article>
      </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' ) ?>/libs/medium-editor/5.23.3/js/medium-editor.min.js"></script>
  <?php }
?>
<?php get_footer(); ?>
