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
    <?php if ( current_user_can('administrator') ){?>
    <tr>
      <th><label for="botwiki-team-role">Botwiki team role</label></th>
      <td>
        <textarea name="botwiki-team-role" id="botwiki-team-role" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'botwiki-team-role', $user->ID ) ); ?></textarea><br />
        <span class="description"><?php _e("What do you do at Botwiki?"); ?></span>
      </td>
    </tr>
    <?php } ?>
    <tr>
      <th><label for="twitter-handle">Twitter handle</label></th>
      <td>
        <input type="text" name="twitter-handle" id="twitter-handle" value="<?php echo esc_attr( get_the_author_meta( 'twitter-handle', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Your Twitter handle"); ?></span>
      </td>
    </tr>
    <tr>
      <th><label for="background-img-url">Profile image</label></th>
      <td>
        <?php
        $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $user->ID ) );

        if ( empty( $profile_img_url )){
          $profile_img_url = get_avatar_url($author_id);
        }

        ?>
        <img id="profile-img-preview" class="profile-img-preview" src="<?php echo $profile_img_url; ?>">
        <input hidden id="profile-img-url" type="text" value="<?php echo $profile_img_url; ?>" name="profile-img-url" />
        <input id="select-profile-img" type="button" class="button-primary" value="Choose profile image" />
        <input id="clear-profile-img" type="button" class="button" value="Remove profile image" />
        <p class="description">Square images work best. If you don't set your profile image, your <a href="https://gravatar.com" target="_blank">Gravatar</a> will be used.</p>
      </td>
    </tr>
    <tr>
      <th><label for="background-img-url">Profile header</label></th>
      <td>
        <?php
        $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $user->ID ) );
        ?>
        <img id="background-img-preview" class="background-img-preview" src="<?php echo $background_img_url; ?>">
        <input hidden id="background-img-url" type="text" value="<?php echo $background_img_url; ?>" name="background-img-url" />
        <input id="select-background-img" type="button" class="button-primary" value="Choose background" />
        <input id="clear-background-img" type="button" class="button" value="Remove background" />
      </td>
    </tr>
    </table>
    <script type="text/javascript">
      jQuery(document).ready(function($){

        var mediaUploaderBackgroundIMG;

        $('#select-background-img').click(function(e) {
          e.preventDefault();
          if (mediaUploaderBackgroundIMG) {
            mediaUploaderBackgroundIMG.open();
            return;
          }
          mediaUploaderBackgroundIMG = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
            text: 'Choose Image'
          }, multiple: false });

          mediaUploaderBackgroundIMG.on('select', function() {
            attachment = mediaUploaderBackgroundIMG.state().get('selection').first().toJSON();
            $('#background-img-url').val(attachment.url);
            $('#background-img-preview').attr('src', attachment.url);
          });
          mediaUploaderBackgroundIMG.open();
        });

        var mediaUploaderProfileIMG;

        $('#select-profile-img').click(function(e) {
          e.preventDefault();
          if (mediaUploaderProfileIMG) {
            mediaUploaderProfileIMG.open();
            return;
          }
          mediaUploaderProfileIMG = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
            text: 'Choose Image'
          }, multiple: false });

          mediaUploaderProfileIMG.on('select', function() {
            attachment = mediaUploaderProfileIMG.state().get('selection').first().toJSON();
            $('#profile-img-url').val(attachment.url);
            $('#profile-img-preview').attr('src', attachment.url);
          });
          mediaUploaderProfileIMG.open();
        });

        $('#clear-background-img').click(function(e) {
          e.preventDefault();
          $('#background-img-url').val('');
          $('#background-img-preview').attr('src', '');
        });

        $('#clear-profile-img').click(function(e) {
          e.preventDefault();
          $('#profile-img-url').val('');
          $('#profile-img-preview').attr('src', '<?php echo get_avatar_url($author_id); ?>');
        });
      });
    </script>
  <?php }

  function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    update_user_meta( $user_id, 'botwiki-team-role', $_POST['botwiki-team-role'] );
    update_user_meta( $user_id, 'twitter-handle', $_POST['twitter-handle'] );
    update_user_meta( $user_id, 'profile-img-url', $_POST['profile-img-url'] );
    update_user_meta( $user_id, 'background-img-url', $_POST['background-img-url'] );

    try {
      $dominant_color = ColorThief::getColor( str_replace( get_site_url(), ABSPATH, $_POST['background-img-url'] ) );
      update_user_meta($user_id, 'background-img-dominant-color', json_encode($dominant_color));
    } catch (Exception $e) { /* NOOP */ }
  }


}

$extra_user_fields_init = new Extra_User_Fields();
