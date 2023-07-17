<?php
function add_jquery() {
    wp_enqueue_script( 'jquery' );
}
add_action('init', 'add_jquery');


add_action('wp_enqueue_scripts', 'event_custom_css_frontend');
function event_custom_css_frontend() {	
    wp_enqueue_style( 'custom_css_frontend', get_stylesheet_directory_uri(). '/style.css',false,'1.0','all'); 
}


/************************************************************************************* timepicker and custom js *************************************************************************************/
add_action( 'admin_enqueue_scripts', 'event_custom_file' );
function event_custom_file() {
	wp_enqueue_style('admin-custom-css', get_stylesheet_directory_uri() . '/assets/css/admin-style.css', array(), '1.0', 'all');
	wp_enqueue_style( 'timepicker_css', get_stylesheet_directory_uri() . '/assets/css/timepicker.min.css', array(), '1.0', 'all');
	wp_enqueue_script( 'timepicker_js', get_stylesheet_directory_uri() . '/assets/js/timepicker.min.js', '1.0.0', array( 'jquery' ), true );
	wp_enqueue_script( 'custom_js', get_stylesheet_directory_uri() . '/assets/js/event-js.js', '1.0.0', array( 'jquery' ), true );
}
// enqueue and localize poat load
function my_theme_enqueue_scripts() {
	wp_enqueue_script( 'post-load', get_stylesheet_directory_uri() . '/assets/js/post-load.js', array( 'jquery' ), '1.0', true );
	wp_localize_script( 'post-load', 'post_load_params', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'post_load' ),
		//'max_pages' => $query->max_num_pages,
		'posts_per_page' => 6,
	));
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_scripts' );


/****************************************************************************************** flip files ***********************************************************************************************/
add_action('wp_enqueue_scripts', 'enqueue_flipjs');
function enqueue_flipjs() {	
	wp_enqueue_style( 'flipjs-style', get_stylesheet_directory_uri() . '/assets/css/flip.min.css',false,'1.0','all'); 
	wp_enqueue_script( 'flipjs-script', get_stylesheet_directory_uri() . '/assets/js/flip.min.js', '1.0.0', array( 'jquery' ), true );
}
//****************************************************************************************************************************************************************************************************

//*************************************************************************************** Custome Post Type for Events*********************************************************************************

function event_post_type() {
	$event = array(
					'description'         => 'Events',
					'show_ui'             => true,
					'menu_position'       => 2, // place menu item
					'menu_icon'           => 'dashicons-portfolio', // change menu icon
					'exclude_form_search' => false,
					'labels'			  => array(
											'name'                => 'Events',
											'singular_name'       => 'Event',
											'add_new'             => 'Add Event',
											'add_new_item'        => 'Add Event',
											'edit'                => 'Edit Event',
											'edit_item'           => 'Edit Events',
											'new-item'            => 'New Event',
											'view'                => 'View Event',
											'view-item'           => 'View Events',
											'search_item'         => 'Search Events',
											'not_found'           => 'No Events Found',
											'not_found_in_trash'  => 'No Events Found in Trash',
											'parent'              => 'Parent Events'					
					),
					'public'              => true,
					'capability_type'     => 'post',
					'hierarchical'        => false,
					'rewrite'             => true,
					'supports'            => array('title', 'editor', 'excerpt', 'thumbnail'),
					'has_archive'         => true,
					'menu_icon'   		  => 'dashicons-calendar-alt',
					'show_in_rest'        => false,
					
			);
			register_post_type('event', $event);
}

//call the function
add_action('init', 'event_post_type');

//****************************************************************************** Register Category Taxonomy for Evenets ******************************************************************************* 

if (!function_exists('event_categories_taxonomy')) {
	
	function event_categories_taxonomy() {
		$event = array(
			'labels'  => array(
					'name'               => 'Categories',
					'singular_name'      => 'Evenet Category',
					'search_items'       => 'Search Evenet Categories',
					'popular_items'      => 'Popular Evenet Categories',
					'all_items'          => 'All Evenet Categories',
					'parent_item'        => 'Parent Evenet Categories',
					'parent_item-colon'  => 'parent Evenet categories',
					'edit_item'			 => 'Edit Evenet Categories',
					'update_item'        => 'Update Evenet Categories',
					'add_new_item'       => 'Add Evenet Categories',
					'new_item_name'      => 'New Evenet Categories',
				),
					'hierarchical'       => true,
					'show_ui'            => true,
					'show_tagcloud'      => true,
					'show_admin_column'  => true,
					'rewrite'            => true,
					'public'             => true
			);
			register_taxonomy('event-categories', 'event', $event);		
	}
}

