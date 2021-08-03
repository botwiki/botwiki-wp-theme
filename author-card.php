<?php
  $profile_img_url = str_replace( '?s=96&', '?s=360&', $profile_img_url );
  function bw_render_author_links( $helpers, $options ){ ?>
    <ul class="btn-list mt-4 mb-2">
      <?php if ( !is_author() ){ ?> 
        <li>
          <a class="btn mb-2" title="Botwiki profile page" rel="me" href="<?php echo $options['botwiki_profile_page_url']; ?>">View profile</a>
        </li>
      <?php }
      if ( !empty( $options['website_url'] ) ){ ?> 
        <li> 
          <a class="btn mb-2" title="Personal website" rel="me" href="<?php echo $options['website_url']; ?>"><?php echo $helpers->get_domain_from_url( $options['website_url'] ); ?>
          </a>  
        </li> 
      <?php } ?>
      <?php if ( !empty( $options['twitter_handle'] )){ ?> 
        <li>  
          <a class="btn mb-2" title="Twitter" rel="me" href="https://twitter.com/<?php echo $options['twitter_handle']; ?>">@<?php echo $options['twitter_handle']; ?></a> 
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

<div class="container card <?php echo ( is_author() ? 'mt-n5' : 'mt-5' ) ?> mb-4 pl-0 pr-0 author-card">
  <div class="card-body mt-4">
    <div class="row">
      <div class="col-sm-12 col-md-2 text-center text-md-left">
        <a title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
          <img loading="lazy" class="d-lg-none d-sm-block d-md-none lazy-load mt-3 mb-5 u-photo rounded-circle w-50" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
          <img loading="lazy" class="d-none d-md-block d-lg-block lazy-load mb-4 u-photo rounded-circle" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
        </a>
      </div>
      <div class="col-sm-12 col-md-10">
        <a class="no-underline" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
          <h3 class="mt-0 mb-3 d-inline-block"><?php echo $nickname; ?></h3>
        </a>
        <?php if ( !is_author() ){ ?>
        <p class="mt-3"><strong><?php echo $botwiki_team_role; ?></strong></p>
        <?php } ?>

        <?php if ( !is_author() ){ bw_render_author_links( $helpers, $author_info ); } ?>
        <?php if ( is_author() ){
          if ( !empty( $description ) ){ ?> 
            <p><?php echo $description; ?></p>
          <?php } else { ?>
            <p><?php echo $botwiki_team_role; ?></p>
          <?php } 
          bw_render_author_links( $helpers, $author_info );
        } ?>        
      </div>      
    </div>
  </div>
</div>
