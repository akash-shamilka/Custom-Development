<?php

/*
 * Template Name: Blog Archive2
 */

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); 
?>

<h1 class="rooster-blog-heading">Rooster Blogs</h1>

<!--********************************************************************************************* TOP POST *****************************************************************************************-->
<!--retrieve and display posts-->
<div class="custom-posts-container-blog-one">
 <div class="custom-posts-wrapper-blog-one">
    <?php
       $query = new WP_Query( array(
        'posts_per_page' => 1, // one maximum number of post to display per page 
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ) );

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            ?>

           <div class="custom-post-blog-one-image">
			  <?php
				// Get the content template based on the display option
				get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );

				// Get the event title
				$event_title = sanitize_text_field(trim(get_the_title()));
			   
				// Get the permalink of the posts
				$event_permalink = sanitize_text_field(trim(get_permalink()));
			   
			    // Get the author fist name and last name
				$author_id = get_the_author_meta('ID');
				$author_first_name = get_the_author_meta('first_name', $author_id);
				$author_last_name = get_the_author_meta('last_name', $author_id);
				// Sanitize the author name fields
				$author_first_name = sanitize_text_field($author_first_name);
				$author_last_name = sanitize_text_field($author_last_name);
				$author_name = trim($author_first_name . ' ' . $author_last_name);
			   
			    // Retrieve the post publish date
				$post_date = sanitize_text_field( trim( get_the_date('j M Y')));
			   
				//Get the categories
				$categories = get_the_category();
			   
			   	// Get the post excerpt
				$post_excerpt = sanitize_textarea_field( trim(get_the_excerpt()));
			  ?>

				<!--Get the post featured image and if there is no featured image display the default thumbnail-->
				<div class="featured-image-blog-one-image">
				<a href="<?php echo esc_url( $event_permalink ); ?>">
				  <?php
					$post_thumbnail_id = get_post_thumbnail_id();
					if ( isset( $post_thumbnail_id ) && has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					} else {
						echo '<img src="http://localhost:8012/surgeglobal/akashint/wp-content/uploads/2023/04/default-event-image.jpg" class="no-featured-image-blog-one">';
					}
				  ?>
				</a>
				</div>  	
		   </div>
		   
		   <div class="custom-post-blog-one">
			   <div class="post-text-content-blog-one">

				   <!--Display the custom field 'reading_time'-->
				   <div class="date-time-blog-one">
					   <div class="reading-time-blog-one">
						   <?php
						   $reading_time = get_estimated_reading_time();
						   if ( isset( $reading_time ) && ! empty( $reading_time ) ) {
							   echo '<p class="reading-time-blog-one">' . esc_html( $reading_time ) . ' min read</p>';
						   } else {
							   echo '<p class="reading-time-blog-one">N/A</p>';
						   }
						   ?>
					   </div>
					   <div class="divider">|</div>	
					   <div class="publish-date-blog-one">
						   <?php 
						   if ( isset( $post_date ) ) {
							   echo '<p class="post-publish-date-blog-one">' . esc_html( $post_date ) . '</p>';
						   }
						   ?>
					   </div>	
				   </div>  	

				   <?php 
				   // Display the event title
				   if ( isset( $event_title ) && $event_title ) {
					   echo '<a href="' . esc_url( $event_permalink ) . '"><h2 class="event-title-archive-blog-one">' . esc_html( $event_title ) . '</h2></a>';
				   }		  
				   ?>

				   <!--Display the author name-->
				   <div class="author-name-blog-one">
						<?php
							if (isset($author_name) && $author_name != '') {
								echo '<p class="author-name-blog-one">Written by ' . esc_html($author_name) . '</p>';
							}
						?>
					</div>  

				   <!--Display the relevant categories on the post-->  
				   <div class="categories-cloud-archive-blog-one">
					   <?php 
					   // If categories exist, display
					   if ( $categories ) {
						   echo '<div class="entry-categories-archive-blog-one">';
						   foreach ( $categories as $category ) {
							   echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="categories-cloud-link-archive-blog-one">' . esc_html( $category->name ) . '</a> ';
						   }
						   echo '</div>';
					   }
					   ?>
				   </div>

				   <?php

				   // Display the post excerpt if it is set
				   if ( isset( $post_excerpt ) ) {
					   echo '<div class="post-excerpt-blog-one">' . esc_html( $post_excerpt ) . '</div>';
					   echo '<a href="' . esc_url( get_permalink()) . '" class="read-more-link-blog-one"><span>Read More</span><img src="https://akashint.wpengine.com/wp-content/uploads/2023/04/vuesax-bold-arrow-right.jpg" alt="Read More"></a>';
				   }

				   ?>
			   </div>		
		   </div>

            <?php
		    get_template_part( 'template-parts/content/content', get_post_type());
        endwhile;
		the_posts_pagination();
	else:
    	get_template_part( 'template-parts/content/content-none' );		   
    endif;
    wp_reset_postdata();
    ?>
 </div>
</div>

