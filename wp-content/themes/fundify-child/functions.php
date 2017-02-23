<?php
/******************************/
/********* THEME STYLE ********/
/******************************/

function enqueue_parent_styles() {
	/*TEMPLATE STYLE*/
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	
	/*JQUERY UI STYLE*/
	wp_enqueue_style( 'jquery-ui-style', get_stylesheet_directory_uri() . '/js/jqueryui/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui-theme', get_stylesheet_directory_uri() . '/js/jqueryui/theme/jquery-ui.css' );

	/*BOOTSTRAP STYLE*/
	wp_enqueue_style( 'bootstrap-4', get_stylesheet_directory_uri() . '/libs/bootstrap/css/bootstrap.min.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function wp_dequeue_plugin_styles(){
	wp_dequeue_style('fep-common-style');
	wp_dequeue_style('fep-style');	
}
add_action('wp_enqueue_scripts','wp_dequeue_plugin_styles',100);

function fundify_child_scripts() {

	
	wp_dequeue_script( 'fundify-scripts' );
	wp_enqueue_script( 'fundify-child-scripts',get_stylesheet_directory_uri().'/js/fundify.js' );

	wp_enqueue_script( 'custom-scripts',get_stylesheet_directory_uri().'/js/custom.js' );
	wp_localize_script( 'custom-scripts', 'ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_script(  'project_edit-scripts',get_stylesheet_directory_uri().'/js/project_edit.js' );
	wp_localize_script( 'project_edit-scripts', 'ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	wp_dequeue_script( 'auto-completition' );
	wp_enqueue_script( 'auto-completition',get_stylesheet_directory_uri().'/js/city_autocompletion.js' );

	$ggMapApiKey = 'AIzaSyC9nyzlbX2AP1ymaGH8DWuyOxq3mUvxzmY';
	wp_dequeue_script( 'googlemap' );
	wp_enqueue_script( 'googlemap','https://maps.googleapis.com/maps/api/js?key='.$ggMapApiKey.'&libraries=places' );

	/* JQUERY UI */
	wp_dequeue_script( 'jquery-ui' );
	wp_enqueue_script( 'fundify-jquery-ui',get_stylesheet_directory_uri().'/js/jqueryui/jquery-ui.min.js' );

	/* BOOTSTRAP ET DEPENDANCES */
	wp_dequeue_script( 'tether' );
	wp_enqueue_script( 'fundify-tether',get_stylesheet_directory_uri().'/libs/bootstrap/js/tether.min.js' );
	wp_dequeue_script( 'bootstrap-4' );
	wp_enqueue_script( 'fundify-bootstrap-4',get_stylesheet_directory_uri().'/libs/bootstrap/js/bootstrap.min.js' );


}
add_action( 'wp_print_scripts', 'fundify_child_scripts', 100 );


function translate_theme_locale() {
    load_child_theme_textdomain( 'fundify', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'translate_theme_locale' );

/*******************************/
/*******************************/
/*******************************/
/******************************/



/* DEFINITION DE POST TYPE POUR LES ARCHIVES */
//$_GET['post_type'] = 'ignition_product';
//get_queried_object()->term_id;


/* GESTION DES COOKIES */

function set_cookie($name,$value){
	if( setcookie($name, $value, time() + (86400 * 30), "/") ){
		return true;
	}
}

function get_cookie($name){
	$cookie = $_COOKIE[$name];
	return $cookie;
}

/* * * * * * * * * * * */




/******************************/
/** GESTION DES REDIRECTIONS **/
/******************************/

require_once('hooks/redirections.php');

/*******************************/
/*******************************/
/*******************************/

/* GLOBAL VARIABLES */
session_start();
$GLOBALS['alerts'] = array('type' => NULL, 'message' => NULL);
if( !empty($_SESSION['trans_alert']) ){
	$GLOBALS['alerts'] = array('type' => $_SESSION['trans_alert']['type'], 'message' => $_SESSION['trans_alert']['message']);
	unset($_SESSION['trans_alert']);
}



function getCurentCurency(){

	if( is_user_logged_in() === true ):

		if( get_cookie('currency') !== NULL ){
			$curency = get_cookie('currency');
		}else{
			$curency = get_user_meta(get_current_user_id(),'currency',true);			
		}
	else:
		if( get_cookie('currency') !== NULL ){
			$curency = 	get_cookie('currency');
		}else{
			$curency = 'EUR';
		}		
	endif;
	return $curency;
}



/******************************/
/********** HOME PAGE *********/
/******************************/

require_once('hooks/home.php');

/*******************************/
/*******************************/
/*******************************/




/******************************/
/**** ZONE DE SIDEBAR 2 *******/
/******************************/

require_once('hooks/sidebar.php');



/* * * * * * * * * * * * * * * */
/* * FORMULAIRE DE RECHERCHE * */
/* * * * * * * * * * * * * * * */

require_once('hooks/search.php');

/* * * * * * * * * * * * * * * */



/*******************************/
/*******************************/
/*******************************/




/******************************/
/********** DASHBOARD *********/
/******************************/

/* * * * * * * * * * * * * * * */
/* * *  MESSAGERIE INTERNE * * */
/* * * * * * * * * * * * * * * */

require_once('hooks/messages.php');

/* * * * * * * * * * * * * * * */


/*******************************/
/*******************************/
/*******************************/




/******************************/
/********* CROWDFINDING *******/
/******************************/

/* * * * * * * * * * * * * * * */
/* * HOOKS SUR IGNITION DECK * */
/* * * * * * * * * * * * * * * */

require_once('hooks/ignition.php');

/* * * * * * * * * * * * * * * */

/*******************************/
/*******************************/
/*******************************/



/******************************/
/********* NOTIFICATIONS ******/
/******************************/

require_once('hooks/notifs.php');


/*******************************/
/*******************************/
/*******************************/


/*******************************/
/*********     USERS     ******/
/******************************/

require_once('hooks/users.php');


/*******************************/
/*******************************/
/*******************************/


 
/*******************************/
/*********     AVATAR    ******/
/******************************/

require_once('hooks/avatar.php');


/*******************************/
/*******************************/
/*******************************/


/* * * * * * * * * * * * * * * * * * * * */
/*               PROJETS                 */
/* * * * * * * * * * * * * * * * * * * * */


require_once('hooks/projects.php');


/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */



/* * * * * * * * * * * * * * * * * * * * */
/*               CHECKOUT                */
/* * * * * * * * * * * * * * * * * * * * */


require_once('hooks/checkout.php');


/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */



/* * * * * * * * * * * * * * * * * * * * */
/*              SHORTCODES               */
/* * * * * * * * * * * * * * * * * * * * */


require_once('hooks/shortcodes.php');


/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * */


//add_action('wp', function(){ echo '<pre>';print_r($GLOBALS['wp_filter']); echo '</pre>';exit; } );

