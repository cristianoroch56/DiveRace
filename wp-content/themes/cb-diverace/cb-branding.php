<?php

if( !\defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'login_headerurl', 'cb_login_headerurl' );
/**
 * Makes the login screen's logo link to your homepage, instead of to WordPress.org.
 *
 * @since 1.0.0
 */
function cb_login_headerurl() {

	return home_url();

}

add_filter( 'login_headertext', 'cb_login_headertext' );
/**
 * Makes the login screen's logo title attribute your site title, instead of 'WordPress'.
 *
 * @since 1.0.0
 */
function cb_login_headertext() {

	return get_bloginfo( 'name' );

}

add_action( 'login_enqueue_scripts', 'cb_replace_login_logo' );
/**
 * Replaces the login screen's WordPress logo with the 'login-logo.png' in your child theme images folder.
 *
 * Disabled by default. Make sure you have a login logo before using this function!
 *
 * Updated 2.0.1: Assumes SVG logo by default
 * Updated 2.0.20: WP 3.8 logo
 *
 * @since 2.0.0
 */
function cb_replace_login_logo() {

	?><style type="text/css">
		body.login {
			font-family: 'system-ui', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
			font-size: 14px;
			line-height: 1.5;
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/login-bg.jpg );
			background-size: cover;
			overflow: hidden;
		}
		body.login #login {
			background: #f6f9fc;
			width: 1080px;
			padding: 0;
			margin: 25vh auto;
			position: relative;
			overflow: hidden;
			box-shadow: 0 5px 20px rgba(0,0,0,.1);
		}
		
		body.login #login:after {
			content: '';
			display: inline-block;
			background-image: url( <?php echo get_stylesheet_directory_uri(); ?>/images/login-bg.png );
			background-size: cover;
			width: 720px;
			height: 420px;
			position: absolute;
			top: 0;
			right: 0;
		}
		
		body.login h1 {
			padding: 20px;
			max-width: 320px;
		}
		
		body.login h1 a {
			background-image: url( <?php echo get_stylesheet_directory_uri(); ?>/images/login-logo.png );
			background-size: 180px 49px;
			width: 180px;
			height: 49px;
			margin: 0;
		}
		
		body.login form {
			background: #f6f9fc;
			border: none;
			box-shadow: none;
			margin: 0;
			padding: 40px 40px 20px 40px;
			max-width: 260px;
		}
		
		body.login form label {
			margin-bottom: 5px;
			font-size: 14px;
			color: #777;
		}
		
		body.login form input[type=password],
		body.login form input[type=text] {
			background: #fcfcfc;
			border: 0;
			border-radius: 0;
			border-bottom: 1px solid #d7d5d6;
		}
		
		body.login form input:focus {
			border-color: #d7d5d6;
			box-shadow: 0 0 0 1px #d7d5d6;
		}
		
		body.login form #wp-submit {
			position: relative;
			overflow: hidden;
			padding: 8px 3rem;
			border: none;
			border-radius: 30px;
			background: rgb(83,144,241);
			background: linear-gradient(125deg, rgba(83,144,241,1) 0%, rgba(219,96,204,1) 100%);
			box-shadow: 0 5px 20px rgba(0,0,0,.1);
			color: #fff;
			letter-spacing: 1px;
			font-weight: 600;
			font-size: 14px;
			cursor: pointer;
			transition: all .5s ease;
			margin: 0 auto;
		}
		
		body.login form #wp-submit:hover {
			background-color: #2e3c50
		}
		
		body.login #nav {
			margin: 0;
			padding: 20px 20px 16px 20px;
			max-width: 320px;
		}
		
		body.login #nav a {
			color: #4d81cc;
		}
		
		body.login .forgetmenot,
		body.login .privacy-policy-page-link,
		body.login #backtoblog {
			display: none;
		}

		body.login .login-footer {
			position: absolute;
			bottom: 20px;
			right: 20px;
			color: #fff;
		}
		
		.login #login_error,
		.login .message,
		.login .success {
			max-width: 320px;
		}
		
	</style>
	<?php

}

add_action( 'login_footer', 'cb_add_login_footer' );
function cb_add_login_footer() {
	
	?><div class="login-footer">
		<p style="text-align: right;">&copy; <?php echo date('Y'); ?> ChillyBin Web Design, All Rights Reserved.</p>
	</div>
	<?php
}

add_filter( 'wp_mail_from_name', 'cb_mail_from_name' );
/**
 * Makes WordPress-generated emails appear 'from' your WordPress site name, instead of from 'WordPress'.
 *
 * @since 1.0.0
 */
function cb_mail_from_name() {

	return get_option( 'blogname' );

}

add_filter( 'wp_mail_from', 'cb_wp_mail_from' );
/**
 * Makes WordPress-generated emails appear 'from' your WordPress admin email address.
 *
 * Disabled by default, in case you don't want to reveal your admin email.
 *
 * @since 1.0.0
 */
function cb_wp_mail_from() {

	return get_option( 'admin_email' );

}

add_filter( 'retrieve_password_message', 'cb_cleanup_retrieve_password_message' );
/**
 * Remove the brackets from the retreive PW link, since they get hidden on HTML.
 *
 * @since 2.2.24
 */
function cb_cleanup_retrieve_password_message($message) {

	return \preg_replace( '/<(.+?)>/', '$1', $message );

}

add_action( 'wp_before_admin_bar_render', 'cb_remove_wp_icon_from_admin_bar' );
/**
 * Removes the WP icon from the admin bar.
 *
 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
 *
 * @since 1.0.0
 */
function cb_remove_wp_icon_from_admin_bar() {

	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');

}

add_filter( 'admin_footer_text', 'cb_admin_footer_text' );
/**
 * Modify the admin footer text.
 *
 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
 *
 * @since 1.0.0
 */
function cb_admin_footer_text() {

	$text = __( 'Built by <a href="%s" target="_blank" rel="noopener">ChillyBin Web Design</a>', CHILD_THEME_TEXT_DOMAIN );

	return \sprintf(
		$text,
		'https://chillybin.com.sg'
	);

}

