<?php
/*
Plugin Name: Teams
Plugin URI: http://geniux.design
Description: Un plugin qui permet de créer des équipes
Version: 0.1
Author: Simon Gourfink
Author URI: http://geniux.design
License: GPL2
*/

define( 'TEAM_PATH','/wp-content/plugins/geniux-teams/' );

require( ABSPATH . WPINC . '/pluggable.php' );
require( ABSPATH . WPINC . '/pluggable-deprecated.php' );

require_once('content_type.php');
require_once('front.php');

class Teams{

	public function __construct(){

		if( $_GET['action'] === 'change_image_team' ){
			if( WPSubmitFromFront::uploadTeamImage($_FILES,$_POST["team"]) ){

			}
		}else
		if( $_GET['action'] === 'change_desc_team'){
			WPSubmitFromFront::changeTeamDesc($_POST["postid"]);
		}
		add_shortcode('MY_TEAMS', array($this, 'getTeams'));
	}

	public function getTeams(){
		$u = wp_get_current_user();
		?>
		<div class="my_teams">
			<?php foreach($this->getMyTeams($u) as $team): ?>
				<div>
					<a href="<?php echo get_permalink($team->ID) ?>"><?php echo $team->post_title ?></a>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function getMyTeams($user){

		global $wpdb;
		$teams = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_type`="teams" AND `post_author`='.$user->ID.' AND `post_status`="publish" ORDER BY `id` DESC ');
		return $teams;
	}

	public function add_project_to_team_first(){
		$u = wp_get_current_user();
		global $wpdb;
		$teams = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_type`="teams" AND `post_author`='.$u->ID.' AND `post_status`="publish" ORDER BY `id` DESC ');
		$option = '<select name="team">';
		$option .= '<option value="null">'.__('Select your team').'</option>';
		foreach($teams as $team){
			$option .= '<option value="'.$team->ID.'">'.$team->post_title.'</option>';
		}
		$option .= '</select>';
		return $option;
	}

	public function add_to_team($dt){
		$u = wp_get_current_user();		
		$prev_value = json_decode(get_post_meta( $dt["team"],'projects')[0]);
		$now = new DateTime();
		if( $prev_value !== NULL){
			array_push($prev_value,array($dt["project"],$now->format('Y-m-d H:i:s')) );			
		}else{
			$prev_value = array('0' => array($dt["project"],$now->format('Y-m-d H:i:s')) );
		}
		$prev_value = array_unique($prev_value,SORT_REGULAR);
		array_filter($prev_value, function($value) { return $value !== ''; });
		$meta_value = json_encode($prev_value);
		update_post_meta($dt["team"], 'projects', $meta_value);
		return '<p>'.__("Ce projet est déjà dans l'une de vos équipes ").'</p>';
	}

	public function isInMyTeam($id){
		global $wpdb;
		$u = wp_get_current_user();
		$teams = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_type`="teams" AND `post_author`='.$u->ID.' AND `post_status`="publish" ORDER BY `id` DESC ');
		$ret = 0;
		foreach($teams as $team){

			if( !is_array( get_post_meta( $team->ID,'projects')[0] ) ){
				$team_meta = json_decode(get_post_meta( $team->ID,'projects')[0]);	
				if($team_meta !== NULL ){
					foreach($team_meta as $teammetateam){
						if($id === intval($teammetateam)){
							$ret++;
						}
					}					
				}
			}else{
				$team_meta = get_post_meta( $team->ID,'projects')[0];
				if($id === $team_meta){
					$ret++;	
				}
			}
		}

			if( $ret > 0 ){
				return true;
			}else{
				return false;
			}
	}

	public function delete_from_team($team,$projet){
		$pinteam = json_decode(get_post_meta( $team,'projects')[0]);
		foreach($pinteam as $key => $te){
			if( $te[0] === $projet){
				unset($pinteam[$key]);
			}
		}
		$pinteam = json_encode($pinteam);
		update_post_meta($team, 'projects', $pinteam);	
	}

	public function delete_u_fr_team($team,$user){
		$pinteam = json_decode(get_post_meta( $team,'members')[0]);
		foreach($pinteam as $key => $te){
			if( $te[0] === $user){
				unset($pinteam[$key]);
			}
		}
		$pinteam = json_encode($pinteam);
		update_post_meta($team, 'members', $pinteam);
	}


	public function search_users($search,$team){
		global $wpdb;
		$users =  $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE  (`user_nicename` LIKE "%'.$search.'%"  OR `user_login` LIKE "%'.$search.'%"  OR `user_email` LIKE "%'.$search.'%")  AND `user_status`=0 ORDER BY `id` DESC ');
		$li = '<ul>';
		foreach($users as $user){
			$li .= '<li><a onclick="javascript:add_user_to_team('.$team.','.$user->ID.');">'.$user->user_nicename.'</a><li>';
		}
		$li .= '</ul>';
		return $li;
	}

	public function add_u_to_team($team,$user){	
		$prev_value = json_decode(get_post_meta( $team,'members')[0]);
		$now = new DateTime();
		if( $prev_value !== NULL){
			array_push($prev_value,array($user,$now->format('Y-m-d H:i:s')) );			
		}else{
			$prev_value = array('0' => array($user,$now->format('Y-m-d H:i:s')) );
		}
		
		$prev_value = array_unique($prev_value,SORT_REGULAR);
		array_filter($prev_value, function($value) { return $value !== ''; });
		$meta_value = json_encode($prev_value);
		update_post_meta($team, 'members', $meta_value);
		//var_dump($meta_value);
		$li = '';
		foreach($prev_value as $us){
			$userlog = get_user_by('ID',$us[0]);
			$li .='<li id="'.$userlog->ID.'">'.$userlog->user_login.'<button type="button" data-team="'.$team.'" data-user="'.$userlog->ID.'" class="delete_u_fr_team">'. __('Retirer').'</button></li>';
		}
		return $li;
	}


	public function date_compare($a, $b){
	    $t1 = strtotime($a['time']);
	    $t2 = strtotime($b['time']);
	    return $t1 - $t2;
	}    


	public function getFollows($uid,$pid = NULL){
		global $wpdb;		
		$pid = implode(',',$pid);
		$folows = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'follows WHERE `post` IN('.$pid.') ORDER BY `time` ASC ');
		$return = array();
		foreach($folows as $fol){
			$return[] = array('event' => 'follow' ,'user' => $fol->user ,'pid' => $fol->post, 'time' => $fol->time);
		}
		foreach($uid as $user){
			$user = get_user_by('ID',$user);
			$return[] = array('event' => 'register' , 'user' => $user->ID  , 'uid' => $user->ID , 'time' => $user->user_registered);
		}

		usort($return, array($this, 'date_compare'));
		$return = array_reverse($return);
		return $return;
	}


	public function getActivity($us,$pr){
		$activity = $this->getFollows($us,$pr);
		return $activity;
	}

	

	

}




function ajax_actions(){
	switch ($_REQUEST['fn']) {
		case 'search_user':
			echo Teams::search_users($_REQUEST['search'],$_REQUEST['team']);
		break;
		case 'add_to_team_sel_first':
			echo Teams::add_project_to_team_first();
		break;
		case 'delete_from_team':
			echo Teams::delete_from_team($_REQUEST['team'],$_REQUEST['project']);
		break;
		case 'delete_u_fr_team':
			echo Teams::delete_u_fr_team($_REQUEST['team'],$_REQUEST['user']);
		break;
		case 'add_to_team' :
			$datas = explode('&',$_REQUEST["datas"]);
			$data = array();
			foreach($datas as $dt){
				$dt = explode('=', $dt);
				$data[$dt[0]] = $dt[1];
			}
			echo Teams::add_to_team($data);
		break;
		case 'add_u_to_team':
			echo Teams::add_u_to_team($_REQUEST['team'],$_REQUEST['user']);
		break;
		default:
			echo '';
		break;
	}
}

add_action('wp_ajax_nopriv_do_ajax', 'ajax_actions');
add_action('wp_ajax_do_ajax', 'ajax_actions');


add_action( 'wp_print_scripts', 'add_script', 100 );
function add_script(){
	wp_enqueue_script( 'teams-script',TEAM_PATH.'js/team.js' );
}



/* * * * * * * * * * * * * */

$wpSubmitFromFEObj = new WPSubmitFromFront(); 
$teams = new Teams();