<?php
get_header(); ?>
 
<main class="container">
    <div class="row d-flex justify-content-center">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <div class="col-md-6 mb-4">
                    <a href="/blog" class="back-link"><i style="font-size:24px" class="fa">&#xf177;</i> Back</a>
                        <?php if (has_post_thumbnail()) { ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" class="rounded" alt="<?php the_title(); ?>" width="100%">
                        <?php } ?>
                        <h1 class="postTitle"><?php the_title(); ?></h1>
                        <p><?php the_content(); ?></p>
                </div>
                <?php
            endwhile;

        else :
            echo '<p>No posts found.</p>';
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>