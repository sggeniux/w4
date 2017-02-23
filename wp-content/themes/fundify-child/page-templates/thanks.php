<?php
/**
 * Template Name: Thanks
 *
 * @package Fundify
 * @since Fundify 1.0
 */




get_header();
?>

	<div id="content">
		<div class="container">

			<div class="thanks">
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