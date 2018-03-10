<?php
  /* Template Name: Submit Your Bot Page Template */
  get_header();
  $post_id = get_the_ID();

  if ( !empty( $_POST ) ){
    if (
      ( isset( $_POST['bot-description'] ) && !empty( $_POST['bot-description'] ) ) &&
      ( isset( $_POST['bot-networks'] ) && !empty( $_POST['bot-networks'] ) ) &&
      ( isset( $_POST['bot-urls'] ) && !empty( $_POST['bot-urls'] ) ) &&
      ( isset( $_POST['bot-tagline'] ) && !empty( $_POST['bot-tagline'] ) ) &&
      ( isset( $_POST['bot-tags'] ) && !empty( $_POST['bot-tags'] ) )
    ) {

    ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1><?php the_title(); ?></h1>
            <p><strong>Thank you for your submission!</strong> Please be patient while we review it 😊</p> 

            <ul class="btn-list">
              <li>
                <a class="btn" href="<?php echo get_permalink(); ?>">Add one more</a>
              </li>
              <li>
                <a class="btn" href="/bots/#browse-bots-by-category">Browse bots</a>
              </li>
              <li>
                <a class="btn" href="<?php echo get_site_url(); ?>">Back to home page</a>
              </li>
            </ul>
          </article>
        </div>
      </main>    
    <?php }
    else{ ?>
      <main role="main" class="container-fluid m-0 p-0">
        <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
          <?php
            the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
          ?>
        </div>
        <div class="container">
          <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
            <h1><?php the_title(); ?></h1>
            <p>Please <a href="<?php echo get_permalink(); ?>">return to the previous page</a> and make sure all required fields are filled out. <strong>Thank you!</strong></p>
          </article>
        </div>
      </main>

    <?php }
  }
  else { ?>
    <link rel='stylesheet' href='<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/css/select2.min.css' media='all' />

    <main role="main" class="container-fluid m-0 p-0">
      <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
        <?php
          the_post_thumbnail('post-thumbnail', ['class' => 'lazy-load', 'title' => get_post(get_post_thumbnail_id())->post_title ]);
        ?>
      </div>
      <div class="container">
        <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
<!-- 
          <ul class="btn-list">
            <li>
              <button class="btn" id="test" href="#">Test</button>
            </li>
            <li>
              <a class="btn" href="#">Button</a>
            </li>
            <li>
              <a class="btn" href="#">Button</a>
            </li>
          </ul>
 -->
          <?php echo get_post_field('post_content', $post_id) ?>
          <form method="post" class="mt-5">
            <div class="author-fields form-row">
              <div class="form-group col-md-6">
                <label for="author-1-name">Author's name</label>
                <input type="text" class="form-control" id="author-1-name" name="author-1-name" placeholder="Author">
              </div>
              <div class="form-group col-md-6">
                <label for="author-1-url">Author's URL</label>
                <input type="url" class="form-control" id="author-1-url" name="author-1-url" placeholder="https://twitter.com/author">
              </div>
            </div>
            <div class="form-group">
              <button id="add-author-fields" class="btn">Add more authors</button>
            </div>
            <div class="form-group">
              <label for="bot-description">What does your bot do? <sup title="This field is required.">*</sup></label>
              <textarea required class="form-control" id="bot-description" name="bot-description" rows="3" placeholder="This bot makes..."></textarea>
              <small id="bot-description-help" class="form-text text-muted">Include any relevant links to your blog.</small>
            </div>
            <div class="form-group">
              <label for="bot-networks">Where does your bot operate? <sup title="This field is required.">*</sup></label>

              <select required class="form-control js-select2" id="bot-networks" name="bot-networks[]" multiple="multiple" placeholder="Twitter, Tumblr, Slack...">
              <?php
                $networks = get_terms( 'network', array(
                    'hide_empty' => false,
                ) );

                foreach ($networks as $network) { ?>
                  <option value="<?php echo $network->term_id ?>"><?php echo $network->name ?></option>
                <?php }
              ?> 
              </select>

              <small id="bot-networks-help" class="form-text text-muted">List all networks where your bot posts. Missing something?
              <a href="mailto:stefan@botwiki.org" target="_blank">Let us know.</a>
              </small>
            </div>
            <div class="form-group">
              <label for="bot-urls">Where can we see your bot? <sup title="This field is required.">*</sup></label>
              <textarea required class="form-control" id="bot-urls" name="bot-urls" rows="3" placeholder="https://twitter.com/mycoolbot&#x0a;https://mycoolbot.tumblr.com"></textarea>
              <small id="bot-urls-help" class="form-text text-muted">Links to your bot, one on each line, please.</small>
            </div>

            <div class="form-group">
              <label for="bot-tagline">A short tagline <sup title="This field is required.">*</sup></label>
              <input required type="text" class="form-control" id="bot-tagline" name="bot-tagline" placeholder="A bot that does cool stuff.">
              <small id="bot-tagline-help" class="form-text text-muted">This shows up in search.</small>
            </div>

            <div class="form-check mb-2">
              <input type="checkbox" class="form-check-input" id="bot-is-opensource" name="bot-is-opensource">
              <label class="form-check-label" for="bot-is-opensource">This bot is open-source</label>
            </div>
            <div id="bot-source-info" class="mt-3">
              <div class="form-group">
                <label for="bot-source-url">Link to your bot's source code</label>
                <input type="url" class="form-control" id="bot-source-url" name="bot-source-url" placeholder="https://github.com/me/mycoolbot">
                <small id="bot-source-url-help" class="form-text text-muted">Link to your bot's repo on GitHub, Bitbucket, etc.</small>
              </div>
              <div class="form-group">
                <label for="bot-source-language">What language(s) did you use?</label>

                <select class="form-control js-select2" id="bot-source-language" name="bot-source-language[]" multiple="multiple" placeholder="node.js, Python, Java...">
                <?php
                  $languages = get_terms( 'programing_language', array(
                      'hide_empty' => false,
                  ) );

                  foreach ($languages as $language) { ?>
                    <option value="<?php echo $language->term_id ?>"><?php echo $language->name ?></option>
                  <?php }
                ?> 
                </select>

                <small id="bot-source-language-help" class="form-text text-muted">Yes, node.js is technically a JavaScript framework, bear with us.</small>
              </div>
            </div>
            <div class="form-group">
              <label for="bot-tags">Tag your bot <sup title="This field is required.">*</sup></label>

              <select required class="form-control js-select2" id="bot-tags" name="bot-tags[]" multiple="multiple">
              <?php
                $tags = get_tags( array(
                  'hide_empty' => true
                ) );

                foreach ($tags as $tag) { ?>
                  <option value="<?php echo $tag->term_id ?>"><?php echo $tag->name ?></option>
                <?php }
              ?> 
              </select>

              <small id="bot-tags-help" class="form-text text-muted">Add as many relevant tags as you can, this will make it easier for others to find it. (Don't overdo it!)</small>
            </div>



            <button type="submit" class="btn mt-4">Okay, looks good</button>
          </form>
        </article>
      </div>
    </main>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/libs/select2/4.0.5/js/select2.min.js"></script>

  <?php }
?>
<?php get_footer(); ?>
