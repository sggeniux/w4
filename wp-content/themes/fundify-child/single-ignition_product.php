<?php
/**
 * The Template for displaying all single projects.
 *
 * @package Fundify
 * @since Fundify 1.0
 */


global $campaign;
global $post;

$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);

$project_single = new Project_Single($post->ID);

	
if ($project_id > 0) {

	$project = new ID_Project($project_id);
	$the_project = $project->the_project();
	$days_left = $project->days_left();
	$end_type = get_post_meta($post_id, 'ign_project_type', true);
	$closed = $project->project_closed();
	$deck = new Deck($project_id);
	$the_deck = $deck->the_deck();
	if (!empty($the_deck)) {
		$levels = $the_deck->level_data;
	}
	$short_desc = html_entity_decode(get_post_meta($post_id, 'ign_project_description', true));
	$long_desc = html_entity_decode(get_post_meta($post_id, 'ign_project_long_description', true));
	$permalink_structure = get_option('permalink_structure');
	if (empty($permalink_structure)) {
		$url_suffix = '&';
	}
	else {
		$url_suffix = '?';
	}
	$url = get_permalink($post_id).$url_suffix.'purchaseform=1&prodid='.$project_id;
}





get_header(); ?>

<?php 
if($_GET['step'] === 'preview'):

global $submit;

endif;

require_once('page-templates/steps/header_edit.php');
?>




	<?php while ( have_posts() ) : the_post(); ?>



		<div id="content" class="post-details">
			<?php 
			if (isset($_GET['purchaseform']) && $_GET['purchaseform'] == 'fundify') {
				locate_template( array( 'project/project-purchase-form.php' ), true);
			}else { 
			
			do_action( 'atcf_campaign_before', array('ID' => $post_id) ); ?>

			<?php locate_template( array( 'project/title.php' ), true ); ?>

			<div class="container_first">
				
				<div class="short_desc">
					<p><?php echo $short_desc ?></p>
				</div>





					<?php locate_template( array( 'project/project-details.php' ), true ); ?>


					<div class="details-bottom">
						<div>
							<?php if($teams->isInMyTeam($post_id) === true): ?>
								<p><?php _e("Ce projet est déjà dans l'une de vos équipes ") ?></p>
							<?php endif; ?>
						</div>
						<form action="#" id="aptmt" method="POST" enctype="multipart/form-data">
							<input type="hidden" id="project" name="project" value="<?php echo $post_id ?>"/>
							<button type="button" class="add_to_team"><img src="/wp-content/themes/fundify-child/img/users.jpg" /> <?php _e('Ajouter à mon équipe') ?></button>
							<div id="teams_select"></div>
						</form>
					</div>
					<?php locate_template( array( 'project/share.php' ), true ); ?>

					<?php //locate_template( array( 'searchform-project.php' ), true ); ?>
					
				
			</div>
				<?php locate_template( array( 'project/project-sort-tabs.php' ), true ); ?>
			<div class="container_second">



					<div id="main-content">



						<div class="entry-content inner campaign-tabs">
							<div id="description">

									<div class="desciption_texte">
										<h3><?php _e('Le défi','fundify') ?></h3>
										<div class="defi">
											<?php the_content() ?>
										</div>
										<?php //echo apply_filters('fh_project_content', $long_desc, $project_id); ?>
										<h3><?php _e('Les solution que nous proposons','fundify') ?></h3>
										<div class="soluces">
											<?php echo $project_single->metas["soluces"] ?>
										</div>
										<h3><?php _e("L'impact de votre don",'fundify') ?></h3>
										<div class="impact">
											<?php echo $project_single->metas["impact"] ?>
										</div>
										<div class="infos_sup">
											<p><strong><?php _e('Website :','fundify') ?></strong> <a target="_blank" href="<?php echo $project_single->metas["website"] ?>"><?php echo $project_single->metas["website"] ?></a></p>
										</div>

										<div class="chrono">
											<p><strong><?php _e('Chronologie :','fundify') ?></strong> <?php echo $project_single->metas["chrono"] ?></p>
										</div>

										<div class="risques">
											<p><strong><?php _e('Risques :','fundify') ?></strong> <?php echo $project_single->metas["risques"] ?></p>
										</div>
									</div>
									<aside id="sidebar">
										<h3><?php _e('Soutenir ce projet','fundify') ?></h3>
											<?php if (is_id_pro()) {
												locate_template( array( 'project/author-info.php' ), true );
											} ?>
											<div id="contribute-now" class="single-reward-levels">
												<?php
													if ( !$closed ) { ?>
														<div class="edd_price_options active">
															<ul>
																<?php if (!empty($levels)) { ?>
																	<?php foreach ($levels as $level) { ?>
																	<?php
																	$level_invalid = getLevelLimitReached($project_id, $post_id, $level->id);
																	if (!function_exists('is_id_licensed') || !is_id_licensed()) {
																		$level_invalid = 1;
																	}
																	if( !empty( get_post_meta($post_id,'reward_image_'.$level->id,true)) ){
																		$image = ' data-image="'.get_post_meta($post_id,'reward_image_'.$level->id,true).'" ';
																	}else{
																		$image = '';
																	}
																	?>
																	<?php echo '<a data-level="'.$level->id.'" '.$image.' class="level-binding" '.(isset($level_invalid) && $level_invalid ? '' : 'href="'.apply_filters('id_level_'.$level->id.'_link', $url.'&level='.$level->id, $project_id).'"').'>'; ?>
																	<li class="atcf-price-option <?php echo ((isset($level_invalid) && $level_invalid) ? 'inactive' : ''); ?>">
																		<div class="clear">
																			<h3>
																				<label>
																				<span class="pledge-verb"><?php echo $level->meta_title ?></span>
																				<p><?php echo $level->meta_desc ?></p>
																				<button class="btn btn-orange"><?php _e('Donner', 'fundify'); ?> <?php echo (!empty($level->meta_price) ? apply_filters('id_price_selection', $level->meta_price, $the_deck->post_id) : ''); ?></button>
																				<?php //_e('Pledge', 'fundify'); ?>
																				 <?php //echo (!empty($level->meta_price) ? apply_filters('id_price_selection', $level->meta_price, $the_deck->post_id) : ''); ?>
																				</label>
																			</h3>
																			<!--
																			<div class="backers">
																				<div class="backer-count">
																					<i class="icon-user"></i> <?php //echo $level->meta_count; ?> <?php //_e('Backers', 'fundify'); ?>
																				</div>
																			</div>
																			 <p><?php //echo $level->meta_short_desc; ?></p> -->
																		</div>
																	</li>
																	<?php echo '</a>'; ?>
																	<?php } ?>
																<?php } ?>
															</ul>
															<?php echo do_action('id_widget_after', $project_id, $the_deck); ?>
														</div>
													<?php }
												?>
											</div>
										</aside>
							</div>


							<?php locate_template( array( 'project/updates.php' ), true ); ?>

							<?php comments_template(); ?>

							<?php locate_template( array( 'project/backers.php' ), true ); ?>

							<?php // locate_template( array( 'project/faqs.php' ), true); ?>

							<?php //locate_template( array( 'project/updates.php' ), true); ?>
						</div>

				<?php /*foreach($project_single->metas as $key => $data): ?>
					<pre>
					<?php var_dump($key); ?>
					<?php var_dump($data); ?>
					</pre>
				<?php endforeach; */ ?>

					</div>
			</div>
			<?php } ?>
		</div>

	<?php endwhile; ?>

<?php get_footer(); ?>