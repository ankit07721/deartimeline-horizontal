<?php
/*
Plugin Name: Dear Timeline (Best Vertical & Horizontal Timeline)
Description: A responsive vertical and horizontal timeline designed for modern WordPress sites.
Version: 1.4.0
Author: Ankit Gyanwali
License: GPLv2 or later
Text Domain: deartimeline
Requires PHP: 7.4
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register a Custom Post Type for Timeline Events.
 */
function dearti_register_cpt() {
	$labels = array(
		'name'                  => _x( 'Timeline Events', 'Post Type General Name', 'deartimeline' ),
		'singular_name'         => _x( 'Timeline Event', 'Post Type Singular Name', 'deartimeline' ),
		'menu_name'             => __( 'Timeline', 'deartimeline' ),
		'name_admin_bar'        => __( 'Timeline Event', 'deartimeline' ),
		'archives'              => __( 'Item Archives', 'deartimeline' ),
		'attributes'            => __( 'Item Attributes', 'deartimeline' ),
		'parent_item_colon'     => __( 'Parent Item:', 'deartimeline' ),
		'all_items'             => __( 'All Events', 'deartimeline' ),
		'add_new_item'          => __( 'Add New Event', 'deartimeline' ),
		'add_new'               => __( 'Add New', 'deartimeline' ),
		'new_item'              => __( 'New Event', 'deartimeline' ),
		'edit_item'             => __( 'Edit Event', 'deartimeline' ),
		'update_item'           => __( 'Update Event', 'deartimeline' ),
		'view_item'             => __( 'View Event', 'deartimeline' ),
		'view_items'            => __( 'View Events', 'deartimeline' ),
		'search_items'          => __( 'Search Event', 'deartimeline' ),
		'not_found'             => __( 'Not found', 'deartimeline' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'deartimeline' ),
		'featured_image'        => __( 'Featured Image', 'deartimeline' ),
		'set_featured_image'    => __( 'Set featured image', 'deartimeline' ),
		'remove_featured_image' => __( 'Remove featured image', 'deartimeline' ),
		'use_featured_image'    => __( 'Use as featured image', 'deartimeline' ),
		'insert_into_item'      => __( 'Insert into item', 'deartimeline' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'deartimeline' ),
		'items_list'            => __( 'Items list', 'deartimeline' ),
		'items_list_navigation' => __( 'Items list navigation', 'deartimeline' ),
		'filter_items_list'     => __( 'Filter items list', 'deartimeline' ),
	);
	$args = array(
		'label'                 => __( 'Timeline Event', 'deartimeline' ),
		'description'           => __( 'Events for the timeline', 'deartimeline' ),
		'labels'                => $labels,
		'supports'              => array( 'page-attributes' ), // Removing 'title', 'editor', and 'thumbnail' for clean interface
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => plugin_dir_url( __FILE__ ) . 'assets/images/logo.png',
		'show_in_nav_menus'     => true,
		'show_in_admin_bar'     => true,
		'show_in_rest'          => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
	);
	register_post_type( 'dearti_event', $args );

	// Register Taxonomy for Categories
	$cat_labels = array(
		'name'              => _x( 'Timeline Categories', 'taxonomy general name', 'deartimeline' ),
		'singular_name'     => _x( 'Timeline Category', 'taxonomy singular name', 'deartimeline' ),
		'search_items'      => __( 'Search Categories', 'deartimeline' ),
		'all_items'         => __( 'All Categories', 'deartimeline' ),
		'parent_item'       => __( 'Parent Category', 'deartimeline' ),
		'parent_item_colon' => __( 'Parent Category:', 'deartimeline' ),
		'edit_item'         => __( 'Edit Category', 'deartimeline' ),
		'update_item'       => __( 'Update Category', 'deartimeline' ),
		'add_new_item'      => __( 'Add New Category', 'deartimeline' ),
		'new_item_name'     => __( 'New Category Name', 'deartimeline' ),
		'menu_name'         => __( 'Categories', 'deartimeline' ),
	);

	$cat_args = array(
		'hierarchical'      => true,
		'labels'            => $cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'timeline-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'dearti_category', array( 'dearti_event' ), $cat_args );
}
add_action( 'init', 'dearti_register_cpt', 0 );

/**
 * Add submenu page and button.
 */
function dearti_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=dearti_event',
		__( 'Live Desktop Preview', 'deartimeline' ),
		__( 'Live Preview', 'deartimeline' ),
		'edit_posts',
		'dearti-live-preview',
		'dearti_render_live_preview_page'
	);
}
add_action( 'admin_menu', 'dearti_admin_menu' );

function dearti_render_live_preview_page() {
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Live Desktop Preview', 'deartimeline' ); ?></h1>
		<hr class="wp-header-end">
		<div style="background: #f8fafc; padding: 40px; margin-top: 20px; border-radius: 8px; border: 1px dashed #cbd5e1; box-shadow: 0 4px 6px rgba(0,0,0,0.05); min-height: 500px;">
			<?php echo do_shortcode( '[deartimeline layout="flat"]' ); ?>
		</div>
	</div>
	<?php
}

function dearti_add_preview_button() {
	$screen = get_current_screen();
	if ( $screen && $screen->id === 'edit-dearti_event' ) {
		$url = admin_url( 'edit.php?post_type=dearti_event&page=dearti-live-preview' );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('<a href="<?php echo esc_url( $url ); ?>" class="page-title-action" style="background:#4f46e5; color:#fff; border-color:#4338ca; font-weight: 600;">Live Desktop Preview</a>').insertAfter('.page-title-action');
			});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'dearti_add_preview_button' );

/**
 * Add Meta Box for Background Color
 */
