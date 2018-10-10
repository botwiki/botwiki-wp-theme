<?php

/* Add /blog slug to default post types. */

class Blog_Slug {
  public function __construct() {
    add_action( 'init', array( $this, 'override_default_post_type' ) );
    add_action( 'pre_get_posts', array( $this, 'change_post_count' ) );
  }

  public function override_default_post_type( $content ) { 
    register_post_type( 'post', array(
        'labels' => array(
            'name_admin_bar' => _x( 'Post', 'add new on admin bar' ),
        ),
        'public'  => true,
        '_builtin' => false, 
        '_edit_link' => 'post.php?post=%d', 
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'blog' ),
        'query_var' => false,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats' ),
    ) );
  }

    function change_post_count($query){
      global $wp_the_query;

      if ( ( ! is_admin() ) && ( $query === $wp_the_query ) ) {
        if ( in_array ( $query->get('post_type'), array('post') ) ){
          $query->set( 'posts_per_page', 21 );
        }
        else{
          $query->set( 'posts_per_page', 24 );
        }
      }
    }
}

$blog_slug_init = new Blog_Slug();
