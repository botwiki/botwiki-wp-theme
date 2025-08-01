<?php
header("Access-Control-Allow-Origin: *");

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
require 'includes/opensource-bots.php';
require 'includes/show-post-id.php';
require 'includes/card-title.php';
require 'includes/add-tags-to-pages.php';
require 'includes/extra-user-fields.php';
require 'includes/contributors.php';
require 'includes/profile-page-link.php';
require 'includes/new-post-instructions.php';
require 'includes/rss-feed.php';
require 'includes/wp-json-api.php';
// Disabled for performance reasons.
// require 'includes/archive-page.php'; 

if ( !class_exists( 'simple_html_dom_node' ) ){
  require 'includes/simple_html_dom.php';
}

require 'includes/newonbotwiki.php';


/*------------------------------------*\
Custom post types
\*------------------------------------*/

require 'includes/post-types/bots.php';
// require 'includes/post-types/tutorials.php';
require 'includes/post-types/resources.php';

/*------------------------------------*\
Short codes
\*------------------------------------*/

require 'includes/shortcodes/admin-emails.php';
require 'includes/shortcodes/botmaker-badges-count.php';
require 'includes/shortcodes/describe.php';
require 'includes/shortcodes/post-links.php';
require 'includes/shortcodes/shortcodes-list.php';

/*------------------------------------*\
Tools
\*------------------------------------*/

require 'includes/tools/screenshots.php';

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

function botwiki_site_nav(){ ?>
  <div class="row w-100">
    <div class="col-sm-12 pl-0 pr-0">
      <?php
        $frontpage_id = get_option( 'page_on_front' );
        echo get_post_field( 'post_content', $frontpage_id);
      ?>
    </div>
    <?php if (!is_search()){ ?>
    <div class="col-sm-12 pt-4 pr-4 pl-4 pb-4">
      <?php echo get_search_form( false ); ?>
    </div>
    <?php } ?>
  </div>
<?php }

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts(){
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    $js_file_path = get_template_directory() . '/libs/bootstrap/bootstrap.min.js';

    wp_register_script( 'bootstrap-js', get_template_directory_uri() . '/libs/bootstrap/bootstrap.min.js', array( 'jquery' ), filemtime( $js_file_path ));
    wp_enqueue_script( 'bootstrap-js' );
    $dependencies = array( 'jquery', 'select2-js' );

    $js_file_path = get_template_directory() . '/libs/tilt/tilt.jquery.min.js';

    wp_register_script( 'tilt-js', get_template_directory_uri() . '/libs/tilt/tilt.jquery.min.js', array( 'jquery' ), filemtime( $js_file_path ));
    wp_enqueue_script( 'tilt-js' );    
    $dependencies[] = 'tilt-js';
    

    $js_file_path = get_template_directory() . '/libs/js-cookie/js.cookie-2.2.1.min.js';
    wp_register_script( 'js-cookie-js', get_template_directory_uri() . '/libs/js-cookie/js.cookie-2.2.1.min.js', array(), filemtime( $js_file_path ));
    wp_enqueue_script( 'js-cookie-js' );
    $dependencies[] = 'js-cookie-js';


    global $post;

    if ( strpos( get_post_field( 'post_content', $post->ID ), '<code>' ) !== false ){
      $dependencies[] = 'highlight';
    }


    wp_register_script( 'scripts', get_template_directory_uri() . '/js/scripts.min.js', $dependencies, filemtime( $js_file_path ) );
    wp_enqueue_script( 'scripts' );
  }
}

