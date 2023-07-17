<?php
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

$description = get_the_archive_description();
?>

<?php if ( have_posts() ) : ?>

<header class="page-header alignwide">
	<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
	<?php if ( $description ) : ?>
	<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
	<?php endif; ?>
</header><!-- .page-header -->

<h1 class="heading-archive">EVENTS</h1>
	<div class="subheading-archive">
	  <div class="subheading-wrapper">
		<h4 class="event-sub-heading">[Our Events]</h4>
		<h1 class="new-sub-heading">What's New?</h1>
	  </div>
	  <p class="event-details-archive">The day after the conference, continue learning with one of our expert <span class="break-word">trainers. </span>Places are limited - first come, first served!</p>
	</div>

	<div class="custom-posts-container">
	   <div class="custom-posts-wrapper">

		<?php
		   //**************************** retrieve a list of event posts filtered by a specific category name **************************************************
			$category = get_query_var( 'event-categories' ); // Get the category name from the URL parameter
			$args = array(
				'post_type' => 'event',
				'meta_key' => 'event_date',
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'posts_per_page' => -1, // To display all posts
				'event-categories' => $category, // Show only posts from the specified category
				'post_status' => 'publish' // Show only published posts
			);
			$query = new WP_Query( $args );
		?>   
		
		<!--This loop iterates through posts retrieved using a WP_Query object-->
		<?php while (  $query->have_posts() ) : ?>
			<?php $query->the_post(); ?>

			<div class="custom-post">
			  <?php
				// Get the content template based on the display option
				get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );

				// Get the custom field 'event_date'
				$event_date = sanitize_text_field(trim(get_post_meta( get_the_ID(), 'event_date', true )));
				// Get the custom field 'event_address'
				$address = sanitize_text_field(trim(get_post_meta( get_the_ID(), 'address', true )));
				// Get the event title
				$event_title = sanitize_text_field(trim(get_the_title()));
				// Get the permalink of the event
				$event_permalink = sanitize_text_field(trim(get_permalink()));
				// Get the categories  
				$categories = get_the_terms( get_the_ID(), 'event-categories' );

				// Check if event is past or upcoming
				$event_status = '';
				if ( isset( $event_date ) ) {
					$event_date_unix = strtotime( $event_date );
					$today_unix = strtotime( 'today' );
					if ( $event_date_unix < $today_unix ) {
						$event_status = 'Expired';
					}
				}
			  ?>

			  <div class="post-image-wrapper">
				<!--Get the post featured image and if there is no featured image display the default thumbnail-->  
				<a href="<?php echo esc_url( $event_permalink ); ?>">
				  <?php
					$post_thumbnail_id = get_post_thumbnail_id();
					if ( isset( $post_thumbnail_id ) && has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					} else {
						echo '<img src="https://akashint.wpengine.com/wp-content/uploads/2023/04/default-event-image.jpg" class="no-featured-image">';
					}
				  ?>
				</a>
				
				<!--If the date is past date display the expired label on the post-->
				<div class="event-status-archive-div">
					 <?php 
						if ( isset( $event_status ) && $event_status ) {
							echo '<span class="event-status-archive">' . esc_html( $event_status ) . '</span>';
						}
					 ?>
				</div>
				
				<!--Display the relevant categories on the post-->  
				<div class="categories-cloud-archive">
					<?php 
					// If categories exist, display
					if ( $categories && ! is_wp_error( $categories ) ) {
						echo '<div class="entry-categories-archive">';
						foreach ( $categories as $category ) {
							echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="categories-cloud-link-archive">' . esc_html( $category->name ) . '</a> ';
						}
						echo '</div>';
					}
					?>
				</div>
			  </div>

			  <div class="post-text-content">
				  <?php  
				  // Display the custom field 'event_date'
				  if ( isset( $event_date ) && $event_date ) {
					  $formatted_date = date('F j, Y', strtotime($event_date));
					  echo '<p class="event-date-archive">[ ' . esc_html( $formatted_date ) . ' ]</p>';
				  }
				  
				  // Display the custom field 'address'
				  if ( isset( $address ) && $address ) {
					  echo '<p class="event-address-archive">' . esc_html( $address ) . '</p>';
			      }

				  // Display the event title
				  if ( isset( $event_title ) && $event_title ) {
					  echo '<a href="' . esc_url( $event_permalink ) . '"><h2 class="event-title-archive">' . esc_html( $event_title ) . '</h2></a>';
				  }
				  ?>
			  </div>		
			</div>
		<?php endwhile; ?>
	   </div>
	</div>
<?php wp_reset_postdata(); ?>
<?php twenty_twenty_one_the_posts_navigation(); ?>

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php
//get_footer();
?>