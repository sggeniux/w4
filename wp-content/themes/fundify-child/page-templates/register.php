<?php
/**
 * Template Name: Registration
 *
 * @package Fundify
 * @since Fundify 1.0
 */

if( !empty(get_current_user_id()) ){
	wp_redirect( '/register-thanks');
	exit;
}


get_header();
?>

	<div id="content">
		<div class="container">

		<div class="global_alertbox">
			<div>
				<span class="payment-errors"></span>
			</div>
		</div>
		
			<div class="registration_content">

			<div class="memberdeck">

			

				<div class="register_title">
					<p><?php _e('Rejoignez W4<br/> et participez à l’émancipation des femmes','fundify') ?></p>
				</div>

				<form action="" method="POST" id="payment-form" name="reg-form" data-regkey="">
					<h1 class="registration_page"><?php _e('Inscription') ?></h1>
				<div id="logged-input" class="no">

				<div class="form-row form_50">
					<input placeholder="<?php _e('First Name') ?>" type="text" size="20" class="first-name required" name="first-name" value="">
				</div>
				<div class="form-row form_50">
					<input placeholder="<?php _e('Last Name') ?>" type="text" size="20" class="last-name required" name="last-name" value="">
				</div>


<!--
				<div id="registration-form-extra-fields" class="form-row avatar_upload">
					<label><?php // _e('Avatar') ?></label>
					<input type="file" class="idc_avatar_file" id="idc_avatar_file" name="idc_avatar_file" value="">
					<input type="hidden" name="idc_avatar" id="idc_avatar" value="">
					<div class="avatar_preview"></div>

				</div>

				<div class="form-row">
					<label><?php // _e('ou choisir un avatar') ?></label>
					<?php /* foreach(getDefaultsAvatars() as $img): ?>
					<div class="choose_default_avatar">
						<label>
						<img src="<?php echo $img ?>">
						<input class="default_avatar" value="<?php echo $img ?>"  type="radio" name="default_avatar">
						</label>
					</div>
				<?php endforeach; */ ?>
					<div class="clearfix"></div>
				</div>
-->

				<div class="form-row">
					<input placeholder="<?php _e('Email Address') ?>" type="email" size="20" class="email required" data-alertmail="<?php _e("Ceci n'est pas une adresse mail","fundify") ?>" name="email" value="">
				</div>

				<div class="form-row">
					<input placeholder="<?php _e('Password') ?>" type="password" size="20" class="pw required" name="pw">
				</div>
				<div class="form-row">
					<input placeholder="<?php _e('Re-enter Password') ?>" type="password" size="20" class="cpw required" name="cpw">
				</div>



				<div id="registration-form-extra-fields" class="form-row">
					<select id="how_di_you_know" name="how_di_you_know" onchange="input_autr()">
						<option value="null"><?php _e('How did you know W4') ?></option>
						<option value="<?php _e('En cherchant sur Google') ?>"><?php _e('En cherchant sur Google') ?></option>
						<option value="<?php _e('Campagne publicitaire offline') ?>"><?php _e('Campagne publicitaire offline') ?></option>
						<option value="Other"><?php _e('Autre') ?></option>
					</select>
					<input type="text" id="autre_reponse" size="20" placeholder="<?php _e('How did you know W4') ?>" name="autre_reponse" class="autre_reponse input hide" value="" />
				</div>

				<script type="text/javascript">
				function input_autr(){
						if( jQuery('#how_di_you_know').val() === 'Other' ){
							jQuery('#autre_reponse').removeClass('hide');
						}else{
							jQuery('#autre_reponse').addClass('hide');
						}
				}
				</script>

			<!-- ///////////////// -->
			<!-- CUSTOMS W4 FIELDS -->
			<!-- ///////////////// -->

<!--
				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Pseudo') ?></label>
					<input type="text" size="20" class="pseudo" name="pseudo" value="">
				</div>


				<div id="registration-form-extra-fields" class="form-row">
					<label><?php // _e('Postal Adress') ?></label>
					<input type="text" size="20" class="adresse" name="adresse" value="">
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Zip code') ?></label>
					<input type="text" size="20" class="code_postal" name="code_postal" value="">
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('City') ?></label>
					<input type="text" size="20" class="city" name="city" value="">
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Phone number') ?></label>
					<input type="text" size="20" class="phone" name="phone" value="">
				</div>



				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('About you') ?></label>
					<textarea class="aboutyou" name="aboutyou" ></textarea>
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Presentation') ?></label>
					<textarea class="description" name="description" ></textarea>
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Nationality') ?></label>
					<select class="nationality" name="nationality" >
					<?php //echo get_users_select('nationality'); ?>
					</select>
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Birthday') ?></label>
					<input type="text" size="20" class="birthday" name="birthday" value="">
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('How did you know W4') ?></label>
					<textarea class="stat_field" name="stat_field" ></textarea>
				</div>

				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Language') ?></label>
					<select class="language" name="language" >
					<?php //echo get_users_select('language'); ?>
					</select>
				</div>


				<div id="registration-form-extra-fields" class="form-row">
					<label><?php //_e('Currency') ?></label>
					<select class="currency" name="currency" >
						<?php // echo get_users_select('currency'); ?>
					</select>
				</div>

				-->

			<!-- ///////////////// -->
			<!-- CUSTOMS W4 FIELDS -->
			<!-- ///////////////// -->


				
				<div id="registration-form-extra-fields"></div>

				</div>
				
				


					<button type="submit" id="id-reg-submit" class="submit-button"><?php _e('Register') ?></button>

					<div class="social_connect">
						<p class="ou"><span><?php _e('ou','fundify'); ?></span></p>
						<?php do_action('oa_social_login'); ?>
						<?php //do_action( 'wordpress_social_login' ); ?>
					</div>

					<div class="allready_registered">
						<p><?php _e('Déjà inscrit ?','fundify'); ?> <a href="/login-page/"><?php _e('Se connecter','fundify'); ?></a></p>
					</div>

				</form>
			</div>




			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>