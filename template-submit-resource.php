<?php
  use ColorThief\ColorThief;
  global $helpers;

  /* Template Name: Submit Resource Page Template */
  get_header();
  $post_id = get_the_ID();

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);

  $resource_name_val = !empty( $_REQUEST['resource-name'] ) ? $_REQUEST['resource-name'] : '';
  $resource_description_val = !empty( $_REQUEST['resource-description'] ) ? $_REQUEST['resource-description'] : '';
  $resource_url_val = !empty( $_REQUEST['resource-url'] ) ? $_REQUEST['resource-url'] : '';

  if ( !empty( $_POST ) && empty( $_POST['sec'] ) ){
    // error_log( print_r( $_POST, true ) );

    if (
      ( isset( $_POST['resource-type'] ) && !empty( $_POST['resource-type'] ) ) &&
      ( isset( $_POST['resource-name'] ) && !empty( $_POST['resource-name'] ) ) &&
      ( isset( $_POST['resource-url'] ) && !empty( $_POST['resource-url'] ) ) &&
      ( isset( $_POST['resource-tagline'] ) && !empty( $_POST['resource-tagline'] ) ) &&
      ( isset( $_POST['resource-tags'] ) && !empty( $_POST['resource-tags'] ) )
    ) {

      $resource_authors = array();

      if ( !empty( $_POST['author-names'] ) ){
        foreach ($_POST['author-names'] as $index => $author_name) {
          if (!empty( $_POST['author-names'][$index] )){
            array_push($resource_authors, trim( $author_name ) . 
                      ( array_key_exists( $index, $_POST['author-urls']  ) && !empty( $_POST['author-urls'][$index] )
                      ? ', ' . trim( $_POST['author-urls'][$index] ) : '' ) );
          }
        }
      }

      $allowed_tags = array(
        'p' => array(),
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'i' => array(),
        'em' => array(),
        'strong' => array(),
        'b' => array(),
        'blockquote' => array(
            'cite' => true,
        ),
        'q' => array(
          'cite' => true,
         ),
        'cite' => array(),
        'code' => array(),
      );          

      $resource_author_info = implode( "\n", $resource_authors );
      $resource_description = wp_kses(trim( $_POST['resource-description'] ), $allowed_tags);
      $resource_url = trim($_POST['resource-url']);

      global $helpers;
      $created_by_html_array = array();
      $author_tags = array();

      foreach ($resource_authors as $resource_author) {
        $resource_author_info_arr = explode(',', $resource_author);



        if ( count( $resource_author_info_arr ) === 2 ){
          array_push( $created_by_html_array, '<a href="' . trim( $resource_author_info_arr[1] ) . '">' . trim( $resource_author_info_arr[0] ) . '</a>');

          $twitter_username = $helpers->get_twitter_username_from_url( $resource_author_info_arr[1] );

          if ( $twitter_username ){
            array_push( $author_tags , $twitter_username );
          }
        }
        else{
          array_push( $created_by_html_array, $resource_author_info_arr[0]);          
        }
      }

      $resource_meta = array();
      $resource_meta['resource_url'] = $resource_url;
      $resource_meta['resource_author_email'] = trim( $_POST['resource-author-email'] );      

      $resource_tags = array();

      foreach ($_POST['resource-tags'] as $resource_tag) {
        array_push( $resource_tags, ltrim( trim( $resource_tag ), '#' ) );
      }

      if ( is_user_logged_in() && $_POST['disassociate-author-input'] === 'false' ){
        $fediverse_handle =  esc_attr( get_the_author_meta( 'fediverse-handle', get_current_user_id() ) );
        if ( !empty( $fediverse_handle ) ){
          array_push( $resource_tags, $fediverse_handle );
        }
        
        $twitter_handle = '@' . str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', get_current_user_id() ) ) );
        if ( !empty( $twitter_handle ) ){
          array_push( $resource_tags, $twitter_handle );
        }
      }

      if ( count( $author_tags ) > 0 ){
        $resource_tags = array_merge( $resource_tags, $author_tags );
      }

      // if ( isset( $_POST['resource-source-url'] ) && !empty( $_POST['resource-source-url'] ) ){
      //   array_push( $resource_tags, 'opensource' );
      // }

      $post_data = array(
        'post_author' => ( ( is_user_logged_in() && $_POST['disassociate-author-input'] === 'false' ) ? get_current_user_id() : 2 ),
        'post_content' => '',
        'post_title' => $_POST['resource-name'],
        'post_excerpt' => $_POST['resource-tagline'],
        'post_status' => 'draft',
        'post_type' => 'resource',
        'post_category' => '',
        // 'tax_input' => array(
        //   'post_tag' => $resource_tags
        // ),
        'meta_input' => $resource_meta
      );

      // error_log( print_r( $resource_meta, true ) );

      $new_post_id = wp_insert_post($post_data);

      if (get_current_user_id() !== 1){
        if (
          ( isset( $_POST['apply-for-botmaker-badge'] ) && !empty( $_POST['apply-for-botmaker-badge'] ) ) &&
          ( isset( $_POST['resource-author-email'] ) && !empty( $_POST['resource-author-email'] ) )
        ){
          $email_subject = 'Badge request and new resource submission';         
        }
        else{
          $email_subject = 'New resource submission';          
        }

        $post_data = $_POST;
        $post_data['edit_link'] = "https://botwiki.org/wp-admin/post.php?post=$new_post_id&action=edit";

        wp_mail( get_the_author_meta('user_email', 1), $email_subject, print_r( $post_data, true ) );      
      }

      wp_set_object_terms($new_post_id, $resource_tags, 'post_tag');
      update_post_meta($new_post_id, 'resource_author_info', $resource_author_info);

      foreach ($resource_meta as $key => $value) {
        update_post_meta( $new_post_id, $key, $value );
      }

      wp_set_object_terms( $new_post_id, $_POST['resource-networks'], 'network' );
      wp_set_object_terms( $new_post_id, $_POST['resource-type'], 'resource_type' );
      wp_set_object_terms( $new_post_id, $_POST['resource-language'], 'programing_language' );

      try {
        $screenshot = $helpers->make_screenshot( array(
          'url' => $resource_url,
          'file_name' => trim( $_POST['bot-name'] )
        ) );

        try {
          if ( class_exists( 'ColorThief' ) ){
            $dominant_color = ColorThief::getColor( $screenshot['image_path'] );
            update_post_meta( $new_post_id, 'dominant_color', json_encode( $dominant_color ) );
          }
        } catch (Exception $e) {
          /* noop */            
        }

        $helpers->add_post_thumbnail( $new_post_id, $screenshot['image_path'], $bot_description );

        if ( !is_user_logged_in() || $_POST['disassociate-author-input'] !== 'false' ){
          global $wpdb;
          $query = "UPDATE " . $wpdb->prefix . "posts SET post_status='pending' WHERE ID = '" . $new_post_id . "'";
          $wpdb->query( $query );
        }

        if ( !is_user_logged_in() || $_POST['disassociate-author-input'] !== 'false' ){
          global $wpdb;
          $query = "UPDATE " . $wpdb->prefix . "posts SET post_status='pending' WHERE ID = '" . $new_post_id . "'";
          $wpdb->query($query);
        }

      } catch ( Exception $e ) {
        // TODO: Proper error handling.
        // log_this( $e->getMessage() );
      }

    ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            $post_thumbnail_id = get_post_thumbnail_id();
            the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ]);
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1 class="post-title text-center">Thank you!</h1>
            <div class="post-content">
              <?php if ( ( is_user_logged_in() && $_POST['disassociate-author-input'] === 'false' ) ){ ?>
                <p><strong>Thank you!</strong> Feel free to <a href="/wp-admin/edit.php?post_status=draft&post_type=resource&author=<?php echo get_current_user_id(); ?>">submit more of your resources for review</a>.</p> 
              <?php } else { ?>
                <p><strong>Thank you for your submission!</strong> Please be patient while we review it 😊</p>
                <p>You can follow <a href="https://stefanbohacek.online/@newonbotwiki" target="_blank">@newonbotwiki</a> on Mastodon to see when the site gets updated.</p>
              <?php } ?>
              <ul class="btn-list mt-4">
                <li>
                  <a class="btn" href="<?php echo get_permalink(); ?>">Add one more</a>
                </li>
                <li>
                  <a class="btn" href="/submit-your-bot/">Submit a bot</a>
                </li>
                <li>
                  <a class="btn" href="/resources/">Browse resources</a>
                </li>
                <li>
                  <a class="btn" href="<?php echo get_site_url(); ?>">Back to home page</a>
                </li>
              </ul>
              <div class="row mt-5">
                  <div class="col-sm-12 col-md-6">
                    <blockquote class="mastodon-embed" data-embed-url="https://mastodon.social/@botwiki/113828353032643518/embed" style="background: #FCF8FF; border-radius: 8px; border: 1px solid #C9C4DA; margin: 0; max-width: 540px; min-width: 270px; overflow: hidden; padding: 0;"> <a href="https://mastodon.social/@botwiki/113828353032643518" target="_blank" style="align-items: center; color: #1C1A25; display: flex; flex-direction: column; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Roboto, sans-serif; font-size: 14px; justify-content: center; letter-spacing: 0.25px; line-height: 20px; padding: 24px; text-decoration: none;"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 79 75"><path d="M63 45.3v-20c0-4.1-1-7.3-3.2-9.7-2.1-2.4-5-3.7-8.5-3.7-4.1 0-7.2 1.6-9.3 4.7l-2 3.3-2-3.3c-2-3.1-5.1-4.7-9.2-4.7-3.5 0-6.4 1.3-8.6 3.7-2.1 2.4-3.1 5.6-3.1 9.7v20h8V25.9c0-4.1 1.7-6.2 5.2-6.2 3.8 0 5.8 2.5 5.8 7.4V37.7H44V27.1c0-4.9 1.9-7.4 5.8-7.4 3.5 0 5.2 2.1 5.2 6.2V45.3h8ZM74.7 16.6c.6 6 .1 15.7.1 17.3 0 .5-.1 4.8-.1 5.3-.7 11.5-8 16-15.6 17.5-.1 0-.2 0-.3 0-4.9 1-10 1.2-14.9 1.4-1.2 0-2.4 0-3.6 0-4.8 0-9.7-.6-14.4-1.7-.1 0-.1 0-.1 0s-.1 0-.1 0 0 .1 0 .1 0 0 0 0c.1 1.6.4 3.1 1 4.5.6 1.7 2.9 5.7 11.4 5.7 5 0 9.9-.6 14.8-1.7 0 0 0 0 0 0 .1 0 .1 0 .1 0 0 .1 0 .1 0 .1.1 0 .1 0 .1.1v5.6s0 .1-.1.1c0 0 0 0 0 .1-1.6 1.1-3.7 1.7-5.6 2.3-.8.3-1.6.5-2.4.7-7.5 1.7-15.4 1.3-22.7-1.2-6.8-2.4-13.8-8.2-15.5-15.2-.9-3.8-1.6-7.6-1.9-11.5-.6-5.8-.6-11.7-.8-17.5C3.9 24.5 4 20 4.9 16 6.7 7.9 14.1 2.2 22.3 1c1.4-.2 4.1-1 16.5-1h.1C51.4 0 56.7.8 58.1 1c8.4 1.2 15.5 7.5 16.6 15.6Z" fill="currentColor"/></svg> <div style="color: #787588; margin-top: 16px;">Post by @botwiki@mastodon.social</div> <div style="font-weight: 500;">View on Mastodon</div> </a> </blockquote> <script data-allowed-prefixes="https://mastodon.social/" async src="https://mastodon.social/embed.js"></script>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <blockquote class="mastodon-embed" data-embed-url="https://mastodon.social/@botwiki/100684556853532276/embed" style="background: #FCF8FF; border-radius: 8px; border: 1px solid #C9C4DA; margin: 0; max-width: 540px; min-width: 270px; overflow: hidden; padding: 0;"> <a href="https://mastodon.social/@botwiki/100684556853532276" target="_blank" style="align-items: center; color: #1C1A25; display: flex; flex-direction: column; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Roboto, sans-serif; font-size: 14px; justify-content: center; letter-spacing: 0.25px; line-height: 20px; padding: 24px; text-decoration: none;"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 79 75"><path d="M63 45.3v-20c0-4.1-1-7.3-3.2-9.7-2.1-2.4-5-3.7-8.5-3.7-4.1 0-7.2 1.6-9.3 4.7l-2 3.3-2-3.3c-2-3.1-5.1-4.7-9.2-4.7-3.5 0-6.4 1.3-8.6 3.7-2.1 2.4-3.1 5.6-3.1 9.7v20h8V25.9c0-4.1 1.7-6.2 5.2-6.2 3.8 0 5.8 2.5 5.8 7.4V37.7H44V27.1c0-4.9 1.9-7.4 5.8-7.4 3.5 0 5.2 2.1 5.2 6.2V45.3h8ZM74.7 16.6c.6 6 .1 15.7.1 17.3 0 .5-.1 4.8-.1 5.3-.7 11.5-8 16-15.6 17.5-.1 0-.2 0-.3 0-4.9 1-10 1.2-14.9 1.4-1.2 0-2.4 0-3.6 0-4.8 0-9.7-.6-14.4-1.7-.1 0-.1 0-.1 0s-.1 0-.1 0 0 .1 0 .1 0 0 0 0c.1 1.6.4 3.1 1 4.5.6 1.7 2.9 5.7 11.4 5.7 5 0 9.9-.6 14.8-1.7 0 0 0 0 0 0 .1 0 .1 0 .1 0 0 .1 0 .1 0 .1.1 0 .1 0 .1.1v5.6s0 .1-.1.1c0 0 0 0 0 .1-1.6 1.1-3.7 1.7-5.6 2.3-.8.3-1.6.5-2.4.7-7.5 1.7-15.4 1.3-22.7-1.2-6.8-2.4-13.8-8.2-15.5-15.2-.9-3.8-1.6-7.6-1.9-11.5-.6-5.8-.6-11.7-.8-17.5C3.9 24.5 4 20 4.9 16 6.7 7.9 14.1 2.2 22.3 1c1.4-.2 4.1-1 16.5-1h.1C51.4 0 56.7.8 58.1 1c8.4 1.2 15.5 7.5 16.6 15.6Z" fill="currentColor"/></svg> <div style="color: #787588; margin-top: 16px;">Post by @botwiki@mastodon.social</div> <div style="font-weight: 500;">View on Mastodon</div> </a> </blockquote> <script data-allowed-prefixes="https://mastodon.social/" async src="https://mastodon.social/embed.js"></script>                    
                  </div>
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
            the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ]);
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-content">
              <p>Please <a href="<?php echo get_permalink(); ?>">return to the previous page</a> and make sure all required fields are filled out. <strong>Thank you!</strong></p>
            </div>
          </article>
        </div>
      </main>
    <?php }
  }
  else { ?>
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/medium-editor/5.23.3/css/medium-editor.css' media='all' />
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/medium-editor/5.23.3/css/themes/default.css' media='all' />
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/select2/4.0.10/css/select2.min.css' media='all' />
    <main role="main" class="container-fluid m-0 p-0">
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php
          $post_thumbnail_id = get_post_thumbnail_id();
          the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ]);
        ?>
      </div>
      <div class="container">
        <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
          <h1 class="post-title"><?php the_title(); ?></h1>
          <div class="post-content">
            <ul class="btn-list">
              <li>
                <a class="btn" href="https://stefanbohacek.online/@newonbotwiki" target="_blank">@newonbotwiki</a>
              </li>
              <li>
                <a class="btn" href="/botwiki-weekly-digest/" target="_blank">Weekly Digest</a>
              </li>
              <li>
                <a class="btn" href="https://botmakers.org/" target="_blank">botmakers.org</a>
              </li>
              <?php if ( is_user_logged_in() && get_current_user_id() === 1 ) {?>
              <li>
                <button class="btn" id="test" href="#">Test submission</button>
              </li>
              <?php } ?>
            </ul>
            <?php echo do_shortcode( get_post_field( 'post_content', $post_id ) ); ?>
            <form id="submit-resource-form" method="post" class="mt-5 xxxbg-light p-5">
            <?php if ( is_user_logged_in() ) {
              $author_id = get_current_user_id();
              $username = get_the_author_meta('user_nicename', get_current_user_id() );
              $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

              if ( empty( $profile_img_url )){
                $profile_img_url = get_avatar_url( $author_id, array( 'size' => 360, 'scheme' => 'https' ) );
              }
              $botwiki_profile_page_url = get_site_url() . '/author/' . $username;
            ?>
              <div id="logged-in-author" class="card mb-5">
                <div class="card-body">
                  <div class="container">
                    <div class="row">
                      <div class="col-sm-2">
                        <a href="<?php echo $botwiki_profile_page_url; ?>">
                          <img class="img-thumbnail" style="width: 100%; height: 100%; max-width: 100px; max-height: 100px;" src="<?php echo $profile_img_url; ?>">
                        </a>
                      </div>
                      <div class="col-sm-10">
                        <h5 class="card-title mt-1">You are logged in</h5>
                        <p class="card-text">This resource will be added to <a href="<?php echo $botwiki_profile_page_url; ?>">your profile</a>.</p>
                        <p>
                          <a id="disassociate-author" href="#"><em>This is not my resource.</em></a>
                          <input type="hidden" name="disassociate-author-input" value="false">
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <div class="author-fields form-row row">
                <div class="form-group mt-1 mb-1 col-md-6">
                  <label class="form-label" for="author-1-name">Author's name</label>
                  <input type="text" class="form-control" id="author-1-name" name="author-names[]" placeholder="Author">
                </div>
                <div class="form-group mt-1 mb-1 col-md-6">
                  <label class="form-label" for="author-1-url">Author's URL</label>
                  <input type="url" class="form-control" id="author-1-url" name="author-urls[]" placeholder="https://fediverse.social/@author">
                </div>
              </div>
            <?php } ?>
              <div class="form-group mt-1 mb-1">
                <button id="add-author-fields" class="btn">Add more authors</button>
              </div>
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-type">What kind of a resource would you like to submit?</label>
                <select class="form-control js-select2" id="resource-type" name="resource-type" placeholder="Library, framework, tutorial,..." data-minimum-input-length="0">
                <?php
                  $resource_types = get_terms( 'resource_type', array(
                      'hide_empty' => false,
                  ) );

                  foreach ($resource_types as $resource_type) { ?>
                    <option <?php echo ( $resource_type->slug === 'tutorial' ? ' selected ' : '') ?> value="<?php echo $resource_type->slug ?>"><?php echo $resource_type->name ?></option>
                  <?php }
                ?> 
                </select>
              </div>            
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-name">What's the title of your <span class="resource-type-name">tutorial</span>? <sup title="This field is required.">*</sup></label>
                <input required type="text" class="form-control" id="resource-name" name="resource-name" value="<?php echo $resource_name_val; ?>" placeholder="My tutorial...">
              </div>
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-tagline">A short description of your <span class="resource-type-name">tutorial</span> <sup title="This field is required.">*</sup></label>
                <input required type="text" class="form-control" id="resource-tagline" name="resource-tagline" value="<?php echo $resource_description_val; ?>" placeholder="A useful tutorial.">
                <small id="resource-tagline-help" class="form-text text-muted">Briefly explain what this <span class="resource-type-name">tutorial</span> is for.</small>
              </div>
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-url">Where can we see your <span class="resource-type-name">tutorial</span>?<sup title="This field is required.">*</sup></label>
                <input required type="url" class="form-control" id="resource-url" name="resource-url" value="<?php echo $resource_url_val; ?>" placeholder="https://github.com/developer/resource">
              </div>
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-networks">Is this <span class="resource-type-name">tutorial</span> for a specific network?</label>
                <select multiple class="form-control js-select2" id="resource-networks" name="resource-networks" placeholder="Fediverse, Tumblr, Slack,...">
                <?php
                  $networks = get_terms( 'network', array(
                      'hide_empty' => false,
                  ) );

                  foreach ($networks as $network) { ?>
                    <option value="<?php echo $network->slug ?>"><?php echo $network->name ?></option>
                  <?php }
                ?> 
                </select>
                <small id="resource-network-help" class="form-text text-muted">You can leave this blank.</small>
              </div>
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-language">Does this <span class="resource-type-name">tutorial</span> use a specific programing language?</label>

                <select class="form-control js-select2" id="resource-language" name="resource-language[]" multiple="multiple" placeholder="node.js, Python, Java..." data-minimum-input-length="1">
                <?php
                  $languages = get_terms( 'programing_language', array(
                      'hide_empty' => false,
                  ) );

                  foreach ($languages as $language) { ?>
                    <option value="<?php echo $language->slug ?>"><?php echo $language->name ?></option>
                  <?php }
                ?> 
                </select>

                <small id="resource-language-help" class="form-text text-muted">Yes, node.js is technically a JavaScript framework, bear with us. Leave blank if not applicable.</small>
              </div>            
              <div class="form-group mt-1 mb-1">
                <label class="form-label" for="resource-tags">Tag your <span class="resource-type-name">tutorial</span> <sup title="This field is required.">*</sup></label>

                <select data-ajax="/wp-json/wp/v2/tags?search=" data-minimum-input-length="1" required class="form-control js-select2" id="resource-tags" name="resource-tags[]" multiple="multiple" placeholder="Type to search..."></select>
                <div id="resource-tags-help" class="form-text form-help-text text-muted mt-3">
                  <p>Add as many relevant tags as you can, this will make it easier for others to find your submission.</p>
                  <p>Note that <strong>you don't need to add tags based on your bot's network or programming language</strong>, for example <code>#twitterbot</code> or <code>#python</code>. These tags are added automatically based on provided information.</p>
                </div>
              </div>

              <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" id="apply-for-botmaker-badge" name="apply-for-botmaker-badge">
                <label class="form-label" class="form-check-label" for="apply-for-botmaker-badge">Apply for <a target="_blank" href="/botmaker-badges/">Botmaker Badge</a></label>
              </div>
              <div id="botmaker-badge-application" class="mt-3 d-none">
                <div class="form-group mt-1 mb-1">
                  <label class="form-label" for="resource-author-email">List of email addresses of all authors</label>
                  <textarea class="form-control" id="resource-author-email" name="resource-author-email" rows="3" placeholder="coolbotresourceauthor@email.com"></textarea>
                  <small id="resource-author-email-help" class="form-text text-muted">We will send your badge to these email addresses. No spam, ever!</small>
                </div>
              </div>
              <label for="sec" aria-hidden="true" class="visually-hidden">
                <input type="radio" name="sec" id="sec" style="display:none" value="1">
              </label>
              <button id="resource-form-submit" type="submit" class="btn mt-4">Okay, looks good</button>
            </form>
          </div>
        </article>
      </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' ) ?>/libs/medium-editor/5.23.3/js/medium-editor.min.js"></script>
  <?php }
?>
<?php get_footer(); ?>
