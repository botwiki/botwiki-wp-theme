<?php
  $profile_img_url = str_replace( '?s=96&', '?s=360&', $profile_img_url );
  function bw_render_author_links( $helpers, $options ){ ?>
    <ul class="btn-list mt-2 mb-2">
      <?php if ( !is_author() ){ ?> 
        <li>
          <a class="btn" title="Botwiki profile page" rel="me" href="<?php echo $options['botwiki_profile_page_url']; ?>">View profile</a>
        </li>
      <?php }
      if ( !empty( $options['website_url'] ) ){ ?> 
        <li> 
          <a class="btn" title="Personal website" rel="me" href="<?php echo $options['website_url']; ?>"><?php echo $helpers->get_domain_from_url( $options['website_url'] ); ?>
          </a>  
        </li> 
      <?php } ?>
      <?php if ( !empty( $options['twitter_handle'] )){ ?> 
        <li>  
          <a class="btn" title="Twitter" rel="me" href="https://twitter.com/<?php echo $options['twitter_handle']; ?>">@<?php echo $options['twitter_handle']; ?></a> 
        </li> 
      <?php } ?>
    </ul>
  <?php }

$author_info = array(
  'botwiki_profile_page_url' => $botwiki_profile_page_url,
  'website_url' => $website_url,
  'twitter_handle' => $twitter_handle
);
?>

<div class="container mt-5 pl-0 pr-0">
  <div class="card mt-4 mb-4 author-card">
    <div class="card-body mt-4">
      <div class="row">
        <div class="col-sm-12 col-md-2 text-center text-md-left">
          <a title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <img class="d-lg-none d-sm-block d-md-none lazy-load mb-4 u-photo rounded w-50" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
            <img class="d-none d-md-block d-lg-block lazy-load mb-4 u-photo rounded" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
          </a>
        </div>
        <div class="col-sm-12 col-md-10">
          <a class="no-underline" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <h3 class="mt-0 mb-3"><?php echo $nickname; ?></h3>
          </a>
          <p><strong><?php echo $botwiki_team_role; ?></strong></p>
          <?php if ( !is_author() ){ bw_render_author_links( $helpers, $author_info ); } ?>
        </div>
        <div class="col-sm-12">
          <?php if ( is_author() ){
            if ( !empty( $description ) ){ ?> 
              <p><?php echo $description; ?></p>
            <?php }
          } ?>
        </div>
        <div class="col-sm-12">
          <?php
            if ( is_author() ){
              bw_render_author_links( $helpers, $author_info );
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
