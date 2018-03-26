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

			<h1><?php _e( 'Author archives for ', 'botwiki' ); echo get_the_author(); ?></h1>
			<?php 
				get_template_part('loop', 'author');
				get_template_part('pagination');
			?>
		</div>
	</main>

<?php get_footer(); ?>
