<?php

function widget_content( $content )
{


	$top_nav  = '<div class="sidebar_header">';
	$top_nav .= '<div class="sidebar_logo"><img alt="W4" src="'.site_url().'/wp-content/themes/fundify-child/img/nav/w4_logo.png" /></div>';
	$top_nav .= '<div class="sidebar_top_men">';
	$top_nav .= '<ul class="sidebar_top_men_ctn list-inline pull-right">';
	$top_nav .= '<li><a class="member" href="#"><img alt="W4" src="'.site_url().'/wp-content/themes/fundify-child/img/nav/account.svg" /></a></li>';
	$top_nav .= '<li><a class="checkout" href="#"><img alt="W4" src="'.site_url().'/wp-content/themes/fundify-child/img/nav/cart.svg" /></a></li>';
	$top_nav .= '</ul>';
	$top_nav .= '</div>';
	$top_nav .= '</div>';


	if( is_user_logged_in() === true ):
		$userid = get_current_user_id();
		$logout = wp_logout_url('/login-page/');
		$userinfos = get_currentuserinfo();
		$avatar = get_user_meta($userid, 'idc_avatar')[0];
		$use_pseudo = get_user_meta($userid, 'use_pseudo')[0];

		if( $use_pseudo === '1' ){
			$username = get_user_meta($userid,'pseudo')[0];
		}else{
			$username = get_user_meta($userid,'first_name')[0].' '.get_user_meta($userid,'last_name')[0];			
		}
		
		$content .='<div class="avatar_user hide">';
		$content .= '<ul>';
		if( strlen( $avatar ) < 10 ){
			$content .= '<li style="max-width:200px;"><a href="/dashboard/?edit-profile='.$userid.'">'.wp_get_attachment_image( $avatar, 'thumbnail' ).'</a></li>';
		}else{
			$content .= '<li><a href="/dashboard/?edit-profile='.$userid.'"><img style="max-width:200px;" src="'.$avatar.'" /></a></li>';
		}
		$content .= '<li><a href="/dashboard/?edit-profile='.$userid.'">'.$username.'</a></li>';
	    $content .= '<li><a href="'.$logout.'">'.__('Logout').'</a></li>';
	    $content .= '<ul>';
	    $content .='</div>';
	else:
		$content .='<div class="avatar_user hide">';
		$content .= '<ul>';
		$content .= '<li></li>';
	    $content .= '<ul>';
	    $content .='</div>';		
    endif;
    return $content.$top_nav;
}

add_filter( 'widget_text', 'widget_content', 17 );

