<?php

class ResourcesPostType {
  function post_type_setup() {
    $this->create_post_type();

    add_action( 'add_meta_boxes', array( $this, 'add_resource_info' ) );
    add_action( 'save_post',  array( $this, 'save_meta' ), 10, 2 );
    add_filter( 'enter_title_here', array( $this, 'change_post_title_placeholder' ) );
    add_filter( 'post_type_link', array( $this, 'resource_page_link' ), 1, 3 );
    add_filter( 'template_redirect', array( $this, 'external_resource_redirect' ) );
    add_action( 'admin_bar_menu', array( $this, 'add_pending_resources_link' ), 100 );

  }

  function create_post_type() {
    add_action( 'init', array( $this, 'register_resource_post_type' ), 40 );
    add_action( 'init', array( $this, 'register_resource_type_taxonomy' ), 40 );
  }

  function external_resource_redirect() {
    global $wp_query;
    if ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] === 'resource' ){
      $external_resource_url = get_post_meta( $wp_query->posts[0]->ID, 'resource_url', true );
      $content = get_post_field( 'post_content', $wp_query->posts[0]->ID );

      if ( empty( $content ) && !empty( $external_resource_url ) ){
        wp_redirect( $external_resource_url );
      }
    }
  }

  function resource_page_link( $post_link, $post ){
    $post = get_post($post->ID);
    $post_type = get_post_type( $post->ID );

    if ( is_object( $post ) ){
      $post_link_resource = get_post_meta( $post->ID, 'resource_url', true );


      if ( empty( $content ) && !empty( $external_resource_url ) ){
        $post_link = $post_link_resource;
      }
      else{
        $terms = wp_get_object_terms( $post->ID, 'resource_type' );

        if( $terms ){
            $post_link = str_replace( '%resource_type%' , $terms[0]->slug , $post_link );
        }
      }
    }

    return $post_link;  
  }

  function change_post_title_placeholder( $title ){
    $screen = get_current_screen();
  
    if  ( $screen->post_type == 'resource' ) {
      $title = "Title...";
    }    
   return $title;
  }

  function register_resource_post_type() {
    $args = array(
      'label' => __( 'Post Type', 'botwiki' ),
      'description' => __( 'Post Type Description', 'botwiki' ),
      'labels' => array(
        'name' => __( 'Resources', 'post type general name', 'botwiki' ),
        'singular_name' => __( 'Resource', 'post type singular name', 'botwiki' ),
        'menu_name' => __( 'Resources', 'admin menu', 'botwiki' ),
        'name_admin_bar' => __( 'Resource', 'add new on admin bar', 'botwiki' ),
        'archives' => __( 'Resource Archives', 'botwiki' ),
        'attributes' => __( 'Resource Attributes', 'botwiki' ),
        'add_new' => __( 'Add New', 'book', 'botwiki' ),
        'add_new_item' => __( 'Add New Resource', 'botwiki' ),
        'new_item' => __( 'New Resource', 'botwiki' ),
        'edit_item' => __( 'Edit Resource', 'botwiki' ),
        'update_item' => __( 'Update Resource', 'botwiki' ),
        'view_item' => __( 'View Resource', 'botwiki' ),
        'view_items' => __( 'View Resources', 'botwiki' ),
        'all_items' => __( 'All Resources', 'botwiki' ),
        'search_items' => __( 'Search Resources', 'botwiki' ),
        'parent_item_colon' => __( 'Parent Resources:', 'botwiki' ),
        'not_found' => __( 'No Resources found.', 'botwiki' ),
        'not_found_in_trash' => __( 'No Resources found in Trash.', 'botwiki' ),
        'featured_image' => __( 'Featured Image', 'botwiki' ),
        'set_featured_image' => __( 'Set featured image', 'botwiki' ),
        'remove_featured_image' => __( 'Remove featured image', 'botwiki' ),
        'use_featured_image' => __( 'Use as featured image', 'botwiki' ),
        'insert_into_item' => __( 'Insert into Resource', 'botwiki' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Resource', 'botwiki' ),
        'items_list' => __( 'Items list', 'botwiki' ),
        'items_list_navigation' => __( 'Items list navigation', 'botwiki' ),
        'filter_items_list' => __( 'Filter items list', 'botwiki' )
      ),
      'taxonomies' => array('resource_type', 'post_tag', 'programing_language', 'network'),
      'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail', 'author', 'revisions', 'custom-fields' ),
      'hierarchical' => true,
      'public' => true,
      'show_ui' => true,
      'rewrite' => array( 'slug' => 'resource/%resource_type%' ),
      // 'rewrite' => array( 'slug' => 'resources' ),
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
      'rest_base' => 'resource',
      'rest_controller_class' => 'WP_REST_Posts_Controller'
    );
    register_post_type( 'resource', $args );
  }

  function register_resource_type_taxonomy() {
    $labels = array(
      'name'                       => _x( 'Resource Types', 'Taxonomy General Name', 'botwiki' ),
      'singular_name'              => _x( 'Resource Type', 'Taxonomy Singular Name', 'botwiki' ),
      'menu_name'                  => __( 'Resource Type', 'botwiki' ),
      'all_items'                  => __( 'All Languages', 'botwiki' ),
      'parent_item'                => __( 'Parent Resource Type', 'botwiki' ),
      'parent_item_colon'          => __( 'Parent Resource Type:', 'botwiki' ),
      'new_item_name'              => __( 'New Resource Type Name', 'botwiki' ),
      'add_new_item'               => __( 'Add New Resource Type', 'botwiki' ),
      'edit_item'                  => __( 'Edit Resource Type', 'botwiki' ),
      'update_item'                => __( 'Update Resource Type', 'botwiki' ),
      'view_item'                  => __( 'View Resource Type', 'botwiki' ),
      'separate_items_with_commas' => __( 'Separate items with commas', 'botwiki' ),
      'add_or_remove_items'        => __( 'Add or remove items', 'botwiki' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'botwiki' ),
      'popular_items'              => __( 'Popular Resource Types', 'botwiki' ),
      'search_items'               => __( 'Search Resource Types', 'botwiki' ),
      'not_found'                  => __( 'Not Found', 'botwiki' ),
      'items_list'                 => __( 'Resource Type list', 'botwiki' ),
      'items_list_navigation'      => __( 'Resource Type list navigation', 'botwiki' )
    );

    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => true,
      'show_in_rest'               => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true
    );
    register_taxonomy( 'resource_type', array( 'resource' ), $args );
  }

  function resource_info_fields(){
    $id = get_the_id();
    $resource_meta = get_post_meta( $id );

    // echo "<pre><code>";
    // var_dump($resource_meta);
    // echo "</code></pre>";

    wp_nonce_field( basename( __FILE__ ), 'bw_nonce' ); ?>
    <table class="w-100">
      <tr>
        <td>
          <label for="resource_author_url">URL of the resource</label>
        </td>
      </tr>
      <tr>
        <td>
          <input type="text" class="w-100" name="resource_url" value="<?php echo $resource_meta['resource_url'][0]; ?>">
        </td>
      </tr>
      <tr>
        <td>
          <label for="resource_author_name">Author(s)</label>
          <p>Put each author on a separate line in the following format:</p>
          <pre><code>Author name, URL (optional)</code></pre>
        </td>
      </tr>
      <tr>
        <td>
          <textarea class="w-100" rows="5" name="resource_author_info"><?php echo $resource_meta['resource_author_info'][0]; ?></textarea>
        </td>
      </tr>      
    </table>
    <?php
    if ( isset( $resource_meta['resource_author_email'] ) && strlen( $resource_meta['resource_author_email'][0] ) > 0 ){
      $botmaker_badge_awarded = ( array_key_exists('botmaker_badge_awarded', $resource_meta ) && $resource_meta['botmaker_badge_awarded'][0] == "on" );
      $bot_resource_author_emails = implode( ', ', preg_split('/\s+/', $resource_meta['resource_author_email'][0] ) );
      ?>
      <div class="notice <?php echo ($botmaker_badge_awarded ? "notice-info" : "notice-warning"); ?> inline">
        <p>
          <a href="https://badgr.com/issuers/5e1fd98146e0fb003395f0b4/badges/5e1fe34146e0fb0035a130e0/award" target="_blank">Award bot teacher badge</a> to <code><?php echo $bot_resource_author_emails; ?></code>.
        </p>
        <p>
          <label>
            <input type="checkbox" class="w-100" name="botmaker_badge_awarded" <?php echo ($botmaker_badge_awarded ? "checked" : ""); ?>> Awarded
          </label>
        </p>
      </div>
    <?php }
  }   


  function add_resource_info(){
    add_meta_box(
      'resource-info-meta',
      esc_html__( 'External resource info' ),
      array($this, 'resource_info_fields' ),
      'resource',
      'normal',
      'core'      
    );
  }


  function add_pending_resources_link($wp_admin_bar) {
    if ( current_user_can('administrator') ){
      $query = array(
        'post_type' => 'resource',
        'post_status' => array('pending'),
        'posts_per_page'    => -1
      );

      $pending_count = count( query_posts($query) );

      if ( $pending_count > 0 ){
        $args = array(
          'id' => 'review-pending-resources',
          'title' => 'New Resources (' . $pending_count . ')', 
          'href' => '/wp-admin/edit.php?post_status=pending&post_type=resource', 
          'meta' => array(
            'class' => 'review-pending-resources', 
            'title' => 'Review pending resources'
          )
        );
        $wp_admin_bar->add_node($args);
      }
      wp_reset_query();    
    }
  }

  function save_meta( $post_id ) {
    if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce') ){
      return;
    }

    $post_type = get_post_type( $post_id );
    if ( $post_type === 'resource' ){
      update_post_meta($post_id, 'resource_author_info', $_POST['resource_author_info']);
      update_post_meta($post_id, 'resource_url', $_POST['resource_url']);
      update_post_meta($post_id, 'botmaker_badge_awarded', $_POST['botmaker_badge_awarded']);
    }
  }
}

$resources_post_type = new ResourcesPostType;
$resources_post_type->post_type_setup();
