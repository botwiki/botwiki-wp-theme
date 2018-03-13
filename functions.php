<?php
require_once 'vendor/autoload.php';

/*
*  Author: Todd Motto | @toddmotto
*  URL: html5blank.com | @html5blank
*  Custom functions, support, custom post types and more.
*/

/*------------------------------------*\
External Modules/Files
\*------------------------------------*/

require 'includes/helpers.php';
require 'includes/blog-slug.php';
require 'includes/search-filters.php';
require 'includes/navbar.php';
require 'includes/background-image-dominant-color.php';
require 'includes/header-links.php';
require 'includes/tutorials-note.php';
require 'includes/random-bot.php';
require 'includes/show-post-id.php';
require 'includes/card-title.php';
require 'includes/add-tags-to-pages.php';


/*------------------------------------*\
Custom post types
\*------------------------------------*/

require 'includes/post-types/bots.php';
// require 'includes/post-types/tutorials.php';
require 'includes/post-types/resources.php';

/*------------------------------------*\
Short codes
\*------------------------------------*/

require 'includes/shortcodes/post-links.php';

/*------------------------------------*\
Theme Support
\*------------------------------------*/

if (!isset($content_width)){
  $content_width = 980;
}

if ( function_exists( 'add_theme_support' ) ){
 
  add_theme_support( 'menus' );

 
  add_theme_support( 'post-thumbnails' );
  add_image_size( 'large', 700, '', true);
  add_image_size( 'medium', 250, '', true);
  add_image_size( 'small', 120, '', true);
  add_image_size( 'custom-size', 700, 200, true);

 
  /*add_theme_support( 'custom-background', array(
'default-color' => 'FFF',
'default-image' => get_template_directory_uri() . '/img/bg.jpg'
  ));*/

 
  /*add_theme_support( 'custom-header', array(
'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
'header-text'			=> false,
'default-text-color'		=> '000',
'width'				=> 1000,
'height'			=> 198,
'random-default'		=> false,
'wp-head-callback'		=> $wphead_cb,
'admin-head-callback'		=> $adminhead_cb,
'admin-preview-callback'	=> $adminpreview_cb
  ));*/

 
  add_theme_support( 'automatic-feed-links' );
  load_theme_textdomain( 'botwiki', get_template_directory() . '/languages' );
}

/*------------------------------------*\
Functions
\*------------------------------------*/

// function botwiki_site_nav(){
//   wp_nav_menu( array(
//     'theme_location'  => 'header-menu',
//     'container' => false,
//     'menu_class' => '',
//     'fallback_cb' => '__return_false',
//     'items_wrap' => '<ul id="%1$s" class="navbar-nav mr-auto mt-2 mt-lg-0 %2$s">%3$s</ul>',
//     'depth' => 2,
//     'walker' => new b4st_walker_nav_menu()
//   ) );
// }

function botwiki_site_nav(){
  $frontpage_id = get_option( 'page_on_front' );
  echo get_post_field( 'post_content', $frontpage_id);

}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts(){
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    $js_file_path = get_template_directory() . '/libs/bootstrap/bootstrap.min.js';

    wp_register_script( 'bootstrap-js', get_template_directory_uri() . '/libs/bootstrap/bootstrap.min.js', array( 'jquery' ), filemtime( $js_file_path ));
    wp_enqueue_script( 'bootstrap-js' );

    $js_file_path = get_template_directory() . '/js/scripts.min.js';

    wp_register_script( 'scripts', get_template_directory_uri() . '/js/scripts.min.js', array( 'jquery' ), filemtime( $js_file_path ) );
    wp_enqueue_script( 'scripts' );
  }
}

function load_twitter_js(){
  global $post;
  if ( strpos( get_post_field( 'post_content', $post->ID ), 'twitter-tweet' ) !== false ){ ?>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  <?php }
}

function load_highlight_js(){
  global $post;
  if ( strpos( get_post_field( 'post_content', $post->ID ), '<code>' ) !== false ){
    wp_register_style( 'highlight-monokai-sublime', get_template_directory_uri() . '/libs/highlight.js/styles/monokai-sublime.css' );
    wp_enqueue_style( 'highlight-monokai-sublime' );

    wp_register_script( 'highlight', get_template_directory_uri() . '/libs/highlight.js/highlight.pack.js', array( 'jquery' ));
    wp_enqueue_script( 'highlight' );

    wp_add_inline_script( 'highlight', "jQuery(function(){if (hljs){hljs.initHighlighting.called = false;hljs.initHighlighting();}});", 'after' );
  }
}

function load_styles(){
  $css_file_path = get_stylesheet_directory() . '/libs/bootstrap/bootstrap.min.css';

  wp_register_style( 'bootstrap-styles', get_template_directory_uri() . '/libs/bootstrap/bootstrap.min.css', array(), filemtime( $css_file_path ), 'all' );
  wp_enqueue_style( 'bootstrap-styles' );

  $css_file_path = get_stylesheet_directory() . '/css/styles.min.css';

  wp_register_style( 'styles', get_template_directory_uri() . '/css/styles.min.css', array(), filemtime( $css_file_path ), 'all' );
  wp_enqueue_style( 'styles' );
}

