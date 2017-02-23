<?php

	$project = $submit->post ;

?>

					<p class="step_5_title text-center"><?php _e('Informations sur votre organisation') ?></p>

					<div class="form-row">
						<input class="required " type="text" placeholder="<?php _e('Nom de votre organisation') ?>" name="organisation_name" value="<?php echo $project->organisation_name ?>">
					</div>

					<div class="form-row">
						<select name="organisation_country">
							<option value="null" disabled="disabled"><?php _e('Dans quel pays votre organisation est-elle établie ?') ?></option>
							<?php echo $submit->select_util('countrys',$project->organisation_country); ?>
						</select>
					</div>

					<div class="form-row">
						<select name="organisation_type">
							<option value="null" disabled="disabled"><?php _e('Quelle est la nature de votre organisation ?') ?></option>
							<option value="asso"><?php _e('Association') ?></option>
							<option value="entreprise"><?php _e('Entreprise') ?></option>
						</select>
					</div>

					<div class="form-row">
						<input type="text" name="organisation_siret" placeholder="<?php _e('Numéro d\'immatriculation de votre organisation...') ?>" value="<?php echo $project->organisation_siret ?>" />
					</div>

					<div class="form-row clearfix">
						<label class="import_doc">
							<?php _e('Ajoutez le formulaire officiel','fundify') ?>
							<input class="organisation_offi_form" type="file" id="organisation_offi_form" value="" /> <span class="load hide"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></span>
						</label>
						<input class="input" type="hidden" name="organisation_offi_form" value="<?php echo $project->organisation_offi_form ?>" />
					</div>

					<div class="form-row clearfix">
						<label class="import_doc">
						<?php _e("Ajoutez d'autres fichiers *",'fundify') ?>
						<input class="organisation_offi_form" type="file"  id="organisation_other_doc" value="" /> <span class="load hide"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></span>
						</label>
						<input class="input" type="hidden" name="organisation_other_doc" value="<?php echo $project->organisation_other_doc ?>" />
					</div>

<input type="hidden" name="next" value="<?php echo $submit->next ?>">
<input type="hidden" name="postid" id="postid" value="<?php echo $submit->postid ?>">

	