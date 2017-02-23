<?php
global $post;
$id = $post->ID;
$project_id = get_post_meta($id, 'ign_project_id', true);
global $checkout;
?>

<div class="container_first">

<h1><?php _e('Checkout','fundify'); ?></h1>

<form>
	<pre>
		<?php var_dump($checkout); ?>
	</pre>
	<input type="hidden" name="mdid_checkout" value="<?php echo $checkout_infos["mdid_checkout"] ?>" />
	<input type="hidden" name="level" value="<?php echo $checkout_infos["level"] ?>">
	<input type="hidden" name="price" value="<?php echo $checkout_infos["price"] ?>">
</form>	

</div>

<?php //echo apply_filters('the_content', do_shortcode('[project_purchase_form]')); ?>
<?php echo apply_filters('the_content', do_shortcode('[idc_checkout]')); ?>