add_action('init', 'event_categories_taxonomy');

// ******************************************************************************** Register Events Tags Taxonomy ************************************************************************************

function event_tags_taxonomy() {
	$event = array(
				'labels'  => array(
					'name'               => 'Tags',
					'singular_name'      => 'Event Tag',
					'search_items'       => 'Search Event Tags',
					'popular_items'      => 'Popular Event Tags',
					'all_items'          => 'All Event Tags',
					'parent_item'        => 'Parent Event Tags',
					'parent_item-colon'  => 'parent Event tags',
					'edit_item'			 => 'Edit Event Tags',
					'update_item'        => 'Update Event Tags',
					'add_new_item'       => 'Add Event Tags',
					'new_item_name'      => 'New Event Tags',
				),
					'hierarchical'       => false,
					'show_ui'            => true,
					'show_in_rest'       => true,
					'show_admin_column'  => true,
					'query-var'          => true,  
					'rewrite'            => array ('slug' => 'event-tag'),
			);
			register_taxonomy('event-tags', 'event', $event);		
}

add_action('init', 'event_tags_taxonomy');

// *************************************************************************************** Events Meta Boxses ****************************************************************************************

function event_add_custom_box() {
    $screens = array( 'event' );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'event_datetime_box_id', // id for the meta box
            'Event Date and Time', // Title for the meta box
            'event_datetime_custom_box_html', // Callback function to display the meta box content
            $screen, // Post type where the meta box should appear
        );
        add_meta_box(
            'event_location_box_id', 
            'Event Location', 
            'event_location_custom_box_html',
            $screen, 
        );
		add_meta_box(
            'event_organizer_box_id', 
            'Event Organizer Details',
            'event_organizer_custom_box_html', 
            $screen, // 
        );
		add_meta_box(
            'event_ticket_box_id', 
            'Event Ticket Informations (Price per Person in LKR)', 
            'event_ticket_custom_box_html', 
            $screen, 
        );
    }
}
add_action( 'add_meta_boxes', 'event_add_custom_box' );

// *********** HTML for the Display custom meta box for Event Date and Time *************
function event_datetime_custom_box_html( $post ) {
    // Get the values for the custom fields
    $event_date = isset($post->ID) ? sanitize_text_field(trim(get_post_meta($post->ID, 'event_date', true))) : '';
	$event_time = isset($post->ID) ? sanitize_text_field(trim(get_post_meta($post->ID, 'event_time', true))) : '';

   // Format the date if it exists
	if ( isset( $event_date ) ) {
		$formatted_date = date( 'F j, Y', strtotime( trim( $event_date ) ) );
	} else {
		$formatted_date = '';
	}
    ?>
   
    <style>
		.date-time-container {
		  display: flex;
		  justify-content: space-between;
		  width: 100%;
		}
		.date-time-div {
		  width: 100%;
		}
		.date-time-div label {
			margin-right: 70px;
		}
		.date-time-label {
			margin-left: 0px;
		}
		.regular-text-date-time {
			width: 510px;
		}
		#event_time {
			width: 510px;
		}
	</style>

	 <!-- Display the fields for the custom meta box -->
	<div class="date-time-container">
	  <div class="date-time-div">
		<label for="event_date"><?php _e( 'Date:' ); ?> <span class="required">*</span></label>
		<input type="date" id="event_date" name="event_date" value="<?php echo isset($event_date) ? esc_attr(trim($event_date)) : ''; ?>" class="regular-text-date-time" required>
	  </div>
	  <div class="date-time-div">
		<label for="event_time" class="date-time-label"><?php _e( 'Time:' ); ?> <span class="required">*</span></label>
		<input type="text" class="time" id="event_time" name="event_time" value="<?php echo isset($event_time) ? esc_attr(trim($event_time)) : ''; ?>" class="regular-text-date-time" required>
	  </div>
	</div>

    <?php
}


