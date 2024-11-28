<?php get_header(); ?>

<main class="container">
	<div class="row d-flex justify-content-center">
		<div class="col-md-6 mb-4">
			<a href="/" class="back-link"><i style="font-size:24px" class="fa">&#xf177;</i> Back</a>
    <?php
    // Check if external event data is passed
    $external_event = get_query_var('external_event_data', false);

    if ($external_event) {
        // Render external event details
        ?>
		<?php if (!empty($external_event['image'])) { ?>
            <div class="mb-4">
                <img src="<?php echo esc_url($external_event['image']); ?>" alt="<?php echo esc_attr($external_event['title']); ?>" class="img-fluid rounded">
            </div>
        <?php } ?>
        <h1 class="mb-0"><?php echo esc_html($external_event['title']); ?></h1>
        <h4 style="color: #888;"><?php echo esc_html($external_event['location']); ?></h4>
        <h5 class="mb-4"><?php echo date('j F Y @ H:i', strtotime($external_event['start_date_time'])); ?></h5>
        
        <div><?php echo esc_html($external_event['description']); ?></div>
			<script type="application/ld+json">
				{
				  "@context": "https://schema.org",
				  "@type": "Event",
				  "name": "<?php echo esc_html($external_event['title']); ?>",
				  "startDate": "<?php echo $external_event['start_date_time']; ?>",
				  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
				  "eventStatus": "https://schema.org/EventScheduled",
				  "location": {
					"@type": "Place",
					"name": "<?php echo esc_html($external_event['location']); ?>"
				  },
				  <?php if (!empty($external_event['image'])) { ?>
				  "image": [
					"<?php echo esc_url($external_event['image']); ?>"
				   ],
				   <?php } ?>
				  "description": "<?php echo esc_html($external_event['description']); ?>"      
				}
			</script>
        <?php
    } elseif (have_posts()) {
        // Render internal event details
        while (have_posts()) {
            the_post();
            $location = get_post_meta(get_the_ID(), '_event_location', true);
            $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
            ?>
			<?php if (has_post_thumbnail()) { ?>
                <div class="mb-4">
                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>" class="img-fluid rounded">
                </div>
            <?php } ?>
            <h1 class="mb-0"><?php the_title(); ?></h1>
            <h4 style="color: #888;"><?php echo esc_html($location); ?></h4>
            <h5 class="mb-4"><?php echo date('j F Y @ H:i', strtotime($start_date)); ?></h5>            
            <div><?php the_content(); ?></div>
			<script type="application/ld+json">
				{
				  "@context": "https://schema.org",
				  "@type": "Event",
				  "name": "<?php the_title(); ?>",
				  "startDate": "<?php echo $start_date; ?>",
				  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
				  "eventStatus": "https://schema.org/EventScheduled",
				  "location": {
					"@type": "Place",
					"name": "<?php echo esc_html($location); ?>"
				  },
				  <?php if (has_post_thumbnail()) { ?>
				  "image": [
					"<?php the_post_thumbnail_url('large'); ?>"
				   ],
				   <?php } ?>
				  "description": "<?php echo esc_js(wp_strip_all_tags(get_the_content(), true)); ?>"      
				}
			</script>
            <?php
        }
    }
    ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
