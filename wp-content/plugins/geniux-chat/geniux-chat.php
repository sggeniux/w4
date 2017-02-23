<?php
/*
Plugin Name: Gchat
Plugin URI: http://geniux.design
Description: Un plugin qui permet de discuter
Version: 0.1
Author: Simon Gourfink
Author URI: http://geniux.design
License: GPL2
*/

define( 'CHAT_PATH','/wp-content/plugins/geniux-chat/' );

require( ABSPATH . WPINC . '/pluggable.php' );
require( ABSPATH . WPINC . '/pluggable-deprecated.php' );


class Gchat{

public $user = NULL;


	public function __construct(){

		$this->user = wp_get_current_user()->ID;

		switch ($_GET["gcaction"]) {
			case 'create_discus':
				$this->create_discus();
			break;
			case 'save_mess':
				$this->save_mess();
			break;			
			default:
			break;
		}
		$this->discussions();

	}


	public function discussions(){
		global $wpdb;
		$discuss = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'chat_discus WHERE `owner`='.$this->user.' ORDER BY `creation_date` DESC ');
		return $discuss;
	}

	public function save_mess(){
		global $wpdb;
		if( !empty($_GET["message"]) && !empty($_GET["discuss"]) ){
			$now = new DateTime;
			$wpdb->insert( $wpdb->prefix.'chat_mess', array( 'id' => NULL, 'discuss' => $_GET["discuss"], 'owner' => $this->user, 'message' => $_GET["message"], 'creation_date' => $now->format('Y-m-d H:i:s') ) );
		}
	}

	public function messages(){
		global $wpdb;
		
		$disc = $_REQUEST['disc'];

		var_dump($_REQUEST);

		if( $_REQUEST['last'] !== 0 ){
			$last = new DateTime($_REQUEST['last']);			
			$wheredate = ' AND (`creation_date` >= '.$last->format('Y-m-d H:i:s').')';
		}else{
			$last = new DateTime();
			$wheredate = '  ';
		}
		$messages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'chat_mess WHERE `discuss`='.$disc.' '.$wheredate.'  ORDER BY `creation_date` ASC ');
		return json_encode($messages);
	}

	public function create_discus(){
		global $wpdb;
		if( !empty($_POST["users"]) ){
			$now = new DateTime;
			$wpdb->insert( $wpdb->prefix.'chat_discus', array( 'id' => NULL, 'owner' => $this->user, 'users' => $_POST["users"], 'state' => 1, 'creation_date' => $now->format('Y-m-d H:i:s') ) );
		}
	}

	public function delete_discuss(){

	}

	public function title($users){
		$users = explode(',',$users);
		$title = '';
		foreach($users as $user){
			$name = get_user_by('ID',$user);
			$title .= $name->user_nicename.',';
		}
		return $title;
	}

}


function chat_ajax_actions(){
	switch ($_REQUEST['fn']) {
		case 'messages':
			$_GET = $_REQUEST['form'];
			echo Gchat::messages();
		break;
		case 'save_mess':
			$_GET = $_REQUEST['form'];
			echo Gchat::save_mess();
		break;
		default:
			echo '';
		break;
	}
}

add_action('wp_ajax_nopriv_do_ajax', 'chat_ajax_actions');
add_action('wp_ajax_do_ajax', 'chat_ajax_actions');


add_action( 'wp_print_scripts', 'chat_add_script', 100 );
function chat_add_script(){
	wp_enqueue_script( 'chat-script',CHAT_PATH.'js/chat.js' );
}

$gchat = new Gchat;