function load_social_media_embed_js(){
  global $post;
  $html = get_post_field( 'post_content', $post->ID );
  $html_obj = str_get_html( $html );
  
  if ( strpos( $html, 'twitter-tweet' ) !== false ){
    // Tweet embeds.
  ?>
    <!-- <script defer src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> -->
  <?php }
    if ( strpos( $html, 'mastodon-embed' ) !== false ){
      // Mastodon post embeds. Each domain needs its own script.
      $domains = array();
      $iframes = $html_obj->find('iframe');
      global $helpers;

      foreach( $iframes as $iframe ){
        $toot_url = $iframe->src;
        $domain = $helpers->get_domain_from_url( $toot_url );

        if ( !in_array( $domain, $domains ) ){
          $domains[] = $domain;     
        }
      } 

      foreach ( $domains as $domain ) {
        echo '<script src="https://' . $domain . '/embed.js" async="async"></script>';
      }
    ?>
  <?php }
}

function load_mastodon_js(){
  global $post;
  global $helpers;

  $bot_tweets_html = get_post_meta( $post->ID, 'bot_tweets_html', true );
  $urls = wp_extract_urls($bot_tweets_html);

  foreach ( $urls as $url ){
    if ( $helpers->is_mastodon_instance($url) ){
      echo $helpers->get_mastodon_embed_script_tag($helpers->get_domain_from_url($url));
      break;  
    }
  }
}

function load_js_libraries(){
  global $post;

  if ( strpos( get_post_field( 'post_content', $post->ID ), '<code' ) !== false ){
    wp_register_style( 'highlight-dark', get_template_directory_uri() . '/libs/highlight.js/styles/dark.css' );
    wp_enqueue_style( 'highlight-dark' );
    wp_register_script( 'highlight', get_template_directory_uri() . '/libs/highlight.js/highlight.pack.js', array( 'jquery' ) );
    wp_enqueue_script( 'highlight' );
  }

  $js_file_path = get_template_directory() . '/libs/select2/4.0.10/js/select2.min.js';

  wp_register_script( 'select2-js', get_template_directory_uri() . '/libs/select2/4.0.10/js/select2.min.js', array( 'jquery' ), filemtime( $js_file_path ) );
  wp_enqueue_script( 'select2-js' );


  // $js_file_path = get_template_directory() . '/libs/medium-editor/5.23.3/js/medium-editor.min.js';

  // wp_register_script( 'medium-editor-js', get_template_directory_uri() . '/libs/medium-editor/5.23.3/js/medium-editor.min.js', array( 'jquery' ), filemtime( $js_file_path ) );
  // wp_enqueue_script( 'medium-editor-js' );



}

function load_styles(){
  $css_file_path = get_stylesheet_directory() . '/libs/select2/4.0.10/css/select2.min.css';

  wp_register_style( 'select2-styles', get_template_directory_uri() . '/libs/select2/4.0.10/css/select2.min.css', array(), filemtime( $css_file_path ), 'all' );
  wp_enqueue_style( 'select2-styles' );

  // $css_file_path = get_stylesheet_directory() . '/libs/bootstrap/bootstrap.min.css';

  // wp_register_style( 'bootstrap-styles', get_template_directory_uri() . '/libs/bootstrap/bootstrap.min.css', array(), filemtime( $css_file_path ), 'all' );
  // wp_enqueue_style( 'bootstrap-styles' );

  $css_file_path = get_stylesheet_directory() . '/css/styles.min.css';

  wp_register_style( 'styles', get_template_directory_uri() . '/css/styles.min.css', array(), filemtime( $css_file_path ), 'all' );
  wp_enqueue_style( 'styles' );
}

