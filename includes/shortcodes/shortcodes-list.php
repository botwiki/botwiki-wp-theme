<?php

class Shortcodes_List {
  public function __construct() {
    add_action('admin_menu', array( $this, 'add_tools_submenu_page' ) );
  }

  function add_tools_submenu_page() {
    add_submenu_page(
      'tools.php',
      'Custom Shortcodes',
      'Custom Shortcodes',
      'manage_options',
      'shortcodes-list',
      array( $this, 'shortcodes_list_page_render' )
    );
  }


  function shortcodes_list_page_render() { ?>
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
      <h2>List of custom shortcodes</h2>
    </div>

    <table class="widefat">
      <tr>
        <th class="row-title">Shortcode</th>
        <th>Purpose</th>
      </tr>
      <tr valign="top">
        <td scope="row">
          <code>[admin-emails text="send us an email"]</code>
        </td>
        <td>
          Generate a <code>mailto</code> link with email addresses of all admins.
        </td>
      </tr>
      <tr valign="top" class="alternate">
        <td scope="row"><pre><code>[link id="x"]
[link id="x" class="btn"]
[btn-list id="x" class="btn"]
[link-list ids="x,y,z"]
[link-cards ids="x,y,z"]
[link-cards ids="x,y,z" description="yes"]</code></pre>
        </td>
        <td>
          Ways to link to a specific page or a list of pages.
        </td>
      </tr>
    </table>

  <?php }
}

$shortcodes_list_init = new Shortcodes_List();
