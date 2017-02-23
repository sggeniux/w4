<?php
/**
 * Template Name: Full Width
 *
 * @package Fundify
 * @since Fundify 1.0
 */

get_header(); ?>

	<div class="title">
		<div class="container-fluid">
			<?php while ( have_posts() ) : the_post(); ?>
			<?php
					$post = get_post();
					echo get_the_post_thumbnail($post->ID,'full');
			 ?>
			<h1><?php the_title() ;?></h1>
			<?php endwhile; ?>
		</div>
		<!-- / container -->
	</div>
	<div id="content">
		<div class="container-fluid">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'single' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>
			<?php endwhile; ?>
		</div>
		<!-- / container -->

		<div class="container">
			<?php $projects = have_projects(); ?>
				<?php
				$i=0;
				while ( $projects->have_posts() ) :
					$projects->the_post();
						if (idcf_is_crowdfunding()) {
							if($i === 0){
								$class = 'first';
								//apply_filters( 'post_class', $class,$class,$projects->the_post()->ID);
							}else{
								$class = 'other';
								//apply_filters( 'post_class', $class,$class,$projects->the_post()->ID);
							}
							echo '<div class="'.$class.'">';
							get_template_part('content', 'project');
							echo '</div>';
						}
						else {
							get_template_part( 'content', 'campaign' );
						}
				$i++;
				endwhile;
				?>
		</div>

	</div>
	<!-- / content -->

<?php get_footer(); ?>