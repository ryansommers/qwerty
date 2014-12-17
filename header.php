<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package qwerty
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

  <!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
  <![endif]-->
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'qwerty' ); ?></a>

		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="superfluous"></div>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
				<form class="main-navigation-select" action="<?php echo esc_url( get_template_directory_uri() ); ?>/nav.php" method="get" onChange="window.location.replace(document.getElementById('page-nav').value)">
					<div class="custom-select">
						<?php 
						if (has_nav_menu('primary')) {
							wp_nav_menu(array(
								'theme_location' => 'primary',
								'items_wrap' => '<select id="page-nav" name="page-nav">%3$s</select>', 
								'walker' => new qwerty_select_menu_walker() 
								)
							); 
						} else {
							wp_nav_menu(array(
								'theme_location' => '',
								'items_wrap' => '<select id="page-nav" name="page-nav">%3$s</select>', 
								'walker' => new qwerty_select_menu_walker() 
								)
							); 
						}
						?>
					</div>
					<noscript><input class="mob-sub" type="submit" value="Submit" /></noscript>
				</form>
			</nav><!-- #site-navigation -->

		</header><!-- #masthead -->

		<div id="content" class="site-content">
