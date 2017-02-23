

jQuery(document).ready(function(){


	/* ALERT QUAND ON CLIQUE LE FORMULAIRE */

	jQuery('#first .remove_line').addClass('hide');
	jQuery('.add_utilisation_line').click(function(){
		jQuery('#first .remove_line').removeClass('hide');
		var html = jQuery('.utilisation_group').find('.line#first').html();
		jQuery('.utilisation_group').append('<div class="line just_added" data-id="0">'+html+'</div>');
		jQuery('.utilisation_group').find('.just_added').find('input').val('');
		jQuery('.utilisation_group').find('.just_added').find('textarea').val('');
		jQuery('.utilisation_group').find('.just_added').removeClass('just_added');
		reload_line_ids();
		autoResizeTextarea();
		readonly();
	});
	reload_line_ids();
	jQuery(this).find('.remove_line').click(function(){
		var postid = jQuery('#postid').val();
		var lineid = jQuery(this).parent('.line').attr('data-id');
		delete_line('montant_'+lineid,postid);
		delete_line('currency_'+lineid,postid);
		delete_line('utilisation_'+lineid,postid);
		
		jQuery(this).parent('.line').remove();
		reload_line_ids();
		return false;
	});



	/* GESTION DES REWARDS */

	jQuery('.rewards_list_ul li').each(function(index,value){
		if( index !== 0 ){
			if( (!jQuery(this).hasClass('exist')) && (!jQuery(this).hasClass('add_reward')) ){
				jQuery(this).addClass('hide');				
			}
		}
	});

	jQuery('.rewards .panel').each(function(ind,val){
		if( jQuery(this).find('.used_field').val() === '1' ){
			var liid = jQuery(this).attr('data-id');
			jQuery('.rewards_list_ul .list_li_'+liid).removeClass('hide');
			jQuery('.rewards_list_ul .list_li_'+liid).addClass('op');
		}
	})

	
	jQuery('.save_reward').click(function(){
		var current = jQuery('.rewards').find('.panel.open').attr('data-id');
		if( saveReward(current) === true ){
			var next = getnext(current);
			active_reward(next);
			jQuery('.rewards_list_ul').find('.list_li_'+next).removeClass('hide');
		}
		countRewards();
	});


	jQuery('.duplicate_reward').click(function(){
		var current = jQuery('.rewards').find('.panel.open').attr('data-id');
		var next = getnext(current);
		if( saveReward(current) === true ){
			for(i=1; i < 15;i++){
				jQuery('.rewards #rw_'+next+' #field_'+i+'_'+next).val(jQuery('.rewards #rw_'+current+' #field_'+i+'_'+current).val());
			}
			active_reward(next);
			jQuery('.rewards_list_ul').find('.list_li_'+next).removeClass('hide');
		}
		countRewards();
	});
	jQuery('.delete_reward').click(function(){
		var curr = jQuery('.rewards .open').attr('data-id');
		if( curr !== '1' ){
			jQuery('.rewards .open').find('.edition_view').find('.field').each(function(){
				jQuery(this).removeClass('required');
				jQuery(this).removeClass('alert_required');
			});
			jQuery('#rw_'+curr).find('input').each(function(){
				jQuery(this).val('');
			});
			jQuery('#rw_'+curr).find('select').each(function(){
				jQuery(this).val('null');
			});
			jQuery('#rw_'+curr).find('textarea').each(function(){
				jQuery(this).val('');

				var editId = jQuery(this).attr('id');
				if( (editId !== '') && (editId !== 'null') && (jQuery(this).hasClass('wp-editor-area')) ){
					tinyMCE.get(editId).setContent('');
				}
			});
			jQuery('#rw_'+curr).find('.contrepartie_view').html('');			
		}		
		deleteMetas(curr);
		countRewards();
	});

	jQuery('.rw_add_photo .rw_file_input').change(function(){

		jQuery(this).parent('label').find('.load_img').removeClass('hide');
		var inputId = jQuery(this).attr('id');

			var data = new FormData();
			data.append( 'action', 'do_ajax_upload_rw_img');
			data.append( 'fn', 'rw_img');
			data.append( 'rw_img_up', jQuery(this)[0].files[0] );

			jQuery.ajax({
				url: ajax_params.ajax_url,
				type: 'POST',
				data: data,
				contentType: false,
				processData: false,
				cache:false,
				dataType: 'html',
				success: function(data) {
					jQuery('.data_'+inputId).val(data);
					jQuery('.phot_'+inputId).find('img').remove();
					jQuery('.phot_'+inputId).append('<img src="'+data+'" style="max-width: 240px" /> <a href="javascript:deleteRwPhoto(\''+inputId+'\')"><i class="fa fa-times"></i></a>');
					jQuery('.phot_'+inputId).find('.labeltext').remove();
					jQuery('.phot_'+inputId).parent('.rw_add_photo').removeClass('input_image');
					jQuery('.phot_'+inputId).parent('.rw_add_photo').find('.load_img').addClass('hide');
					jQuery('#'+inputId).remove();



					jQuery('.save_reward').click(function(){
						var current = jQuery('.rewards').find('.panel.open').attr('data-id');
						if( saveReward(current) === true ){
							var next = getnext(current);
							active_reward(next);
							jQuery('.rewards_list_ul').find('.list_li_'+next).removeClass('hide');
						}
					});
				}
			});
	});


	/* CHAMPS AJOUTER UN DOC  */
	if( (jQuery('#organisation_offi_form').length > 0) || (jQuery('#organisation_other_doc').length > 0) ){
		jQuery('#organisation_offi_form').change(function(){
				jQuery(this).parent('label').parent('div').find('.load').removeClass('hide');
				var elm = jQuery(this);
				uploadDoc(elm);
		});

		jQuery('#organisation_other_doc').change(function(){
				jQuery(this).parent('label').parent('div').find('.load').removeClass('hide');
				var elm = jQuery(this);
				uploadDoc(elm);
		});

		jQuery('.import_doc').parent('div').each(function(){
			jQuery(this).find('.load').removeClass('hide');
			var cont_elem = jQuery(this);
			var docs = jQuery(this).find('.input').val();
			var arraydocs = docs.split(';');
			jQuery(arraydocs).each(function(i,v){
				if((v !== '') && (v !== 'undefined')){
					jQuery(cont_elem).append('<div class="col-sm-6 pdf_cont"><a class="pull-right delete_pdf" href="'+v+'"><i class="fa fa-times"></i></a><iframe class="upload_iframe" src="'+v+'"   width="100%" height="450"></iframe></div>');
					jQuery(cont_elem).find('iframe').load(function() {
						jQuery(this).contents().find('img').css('max-width','100%');
						jQuery(this).contents().find('img').css('height',jQuery(this).height());
						jQuery(this).contents().find('body').css('text-align','center');
						jQuery(this).contents().find('img').css('margin','auto');
					});
				}
			});
			jQuery(this).find('.load').addClass('hide');

			jQuery('.delete_pdf').click(function(){
				var remove = jQuery(this).attr('href');
				var inpt = jQuery(this).parent('div').parent('div').find('.input').val();
				var res = inpt.replace(remove,'');
				jQuery(this).parent('div').parent('div').find('.input').val(res);
				jQuery(this).parent('div').remove();
				return false;
			});
		});

	}


	/* GESTION DES REWARDS */



	jQuery('.choose_delay .del_choo').change(function(){
		jQuery('.choose_delay .toggle').toggleClass('hide');
	});

	jQuery('.reseau .chek').change(function(){
		jQuery(this).parents('.reseau').find('input[type=text]').toggleClass('hide');
	});





	/* IMAGES ET VIDEOS */

	jQuery('.add_video_images_button').click(function(){
		addNewMedia();
	});

	initRequiredForm();

	jQuery('.add_video_images .input').find('input').change(function(){
		var idm = jQuery(this).parent('.input').attr('id');
		deleteMedia(idm);
		addNewMedia();
	});


	/* GESTION DES EQUIPES */
	jQuery('#add_member_team').submit(function(){
		var datas = jQuery(this).serialize();
		jQuery(this)[0].reset();
		jQuery.ajax({
			url: ajax_params.ajax_url,
				data:{
				'action':'do_ajax_add_team_member',
				'fn':'add_team_member',
				'datas' : datas,
				'postid': jQuery('#postid').val(),
				},
			dataType: 'html',
			success: function(data){
				tb_remove();
				jQuery('.members').find('ul').append(data);
				jQuery('.members ul li').each(function(i,v){
					jQuery(this).attr('id',i);
					jQuery(this).find('a').remove();
					jQuery(this).find('p').append('<span class="pull-right"><a href="javascript:deleteMember('+i+')"><i class="fa fa-times"></i></a> </span>')
					//jQuery(this).append('<span class="pull-right"><a href="javascript:deleteMember('+i+')"><i class="fa fa-times"></i></a> </span>');
				});
			}
		});
		return false;
	});

	if( jQuery('#parent_section').val() !==''){
		choose_category(jQuery('#parent_section').val(),false);		
	}else{
		jQuery('.choose_category_first').removeClass("hide");	
	}


	jQuery('.maxchars').keyup(function(){
		if( jQuery(this).val().length > (parseInt(jQuery(this).attr('maxlength')) - 1) ){
			jQuery(this).addClass('max_length');
			jQuery(this).parent('div').find('.warning_message').remove();
			jQuery(this).parent('div').append('<span class="warning_message text-danger">'+jQuery(this).attr('data-maxmess')+'</span>');
		}else{
			jQuery(this).parent('div').find('.warning_message').remove();
			jQuery(this).removeClass('max_length');
		}
	});


	jQuery('.empty_input').click(function(){
		jQuery(this).parent('div').find('input').val('');
		jQuery(this).parent('div').find('.warning_message').remove();
	});

	autoResizeTextarea();
	readonly();
	makeMediasSortabl();


	jQuery('.edit_projects_steps li a').click(function(){
		if( jQuery('#postid').val().length < 1 ){
			tb_show('','#TB_inline?width=600&height=150&inlineId=alert_complete_form');
			return false;
		}
	});

});


