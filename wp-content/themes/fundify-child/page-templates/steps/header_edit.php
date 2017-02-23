	<?php if( !empty($_GET['step']) || !empty($_GET['next']) || ($projecteditpage === 1) ): ?>
		<div class="project_publishment row">
					
					<div class="col-sm-9">

							<?php if( !empty($submit->post->post_status) ): ?>
								<p class="text-center"><?php _e('STATU_'.$submit->post->post_status,'fundify') ?></p>
							<?php endif; ?>

						<ul class="edit_projects_steps">
						<?php foreach($submit->steps as $key => $value): ?>
							<?php

								if( $post_id_url !== NULL ){
									$post_id_url_edit = $post_id_url;
								}

								if( $_POST['postid'] !== NULL ){
									$post_id_url_edit = '&postid='.$_POST['postid'];
								}


								$link = '/creer-un-projet/?next='.$key.$post_id_url_edit;

								if( $key === 6){
									$_GET['p'] = $submit->postid;
									$link = home_url().'?post_type=ignition_product&p='.$submit->postid.'&step=preview';
								}
							?>
							<li class="<?php echo $value ?>">
								<hr>
								<a href="<?php echo $link ?>">
								
								<span class="puce">
								<?php if($value === 'active curent'): ?>
									<img src="/wp-content/themes/fundify-child/img/edition/puce_active.png" />
								<?php else: ?>
									<img src="/wp-content/themes/fundify-child/img/edition/puce.png" />
								<?php endif; ?>
								</span>
								<?php _e('Edit_project_step_'.$key , 'fundify') ?></a>
							</li>
						<?php endforeach; ?>
						</ul>
					</div>

					<div class="col-sm-3">
						<?php if( ($_GET['step'] === 'preview') && ($submit->post->post_status !== 'ready') && ($post->post_status !== 'ready') ): ?>
							<span class="pull-right publish_buttons">

								<?php if( $post->post_status === 'publish' ): ?>
									<button class="btn btn-success " onclick="change_state(<?php echo $post->ID ?>,'draft')"><?php _e('Suspendre le projet', 'fundify') ?></button>
								<?php endif; ?>
								
								<?php if($post->post_status === 'draft'): ?>
									<button class="btn btn-success " onclick="change_state(<?php echo $post->ID  ?>,'pending')"><?php _e('Soumettre votre projet', 'fundify') ?></button>
								<?php endif; ?>


							<?php  if( !empty($submit->post->post_status) && empty($submit->post->post_status) ): ?>
								<?php if($submit->post->post_status === 'draft'): ?>
									<button class="btn btn-success " onclick="change_state(<?php echo $submit->post->ID ?>,'pending')"><?php _e('Soumettre votre projet', 'fundify') ?></button>
								<?php endif; ?>
								<?php if( $submit->post->post_status === 'publish' ): ?>
									<button class="btn btn-success " onclick="change_state(<?php echo $submit->post->ID ?>,'draft')"><?php _e('Suspendre le projet', 'fundify') ?></button>
								<?php endif; ?>								
							<?php endif;  ?>

							</span>
						<?php else: ?>

						<?php if( ($post->post_status === 'pending') || ($submit->post->post_status === 'pending' ) ): ?>
									<p class="text-danger waiting_for_approval"><?php _e('Votre projet est en attente de validation', 'fundify') ?></p>
						<?php endif; ?>

							<?php if( $_GET['step'] !== 'preview' ): ?>
								<h5 class="guide_title"><?php _e('Guide') ?></h5>
							<?php endif; ?>



							<span class="pull-right publish_buttons">

								<?php if( ($submit->post->post_status === 'ready') ): ?>
									<button class="btn btn-success " onclick="change_state(<?php echo $submit->post->ID  ?>,'pending')"><?php _e('Soumettre votre projet', 'fundify') ?></button>
								<?php else: ?>
									<?php if( ($post->post_status === 'ready') ): ?>
										<button class="btn btn-success " onclick="change_state(<?php echo $post->ID  ?>,'pending')"><?php _e('Soumettre votre projet', 'fundify') ?></button>
									<?php endif; ?>
								<?php endif; ?>
							</span>
						
						<?php endif ?>


					</div>
			</div>
	<?php endif; ?>
