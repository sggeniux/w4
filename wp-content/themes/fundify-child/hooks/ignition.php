<?php

/* AJOUTER UN CHAMP */
add_action('id_postmeta_boxes', 'ajoute_un_champ');
function ajoute_un_champ($meta_boxes) {
	/* CHAMP ZONES*/
	$field = array('id'=>'zones','name' => 'Zones', 'desc' => 'Zones', 'class'=>'zones', 'show_help'=> false,'options' => select_util('zones'),'type'=> 'select' );
	array_push($meta_boxes[0]["fields"], $field);
	return $meta_boxes;
}

/* CHAMPS DE TYPE SELECT */
function select_util($type){
	$select = array(array(' - - ','null','null'));
	$type = file_get_contents(get_stylesheet_directory_uri().'/util/'.$type.'.txt');
	$type = explode("\n", $type);
	foreach($type as $value){
		$value = explode('|',$value);
		$label = trim(utf8_encode($value[0]));
		$val = trim($value[1]);
		$options = array('name' => $label,'id'=> $val,'value' => $val);
		array_push($select,$options);
	}
  return $select;
}


add_action('id_postmeta_boxes', 'champ_vedette');
function champ_vedette($meta_boxes){
	$field = array('id'=>'vedette','name' => 'Vedette', 'desc' => 'Vedette', 'class'=>'vedette', 'show_help'=> false,'options' => array(array('name' => 'Oui','id' => 'yes','value' => '1'),array('name' => 'Non','id' => 'no','value' => '0')),'type'=> 'select' );
	array_push($meta_boxes[0]["fields"], $field);
	return $meta_boxes;
}

function save_notif($notif_type,$id){

	$notif = new Notif;
	$event = $notif_type;
	$project = $id;
	$amount = 'NULL';
	$notif->save($notif_type,$event,$project,$amount);
}


add_action('id_update_project','save_notif_update');
function save_notif_update($post){
	$e = 'update_project';
	$i = $post;
	save_notif($e,$i);
}


/* * * * * * * * * * * * * * * * * * * * */
/* INSERTION DES PROJETS DEPUIS LE FRONT */
/* * * * * * * * * * * * * * * * * * * * */


require_once('project_submission.php');


/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */





add_action('md_profile_extrafields','add_extra_geniux_fields');

function add_extra_geniux_fields(){

	global $wpdb;
	$u = get_current_user_id();
	$user = wp_get_current_user();


	/* SELECTIONNE LES META DATAS POUR REMPLIR LESCHAMPS CUSTOMS */
	$fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'usermeta WHERE `user_id` ='.$u.' ');

	/* => REMPLIR LESCHAMPS CUSTOMS */
	$extras_fields = array();
	foreach($fields as $field){
		$extras_fields[$field->meta_key] = $field->meta_value;
	}
	if( !ctype_digit($extras_fields["idc_avatar"]) ){
		$avatar_extra = $extras_fields["idc_avatar"];
	}else{
		$avatar_extra = NULL;
	}
?>

<?php if($avatar_extra !== NULL ): ?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.idc_avatar_image').find('img').attr('src','<?php echo $avatar_extra ?>');
		jQuery('.idc_avatar_image').find('img').css('display','block');
	});
	</script>
<?php endif; ?>

<?php if($extras_fields["social_register"] === 'true'): ?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.form-row input.pw').parent('.form-row').remove();
			jQuery('.form-row input.cpw').parent('.form-row').remove();
			jQuery('.desc-note:nth-child(2)').remove();
			jQuery('h2.border-bottom:nth-child(2)').remove();
			jQuery('p.desc-note').remove();
		});
	</script>
