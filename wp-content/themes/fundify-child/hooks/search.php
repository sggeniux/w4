<?php

/* * * * * * * * * * * * * */
/* FORMULAIRE DE RECHERCHE */
/* * * * * * * * * * * * * */

/* Compte les posts par zones */
function countzones($term_id){
	global $wpdb;
	$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'postmeta meta LEFT JOIN '.$wpdb->prefix.'posts post ON meta.post_id = post.ID WHERE meta.meta_key = "zones" AND meta.meta_value = "'.$term_id.'" AND post.post_status ="publish" ', OBJECT );
	return count($results);
}
/* Compte les posts par catégories */
function countcategorys($term_id){
		$count = new WP_Query( array(
		'post_type'  => 'ignition_product',
		'paged' 	 => ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ),
		'tax_query'	 => array(
						array(
						  'taxonomy' => 'project_category',
						  'field' => 'id',
						  'terms' => $term_id
						)
				)
		) );
		return count($count);
}

/* modifie le formulaire de base */
function wpdocs_my_search_form( $form ) {
	$filters_state = 'hide';
	if( !empty($_GET['ss']) && ($_GET['ss'] === '1') ){
		$filters_state = '';
	}
	$selectzones = select_util('zones');
	if( empty($_GET['z'])){
	$options = '<option value="default" selected="selected"> '.__(" Sélectionnez une zone ").' </option>';
	$z_links = '<li class="active"><a class="z_link" data-zo="default" href="#" >'.__(" Toutes les zones ").'</a></li>';
	}else{
	$options = '<option value="default"> '.__(" Sélectionnez une zone ").' </option>';
	$z_links = '<li><a class="z_link" data-zo="default" href="#" >'.__(" Toutes les zones ").'</a></li>';		
	}
	foreach($selectzones as $zone){
		$znb = 0;
		$selected = "";
		$z_class = "";
		if($zone["value"] === $_GET['z']){ $selected = ' selected="selected" ' ; $z_class="active"; }
		if( !empty(trim($zone["value"]))){
			$znb = countzones($zone["value"]);
			$options .= '<option '.$selected.' value="'.trim($zone["value"]).'">'.$zone["name"].' <span class="pull-right">'.$znb.'</span></option>';
			if( $znb > 0){
				$z_links .= '<li class="'.$z_class.'"><a class="z_link" data-zo="'.trim($zone["value"]).'" href="#" >'.$zone["name"].' <span class="pull-right">'.$znb.'</span></a></li>';
			}
		}
	}
	$allterms = '';
	$args = array(
    'orderby'           => 'name', 
    'order'             => 'DESC'
	); 
	$terms = get_terms('project_category', $args);
	$allterms = '<li id="null" class="active"><a href="javascript:project_cat(null)">'.__( 'Toutes' ).'</a></li>';
	foreach($terms as $terme_name){
		$allterms .= '<li id="'.$terme_name->term_id.'" class=""><a href="javascript:project_cat('.$terme_name->term_id.')">'.$terme_name->name.'</a> <span class="pull-right">'.countcategorys($terme_name->term_id).'</span></li>';
	}
    $form = '<div class="search-box">
		    <form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
			<div>
					<input type="hidden" name="ss" value="1">
			    	<input type="hidden" name="type" value="ignition_product">
			    	<input type="hidden" name="pc" id="pc" value="">
			    	<div class="search_input">
			    	<img src="'.get_stylesheet_directory_uri().'/img/nav/search.svg">
				    <input id="s" type="text" placeholder="' . __( 'Search' ) . '" value="' . get_search_query() . '" name="s" />
				    </div>
				    <!-- <button type="button" id="searchsubmit" class="submit" value="'. esc_attr__( 'Search' ) .'"><i class="icon-search"></i></button> -->
			    </div>
			    <div class="clearfix"></div>
			    <div class="clearfix"></div>
			    <div class="filters '.$filters_state.'">
				    <div class="close_filters">
				    	<button id="close_filter" type="button">' . __( 'Fermer les filtres' ) .' <i class="icon-cancel"></i></button>
				    </div>
				    <div>
				    <label>'.__("Catégories").'</label>
					    <ul class="terms">
					    '.$allterms.'
					    </ul>
				    </div>
				    <div>
				    	<label>'.__("Zones géographiques").'</label>
				    	<div class="zones_map">
				    		<img class="img-responsive" src="'.get_stylesheet_directory_uri().'/img/zones/default.png" />
				    		<span data-dt="amerique" data-zone="'.get_stylesheet_directory_uri().'/img/zones/amerique.png" class="am"></span>
				    		<span data-dt="europe" data-zone="'.get_stylesheet_directory_uri().'/img/zones/europe.png" class="eu"></span>
				    		<span data-dt="asie" data-zone="'.get_stylesheet_directory_uri().'/img/zones/asie.png" class="as"></span>
				    		<span data-dt="afrique" data-zone="'.get_stylesheet_directory_uri().'/img/zones/afrique.png" class="af"></span>
				    		<span data-dt="ameriquesud" data-zone="'.get_stylesheet_directory_uri().'/img/zones/ameriquesud.png" class="ams"></span>
				    		<span data-dt="oceanie" data-zone="'.get_stylesheet_directory_uri().'/img/zones/oceanie.png" class="oc"></span>
				    		<span data-dt="antarctic" data-zone="'.get_stylesheet_directory_uri().'/img/zones/antarctic.png" class="at"></span>
				    	</div>
				   		<select class="hide" name="z" id="z" size="6">'.$options.'</select>
				   		<ul class="zones">'.$z_links.'</ul>
				    </div>
				    <div class="clearfix"></div>
				    <label>'.__("Autres critères").'</label>
				    <div class="clearfix"></div>
				    <div class="select_input">
				    <label>'.__("Dates").'</label>
				    <select class="hide" size="3" name="so">
				    	<option value="null">'.__( 'Aucun' ).'</option>
				    	<option value="post_date_DESC">'.__( 'à partir du plus récent' ).'</option>
				    	<option value="post_date_ASC">'.__( 'à partir du plus ancien' ).'</option>
				    </select>
				    <ul>
				    	<li><a data-value="null" href="#">'.__( 'Aucun' ).'</a></li>
				    	<li><a data-value="post_date_DESC" href="#">'.__( 'à partir du plus récent' ).'</a></li>
				    	<li><a data-value="post_date_ASC" href="#">'.__( 'à partir du plus ancien' ).'</a></li>
				    </ul>
				    </div>
				    <div class="clearfix"></div>
				    <div class="select_input">
				    <label>'.__("Pourcentage").'</label>
				    <select class="hide" size="3" name="pe">
				    	<option value="null">'.__( 'Aucun' ).'</option>
				    	<option value="ign_percent_raised_ASC">'.__( 'à partir du plus faible' ).'</option>
				    	<option value="ign_percent_raised_DESC">'.__( 'à partir du plus élevé' ).'</option>
				    </select>
				    <ul>
				    	<li><a data-value="null" href="#">'.__( 'Aucun' ).'</a></li>
				    	<li><a data-value="ign_percent_raised_ASC" href="#">'.__( 'à partir du plus faible' ).'</a></li>
				    	<li><a data-value="ign_percent_raised_DESC" href="#">'.__( 'à partir du plus élevé' ).'</a></li>
				    </ul>
				    </div>
				    <div class="clearfix"></div>
				    <div class="select_input">
				    <label>'.__("Objectif de financement").'</label>
				    <select class="hide" size="3" name="ob">
				    	<option value="null">'.__( 'Aucun' ).'</option>
				    	<option value="ign_fund_goal_ASC">'.__( 'à partir du plus faible' ).'</option>
				    	<option value="ign_fund_goal_DESC">'.__( 'à partir du plus élevé' ).'</option>
				    </select>
				    <ul>
				    	<li><a data-value="null" href="#">'.__( 'Aucun' ).'</a></li>
				    	<li><a data-value="ign_fund_goal_ASC" href="#">'.__( 'à partir du plus faible' ).'</a></li>
				    	<li><a data-value="ign_fund_goal_DESC" href="#">'.__( 'à partir du plus élevé' ).'</a></li>
				    </ul>
				    </div>
			    </div>
		    </form>
		    </div>
    <div class="clearfix"></div>';
    return $form;    
}
add_filter( 'get_search_form', 'wpdocs_my_search_form' );


