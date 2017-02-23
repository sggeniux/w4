<?php
/**
 * Campaign details.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $campaign, $wp_embed;
global $post;
global $teams;

global $follow;
$user = wp_get_current_user();

$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);
if ($project_id > 0) {
	$project = new ID_Project($project_id);
	$deck = new Deck($project_id);
	$hDeck = $deck->hDeck();
	//$the_project = $project->the_project();
	$video = get_post_meta($post_id, 'ign_product_video', true);
	//$end_type = get_post_meta($post_id, 'ign_end_type', true);
	$closed = $project->project_closed();
	//$ccode = $project->currency_code();
}
$prefix = idf_get_querystring_prefix(); 


global $project_single;


$hDeck->pledges = count($project_single->get_backers($project_id));
?>

<article class="project-details">
	<div class="image">
	<ul class="list-inline project_info_tags">
        	<li class="location">
        	<img class="site_logo" src="/wp-content/themes/fundify-child/img/location.png" />
        	<?php _e($project_single->getCountryName($project_single->metas["country"])) ?>
        	<?php //echo get_post_meta($post_id, 'ign_company_location', true); ?>
        	</li>
			<li class="project-tag-single category">
			<img class="site_logo" src="/wp-content/themes/fundify-child/img/category.png" />
			<?php _e(get_cat_name($project_single->metas["section"])) ?>
			<?php
                  $terms = wp_get_post_terms( $post->ID, 'project_category');
                  if(!empty($terms)) {
                  $site_url = home_url();
                  $cat_name = "";
                   foreach($terms as $term){
                      if($term->count > 0){
                           $cat_name .= "<a href='".esc_url( $site_url )."/project-category/".$term->slug."'>".$term->name."</a>";
                          break;
                      }
                   }
                  if($term->count > 0){ echo $cat_name; }
                  }
               ?>
        	</li>
        </ul>
        <div id="carousel_project" class="carousel slide" data-ride="carousel">
	        <div class="carousel-inner" role="listbox">
	        	<?php 
	        		$c = 0;
	        		$activ_c = 'active';
	        		$indic = '';
	        	?>
		        <?php foreach($project_single->media as $media ): ?>
		        	<div class="carousel-item <?php echo $activ_c; ?>"><?php echo $project_single->printMedia($media->meta_value); ?></div>
		        	<?php
		        		$indic .= '<li data-target="#carousel_project" data-slide-to="'.$c.'" class="'.$activ_c.'">'.$project_single->printIndicator($media->meta_value).'</li>' ;
		        		$activ_c = '';
		        		$c++;
		        	?>
		        <?php endforeach; ?>
				<ul class="carousel-indicators">
					<?php echo $indic; ?>
				</ul>
	        </div>
        </div>
		<?php /* if ( !empty($video) ) : ?>
			<div class="video-container">
				<?php echo idf_handle_video($video); ?>
			</div>
		<?php else : ?>
			<?php 
				echo '<img src="'.ID_Project::get_project_thumbnail($post_id, 'blog').'"/>';
			?>
		<?php endif; */ ?>


	</div>
	<div class="right-side">
		<ul class="campaign-stats">
			<li>
				<h3><?php echo get_price($project_single->metas["ign_fund_raised"], $project_single->metas["currency_gbl"] ); //$hDeck->total  ?></h3>
				<p><?php printf( __( 'Pledged of %s Goal', 'fundify' ), $hDeck->goal ); ?></p>
			</li>
			<li>
			<h3><?php echo  number_format($hDeck->percentage,1); ?>%</h3>
			<p><?php _e('Financé') ?></p>
			</li>

			<li class="progress"><div class="bar"><span style="width: <?php echo $hDeck->percentage; ?>%"></span></div></li>

			<li class="backer-count">
				<h3><?php echo $hDeck->pledges; ?></h3>
				<p><?php echo _nx( 'Backer', 'Backers', $hDeck->pledges, 'number of backers for campaign', 'fundify' ); ?></p>
			</li>
			<?php //if ( $hDeck->end_type == 'closed' ) : ?>
			<li class="days-remaining">
				<?php if ( $hDeck->days_left >= 0 ) : ?>
					<h3><?php echo $hDeck->days_left; ?></h3>
					<p><?php echo _n( 'Day to Go', 'Days to Go', $hDeck->days_left, 'fundify' ); ?></p>
				<?php else : ?>
					<h3><?php echo '00:00' /*$campaign->hours_remaining()*/; ?></h3>
					<p><?php echo _n( 'Hour to Go', 'Hours to Go', '00:00', 'fundify' ); ?></p>
				<?php endif; ?>
			</li>
			<?php //endif; ?>
		</ul>

		<div class="contribute-now ign-supportnow" data-projectid="<?php echo $project_id; ?>">
			<?php if ( !$closed ) : ?>
				<a href="<?php the_permalink().$prefix; ?>purchaseform=fundify&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-orange contribute"><?php _e( 'Contribute Now', 'fundify' ); ?></a>
				<a class="btn btn-orange-circle offer" href="<?php the_permalink().$prefix; ?>purchaseform=fundify&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><img src="/wp-content/themes/fundify-child/img/offer.jpg" /><?php _e( 'Offrir un don', 'fundify' ); ?></a>
			<?php else : ?>
				<a class="btn btn-orange expired"><?php _e('Project Closed'); ?></a>
			<?php endif; ?>

		</div>
			<div class="clearfix"></div>
		<div class="follow_plugin">
			<i class="fa fa-star" aria-hidden="true"></i> <?php echo $follow->followButton($post_id,$user->ID); ?>
		</div>

		<?php
			if ( $hDeck->end_type == 'closed' ) :
				$end_date = date_i18n( get_option( 'date_format' ), strtotime( $hDeck->end ) );
		?>
		
		<p class="fund">
			<?php //if ( 'fixed' == $campaign->type() ) : ?>
			<?php //printf( __( 'This %3$s will only be funded if at least %1$s is pledged by %2$s.', 'fundify' ), $campaign->goal(), $end_date, strtolower( edd_get_label_singular() ) ); ?>
			<?php //elseif ( 'flexible' == $campaign->type() ) : ?>
			<?php //printf( __( 'All funds will be collected on %1$s.', 'fundify' ), $end_date ); ?>
			<?php //else : ?>
			<?php //printf( __( 'All pledges will be collected automatically until %1$s.', 'fundify' ), $end_date ); ?>
			<?php //endif; ?>
		</p>
		<?php endif; ?>
	</div>

	<div class="clearfix"></div>




</article>