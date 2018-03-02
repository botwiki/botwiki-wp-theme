<?php
class Show_Card_Title {
  public function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'show_card_title' ) );
    add_action( 'save_post', array( $this, 'save_card_title' ) );
    // add_action( 'edit_form_after_title', array( $this, 'move_card_title' ) );
  }

  public function move_card_title() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes['post']['advanced']);
  }

  public function show_card_title( $post_type ) {
    add_meta_box(
      'card-title',
      'Card title',
      array( $this, 'card_title_fields' ),
      $post_type,
      'advanced',
      'high'
    );
  }

  public function card_title_fields( $post ) {
    $card_title = get_post_meta( $post->ID, 'card_title', true );
    ?>
      <label class="screen-reader-text" for="card_title">Card title</label>
      <input id="card_title" type="text" autocomplete="off" value="<?=esc_attr($card_title)?>" name="card_title" placeholder="Title when rendered as a card..." class="w-100">
    <?php
    wp_nonce_field(
        plugin_basename(__FILE__),
        'card_title_meta_box'
    );
  }

  function save_card_title($post_id) {
    $card_title = sanitize_text_field($_POST['card_title']);

    if ($card_title !== '') {
      update_post_meta($post_id, 'card_title', $card_title);
    }
  }
}

$show_card_title_init = new Show_Card_Title();
