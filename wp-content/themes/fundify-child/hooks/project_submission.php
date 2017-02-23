<?php


Class Submit_Project{
	
	public $curent = 1;
	public $prev = 1;
	public $steps = array(1 => 'none',2 => 'none', 3 => 'none' ,4 => 'none' ,5 => 'none' ,6 => 'none');
	public $next = NULL;
	public $postid = NULL;
	public $post = NULL;
	public $utilisations = NULL;
	public $medias = NULL;
	public $members = NULL;
	public $guide = NULL;
	public $bravo = NULL;

	public function __construct(){		


		if( !empty($_POST['next'])){
			if( empty($_GET['next'])){
				$this->curent = $_POST['next'];			
			}else{
				$this->curent = $_GET['next'];			
			}
		}else{
			if( empty($_GET['next'])){
				$this->curent = 1;			
			}else{
				$this->curent = $_GET['next'];			
			}
		}

		if( $_GET['step'] === 'preview' ){
			$this->curent = 6 ;
		}

		if( !empty($_GET['postid']) ){
			$_POST['postid'] = $_GET['postid'];
			$this->postid = $_GET['postid'];
		}else{
			if(!empty($_GET['p']) && ($_GET['post_type'] === 'ignition_product') ){
				$_POST['postid'] = $_GET['p'];
				$this->postid = $_GET['p'];	
			}
		}



		if($this->isFormSubmitted() && $this->isNonceSet()) {

			$this->switchpostdata();


			if( $this->curent === '2' ){
				if( !empty($_POST) ){
					/* CHAMPS DE BASE IGNITION PRODUCT */
					//$meta_post = array();
					$_POST["ign_project_closed"] = '' ;
					$_POST["ign_days_left"] 	 = 0 ;
					$_POST["ign_percent_raised"] = 0 ;
					$_POST["ign_fund_raised"] = 0 ;
					$_POST["ign_option_purchase_url"] = 'default' ;
					$_POST["ign_option_project_url"] = 'current_page' ;
					$_POST["shortcode"] = '' ;
					$_POST["ign_product_level_count"] = 1 ;
					$_POST["vedette"] = 1 ;
					$_POST["ign_end_type"] = 'open' ;
					$_POST["_edit_last"] = 1 ;
					$_POST["_edit_lock"] = '' ;
					$_POST["ign_project_id"] = $this->insert_ign_product();

					$insert_post = $this->create_project();
					$this->postid = $insert_post;


					if( $insert_post  !== false){
						$insert_meta = $this->insertMeta($insert_post);
						unset($_POST);
						if( !empty($insert_post) && ($insert_post !== NULL) ){
							$this->postid = $insert_post;
						}
					}
				}
			}else{

				if( $this->curent === '3' ){

					$this->deleteMediasBeforeUpdate();
				}

				if( !empty($_POST['postid']) && ($_POST['postid'] !== NULL) ){
					$this->postid = $_POST['postid'];
				}
				$insert_meta = $this->insertMeta($this->postid);
				unset($_POST);
			}


			/* INSERTION DES LEVELS DANS IGN */
			
			if( $this->curent === '4' ){
				//$this->putIgnLevels();
			}
			

		}

		global $wpdb;
		$this->bravo = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_name` ="bravo" ')[0];

		/* GET META DATAS */
		if( $this->postid !== NULL ){
			$this->getPostInfos();
			$this->getMedias();
			$this->getTeam();
		}


		$this->steps = $this->constrsteps();
		$this->getGuide($this->curent);

		return $this;					
	}

	public function insertMeta($postid){

		foreach($_POST as $key => $value){
			if( !empty($value) && ($value !== "") ){
				if( !empty(get_post_meta( $postid,$key)) ){
					// UPDATE POST META DATAS
					update_post_meta($postid,$key,$value);
				}else{
					// INSERT POST META DATAS
					add_post_meta($postid,$key,$value);
				}
			}else{
				if( !empty(get_post_meta( $postid,$key)) ){
					// UPDATE POST META DATAS EMPTY VALUES
					update_post_meta($postid,$key,$value);
				}
			}
		}
		//exit;
		return true;
	}


	public function create_project(){
		//global $wpdb;
		$userid = get_current_user_id();
		$date = new DateTime;
		$sec_slug = $date->format('s');
		$post_post = array();
		$post_post['ID'] = $this->postid;
		$post_post['post_title'] = $_POST["post_title"];
		$post_post["post_author"] = $userid;
		$post_post["post_date"] = $date->format('Y-m-d H:i:s');
		$post_post["post_type"] = 'ignition_product';
		$post_post["comment_status"] = 'open';

		// on crée le slug avec le nom du projet, les secondes de la date courante et l'id user.
		$post_post["post_name"] = sanitize_title($_POST["post_title"].' '.$sec_slug.' '.$userid);
		
		$err = 0;
		foreach($post_post as $key => $val){
			if( !empty($val) ){
				$err += 0;
			}else if($key !== 'ID'){
				$err += 1;
			}
		}
		unset($_POST["post_title"]);

		if( $err === 0 ){
			$postid = wp_insert_post($post_post);		
			wp_set_post_categories($postid,array($_POST["parent_section"],$_POST["section"]));
			return $postid;
		}else{
			return false;
		}
	}


	public function insert_ign_product(){
		global $wpdb;
		$now = new DateTime();
		$product = array();
		$product["product_image"] = 'product image';
		$product["product_name"] = $_POST["post_title"];
		$product["product_url"] = '/';
		$product["ign_product_title"] = '';
		$product["ign_product_limit"] = '';
		$product["product_details"] = '';
		$product["product_price"] = 0;
		$product["goal"] = $_POST["ign_fund_goal"];
		$product["created_at"] = $now->format('Y-m-d h:i:s');
		$wpdb->insert($wpdb->prefix.'ign_products', $product);
		return $wpdb->insert_id;
	}


	public function uploadFile($file){
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		if( ($file["type"] === 'image/jpeg') || ($file["type"] === 'image/png') ){
				$uploadedfile = $file;
				$upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'renameFile' );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
				return $movefile['url'];
			}			
		}else{
			//var_dump($file);
			//exit;			
		}
	}


	public function switchpostdata(){

		/* DELETE UTILISATIONS AVANT DE LES REINSERER */
		if( $this->curent === '3'){
			global $wpdb;
			$del_mont = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "montant%"';
			$del_cur  = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "currency%" AND `meta_key` != "currency_gbl" ';
			$del_uti  = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "utilisation%"';
			

			$wpdb->query($del_mont);
			$wpdb->query($del_cur);
			$wpdb->query($del_uti);
		}

		/* * * * * * * * * * * * * * * * * * * * * * */

		foreach($_POST as $key => $val){
			switch ($key) {
				case 'delay_type':
					$ign_fund_end = explode('/',$_POST["ign_fund_end"]);
					unset($_POST['ign_fund_end']);
					$formateddate = $ign_fund_end[2].'-'.$ign_fund_end[0].'-'.$ign_fund_end[1].' '.$_POST['end_h'].':00';
					$enddate = new DateTime($formateddate);
					$_POST['ign_fund_end'] = $enddate->format('Y-m-d H:i:s');
				break;
				case 'defi':
					$my_post = array('ID'=> $this->postid, 'post_content' => $val, );
					wp_update_post( $my_post );
				break;
				case 'country':
					$_POST['zones'] = $this->getCountryZone($_POST['country']);
				default:
					$_POST[$key] = $_POST[$key];
				break;
			}
		}

		if( !empty($_FILES)){
			foreach($_FILES as $cle => $file){
				if( !empty($file["tmp_name"]) ){
					$_POST[$cle] = $this->uploadFile($file);
				}
			}		
		}
			unset($_FILES);
			unset($_POST['delay_type']);
			unset($_POST['end_y']);
			unset($_POST['end_m']);
			unset($_POST['end_d']);
			unset($_POST['days_nb']);
	}

	public function constrsteps(){
		$constrsteps = array();
		switch ($this->curent) {
			case 1:
				$constrsteps = array(1 => 'active curent',2 => 'none', 3 => 'none' ,4 => 'none' ,5 => 'none' ,6 => 'none');	
				$this->prev = 1;
				$this->next = 2;
			break;
			case 2:
				$constrsteps = array(1 => 'active',2 => 'active curent', 3 => 'none' ,4 => 'none' ,5 => 'none' ,6 => 'none');	
				$this->prev = 1;
				$this->next = 3;
			break;
			case 3:
				$constrsteps = array(1 => 'active',2 => 'active', 3 => 'active curent' ,4 => 'none' ,5 => 'none' ,6 => 'none');	
				$this->prev = 2;
				$this->next = 4;
			break;
			case 4:
				$constrsteps = array(1 => 'active',2 => 'active', 3 => 'active' ,4 => 'active curent' ,5 => 'none' ,6 => 'none');	
				$this->prev = 3;
				$this->next = 5;
			break;
			case 5:
				$constrsteps = array(1 => 'active',2 => 'active', 3 => 'active' ,4 => 'active' ,5 => 'active curent' ,6 => 'none');	
				$this->prev = 4;
				$this->next = 6;
			break;
			case 6:
				$constrsteps = array(1 => 'active',2 => 'active', 3 => 'active' ,4 => 'active' ,5 => 'active' ,6 => 'active curent');	
				$this->prev = 5;
				$this->next = 6;
				if( empty($_GET['step']) ){
					$loc = get_permalink($_GET['postid']).'&post_type=ignition_product&step=preview';
					wp_redirect($loc);
					exit;					
				}
			break;			
		}		
		return $constrsteps;
	}


	public function getCountryZone($countryCode){
		$zones_country = file_get_contents(get_stylesheet_directory_uri().'/util/country_zones.txt');
		$zones_country = explode("\n",$zones_country);
		$zones = array();
		foreach($zones_country as $code_zone){
			$code_zone = explode('=', $code_zone);
			$country = trim($code_zone[0]);
			$zone = trim($code_zone[1]);
			$zones[$country] = $zone;
		}
		return $zones[trim($countryCode)];
	}

	
	public function select_util($type,$value = NULL){
		$texte = file_get_contents(get_stylesheet_directory_uri().'/util/'.$type.'.txt');
		$texte = explode("\n",$texte);
		$select = '';
		foreach($texte as $option){
			$option = explode('=',$option);
			if(trim($option[0]) === trim($value)){$selected = ' selected="selected" ';}
			else{ $selected = ' '; }
			$select .= '<option '.$selected.' value="'.trim($option[0]).'">'.trim($option[1]).'</option>';
		}
		return $select;
	}


	public function getSections($value = NULL){
		$options = '';
		$categorys = get_categories(array('parent' => 12));
		$selected = '';
		foreach($categorys as $category){
			if( trim($value) === trim($category->cat_ID) ){$selected = ' selected="selected" ';}
			$options .= '<option '.$selected.' value="'.$category->cat_ID.'">'.$category->name.'</option>';
		}
		return $options;
	}

	public function isFormSubmitted() {
  		if( isset( $_POST['submitForm'] ) ) return true;
  		else return false;
	}

	public function isNonceSet() {
	if( ( $_POST["post_type"] === 'ignition_product' ) && isset( $_POST['nonce_field_for_submit_new_project'] )  && 	wp_verify_nonce( $_POST['nonce_field_for_submit_new_project'], 'submit_new_project' ) ) return true;
	else return false;
	}

	public function getPostInfos(){
		global $wpdb;
		$this->post = get_post($this->postid);
		
		$montants = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "montant%"  ');
		$currencys = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "currency%"  ');
		$util = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "utilisation%"  ');

		$this->utilisations = array();
		//$this->utilisations[0] = array('montant' => '' ,'currency' => '', 'utilisation' => '');
		$i=0;
		foreach($montants as $mont){
			$this->utilisations[$i] = array('montant' => $mont->meta_value, 'currency' => $currencys[$i]->meta_value ,'utilisation' => $util[$i]->meta_value );
			$i++;
		}
	}

	public function getMedias(){
		global $wpdb;
		$this->post = get_post($this->postid);
		$medias = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "%video_image%" ORDER BY `meta_key` ASC  ');
		$this->medias = array();
		$i=0;		
		foreach($medias as $med){
			$this->medias[$i] = $med;
			$i++;
		}
	}

	public function getMediaTypeInput($media){

		if( stripos($media->meta_value, 'youtube') ){
			$urlframe = str_replace('watch?v=', 'embed/', $media->meta_value);
			return '<iframe width="100%" height="220" src="'.$urlframe.'" frameborder="0" allowfullscreen></iframe> <a class="delete_media_link" href="javascript:deleteMedia('.$media->meta_id.')" title="'.__('Delete').'"><i class="fa fa-times"></i></a>';
		}

		if( stripos($media->meta_value, 'youtu.be') ){
			$urlframe = explode('/',$urlframe);
			//$urlframe = str_replace('watch?v=', 'embed/', $media->meta_value);
			return '<iframe width="100%" height="220" src="https://www.youtube.com/embed/'.$urlframe[3].'" frameborder="0" allowfullscreen></iframe> <a class="delete_media_link" href="javascript:deleteMedia('.$media->meta_id.')" title="'.__('Delete').'"><i class="fa fa-times"></i></a>';
		}

		if( stripos($media->meta_value, '.jpg') || stripos($media->meta_value, '.png') || stripos($media->meta_value, '.jpeg') ){
			return '<img src="'.$media->meta_value.'" /> <a class="delete_media_link" href="javascript:deleteMedia('.$media->meta_id.')" title="'.__('Delete').'"><i class="fa fa-times"></i></a>';
		}

	}

	public function deleteMediasBeforeUpdate(){
		global $wpdb;
		
		$medias = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` LIKE "%video_image%" ORDER BY `meta_key` ASC  ');

		foreach($medias as $med){
			if( !empty( $_POST[$med->meta_key] ) && ($_POST[$med->meta_key] !==  $med->meta_value) ){
				$delete = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND `meta_key` = "'.$med->meta_key.'"  ';
				$wpdb->query($delete);				
			}
		}
	}

	public function concatfind($obj,$field,$nb = NULL){
		if( $nb !== NULL ){
			$field = $field.$nb;
			return $obj->$field;			
		}else{
			$field = $field;
			return $obj->$field;
		}
	}

	public function getTeam(){

		if( !empty(get_post_meta( $this->postid, 'team_member')) ){
			$this->members = get_post_meta( $this->postid, 'team_member');			
		}
		return $this;

	}

	public function getMemberByMail($email){
		if( !empty(get_user_by('email',$email))){
			return get_user_by('email',$email);	
		}else{
			return $email;
		}
		
	}


	public function getGuide($step){
		global $wpdb;
		$this->guide = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'posts WHERE `post_name` ="guide-step-'.$step.'" ')[0];
		return $this;
	}
