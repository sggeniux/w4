jQuery(document).ready(function($){

	$('#searchform').find('input').keyup(function(){
		search();		
	});
	$('#searchform').find('select').change(function(){
		search();		
	});


	/* ZONES */
	$('#searchform').find('#z').change(function(){
		$('.zones_map').find('img').attr('src','/wp-content/themes/fundify-child/img/zones/'+$(this).val()+'.png');
	});
	$('.zones_map').find('span').hover(function(){
		var zon = $(this).attr('data-zone');
		var data = $(this).attr('data-dt');
		$('.zones_map').find('img').attr('src',zon);
		$(this).click(function(){
			$('#searchform').find('#z').val(data);
			$('.zones').find('.active').removeClass('active');
			$('.zones').find('[data-zo="'+data+'"]').parent('li').addClass('active');
			search();
		});
	});

	$('.z_link').click(function(){

		$('.zones').find('.active').removeClass('active');
		$(this).parent('li').addClass('active');

		$('#z').val($(this).attr('data-zo'));
		$('.zones_map').find('img').attr('src','/wp-content/themes/fundify-child/img/zones/'+$(this).attr('data-zo')+'.png');
		search();
	});

	/* ZONES */

	$('#searchform').find('#searchsubmit').click(function(){
		//return false;
	});

	$('#close_filter').click(function(){
		$('.filters').addClass('hide');
	});
	$('#s').focus(function(){
		//$('.filters').removeClass('hide')
	});

	$('#searchform .select_input').find('ul li a').click(function(){
		$('.select_input').find('ul li').removeClass('active');
		$('.select_input').find('select').val('null');
		var selectObj = $(this).parents('.select_input').find('select');
		selectObj.val($(this).attr('data-value'));
		$(this).parents('li').addClass('active');
		search();
		return false;
	});


	$('.idc_avatar_file').change(function(){
		upload_avatar();
	})

	$('.default_avatar').change(function(){
		$('.choose_default_avatar').removeClass('active');
		$('.avatar_preview').find('img').attr('src',$(this).val());
//		$('.avatar_preview').html('');
		$('.idc_avatar_file').val('');
		$('#idc_avatar').val($(this).val());
		$(this).parents('.choose_default_avatar').addClass('active');
	});
	$('.idc_avatar_file').change(function(){
		$('.default_avatar').removeAttr('checked');
	});




	  /* * * * * * * * * * * * * */
	  /* * * * *  PROFIL * * * * */
	  /* * * * * * * * * * * * * */

	  /* CACHE LES CHAMPS INUTILES */

	
	  $('#edit-profile .form-row').each(function(index,val){
	    var fieldname = $(this).find('input').attr('name');
	    	if (fieldname !== ''){
	    		$(this).addClass('cont_'+fieldname);
	    	}
	    		$(this).addClass('cont_'+index);
	  });


	  for(nb=0;nb < 14;nb++){
		  jQuery('.cont_'+nb).remove();
	  }

	  $('#edit-profile .desc-note').each(function(index,val){
	    $(this).addClass('descnote_'+index);
	  });

	  $('#edit-profile h2').each(function(index,val){
	    $(this).addClass('h2_'+index);
	  });
	  $('#edit-profile .cont_phone').remove();
	

/*
	  // LOCATION COMPAGNY
	  $('#edit-profile .cont_7').addClass('hide');
	  // WEBSITE URL
	  $('#edit-profile .cont_8').addClass('hide');

	  $('#edit-profile .cont_32').remove();
	  $('#edit-profile .cont_33').remove();
	  $('#edit-profile .cont_34').remove();
*/

	  $('.h2_1').addClass('hide');
	  $('.descnote_1').addClass('hide');
	  
	  $('.h2_2').addClass('hide');
	  $('.descnote_2').addClass('hide');

	  if( $('.memberdeck p.success').length > 0  ){
	  	jQuery('.global_alertbox').find('div').html($('.memberdeck p.success'));
	  }

	  $('.buttons_sociaux li a').click(function(){
	  	$(this).find('img').toggleClass('hide');
	  });

	  /* DEMANDE LE MOT DE PASSE ACTUEL POUR LA MODIF DE MOT DE PASSE*/
	  //$('#edit-profile .pw').parent('.form-row').prepend('<div><label for="aw">Actual Password</label><input type="password" size="20" class="aw" name="aw"><input id="is_cpas_ok" type="hidden" value="0" /></div>');
	  currentPassword();


	  /* REGISTRATION */

	  $('.registration_content form').submit(function(){
	  	$(this).find('.required_field').removeClass("required_field");
	  	$(this).find('.alert_this_mail').remove();
	  	var ret = true; 
			$(this).find('.required').each(function(){
				  	if( $(this).val() === '' ){
				  		// champ vide
				  		$(this).addClass("required_field");
				  		ret = false;
				  	}else{
				  		// champ pas vide
				  	}

			  		if($(this).attr('type') === 'email' ){
			  			var alertmail = $(this).attr('data-alertmail');
			  			//emails 
			  			if( verifMail($(this).val()) === true ){
			  				// c'est un email
			  			}else{
			  				// la valeur n'est pas un mail
			  				$(this).parent('div').append('<span class="alert_this_mail">'+alertmail+'</span>');
			  				ret = false;
			  			}
			  		}else{
			  			// autre que email
			  		}
			});	  	
			return ret;
	  });





	  /* PROJETS */

	  jQuery('.level-binding').click(function(){
	  	
	  	jQuery('.idc_lightbox').find('select.level_select').find('option').removeAttr('selected');

	  	if (typeof jQuery(this).attr('data-image') !== typeof undefined && jQuery(this).attr('data-image') !== false){
		  	var image = jQuery(this).attr('data-image');
		  	jQuery('.idc_lightbox').find('.project_image').css('background-image','url('+image+')');
	  	}else{
	  		jQuery('.idc_lightbox').find('.project_image').css('background-image','url()');
	  	}

	  	var levelid = jQuery(this).attr('data-level');
	  	jQuery('.idc_lightbox').find('select.level_select').find('option[value="'+levelid+'"]').attr('selected','selected');
	  	var total = jQuery('.idc_lightbox').find('select.level_select').find('option[value="'+levelid+'"]').attr('data-price');
	  	jQuery('.idc_lightbox').find('.total').val(total);
	  });

	  jQuery('.idc_lightbox').find('select.level_select').change(function(){
	  	var value = jQuery(this).val();
	  	var total = jQuery(this).find('option[value="'+value+'"]').attr('data-price');
	  	jQuery('.idc_lightbox').find('.total').val(total);
	  });


	  jQuery('.contribute-now').find('a').click(function(){
	  		jQuery('.idc_lightbox').find('.project_image').css('background-image','url()');
	  		var form = jQuery('.idc_lightbox').find('.form').find('form').find('div.submit');
	  		var input = '<div class="form-row lgbx_email_input"><label>Email</label><input name="offer_to" id="offer_to" type="text" value="" /></div>';
	  		if( jQuery(this).hasClass('offer') ){
	  			if(jQuery('.lgbx_email_input').length < 1 ){
		  			jQuery(input).insertBefore(form);	  				
	  			}
	  		}else{
	  			jQuery('.lgbx_email_input').remove();
	  		}
	  });

	  jQuery('#contribute-now').find('a').click(function(){
	  		var form = jQuery('.idc_lightbox').find('.form').find('form').find('div.submit');
	  		var input = '<div class="form-row lgbx_email_input"><label>Email</label><input name="offer_to" id="offer_to" type="text" value="" /></div>';
	  		if( jQuery(this).hasClass('offer') ){
	  			if(jQuery('.lgbx_email_input').length < 1 ){
		  			jQuery(input).insertBefore(form);	  				
	  			} 			
	  		}else{
	  			jQuery('.lgbx_email_input').remove();
	  		}
	  });

	  jQuery('.idc_lightbox').find('.form').find('form').submit(function(){

	  	if( jQuery(this).find('input#offer_to').length > 0 ){
	  		var action = jQuery(this).attr('action') + '&offer_to='+jQuery(this).find('input#offer_to').val();
	  		jQuery(this).attr('action',action);
	  	}

	  });


	  jQuery('.sort-tabs li').find('a').click(function(){
	  	jQuery('.sort-tabs li').removeClass('active');
	  	jQuery(this).parent('li').addClass('active');
	  });

	  /* * * * */



	  /* GESTION DES ACTUS*/

	  jQuery('.add_actu').submit(function(){
	  	var data = unescape(jQuery(this).serialize());
	  	var post = jQuery(this).attr('id');

		jQuery.ajax({
	        url: ajax_params.ajax_url,
	        data:{
	        'action':'do_ajax_save_actu',
	        'fn':'save_actu',
	        'data': data,
	        'postid':post,
	        },
	        dataType: 'html',
	        success: function( data ) {
	        	var obj = JSON.parse(data);
	        	reloadActus(post);	  	
	        	jQuery('#'+post).find('textarea').val('');
	        	jQuery('#'+post).find('input').val('');
	        }
	      });
		return false;
	  });


	/* * * * * * * * * * * * * * * * * */
	/* * * * * *  NAVIGATION * * * * * */
	/* * * * * * * * * * * * * * * * * */

	/* CLICK SUR LE BOUTON DE MENU DU HEADER BLANC */
	$('.menu_control').click(function(){
		$('#header').toggleClass('hide');
		$('body').toggleClass('menu_active');
		$('.search_sidebar').toggleClass('active');
		$('#page').toggleClass('container-left-push');
		return false;
	});

	/* CLICK SUR LE LOGO DU HAUT */
	$('.sidebar_logo').find('img').click(function(){
		$('.principal_menu').removeClass('hide');
		$('.members_menu').addClass('hide');
		return false;
	});

	 /* CLICK SUR LE BOUTON MENU MEMBRES */
	$('.sidebar_top_men_ctn').find('.member').click(function(){
		$('.principal_menu').addClass('hide');
		$('.members_menu').removeClass('hide');
		return false;
	});

	/* CLICK SUR LE BOUTON CART */
	$('.sidebar_top_men_ctn').find('.checkout').click(function(){
		return false;
	});

	/* CLICK SUR LE BOUTON TIRETTE */
	$('.search_sidebar').find('span.tirette').click(function(){
		$('#header').toggleClass('hide');
		$('body').toggleClass('menu_active');
		$('.search_sidebar').toggleClass('active');
		$('#page').toggleClass('container-left-push');
		return false;
	});



	var curent_menu_item = $('.menu').find('.current-menu-item:not(.hiding_menu)');
	var current_menu = $(curent_menu_item).parents('.menu').attr('id');

	/* ON AFFICHE LE BON MENU : MEMBERS OU PRINCIPAL */
	if( current_menu === 'menu-users' ){
		$('#header').toggleClass('hide');
		$('body').toggleClass('menu_active');
		$('.search_sidebar').toggleClass('active');
		$('#page').toggleClass('container-left-push');
		$('.principal_menu').toggleClass('hide');
		$('.members_menu').toggleClass('hide');
	}

	/* SOUS MENUS OUVERTS AU CHARGEMENT */
	if( ($(curent_menu_item).parent().hasClass('sub-menu')) || ( $(curent_menu_item).parent().parent().parent().hasClass('sub-menu') ) ){

		$('#'+current_menu).find('li').each(function(){
			if( ( $(this).attr('id') !== $(curent_menu_item).attr('id') ) &&  ( !$(this).parent().hasClass('sub-menu') )  && ( $(this).find('#'+$(curent_menu_item).attr('id')).length < 1  )  ){
				$(this).toggleClass('hide');
			}
		});

		if( current_menu !== 'menu-users' ){
			$('#header').toggleClass('hide');
			$('body').toggleClass('menu_active');
			$('.search_sidebar').toggleClass('active');
			$('#page').toggleClass('container-left-push');			
		}

		$(curent_menu_item).parent().removeClass('hide');
		$(curent_menu_item).parent().parent().find('a:first').addClass('actif');
		if( jQuery('.menu').find('a.actif').find('.triangle').length < 1 ){
			jQuery('.menu').find('a.actif').prepend('<span class="triangle"></span>');		
		}
	}

	/* SOUS SOUS MENU */
	if( $(curent_menu_item).parent().parent().parent().hasClass('sub-menu') ){
		$(curent_menu_item).parent().parent().parent().toggleClass('hide');
		$(curent_menu_item).parent().parent().parent().parent().toggleClass('hide');
	}

	/* CLICK SUR LES LIENS */
	$('.menu').find('.menu-item').find('a:first-child:not(.back)').click(function(){
		var menuclick = $(this).parents('.menu');
		if( $(this).parent().hasClass('menu-item-has-children') ){
			$(this).toggleClass('actif');
			if( jQuery(menuclick).find('a.actif').find('.triangle').length < 1 ){
				jQuery(menuclick).find('a.actif').prepend('<span class="triangle"></span>');		
			}
			linkHasChildren($(this).parent());
		}
	});

	/* AJOUTE LE LIEN 'BACK' */
	$('.menu .sub-menu').each(function(){
		if( !$(this).parent().parent().hasClass('sub-menu') ){
			$(this).parent().prepend('<a class="back"><img alt="W4" src="/wp-content/themes/fundify-child/img/nav/back.svg" /> Menu</a>');
		}
		if( $(this).hasClass('hide') ){
			$(this).parent().find('.back').toggleClass('hide');
		}			
	});

	/* CLICK SUR LE LIEN BACK */
	$('.menu').find('.menu-item').find('a.back').click(function(){
		backToFirst( jQuery(this).parent() );
		jQuery(this).toggleClass('hide');
		jQuery(this).parent().find('.sub-menu').toggleClass('hide');
	});



	/* * * * * * * * * * * * * * * * * */
	/* * * * * *  NAVIGATION * * * * * */
	/* * * * * * * * * * * * * * * * * */



});


