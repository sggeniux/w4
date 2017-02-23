<form method="post" action="options.php">
<?php settings_fields('follow_settings') ?>
<?php do_settings_sections('follow_settings') ?>
<?php submit_button(); ?>
</form>