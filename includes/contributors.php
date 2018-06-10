<?php

class Contributors_Restrictions {
  public function __construct() {
    add_action( 'load-edit.php', array( $this, 'hide_others_posts' ) );
    add_filter( 'views_edit-post', array( $this, 'hide_others_posts_links' ) );
    add_filter( 'admin_footer', array( $this, 'hide_others_posts_links_admin' ) );
    add_filter( 'ajax_query_attachments_args', array( $this, 'restrict_media_library' ) );
  }

  public function hide_others_posts() { 
    add_action( 'request', array( $this, 'hide_others_posts_filter' ) );
  }

  public function hide_others_posts_filter( $query_vars ) { 
    if ( ! current_user_can( $GLOBALS['post_type_object']->cap->edit_others_posts ) ) {
        $query_vars['author'] = get_current_user_id();
    }
    return $query_vars;
  }

  public function hide_others_posts_links( $views ) { 
    if ( !current_user_can( 'administrator' ) ) {
      $views = array();
    }
    return $views;
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

  function restrict_media_library( $query ) {
    global $current_user;
    $query['author'] = $current_user->ID ;
    return $query;
  }

}

$contributors_restrictions_init = new Contributors_Restrictions();
