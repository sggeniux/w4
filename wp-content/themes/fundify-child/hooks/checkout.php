<?php

/** CLASS CHECKOUT * */

class Checkout{
	
	function __construct($get = NULL){
		
		session_start();

		echo '<pre>';
		var_dump($_COOKIE);
		var_dump($_SESSION);
		echo '</pre>';

	}


}

if (isset($_GET['purchaseform']) && $_GET['purchaseform'] == 'fundify') {
	$checkout_infos = $_GET;
	$checkout = new Checkout($_GET);
}