function countRewards(){
	var count = jQuery('.rewards_list_ul .exist').length;
	jQuery('#ign_product_level_count').val(count);
}


function makeMediasSortabl(){
	jQuery( "#sortable_media" ).sortable({
		start : function(event, ui){console.log('start');},
		change : function(event, ui){console.log('change');},
		update: function(event, ui) {
			initAddVidsBtn();
			/*
		    var media_order = new Array();
		    jQuery( "#sortable_media li" ).each(function(i,v){
		    	var id = jQuery(this).find('.input').attr('id');
		    	media_order[i] = id;
		    });
		    var jsonorder = JSON.stringify(media_order);
		    jQuery('#media_order').val(jsonorder);
		    */
        }
	});
    jQuery( "#sortable_media" ).disableSelection();
}

function readonly(){
	jQuery( "select[readonly=readonly]").change(function(){
		if( (jQuery(this).attr('data-global') !== 'null') && (jQuery(this).attr('data-global') !== jQuery(this).val() ) ){
				
				jQuery(this).val(jQuery(this).attr('data-global'));
				
				jQuery(this).parent('div').append('<span class="readonly_mess">'+jQuery(this).attr('data-curmess')+'</span>');
				var errMess = jQuery(this).parent('div').find('.readonly_mess');
				
				setTimeout(function(){ errMess.remove(); }, 2000);

		}
	});
}

