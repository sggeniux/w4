<?php

function shipping_info_scripts() {
	wp_register_script('shipping_info-script', plugins_url('js/shipping_info.js', __FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('shipping_info-script');
}

function idc_shipping_info_template(){
	if (is_user_logged_in()) {
		$user = wp_get_current_user();
		if (!empty($user->ID)) {
			$address_info = get_user_meta($user->ID, 'md_shipping_info', true);
		}
	}
	require_once 'templates/_shippingInfoForm.php';
}
function idc_save_shipping_info($user_id, $order_id, $paykey = '', $fields = null, $source = '') {
	// Looping the extra fields posted from Checkout form
	if (!empty($fields)) {
		$address_info = get_user_meta($user_id, 'md_shipping_info', true);
		if (empty($address_info)) {
			$address_info = array(
				'address' => '',
				'address_two' => '',
				'city' => '',
				'state' => '',
				'zip' => '',
				'country' => ''
			);
		}
		foreach ($fields as $field) {
			if (array_key_exists($field['name'], $address_info)) {
				if (!empty($field['value'])) {
					$address_info[$field['name']] = sanitize_text_field(str_replace('%20', ' ', $field['value']));
				}
			}
		}

		// Adding the Address info to shipping info to show in Account tab
		if (!empty($address_info)) {
			update_user_meta($user_id, 'md_shipping_info', $address_info);
		}

		// Adding address to order meta as well, in case needed
		ID_Member_Order::update_order_meta($order_id, 'shipping_info', $address_info);
	}
}
function idc_update_idcf_shipping($pay_id){
	$idcf_order = new ID_Order($pay_id);
	$order = $idcf_order->get_order();
	if (!empty($order)) {
		// Getting user for getting his meta_data
		$user_info = get_user_by('email', $order->email); 
		if (!empty($user_info)) {
			// Getting shipping info, if address is stored in it, then would save that address to IDCF order
			$address_info = get_user_meta($user_info->ID, 'md_shipping_info', true);
			if (!empty($address_info)) {
				// set address fields
				$address = $address_info['address']." ".$address_info['address_two'];
				$country = $address_info['country'];
				$state = $address_info['state'];
				$city = $address_info['city'];
				$zip = $address_info['zip'];
				// now update
				$update = new ID_Order($pay_id,
					$order->first_name,
					$order->last_name,
					$order->email,
					$address,
					$city,
					$state,
					$zip,
					$country,
					$order->product_id,
					$order->transaction_id,
					$order->preapproval_key,
					$order->product_level,
					$order->prod_price,
					$order->status,
					$order->created_at
				);
				$update->update_order();
			}
		}
	}
}
?>