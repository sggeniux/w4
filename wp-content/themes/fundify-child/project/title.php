<?php
/**
 * Campaign title.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $post;
$company_name = get_post_meta($post->ID, 'ign_company_name', true);
?>

<div class="title">
	<div class="container">
		<h1><?php the_title() ;?></h1>

	</div>
</div>