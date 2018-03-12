<?php
	get_header();

	$author_id = get_query_var('author');
	$author_data = get_userdata( intval($author_id ));
	echo "<pre><code>";
	// var_dump(get_userdata( $author_id ));
	echo "</code></pre>";

	$nickname = get_the_author_meta('nickname', $author_id);
	$description = get_the_author_meta('description', $author_id);

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

?>
	<main role="main" class="container">
    <div class="mt-5">

			<div class="media mb-5">
			  <img class="mr-3" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
			  <div class="media-body">
			    <h1 class="mt-0 mb-1"><?php echo $nickname; ?></h1>
			    <?php echo $description; ?>
			  </div>
			</div>


		<?php if (have_posts()): the_post(); ?>

			<h1><?php _e( 'Author archives for ', 'botwiki' ); echo get_the_author(); ?></h1>

		<?php rewind_posts(); while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- post thumbnail -->
				<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php the_post_thumbnail(array(120,120), ['class' => 'img-thumbnail mb-5 lazy-load', 'title' => $full_name ]); // Declare pixel size you need inside the array ?>
					</a>
				<?php endif; ?>
				<!-- /post thumbnail -->

				<!-- post title -->
				<h2 class="mb-3">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h2>
				<!-- /post title -->

				<!-- post details -->
				<?php
					$post_date = get_the_time('F j, Y') . ' ' . get_the_time('g:i a');
					$m = new \Moment\Moment($post_date);
					$post_date_ago = $m->fromNow()->getRelative();
				?>
				<span class="date"><?php ; ?></span>

				<em class="date" title="<?php echo $post_date; ?>"><?php echo $post_date_ago; ?></em>
				<!-- /post details -->

				<?php bw_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

				<br class="clear">

			</div>
			<!-- /article -->

		<?php endwhile; ?>

		<?php endif; ?>

		<?php get_template_part('pagination'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
