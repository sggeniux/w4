<?php


class Project_Single{

public $metas = NULL;
public $media = NULL;

	public function __construct($postid){
		
		$this->initRaised($postid);
		$this->get_metas($postid);
		$this->get_media($postid);
		return $this;
	}

	public function initRaised($post_id){
		$product = get_post_meta($post_id,'ign_project_id',true);
		global $wpdb;
		$raised = $wpdb->get_results(' SELECT SUM(prod_price) AS raised FROM '.$wpdb->prefix.'ign_pay_info WHERE `product_id` ='.$product);
		update_post_meta($post_id, 'ign_fund_raised', $raised[0]->raised);
	}


	public function get_metas($post_id){

		/* RECUPERE LES METADATAS */
		global $wpdb;
		$meta = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `post_id` ='.$post_id);
		$this->metas = array();
		foreach($meta as $value){
			$this->metas[$value->meta_key] = $value->meta_value;
		}
		return $this;
	}

	public function get_media($post_id){
		global $wpdb;
		$medias = $wpdb->get_results(' SELECT * FROM '.$wpdb->prefix.'postmeta WHERE `meta_key` LIKE "video_image%" AND `post_id` ='.$post_id);
		$this->media = $medias;
		return $this;
	}


	public function printMedia($media){
		if( stripos($media, 'youtube') ){
			$urlframe = str_replace('watch?v=', 'embed/', $media);
			return '<iframe width="100%" height="280" src="'.$urlframe.'" frameborder="0" allowfullscreen></iframe>';
		}
		if( stripos($media, 'youtu.be') ){
			$urlframe = explode('/',$urlframe);
			return '<iframe width="100%" height="280" src="https://www.youtube.com/embed/'.$urlframe[3].'" frameborder="0" allowfullscreen></iframe>';
		}
		if( stripos($media, '.jpg') || stripos($media, '.png') || stripos($media, '.jpeg') ){
			return '<img src="'.$media.'" />';
		}
	}

	public function printIndicator($media){
		if( stripos($media, 'youtube') ){
			$urlimage = str_replace('www.youtube.com/embed', 'img.youtube.com/vi', $media);
			//return '<p class="indicator_p">'.__("Vidéo").'</p>';
			//return '<img class="indicator_img" src="/wp-content/themes/fundify-child/img/video.jpg" />';
			//https://img.youtube.com/vi/https://www.youtube.com/embed/_LMbQV8e9ho/0.jpg
			return '<img class="indicator_img" src="'.$urlimage.'/0.jpg" />';
		}
		if( stripos($media, 'youtu.be') ){
			$urlimage = str_replace('https://youtu.be', 'img.youtube.com/vi', $media);
			//return '<p class="indicator_p">'.__("Vidéo").'</p>';
			//return '<img class="indicator_img" src="/wp-content/themes/fundify-child/img/video.jpg" />';
			//https://youtu.be/_LMbQV8e9ho
			return '<img class="indicator_img" src="'.$urlimage.'/0.jpg" />';
		}
		if( stripos($media, '.jpg') || stripos($media, '.png') || stripos($media, '.jpeg') ){
			return '<img class="indicator_img" src="'.$media.'" />';
		}
	}

	public function getCountryName($iso){
		$texte = file_get_contents(get_stylesheet_directory_uri().'/util/countrys.txt');
		$texte = explode("\n",$texte);
		$select = array();
		foreach($texte as $option){
			$option = explode('=',$option);
			$select[trim($option[0])] = trim($option[1]);
		}
		$string = $select[trim($iso)];
		return $string;
	}

