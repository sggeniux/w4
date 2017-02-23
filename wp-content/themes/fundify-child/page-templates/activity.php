<?php
/**
 * Template Name: Activité
 *
 * @package Fundify
 * @since Fundify 1.0
 */
global $post;
global $useractivity;
$user = wp_get_current_user();

get_header(); ?>

	<div id="content">
		<div class="container">

		<h1 class="activity_page_title"><?php _e('Activité') ?></h1>
			<div class="activity_content">	
			<ul>
				<?php 
					foreach($useractivity->activity as $event):
						$date = new DateTime($event['time']);
				 ?>
					<li>
						<div>
							<strong><?php echo $date->format('d M Y') ?></strong>
							<p><?php echo $event['event'] ?></p>
						</div>
					</li>
					<li class="separator"></li>
				<?php endforeach; ?>
			</ul>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>