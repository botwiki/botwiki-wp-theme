<?php

class Show_Post_ID {
  public function __construct() {
    add_filter( 'add_meta_boxes', array( $this, 'show_post_id_meta_box' ) );
  }

  public function show_post_id() { 
    global $post; ?>
      <pre><code title="Regular link">[link id="<?php echo $post->ID ?>"]</code></pre>
      <pre><code title="Link with a custom CSS class, for example 'btn'">[link id="<?php echo $post->ID ?>" class="btn"]</code></pre>
      <pre><code title="A list of links formatted as buttons">[btn-list id="<?php echo $post->ID ?>" class="btn"]</code></pre>
      <pre><code title="A bulleted list of links">[link-list ids="<?php echo $post->ID ?>,x,y,z"]</code></pre>
      <pre><code title="Links formatted as cards">[link-cards ids="<?php echo $post->ID ?>,x,y,z"]</code></pre>
      <pre><code title="Links formatted as cards, no description">[link-cards ids="<?php echo $post->ID ?>,x,y,z"<br/> description="yes"]</code></pre>
    <?php
  }

  public function show_post_id_meta_box( $post_type ) { 
    add_meta_box('projects_refid', 'Link shortcodes', array( $this, 'show_post_id' ), $post_type, 'side', 'high');
  }
}

$show_post_id_init = new Show_Post_ID();
