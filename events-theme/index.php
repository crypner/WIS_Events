<?php
get_header(); ?>

<main class="container">
  <h1 class="mb-4">Blog</h1>
  
  <div class="row">
    <?php
    // WP Query to fetch posts excluding the 'event' post type
    $query = new WP_Query(array(
        'post_type' => 'post', // Only regular posts (excluding custom post types like 'event')
        'posts_per_page' => 10, // You can change this number to control how many posts to show per page
        'post_status' => 'publish', // Only published posts
    ));

    if ($query->have_posts()) :
      while ($query->have_posts()) : $query->the_post(); ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if (has_post_thumbnail()) : ?>
			  <div class="postThumbnail" style="background-image: url('<?php the_post_thumbnail_url('medium'); ?>')" title="<?php the_title(); ?>"></div>
			<?php else : ?>  
			  <div class="postThumbnail" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/no-image.png')" title="<?php the_title(); ?>"></div>
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?php the_title(); ?></h5>
              <p class="card-text"><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
            </div>
          </div>
        </div>
      <?php endwhile;

      // Pagination
      the_posts_pagination();

    else :
      echo '<p>No posts found.</p>';
    endif;

    wp_reset_postdata();
    ?>
  </div>
</main>

<?php get_footer(); ?>