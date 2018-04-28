<?php
	get_header();
	$post_id = get_the_id();
	$post_type = get_post_type();
	$post_meta = get_post_meta( $post_id );	
?>
	<main role="main" class="container-fluid m-0 p-0">

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) { ?>

		<?php
	    $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
	    $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
	    $dominant_color_css = str_replace(']', ')', $dominant_color_css);
		?>
			<div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
				<?php if ($post_type == 'bot'){ ?>
				<a href="<?php echo get_the_post_thumbnail_url( $post_id ); ?>">
				<?php } ?>
					<?php
						$post_thumbnail_id = get_post_thumbnail_id();
						the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ), 'class' => 'lazy-load expand-image', 'title' => get_post( $post_thumbnail_id )->post_title ]);
					?>
					<?php if ($post_type == 'bot'){ ?>
					</a>
				<?php } ?>

			</div>
		<?php } ?>
		<!-- /post thumbnail -->

		<div class="container">
			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- post title -->
				<h1><?php the_title(); ?></h1>
				<!-- /post title -->


	        <?php
	        if ($post_type == 'bot'){ ?>
	        	<ul class="btn-list">
						<?php

						$bot_urls = preg_split('/\n|\r\n?/', $post_meta['bot_url'][0]);

						$bot_source_url = '';

						if ( array_key_exists('bot_source_url', $post_meta) && !empty($post_meta['bot_source_url'][0]) ){
							$bot_source_url = $post_meta['bot_source_url'][0];
						}

						if ( is_array( $bot_urls ) && $bot_urls[0] ){
							foreach ($bot_urls as $url) {
								if ( $url !== $bot_source_url ){
									$info = parse_url($url);
									$host = $info['host'];
									$host_names = explode(".", $host);
									$domain = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
									?>
			            <li>
			              <a class="btn" href="<?php echo $url; ?>">View on <?php echo $domain; ?></a>
			            </li>
								<?php }									
							}
						}

	          $bot_languages = wp_get_post_terms($post_id, 'programing_language');

	          if ( array_key_exists('bot_source_url', $post_meta) && !empty($post_meta['bot_source_url'][0]) ){ ?>
	            <li>
	              <a class="btn view-source" href="<?php echo $post_meta['bot_source_url'][0]; ?>">View source</a>
	            </li>
	            <?php
	              foreach ($bot_languages as $bot_language) {
	                if ($bot_language->name == 'node.js' && strpos($post_meta['bot_source_url'][0], 'github.com') > -1){ ?>
	                  <li>
	                    <a class="btn glitch-remix" href="https://gomix.com/#!/import/<?php echo str_replace('https://github.com/', 'github/', $post_meta['bot_source_url'][0]); ?>" target="_blank">Remix on Glitch</a>
	                  </li>
	                  <li>
	                    <a href="<?php echo get_site_url(); ?>/resource/tutorial/hosting-bots-on-glitch/">What is Glitch?</a>
	                  </li>
	                <?php }
	                if ($bot_language->name == 'node.js' && strpos($post_meta['bot_source_url'][0], 'glitch.com/edit/#!/') > -1){ ?>
	                  <li>
	                    <a class="btn glitch-remix" href="<?php echo str_replace('edit/#!/', 'edit/#!/remix/', $post_meta['bot_source_url'][0]); ?>" target="_blank">Remix on Glitch</a>
	                  </li>
	                  <li>
	                    <a href="<?php echo get_site_url(); ?>/resource/tutorial/hosting-bots-on-glitch/">What is Glitch?</a>
	                  </li>
	                <?php }
	              } ?>
							<?php } ?>
	              </ul>
	          <?php }
	        ?>
	      



				<?php the_content(); // Dynamic Content ?>

				<?php
				if ( $post_type == 'bot' ) { 
					$networks = get_the_terms($post_id, 'network');
					function get_network_name($network){
						return $network->name;
					}

					$network_names = array_map("get_network_name", $networks);

					if ( in_array('Twitter', $network_names) ) { 
						// $bot_tweets_html = get_post_meta( $post_id, 'bot_tweets_html', true );
						$bot_tweets_html = explode( '</blockquote>', get_post_meta( $post_id, 'bot_tweets_html', true ) );
						?>

					  <div class="row">


						<?php foreach ($bot_tweets_html as $tweet_html) { ?>
					    <div class="col-sm-12 col-md-6">
					      <?php echo $tweet_html . '</blockquote>' ?>
					    </div>

						<?php } ?>
					  </div>
					<?php } ?>
					<!-- post details -->
					<p class="post-tags mt-5 mb-5">
						<?php 

					    $network_tags = array();

					    if ($networks){
						    foreach ( $networks as $network ) {
					        $network_tags[] = '<a href="' . get_site_url() . '/networks/' . $network->slug . '">' . $network->slug . '</a> ';
						    }					    	
					    }


					    echo join( ' ', $network_tags );

							$languages = get_the_terms($post_id, 'programing_language');
					    $language_tags = array();

					    if ($languages){
						    foreach ( $languages as $language ) {
					        $language_tags[] = '<a href="' . get_site_url() . '/languages/' . $language->slug . '">' . $language->slug . '</a> ';
						    }

					    }

					    echo join( ' ', $language_tags );

							$tags = get_the_tags();
					    $tags_array = array();

					    if ($tags){
						    foreach ( $tags as $tag ) {
					        $tags_array[] = '<a href="' . get_site_url() . '/bot/?tags=' . $tag->slug . '">' . $tag->slug . '</a> ';
						    }

					    }

					    echo join( ' ', $tags_array );

							// the_tags('', ' ', '<br>');
						?>
					</p>
					<!-- /post details -->
				<?php } elseif ($post_type == 'post') {
					$post_date = get_the_time('F j, Y');
					$post_date_full = $post_date . ' ' . get_the_time('g:i a');
					$m = new \Moment\Moment($post_date);
					$post_date_ago = $m->fromNow()->getRelative();
				?>

					<p class="mt-5"><em>Posted <span title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></span>
						by <a href="<?php echo get_author_posts_url($author_id, get_the_author_meta('nickname', $author_id)); ?>"><?php echo get_the_author_meta('nickname', $author_id); ?></a> in <?php the_category(', '); ?></em></p>
					<p class="post-tags mt-5 mb-5"><?php the_tags('', ' ', '<br>'); // Separated by commas with a line break at the end ?></p>
				<?php }



						global $coauthors_plus;

  					$coauthors = get_coauthors();

  					$coauthors_count = count( $coauthors );
  					if ($coauthors_count > 1 || ( $coauthors_count === 1 && $coauthors[0]->data->ID !== "2") ){ ?>
  						<?php if ( $post_type === 'bot' ){ ?>
	  						<h2 id="authors">Created by <a class="pilcrow" href="#authors">¶</a></h2>
  						<?php }

							foreach ($coauthors as $coauthor) {

								$author_id = $coauthor->data->ID;
								if ($author_id != 2){
									$author_data = get_userdata( intval($author_id ));

									// echo "<pre><code>";
									// var_dump(get_userdata( $author_id ));
									// echo "</code></pre>";

									$nickname = get_the_author_meta('nickname', $author_id);
									$username = get_the_author_meta('user_nicename', $author_id);

								  if ( user_can($author_id, 'administrator') ){  
								  	$botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
								    if ( empty( $botwiki_team_role ) ){
								      $botwiki_team_role = "Botwiki team member.";      
								    }
								  }
								  else{
								    $botwiki_team_role = "Botwiki contributor.";    
								  }

									$first_name = get_the_author_meta('nickname', $author_id);
									$last_name = get_the_author_meta('last_name', $author_id);
									$full_name = '';

									if( empty($first_name)){
									    $full_name = $last_name;
									} elseif( empty( $last_name )){
									    $full_name = $first_name;
									} else {
									    $full_name = "{$first_name} {$last_name}";
									}

									$botwiki_profile_page_url = get_site_url() . '/author/' . $username;

							    $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $user->ID ) );

							    if ( empty( $profile_img_url )){
							      $profile_img_url = get_avatar_url($author_id);
							    }

									// $background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
									// $background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

								 //  $background_img_dominant_color_css = str_replace('[', 'background-color:rgb(', $background_img_dominant_color);
								 //  $background_img_dominant_color_css = str_replace(']', ')', $background_img_dominant_color_css);
									include( locate_template( 'author-card.php', false, false ) ); 	
								}
							}
  					}


				if ( get_post_type() == 'post' ) { ?>
					<!-- post details -->
					<?php
					?>

					<!-- /post details -->
				<?php } ?>

			</article>
			<!-- /article -->
		</div>

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Sorry, nothing to display.', 'botwiki' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>

	</section>
	<!-- /section -->
	</main>

<?php get_footer(); ?>
