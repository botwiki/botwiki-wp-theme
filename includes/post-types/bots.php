<?php

class BotsPostType {
  function post_type_setup() {
    $this->create_post_type();

    add_action( 'add_meta_boxes', array( $this, 'add_bot_info' ) );
    add_action( 'add_meta_boxes', array( $this, 'add_bot_author_info' ) );
    add_action( 'add_meta_boxes', array( $this, 'add_bot_tweets' ) );
    add_action( 'save_post',  array( $this, 'save_meta' ), 10, 2 );
    add_filter( 'enter_title_here', array( $this, 'change_post_title_placeholder' ) );
    add_action( 'pre_get_posts', array( $this, 'filter_query' ) );
    add_action( 'admin_bar_menu', array( $this, 'add_pending_bots_link' ), 100 );
    add_shortcode( 'bot_count', array( $this, 'get_bot_count' ) );
    add_shortcode( 'bot_languages', array( $this, 'get_bot_language_cards' ) );

    add_filter( 'lazyblock/bot-output/frontend_callback', array( $this, 'lazyblock_bot_output' ), 10, 2 );
    add_filter( 'lazyblock/bot-output/frontend_allow_wrapper', '__return_false' );

    // add_action( 'enqueue_block_editor_assets', array( $this, 'register_gutenberg_blocks' ) );

  }

  function lazyblock_bot_output( $output, $attributes ){
    // log_this( array(
    //   // 'output' => $output,
    //   // 'attributes' => $attributes,
    //   'bot-output-layout-style' => $attributes['bot-output-layout-style'],
    //   'bot-output' => $attributes['bot-output']
    // ) );

    $html = '';

    $bot_output = $attributes['bot-output'];
    $bot_output_count = count( $bot_output );


    if ( !empty( $bot_output ) ){
      if ( !empty( $attributes['bot-output-layout-style'] ) ){
        $layout_style = $attributes['bot-output-layout-style'];
      } else {
        $layout_style = 'layout-1';
      }

      if ( in_array( $layout_style, [ 'layout-1', 'layout-2' ] ) ){
        $html .= '<div class="container mt-5 mb-5"><div class="row no-gutters">';


        if ( $bot_output_count > 4 ){
          if ( $layout_style === 'layout-1' ){
            $first_col = 7;
          } elseif ( $layout_style === 'layout-2' ){
            $first_col = 5;
          }
        } else {
            $first_col = 6;
        }

        foreach ( $bot_output as $index => $bot_output_item ) {
          if ( $index === 0 ){
            $col_class = 'col-sm-12 col-md-' . $first_col . ' align-self-end p-1 text-center';
          } elseif( $index === 1 ){
            $col_class = 'col-sm-12 col-md-' . ( 12 - $first_col ) . ' align-self-end p-1';
          } elseif ( $bot_output_count === 3 ) {
            $col_class = 'col-sm-12 p-1 text-center';
          } elseif ( $bot_output_count === 4 ) {
            $col_class = 'col-sm-12 col-md-6 p-1';
          } else{
            $col_class = 'col-sm-12 col-md-4 p-1';
          }

          $html .= '<div class="' . $col_class . ' mb-5 mb-md-0">';

          if ( !empty( $bot_output_item['bot-output-image'] ) ){
            $html .= '<a href="' . $bot_output_item['source-url'] . '">';
            $html .= '<img class="bot-output bot-output-img lazy-load" data-src="' . $bot_output_item['bot-output-image']['url'] . '">';
            $html .= '<noscript><img class="bot-output bot-output-img lazy-load" src="' . $bot_output_item['bot-output-image']['url'] . '"></noscript>';
            $html .= '</a>';
          }
          $html .= '</div>';
        }
        $html .= '</div></div>';
      } elseif ( $layout_style === 'layout-4' ){
        $html .= '<div class="container mt-5 mb-5"><div class="row no-gutters">';

        foreach ( $bot_output as $index => $bot_output_item ) {
          $html .= '<div class="col-sm-12 col-md-4 p-1 text-center mb-5 mb-md-0">';

          if ( !empty( $bot_output_item['bot-output-image'] ) ){
            $html .= '<a href="' . $bot_output_item['source-url'] . '">';
            $html .= '<img class="bot-output bot-output-img lazy-load" data-src="' . $bot_output_item['bot-output-image']['url'] . '">';
            $html .= '<noscript><img class="bot-output bot-output-img lazy-load" src="' . $bot_output_item['bot-output-image']['url'] . '"></noscript>';
            $html .= '</a>';
          }
          $html .= '</div>';
        }
        $html .= '</div></div>';


      } elseif ( $layout_style === 'layout-3' ){
        $bot_output_count_half = floor( $bot_output_count / 2 );

        $bot_output_halved = array(
          array_slice( $bot_output, 0, $bot_output_count_half ),
          array_slice( $bot_output, $bot_output_count_half )
        );

        $html .= '<div class="container mt-5 mb-5"><div class="row no-gutters">';

        foreach ( $bot_output_halved as $group_index => $bot_output_group ) {
          $html .= '<div class="col-sm-12 col-md-' . ( $group_index === 0 ? '7' : '5' ) . '"><div class="row no-gutters">';
          foreach ( $bot_output_group as $index => $bot_output_item ) {

            $col_class = 'col-sm-12 p-1 mb-5 mb-md-0';
            $html .= '<div class="' . $col_class . '">';

            if ( !empty( $bot_output_item['bot-output-image'] ) ){
              $html .= '<a href="' . $bot_output_item['source-url'] . '">';
              $html .= '<img class="bot-output bot-output-img lazy-load" data-src="' . $bot_output_item['bot-output-image']['url'] . '">';
              $html .= '<noscript><img class="bot-output bot-output-img lazy-load" src="' . $bot_output_item['bot-output-image']['url'] . '"></noscript>';
              $html .= '</a>';
            }

            $html .= '</div>';
          }
          $html .= '</div></div>';
        }

        $html .= '</div></div>';
      }
    }

    return $html;
  }

