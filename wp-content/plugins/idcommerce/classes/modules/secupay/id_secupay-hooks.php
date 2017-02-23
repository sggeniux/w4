<?php
function id_secupay_apiurl($settings) {
	$settings['apiurl'] = 'https://api.sofort.com/api/xml';
	return $settings;
}

function id_secupay_settings() {
	return get_option('id_secupay_settings');
}

function id_secupay_admin() {
	add_submenu_page('idc', __('Secupay', 'memberdeck'), __('Secupay', 'memberdeck'), 'manage_options', 'id-secupay', 'id_secupay_menu');
}

function id_secupay_query_handler() {
	$get_args = wp_parse_args($_GET);
	$post_args = wp_parse_args($_POST);
	if (array_key_exists('gateway-submit', $post_args)) {
		// saving gateway settings
		add_filter('idc_gateway_settings', 'id_secupay_gateway_toggle');
	}
}

function id_secupay_gateway_toggle($settings) {
	$post_args = wp_parse_args($_POST);
	$enable_secupay = isset($post_args['enable_secupay']) ? absint($post_args['enable_secupay']) : 0;
	$settings['enable_secupay'] = $enable_secupay;
	return $settings;
}

function id_secupay_admin_toggle() {
	$settings = maybe_unserialize(get_option('memberdeck_gateways'));
	$enable_secupay = isset($settings['enable_secupay']) ? $settings['enable_secupay'] : 0;
	$output = '<div class="form-input inline">';
	$output .= '<input type="checkbox" name="enable_secupay" id="enable_secupay" value="1" class="cc-gateway-chkbox" '.(isset($enable_secupay) && $enable_secupay ? 'checked="checked"' : '').'/>';
	$output .= '<label for="enable_secupay">'.__('Enable Secupay', 'memberdeck').'</label>';
	$output .= '</div>';
	echo $output;
}

function id_secupay_menu() {
	$settings = get_option('id_secupay_settings');
	if (isset($_POST['submit_secupay_settings'])) {
		$settings = array();
		foreach ($_POST as $k=>$v) {
			if ($k !== 'submit_secupay_settings') {
				$settings[$k] = sanitize_text_field($v);
			}
		}
		$settings = apply_filters('id_secupay_settings', $settings);
		update_option('id_secupay_settings', $settings);
	}
	$fields = array(
		'api_key' => array(
			'label' => __('API Key', 'memberdeck'),
			'name' => 'api_key',
			'id' => 'api_key',
			'class' => 'form-row third left',
			'type' => 'text',
			'value' => (isset($settings['api_key']) ? $settings['api_key'] : '')
		),
		'submit_secupay_settings' => array(
			'name' => 'submit_secupay_settings',
			'id' => 'submit_secupay_settings',
			'class' => 'form-row third left button button-primary',
			'type' => 'submit',
			'value' => __('Save', 'memberdeck')
		)
	);
	$form = new MD_Form($fields);
	$output = '<form name="secupay_settings" id="secupay_settings" action="" method="POST">';
	$output .= $form->build_form();
	$output .= '</form>';
	include_once('templates/admin/_secupaySettings.php');
}
?>