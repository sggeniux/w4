<?php

Class Follow_Admin{


	public function __construct(){
		add_action('admin_menu', array($this, 'add_admin_menu'), 20);
		add_action('admin_init', array($this, 'register_settings'));
	}

	public function add_admin_menu(){
        add_menu_page('Settings', 'Settings', 'manage_options', 'follow_settings', array($this, 'settings'),'dashicons-heart');
    }


	public function settings(){
    	echo '<h1>'.get_admin_page_title().'</h1>';
    	require_once('admin/settings.php');
	}

	public function register_settings(){

    	register_setting('follow_settings', 'follow_settings_nbrj');
    	register_setting('follow_settings', 'follow_settings_notif_menu');
    	add_settings_section('follow_settings_section', 'Parametres', array($this, 'section_html'), 'follow_settings');
    	add_settings_field('follow_settings_nbrj', 'Nombre de jours pour les derniers rappels', array($this, 'last_days_nbr'), 'follow_settings', 'follow_settings_section');
    	add_settings_field('follow_settings_notif_menu', 'Id du menu des notifications', array($this, 'notif_menu'), 'follow_settings', 'follow_settings_section');
	}

	public function last_days_nbr(){
		echo '<input type="number" name="follow_settings_nbrj" value="'.get_option('follow_settings_nbrj').'" />';
	}

	public function notif_menu(){
		echo '<input type="text" name="follow_settings_notif_menu" value="'.get_option('follow_settings_notif_menu').'" />';	
	}

	public function select_functions(){
		$plugins = get_plugins();
		echo '<select name="follow_settings_funct" >';
		echo '<option> Choisissez un plugin </option>';
		foreach ($plugins as $plug) {
			if( is_plugin_active($plug["TextDomain"].'/'.$plug["TextDomain"].'.php') ){
						echo '<option value="'.$plug["TextDomain"].'">'.$plug["Name"].'</option>';	
			}
		}
		echo '</select>';
	}
	public function section_html(){
	    echo 'Renseignez les paramètres.';
	}


}