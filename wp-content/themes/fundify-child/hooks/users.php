<?php

function tml_user_register( $user_id ) {
	
	$fields = $_POST["Fields"];



    foreach($fields as $key => $field){

        if ( (!empty( $field["value"]) && ($field["name"] !== 'idc_avatar_file')  ) ){
            if( update_user_meta($user_id, $field["name"], urldecode($field["value"])) ){
                unset($fields[$key]);
            }
          }

    }

    /// CHAMPS SUR LES NIVEAU D'UTIISATEURS wp_capabilities, wp_user_level, admin_bar_front, role
    $capa = array( 'author' => 1);
    update_user_meta($user_id, 'wp_capabilities',$capa );
    update_user_meta($user_id, 'wp_user_level', '2');
    update_user_meta($user_id, 'role', 'author' );
    update_user_meta($user_id, 'admin_bar_front', '0');
    update_user_meta($user_id, 'show_admin_bar_front', 'false' );
    update_user_meta($user_id, 'show_admin_bar_admin', 'false' );
    update_user_meta($user_id, 'locale', 'site-default' );
    update_user_meta($user_id, 'from', 'profile' );
    add_user_meta( $user_id, 'user_is_new', '1' );
}
add_action( 'user_register', 'tml_user_register' , 10, 1 );


function getDefaultsAvatars(){

	$dir = scandir('wp-content/uploads/default_avatars/');
	$return = array();
	foreach($dir as $file){
		if( !empty($file) && ($file !== '.') && ($file !== '..') ){
			if( stripos($file, 'homme') !== false ){
				array_push($return,'/wp-content/uploads/default_avatars/'.$file);				
			}
		}
	}
	return $return;
}



function tml_user_update( $user_id ) {
	$fields = $_POST;
    foreach($fields as $key => $field){
        if (  (!empty( $field) ) ){
            if( update_user_meta($user_id, $key, urldecode($field)) ){
                unset($fields[$key]);
            }
        }
    }
}
add_action( 'profile_update', 'tml_user_update' , 10, 1 );

function get_users_select($type,$value = NULL){
	$texte = file_get_contents(get_stylesheet_directory_uri().'/util/'.$type.'.txt');
	$texte = explode("\n",$texte);
	$select = '';
	foreach($texte as $option){
		$option = explode('=',$option);
		if(trim($option[0]) === trim($value)){$selected = ' selected="selected" ';}
		else{ $selected = ' '; }
		$select .= '<option '.$selected.' value="'.trim($option[0]).'">'.trim($option[1]).'</option>';
	}
	return $select;
}




add_action('wp_ajax_do_ajax_upload_avatar', 'upload_avatar');
add_action('wp_ajax_nopriv_do_ajax_upload_avatar', 'upload_avatar');
function upload_avatar(){
	if ( ! function_exists( 'wp_handle_upload' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	$uploadedfile = $_FILES['avatar'];
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
	if ( $movefile && ! isset( $movefile['error'] ) ) {
	    echo $movefile['url'];
	    wp_die();
	} else {
	    //echo $movefile['error'];
	}
}







/* SOCIAL REGISTER */

function oa_social_login_set_email_as_user_login ($user_fields)
{
  if ( ! empty ($user_fields['user_email']))
  {
    if ( ! username_exists ($user_fields['user_email']))
    {
	    $user_fields['user_login'] = $user_fields['user_email'];
	    $user_fields['user_url'] = '';
    }
  }
  return $user_fields;
}
add_filter('oa_social_login_filter_new_user_fields', 'oa_social_login_set_email_as_user_login');


function store_social_infos($data,$id){


	if( $id->provider === 'facebook'){
		$prof_url = 'https://www.facebook.com/'.$id->preferredUsername;
		update_user_meta($data->data->ID,'facebook',$prof_url);	
		update_user_meta($data->data->ID,'idc_avatar',$id->pictureUrl);
		update_user_meta($data->data->ID,'social_register','true');
	}

	if( $id->provider === 'google'){
		update_user_meta($data->data->ID,'google',$id->profileUrl);	
		update_user_meta($data->data->ID,'idc_avatar',$id->pictureUrl);
		update_user_meta($data->data->ID,'social_register','true');
	}

}

add_action ('oa_social_login_action_after_user_insert', 'store_social_infos', 10, 2);



/* * * * * * * * */

/*

function get_user_social_infos($p,$a,$b){

	var_dump($p);
	var_dump($a);
	var_dump($b);
	exit;

}
apply_filters('wsl_hook_process_login_alter_wp_insert_user_data','get_user_social_infos');

*/



function check_current_pass(){
	if($_REQUEST["fn"] === 'check_current_pass' ){
		$userinfos = get_currentuserinfo();
		$hash = $userinfos->user_pass;
		$userid = $userinfos->ID;
		//var_dump(wp_check_password($_REQUEST["pass"],$hash, $userid));
		echo wp_check_password($_REQUEST["pass"],$hash, $userid);
		exit;
	}else{
		return false;
		exit;
	}
	exit;
}
add_action('wp_ajax_nopriv_do_ajax_pass', 'check_current_pass');
add_action('wp_ajax_do_ajax_pass', 'check_current_pass');



function resend_regis_mail(){
	if($_REQUEST["fn"] === 'resend_regis_mail' ){
		$current_user = wp_get_current_user();
		wp_new_user_notification($current_user->ID,NULL,'user');
		_e('The email has been re-send','fundify');
	}
	exit;
}
add_action('wp_ajax_nopriv_do_ajax_resend_regis_mail', 'resend_regis_mail');
add_action('wp_ajax_do_ajax_resend_regis_mail', 'resend_regis_mail');