function linkHasChildren(li){			
	li.find('.sub-menu').toggleClass('hide');
	li.find('.back').toggleClass('hide');
	jQuery(li).parent('.menu').find('li').each(function(){
		if( (jQuery(this).attr('id') !== jQuery(li).attr('id')) &&  ( !jQuery(this).parent().hasClass('sub-menu') )  ){
			jQuery(this).toggleClass('hide');
		}
	});
}

function backToFirst(from){

	var menu_click = jQuery(from).parents('.menu');
	if( jQuery(from).parent().find('.actif').find('.triangle').length < 1 ){
		jQuery(from).parent().find('.actif').find('.triangle').remove();		
	}
	jQuery(from).parent().find('.actif').toggleClass('actif');

	jQuery(menu_click).find('li').each(function(){
		if( !jQuery(this).parent().parent().hasClass('sub-menu') && (jQuery(from).attr('id') !== jQuery(this).attr('id')  && (!jQuery(this).parent().hasClass('sub-menu')) ) ){
			jQuery(this).toggleClass('hide');
		}else{
			if( (jQuery(from).attr('id') !== jQuery(this).attr('id') ) ){
				jQuery(this).find('sub-menu').toggleClass('hide');				
			}
		}
	});
}

function initBackMenu(){

	jQuery('.menu').find('.back').each(function(){
		jQuery(this).click(function(){
			var li = jQuery(this).parent();
			jQuery(li).parent('.menu').find('li').each(function(){
				jQuery(this).toggleClass('hide');
			});
			jQuery(this).toggleClass('hide');
		});
	});
}


