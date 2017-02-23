<?php
$project = $submit->post ;
$user = wp_get_current_user();
$author = $submit->getMemberByMail($user->user_email);

?>
					
				<div class="team_item">

					<div class="form-row">
						<input type="text" name="firstname" value="<?php echo $user->user_firstname; ?>" placeholder="<?php _e("Prénom") ?>">
					</div>
					<div class="form-row">
						<input type="text" name="firstname" value="<?php echo $user->user_lastname; ?>" placeholder="<?php _e("Nom") ?>">
					</div>
					<div class="form-row">
						<input type="text" name="user_poste" value="<?php echo $project->user_poste ?>" placeholder="<?php _e("Quel est votre poste au sein de l'organisation ?") ?>">
					</div>
					<div class="form-row">
						<textarea name="user_qualif" placeholder="<?php _e("Quelles sont vos qualifications pour réaliser ce projet ?") ?>"><?php echo $project->user_qualif ?></textarea>
					</div>
					<div class="form-row">
						<input type="email" name="user_email" value="<?php echo $user->user_email; ?>" placeholder="<?php _e("Votre adresse email") ?>">
					</div>
					<div class="form-row input_image">
						<label>
							<input type="file" name="user_photo" placeholder="" value="">
							<?php _e("Ajoutez une photo") ?>
						</label>
						<?php if( !empty($project->user_photo) ): ?>
							<div class="imgcont col-sm-5">
								<img src="<?php echo $project->user_photo; ?>" />
							</div>
						<?php endif; ?>
						
					</div>
					<div class="author col-sm-6">
						<ul>
							<li>
								<div class="author_item">
									<p><?php echo $author->user_email ?></p>
								</div>
							</li>
						</ul>
					</div>
					<div class="members col-sm-6">
						<ul>
								<?php if(!empty($project->team_member)): ?>
									<?php $i=0; ?>
									<?php foreach($project->team_member as $member): ?>
										<li id="<?php echo $i; ?>">
											<div class="member" >
												<p><?php echo $member['member_email'] ?> <span class="pull-right"><a title="<?php _e("Delete") ?>" href="javascript:deleteMember(<?php echo $i; ?>)"><i class="fa fa-times"></i></a></span></p>
											</div>
										</li>
										<?php $i++; ?>
									<?php endforeach; ?>
								<?php endif; ?>
						</ul>
					</div>
				</div>
				<br/>
				<div class="form-row add_members_button">
					<a href="#TB_inline?width=600&height=550&inlineId=add_team_member" class="thickbox btn btn-danger" title="<?php _e("Ajoutez des membres à votre équipe") ?>"><?php _e("Ajoutez des membres à votre équipe") ?></a>	
				</div>


<input type="hidden" name="next" value="<?php echo $submit->next ?>">
<input type="hidden" name="postid" id="postid" value="<?php echo $submit->postid ?>">