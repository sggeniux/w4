	
	<div>
		<?php echo $firstmsg->post_title ?>
	</div>

	<div>
		<?php echo $firstmsg->post_content ?>
	</div>

	<div>
		<a href="?fepaction=viewmessage&id=<?php echo $firstmsg->ID ?>">Lire</a>
	</div>

	<?php echo Fep_Form::init()->form_field_output('reply', '', array( 'fep_parent_id' => $firstmsg->ID )); ?>