function open_soc(res){
	jQuery('.sociaux_inputs').find('.'+res).toggleClass('hide');
}

function choose_avatar_types(types){
	if( types === 'ff'){
		choose_avatar_src('femme');
		jQuery('a.ff').addClass('hide');
		jQuery('a.hh').removeClass('hide');
	}else{
		choose_avatar_src('homme');
		jQuery('a.ff').removeClass('hide');
		jQuery('a.hh').addClass('hide');
	}
}

function choose_avatar_src(src){
	jQuery('.choose_default_avatar').each(function(){
		var current_src = jQuery(this).find('img').attr('src');
		if(src === 'femme'){
			var source = current_src.replace("homme", "femme");
		}else{
			var source = current_src.replace("femme", "homme");
		}
		jQuery(this).find('img').attr('src',source);
		jQuery(this).find('.default_avatar').val(source);
	});
}

function verifMail(mailval){
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,20}$/;
   if(!regex.test(mailval)){
      return false;
   }else{
      return true;
   }
}


function set_cookie(nom, valeur) {
	var date = new Date();
	date.setTime(date.getTime()+(30*24*60*60*1000));
	var expire = "; expire="+date.toGMTString();
	document.cookie = nom+"="+valeur+expire+"; path=/";
}


function get_cookie(nom) {
// Ajoute le signe égale virgule au nom
        // pour la recherche
        var nom2 = nom + "=";
        // Array contenant tous les cookies
var arrCookies = document.cookie.split(';');
        // Cherche l'array pour le cookie en question
for(var i=0;i < arrCookies.length;i++) {
var a = arrCookies[i];
// Si c'est un espace, enlever
                while (a.charAt(0)==' ') {
                  a = a.substring(1,a.length);
                }
if (c.andexOf(nom2) == 0) {
                  return a.substring(nom2.length,a.length);
                }
}
        // Aucun cookie trouvé
return null;
}