function dearti_add_meta_boxes() {
	add_meta_box(
		'dearti_live_preview_box',
		__( 'Live Desktop Preview', 'deartimeline' ),
		'dearti_render_preview_meta_box',
		'dearti_event',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'dearti_add_meta_boxes' );

function dearti_color_meta_box_html( $post ) {
	$color_value = get_post_meta( $post->ID, '_dearti_color', true );
	$year_value  = get_post_meta( $post->ID, '_dearti_date', true ); // Used for Year
	$day_value   = get_post_meta( $post->ID, '_dearti_day_month', true ); // New field
	$title_meta  = get_post_meta( $post->ID, '_dearti_title', true );
	$description = get_post_meta( $post->ID, '_dearti_description', true );
	$img_width   = get_post_meta( $post->ID, '_dearti_img_width', true );
	$img_height  = get_post_meta( $post->ID, '_dearti_img_height', true );

	wp_nonce_field( 'dearti_save_meta_box_data', 'dearti_meta_box_nonce' );
	?>
	<div class="dearti-admin-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0;">
		<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/images/logo.png" style="width: 50px; height: 50px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
		<h2 style="margin: 0; font-size: 1.5rem; color: #1e293b;"><?php esc_html_e( 'DearTimeline Editor', 'deartimeline' ); ?></h2>
	</div>
	<p>
		<label for="deartimeline_title_field"><strong><?php esc_html_e( 'Event Title:', 'deartimeline' ); ?></strong></label><br>
		<input type="text" id="deartimeline_title_field" name="deartimeline_title_field" value="<?php echo esc_attr( $title_meta ); ?>" style="width: 100%; margin-top: 5px;" placeholder="<?php esc_attr_e( 'If empty, main Title is used.', 'deartimeline' ); ?>">
	</p>
	<p>
		<label for="deartimeline_full_date"><strong><?php esc_html_e( 'Event Date:', 'deartimeline' ); ?></strong></label><br>
		<?php 
		$formatted_date = '';
		if ( $year_value && $day_value ) {
			$timestamp = strtotime("$day_value $year_value");
			if ( $timestamp ) $formatted_date = gmdate('Y-m-d', $timestamp);
		}
		?>
		<input type="date" id="deartimeline_full_date" name="deartimeline_full_date" value="<?php echo esc_attr( $formatted_date ); ?>" style="width: 100%; margin-top: 5px;">
		<small><?php esc_html_e( 'Select the event date for the calendar box display.', 'deartimeline' ); ?></small>
	</p>
	<p>
		<label for="deartimeline_color_field"><strong><?php esc_html_e( 'Background Color:', 'deartimeline' ); ?></strong></label><br>
		<input type="color" id="deartimeline_color_field" name="deartimeline_color_field" value="<?php echo esc_attr( $color_value ? $color_value : '#ffffff' ); ?>" style="height: 40px; vertical-align: middle;">
	</p>
	<p>
		<label for="deartimeline_description_field"><strong><?php esc_html_e( 'Event Description:', 'deartimeline' ); ?></strong></label><br>
		<textarea id="deartimeline_description_field" name="deartimeline_description_field" rows="4" style="width: 100%; margin-top: 5px;"><?php echo esc_textarea( $description ); ?></textarea>
		<small><?php esc_html_e( 'This description will be shown on the timeline. If left empty, the main content will be used.', 'deartimeline' ); ?></small>
	</p>
	<p>
		<label><strong><?php esc_html_e( 'Featured Image:', 'deartimeline' ); ?></strong></label><br>
		<div id="dearti-image-preview" style="margin: 10px 0; min-height: 100px; max-width: 200px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
			<?php 
			$thumb_id = get_post_thumbnail_id( $post->ID );
			if ( $thumb_id ) {
				echo wp_get_attachment_image( $thumb_id, 'thumbnail', false, array( 'style' => 'width:100%; height:auto;' ) );
			} else {
				echo '<span class="dashicons dashicons-format-image" style="font-size: 40px; width: 40px; height: 40px; color: #94a3b8;"></span>';
			}
			?>
		</div>
		<input type="hidden" id="dearti_image_id" name="dearti_image_id" value="<?php echo esc_attr( $thumb_id ); ?>">
		<button type="button" id="dearti_select_image" class="button"><?php esc_html_e( 'Set Featured Image', 'deartimeline' ); ?></button>
		<button type="button" id="dearti_remove_image" class="button" style="<?php echo $thumb_id ? '' : 'display:none;'; ?> color: #ef4444; border-color: #ef4444;"><?php esc_html_e( 'Remove', 'deartimeline' ); ?></button>
	</p>
	<div style="display: flex; gap: 20px;">
		<p style="flex: 1;">
			<label for="deartimeline_img_width"><strong><?php esc_html_e( 'Image Width:', 'deartimeline' ); ?></strong></label><br>
			<input type="text" id="deartimeline_img_width" name="deartimeline_img_width" value="<?php echo esc_attr( $img_width ); ?>" placeholder="e.g. 100% or 300px" style="width: 100%;">
		</p>
		<p style="flex: 1;">
			<label for="deartimeline_img_height"><strong><?php esc_html_e( 'Image Height:', 'deartimeline' ); ?></strong></label><br>
			<input type="text" id="deartimeline_img_height" name="deartimeline_img_height" value="<?php echo esc_attr( $img_height ); ?>" placeholder="e.g. auto or 200px" style="width: 100%;">
		</p>
	</div>
	<?php
}

function dearti_render_preview_meta_box() {
	?>
	<div class="dearti-preview-wrap">
		<div id="dearti-preview-content">
			<div class="tsp-timeline tsp-layout-flat" style="padding-top:0;">
				<div class="dearti-add-spine-item">
					<div class="dearti-add-spine-col"></div>
					<div class="dearti-add-spine-line">
						<button type="button" id="dearti-quick-add-btn" title="<?php esc_attr_e( 'Quick Add Event', 'deartimeline' ); ?>">+</button>
					</div>
					<div class="dearti-add-spine-label">
						<span class="dearti-quick-add-label" onclick="jQuery('#dearti-quick-add-btn').trigger('click');"><?php esc_html_e( 'Add New Event', 'deartimeline' ); ?></span>
					</div>
				</div>
			</div>
			<?php echo do_shortcode( '[deartimeline layout="flat"]' ); ?>
		</div>
	</div>
	<?php
}

function dearti_save_meta_box_data( $post_id ) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['dearti_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dearti_meta_box_nonce'] ) ), 'dearti_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( ( isset( $_POST['post_type'] ) && 'dearti_event' === sanitize_key( wp_unslash( $_POST['post_type'] ) ) ) ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( isset( $_POST['deartimeline_color_field'] ) ) {
		update_post_meta( $post_id, '_dearti_color', sanitize_hex_color( wp_unslash( $_POST['deartimeline_color_field'] ) ) );
	}

	if ( isset( $_POST['deartimeline_full_date'] ) ) {
		$date_raw = sanitize_text_field( wp_unslash( $_POST['deartimeline_full_date'] ) );
		if ( $date_raw ) {
			$timestamp = strtotime( $date_raw );
			if ( $timestamp ) {
				$year      = gmdate( 'Y', $timestamp );
				$day_month = gmdate( 'M d', $timestamp );
				update_post_meta( $post_id, '_dearti_date', $year );
				update_post_meta( $post_id, '_dearti_day_month', $day_month );
			}
		}
	}

	if ( isset( $_POST['deartimeline_description_field'] ) ) {
		update_post_meta( $post_id, '_dearti_description', wp_kses_post( wp_unslash( $_POST['deartimeline_description_field'] ) ) );
	}

	if ( isset( $_POST['deartimeline_title_field'] ) ) {
		$title = sanitize_text_field( wp_unslash( $_POST['deartimeline_title_field'] ) );
		update_post_meta( $post_id, '_dearti_title', $title );

		// Sync back to post_title to prevent (no title) in list table
		if ( ! empty( $title ) ) {
			remove_action( 'save_post', 'dearti_save_meta_box_data' );
			wp_update_post( array(
				'ID'         => $post_id,
				'post_title' => $title
			) );
			add_action( 'save_post', 'dearti_save_meta_box_data' );
		}
	}

	if ( isset( $_POST['deartimeline_img_width'] ) ) {
		update_post_meta( $post_id, '_dearti_img_width', sanitize_text_field( wp_unslash( $_POST['deartimeline_img_width'] ) ) );
	}

	if ( isset( $_POST['deartimeline_img_height'] ) ) {
		update_post_meta( $post_id, '_dearti_img_height', sanitize_text_field( wp_unslash( $_POST['deartimeline_img_height'] ) ) );
	}

	if ( isset( $_POST['dearti_image_id'] ) ) {
		$img_id = intval( $_POST['dearti_image_id'] );
		if ( $img_id > 0 ) {
			set_post_thumbnail( $post_id, $img_id );
		} else {
			delete_post_thumbnail( $post_id );
		}
	}
}
add_action( 'save_post', 'dearti_save_meta_box_data' );