// *********** HTML for the Display custom meta box for Event Location *************
function event_location_custom_box_html( $post ) {
    // Get the values for the custom fields
    $address = sanitize_text_field( trim( get_post_meta( $post->ID, 'address', true ) ) );
	$city = sanitize_text_field( trim( get_post_meta( $post->ID, 'city', true ) ) );
	$state = sanitize_text_field( trim( get_post_meta( $post->ID, 'state', true ) ) );
	$zipcode = sanitize_text_field( trim( get_post_meta( $post->ID, 'zipcode', true ) ) );
    ?>
	
	<style>
		.form-event-post {
			margin-bottom: 20px;
		}
		.form-event-post label {
			display: inline-block;
			width: 200px;
			margin-right: -100px;
		}
		.form-event-post input {
			width: 1200px;
		}
		.form-event-post textarea {
			width: 1200px;
		}
	</style>

    <!-- Display the fields for the custom meta box -->
	<div class="form-event-post">
		<label for="address"><?php _e( 'Address:' ); ?> <span class="required">*</span></label>
		<input type="text" id="address" name="address" value="<?php echo isset($address) ? esc_attr(trim($address)) : ''; ?>" class="regular-text" required>
	</div>
	<div class="form-event-post">
		<label for="city"><?php _e( 'City:' ); ?> <span class="required">*</span></label>
		<input type="text" id="city" name="city" value="<?php echo isset($city) ? esc_attr(trim($city)) : ''; ?>" class="regular-text" required>
	</div>
	<div class="form-event-post">
		<label for="state"><?php _e( 'State:' ); ?></label>
		<input type="text" id="state" name="state" value="<?php echo isset($state) ? esc_attr(trim($state)) : ''; ?>" class="regular-text">
	</div>
	<div class="form-event-post">
		<label for="zipcode"><?php _e( 'ZipCode:' ); ?></label>
		<input type="number" id="zipcode" name="zipcode" value="<?php echo isset($zipcode) ? esc_attr(trim($zipcode)) : ''; ?>" class="regular-text">
	</div>

    <?php
}

// *********** HTML for the custom meta box for Event Organizer Details *************
function event_organizer_custom_box_html( $post ) {
    // Get the values for the custom fields
    $name = sanitize_text_field( trim( get_post_meta( $post->ID, 'name', true ) ) );
	$email = sanitize_email( trim( get_post_meta( $post->ID, 'email', true ) ) );
	$phone_number = sanitize_text_field( trim( get_post_meta( $post->ID, 'phone_number', true ) ) );
	$description = sanitize_textarea_field( trim( get_post_meta( $post->ID, 'description', true ) ) );
    ?>
	
    <!-- Display the fields for the custom meta box -->
    <div class="form-event-post">
    <label for="name"><?php _e( 'Name:' ); ?> <span class="required">*</span></label>
    <input type="text" id="name" name="name" value="<?php echo isset($name) ? esc_attr(trim($name)) : ''; ?>" class="regular-text" required>
	</div>
	<div class="form-event-post">
		<label for="email"><?php _e( 'Email:' ); ?> <span class="required">*</span></label>
		<input type="email" id="email" name="email" value="<?php echo isset($email) ? esc_attr(trim($email)) : ''; ?>" class="regular-text" required>
	</div>
	<div class="form-event-post">
		<label for="phone_number"><?php _e( 'Phone Number:' ); ?> <span class="required">*</span></label>
		<input type="tel" id="phone_number" name="phone_number" value="<?php echo isset($phone_number) ? esc_attr(trim($phone_number)) : ''; ?>" class="regular-text" required>
		<?php 
			if (isset($phone_number)) {
				$phone_number_digits = preg_replace('/[^0-9+]/', '', $phone_number);
				if (strlen($phone_number_digits) >= 9) {
					$phone_url = 'tel:' . $phone_number_digits;
				} 
			}
		?>
	</div>
	<div class="form-event-post">
		<label for="description"><?php _e( 'Description:' ); ?></label>
		<textarea name="description" id="description" rows="7" cols="50" class="regular-text"><?php echo isset($description) ? esc_attr(trim($description)) : ''; ?></textarea>       
	</div>

    <?php
}

// *********** HTML for the custom meta box for Event Tickets Informations *************
function event_ticket_custom_box_html( $post ) {
    // Get the values for the custom fields
    $ticket = sanitize_text_field( trim( get_post_meta( $post->ID, 'ticket', true ) ) );
    ?>
	
    <!-- Display the fields for the custom meta box -->
    <div class="form-event-post">
	<label for="ticket"><?php _e( 'Tickets:' ); ?> <span class="required">*</span></label>
<input type="number" step="0.01" min="0" id="ticket" name="ticket" value="<?php echo isset( $_POST['ticket'] ) ? esc_attr( trim( $_POST['ticket'] ) ) : esc_attr( $ticket ); ?>" class="regular-text" required>
	</div>
    <?php
}

