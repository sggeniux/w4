jQuery(document).ready(function(){
	init_buttons();

	jQuery('.notifications_content .notification').click(function(){
		if( jQuery(this).hasClass('non-vu') ){
			var notifId = jQuery(this).attr('id');
			var user = jQuery(this).attr('data-user');
			mark_lu(notifId,user);
			jQuery(this).removeClass('non-vu');
			jQuery(this).addClass('vu');			
		}
	});
	init_notif_menu();
});

function init_notif_menu(){
	jQuery.ajax({
		url: "/wp-admin/admin-ajax.php",
		data:{
			'action':'do_ajax',
			'fn':'notif_count_lu',
		},
		dataType: 'html',
		success: function( data ) {
			jQuery('.is_notifs_menu').find('span').remove();
			jQuery('.is_notifs_menu').append('<span><i class="fa fa-bell" aria-hidden="true"></i> '+data+' </span>');
		}
	});	
}

function mark_lu(id,user){
	jQuery.ajax({
		url: "/wp-admin/admin-ajax.php",
		data:{
		'action':'do_ajax',
		'fn':'notif_mark_lu',
		'user': user,
		'not': id,
		},
		dataType: 'html',
		success: function( data ) {
			init_notif_menu();
		}
	});
}

function init_buttons(){
	jQuery('.follow_btn').click(function(){
		var user = jQuery(this).attr('data-user');
		var post = jQuery(this).attr('data-post');
		follow(user,post,'save_follow');
	});

	jQuery('.unfollow').click(function(){
		var user = jQuery(this).attr('data-user');
		var post = jQuery(this).attr('data-post');
		if(confirm("Souhaitez vous réellement ne plus suivre ce projet ?")){
			follow(user,post,'delete_follow');	
			init_notif_menu();		
		}
	});
}

function follow(user,post,fn){
	jQuery.ajax({
		url: "/wp-admin/admin-ajax.php",
		data:{
		'action':'do_ajax',
		'fn':fn,
		'user': user,
		'post': post,
		},
		dataType: 'html',
		success: function( data ) {
			jQuery('.follow_post').html(data);
			init_buttons();
			init_notif_menu();
		}
	});
}