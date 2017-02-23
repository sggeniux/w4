<?php
/*
Plugin Name: Follow post
Plugin URI: http://geniux.design
Description: Un plugin qui permet de suivre les posts WordPress
Version: 0.1
Author: Simon Gourfink
Author URI: http://geniux.design
License: GPL2
*/

define( 'FOLLOW_PATH','/wp-content/plugins/geniux-follow-post/' );

require( ABSPATH . WPINC . '/pluggable.php' );
require( ABSPATH . WPINC . '/pluggable-deprecated.php' );

require_once('follow_admin.php');
require_once('notif.php');

class Follow_Post{

	public $follow;

	public function __construct(){
		new Follow_Admin;		
	}

	public function followButton($post,$user){

		global $wpdb;
		$is_follow = $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'follows WHERE `post`='.$post.' AND `user`='.$user);

		if( $is_follow === '0' ){
			if( $user !== 0 ){
				$button = '<span class="follow_post"><a data-user="'.$user.'" data-post="'.$post.'" class="follow_btn" href="#">'.__("Suivre ce projet").'</a></span>';				
			}
		}else{
			if( $user !== 0 ){
				$delete = '<a class="unfollow" data-user="'.$user.'" data-post="'.$post.'" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>';
				$button = '<span class="follow_post">'.__("Vous suivez ce projet").' '.$delete.'</span>';
			}
		}
		return $button;
	}

	public function save(){
		global $wpdb;
		$now = new DateTime;
		if( $wpdb->insert($wpdb->prefix.'follows',array('id' => NULL,'user' => $_REQUEST["user"],'post' => $_REQUEST["post"], 'state' => 1 ,'time' => $now->format('Y-m-d H:i:s')) ) ){
			return true;			
		}else{
			return false;
		}
	}

	public function delete(){
		global $wpdb;
		if( $wpdb->delete( $wpdb->prefix.'follows', array( 'user' => $_REQUEST["user"],'post' => $_REQUEST["post"] ) )  ){
			return true;
		}else{
			return false;
		}
		
		$now = new DateTime;
		if( $wpdb->insert($wpdb->prefix.'follows',array('id' => NULL,'user' => $_REQUEST["user"],'post' => $_REQUEST["post"], 'state' => 0 ,'time' => $now->format('Y-m-d H:i:s')) ) ){
			return true;			
		}else{
			return false;
		}
	}

	public function get($u){
		global $wpdb;
		$notifs = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'follows WHERE `user`='.$u);
		return $notifs;
	}

}

function follow_script(){
	wp_enqueue_script( 'follow-script',FOLLOW_PATH.'js/follow.js' );
}
add_action( 'wp_print_scripts', 'follow_script', 100 );

function save_follow(){

	//if ( is_user_logged_in() ) {
		if( $_REQUEST["fn"] === 'save_follow' ){
			$foll = new Follow_Post();		
			if( $foll->save($_REQUEST["user"],$_REQUEST["post"]) === true ){
				$delete = '<a class="unfollow" data-user="'.$_REQUEST["user"].'" data-post="'.$_REQUEST["post"].'" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>';
				echo __('Vous suivez ce projet').' '.$delete;
			}
		}else
			if( $_REQUEST["fn"] === 'delete_follow' ){
			$foll = new Follow_Post();		
			if( $foll->delete($_REQUEST["user"],$_REQUEST["post"]) === true ){
				$button = '<a data-user="'.$_REQUEST["user"].'" data-post="'.$_REQUEST["post"].'" class="follow_btn" href="#">'.__("Suivre ce projet").'</a>';	
				echo $button;
			}
		}else
		if($_REQUEST["fn"] === 'notif_mark_lu'){
			$notif = new Notif();
			if( $notif->markLu($_REQUEST["user"],$_REQUEST["not"]) === true ){
				
			}
		}
		else
		if( $_REQUEST['fn'] === 'notif_count_lu' ){
			$notif = new Notif();
			if( $notif->count_lu() === true ){
				
			}
		}
	//}
}

add_action('wp_ajax_nopriv_do_ajax', 'save_follow');
add_action('wp_ajax_do_ajax', 'save_follow');







/* NOUVEAU TYPE DE CONTENU */

function create_notifs() {

	$labels = array(
		'name' => __( 'Notifs' ),
		'singular_name' => __( 'Notifs' ),
    	'add_new' => 'Add New Notif',
    	'add_new_item' => 'Add New Notif',
    	'edit_item' => 'Edit Notif',
    	'new_item' => 'New Notif',
    	'all_items' => 'All Notifs',
    	'view_item' => 'View Notif',
    	'search_items' => 'Search Notif',
    	'not_found' =>  'No Notifs Found',
    	'not_found_in_trash' => 'No Notifs found in Trash', 
    	'parent_item_colon' => '',
    	'menu_name' => 'Notifs',
    );

    $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'notifs'),
            'menu_position'       => 8,
            'menu_icon'           => 'dashicons-heart',
            'taxonomies'          => array( 'notifs' )
        );

    register_post_type( 'notifs', $args );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_notifs' );


function add_notifs_meta_boxes() {
	add_meta_box("notifs_contact_meta", "Notice &uArr;", "add_shortcode_notifs_meta_box", "notifs", "normal", "low");
}

function add_shortcode_notifs_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
	?>
	<div>
		<p>[USER] [PROJECT] [EVENT]</p>
	</div>
	<p>
		<h2>Shortcode pour ce type de notification:</h2>
		<input type="text" name="shortcode" value="<?= @$custom["shortcode"][0] ?>">
	</p>
	<?php
}

function save_notifs_custom_fields(){
  global $post;
 
  if ( $post )
  {
    update_post_meta($post->ID, "shortcode", @$_POST["shortcode"]);
  }
}

add_action( 'admin_init', 'add_notifs_meta_boxes' );
add_action( 'save_post', 'save_notifs_custom_fields' );

/* * * * * * * * * * * * * */







/* ENREGISTREMENT DES NOTIFICATIONS */

$notifications = new Notif();

$follow = new Follow_Post();