/*
	public function putIgnLevels(){

		global $wpdb;
		$levels = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$this->postid.' AND  (`meta_key` LIKE "reward_%" OR `meta_key` LIKE "contrepartie_%" OR `meta_key` LIKE "rewards_%")  ');

		foreach($levels as $level){
			$key = $level->meta_key;
			$value = $level->meta_value;

			$n = substr($key,-1,1);
			$newkey = str_replace('_'.$n,'',$key);

			$newkey = str_replace('montant','price',$newkey);
			$newkey = str_replace('reward_', 'ign_product_level_'.$n.'_',$newkey);
			$newkey = str_replace('rewards_', 'ign_product_level_'.$n.'_',$newkey);
			$newkey = str_replace('contrepartie_', 'ign_product_level_'.$n.'_',$newkey);

			if( $n === '1'){
				$newkey = str_replace('level_1_','',$newkey);
			}

			
			add_metadata( 'post', $this->postid, $newkey, $value, true );
			add_metadata( 'post', $this->postid, 'ign_product_level_'.$n.'_order', $value, true );		

		}
	}
	*/

}

$submit = new Submit_Project;









function change_state(){
	if( $_REQUEST["fn"] === "change_project_state" ){
		  $uppost = array(
		      'ID'           => $_REQUEST["id"],
		      'post_status'   => $_REQUEST["state"],
		  );
		  wp_update_post($uppost);
		  if( $_REQUEST['state'] === 'publish' ){
		  	echo '<button class="btn btn-success " onclick="change_state('.$_REQUEST["id"].',\'draft\')">'.__('Suspendre le projet').'</button>'."<br/>";
		  	echo '<a href="'.get_permalink($_REQUEST["id"]).'">Voir</a>';
		  	exit;
		  }
		  if( $_REQUEST['state'] === 'draft' ){
		  	echo '<button class="btn btn-success " onclick="change_state('.$_REQUEST["id"].',\'publish\')">'.__('Soumettre votre projet').'</button>';
		  	exit;
		  }
		  if( $_REQUEST['state'] === 'pending' ){
		  	echo 'pending';
		  	exit;
		  }
	}
}
add_action('wp_ajax_nopriv_do_ajax_change_state', 'change_state');
add_action('wp_ajax_do_ajax_change_state', 'change_state');


