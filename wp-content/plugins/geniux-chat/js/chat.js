jQuery(document).ready(function(){
	jQuery('#chat_form').find('button').click(function(){
		disc = jQuery('#chat_form').find('#discuss').val();
		post_mess(disc);
	});
});


function open_discus(id){
	jQuery('.chat_content').find('.flux').html('');
	jQuery('#chat_form').find('#discuss').val(id);
	refresh(id);
}


function post_mess(disc){
		jQuery.ajax({
			url: "/w4/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'save_mess',
				'form' : jQuery('#chat_form').serialize(),
				'disc':disc,
		},
		dataType: 'html',
			success: function(data) {
				refresh(disc);
			}
		});
}

function refresh(disc){
		jQuery.ajax({
			url: "/w4/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'messages',
				'form' : jQuery('#chat_form').serialize(),
				'disc':disc,
		},
		dataType: 'html',
		success: function(data) {
				mess = jQuery.parseJSON(data);
				jQuery(mess).each(function(){
					message = jQuery(this)[0]['message'];
					id = jQuery(this)[0]['id'];
					date = jQuery(this)[0]['creation_date'];
					owner = jQuery(this)[0]['owner'];
					mess_obj = '<div id="'+id+'">'+message+' <span>'+date+'</span></div>';
					jQuery('.chat_content').find('.flux').append('<li>'+mess_obj+'</li>')
					jQuery("#last").val(date);
				});
			}
		});
}