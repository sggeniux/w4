<?php

/******************************/
/**** ZONE DE SIDEBAR 2 *******/
/******************************/
function search_sidebar_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Search Sidebar', 'search_sidebar' ),
        'id' => 'search_sidebar',
        'before_widget' => '<div id="%1$s" class="widget %1$s %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h1>',
        'after_title' => '</h1>',
    ) );

}
add_action( 'widgets_init', 'search_sidebar_widgets_init' );


function change_submenu_class($menu) {  

  //$menu = preg_replace('/ class="menu"/','/ class="menu hide" /',$menu);
  $menu = preg_replace('/ class="sub-menu"/','/ class="sub-menu hide" /',$menu);  
  return $menu;  
}  
add_filter('wp_nav_menu','change_submenu_class');  

/*
function menu_objet($val){
    return $val;
}
add_filter('nav_menu_link_attributes', 'menu_objet');
*/

function curency_chooser(){

    $return  = "<ul>";
    if( getCurentCurency() === 'EUR' ){
        $return .= "<li class=\"active\"><a href=\"javascript:choose_curency('EUR');\">€</a></li>";
        $return .= "<li><a href=\"javascript:choose_curency('USD');\">$</a></li>";
    }else{
        $return .= "<li class=\"active\"><a href=\"javascript:choose_curency('USD');\">$</a></li>";
        $return .= "<li><a href=\"javascript:choose_curency('EUR');\">€</a></li>";
    }
    $return .= "</ul>";

    return $return;
}

function lang_chooser(){
    $return  = "<ul>";
    if( pll_current_language() === 'fr' ){
        $return .= "<li class=\"active\"><a href=\"javascript:choose_language('fr');\">FR</a></li>";
        $return .= "<li><a href=\"javascript:choose_language('en');\">EN</a></li>";
    }else{
        $return .= "<li class=\"active\"><a href=\"javascript:choose_language('en');\">EN</a></li>";
        $return .= "<li><a href=\"javascript:choose_language('fr');\">FR</a></li>";
    }
    $return .= "</ul>";

    return $return;
}



/* PRAMÊTRES URLS */

function is_active_search(){
	if( !empty($_GET['ss']) && ($_GET['ss'] === '1') ){
		return 'active';
	}else{
		return '';
	}
}


if( !empty($_GET['s']) && ($_GET['type'] !== 'ignition_product' ) ){
	$_GET['type'] = 'ignition_product';
}
/* PRAMÊTRES URLS */



/*
function logout_alert_message(){
    //$_SESSION['trans_alert'] = array('type' => 'success', 'message' => "Vous êtes déconnecté.");
}
add_filter( 'wp_nav_menu_objects', 'logout_alert_message');
*/

/* LOGOUT FUNCTION */
/*
add_filter( 'wp_nav_menu_objects', 'adding_logout_link', 10, 2 );
function adding_logout_link( $sorted_menu_items, $args )
{
    $link = array (
        'title'            => 'Logout',
        'menu_item_parent' => 0,
        'ID'               => '',
        'db_id'            => '',
        'url'              => wp_logout_url(home_url())
    );

    if( is_user_logged_in() === true ){
        $sorted_menu_items[] = (object) $link;
    }

    return $sorted_menu_items;        
}
*/