function autoResizeTextarea(){
	jQuery('.project_edit_content').find('textarea').keyup(function(){
		while(jQuery(this).outerHeight() < this.scrollHeight + parseFloat(jQuery(this).css("borderTopWidth")) + parseFloat(jQuery(this).css("borderBottomWidth"))) {
        	jQuery(this).height(jQuery(this).height()+1);
    	};
	});

	jQuery('.project_edit_content textarea').each(function(){
		while(jQuery(this).outerHeight() < this.scrollHeight + parseFloat(jQuery(this).css("borderTopWidth")) + parseFloat(jQuery(this).css("borderBottomWidth"))) {
	        	jQuery(this).height(jQuery(this).height()+1);
	    };
	});
}


function uploadDoc(elem){
	var data = new FormData();
	data.append( 'action', 'do_ajax_upload_doc');
	data.append( 'fn', 'up_doc');
	data.append( 'doc_file', elem[0].files[0] );

	jQuery.ajax({
		url: ajax_params.ajax_url,
		type: 'POST',
		data: data,
		contentType: false,
		processData: false,
		cache:false,
		dataType: 'html',
		success: function(data) {
			if( data !== 'no' ){
				jQuery('<div class="col-sm-6 pdf_cont"><a class="pull-right delete_pdf" href="'+data+'"><i class="fa fa-times"></i></a><iframe class="upload_iframe" src="'+data+'"  width="100%" height="450" ></iframe></div>').insertAfter(elem.parent('label').parent('div').find('.input'));						
				var currentdatas = elem.parent('label').parent('div').find('.input').val();
				elem.parent('label').parent('div').find('.input').val(currentdatas+';'+data);
				elem.parent('label').parent('div').find('.load').addClass('hide');
				elem.parent('label').parent('div').find('iframe').load(function() {
					jQuery(this).contents().find('img').css('max-width','100%');
					jQuery(this).contents().find('img').css('height',jQuery(this).height());
				});
			}else{
				//alert("Ce format de fichier n'est pas autorisé.");
				alert_box("Ce format de fichier n'est pas autorisé.");
				elem.parent('label').parent('div').find('.load').addClass('hide');
			}
		}
	});
}


