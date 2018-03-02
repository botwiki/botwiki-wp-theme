<?php
/*
Based on https://github.com/soderlind/wp-anchor-header
*/

if ( defined( 'ABSPATH' ) ) {
	Anchor_Header::instance();
}

class Anchor_Header {
	private static $instance = null;

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	function __construct() {
		add_filter( 'the_content', array( $this, 'the_content' ) );
	}

	function the_content( $content ) {

		if ( $content == '' ) {
			return $content;
		}

		$anchors = array();
		$doc = new DOMDocument();
		$libxml_previous_state = libxml_use_internal_errors( true );
		$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_previous_state );
		foreach ( array( 'h2', 'h3', 'h4', 'h5', 'h6' ) as $h ) {
			$headings = $doc->getElementsByTagName( $h );
			foreach ( $headings as $heading ) {
				$heading_id = $heading->getAttribute( 'id' );
				if ($heading_id){
					$slug = $tmpslug = sanitize_title( $heading_id );
					$a = $doc->createElement( 'a' );
					$a->nodeValue = '¶';
					$newnode = $heading->appendChild( $a );
					$newnode->setAttribute( 'class', 'pilcrow' );
					$i = 2;
					while ( false !== in_array( $slug, $anchors ) ) {
						$slug = sprintf( '%s-%d', $tmpslug, $i++ );
					}
					$anchors[] = $slug;
					$heading->setAttribute( 'id', $slug );
					$newnode->setAttribute( 'href', '#' . $slug );					
				}
			}
		}
		return $doc->saveHTML();
	}
}


// $header_links = new Anchor_Header();