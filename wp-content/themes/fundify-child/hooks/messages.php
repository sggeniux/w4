<?php

add_filter( 'fep_menu_buttons', 'fep_cus_fep_menu_buttons' );
function fep_cus_fep_menu_buttons( $menu )
{
    unset( $menu['announcements'] );
    unset( $menu['settings'] );
    return $menu;
}



function ajax_msg(){
	switch ($_REQUEST['fn']) {
		case 'viewmessage':
			require('ajax_message_bloc.php');
		break;
		default:
			echo '';
		break;
	}
}

add_action('wp_ajax_nopriv_do_ajax', 'ajax_actions');
add_action('wp_ajax_do_ajax', 'ajax_actions');