function editRw(id){
	jQuery('#rw_'+id).find('.edition_view').removeClass('hide');
	jQuery('#rw_'+id).find('.contrepartie_view').addClass('hide');
}

function deleteRwPhoto(photo){
	jQuery('.phot_'+photo).remove();
}

function choose_category(catslug,label){
	jQuery('.choose_category_first').addClass("hide");
	if(label !== false){
		jQuery('#section_label').html(label);		
	}
	jQuery('#parent_section').val(parseInt(catslug));
		jQuery.ajax({
			url: ajax_params.ajax_url,
				data:{
				'action':'do_ajax_get_cat_sect',
				'fn':'get_cat_sect',
				'section' : jQuery('#section').attr('data-section'),
				'catpar': catslug,
				},
			dataType: 'html',
			success: function(data){
				jQuery('#section').html(data);
			}
		});
}

function deleteMember(id){
	if( confirm("Souhaitez vous vraiment suprmier ce membre ?") ){
		jQuery.ajax({
			url: ajax_params.ajax_url,
				data:{
				'action':'do_ajax_del_team_member',
				'fn':'del_team_member',
				'id' : id,
				'postid': jQuery('#postid').val(),
				},
			dataType: 'html',
			success: function(data){
				if( data === '1' ){
					jQuery('.members').find('#'+id).remove();
					jQuery('.members ul li').each(function(i,v){
						jQuery(this).attr('id',i);
					});					
				}
			}
		});
		return false;		
	}
}

function submitedit(){
	initRequiredForm();
	jQuery('#projects_form').submit();
}

function initRequiredForm(){
	jQuery('#projects_form').submit(function(){
		if( required() === true ){
			return true;
		}else{
			return false;
		}
	});
}