/**
 * Enqueue scripts and styles.
 */
function dearti_enqueue_scripts() {
	// Local Flatpickr
	wp_enqueue_style( 'flatpickr-css', plugin_dir_url( __FILE__ ) . 'assets/vendor/flatpickr.min.css', array(), '4.6.13' );
	wp_enqueue_script( 'flatpickr-js', plugin_dir_url( __FILE__ ) . 'assets/vendor/flatpickr.min.js', array(), '4.6.13', true );

	wp_enqueue_style( 'dearti-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array( 'flatpickr-css' ), '1.4.0' );
	wp_enqueue_script( 'dearti-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery', 'flatpickr-js' ), '1.4.0', true );

	// Localize script for AJAX support
	wp_localize_script( 'dearti-script', 'dearti_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'dearti_create_event_nonce' )
	) );
}
add_action( 'wp_enqueue_scripts', 'dearti_enqueue_scripts' );

/**
 * Enqueue scripts and styles for Admin.
 */
function dearti_admin_enqueue_scripts( $hook ) {
	// If on the Live Preview Submenu Page, load the frontend styles and scripts
	if ( isset($_GET['page']) && $_GET['page'] === 'dearti-live-preview' ) {
		wp_enqueue_style( 'dearti-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.4.0' );
		wp_enqueue_script( 'dearti-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.4.0', true );
		
		wp_localize_script( 'dearti-script', 'dearti_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'dearti_create_event_nonce' )
		) );
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'dearti_event' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'flatpickr-css', plugin_dir_url( __FILE__ ) . 'assets/vendor/flatpickr.min.css', array(), '4.6.13' );
	wp_enqueue_script( 'flatpickr-js', plugin_dir_url( __FILE__ ) . 'assets/vendor/flatpickr.min.js', array(), '4.6.13', true );
	wp_enqueue_style( 'dearti-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.4.0' );
	wp_enqueue_script( 'dearti-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery', 'flatpickr-js' ), '1.4.0', true );

	wp_localize_script( 'dearti-script', 'dearti_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'dearti_create_event_nonce' )
	) );
}
add_action( 'admin_enqueue_scripts', 'dearti_admin_enqueue_scripts' );

/**
 * AJAX Handler for Creating Events from Frontend
 */
