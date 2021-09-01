<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
  <head>
    <?php
      global $helpers;
      global $wp;
      global $wp_query;
      global $page_title;

      echo '<style type="text/css">' . file_get_contents( get_template_directory() . '/css-critical/styles.css' ) . '</style>';

      if ( isset( $wp_query->query['post_type'] ) ){
        $post_type = $wp_query->query['post_type'];
      } else {
        $post_type = false;
      }

      $post_type_for_title = '';

      if ( !empty( $post_type ) ){
        if ($helpers->ends_with( $post_type, 's' ) ){
          $post_type_for_title = $post_type . "'";
        }
        else{
          $post_type_for_title = $post_type . 's';
        }
      }

      $page_url = home_url( $wp->request );
      $page_thumbnail = get_the_post_thumbnail_url();
      $page_description = get_bloginfo('description');

      if ( empty($page_thumbnail ) ){
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );
      }

      if ( is_page() ) {
        $page_title = get_the_title();
        $page_description = get_the_excerpt();
        if ( empty( $page_description ) ){
          $page_description = get_bloginfo('description');        
        }
      }    
      elseif ( !empty( $_GET['networks'] ) || !empty( $_GET['tags'] ) || !empty( $_GET['languages'] ) ) {
        $page_tags = array();

        if ( isset( $_GET['networks'] ) ){
          $page_tags = array_merge(
            $page_tags,
            explode( ',', $_GET['networks'] )
          );
        }

        if ( isset( $_GET['tags'] ) ){
          $page_tags = array_merge(
            $page_tags,
            explode( ',', $_GET['tags'] )
          );
        }

        if ( isset( $_GET['languages'] ) ){
          $page_tags = array_merge(
            $page_tags,
            explode( ',', $_GET['languages'] )
          );
        }

        $page_title .= ucfirst( $post_type_for_title ) . ' tagged #' . implode( $page_tags, ' #' );
        $page_description = 'Browsing pages tagged #' . implode( $page_tags, ' #' ) . ' on Botwiki';
      }
      elseif ( is_category() ) {
        // $page_title = 'Posts labeled ' . single_cat_title();
        $page_title = 'Posts labeled #' . $wp_query->query_vars['category_name'];
        $page_description = 'Browsing pages tagged #' . implode( $page_tags, ' #' ) . ' on Botwiki';
      }
      elseif ( is_post_type_archive() ) {
        if (is_author()){
          $nickname = get_the_author_meta('nickname', $author_id);
          $username = get_the_author_meta('user_nicename', $author_id);
          $post_type = $wp_query->query['post_type'];

          if (!empty($post_type)){
            $page_title = ( !empty( $_GET['opensource'] ) ? 'open source' : '' ) . $post_type . 's by ' . $nickname;
          } elseif ( !empty( $_GET['tags'] )){ 
            $page_title .= ' tagged ' . $_GET['tags'];
          }
          else{
            $page_title = get_the_title();       
          }

          $page_title = ucfirst($page_title);
          $page_description = 'Browsing ' . lcfirst( $page_title );          
        }
        else{
          $page_title = 'Browsing all ' . $post_type_for_title . ' ...'; 
          $page_description = 'Browsing all ' . $post_type_for_title . ' on Botwiki...';          
        }
      }
      elseif ( is_author() ) {
        $author_id = get_query_var('author');
        $nickname = get_the_author_meta('nickname', $author_id);

        if ( user_can($author_id, 'administrator') ){  
          $page_description = get_the_author_meta('botwiki-team-role', $author_id);
          if ( empty( $page_description ) ){
            $page_description = "Botwiki team member.";
          }
        }
        else{
          $page_description = "Botwiki contributor.";    
        }

        // $page_title = get_the_title();

        if ( !empty( $post_type_for_title ) ){
          $page_title = ucfirst($post_type_for_title) . ' by ' . $nickname;          
          $page_description = 'Browsing ' . ucfirst($post_type_for_title) . ' by ' . $nickname . ' on Botwiki';          
        }
        else{
          $page_title = $nickname;                    
          $page_description = $nickname . ' on Botwiki';                    
        }
        $page_thumbnail = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
      }
      elseif ( is_tag() ) {
        $tags = preg_split( "/(\+|,)/", $wp_query->query['tag'] );
  
        $page_title = "Posts tagged #" . implode( $tags, ' #' );
        $page_description = "Browsing posts tagged #" . implode( $tags, ' #' ) . ' on Botwiki';
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );
      }
      elseif ( is_tax() ) {
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 

        if ( get_query_var( 'taxonomy' ) === 'programing_language' ) {
          $page_title = "Bots made with " . $term->name;
        } else {
          $page_title = "Posts tagged #" . $term->name;
        }

        $page_description = "Browsing posts tagged #" . $term->name . " on Botwiki";
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );  
      }
      elseif ( is_archive() ) {
        global $wp_query;
        $page_title = str_replace( 'Archives:', '', get_the_archive_title());
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );
      }
      elseif ( is_search() ) {
        $page_title = 'Searching Botwiki for "' . esc_html( $_GET['s'] ) . '"...';
      }
      elseif ( is_home()) {
        $page_title = 'Latest posts from our blog';
      }
      elseif ( is_404()) {
        $page_title = 'Oh no, 404!';
      }
      else{
        $page_title = get_the_title();
        $page_description = get_the_excerpt();
      }

      $page_title .=  ' | ' . get_bloginfo('name');
    ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php echo $page_title ?></title>
    <meta itemprop="name" content="<?php echo $page_title ?>"/>
    <meta itemprop="url" content="<?php echo $page_url; ?>"/>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="/feed" />
    <meta property="og:title" content="<?php echo $page_title; ?>" />
    <meta name="twitter:title" content="<?php echo $page_title; ?>" />
    <meta name="description" content="<?php echo $page_description; ?>" />
    <meta property="og:description" content="<?php echo $page_description; ?>" />
    <meta name="twitter:description" content="<?php echo $page_description; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $page_url; ?>" />
    <?php if ( !empty( $page_thumbnail ) ){ ?>
    <meta property="og:image" content="<?php echo $page_thumbnail; ?>" /> 
    <meta property="twitter:image:src" content="<?php echo $page_thumbnail; ?>" /> 
    <?php } ?>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@botwikidotorg" />
    <meta name="twitter:domain" content="https://botwiki.org/" />
    <link type="text/plain" rel="author" href="https://botwiki.org/humans.txt" />
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri(); ?>/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#38313a">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--
    <link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" href='https://fonts.googleapis.com/css?family=Fira+Sans:400,700|Open+Sans|Source+Serif+Pro|Source+Code+Pro&display=swap' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,700|Open+Sans|Source+Serif+Pro|Source+Code+Pro&display=swap' rel='stylesheet' type='text/css'>
    <link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" href='https://fonts.googleapis.com/css?family=Bree+Serif&text=botwiki&display=swap' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif&text=botwiki&display=swap' rel='stylesheet' type='text/css'>
    -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "<?php echo $page_title ?>",
      "image": [
        "<?php echo $page_thumbnail; ?>"
       ],
      "datePublished": "<?php echo get_the_date(); ?>",
      "dateModified": "<?php echo get_the_modified_date(); ?>"
    }
    </script>
    <?php include "fonts.php"; ?>
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <noscript><style type="text/css">.lazy-load{opacity:1;transform:none;}</style></noscript>
    <header id="header">
    <?php if (!is_front_page()){ ?>
      <nav class="navbar navbar-expand-md fixed-top">
        <div class="container-fluid">
          <div class="row m-0 w-100">
            <div class="col-sm-6">
              <label id="menu-icon" class="cursor-pointer botwiki-logo" for="menu-toggle">b</label>
            </div>
            <div class="col-sm-6 text-right pr-0">
            </div>
          </div>
        </div>
        <input type="checkbox" id="menu-toggle">
        <div id="menu-wrapper" class="container-fluid">
          <?php botwiki_site_nav(); ?>
        </div>
      </nav>
    <?php } ?>
  </header>
  <!-- header -->