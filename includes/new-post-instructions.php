<?php

class New_Post_Instructions {
  public function __construct() {
    add_action( 'edit_form_after_title', array( $this, 'show_new_post_instructions' ) );
  }

  public function show_new_post_instructions( $post ) { 
    $current_screen = get_current_screen();

    // $post_meta = get_post_meta( $post->ID );

    if ( $current_screen->parent_base === 'edit' ){
      $post_type = $current_screen->post_type;

      if ( $post_type === 'resource' ){ ?>
        <h2 style="padding-left: 0;">Adding resources to Botwiki</h2>
        <p>
          To add an <strong>external resource</strong> not hosted on Botwiki, fill out the URL of the resource <a href="#resource-info-meta">below</a>. Be sure to also <a href="#postexcerpt">add an excerpt</a>, which will be shown on search results page.
        </p>
      <?php } else if ( $post_type === 'bot' ){ ?>
        <div class="notice notice-info">
          <p>
            Please use the <a href="/submit-your-bot/">bot submission form</a> to add a new bot.
          </p>
        </div>
      <?php }
    }
  }
}

$new_post_instructions_init = new New_Post_Instructions();
