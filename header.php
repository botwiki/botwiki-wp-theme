<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
    <?php
      global $wp;
      $page_url = home_url( $wp->request );
      $page_thumbnail = get_the_post_thumbnail_url();

      if ( empty($page_thumbnail ) ){
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );
      }
      
      if ( !empty( $_GET['networks'] ) || !empty( $_GET['tags'] ) || !empty( $_GET['languages'] ) ) {
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

        $page_title .= 'Pages tagged #' . implode( ' #', $page_tags );
      }
      elseif ( is_author() ) {
        $author = get_queried_object();
        $page_title = $author->nickname;
        $page_thumbnail = get_avatar_url( $author->ID );
      }
      elseif ( is_tag() ) {
        global $wp_query;
        $tags = preg_split( "/(\+|,)/", $wp_query->query['tag'] );
  
        $page_title = "Posts tagged #" . implode( ' #', $tags );
        $page_thumbnail = get_the_post_thumbnail_url( (int)get_option( 'page_on_front' ) );
      }
      elseif ( is_search() ) {
        $page_title = 'Searching Botwiki for "' . $_GET['s'] . '"...';
      }
      else{
        $page_title = get_the_title();
      }

      $page_title .=  ' | ' . get_bloginfo('name');
    ?>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php echo $page_title ?></title>
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
		<meta name="description" content="<?php bloginfo('description'); ?>">
    <link href='https://fonts.googleapis.com/css?family=Anton|Open+Sans|Source+Code+Pro' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif&text=botwiki' rel='stylesheet' type='text/css'>
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
              <!-- <a class="navbar-brand botwiki-logo" href="<?php #echo get_site_url(); ?>">b</a> -->
            </div>
            <div class="col-sm-6 text-right pr-0">
              <label id="menu-icon" class="cursor-pointer" for="menu-toggle">☰</label>
            </div>
          </div>
        </div>
        <input type="checkbox" id="menu-toggle">
        <div id="menu-wrapper" class="container-fluid">
          <div class="row w-100">
            <div class="col-sm-12">
              <?php botwiki_site_nav(); ?>
            </div>              
            <div class="col-sm-12 pt-4 pr-5 pl-5 pb-4">
              <?php echo get_search_form( false ); ?>
            </div>
          </div>
        </div>
      </nav>
    <?php } ?>
  </header>
	<!-- header -->