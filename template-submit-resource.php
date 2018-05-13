<?php
  use ColorThief\ColorThief;
  global $helpers;

  /* Template Name: Submit Resource Page Template */
  get_header();
  $post_id = get_the_ID();

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);

  if ( !empty( $_POST ) ){

    // error_log( print_r( $_POST, true ) );

    if (
      ( isset( $_POST['resource-type'] ) && !empty( $_POST['resource-type'] ) ) &&
      ( isset( $_POST['resource-name'] ) && !empty( $_POST['resource-name'] ) ) &&
      ( isset( $_POST['resource-url'] ) && !empty( $_POST['resource-url'] ) ) &&
      ( isset( $_POST['resource-tagline'] ) && !empty( $_POST['resource-tagline'] ) ) &&
      ( isset( $_POST['resource-tags'] ) && !empty( $_POST['resource-tags'] ) )
    ) {

      if (get_current_user_id() !== 1){
        wp_mail( get_the_author_meta('user_email', 1), 'New resource submission', print_r( $_POST, true ) );      
      }

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
          // 'post_title' => $description,
          'post_title' => $_POST['resource-name'],
          'post_content' => '',
          'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
        $res2 = set_post_thumbnail( $post_id, $attach_id );
      }


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

      $resource_author_info = implode( "\n", $resource_authors );

      $resource_description = trim($_POST['resource-description'] );
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

      $resource_tags = array();

      foreach ($_POST['resource-tags'] as $resource_tag) {
        array_push( $resource_tags, $resource_tag );
      }

      if ( count( $author_tags ) > 0 ){
        $resource_tags = array_merge( $resource_tags, $author_tags );
      }

      if ( isset( $_POST['resource-source-url'] ) && !empty( $_POST['resource-source-url'] ) ){
        array_push( $resource_tags, 'opensource' );
      }

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

      wp_set_object_terms($new_post_id, $resource_tags, 'post_tag');
      update_post_meta($new_post_id, 'resource_author_info', $resource_author_info);

      foreach ($resource_meta as $key => $value) {
        update_post_meta( $new_post_id, $key, $value );
      }

      wp_set_object_terms( $new_post_id, $_POST['resource-networks'], 'network' );
      wp_set_object_terms( $new_post_id, $_POST['resource-type'], 'resource_type' );
      wp_set_object_terms( $new_post_id, $_POST['resource-language'], 'programing_language' );

      try {
        // TODO: Proper error handling.

        $screenshot_data = file_get_contents("https://screenshot-beta.glitch.me/?url=" . $resource_url . "&width=1200&height=685");

        $screenshot_data_json = json_decode( $screenshot_data );

        $image_path = ABSPATH . 'temp/' . str_replace( '@', '', trim( urlencode( $_POST['resource-name'] ) ) ) . '.png';

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

        add_post_thumbnail( $new_post_id, $image_path, $resource_description );

        if ( !is_user_logged_in() || $_POST['disassociate-author-input'] !== 'false' ){
          global $wpdb;
          $query = "UPDATE " . $wpdb->prefix . "posts SET post_status='pending' WHERE ID = '" . $new_post_id . "'";
          $wpdb->query($query);
        }

      } catch (Exception $e) {
        /* NOOP */
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
            <h1>Thank you</h1>
            <?php if ( ( is_user_logged_in() && $_POST['disassociate-author-input'] === 'false' ) ){ ?>
              <p><strong>Thank you!</strong> Make sure to <a href="/wp-admin/edit.php?post_status=draft&post_type=resource&author=<?php echo get_current_user_id(); ?>">submit your resource for review</a>.</p> 
            <?php } else { ?>
              <p><strong>Thank you for your submission!</strong> Please be patient while we review it 😊</p> 
            <?php } ?>
            <ul class="btn-list mt-4">
              <li>
                <a class="btn" href="<?php echo get_permalink(); ?>">Add one more</a>
              </li>
              <li>
                <a class="btn" href="/resources/">Browse resources</a>
              </li>
              <li>
                <a class="btn" href="<?php echo get_site_url(); ?>">Back to home page</a>
              </li>
            </ul>
            <div class="text-center mt-5">
              <blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">We are a diverse group of enthusiasts who make and share fun and creative online bots. Come join us! 😊 <a href="https://t.co/4FH6OgVuCG">https://t.co/4FH6OgVuCG</a></p>&mdash; A friendly encyclopedia of 🤖💻💾 (@botwikidotorg) <a href="https://twitter.com/botwikidotorg/status/984405698103726082?ref_src=twsrc%5Etfw">April 12, 2018</a></blockquote>
              <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
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
            <h1><?php the_title(); ?></h1>
            <p>Please <a href="<?php echo get_permalink(); ?>">return to the previous page</a> and make sure all required fields are filled out. <strong>Thank you!</strong></p>
          </article>
        </div>
      </main>
    <?php }
  }
  else { ?>
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/medium-editor/5.23.3/css/medium-editor.css' media='all' />
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/medium-editor/5.23.3/css/themes/default.css' media='all' />
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/css/select2.min.css' media='all' />
    <main role="main" class="container-fluid m-0 p-0">
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php
          $post_thumbnail_id = get_post_thumbnail_id();
          the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load', 'title' => get_post( $post_thumbnail_id )->post_title ]);
        ?>
      </div>
      <div class="container">
        <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
          <?php if ( is_user_logged_in() && get_current_user_id() === 1 ) {?>
            <ul class="btn-list">
              <li>
                <button class="btn" id="test" href="#">Test submission</button>
              </li>
            </ul>
          <?php } ?>
          <?php echo do_shortcode( get_post_field( 'post_content', $post_id ) ); ?>
          <form id="submit-resource-form" method="post" class="mt-5">
          <?php if ( is_user_logged_in() ) {
            $author_id = get_current_user_id();
            $username = get_the_author_meta('user_nicename', get_current_user_id() );
            $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

            if ( empty( $profile_img_url )){
              $profile_img_url = get_avatar_url($author_id);
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
              <label for="resource-type">What kind of a resource would you like to submit?</label>
              <select class="form-control js-select2" id="resource-type" name="resource-type" placeholder="Library, framework, tutorial,...">
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
            <div class="form-group">
              <label for="resource-name">What's the title of your <span class="resource-type-name">tutorial</span>? <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="resource-name" name="resource-name" placeholder="My tutorial...">
            </div>
            <div class="form-group">
              <label for="resource-tagline">A short description of your <span class="resource-type-name">tutorial</span> <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="resource-tagline" name="resource-tagline" placeholder="A useful tutorial.">
              <small id="resource-tagline-help" class="form-text text-muted">Briefly explain what this <span class="resource-type-name">tutorial</span> is for.</small>
            </div>
            <div class="form-group">
              <label for="resource-url">Where can we see your <span class="resource-type-name">tutorial</span>?<sup title="This field is required.">*</sup></label>
              <input required type="url" class="form-control" id="resource-url" name="resource-url" placeholder="https://github.com/developer/resource">
            </div>
            <div class="form-group">
              <label for="resource-networks">Is this <span class="resource-type-name">tutorial</span> for a specific network?</label>
              <select multiple class="form-control js-select2" id="resource-networks" name="resource-networks" placeholder="Twitter, Tumblr, Slack,...">
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
            <div class="form-group">
              <label for="resource-language">Does this <span class="resource-type-name">tutorial</span> use a specific programing language?</label>

              <select class="form-control js-select2" id="resource-language" name="resource-language[]" multiple="multiple" placeholder="node.js, Python, Java...">
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
            <div class="form-group">
              <label for="resource-tags">Tag your <span class="resource-type-name">tutorial</span> <sup title="This field is required.">*</sup></label>

              <select required class="form-control js-select2" id="resource-tags" name="resource-tags[]" multiple="multiple">
              <?php


                $tags = get_tags( array(
                  'hide_empty' => true
                ) );

                foreach ($tags as $tag) { ?>
                  <option value="<?php echo $tag->slug ?>"><?php echo $tag->name ?></option>
                <?php }
              ?> 
              </select>

              <small id="resource-tags-help" class="form-text text-muted">Add as many relevant tags as you can, this will make it easier for others to find it.</small>
            </div>

            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="apply-for-botmaker-badge" name="apply-for-botmaker-badge">
              <label class="form-check-label" for="apply-for-botmaker-badge">Apply for <a target="_blank" href="/botmaker-badges/">Botmaker Badge</a></label>
            </div>
            <div id="botmaker-badge-application" class="mt-3 d-none">
              <div class="form-group">
                <input type="email" class="form-control" id="resource-author-email" name="resource-author-email" placeholder="coolresourceauthor@email.com">
                <small id="resource-author-email-help" class="form-text text-muted">We will send your badge to this email. No spam, ever!</small>
              </div>
            </div>


            <button id="resource-form-submit" type="submit" class="btn mt-4">Okay, looks good</button>
          </form>
        </article>
      </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/js/select2.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/libs/medium-editor/5.23.3/js/medium-editor.min.js"></script>
  <?php }
?>
<?php get_footer(); ?>