	public function get_backers($product){
		global $wpdb;
		$sql = "SELECT * FROM ".$wpdb->prefix."ign_pay_info WHERE product_id = ".$product.'  ORDER BY `created_at` DESC ';
		$payment = $wpdb->get_results($sql);

		$backers = array();
		foreach($payment as $pay){
			 $user_order = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."memberdeck_orders WHERE transaction_id='".$pay->transaction_id."' ")[0]; 
			 if( !empty($user_order->user_id)){
			 	$backers[$user_order->transaction_id] = array('infos' => get_userdata($user_order->user_id),'price' => $user_order->price);			 	
			 }
		}
		$backers = array_unique($backers);
		return $backers;
	}


	public function getAllUpdates($postid){
		$now = new DateTime();
		//$return = array('actu' => '', 'date_actu' => $now->format('M d'));
		$return = array();
		//$return[0] = array('actu' => __('TODAY_DATE'), 'date_actu' => $now->format('m/d/Y') , 'class' => 'today');
		$return[0] = array('actu' => '', 'date_actu' => $now->format('m/d/Y') , 'class' => 'today');
		$i=1;
		foreach(get_post_meta($postid, 'ign_updates') as $update ){
			$update = json_decode(stripslashes($update));
			$return[$i] = array('actu' => html_entity_decode($update->actu) , 'date_actu' => $update->date_actu , 'class' => 'actu');
			$i++;
		}
		usort($return, 'sortFunction');
		return array_reverse($return);
	}


}


function sortFunction( $a, $b ) {
    	return strtotime($a["date_actu"]) - strtotime($b["date_actu"]);
	}

function formatPrice($price){

		$price = explode('.',$price);
		$price = $price[0];

		if(getCurentCurency() === 'USD'){
			$curency = '$' ;
			$price = str_replace('$','',trim($price));
			$price = str_replace('€','',trim($price));
			$price = str_replace('USD','',trim($price));
			$price = str_replace('EUR','',trim($price));
			$price = $curency.' '.str_replace('$','',trim($price));
		}else{
			$curency = '€' ;
			$price = str_replace('$','',trim($price));
			$price = str_replace('€','',trim($price));
			$price = str_replace('USD','',trim($price));
			$price = str_replace('EUR','',trim($price));
			$price = str_replace('€','',trim($price)).' '.$curency;
		}

	return $price;
}

function get_price($price,$cur){

	if( getCurentCurency() !== $cur ){
		if( getCurentCurency() === 'USD' ){
			$newprice = convert_in($price,$cur,'USD');		
		}else if( getCurentCurency() === 'EUR' ){
			$newprice = convert_in($price,$cur,'EUR');
		}		
	}else{
		$newprice = $price;
	}

	return formatPrice($newprice);
}

function convert_in($amount, $from, $to){
    $url  = "https://www.google.com/finance/converter?a=".$amount."&from=".$from."&to=".$to;
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    return round($converted, 3);
}



function cust_fund_child_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	
	$avatar = get_user_meta($comment->user_id, 'idc_avatar')[0];
	$use_pseudo = get_user_meta($comment->user_id, 'use_pseudo')[0];

	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div class="commentarrow"></div>
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">

				<?php
					//echo get_avatar( $comment, 44 );
					if( strlen( $avatar ) < 10 ){
						echo '<div class="avatar"><a href="/dashboard/?edit-profile='.$comment->user_id.'">'.wp_get_attachment_image( $avatar, 'thumbnail' ).'</a></div>';
					}else{
						echo '<div class="avatar"><a href="/dashboard/?edit-profile='.$comment->user_id.'"><img style="max-width:200px;" src="'.$avatar.'" /></a></div>';
					}

					if( $use_pseudo === '1' ){
						echo '<span class="pseudo_name">'.get_user_meta($comment->user_id,'pseudo')[0].'</span>';
					}else{
						echo '<span class="pseudo_name">'.get_user_meta($comment->user_id,'first_name')[0].' '.get_user_meta($comment->user_id,'last_name')[0].'</span>';			
					}
					/*
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'fundify' ) . '</span>' : ''
					);
					*/


					//printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
					//	esc_url( get_comment_link( $comment->comment_ID ) ),
					//	get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
					//	sprintf( __( '%1$s at %2$s', 'fundify' ), get_comment_date(), get_comment_time() )
					//);

					echo '<span class="date_comment"> Il y a ' . human_time_diff(get_comment_time('U'), current_time('timestamp')).'</span>' ;
				?>
			</div><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'fundify' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php //edit_comment_link( __( 'Edit', 'fundify' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php //comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'fundify' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	</li>
	<?php

}