  // function register_gutenberg_blocks(){
  //   $js_file_path = get_template_directory() . '/includes/post-types/test-block.js';
  //   wp_register_script( 'test-block', get_template_directory_uri() . '/includes/post-types/test-block.js', array( 'wp-blocks','wp-editor', 'jquery' ), filemtime( $js_file_path ));
  //   wp_enqueue_script( 'test-block' );
  // }

  function get_bot_count( $atts ) {
    return number_format( wp_count_posts( 'bot' )->publish );
  }

  function get_bot_language_cards( $atts ) {
    $html = '';
    $languages = get_terms( array( 
        'taxonomy' => 'programing_language',
        'orderby' => 'count',
        'order' => 'DESC',
    ) );

    foreach ( $languages as $language ){
      $slug = $language->slug;
      $name = $language->name;

      $args = array(
          'posts_per_page' => -1,
          'post_type' => array(
              'bot'
          ),
          'programing_language' => $language->slug,
          'meta_query' => array(
              array(
                  'key' => 'bot_source_url',
                  'value' => array(''),
                  'compare' => 'NOT IN'
              )
          )
      );

      $query = new WP_Query( $args );
      $count = number_format( $query->found_posts );

      if ( $count > 0 ){
        $html .= <<<HTML
          <div class="col-sm-12 col-md-6 col-lg-4 list-item">
            <div class="card w-100" style="will-change: transform; transform: perspective(300px) rotateX(0deg) rotateY(0deg);">
              <div class="card-body">
                <h5 class="card-title"><a class="stretched-link" href="/languages/{$slug}/?opensource=true">{$name} ({$count})</a></h5>
              </div>
            </div>
          </div>
HTML;
      }

    }

    return "<div class='row list'>$html</div>";
  }