function load_admin_js_styles(){
  $js_file_path = get_template_directory() . '/admin-js/scripts.min.js';

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
  return str_replace( 'rel="category tag"', 'class="badge badge-secondary" rel="tag"', $thelist);
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
function botwiki_site_pagination( $wp_query = null, $echo = true, $params = [] ){
  global $wp_query;

  $big = 999999999;
  $pages = paginate_links( array_merge( array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'format'       => '?paged=%#%',
    'current'      => max( 1, get_query_var( 'paged' ) ),
    'total'        => $wp_query->max_num_pages,
    'type'         => 'array',
    'show_all'     => false,
    'end_size'     => 3,
    'mid_size'     => 1,
    'prev_next'    => true,
    'prev_text'    => __( '«<span class="d-none d-md-inline d-lg-inline"> Prev</span>' ),
    'next_text'    => __( '<span class="d-none d-md-inline d-lg-inline">Next </span>»' ),
    // 'add_args'     => $add_args,
    'add_args'     => false,
    'add_fragment' => ''
  ), $params ) );

  $pagination = '<div class="pagination"><ul class="pagination">';
  foreach ( $pages as $page ) {
      $pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
  }

  $pagination .= '</ul></div>';

  return $pagination;
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
add_action( 'wp_enqueue_scripts', 'load_social_media_embed_js' );
add_action( 'wp_enqueue_scripts', 'load_mastodon_js' );
add_action( 'wp_enqueue_scripts', 'load_js_libraries' );
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


add_action('init', 'fix_blog_pagination');

function fix_blog_pagination(){
  global $wp_rewrite;
  add_rewrite_rule('^blog/page/?([0-9])?','index.php?post_type=post&paged=$matches[1]','top');  
  add_rewrite_endpoint( 'blog', EP_PERMALINK | EP_PAGES );
  $wp_rewrite->flush_rules();
}

function css_add_rel_preload( $html, $handle, $href, $media ) {
  if ( is_admin() ){
    return $html;
  }
  $html = <<<EOT
<link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" id='$handle' href='$href' type='text/css' media='all' />
<link rel='stylesheet' id='$handle' href='$href' type='text/css' media='all' />
EOT;
  return $html;
}

add_filter( 'style_loader_tag', 'css_add_rel_preload', 10, 4 );



function ftf_add_defer_attribute( $tag, $handle ) {
  if ( !is_admin() ){
    // $ignore_scripts = array( 'jquery', 'jquery-core' );
   
    // if ( !in_array( $handle, $ignore_scripts ) ){
    //   return str_replace( ' src', ' defer="defer" src', $tag );
    // }
  }
  return $tag;
}

add_filter( 'script_loader_tag', 'ftf_add_defer_attribute', 10, 2 );


function dequeue_jquery_migrate( $scripts ) {
    if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            [ 'jquery-migrate' ]
        );
    }
}

add_action( 'wp_default_scripts', 'dequeue_jquery_migrate' );

function add_feedly_featured_image( $content ) {
  return str_replace( 'attachment-post-thumbnail', 'attachment-post-thumbnail webfeedsFeaturedVisual', $content );
}

add_action( 'the_content_feed', 'add_feedly_featured_image' );


add_action( 'add_meta_boxes', 'add_hide_featured_image_option' );
add_action( 'save_post', 'save_hide_featured_image_option' );

function add_hide_featured_image_option(){
  add_meta_box( 'hide_featured', 'Hide Featured Image', 'render_hide_featured_image_option_metabox',['page'], 'side', 'default' );
}

function render_hide_featured_image_option_metabox( $post ){
    $hide_featured_image = get_post_meta( $post->ID, 'hide_featured_image', true ); ?>
    <label>
      <input type="checkbox" name="hide_featured_image" <?php checked( $hide_featured_image, 'on' ); ?>> Hide featured image
    </label>
  <?php }

function save_hide_featured_image_option( $post_id ){
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
    return;
  }

  if ( !current_user_can( 'edit_page', $post_id ) ){
      return;
  }

  if ( isset( $_POST['hide_featured_image'] ) ){
    update_post_meta( $post_id, 'hide_featured_image', $_POST['hide_featured_image'] );
  } else {
    delete_post_meta( $post_id, 'hide_featured_image' );
  }
}

add_filter( 'the_content', 'add_lazy_loading_img_attr' );

function add_lazy_loading_img_attr( $content ){
    $content = str_replace( '<img', '<img loading="lazy"', $content );
    return $content;
}

/*
    Helper dev functions.
*/

