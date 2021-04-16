<?php

class TutorialsPostType {
  function post_type_setup() {
    $this->create_post_type();

    add_action( 'add_meta_boxes', array( $this, 'add_tutorial_info' ) );
    add_action( 'save_post',  array( $this, 'save_meta' ), 10, 2 );
    add_filter( 'enter_title_here', array( $this, 'change_post_title_placeholder' ) );
  }

  function create_post_type() {
    add_action( 'init', array( $this, 'register_tutorial_post_type' ), 40 );
  }

  function change_post_title_placeholder( $title ){
    $screen = get_current_screen();
  
    if  ( $screen->post_type == 'tutorial' ) {
      $title = "Title...";
    }    
   return $title;
  }
  

  function register_tutorial_post_type() {
    $args = array(
      'label' => __( 'Post Type', 'tutorialwiki' ),
      'description' => __( 'Post Type Description', 'tutorialwiki' ),
      'labels' => array(
        'name' => __( 'Tutorials', 'post type general name', 'tutorialwiki' ),
        'singular_name' => __( 'Tutorial', 'post type singular name', 'tutorialwiki' ),
        'menu_name' => __( 'Tutorials', 'admin menu', 'tutorialwiki' ),
        'name_admin_bar' => __( 'Tutorial', 'add new on admin bar', 'tutorialwiki' ),
        'archives' => __( 'Tutorial Archives', 'tutorialwiki' ),
        'attributes' => __( 'Tutorial Attributes', 'tutorialwiki' ),
        'add_new' => __( 'Add New', 'book', 'tutorialwiki' ),
        'add_new_item' => __( 'Add New Tutorial', 'tutorialwiki' ),
        'new_item' => __( 'New Tutorial', 'tutorialwiki' ),
        'edit_item' => __( 'Edit Tutorial', 'tutorialwiki' ),
        'update_item' => __( 'Update Tutorial', 'tutorialwiki' ),
        'view_item' => __( 'View Tutorial', 'tutorialwiki' ),
        'view_items' => __( 'View Tutorials', 'tutorialwiki' ),
        'all_items' => __( 'All Tutorials', 'tutorialwiki' ),
        'search_items' => __( 'Search Tutorials', 'tutorialwiki' ),
        'parent_item_colon' => __( 'Parent Tutorials:', 'tutorialwiki' ),
        'not_found' => __( 'No Tutorials found.', 'tutorialwiki' ),
        'not_found_in_trash' => __( 'No Tutorials found in Trash.', 'tutorialwiki' ),
        'featured_image' => __( 'Featured Image', 'tutorialwiki' ),
        'set_featured_image' => __( 'Set featured image', 'tutorialwiki' ),
        'remove_featured_image' => __( 'Remove featured image', 'tutorialwiki' ),
        'use_featured_image' => __( 'Use as featured image', 'tutorialwiki' ),
        'insert_into_item' => __( 'Insert into Tutorial', 'tutorialwiki' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Tutorial', 'tutorialwiki' ),
        'items_list' => __( 'Items list', 'tutorialwiki' ),
        'items_list_navigation' => __( 'Items list navigation', 'tutorialwiki' ),
        'filter_items_list' => __( 'Filter items list', 'tutorialwiki' )
      ),
      'taxonomies' => array('post_tag', 'programing_language', 'network'),
      'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
      'hierarchical' => true,
      'public' => true,
      'show_ui' => true,
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
      'rest_base' => 'tutorial',
      'rest_controller_class' => 'WP_REST_Posts_Controller'
    );
    register_post_type( 'tutorial', $args );
  }

  function tutorial_info_fields(){
    $id = get_the_id();
    $tutorial_meta = get_post_meta( $id );

    // echo "<pre><code>";
    // var_dump($tutorial_meta);
    // echo "</code></pre>";

    wp_nonce_field( basename( __FILE__ ), 'bw_nonce' ); ?>
    <table class="w-100">
      <tr>
        <td>
          <label for="tutorial_author_url">URL of the tutorial</label>
        </td>
      </tr>
      <tr>
        <td>
          <input type="text" class="w-100" name="tutorial_url" value="<?php echo $tutorial_meta['tutorial_url'][0]; ?>">
        </td>
      </tr>
      <tr>
        <td>
          <label for="tutorial_author_name">Author(s)</label>
          <p>Put each author on a separate line in the following format:</p>
          <pre><code>Author name, URL (optional)</code></pre>
        </td>
      </tr>
      <tr>
        <td>
          <textarea class="w-100" rows="5" name="tutorial_author_info"><?php echo $tutorial_meta['tutorial_author_info'][0]; ?></textarea>
        </td>
      </tr>      
    </table>
  <?php }   

  function add_tutorial_info(){
    add_meta_box(
      'tutorial-info-meta',
      esc_html__( 'External tutorial info' ),
      array($this, 'tutorial_info_fields' ),
      'tutorial',
      'normal',
      'core'      
    );
  }

  function save_meta( $post_id ) {
    if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce') ){
      return;
    }

    $post_type = get_post_type( $post_id );
    if ( $post_type === 'tutorial' ){
      update_post_meta($post_id, 'tutorial_author_info', $_POST['tutorial_author_info']);
      update_post_meta($post_id, 'tutorial_url', $_POST['tutorial_url']);
    }
  }
}

$tutorials_post_type = new TutorialsPostType;
$tutorials_post_type->post_type_setup();
