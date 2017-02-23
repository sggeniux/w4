<?php
function google_ecommerce_scripts() {
	$property_code = get_transient('idc_ga_property_code');
	wp_register_script('google_ecommerce-script', plugins_url('js/google_ecommerce.js', __FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('google_ecommerce-script');
	wp_localize_script('google_ecommerce-script', 'idc_ga_property_code', $property_code);
}

function google_ecommerce_admin() {
	add_submenu_page('idf', __('Google Ecommerce', 'memberdeck'), __('Google Ecommerce', 'memberdeck'), 'manage_options', 'idc-google-ecommerce', 'google_ecommerce_menu');
}

function google_ecommerce_menu() {
	$property_code = get_transient('idc_ga_property_code');
	if (isset($_POST['idc_ga_property_code'])) {
		$property_code = sanitize_text_field($_POST['idc_ga_property_code']);
		set_transient('idc_ga_property_code', $property_code, 0);
	}
	include_once(dirname(__FILE__) . '/templates/admin/_googleEcommerceMenu.php');
}

function google_ecommerce_js_triggers($user_id, $order_id, $paykey = '', $fields = 'fields', $source = '') {
	// jQuery triggers for use in analytics/tracking modules
	$source_list = array('paypal', 'pp-adaptive', 'coinbase');
	if (in_array($source, $source_list)) {
		$order = new ID_Member_Order($order_id);
		$order_details = $order->get_order();
		if (!empty($order_details)) {
			$level = ID_Member_Level::get_level($order_details->level_id);
			if (!empty($level)) {
				$user_id = $order_details->user_id;
				$user = get_user_by('id', $user_id);
				if (!empty($user)) {
					add_action('wp_footer', function() use ($order_id, $user_id, $level, $user) {
						echo '
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery(document).trigger("idcPaymentSuccess", ["'.$order_id.'", "", "'.$user_id.'", "'.$level->id.'", "'.md5($user->user_email.time()).'", "", "'.$level->product_type.'"]);
							});
						</script>';
					});
				}
			}
		}
	}
}

function google_ecommerce_order_data() {
	if (isset($_POST['Order'])) {
		$order_id = absint($_POST['Order']);
		if ($order_id > 0) {
			$order = new ID_Member_Order($order_id);
			$the_order = $order->get_order();
			if (!empty($the_order)) {
				$level_id = $the_order->level_id;
				$level = ID_Member_Level::get_level($level_id);
			}
		}
	}
	if (isset($_POST['User'])) {
		$user_id = absint($_POST['User']);
		if ($user_id > 0) {
			$user = get_user_by('id', $user_id);
		}
	}
	$data = array(
		'order' => (isset($the_order) ? $the_order : null),
		'user' => (isset($user) ? $user : null),
		'level' => (isset($level) ? $level : null),
	);
	print_r(json_encode($data));
	exit;
}
?>