function log_this( $title, $data = false ){
  if ( func_num_args( ) === 0 ){
    return false;
  }
  elseif ( func_num_args( ) === 1 ){
    $data = $title;
    $title = "LOG";
  }

  $border_char = "/";
  $border_length_bottom = 100;
  $border_length_top = $border_length_bottom - strlen( $title ) - 2;

  $border_length_top_left = floor( $border_length_top/2 );
  $border_length_top_right = ceil( $border_length_top/2 );

  $border_top_left = str_pad( "", $border_length_top_left, $border_char );
  $border_top_right = str_pad( "", $border_length_top_right, $border_char );  

  error_log( "\n\n" );
  error_log( "$border_top_left $title $border_top_right" );

  if ( is_array( $data ) || is_object( $data ) ){
    error_log( print_r( $data, true ) );
  }
  else{
    error_log( "" );
    error_log( $data );
    error_log( "" );
  }
  error_log( str_pad( "", $border_length_bottom, $border_char ) . "\n" );
}

function str_contains_arr( $str, array $arr ){
    foreach( $arr as $a ){
        if ( stripos( $str,$a ) !== false ) return true;
    }
    return false;
}


function bw_add_robotstxt( $robots ){
  $resources = get_posts( array( 
    'posts_per_page' => -1,
    'post_type' => 'resource',
    'post_status' => 'publish'
  ) );

  $disallow_urls = implode( array_map( function( $post ){
    $resource_url = get_post_meta( $post->ID, 'resource_url', true );

    if ( !empty( $resource_url ) ){
      $permalink = get_permalink( $post->ID );
      return "Disallow: " . $permalink . "\n";
    } else {
      return '';      
    }
  }, $resources ) );

  echo <<<ROBOTS
User-agent: *
{$disallow_urls}
ROBOTS;  
}

add_action( 'do_robotstxt', 'bw_add_robotstxt', PHP_INT_MAX );

function bw_admin_reorder_posts( $query ){
  if ( is_admin() && $query->is_main_query() && in_array( $_GET['post_type'], ['bot', 'resource'] ) ) { 
    $query->set( 'order', 'DESC' ); 
  } 
} 

add_action( 'pre_get_posts', 'bw_admin_reorder_posts' );


add_action('rest_api_init', function() {
  remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
}, 15);













// EXPERIMENTAL API

add_action( 'rest_api_init', 'bw_register_import_endpoint');
add_action( 'wp_ajax_nopriv_adw_cache_data', 'cache_data' );

function get_site_info( $url ){
  $ch = curl_init(); 
  curl_setopt( $ch, CURLOPT_HEADER, 0 ); 
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
  curl_setopt( $ch, CURLOPT_URL, $url ); 
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 ); 
  
  $data = curl_exec( $ch ); 
  curl_close( $ch ); 
  
  $dom = new DOMDocument(); 
  @$dom->loadHTML( $data ); 
  
  $nodes = $dom->getElementsByTagName( 'title' ); 
  $title = $nodes->item( 0 )->nodeValue; 
  
  $metas = $dom->getElementsByTagName( 'meta' ); 
  $description = ''; 


  $title_arr = explode( ' (@', $title );
  $title = trim( $title_arr[0] );

  for( $i=0; $i<$metas->length; $i++ ){ 
      $meta = $metas->item( $i ); 
      
      if( $meta->getAttribute( 'name' ) == 'description' ){ 
          $description = $meta->getAttribute( 'content' ); 
      } 
  } 

  $description_arr = explode( ' · ', $description );
  
  if ( count( $description_arr ) > 1 ){
      $description = trim( $description_arr[1] );
  }

  return array( 
      "title" => $title,
      "description" => $description
  );
}

function get_network_name( $network ){
  return $network->name;
}

function bw_register_import_endpoint(/* $_REQUEST */) {
    register_rest_route('bw', 'import-bots', array(
        'methods' => \WP_REST_Server::READABLE,
        'permission_callback' => '__return_true',
        'callback' => 'bw_import_bots'
  ));
}