/* * * * * * * * * */
/* SEARCH RESULTS */
/* * * * * * * * * */


if ( isset($_GET['z']) ) {
		add_action( 'pre_get_posts', 'advanced_search_query' );
}

function advanced_search_query($query){

}

add_action('wp_ajax_nopriv_do_ajax', 'ajax_search_results');
add_action('wp_ajax_do_ajax', 'ajax_search_results');
function ajax_search_results(){
	if( $_REQUEST['fn'] === 'get_search_results' ){
			$filtres = explode('&', $_REQUEST['search_form']);
			$get = array();
			foreach($filtres as $filtre){
				$filtre = explode('=',$filtre);
				$get[$filtre[0]] = $filtre[1];
			}
			$_model = $get['z'] != '' ? $get['z'] : '';

			$orderby = '';
			$order = '';
	    	$metakey = '';

			if( (!empty($get['so'])) && ($get['so'] !== 'null') ){
		    	$so = explode('_', $get['so']);
		    	$orderby = $so[0].'_'.$so[1];
		    	$order = $so[2];				
			}



	    	
	    	if( !empty($get['pc']) && ($get['pc'] !== 'null') ){
	    		$category = $get['pc'];	    		
				$tax_querys = array(
					'relation' => 'AND',
						array(
						  'taxonomy' => 'project_category',
						  'field' => 'id',
						  'terms' => $category
						)
				);
	    	}else{
	    		$tax_querys = array();
	    	}

	    	if( $_model !== 'default' ){
			    $meta_query = array(
			    	'relation' => 'AND',
		            array(
		                'key'     => 'zones',
		                'value'   => $_model,
		                'compare' => 'LIKE',
		            )
			    );
	    	}else{
	    		$meta_query = array();
	    	}

	    	if( !empty($get["pe"]) && ($get["pe"] !== 'null') ){
			    	$pe = explode('_', $get['pe']);
	    			$meta_orderby = $pe[0].'_'.$pe[1].'_'.$pe[2];
	    			$meta_order = $pe[3];
	    			
			    	$orderby = 'meta_value_num';
			    	$order = $meta_order;
			    	$metakey = $meta_orderby;
	    	}

	    	if( !empty($get["ob"]) && ($get["ob"] !== 'null') ){
			    	$pe = explode('_', $get['ob']);
	    			$meta_orderby = $pe[0].'_'.$pe[1].'_'.$pe[2];
	    			$meta_order = $pe[3];
	    			
			    	$orderby = 'meta_value_num';
			    	$order = $meta_order;
			    	$metakey = $meta_orderby;
	    	}

		if ( idcf_is_crowdfunding()  ) :
			$wp_query = new WP_Query( array(
				'post_type'  => 'ignition_product',
				's' 		 => $get['s'],
				'paged' 	 => ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ),
				'meta_query' => $meta_query,
				'tax_query'	 => $tax_querys,
				'orderby'    => $orderby,
				'order'      => $order,
				'meta_key'	 => $metakey,
				'post_status' => 'publish',
			) );
		//var_dump($wp_query->query_vars);
		else :
			$wp_query = new WP_Query( array(
				'posts_per_page' => get_option( 'posts_per_page' ),
				'paged'          => ( get_query_var('page') ? get_query_var('page') : 1 ),
				'meta_query' => $meta_query,
				'tax_query'	 => $tax_querys,
				'orderby'    => $orderby,
				'order'      => $order,
				'post_status' => 'publish',
			) );
		endif;

		if ( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
				get_template_part( 'content', idcf_is_crowdfunding() ? 'project' : 'post' ); 
			endwhile;
		else : 
			get_template_part( 'no-results', 'index' );
			wp_reset_query();
		endif;
	}
	die();
}

/* * * * * * * * * */
/* SEARCH RESULTS */
/* * * * * * * * * */