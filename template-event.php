<?php
/*
Template Name: Event Template
Template Post Type: event
*/

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content/content-single' );

	if ( is_attachment() ) {
		// Parent post navigation.
		the_post_navigation(
			array(
				/* translators: %s: Parent post link. */
				'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			)
		);
	}

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	//*****************************************************************************************************************************************************************************************************

//retrieves the fields values, assigned to the current event post.
$event_date = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'event_date', true ) ) );
$event_time = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'event_time', true ) ) );

$address = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'address', true ) ) );
$city = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'city', true ) ) );
$state = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'state', true ) ) );
$zipcode = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'zipcode', true ) ) );

$name = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'name', true ) ) );
$email = sanitize_email( trim( get_post_meta( get_the_ID(), 'email', true ) ) );
$phone_number = sanitize_text_field( trim( get_post_meta( get_the_ID(), 'phone_number', true ) ) );
$description = sanitize_textarea_field( trim( get_post_meta( get_the_ID(), 'description', true ) ) );

$ticket		= sanitize_text_field( trim( get_post_meta( get_the_ID(), 'ticket', true ) ) );

// Get the categories and tags assigned to the event post
$categories = get_the_terms( get_the_ID(), 'event-categories' );
$tags = get_the_terms( get_the_ID(), 'event-tags' );

// Display the featured image
if (has_post_thumbnail()) {
  $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
  echo '<div class="featured-image-view"><img src="' . esc_url($featured_image_url) . '" alt="Featured Image"/></div>';
} else {
  echo '<div class="featured-image-view"><img src="' . get_stylesheet_directory_uri() . '/single-default-event-image.jpg" alt="Default Image"></div>';
}

// get the post title
echo '<h2 class="display_data_title">' . sanitize_text_field( trim( get_the_title() ) ) . '</h2>';
?>

<!--get the post's relevant categories-->
<div class="categories-cloud">
<?php	
// If categories exist, display
if ( $categories && ! is_wp_error( $categories ) ) {
    echo '<div class="entry-categories">';
    //echo '<strong>Categories:</strong> ';
    foreach ( $categories as $category ) {
        echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="categories-cloud-link">' . esc_html( $category->name ) . '</a> ';
    }
    echo '</div>';
}
?>

<?php 
	//checks if the $event_date variable is set and then formats it into a human-readable date format
	if (isset($event_date)) {
	  $day = date('j', strtotime(sanitize_text_field( trim($event_date))));
	  $suffix = date('S', strtotime(sanitize_text_field( trim($event_date))));
	  $formatted_date = $day . '<sup>' . $suffix . '</sup> ' . date('F Y', strtotime(sanitize_text_field( trim($event_date))));
	  $now = time();
	  $event_timestamp = strtotime(sanitize_text_field( trim($event_date)));
	  if ($event_timestamp < $now) {
		echo '<p class="display_data_expired">Expired</p>';
	  } 
	}
?>	
</div>

<!--get the post content-->
<div class="custom-post-get_content"><?php echo sanitize_text_field( trim( get_the_content() ) ); ?></div>

<!--*********************************************** displaying event details **************************************************-->
<p class="display_data_title_event"><?php _e( 'Event Details' ); ?></p>
<div class="event-details">
  <?php 
    
    if (isset($event_date)) {
	  $day = date('j', strtotime($event_date));
	  $suffix = date('S', strtotime($event_date));
	  $formatted_date = $day . '<sup>' . $suffix . '</sup> ' . date('F Y', strtotime($event_date));
	  echo '<p class="display_data"><strong>Date:</strong> ' . $formatted_date . '</p>';
	}
	
    if (isset($event_time)) {
      echo '<p class="display_data"><strong>Time:</strong> ' . esc_html($event_time) . '</p>';
    }
	
    if (isset($address)) {
    $address_query = urlencode($address . ',' . $city . ',' . $state . ' ' . $zipcode);
    $address_url = "https://www.google.com/maps/search/?api=1&query=$address_query";
    echo '<p class="display_data"><strong>Location:</strong> <a href="' . esc_url($address_url) . '" target="_blank" rel="noopener noreferrer" style="text-decoration: none;" class="address-hover">' . esc_html($address) . '</a></p>';
	} else {
		echo '<p class="display_data"><strong>Location:</strong> N/A</p>';
	}
  ?>
</div>

<!--******************************************** Displaying Organizer Details ******************************************************-->
<?php echo '<p class="display_data_title_organizer">About The Organizer</p>'; ?>
<div class="organizer-details">
  <?php
    echo !empty($name) ? '<p class="display_data_none_name">' . esc_html($name) . '</p></br>' : '<p>N/A</p></br>';
    echo !empty($description) ? '<p class="display_data_none">' . esc_html($description) . '</p></br>' : '<p>N/A</p></br>';

    if (!empty($email)) {
        $email_url = 'mailto:' . $email;
        echo '<p class="display_data"><strong>Email:</strong> <a href="' . esc_url($email_url) . '" target="_blank" style="text-decoration: none;" class="email-hover">' . esc_html($email) . '</a></p>';
    } else {
        echo '<p>N/A</p></br>';
    }

    if (!empty($phone_number)) {
        $phone_url = 'tel:' . preg_replace('/[^0-9+]/', '', $phone_number);
        echo '<p class="display_data"><strong>Phone:</strong> <a href="' . esc_url($phone_url) . '" target="_blank" style="text-decoration: none;" class="phone-hover">' . esc_html($phone_number) . '</a></p>';
    } else {
        echo '<p>N/A</p></br>';
    }
  ?>
</div>

<!--******************************************** Displaying Ticket Details ******************************************************-->
<?php echo '<p class="display_data_title_ticket">Ticket Details</p>'; ?>
<div class="ticket-details">
  <?php 
	if (isset($ticket)) {
      echo '<p class="display_data"><strong>Per Person:</strong> LKR ' . esc_html($ticket) . '</p>';
    }
  ?>
</div>

<!--get the post's relevant tags-->
<div class="tag-cloud">
<?php 
	 echo '<p class="display_data_title_tag">Tag Cloud</p>';
	// If tags exist, display
	if ( $tags && ! is_wp_error( $tags ) ) {
		echo '<div class="entry-tags">';
		foreach ( $tags as $tag ) {
			echo '<a href="' . esc_url( get_term_link( $tag ) ) . '"class="tags-cloud-link">' . esc_html( $tag->name ) . '</a> ';
		}
		echo '</div>';
	}
?>
</div>
<?php
//****************************************************************************************************************************************************************************************************

	// Previous/next post navigation.
	$twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
	$twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );

	$twentytwentyone_next_label     = esc_html__( 'Next post', 'twentytwentyone' );
	$twentytwentyone_previous_label = esc_html__( 'Previous post', 'twentytwentyone' );

	the_post_navigation(
		array(
			'next_text' => '<p class="meta-nav">' . $twentytwentyone_next_label . $twentytwentyone_next . '</p><p class="post-title">%title</p>',
			'prev_text' => '<p class="meta-nav">' . $twentytwentyone_prev . $twentytwentyone_previous_label . '</p><p class="post-title">%title</p>',
		)
	);
endwhile; // End of the loop.
?>


<?php
get_footer();
?>

<style>
.single-event .entry-content {
   display: none!important;
}		
</style>