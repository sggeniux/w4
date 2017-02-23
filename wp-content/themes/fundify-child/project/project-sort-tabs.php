<?php
/**
 * Campaign tabs.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $campaign;
global $post;
$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);
if (class_exists('ID_Member')) {
	$durl = md_get_durl();
}

$auteur = get_the_author_meta('login');

$project_single = new Project_Single($post->ID);
?>

<div class="sort-tabs campaign">
	<ul>
		<?php do_action( 'fundify_campaign_tabs_before', $project_id ); ?>

		<li class="active">
			<a href="#description" class="campaign-view-descrption tabber"><?php _e( 'Overview', 'fundify' ); ?></a>
		</li>

		<?php /* //if ( !empty($faq) ) : ?>
		<li><a href="#faq" class="tabber"><?php _e( 'FAQ', 'fundify' ); ?></a></li>
		<?php endif;*/ ?>

		<?php //if ( !empty($updates) ) : ?>
		
		<li><a href="#updates" class="tabber"><?php _e( 'Updates', 'fundify' ); ?></a></li>
		<?php //endif; ?>
		
		<?php if (comments_open()) { ?>
		<li class="comments_tab_btn"><a href="#comments" class="tabber"><?php _e( 'Comments', 'fundify' ); ?> <span class="sort_tbl_nbr">(<?php echo get_comments_number() ?>)</span> </a></li>
		<?php } ?>
		<li><a href="#backers" class="tabber"><?php _e( 'Backers', 'fundify' ); ?> <span class="sort_tbl_nbr">(<?php echo count($project_single->get_backers($project_id)) ?>)</span></a></li>
		<?php if ( get_current_user_id() == $post->post_author || current_user_can( 'manage_options' ) && isset($durl)) : ?>
		<!-- <li><a href="<?php //echo $durl.'/?edit_project='.$post_id; ?>"><?php // _e( 'Edit Campaign', 'fundify' ); ?></a></li> -->
		<li><a href="<?php echo $durl.'creer-un-projet/?postid='.$post_id; ?>"><?php  _e( 'Edit Campaign', 'fundify' ); ?></a></li>
		<?php endif; ?>

		<li><a href="messages/?fepaction=newmessage&to=<?php echo $auteur ?>&message_title=<?php  _e( 'Message about ' ) ?><?php echo $post->post_title ?>"><?php  _e( 'Contacter' ) ?></a></li>

		<?php do_action( 'fundify_campaign_tabs_after', $campaign ); ?>
	</ul>
</div>