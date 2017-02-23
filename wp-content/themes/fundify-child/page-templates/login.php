<?php
/**
 * Template Name: Login page
 *
 * @package Fundify
 * @since Fundify 1.0
 */


$formargs = array(
	'echo'           => true,
	'remember'       => true,
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . '/dashboard/',
	'form_id'        => 'loginform',
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'label_username' => __( 'Username / Email' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'value_username' => '',
	'value_remember' => false
);

get_header();
?>

	<div id="content">
		<div class="container">





		<h1 class="registration_page"><?php _e('Connexion') ?></h1>
			<div class="registration_content">

				<div class="social_connect">
					<?php do_action('oa_social_login'); ?>
				</div>

				<?php wp_login_form($formargs) ?>


			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'single' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>
			<?php endwhile; ?>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>