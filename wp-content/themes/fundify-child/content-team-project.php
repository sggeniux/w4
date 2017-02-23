<?php

global $post;
global $teams;


if( !is_array( get_post_meta($post->ID,'projects')[0] ) ){
	$projects = json_decode(get_post_meta($post->ID,'projects')[0]);
}else{
	$projects = get_post_meta($post->ID,'projects')[0];
}


?>
<ul class="p_in_team">
<?php if(($projects !== NULL) && !empty($projects) ): ?>
	<?php foreach($projects as $project): ?>
		<li id="<?php echo $project[0] ?>">
			<a href="<?php echo get_permalink($project[0]) ?>"><?php echo get_the_post_thumbnail($project[0]) ?></a>
			<a href="<?php echo get_permalink($project[0]) ?>"><?php echo get_post($project[0])->post_title ?></a>
			<button type="button" data-team="<?php echo $post->ID ?>" data-project="<?php echo $project[0] ?>" class="delete_from_team"><?php _e('Retirer') ?></button>
		</li>
	<?php endforeach; ?>

<?php endif; ?>
</ul>