function dearti_handle_ajax_create_event() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );

	if ( ! current_user_can( 'publish_posts' ) ) {
		wp_send_json_error( 'Unauthorized: You do not have permission to create events.' );
	}

	if ( empty( $_POST['event_titles'] ) || ! is_array( $_POST['event_titles'] ) ) {
		wp_send_json_error( 'No events provided.' );
	}

	$titles   = $_POST['event_titles'];
	$dates    = $_POST['event_dates'] ?? array();
	$contents = $_POST['event_contents'] ?? array();
	$widths   = $_POST['event_image_widths'] ?? array();
	$heights  = $_POST['event_image_heights'] ?? array();
	$count    = count( $titles );
	$created  = 0;

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	for ( $i = 0; $i < $count; $i++ ) {
		$title   = sanitize_text_field( wp_unslash( $titles[$i] ) );
		$content = wp_kses_post( wp_unslash( $contents[$i] ?? '' ) );
		$date    = sanitize_text_field( wp_unslash( $dates[$i] ?? '' ) );

		if ( empty( $title ) ) continue;

		// Create the Post
		$post_id = wp_insert_post( array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'dearti_event'
		) );

		if ( is_wp_error( $post_id ) ) continue;

		// Handle Date Meta
		if ( ! empty( $date ) ) {
			$timestamp = strtotime( $date );
			if ( $timestamp ) {
				update_post_meta( $post_id, '_dearti_date', gmdate( 'Y', $timestamp ) );
				update_post_meta( $post_id, '_dearti_day_month', gmdate( 'M d', $timestamp ) );
			}
		}

		// Handle Optional Styling
		update_post_meta( $post_id, '_dearti_color', '#4f46e5' );
		if ( ! empty( $widths[$i] ) ) {
			update_post_meta( $post_id, '_dearti_img_width', sanitize_text_field( wp_unslash( $widths[$i] ) ) );
		}
		if ( ! empty( $heights[$i] ) ) {
			update_post_meta( $post_id, '_dearti_img_height', sanitize_text_field( wp_unslash( $heights[$i] ) ) );
		}

		// Handle Image Upload
		if ( ! empty( $_FILES['event_images']['name'][$i] ) ) {
			$file = array(
				'name'     => $_FILES['event_images']['name'][$i],
				'type'     => $_FILES['event_images']['type'][$i],
				'tmp_name' => $_FILES['event_images']['tmp_name'][$i],
				'error'    => $_FILES['event_images']['error'][$i],
				'size'     => $_FILES['event_images']['size'][$i]
			);

			// We need to use a hook to handle the custom $_FILES structure
			$_FILES['temp_upload'] = $file;
			$attachment_id = media_handle_upload( 'temp_upload', $post_id, array(), array( 'test_form' => false ) );
			unset( $_FILES['temp_upload'] );

			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}

		$created++;
	}

	if ( $created > 0 ) {
		wp_send_json_success( sprintf( '%d events created successfully! Refreshing...', $created ) );
	} else {
		wp_send_json_error( 'Failed to create events.' );
	}
}
add_action( 'wp_ajax_dearti_create_event', 'dearti_handle_ajax_create_event' );
add_action( 'wp_ajax_nopriv_dearti_create_event', 'dearti_handle_ajax_create_event' );

/**
 * AJAX handler for admin quick-add event.
 */
function dearti_handle_admin_quick_add_event() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );

	if ( ! current_user_can( 'publish_posts' ) ) {
		wp_send_json_error( 'Unauthorized.' );
	}

	$title       = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
	$date        = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';
	$color       = isset( $_POST['color'] ) ? sanitize_hex_color( wp_unslash( $_POST['color'] ) ) : '#4f46e5';
	$description = isset( $_POST['description'] ) ? wp_kses_post( wp_unslash( $_POST['description'] ) ) : '';
	$img_width   = isset( $_POST['img_width'] ) ? sanitize_text_field( wp_unslash( $_POST['img_width'] ) ) : '';
	$img_height  = isset( $_POST['img_height'] ) ? sanitize_text_field( wp_unslash( $_POST['img_height'] ) ) : '';
	$image_id    = isset( $_POST['image_id'] ) ? intval( $_POST['image_id'] ) : 0;

	if ( empty( $title ) ) {
		wp_send_json_error( 'Title is required.' );
	}

	$post_id = wp_insert_post( array(
		'post_title'   => $title,
		'post_content' => $description,
		'post_status'  => 'publish',
		'post_type'    => 'dearti_event'
	) );

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( 'Failed to create event.' );
	}

	update_post_meta( $post_id, '_dearti_title', $title );

	if ( ! empty( $date ) ) {
		$timestamp = strtotime( $date );
		if ( $timestamp ) {
			update_post_meta( $post_id, '_dearti_date', gmdate( 'Y', $timestamp ) );
			update_post_meta( $post_id, '_dearti_day_month', gmdate( 'M d', $timestamp ) );
		}
	}

	update_post_meta( $post_id, '_dearti_color', $color );

	if ( ! empty( $description ) ) {
		update_post_meta( $post_id, '_dearti_description', $description );
	}

	if ( ! empty( $img_width ) ) {
		update_post_meta( $post_id, '_dearti_img_width', $img_width );
	}

	if ( ! empty( $img_height ) ) {
		update_post_meta( $post_id, '_dearti_img_height', $img_height );
	}

	if ( $image_id > 0 ) {
		set_post_thumbnail( $post_id, $image_id );
	}

	wp_send_json_success( 'Event created successfully!' );
}
add_action( 'wp_ajax_dearti_admin_quick_add_event', 'dearti_handle_admin_quick_add_event' );

/**
 * AJAX handler for rendering preview HTML (for refreshing after quick-add).
 */
function dearti_render_preview_ajax() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );
	echo do_shortcode( '[deartimeline layout="flat"]' );
	wp_die();
}
add_action( 'wp_ajax_dearti_render_preview', 'dearti_render_preview_ajax' );

/**
 * AJAX handler for deleting an event from the live preview.
 */
function dearti_handle_admin_delete_event() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );

	if ( ! current_user_can( 'delete_posts' ) ) {
		wp_send_json_error( 'Unauthorized.' );
	}

	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

	if ( $post_id <= 0 || 'dearti_event' !== get_post_type( $post_id ) ) {
		wp_send_json_error( 'Invalid event.' );
	}

	if ( ! wp_delete_post( $post_id, true ) ) {
		wp_send_json_error( 'Failed to delete event.' );
	}

	wp_send_json_success( 'Event deleted successfully!' );
}
add_action( 'wp_ajax_dearti_admin_delete_event', 'dearti_handle_admin_delete_event' );

/**
 * AJAX handler for getting event data (for editing).
 */