function savemeta(){
	if( $_REQUEST["fn"] === "savemeta" ){
		$is_stil_there = get_post_meta($_REQUEST["postid"],$_REQUEST["meta_key"],true);
		if( !empty($is_stil_there) ){
			if( update_post_meta($_REQUEST["postid"],$_REQUEST["meta_key"], $_REQUEST["meta_value"]) ){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			if( add_post_meta($_REQUEST["postid"],$_REQUEST["meta_key"], $_REQUEST["meta_value"], true) ){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	exit;
}

add_action('wp_ajax_nopriv_do_ajax_savemeta', 'savemeta');
add_action('wp_ajax_do_ajax_savemeta', 'savemeta');


function deletemeta(){
	if( $_REQUEST["fn"] === "deletemeta" ){
		if( delete_post_meta($_REQUEST["postid"], $_REQUEST["meta_key"]) === true ){
			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}
	}
	exit;
}


add_action('wp_ajax_nopriv_do_ajax_deletemeta', 'deletemeta');
add_action('wp_ajax_do_ajax_deletemeta', 'deletemeta');



function deletemedia(){
	if( $_REQUEST["fn"] === "deletemedia" ){
		global $wpdb;
		if( $wpdb->delete( $wpdb->prefix.'postmeta', array('meta_id' => $_REQUEST["metaid"])) ){
			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}
	}
	exit;
}


add_action('wp_ajax_nopriv_do_ajax_deletemedia', 'deletemedia');
add_action('wp_ajax_do_ajax_deletemedia', 'deletemedia');


function add_team_member(){

	if( $_REQUEST["fn"] === "add_team_member" ){

		$post = explode('&', urldecode($_REQUEST['datas']) );
		$json = array();
		foreach ($post as $val) {
			$val = explode('=', $val);
			$json[$val[0]] = $val[1];
			if( $val[0] === 'member_email'){
				$member_email = $val[1];
			}
		}

		$retour = false;
		$invited = get_post_meta($_REQUEST["postid"],'team_member')[0];

		if( !empty($invited) ){
			$invited = maybe_unserialize($invited);
			$update = $invited;
			array_push($update,$json);
			if( update_post_meta($_REQUEST["postid"],'team_member',$update) ){
				$retour = true;
			}
		}else{
			$update = array();
			array_push($update,$json);
			if(add_post_meta($_REQUEST["postid"],'team_member', $update, true)){
				$retour = true;
			}			
		}

		if($retour === true){
			echo '<li>
					<div class="member" >
						<p>'.$member_email.'</p>
					</div>
				</li>';			
		}	

	}
	exit;
}

add_action('wp_ajax_nopriv_do_ajax_add_team_member', 'add_team_member');
add_action('wp_ajax_do_ajax_add_team_member', 'add_team_member');

function del_team_member(){
	if( $_REQUEST["fn"] === "del_team_member" ){
		
		$newarray = get_post_meta($_REQUEST["postid"],'team_member')[0];
		$newarray = maybe_unserialize($newarray);
		unset($newarray[$_REQUEST["id"]]);

		if( count($newarray) > 0){
			if( update_post_meta($_REQUEST["postid"],'team_member',$newarray) ){
				echo 1;
			}else{
				echo 0;
			}			
		}else{
			global $wpdb;
			$delete = 'DELETE FROM '.$wpdb->prefix.'postmeta WHERE `post_id` = '.$_REQUEST["postid"].' AND `meta_key` = "team_member" ';
			$wpdb->query($delete);
			echo 1;
		}
		exit;
	}
}

add_action('wp_ajax_nopriv_do_ajax_del_team_member', 'del_team_member');
add_action('wp_ajax_do_ajax_del_team_member', 'del_team_member');

function get_cat_sect(){

	if( $_REQUEST["fn"] === "get_cat_sect" ){
		$categories = get_term_children( $_REQUEST["catpar"], 'category' );
		$options = '';
		foreach($categories as $k => $cat){
			$cat = get_category( $cat );
			if( trim($_REQUEST['section']) === trim($cat->cat_ID) ){ $chk = ' selected="selected" '; }else{$chk = '';}
			$options .= '<option '.$chk.' value="'.$cat->cat_ID.'">'.$cat->name.'</option>'."\n";
		}
		echo $options;
		exit;
	}
}


add_action('wp_ajax_nopriv_do_ajax_get_cat_sect', 'get_cat_sect');
add_action('wp_ajax_do_ajax_get_cat_sect', 'get_cat_sect');




add_action('wp_ajax_nopriv_do_ajax_upload_rw_img', 'up_img_rw');
add_action('wp_ajax_do_ajax_upload_rw_img', 'up_img_rw');

function up_img_rw(){
	if( $_REQUEST["fn"] === "rw_img" ){
			if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
				
			$uploadedfile = $_FILES['rw_img_up'];
			$upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'renameFile' );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
			    echo $movefile['url'];
			    wp_die();
			} else {
			    //echo $movefile['error'];
			}
	}
	exit;
}


add_action('wp_ajax_nopriv_do_ajax_upload_media', 'upload_media');
add_action('wp_ajax_do_ajax_upload_media', 'upload_media');

function upload_media(){
	if( $_REQUEST["fn"] === "upload_media" ){
			if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile = $_FILES['media_file'];
			$upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'renameFile' );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
				//var_dump($_FILES['media_file']);
			    echo $movefile['url'];
			    wp_die();
			} else {
			    //echo $movefile['error'];
			}
	}
	exit;
}



add_action('wp_ajax_nopriv_do_ajax_upload_doc', 'up_doc');
add_action('wp_ajax_do_ajax_upload_doc', 'up_doc');

function up_doc(){
	if( $_REQUEST["fn"] === "up_doc" ){
			if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
				
			$uploadedfile = $_FILES['doc_file'];
			$upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'renameFile' );

			if( ($uploadedfile["type"] === 'application/pdf') || ($uploadedfile["type"] === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') || ($uploadedfile["type"] === 'image/jpeg') ){
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				if ( $movefile && ! isset( $movefile['error'] ) ) {
				    echo $movefile['url'];
				    wp_die();
				} else {
				    //echo $movefile['error'];
				}				
			}else{
				echo 'no';
				wp_die();
			}

	}
	exit;
}


function renameFile($dir, $name, $ext){
	$newname = str_replace($ext,'',$name);
	$newname = time().$newname;
	$newname = str_shuffle($newname);
	$newname = md5($newname);
	$newname = substr($newname, 0, 9).$ext;
    return $newname;
}