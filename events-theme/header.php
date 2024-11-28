<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo get_site_icon_url(); ?>" sizes="32x32" />
    <title>
    <?php 
        if ( is_front_page() || is_home() ) {
            // On the homepage, show only the site name
            bloginfo( 'name' );
        } else {
            // On other pages, show site name | page title
            wp_title( '|', true, 'right' ); 
            bloginfo( 'name' );
        }
        ?>
    </title>

    <!-- Bootstrap CSS -->    
  	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  	<!-- FontAwesome CSS -->    
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Anaheim - Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anaheim:wght@400..800&display=swap" rel="stylesheet">

    <?php wp_head(); ?> <!-- Hook for additional styles, scripts, etc. --> 
</head>
<body <?php body_class(); ?>>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-sm fixed-top">
      <div class="container">
      		<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			  	<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<?php bloginfo( 'name' ); ?>
				<?php endif; ?>
			</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarContent">
          <?php 
            wp_nav_menu( array( 
                'theme_location' => 'main_menu', 
                'container' => false, 
                'menu_class' => 'navbar-nav', 
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 2,
            ) ); 
            ?>
        </div>
      </div>
    </nav>