function dearti_handle_admin_get_event() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'Unauthorized.' );
	}

	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

	if ( $post_id <= 0 || 'dearti_event' !== get_post_type( $post_id ) ) {
		wp_send_json_error( 'Invalid event.' );
	}

	$post = get_post( $post_id );
	$formatted_date = '';
	$year  = get_post_meta( $post_id, '_dearti_date', true );
	$day   = get_post_meta( $post_id, '_dearti_day_month', true );
	if ( $year && $day ) {
		$timestamp = strtotime( "$day $year" );
		if ( $timestamp ) {
			$formatted_date = gmdate( 'Y-m-d', $timestamp );
		}
	}

	wp_send_json_success( array(
		'post_id'     => $post_id,
		'title'       => get_post_meta( $post_id, '_dearti_title', true ),
		'date'        => $formatted_date,
		'color'       => get_post_meta( $post_id, '_dearti_color', true ) ?: '#4f46e5',
		'description' => get_post_meta( $post_id, '_dearti_description', true ),
		'image_id'    => get_post_thumbnail_id( $post_id ),
		'img_width'   => get_post_meta( $post_id, '_dearti_img_width', true ),
		'img_height'  => get_post_meta( $post_id, '_dearti_img_height', true ),
		'image_url'   => get_post_thumbnail_id( $post_id ) ? wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) : '',
	) );
}
add_action( 'wp_ajax_dearti_admin_get_event', 'dearti_handle_admin_get_event' );

/**
 * AJAX handler for updating an event from the live preview.
 */
function dearti_handle_admin_update_event() {
	check_ajax_referer( 'dearti_create_event_nonce', 'security' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'Unauthorized.' );
	}

	$post_id     = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	$title       = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
	$date        = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';
	$color       = isset( $_POST['color'] ) ? sanitize_hex_color( wp_unslash( $_POST['color'] ) ) : '#4f46e5';
	$description = isset( $_POST['description'] ) ? wp_kses_post( wp_unslash( $_POST['description'] ) ) : '';
	$img_width   = isset( $_POST['img_width'] ) ? sanitize_text_field( wp_unslash( $_POST['img_width'] ) ) : '';
	$img_height  = isset( $_POST['img_height'] ) ? sanitize_text_field( wp_unslash( $_POST['img_height'] ) ) : '';
	$image_id    = isset( $_POST['image_id'] ) ? intval( $_POST['image_id'] ) : 0;

	if ( $post_id <= 0 || 'dearti_event' !== get_post_type( $post_id ) ) {
		wp_send_json_error( 'Invalid event.' );
	}

	if ( empty( $title ) ) {
		wp_send_json_error( 'Title is required.' );
	}

	remove_action( 'save_post', 'dearti_save_meta_box_data' );

	wp_update_post( array(
		'ID'         => $post_id,
		'post_title' => $title,
		'post_content' => $description,
	) );

	add_action( 'save_post', 'dearti_save_meta_box_data' );

	update_post_meta( $post_id, '_dearti_title', $title );

	if ( ! empty( $date ) ) {
		$timestamp = strtotime( $date );
		if ( $timestamp ) {
			update_post_meta( $post_id, '_dearti_date', gmdate( 'Y', $timestamp ) );
			update_post_meta( $post_id, '_dearti_day_month', gmdate( 'M d', $timestamp ) );
		}
	}

	update_post_meta( $post_id, '_dearti_color', $color );

	if ( ! empty( $description ) ) {
		update_post_meta( $post_id, '_dearti_description', $description );
	}

	if ( ! empty( $img_width ) ) {
		update_post_meta( $post_id, '_dearti_img_width', $img_width );
	}

	if ( ! empty( $img_height ) ) {
		update_post_meta( $post_id, '_dearti_img_height', $img_height );
	}

	if ( $image_id > 0 ) {
		set_post_thumbnail( $post_id, $image_id );
	} else {
		delete_post_thumbnail( $post_id );
	}

	wp_send_json_success( 'Event updated successfully!' );
}
add_action( 'wp_ajax_dearti_admin_update_event', 'dearti_handle_admin_update_event' );


/**
 * Shortcode [timeline]
 */
