<div class="container mt-5">
  <div class="card mt-4 mb-4 author-card">
    <div class="card-body mt-4">
      <div class="row">
        <div class="col-sm-12 col-md-2 text-center text-md-left">
          <a title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <img class="mb-4 u-photo rounded" src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
          </a>
        </div>
        <div class="col-sm-12 col-md-10">
          <a class="no-underline" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <h3 class="mt-0 mb-3"><?php echo $nickname; ?></h3>
          </a>
          <p><strong><?php echo $botwiki_team_role; ?></strong></p>
        </div>
        <div class="col-sm-12">
          <?php if ( is_author() && !empty( $description )){ ?> 
            <p><?php echo $description; ?></p>
          <?php } ?>
        </div>
        <div class="col-sm-12">
          <ul class="btn-list mt-2 mb-2">
            <?php if ( !is_author() ){ ?> 
              <li>
                <a class="btn" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">View profile</a>
              </li>
            <?php } ?>
            <?php if ( !empty( $website_url )){ ?> 
              <li> 
                <a class="btn" title="Personal website" rel="me" href="<?php echo $website_url; ?>"><?php echo $helpers->get_domain_from_url($website_url); ?>
                </a>  
              </li> 
            <?php } ?>
            <?php if ( !empty( $twitter_handle )){ ?> 
              <li>  
                <a class="btn" title="Twitter" rel="me" href="https://twitter.com/<?php echo $twitter_handle; ?>">@<?php echo $twitter_handle; ?></a> 
              </li> 
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