function load_admin_js_styles(){
  $js_file_path = get_template_directory() . '/admin-js/scripts.min.css';

  error_log("##########################\n");

  wp_register_script( 'admin-js', get_template_directory_uri() . '/admin-js/scripts.min.js', array( 'jquery' ), filemtime( $js_file_path ));
  wp_enqueue_script( 'admin-js' );


  $css_file_path = get_stylesheet_directory() . '/admin-css/styles.min.css';
  wp_register_style( 'admin-styles', get_template_directory_uri() . '/admin-css/styles.min.css', array(), filemtime( $css_file_path ), 'all' );
  wp_enqueue_style( 'admin-styles' );
}

function register_site_menu(){
  register_nav_menus(array(
    'header-menu' => __( 'Header Menu', 'botwiki' ),
    'sidebar-menu' => __( 'Sidebar Menu', 'botwiki' ),
    'extra-menu' => __( 'Extra Menu', 'botwiki' )
  ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '' ){
  $args['container'] = false;
  return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var){
  return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist){
  return str_replace( 'rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes){
  global $post;
  if (is_home()) {
    $key = array_search( 'blog', $classes);
    if ($key > -1) {
        unset($classes[$key]);
    }
  } elseif (is_page()) {
    $classes[] = sanitize_html_class($post->post_name);
  } elseif (is_singular()) {
    $classes[] = sanitize_html_class($post->post_name);
  }

  return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists( 'register_sidebar' )){
 
  register_sidebar(array(
    'name' => __( 'Widget Area 1', 'botwiki' ),
    'description' => __( 'Description for this widget-area...', 'botwiki' ),
    'id' => 'widget-area-1',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));

 
  register_sidebar(array(
    'name' => __( 'Widget Area 2', 'botwiki' ),
    'description' => __( 'Description for this widget-area...', 'botwiki' ),
    'id' => 'widget-area-2',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style(){
  global $wp_widget_factory;
  remove_action( 'wp_head', array(
    $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
    'recent_comments_style'
  ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function botwiki_site_pagination(){
  global $wp_query;
  $big = 999999999;
  echo paginate_links(array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var( 'paged' )),
    'total' => $wp_query->max_num_pages
  ));
}


function bw_add_custom_types( $query ) {
  if( is_tag() && $query->is_main_query() ) {
    $post_types = get_post_types();
    $query->set( 'post_type', $post_types );
  }
}

add_filter( 'pre_get_posts', 'bw_add_custom_types' );


// Custom Excerpts
function html5wp_index($length){
  return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using bw_excerpt( 'html5wp_custom_post' );
function html5wp_custom_post($length){
  return 40;
}

// Create the Custom Excerpts callback
function bw_excerpt($post_data, $length_callback = '', $more_callback = '' ){

  global $post;
  if (function_exists($length_callback)) {
    add_filter( 'excerpt_length', $length_callback);
  }
  if (function_exists($more_callback)) {
    add_filter( 'excerpt_more', $more_callback);
  }
  $output = get_the_excerpt();
  $output = apply_filters( 'wptexturize', $output);
  $output = apply_filters( 'convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more){
  global $post;
  return '...';
}

// Remove Admin bar
// function remove_admin_bar(){
//   return true;
// }

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag){
  return preg_replace( '~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html ){
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html);
  return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults){
  $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
  $avatar_defaults[$myavatar] = "Custom Gravatar";
  return $avatar_defaults;
}

/*------------------------------------*\
Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action( 'init', 'html5blank_header_scripts' );
add_action( 'wp_enqueue_scripts', 'load_styles' );
add_action( 'wp_enqueue_scripts', 'load_twitter_js' );
add_action( 'wp_enqueue_scripts', 'load_highlight_js' );
add_action( 'admin_enqueue_scripts', 'load_admin_js_styles' );
add_action( 'init', 'register_site_menu' );
add_action( 'widgets_init', 'my_remove_recent_comments_style' );
add_action( 'init', 'botwiki_site_pagination' );

// Remove Actions
remove_action( 'wp_head', 'feed_links_extra', 3);
remove_action( 'wp_head', 'feed_links', 2);
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0);
remove_action( 'wp_head', 'start_post_rel_link', 10, 0);
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter( 'avatar_defaults', 'html5blankgravatar' );
add_filter( 'body_class', 'add_slug_to_body_class' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
// add_filter( 'nav_menu_css_class', 'my_css_attributes_filter', 100, 1);by default)
// add_filter( 'nav_menu_item_id', 'my_css_attributes_filter', 100, 1);default)
// add_filter( 'page_css_class', 'my_css_attributes_filter', 100, 1);
add_filter( 'the_category', 'remove_category_rel_from_category_list' );
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );
add_filter( 'excerpt_more', 'html5_blank_view_article' );
// add_filter( 'show_admin_bar', 'remove_admin_bar' );
add_filter( 'style_loader_tag', 'html5_style_remove' );
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10);

// Remove Filters
remove_filter( 'the_excerpt', 'wpautop' );

// Shortcodes
add_shortcode( 'html5_shortcode_demo', 'html5_shortcode_demo' );
add_shortcode( 'html5_shortcode_demo_2', 'html5_shortcode_demo_2' );

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
Custom Post Types
\*------------------------------------*/

/*------------------------------------*\
ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null){
  return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>';
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null){
  return '<h2>' . $content . '</h2>';
}

// Allow <p> tags in author biographical bio.

remove_filter( 'pre_user_description',  'wp_filter_kses' );
add_filter(  'pre_user_description',  'wp_filter_post_kses' );

add_post_type_support( 'page', 'excerpt' );

?>
