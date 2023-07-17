<?php
/*
Template Name: Blog Template
Template Post Type: post
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

// Get the categories assigned to the current event post
$categories = get_the_category();
?>

<!--Display the custom field 'reading_time', 'publish date' and 'author name'-->
<div class="date-time-blog-singel">
	<div class="reading-time-blog-singel">
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
	<div class="publish-date-blog-singel">
		<?php 
		if ( isset( $post_date ) ) {
			echo '<p class="post-publish-date-blog-singel">' . esc_html( $post_date ) . '</p>';
		}
		?>
	</div>
	<div class="divider">|</div>
	<!--Display the author name-->
	<div class="author-name-blog-singel">
		<?php
		if (isset($author_name) && $author_name != '') {
			echo '<p class="author-name-blog-one">Written by ' . esc_html($author_name) . '</p>';
		} else {
			echo '<p class="author-name-blog-singel">N/A</p>';
		}
		?>
	</div>
</div>
	
<?php
// get the post title
echo '<h2 class="display_data_title-blog-single">' . sanitize_text_field( trim( get_the_title() ) ) . '</h2>';
?>

<!--get the post's relevant categories-->
<div class="categories-cloud-blog-single">
<?php	
// If categories exist, display
if ( $categories && ! is_wp_error( $categories ) ) {
    echo '<div class="entry-categories-blog-single">';
    //echo '<strong>Categories:</strong> ';
    foreach ( $categories as $category ) {
        //echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="categories-cloud-link-blog-single">' . esc_html( $category->name ) . '</a> ';
        echo '<a class="categories-cloud-link-blog-single">' . esc_html( $category->name ) . '</a> ';
    }
    echo '</div>';
}
?>
</div>

<?php
// Display featured image
if (has_post_thumbnail()) {
  $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
  echo '<div class="featured-image-view-blog-single"><img src="' . esc_url($featured_image_url) . '" alt="Featured Image"/></div>';
} else {
  echo '<div class="featured-image-view-blog-single"><img src="' . get_stylesheet_directory_uri() . '/single-default-event-image.jpg" alt="Default Image"></div>';
}
?>

<!--get the post content-->
<div class="custom-post-get_content-blog-single"><?php echo sanitize_text_field( trim( get_the_content() ) ); ?></div>

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