<?php
class ID_Secupay {

	function __construct() {
		self::autoload();
		self::set_filters();
	}

	private static function autoload() {
		require dirname(__FILE__) . '/' . 'id_secupay-hooks.php';
		if (self::is_active()) {
			require dirname(__FILE__) . '/' . 'id_secupay-tests.php';
		}
	}

	private static function set_filters() {
		//add_action('wp_enqueue_scripts', 'secupay_scripts');
		add_action('id_set_module_status_before', array('ID_Secupay', 'module_status_actions'), 10, 2);
		add_action('idc_other_gateways_after', 'id_secupay_admin_toggle');
		add_action('init', 'id_secupay_query_handler');
		if (self::is_active()) {
			add_action('admin_menu', 'id_secupay_admin', 12);
			add_filter('id_secupay_settings', 'id_secupay_apiurl');
		}
	}

	public static function is_active() {
		$settings = maybe_unserialize(get_option('memberdeck_gateways'));
		return isset($settings['enable_secupay']) ? $settings['enable_secupay'] : 0;
	}

	public static function module_status_actions($module, $status) {
		if ($module == 'secupay') {
			if (!$status) {
				$settings = maybe_unserialize(get_option('memberdeck_gateways'));
				$settings['enable_secupay'] = 0;
				update_option('memberdeck_gateways', $settings);
			}
		}
	}
	
}
new ID_Secupay();
?>