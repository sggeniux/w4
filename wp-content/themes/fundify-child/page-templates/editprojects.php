<?php
/**
 * Template Name: Edit project
 *
 * @package Fundify
 * @since Fundify 1.0
 */

global $submit;


get_header();

//$editor_settings = array( 'media_buttons' => false, 'textarea_rows' => '3','formats' => false,'plugins' => false ,'quicktags' => array( 'buttons' => 'strong,em,li,link','block_formats' => ','  ) );
$editor_settings = array('media_buttons' => false,'textarea_rows' => '3',  'quicktags' => false);

if( !empty($submit->postid) ){
	$post_id_url = '&postid='.$submit->postid ;
}else{
	$post_id_url = '';
}

$projecteditpage = 1;
$contanier_class = '';

?>
	<div id="content">


		<?php if( $submit->post->post_status !== 'ready' ): ?>
			<?php require_once('steps/header_edit.php') ?>
		<?php else: ?>
			<?php $contanier_class = ' bravo_container '; ?>
		<?php endif; ?>

		<div class="container edition_form_page <?php echo $contanier_class ?>">

			<!-- <h1 class="project_edit_page"><?php // _e('Créer un projet') ?></h1> -->
			
			<div class="project_edit_content row">

			<?php if( $submit->post->post_status === 'ready' ): ?>

				<div class="bravo col-sm-12">
				<?php echo $submit->bravo->post_content ?>
					<div class="bravo_buttons publish_buttons">
						<ul class="list-inline">
							<li><button class="btn btn-success" onclick="change_state(<?php echo $submit->post->ID ?>,'publish')"><?php _e('Mettre en ligne', 'fundify') ?></button></li>
							<li><a class="btn btn-info" href="/my-projects/"  ><?php _e('Mettre en ligne plus tard', 'fundify') ?></a></li>
						</ul>
					</div>
				</div>

			<?php else: ?>

			<div class="col-sm-9">
				<ul class="list-inline language_choice">
					<li><a href=""><?php _e('French') ?></a></li>
					<li><a href=""><?php _e('English') ?></a></li>
				</ul>
				<form id="projects_form" method="POST" action="?next=<?php echo $submit->next ?><?php echo $post_id_url ?>" enctype="multipart/form-data">
				
					<?php require_once('steps/step_'.$submit->curent.'.php') ?>
					<p class="center-text bottom_buttons">

					<?php if($submit->curent !== '1'): ?>
						<a class="prev_button buttons_endsform" href="?postid=<?php echo $submit->postid ?>&next=<?php echo $submit->prev ?>"> <i class="fa fa-arrow-left" aria-hidden="true"></i> <?php _e('PREV', 'fundify') ?></a>
					<?php endif; ?>

						<button class="btn btn-success submit_edit buttons_endsform" name="submitForm" type="submit"><?php _e('NEXT_AND_SAVE', 'fundify') ?></button>
					</p>
					<input type="hidden" name="post_type" value="ignition_product">
					<?php wp_nonce_field( 'submit_new_project' , 'nonce_field_for_submit_new_project'); ?>
				</form>
					<?php if(($submit->curent === 1 ) &&  empty($submit->postid) ){$choosecf = '';}else{$choosecf = 'hide';} ?>

					<div class="choose_category_first <?php echo $choosecf; ?>">
						<p>Choisissez un type de projet...</p>
						<h1><a href="javascript:choose_category('10','<?php _e('Projet solidaire') ?>')"><?php _e('Projet solidaire', 'fundify') ?></a></h1>
						<h1><a href="javascript:choose_category('9','<?php _e('Crowdfunding') ?>')"><?php _e('Crowdfunding', 'fundify') ?></a></h1>
					</div>

				</div>				
				<div class="col-sm-3">
						<div id="guide" class="guide">
							<?php echo wpautop($submit->guide->post_content); ?>
						</div>
				</div>

				<?php endif; ?>

				


				<?php add_thickbox(); ?>
				<div id="alert_form" style="display:none;">
				     <p></p>
				</div>
				<a href="#TB_inline?width=600&height=600&inlineId=alert_form" class="thickbox"></a>

				<div id="alert_complete_form" style="display:none;">
				     <p><?php _e("Certaines modifications n'ont pas été enregistrées, veuillez sauvegarder vos modifications pour continuer") ?></p>
				     <ul class="list-inline text-center">
				     	<li><a class="btn btn-success" href="javascript:submitedit();"><?php _e('SAVE', 'fundify') ?></a></li>
				     </ul>
				</div>
				<a href="#TB_inline?width=600&height=550&inlineId=alert_complete_form" class="thickbox"></a>

				<div id="alert_complete_fields" style="display:none;">
				     <p><?php _e('Veuillez compléter les champs obligatoires','fundify') ?></p>
				     <ul class="list-inline text-center">
				     	<li><a class="btn btn-success" href="javascript:tb_remove();"><?php _e('Okay','fundify') ?></a></li>
				     </ul>
				</div>
				<a  href="#TB_inline?width=600&height=550&inlineId=alert_complete_fields" class="thickbox"></a>

				<div id="add_team_member" style="display:none;">
					<?php require_once('steps/add_team_member.php') ?>
				</div>

				<?php if( $submit->post->post_status === 'publish' && $submit->curent === 1 ): ?>
				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#alert_form').find('p').html('<?php _e("ATTENTION : Votre projet est actuellement en ligne. Si vous le modifiez, il devra à nouveau être soumis à relecture.") ?>');
					setTimeout(function(){ 
						tb_show('<?php _e("Projet déja en ligne") ?>','#TB_inline?width=600&height=150&inlineId=alert_form');
					}, 10);
				});
				</script>
				<?	endif;	?>

			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>