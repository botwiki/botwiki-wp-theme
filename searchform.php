<form class="form mt-5 mt-md-0 mb-5 text-right pr-0 w-100" method="get" action="<?php echo home_url(); ?>" role="search">
  <input id="search-input" class="form-control mt-4 mb-4 mr-sm-2" type="search" name="s" placeholder="<?php _e( 'Search...', 'botwiki' ); ?>" aria-label="Search" value="<?php echo get_search_query(); ?>">
  <div id="search-filters" class="mt-3 text-left<?php if ( !is_search() ) { echo ' slide-up-hidden'; } ?>">
    <div class="form-check form-check-inline d-xs-block">
      <input class="form-check-input" type="radio" id="search-filters-bots" name="search-filters-options[]" value="bots"<?php if ( !empty($_GET['search-filters-options']) && in_array('bots', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
      <label class="form-check-label" for="search-filters-bots">Bots</label>
    </div>
    <div class="form-check form-check-inline d-xs-block">
      <input class="form-check-input" type="radio" id="search-filters-tutorials" name="search-filters-options[]" value="tutorials"<?php if ( !empty($_GET['search-filters-options']) && in_array('tutorials', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
      <label class="form-check-label" for="search-filters-tutorials">Tutorials</label>
    </div>
    <div class="form-check form-check-inline d-xs-block">
      <input class="form-check-input" type="radio" id="search-filters-resources" name="search-filters-options[]" value="resources"<?php if ( !empty($_GET['search-filters-options']) && in_array('resources', $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
      <label class="form-check-label" for="search-filters-resources">All resources</label>
    </div>
    <div class="form-check form-check-inline d-xs-block">
      <input class="form-check-input" type="radio" id="search-filters-everything" name="search-filters-options[]" value="everything"<?php if ( ( !empty($_GET['search-filters-options']) && in_array('everything', $_GET['search-filters-options'] ) ) || empty( $_GET['search-filters-options'] ) ){ echo ' checked'; } ?>>
      <label class="form-check-label" for="search-filters-everything">Everything</label>
    </div>
    <div class="text-left mt-4">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit" role="button">Search</button>
    </div>
  </div>
</form>