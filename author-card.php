<div class="container mt-5">
  <div class="card mt-4 mb-4 author-card">
    <div class="card-body mt-4">
      <div class="row">
        <div class="col-sm-12 col-md-2">
          <a title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <img class="mb-4 u-photo rounded" src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>">
          </a>
        </div>
        <div class="col-sm-12 col-md-10">
          <a class="no-underline" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">
            <h3 class="mt-0 mb-3"><?php echo $nickname; ?></h3>
          </a>
          <p><?php echo $botwiki_team_role; ?></p>
        </div>
        <div class="col-sm-12">
          <ul class="btn-list mt-2 mb-2">
            <li>
              <a class="btn" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">View profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
