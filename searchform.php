<form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" class="form mt-5 mt-md-0 mb-5 text-right pr-0 w-100" method="get" action="<?php echo home_url(); ?>" role="search">
  <meta itemprop="target" content="https://botwiki.org/?s={search_term_string}"/>
  <input required pattern="\s*(\S\s*){2,}" title="What would you like to search for?" itemprop="query-input" id="search-input" class="form-control mt-4 mb-4 mr-sm-2" type="search" name="s" placeholder="<?php _e( 'Search...', 'botwiki' ); ?>" aria-label="Search" value="<?php echo get_search_query(); ?>">
  <div id="search-filters" class="container mt-3 text-left<?php if ( !is_search() ) { echo ' slide-up-hidden'; } ?>">
    <div class="row">
      <div class="col-sm-12 mt-2 pl-1">
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
      </div>
      <div class="col-sm-12 mt-4 pl-1 pr-0">
        <div class="form-row">
          <label for="bot-info-1-network">Network</label>
          <select class="form-control js-select2" name="networks" placeholder="Twitter, Tumblr, Slack,..." data-minimum-input-length="0" data-clear="true" data-multiple="true" data-tags="false">
          <?php
            $networks = get_terms( 'network', array(
                'hide_empty' => false,
            ) );
            foreach ($networks as $network) { ?>
              <option <?php if ( isset( $_GET['networks'] ) && in_array($network->slug, explode( ',', $_GET['networks'] )) ){
                echo 'selected ';
              } ?> value="<?php echo $network->slug ?>"><?php echo $network->name ?></option>
            <?php }
          ?> 
          </select>
        </div>
      </div>
      <div class="col-sm-12 mt-4 pl-1 pr-0">
        <div class="form-row">        
          <label><input id="search-filter-opensource" name="opensource" type="checkbox" value="true" <?php checked( $_GET['opensource'], 'true' ); ?>> Open-source</label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 mt-4 pl-1 pr-0">
        <div class="text-left">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit" role="button">Search</button>
        </div>
      </div>
    </div>
  </div>
</form>