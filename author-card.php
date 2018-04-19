<div class="mt-5">
  <div class="card mt-4 mb-4 author-card">
    <div class="card-body">
      <div class="media mt-3">
        <a href="<?php echo $botwiki_profile_page_url;?>">
          <img class="mr-3 u-photo" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
        </a>
        <div class="media-body">
          <h3 class="mt-0 mb-3"><?php echo $nickname; ?></h3>
          <div class="p-note font-weight-bold">
            <?php echo $botwiki_team_role; ?>
          </div>
          <ul class="btn-list mt-4">
            <li>
              <a class="btn" title="Botwiki profile page" rel="me" href="<?php echo $botwiki_profile_page_url; ?>">View profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>