function dearti_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'order'     => 'DESC',
			'layout'    => 'flat', // Default to Flat as per request, or 'classic'
			'animation' => 'fade-in', // Default animation
		),
		$atts,
		'deartimeline'
	);

	$layout    = in_array( $atts['layout'], array( 'classic', 'flat', 'horizontal' ) ) ? $atts['layout'] : 'flat';
	$animation = in_array( $atts['animation'], array( 'fade-in', 'flip-up', 'zoom' ) ) ? $atts['animation'] : 'fade-in';
	
	// Map to class names
	$anim_class = 'tsp-reveal-' . str_replace( '-in', '', str_replace( '-up', '', $animation ) );

	$can_delete = is_admin();

	$args = array(
		'post_type'      => 'dearti_event',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	// Manually Sort by Year (Numeric) then by Month/Day (Date Parse)
	if ( $query->have_posts() ) {
		usort( $query->posts, function( $a, $b ) {
			$year_a = get_post_meta( $a->ID, '_dearti_date', true );
			$year_b = get_post_meta( $b->ID, '_dearti_date', true );
			
			$day_a  = get_post_meta( $a->ID, '_dearti_day_month', true );
			$day_b  = get_post_meta( $b->ID, '_dearti_day_month', true );

			// Default if missing
			if ( ! $year_a ) $year_a = get_the_date( 'Y', $a );
			if ( ! $year_b ) $year_b = get_the_date( 'Y', $b );
			
			// Parse full date for comparison (e.g. "Jan 15 2026")
			$date_a = strtotime( "$day_a $year_a" );
			$date_b = strtotime( "$day_b $year_b" );

			if ( ! $date_a ) $date_a = strtotime( "$year_a-01-01" );
			if ( ! $date_b ) $date_b = strtotime( "$year_b-01-01" );

			// Descending Order (Newest First)
			return $date_b - $date_a; 
		});
	}

	ob_start();
	if ( $query->have_posts() ) {
		if ( $layout === 'horizontal' ) {
			echo '<div class="tsp-timeline-container tsp-layout-horizontal">';
			echo '<div class="deartimeline-horizontal-wrapper">';
			echo '<button class="dearti-h-nav dearti-h-prev" aria-label="' . esc_attr__( 'Previous', 'deartimeline' ) . '">&lsaquo;</button>';
			echo '<div class="deartimeline-track" tabindex="0" role="region" aria-label="' . esc_attr__( 'Timeline', 'deartimeline' ) . '">';

			while ( $query->have_posts() ) {
				$query->the_post();

				$h_title_meta = get_post_meta( get_the_ID(), '_dearti_title', true );
				$h_title      = ! empty( $h_title_meta ) ? $h_title_meta : get_the_title();

				$h_year_label = get_post_meta( get_the_ID(), '_dearti_date', true );
				if ( ! $h_year_label ) $h_year_label = get_the_date( 'Y' );

				$h_day_month  = get_post_meta( get_the_ID(), '_dearti_day_month', true );
				if ( ! $h_day_month ) $h_day_month = get_the_date( 'M j' );

				$h_desc_meta = get_post_meta( get_the_ID(), '_dearti_description', true );
				$h_color     = get_post_meta( get_the_ID(), '_dearti_color', true ) ?: '#4f46e5';

				$h_img_w = get_post_meta( get_the_ID(), '_dearti_img_width', true );
				$h_img_h = get_post_meta( get_the_ID(), '_dearti_img_height', true );

				$h_stamp = strtotime( "$h_day_month $h_year_label" );
				$h_month = $h_stamp ? gmdate( 'M', $h_stamp ) : '';
				$h_day   = $h_stamp ? gmdate( 'd', $h_stamp ) : '';
				$h_year  = $h_stamp ? gmdate( 'Y', $h_stamp ) : $h_year_label;
				?>
<div class="dearti-h-item" data-post-id="<?php echo get_the_ID(); ?>" style="--tsp-bg: <?php echo esc_attr( $h_color ); ?>;">
					<div class="dearti-h-card">
						<?php if ( $can_delete ) : ?>
							<div class="dearti-action-btns" style="position:absolute; top:8px; right:8px; z-index:20; display:none; gap:4px;">
								<button type="button" class="dearti-edit-btn" data-post-id="<?php echo get_the_ID(); ?>" title="<?php esc_attr_e( 'Edit Event', 'deartimeline' ); ?>" style="width:26px; height:26px; border-radius:50%; background:#4f46e5; color:#fff; border:none; font-size:13px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; line-height:1; box-shadow:0 2px 6px rgba(79,70,229,0.4);">&#9998;</button>
								<button type="button" class="dearti-delete-btn" data-post-id="<?php echo get_the_ID(); ?>" title="<?php esc_attr_e( 'Delete Event', 'deartimeline' ); ?>" style="width:26px; height:26px; border-radius:50%; background:#ef4444; color:#fff; border:none; font-size:15px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; line-height:1; box-shadow:0 2px 6px rgba(239,68,68,0.4);">&times;</button>
							</div>
						<?php endif; ?>
						<div class="dearti-h-body">
							<div class="dearti-h-head">
								<div class="dearti-h-cal">
									<span class="dearti-h-cal-month"><?php echo esc_html( $h_month ); ?></span>
									<span class="dearti-h-cal-day"><?php echo esc_html( $h_day ); ?></span>
									<span class="dearti-h-cal-year"><?php echo esc_html( $h_year ); ?></span>
								</div>
								<h3 class="dearti-h-title"><?php echo esc_html( $h_title ); ?></h3>
							</div>
							<?php if ( ! empty( $h_desc_meta ) ) : ?>
								<div class="dearti-h-desc"><?php echo wp_kses_post( wp_unslash( $h_desc_meta ) ); ?></div>
							<?php endif; ?>
						</div>
						<?php if ( has_post_thumbnail() ) :
							$h_img_style = '';
							if ( ! empty( $h_img_w ) ) $h_img_style .= 'width:' . esc_attr( $h_img_w ) . ';';
							if ( ! empty( $h_img_h ) ) $h_img_style .= 'height:' . esc_attr( $h_img_h ) . ';';
							if ( ! empty( $h_img_style ) ) $h_img_style .= 'object-fit:cover;';
						?>
						<div class="dearti-h-image">
							<?php the_post_thumbnail( 'large', array( 'style' => $h_img_style ) ); ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="dearti-h-connector"></div>
					<div class="dearti-h-dot" style="--dot-color: <?php echo esc_attr( $h_color ); ?>;"></div>
				</div>
				<?php
			}

			echo '</div>'; // .deartimeline-track
			echo '<div class="dearti-h-progress" aria-hidden="true"><span class="dearti-h-progress-bar"></span></div>';
			echo '<button class="dearti-h-nav dearti-h-next" aria-label="' . esc_attr__( 'Next', 'deartimeline' ) . '">&rsaquo;</button>';
			echo '</div>'; // .deartimeline-horizontal-wrapper
			echo '</div>'; // .tsp-timeline-container
		} else {
			echo '<div class="tsp-timeline-container tsp-layout-' . esc_attr( $layout ) . '">';
			echo '<div class="tsp-timeline">';

			$prev_year = null;
			$tsp_item_index = 0;

		while ( $query->have_posts() ) {
			$query->the_post();
			
			$title_meta = get_post_meta( get_the_ID(), '_dearti_title', true );
			$title      = ! empty( $title_meta ) ? $title_meta : get_the_title();
			
			$year_label = get_post_meta( get_the_ID(), '_dearti_date', true ); 
			if ( ! $year_label ) $year_label = get_the_date( 'Y' );
			
			$day_month  = get_post_meta( get_the_ID(), '_dearti_day_month', true );
			if ( ! $day_month ) $day_month = get_the_date( 'M j' );

			$bg_color   = get_post_meta( get_the_ID(), '_dearti_color', true );
			$color_var  = $bg_color ? '--tsp-bg: ' . $bg_color . ';' : '';

			$description_meta = get_post_meta( get_the_ID(), '_dearti_description', true );
			$img_w = get_post_meta( get_the_ID(), '_dearti_img_width', true );
			$img_h = get_post_meta( get_the_ID(), '_dearti_img_height', true );
			
			// Build Image Style
			$img_style = '';
			if ( ! empty( $img_w ) ) $img_style .= 'width:' . esc_attr($img_w) . ';';
			if ( ! empty( $img_h ) ) $img_style .= 'height:' . esc_attr($img_h) . ';';
			if ( ! empty( $img_style ) ) $img_style .= 'object-fit:cover;';

			$cat_slugs  = '';
			if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
				$slugs = wp_list_pluck( $post_terms, 'slug' );
				$cat_slugs = implode( ' ', $slugs );
			}

			// Year Separator
			if ( $year_label !== $prev_year ) {
				echo '<div class="tsp-year-separator" data-year="' . esc_attr( $year_label ) . '">';
				// Flat layout often uses simple square/box for year
				echo '<div class="tsp-sep-left"></div>';
				echo '<div class="tsp-sep-center"><span class="tsp-year-label">' . esc_html( $year_label ) . '</span></div>';
				echo '<div class="tsp-sep-right"></div>';
				echo '</div>';
				$prev_year = $year_label;
			}
			?>
			<?php $zigzag_class = ( $tsp_item_index % 2 === 0 ) ? '' : 'tsp-zigzag-even'; ?>
			<div class="tsp-item tsp-reveal <?php echo esc_attr( $anim_class ); ?> <?php echo esc_attr( $cat_slugs ); ?> <?php echo esc_attr( $zigzag_class ); ?>" data-post-id="<?php echo get_the_ID(); ?>" data-category="<?php echo esc_attr( $cat_slugs ); ?>" data-year-group="<?php echo esc_attr( $year_label ); ?>" <?php echo $color_var ? 'style="' . esc_attr( $color_var ) . '"' : ''; ?>>
			<?php if ( $can_delete ) : ?>
				<div class="dearti-action-btns" style="position:absolute; top:12px; right:12px; z-index:20; display:none; gap:4px;">
					<button type="button" class="dearti-edit-btn" data-post-id="<?php echo get_the_ID(); ?>" title="<?php esc_attr_e( 'Edit Event', 'deartimeline' ); ?>" style="width:28px; height:28px; border-radius:50%; background:#4f46e5; color:#fff; border:none; font-size:14px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; line-height:1; box-shadow:0 2px 6px rgba(79,70,229,0.4);">&#9998;</button>
					<button type="button" class="dearti-delete-btn" data-post-id="<?php echo get_the_ID(); ?>" title="<?php esc_attr_e( 'Delete Event', 'deartimeline' ); ?>" style="width:28px; height:28px; border-radius:50%; background:#ef4444; color:#fff; border:none; font-size:16px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; line-height:1; box-shadow:0 2px 6px rgba(239,68,68,0.4);">&times;</button>
				</div>
			<?php endif; ?>
			<?php $tsp_item_index++; ?>
				
				<?php if ( $layout === 'classic' ) : ?>
					<!-- CLASSIC: Date Left, Spine, Card Right -->
					<div class="tsp-date-col">
						<?php 
						$full_stamp = strtotime("$day_month $year_label");
						$m_label = $full_stamp ? gmdate('M', $full_stamp) : '';
						$d_label = $full_stamp ? gmdate('d', $full_stamp) : '';
						?>
						<span class="tsp-month-text" data-month="<?php echo esc_attr($m_label); ?>" data-day="<?php echo esc_attr($d_label); ?>"></span>
					</div>

					<div class="tsp-marker-col">
						<div class="tsp-marker-line"></div>
						<div class="tsp-marker-dot"></div>
					</div>

					<div class="tsp-content-col">
						<div class="tsp-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="tsp-image">
									<?php the_post_thumbnail( 'large', array( 'style' => $img_style ) ); ?>
								</div>
							<?php endif; ?>
							<div class="tsp-card-header">
								<h3 class="tsp-title"><?php echo esc_html( $title ); ?></h3>

							</div>
								<div class="tsp-text">
									<?php 
									if ( ! empty( $description_meta ) ) {
										echo wp_kses_post( wp_unslash( $description_meta ) );
									} else {
										the_content(); 
									}
									?>
								</div>
							</div>
						</div>
					</div>

				<?php else : ?>
					<!-- FLAT LAYOUT: Image Card | Spine | Text Card -->

					<!-- Col 1: Image -->
					<div class="tsp-col-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="tsp-card tsp-card-image">
								<?php the_post_thumbnail( 'large', array( 'style' => $img_style ) ); ?>
							</div>
						<?php endif; ?>
					</div>

					<!-- Col 2: Spine -->
					<div class="tsp-col-spine">
						<div class="tsp-dot"></div>
					</div>

					<!-- Col 3: Text Card -->
					<div class="tsp-col-text">
						<div class="tsp-card tsp-card-text">
							<?php 
							$full_stamp_flat = strtotime("$day_month $year_label");
							$m_flat = $full_stamp_flat ? gmdate('M', $full_stamp_flat) : '';
							$d_flat = $full_stamp_flat ? gmdate('d', $full_stamp_flat) : '';
							?>
							<div class="tsp-flat-date" data-month="<?php echo esc_attr($m_flat); ?>" data-day="<?php echo esc_attr($d_flat); ?>"></div>
							<h3 class="tsp-flat-title"><?php echo esc_html( $title ); ?></h3>
							<div class="tsp-flat-desc">
								<?php 
								if ( ! empty( $description_meta ) ) {
									echo wp_kses_post( wp_unslash( $description_meta ) );
								} else {
									the_content(); 
								}
								?>
							</div>
						</div>
					</div>

				<?php endif; ?>
				
			</div>
			<?php
		}
		echo '</div>'; // .tsp-timeline
		echo '</div>'; // .tsp-timeline-container
		}
		wp_reset_postdata();
	} else {
		echo '<p>No timeline events found.</p>';
	}

	return ob_get_clean();
}
add_shortcode( 'deartimeline', 'dearti_shortcode' );

