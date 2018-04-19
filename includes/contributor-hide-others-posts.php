<?php

class Contributors_Hide_Others_Posts {
  public function __construct() {
    add_action( 'load-edit.php', array( $this, 'hide_others_posts' ) );
    add_filter( 'views_edit-post', array( $this, 'hide_others_posts_links' ) );
    add_filter( 'admin_footer', array( $this, 'hide_others_posts_links_admin' ) );
  }

  public function hide_others_posts() { 
    add_action( 'request', array( $this, 'hide_others_posts_filter' ) );
  }

  public function hide_others_posts_filter( $query_vars ) { 
    if ( ! current_user_can( $GLOBALS['post_type_object']->cap->edit_others_posts ) ) {
        $query_vars['author'] = get_current_user_id();
    } ?>
    <? return $query_vars;
  }

  public function hide_others_posts_links( $views ) { 
    return array();    
  }

  public function hide_others_posts_links_admin(  ) { 
    if ( ! current_user_can( 'administrator' ) ) { ?>
      <script type="text/javascript">
        $(function(){
          $('.subsubsub .all, .subsubsub .publish, .subsubsub .pending, .subsubsub .publish, .subsubsub .trash').hide();
        });
      </script>
    <?php }
  }
}

$contributors_hide_others_posts_init = new Contributors_Hide_Others_Posts();
