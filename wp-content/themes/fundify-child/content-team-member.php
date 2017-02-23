<?php

global $post;
global $teams;



if( !is_array( get_post_meta($post->ID,'members')[0] ) ){
	$members = json_decode(get_post_meta($post->ID,'members')[0]);
}else{
	$members = get_post_meta($post->ID,'members')[0];
}

//$members = get_post_meta($post->ID,'members')[0];

?>
<pre>
	<?php //var_dump([["1","2016-01-04 11:00:00"],["2","2016-01-04 10:00:00"]]); ?>
</pre>
<form class="add_user_to_team" id="add_user_to_team"  method="POST" enctype="multipart/form-data" >
	<input data-team="<?php echo $post->ID ?>" type="text" name="user_search" id="user_search" value="" />
	<div class="user_search_results hide">
		<p><?php _e('No results') ?></p>
	</div>
</form>

<ul class="users_team">
<?php 
	if($members !== NULL):
		foreach($members as $member): 
			
		$user = get_user_by('ID',$member[0]);
		$datetime = new DateTime($member[1]);
?>
		<li id="<?php echo $user->ID; ?>">
			<?php echo $user->user_login; ?> ( from <?php echo $datetime->format('d-m-Y H:i:s') ?>)
			<button type="button" data-team="<?php echo $post->ID ?>" data-user="<?php echo $user->ID ?>" class="delete_u_fr_team"><?php _e('Retirer') ?></button>
			<a href="messages/?fepaction=newmessage&to=<?php echo $user->user_login ?>&message_title=<?php  _e('Message for '.$user->user_login) ?>"><?php  _e( 'Contacter' ) ?></a>
		</li>
<?php
		endforeach;
	endif;
?>
</ul>