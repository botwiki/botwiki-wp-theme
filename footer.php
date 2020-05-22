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
            <a id="twitter-link" title="Follow @botwikidotorg on Twitter" rel="me" href="https://twitter.com/botwikidotorg">
              <img src="<?php echo get_template_directory_uri(); ?>/images/icons/twitter-white.svg">
              <span>@botwikidotorg</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- footer -->
      <footer class="footer mb-0 pt-3" role="contentinfo">
          <?php if ( !is_front_page() ){ ?>
            <div class="container-fluid pt-5 pb-1 pl-0 pr-0 background-alternate">
              <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="share-prompt">
                      <?php
                      global $wp;
                      global $page_title;
                      $page_title = urlencode( str_replace( ' | ' . get_bloginfo('name'), '', $page_title ) );
                      ?>
                      <ul class="sharing-is-caring">
                        <li>
                        <a href="https://twitter.com/intent/tweet?source=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;text=<?php echo $page_title; ?>%20<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>&amp;via=botwikidotorg" target="_blank" title="Tweet"><img alt="Tweet" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/twitter.svg"></a>
                        </li>
                        <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>" target="_blank" title="Share on Facebook"><img alt="Share on Facebook" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/facebook.svg"></a>
                        </li>
                        <li>
                        <a href="https://plus.google.com/share?url=<?php echo urlencode( home_url(add_query_arg( array(), $wp->request) ) ); ?>" target="_blank" title="Share on Google+"><img alt="Share on Google+" src="<?php echo get_template_directory_uri(); ?>/images/sharing-icons/google-plus.svg"></a>
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
                   <div class="row mt-0 mb-3 ml-n3 mr-n3">
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
                          <li><a class="h-card" rel="me" href="https://twitter.com/botwikidotorg">@botwikidotorg</a></li>
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
                          <li><a href="https://www.paypal.me/botwiki">PayPal</a></li>
                          <!-- <li><a href="<?php //echo site_url(); ?>/merch">Botwiki Store</a></li> -->
                          <li><a href="<?php echo site_url(); ?>/about/supporters">Our supporters</a></li>
                        </ul>                
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="container-fluid pt-0 pb-1 pl-0 pr-0 background-alternate">
              <div class="container">
                <div class="row">
                  <div class="col-12 pt-3 pb-4">
                    <p>This website is <a href="https://github.com/botwiki/botwiki-wp-theme">open source</a> and powered by <a href="https://wordpress.org" title="WordPress">WordPress</a>. Botwiki and the <a href="http://botzine.org/">Bot! zine</a> and <a href="https://botmakers.org/">Botmakers</a> landing pages are all proudly hosted by <a href="https://digitalocean.com/"><img class="footer-logo-do" src="<?php echo get_template_directory_uri(); ?>/images/logos/digitalocean.svg"></a>, a generous supporter and the sponsor of the very first <a href="<?php echo site_url(); ?>/monthly-bot-challenge/">Monthly Bot Challenge</a>.</p>
                    <p>One more way to support us is to sign up using our <a href="https://www.digitalocean.com/?refcode=9e279abc3337">DigitalOcean referral link</a>, which will also earn you a $10 starter credit.</p>
                    <p>For attributions, see the <a href="<?php echo site_url(); ?>/about/attributions">Attributions page</a>.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="container-fluid pt-0 pb-1 pl-0 pr-0 background-fff">
              <div class="container">
                <div class="row">
                  <div class="col-12 pt-4 pb-3">
                    <div class="row align-items-center mt-3 mb-5 pb-5">
                      <div class="col-sm-6 col-md-3 mx-auto">
                        <p class="text-center">
                          <a href="https://fightfascism.glitch.me/">
                            <img class="lazy-load" alt="Fight Fascism" title="Fight fascism! Sticker by Angus Johnston" src="<?php echo get_template_directory_uri(); ?>/images/other/fight-fascism.jpg" data-src="<?php echo get_template_directory_uri(); ?>/images/other/fight-fascism-block.jpg">
                          </a>
                        </p>              
                      </div>
                      <div class="col-sm-6 col-md-3 mx-auto">
                        <p class="text-center">
                          <a href="https://blacklivesmatter.com/">
                            <img class="lazy-load" alt="Black Lives Matter" title="Black Lives Matter logo" src="<?php echo get_template_directory_uri(); ?>/images/other/blm.jpg" data-src="<?php echo get_template_directory_uri(); ?>/images/other/blm.jpg">
                          </a>
                        </p>              
                      </div>
                      <div class="col-sm-6 col-md-3 mx-auto">
                        <p class="text-center">
                          <a href="https://indieweb.org/">
                            <img class="lazy-load" alt="IndieWeb" title="Support #indieweb!" src="<?php echo get_template_directory_uri(); ?>/images/other/indiewebcamp.png" data-src="<?php echo get_template_directory_uri(); ?>/images/other/indiewebcamp.png">
                          </a>
                        </p>              
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
      </footer>
      <!-- /footer -->
    <?php wp_footer(); ?>
    <script type="text/javascript" src="<?php bloginfo('template_directory') ?>/libs/select2/4.0.10/js/select2.min.js"></script>    
    <!-- analytics -->
    <script type="text/javascript">var sc_project=10530872;var sc_invisible=1;var sc_security="b852d106";var scJsHost = (("https:" == document.location.protocol) ? "https://secure." : "http://www."); document.write("<sc"+"ript type='text/javascript' src='"+scJsHost+"statcounter.com/counter/counter.js'></"+"script>");</script><noscript><div class="statcounter"><a title="hit counter" href="http://statcounter.com/" target="_blank"><img class="statcounter" src="http://c.statcounter.com/10530872/0/b852d106/1/" alt="hit counter"></a></div></noscript>
  </body>
</html>
