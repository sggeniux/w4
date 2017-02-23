<?php
/**
 * Campaign meta.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $post, $campaign;
$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);
if ($project_id > 0) {
	$project = new ID_Project($project_id);
	$end_type = get_post_meta($post_id, 'ign_end_type', true);
}

$end_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'ign_fund_end', true) ) );


$project_single = new Project_Single($post->ID);

?>

<!--
<div class="date_now post-meta campaign-meta">
	<div class="date">
		<i class="icon-calendar"></i>
		<?php // echo $now->format('M d') ?>
	</div>
</div>
-->
<?php do_action( 'fundify_campaign_meta_before' ); ?>

<div class="post-meta campaign-meta">

	<?php if ( $end_type == 'closed' ) : ?>
	<div class="funding-ends">
		<!-- <i class="icon-clock"></i> -->
		<?php printf( __( 'Funding Ends: %s', 'fundify' ), get_post_meta($post->ID, 'ign_fund_end', true) ); ?>
	</div>
	<?php endif; ?>

</div>

<?php
	 foreach($project_single->getAllUpdates($post->ID) as $update):
		$actu_date = new DateTime($update['date_actu']);
?>
	<div class="post-meta campaign-meta <?php echo $update['class'] ?>">
		<div class="date">
		<!-- <i class="icon-calendar"></i> -->
		<?php echo $actu_date->format('M d') ?> <?php // echo $update['actu'] ?>			
		</div>
		<?php if(!empty($update['actu'])): ?>
		<div class="actu_description">
			<?php echo $update['actu'] ?>
		</div>
	<?php endif; ?>
		<div class="separator">
			<hr/>
		</div>

	</div>
<?php endforeach; ?>

<div class="post-meta campaign-meta">
	<div class="separator to_top">
			<hr/>
	</div>
	<div class="date">
		<!-- <i class="icon-calendar"></i>-->
		<?php echo get_the_date(); ?><br/>
		<?php _e( 'Lancement de la campagne', 'fundify' ) ?>
		<?php //printf( __( 'Launched: %s', 'fundify' ), get_the_date() ); ?>
	</div>

	<?php /* //if ( $campaign->location() ) : ?>
	<div class="location">
		<i class="icon-compass"></i>
		<?php echo get_post_meta($post_id, 'ign_company_location', true); ?>
	</div>
	<?php //endif; */ ?>

</div>

<?php do_action( 'fundify_campaign_meta_after' ); ?>