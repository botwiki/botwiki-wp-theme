<form class="form mt-2 mt-md-0 text-right pr-0 w-100" method="get" action="<?php echo home_url(); ?>" role="search">
  <input id="search-input" class="form-control mr-sm-2" type="search" name="s" placeholder="<?php _e( 'Search...', 'botwiki' ); ?>" aria-label="Search">
  <div id="search-filters" class="mt-3 text-left<?php if ( is_front_page() ) { echo ' d-none'; } ?>">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="search-filters-bots" name="search-filters-options[]" value="bots">
      <label class="form-check-label" for="search-filters-bots">Bots</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="search-filters-tutorials" name="search-filters-options[]" value="tutorials">
      <label class="form-check-label" for="search-filters-tutorials">Tutorials</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="search-filters-resources" name="search-filters-options[]" value="resources">
      <label class="form-check-label" for="search-filters-resources">All resources</label>
    </div>
    <div class="form-check form-check-inline">
      <span id="search-filters-everything" class="cursor-pointer">Everything</span>
    </div>
  </div>
<!-- 
  <button class="btn btn-outline-success my-2 my-sm-0" type="submit" role="button"><?php _e( 'Search', 'botwiki' ); ?></button>
 -->
</form>