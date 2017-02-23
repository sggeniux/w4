<?php
class ID_Member_Level {
	var $level_id;
	var $product_status;
	var $product_type;
	var $level_name;
	var $level_price;
	var $credit_value;
	var $txn_type;
	var $level_type;
	var $recurring_type;
	var $limit_term;
	var $term_length;
	var $plan;
	var $license_count;
	var $enable_renewals;
	var $renewal_price;
	var $enable_multiples;
	var $create_page;
	var $combined_product;
	var $custom_message;

	function __construct($level_id = null) {
		$this->level_id = $level_id;
		add_action('idc_add_level', array($this, 'flush_level_cache'), 1);
		add_action('idc_update_level', array($this, 'flush_level_cache'), 1);
		add_action('idc_delete_level', array($this, 'flush_level_cache'), 1);
	}

	function add_level($level) {
		global $wpdb;
		$this->product_status = (isset($level['product_status']) ? $level['product_status'] : 'active');
		$this->product_type = (isset($level['product_type']) ? $level['product_type'] : 'purchase');
		$this->level_name = (isset($level['level_name']) ? $level['level_name'] : '');
		$this->level_price = (isset($level['level_price']) ? $level['level_price'] : '0');
		$this->credit_value = (isset($level['credit_value']) ? $level['credit_value'] : null);
		$this->txn_type = (isset($level['txn_type']) ? $level['txn_type'] : 'capture');
		$this->level_type = (isset($level['level_type']) ? $level['level_type'] : null);
		
		if ($this->level_type !== 'recurring') {
			$this->recurring_type = 'none';
			$this->plan = '';
		}
		else {
			$this->recurring_type = $level['recurring_type'];
			$this->plan = $level['plan'];
		}
		$this->limit_term = (isset($level['limit_term']) ? $level['limit_term'] : '0');
		$this->term_length = (isset($level['term_length']) ? $level['term_length'] : null);
		$this->license_count = (isset($level['license_count']) ? $level['license_count'] : null);
		$this->enable_renewals = (isset($level['enable_renewals']) ? $level['enable_renewals'] : '0');
		$this->renewal_price = (isset($level['renewal_price']) ? $level['renewal_price'] : null);
		$this->enable_multiples = (isset($level['enable_multiples']) ? $level['enable_multiples'] : '0');
		$this->create_page = (isset($level['create_page']) ? $level['create_page'] : false);
		$this->combined_product = (isset($level['combined_product']) ? $level['combined_product'] : 0);
		$this->custom_message = (isset($level['custom_message']) ? $level['custom_message'] : null);
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_levels (product_status, product_type, level_name, level_price, credit_value, txn_type, level_type, recurring_type, limit_term, term_length, plan, license_count, enable_renewals, renewal_price, enable_multiples, combined_product, custom_message) VALUES (%s, %s, %s, %s, %d, %s, %s, %s, %d, %d, %s, %d, %d, %s, %d, %d, %d)', $this->product_status, $this->product_type, $this->level_name, $this->level_price, $this->credit_value, $this->txn_type, $this->level_type, $this->recurring_type, $this->limit_term, $this->term_length, $this->plan, $this->license_count, $this->enable_renewals, $this->renewal_price, $this->enable_multiples, $this->combined_product, $this->custom_message);
		$res = $wpdb->query($sql);
		$this->level_id = $wpdb->insert_id;
		if ($this->create_page) {
			$post_id = memberdeck_auto_page($this->level_id, $this->level_name);
		}
		do_action('idc_add_level', $this->level_id, $this);
		return array('level_id' => $this->level_id, 'post_id' => (isset($post_id) ? $post_id : null));
	}

	function update_level($level) {
		global $wpdb;
		$this->product_status = (isset($level['product_status']) ? $level['product_status'] : 'active');
		$this->product_type = (isset($level['product_type']) ? $level['product_type'] : 'purchase');
		$this->level_name = (isset($level['level_name']) ? $level['level_name'] : '');
		$this->level_price = (isset($level['level_price']) ? $level['level_price'] : '0');
		$this->credit_value = (isset($level['credit_value']) ? $level['credit_value'] : null);
		$this->txn_type = (isset($level['txn_type']) ? $level['txn_type'] : 'capture');
		$this->level_type = (isset($level['level_type']) ? $level['level_type'] : null);
		$this->recurring_type = $level['recurring_type'];
		$this->limit_term = (isset($level['limit_term']) ? $level['limit_term'] : '0');
		$this->term_length = (isset($level['term_length']) ? $level['term_length'] : null);
		$this->plan = $level['plan'];
		$this->license_count = (isset($level['license_count']) ? $level['license_count'] : null);
		$this->enable_renewals = (isset($level['enable_renewals']) ? $level['enable_renewals'] : '0');
		$this->renewal_price = (isset($level['renewal_price']) ? $level['renewal_price'] : null);
		$this->enable_multiples = (isset($level['enable_multiples']) ? $level['enable_multiples'] : '0');
		$this->combined_product = (isset($level['combined_product']) ? $level['combined_product'] : 0);
		$this->custom_message = (isset($level['custom_message']) ? $level['custom_message'] : '');
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_levels SET product_status = %s, product_type = %s, level_name=%s, level_price=%s, credit_value = %d, txn_type=%s, level_type=%s, recurring_type=%s, limit_term = %d, term_length = %d, plan=%s, license_count=%d, enable_renewals = %d, renewal_price = %s, enable_multiples = %d, combined_product = %d, custom_message = %d WHERE id=%d',$this->product_status, $this->product_type, $this->level_name, $this->level_price, $this->credit_value, $this->txn_type, $this->level_type, $this->recurring_type, $this->limit_term, $this->term_length, $this->plan, $this->license_count, $this->enable_renewals, $this->renewal_price, $this->enable_multiples, $this->combined_product, $this->custom_message, $this->level_id);
		$res = $wpdb->query($sql);
		do_action('idc_update_level', $this->level_id);
	}

