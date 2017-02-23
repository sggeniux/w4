<?php


Class Notif{

	public $count;


	public function __construct(){

	}


	public function save($type,$event,$project,$amount){
		$user = wp_get_current_user();
		$now = new DateTime;
		global $wpdb;
		$wpdb->insert($wpdb->prefix.'notifs',array('id' => NULL,'event' => $event,'project' => $project,'amount' => $amount,'user' => $user->ID,'time' => $now->format('Y-m-d H:i:s')) );
	}

	public function get($p){
		global $wpdb;
		$notifs = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'notifs WHERE `project`='.$p);
		return $notifs;
	}

	public function getmessage($type){

			$meta_query = array(
		    	'relation' => 'AND',
	            array(
	                'key'     => 'shortcode',
	                'value'   => $type,
	                'compare' => '=',
	            )
			);

			$wp_query = new WP_Query( array(
				'post_type'  => 'notifs',
				'paged' 	 => ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ),
				'meta_query' => $meta_query,
			) );

			return $wp_query;
	}

	public function replaceTags($post,$event,$user,$amount,$time,$projet,$pid){

		/* ////// [USER] [PROJECT] [EVENT] */
		$event = __($event);
		$user = get_user_by('ID',$user);
		$texte = $post[0]->post_content;
		$texte = str_replace('[USER]', $user->display_name,$texte);
		$texte = str_replace('[PROJECT]', $projet,$texte);
		$texte = str_replace('[EVENT]', $event,$texte);

		/* TITLE */
		$title = str_replace('[USER]', $user->display_name, $post[0]->post_title);
		$title = str_replace('[PROJECT]',  $projet, $title);
		$title = str_replace('[EVENT]', $event, $title);

		$the_time = new DateTime($time);
		//$time = $the_time->format('d-m-Y H:i:s');
		$notif_id = $pid.'_'.$the_time->format('Y_m_d_h_i_s');
		$link = get_permalink($pid);

		$message = array('title' =>  $title,'text' => $texte,'link' => $link,'notif_id' => $notif_id ,'time' => $time);

		return $message;
	}

	public function checkNotifVu($notif_id,$user){

		global $wpdb;
		$vu = $wpdb->get_var('SELECT `state` FROM '.$wpdb->prefix.'notif_user WHERE `notif_id`="'.$notif_id.'" AND `userid`='.$user);
		if( $vu === '1'){
			return true;
		}else{
			return false;
		}
	}

	public function markLu($user,$notif_id){
		global $wpdb;
		
		$nb = $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'notif_user WHERE `notif_id`='.$notif_id.' AND `userid`='.$user.' AND `state`=1 ');
		if( intval($nb) < 1 ){
			$wpdb->insert($wpdb->prefix.'notif_user',array('id' => NULL,'notif_id' => $notif_id,'userid' => $user,'state'=>1));		
		}
	}

	public function count_lu(){
		global $wpdb;
		$user = wp_get_current_user();
		
		$nuVus = $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'notif_user WHERE `userid`='.$user->ID.' AND `state`=1 ');

		$folows = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'follows WHERE  `user`='.$user->ID.' AND `state`=1 ' );
		$count = 0;
		foreach($folows as $fo){
			$notifications = $this->get($fo->post);
			$count = $count + count($notifications);
		}
		if( ($count - $nuVus) >= 0 ){
			echo $count - $nuVus;			
		}else{
			echo 0;
		}
	}

}

/* GESTION ORDRE ET COOKIES */
function sort_by_time($a,$b){
	return strcmp($a['time'], $b['time']);
}

function add_class_to_nav_items($nav_items, $args) {
	$notifs_menu = get_option('follow_settings_notif_menu', array() );
	foreach($nav_items as $menu_item){
		if( $menu_item->ID === intval($notifs_menu) ){
			$menu_item->classes[] = 'is_notifs_menu';
		}
	}
  	return $nav_items;
}
add_filter( 'wp_nav_menu_objects', 'add_class_to_nav_items', 11,2 );