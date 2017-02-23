<?php
/**
 * Campaign sharing.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $campaign;
global $post;
global $backer_list;

$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);

$project_single = new Project_Single($post->ID);
?>

<div id="backers">

<h3><?php _e("Contributeurs") ?></h3>

	<ul class="ign_backer_list">
	<?php
	foreach($project_single->get_backers($project_id) as $key => $backer):
		$price = $backer['price'];
		$backer = $backer['infos'];
	?>
		<li class="backer_list_item backers_tab content_tab">
		<?php if( !empty(get_user_meta($backer->ID,'idc_avatar',true)) ) : ?>
			<div class="backer_list_avatar">
				<img src="<?php echo get_user_meta($backer->ID,'idc_avatar',true) ?>" />
			</div>
		<?php endif; ?>
			<?php if( get_user_meta($backer->ID, 'use_pseudo',true) === '1' ): ?>
				<p><?php echo get_user_meta($backer->ID,'pseudo',true) ?> <?php //echo $price; ?></p>
			<?php else: ?>
				<p><?php echo get_user_meta($backer->ID,'first_name',true) ?> <?php echo get_user_meta($backer->ID,'last_name',true) ?> <?php //echo $price; ?></p>
			<?php endif; ?>
		</li>
	<?php
	endforeach;
	/*
	if ($project_id > 0 && use_idc()) {
		mdid_backers_list($project_id);
	}*/
	?>
	</ul>
</div>