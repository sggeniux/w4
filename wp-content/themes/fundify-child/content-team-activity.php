<?php

global $post;
global $teams;


if( !is_array( get_post_meta($post->ID,'members')[0] ) ){
	$members = json_decode(get_post_meta($post->ID,'members')[0]);
}else{
	$members = get_post_meta($post->ID,'members')[0];
}

if( !is_array( get_post_meta($post->ID,'projects')[0] ) ){
	$projects = json_decode(get_post_meta($post->ID,'projects')[0]);
}else{
	$projects = get_post_meta($post->ID,'projects')[0];
}

$team = new Teams;

$activitys = $team->getActivity($members,$projects);

?>

<div class="activity_content">
	<ul>
		<?php foreach($activitys as $activity): ?>
			<?php 
				$member = get_user_by('ID',$activity["user"]);
				$datetime = new DateTime($activity["time"]);
				$date = $datetime->format('d M Y');
				$time = $datetime->format('H:i:s');
			?>
			<li>
				<div>
					<strong><?php echo $date; ?></strong><br/>
					<p><?php echo $time; ?></p>
					<p><?php echo $member->user_nicename; ?></p>
					<p><?php echo _e($activity["event"]); ?></p>
				</div>
			</li>
			<li class="separator"></li>
		<?php endforeach; ?>
	</ul>
</div>

