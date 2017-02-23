<form id="add_member_team" class="add_member_team" method="POST" action="#" enctype="multipart/form-data">

	<div class="team_item">

			<div class="form-row">
				<input type="email" name="member_email" value="" placeholder="<?php _e("Email...") ?>">
			</div>

			<div class="form-row">
				<input type="text" name="member_poste" value="" placeholder="<?php _e("Poste...(exemple: collaborateur)") ?>">
			</div>


			<div class="form-row">
				<label><?php _e("Authorisations") ?></label>
				

				<div class="form-row">
					<p><label><input type="checkbox" name="member_acl" value="" /> <?php _e("Modifier le projet") ?></label></p>
					<ul>
						<li><label><input type="checkbox" name="member_acl" value=""> <?php _e("Le collaborateur peut modifier les éléments de base du projet, votre histoire, vos récompenses") ?></label></li>
						<li><label><input type="checkbox" name="member_acl" value=""> <?php _e("Le collaborateur peut gérer la FAQ du projet") ?></label></li>
					</ul>
				</div>

				<div class="form-row">
					<p><label><input type="checkbox" name="member_acl" value=""/> <?php _e("Gérer ma communauté") ?></label></p>
				</div>

				<div class="form-row">
					<p><label><input type="checkbox" name="member_acl" value=""/> <?php _e("Vos promesses") ?></label></p>
				</div>
			</div>

			<div class="form-row">
				<button type="submit" class="button btn btn-danger">Envoyer l'invitation</button>
			</div>
	</div>
</form>