function addNewMedia(){
	if( jQuery('#choose_vid_img').val() === 'img' ){
		var html = jQuery('.add_video_images').find('.model').html();			
	}

	if( jQuery('#choose_vid_img').val() === 'vid' ){
		var html = jQuery('.add_video_images').find('.modelvideo').html();
	}
	jQuery('.add_video_images ul').append('<li class="ui-state-default"><div class="input">'+html+'<a href="#" class="delete_input_vid"><i class="fa fa-times"></i></a></div></li>');
	initAddVidsBtn();
	makeMediasSortabl();
}

function required(){

	jQuery('.required').removeClass('alert_required');
	var err = 0;
	jQuery('.required').each(function(){
		if( (jQuery(this).val() !== '') && (jQuery(this).val() !== 'null') ){

		}else{
			err++;
			jQuery(this).addClass('alert_required');
			console.log(jQuery(this).attr('name'));
		}
	});

	jQuery('.alert_required').keyup(function(){
		jQuery(this).removeClass('alert_required');
	});

		if(err > 0){
			tb_show('','#TB_inline?width=600&height=150&inlineId=alert_complete_fields');
			return false;
		}else{
			return true;
		}
}

function initAddVidsBtn(){
	jQuery('.input').each(function(index,val){
		var eleme = jQuery(this);
		jQuery(this).find('.delete_input_vid').click(function(){
			jQuery(eleme).parent('li').remove();
			return false;
		});
		jQuery(eleme).find('input').attr('name','video_image_'+index);
	});

	initUploadMediaStep2();
}

function getnext(current){
	var next = parseInt(current)+1
	if( next > 6){
		alert_box("6 rewards maximum");
		next = 6;
	}
	return next;
}

function active_reward(panel){
	jQuery('.rewards .panel').addClass('hide');
	jQuery('.rewards .panel').removeClass('open');
	jQuery('.rewards').find('#rw_'+panel).removeClass('hide');
	jQuery('.rewards').find('#rw_'+panel).addClass('open');
	jQuery('.rewards').find('#rw_'+panel).find('.contrepartie_title').addClass('required');
	jQuery('.rewards').find('#rw_'+panel).find('.reward_montant').addClass('required');
}

function saveReward(reward){
	

	var error = 0;
	var errorText = '';
	var fieldNbr = jQuery('.rewards #rw_'+reward+' .field').length;

	jQuery('.rewards #rw_'+reward+' .field').removeClass('alert_required');

	jQuery('.rewards #rw_'+reward+' .field').each(function(i,v){
		if(i === 0){
			alert_box('Sauvegarde en cours...');
		}

		if( jQuery(this).hasClass('required') ){ 

			if( (jQuery(this).val() !== '') && (jQuery(this).val() !== 'null') ){
				var meta_key = jQuery(this).attr('name');
				var meta_value = jQuery(this).val();
				var saving = savemetabyajax(meta_key,meta_value);
				try{
					if( saving === 1 ) throw i++;
				}catch(err){
					error += 1;
					errorText += jQuery(this).attr('placeholder')+' : '+err+"<br/>";
				}
			}else{
				jQuery(this).addClass('alert_required');
				error += 1;
			}

		}else{
			if( jQuery(this).val() !== '' ){
				var meta_key = jQuery(this).attr('name');
				var meta_value = jQuery(this).val();
				var saving = savemetabyajax(meta_key,meta_value);
				try{
					if( saving === 1 ) throw i++;
				}catch(err){
					error += 1;
					errorText += jQuery(this).attr('placeholder')+' : '+err+"<br/>";
				}
			}
		}

		if( (i+1) === fieldNbr ){
			closeAlrt();
		}
	});

	
	if( error > 0){
		alert_box("Veuillez remplir tous les champs pour cette récompense "+"<br/>"+errorText);
		return false;
	}else{
		jQuery('.rewards_list_ul').find('.list_li_'+reward).addClass('exist');
		closeAlrt();
		countRewards();	
		return true;	
	}
}


