<?php
	$project = $submit->post;
	$rwnb = 0;
?>					

				<?php $op =''; ?>
				<ul class="rewards_list_ul">
					<?php for($i=1; $i < 7; $i++): ?>
						<?php
						if( !empty($submit->concatfind($project,'used_',$i)) ){
							$op = ' op ';
						}else{
							$op = '';
						} 

						if( $i === 1 ){
							$titlenameinpt = 'ign_product_title';
						}else{
							$titlenameinpt = 'ign_product_level_'.$i.'_title';
						}

							if( !empty($submit->concatfind($project,$titlenameinpt)) ){
								$exist = 'exist';
								$rwnb++;
							}else{
								$exist = '';
							}
						?>						
						<li data-id="<?php echo $i ?>" class="list_li_<?php echo $i ?> <?php echo $op; ?> <?php echo $exist; ?>" > <a href="javascript:active_reward(<?php echo $i ?>)"><?php _e('Contrepartie n°'.$i,'fundify') ?></a> </li>
					<?php endfor; ?>
					<li class="add_reward" ><a href="javascript:addReward()"><i class="fa fa-plus"></i></a></li>
				</ul>

	<div class="rewards">

				<?php $active = 'open'; ?>
				<?php $used = 1;  ?>
				<?php for($i=1; $i < 7; $i++): ?>


					<div id="rw_<?php echo $i; ?>" data-id="<?php echo $i; ?>" class="panel rewards_<?php echo $i; ?> <?php echo $active; ?>">

						<?php if( !empty($submit->concatfind($project,'used_',$i)) ){ $used = $submit->concatfind($project,'used_',$i); }else{ $used = 0; } ?>

						<input type="hidden" class="used_field" id="used_<?php echo $i; ?>" name="used_<?php echo $i; ?>" value="<?php echo $used; ?>" />

						
						<div class="form-row">
						<p class="text-center"><strong><?php _e('Contrepartie n°'.$i,'fundify') ?></strong></p>
						</div>

							<div class="contrepartie_view">
								<?php if( !empty($submit->concatfind($project,'ign_product_level_'.$i.'_title')) || ($i === 1) ): ?>

								<p>
									<ul class="list-inline pull-right">
										<li><a href="javascript:editRw('<?php echo $i ?>')"><?php _e('Edit','fundify') ?> <i class="fa fa-pencil-square-o"></i></a></li>
										<li><a class="delete_reward" href="#"><i class="fa fa-times"></i></a>	</li>
									</ul>
								</p>	
								<?php 
									$edit = 'hide';

									if( ($i === 1) && empty($submit->concatfind($project,'ign_product_title')) ){
										$edit = '';
									}

								?>
								
								<?php if( $i > 1 ): ?>
									<p><?php echo $submit->concatfind($project,'ign_product_level_'.$i.'_price'); ?> <?php _e($submit->concatfind($project,'ign_product_level_'.$i.'_currency'),'fundify'); ?></p>
								<?php else: ?>
									<p><?php echo $submit->concatfind($project,'ign_product_price'); ?> <?php _e($submit->concatfind($project,'ign_product_currency'),'fundify'); ?></p>
								<?php endif; ?>

								<?php if( $i > 1 ): ?>
									<p><strong><?php echo $submit->concatfind($project,'ign_product_level_'.$i.'_title'); ?></strong></p>
								<?php else: ?>
									<p><strong><?php echo $submit->concatfind($project,'ign_product_title'); ?></strong></p>
								<?php endif; ?>

								<?php if( $i > 1 ): ?>
									<p><?php echo $submit->concatfind($project,'ign_product_level_'.$i.'_desc'); ?></p>
								<?php else: ?>
									<p><?php echo $submit->concatfind($project,'ign_product_details'); ?></p>
								<?php endif ?>

									<?php if( $submit->concatfind($project,'reward_assoc_don_',$i) === 'true'): ?>
										<p><strong><?php _e('Récompenses :','fundify') ?></strong></p>
										<p><?php echo $submit->concatfind($project,'reward_title_',$i); ?></p>
										<p><?php echo $submit->concatfind($project,'reward_desc_',$i); ?></p>

										<?php if( !empty($submit->concatfind($project,'reward_image_',$i)) ): ?>
											<p><img src="<?php echo $submit->concatfind($project,'reward_image_',$i); ?>" style="max-width: 240px;" /></p>
										<?php endif; ?>

										<p><?php _e('Livraison prévue :','fundify') ?> <?php echo $submit->concatfind($project,'livraison_month_',$i); ?> <?php echo $submit->concatfind($project,'livraison_year_',$i); ?></p>

										<p><?php _e('Détails de livraison :','fundify') ?> <?php echo $submit->concatfind($project,'reward_livr_infos_',$i); ?></p>
										
										<?php if( $submit->concatfind($project,'qte_limit_',$i) === 'limit'): ?>
											<p class="text-danger"><?php _e('Récompenses limitée :','fundify') ?> <?php echo $submit->concatfind($project,'rewards_number_',$i); ?> <?php _e('disponibles','fundify') ?></p>
										<?php endif; ?>

									<?php endif; ?>


							<?php else: $edit = ''; ?>
								<?php endif; ?>
							</div>

							<div class="edition_view <?php echo $edit; ?>">



									<div class="form-row">
										<!--<input id="field_1_<?php /*echo $i ?>" class="field contrepartie_title" type="text" name="contrepartie_title_<?php echo $i ?>" value="<?php echo $submit->concatfind($project,'contrepartie_title_',$i); ?>" placeholder="<?php _e('Nom de la contrepartie','fundify')*/ ?>"> -->
										<?php if( $i > 1 ): ?>
										<input id="field_1_<?php echo $i ?>" class="field contrepartie_title" type="text" name="ign_product_level_<?php echo $i ?>_title" value="<?php echo $submit->concatfind($project,'ign_product_level_'.$i.'_title'); ?>" placeholder="<?php _e('Nom de la contrepartie','fundify') ?>">
										<input type="hidden" name="ign_product_level_<?php echo $i ?>_order" value="<?php echo $i ?>">

									<?php else: ?>
										<input id="field_1_<?php echo $i ?>" class="field contrepartie_title" type="text" name="ign_product_title" value="<?php echo $submit->concatfind($project,'ign_product_title'); ?>" placeholder="<?php _e('Nom de la contrepartie','fundify') ?>">
									<?php endif; ?>
									</div>

									<div class="form-row">
										<label><?php _e('Description','fundify') ?></label>
										<?php
										/*
											$contrepartie_desc_cont = $submit->concatfind($project,'contrepartie_desc_',$i);
											wp_editor( $contrepartie_desc_cont, 'contrepartie_desc_'.$i, $editor_settings );
										*/
											if( $i > 1 ):
												$contrepartie_desc_cont = $submit->concatfind($project,'ign_product_level_'.$i.'_desc');
												wp_editor( $contrepartie_desc_cont, 'ign_product_level_'.$i.'_desc', $editor_settings );
											else:
												$contrepartie_desc_cont = $submit->concatfind($project,'ign_product_details');
												wp_editor( $contrepartie_desc_cont, 'ign_product_details', $editor_settings );
											endif;
										?>
									</div>

									<div class="form-row rwgrpfields">
										<!--
										<input id="field_3_<?php /* echo $i ?>" class="field reward_montant" placeholder="<?php _e("Montant",'fundify') ?>" type="text" name="reward_montant_<?php echo $i; ?>" value="<?php echo $submit->concatfind($project,'reward_montant_',$i); */?>" />
										-->
										<?php if( $i > 1 ): ?>
											
											<input id="field_3_<?php echo $i ?>" class="field reward_montant" placeholder="<?php _e("Montant",'fundify') ?>" type="text" name="ign_product_level_<?php echo $i ?>_price" value="<?php echo $submit->concatfind($project,'ign_product_level_'.$i.'_price'); ?>" />

											<select id="field_4_<?php echo $i ?>" class="field required" name="ign_product_level_<?php echo $i ?>_currency" placeholder="Curency">
											<?php if($submit->concatfind($project,'ign_product_level_'.$i.'_currency') === 'EUR' ): ?>
												<option checked="checked" value="EUR"> € </option>
											<?php else: ?>
												<option value="EUR"> € </option>
											<?php endif; ?>
											<?php if($submit->concatfind($project,'ign_product_level_'.$i.'_currency') === 'USD' ): ?>
												<option checked="checked" value="USD"> $ </option>
											<?php else: ?>
												<option value="USD"> $ </option>
											<?php endif; ?>
											</select>

									<?php else: ?>
											<input id="field_3_<?php echo $i ?>" class="field reward_montant" placeholder="<?php _e("Montant",'fundify') ?>" type="text" name="ign_product_price" value="<?php echo $submit->concatfind($project,'ign_product_price'); ?>" />

											<select id="field_4_<?php echo $i ?>" class="field required" name="ign_product_level_<?php echo $i ?>_currency" placeholder="Curency">
											<?php if($submit->concatfind($project,'ign_product_currency') === 'EUR' ): ?>
												<option checked="checked" value="EUR"> € </option>
											<?php else: ?>
												<option value="EUR"> € </option>
											<?php endif; ?>
											<?php if($submit->concatfind($project,'ign_product_currency') === 'USD' ): ?>
												<option checked="checked" value="USD"> $ </option>
											<?php else: ?>
												<option value="USD"> $ </option>
											<?php endif; ?>
											</select>
									<?php endif; ?>
										<!--
										<select id="field_4_<?php /* echo $i ?>" class="field required" name="reward_currency_<?php echo $i; ?>" placeholder="Curency">
										<?php if($submit->concatfind($project,'reward_currency_',$i) === 'EUR' ): ?>
											<option checked="checked" value="EUR"> € </option>
										<?php else: ?>
											<option value="EUR"> € </option>
										<?php endif; ?>
										<?php if($submit->concatfind($project,'reward_currency_',$i) === 'USD' ): ?>
											<option checked="checked" value="USD"> $ </option>
										<?php else: ?>
											<option value="USD"> $ </option>
										<?php endif;*/ ?>
										</select>
										-->


									</div>

									<div class="form-row">
										<label>
											<?php if($submit->concatfind($project,'reward_assoc_don_',$i) === 'true' ){
												$rew_assoc_sups = ' ';
												$reward_assoc_don = ' checked="checked" ';
											}else{
												$rew_assoc_sups = 'hide';
												$reward_assoc_don = '';
											} ?>
											
											<input id="field_5_<?php echo $i ?>" class="field" type="checkbox" value="<?php echo $submit->concatfind($project,'reward_assoc_don_',$i) ?>" <?php echo $reward_assoc_don; ?> placeholder="<?php _e("Associer une récompense à ce don") ?>" name="reward_assoc_don_<?php echo $i; ?>"> <?php _e("Associer une récompense à ce don") ?>

												<script type="text/javascript">
													jQuery(document).ready(function(){
														jQuery('#field_5_<?php echo $i ?>').change(function(){
															if( jQuery(this).prop('checked') === true ){
																jQuery(this).val(true);
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').removeClass('hide');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.reward_title').addClass('required');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.livraison_month').addClass('required');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.livraison_year').addClass('required');
															}else{
																jQuery(this).val(false);
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').addClass('hide');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.field').each(function(){
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.reward_title').removeClass('required');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.livraison_month').removeClass('required');
																jQuery(this).parents('.edition_view').find('.rew_assoc_sups').find('.livraison_year').removeClass('required');
																});
															}
														})
													});
												</script>
										</label>
									</div>

									<div class="rew_assoc_sups <?php echo $rew_assoc_sups ?>">

										<div class="form-row">
											<input id="field_6_<?php echo $i ?>" class="field reward_title" type="text" name="reward_title_<?php echo $i ?>" value="<?php echo $submit->concatfind($project,'reward_title_',$i); ?>" placeholder="<?php _e('Titre de la récompense') ?>">
										</div>

										<div class="form-row">
											<label><?php _e('Description','fundify') ?></label>
											<?php
												$reward_desc_cont = $submit->concatfind($project,'reward_desc_',$i);
												wp_editor( $reward_desc_cont, 'reward_desc_'.$i, $editor_settings );
											?>
										</div>

										<div class="form-row rew_qt_limit">
											<?php if( $submit->concatfind($project,'qte_limit_',$i) === 'nolimit' ): ?>
											<label><input id="field_8_<?php echo $i ?>" class="field qte_limit" checked="checked" type="radio" name="qte_limit_<?php echo $i; ?>" value="nolimit">  <?php _e('Quantitée illimitée de récompenses','fundify') ?></label>
										<?php else: ?>
											<label><input id="field_8_<?php echo $i ?>" class="field qte_limit" type="radio" name="qte_limit_<?php echo $i; ?>" value="nolimit">  <?php _e('Quantitée illimitée de récompenses','fundify') ?></label>
										<?php endif; ?>
										<?php if( $submit->concatfind($project,'qte_limit_',$i) === 'limit' ): ?>
											<label><input id="field_9_<?php echo $i ?>" class="field qte_limit" type="radio" name="qte_limit_<?php echo $i; ?>" value="limit"> <?php _e('Quantitée limitée de récompenses','fundify') ?></label>
										<?php else: ?>
											<label><input id="field_9_<?php echo $i ?>" class="field qte_limit" checked="checked" type="radio" name="qte_limit_<?php echo $i; ?>" value="limit"> <?php _e('Quantitée limitée de récompenses','fundify') ?></label>
										<?php endif; ?>
										</div>

										<?php if( $submit->concatfind($project,'qte_limit_',$i) === 'limit' ){ $limitHide = 'hide'; }else{ $limitHide = ''; } ?>
										<div class="form-row limitiflimit <?php echo $limitHide ?>">
											<input id="field_10_<?php echo $i ?>" class="field rewards_number" type="text" name="rewards_number_<?php echo $i ?>" value="<?php echo $submit->concatfind($project,'rewards_number_',$i); ?>" placeholder="<?php _e('Nombre de récompenses','fundify') ?>">
										</div>

										<script type="text/javascript">
											jQuery(document).ready(function(){
												jQuery('.rew_qt_limit input[type=radio]').change(function(){
													if( jQuery(this).val() === 'nolimit' ){
														jQuery('.limitiflimit').addClass('hide');
													}else{
														jQuery('.limitiflimit').removeClass('hide');
													}
												})
											});
										</script>

										<div class="form-row rwgrpfields">
											<label><?php _e('Livraison estimée','fundify') ?></label>
											<select class="field livraison_month" id="field_11_<?php echo $i ?>" name="livraison_month_<?php echo $i; ?>">
												<option disabled="disabled" value="null"><?php _e('Mois','fundify'); ?></option>
												<option value="01"><?php _e('Janvier','fundify'); ?></option>
												<option value="02"><?php _e('Février','fundify'); ?></option>
												<option value="03"><?php _e('Mars','fundify'); ?></option>
												<option value="04"><?php _e('Avril','fundify'); ?></option>
												<option value="05"><?php _e('Mai','fundify');  ?></option>
												<option value="06"><?php _e('Juin','fundify'); ?></option>
												<option value="07"><?php _e('Juillet','fundify'); ?></option>
												<option value="08"><?php _e('Aout','fundify'); ?></option>
												<option value="09"><?php _e('Septembre','fundify'); ?></option>
												<option value="10"><?php _e('Octobre','fundify'); ?></option>
												<option value="11"><?php _e('Novembre','fundify'); ?></option>
												<option value="12"><?php _e('Décembre','fundify'); ?></option>
											</select>
											<select id="field_12_<?php echo $i ?>" class="field livraison_year" name="livraison_year_<?php echo $i; ?>">
												<option disabled="disabled" value="null"><?php _e('Année','fundify'); ?></option>
											<?php for($y=2017; $y < 2051; $y++ ): ?>
												<option value="<?php echo $y; ?>"><?php echo $y ?></option>
											<?php endfor; ?>
											</select>
											<?php if( empty($submit->concatfind($project,'livraison_month_',$i)) || empty($submit->concatfind($project,'livraison_year_',$i)) ): ?>
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery('#field_11_<?php echo $i ?>').val('null');
													jQuery('#field_12_<?php echo $i ?>').val('null');
												})
											</script>
											<?php else: ?>
											<script type="text/javascript">
												jQuery(document).ready(function(){
													jQuery('#field_11_<?php echo $i ?>').val('<?php echo $submit->concatfind($project,'livraison_month_',$i); ?>');
													jQuery('#field_12_<?php echo $i ?>').val('<?php echo $submit->concatfind($project,'livraison_year_',$i); ?>');
												})
											</script>
										<?php endif; ?>
										</div>


									<div class="form-row">
									<textarea class="field reward_livr_infos" id="field_13_<?php echo $i ?>" name="reward_livr_infos_<?php echo $i ?>" placeholder="<?php _e("Informations de livraison") ?>"><?php echo $submit->concatfind($project,'reward_livr_infos_',$i); ?></textarea>
									</div>

									<div class="form-row rw_add_photo input_image">
									<label class="phot_field_14_<?php echo $i ?>">
										<span class="labeltext"><?php _e('Ajouter une photo','fundify'); ?></span> <span class="pull-right load_img hide"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></span><br/>

											<input type="hidden" class="field reward_image rw_hide_input data_field_14_<?php echo $i ?>" name="reward_image_<?php echo $i ?>" value="<?php echo $submit->concatfind($project,'reward_image_',$i); ?>" />
											<input type="file"   class="rw_file_input" id="field_14_<?php echo $i ?>" name="rw_img_f_<?php echo $i ?>" placeholder="<?php _e('Ajouter une photo','fundify'); ?>" />

										<?php if( !empty($submit->concatfind($project,'reward_image_',$i)) ): ?>
											<img src="<?php echo $submit->concatfind($project,'reward_image_',$i) ?>" style="max-width: 240px" />
										<?php endif; ?>

									</label>
									</div>

								</div>

								<div class="form-row">
								<ul class="rewards_tools">
									<li><a class="save_reward" href="#"><?php _e('Enregistrer & nouvelle contrepartie','fundify'); ?></a></li>
									<li><a class="duplicate_reward" href="#"><?php _e('Dupliquer','fundify'); ?></a></li>
									<li><a class="delete_reward" href="#"><?php _e('Supprimer','fundify'); ?></a></li>
								</ul>
								</div>

						</div>

					</div>
				<?php
					$active = ' hide ';
					$used = 0;
				?>
				<?php endfor; ?>
	</div>



<input type="hidden" id="ign_product_level_count" name="ign_product_level_count" value="<?php echo $rwnb ?>">


<input type="hidden" name="next" value="<?php echo $submit->next ?>">
<input type="hidden" id="postid" name="postid" value="<?php echo $submit->postid ?>">