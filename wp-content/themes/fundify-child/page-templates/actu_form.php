<form action="#" id="<?php echo $post->ID ?>" class="add_actu">
	<div>
		<textarea name="actu" class="resizable" placeholder="<?php _e('Actualité du projet') ?>"></textarea>
	</div>
	<div>
		<input name="date_actu" type="text" class="datepicker" placeholder="<?php _e("Date de l'actualité") ?>" name="">
	</div>
	<div>
		<button class="btn btn-success" type="submit"><?php _e('Enregsitrer') ?></button>
	</div>
</form>


<?php //var_dump($post->ign_fund_end) ?>

<?php //var_dump($post->post_date) ?>


<script type="text/javascript">
	jQuery( function() {
		    	jQuery( ".datepicker" ).datepicker({
		    		dateFormat : 'mm/dd/yy',
		    		//minDate: "+1D",
		    		maxDate: "+12M +1D",
		    	});
		    autoResizeDTextarea();
	});

</script>