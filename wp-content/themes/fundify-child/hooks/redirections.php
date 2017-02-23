<?php

/* Bloquer accès aux non-admins */
function wpc_block_dashboard() {

$user = wp_get_current_user();

	$file = basename($_SERVER['PHP_SELF']);
	if (is_user_logged_in() && !in_array( 'administrator', (array) $user->roles )  && $file != 'admin-ajax.php') {
		session_start();
		$_SESSION['trans_alert'] = array('type' => 'danger', 'message' => "Vous n'avez pas accès à cette page.");
		wp_redirect( home_url() );
		exit();
	}
}
add_action('admin_init', 'wpc_block_dashboard');


/* ALERTE DE LOGOUT */

function logout_event(){
	session_start();
	$_SESSION['trans_alert'] = array('type' => 'success', 'message' => "Vous n'êtes plus connecté.");
}

add_action('wp_logout', 'logout_event');


function login_event($user_login){
	//$user = get_user_by( 'email',$user_login);
	//$isnew = get_user_meta($user->id, 'user_is_new', true);
	session_start();
	$_SESSION['trans_alert'] = array('type' => 'success', 'message' => "Vous êtes connecté.");	
}

add_action('wp_login', 'login_event');


/* REDIRECTION DASHBOARD VERS EDITION PROFIL */
add_action( 'wp', 'redir_dashboard' );
function redir_dashboard(){
	global $post;

	if( ($post->post_name === 'dashboard') && (empty($_GET)) ){
		session_start();
		$_SESSION['trans_alert'] = $_SESSION['trans_alert'];
		wp_redirect('/dashboard?edit-profile');
		exit;
	}

	if( ($post->post_name === 'dashboard') && $_GET['account_created'] === '1' ){
		//session_start();
		//$_SESSION['trans_alert'] = array('type' => 'success', 'message' => "Vous êtes inscrit avec succès.");
		wp_redirect('/register-thanks/');
		exit;		
	}

	if( !is_user_logged_in() && ($post->post_name === 'dashboard') && ($_GET['edit-profile'] === '1') ){
		session_start();
		wp_redirect('/login-page/');
		exit;
	}

}