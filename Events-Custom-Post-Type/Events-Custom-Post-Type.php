<?php
/**
 * Plugin Name: Events Custom Post Type
 * Description: A plugin to create a custom post type for Events.
 * Version: 1.0
 * Author: Andre DeCarlo
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Register the 'Events' Custom Post Type
function events_custom_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Event', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Events', 'textdomain'),
        'name_admin_bar'        => __('Event', 'textdomain'),
        'add_new'               => __('Add New Event', 'textdomain'),
        'add_new_item'          => __('Add New Event', 'textdomain'),
        'new_item'              => __('New Event', 'textdomain'),
        'edit_item'             => __('Edit Event', 'textdomain'),
        'view_item'             => __('View Event', 'textdomain'),
        'all_items'             => __('All Events', 'textdomain'),
        'search_items'          => __('Search Events', 'textdomain'),
        'not_found'             => __('No events found', 'textdomain'),
        'not_found_in_trash'    => __('No events found in Trash', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'show_in_rest'          => true,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'rewrite'               => array('slug' => 'events'),
        'menu_icon'             => 'dashicons-calendar',
    );

    register_post_type('event', $args);
}

// Hook the function into WordPress
add_action('init', 'events_custom_post_type');

// Add Custom Meta Fields for Events
function event_meta_boxes() {
    add_meta_box(
        'event_meta_box',
        __('Event Details', 'textdomain'),
        'render_event_meta_box',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'event_meta_boxes');

function render_event_meta_box($post) {
    // Nonce for security
    wp_nonce_field('save_event_meta', 'event_meta_nonce');

    // Retrieve current values
    $location = get_post_meta($post->ID, '_event_location', true);
    $start_date = get_post_meta($post->ID, '_event_start_date', true);

    echo '<p>';
    echo '<label for="event_location">' . __('Location:', 'textdomain') . '</label>';
    echo '<input type="text" id="event_location" name="event_location" value="' . esc_attr($location) . '" class="widefat" />';
    echo '</p>';

    echo '<p>';
    echo '<label for="event_start_date">' . __('Start Date and Time:', 'textdomain') . '</label>';
    echo '<input type="datetime-local" id="event_start_date" name="event_start_date" value="' . esc_attr($start_date) . '" class="widefat" />';
    echo '</p>';
}

// Save the Meta Box Data
function save_event_meta_data($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['event_meta_nonce']) || !wp_verify_nonce($_POST['event_meta_nonce'], 'save_event_meta')) {
        return;
    }

    // Avoid autosave overwrites
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save location
    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
    }

    // Save start date
    if (isset($_POST['event_start_date'])) {
        update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['event_start_date']));
    }
}
add_action('save_post', 'save_event_meta_data');