function addReward(){
	var clickindex = 0;
	jQuery('.rewards_list_ul li').each(function(index,value){
		if( (!jQuery(this).hasClass('hide')) && (!jQuery(this).hasClass('add_reward')) ){
			clickindex = parseInt(jQuery(this).attr('data-id'))+1;
		}
	});
	if((clickindex !== 7) && (clickindex !== 0)){
		jQuery('.list_li_'+clickindex).removeClass('hide');
		active_reward(clickindex);
	}
	countRewards();
}

function reload_line_ids(){
	jQuery('#first .remove_line').addClass('hide');
	jQuery('.utilisation_group .line').each(function(index,val){
		
			if(jQuery(this).attr('id') !== 'first' ){
				jQuery(this).attr('data-id',index);
				
				jQuery(this).find('input').attr('name', 'montant_'+index );
				jQuery(this).find('input').addClass('required');
				
				jQuery(this).find('select').attr('name', 'currency_'+index);
				jQuery(this).find('select').addClass('required');
				
				jQuery(this).find('textarea').attr('name', 'utilisation_'+index);
				jQuery(this).find('textarea').addClass('required');

				jQuery(this).find('.remove_line').click(function(){
					var postid = jQuery('#postid').val();
					var lineid = jQuery(this).parent('.line').attr('data-id');
					delete_line('montant_'+lineid,postid);
					delete_line('currency_'+lineid,postid);
					delete_line('utilisation_'+lineid,postid);
					jQuery(this).parent('.line').remove();
					reload_line_ids();
					return false;
				});				
			}

	});
	initRequiredForm();
}

function delete_line(meta_key,id){
	jQuery.ajax({
		url: ajax_params.ajax_url,
			data:{
			'action':'do_ajax_deletemeta',
			'fn':'deletemeta',
			'postid' : id,
			'meta_key' : meta_key,
			},
		dataType: 'html',
		success: function(data){

		}
	});
}



function alert_box(text){
		jQuery('#alert_form').find('p').html(text);
		tb_show('','#TB_inline?width=600&height=150&inlineId=alert_form');
}

function closeAlrt(){
	tb_remove();
}


function change_state(id,state){
	jQuery.ajax({
		url: ajax_params.ajax_url,
			data:{
			'action':'do_ajax_change_state',
			'fn':'change_project_state',
			'id' : id,
			'state': state,
			},
		dataType: 'html',
		success: function(data){
			if(data === 'pending'){
				//'<p>'.__('Votre projet est en attente de validation', 'fundify').'</p>'
				jQuery('.publish_buttons').html('Votre projet est en attente de validation');
				document.location.href="/my-projects/";
			}else{
				jQuery('.publish_buttons').html(data);				
			}
		}
	});
}

function savemetabyajax(meta_key,meta_value){
	jQuery.ajax({
		url: ajax_params.ajax_url,
			data:{
			'action':'do_ajax_savemeta',
			'fn':'savemeta',
			'postid' : jQuery('#postid').val(),
			'meta_key' : meta_key,
			'meta_value': meta_value,
			},
		dataType: 'html',
		success: function(data){
			return data;
		}
	});
}

function deleteMetas(rwdid){
	if( rwdid !== '1'){
		var d = 0;
		var e=0;
		jQuery('.rewards #rw_'+rwdid+' .field').each(function(i,v){
				var meta_key = jQuery(this).attr('name');
				jQuery.ajax({
					url: ajax_params.ajax_url,
						data:{
						'action':'do_ajax_deletemeta',
						'fn':'deletemeta',
						'postid' : jQuery('#postid').val(),
						'meta_key' : meta_key,
						},
					dataType: 'html',
					success: function(data){
						if(data === '1'){
							d+parseInt(data);
						}else{
							e+parseInt(data);
						}
					}
			});
		});

		if( e === 0 ){
			jQuery('.rewards #rw_'+rwdid).removeClass('open');
			jQuery('.rewards #rw_'+rwdid).addClass('hide');
			jQuery('.rewards #rw_'+rwdid+' .used_field').val(0);
			jQuery('.rewards_list_ul .list_li_'+rwdid).removeClass('op');
			jQuery('.rewards_list_ul .list_li_'+rwdid).addClass('hide');
			active_reward(1);
		}else{
			alert(d+' erreurs ');
		}	
	}else{
		//alert('Vous devez avoir au moins une récompense !!!');
		alert_box("Vous devez avoir au moins une récompense !!!");
	}
}

