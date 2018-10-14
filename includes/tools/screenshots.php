<?php

class Screenshot_Tool {
  public function __construct() {
    add_action('admin_menu', array( $this, 'add_tools_submenu_page' ) );
    add_filter('admin_post_thumbnail_html', array( $this, 'add_featured_image_instruction' ) );
  }

  function add_featured_image_instruction( $content ) {
    $append_url = '';
    $post_id = get_the_id();
    $post_type = get_post_type();
    if (in_array($post_type, [ 'bot', 'resource' ] ) ){
      global $helpers;
      $post_meta = get_post_meta( $post_id );     
      $bot_urls = preg_split('/\n|\r\n?/', $post_meta['bot_url'][0]);
      $screenshotable_url = $helpers->get_screenshotable_url( $bot_urls );
      if ( $screenshotable_url !== false ){
        $append_url = '&url=' . $screenshotable_url;
      }
    }

    return $content .= '<p>You can use the <a href="/wp-admin/tools.php?page=screenshot-tool' . $append_url . '" target="_blank">Screenshot tool</a> to create the thumbnail image.</p>';
  }

  function add_tools_submenu_page() {
    add_submenu_page(
      'tools.php',
      'Screenshot Tool',
      'Screenshot Tool',
      'read',
      'screenshot-tool',
      array( $this, 'screenshot_tool_page_render' )
    );
  }

  function screenshot_tool_page_render() { ?>
    <div class="wrap screenshot-tool">
      <div id="icon-tools" class="icon32"></div>
      <h2>Screenshot tool</h2>
      <label for="page-url"><p>URL of the bot</p></label>
      <input type="url" id="page-url" class="regular-text" name="page-url" placeholder="https://twitter.com/botwikidotorg" value="<?php echo $_GET['url']; ?>">
      <button id="get-screenshot">Fetch screenshot</button>
      <div>
        <img id="screenshot-img" src="" download>
      </div>
      <p id="screenshot-hint" class="hidden"><span class="description">Right-click the image and use "Save image as..." to download it.</span>
    </div>
  <?php }
}

$screenshot_tool_init = new Screenshot_Tool();
