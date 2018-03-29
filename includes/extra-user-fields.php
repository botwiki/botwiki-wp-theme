<?php
use ColorThief\ColorThief;

class Extra_User_Fields {
  public function __construct() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media_lib_uploader' ) );

    add_action( 'show_user_profile', array( $this, 'extra_user_profile_fields' ) );
    add_action( 'edit_user_profile', array( $this, 'extra_user_profile_fields' ) );

    add_action( 'personal_options_update', array( $this, 'save_extra_user_profile_fields' ) );
    add_action( 'edit_user_profile_update', array( $this, 'save_extra_user_profile_fields' ) );


  }

  function enqueue_media_lib_uploader() {
    wp_enqueue_media();
    // wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
    // wp_enqueue_script( 'media-lib-uploader-js' );
  }

  function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
      <th><label for="twitter-handle">Twitter handle</label></th>
      <td>
        <input type="text" name="twitter-handle" id="twitter-handle" value="<?php echo esc_attr( get_the_author_meta( 'twitter-handle', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Your Twitter handle"); ?></span>
      </td>
    </tr>
    <tr>
      <th><label for="twitter-handle">Profile header</label></th>
      <td>
        <img id="background-img-preview" class="background-img-preview" src="<?php echo esc_attr( get_the_author_meta( 'background-img-url', $user->ID ) ); ?>">
        <input hidden id="background-img-url" type="text" name="background-img-url" />
        <input id="upload-button" type="button" class="button" value="Choose background" />
      </td>
    </tr>
    </table>
    <script type="text/javascript">
      jQuery(document).ready(function($){

        var mediaUploader;

        $('#upload-button').click(function(e) {
          e.preventDefault();
            if (mediaUploader) {
            mediaUploader.open();
            return;
          }
          mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
            text: 'Choose Image'
          }, multiple: false });

          mediaUploader.on('select', function() {
            attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#background-img-url').val(attachment.url);
            $('#background-img-preview').attr('src', attachment.url);
          });
          mediaUploader.open();
        });
      });
    </script>
  <?php }

  function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    update_user_meta( $user_id, 'twitter-handle', $_POST['twitter-handle'] );
    update_user_meta( $user_id, 'background-img-url', $_POST['background-img-url'] );

    try {
      $dominant_color = ColorThief::getColor( str_replace( get_site_url(), ABSPATH, $_POST['background-img-url'] ) );
      update_user_meta($user_id, 'background-img-dominant-color', json_encode($dominant_color));
    } catch (Exception $e) { /* NOOP */ }
  }


}

$extra_user_fields_init = new Extra_User_Fields();
