<?php
/**
 * Template Name: Notifications
 *
 * @package Fundify
 * @since Fundify 1.0
 */
global $post;
global $follow;
global $notifications;
$user = wp_get_current_user();




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
$flux = array_reverse($flux,true);

get_header(); ?>

	<div id="content">
		<div class="container">

		<h1 class="notifications_page_title"><?php _e('Notifications') ?></h1>
			<div class="notifications_content">
				<?php
					foreach($flux as $item): 

					$time = new DateTime($item["time"]);

				
					if( $notifications->checkNotifVu($item['notif_id'],$user->ID) === true ){
						$class = 'vu';
					}else{
						$class = 'non-vu';
					}
				
				?>
				<div id="<?php echo $item['notif_id'] ?>" data-user="<?php echo $user->ID ?>" class="notification <?php echo $class ?>">
					<h2 class="notif_title"><?php echo $item["title"] ?></h2>
					<p class="text"><?php echo $item["text"] ?></p>
					<p><a href="<?php echo $item["link"] ?>"><?php _e('Voir le projet') ?></a></p>
					<p class="time"><?php echo $time->format('d-m-Y h:i:s') ?></p>
				</div>

				<?php endforeach; ?>		
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>