<?php
/**
 * Template Name: My projects
 *
 * @package Fundify
 * @since Fundify 1.0
 */


$args = array(
    'author'        =>	get_current_user_id(),
    'orderby'       =>	'post_date',
    'post_type'     =>	'ignition_product',
    'order'         =>'ASC',
    );

global $wpdb;
$myprojects = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_author`='.get_current_user_id().' AND  `post_type`="ignition_product" ');

get_header();
?>

	<div id="content">
		<div class="container">

		<h1 class="myprojects_page_title"><?php _e('Mes projets') ?></h1>
		<hr>
			<div class="myprojects_content">
			<ul>
			<?php
				foreach($myprojects as $post):

					switch ($post->post_status) {
						case 'ready':
							$change = 'publish';
						break;
						case 'draft':
							$change = 'pending';
						break;
						case 'pending':
							$change = 'draft';
						case 'publish':
							$change = 'draft';
						break;
						default:
							$change = 'draft';
						break;
					}

				$postactu = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `meta_key`="ign_updates" AND  `post_id`='.$post->ID.' ');
			?>

				<li id="<?php echo $post->ID ?>" class="clearfix">
					<div class="col-sm-6">
						<p><?php echo $post->post_title ?> <span> <button class="btn btn-success" onclick="change_state(<?php echo $post->ID ?>,'<?php echo $change ?>')"><?php echo $post->post_status; ?></button> </span> </p>
						<p><a href="/creer-un-projet/?postid=<?php echo $post->ID ?>">Edit</a></p>
						<p><a target="_blank" href="<?php echo get_permalink($post->ID); ?>">Voir</a></p>
					</div>
					<div class="col-sm-6">
						<div class="project_actus">
							<ul>
								<?php foreach ($postactu as $actual):
									$actu = json_decode(stripslashes($actual->meta_value));
								?>
								<li id="<?php echo $actual->meta_id ?>">
									<div>
									<a class="pull-right" href="javascript:deleteActu(<?php echo $post->ID ?>,<?php echo $actual->meta_id ?>)" ><i class="fa fa-times"></i></a>
									<p><?php echo html_entity_decode($actu->actu) ?></p>
									<p><?php echo $actu->date_actu ?></p>
									</div>
								</li>			
								<?php endforeach; ?>
							</ul>
						</div>
						<p><a href="javascript:addActu(<?php echo $post->ID; ?>)"><?php _e('Ajouter une actu') ?></a></p>
						<div class="actuform hide">
							<?php require('actu_form.php'); ?>
						</div>
					</div>
						<hr class="clearfix">
				</li>
				
			<?php
				endforeach;
			?>
			</ul>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>