<?php

class Post_Links {
  public function __construct() {
    add_shortcode( 'link', array( $this, 'post_link_shortcode' ) );
    add_shortcode( 'link-list', array( $this, 'link_list_shortcode' ) );
    add_shortcode( 'btn-list', array( $this, 'btn_list_shortcode' ) );
    add_shortcode( 'link-cards', array( $this, 'link_cards_shortcode' ) );
  }

  public function post_link_shortcode( $atts ) {
    $is_external = false;    
    $post_id = $atts['id'];

    $link_classes = $atts['class'];

    $link_url = get_post_meta( $post_id, 'resource_url', true );

    if ( empty( $link_url ) ){
      $link_url = post_permalink( $post_id );
    }
    else{
      $is_external = true;
    }

    $link_title = get_the_title( $post_id );

    $link_classes_html = '';

    if (!empty( $link_classes )){
      $link_classes_html = ' class="' . $link_classes . '"';      
    }

    return '<a href="' . $link_url . '" ' . $link_classes_html . '>' . $link_title . '</a>'
           . ($is_external ? ': ' . get_the_excerpt($post_id) . ' (' .  parse_url($link_url)['host'] . ')' : '');
  }

  public function btn_list_shortcode( $atts ) {
    $is_external = false;

    $post_ids = explode( ',', $atts['ids'] );
    $link_list_html = '<ul class="btn-list mb-2">';

    foreach ($post_ids as $post_id) {
      $link_url = get_post_meta( $post_id, 'resource_url', true );

      if ( empty( $link_url ) ){
        $link_url = post_permalink( $post_id );
      }
      else{
        $is_external = true;
      }

      $link_title = get_the_title( $post_id );
      $link_list_html .= '<li><a data-resource-id="' . $post_id . '" href="' . $link_url . '" class="btn">' . $link_title . '</a>' 
                      . ($is_external ? ': ' . get_the_excerpt($post_id) . ' (' .  parse_url($link_url)['host'] . ')' : '')
                      . '</li>' ;
    }

    $link_list_html .= '</ul>';

    return $link_list_html;
  }

  public function link_list_shortcode( $atts ) {
    $post_ids = explode( ',', $atts['ids'] );
    $link_list_html = '<ul>';

    foreach ($post_ids as $post_id) {
      $is_external = false;
      $link_url = get_post_meta( $post_id, 'resource_url', true );

      if ( empty( $link_url ) ){
        $link_url = post_permalink( $post_id );
      }
      else{
        $is_external = true;
      }

      $link_title = get_the_title( $post_id );
      $link_list_html .= '<li><a data-resource-id="' . $post_id . '" href="' . $link_url . '">' . $link_title . '</a>';

      $link_authors = explode( PHP_EOL, get_post_meta( $post_id, 'resource_author_info', true ) );

      $link_authors_html = '';


      if ( count( $link_authors ) === 1 ){
        if ( !empty( $link_authors[0] ) && $is_external ){
          list($author_name, $author_url ) = explode(',', $link_authors[0]);          
        }
        elseif ( !$is_external ) {
          $post_author = get_post_field( 'post_author', $post_id );

          $author_name = get_the_author_meta( 'nickname', $post_author);
          $author_url = get_author_posts_url($author_id, get_the_author_meta('nickname', $author_id));;
        }
        $link_authors_html .= '<a data-resource-id="' . $post_id . '" href="' . $author_url . '">' . $author_name . '</a>';
      }
      else{
        foreach ($link_authors as $link_author) {
          /* TODO: Add support for multiple authors */
          list($author_name, $author_url ) = explode(',', $link_author);

          // error_log( print_r( array(
          //   'author_name' => $author_name,
          //   'author_url' => $author_url,
          //    ), true
          // ));

          // echo join(' and ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1)), 'strlen'));

          $link_authors_html .= '<a data-resource-id="' . $post_id . '" href=""></a>';
        }
      }

      if ( $is_external ){
        $link_domain = str_replace('www.', '', parse_url($link_url)['host']);



        $link_list_html .= ': ' . get_the_excerpt($post_id) . ' ('
                        . ( !empty(  $link_authors_html ) ?  $link_authors_html . ' via ' : '' )
                        .  $link_domain . ')';
      }
      else{
        $link_list_html .= ' ('
                        . ( !empty(  $link_authors_html ) ? 'by ' . $link_authors_html : '' )
                        . ')';        
      }

      $link_list_html .= '</li>';
    }

    $link_list_html .= '</ul>';

    return $link_list_html;
  }

  public function link_cards_shortcode( $atts ) {
    $post_ids = explode( ',', $atts['ids'] );

    $post_id_groups = array_chunk( $post_ids, 2 );
    $include_description = ( $atts['description'] === 'yes' || $atts['description'] === 'true' );

    $link_list_html = '<div class="mt-md-5 mb-md-5">';

    foreach ($post_id_groups as $post_ids) {
      $link_list_html .= '<div class="card-deck">';
      foreach ($post_ids as $post_id) {

        $link_url = get_post_meta( $post_id, 'resource_url', true );

        if ( empty( $link_url ) ){
          $link_url = post_permalink( $post_id );
        }

        $link_title = get_post_meta( $post_id, 'card_title', true );
        if ( empty( $link_title ) ){
          $link_title = get_the_title( $post_id );
        }

        $link_thumbnail = get_the_post_thumbnail( $post_id, 'full', array( 'class' => 'card-img-top' ) );
        $link_description = ( $include_description ? get_the_excerpt( $post_id ) : '');

        $link_list_html .= '    <div class="card">' .
                           '      <a data-resource-id="' . $post_id . '" href="' . $link_url . '">' . $link_thumbnail . '</a>' .
                           '      <div class="card-body">' .
                           '        <h5 class="card-title">' . $link_title . '</h5>' .
                           '        <p class="card-text">' . $link_description . '</p>' .
                           '      </div>' .
                           '      <div class="card-footer">' .
                           '        <a data-resource-id="' . $post_id . '" href="' . $link_url . '" class="btn">Browse</a>' .
                           '      </div>' .
                           '    </div>';
        if ( count($post_ids) === 1 ){
          $link_list_html .= '<div class="card d-none d-sm-block" style="visibility: hidden;"></div>';
        }
      }

      $link_list_html .= '</div><div>&nbsp;</div>';

    }
    $link_list_html .= '</div>';


    return $link_list_html;
  }
}

$post_links_init = new Post_Links();
