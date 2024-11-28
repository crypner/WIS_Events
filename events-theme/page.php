<?php
get_header();
?>
<main class="container">
	<div class="row d-flex justify-content-center">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				?>
				<div class="col-md-6 mb-4">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				</div>
				<?php
			endwhile;
		endif;
		?>
	</div>
</main>
<?php
get_footer();
?>