  function add_pending_bots_link($wp_admin_bar) {
    if ( current_user_can('administrator') ){
      $query = array(
        'post_type' => 'bot',
        'post_status' => array('pending'),
        'posts_per_page'    => -1
      );

      $pending_count = count( query_posts($query) );

      if ( $pending_count > 0 ){
        $args = array(
          'id' => 'review-pending-bots',
          'title' => 'New Bots (' . $pending_count . ')', 
          'href' => '/wp-admin/edit.php?post_status=pending&post_type=bot', 
          'meta' => array(
            'class' => 'review-pending-bots', 
            'title' => 'Review pending bots'
          )
        );
        $wp_admin_bar->add_node($args);
      }
      wp_reset_query();    
    }
  }

  function filter_query($query){
    $tax_query = array();

    if ( isset( $_GET['networks'] ) && !empty( $_GET['networks'] ) ){
      foreach (explode(',', $_GET['networks']) as $network) {
        array_push($tax_query, array(
          'taxonomy' => 'network',
          'field' => 'slug',
          'terms' =>  $network
        ));
      }
    }


    if ( isset( $_GET['languages'] ) && !empty( $_GET['languages'] ) ){
      foreach (explode(',', $_GET['languages']) as $language) {
        array_push($tax_query, array(
          'taxonomy' => 'programing_language',
          'field' => 'slug',
          'terms' =>  $language
        ));
      }
    }


    if ( isset( $_GET['tags'] ) && !empty( $_GET['tags'] ) ){
      foreach (explode(',', $_GET['tags']) as $tag) {
        array_push($tax_query, array(
          'taxonomy' => 'post_tag',
          'field' => 'slug',
          'terms' =>  $tag
        ));
      }
    }

    $query->set('tax_query', $tax_query);
    return $query;
  }

  function create_post_type() {
    add_action( 'init', array( $this, 'register_bot_post_type' ), 40 );
    add_action( 'init', array( $this, 'register_bot_network_taxonomy' ), 40 );
    add_action( 'init', array( $this, 'register_programing_language_taxonomy' ), 40 );
  }

  function change_post_title_placeholder( $title ){
    $screen = get_current_screen();
  
    if  ( $screen->post_type == 'bot' ) {
      $title = "Bot's handle or name...";
    }    
   return $title;
  }
  

  function register_bot_post_type() {
    $args = array(
      'label' => __( 'Post Type', 'botwiki' ),
      'description' => __( 'Post Type Description', 'botwiki' ),
      'labels' => array(
        'name' => __( 'Bots', 'post type general name', 'botwiki' ),
        'singular_name' => __( 'Bot', 'post type singular name', 'botwiki' ),
        'menu_name' => __( 'Bots', 'admin menu', 'botwiki' ),
        'name_admin_bar' => __( 'Bot', 'add new on admin bar', 'botwiki' ),
        'archives' => __( 'Bot Archives', 'botwiki' ),
        'attributes' => __( 'Bot Attributes', 'botwiki' ),
        'add_new' => __( 'Add New', 'book', 'botwiki' ),
        'add_new_item' => __( 'Add New Bot', 'botwiki' ),
        'new_item' => __( 'New Bot', 'botwiki' ),
        'edit_item' => __( 'Edit Bot', 'botwiki' ),
        'update_item' => __( 'Update Bot', 'botwiki' ),
        'view_item' => __( 'View Bot', 'botwiki' ),
        'view_items' => __( 'View Bots', 'botwiki' ),
        'all_items' => __( 'All Bots', 'botwiki' ),
        'search_items' => __( 'Search Bots', 'botwiki' ),
        'parent_item_colon' => __( 'Parent Bots:', 'botwiki' ),
        'not_found' => __( 'No Bots found.', 'botwiki' ),
        'not_found_in_trash' => __( 'No Bots found in Trash.', 'botwiki' ),
        'featured_image' => __( 'Featured Image', 'botwiki' ),
        'set_featured_image' => __( 'Set featured image', 'botwiki' ),
        'remove_featured_image' => __( 'Remove featured image', 'botwiki' ),
        'use_featured_image' => __( 'Use as featured image', 'botwiki' ),
        'insert_into_item' => __( 'Insert into Bot', 'botwiki' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Bot', 'botwiki' ),
        'items_list' => __( 'Items list', 'botwiki' ),
        'items_list_navigation' => __( 'Items list navigation', 'botwiki' ),
        'filter_items_list' => __( 'Filter items list', 'botwiki' )
      ),
      'taxonomies' => array('post_tag', 'programing_language', 'network' ),
      'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail', 'author', 'revisions' ),
      'hierarchical' => true,
      'public' => true,
      'show_ui' => true,
      // 'rewrite' => array( 'slug' => 'bots/%network%' ),
      // 'rewrite' => array( 'slug' => 'bots' ),
      'show_in_menu' => true,
      'menu_position' => 6,
      'menu_icon' => "dashicons-admin-page",
      'show_in_admin_bar' => true,
      'show_in_nav_menus' => true,
      'can_export' => true,
      'has_archive' => true,
      'exclude_from_search' => false,
      'publicly_queryable' => true,
      'show_in_rest' => true,
      'rest_base' => 'bot',
      'rest_controller_class' => 'WP_REST_Posts_Controller'
    );
    register_post_type( 'bot', $args );
  }

