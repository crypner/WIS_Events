<?php
// ----- Theme setup
function events_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo',array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
	add_theme_support('site-icon');
	add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'events_theme_setup');

// ----- Enqueue styles and scripts
function events_theme_scripts() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());
	wp_enqueue_script('events_theme-script', get_template_directory_uri() . '/js/customizer.js', array('jquery'), null, true);
	wp_enqueue_script('events_theme-script', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'events_theme_scripts');  

// ----- Register Main Menu
function register_my_menus() {
    register_nav_menus(
        array(
            'main_menu' => __( 'Main Menu' )
        )
    );
}
add_action( 'after_setup_theme', 'register_my_menus' );

// ----- Add 'nav-item' class to <li> elements for BS5 Nav
function add_nav_item_class($classes, $item, $args, $depth) {
    // Apply only to the main menu
    if ($args->theme_location == 'main_menu') {
        $classes[] = 'nav-item'; // Add the 'nav-item' class
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_nav_item_class', 10, 4);

// ----- Add 'nav-link' class to <a> elements for BS5 Nav
function add_nav_link_class($atts, $item, $args, $depth) {
    // Apply only to the main menu
    if ($args->theme_location == 'main_menu') {
        $atts['class'] = 'nav-link'; // Add the 'nav-link' class
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_nav_link_class', 10, 4);

// ----- Register "Event Details" Guttenberg Block
function events_theme_register_blocks() {
    // Path to the built JavaScript file
    $block_js = get_template_directory() . '/build/index.js';

    // Path to the generated asset file
    $block_assets = include(get_template_directory() . '/build/index.asset.php');

    // Register the block JavaScript file
    wp_register_script(
        'event-details-block',
        get_template_directory_uri() . '/build/index.js',
        $block_assets['dependencies'],
        $block_assets['version']
    );

    // Register the block frontend and editor styles
    wp_register_style(
        'event-details-block-style',
        get_template_directory_uri() . '/build/style-index.css',
        array(),
        filemtime(get_template_directory() . '/build/style-index.css')
    );

    // Register the block
    register_block_type('events-theme/event-details-block', array(
        'editor_script' => 'event-details-block',
        'style'         => 'event-details-block-style', // Frontend styles
        'editor_style'  => 'event-details-block-style', // Editor styles
    ));
}
add_action('init', 'events_theme_register_blocks');

// ----- Theme Settings Page
function theme_settings_menu() {
    add_menu_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'theme-settings',
        'theme_settings_page',
        'dashicons-admin-settings',
        100
    );
}
add_action('admin_menu', 'theme_settings_menu');

function theme_settings_page() {
    // Get saved options
    $import_events = get_option('import_external_events', false);
    $api_url = get_option('api_url', '');
    ?>
    <div class="wrap">
        <h1>Theme Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Add settings fields
            settings_fields('theme_settings_group');
            do_settings_sections('theme-settings');
            ?>
            <h2>Import External Events</h2>
            <label for="import_external_events">Enable Import External Events:</label>
            <input type="checkbox" id="import_external_events" name="import_external_events" <?php if ($import_events) echo 'checked="checked"'; ?> />
            
            <div id="api_url_section" style="margin-top: 20px; <?php if (!$import_events) echo 'display: none;'; ?>">
                <label for="api_url">API URL:</label>
                <input type="url" id="api_url" name="api_url" value="<?php echo esc_attr($api_url); ?>" style="width: 100%;" />
                <div style="display: flex;align-items: flex-start;flex-direction: column-reverse;">
					<button type="button" id="test_api_url" class="button button-primary" style="margin-top: 10px;">Test API</button>
					<span id="api_test_result" style="color: green; display: none;">Pass: Successfully connected to the API.</span>
					<span id="api_test_error" style="color: red; display: none;">Error: Invalid API URL or data.</span>
				</div>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <script>
        // Show the API URL section when the toggle is on
        document.getElementById('import_external_events').addEventListener('change', function() {
            var apiSection = document.getElementById('api_url_section');
            if (this.checked) {
                apiSection.style.display = 'block';
            } else {
                apiSection.style.display = 'none';
            }
        });

        // Test API URL functionality
        document.getElementById('test_api_url').addEventListener('click', function() {
            var apiUrl = document.getElementById('api_url').value;
            var result = document.getElementById('api_test_result');
            var error = document.getElementById('api_test_error');

            if (!apiUrl) {
                alert('Please enter a valid API URL.');
                return;
            }

            // Reset test results
            result.style.display = 'none';
            error.style.display = 'none';

            // Make the fetch request
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // Check if the response is an array
                    if (Array.isArray(data)) {
                        result.style.display = 'inline';
                        error.style.display = 'none';
                    } else {
                        error.style.display = 'inline';
                    }
                })
                .catch(() => {
                    error.style.display = 'inline';
                    result.style.display = 'none';
                });
        });
    </script>
    <?php
}
// ----- Initialize Theme Settings storage values
function theme_settings_init() {
    register_setting('theme_settings_group', 'import_external_events');
    register_setting('theme_settings_group', 'api_url', 'sanitize_text_field');
}
add_action('admin_init', 'theme_settings_init');

// ----- Handling External Events 
// Add rewrite rule for external events
function custom_external_event_rewrite() {
    add_rewrite_rule(
        '^external-event/([^/]+)/?$',
        'index.php?external_event=$matches[1]',
        'top'
    );
}
add_action('init', 'custom_external_event_rewrite');

// ----- Register a query variable for external events
function custom_external_event_query_var($vars) {
    $vars[] = 'external_event';
    return $vars;
}
add_filter('query_vars', 'custom_external_event_query_var');

function custom_external_event_template_redirect() {
    $external_event_id = get_query_var('external_event');
    if ($external_event_id) {
        // Fetch external event details based on the ID
        $api_url = get_option('api_url', '');
        if ($api_url) {
            $response = wp_remote_get($api_url);
            if (is_array($response) && !is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $external_events = json_decode($body, true);

                // Find the event with the matching ID
                foreach ($external_events as $index => $event) {
                    if ('external-' . $index === $external_event_id) {
                        // Inject the event data into the template context
                        set_query_var('external_event_data', $event);

                        // Force WordPress to use the single-event.php template
                        include locate_template('single-event.php');
                        exit;
                    }
                }
            }
        }

        // If no event found, show 404
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        include get_404_template();
        exit;
    }
}
add_action('template_redirect', 'custom_external_event_template_redirect');

// ----- Function to avoid showing an external event as a blog post
function exclude_external_events_from_blog($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_home()) {
        $query->set('meta_query', array(
            array(
                'key' => '_is_external_event',
                'compare' => 'NOT EXISTS',
            ),
        ));
    }
}
add_action('pre_get_posts', 'exclude_external_events_from_blog');

