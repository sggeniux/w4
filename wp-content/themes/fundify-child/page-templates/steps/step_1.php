<?php


	$project = $submit->post ;
	$end_d;
	$end_m;
	$end_y;
	$end_h;
	$the_end_date;

	
	if( !empty($project->ign_fund_end) ){
		$ign_funded_end = explode('/',$project->ign_fund_end);
		if( empty($project->end_h)){
			$project->end_h = '00';
		}
		$the_end = new DateTime($ign_funded_end[1].'-'.$ign_funded_end[0].'-'.$ign_funded_end[2].' '.$project->end_h.':00 ');
		$end_d = $the_end->format('d');
		$end_m = $the_end->format('m');
		$end_y = $the_end->format('Y');
		$end_h = $the_end->format('H');	
		$the_end_date = $the_end->format('m/d/Y');
	}

?>
				<div class="form-row">
					<!--<label><?php // _e('Project title') ?></label>-->
					<input class="required maxchars" type="text" name="post_title" maxlength="45" data-maxmess="Attention : votre titre est trop long."  value="<?php echo $project->post_title ?>" placeholder="<?php _e('Project title') ?>">
					<span class="empty_input"><i class="fa fa-times"></i></span>
				</div>

					<div class="form-row">
						<label><?php _e('Catégory') ?> : " <span id="section_label"><?php echo get_category( $project->parent_section )->name ?></span> " <a href="javascript:jQuery('.choose_category_first').removeClass('hide');"><i class="fa fa-pencil-square-o"></i></a></label>
						<div>
							<select data-section="<?php echo $project->section; ?>" id="section" name="section">
								<?php echo $submit->getSections($project->section); ?>
							</select>
							<input type="hidden" id="parent_section" name="parent_section" value="<?php echo $project->parent_section ?>">
						</div>
					</div>

					<div class="form-row">
						<!-- <label><?php //_e('Project country') ?></label> -->
						<select name="country">
							<option value="null"><?php _e('Project country') ?></option>
							<?php echo $submit->select_util('countrys',$project->country); ?>
						</select>
					</div>

					<div class="group_fields fundraising_curency">
						<div class="form-row">
							<!-- <label><?php // _e('Fix a fundraising limit') ?></label>-->
							<input class="required" type="text" name="ign_fund_goal" value="<?php echo $project->ign_fund_goal ?>" placeholder="<?php _e('Fix a fundraising limit for your project') ?>">
							<span class="empty_input"><i class="fa fa-times"></i></span>
						</div>

						<div class="form-row">
							<select class="required" name="currency_gbl">
								<option value="null"> <?php _e('$ / €') ?> </option>
								<?php if(trim($project->currency_gbl) === 'EUR'): ?>
									<option selected="selected" value="EUR"> € </option>
								<?php else: ?>
									<option value="EUR"> € </option>
								<?php endif ?>
								<?php if(trim($project->currency_gbl) === 'USD'): ?>
									<option selected="selected" value="USD"> $ </option>
								<?php else: ?>
									<option value="USD"> $ </option>
								<?php endif; ?>
							</select>
						</div>
					</div>

					<div class="form-row date_chooser">
							<label><?php _e('Date / Heure limite') ?></label>
							<div>
								<div class="select_date">
									<input type="text" id="datepicker" name="ign_fund_end" autocomplete="off" value="<?php echo $the_end_date ?>">
								</div>

								<div class="select_heure">
									<select class="toggle" name="end_h">
										<option selected="true" value="null" disabled>Heure</option>
										<?php for($h=0;$h < 24;$h++): ?>
										<?php if( $h < 10 ){$hh = '0'.$h; }else{$hh = $h;} ?>
											<?php if(trim($hh) === trim($end_h)): ?>
												<option selected="selected" value="<?php echo $hh ?>:00"><?php echo $hh ?>:00</option>
											<?php else: ?>
												<option value="<?php echo $hh ?>:00"><?php echo $hh ?>:00</option>
											<?php endif; ?>
										<?php endfor; ?>
									</select>		
								</div>
							</div>

						<div>
							<label><?php _e('Nombre de jours') ?></label>
							<div id="slider">
								<div id="custom-handle" class="ui-slider-handle"></div>
							</div>
						</div>

						<input type="hidden" id="days_number" />

					</div>
					<script>
					  jQuery( function() {

					    	jQuery( "#datepicker" ).datepicker({
					    		dateFormat : 'mm/dd/yy',
					    		minDate: "+1D",
					    		maxDate: "+12M +1D",
					    	});

					    	jQuery( "#datepicker" ).change(function(){
					    		getNumberByDate(jQuery(this).val());
					    	});

					        var handle = jQuery( "#custom-handle" );
						    jQuery( "#slider" ).slider({
								value:0,
								min: 1,
								max: 366,
								step: 1,
							    create: function() {
							    	if( jQuery('#datepicker').val() !== '' ){
							    		getNumberByDate('<?php echo $the_end_date ?>');
							    	}else{
								        jQuery( "#days_number" ).val( jQuery( this ).slider( "value" ) );
								        handle.text( jQuery( this ).slider( "value" ) );
								        getDateByNumber(jQuery( this ).slider( "value" ));
							    	}
							      },
							    slide: function( event, ui ) {
								        jQuery( "#days_number" ).val( ui.value );
								        handle.text( ui.value );
								        getDateByNumber(ui.value);							    		
							      }
						    });
					  } );

					  function getDateByNumber(dayNb){
					  		var today = new Date();
					  		today.setDate(today.getDate() + dayNb);
					  		var inputdate =   (today.getMonth() + 1).toString().replace(/^(\d)$/,'0$1') + '/' +  (today.getDate()).toString().replace(/^(\d)$/,'0$1') + '/' +  today.getFullYear();
					  		jQuery( "#datepicker" ).val(inputdate);
					  }

					function getNumberByDate(date){
					  	date = date.split("/");
						var date1 = new Date();
						var date2 = new Date('"'+date[0]+','+date[1]+','+date[2]+'"');
						var diffDays = dayDiff(date1, date2);
						jQuery( "#slider" ).slider( "value", diffDays );
						jQuery( "#custom-handle" ).text( diffDays );
						jQuery( "#days_number" ).val(diffDays);
					}


					function dayDiff(d1, d2){
						d1 = d1.getTime();
						d2 = d2.getTime();
						var diff = Math.round(d2 - d1).toFixed(0);
						var minutes = 1000 * 60;
						var hours = minutes * 60;
						var days = hours * 24;
						var years = days * 365;
						var nbd = Math.round(diff / days) +1 ;
						return nbd;
					}

					  </script>



					<div class="form-row">

						<label>Ajoutez vos réseaux sociaux</label>

							<div>

								<div class="reseau">
									<label>
									<i class="fa fa-twitter" aria-hidden="true"></i>
									<?php if(!empty($project->reseau_twitter)){ $tcheck = ' checked="checked" '; $tclass = ''; }else{$tcheck = ''; $tclass = 'hide';} ?>
									<input class="chek tw" type="checkbox" onchange="changeInputValue('tw')" value="twitter" <?php echo $tcheck ?> />
									<input type="text" class="input_reseaux <?php echo $tclass; ?>" id="tw" name="reseau_twitter" value="<?php echo $project->reseau_twitter ?>" placeholder="Twitter">
									</label>
								</div>
								<div class="reseau">
									<label>
									<i class="fa fa-facebook" aria-hidden="true"></i>
									<?php if(!empty($project->reseau_facebook)){ $fcheck = ' checked="checked" '; $fclass = ''; }else{$fcheck = '';$fclass = 'hide';} ?>
									<input class="chek fb"  type="checkbox" onchange="changeInputValue('fb')" value="facebook" <?php echo $fcheck ?> />
									<input type="text" class="input_reseaux <?php echo $fclass; ?>" id="fb" name="reseau_facebook" value="<?php echo $project->reseau_facebook ?>" placeholder="Facebook">
									</label>
								</div>
								<div class="reseau">
									<label>
									<i class="fa fa-instagram" aria-hidden="true"></i>
									<?php if(!empty($project->reseau_instagram)){ $icheck = ' checked="checked" '; $iclass = ''; }else{$icheck = '';$iclass = 'hide';} ?>
									<input class="chek in" type="checkbox" onchange="changeInputValue('in')" value="instagram" <?php echo $icheck ?> />
									<input type="text" class="input_reseaux <?php echo $iclass; ?>" id="in" name="reseau_instagram" value="<?php echo $project->reseau_instagram ?>" placeholder="Instagram">
									</label>
								</div>
								<div class="reseau">
									<label>
									<i class="fa fa-linkedin" aria-hidden="true"></i>
									<?php if(!empty($project->reseau_linkedin)){ $lcheck = ' checked="checked" '; $lclass = ''; }else{$lcheck = '';$lclass = 'hide';} ?>
									<input class="chek lk" type="checkbox" onchange="changeInputValue('lk')" value="linkedin" <?php echo $lcheck ?> />
									<input type="text" class="input_reseaux <?php echo $lclass; ?>" id="lk" name="reseau_linkedin" value="<?php echo $project->reseau_linkedin ?>" placeholder="Linkedin">
									</label>
								</div>
								<div class="reseau">
									<label>
									<i class="fa fa-google-plus" aria-hidden="true"></i>
									<?php if(!empty($project->reseau_google_plus)){ $gcheck = ' checked="checked" '; $gclass = ''; }else{$gcheck = '';$gclass = 'hide';} ?>
									<input class="chek gg" type="checkbox" onchange="changeInputValue('gg')" value="google_plus"  <?php echo $gcheck ?> />
									<input type="text" class="input_reseaux <?php echo $gclass; ?>" id="gg" name="reseau_google_plus" value="<?php echo $project->reseau_google_plus ?>" placeholder="Google plus">
									</label>
								</div>

							</div>

						<script type="text/javascript">
							function changeInputValue(input){
								if( jQuery('.reseau .'+input).is(':checked') ){

								}else{
									jQuery('.reseau #'+input).val('');									
								}
							}
						</script>
						
					</div>


					<div class="form-row">
						<input type="text" name="website" placeholder="<?php _e('Ajoutez votre site') ?>" value="<?php echo $project->website ?>">
						<span class="empty_input"><i class="fa fa-times"></i></span>
					</div>

					<input type="hidden" id="postid" name="postid" value="<?php echo $submit->postid ?>">

					<input type="hidden" name="next" value="<?php echo $submit->next ?>">