  function register_bot_network_taxonomy() {
    $labels = array(
      'name'                       => _x( 'Networks', 'Taxonomy General Name', 'botwiki' ),
      'singular_name'              => _x( 'Network', 'Taxonomy Singular Name', 'botwiki' ),
      'menu_name'                  => __( 'Network', 'botwiki' ),
      'all_items'                  => __( 'All Networks', 'botwiki' ),
      'parent_item'                => __( 'Parent Network', 'botwiki' ),
      'parent_item_colon'          => __( 'Parent Network:', 'botwiki' ),
      'new_item_name'              => __( 'New Network Name', 'botwiki' ),
      'add_new_item'               => __( 'Add New Network', 'botwiki' ),
      'edit_item'                  => __( 'Edit Network', 'botwiki' ),
      'update_item'                => __( 'Update Network', 'botwiki' ),
      'view_item'                  => __( 'View Network', 'botwiki' ),
      'separate_items_with_commas' => __( 'Separate items with commas', 'botwiki' ),
      'add_or_remove_items'        => __( 'Add or remove items', 'botwiki' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'botwiki' ),
      'popular_items'              => __( 'Popular Networks', 'botwiki' ),
      'search_items'               => __( 'Search Networks', 'botwiki' ),
      'not_found'                  => __( 'Not Found', 'botwiki' ),
      'items_list'                 => __( 'Network list', 'botwiki' ),
      'items_list_navigation'      => __( 'Network list navigation', 'botwiki' )
    );

    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => true,
      'show_in_rest'               => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'rewrite' =>  array('slug' => 'networks', 'with_front' => false)      
    );
    register_taxonomy( 'network', array( 'bot' ), $args );
  }

  function register_programing_language_taxonomy() {
    $labels = array(
      'name'                       => _x( 'Programing Languages', 'Taxonomy General Name', 'botwiki' ),
      'singular_name'              => _x( 'Programing Language', 'Taxonomy Singular Name', 'botwiki' ),
      'menu_name'                  => __( 'Programing Language', 'botwiki' ),
      'all_items'                  => __( 'All Languages', 'botwiki' ),
      'parent_item'                => __( 'Parent Programing Language', 'botwiki' ),
      'parent_item_colon'          => __( 'Parent Programing Language:', 'botwiki' ),
      'new_item_name'              => __( 'New Programing Language Name', 'botwiki' ),
      'add_new_item'               => __( 'Add New Programing Language', 'botwiki' ),
      'edit_item'                  => __( 'Edit Programing Language', 'botwiki' ),
      'update_item'                => __( 'Update Programing Language', 'botwiki' ),
      'view_item'                  => __( 'View Programing Language', 'botwiki' ),
      'separate_items_with_commas' => __( 'Separate items with commas', 'botwiki' ),
      'add_or_remove_items'        => __( 'Add or remove items', 'botwiki' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'botwiki' ),
      'popular_items'              => __( 'Popular Programing Languages', 'botwiki' ),
      'search_items'               => __( 'Search Programing Languages', 'botwiki' ),
      'not_found'                  => __( 'Not Found', 'botwiki' ),
      'items_list'                 => __( 'Programing Language list', 'botwiki' ),
      'items_list_navigation'      => __( 'Programing Language list navigation', 'botwiki' )
    );

    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_rest'               => true,
      'show_in_nav_menus'          => true,
      'rewrite' =>  array('slug' => 'languages', 'with_front' => false),
      'show_tagcloud'              => true
    );
    register_taxonomy( 'programing_language', array( 'bot' ), $args );
  }

  function bot_info_fields(){
    $id = get_the_id();
    $bot_meta = get_post_meta( $id );

    // echo "<pre><code>";
    // var_dump($bot_meta);
    // echo "</code></pre>";

    wp_nonce_field( basename( __FILE__ ), 'bw_nonce' ); ?>
    <table class="w-100">
      <tr>
        <td class="w-100">
          <label for="bot_author_url">Bot's URL(s) (One per line)</label>
        </td>
      </tr>
      <tr>      
        <td class="w-100">
          <textarea class="w-100" name="bot_url" rows="5"><?php echo $bot_meta['bot_url'][0]; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>
          <p>If this bot's code is opensource, select the programing language(s) in the sidebar and provide a link to the source code below.</p>
        </td>
      </tr>
      <tr>
        <td>
          <p>
            <button id="output-archive-btn" class="button-secondary">Select output archive</button>
          </p>
          <?php
            $has_archive = ( isset( $bot_meta['output_archive_url'] ) &&
                             strlen( trim( $bot_meta['output_archive_url'][0] ) ) !== 0 );
          ?>
          <p id="output-archive-file-wrapper" class="<?php echo ( $has_archive ? '' : 'hidden' ); ?>">
            <input hidden
                   name="output-archive-url"
                   id="output-archive-url" 
                   value="<?php echo $bot_meta['output_archive_url'][0] ?>">
            <input readonly
                   name="output-archive-filename"
                   id="output-archive-filename"
                   class="regular-text code" 
                   value="<?php echo $bot_meta['output_archive_filename'][0] ?>">
            <a class="button-secondary"
               id="output-archive-link"
               href="<?php echo $bot_meta['output_archive_url'][0] ?>"
               target="_blank">View archive</a>
            <button class="button-primary" id="output-archive-remove">Remove</button>
          </p>
          <p id="output-archive-date-wrapper" class="<?php echo ( $has_archive ? '' : 'hidden' ); ?>">
            <label for="output-archive-date"> Archive date</label><br/>
            <input type="date" name="output-archive-date" id="output-archive-date" class="regular-text" 
                   value="<?php echo $bot_meta['output_archive_date'][0] ?>">
          </p>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <label for="bot_source_url">Source code URL(s)</label>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <textarea class="w-100" name="bot_source_url"><?php echo $bot_meta['bot_source_url'][0]; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <h4>Additional details</h4>
        </td>
      </tr>      
      <?php
        $bot_is_featured = ( array_key_exists('bot_is_featured', $bot_meta ) && $bot_meta['bot_is_featured'][0] === "on" );
        $bot_is_nsfw = ( array_key_exists('bot_is_nsfw', $bot_meta ) && $bot_meta['bot_is_nsfw'][0] === "on" );
      ?>
      <tr>
        <td class="w-100">
          <label>
            <input type="checkbox" class="w-100" id name="bot-is-featured" <?php echo ($bot_is_featured ? "checked" : ""); ?>> Featured
          </label>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <label>
            <input type="checkbox" class="w-100" id name="bot-is-nsfw" <?php echo ($bot_is_nsfw ? "checked" : ""); ?>> NSFW
          </label>
        </td>
      </tr>
    </table>
  <?php }

   function bot_tweet_fields(){
    $id = get_the_id();
    $bot_meta = get_post_meta( $id );
    $bot_tweets_hide = ( array_key_exists('bot_tweets_hide', $bot_meta ) && $bot_meta['bot_tweets_hide'][0] === "on" );

    wp_nonce_field( basename( __FILE__ ), 'bw_nonce' ); ?>
    <table class="w-100">
      <tr>
        <td class="w-100 w-m-100">
          <label for="bot_tweets">One URL per line</label>
        </td>
      </tr>
      <tr>
        <td class="w-77 w-m-100">
          <textarea class="w-100" name="bot_tweets" rows="5"><?php echo $bot_meta['bot_tweets'][0]; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="w-100 w-m-100">
          <label>
            <input type="checkbox" class="w-100" name="bot-tweets-hide" <?php echo ($bot_tweets_hide ? "checked" : ""); ?>> Hide example output
          </label>
        </td>
      </tr>
    </table>
  <?php }   

  function bot_author_info_field(){
    $id = get_the_id();
    $bot_meta = get_post_meta( $id );

    wp_nonce_field( basename( __FILE__ ), 'bw_nonce' ); ?>
    <table class="w-100">
      <tr>
        <td class="w-33 w-m-100">
          <label for="bot_author_name">Author's name</label>
          <p>Put each author on a separate line in the following format:</p>
          <pre><code>Author name, URL (optional)</code></pre>
        </td>
      </tr>
      <tr>
        <td>
          <textarea class="w-100" rows="5" name="bot_author_info"><?php echo $bot_meta['bot_author_info'][0]; ?></textarea>
        </td>
      </tr>
    </table>

    <?php
    if ( isset( $bot_meta['bot_author_email'] ) && strlen( $bot_meta['bot_author_email'][0] ) > 0 ){
      $botmaker_badge_awarded = ( array_key_exists('botmaker_badge_awarded', $bot_meta ) && $bot_meta['botmaker_badge_awarded'][0] == "on" );
      $bot_author_emails = implode( ', ', preg_split('/\s+/', $bot_meta['bot_author_email'][0] ) );
      ?>
      <div class="notice <?php echo ($botmaker_badge_awarded ? "notice-info" : "notice-warning"); ?> inline">
        <p>
          <a href="https://badgr.com/issuers/5e1fd98146e0fb003395f0b4/badges/5e1fe33c46e0fb0035a12afc/award" target="_blank">Award botmaker badge</a> to <code><?php echo $bot_author_emails; ?></code>.
        </p>
        <p>
          <label>
            <input type="checkbox" class="w-100" name="botmaker_badge_awarded" <?php echo ($botmaker_badge_awarded ? "checked" : ""); ?>> Awarded
          </label>
        </p>
      </div>
    <?php }
  }   

  function add_bot_tweets(){
    add_meta_box(
      'bot-tweets-meta',
      esc_html__( 'Example output' ),
      array($this, 'bot_tweet_fields' ),
      'bot',
      'normal',
      'core'      
    );
  }

  function add_bot_info(){
    add_meta_box(
      'bot-info-meta',
      esc_html__( 'About the bot' ),
      array($this, 'bot_info_fields' ),
      'bot',
      'normal',
      'core'      
    );
  }

  function add_bot_author_info(){
    add_meta_box(
      'bot-author-meta',
      esc_html__( 'About the author(s)' ),
      array($this, 'bot_author_info_field' ),
      'bot',
      'normal',
      'core'      
    );
  }

  function save_meta( $post_id ) {
    if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce') ){
      return;
    }

    $post_type = get_post_type( $post_id );
    if ( $post_type === 'bot' ){
      update_post_meta($post_id, 'bot_author_info', $_POST['bot_author_info']);
      update_post_meta($post_id, 'bot_url', $_POST['bot_url']);
      update_post_meta($post_id, 'bot_source_url', $_POST['bot_source_url']);
      update_post_meta($post_id, 'botmaker_badge_awarded', $_POST['botmaker_badge_awarded']);
      update_post_meta($post_id, 'bot_tweets', $_POST['bot_tweets']);

      update_post_meta($post_id, 'bot_tweets_hide', $_POST['bot-tweets-hide']);
      update_post_meta($post_id, 'bot_is_featured', $_POST['bot-is-featured']);
      update_post_meta($post_id, 'bot_is_nsfw', $_POST['bot-is-nsfw']);

      update_post_meta($post_id, 'output_archive_url', $_POST['output-archive-url']);
      update_post_meta($post_id, 'output_archive_filename', $_POST['output-archive-filename']);
      update_post_meta($post_id, 'output_archive_date', $_POST['output-archive-date']);

      if ($_POST['bot_tweets']){
        $bot_tweets = explode("\n", str_replace("\r", "", $_POST['bot_tweets']));
        $twitter_oembed = 'https://publish.twitter.com/oembed?url=';

        $bot_tweets_html = '';

        if ($bot_tweets){
          foreach ($bot_tweets as $index=>$tweet_url) {
            if ( strpos( $tweet_url, 'twitter.com/' ) !== false ){
              $data = file_get_contents($twitter_oembed . urlencode( $tweet_url ));
              $bot_tweets_html .= json_decode($data)->html;              
            }
            elseif ( strpos( $tweet_url, 'youtube.com/' ) !== false ){
              $data = file_get_contents( 'https://www.youtube.com/oembed?url=' . urlencode( $tweet_url ) . '&format=json' );
              $bot_tweets_html .= json_decode($data)->html;
            }
            elseif ( strpos( $tweet_url, 'twitch.tv/' ) !== false ){
              $data = file_get_contents( 'https://api.twitch.tv/v5/oembed?url=' . urlencode( $tweet_url ) );
              $bot_tweets_html .= json_decode($data)->html;
            }
            elseif ( strpos( $tweet_url, 'tumblr.com/post/' ) !== false ){
              $data = file_get_contents( 'https://www.tumblr.com/oembed/1.0?url=' . urlencode( $tweet_url ) );
              $bot_tweets_html .= json_decode($data)->html;
            }
            elseif ( strpos( $tweet_url, 'facebook.com/' ) !== false ){
              $bot_tweets_html .= '<blockquote><div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id))  return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-post" data-href="' . $tweet_url . '"></div></blockquote>';
              // $browser = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.16 (KHTML, like Gecko) \Chrome/24.0.1304.0 Safari/537.16';
              // curl_setopt($ch, CURLOPT_USERAGENT, $browser);

              // $options = array(
              //   'http'=>array(
              //     'method'=>"GET",
              //     'header'=>"Accept-language: en\r\n" .
              //               "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
              //   )
              // );

              // $context = stream_context_create($options);
              // $json_post = @file_get_contents('https://www.facebook.com/plugins/video/oembed.json/?url=' . urlencode( $tweet_url ), false, $context);
              // $data = json_decode($json_post);
              // $bot_tweets_html .= $data->html;
            }
            elseif ( strpos( $tweet_url, 'botsin.space/' ) !== false ||
                     strpos( $tweet_url, 'beeping.town/' ) !== false ||
                     strpos( $tweet_url, 'mastodon.social/' ) !== false ) {
              $bot_tweets_html .= '<blockquote><iframe src="' . $tweet_url . '/embed" class="mastodon-embed" style="max-width: 100%; border: 0" width="400"></iframe></blockquote>';
            }
          }

          update_post_meta($post_id, 'bot_tweets_html', $bot_tweets_html);        
        }        
      }
    }
  }
}

$bots_post_type = new BotsPostType;
$bots_post_type->post_type_setup();
