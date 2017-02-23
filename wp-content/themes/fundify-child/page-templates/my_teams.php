<?php
/**
 * Template Name: My teams
 *
 * @package Fundify
 * @since Fundify 1.0
 */

get_header();
?>

	<div id="content">
		<div class="container">

		<h1 class="myteams_page_title"><?php _e('Mes équipes') ?></h1>
			<div class="myteams_content">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'single' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>
			<?php endwhile; ?>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>