// Save the custom meta box data
function event_save_postdata( $post_id ) {
	$data_time_field = array( 'event_date', 'event_time' );
	$location_field  = array( 'address', 'city', 'state', 'zipcode' );
	$organizer_field = array( 'name', 'email', 'phone_number', 'description' );
	$ticket_field	 = array( 'ticket');

    // Save Date and Time fields
    foreach ( $data_time_field as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field(trim( $_POST[ $field ])));
        }
    }

    // Save Location field
    foreach ( $location_field as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field(trim( $_POST[ $field ])));
        }
    }
	
	// Save Organizer field
    foreach ( $organizer_field as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field(trim( $_POST[ $field ])));
        }
    }
	
	// Save Ticket field
    foreach ( $ticket_field as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field(trim( $_POST[ $field ])));
        }
    }
}
add_action( 'save_post_event', 'event_save_postdata' );

?>

<?php 
function remove_custom_post_type_content() {
  if (is_singular('event')) {
    remove_action( 'event' . '_entry_content', 'twenty_twenty_one_entry_content' );
  }
}
add_action('wp', 'remove_custom_post_type_content');
?>

<?php 
//******************************************************************************************* Event CountDown Timer ***********************************************************************************
function countdown_shortcode($atts) {
    // Get the event date and time
    $event_date = sanitize_text_field(trim(get_post_meta($atts['event_id'], 'event_date', true)));
    $event_time = sanitize_text_field(trim(get_post_meta($atts['event_id'], 'event_time', true)));

    // Convert the event date and time to Unix timestamp
    $event_datetime = strtotime( $event_date . ' ' . $event_time );

    // Calculate the time left for the event
    $time_left = $event_datetime - time();

    // Calculate the number of days, hours, minutes, and seconds left
    $days_left    = floor( $time_left / 86400 );
    $hours_left   = floor( ( $time_left % 86400 ) / 3600 );
    $minutes_left = floor( ( $time_left % 3600 ) / 60 );
    $seconds_left = $time_left % 60;

    // Start output buffering
    ob_start();

    // Generate the HTML for the countdown
    ?>
    <div class="countdown-timer">
        <span class="countdown-item"><?php echo $days_left ?> days</span>
        <span class="countdown-item"><?php echo $hours_left ?> hours</span>
        <span class="countdown-item"><?php echo $minutes_left ?> minutes</span>
        <span class="countdown-item"><?php echo $seconds_left ?> seconds</span>
    </div>
    <?php

    // Get the captured output and clean the buffer
    $html = ob_get_clean();

    return $html;
}
?>

<?php 
function next_upcoming_event_shortcode() {
    // Get the next upcoming event
    $event_args = array(
        'post_type' => 'event', // custom post type name
        'meta_key' => 'event_date', // custom field name that holds the event date
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'event_date', // custom field name that holds the event date
                'value' => date('Ymd'), // Get events that occur after today
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    );
    $event_query = new WP_Query($event_args);
	
	//retrieve information about the first post in the $event_query and store them in variables
    if ($event_query->have_posts()) {
        $event = $event_query->posts[0];
        $event_date = get_post_meta($event->ID, 'event_date', true); 
        $event_address = get_post_meta($event->ID, 'address', true); 

        // Use ob_start() to capture the output
        ob_start(); 
        ?>

		<!--********** generates an HTML block that displays an upcoming event with featured image, event date, location and countdown timer *************-->
		<div class="next-upcoming-event">
			<?php
				// Get the featured image
				$event_image = get_the_post_thumbnail($event->ID, 'full'); // specify the image size
				if (!empty($event_image)) {
					// Get the post URL
					$post_url = get_permalink($event->ID);
			?>
			<!--creates a clickable link to the event's post URL-->
			<a href="<?php echo $post_url ?>" class="event-image">
				<?php echo $event_image ?>
				<div class="event-countdown-wrapper">
					<div class="event-details">
						<h3 class="event-title"><?php echo sanitize_text_field(trim(get_the_title($event->ID))); ?></h3>
						<div class="event-info">
							
							<!--********************************* Give a date format **************************************-->
							<p class="event-date"><?php echo date('j<\s\u\p>S</\s\u\p> F Y', strtotime($event_date)) ?></p>
							<p class="event-location"><?php echo $event_address ?></p>
						</div>
					</div>

					<!--*************************************** HTML For CountDown Timer **********************************************-->
					<div class="countdown-timer">
						<div class="tick" data-did-init="handleTickInit">
							<div data-repeat="true" data-layout="horizontal center fit" data-transform="preset(d, h, m, s) -> delay">
								<div class="tick-group">
									<div data-key="value" data-repeat="true" data-transform="pad(00) -> split -> delay">
										<span data-view="flip"></span>
									</div>
									<span data-key="label" data-view="text" class="tick-label"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</a>
			<?php
				} else {
					//************************ If there is no featured image found, display default image ********************************************
			?>
			<a href="<?php echo $post_url ?>" class="event-image">
				<div class="featured-image-view">
					<!--******** creates an image element that displays the default event image when no featured image is available **********-->
					<img src="<?php echo get_stylesheet_directory_uri() ?>/single-default-event-image.jpg" alt="Default Image">
				</div>
				<div class="event-countdown-wrapper">
					<div class="event-details">
						<h3 class="event-title"><?php echo sanitize_text_field(trim(get_the_title($event->ID))); ?></h3>
						<div class="event-info">
							<p class="event-date"><?php echo date('j<\s\u\p>S</\s\u\p> F Y', strtotime($event_date)) ?></p>
							<p class="event-location"><?php echo $event_address ?></p>
						</div>
					</div>

					<div class="countdown-timer">
						<div class="tick" data-did-init="handleTickInit">
							<div data-repeat="true" data-layout="horizontal center fit" data-transform="preset(d, h, m, s) -> delay">
								<div class="tick-group">
									<div data-key="value" data-repeat="true" data-transform="pad(00) -> split -> delay">
										<span data-view="flip"></span>
									</div>
									<span data-key="label" data-view="text" class="tick-label"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</a>
			<?php
				}
			?>
		</div>

        <?php
        $output = ob_get_clean();
        // Use ob_start() again to capture the countdown timer output
        ob_start(); 
        ?>

		<!--*************************** initialize a countdown timer using the Tick.js library **********************************-->
        <script type="text/javascript">
			function handleTickInit(tick) {
				var eventDate = "<?php echo $event_date; ?>";
				// Check if $event_date is a valid date
				if (Date.parse(eventDate)) {
					eventDate = new Date(eventDate);
					Tick.count.down(eventDate).onupdate = function (value) {
						tick.value = value;
					};
				}
				// If $event_date is not a valid date, display an error message
				else {
					tick.value = "Invalid date";
				}
			}
		</script>

        <?php
        $countdown_html = ob_get_clean();

        // Concatenate the output and countdown timer
        $output .= $countdown_html;

        wp_reset_postdata();
    } else {
        $output = '<p>No upcoming events</p>';
    }

    return $output;
}
add_shortcode('next_upcoming_event', 'next_upcoming_event_shortcode');
?>