	function delete_level() {
		global $wpdb;
		$sql = 'DELETE FROM '.$wpdb->prefix.'memberdeck_levels WHERE id='.$this->level_id;
		$res = $wpdb->query($sql);
		do_action('idc_delete_level', $this->level_id);
	}

	function flush_level_cache($level_id) {
		idf_flush_object('id_member_level-get_levels');
	}

	/*public static function update_level($level) {
		global $wpdb;
		if (isset($level['level_id']) && $level['level_id'] > 0) {
			$level_id = $level['level_id'];
			$product_status = (isset($level['product_status']) ? $level['product_status'] : 'active');
			$product_type = (isset($level['product_type']) ? $level['product_type'] : 'purchase');
			$level_name = (isset($level['level_name']) ? $level['level_name'] : '');
			$level_price = (isset($level['level_price']) ? $level['level_price'] : '0');
			$credit_value = (isset($level['credit_value']) ? $level['credit_value'] : null);
			$txn_type = (isset($level['txn_type']) ? $level['txn_type'] : 'capture');
			$level_type = (isset($level['level_type']) ? $level['level_type'] : null);
			$recurring_type = $level['recurring_type'];
			$limit_term = (isset($level['limit_term']) ? $level['limit_term'] : '0');
			$term_length = (isset($level['term_length']) ? $level['term_length'] : null);
			$plan = $level['plan'];
			$license_count = (isset($level['license_count']) ? $level['license_count'] : null);
			$enable_renewals = (isset($level['enable_renewals']) ? $level['enable_renewals'] : '0');
			$renewal_price = (isset($level['renewal_price']) ? $level['renewal_price'] : null);
			$enable_multiples = (isset($level['enable_multiples']) ? $level['enable_multiples'] : '0');
			$combined_product = (isset($level['combined_product']) ? $level['combined_product'] : 0);
			$custom_message = (isset($level['custom_message']) ? $level['custom_message'] : '');
			$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_levels SET product_status = %s, product_type = %s, level_name=%s, level_price=%s, credit_value = %d, txn_type=%s, level_type=%s, recurring_type=%s, limit_term = %d, term_length = %d, plan=%s, license_count=%d, enable_renewals = %d, renewal_price = %s, enable_multiples = %d, combined_product = %d, custom_message = %d WHERE id=%d',$product_status, $product_type, $level_name, $level_price, $credit_value, $txn_type, $level_type, $recurring_type, $limit_term, $term_length, $plan, $license_count, $enable_renewals, $renewal_price, $enable_multiples, $combined_product, $custom_message, $level_id);
			$res = $wpdb->query($sql);
			do_action('idc_update_level', $level_id);
		}
	}*/

	public static function delete_user_level($level_id,$user_id){
		global $wpdb;
		$sql = "DELETE FROM ".$wpdb->prefix."memberdeck_member_levels WHERE user_id = '".$user_id."' and level_id = '".$level_id."'";
		$res = $wpdb->query($sql);
	}

	public static function get_levels() {
		$res = idf_get_object('id_member_level-get_levels');
		if (empty($res)) {
			global $wpdb;
			$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels';
			$res = $wpdb->get_results($sql);
			do_action('idf_cache_object', 'id_member_level-get_levels', $res, 60 * 60 * 12);
		}
		return $res;
	}

	public static function get_level($id) {
		global $wpdb;
		$level_id = absint(esc_attr($id));
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE id='.$level_id;
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_level_by_plan($plan) {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE plan = "'.$plan.'"';
		$res = $wpdb->get_row($sql);
		return $res->id;
	}

	public static function get_level_member_count($id) {
		global $wpdb;
		$sql = 'SELECT COUNT(*) as count FROM '.$wpdb->prefix.'memberdeck_members WHERE access_level LIKE "%i:'.$id.'%" OR access_level LIKE "%s:1:\"'.$id.'\"%"';
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_level_member_updated_count($id) {
		global $wpdb;
		$count = 0;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_member_levels WHERE level_id = %d', $id);
		$res = $wpdb->get_results($sql);
		if (!empty($res)) {
			$count = count($res);
		}
		return $count;
	}

	public static function is_level_renewable($id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE id = %d', $id);
		$res = $wpdb->get_row($sql);
		$renewable = 0;
		if (!empty($res)) {
			if ($res->level_type == 'standard') {
				$renewable = $res->enable_renewals;
			}
		}
		return $renewable;
	}

	public static function get_multiple_levels($level_ids) {
		global $wpdb;
		$level_ids_str = implode(",", $level_ids);
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE id IN ('.$level_ids_str.')';
		// echo "get_multiple_levels sql: $sql<br>";
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function get_levels_by_type($type, $single = false) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE level_type = %s'.(($single) ? ' ORDER BY id DESC LIMIT 1' : ''), $type);
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function get_levels_by_custom($custom_field, $custom_value) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE '.$custom_field.' = %s', $custom_value);
		$res = $wpdb->get_results($sql);
		return $res;
	}
}
?>