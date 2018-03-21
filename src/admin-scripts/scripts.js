"use strict";

import {helpers} from "./helpers.js";
import {screenshot_tool} from "./screenshot-tool.js";

(function($) {

  helpers.hide_duplicate_posts_menu();
  screenshot_tool.init();

})( jQuery );