<?php 

//******************************************************************************************************************************************************************************************************
  //******************************************************************************************** WP Advance Assingment 2 **************************************************************************
//******************************************************************************************************************************************************************************************************
// add a new custom template  
add_filter( 'theme_page_templates', 'add_blog_archive_template' );
function add_blog_archive_template( $templates ) {
    $templates['archive.php'] = __( 'Blog Archive' );
    return $templates;
}
?>

<?php
// ************** get and estimated the reding time ***********************
function get_estimated_reading_time() {
	$post_id = get_the_ID(); //gets the current post ID 
	$content = get_post_field( 'post_content', $post_id ); //retrieves the content of the post
	$word_count = str_word_count( strip_tags( $content ) ); //calculates the number of words in the post content 
	$average_reading_speed = 200; //sets the average reading speed (word per minitues)
	$estimated_reading_time = ceil( $word_count / $average_reading_speed );
	return $estimated_reading_time;
}
?>

<?php 
//****************************************************** Load More Butotn *************************************************************

function post_load() {
	//sleep(3);
    $page = $_POST['page']; // get from the AJAX request
    $postsPerPage = $_POST['postsPerPage']; // get from the AJAX request
    $offset = ($page - 1) * $postsPerPage; // calculate the offset

    $query = new WP_Query(array(
        'posts_per_page' => $postsPerPage,
        'offset' => $offset + 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish',
    ));

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            ?>
          <!-- HTML for the newly loading post list -->
          <div class="custom-post-blog">
			  <?php
				// Get the content template based on the display option
				get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );

				// Get the event title
				$event_title = sanitize_text_field(trim(get_the_title()));
				// Get the permalink
				$event_permalink = sanitize_text_field(trim(get_permalink()));
			    // Retrieve the post publish date
				$post_date = get_the_date( 'j M Y' );
				//Get the categories
				$categories = get_the_category();
			   	// Get the post excerpt
				$post_excerpt = get_the_excerpt();

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
						echo '<img src="https://akashint.wpengine.com/wp-content/uploads/2023/04/default-event-image.jpg" class="no-featured-image-blog">';
					}
				  ?>
				</a>
				</div>  	
				
				<!--Display the custom field 'reading_time'-->
				<div class="date-time-blog">
				<div class="reading-time-blog">
					<?php
						//calculate the reading time by calling the function
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
				  
				  // Display the title
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
    die();
}
add_action('wp_ajax_post_load', 'post_load');
add_action('wp_ajax_nopriv_post_load', 'post_load');
?>