// ----- Ensures _.contains is used after Underscore.js is loaded
function fix_underscore_contains() {
    wp_add_inline_script(
        'underscore', // Ensure it loads after Underscore.js
        "if (typeof _.contains === 'undefined') {
            _.contains = function(collection, value) {
                return _.includes(collection, value);
            };
        }"
    );
}
add_action('admin_enqueue_scripts', 'fix_underscore_contains');

// ----- Set redirect for Blog posts
add_action('template_redirect', function () {
    if (is_page('blog')) {
        include(get_template_directory() . '/index.php');
        exit; // Prevent further execution
    }
});

add_action('after_switch_theme', 'setup_home_and_blog_pages');

// ----- Create Home and Blog Pages and set as static pages on theme install
function setup_home_and_blog_pages() {
    // Check if pages already exist to avoid duplication
    $home_page = get_page_by_title('Home');
    $blog_page = get_page_by_title('Blog');

    // Create "Home" page if it doesn't exist
    if (!$home_page) {
        $home_page_id = wp_insert_post(array(
            'post_title'     => 'Home',
            'post_content'   => 'Welcome to our homepage!', // Default content (optional)
            'post_status'    => 'publish',
            'post_type'      => 'page',
        ));
    } else {
        $home_page_id = $home_page->ID;
    }

    // Create "Blog" page if it doesn't exist
    if (!$blog_page) {
        $blog_page_id = wp_insert_post(array(
            'post_title'     => 'Blog',
            'post_content'   => '', // Leave empty for the blog index
            'post_status'    => 'publish',
            'post_type'      => 'page',
        ));
    } else {
        $blog_page_id = $blog_page->ID;
    }

    // Set the "Home" and "Blog" pages in Settings > Reading
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page_id); // Set "Home" page
    update_option('page_for_posts', $blog_page_id); // Set "Blog" page
}