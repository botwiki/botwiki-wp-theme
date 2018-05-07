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
          'title' => 'Review Pending Bots (' . $pending_count . ')', 
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

    if ( isset( $_GET['networks'] ) ){
      foreach (explode(',', $_GET['networks']) as $network) {
        array_push($tax_query, array(
          'taxonomy' => 'network',
          'field' => 'slug',
          'terms' =>  $network
        ));
      }
    }


    if ( isset( $_GET['languages'] ) ){
      foreach (explode(',', $_GET['languages']) as $language) {
        array_push($tax_query, array(
          'taxonomy' => 'programing_language',
          'field' => 'slug',
          'terms' =>  $language
        ));
      }
    }


    if ( isset( $_GET['tags'] ) ){
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
      'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail', 'author' ),
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
          <p>Be sure to also add the <code>opensource</code> tag.</p>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <label for="bot_source_url">Source code URL</label>
        </td>
      </tr>
      <tr>
        <td class="w-100">
          <input type="text" class="w-100" name="bot_source_url" value="<?php echo $bot_meta['bot_source_url'][0]; ?>">
        </td>
      </tr>
    </table>
  <?php }   

   function bot_tweet_fields(){
    $id = get_the_id();
    $bot_meta = get_post_meta( $id );

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
    </table>
  <?php }   

  function bot_author_info_field(){
    $id = get_the_id();
    $bot_meta  = get_post_meta( $id );

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
  <?php }   

  function add_bot_tweets(){
    add_meta_box(
      'bot-tweets-meta',
      esc_html__( 'Selected tweets' ),
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
      update_post_meta($post_id, 'bot_tweets', $_POST['bot_tweets']);

      if ($_POST['bot_tweets']){
        $bot_tweets = explode("\n", str_replace("\r", "", $_POST['bot_tweets']));
        $twitter_oembed = 'https://publish.twitter.com/oembed?url=';

        $bot_tweets_html = '';

        if ($bot_tweets){
          foreach ($bot_tweets as $index=>$tweet_url) {
            $data = file_get_contents($twitter_oembed . urlencode($tweet_url));
            $bot_tweets_html .= json_decode($data)->html;
          }

          update_post_meta($post_id, 'bot_tweets_html', $bot_tweets_html);        
        }        
      }
    }
  }
}

$bots_post_type = new BotsPostType;
$bots_post_type->post_type_setup();