function currentPassword(){

	if( jQuery( "#is_cpas_ok" ).length > 0 ){
	    jQuery('#edit-profile .aw').keyup(function(){
	        var actualpass = jQuery('.aw').val();
	        if( actualpass !== '' ){
	          // vérifie le mot de passe actuel;
	              jQuery.ajax({
	                url: ajax_params.ajax_url,
	                data:{
	                'action':'do_ajax_pass',
	                'fn':'check_current_pass',
	                'pass': actualpass,
	                },
	                dataType: 'html',
	                success: function( data ) {
	                    if( data === '1'){
	                      jQuery('#is_cpas_ok').val('1');
	                    }else{
	                      jQuery('#is_cpas_ok').val('0');
	                    }
	                }
	              });
	        }else{
	          return false;
	        }
	    });

	  jQuery('#edit-profile').submit(function(){
	    if( jQuery('.pw').val() !== '' ){
	      if( jQuery('.aw').val() !== '' ){
	        if( jQuery('#is_cpas_ok').val() === '1' ){
	          return true;
	        }else{
	          alert("Le mot de passe actuel est incorrecte")
	          return false;
	        }
	        return false;
	      }else{
	        alert("Veuillez entrer votre mot de passe actuel !");
	        return false;
	      }
	      return false;
	    }
	  });		
	}
}