<!--***************************************************************************************** Other Posts *****************************************************************************************-->
<!--retrieve and display posts-->
<div class="custom-posts-container-blog">
 <div class="custom-posts-wrapper-blog">
    <?php
       $query = new WP_Query( array(
        'posts_per_page' => 6, //six maximum number of post to display per page 
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
		'offset'         => 1, // Skip the first post  
    ) );

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            ?>

           <div class="custom-post-blog">
			  <?php
				// Get the content template based on the display option
				get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );

				// Get the event title
				$event_title = sanitize_text_field(trim(get_the_title()));
				// Get the permalink of the posts
				$event_permalink = sanitize_text_field(trim(get_permalink()));
			    // Retrieve the post publish date
				$post_date = sanitize_text_field( trim( get_the_date('j M Y')));
				//Get the categories
				$categories = get_the_category();
			   	// Get the post excerpt
				$post_excerpt = sanitize_textarea_field( trim(get_the_excerpt()));
			  ?>

			  <div class="post-image-wrapper-blog">
				<!--Get the post featured image and if there is no featured image display the default thumbnail-->
				<div class="featured-image-blog">
				<a href="<?php echo esc_url( $event_permalink ); ?>">
				  <?php
					$post_thumbnail_id = get_post_thumbnail_id();
					if ( isset( $post_thumbnail_id ) && has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					} else {
						echo '<img src="http://localhost:8012/surgeglobal/akashint/wp-content/uploads/2023/04/default-event-image.jpg" class="no-featured-image-blog">';
					}
				  ?>
				</a>
				</div>  	
				
				<!--Display the custom field 'reading_time'-->
				<div class="date-time-blog">
				<div class="reading-time-blog">
					<?php
						$reading_time = get_estimated_reading_time();
						if ( isset( $reading_time ) && ! empty( $reading_time ) ) {
							echo '<p class="reading-time-blog-one">' . esc_html( $reading_time ) . ' min read</p>';
						} else {
							echo '<p class="reading-time-blog-one">N/A</p>';
						}
					?>
				</div>
				<div class="divider">|</div>	
				<div class="publish-date-blog">
					<?php 
						if ( isset( $post_date ) ) {
							echo '<p class="post-publish-date-blog">' . esc_html( $post_date ) . '</p>';
						}
					?>
				</div>	
				</div>  	
				  
				<!--Display the relevant categories on the post-->  
				<div class="categories-cloud-archive-blog">
					<?php 
					// If categories exist, display
					if ( $categories ) {
						echo '<div class="entry-categories-archive-blog">';
						foreach ( $categories as $category ) {
							echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="categories-cloud-link-archive-blog">' . esc_html( $category->name ) . '</a> ';
						}
						echo '</div>';
					}
					?>
				</div>
			  </div>

			  <div class="post-text-content-blog">
				  <?php  
				  
				  // Display the event title
				  if ( isset( $event_title ) && $event_title ) {
					  echo '<a href="' . esc_url( $event_permalink ) . '"><h2 class="event-title-archive-blog">' . esc_html( $event_title ) . '</h2></a>';
				  }
				  
				  // Display the post excerpt if it is set
				  if ( isset( $post_excerpt ) ) {
					  echo '<div class="post-excerpt-blog">' . esc_html( $post_excerpt ) . '</div>';
					  echo '<a href="' . esc_url( get_permalink()) . '" class="read-more-link-blog"><span>Read More</span><img src="https://akashint.wpengine.com/wp-content/uploads/2023/04/vuesax-bold-arrow-right.jpg" alt="Read More"></a>';
				  }
				  ?>
			  </div>		
		  </div>

            <?php
        endwhile;
    endif;
    wp_reset_postdata();
    ?>
 </div>
	
	<div class="load-more-btn-gif">
		<button id="load-more-btn" class="btn-load-more">Load More</button>
		<img src="<?php echo get_stylesheet_directory_uri(). '/assets/images/loadinggif.gif'; ?>" style="display: none;" width="50px" id="loadinggif-image"/>
		<div class="append-post-message"></div>
	</div>

	<script type="text/javascript">
		jQuery(function($) {
			var nonce = '<?php echo wp_create_nonce( "post_load" ); ?>';
			var page = 1; // current page
			var postsPerPage = 6; // posts per page
			var maxPages = <?php echo $query->max_num_pages; ?>; // total pages
			var loading = false; // loading flag, once the request is completed, loading is set back to false

			$('#load-more-btn').click(function() {
				if (page < maxPages && !loading) {
					loading = true;
					page++;

					$.ajax({
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						type: 'post',
						data: {
							action: 'post_load',
							page: page,
							postsPerPage: postsPerPage,
							'nonce': nonce,
						},
						beforeSend: function() {
							$('#load-more-btn').hide();
							$('#loadinggif-image').fadeIn();
						},
						success: function(data) {
							$('.custom-posts-wrapper-blog').append(data);
							loading = false;
							// check if all posts have been loaded
							if (page == maxPages) {
								// hide the load more button and loading GIF
								$('#load-more-btn').hide();
								$('#loadinggif-image').hide();
								// append a message to the container for the posts
								$('.append-post-message').append('<p class="post-message">All posts have been loaded.</p>');
							} else {
								// show the load more button
								$('#load-more-btn').show();
							}
						},
						error: function() {
							// display error message
							loading = false;
						},
						complete: function() {
							// hide the loading GIF
							$('#loadinggif-image').hide();
						}
					});
				}
			});
		});
	</script>
	
</div>
<?php get_footer(); ?>

