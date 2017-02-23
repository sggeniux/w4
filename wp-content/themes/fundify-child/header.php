<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Fundify
 * @since Fundify 1.0
 */

$header_hide = '';
$pagestate = '';
if( !empty($_GET['ss']) && ($_GET['ss'] === '1') ){
	$header_hide = 'hide';
	$pagestate = 'container-left-push';
}
?><!DOCTYPE html>
<!--[if IE 7]> <html class="ie7 oldie" lang="en" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]> <html class="ie8 oldie" lang="en" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]> <html class="ie9" lang="en" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<header id="header" class="site-header <?php echo $header_hide; ?>" role="banner">
		<div class="sg_head_container">
			<a href="#" class="menu-toggle"><i class="icon-menu"></i></a>

			<div class="menu_bouton">
			<a class="menu_control" href="#">
				<span><?php _e('Menu') ?></span>
				<span class="chevron_menu">
					<img class="site_logo" src="/wp-content/themes/fundify-child/img/chevron.png" />
				</span>
			</a>
			</div>

			<nav id="menu">
				<?php //wp_nav_menu( array( 'theme_location' => 'primary-left', 'container' => false ) ); ?>
				<?php //wp_nav_menu( array( 'theme_location' => 'primary-right', 'container' => false, 'menu_class' => 'right' ) ); ?>

				<div class="menu_principal">
					<?php // wp_nav_menu( array( 'theme_location' => 'primary-left', 'container' => false, 'menu_class' => 'right' ) ); ?>
				</div>


				
			</nav>
			<!-- / navigation -->


			<div class="breadcrumb">
				<?php single_cat_title() ?>
			</div>



			<hgroup>
				<?php /* ?>
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php $header_image = get_header_image();
						if ( ! empty( $header_image ) ) : ?>
							<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
						<?php else : ?>
							<span><?php bloginfo( 'name' ); ?></span>
						<?php endif; ?>
					</a>
				</h1>
				<?php */ ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img class="site_logo" src="<?php echo get_stylesheet_directory_uri() ?>/img/w4.jpg" />
				</a>
			</hgroup>

			<div class="lang_chooser">
				<?php wp_nav_menu(67) //echo lang_chooser(); ?>
			</div>
			<div class="curency_chooser">
				<?php echo curency_chooser(); ?>
			</div>		
		</div>
		<!-- / container -->
	</header>
	<!-- / header -->
	<div class="search_sidebar <?php echo is_active_search(); ?>">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('search_sidebar') ) : 
	endif; ?>
		<div class="principal_menu">
			<?php wp_nav_menu( array('menu' => 'menu-principal' )); ?>
		</div>
		<div class="members_menu hide">
			<?php wp_nav_menu( array('menu' => '11' )); ?>
		</div>
		<span class="tirette">
			<img alt="W4" src="<?php echo get_stylesheet_directory_uri() ?>/img/nav/close.svg">
		</span>
	</div>

	
<div id="page" class="hfeed site <?php echo $pagestate; ?>">
	<?php do_action( 'before' ); ?>


	<?php //get_sidebar(); ?>


	<?php if( !empty($GLOBALS['alerts']['message']) ): ?>
	<div class="alertbox <?php echo $GLOBALS['alerts']['type'] ?>">
		<p><?php echo $GLOBALS['alerts']['message']; ?></p>
	</div>
	<?php endif; ?>