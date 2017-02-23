<?php


function registermail( $atts, $content = null ) {
	$current_user = wp_get_current_user();
	return '<strong class="registermail">'.$current_user->user_email.'</strong>';
}
add_shortcode( 'G_SH_REGISTER_MAIL', 'registermail' );


function resendmail( $atts, $content = null ) {
	$current_user = wp_get_current_user();
	return '<a class="resendmail" href="javascript:resendregistermail()">'.$content.'</a>';
}
add_shortcode( 'G_SH_RESEND_MAIL', 'resendmail' );