/**
 * Admin Quick-Add Modal for Live Preview.
 */
function dearti_admin_quick_add_modal() {
	$screen = get_current_screen();
	if ( ! $screen || 'dearti_event' !== $screen->post_type || 'post' !== $screen->base ) {
		return;
	}
	?>
	<div id="dearti-quick-add-overlay" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,23,42,0.75);-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);justify-content:center;align-items:center;">
		<div class="dearti-modal-box">
			<div class="dearti-modal-header">
				<h3 id="dearti-quick-add-title"><?php esc_html_e( 'Add New Timeline Item', 'deartimeline' ); ?></h3>
				<button type="button" id="dearti-quick-add-close">&times;</button>
			</div>
			<form id="dearti-quick-add-form">
				<input type="hidden" name="post_id" id="dearti-quick-post-id" value="">
				<div class="dearti-modal-body">
					<div class="dearti-field">
						<label><?php esc_html_e( 'Event Title:', 'deartimeline' ); ?></label>
						<input type="text" name="title" placeholder="<?php esc_attr_e( 'If empty, main Title is used.', 'deartimeline' ); ?>">
					</div>
					<div class="dearti-field">
						<label><?php esc_html_e( 'Event Date:', 'deartimeline' ); ?></label>
						<input type="text" name="date" id="dearti-quick-date" placeholder="mm/dd/yyyy" autocomplete="off">
						<small><?php esc_html_e( 'Select the event date for the calendar box display.', 'deartimeline' ); ?></small>
					</div>
					<div class="dearti-field">
						<label><?php esc_html_e( 'Background Color:', 'deartimeline' ); ?></label>
						<input type="color" name="color" value="#4f46e5">
					</div>
					<div class="dearti-field">
						<label><?php esc_html_e( 'Event Description:', 'deartimeline' ); ?></label>
						<textarea name="description" rows="4" placeholder="<?php esc_attr_e( 'This description will be shown on the timeline. If left empty, the main content will be used.', 'deartimeline' ); ?>"></textarea>
					</div>
					<div class="dearti-field">
						<label><?php esc_html_e( 'Featured Image:', 'deartimeline' ); ?></label>
						<div id="dearti-quick-img-preview">
							<span class="dashicons dashicons-format-image"></span>
						</div>
						<input type="hidden" name="image_id" id="dearti-quick-image-id" value="">
						<div class="dearti-img-actions">
							<button type="button" id="dearti-quick-select-image" class="button"><?php esc_html_e( 'Set Featured Image', 'deartimeline' ); ?></button>
							<button type="button" id="dearti-quick-remove-image" class="button" style="display:none;"><?php esc_html_e( 'Remove', 'deartimeline' ); ?></button>
						</div>
					</div>
					<div class="dearti-field-row">
						<div class="dearti-field">
							<label><?php esc_html_e( 'Image Width:', 'deartimeline' ); ?></label>
							<input type="text" name="img_width" placeholder="e.g. 100% or 300px">
						</div>
						<div class="dearti-field">
							<label><?php esc_html_e( 'Image Height:', 'deartimeline' ); ?></label>
							<input type="text" name="img_height" placeholder="e.g. auto or 200px">
						</div>
					</div>
				</div>
				<div class="dearti-modal-footer">
					<button type="button" id="dearti-quick-cancel" class="button"><?php esc_html_e( 'Cancel', 'deartimeline' ); ?></button>
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Event', 'deartimeline' ); ?></button>
				</div>
				<div id="dearti-quick-status"></div>
			</form>
		</div>
	</div>
	<?php
}
add_action( 'admin_footer', 'dearti_admin_quick_add_modal' );

