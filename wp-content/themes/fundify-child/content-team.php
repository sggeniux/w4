<?php
/**
 * The template part for displaying single teams posts
 *
 * @package WordPress
 * @subpackage Fundify Child
 * @since Fundify
 */

global $post;
global $teams;

$goals = get_post_meta($post->ID,'goals')[0];

$thumb = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="team_pic">
		<?php add_thickbox(); ?>

		<a href="#TB_inline?width=600&height=550&inlineId=change_thumb" class="thickbox">
		<?php echo $thumb ?>
		<span class="hide_on_out">Upload file</span>
		</a>

		<div id="change_thumb" style="display:none;">
			<form action="?action=change_image_team" id="change_image_team" method="POST" enctype="multipart/form-data">
				<fieldset>
                	<label><?php _e("Photo") ?></label>
                	<input type="file" id="image" name="image" accept='image/*' />
            	</fieldset>
            	<input type="hidden" name="team" id="team" value="<?php echo $post->ID ?>">
				<button type="submit"><?php _e('Uploader') ?></button>
			</form>
		</div>
	</div>

	<div class="entry-content">
	<h2><?php _e('Team description') ?></h2>
		<?php the_content();?>
		<a href="#TB_inline?width=600&height=550&inlineId=change_desc" class="thickbox">Modifier</a>
		<div id="change_desc" style="display:none;">
			<form action="?action=change_desc_team" id="formpost" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="postid" id="postid" value="<?php echo $post->ID ?>">
			  <fieldset>
                  <label for="postTitle"><?php _e("Nom de l'équipe") ?></label>
                  <input type="text" name="postTitle" id="postTitle" value="<?php echo $post->post_title ?>" />
              </fieldset>
           
              <fieldset>
                  <label for="postContent"><?php _e("Description de l'équipe") ?></label>
                  <textarea name="postContent" id="postContent" rows="10" cols="35" ><?php echo $post->post_content ?></textarea>
              </fieldset>

               <fieldset>
                <label><?php _e("Catégorie") ?></label>
                <select><option><?php _e("Choisissez une catégorie") ?></option></select>
              </fieldset>
              <button type="submit"><?php  _e("Enregistrer") ?></button>
			</form>
		</div>
	</div>

	<div class="goals">
	<h2><?php _e('Team goals') ?></h2>
		<p><?php echo $goals ?></p>
	</div>

	<div class="projects">
		<h2><?php _e('Team projects') ?></h2>
		<?php  get_template_part( 'content', 'team-project' ); ?>
	</div>

	<div class="members">
		<h2><?php _e('Team members') ?></h2>
		<?php get_template_part( 'content', 'team-member' ); ?>
	</div>

	<div class="team_activity">
		<h2><?php _e('Team activity') ?></h2>
		<?php get_template_part( 'content', 'team-activity' ); ?>
	</div>

</article>
<!-- #post-## -->
