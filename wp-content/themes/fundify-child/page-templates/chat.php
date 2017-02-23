<?php
/**
 * Template Name: Chat
 *
 * @package Fundify
 * @since Fundify 1.0
 */
global $wp;
global $post;
global $gchat;

$action = home_url(add_query_arg(array(),$wp->request));

get_header();

$first = $gchat->discussions()[0]->id;
?>

	<div class="chat_page" id="content">
		<div class="container">

		<h1 class="chat_page_title"><?php _e('Chat') ?></h1>
			<div class="chat_content">
					<ul class="flux"></ul>
					<div class="tools">
						<form id="chat_form" methode="GET" action="<?php echo $action ?>" enctype="multipart/form-data">
							<input type="hidden" name="last" id="last" value="0">
							<input type="hidden" name="gcaction" value="save_mess">
							<input type="hidden" id="discuss" name="discuss" value="<?php echo $first ?>" />
							<input type="hidden" name="owner" value="<?php echo $user->ID ?>" />
							<textarea name="message"></textarea>
							<button type="button">Envoyer</button>
						</form>
					</div>
			</div>
			<div class="discussions">
			<a href="#TB_inline?width=600&height=550&inlineId=disc_form" class="create thickbox">Créer une discussion</a>
				<ul class="disc">
				<?php foreach($gchat->discussions() as $disc): ?>
					<li><a href="javascript:open_discus(<?php echo $disc->id ?>)"><?php echo $gchat->title($disc->users) ?></a></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->


	<?php add_thickbox(); ?>
<div id="disc_form" class="hide">
<form action="?gcaction=create_discus" id="discuss" method="POST" enctype="multipart/form-data" >
	<label>Membres</label>
	<input type="text" name="users" value="">
	<button>Créer</button>
</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		open_discus(<?php echo $first ?>);
	});
</script>

<?php get_footer(); ?>