function search(){
	if( jQuery('body').hasClass('search') ){
		// RESTE SUR LA PAGE DE RESULTATS
		ajax_search();
		//return false;
	}else{
		// VA AU RESULTAS DE RECHERCHE
		//return true;
		jQuery('#searchform').submit();
	}
}

function ajax_search(){
	jQuery.ajax({
		url: ajax_params.ajax_url,
		data:{
		'action':'do_ajax',
		'fn':'get_search_results',
		'search_form': jQuery('#searchform').serialize(),
		},
		dataType: 'html',
		success: function( data ) {
			jQuery("#projects section").html(data);
		}
	});
}

function project_cat(termid){
	jQuery('#searchform').find('ul.terms li').removeClass('active');
	jQuery('#searchform').find('#pc').val(termid);
	search();
	jQuery('#searchform').find('ul.terms li#'+termid).addClass('active');
}


function upload_avatar(){

	var data = new FormData();
	data.append( 'action', 'do_ajax_upload_avatar');
	data.append( 'fn', 'up_av');
	data.append( 'avatar', jQuery('#idc_avatar_file')[0].files[0] );

	jQuery.ajax({
		url: ajax_params.ajax_url,
		type: 'POST',
		data: data,
		contentType: false,
		processData: false,
		cache:false,
		dataType: 'html',
		success: function(data) {
			jQuery('#idc_avatar').val(data);
			jQuery('.avatar_preview').html('<img src="'+data+'" />');				
		}
	});
}


function choose_curency(cur){
	set_cookie('currency',cur);
	location.reload();
}


function autoResizeDTextarea(){
	jQuery('textarea.resizable').keyup(function(){
		jQuery(this).height(0);
		while(jQuery(this).outerHeight() < this.scrollHeight + parseFloat(jQuery(this).css("borderTopWidth")) + parseFloat(jQuery(this).css("borderBottomWidth"))) {
        	jQuery(this).height(jQuery(this).height()+1);
    	};
	});

	jQuery('textarea.resizable').each(function(){
		jQuery(this).height(0);
		while(jQuery(this).outerHeight() < this.scrollHeight + parseFloat(jQuery(this).css("borderTopWidth")) + parseFloat(jQuery(this).css("borderBottomWidth"))) {
	        	jQuery(this).height(jQuery(this).height()+1);
	    };
	});
}

function addActu(project){
	jQuery('.myprojects_content ul li#'+project).find(".actuform").removeClass('hide');
	jQuery('.myprojects_content ul li#'+project).find(".actuform").find('textarea').focus();
}

function deleteActu(postid,metaid){
		jQuery.ajax({
	        url: ajax_params.ajax_url,
	        data:{
	        'action':'do_ajax_delete_actu',
	        'fn':'delete_actu',
	        'metaid': metaid,
	        },
	        dataType: 'html',
	        success: function(data) {
	        	if(data === '1'){
	        		jQuery('#'+postid).find('#'+metaid).addClass('hide');	        		
	        	}
	        }
	      });
		return false;
}

function reloadActus(postid){
	jQuery.ajax({
	        url: ajax_params.ajax_url,
	        data:{
	        'action':'do_ajax_load_actu',
	        'fn':'load_actu',
	        'post': postid,
	        },
	        dataType: 'html',
	        success: function(data) {
	        	jQuery('#'+postid+' .project_actus ul').html(data);
	        }
	      });
	return false;
}

function resendregistermail(){
		jQuery.ajax({
	        url: ajax_params.ajax_url,
	        data:{
	        'action':'do_ajax_resend_regis_mail',
	        'fn':'resend_regis_mail',
	        },
	        dataType: 'html',
	        success: function(data) {
	        	jQuery('.resendmail').parent().html(data);
	        }
	      });
	return false;
}