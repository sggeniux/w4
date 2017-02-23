<?php
/*
Plugin Name: User actions
Plugin URI: http://geniux.design
Description: Un plugin qui permet de montrer l'activité de l'utilisateur
Version: 0.1
Author: Simon Gourfink
Author URI: http://geniux.design
License: GPL2
*/

define( 'FOLLOW_PATH','/wp-content/plugins/geniux-user-actions/' );

require( ABSPATH . WPINC . '/pluggable.php' );
require( ABSPATH . WPINC . '/pluggable-deprecated.php' );

Class User_Actions{

	public $activity = NULL;

	public function __construct($user,$pid = NULL){
		if($pid === NULL){
			$this->getActivity($user);			
		}else{
			$this->getTeamActivity($user,$pid);
		}
	}

	public function getRegister($uid){
		return array('event' => 'register' , 'uid' => $uid->ID , 'time' => $uid->user_registered);
	}

	public function getFollows($uid,$pid = NULL){
		global $wpdb;		
		if( $pid !== NULL ){
			$whpid = ' `post`='.$pid.' ';
		}else{
			$whpid = ' `user`='.$uid->ID.' ';
		}

		$folows = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'follows WHERE '.$whpid.' ORDER BY `time` ASC ');
		$return = array();
		foreach($folows as $fol){
			$return[] = array('event' => 'follow' ,'pid' => $fol->post, 'time' => $fol->time);
		}
		return $return;
	}

	public function getProjects($uid){
		return NULL;	
	}

	public function getPayments($uid){
		return NULL;
	}

	public function sort_by_time($a,$b){
		return strcmp($a['time'], $b['time']);
	}

	public function getActivity($u){
		
		$actions = array();

		$actions[] = $this->getRegister($u);
		foreach($this->getFollows($u) as $ev){
			$actions[] = $ev;
		}
		//$actions[] = $this->getProjects($u);
		//$actions[] = $this->getPayments($u);
		usort($actions,'sort_by_time');
		$this->activity = $actions;
		return $this;
	}


}


$u = wp_get_current_user();
$useractivity = new User_Actions($u);