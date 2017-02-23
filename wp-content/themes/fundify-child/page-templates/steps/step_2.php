<?php
	$project = $submit->post ;
	$video_images = $submit->medias;
?>

					<div class="form-row">
						<div class="add_video_images">
							<div class="input model input_img_vid hide">
								<label>
								<input type="file" value="">
									<?php _e('Choisissez une image') ?> <span class="pull-right load_img hide"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></span>
								</label>
							</div>
							<div class="modelvideo input_img_vid hide">
								<label>
								<input type="text" placeholder="<?php _e("Entrez l'url d'une vidéo Youtube") ?>" value="" /> <span class="pull-right load_img hide"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></span>
								</label>
							</div>
						<ul id="sortable_media">
						<?php $m = 1; ?>
						<?php foreach( $video_images as $media ):?>
							<li class="ui-state-default">
							<div class="input" id="<?php echo $media->meta_id ?>">
								<?php echo $submit->getMediaTypeInput($media); ?>
								<input type="hidden" value="<?php echo $media->meta_value ?>" name="video_image_<?php echo $m; ?>" />
							</div>
							</li>
							<?php $m++; ?>
						<?php endforeach; ?>
						</ul>
						</div>
						<div class="clearfix">
							<select class="choose_vid_img" id="choose_vid_img">
								<option value="img"> Image </option>
								<option value="vid"> Vidéo </option>
							</select>
							<button class="add_video_images_button btn btn-danger" type="button"><?php _e('Ajoutez des vidéos et des images') ?></button>
							<!-- <input type="hidden" id="media_order" name="media_order"> -->
						</div>
					</div>


					<div class="form-row">
					<label><?php _e("Quel défi choisissez vous de relever ?") ?></label>
					<!-- <textarea class="editor" placeholder="<?php // _e("Quel défi choisissez vous de relever ?") ?>"></textarea> -->
					<?php
						wp_editor( $project->defi, 'defi', $editor_settings );
					?>
					</div>

					<div class="form-row">
					<label><?php _e("Quelles solutions proposez vous ?") ?></label>
					<!-- <textarea class="editor" placeholder="<?php // _e("Quelles solutions proposez vous ?") ?>"></textarea> -->
					<?php
						$soluces_cont = '';
						wp_editor( $project->soluces, 'soluces', $editor_settings );
					?>
					</div>

					<div class="form-row">
					<label><?php _e("Quel sera l'impact des dons sur votre projet ?") ?></label>
					<!-- <textarea class="editor" placeholder="<?php // _e("Quel sera l'impact des dons sur votre projet ?") ?>"></textarea> -->
					<?php
						$impact_cont = '';
						wp_editor( $project->impact, 'impact', $editor_settings );
					?>
					</div>

					<div class="form-row">
					<label><?php _e("Comment les fonds récoltés vont-ils être utilisés ?") ?></label>
					
					<?php
						if( !empty($project->currency_gbl) ){
							$readonly_cur = ' readonly="readonly" data-global="'.$project->currency_gbl.'" data-curmess="'.__("Veuillez modifier cette option à l'étape précédente.",'fundify').'" ';
						}else{
							$readonly_cur = ' data-global="null" ';
						}
					?>

					<div class="utilisation_group">
					<div id="first" class="line hide" data-id="0">
						<input class="" placeholder="<?php _e("Montant") ?>" type="text" value="null">
						<select <?php echo $readonly_cur; ?> class="">
							<option value="null"> <?php _e('Devise') ?> </option>
							<?php if( $project->currency_gbl === 'EUR'): ?>
								<option selected="selected" value="EUR"> € </option>
							<?php else: ?>
								<option value="EUR"> € </option>
							<?php endif ?>
							<?php if( $project->currency_gbl === 'USD'): ?>
								<option selected="selected" value="USD"> $ </option>
							<?php else: ?>
								<option value="USD"> $ </option>
							<?php endif; ?>
						</select>
						<textarea class="" placeholder="<?php _e("Utilisation") ?>">NULL</textarea>
						<a href="#" class="remove_line"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div>


						<?php foreach($submit->utilisations as $cle => $utili) : ?>
							<div <?php echo $id_numb ?> class="line" data-id="<?php echo $cle ?>">
								<input class="required" placeholder="<?php _e("Montant") ?>" type="text" name="montant<?php echo '_'.$cle ?>" value="<?php echo $utili["montant"] ?>">
								<select <?php echo $readonly_cur; ?>s class="required" name="currency<?php echo '_'.$cle ?>">
									<option value="null"> <?php _e('Devise') ?> </option>
									<?php if(trim($utili["currency"]) === 'EUR'): ?>
										<option selected="selected" value="EUR"> € </option>
									<?php else: ?>
										<option value="EUR"> € </option>
									<?php endif ?>
									<?php if(trim($utili["currency"]) === 'USD'): ?>
										<option selected="selected" value="USD"> $ </option>
									<?php else: ?>
										<option value="USD"> $ </option>
									<?php endif; ?>
								</select>
								<textarea class="required" name="utilisation<?php echo '_'.$cle ?>" placeholder="<?php _e("Utilisation") ?>"><?php echo $utili["utilisation"] ?></textarea>

								<a href="#" class="remove_line"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div>
						<?php endforeach; ?>
						</div>
						<button type="button" class="add_utilisation_line btn btn-danger"><?php _e("Ajouter une ligne") ?></button>

					</div>

					<div class="form-row">
					<label></label>
					<textarea class="chrono" name="chrono" placeholder="<?php _e("Présentez la chronologie de votre projet") ?>"><?php echo $project->chrono ?></textarea>
					</div>

					<div class="form-row">
					<label></label>
					<textarea class="risques" name="risques" placeholder="<?php _e("Quels sont les risque et défi de la réalisation de votre projet ?") ?>"><?php echo $project->risques ?></textarea>
					</div>

					<input type="hidden" name="next" value="<?php echo $submit->next ?>">
					<input type="hidden" name="postid" id="postid" value="<?php echo $submit->postid ?>">
