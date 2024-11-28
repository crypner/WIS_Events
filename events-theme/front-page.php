<?php get_header(); ?>

<main class="container">    

    <?php
    // Fetch Internal Events
    $internal_events = [];
    $query = new WP_Query(array(
        'post_type' => 'event',
        'meta_key' => '_event_start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_event_start_date',
                'value' => current_time('Y-m-d H:i'),
                'compare' => '>=',
                'type' => 'DATETIME',
            ),
        ),
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $internal_events[] = array(
                'type' => 'internal',
                'title' => get_the_title(),
                'location' => get_post_meta(get_the_ID(), '_event_location', true),
                'start_date' => get_post_meta(get_the_ID(), '_event_start_date', true),
                'image' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '',
                'url' => get_the_permalink(),
            );
        }
        wp_reset_postdata();
    }

    // Fetch External Events
    $external_events = [];
    if (get_option('import_external_events', false)) {
        $api_url = get_option('api_url', '');

        if ($api_url) {
            $response = wp_remote_get($api_url);
            if (is_array($response) && !is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $api_events = json_decode($body, true);

                if (!empty($api_events) && is_array($api_events)) {
                    foreach ($api_events as $index => $event) {
                        $external_events[] = array(
                            'type' => 'external',
                            'title' => $event['title'],
                            'location' => $event['location'],
                            'start_date' => $event['start_date_time'],
                            'image' => $event['image'],
                            'url' => site_url('?external_event=external-' . $index),
                        );
                    }
                }
            }
        }
    }

    // Combine Internal and External Events
    $all_events = array_merge($internal_events, $external_events);

    // Sort All Events by Start Date
    usort($all_events, function ($a, $b) {
        return strtotime($a['start_date']) - strtotime($b['start_date']);
    });

    // Get the Next Upcoming Event
    $next_event = !empty($all_events) ? $all_events[0] : null;
    ?>

    <!-- Jumbotron for Next Upcoming Event -->
    <?php if ($next_event) : ?>
        <div class="mt-4 p-5 bg-secondary rounded jumbotron" style="background-image:url('<?php echo esc_url($next_event['image']); ?>'); background-size: cover; background-position: center;">
            <div class="jumbotronBanner rounded">
                <h1><?php echo esc_html($next_event['title']); ?></h1>
                <p><?php echo esc_html($next_event['location']); ?></p>
                <p><?php echo date('j F Y @ H:i', strtotime($next_event['start_date'])); ?></p>
                <a href="<?php echo esc_url($next_event['url']); ?>" class="btn btn-primary">Find out more ></a>
            </div>
        </div>
		
		<h1 class="mb-4">Upcoming Events</h1>
    

		<!-- Display Internal Events -->
		<div class="row">
			<?php foreach ($internal_events as $event) : ?>
				<div class="col-md-4 mb-4">
					<a href="<?php echo esc_url($event['url']); ?>" class="card-Link">
						<div class="card h-100">
							<?php if (!empty($event['image'])) : ?>
								<img src="<?php echo esc_url($event['image']); ?>" class="card-img-top" alt="<?php echo esc_attr($event['title']); ?>">
							<?php endif; ?>
							<div class="card-body">
								<h5 class="card-title"><?php echo esc_html($event['title']); ?></h5>
								<p class="card-text"><?php echo esc_html($event['location']); ?></p>
								<p class="card-text dateTime"><?php echo date('j F Y @ H:i', strtotime($event['start_date'])); ?></p>

							</div>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<?php 
			if (!empty($api_events) && is_array($api_events)) {
		?>
			<!-- Display External Events -->
			<hr />
			<h2 class="mb-4">External Events</h2>
			<div class="row" id="external-events">
				<?php foreach ($external_events as $event) : ?>
					<div class="col-md-4 mb-4">
						<a href="<?php echo esc_url($event['url']); ?>" class="card-Link">
							<div class="card h-100">
								<?php if (!empty($event['image'])) : ?>
									<img src="<?php echo esc_url($event['image']); ?>" class="card-img-top" alt="<?php echo esc_attr($event['title']); ?>">
								<?php endif; ?>
								<div class="card-body">
									<h5 class="card-title"><?php echo esc_html($event['title']); ?></h5>
									<p class="card-text"><?php echo esc_html($event['location']); ?></p>
									<p class="card-text dateTime"><strong>Date:</strong> <?php echo date('F j, Y, g:i A', strtotime($event['start_date'])); ?></p>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php 
			}
		?>
	<?php else : ?>
        <p class="text-center">No upcoming events at the moment.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

