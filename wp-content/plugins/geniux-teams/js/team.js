jQuery(document).ready(function(){
	initTeams();
});

function initTeams(){
	jQuery('.add_to_team').click(function(){
		add_to_team_select_first();	
	});
	jQuery('.delete_from_team').click(function(){
		delete_from_team(jQuery(this).attr('data-team'),jQuery(this).attr('data-project'));
	});

	jQuery('.delete_u_fr_team').click(function(){
		delete_u_fr_team(jQuery(this).attr('data-team'),jQuery(this).attr('data-user'));
	});

	jQuery('#user_search').keyup(function(){
		search_user_for_team(jQuery(this).val(),jQuery(this).attr('data-team'));
	});
}


function delete_from_team(team,project){
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'delete_from_team',
				'team':team,
				'project':project,
		},
		dataType: 'html',
		success: function(data) {
			jQuery('.p_in_team').find('#'+project).addClass('hide');
			initTeams();
		}
	});
}


function add_to_team_select_first(){
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'add_to_team_sel_first',
		},
		dataType: 'html',
		success: function(data) {
			jQuery('#teams_select').removeClass('hide');
			jQuery('#teams_select').html(data);
			jQuery('#teams_select select').change(function(){
				add_to_team();
				initTeams();
			});
		}
	});
}

function add_to_team(){
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'add_to_team',
				'datas': jQuery('#aptmt').serialize()
		},
		dataType: 'html',
		success: function(data) {
			jQuery('#teams_select').addClass('hide');
			jQuery('#details-bottom div').html(data);
			initTeams();
		}
	});
}

function delete_u_fr_team(team,user){
	jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'delete_u_fr_team',
				'team':team,
				'user':user,
		},
		dataType: 'html',
		success: function(data) {
			jQuery('.users_team').find('#'+user).addClass('hide');
			initTeams();
		}
	});
}

function add_user_to_team(team,user){
	jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'add_u_to_team',
				'team':team,
				'user':user,
		},
		dataType: 'html',
		success: function(data) {
			jQuery('.user_search_results').addClass('hide');
			jQuery('#user_search').val("");
			jQuery('.users_team').html(data);
			initTeams();
		}
	});
}

function search_user_for_team(searchvalue,team){
	jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method:'POST',
			data:{
				'action':'do_ajax',
				'fn':'search_user',
				'search':searchvalue,
				'team' : team,
		},
		dataType: 'html',
		success: function(data) {
			jQuery('.user_search_results').removeClass('hide');
			jQuery('.user_search_results').html(data);
			initTeams();
		}
	});
}

