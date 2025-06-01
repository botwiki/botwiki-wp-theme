<?php global $helpers; ?>
      <div class="bottom-page-links">
        <ul>
          <li>
            <a id="back-to-top" title="Go back all the way to the top of this page" href="#header" class="slide-down"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow-up.svg"></a>
          </li>
          <li>
            <a id="whats-new" title="See what's new on Botwiki" href="/new/">✨</a>
          </li>
          <li>
            <a id="random-bot-link" title="Explore the wonderful world of online bots, one random bot at a time" href="<?php echo site_url(); ?>/random-bot/">🤖</a>
          </li>
          <li>
            <a id="contribute-link" title="Contribute to Botwiki" href="<?php echo site_url(); ?>/contribute/">📋</a>
          </li>
          <li>
            <a id="mastodon-link" class="social-link" title="Follow @botwiki@mastodon.social" rel="me" href="https://mastodon.social/@botwiki">
              <span>@</span><span class="d-none d-lg-inline d-md-inline d-xl-inline">botwiki</span>
            </a>
          </li>
        </ul>
      </div>
      <?php if ( is_front_page() ){ ?>
        <!--
        <div class="container-fluid pl-5 pr-5 mb-5 pb-5">
          <div class="row m-0 p-0 mb-5">
            <div class="col-sm-4 justify-content-center align-self-center">
              <img loading="lazy" class="lazy-load" src="https://botwiki.org/wp-content/uploads/2020/01/D-An1_cXUAE2gBb.png" data-src="https://botwiki.org/wp-content/uploads/2020/01/D-An1_cXUAE2gBb.png">
            </div>
            <div class="col-sm-2 justify-content-center align-self-center">
              <img loading="lazy" class="lazy-load" src="https://botwiki.org/wp-content/uploads/2020/01/EPZDAxdW4AA89as.jpeg" data-src="https://botwiki.org/wp-content/uploads/2020/01/EPZDAxdW4AA89as.jpeg">
            </div>
            <div class="col-sm-4 justify-content-center align-self-center">
              <img loading="lazy" class="lazy-load" src="https://botwiki.org/wp-content/uploads/2020/02/ECgJCClVUAAyS1u-scaled.jpeg" data-src="https://botwiki.org/wp-content/uploads/2020/02/ECgJCClVUAAyS1u-scaled.jpeg">
            </div>
            <div class="col-sm-2 justify-content-center align-self-center">
              <img loading="lazy" class="lazy-load" src="https://botwiki.org/wp-content/uploads/2020/07/perpetualsoaps-01.jpeg" data-src="https://botwiki.org/wp-content/uploads/2020/07/perpetualsoaps-01.jpeg">
            </div>
          </div>
        </div>
        -->
      <?php } ?>
      <!-- footer -->
      <footer class="footer mt-5 mb-0 pt-3" role="contentinfo">
        <div class="container-fluid pt-5 pb-1 pl-0 pr-0 background-alternate">
          <div class="container pt-5">
            <div class="row">
              <div class="col-12">
                <div class="share-prompt">
                  <?php
                  global $wp;
                  global $page_title;
                  $page_title = urlencode( str_replace( ' | ' . get_bloginfo('name'), '', $page_title ) );
                  ?>

                  <form class="fsb-prompt">
                    <label>Share with the fediverse</label>
                    <div class="fsb-input-group mb-3">
                      <span class="fsb-input-group-text">https://</span>
                      <input required
                        type="text"
                        name="fediverse-domain"
                        placeholder="mastodon.social"
                        class="fsb-input fsb-domain"
                        aria-label="Amount (to the nearest dollar)">
                      <button class="fsb-button"
                        type="submit"><img
                          src="https://fediverse-share-button.stefanbohacek.com/fediverse-share-button/icons/mastodon.svg"
                          class="fsb-icon"></span>Share</button>
                    </div>
                    <p class="fsb-support-note fsb-d-none">This server does not support sharing. Please visit <a
                        class="fsb-support-note-link"
                        target="_blank"
                        href=""></a>.</p>
                  </form>
                  <link rel="stylesheet"
                    href="https://fediverse-share-button.stefanbohacek.com/fediverse-share-button/styles.min.css">
                  <script src="https://fediverse-share-button.stefanbohacek.com/fediverse-share-button/script.min.js"
                    defer
                    class="fsb-script"></script>

                  <ul class="sharing-is-caring">
                    <li>
                    <a href="https://twitter.com/intent/tweet?source=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;text=<?php echo $page_title; ?>%20<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;via=botwikidotorg" target="_blank" title="Tweet"><img alt="Tweet" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/twitter.svg"></a>
                    </li>
                    <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>" target="_blank" title="Share on Facebook"><img alt="Share on Facebook" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/facebook.svg"></a>
                    </li>
                    <li>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;description=<?php echo $page_title; ?>" target="_blank" title="Pin on Pinterest"><img alt="Pin on Pinterest" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/pinterest.svg"></a>
                    </li>
                    <li>
                    <a href="https://www.reddit.com/submit?url=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;title=<?php echo $page_title; ?>" target="_blank" title="Post on Reddit"><img alt="Post on Reddit" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/reddit.svg"></a>
                    </li>
                    <li>
                    <a href="mailto:?subject=<?php echo $page_title; ?>&amp;body=Botwiki is a collection of useful, friendly, and artistic online bots, and resources for making them.:%20<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>" target="_blank" title="Send an email"><img alt="Send an email" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/email.svg"></a>
                    </li><li>
                    <a href="https://getpocket.com/save?url=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;title=<?php echo $page_title; ?>" target="_blank" title="Add to Pocket"><img alt="Add to Pocket" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/pocket.svg"></a>
                    </li>
                    <li>
                    <a href="https://pinboard.in/popup_login/?url=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;title=<?php echo $page_title; ?>&amp;description=Botwiki is a collection of useful, friendly, and artistic online bots, and resources for making them." target="_blank" title="Save to Pinboard"><img alt="Save to Pinboard" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/pinboard.svg"></a>
                    </li>
                  </ul>
                  <p class="ssbg-attribution">Sharing buttons by <a href="https://simplesharingbuttons.com/">simplesharingbuttons.com</a></p>
                  <p>Botwiki is a personal side project that's made possible <a href="/about/support-us/">through donations</a> and <a href="/about/team/">hard work and dedication</a>. Huge thanks to <a href="/about/supporters/">everyone who helped us out</a>! 🙌</p>
                </div>
              </div>
            </div>
          </div>                  
        </div>
        <div class="container-fluid pt-0 pb-1 pl-0 pr-0 background-alternate">
          <div class="container">
            <div class="row">
              <div class="col-12">
               <div class="row mt-5 mb-3 ml-n3 mr-n3">
                  <div class="col-sm-12 col-md-4">
                    <ul class="footer-list">
                      <li class="list-header">About</li>
                      <li><a href="<?php echo site_url(); ?>/about/">About Botwiki</a></li>
                      <li><a href="<?php echo site_url(); ?>/about/projects/">Our projects</a></li>
                      <li><a href="<?php echo site_url(); ?>/projects/botwiki-browser-extension/">Browser Extension</a></li>
                      <li><a href="<?php echo site_url(); ?>/about/press/">Press</a></li>
                      <li><a href="<?php echo site_url(); ?>/coc/">Code of Conduct</a></li>
                    </ul>                
                  </div>
                  <div class="col-sm-12 col-md-4">
                    <ul class="footer-list">
                      <li class="list-header">Contact</li>
                      <li><a class="h-card" rel="me" href="https://mastodon.social/@botwiki">@botwiki</a></li>
                      <li><a class="u-email" rel="me" href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Email us</a></li>
                      <li><a href="<?php echo site_url(); ?>/about/feedback">Give feedback</a></li>
                      <li><a href="<?php echo site_url(); ?>/contribute/" title="Add your bot, tutorial, and other botmaking resources to Botwiki">Contribute</a></li>
                      <li><a href="https://botmakers.org/">Join Botmakers</a></li>
                    </ul>                
                  </div>
                  <div class="col-sm-12 col-md-4">
                    <ul class="footer-list">
                      <li class="list-header">Support us</li>
                      <li><a href="<?php echo site_url(); ?>/about/support-us">Support us</a></li>
                      <li><a href="https://www.patreon.com/botwiki">Patreon</a></li>
                      <li><a href="https://www.paypal.me/stefanbohacek">PayPal</a></li>
                      <!-- <li><a href="<?php //echo site_url(); ?>/merch">Botwiki Store</a></li> -->
                      <li><a href="<?php echo site_url(); ?>/about/supporters">Our supporters</a></li>
                    </ul>                
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid pt-0 pb-4 pl-0 pr-0 background-alternate">
          <div class="container">
            <div class="row">
              <div class="col-12 pt-3 pb-5">
                <p>This website is <a href="https://github.com/botwiki/botwiki-wp-theme">open source</a> and powered by <a href="https://wordpress.org" title="WordPress">WordPress</a>. Botwiki and <a href="https://botmakers.org/">Botmakers</a> landing pages are all proudly hosted by <a href="https://digitalocean.com/"><img class="footer-logo-do" src="<?php echo get_template_directory_uri(); ?>/images/logos/digitalocean.svg"></a>, a generous supporter and the sponsor of the very first <a href="<?php echo site_url(); ?>/monthly-bot-challenge/">Monthly Bot Challenge</a>.</p>
                <p>One more way to support us is to sign up using our <a href="https://www.digitalocean.com/?refcode=9e279abc3337">DigitalOcean referral link</a>, which will also earn you a $10 starter credit.</p>
                <p>For attributions, see the <a href="<?php echo site_url(); ?>/about/attributions">Attributions page</a>.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="container mb-5 pb-3">
          <div class="row align-items-center mx-auto mt-5 mb-5">
            <div class="col-sm-12 mx-auto mt-4 text-center">
              <p class="d-inline text-center">
                <a class="text-decoration-none" href="https://fightfascism.glitch.me/">
                  <img loading="lazy" class="m-2 lazy-load" alt="Fight Fascism" title="Fight fascism! Design by Angus Johnston" src="<?php echo get_template_directory_uri(); ?>/images/other/fight-fascism-square.png" data-src="<?php echo get_template_directory_uri(); ?>/images/other/fight-fascism-square.png">
                </a>
              </p>
              <p class="d-inline text-center">
                <a class="text-decoration-none" href="https://blacklivesmatter.com/">
                  <img loading="lazy" class="m-2 lazy-load" alt="Black Lives Matter" title="Black Lives Matter logo" src="<?php echo get_template_directory_uri(); ?>/images/other/blm.png" data-src="<?php echo get_template_directory_uri(); ?>/images/other/blm.png">
                </a>
              </p>
              <p class="d-inline text-center">
                <a class="text-decoration-none" href="https://indieweb.org/">
                  <img loading="lazy" class="m-2 lazy-load" alt="IndieWeb" title="Support #indieweb!" src="<?php echo get_template_directory_uri(); ?>/images/other/indiewebcamp-black.png" data-src="<?php echo get_template_directory_uri(); ?>/images/other/indiewebcamp-black.png">
                </a>
              </p>              
            </div>
          </div>
        </div>
      </footer>
    <!-- /footer -->
    <?php wp_footer(); ?>.
  </body>
</html>