<?php endif; ?>

		<div class="global_alertbox">
			<div>
			</div>
		</div>

	<div class="dashboard_page_title">
		<h1 class="dashboard_title"><?php _e('Profil','fundify') ?></h1>
	</div>

				<div id="registration-form-extra-fields" class="form-row avatar_upload">
					<?php
						if( is_numeric(get_user_meta($u,'idc_avatar',true))){ 
							$img = wp_get_attachment_url(get_user_meta($u,'idc_avatar',true));
							//$img = wp_get_attachment_image( get_user_meta($u,'idc_avatar',true), 'thumbnail' );
						}else{
							$img = get_user_meta($u,'idc_avatar',true);
							//$img = '<img src="'.get_user_meta($u,'idc_avatar',true).'" />' ;
						}

						if( empty($img) ){
							$img = '/wp-content/uploads/default_avatars/homme_1.svg';
						}
					?>
					<input type="hidden" name="idc_avatar" id="idc_avatar" value="<?php echo $img; ?>">
					<div class="avatar_preview pull-left-element">
						<img src="<?php echo $img; ?>" />
					</div>

					<div class="pull-left-element">
						<div class="choose_avatar">
							<?php  foreach(getDefaultsAvatars() as $img): ?>
							<div class="choose_default_avatar">
								<label>
								<img src="<?php echo $img ?>">
								<input class="default_avatar" value="<?php echo $img ?>"  type="radio" name="default_avatar">
								</label>
							</div>
						<?php endforeach; ?>
							<div class="clearfix"></div>
						</div>
						<span class="file_input">
							<label>
							<?php _e('MODIFIER LA PHOTO') ?>
							<input type="file" class="idc_avatar_file" id="idc_avatar_file" name="idc_avatar_file" value="">
							</label>
						</span>
					</div>
					<div class="avatar_types pull-left-element">
						<a class="ff" href="javascript:choose_avatar_types('ff')"><?php _e('Choisir un visage féminin','fundify') ?></a>
						<a class="hh hide" href="javascript:choose_avatar_types('hh')"><?php _e('Choisir un visage masculin','fundify') ?></a>
					</div>
				</div>

				<div class="line_input">
					<div class="form-row">
						<label for="first-name"><?php _e('First Name') ?></label>
						<input type="text" size="20" class="first-name" name="first-name" value="<?php echo get_user_meta($u,'first_name',true); ?>">
					</div>
					<div class="form-row">
						<label for="last-name"><?php _e('Last Name') ?></label>
						<input type="text" size="20" class="last-name" name="last-name" value="<?php echo get_user_meta($u,'last_name',true); ?>">
					</div>
				</div>

				<div class="line_input">
					<div class="form-row full ">
						<label for="email"><?php _e('Email Address ') ?><span class="starred">*</span></label>
						<input type="email" size="20" class="email" name="email" value="<?php echo $user->user_email; ?>">
					</div>

					<div id="registration-form-extra-fields" class="form-row pseudo_cont">
												
						<?php

						if( ($extras_fields["use_pseudo"] === '2') || ($extras_fields["use_pseudo"] === NULL) || empty($extras_fields["use_pseudo"])  ){ 
							$checked = ' ';
							$readonly = ' hide ';
							$text_lab = __('Je préfère afficher un pseudo plutôt que mon nom','fundify');
						}else{
							$checked = ' checked="checked" ';
							$readonly = ' ';
							$text_lab = __('Pseudo','fundify');
						}
						
						?>

						<label>
						<input style="width: auto;float: left" type="checkbox" class="use_pseudo_chek" <?php echo $checked; ?> />
						<span class="label_text"><?php echo $text_lab; ?></span>
						<input type="hidden" class="use_pseudo" name="use_pseudo" value="<?php echo $extras_fields["use_pseudo"]; ?>" />
						</label>

						<?php
							if( $extras_fields['use_pseudo'] === '1' ){
								$pseud = $extras_fields["pseudo"] ;
								$required = 'required="required"';
							}else{
								$pseud = '';
								$required = '';
							}
						?>

						<input type="text" <?php echo $required ?>  size="20" class="pseudo <?php echo $readonly; ?>"  name="pseudo" value="<?php echo $pseud; ?>">
						
					</div>
				</div>

				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('.use_pseudo_chek').change(function(){
							if( jQuery(this).prop('checked') === true ){
								jQuery('.pseudo').removeAttr('required');
								jQuery('.pseudo').removeClass('hide');
								jQuery('.label_text').html('<?php _e('Pseudo','fundify') ?>');
								jQuery('.use_pseudo').val('1');
							}else{
								jQuery('.pseudo').val("");
								jQuery('.pseudo').attr('required','required');
								jQuery('.pseudo').addClass('hide');
								jQuery('.label_text').html('<?php _e('Je préfère afficher un pseudo plutôt que mon nom','fundify') ?>');
								jQuery('.use_pseudo').val('2');
							}
						});
					});
				</script>


				<div id="registration-form-extra-fields" class="line_input">
					<?php 
						$bd_date = explode('/',$extras_fields["birthday"]);
						$bd_j = trim($bd_date[0]);
						$bd_m = trim($bd_date[1]);
						$bd_y = trim($bd_date[2]);
					?>
					<div class="form-row">
						<label><?php _e('Birthday') ?></label>
						<input placeholder="JJ/MM/AAAA" type="text" name="birthday" autocomplete="off" value="<?php echo $extras_fields["birthday"] ?>" class="datepicker">
					</div>
					<script type="text/javascript">
					jQuery( function() {
						jQuery( ".datepicker" ).datepicker({
							changeMonth: true,
							changeYear: true,
							dateFormat : 'dd/mm/yy',
							minDate: "-99Y",
							maxDate: '-1Y',
						});
					});
					</script>
				</div>

					<div class="form-row 3_bloks">
					<!--
					<select class="btd_select jj" name="jj">
						<?php /* for($j=1;$j < 32;$j++): ?>
							<?php if( $j < 10 ){$j = '0'.$j; } ?>
							<?php if( trim($j) === $bd_j){ $j_sel = ' selected="selected" '; }else{$j_sel = '';} ?>
							<option <?php echo $j_sel; ?> value="<?php echo $j ?>"><?php echo $j ?></option>
						<?php endfor; ?>
					</select>
					</div>
					<div class="form-row 3_bloks">
					<select class="btd_select mm" name="mm">
						<?php for($m=1;$m < 13;$m++): ?>
							<?php if( $m < 10 ){$m = '0'.$m; } ?>
							<?php if( trim($m) === $bd_m){ $m_sel = ' selected="selected" '; }else{$m_sel = '';} ?>
							<option <?php echo $m_sel; ?> value="<?php echo $m ?>"><?php echo $m ?></option>
						<?php endfor; ?>
					</select>
					</div>
					<div class="form-row 3_bloks">
					<select class="btd_select yy" name="yy">
						<?php for($y=2016;$y > 1899;$y--): ?>
							<?php if( trim($y) === $bd_y){ $y_sel = ' selected="selected" '; }else{$y_sel = '';} ?>
							<option <?php echo $y_sel; ?> value="<?php echo $y ?>"><?php echo $y ?></option>
						<?php endfor; ?>
					</select>
					</div>
					<input type="hidden" id="birthday" name="birthday" value="<?php echo $extras_fields["birthday"] */?>">

					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('.btd_select').change(function(){
								var birthday = jQuery('.jj').val()+'/'+jQuery('.mm').val()+'/'+jQuery('.yy').val();
								jQuery('#birthday').val(birthday);
							});
						});
					</script>
				-->
				</div>

				<div class="form-row half left ">
						<!--<label for="nicename"><?php // _e('Display Name') ?> <span class="starred">*</span></label>
						<input type="text" size="20" class="nicename" name="nicename" value="<?php //echo get_user_meta($u,'nicename',true); ?>"> -->
				</div>

				<div class="form-row full ">
						<label for="description"><?php _e('Biographie','fundify') ?></label>
						<textarea row="10" class="description" name="description" placeholder="<?php _e('Quel est votre parcours ?','fundify') ?>"><?php echo $extras_fields["description"] ?></textarea>
				</div>


				<p class="section_title"><?php _e('Localisation','fundify') ?></p>

				<!--
				<div class="line_input">
					<div id="registration-form-extra-fields" class="form-row">
						<label><?php // _e('Nationality') ?></label>
						<select class="nationality" name="nationality" >
						<?php // echo get_users_select('nationality',$extras_fields["nationality"]); ?>
						</select>
					</div>
				</div>
				-->

				<div id="registration-form-extra-fields full" class="form-row">
					<label><?php _e('Postal Adress') ?></label>
					<input id="autocomplete" onfocus="initAutocomplete()" type="text" size="20" class="adresse" name="adresse" value="<?php echo $extras_fields["adresse"] ?>">
				</div>


				<div class="line_input">
					<div id="registration-form-extra-fields" class="form-row">
						<!--<label><?php // _e('Zip code') ?></label>-->
						<input type="hidden" size="20" id="postal_code" onfocus="initAutocomplete()" class="code_postal" name="code_postal" value="<?php echo $extras_fields["code_postal"] ?>">
					</div>

					<div id="registration-form-extra-fields" class="form-row">
						<!-- <label><?php // _e('City') ?></label> -->
						<input type="hidden" size="20" id="locality" class="city" name="city" value="<?php echo $extras_fields["city"] ?>">
					</div>
				</div>

				<p class="section_title"><?php _e('Réseaux','fundify') ?></p>


				<div class="line_input">
					<ul class="buttons_sociaux list-inline">
						<li><a href="javascript:open_soc('fb')">
						<img class="<?php if( !empty(get_user_meta($u,'facebook',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/disabled/facebook.png">
						<img class="<?php if( empty(get_user_meta($u,'facebook',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/active/facebook.png">
						</a></li>
						<li><a href="javascript:open_soc('tw')">
						<img class="<?php if( !empty(get_user_meta($u,'twitter',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/disabled/twitter.png">
						<img class="<?php if( empty(get_user_meta($u,'twitter',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/active/twitter.png">
						</a></li>
						<li><a href="javascript:open_soc('gg')">
						<img class="<?php if( !empty(get_user_meta($u,'google',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/disabled/google.png">
						<img class="<?php if( empty(get_user_meta($u,'google',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/active/google.png">
						</a></li>
						<li><a href="javascript:open_soc('ln')">
						<img class="<?php if( !empty(get_user_meta($u,'linkedin',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/disabled/linkedin.png">
						<img class="<?php if( empty(get_user_meta($u,'linkedin',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/active/linkedin.png">
						</a></li>
						<li><a href="javascript:open_soc('insta')">
						<img class="<?php if( !empty(get_user_meta($u,'instagram',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/disabled/instagram.png">
						<img class="<?php if( empty(get_user_meta($u,'instagram',true)) ){ echo 'hide'; } ?>" src="/wp-content/themes/fundify-child/img/sociaux/active/instagram.png">
						</a></li>
					</ul>
				</div>

				<div class="sociaux_inputs">
						
						<div class="line_input">
		                    <div class="form-row fb <?php if( empty(get_user_meta($u,'facebook',true)) ){ echo 'hide'; } ?>">
								<label for="facebook"><?php _e('Facebook URL') ?></label>
								<input type="url" size="20" class="facebook" name="facebook" value="<?php echo get_user_meta($u,'facebook',true); ?>">
		                    </div>
		                </div>
		                
		                <div class="line_input">
							<div class="form-row tw <?php if( empty(get_user_meta($u,'twitter',true)) ){ echo 'hide'; } ?>">
								<label for="twitter"><?php _e('Twitter URL') ?></label>
								<input type="url" size="20" class="twitter" name="twitter" value="<?php echo get_user_meta($u,'twitter',true); ?>">
		                    </div>
		                </div>
		                
		                <div class="line_input">
		                   <div class="form-row gg <?php if( empty(get_user_meta($u,'google',true)) ){ echo 'hide'; } ?>">
								<label for="google"><?php _e('Google URL') ?></label>
								<input type="url" size="20" class="google" name="google" value="<?php echo get_user_meta($u,'google',true); ?>">
							</div> 
						</div>
						
						<div class="line_input">
		                   <div class="form-row ln <?php if( empty(get_user_meta($u,'linkedin',true)) ){ echo 'hide'; } ?>">
								<label for="linkedin"><?php _e('Linkedin URL') ?></label>
								<input type="url" size="20" class="linkedin" name="linkedin" value="<?php echo get_user_meta($u,'linkedin',true); ?>">
							</div>
						</div>

						<div class="line_input">
		                   <div class="form-row insta <?php if( empty(get_user_meta($u,'instagram',true)) ){ echo 'hide'; } ?>">
								<label for="instagram"><?php _e('Instagram URL') ?></label>
								<input type="url" size="20" class="instagram" name="instagram" value="<?php echo get_user_meta($u,'instagram',true); ?>">
							</div> 
						</div>
				</div>

					<div class="line_input">
						<div class="form-row half">
							<label for="url"><?php _e('Website URL', 'memberdeck'); ?></label>
							<input type="url" size="20" class="url" name="url" value="<?php echo get_user_meta($u,'url',true); ?>"/>
						</div>
					</div>



					<?php  if( get_user_meta($u,'social_register',true) !== 'true' ): ?>

					<p class="section_title"><?php _e('Paramètres','fundify') ?></p>

					<p class="sous_title"><?php _e('Mot de passe','fundify') ?></p>
					<p class="infos_help"><?php _e('Pour modifier votre mot de passe, entrez votre mot de passe actuel puis votre nouveau mot de passe.','fundify') ?></p>

					<div class="line_input">
						<div class="form-row half ">
						<label for="aw"><?php _e('Actual Password') ?></label>
						<input type="password" size="20" class="aw" name="aw">
						<input id="is_cpas_ok" type="hidden" value="">
						</div>
					</div>

					<div class="line_input">
						<div class="form-row half ">
							<label for="pw"><?php _e('Password') ?></label>
							<input type="password" size="20" class="pw" name="pw">
						</div>

						<div class="form-row half ">
							<label for="cpw"><?php _e('Re-enter Password') ?></label>
							<input type="password" size="20" class="cpw" name="cpw">
						</div>
					</div>

					<!-- <a class="btn btn-default" href="#" ><?php // _e('ENREGISTRER LE NOUVEAU MOT DE PASSE','fundify'); ?></a> -->

				<?php endif; ?>


				<!--
				<div id="registration-form-extra-fields" class="form-row">
					<label><?php // _e('Phone number') ?></label>
					<input type="text" size="20" class="phone" name="phone" value="<?php // echo $extras_fields["phone"] ?>">
				</div>
				-->

				<div class="notifications_params">
					<p class="notifs_big_title"><?php _e('Notifications','fundify') ?></p>
					<div class="form-row" >
						<p class="notifs_title"><?php _e('W4','fundify') ?></p>
						<label class="checkbox">
						<?php if($extras_fields["notif_newsletter"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_newsletter"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e('Recevoir les dernières nouvelles et événements qui ont retenu notre attention.','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_newsletter" value="<?php echo $extras_fields["notif_newsletter"] ?>">
						</label>	
					</div>
					<div class="form-row" >
						<p class="notifs_title"><?php _e('Projets créés','fundify') ?></p>
						
						<label class="checkbox">
						<?php if($extras_fields["notif_donation"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_donation"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e('Votre projet a reçu une contribution','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_donation" value="<?php echo $extras_fields["notif_donation"] ?>">
						</label>
						
						<label class="checkbox">
						<?php if($extras_fields["notif_follow"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_follow"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e('Votre projet est suivi','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_follow" value="<?php echo $extras_fields["notif_follow"] ?>">
						</label>
						
						
						<label class="checkbox">
						<?php if($extras_fields["notif_comment"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_comment"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e('Votre projet est commenté','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_comment" value="<?php echo $extras_fields["notif_comment"] ?>">
						</label>
						
						
						<label class="checkbox">
						<?php if($extras_fields["notif_add_to_team"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_add_to_team"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e('Votre projet est ajouté à une équipe','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_add_to_team" value="<?php echo $extras_fields["notif_add_to_team"] ?>">
						</label>
						
						
						<label class="checkbox">
						<?php if($extras_fields["notif_alert_reward"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_alert_reward"] === 'true'){ echo 'checked="checked"';;} ?> ><?php _e("L'une des contreparties de votre projet n'est plus disponible",'fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_alert_reward" value="<?php echo $extras_fields["notif_alert_reward"] ?>">
						</label>

					</div>
					<div class="form-row" >
						<p class="notifs_title"><?php _e('Projets soutenus','fundify') ?></p>
						
						
						<label class="checkbox">
						<?php if($extras_fields["notif_project_saved"] === 'true'): ?>
							<i class="fa fa-dot-circle-o" aria-hidden="true"></i>
						<?php else: ?>
							<i class="fa fa-circle-o" aria-hidden="true"></i>
						<?php endif; ?>
						<input type="checkbox" value="true" <?php if($extras_fields["notif_project_saved"] === 'true'){ echo 'checked="checked"';;} ?>><?php _e('Le projet a été mis à jour','fundify') ?>
						<input type="hidden" class="hid_ckbx" name="notif_project_saved" value="<?php echo $extras_fields["notif_project_saved"] ?>">
						</label>	


					</div>		

					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('.notifications_params').find('input[type="checkbox"]').each(function(){
								jQuery(this).change(function(){
									jQuery(this).parent().find('i').toggleClass('fa-circle-o');
									jQuery(this).parent().find('i').toggleClass('fa-dot-circle-o');
									if( jQuery(this).prop('checked') ){
										jQuery(this).parent().find('.hid_ckbx').val('true');
									}else{
										jQuery(this).val('false');
										jQuery(this).parent().find('.hid_ckbx').val('false');
									}
								});
								if( jQuery(this).prop('checked') ){
									jQuery(this).val('true');
								}else{
									jQuery(this).val('false');
								}
							});

							var annulBtn = '<div class="anul_refresh"><p class="text-center"> <a class="" href="<?php echo site_url() ?>/dashboard/"><?php _e('Annuler','fundify') ?></a></p></div>';
							jQuery("#edit-profile-submit").after(  annulBtn );
						})
					</script>

				</div>


				

				<!--
				<div id="registration-form-extra-fields" class="form-row">
					<label><?php // _e('Language') ?></label>
					<select class="language" name="language" >
					<?php //echo get_users_select('language',$extras_fields["language"]); ?>
					</select>
				</div>


				<div id="registration-form-extra-fields" class="form-row">
					<label><?php // _e('Currency') ?></label>
					<select class="currency" name="currency" >
						<?php // echo get_users_select('currency',$extras_fields["currency"]); ?>
					</select>
				</div>
				-->



<?php
}


/* GESTION DES ACTUS */

function save_actu(){
	if($_REQUEST["fn"] === 'save_actu' ){
		
		$actusform = utf8_decode(urldecode($_REQUEST['data']));
		$actusform = explode('&',$actusform);
		$json = array();
		foreach($actusform as $actu){
			$actu = explode('=', $actu);
			if( !empty($actu[1])){
				$json[$actu[0]] = htmlentities($actu[1]);				
			}
		}
		
		if( !empty($json)){
			$actus = json_encode($json);
			add_post_meta($_REQUEST['postid'], 'ign_updates', $actus,false);
			echo $actus;			
		}
		exit;			
		
	}
}

add_action('wp_ajax_nopriv_do_ajax_save_actu', 'save_actu');
add_action('wp_ajax_do_ajax_save_actu', 'save_actu');


function delete_actu(){
	if($_REQUEST["fn"] === 'delete_actu' ){
		global $wpdb;
		//$sql = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `meta_id` ='.$_REQUEST["metaid"];
		$wpdb->delete( $wpdb->prefix.'postmeta', array( 'meta_id' => $_REQUEST["metaid"] ) );
		echo '1';
		exit;
	}
}
add_action('wp_ajax_nopriv_do_ajax_delete_actu', 'delete_actu');
add_action('wp_ajax_do_ajax_delete_actu', 'delete_actu');

function load_actu(){
	if($_REQUEST["fn"] === 'load_actu' ){
		global $wpdb;
		$postactu = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `meta_key`="ign_updates" AND  `post_id`='.$_REQUEST["post"].' ');
		foreach ($postactu as $actual):
			$actu = json_decode(stripslashes($actual->meta_value));
			$return .= '<li id="'.$actual->meta_id.'"><div><a class="pull-right" href="javascript:deleteActu('.$_REQUEST["post"].','.$actual->meta_id.')" ><i class="fa fa-times"></i></a><p>'.html_entity_decode($actu->actu).'</p><p>'.$actu->date_actu.'</p></div></li>';
		endforeach;
		echo $return;
		exit;			
		
	}
}

add_action('wp_ajax_nopriv_do_ajax_load_actu', 'load_actu');
add_action('wp_ajax_do_ajax_load_actu', 'load_actu');