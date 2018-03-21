<?php

class Screenshot_Tool {
  public function __construct() {
    add_action('admin_menu', array( $this, 'add_tools_submenu_page' ) );
  }

  function add_tools_submenu_page() {
    add_submenu_page(
      'tools.php',
      'Screenshot Tool',
      'Screenshot Tool',
      'manage_options',
      'screenshot-tool',
      array( $this, 'screenshot_tool_page_render' )
    );
  }


  function screenshot_tool_page_render() { ?>
    <div class="wrap screenshot-tool">
      <div id="icon-tools" class="icon32"></div>
      <h2>Screenshot tool</h2>
      <label for="page-url"><p>URL of the bot</p></label>
      <input type="url" id="page-url" class="regular-text" name="page-url" placeholder="https://twitter.com/botwikidotorg">
      <button id="get-screenshot">Fetch screenshot</button>
      <div>
        <img id="screenshot-img" src="" download>
      </div>
      <p id="screenshot-hint" class="hidden"><span class="description">Right-click the image and use "Save image as..." to download it.</span>
    </div>

    

  <?php }
}

$screenshot_tool_init = new Screenshot_Tool();
