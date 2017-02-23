<?php 

/* * * * * * */
/* HOME PAGE */
/* * * * * * */

function have_projects(){
		$wp_query = new WP_Query( array(
			'post_type'  => 'ignition_product',
			'paged' 	 => ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ),
			'meta_query' => array(
			  	'relation' => 'AND',
		            array(
		                'key'     => 'vedette',
		                'value'   => '1',
		                'compare' => '=',
		        )
			),
		) );
	return $wp_query;
}