function bw_import_bots(\WP_REST_Request $request){
    global $helpers;

    $urls = $request['urls'];
    $key = $request['key'];

    if (empty($key) || $key !== INTERNAL_API_KEY ){
      $response = array(
        'status' => '403 forbidden'
      );
      
      return $response;
    }



    if (empty($urls)){
      $response = array(
        'status' => '422 unprocessable entity'
      );
      
      return $response;
    }

    $bot_urls = explode(",", $urls);
    $bot_count = count( $bot_urls );
    $bot_import_count = 0;
    
    log_this( "\nfound " . number_format( $bot_count ) . " URLs\n" );
    
    foreach ( $bot_urls as $index => $url ){
      log_this( 'processing: ' . $url . "..." );
    
      $bot_info = get_site_info( $url );
    
      log_this(( print_r( $bot_info, true ) ) );
    
      if ( true || strlen( $bot_info['description'] ) > 0 ){
          log_this( "begin..." );
    
          $bot_networks = ['mastodon', 'fediverse'];
          $bot_description = trim( $bot_info['description'] );
          
          $bot_urls = [$url];
          $main_bot_url = $url;
          
          $created_by_html_array = array();
          $author_tags = array();
          
          $post_content = '<!-- wp:paragraph -->' 
          . '<p><a href="' . $main_bot_url . '">' . $bot_info['title'] . '</a> is a Mastodon bot created by AUTHOR that '
          . $bot_description
          . "</p>"
          . '<!-- /wp:paragraph -->';
    
          log_this(array(
            'post_content' => $post_content
          ));
          
          $bot_meta = array();
          $bot_meta['bot_is_featured'] = 'on';
          $bot_meta['bot_url'] = trim( implode( "\n", $bot_urls ) );
          
          $screenshotable_url = trim( str_replace( array( "\n", "\r" ), '', $main_bot_url ) );
          $bot_tags = array();
          
          foreach ( $bot_urls as $bot_url ) {
              if ( strpos( $bot_url, 'botsin.space/' ) ){
                  array_push( $bot_tags, 'botsin.space' );
                  break;
              }
          }
    
          log_this( array(
              'bot_tags' => $bot_tags
          ));
          
          $post_data = array( 
              'post_author' => 2,
              'post_content' => $post_content,
              'post_title' => $bot_info['title'],
              'post_excerpt' => $bot_info['description'],
              'post_status' => 'draft',
              'post_type' => 'bot',
              'post_category' => '',
              'meta_input' => $bot_meta
          );
              
          $new_post_id = wp_insert_post( $post_data );
          
          log_this( array(
              'new_post_id' => $new_post_id
          ));
                  
          wp_set_object_terms( $new_post_id, $bot_tags, 'post_tag' );
          
          foreach ( $bot_meta as $key => $value ) {
              update_post_meta( $new_post_id, $key, $value );
          }
          
          wp_set_object_terms( $new_post_id, $bot_networks, 'network' );
    
          try {
              log_this(array(
                'screenshotable_url' => $screenshotable_url
              ));
    
              $screenshot = $helpers->make_screenshot( array( 
                  'url' => $screenshotable_url,
                  'file_name' => trim( $bot_info['title'] )
              ) );
    
              log_this( array(
                  'screenshot' => $screenshot
              ));
    
              
              if ( class_exists( 'ColorThief\ColorThief ' ) ){
                  try {
                      $dominant_color = ColorThief::getColor( $screenshot['image_path'] );
                      update_post_meta( $new_post_id, 'dominant_color', json_encode( $dominant_color ) );
                  } catch ( Exception $e ) {
                      /* noop */            
                  }
              }
              $helpers->add_post_thumbnail( $new_post_id, $screenshot['image_path'], $bot_description );
          } catch ( Exception $e ) {
              // TODO: Proper error handling.
              log_this($e->getMessage());
    
          }
      $bot_import_count++;
      } else {
          log_this( "skipping..." );
      }
      
      log_this( ( $index + 1 ) . '/' . $bot_count . " bots processed\n" );
    }

    $response = array(
      'status' => '200 okay',
      'processed' => $bot_count,
      'imported' => $bot_import_count
    );
    
    return $response;
}
