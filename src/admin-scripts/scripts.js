"use strict";

import {helpers} from "./helpers.js";
import {screenshot_tool} from "./screenshot-tool.js";

(function($) {

  helpers.hide_duplicate_posts_menu();
  helpers.show_bot_submission_form_link_admin();
  screenshot_tool.init();

})( jQuery );