/**
 * Output Modal Markup in Footer
 * This prevents duplicate IDs if multiple shortcodes are used and keeps HTML clean.
 */
function dearti_output_footer_modals() {
	?>
	<!-- Event Detail Modal -->
	<div id="tsp-modal-overlay" class="tsp-modal-overlay">
		<div class="tsp-modal-box">
			<span class="tsp-close-btn">&times;</span>
			<div class="tsp-modal-body"></div>
		</div>
	</div>

	<?php if ( current_user_can( 'publish_posts' ) ) : ?>
		<!-- Add New Event Modal -->
		<div id="tsp-add-modal-overlay" class="tsp-modal-overlay">
			<div class="tsp-modal-box">
				<span class="tsp-close-btn">&times;</span>
				<div class="tsp-modal-body">
					<h3 style="margin-top: 0; margin-bottom: 25px; color: #1e293b; font-size: 1.5rem;">Add New Event</h3>
					<form id="tsp-add-event-form" class="tsp-form" enctype="multipart/form-data">
						<div id="tsp-repeater-rows">
							<div class="tsp-repeater-row" style="position:relative; padding:20px; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:20px; background:#f8fafc;">
								<div class="tsp-form-group">
									<label>Event Title</label>
									<input type="text" name="event_titles[]" placeholder="Enter title..." required>
								</div>
								<div class="tsp-form-group">
									<label>Event Date</label>
									<input type="date" name="event_dates[]" class="dearti-datepicker" required>
								</div>
								<div class="tsp-form-group">
									<label>Featured Image</label>
									<input type="file" name="event_images[]" accept="image/*">
								</div>
								<div style="display: flex; gap: 15px;">
									<div class="tsp-form-group" style="flex: 1;">
										<label>Img Width (e.g. 100% or 200px)</label>
										<input type="text" name="event_image_widths[]" placeholder="Default: 100%">
									</div>
									<div class="tsp-form-group" style="flex: 1;">
										<label>Img Height (e.g. auto or 150px)</label>
										<input type="text" name="event_image_heights[]" placeholder="Default: auto">
									</div>
								</div>
								<div class="tsp-form-group">
									<label>Description</label>
									<textarea name="event_contents[]" rows="3" placeholder="Enter event details..." required></textarea>
								</div>
							</div>
						</div>
						
						<div style="text-align:center; margin-bottom:20px;">
							<button type="button" id="tsp-add-row-btn" class="tsp-secondary-btn" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; padding:10px 20px; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.9rem;">
								+ Add Another Event
							</button>
						</div>

						<button type="submit" class="tsp-submit-btn">Publish Events</button>
						<div id="tsp-form-status" style="margin-top:15px; font-weight:600; text-align:center;"></div>
					</form>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
}
add_action( 'wp_footer', 'dearti_output_footer_modals' );

