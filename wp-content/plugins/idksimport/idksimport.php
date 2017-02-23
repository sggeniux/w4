<?php

//error_reporting(E_ALL);
//@ini_set('display_errors', 1);

/*
Plugin Name: IgnitionDeck Kicksarter Import
URI: http://IgnitionDeck.com
Description: Quickly transfer raw Kickstarter campaign data into your new IgnitionDeck project to continue where your Kickstarter campaign left off. Also updates hourly so you can combine Kickstarter totals with IgnitionDeck totals using simultaneous campaigns.
Version: 1.0.6
Author: Virtuous Giant
Author URI: http://VirtuousGiant.com
License: GPL2
*/

//include_once 'idksimport-admin.php';

function idksimport_load_metabox_styles() {
	global $pagenow;
	if (isset($pagenow)) {
		if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
			//add_action('admin_enqueue_scripts', 'idksimport_metabox_styles');
			call_idksimport_metabox();
		}
	}
}

add_action ('admin_init', 'idksimport_load_metabox_styles');

function call_idksimport_metabox() {
	$metabox = new ID_KSImport_Metaboxes();
}

class ID_KSImport_Metaboxes {

	function __construct() {
		add_action('add_meta_boxes', array(&$this, 'generate_metabox'));
		add_action( 'save_post', array(&$this, 'save_metabox'));
	}

	function generate_metabox($post) {
		$post_types = get_post_types();
		//$function = ID_Member_Level::generate_metabox();
		$types = array('ignition_product');
		foreach($types as $type) {
			add_meta_box('ks_import', 'Kickstarter Import', array(&$this, 'render_metabox'), $type, 'normal', 'high');
		}
	}

	function render_metabox($post) {
		// Use nonce for verification
  		wp_nonce_field('idksimport', 'idksimport_metabox');
  		$ksurl = get_post_meta($post->ID, 'ksurl', true);
  		include DIRNAME(__FILE__).'/templates/admin/metaboxContent.php';
	}

	function save_metabox($post_id) {
		// First we need to check if the current user is authorized to do this action. 
		if (isset($_POST)) {
			if ( isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		    	if ( !current_user_can( 'edit_page', array($post_id) ) ) {
		    		return;
		    	}
		  	} else {
		   		 if ( !current_user_can( 'edit_post', array($post_id) ) ) {
		        	return;
		    	}
		  	}
		  	// Secondly we need to check if the user intended to change this value.
		  	if ( ! isset( $_POST['ksurl'] ) || ! wp_verify_nonce( $_POST['idksimport_metabox'], 'idksimport' ) ) {
		      	return;
		  	}
		  	$post_id = absint($_POST['post_ID']);
		  	$ksurl = esc_attr($_POST['ksurl']);
		  	$args = array('post_id' => $post_id, 'ksurl' => $ksurl);
		  	update_post_meta($post_id, 'ksurl', $ksurl);
		  	$ksprojects = get_option('idksimport_projects');
		  	if (is_array($ksprojects)) {
		  		$ksprojects[] = $post_id;
		  	}
		  	else {
		  		$ksprojects = array($post_id);
		  	}
		  	$ksprojects = array_unique($ksprojects);
		  	update_option('idksimport_projects', $ksprojects);
		  	run_ksupdate_cron($args);
		}
	}
}

if (!wp_next_scheduled('schedule_hourly_ksupdate_cron')) {
	wp_schedule_event(time(), 'hourly', 'schedule_hourly_ksupdate_cron');
}

function schedule_hourly_ksupdate_cron($args = null) {
	if (empty($args)) {
		$ksprojects = get_option('idksimport_projects');
		if (!empty($ksprojects)) {
			foreach ($ksprojects as $project) {
				$post_id = $project;
				$ksurl = get_post_meta($post_id, 'ksurl', true);
				$args = array('post_id' => $post_id, 'ksurl' => $ksurl);
				$run_ksupdate_cron($args);
			}
		}
	}
}

function run_ksupdate_cron($args) {
	if (isset($args['post_id'])) {
		$post_id = absint($args['post_id']);
	}
	if (isset($post_id) && $post_id > 0) {
		if (!empty($args['ksurl'])) {
			$ksurl = $args['ksurl'];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ksurl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

			$data = curl_exec($ch);
			curl_close($ch);

			$doc = new DOMDocument();
			@$doc->loadHTML($data);
			$divs = $doc->getElementsByTagName('div');
			foreach ($divs as $div) {
				$divid = $div->getAttribute('id');
				if ($divid == 'backers_count') {
					$backers_count = $div->getAttribute('data-backers-count');
				}
				else if ($divid == 'pledged') {
					$pledged = $div->getAttribute('data-pledged');
				}
				if (isset($backers_count) && isset($pledged)) {
					break;
				}
			}
			if (!empty($backers_count)) {
				update_post_meta($post_id, 'ks_backers_count', $backers_count);
			}
			if (!empty($pledged)) {
				update_post_meta($post_id, 'ks_pledged', $pledged);
			}
		}
	}
}

/*
add_action('id_widget_before', 'insert_ks_data', 3, 2);
add_action('fh_project_summary_before', 'insert_ks_data', 3);
add_action('fh_hDeck_before', 'insert_ks_data', 3);
*/

add_action('init', 'insert_ks_data', 9);

function insert_ks_data() {
	add_filter('id_funds_raised', 'update_ks_raised', 9, 2);
	add_filter('id_number_pledges', 'update_ks_pledges', 9, 2);
	// we no longer need this since we're calculating raised dynamically
	add_filter('id_percentage_raised', 'update_ks_percentage', 9, 4);
}

function update_ks_raised($content, $post_id) {
	$ks_raised = get_post_meta($post_id, 'ks_pledged', true);
	if (!empty($ks_raised)) {
		$content = $ks_raised + $content;
	}
	return $content;
}

function update_ks_pledges($content, $post_id) {
	$ks_backers_count = get_post_meta($post_id, 'ks_backers_count', true);
	if (!empty($ks_backers_count)) {
		$content = $ks_backers_count + $content;
	}
	return $content;
}

function update_ks_percentage($percentage, $pledged, $post_id, $goal) {
	// Removing currency sign and other formatting from $pledged and $goal
	$goal = preg_replace('/[^0-9.]+/', '', $goal);
	$pledged = preg_replace('/[^0-9.]+/', '', $pledged);
	$ks_raised = get_post_meta($post_id, 'ks_pledged', true);
	// if (!empty($ks_raised)) {
		if ($goal > 0) {
			// $new_raised = floatval($pledged + $ks_raised);
			// return $new_raised;
			if ($pledged > 0) {
				$percentage = ($pledged / $goal) * 100;
			}
			else {
				$percentage = '0';
			}
		}
	// }
	return $percentage;
}
?>