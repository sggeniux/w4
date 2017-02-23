<?php


global $follow;
global $notifications;
$user = wp_get_current_user();


/*
	$flux = array();
	$i = 0;
	foreach($follow->get($user->ID) as $notif): 
	$post = get_post($notif->post);
		foreach($notifications->get($notif->post) as $message):
			$post_not = $notifications->getmessage($message->event);
			$message = $notifications->replaceTags($post_not->posts,$message->event,$message->user,$message->amount,$message->time,$post->post_title,$notif->post);
			$flux[$i] = $message;
			$i++;
		endforeach;
	endforeach;

usort($flux,'sort_by_time');
compareCookies($flux);
*/