<?php

/* Add /blog slug to default post types. */

class Blog_Slug {
  public function __construct() {
    add_action( 'init', array( $this, 'override_default_post_type' ) );
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
}

$blog_slug_init = new Blog_Slug();