function confirm_box(text){
		jQuery('#alert_form').find('p').html(text+'<p class="text-center"><a class="okay_confirm" href="#">Okay</a></p>');
		tb_show('','#TB_inline?width=600&height=150&inlineId=alert_form');
}


function deleteMedia(i){
	var confirm = confirm_box("Souhaitez vous vraiment suprimer ce média ?");
	jQuery('.okay_confirm').click(function(){
			jQuery.ajax({
				url: ajax_params.ajax_url,
					data:{
					'action':'do_ajax_deletemedia',
					'fn':'deletemedia',
					'metaid' : i,
					},
				dataType: 'html',
				success: function(data){
					tb_remove();
					jQuery('.add_video_images').find('#'+i+'.input').parent('li').remove();
				}
			});
	});
	
	
}


function initUploadMediaStep2(){

	jQuery('.add_video_images .input').find('input[type="file"]').each(function(){
		jQuery(this).change(function(){
			jQuery(this).parent('label').find('.load_img').removeClass('hide');
			element = jQuery(this);
			var data = new FormData();
			data.append( 'action', 'do_ajax_upload_media');
			data.append( 'fn', 'upload_media');
			data.append( 'media_file', jQuery(this)[0].files[0] );
				jQuery.ajax({
					url: ajax_params.ajax_url,
					type: 'POST',
					data: data,
					contentType: false,
					processData: false,
					cache:false,
					dataType: 'html',
					success: function(data) {
						if( data !== ''){
							var el_name = jQuery(element).attr('name');
							var outputimg = '<img src="'+data+'" /><input type="hidden" value="'+data+'" name="'+el_name+'" />';
							jQuery(element).parent('label').parent('.input').html(outputimg);
							//jQuery(this).parent('label').find('.load_img').addClass('hide');						
						}
					}
				});
		});
	});

	jQuery('.add_video_images .input').find('input[type="text"]').each(function(){
		jQuery(this).focusout(function(){
			
			if(jQuery(this).val() !=='' ){

				jQuery(this).parent('label').find('.load_img').removeClass('hide');
				var vid_sourc = jQuery(this).val();
				var el_name   = jQuery(this).attr('name');
				var videoprev = '';
					if ( vid_sourc.indexOf("youtube.com") >= 0 ){
						vid_sourc = vid_sourc.replace("watch?v=", "embed/");
						videoprev = '<iframe width="100%" height="220" src="'+vid_sourc+'" frameborder="0" allowfullscreen></iframe>';
						var input_hide = '<input type="hidden" value="'+vid_sourc+'" name="'+el_name+'" />';
						jQuery(this).parent('label').parent('.input').html(videoprev+input_hide);
					}else if ( vid_sourc.indexOf("youtu.be") >= 0 ){
						vid_sourc = vid_sourc.split("/");
						videoprev = '<iframe width="100%" height="220" src="https://www.youtube.com/embed/'+vid_sourc[3]+'" frameborder="0" allowfullscreen></iframe>';
						var input_hide = '<input type="hidden" value="https://www.youtube.com/embed/'+vid_sourc[3]+'" name="'+el_name+'" />';
						jQuery(this).parent('label').parent('.input').html(videoprev+input_hide);
					}else{
						jQuery(this).parent('label').parent('.input').append('<p>Erreur...</p>');
					}
					

			}

		});
	});

}
